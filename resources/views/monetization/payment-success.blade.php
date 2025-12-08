@extends('layouts.app')

@section('title', 'Paiement Réussi - NiangProgrammeur')

@push('styles')
<style>
    .payment-success-page {
        min-height: 100vh;
        padding: 120px 20px 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
    }

    body:not(.dark-mode) .payment-success-page {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 50%, #f8fafc 100%);
    }

    .success-card {
        max-width: 600px;
        width: 100%;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(20px) saturate(180%);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 24px;
        padding: 60px 40px;
        text-align: center;
        box-shadow: 0 20px 40px rgba(6, 182, 212, 0.15);
        animation: fadeInUp 0.6s ease;
    }

    body:not(.dark-mode) .success-card {
        background: rgba(255, 255, 255, 0.8);
        border-color: rgba(6, 182, 212, 0.3);
        box-shadow: 0 20px 40px rgba(6, 182, 212, 0.1);
    }

    .success-icon {
        font-size: 5rem;
        color: #10b981;
        margin-bottom: 30px;
        animation: scaleIn 0.5s ease;
    }

    .success-title {
        font-size: 2.5rem;
        font-weight: 900;
        background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 20px;
    }

    body:not(.dark-mode) .success-title {
        background: linear-gradient(135deg, #0891b2 0%, #0d9488 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .success-message {
        font-size: 1.125rem;
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 30px;
        line-height: 1.8;
    }

    body:not(.dark-mode) .success-message {
        color: rgba(30, 41, 59, 0.8);
    }

    .donation-details {
        background: rgba(6, 182, 212, 0.1);
        border: 1px solid rgba(6, 182, 212, 0.3);
        border-radius: 16px;
        padding: 30px;
        margin: 30px 0;
        text-align: left;
    }

    body:not(.dark-mode) .donation-details {
        background: rgba(6, 182, 212, 0.05);
        border-color: rgba(6, 182, 212, 0.2);
    }

    .donation-details h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: white;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    body:not(.dark-mode) .donation-details h3 {
        color: rgba(30, 41, 59, 0.9);
    }

    .donation-details h3 i {
        color: #06b6d4;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid rgba(6, 182, 212, 0.1);
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        color: rgba(255, 255, 255, 0.7);
        font-weight: 500;
    }

    body:not(.dark-mode) .detail-label {
        color: rgba(30, 41, 59, 0.7);
    }

    .detail-value {
        color: white;
        font-weight: 700;
    }

    body:not(.dark-mode) .detail-value {
        color: rgba(30, 41, 59, 0.9);
    }

    .detail-amount {
        font-size: 1.5rem;
        color: #14b8a6;
        font-weight: 800;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 16px 32px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border: none;
        border-radius: 12px;
        color: white;
        font-size: 1.1rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
        margin-top: 30px;
    }

    .btn-back:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(6, 182, 212, 0.4);
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

    @keyframes scaleIn {
        from {
            transform: scale(0);
        }
        to {
            transform: scale(1);
        }
    }
</style>
@endpush

@section('content')
<div class="payment-success-page">
    <div class="success-card">
        <div class="success-icon">✅</div>
        <h1 class="success-title">Paiement Réussi !</h1>
        <p class="success-message">
            Merci pour votre générosité ! Votre don a été effectué avec succès.
            Votre contribution nous aide à continuer à offrir des formations gratuites de qualité.
        </p>

        @if(isset($donation) && $donation)
        <div class="donation-details">
            <h3>
                <i class="fas fa-receipt"></i>
                Détails du Don
            </h3>
            <div class="detail-row">
                <span class="detail-label">Donateur :</span>
                <span class="detail-value">{{ $donation->display_name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Montant :</span>
                <span class="detail-value detail-amount">{{ number_format($donation->amount, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Méthode :</span>
                <span class="detail-value">{{ ucfirst($payment->payment_method) }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Date :</span>
                <span class="detail-value">{{ $payment->paid_at ? $payment->paid_at->format('d/m/Y à H:i') : now()->format('d/m/Y à H:i') }}</span>
            </div>
            @if($donation->message)
            <div class="detail-row">
                <span class="detail-label">Message :</span>
                <span class="detail-value" style="font-style: italic;">"{{ $donation->message }}"</span>
            </div>
            @endif
        </div>
        @endif

        <a href="{{ route('monetization.donations') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i>
            Retour aux Donations
        </a>
    </div>
</div>
@endsection



