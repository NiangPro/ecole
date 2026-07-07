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
                @if($siteSettings->wave_enabled ?? true)
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

                <!-- Orange Money -->
                @if($siteSettings->orange_money_enabled ?? false)
                <label class="payment-method-card">
                    <input type="radio" name="payment_method" value="orange_money" {{ $payment->payment_method === 'orange_money' ? 'checked' : '' }} class="method-radio">
                    <div class="method-content">
                        <div class="method-icon mobile-money">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <div class="method-info">
                            <h4 class="method-name">Orange Money</h4>
                            <p class="method-description">Envoi direct au numéro marchand</p>
                        </div>
                        <div class="method-check">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </label>
                @endif

                <!-- PayPal -->
                @if($siteSettings->paypal_enabled ?? false)
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
                @if($siteSettings->stripe_enabled ?? false)
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
                @elseif($payment->payment_method === 'orange_money')
                <div class="instructions-content">
                    <div class="instructions-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <div class="instructions-text">
                        <h4>Paiement via Orange Money</h4>
                        <p>{{ \App\Services\OrangeMoneyPaymentService::getInstructions() }}</p>
                        @if(\App\Services\OrangeMoneyPaymentService::getNumber())
                        <div class="bank-details">
                            <p><strong>Numéro Orange Money :</strong> {{ \App\Services\OrangeMoneyPaymentService::getNumber() }}</p>
                            <p><strong>Montant :</strong> {{ number_format($payment->amount, 0, ',', ' ') }} FCFA</p>
                            <p><strong>Référence :</strong> {{ $payment->payment_reference }}</p>
                        </div>
                        @endif
                        <div class="instructions-actions">
                            <button type="button" onclick="confirmPayment()" class="confirm-payment-btn">
                                <i class="fas fa-check"></i>
                                J'ai effectué le paiement
                            </button>
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
        position: relative;
        min-block-size: 100vh;
        background: var(--surface);
        padding-block: 48px;
        padding-inline: 20px;
        overflow-x: hidden;
    }

    .payment-confirm-page::before {
        content: '';
        position: fixed;
        inset: 0;
        pointer-events: none;
        z-index: 0;
        background:
            radial-gradient(circle at 15% 20%, oklch(65% 0.20 200 / 6%) 0%, transparent 45%),
            radial-gradient(circle at 85% 75%, oklch(65% 0.18 170 / 6%) 0%, transparent 45%);
    }

    .payment-container {
        max-inline-size: 860px;
        margin-inline: auto;
        position: relative;
        z-index: 1;
    }

    /* ============================================
       HEADER
       ============================================ */
    .payment-header {
        text-align: center;
        margin-block-end: 36px;
        animation: fade-in-down 0.5s var(--ease-out);
    }

    .payment-status-icon {
        inline-size: 88px;
        block-size: 88px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
        font-size: 2.5rem;
    }

    .payment-status-icon.pending {
        background: oklch(78% 0.15 85 / 15%);
        color: oklch(68% 0.17 55);
        border: 3px solid oklch(78% 0.15 85 / 60%);
        animation: pulse 2s ease infinite;
    }

    .payment-status-icon.success {
        background: oklch(72% 0.17 145 / 15%);
        color: oklch(55% 0.16 150);
        border: 3px solid oklch(72% 0.17 145 / 60%);
    }

    .payment-status-icon.failed {
        background: oklch(65% 0.22 25 / 15%);
        color: oklch(55% 0.20 25);
        border: 3px solid oklch(65% 0.22 25 / 60%);
    }

    .payment-title {
        font-size: clamp(1.75rem, 3.5vw, 2.5rem);
        font-weight: 800;
        letter-spacing: -0.02em;
        color: var(--text);
        margin-block-end: 10px;
    }

    .payment-subtitle {
        font-size: 1.0625rem;
        color: var(--text-muted);
    }

    /* ============================================
       CARDS (détails, méthodes, succès)
       ============================================ */
    .payment-details-card,
    .payment-methods-section,
    .payment-success-card {
        position: relative;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 32px;
        margin-block-end: 28px;
        box-shadow: 0 20px 48px oklch(0% 0 0 / 6%);
        animation: fade-in-up 0.5s var(--ease-out);
        overflow: hidden;

        &::before {
            content: '';
            position: absolute;
            inset-block-start: 0;
            inset-inline: 0;
            block-size: 3px;
            background: linear-gradient(90deg, var(--color-brand-500), var(--color-teal-500));
        }
    }

    .payment-success-card::before { background: linear-gradient(90deg, oklch(72% 0.17 145), oklch(60% 0.15 165)); }

    .details-title,
    .methods-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--text);
        margin-block-end: 22px;
        display: flex;
        align-items: center;
        gap: 0.625rem;

        & i { color: var(--color-brand-500); }
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
        gap: 16px;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 6px;
        padding: 18px;
        background: var(--surface-muted);
        border: 1px solid var(--border);
        border-radius: 14px;
    }

    .detail-label {
        font-size: 0.8125rem;
        color: var(--text-muted);
        font-weight: 600;
    }

    .detail-value {
        font-size: 1.0625rem;
        color: var(--text);
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .detail-value.amount {
        font-size: 1.375rem;
        background: linear-gradient(135deg, var(--color-brand-500), var(--color-teal-500));
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .detail-value.reference {
        font-family: var(--font-mono);
        font-size: 0.9375rem;
    }

    .status-badge {
        display: inline-block;
        padding-block: 0.25rem;
        padding-inline: 0.75rem;
        border-radius: var(--radius-pill);
        font-size: 0.8125rem;
        font-weight: 700;
    }

    .status-badge.pending {
        background: oklch(78% 0.15 85 / 18%);
        color: oklch(58% 0.16 65);
        border: 1px solid oklch(78% 0.15 85 / 45%);
    }

    /* ============================================
       MÉTHODES DE PAIEMENT
       ============================================ */
    .payment-methods-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
        gap: 16px;
        margin-block-end: 32px;
    }

    .payment-method-card { position: relative; cursor: pointer; }

    .method-radio { position: absolute; opacity: 0; pointer-events: none; }

    .method-content {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        padding-block: 24px;
        padding-inline: 16px;
        background: var(--surface-muted);
        border: 1px solid var(--border);
        border-radius: 16px;
        transition: transform var(--duration-normal) var(--ease-spring), border-color var(--duration-normal) ease, background var(--duration-normal) ease;
    }

    .method-radio:checked + .method-content {
        border-color: var(--color-brand-500);
        background: oklch(65% 0.20 200 / 10%);
        transform: translateY(-4px);
        box-shadow: 0 14px 32px oklch(65% 0.20 200 / 22%);

        & .method-check { opacity: 1; transform: scale(1); }
    }

    .method-radio:focus-visible + .method-content {
        outline: 2px solid var(--color-brand-500);
        outline-offset: 2px;
    }

    .method-icon {
        inline-size: 56px;
        block-size: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: oklch(100% 0 0);
    }

    .method-icon.mobile-money  { background: linear-gradient(135deg, oklch(72% 0.17 145), oklch(60% 0.15 165)); }
    .method-icon.bank-transfer { background: linear-gradient(135deg, oklch(62% 0.19 275), oklch(52% 0.20 280)); }
    .method-icon.wave          { background: linear-gradient(135deg, var(--color-brand-500), var(--color-brand-600)); }
    .method-icon.paypal        { background: linear-gradient(135deg, oklch(35% 0.15 260), oklch(60% 0.16 235)); }
    .method-icon.stripe        { background: linear-gradient(135deg, oklch(58% 0.22 275), oklch(50% 0.20 290)); }

    .method-info { text-align: center; }

    .method-name {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text);
        margin-block-end: 4px;
    }

    .method-description { font-size: 0.8125rem; color: var(--text-muted); }

    .method-check {
        position: absolute;
        inset-block-start: 12px;
        inset-inline-end: 12px;
        inline-size: 26px;
        block-size: 26px;
        background: linear-gradient(135deg, var(--color-brand-500), var(--color-teal-500));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: oklch(100% 0 0);
        font-size: 0.75rem;
        opacity: 0;
        transform: scale(0);
        transition: transform var(--duration-normal) var(--ease-spring), opacity var(--duration-normal) ease;
    }

    /* ============================================
       INSTRUCTIONS
       ============================================ */
    .payment-instructions { margin-block-start: 32px; }

    .instructions-content {
        display: flex;
        gap: 18px;
        padding: 26px;
        background: var(--surface-muted);
        border: 1px solid var(--border);
        border-radius: 16px;
    }

    .instructions-icon {
        inline-size: 52px;
        block-size: 52px;
        border-radius: 14px;
        background: oklch(65% 0.20 200 / 14%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: var(--color-brand-500);
        flex-shrink: 0;
    }

    .instructions-text { flex: 1; min-inline-size: 0; }

    .instructions-text h4 {
        font-size: 1.125rem;
        font-weight: 800;
        color: var(--text);
        margin-block-end: 10px;
    }

    .instructions-text p {
        color: var(--text-muted);
        line-height: 1.7;
        margin-block-end: 16px;
    }

    .bank-details {
        background: var(--surface);
        border: 1px solid var(--border);
        padding: 18px;
        border-radius: 12px;
        margin-block-end: 16px;
    }

    .bank-details p { margin: 6px 0; color: var(--text); }

    .instructions-actions { margin-block-start: 16px; }

    .confirm-payment-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.625rem;
        padding-block: 0.875rem;
        padding-inline: 1.75rem;
        background: linear-gradient(135deg, var(--color-brand-500), var(--color-teal-500));
        border: none;
        border-radius: var(--radius-pill);
        color: oklch(10% 0 0);
        font-size: 1rem;
        font-weight: 800;
        text-decoration: none;
        cursor: pointer;
        transition: transform var(--duration-normal) var(--ease-spring), box-shadow var(--duration-normal) ease;
        box-shadow: 0 10px 26px oklch(65% 0.20 200 / 30%);

        &:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 34px oklch(65% 0.20 200 / 42%);
        }
    }

    .confirm-payment-btn.wave-btn   { background: linear-gradient(135deg, var(--color-brand-500), var(--color-brand-600)); color: oklch(100% 0 0); }
    .confirm-payment-btn.paypal-btn { background: linear-gradient(135deg, oklch(35% 0.15 260), oklch(60% 0.16 235)); color: oklch(100% 0 0); }
    .confirm-payment-btn.stripe-btn { background: linear-gradient(135deg, oklch(58% 0.22 275), oklch(50% 0.20 290)); color: oklch(100% 0 0); }

    /* ============================================
       ACTIONS
       ============================================ */
    .payment-actions {
        display: flex;
        gap: 12px;
        justify-content: center;
        flex-wrap: wrap;
        margin-block-start: 32px;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding-block: 0.75rem;
        padding-inline: 1.5rem;
        border-radius: var(--radius-pill);
        font-weight: 700;
        font-size: 0.9375rem;
        text-decoration: none;
        transition: transform var(--duration-fast) ease, box-shadow var(--duration-fast) ease, background var(--duration-fast) ease;
    }

    .action-btn.primary {
        background: linear-gradient(135deg, var(--color-brand-500), var(--color-teal-500));
        color: oklch(10% 0 0);
        box-shadow: 0 10px 26px oklch(65% 0.20 200 / 30%);

        &:hover { transform: translateY(-2px); box-shadow: 0 14px 34px oklch(65% 0.20 200 / 42%); }
    }

    .action-btn.secondary {
        background: var(--surface-muted);
        color: var(--text);
        border: 1px solid var(--border);

        &:hover { border-color: oklch(65% 0.20 200 / 45%); }
    }

    /* ============================================
       CARD DE SUCCÈS
       ============================================ */
    .success-details {
        display: grid;
        gap: 14px;
        margin-block-end: 26px;
    }

    .success-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px;
        background: var(--surface-muted);
        border-radius: 14px;
        border: 1px solid var(--border);
    }

    .success-label { color: var(--text-muted); font-weight: 600; }

    .success-value { color: var(--text); font-weight: 700; font-size: 1.0625rem; }

    .success-actions { display: flex; gap: 12px; flex-wrap: wrap; }

    .success-btn {
        flex: 1;
        min-inline-size: 200px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.625rem;
        padding-block: 0.875rem;
        padding-inline: 1.75rem;
        background: linear-gradient(135deg, oklch(72% 0.17 145), oklch(60% 0.15 165));
        border-radius: var(--radius-pill);
        color: oklch(10% 0 0);
        font-weight: 800;
        text-decoration: none;
        transition: transform var(--duration-normal) var(--ease-spring), box-shadow var(--duration-normal) ease;
        box-shadow: 0 10px 26px oklch(72% 0.17 145 / 28%);

        &:hover { transform: translateY(-2px); box-shadow: 0 14px 34px oklch(72% 0.17 145 / 38%); }
    }

    .success-btn.secondary {
        background: var(--surface-muted);
        color: var(--text);
        border: 1px solid var(--border);
        box-shadow: none;

        &:hover { transform: none; border-color: oklch(65% 0.20 200 / 45%); }
    }

    /* ============================================
       CARD D'ERREUR
       ============================================ */
    .payment-error-card {
        background: oklch(65% 0.22 25 / 8%);
        border: 1px solid oklch(65% 0.22 25 / 30%);
        border-radius: 16px;
        padding: 22px;
        margin-block-end: 26px;
        animation: fade-in-up 0.5s var(--ease-out);
    }

    .error-content { display: flex; gap: 14px; align-items: flex-start; }

    .error-content i { font-size: 1.75rem; color: oklch(55% 0.20 25); flex-shrink: 0; }

    .error-content h4 { color: var(--text); font-weight: 700; margin-block-end: 6px; }

    .error-content p { color: var(--text-muted); }

    /* ============================================
       ANIMATIONS
       ============================================ */
    @keyframes fade-in-down {
        from { opacity: 0; transform: translateY(-16px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    @keyframes fade-in-up {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50%      { transform: scale(1.05); }
    }

    /* ============================================
       RESPONSIVE
       ============================================ */
    @media (width <= 768px) {
        .payment-methods-grid { grid-template-columns: 1fr; }
        .details-grid { grid-template-columns: 1fr; }
        .instructions-content { flex-direction: column; }
        .payment-actions { flex-direction: column; }
        .action-btn,
        .success-btn { inline-size: 100%; }
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
