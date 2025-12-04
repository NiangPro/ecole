<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Models\AdSenseSetting;
use App\Models\Statistic;
use App\Models\User;
use App\Models\SiteSetting;
use App\Models\ContactMessage;
use App\Services\BingUrlSubmissionService;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Rate limiting pour prévenir les attaques brute force
        $key = Str::lower($request->email) . '|' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->with('error', "Trop de tentatives. Veuillez réessayer dans {$seconds} secondes.");
        }

        // Tentative d'authentification
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // Vérifier que le compte est actif
            if (!$user->is_active) {
                Auth::logout();
                RateLimiter::hit($key, 300);
                return back()->with('error', 'Votre compte a été désactivé.');
            }

            // Vérifier que l'utilisateur est un administrateur
            if (!$user->isAdmin()) {
                Auth::logout();
                RateLimiter::hit($key, 300);
                return back()->with('error', 'Accès refusé. Seuls les administrateurs peuvent accéder au panel admin.');
            }

            // Réinitialiser le rate limiter en cas de succès
            RateLimiter::clear($key);

            // Logger la connexion réussie
            \Log::info('Admin login successful', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
            ]);

            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        // Incrémenter le rate limiter en cas d'échec
        RateLimiter::hit($key, 300);

        // Logger la tentative échouée
        \Log::warning('Admin login failed', [
            'email' => $request->email,
            'ip' => $request->ip(),
        ]);

        return back()->with('error', 'Email ou mot de passe incorrect');
    }
    
    public function dashboard()
    {
        // Le middleware AdminAuth s'occupe de la vérification
        
        // Publicités qui vont bientôt expirer (dans les 7 prochains jours) - Cache 5 minutes
        $expiringAds = Cache::remember('expiring_ads', 300, function () {
            return \App\Models\Ad::where('status', 'active')
                ->whereNotNull('end_date')
                ->where('end_date', '>=', now())
                ->where('end_date', '<=', now()->addDays(7))
                ->orderBy('end_date', 'asc')
                ->get();
        });
        
        // Statistiques détaillées - Cache 5 minutes
        $stats = Cache::remember('dashboard_stats_detailed', 300, function () {
            $today = Carbon::today();
            $yesterday = Carbon::yesterday();
            $thisMonth = Carbon::now()->startOfMonth();
            $lastMonth = Carbon::now()->subMonth()->startOfMonth();
            $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();
            
            return [
                'today' => [
                    'visits' => Statistic::whereDate('visit_date', $today)->count(),
                    'unique' => Statistic::whereDate('visit_date', $today)->distinct('ip_address')->count('ip_address'),
                ],
                'yesterday' => [
                    'visits' => Statistic::whereDate('visit_date', $yesterday)->count(),
                    'unique' => Statistic::whereDate('visit_date', $yesterday)->distinct('ip_address')->count('ip_address'),
                ],
                'thisMonth' => [
                    'visits' => Statistic::where('visit_date', '>=', $thisMonth)->count(),
                    'unique' => Statistic::where('visit_date', '>=', $thisMonth)->distinct('ip_address')->count('ip_address'),
                ],
                'lastMonth' => [
                    'visits' => Statistic::whereBetween('visit_date', [$lastMonth, $lastMonthEnd])->count(),
                    'unique' => Statistic::whereBetween('visit_date', [$lastMonth, $lastMonthEnd])->distinct('ip_address')->count('ip_address'),
                ],
                'totalArticles' => \App\Models\JobArticle::count(),
                'publishedArticles' => \App\Models\JobArticle::where('status', 'published')->count(),
                'draftArticles' => \App\Models\JobArticle::where('status', 'draft')->count(),
                'totalUsers' => User::count(),
                'activeUsers' => User::where('is_active', true)->count(),
                'totalNewsletter' => \App\Models\Newsletter::count(),
                'activeNewsletter' => \App\Models\Newsletter::where('is_active', true)->count(),
                'totalCategories' => \App\Models\Category::where('is_active', true)->count(),
                'totalAds' => \App\Models\Ad::count(),
                'activeAds' => \App\Models\Ad::where('status', 'active')->count(),
                'unreadMessages' => ContactMessage::where('is_read', false)->count(),
                'recentArticles' => \App\Models\JobArticle::orderBy('created_at', 'desc')->take(5)->get(),
                'topArticles' => \App\Models\JobArticle::where('status', 'published')->orderBy('views', 'desc')->take(5)->get(),
            ];
        });
        
        // Calculer les pourcentages de croissance
        $stats['visitsGrowth'] = $stats['yesterday']['visits'] > 0 
            ? round((($stats['today']['visits'] - $stats['yesterday']['visits']) / $stats['yesterday']['visits']) * 100, 1)
            : 0;
        $stats['monthGrowth'] = $stats['lastMonth']['visits'] > 0
            ? round((($stats['thisMonth']['visits'] - $stats['lastMonth']['visits']) / $stats['lastMonth']['visits']) * 100, 1)
            : 0;
        
        return view('admin.dashboard', compact('expiringAds', 'stats'));
    }
    
    public function profile()
    {
        // Le middleware AdminAuth s'occupe de la vérification
        $user = Auth::user();
        $admin = (object)[
            'name' => $user->name,
            'email' => $user->email,
        ];
        
        return view('admin.profile', compact('admin'));
    }
    
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'current_password' => 'required_with:new_password',
            'new_password' => 'nullable|min:6|confirmed',
        ]);
        
        // Le middleware AdminAuth s'occupe de la vérification
        $user = Auth::user();
        
        // Vérifier le mot de passe actuel
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->with('error', 'Le mot de passe actuel est incorrect');
            }
            
            $user->password = Hash::make($request->new_password);
        }
        
        // Mettre à jour les informations
        // Email mis à jour via Auth::user()
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        
        return redirect()->route('admin.profile')->with('success', 'Profil mis à jour avec succès!');
    }
    
    public function adsense()
    {
        $settings = AdSenseSetting::first();
        
        return view('admin.adsense', compact('settings'));
    }
    
    public function updateAdsense(Request $request)
    {
        $request->validate([
            'publisher_id' => 'nullable|string|max:255',
            'adsense_code' => 'nullable|string',
        ]);
        
        $settings = AdSenseSetting::first();
        
        if (!$settings) {
            $settings = new AdSenseSetting();
        }
        
        $settings->publisher_id = $request->publisher_id;
        $settings->adsense_code = $request->adsense_code;
        $settings->header_banner = $request->has('header_banner');
        $settings->sidebar_banner = $request->has('sidebar_banner');
        $settings->footer_banner = $request->has('footer_banner');
        $settings->save();
        
        // Invalider le cache
        \App\Models\AdSenseSetting::clearCache();
        
        return redirect()->route('admin.adsense')->with('success', 'Configuration AdSense mise à jour avec succès!');
    }
    
    public function statistics(Request $request)
    {$filter = $request->get('filter', 'month');
        $year = $request->get('year', Carbon::now()->year);
        $month = $request->get('month', Carbon::now()->month);
        
        // Obtenir les années disponibles - Cache 1 heure
        $availableYears = Cache::remember('statistics_available_years', 3600, function () {
            return Statistic::getAvailableYears();
        });
        
        // Calculer les statistiques selon le filtre - Cache 5 minutes
        $cacheKey = "statistics_{$filter}_{$year}_{$month}";
        $stats = Cache::remember($cacheKey, 300, function () use ($filter, $year, $month) {
            if ($filter == 'day') {
                $totalVisits = Statistic::getByDay();
                $uniqueVisitors = Statistic::whereDate('visit_date', Carbon::today())
                                          ->distinct('ip_address')
                                          ->count('ip_address');
                $totalPages = Statistic::whereDate('visit_date', Carbon::today())
                                       ->distinct('page_url')
                                       ->count('page_url');
                $avgPerDay = $totalVisits;
            } elseif ($filter == 'month') {
                $totalVisits = Statistic::getByMonth($year, $month);
                $uniqueVisitors = Statistic::whereMonth('visit_date', $month)
                                          ->whereYear('visit_date', $year)
                                          ->distinct('ip_address')
                                          ->count('ip_address');
                $totalPages = Statistic::whereMonth('visit_date', $month)
                                       ->whereYear('visit_date', $year)
                                       ->distinct('page_url')
                                       ->count('page_url');
                $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;
                $avgPerDay = $daysInMonth > 0 ? round($totalVisits / $daysInMonth) : 0;
            } else {
                $totalVisits = Statistic::getByYear($year);
                $uniqueVisitors = Statistic::whereYear('visit_date', $year)
                                          ->distinct('ip_address')
                                          ->count('ip_address');
                $totalPages = Statistic::whereYear('visit_date', $year)
                                       ->distinct('page_url')
                                       ->count('page_url');
                $avgPerDay = round($totalVisits / 365);
            }
            
            return [
                'totalVisits' => $totalVisits,
                'uniqueVisitors' => $uniqueVisitors,
                'totalPages' => $totalPages,
                'avgPerDay' => $avgPerDay,
            ];
        });
        
        $totalVisits = $stats['totalVisits'];
        $uniqueVisitors = $stats['uniqueVisitors'];
        $totalPages = $stats['totalPages'];
        $avgPerDay = $stats['avgPerDay'];
        
        // Pages les plus visitées - Cache 5 minutes
        $topPagesCacheKey = "top_pages_{$filter}";
        $topPages = Cache::remember($topPagesCacheKey, 300, function () use ($filter) {
            return Statistic::getTopPages(10, $filter);
        });
        
        // Données pour le graphique - Cache 5 minutes
        $dailyStatsCacheKey = $filter == 'year' ? "daily_stats_year_{$year}" : "daily_stats_30_days";
        $dailyStats = Cache::remember($dailyStatsCacheKey, 300, function () use ($filter, $year) {
            if ($filter == 'year') {
                return Statistic::getMonthlyStatsForYear($year);
            } else {
                return Statistic::getDailyStats(30);
            }
        });
        
        // Statistiques par pays, navigateur, source - Cache 5 minutes
        $countriesStatsCacheKey = "countries_stats_{$filter}_{$year}_{$month}";
        $countriesStats = Cache::remember($countriesStatsCacheKey, 300, function () use ($filter, $year, $month) {
            return Statistic::getByCountry($filter, $year, $month);
        });
        
        $browsersStatsCacheKey = "browsers_stats_{$filter}_{$year}_{$month}";
        $browsersStats = Cache::remember($browsersStatsCacheKey, 300, function () use ($filter, $year, $month) {
            return Statistic::getByBrowser($filter, $year, $month);
        });
        
        $sourcesStatsCacheKey = "sources_stats_{$filter}_{$year}_{$month}";
        $sourcesStats = Cache::remember($sourcesStatsCacheKey, 300, function () use ($filter, $year, $month) {
            return Statistic::getBySource($filter, $year, $month);
        });
        
        // Statistiques hebdomadaires du mois actuel - Cache 5 minutes
        $weeklyStats = Cache::remember('weekly_stats_current_month', 300, function () {
            return Statistic::getWeeklyStatsForCurrentMonth();
        });
        
        return view('admin.statistics', compact(
            'filter',
            'year',
            'month',
            'availableYears',
            'totalVisits',
            'uniqueVisitors',
            'totalPages',
            'avgPerDay',
            'topPages',
            'dailyStats',
            'countriesStats',
            'browsersStats',
            'sourcesStats',
            'weeklyStats'
        ));
    }
    
    public function truncateStatistics()
    {Statistic::truncate();
        
        // Vider tous les caches liés aux statistiques
        Cache::flush();
        
        return redirect()->route('admin.statistics')->with('success', 'Table statistics vidée avec succès!');
    }
    
    public function users(Request $request)
    {$search = $request->get('search');
        $role = $request->get('role', '');
        $status = $request->get('status', '');
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        
        $query = User::query();
        
        // Recherche
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        // Filtre par rôle
        if ($role) {
            $query->where('role', $role);
        }
        
        // Filtre par statut
        if ($status !== '') {
            $query->where('is_active', $status === 'active' ? 1 : 0);
        }
        
        // Tri
        $query->orderBy($sortBy, $sortOrder);
        
        $users = $query->paginate(15)->withQueryString();
        
        // Statistiques pour le dashboard
        $stats = [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'inactive' => User::where('is_active', false)->count(),
            'admins' => User::where('role', 'admin')->count(),
            'users' => User::where('role', 'user')->count(),
        ];
        
        return view('admin.users.index', compact('users', 'search', 'role', 'status', 'sortBy', 'sortOrder', 'stats'));
    }
    
    public function createUser()
    {return view('admin.users.create');
    }
    
    public function storeUser(Request $request)
    {$request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:user,admin',
            'phone' => 'nullable|string|max:20',
        ]);
        
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'is_active' => $request->has('is_active'),
        ]);
        
        return redirect()->route('admin.users')->with('success', 'Utilisateur créé avec succès!');
    }
    
    public function editUser($id)
    {$user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }
    
    public function updateUser(Request $request, $id)
    {$user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'nullable|min:6',
            'role' => 'required|in:user,admin',
            'phone' => 'nullable|string|max:20',
        ]);
        
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->role = $request->role;
        $user->phone = $request->phone;
        $user->is_active = $request->has('is_active');
        $user->save();
        
        return redirect()->route('admin.users')->with('success', 'Utilisateur modifié avec succès!');
    }
    
    public function deleteUser($id)
    {$user = User::findOrFail($id);
        $user->delete();
        
        return redirect()->route('admin.users')->with('success', 'Utilisateur supprimé avec succès!');
    }
    
    public function messages(Request $request)
    {$filter = $request->get('filter', 'all');
        
        $messages = ContactMessage::when($filter == 'unread', function($query) {
                        return $query->unread();
                    })
                    ->when($filter == 'read', function($query) {
                        return $query->read();
                    })
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
        
        return view('admin.messages', compact('messages', 'filter'));
    }
    
    public function markAsRead($id)
    {$message = ContactMessage::findOrFail($id);
        $message->is_read = true;
        $message->save();
        
        return back()->with('success', 'Message marqué comme lu');
    }
    
    public function deleteMessage($id)
    {$message = ContactMessage::findOrFail($id);
        $message->delete();
        
        return back()->with('success', 'Message supprimé avec succès');
    }
    
    public function settings()
    {$settings = SiteSetting::first();
        
        return view('admin.settings', compact('settings'));
    }
    
    public function updateSettings(Request $request)
    {        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'contact_address' => 'nullable|string',
            'facebook_url' => 'nullable|url',
            'facebook_app_id' => 'nullable|string|max:255',
            'tiktok_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'github_url' => 'nullable|url',
            'google_analytics_id' => 'nullable|string|regex:/^G-[A-Z0-9]+$/',
            'bing_api_key' => 'nullable|string|max:255',
            'mail_mailer' => 'nullable|string|in:smtp,sendmail,mailgun,ses',
            'mail_host' => 'nullable|string|max:255',
            'mail_port' => 'nullable|string|max:10',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|string|in:tls,ssl',
            'mail_from_address' => 'nullable|email|max:255',
            'mail_from_name' => 'nullable|string|max:255',
        ]);
        
        $settings = SiteSetting::first();
        
        if (!$settings) {
            $settings = new SiteSetting();
        }
        
        $data = $request->all();
        
        // Si le mot de passe est vide, ne pas le mettre à jour (garder l'ancien)
        if (empty($data['mail_password'])) {
            unset($data['mail_password']);
        }
        
        $settings->fill($data);
        $settings->save();
        
        // Invalider le cache
        \App\Models\SiteSetting::clearCache();
        
        // Recharger la configuration mail dynamiquement
        $this->updateMailConfig($settings);
        
        return redirect()->route('admin.settings')->with('success', 'Paramètres mis à jour avec succès!');
    }
    
    /**
     * Mettre à jour la configuration mail depuis les settings
     */
    private function updateMailConfig($settings)
    {
        if ($settings->mail_mailer) {
            config(['mail.default' => $settings->mail_mailer]);
        }
        if ($settings->mail_host) {
            config(['mail.mailers.smtp.host' => $settings->mail_host]);
        }
        if ($settings->mail_port) {
            config(['mail.mailers.smtp.port' => (int)$settings->mail_port]);
        }
        if ($settings->mail_username) {
            config(['mail.mailers.smtp.username' => $settings->mail_username]);
        }
        if ($settings->mail_password) {
            config(['mail.mailers.smtp.password' => $settings->mail_password]);
        }
        if ($settings->mail_encryption) {
            config(['mail.mailers.smtp.encryption' => $settings->mail_encryption]);
        }
        if ($settings->mail_from_address) {
            config(['mail.from.address' => $settings->mail_from_address]);
        }
        if ($settings->mail_from_name) {
            config(['mail.from.name' => $settings->mail_from_name]);
        }
    }

    public function bingSubmission()
    {
        $service = new BingUrlSubmissionService();
        $urls = $service->getAllUrlsToSubmit();
        $isConfigured = $service->isConfigured();
        
        // Calculer les statistiques dynamiques
        $totalUrls = count($urls);
        
        // Formations/Exercices/Quiz : 1 (formations) + 15 (langages) + 1 (exercices) + 15 (langages) + 1 (quiz) + 15 (langages) = 48
        $formationsExercicesQuiz = 48;
        
        // Pages statiques : 10 pages à conserver
        $pagesStatiques = 10;
        
        // Articles
        $articlesCount = \App\Models\JobArticle::where('status', 'published')
            ->whereNotNull('published_at')
            ->count();
        
        // Calculer le nombre d'articles inclus (pour atteindre 100 URLs au total)
        // Total fixe = 48 (formations/exercices/quiz) + 10 (pages statiques) = 58
        // Articles nécessaires = 100 - 58 = 42
        $articlesIncluded = min($articlesCount, max(0, 100 - ($formationsExercicesQuiz + $pagesStatiques)));
        
        return view('admin.bing-submission', compact('urls', 'isConfigured', 'totalUrls', 'formationsExercicesQuiz', 'pagesStatiques', 'articlesIncluded', 'articlesCount'));
    }

    public function submitToBing(Request $request)
    {
        $service = new BingUrlSubmissionService();

        if (!$service->isConfigured()) {
            return back()->with('error', 'Clé API Bing non configurée. Veuillez la configurer dans les paramètres.');
        }

        $urls = $service->getAllUrlsToSubmit();
        $result = $service->submitUrls($urls);

        if ($result['success']) {
            return back()->with('success', $result['message'] . " ({$result['submitted']}/{$result['total']} URLs soumises)");
        } else {
            return back()->with('error', $result['message']);
        }
    }
    
    public function logout(Request $request)
    {
        // Logger la déconnexion
        if (Auth::check()) {
            \Log::info('Admin logout', [
                'user_id' => Auth::id(),
                'email' => Auth::user()->email,
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Déconnexion réussie');
    }
    
    public function backups()
    {$backupsPath = storage_path('app/backups');
        $backups = [];
        
        if (is_dir($backupsPath)) {
            $files = glob($backupsPath . '/backup_*.sql.gz');
            foreach ($files as $file) {
                $backups[] = [
                    'filename' => basename($file),
                    'path' => $file,
                    'size' => filesize($file),
                    'created_at' => date('Y-m-d H:i:s', filemtime($file)),
                ];
            }
            
            // Trier par date (plus récent en premier)
            usort($backups, function($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });
        }
        
        return view('admin.backups', compact('backups'));
    }
    
    public function downloadBackup($filename)
    {$path = storage_path('app/backups/' . $filename);
        
        if (!file_exists($path) || !preg_match('/^backup_.*\.sql\.gz$/', $filename)) {
            return redirect()->route('admin.backups')->with('error', 'Fichier de sauvegarde non trouvé');
        }
        
        return response()->download($path);
    }
    
    public function deleteBackup($filename)
    {$path = storage_path('app/backups/' . $filename);
        
        if (!file_exists($path) || !preg_match('/^backup_.*\.sql\.gz$/', $filename)) {
            return redirect()->route('admin.backups')->with('error', 'Fichier de sauvegarde non trouvé');
        }
        
        unlink($path);
        
        return redirect()->route('admin.backups')->with('success', 'Sauvegarde supprimée avec succès!');
    }
    
    public function createBackup()
    {\Artisan::call('backup:database');
        
        return redirect()->route('admin.backups')->with('success', 'Sauvegarde créée avec succès!');
    }
    
    public function adsenseCheck()
    {// Vérifier toutes les exigences AdSense
        $checks = [
            'content_quality' => [
                'title' => 'Qualité du contenu',
                'status' => $this->checkContentQuality(),
                'message' => 'Le site doit contenir suffisamment de contenu original et de qualité (minimum 30 articles publiés)',
            ],
            'pages_legal' => [
                'title' => 'Pages légales (Politique de confidentialité, Mentions légales)',
                'status' => $this->checkLegalPages(),
                'message' => 'Les pages légales doivent être accessibles et complètes',
            ],
            'navigation' => [
                'title' => 'Navigation claire',
                'status' => true,
                'message' => 'La navigation doit être claire et intuitive',
            ],
            'mobile_friendly' => [
                'title' => 'Responsive/Mobile-friendly',
                'status' => true,
                'message' => 'Le site doit être optimisé pour mobile',
            ],
            'contact_page' => [
                'title' => 'Page de contact',
                'status' => $this->checkContactPage(),
                'message' => 'Une page de contact doit être disponible',
            ],
            'about_page' => [
                'title' => 'Page À propos',
                'status' => $this->checkAboutPage(),
                'message' => 'Une page À propos aide à établir la crédibilité',
            ],
            'ads_txt' => [
                'title' => 'Fichier ads.txt',
                'status' => $this->checkAdsTxt(),
                'message' => 'Le fichier ads.txt doit être présent à la racine',
            ],
            'sitemap' => [
                'title' => 'Sitemap.xml',
                'status' => $this->checkSitemap(),
                'message' => 'Un sitemap aide les moteurs de recherche à indexer le site',
            ],
            'robots_txt' => [
                'title' => 'Robots.txt',
                'status' => $this->checkRobotsTxt(),
                'message' => 'Le fichier robots.txt doit être présent',
            ],
        ];
        
        $score = 0;
        $total = 0;
        foreach ($checks as $check) {
            if ($check['status'] !== null) {
                $total++;
                if ($check['status']) {
                    $score++;
                }
            }
        }
        
        $percentage = $total > 0 ? round(($score / $total) * 100) : 0;
        
        return view('admin.adsense-check', compact('checks', 'score', 'total', 'percentage'));
    }
    
    private function checkContentQuality()
    {
        $articlesCount = \App\Models\JobArticle::where('status', 'published')->count();
        return $articlesCount >= 30;
    }
    
    private function checkLegalPages()
    {
        $privacyExists = file_exists(resource_path('views/privacy-policy.blade.php'));
        $legalExists = file_exists(resource_path('views/legal.blade.php'));
        return $privacyExists && $legalExists;
    }
    
    private function checkContactPage()
    {
        return \Illuminate\Support\Facades\Route::has('contact');
    }
    
    private function checkAboutPage()
    {
        return file_exists(resource_path('views/about.blade.php')) || \Illuminate\Support\Facades\Route::has('about');
    }
    
    private function checkAdsTxt()
    {
        return file_exists(public_path('ads.txt'));
    }
    
    private function checkSitemap()
    {
        return file_exists(public_path('sitemap.xml'));
    }
    
    private function checkRobotsTxt()
    {
        return file_exists(public_path('robots.txt'));
    }
}
