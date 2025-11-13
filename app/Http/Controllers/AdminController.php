<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
        
        return view('admin.dashboard');
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
        
        // Obtenir les années disponibles
        $availableYears = Statistic::getAvailableYears();
        
        // Calculer les statistiques selon le filtre
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
        
        // Pages les plus visitées
        $topPages = Statistic::getTopPages(10, $filter);
        
        // Données pour le graphique (30 derniers jours ou mensuel)
        if ($filter == 'year') {
            $dailyStats = Statistic::getMonthlyStatsForYear($year);
        } else {
            $dailyStats = Statistic::getDailyStats(30);
        }
        
        // Statistiques par pays, navigateur, source
        $countriesStats = Statistic::getByCountry($filter, $year, $month);
        $browsersStats = Statistic::getByBrowser($filter, $year, $month);
        $sourcesStats = Statistic::getBySource($filter, $year, $month);
        
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
            'sourcesStats'
        ));
    }
    
    public function truncateStatistics()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        
        Statistic::truncate();
        
        return redirect()->route('admin.statistics')->with('success', 'Table statistics vidée avec succès!');
    }
    
    public function users(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        
        $search = $request->get('search');
        $users = User::when($search, function($query) use ($search) {
                        return $query->where('name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
        
        return view('admin.users.index', compact('users', 'search'));
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
            'twitter_url' => 'nullable|url',
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
