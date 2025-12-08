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
     * Afficher les cours payants
     */
    public function courses()
    {
        $this->ensureLocale();
        
        $courses = PaidCourse::where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('monetization.courses', compact('courses'));
    }

    /**
     * Afficher un cours payant
     */
    public function showCourse($slug)
    {
        $this->ensureLocale();
        
        $course = PaidCourse::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $hasPurchased = Auth::check() && Auth::user()->hasPurchasedCourse($course->id);
        $isPremium = Auth::check() && Auth::user()->hasActivePremium();

        return view('monetization.course-show', compact('course', 'hasPurchased', 'isPremium'));
    }
}
