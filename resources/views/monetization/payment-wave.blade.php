@extends('layouts.app')

@section('title', trans('app.donations.wave_payment.title'))

@push('styles')
<style>
    .wave-payment-container {
        min-height: 100vh;
        padding: 40px 20px;
        background: linear-gradient(to bottom right, #0f172a, #1e293b, #0f172a);
    }
    
    body:not(.dark-mode) .wave-payment-container {
        background: linear-gradient(to bottom right, #f8fafc, #e2e8f0, #f8fafc);
    }
    
    .wave-payment-card {
        max-width: 800px;
        margin: 0 auto;
        background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8));
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 20px;
        padding: 50px 40px;
        text-align: center;
    }
    
    body:not(.dark-mode) .wave-payment-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95));
        border: 2px solid rgba(6, 182, 212, 0.2);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }
    
    .wave-payment-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: white;
        margin-bottom: 20px;
    }
    
    body:not(.dark-mode) .wave-payment-title {
        color: #1e293b;
    }
    
    .wave-payment-description {
        font-size: 1.2rem;
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 40px;
    }
    
    body:not(.dark-mode) .wave-payment-description {
        color: #64748b;
    }
    
    .wave-payment-details {
        background: rgba(15, 23, 42, 0.8);
        border: 1px solid rgba(6, 182, 212, 0.3);
        border-radius: 16px;
        padding: 30px;
        margin-bottom: 30px;
        text-align: left;
    }
    
    body:not(.dark-mode) .wave-payment-details {
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(6, 182, 212, 0.2);
    }
    
    .wave-payment-details-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: white;
        margin-bottom: 25px;
        text-align: center;
    }
    
    body:not(.dark-mode) .wave-payment-details-title {
        color: #1e293b;
    }
    
    .wave-payment-detail-item {
        display: flex;
        justify-content: space-between;
        padding: 15px 0;
        border-bottom: 1px solid rgba(6, 182, 212, 0.2);
    }
    
    .wave-payment-detail-label {
        color: rgba(255, 255, 255, 0.7);
        font-weight: 600;
    }
    
    body:not(.dark-mode) .wave-payment-detail-label {
        color: #64748b;
    }
    
    .wave-payment-detail-value {
        color: white;
        font-weight: 700;
    }
    
    body:not(.dark-mode) .wave-payment-detail-value {
        color: #1e293b;
    }
    
    .wave-payment-amount {
        color: #06b6d4;
        font-weight: 700;
        font-size: 1.25rem;
    }
    
    .wave-payment-instructions {
        background: rgba(6, 182, 212, 0.1);
        border-left: 4px solid #06b6d4;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 30px;
        text-align: left;
    }
    
    body:not(.dark-mode) .wave-payment-instructions {
        background: rgba(6, 182, 212, 0.05);
        border-left: 4px solid #06b6d4;
    }
    
    .wave-payment-instructions-title {
        color: white;
        font-weight: 700;
        margin-bottom: 10px;
    }
    
    body:not(.dark-mode) .wave-payment-instructions-title {
        color: #1e293b;
    }
    
    .wave-payment-instructions-list {
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.8;
        padding-left: 20px;
    }
    
    body:not(.dark-mode) .wave-payment-instructions-list {
        color: #475569;
    }
    
    .wave-payment-link-box {
        background: rgba(251, 191, 36, 0.1);
        border: 1px solid rgba(251, 191, 36, 0.3);
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 30px;
    }
    
    body:not(.dark-mode) .wave-payment-link-box {
        background: rgba(251, 191, 36, 0.05);
        border: 1px solid rgba(251, 191, 36, 0.2);
    }
    
    .wave-payment-link-label {
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.95rem;
        margin-bottom: 10px;
    }
    
    body:not(.dark-mode) .wave-payment-link-label {
        color: #1e293b;
    }
    
    .wave-payment-link-input {
        flex: 1;
        min-width: 200px;
        padding: 10px;
        background: rgba(15, 23, 42, 0.8);
        border: 1px solid rgba(6, 182, 212, 0.3);
        border-radius: 8px;
        color: white;
        font-size: 0.9rem;
        font-family: monospace;
    }
    
    body:not(.dark-mode) .wave-payment-link-input {
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(6, 182, 212, 0.2);
        color: #1e293b;
    }
    
    .wave-payment-copy-btn {
        padding: 10px 20px;
        background: rgba(6, 182, 212, 0.2);
        color: #06b6d4;
        border: 1px solid #06b6d4;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .wave-payment-copy-btn:hover {
        background: rgba(6, 182, 212, 0.3);
    }
    
    body:not(.dark-mode) .wave-payment-copy-btn {
        background: rgba(6, 182, 212, 0.1);
    }
    
    .wave-payment-action-btn {
        padding: 12px 30px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .wave-payment-back-btn {
        background: rgba(6, 182, 212, 0.2);
        color: #06b6d4;
        border: 2px solid #06b6d4;
    }
    
    .wave-payment-back-btn:hover {
        background: rgba(6, 182, 212, 0.3);
    }
    
    body:not(.dark-mode) .wave-payment-back-btn {
        background: rgba(6, 182, 212, 0.1);
    }
    
    .wave-payment-paid-btn {
        background: rgba(59, 130, 246, 0.2);
        color: #3b82f6;
        border: 2px solid #3b82f6;
    }
    
    .wave-payment-paid-btn:hover {
        background: rgba(59, 130, 246, 0.3);
    }
    
    body:not(.dark-mode) .wave-payment-paid-btn {
        background: rgba(59, 130, 246, 0.1);
    }
    
    @media (max-width: 768px) {
        .wave-payment-container {
            padding: 20px 10px;
        }
        
        .wave-payment-card {
            padding: 30px 20px;
        }
        
        .wave-payment-title {
            font-size: 2rem;
        }
    }
</style>
@endpush

@section('content')
<div class="wave-payment-container">
    <div style="max-width: 800px; margin: 0 auto;">
        
        <div class="wave-payment-card">
            
            <div style="font-size: 5rem; margin-bottom: 30px;">
                💰
            </div>
            <h1 class="wave-payment-title">
                {{ trans('app.donations.wave_payment.page_title') }}
            </h1>
            <p class="wave-payment-description">
                {{ trans('app.donations.wave_payment.description') }}
            </p>

            <div class="wave-payment-details">
                <h3 class="wave-payment-details-title">
                    {{ trans('app.donations.wave_payment.details_title') }}
                </h3>
                
                <div style="display: grid; gap: 20px;">
                    <div class="wave-payment-detail-item">
                        <span class="wave-payment-detail-label">{{ trans('app.donations.wave_payment.type') }} :</span>
                        <span class="wave-payment-detail-value">{{ trans('app.donations.wave_payment.type_donation') }}</span>
                    </div>
                    
                    <div class="wave-payment-detail-item">
                        <span class="wave-payment-detail-label">{{ trans('app.donations.wave_payment.amount') }} :</span>
                        <span class="wave-payment-amount">
                            {{ number_format($payment->amount, 0, ',', ' ') }} {{ $payment->currency }}
                        </span>
                    </div>
                    
                    <div class="wave-payment-detail-item">
                        <span class="wave-payment-detail-label">{{ trans('app.donations.wave_payment.method') }} :</span>
                        <span class="wave-payment-detail-value">
                            <i class="fas fa-mobile-alt" style="margin-right: 6px; color: #06b6d4;"></i>
                            Wave
                        </span>
                    </div>
                    
                    <div class="wave-payment-detail-item" style="border-bottom: none;">
                        <span class="wave-payment-detail-label">{{ trans('app.donations.wave_payment.reference') }} :</span>
                        <span class="wave-payment-detail-value" style="font-family: monospace;">
                            {{ $payment->payment_reference }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="wave-payment-instructions">
                <div style="display: flex; align-items: start;">
                    <i class="fas fa-info-circle" style="color: #06b6d4; font-size: 1.5rem; margin-right: 15px; margin-top: 2px;"></i>
                    <div>
                        <h4 class="wave-payment-instructions-title">{{ trans('app.donations.wave_payment.instructions_title') }}</h4>
                        <ol class="wave-payment-instructions-list">
                            <li>{{ trans('app.donations.wave_payment.instruction1') }}</li>
                            <li>{{ trans('app.donations.wave_payment.instruction2') }}</li>
                            <li>{{ trans('app.donations.wave_payment.instruction3') }}</li>
                            <li>{{ trans('app.donations.wave_payment.instruction4') }}</li>
                            <li>{{ trans('app.donations.wave_payment.instruction5') }}</li>
                        </ol>
                    </div>
                </div>
            </div>

            @if($waveLink)
            <div style="margin-bottom: 30px;">
                <a href="{{ $waveLink }}" target="_blank" style="display: inline-block; padding: 20px 50px; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: white; border: none; border-radius: 16px; font-size: 1.3rem; font-weight: 700; text-decoration: none; transition: all 0.3s ease; box-shadow: 0 8px 25px rgba(6, 182, 212, 0.4); position: relative; overflow: hidden;">
                    <span style="position: relative; z-index: 1; display: flex; align-items: center; gap: 12px;">
                        <i class="fas fa-mobile-alt" style="font-size: 1.5rem;"></i>
                        {{ trans('app.donations.wave_payment.pay_button') }}
                        <i class="fas fa-arrow-right" style="font-size: 1.2rem;"></i>
                    </span>
                    <div style="position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent); transition: left 0.5s;"></div>
                </a>
            </div>

            <div class="wave-payment-link-box">
                <p class="wave-payment-link-label">
                    <i class="fas fa-link" style="color: #fbbf24; margin-right: 8px;"></i>
                    <strong>{{ trans('app.donations.wave_payment.link_label') }}</strong>
                </p>
                <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                    <input type="text" id="wave_link_input" value="{{ $waveLink }}" readonly class="wave-payment-link-input">
                    <button onclick="copyWaveLink()" class="wave-payment-copy-btn">
                        <i class="fas fa-copy" style="margin-right: 6px;"></i>
                        {{ trans('app.donations.wave_payment.copy_button') }}
                    </button>
                </div>
            </div>
            @endif

            <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('monetization.index') }}" class="wave-payment-action-btn wave-payment-back-btn">
                    <i class="fas fa-arrow-left" style="margin-right: 8px;"></i>
                    {{ trans('app.donations.wave_payment.back_button') }}
                </a>
                <a href="{{ route('payment.confirm', $payment->id) }}" class="wave-payment-action-btn wave-payment-paid-btn">
                    <i class="fas fa-check-circle" style="margin-right: 8px;"></i>
                    {{ trans('app.donations.wave_payment.already_paid') }}
                </a>
            </div>

        </div>

    </div>
