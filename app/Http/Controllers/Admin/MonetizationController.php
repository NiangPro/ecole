<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\PaidCourse;
use App\Models\CoursePurchase;
use App\Models\Donation;
use App\Models\Affiliate;
use App\Models\AffiliateReferral;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class MonetizationController extends Controller
{
    /**
     * Dashboard de monétisation
     */
    public function dashboard()
    {
        $stats = [
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            'subscriptions_count' => Subscription::where('status', 'active')->count(),
            'courses_sold' => CoursePurchase::where('status', 'completed')->count(),
            'donations_total' => Donation::where('status', 'completed')->sum('amount'),
            'affiliates_count' => Affiliate::where('status', 'active')->count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
        ];

        $recentPayments = Payment::with(['user', 'paymentable'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $revenueByMonth = Payment::where('status', 'completed')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();

        return view('admin.monetization.dashboard', compact('stats', 'recentPayments', 'revenueByMonth'));
    }

    /**
     * Gérer les abonnements
     */
    public function subscriptions(Request $request)
    {
        $query = Subscription::with('user');
        
        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('plan_type')) {
            $query->where('plan_type', $request->plan_type);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('payment_reference', 'like', "%{$search}%");
        }
        
        $subscriptions = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Statistiques
        $stats = [
            'total' => Subscription::count(),
            'active' => Subscription::where('status', 'active')->count(),
            'pending' => Subscription::where('status', 'pending')->count(),
            'cancelled' => Subscription::where('status', 'cancelled')->count(),
            'expired' => Subscription::where('status', 'expired')->count(),
        ];

        return view('admin.monetization.subscriptions', compact('subscriptions', 'stats'));
    }
    
    /**
     * Voir les détails d'un abonnement
     */
    public function showSubscription($id)
    {
        $subscription = Subscription::with(['user', 'payment'])->findOrFail($id);
        
        return view('admin.monetization.subscription-show', compact('subscription'));
    }
    
    /**
     * Activer un abonnement
     */
    public function activateSubscription(Request $request, $id)
    {
        $subscription = Subscription::findOrFail($id);
        
        DB::transaction(function () use ($subscription, $request) {
            $subscription->update([
                'status' => 'active',
                'start_date' => $subscription->start_date ?? now(),
                'end_date' => $request->end_date ?? ($subscription->plan_type === 'monthly' ? now()->addMonth() : now()->addYear()),
                'next_billing_date' => $request->next_billing_date ?? ($subscription->plan_type === 'monthly' ? now()->addMonth() : now()->addYear()),
            ]);
            
            // Mettre à jour l'utilisateur
            $subscription->user->update([
                'is_premium' => true,
                'premium_until' => $subscription->end_date,
                'current_subscription_id' => $subscription->id,
            ]);
            
            // Mettre à jour le paiement associé si existe
            if ($subscription->payment) {
                $subscription->payment->update([
                    'status' => 'completed',
                    'paid_at' => now(),
                ]);
            }
        });
        
        return redirect()->route('admin.monetization.subscriptions')
            ->with('success', 'Abonnement activé avec succès.');
    }
    
    /**
     * Désactiver/Annuler un abonnement
     */
    public function deactivateSubscription(Request $request, $id)
    {
        $subscription = Subscription::findOrFail($id);
        
        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);
        
        DB::transaction(function () use ($subscription, $request) {
            $subscription->update([
                'status' => 'cancelled',
                'notes' => ($subscription->notes ? $subscription->notes . "\n\n" : '') . 'Annulé le ' . now()->format('d/m/Y H:i') . ($request->reason ? ' - ' . $request->reason : ''),
            ]);
            
            // Vérifier si c'est l'abonnement actuel de l'utilisateur
            if ($subscription->user->current_subscription_id === $subscription->id) {
                $subscription->user->update([
                    'is_premium' => false,
                    'premium_until' => null,
                    'current_subscription_id' => null,
                ]);
            }
        });
        
        return redirect()->route('admin.monetization.subscriptions')
            ->with('success', 'Abonnement annulé avec succès.');
    }
    
    /**
     * Mettre à jour un abonnement
     */
    public function updateSubscription(Request $request, $id)
    {
        $subscription = Subscription::findOrFail($id);
        
        $validated = $request->validate([
            'plan_type' => 'required|in:monthly,yearly',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'status' => 'required|in:active,pending,cancelled,expired',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'next_billing_date' => 'nullable|date',
            'payment_method' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:1000',
        ]);
        
        DB::transaction(function () use ($subscription, $validated) {
            $subscription->update($validated);
            
            // Si l'abonnement est activé, mettre à jour l'utilisateur
            if ($validated['status'] === 'active' && $subscription->user->current_subscription_id === $subscription->id) {
                $subscription->user->update([
                    'is_premium' => true,
                    'premium_until' => $subscription->end_date,
                ]);
            }
        });
        
        return redirect()->route('admin.monetization.subscriptions.show', $subscription->id)
            ->with('success', 'Abonnement mis à jour avec succès.');
    }

    /**
     * Gérer les cours payants
     */
    public function courses()
    {
        $courses = PaidCourse::withCount('purchases')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.monetization.courses', compact('courses'));
    }

    /**
     * Gérer les dons (redirige vers le contrôleur dédié)
     */
    public function donations()
    {
        return redirect()->route('admin.monetization.donations.index');
    }

    /**
     * Gérer les affiliés
     */
    public function affiliates(Request $request)
    {
        $query = Affiliate::with('user')
            ->withCount('referrals')
            ->withCount(['referrals as pending_referrals_count' => function($q) {
                $q->where('status', 'pending');
            }])
            ->withCount(['referrals as approved_referrals_count' => function($q) {
                $q->where('status', 'approved');
            }])
            ->withCount(['referrals as paid_referrals_count' => function($q) {
                $q->where('status', 'paid');
            }]);

        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('affiliate_code', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Tri
        $sortBy = $request->get('sort', 'created_at');
        $sortDir = $request->get('dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        $affiliates = $query->paginate(20)->withQueryString();

        // Statistiques
        $stats = [
            'total' => Affiliate::count(),
            'active' => Affiliate::where('status', 'active')->count(),
            'inactive' => Affiliate::where('status', 'inactive')->count(),
            'suspended' => Affiliate::where('status', 'suspended')->count(),
            'total_earnings' => Affiliate::sum('total_earnings'),
            'paid_earnings' => Affiliate::sum('paid_earnings'),
            'pending_earnings' => Affiliate::sum('pending_earnings'),
            'total_referrals' => AffiliateReferral::count(),
        ];

        return view('admin.monetization.affiliates', compact('affiliates', 'stats'));
    }

    /**
     * Afficher les détails d'un affilié
     */
    public function showAffiliate($id)
    {
        $affiliate = Affiliate::with(['user', 'referrals.user'])
            ->withCount('referrals')
            ->findOrFail($id);

        $referrals = $affiliate->referrals()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Statistiques de l'affilié
        $affiliateStats = [
            'total_referrals' => $affiliate->referrals()->count(),
            'pending_referrals' => $affiliate->referrals()->where('status', 'pending')->count(),
            'approved_referrals' => $affiliate->referrals()->where('status', 'approved')->count(),
            'paid_referrals' => $affiliate->referrals()->where('status', 'paid')->count(),
            'total_commission' => $affiliate->referrals()->sum('commission'),
            'pending_commission' => $affiliate->referrals()->where('status', 'pending')->sum('commission'),
            'paid_commission' => $affiliate->referrals()->where('status', 'paid')->sum('commission'),
        ];

        return view('admin.monetization.affiliate-show', compact('affiliate', 'referrals', 'affiliateStats'));
    }

    /**
     * Créer un nouvel affilié
     */
    public function createAffiliate()
    {
        $users = \App\Models\User::orderBy('name')->get();
        return view('admin.monetization.affiliate-create', compact('users'));
    }

    /**
     * Enregistrer un nouvel affilié
     */
    public function storeAffiliate(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'affiliate_code' => 'nullable|string|max:20|unique:affiliates,affiliate_code',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:active,inactive,suspended',
            'notes' => 'nullable|string|max:1000',
        ]);

        $affiliate = Affiliate::create($validated);

        return redirect()->route('admin.monetization.affiliates.show', $affiliate->id)
            ->with('success', 'Affilié créé avec succès.');
    }

    /**
     * Modifier un affilié
     */
    public function editAffiliate($id)
    {
        $affiliate = Affiliate::findOrFail($id);
        $users = \App\Models\User::orderBy('name')->get();
        return view('admin.monetization.affiliate-edit', compact('affiliate', 'users'));
    }

    /**
     * Mettre à jour un affilié
     */
    public function updateAffiliate(Request $request, $id)
    {
        $affiliate = Affiliate::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'affiliate_code' => 'nullable|string|max:20|unique:affiliates,affiliate_code,' . $affiliate->id,
            'commission_rate' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:active,inactive,suspended',
            'notes' => 'nullable|string|max:1000',
        ]);

        $affiliate->update($validated);

        return redirect()->route('admin.monetization.affiliates.show', $affiliate->id)
            ->with('success', 'Affilié mis à jour avec succès.');
    }

    /**
     * Supprimer un affilié
     */
    public function destroyAffiliate($id)
    {
        $affiliate = Affiliate::findOrFail($id);

        // Vérifier s'il y a des références
        if ($affiliate->referrals()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer cet affilié car il a des références associées.');
        }

        $affiliate->delete();

        return redirect()->route('admin.monetization.affiliates')
            ->with('success', 'Affilié supprimé avec succès.');
    }

    /**
     * Approuver une référence
     */
    public function approveReferral($affiliateId, $referralId)
    {
        $referral = AffiliateReferral::where('affiliate_id', $affiliateId)
            ->findOrFail($referralId);

        if ($referral->status !== 'pending') {
            return back()->with('error', 'Cette référence ne peut pas être approuvée.');
        }

        DB::transaction(function () use ($referral) {
            $referral->update([
                'status' => 'approved',
                'approved_at' => now(),
            ]);

            // Mettre à jour les statistiques de l'affilié
            $affiliate = $referral->affiliate;
            $affiliate->increment('total_earnings', $referral->commission);
            $affiliate->decrement('pending_earnings', $referral->commission);
        });

        return back()->with('success', 'Référence approuvée avec succès.');
    }

    /**
     * Marquer une référence comme payée
     */
    public function payReferral($affiliateId, $referralId)
    {
        $referral = AffiliateReferral::where('affiliate_id', $affiliateId)
            ->findOrFail($referralId);

        if ($referral->status !== 'approved') {
            return back()->with('error', 'Cette référence doit être approuvée avant d\'être payée.');
        }

        DB::transaction(function () use ($referral) {
            $referral->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            // Mettre à jour les statistiques de l'affilié
            $affiliate = $referral->affiliate;
            $affiliate->increment('paid_earnings', $referral->commission);
            $affiliate->decrement('pending_earnings', $referral->commission);
        });

        return back()->with('success', 'Référence marquée comme payée avec succès.');
    }

    /**
     * Rejeter une référence (retour au statut pending ou suppression)
     */
    public function rejectReferral($affiliateId, $referralId, Request $request)
    {
        $referral = AffiliateReferral::where('affiliate_id', $affiliateId)
            ->findOrFail($referralId);

        if ($referral->status === 'paid') {
            return back()->with('error', 'Impossible de rejeter une référence déjà payée.');
        }

        DB::transaction(function () use ($referral) {
            $commission = $referral->commission;
            $wasApproved = $referral->status === 'approved';

            // Si approuvée, on retire les gains et on remet en pending
            if ($wasApproved) {
                $affiliate = $referral->affiliate;
                $affiliate->decrement('total_earnings', $commission);
                $affiliate->increment('pending_earnings', $commission);
            }

            // On remet simplement en pending (ou on peut supprimer si nécessaire)
            $referral->update([
                'status' => 'pending',
            ]);
        });

        return back()->with('success', 'Référence remise en attente.');
    }

    /**
     * Gérer les paiements
     */
    public function payments(Request $request)
    {
        $query = Payment::with(['user', 'paymentable']);

        // Filtres
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('type') && $request->type !== '') {
            $query->where('paymentable_type', 'App\\Models\\' . $request->type);
        }

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('payment_reference', 'like', "%{$search}%")
                  ->orWhere('amount', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('date_from') && $request->date_from !== '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to !== '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        // Statistiques
        $stats = [
            'total' => Payment::count(),
            'completed' => Payment::where('status', 'completed')->count(),
            'pending' => Payment::where('status', 'pending')->count(),
            'failed' => Payment::where('status', 'failed')->count(),
            'processing' => Payment::where('status', 'processing')->count(),
            'total_amount' => Payment::where('status', 'completed')->sum('amount'),
            'pending_amount' => Payment::where('status', 'pending')->sum('amount'),
        ];

        return view('admin.monetization.payments', compact('payments', 'stats'));
    }

    /**
     * Voir les détails d'un paiement de cours
     */
    public function showCoursePayment($paymentId)
    {
        $payment = Payment::with(['user', 'paymentable'])
            ->findOrFail($paymentId);

        if (class_basename($payment->paymentable_type) !== 'CoursePurchase') {
            return redirect()->route('admin.monetization.payments')
                ->with('error', 'Ce paiement n\'est pas lié à un cours.');
        }

        $purchase = CoursePurchase::with('course')->findOrFail($payment->paymentable_id);
        $course = $purchase->course;

        return view('admin.monetization.payment-course-details', compact('payment', 'purchase', 'course'));
    }

    /**
     * Accepter un paiement de cours (confirmer l'inscription)
     */
    public function acceptCoursePayment(Request $request, $paymentId)
    {
        $payment = Payment::with('paymentable')->findOrFail($paymentId);

        if (class_basename($payment->paymentable_type) !== 'CoursePurchase') {
            return redirect()->route('admin.monetization.payments')
                ->with('error', 'Ce paiement n\'est pas lié à un cours.');
        }

        DB::transaction(function () use ($payment) {
            // Mettre à jour le paiement
            $payment->update([
                'status' => 'completed',
                'paid_at' => now(),
            ]);

            // Mettre à jour l'achat
            $purchase = $payment->paymentable;
            $purchase->update([
                'status' => 'completed',
                'purchased_at' => now(),
            ]);

            // Mettre à jour le nombre d'étudiants du cours
            $course = $purchase->course;
            $course->increment('students_count');
        });

        return redirect()->route('admin.monetization.payments')
            ->with('success', 'L\'inscription au cours a été acceptée avec succès.');
    }

    /**
     * Refuser un paiement de cours
     */
    public function rejectCoursePayment(Request $request, $paymentId)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $payment = Payment::with('paymentable')->findOrFail($paymentId);

        if (class_basename($payment->paymentable_type) !== 'CoursePurchase') {
            return redirect()->route('admin.monetization.payments')
                ->with('error', 'Ce paiement n\'est pas lié à un cours.');
        }

        DB::transaction(function () use ($payment, $request) {
            // Mettre à jour le paiement
            $payment->update([
                'status' => 'failed',
                'failure_reason' => $request->reason ?? 'Paiement refusé par l\'administrateur',
            ]);

            // Mettre à jour l'achat
            $purchase = $payment->paymentable;
            $purchase->update([
                'status' => 'failed',
            ]);
        });

        return redirect()->route('admin.monetization.payments')
            ->with('success', 'Le paiement a été refusé.');
    }
}

