@extends('layouts.app')

@section('title', 'Confirmation de Paiement - NiangProgrammeur')

@section('content')
<div class="payment-confirm-page">
            @if($payment->status === 'pending')
    <!-- Paiement en Attente -->
    <div class="payment-container">
        <!-- Header -->
        <div class="payment-header">
            <div class="payment-status-icon pending">
                <i class="fas fa-clock"></i>
            </div>
            <h1 class="payment-title">Paiement en Attente</h1>
            <p class="payment-subtitle">Choisissez votre moyen de paiement pour finaliser votre transaction</p>
            </div>

        <!-- Détails du Paiement -->
        <div class="payment-details-card">
            <h3 class="details-title">
                <i class="fas fa-receipt"></i>
                Détails de la transaction
                </h3>
            <div class="details-grid">
                <div class="detail-item">
                    <span class="detail-label">Type</span>
                    <span class="detail-value">
                            @if($payment->paymentable_type === 'App\Models\Subscription')
                            <i class="fas fa-crown"></i> Abonnement
                            @elseif($payment->paymentable_type === 'App\Models\CoursePurchase')
                            <i class="fas fa-graduation-cap"></i> Cours payant
                            @elseif($payment->paymentable_type === 'App\Models\Donation')
                            <i class="fas fa-heart"></i> Don
                            @endif
                        </span>
                    </div>
                <div class="detail-item">
                    <span class="detail-label">Montant</span>
                    <span class="detail-value amount">{{ number_format($payment->amount, 0, ',', ' ') }} {{ $payment->currency }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Référence</span>
                    <span class="detail-value reference">{{ $payment->payment_reference }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Statut</span>
                    <span class="detail-value status-badge pending">En attente</span>
                </div>
            </div>
                    </div>
                    
        <!-- Méthodes de Paiement -->
        <div class="payment-methods-section">
            <h3 class="methods-title">
                <i class="fas fa-credit-card"></i>
                Choisissez votre moyen de paiement
            </h3>
            
            <form method="POST" action="{{ route('payment.update-method', $payment->id) }}" id="paymentMethodForm" class="payment-methods-grid">
                @csrf
                @method('PUT')
                
                <!-- Mobile Money -->
                <label class="payment-method-card">
                    <input type="radio" name="payment_method" value="mobile_money" {{ $payment->payment_method === 'mobile_money' ? 'checked' : '' }} class="method-radio">
                    <div class="method-content">
                        <div class="method-icon mobile-money">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <div class="method-info">
                            <h4 class="method-name">Mobile Money</h4>
                            <p class="method-description">Orange Money, Free Money, MTN Mobile Money</p>
                        </div>
                        <div class="method-check">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </label>

                <!-- Virement Bancaire -->
                <label class="payment-method-card">
                    <input type="radio" name="payment_method" value="bank_transfer" {{ $payment->payment_method === 'bank_transfer' ? 'checked' : '' }} class="method-radio">
                    <div class="method-content">
                        <div class="method-icon bank-transfer">
                            <i class="fas fa-university"></i>
                        </div>
                        <div class="method-info">
                            <h4 class="method-name">Virement Bancaire</h4>
                            <p class="method-description">Transfert bancaire classique</p>
                        </div>
                        <div class="method-check">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </label>

                <!-- Wave -->
                @if(\App\Models\SiteSetting::get('wave_enabled', true))
                <label class="payment-method-card">
                    <input type="radio" name="payment_method" value="wave" {{ $payment->payment_method === 'wave' ? 'checked' : '' }} class="method-radio">
                    <div class="method-content">
                        <div class="method-icon wave">
                            <i class="fas fa-wave-square"></i>
                        </div>
                        <div class="method-info">
                            <h4 class="method-name">Wave</h4>
                            <p class="method-description">Paiement mobile sécurisé</p>
                        </div>
                        <div class="method-check">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </label>
                            @endif

                <!-- PayPal -->
                @if(\App\Models\SiteSetting::get('paypal_enabled', false))
                <label class="payment-method-card">
                    <input type="radio" name="payment_method" value="paypal" {{ $payment->payment_method === 'paypal' ? 'checked' : '' }} class="method-radio">
                    <div class="method-content">
                        <div class="method-icon paypal">
                            <i class="fab fa-paypal"></i>
                        </div>
                        <div class="method-info">
                            <h4 class="method-name">PayPal</h4>
                            <p class="method-description">Carte bancaire ou compte PayPal</p>
                        </div>
                        <div class="method-check">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </label>
                @endif

                <!-- Stripe -->
                @if(\App\Models\SiteSetting::get('stripe_enabled', false))
                <label class="payment-method-card">
                    <input type="radio" name="payment_method" value="stripe" {{ $payment->payment_method === 'stripe' ? 'checked' : '' }} class="method-radio">
                    <div class="method-content">
                        <div class="method-icon stripe">
                            <i class="fab fa-stripe"></i>
                        </div>
                        <div class="method-info">
                            <h4 class="method-name">Carte Bancaire</h4>
                            <p class="method-description">Visa, Mastercard, Amex</p>
                        </div>
                        <div class="method-check">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </label>
                @endif
            </form>

            <!-- Instructions selon la méthode -->
            <div class="payment-instructions" id="paymentInstructions">
                @if($payment->payment_method === 'mobile_money')
                <div class="instructions-content">
                    <div class="instructions-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <div class="instructions-text">
                        <h4>Instructions Mobile Money</h4>
                        <p>Effectuez le paiement via votre application Mobile Money en utilisant la référence <strong>{{ $payment->payment_reference }}</strong>. Le paiement sera validé automatiquement une fois reçu.</p>
                        <div class="instructions-actions">
                            <button type="button" onclick="confirmPayment()" class="confirm-payment-btn">
                                <i class="fas fa-check"></i>
                                Confirmer le paiement
                            </button>
                    </div>
                </div>
            </div>
                            @elseif($payment->payment_method === 'bank_transfer')
                <div class="instructions-content">
                    <div class="instructions-icon">
                        <i class="fas fa-university"></i>
                    </div>
                    <div class="instructions-text">
                        <h4>Instructions Virement Bancaire</h4>
                        <p>Effectuez un virement bancaire en utilisant la référence <strong>{{ $payment->payment_reference }}</strong> comme motif de virement. Le paiement sera validé sous 24-48h.</p>
                        <div class="bank-details">
                            <p><strong>Banque:</strong> À configurer</p>
                            <p><strong>RIB:</strong> À configurer</p>
                            <p><strong>Référence:</strong> {{ $payment->payment_reference }}</p>
                        </div>
                        <div class="instructions-actions">
                            <button type="button" onclick="confirmPayment()" class="confirm-payment-btn">
                                <i class="fas fa-check"></i>
                                J'ai effectué le virement
                            </button>
                        </div>
                    </div>
                </div>
                @elseif($payment->payment_method === 'wave')
                <div class="instructions-content">
                    <div class="instructions-icon">
                        <i class="fas fa-wave-square"></i>
                    </div>
                    <div class="instructions-text">
                        <h4>Paiement via Wave</h4>
                        <p>Vous allez être redirigé vers Wave pour effectuer le paiement de manière sécurisée.</p>
                        <div class="instructions-actions">
                            <a href="{{ route('payment.wave', $payment->id) }}" class="confirm-payment-btn wave-btn">
                                <i class="fas fa-external-link-alt"></i>
                                Payer avec Wave
                            </a>
                        </div>
                    </div>
                </div>
                @elseif($payment->payment_method === 'paypal')
                <div class="instructions-content">
                    <div class="instructions-icon">
                        <i class="fab fa-paypal"></i>
                    </div>
                    <div class="instructions-text">
                        <h4>Paiement via PayPal</h4>
                        <p>Vous allez être redirigé vers PayPal pour effectuer le paiement de manière sécurisée.</p>
                        <div class="instructions-actions">
                            <button type="button" onclick="processPayPal()" class="confirm-payment-btn paypal-btn">
                                <i class="fab fa-paypal"></i>
                                Payer avec PayPal
                            </button>
                        </div>
                    </div>
                </div>
                @elseif($payment->payment_method === 'stripe')
                <div class="instructions-content">
                    <div class="instructions-icon">
                        <i class="fab fa-stripe"></i>
                    </div>
                    <div class="instructions-text">
                        <h4>Paiement par Carte Bancaire</h4>
                        <p>Vous allez être redirigé vers Stripe pour effectuer le paiement de manière sécurisée.</p>
                        <div class="instructions-actions">
                            <button type="button" onclick="processStripe()" class="confirm-payment-btn stripe-btn">
                                <i class="fab fa-stripe"></i>
                                Payer avec Stripe
                            </button>
                    </div>
                </div>
                </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="payment-actions">
                <a href="{{ route('monetization.index') }}" class="action-btn secondary">
                    <i class="fas fa-arrow-left"></i>
                    Retour
                </a>
                @auth
                <a href="{{ route('dashboard.overview') }}" class="action-btn secondary">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
                @endauth
            </div>
        </div>
            </div>

            @elseif($payment->status === 'completed')
    <!-- Paiement Confirmé -->
    <div class="payment-container">
        <div class="payment-header">
            <div class="payment-status-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1 class="payment-title">Paiement Confirmé !</h1>
            <p class="payment-subtitle">Votre paiement a été confirmé avec succès</p>
            </div>

        <div class="payment-success-card">
            <div class="success-details">
                <div class="success-item">
                    <span class="success-label">Montant payé</span>
                    <span class="success-value">{{ number_format($payment->amount, 0, ',', ' ') }} {{ $payment->currency }}</span>
                </div>
                <div class="success-item">
                    <span class="success-label">Référence</span>
                    <span class="success-value reference">{{ $payment->payment_reference }}</span>
                </div>
            </div>

            <div class="success-actions">
                @if($payment->paymentable_type === 'App\Models\Subscription')
                <a href="{{ route('dashboard.overview') }}" class="success-btn">
                    <i class="fas fa-crown"></i>
                    Accéder au contenu Premium
                </a>
                @elseif($payment->paymentable_type === 'App\Models\CoursePurchase')
                <a href="{{ route('monetization.course.show', $payment->paymentable->course->slug) }}" class="success-btn">
                    <i class="fas fa-play"></i>
                    Accéder au cours
                </a>
                @endif
                <a href="{{ route('monetization.index') }}" class="success-btn secondary">
                    Retour à la monétisation
                </a>
            </div>
        </div>
            </div>

            @else
    <!-- Paiement Échoué -->
    <div class="payment-container">
        <div class="payment-header">
            <div class="payment-status-icon failed">
                <i class="fas fa-times-circle"></i>
            </div>
            <h1 class="payment-title">Paiement Échoué</h1>
            <p class="payment-subtitle">Votre paiement n'a pas pu être traité</p>
            </div>

            @if($payment->failure_reason)
        <div class="payment-error-card">
            <div class="error-content">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                    <h4>Raison de l'échec</h4>
                    <p>{{ $payment->failure_reason }}</p>
                </div>
            </div>
            </div>
            @endif

        <div class="payment-actions">
            <a href="{{ route('monetization.index') }}" class="action-btn primary">
                <i class="fas fa-redo"></i>
                    Réessayer
                </a>
            <a href="{{ route('contact') }}" class="action-btn secondary">
                <i class="fas fa-headset"></i>
                    Contacter le support
                </a>
        </div>
            </div>
            @endif
        </div>

<!-- Styles -->
<style>
    /* ============================================
       BASE
       ============================================ */
    .payment-confirm-page {
        min-height: 100vh;
        background: white;
        padding: 40px 20px;
        position: relative;
        overflow-x: hidden;
    }

    .payment-confirm-page::before {
        content: '';
        position: fixed;
        inset: 0;
        background: 
            radial-gradient(circle at 20% 30%, rgba(6, 182, 212, 0.05) 0%, transparent 50%),
            radial-gradient(circle at 80% 70%, rgba(20, 184, 166, 0.05) 0%, transparent 50%);
        pointer-events: none;
        z-index: 0;
        opacity: 0.5;
    }

    .payment-container {
        max-width: 900px;
        margin: 0 auto;
        position: relative;
        z-index: 1;
    }

    /* ============================================
       HEADER
       ============================================ */
    .payment-header {
        text-align: center;
        margin-bottom: 40px;
        animation: fadeInDown 0.6s ease;
    }

    .payment-status-icon {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 30px;
        font-size: 3rem;
        position: relative;
    }

    .payment-status-icon.pending {
        background: linear-gradient(135deg, rgba(251, 191, 36, 0.2), rgba(245, 158, 11, 0.2));
        color: #fbbf24;
        border: 3px solid #fbbf24;
        animation: pulse 2s ease infinite;
    }

    .payment-status-icon.success {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(5, 150, 105, 0.2));
        color: #10b981;
        border: 3px solid #10b981;
    }

    .payment-status-icon.failed {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(220, 38, 38, 0.2));
        color: #ef4444;
        border: 3px solid #ef4444;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .payment-title {
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 900;
        color: rgba(30, 41, 59, 0.95);
        margin-bottom: 15px;
    }

    .payment-subtitle {
        font-size: 1.2rem;
        color: rgba(30, 41, 59, 0.7);
    }

    /* ============================================
       DETAILS CARD
       ============================================ */
    .payment-details-card {
        background: white;
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 24px;
        padding: 35px;
        margin-bottom: 40px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        animation: fadeInUp 0.8s ease;
    }

    .details-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: rgba(30, 41, 59, 0.95);
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .details-title i {
        color: #06b6d4;
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 8px;
        padding: 20px;
        background: #f8fafc;
        border-radius: 12px;
        border: 1px solid rgba(6, 182, 212, 0.2);
    }

    .detail-label {
        font-size: 0.9rem;
        color: rgba(30, 41, 59, 0.6);
        font-weight: 600;
    }

    .detail-value {
        font-size: 1.1rem;
        color: rgba(30, 41, 59, 0.95);
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .detail-value.amount {
        font-size: 1.5rem;
        color: #06b6d4;
    }

    .detail-value.reference {
        font-family: monospace;
        font-size: 1rem;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 700;
    }

    .status-badge.pending {
        background: rgba(251, 191, 36, 0.2);
        color: #fbbf24;
        border: 1px solid #fbbf24;
    }

    /* ============================================
       PAYMENT METHODS
       ============================================ */
    .payment-methods-section {
        background: white;
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 24px;
        padding: 35px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        animation: fadeInUp 1s ease;
    }

    .methods-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: rgba(30, 41, 59, 0.95);
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .methods-title i {
        color: #06b6d4;
    }

    .payment-methods-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .payment-method-card {
        position: relative;
        cursor: pointer;
    }

    .method-radio {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .method-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
        padding: 30px 20px;
        background: #f8fafc;
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 16px;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
    }

    .method-content::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.1));
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .method-radio:checked + .method-content {
        border-color: #06b6d4;
        background: rgba(6, 182, 212, 0.15);
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(6, 182, 212, 0.3);
    }

    .method-radio:checked + .method-content::before {
        opacity: 1;
    }

    .method-radio:checked + .method-content .method-check {
        opacity: 1;
        transform: scale(1);
    }

    .method-icon {
        width: 70px;
        height: 70px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: white;
    }

    .method-icon.mobile-money {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .method-icon.bank-transfer {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
    }

    .method-icon.wave {
        background: linear-gradient(135deg, #06b6d4, #0891b2);
    }

    .method-icon.paypal {
        background: linear-gradient(135deg, #003087, #009cde);
    }

    .method-icon.stripe {
        background: linear-gradient(135deg, #635bff, #7c3aed);
    }

    .method-info {
        text-align: center;
    }

    .method-name {
        font-size: 1.1rem;
        font-weight: 700;
        color: rgba(30, 41, 59, 0.95);
        margin-bottom: 5px;
    }

    .method-description {
        font-size: 0.85rem;
        color: rgba(30, 41, 59, 0.6);
    }

    .method-check {
        position: absolute;
        top: 15px;
        right: 15px;
        width: 30px;
        height: 30px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        opacity: 0;
        transform: scale(0);
        transition: all 0.3s ease;
    }

    /* ============================================
       INSTRUCTIONS
       ============================================ */
    .payment-instructions {
        margin-top: 40px;
        animation: fadeInUp 1.2s ease;
    }

    .instructions-content {
        display: flex;
        gap: 20px;
        padding: 30px;
        background: rgba(6, 182, 212, 0.05);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 16px;
    }

    .instructions-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: #06b6d4;
        flex-shrink: 0;
    }

    .instructions-text {
        flex: 1;
    }

    .instructions-text h4 {
        font-size: 1.3rem;
        font-weight: 800;
        color: rgba(30, 41, 59, 0.95);
        margin-bottom: 12px;
    }

    .instructions-text p {
        color: rgba(30, 41, 59, 0.8);
        line-height: 1.7;
        margin-bottom: 20px;
    }

    .bank-details {
        background: #f8fafc;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        border: 1px solid rgba(6, 182, 212, 0.2);
    }

    .bank-details p {
        margin: 8px 0;
        color: rgba(30, 41, 59, 0.9);
    }

    .instructions-actions {
        margin-top: 20px;
    }

    .confirm-payment-btn {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        padding: 16px 32px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border: none;
        border-radius: 12px;
        color: white;
        font-size: 1.1rem;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(6, 182, 212, 0.4);
    }

    .confirm-payment-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(6, 182, 212, 0.6);
    }

    .confirm-payment-btn.wave-btn {
        background: linear-gradient(135deg, #06b6d4, #0891b2);
    }

    .confirm-payment-btn.paypal-btn {
        background: linear-gradient(135deg, #003087, #009cde);
    }

    .confirm-payment-btn.stripe-btn {
        background: linear-gradient(135deg, #635bff, #7c3aed);
    }

    /* ============================================
       ACTIONS
       ============================================ */
    .payment-actions {
        display: flex;
        gap: 15px;
        justify-content: center;
        flex-wrap: wrap;
        margin-top: 40px;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 28px;
        border-radius: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .action-btn.primary {
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: white;
        box-shadow: 0 8px 25px rgba(6, 182, 212, 0.4);
    }

    .action-btn.primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(6, 182, 212, 0.6);
    }

    .action-btn.secondary {
        background: rgba(6, 182, 212, 0.2);
        color: #06b6d4;
        border: 2px solid #06b6d4;
    }

    .action-btn.secondary:hover {
        background: rgba(6, 182, 212, 0.3);
    }

    /* ============================================
       SUCCESS CARD
       ============================================ */
    .payment-success-card {
        background: white;
        border: 2px solid rgba(16, 185, 129, 0.2);
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        animation: fadeInUp 0.8s ease;
    }

    .success-details {
        display: grid;
        gap: 20px;
        margin-bottom: 30px;
    }

    .success-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        background: #f8fafc;
        border-radius: 12px;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .success-label {
        color: rgba(30, 41, 59, 0.7);
        font-weight: 600;
    }

    .success-value {
        color: rgba(30, 41, 59, 0.95);
        font-weight: 700;
        font-size: 1.2rem;
    }

    .success-actions {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .success-btn {
        flex: 1;
        min-width: 200px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 16px 32px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border-radius: 12px;
        color: white;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(6, 182, 212, 0.4);
    }

    .success-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(6, 182, 212, 0.6);
    }

    .success-btn.secondary {
        background: rgba(6, 182, 212, 0.2);
        border: 2px solid #06b6d4;
        box-shadow: none;
    }

    /* ============================================
       ERROR CARD
       ============================================ */
    .payment-error-card {
        background: rgba(239, 68, 68, 0.05);
        border: 2px solid rgba(239, 68, 68, 0.2);
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 30px;
        animation: fadeInUp 0.8s ease;
    }

    .error-content {
        display: flex;
        gap: 15px;
        align-items: start;
    }

    .error-content i {
        font-size: 2rem;
        color: #ef4444;
        flex-shrink: 0;
    }

    .error-content h4 {
        color: rgba(30, 41, 59, 0.95);
        font-weight: 700;
        margin-bottom: 8px;
    }

    .error-content p {
        color: rgba(30, 41, 59, 0.8);
    }

    /* ============================================
       ANIMATIONS
       ============================================ */
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ============================================
       RESPONSIVE
       ============================================ */
    @media (max-width: 768px) {
        .payment-methods-grid {
            grid-template-columns: 1fr;
        }

        .details-grid {
            grid-template-columns: 1fr;
        }

        .instructions-content {
            flex-direction: column;
        }

        .payment-actions {
            flex-direction: column;
        }

        .action-btn,
        .success-btn {
            width: 100%;
        }
    }

    /* ============================================
       DARK MODE
       ============================================ */
    body.dark-mode .payment-confirm-page {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%) !important;
    }

    body.dark-mode .payment-confirm-page::before {
        opacity: 1;
        background: 
            radial-gradient(circle at 20% 30%, rgba(6, 182, 212, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 70%, rgba(20, 184, 166, 0.1) 0%, transparent 50%);
    }

    body.dark-mode .payment-title {
        color: white !important;
    }

    body.dark-mode .payment-subtitle {
        color: rgba(255, 255, 255, 0.7) !important;
    }

    body.dark-mode .payment-details-card,
    body.dark-mode .payment-methods-section,
    body.dark-mode .payment-success-card {
        background: rgba(30, 41, 59, 0.8) !important;
        border-color: rgba(6, 182, 212, 0.3) !important;
        backdrop-filter: blur(20px);
    }

    body.dark-mode .detail-item {
        background: rgba(15, 23, 42, 0.6) !important;
        border-color: rgba(6, 182, 212, 0.2) !important;
    }

    body.dark-mode .detail-label {
        color: rgba(255, 255, 255, 0.6) !important;
    }

    body.dark-mode .detail-value {
        color: white !important;
    }

    body.dark-mode .details-title {
        color: white !important;
    }

    body.dark-mode .methods-title {
        color: white !important;
    }

    body.dark-mode .method-content {
        background: rgba(15, 23, 42, 0.6) !important;
        border-color: rgba(6, 182, 212, 0.3) !important;
    }

    body.dark-mode .method-name {
        color: white !important;
    }

    body.dark-mode .method-description {
        color: rgba(255, 255, 255, 0.6) !important;
    }

    body.dark-mode .instructions-content {
        background: rgba(6, 182, 212, 0.1) !important;
        border-color: rgba(6, 182, 212, 0.3) !important;
    }

    body.dark-mode .instructions-text h4 {
        color: white !important;
    }

    body.dark-mode .instructions-text p {
        color: rgba(255, 255, 255, 0.8) !important;
    }

    body.dark-mode .bank-details {
        background: rgba(15, 23, 42, 0.6) !important;
        border-color: rgba(6, 182, 212, 0.2) !important;
    }

    body.dark-mode .bank-details p {
        color: rgba(255, 255, 255, 0.9) !important;
    }

    body.dark-mode .success-item {
        background: rgba(15, 23, 42, 0.6) !important;
        border-color: rgba(16, 185, 129, 0.2) !important;
    }

    body.dark-mode .success-label {
        color: rgba(255, 255, 255, 0.7) !important;
    }

    body.dark-mode .success-value {
        color: white !important;
    }

    body.dark-mode .payment-error-card {
        background: rgba(239, 68, 68, 0.1) !important;
        border-color: rgba(239, 68, 68, 0.3) !important;
    }

    body.dark-mode .error-content h4 {
        color: white !important;
    }

    body.dark-mode .error-content p {
        color: rgba(255, 255, 255, 0.8) !important;
    }

    /* ============================================
       LIGHT MODE
       ============================================ */
    body.light-mode .payment-confirm-page {
        background: white !important;
    }

    body.light-mode .payment-confirm-page::before {
        opacity: 0.5;
    }

    body.light-mode .payment-title {
        color: rgba(30, 41, 59, 0.95) !important;
    }

    body.light-mode .payment-subtitle {
        color: rgba(30, 41, 59, 0.7) !important;
    }

    body.light-mode .payment-details-card,
    body.light-mode .payment-methods-section,
    body.light-mode .payment-success-card {
        background: white !important;
        border-color: rgba(6, 182, 212, 0.2) !important;
    }

    body.light-mode .detail-item {
        background: #f8fafc !important;
        border-color: rgba(6, 182, 212, 0.2) !important;
    }

    body.light-mode .detail-label {
        color: rgba(30, 41, 59, 0.6) !important;
    }

    body.light-mode .detail-value {
        color: rgba(30, 41, 59, 0.95) !important;
    }

    body.light-mode .details-title {
        color: rgba(30, 41, 59, 0.95) !important;
    }

    body.light-mode .methods-title {
        color: rgba(30, 41, 59, 0.95) !important;
    }

    body.light-mode .method-content {
        background: #f8fafc !important;
        border-color: rgba(6, 182, 212, 0.2) !important;
    }

    body.light-mode .method-name {
        color: rgba(30, 41, 59, 0.95) !important;
    }

    body.light-mode .method-description {
        color: rgba(30, 41, 59, 0.6) !important;
    }

    body.light-mode .instructions-content {
        background: rgba(6, 182, 212, 0.05) !important;
        border-color: rgba(6, 182, 212, 0.2) !important;
    }

    body.light-mode .instructions-text h4 {
        color: rgba(30, 41, 59, 0.95) !important;
    }

    body.light-mode .instructions-text p {
        color: rgba(30, 41, 59, 0.8) !important;
    }

    body.light-mode .bank-details {
        background: #f8fafc !important;
        border-color: rgba(6, 182, 212, 0.2) !important;
    }

    body.light-mode .bank-details p {
        color: rgba(30, 41, 59, 0.9) !important;
    }

    body.light-mode .success-item {
        background: #f8fafc !important;
        border-color: rgba(16, 185, 129, 0.2) !important;
    }

    body.light-mode .success-label {
        color: rgba(30, 41, 59, 0.7) !important;
    }

    body.light-mode .success-value {
        color: rgba(30, 41, 59, 0.95) !important;
    }

    body.light-mode .payment-error-card {
        background: rgba(239, 68, 68, 0.05) !important;
        border-color: rgba(239, 68, 68, 0.2) !important;
    }

    body.light-mode .error-content h4 {
        color: rgba(30, 41, 59, 0.95) !important;
    }

    body.light-mode .error-content p {
        color: rgba(30, 41, 59, 0.8) !important;
    }
</style>

<!-- JavaScript -->
<script>
    // Mise à jour automatique des instructions lors du changement de méthode
    document.querySelectorAll('.method-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('paymentMethodForm').submit();
        });
    });

    // Confirmer le paiement (pour mobile money et bank transfer)
    function confirmPayment() {
        if (confirm('Confirmez-vous avoir effectué le paiement ?')) {
            // Ici, on pourrait appeler une API pour marquer le paiement comme confirmé
            // Pour l'instant, on affiche un message
            alert('Votre paiement sera vérifié sous peu. Vous recevrez une confirmation par email.');
        }
    }

    // Traiter PayPal - Mettre à jour la méthode puis traiter
    function processPayPal() {
        const form = document.getElementById('paymentMethodForm');
        const paypalRadio = document.querySelector('input[value="paypal"]');
        if (paypalRadio) {
            paypalRadio.checked = true;
            form.submit();
        }
    }

    // Traiter Stripe - Mettre à jour la méthode puis traiter
    function processStripe() {
        const form = document.getElementById('paymentMethodForm');
        const stripeRadio = document.querySelector('input[value="stripe"]');
        if (stripeRadio) {
            stripeRadio.checked = true;
            form.submit();
        }
    }
</script>
@endsection
