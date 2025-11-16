<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use App\Models\AdSenseSetting;
use App\Models\Statistic;
use App\Models\User;
use App\Models\SiteSetting;
use App\Models\ContactMessage;
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
        
        // Compte test admin
        $adminEmail = 'admin@niangprogrammeur.com';
        $adminPassword = 'Admin@2025';
        
        if ($request->email === $adminEmail && $request->password === $adminPassword) {
            session([
                'admin_logged_in' => true,
                'admin_email' => $adminEmail
            ]);
            
            return redirect()->route('admin.dashboard');
        }
        
        return back()->with('error', 'Email ou mot de passe incorrect');
    }
    
    public function dashboard()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        
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
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        
        // Pour l'instant, on utilise les données de session
        // Plus tard, on pourra créer un vrai système d'authentification
        $admin = (object)[
            'name' => 'Administrateur',
            'email' => session('admin_email'),
        ];
        
        return view('admin.profile', compact('admin'));
    }
    
    public function updateProfile(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'current_password' => 'required_with:new_password',
            'new_password' => 'nullable|min:6|confirmed',
        ]);
        
        // Vérifier le mot de passe actuel
        if ($request->filled('new_password')) {
            if ($request->current_password !== 'Admin@2025') {
                return back()->with('error', 'Le mot de passe actuel est incorrect');
            }
            
            // Ici, vous devriez mettre à jour le mot de passe dans la base de données
            // Pour l'instant, on simule juste
        }
        
        // Mettre à jour l'email en session
        session(['admin_email' => $request->email]);
        
        return redirect()->route('admin.profile')->with('success', 'Profil mis à jour avec succès!');
    }
    
    public function adsense()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        
        $settings = AdSenseSetting::first();
        
        return view('admin.adsense', compact('settings'));
    }
    
    public function updateAdsense(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        
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
        
        return redirect()->route('admin.adsense')->with('success', 'Configuration AdSense mise à jour avec succès!');
    }
    
    public function statistics(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        
        $filter = $request->get('filter', 'month');
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
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        
        Statistic::truncate();
        
        // Vider tous les caches liés aux statistiques
        Cache::flush();
        
        return redirect()->route('admin.statistics')->with('success', 'Table statistics vidée avec succès!');
    }
    
    public function users(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        
        $search = $request->get('search');
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
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        
        return view('admin.users.create');
    }
    
    public function storeUser(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        
        $request->validate([
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
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }
    
    public function updateUser(Request $request, $id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        
        $user = User::findOrFail($id);
        
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
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        
        $user = User::findOrFail($id);
        $user->delete();
        
        return redirect()->route('admin.users')->with('success', 'Utilisateur supprimé avec succès!');
    }
    
    public function messages(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        
        $filter = $request->get('filter', 'all');
        
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
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        
        $message = ContactMessage::findOrFail($id);
        $message->is_read = true;
        $message->save();
        
        return back()->with('success', 'Message marqué comme lu');
    }
    
    public function deleteMessage($id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        
        $message = ContactMessage::findOrFail($id);
        $message->delete();
        
        return back()->with('success', 'Message supprimé avec succès');
    }
    
    public function settings()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        
        $settings = SiteSetting::first();
        
        return view('admin.settings', compact('settings'));
    }
    
    public function updateSettings(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'contact_address' => 'nullable|string',
            'facebook_url' => 'nullable|url',
            'tiktok_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'github_url' => 'nullable|url',
            'google_analytics_id' => 'nullable|string|regex:/^G-[A-Z0-9]+$/',
        ]);
        
        $settings = SiteSetting::first();
        
        if (!$settings) {
            $settings = new SiteSetting();
        }
        
        $settings->fill($request->all());
        $settings->save();
        
        return redirect()->route('admin.settings')->with('success', 'Paramètres mis à jour avec succès!');
    }
    
    public function logout()
    {
        session()->forget(['admin_logged_in', 'admin_email']);
        return redirect()->route('admin.login');
    }
}
