<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
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
        
        $subscriptionPlans = [
            'premium' => [
                'name' => 'Premium',
                'price' => 5000, // 5000 FCFA
                'currency' => 'XOF',
                'duration' => 30, // jours
                'features' => [
                    'Accès à tous les cours premium',
                    'Certificats téléchargeables',
                    'Support prioritaire',
                    'Sans publicités',
                    'Contenu exclusif'
                ]
            ],
            'pro' => [
                'name' => 'Pro',
                'price' => 10000, // 10000 FCFA
                'currency' => 'XOF',
                'duration' => 30,
                'features' => [
                    'Tout Premium inclus',
                    'Coaching personnalisé',
                    'Projets pratiques',
                    'Accès communauté VIP',
                    'Webinaires exclusifs'
                ]
            ],
            'enterprise' => [
                'name' => 'Enterprise',
                'price' => 25000, // 25000 FCFA
                'currency' => 'XOF',
                'duration' => 30,
                'features' => [
                    'Tout Pro inclus',
                    'Formation sur mesure',
                    'Support dédié',
                    'Licence multi-utilisateurs',
                    'API personnalisée'
                ]
            ]
        ];

        $paidCourses = PaidCourse::where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        $recentDonations = Donation::where('status', 'completed')
            ->where('show_on_wall', true)
            ->orderBy('completed_at', 'desc')
            ->take(10)
            ->get();

        $totalDonations = Donation::where('status', 'completed')->sum('amount');
        $donationsCount = Donation::where('status', 'completed')->count();

        return view('monetization.index', compact(
            'subscriptionPlans',
            'paidCourses',
            'recentDonations',
            'totalDonations',
            'donationsCount'
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
        
        // Pagination avec 12 cours par page (3 lignes de 4)
        $courses = $query->paginate(12)->withQueryString();
        
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
}
