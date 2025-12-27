<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\PaidCourse;
use App\Models\CoursePurchase;
use App\Models\Donation;
use App\Models\Affiliate;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Concerns\LocaleTrait;

class MonetizationController extends Controller
{
    use LocaleTrait;

    /**
     * Afficher la page de monétisation avec toutes les options
     */
    public function index()
    {
        $this->ensureLocale();
        
        // Récupérer les plans d'abonnement depuis la base de données
        $subscriptionPlans = SubscriptionPlan::active()
            ->ordered()
            ->get()
            ->mapWithKeys(function($plan) {
                return [$plan->slug => [
                    'id' => $plan->id,
                    'name' => $plan->name,
                    'slug' => $plan->slug,
                    'price' => $plan->price,
                    'currency' => $plan->currency,
                    'duration' => $plan->duration_days,
                    'billing_period' => $plan->billing_period,
                    'features' => $plan->features ?? [],
                    'description' => $plan->description,
                    'badge' => $plan->badge,
                    'is_featured' => $plan->is_featured,
                ]];
            });

        $paidCourses = PaidCourse::where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        return view('monetization.index', compact(
            'subscriptionPlans',
            'paidCourses'
        ));
    }

    /**
     * Afficher la page dédiée aux donations
     */
    public function donations()
    {
        $this->ensureLocale();
        
        $recentDonations = Donation::where('status', 'completed')
            ->where('show_on_wall', true)
            ->orderBy('completed_at', 'desc')
            ->take(10)
            ->get();

        $userDonationCount = Auth::check() ? Donation::where('user_id', Auth::id())
            ->where('status', 'completed')
            ->count() : 0;

        return view('monetization.donations', compact(
            'recentDonations',
            'userDonationCount'
        ));
    }