</div>

<script>
    const copiedText = '{{ trans('app.donations.wave_payment.copied') }}';
    const copyButtonText = '{{ trans('app.donations.wave_payment.copy_button') }}';
    
    function copyWaveLink() {
        const input = document.getElementById('wave_link_input');
        input.select();
        input.setSelectionRange(0, 99999); // Pour mobile
        
        try {
            document.execCommand('copy');
            
            // Feedback visuel
            const button = event.target.closest('button');
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check" style="margin-right: 6px;"></i>' + copiedText;
            button.style.background = 'rgba(16, 185, 129, 0.2)';
            button.style.borderColor = '#10b981';
            button.style.color = '#10b981';
            
            setTimeout(() => {
                button.innerHTML = '<i class="fas fa-copy" style="margin-right: 6px;"></i>' + copyButtonText;
                button.style.background = 'rgba(6, 182, 212, 0.2)';
                button.style.borderColor = '#06b6d4';
                button.style.color = '#06b6d4';
            }, 2000);
        } catch (err) {
            console.error('Erreur lors de la copie:', err);
        }
    }

    // Animation au survol du bouton Wave
    document.addEventListener('DOMContentLoaded', function() {
        const waveButton = document.querySelector('a[href*="pay.wave.com"]');
        if (waveButton) {
            const shimmer = waveButton.querySelector('div');
            waveButton.addEventListener('mouseenter', function() {
                if (shimmer) {
                    shimmer.style.left = '100%';
                    setTimeout(() => {
                        shimmer.style.transition = 'none';
                        shimmer.style.left = '-100%';
                        setTimeout(() => {
                            shimmer.style.transition = '';
                        }, 50);
                    }, 500);
                }
            });
        }
    });
</script>
@endsection



