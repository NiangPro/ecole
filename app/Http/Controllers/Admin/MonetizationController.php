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
    public function affiliates()
    {
        $affiliates = Affiliate::with('user')
            ->withCount('referrals')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.monetization.affiliates', compact('affiliates'));
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