    /**
     * Afficher les cours payants avec filtres
     */
    public function courses(Request $request)
    {
        $this->ensureLocale();
        
        $query = PaidCourse::where('status', 'published');
        
        // Recherche par titre/description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Filtre par prix minimum
        if ($request->filled('price_min')) {
            $query->where(function($q) use ($request) {
                $q->whereRaw('CASE 
                    WHEN discount_price IS NOT NULL 
                    AND discount_start <= NOW() 
                    AND discount_end >= NOW() 
                    THEN discount_price 
                    ELSE price 
                END >= ?', [$request->price_min]);
            });
        }
        
        // Filtre par prix maximum
        if ($request->filled('price_max')) {
            $query->where(function($q) use ($request) {
                $q->whereRaw('CASE 
                    WHEN discount_price IS NOT NULL 
                    AND discount_start <= NOW() 
                    AND discount_end >= NOW() 
                    THEN discount_price 
                    ELSE price 
                END <= ?', [$request->price_max]);
            });
        }
        
        // Filtre par durée minimum
        if ($request->filled('duration_min')) {
            $query->where('duration_hours', '>=', $request->duration_min);
        }
        
        // Filtre par durée maximum
        if ($request->filled('duration_max')) {
            $query->where('duration_hours', '<=', $request->duration_max);
        }
        
        // Filtre par note minimum
        if ($request->filled('rating_min')) {
            $query->where('rating', '>=', $request->rating_min);
        }
        
        // Filtre par promotions uniquement
        if ($request->filled('discount_only') && $request->discount_only == '1') {
            $query->whereNotNull('discount_price')
                  ->where('discount_start', '<=', now())
                  ->where('discount_end', '>=', now());
        }
        
        // Tri
        $sortBy = $request->get('sort', 'newest');
        switch ($sortBy) {
            case 'price_asc':
                $query->orderByRaw('CASE 
                    WHEN discount_price IS NOT NULL 
                    AND discount_start <= NOW() 
                    AND discount_end >= NOW() 
                    THEN discount_price 
                    ELSE price 
                END ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('CASE 
                    WHEN discount_price IS NOT NULL 
                    AND discount_start <= NOW() 
                    AND discount_end >= NOW() 
                    THEN discount_price 
                    ELSE price 
                END DESC');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc')->orderBy('reviews_count', 'desc');
                break;
            case 'students':
                $query->orderBy('students_count', 'desc');
                break;
            case 'duration':
                $query->orderBy('duration_hours', 'desc');
                break;
            default: // 'newest'
                $query->orderBy('created_at', 'desc');
        }
        
        // Pagination avec 9 cours par page (3 lignes de 3)
        $courses = $query->paginate(9)->withQueryString();
        
        // Statistiques pour les filtres
        $stats = [
            'min_price' => PaidCourse::where('status', 'published')->min('price') ?? 0,
            'max_price' => PaidCourse::where('status', 'published')->max('price') ?? 100000,
            'min_duration' => PaidCourse::where('status', 'published')->min('duration_hours') ?? 0,
            'max_duration' => PaidCourse::where('status', 'published')->max('duration_hours') ?? 100,
            'min_rating' => 0,
            'max_rating' => 5,
        ];
        
        return view('monetization.courses', compact('courses', 'stats'));
    }

    /**
     * Afficher un cours payant
     */
    public function showCourse($slug)
    {
        $this->ensureLocale();
        
        $course = PaidCourse::with('chapters')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $hasPurchased = Auth::check() && Auth::user()->hasPurchasedCourse($course->id);
        $isPremium = Auth::check() && Auth::user()->hasActivePremium();

        // Récupérer 2 autres cours payants (autres que le cours actuel)
        $relatedCourses = PaidCourse::where('status', 'published')
            ->where('id', '!=', $course->id)
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get();

        return view('monetization.course-show', compact('course', 'hasPurchased', 'isPremium', 'relatedCourses'));
    }

    /**
     * Afficher la page d'affiliation
     */
    public function affiliates()
    {
        $this->ensureLocale();
        
        $userAffiliate = null;
        if (Auth::check()) {
            $userAffiliate = Affiliate::where('user_id', Auth::id())->first();
        }

        return view('monetization.affiliates', compact('userAffiliate'));
    }

    /**
     * Devenir affilié
     */
    public function becomeAffiliate(Request $request)
    {
        $this->ensureLocale();
        
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour devenir affilié.');
        }

        // Vérifier si l'utilisateur est déjà affilié
        $existingAffiliate = Affiliate::where('user_id', Auth::id())->first();
        if ($existingAffiliate) {
            return redirect()->route('monetization.affiliates.dashboard')
                ->with('info', 'Vous êtes déjà affilié.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        // Créer l'affilié avec un taux de commission par défaut de 10%
        $affiliate = Affiliate::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'commission_rate' => 10.00, // Taux par défaut
            'status' => 'active',
        ]);

        return redirect()->route('monetization.affiliates.dashboard')
            ->with('success', 'Félicitations ! Vous êtes maintenant affilié. Commencez à partager votre lien pour gagner des commissions.');
    }

    /**
     * Dashboard d'affiliation pour l'utilisateur
     */
    public function affiliatesDashboard()
    {
        $this->ensureLocale();
        
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à votre dashboard d\'affiliation.');
        }

        $affiliate = Affiliate::where('user_id', Auth::id())->firstOrFail();

        $referrals = $affiliate->referrals()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Statistiques
        $stats = [
            'total_referrals' => $affiliate->referrals()->count(),
            'pending_referrals' => $affiliate->referrals()->where('status', 'pending')->count(),
            'approved_referrals' => $affiliate->referrals()->where('status', 'approved')->count(),
            'paid_referrals' => $affiliate->referrals()->where('status', 'paid')->count(),
            'total_earnings' => $affiliate->total_earnings,
            'paid_earnings' => $affiliate->paid_earnings,
            'pending_earnings' => $affiliate->pending_earnings,
            'commission_rate' => $affiliate->commission_rate,
        ];

        // Références par mois (derniers 12 mois)
        $referralsByMonth = $affiliate->referrals()
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count, SUM(commission) as total_commission')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();

        return view('monetization.affiliates-dashboard', compact('affiliate', 'referrals', 'stats', 'referralsByMonth'));
    }
}
