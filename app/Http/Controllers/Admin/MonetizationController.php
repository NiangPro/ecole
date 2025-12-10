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
    public function subscriptions()
    {
        $subscriptions = Subscription::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.monetization.subscriptions', compact('subscriptions'));
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
    public function payments()
    {
        $payments = Payment::with(['user', 'paymentable'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.monetization.payments', compact('payments'));
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

