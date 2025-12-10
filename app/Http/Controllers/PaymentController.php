<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\CoursePurchase;
use App\Models\Donation;
use App\Models\Affiliate;
use App\Models\AffiliateReferral;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\WavePaymentService;
use App\Services\PayPalPaymentService;
use App\Services\StripePaymentService;
use App\Http\Controllers\Concerns\LocaleTrait;

class PaymentController extends Controller
{
    use LocaleTrait;
    /**
     * Traiter un paiement d'abonnement
     */
    public function processSubscription(Request $request)
    {
        $request->validate([
            'plan_type' => 'required|in:premium,pro,enterprise',
            'payment_method' => 'required|in:mobile_money,bank_transfer,stripe,paypal',
        ]);

        $plans = [
            'premium' => ['price' => 5000, 'duration' => 30],
            'pro' => ['price' => 10000, 'duration' => 30],
            'enterprise' => ['price' => 25000, 'duration' => 30],
        ];

        $plan = $plans[$request->plan_type];
        $user = Auth::user();

        // Créer l'abonnement
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_type' => $request->plan_type,
            'amount' => $plan['price'],
            'currency' => 'XOF',
            'status' => 'pending',
            'start_date' => now(),
            'end_date' => now()->addDays($plan['duration']),
            'next_billing_date' => now()->addDays($plan['duration']),
            'payment_method' => $request->payment_method,
        ]);

        // Créer le paiement
        $payment = Payment::create([
            'user_id' => $user->id,
            'paymentable_type' => Subscription::class,
            'paymentable_id' => $subscription->id,
            'amount' => $plan['price'],
            'currency' => 'XOF',
            'status' => 'pending',
            'payment_method' => $request->payment_method,
            'payment_gateway' => $request->payment_method,
            'transaction_id' => 'SUB-' . Str::upper(Str::random(12)),
            'payment_reference' => 'REF-' . Str::upper(Str::random(10)),
        ]);

        // Gérer l'affiliation si un code de référence existe
        if ($request->has('ref_code')) {
            $this->handleAffiliateReferral($request->ref_code, $subscription, $plan['price']);
        }

        // Rediriger vers la page de confirmation de paiement
        return redirect()->route('payment.confirm', $payment->id)
            ->with('success', 'Votre abonnement a été créé. Veuillez compléter le paiement.');
    }

    /**
     * Traiter un achat de cours
     */
    public function processCoursePurchase(Request $request, $courseId)
    {
        $course = \App\Models\PaidCourse::findOrFail($courseId);
        $user = Auth::user();

        // Vérifier si l'utilisateur a déjà acheté ce cours (complété)
        if ($user->hasPurchasedCourse($course->id)) {
            return redirect()->route('monetization.course.show', $course->slug)
                ->with('info', 'Vous avez déjà acheté ce cours.');
        }

        $request->validate([
            'payment_method' => 'required|in:mobile_money,bank_transfer,wave,stripe,paypal',
        ]);

        $price = $course->current_price;

        // Vérifier s'il existe déjà un achat en attente pour ce cours
        $existingPurchase = CoursePurchase::where('user_id', $user->id)
            ->where('paid_course_id', $course->id)
            ->first();

        if ($existingPurchase) {
            // Mettre à jour l'achat existant
            $existingPurchase->update([
                'amount_paid' => $price,
                'currency' => 'XOF',
                'status' => 'pending',
                'payment_method' => $request->payment_method,
            ]);
            $purchase = $existingPurchase;
        } else {
            // Créer un nouvel achat
            $purchase = CoursePurchase::create([
                'user_id' => $user->id,
                'paid_course_id' => $course->id,
                'amount_paid' => $price,
                'currency' => 'XOF',
                'status' => 'pending',
                'payment_method' => $request->payment_method,
            ]);
        }

        // Vérifier s'il existe déjà un paiement pour cet achat
        $existingPayment = Payment::where('paymentable_type', CoursePurchase::class)
            ->where('paymentable_id', $purchase->id)
            ->where('status', 'pending')
            ->first();

        if ($existingPayment) {
            // Mettre à jour le paiement existant
            $existingPayment->update([
                'amount' => $price,
                'currency' => 'XOF',
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'payment_gateway' => $request->payment_method,
            ]);
            $payment = $existingPayment;
        } else {
            // Créer un nouveau paiement
            $payment = Payment::create([
                'user_id' => $user->id,
                'paymentable_type' => CoursePurchase::class,
                'paymentable_id' => $purchase->id,
                'amount' => $price,
                'currency' => 'XOF',
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'payment_gateway' => $request->payment_method,
                'transaction_id' => 'COURSE-' . Str::upper(Str::random(12)),
                'payment_reference' => 'REF-' . Str::upper(Str::random(10)),
            ]);
        }

        // Gérer l'affiliation
        if ($request->has('ref_code')) {
            $this->handleAffiliateReferral($request->ref_code, $purchase, $price);
        }

        return redirect()->route('payment.confirm', $payment->id)
            ->with('success', 'Votre achat a été créé. Veuillez compléter le paiement.');
    }

    /**
     * Traiter un don
     */
    public function processDonation(Request $request)
    {
        // Valider d'abord les champs de base
        $request->validate([
            'donor_name' => 'required|string|max:255',
            'donor_email' => 'nullable|email',
            'payment_method' => 'required|in:mobile_money,bank_transfer,stripe,paypal,wave',
            'message' => 'nullable|string|max:500',
            'is_anonymous' => 'boolean',
        ]);
        
        // Nouvelle approche : accepter le montant dans sa devise d'origine
        $paymentMethod = $request->payment_method;
        $amountCurrency = $request->input('amount_currency', 'XOF'); // XOF par défaut
        $xofToUsd = 600; // Taux de change
        
        // Déterminer le minimum selon la méthode de paiement et la devise
        $minAmount = 100; // Par défaut 100 FCFA
        $minAmountUSD = 0.50; // Minimum en USD pour Stripe/PayPal
        
        if ($paymentMethod === 'stripe' || $paymentMethod === 'paypal') {
            // Pour Stripe et PayPal, le minimum est en USD
            if ($amountCurrency === 'USD') {
                // Validation en USD
                $request->validate([
                    'amount' => "required|numeric|min:{$minAmountUSD}",
                ], [
                    'amount.min' => $this->getAmountMinMessage($paymentMethod, $minAmountUSD, 'USD'),
                ]);
                
                // Convertir en XOF pour le stockage
                $amountInXOF = (int) round($request->amount * $xofToUsd);
            } else {
                // Validation en XOF
                $minAmount = 300; // 0.50 USD ≈ 300 XOF
                $request->validate([
                    'amount' => "required|numeric|min:{$minAmount}",
                ], [
                    'amount.min' => $this->getAmountMinMessage($paymentMethod, $minAmount, 'XOF'),
                ]);
                $amountInXOF = (int) $request->amount;
            }
        } elseif ($paymentMethod === 'wave') {
            $minAmount = 1000; // Minimum Wave
            $request->validate([
                'amount' => "required|numeric|min:{$minAmount}",
            ], [
                'amount.min' => $this->getAmountMinMessage($paymentMethod, $minAmount, 'XOF'),
            ]);
            $amountInXOF = (int) $request->amount;
        } else {
            // Autres méthodes en XOF
            $request->validate([
                'amount' => "required|numeric|min:{$minAmount}",
            ], [
                'amount.min' => $this->getAmountMinMessage($paymentMethod, $minAmount, 'XOF'),
            ]);
            $amountInXOF = (int) $request->amount;
        }
        
        // Utiliser $amountInXOF pour toutes les opérations suivantes

        $user = Auth::user();

        // Si Wave est sélectionné, générer le lien et rediriger
        if ($request->payment_method === 'wave') {
            // Valider le montant minimum pour Wave (1000 FCFA)

            // Créer le don en attente
            $donation = Donation::create([
                'user_id' => $user?->id,
                'donor_name' => $request->donor_name,
                'donor_email' => $request->donor_email,
                'amount' => $amountInXOF,
                'currency' => 'XOF',
                'status' => 'pending',
                'payment_method' => 'wave',
                'message' => $request->message,
                'is_anonymous' => $request->has('is_anonymous'),
                'show_on_wall' => !$request->has('is_anonymous'),
            ]);

            // Créer le paiement
            $payment = Payment::create([
                'user_id' => $user?->id,
                'paymentable_type' => Donation::class,
                'paymentable_id' => $donation->id,
                'amount' => $amountInXOF,
                'currency' => 'XOF',
                'status' => 'pending',
                'payment_method' => 'wave',
                'payment_gateway' => 'wave',
                'transaction_id' => 'DON-' . Str::upper(Str::random(12)),
                'payment_reference' => 'WAVE-' . Str::upper(Str::random(10)),
            ]);

            // Générer le lien Wave
            $waveLink = WavePaymentService::generateDonationLink(
                $amountInXOF,
                $request->donor_name,
                $request->message
            );

            // Sauvegarder le lien dans les détails du paiement
            $payment->update([
                'payment_details' => [
                    'wave_link' => $waveLink,
                    'donor_name' => $request->donor_name,
                    'donor_email' => $request->donor_email,
                ]
            ]);

            // Rediriger vers la page de confirmation avec le lien Wave
            return redirect()->route('payment.wave', $payment->id)
                ->with('wave_link', $waveLink)
                ->with('donation', $donation);
        }

        // Traitement PayPal
        if ($request->payment_method === 'paypal') {
            // Créer le don en attente
            $donation = Donation::create([
                'user_id' => $user?->id,
                'donor_name' => $request->donor_name,
                'donor_email' => $request->donor_email,
                'amount' => $amountInXOF,
                'currency' => 'XOF',
                'status' => 'pending',
                'payment_method' => 'paypal',
                'message' => $request->message,
                'is_anonymous' => $request->has('is_anonymous'),
                'show_on_wall' => !$request->has('is_anonymous'),
            ]);

            // Créer le paiement
            $payment = Payment::create([
                'user_id' => $user?->id,
                'paymentable_type' => Donation::class,
                'paymentable_id' => $donation->id,
                'amount' => $amountInXOF,
                'currency' => 'XOF',
                'status' => 'pending',
                'payment_method' => 'paypal',
                'payment_gateway' => 'paypal',
                'transaction_id' => 'DON-' . Str::upper(Str::random(12)),
                'payment_reference' => 'PAYPAL-' . Str::upper(Str::random(10)),
            ]);

            try {
                // Créer la commande PayPal
                $order = PayPalPaymentService::createOrder(
                    $amountInXOF,
                    'XOF',
                    'Donation NiangProgrammeur - ' . $request->donor_name
                );

                // Sauvegarder l'ID de commande
                $payment->update([
                    'payment_details' => [
                        'paypal_order_id' => $order['id'],
                        'donor_name' => $request->donor_name,
                        'donor_email' => $request->donor_email,
                    ]
                ]);

                // Rediriger vers PayPal
                $approvalUrl = collect($order['links'])->firstWhere('rel', 'approve')['href'];
                return redirect($approvalUrl);
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['payment_method' => 'Erreur lors de la création du paiement PayPal : ' . $e->getMessage()]);
            }
        }

        // Traitement Stripe
        if ($request->payment_method === 'stripe') {
            // Le montant minimum a déjà été validé dans la validation initiale
            
            // Créer le don en attente
            $donation = Donation::create([
                'user_id' => $user?->id,
                'donor_name' => $request->donor_name,
                'donor_email' => $request->donor_email,
                'amount' => $amountInXOF,
                'currency' => 'XOF',
                'status' => 'pending',
                'payment_method' => 'stripe',
                'message' => $request->message,
                'is_anonymous' => $request->has('is_anonymous'),
                'show_on_wall' => !$request->has('is_anonymous'),
            ]);

            // Créer le paiement
            $payment = Payment::create([
                'user_id' => $user?->id,
                'paymentable_type' => Donation::class,
                'paymentable_id' => $donation->id,
                'amount' => $amountInXOF,
                'currency' => 'XOF',
                'status' => 'pending',
                'payment_method' => 'stripe',
                'payment_gateway' => 'stripe',
                'transaction_id' => 'DON-' . Str::upper(Str::random(12)),
                'payment_reference' => 'STRIPE-' . Str::upper(Str::random(10)),
            ]);

            try {
                // Créer la session Stripe
                $session = StripePaymentService::createCheckoutSession(
                    $amountInXOF,
                    'XOF',
                    'Donation NiangProgrammeur - ' . $request->donor_name,
                    [
                        'payment_id' => $payment->id,
                        'donation_id' => $donation->id,
                    ]
                );

                // Sauvegarder l'ID de session
                $payment->update([
                    'payment_details' => [
                        'stripe_session_id' => $session['id'],
                        'donor_name' => $request->donor_name,
                        'donor_email' => $request->donor_email,
                    ]
                ]);

                // Rediriger vers Stripe
                return redirect($session['url']);
            } catch (\Exception $e) {
                // Extraire le message d'erreur
                $errorMessage = $e->getMessage();
                
                // Si c'est une erreur de montant minimum, l'afficher sur le champ amount
                if (str_contains($errorMessage, 'minimum') || str_contains($errorMessage, '0.50')) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['amount' => $errorMessage]);
                }
                
                // Sinon, afficher sur le champ payment_method
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['payment_method' => 'Erreur lors de la création du paiement Stripe : ' . $errorMessage]);
            }
        }

        // Pour les autres méthodes de paiement (mobile_money, bank_transfer)
        $donation = Donation::create([
            'user_id' => $user?->id,
            'donor_name' => $request->donor_name,
            'donor_email' => $request->donor_email,
            'amount' => $request->amount,
            'currency' => 'XOF',
            'status' => 'pending',
            'payment_method' => $request->payment_method,
            'message' => $request->message,
            'is_anonymous' => $request->has('is_anonymous'),
            'show_on_wall' => !$request->has('is_anonymous'),
        ]);

        // Créer le paiement
        $payment = Payment::create([
            'user_id' => $user?->id,
            'paymentable_type' => Donation::class,
            'paymentable_id' => $donation->id,
            'amount' => $request->amount,
            'currency' => 'XOF',
            'status' => 'pending',
            'payment_method' => $request->payment_method,
            'payment_gateway' => $request->payment_method,
            'transaction_id' => 'DON-' . Str::upper(Str::random(12)),
            'payment_reference' => 'REF-' . Str::upper(Str::random(10)),
        ]);

        return redirect()->route('payment.confirm', $payment->id)
            ->with('success', 'Merci pour votre don ! Veuillez compléter le paiement.');
    }

    /**
     * Rediriger vers Wave pour le paiement
     */
    public function waveRedirect(Request $request, $paymentId)
    {
        // Forcer la locale AVANT tout traitement pour la traduction
        $this->ensureLocale();
        
        $payment = Payment::findOrFail($paymentId);

        // Vérifier que le paiement appartient à l'utilisateur connecté (si connecté)
        if (Auth::check() && $payment->user_id && $payment->user_id !== Auth::id()) {
            abort(403);
        }

        $waveLink = $request->session()->get('wave_link');
        
        if (!$waveLink && $payment->payment_details) {
            $waveLink = $payment->payment_details['wave_link'] ?? null;
        }

        if (!$waveLink) {
            // Régénérer le lien si nécessaire
            $paymentable = $payment->paymentable;
            $description = 'Paiement NiangProgrammeur';
            
            if ($paymentable instanceof Donation) {
                $waveLink = WavePaymentService::generateDonationLink(
                    (float) $paymentable->amount,
                    $paymentable->donor_name,
                    $paymentable->message
                );
            } elseif ($paymentable instanceof CoursePurchase) {
                $description = 'Achat de cours: ' . $paymentable->course->title;
                $waveLink = WavePaymentService::generatePaymentLink(
                    (float) $payment->amount,
                    $payment->payment_reference,
                    $description
                );
            } elseif ($paymentable instanceof Subscription) {
                $description = 'Abonnement ' . ucfirst($paymentable->plan_type);
                $waveLink = WavePaymentService::generatePaymentLink(
                    (float) $payment->amount,
                    $payment->payment_reference,
                    $description
                );
            }
            
            // Sauvegarder le lien si généré
            if ($waveLink) {
                $payment->update([
                    'payment_details' => array_merge(
                        $payment->payment_details ?? [],
                        ['wave_link' => $waveLink]
                    )
                ]);
            }
        }

        return view('monetization.payment-wave', compact('payment', 'waveLink'));
    }

    /**
     * Confirmer un paiement (simulation - à remplacer par l'intégration réelle)
     */
    public function confirm(Request $request, $paymentId)
    {
        $payment = Payment::findOrFail($paymentId);

        // Vérifier que le paiement appartient à l'utilisateur connecté
        if (Auth::check() && $payment->user_id !== Auth::id()) {
            abort(403);
        }

        return view('monetization.payment-confirm', compact('payment'));
    }

    /**
     * Mettre à jour la méthode de paiement
     */
    public function updatePaymentMethod(Request $request, $paymentId)
    {
        $payment = Payment::findOrFail($paymentId);

        // Vérifier que le paiement appartient à l'utilisateur connecté
        if (Auth::check() && $payment->user_id !== Auth::id()) {
            abort(403);
        }

        // Vérifier que le paiement est en attente
        if ($payment->status !== 'pending') {
            return redirect()->route('payment.confirm', $payment->id)
                ->with('error', 'Ce paiement ne peut plus être modifié.');
        }

        $request->validate([
            'payment_method' => 'required|in:mobile_money,bank_transfer,wave,stripe,paypal',
        ]);

        // Mettre à jour la méthode de paiement
        $payment->update([
            'payment_method' => $request->payment_method,
            'payment_gateway' => $request->payment_method,
        ]);

        // Mettre à jour le paymentable si c'est un CoursePurchase
        if ($payment->paymentable_type === CoursePurchase::class) {
            $payment->paymentable->update([
                'payment_method' => $request->payment_method,
            ]);
        }

        // Si c'est PayPal ou Stripe, traiter directement
        if ($request->payment_method === 'paypal') {
            return $this->processPayPalPayment($payment);
        } elseif ($request->payment_method === 'stripe') {
            return $this->processStripePayment($payment);
        } elseif ($request->payment_method === 'wave') {
            return $this->processWavePayment($payment);
        }

        return redirect()->route('payment.confirm', $payment->id)
            ->with('success', 'Méthode de paiement mise à jour.');
    }

    /**
     * Traiter un paiement PayPal
     */
    private function processPayPalPayment(Payment $payment)
    {
        try {
            $paymentable = $payment->paymentable;
            $description = 'Paiement NiangProgrammeur';
            
            if ($paymentable instanceof CoursePurchase) {
                $description = 'Achat de cours: ' . $paymentable->course->title;
            } elseif ($paymentable instanceof Subscription) {
                $description = 'Abonnement ' . ucfirst($paymentable->plan_type);
            } elseif ($paymentable instanceof Donation) {
                $description = 'Donation NiangProgrammeur - ' . $paymentable->donor_name;
            }

            // Créer la commande PayPal
            $order = PayPalPaymentService::createOrder(
                $payment->amount,
                $payment->currency,
                $description
            );

            // Sauvegarder l'ID de commande
            $payment->update([
                'payment_details' => [
                    'paypal_order_id' => $order['id'],
                ]
            ]);

            // Rediriger vers PayPal
            $approvalUrl = collect($order['links'])->firstWhere('rel', 'approve')['href'];
            return redirect($approvalUrl);
        } catch (\Exception $e) {
            return redirect()->route('payment.confirm', $payment->id)
                ->with('error', 'Erreur lors de la création du paiement PayPal : ' . $e->getMessage());
        }
    }

    /**
     * Traiter un paiement Stripe
     */
    private function processStripePayment(Payment $payment)
    {
        try {
            $paymentable = $payment->paymentable;
            $description = 'Paiement NiangProgrammeur';
            
            if ($paymentable instanceof CoursePurchase) {
                $description = 'Achat de cours: ' . $paymentable->course->title;
            } elseif ($paymentable instanceof Subscription) {
                $description = 'Abonnement ' . ucfirst($paymentable->plan_type);
            } elseif ($paymentable instanceof Donation) {
                $description = 'Donation NiangProgrammeur - ' . $paymentable->donor_name;
            }

            // Créer la session Stripe
            $session = StripePaymentService::createCheckoutSession(
                $payment->amount,
                $payment->currency,
                $description,
                [
                    'payment_id' => $payment->id,
                ]
            );

            // Sauvegarder l'ID de session
            $payment->update([
                'payment_details' => [
                    'stripe_session_id' => $session['id'],
                ]
            ]);

            // Rediriger vers Stripe
            return redirect($session['url']);
        } catch (\Exception $e) {
            return redirect()->route('payment.confirm', $payment->id)
                ->with('error', 'Erreur lors de la création du paiement Stripe : ' . $e->getMessage());
        }
    }

    /**
     * Traiter un paiement Wave
     */
    private function processWavePayment(Payment $payment)
    {
        $paymentable = $payment->paymentable;
        $description = 'Paiement NiangProgrammeur';
        
        if ($paymentable instanceof CoursePurchase) {
            $description = 'Achat de cours: ' . $paymentable->course->title;
        } elseif ($paymentable instanceof Subscription) {
            $description = 'Abonnement ' . ucfirst($paymentable->plan_type);
        } elseif ($paymentable instanceof Donation) {
            $description = 'Donation NiangProgrammeur';
        }

        // Générer le lien Wave
        $waveLink = WavePaymentService::generatePaymentLink(
            $payment->amount,
            $payment->payment_reference,
            $description
        );

        // Sauvegarder le lien
        $payment->update([
            'payment_details' => [
                'wave_link' => $waveLink,
            ]
        ]);

        return redirect()->route('payment.wave', $payment->id);
    }

    /**
     * Retour après paiement PayPal réussi
     */
    public function paypalReturn(Request $request)
    {
        $token = $request->query('token');
        $payerId = $request->query('PayerID');

        if (!$token) {
            return redirect()->route('monetization.donations')
                ->with('error', 'Erreur lors du retour PayPal');
        }

        // Trouver le paiement par l'ID de commande PayPal
        $payments = Payment::where('payment_method', 'paypal')
            ->where('status', 'pending')
            ->whereNotNull('payment_details')
            ->get();

        $payment = null;
        foreach ($payments as $p) {
            $details = is_string($p->payment_details) ? json_decode($p->payment_details, true) : $p->payment_details;
            if (isset($details['paypal_order_id']) && $details['paypal_order_id'] === $token) {
                $payment = $p;
                break;
            }
        }

        if (!$payment) {
            return redirect()->route('monetization.donations')
                ->with('error', 'Paiement introuvable');
        }

        try {
            // Récupérer l'ID de commande depuis le token
            // Le token est en fait l'ID de commande PayPal
            $orderId = $token;
            
            // Capturer le paiement PayPal
            $result = PayPalPaymentService::captureOrder($orderId);

            if (isset($result['status']) && $result['status'] === 'COMPLETED') {
                // Mettre à jour le paiement
                $payment->update([
                    'status' => 'completed',
                    'paid_at' => now(),
                    'transaction_id' => $result['id'] ?? $payment->transaction_id,
                ]);

                // Mettre à jour la donation
                $this->updatePaymentable($payment);

                return redirect()->route('payment.success', $payment->id)
                    ->with('success', 'Votre don a été effectué avec succès ! Merci pour votre générosité.');
            }
        } catch (\Exception $e) {
            return redirect()->route('monetization.donations')
                ->with('error', 'Erreur lors de la confirmation du paiement : ' . $e->getMessage());
        }

        return redirect()->route('monetization.donations')
            ->with('error', 'Le paiement n\'a pas pu être confirmé');
    }

    /**
     * Annulation du paiement PayPal
     */
    public function paypalCancel(Request $request)
    {
        return redirect()->route('monetization.donations')
            ->with('info', 'Le paiement PayPal a été annulé');
    }

    /**
     * Retour après paiement Stripe réussi
     */
    public function stripeSuccess(Request $request)
    {
        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            return redirect()->route('monetization.donations')
                ->with('error', 'Erreur lors du retour Stripe');
        }

        try {
            // Récupérer la session Stripe
            $session = StripePaymentService::retrieveSession($sessionId);

            // Trouver le paiement par l'ID de session
            $payments = Payment::where('payment_method', 'stripe')
                ->where('status', 'pending')
                ->whereNotNull('payment_details')
                ->get();

            $payment = null;
            foreach ($payments as $p) {
                $details = is_string($p->payment_details) ? json_decode($p->payment_details, true) : $p->payment_details;
                if (isset($details['stripe_session_id']) && $details['stripe_session_id'] === $sessionId) {
                    $payment = $p;
                    break;
                }
            }

            if (!$payment) {
                return redirect()->route('monetization.donations')
                    ->with('error', 'Paiement introuvable');
            }

            if ($session['payment_status'] === 'paid') {
                // Mettre à jour le paiement
                $payment->update([
                    'status' => 'completed',
                    'paid_at' => now(),
                    'transaction_id' => $session['payment_intent'] ?? $payment->transaction_id,
                ]);

                // Mettre à jour la donation
                $this->updatePaymentable($payment);

                return redirect()->route('payment.success', $payment->id)
                    ->with('success', 'Votre don a été effectué avec succès ! Merci pour votre générosité.');
            }
        } catch (\Exception $e) {
            return redirect()->route('monetization.donations')
                ->with('error', 'Erreur lors de la confirmation du paiement : ' . $e->getMessage());
        }

        return redirect()->route('monetization.donations')
            ->with('error', 'Le paiement n\'a pas pu être confirmé');
    }

    /**
     * Annulation du paiement Stripe
     */
    public function stripeCancel(Request $request)
    {
        return redirect()->route('monetization.donations')
            ->with('info', 'Le paiement Stripe a été annulé');
    }

    /**
     * Page de succès après paiement
     */
    public function paymentSuccess($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        $donation = $payment->paymentable;

        return view('monetization.payment-success', compact('payment', 'donation'));
    }

    /**
     * Webhook pour confirmer un paiement (à appeler par le système de paiement)
     */
    public function webhook(Request $request)
    {
        // TODO: Implémenter la logique de webhook selon le système de paiement
        // Pour l'instant, simulation manuelle
        
        $request->validate([
            'payment_id' => 'required|exists:payments,id',
            'status' => 'required|in:completed,failed',
            'transaction_id' => 'nullable|string',
        ]);

        $payment = Payment::findOrFail($request->payment_id);
        
        if ($request->status === 'completed') {
            $payment->update([
                'status' => 'completed',
                'paid_at' => now(),
                'transaction_id' => $request->transaction_id ?? $payment->transaction_id,
            ]);

            // Mettre à jour l'élément payé
            $this->updatePaymentable($payment);
        } else {
            $payment->update([
                'status' => 'failed',
                'failure_reason' => $request->get('reason', 'Paiement échoué'),
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Mettre à jour l'élément payé après confirmation du paiement
     */
    private function updatePaymentable(Payment $payment)
    {
        $paymentable = $payment->paymentable;

        if ($paymentable instanceof Subscription) {
            $paymentable->update(['status' => 'active']);
            $paymentable->user->update([
                'is_premium' => true,
                'premium_until' => $paymentable->end_date,
                'current_subscription_id' => $paymentable->id,
            ]);
        } elseif ($paymentable instanceof CoursePurchase) {
            $paymentable->update([
                'status' => 'completed',
                'purchased_at' => now(),
            ]);
            $paymentable->course->increment('students_count');
        } elseif ($paymentable instanceof Donation) {
            $paymentable->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
        }
    }

    /**
     * Obtenir le message d'erreur pour le montant minimum selon la méthode de paiement
     */
    private function getAmountMinMessage(string $paymentMethod, int $minAmount): string
    {
        $xofToUsd = 600; // 1 USD ≈ 600 XOF
        
        switch ($paymentMethod) {
            case 'stripe':
                $minUsd = number_format($minAmount / $xofToUsd, 2, '.', '');
                return "Le montant minimum pour Stripe est de {$minUsd} dollar (équivalent à {$minAmount} FCFA)";
            
            case 'paypal':
                $minUsd = number_format($minAmount / $xofToUsd, 2, '.', '');
                return "Le montant minimum pour PayPal est de {$minUsd} dollar (équivalent à {$minAmount} FCFA)";
            
            case 'wave':
                return "Le montant minimum pour Wave est de {$minAmount} FCFA";
            
            default:
                return "Le montant minimum est de {$minAmount} FCFA";
        }
    }

    /**
     * Gérer l'affiliation pour un paiement
     */
    private function handleAffiliateReferral(string $refCode, $paymentable, float $amount)
    {
        $affiliate = Affiliate::where('affiliate_code', $refCode)
            ->where('status', 'active')
            ->first();

        if (!$affiliate) {
            return;
        }

        $commission = ($amount * $affiliate->commission_rate) / 100;

        $referralType = match (true) {
            $paymentable instanceof Subscription => 'subscription',
            $paymentable instanceof CoursePurchase => 'course_purchase',
            $paymentable instanceof Donation => 'donation',
            default => 'other',
        };

        AffiliateReferral::create([
            'affiliate_id' => $affiliate->id,
            'user_id' => $paymentable->user_id ?? null,
            'referral_type' => $referralType,
            'referral_id' => $paymentable->id,
            'amount' => $amount,
            'commission' => $commission,
            'status' => 'pending',
        ]);

        // Mettre à jour les statistiques de l'affilié
        $affiliate->increment('total_earnings', $commission);
        $affiliate->increment('pending_earnings', $commission);
    }
}

