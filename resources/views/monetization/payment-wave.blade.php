@extends('layouts.app')

@section('title', trans('app.donations.wave_payment.title'))

@push('styles')
<style>
    * { box-sizing: border-box; }

    body:not(.dark-mode) { background: #f6f8fb !important; }
    body.dark-mode { background: #0a0a0f !important; }

    /* ── Hero ─────────────────────────────────────────────── */
    .wpay-hero {
        padding: clamp(110px, 12vw, 140px) 20px 50px;
        text-align: center;
        position: relative;
        overflow: hidden;
        background-image: radial-gradient(circle at 20% 20%, rgba(6, 182, 212, 0.20), transparent 55%),
                    radial-gradient(circle at 80% 80%, rgba(20, 184, 166, 0.16), transparent 55%),
                    linear-gradient(135deg, rgba(11,17,32,0.86) 0%, rgba(17,24,39,0.78) 55%, rgba(11,17,32,0.88) 100%),
                    image-set(
                        url('{{ asset('images/payment-hero-security.webp') }}') type('image/webp'),
                        url('{{ asset('images/payment-hero-security.jpg') }}') type('image/jpeg')
                    );
        background-size: auto, auto, auto, cover;
        background-position: center, center, center, center;
        background-repeat: no-repeat;
    }
    body:not(.dark-mode) .wpay-hero {
        background-image: radial-gradient(circle at 20% 20%, rgba(6, 182, 212, 0.12), transparent 55%),
                    radial-gradient(circle at 80% 80%, rgba(20, 184, 166, 0.1), transparent 55%),
                    linear-gradient(135deg, rgba(15,23,42,0.82) 0%, rgba(30,41,59,0.74) 55%, rgba(15,23,42,0.84) 100%),
                    image-set(
                        url('{{ asset('images/payment-hero-security.webp') }}') type('image/webp'),
                        url('{{ asset('images/payment-hero-security.jpg') }}') type('image/jpeg')
                    );
        background-size: auto, auto, auto, cover;
        background-position: center, center, center, center;
        background-repeat: no-repeat;
    }
    .wpay-hero-icon {
        width: 84px; height: 84px; margin: 0 auto 22px;
        border-radius: 50%;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        display: flex; align-items: center; justify-content: center;
        font-size: 2.4rem;
        box-shadow: 0 10px 40px rgba(6, 182, 212, 0.35);
        animation: wpay-float 3s ease-in-out infinite;
    }
    @keyframes wpay-float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }
    .wpay-hero h1 {
        font-family: 'Poppins', sans-serif;
        font-size: clamp(1.9rem, 4vw, 2.8rem);
        font-weight: 900;
        color: #fff;
        margin-bottom: 14px;
    }
    body:not(.dark-mode) .wpay-hero h1 { color: rgba(255,255,255,0.95); }
    .wpay-hero p {
        font-size: 1.05rem;
        color: rgba(255,255,255,0.75);
        max-width: 560px;
        margin: 0 auto;
    }
    body:not(.dark-mode) .wpay-hero p { color: rgba(255,255,255,0.85); }

    /* ── Layout : deux cards côte à côte ─────────────────────── */
    .wpay-container {
        max-width: 1080px;
        margin: -20px auto 0;
        padding: 0 20px 90px;
        position: relative;
        z-index: 2;
    }
    .wpay-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 26px;
        align-items: start;
    }
    @media (max-width: 860px) {
        .wpay-grid { grid-template-columns: 1fr; }
    }

    .wpay-card {
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(20px);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 24px;
        padding: 36px 32px;
        height: 100%;
    }
    body:not(.dark-mode) .wpay-card {
        background: rgba(255, 255, 255, 0.92);
        border-color: rgba(6, 182, 212, 0.2);
        box-shadow: 0 10px 40px rgba(6, 182, 212, 0.08);
    }

    .wpay-card-title {
        font-family: 'Poppins', sans-serif;
        font-size: 1.25rem;
        font-weight: 800;
        color: #fff;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    body:not(.dark-mode) .wpay-card-title { color: #0f172a; }
    .wpay-card-title i { color: #06b6d4; }

    /* Card 1 — récapitulatif */
    .wpay-detail-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 0;
        border-bottom: 1px solid rgba(6, 182, 212, 0.15);
        gap: 12px;
    }
    .wpay-detail-item:last-child { border-bottom: none; }
    .wpay-detail-label {
        color: rgba(255,255,255,0.6);
        font-weight: 600;
        font-size: 0.92rem;
    }
    body:not(.dark-mode) .wpay-detail-label { color: #64748b; }
    .wpay-detail-value {
        color: #fff;
        font-weight: 700;
        text-align: right;
    }
    body:not(.dark-mode) .wpay-detail-value { color: #0f172a; }
    .wpay-detail-value.amount {
        color: #06b6d4;
        font-size: 1.3rem;
    }
    .wpay-detail-value.ref {
        font-family: 'SFMono-Regular', Consolas, monospace;
        font-size: 0.85rem;
        word-break: break-all;
    }

    .wpay-instructions {
        margin-top: 26px;
        padding-top: 22px;
        border-top: 1px dashed rgba(6, 182, 212, 0.25);
    }
    .wpay-instructions-title {
        display: flex; align-items: center; gap: 10px;
        font-weight: 700; color: #fff;
        margin-bottom: 14px; font-size: 0.98rem;
    }
    body:not(.dark-mode) .wpay-instructions-title { color: #0f172a; }
    .wpay-instructions-title i { color: #06b6d4; }
    .wpay-instructions ol {
        margin: 0; padding-left: 20px;
        color: rgba(255,255,255,0.75);
        font-size: 0.92rem;
        line-height: 1.9;
    }
    body:not(.dark-mode) .wpay-instructions ol { color: #475569; }

    /* Card 2 — paiement */
    .wpay-pay-btn {
        display: flex; align-items: center; justify-content: center; gap: 12px;
        width: 100%;
        padding: 18px 24px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: #04141a;
        border: none;
        border-radius: 16px;
        font-size: 1.1rem;
        font-weight: 800;
        text-decoration: none;
        box-shadow: 0 10px 30px rgba(6, 182, 212, 0.35);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        margin-bottom: 22px;
    }
    .wpay-pay-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 16px 40px rgba(6, 182, 212, 0.5);
        color: #04141a;
    }

    .wpay-link-box {
        background: rgba(251, 191, 36, 0.1);
        border: 1px solid rgba(251, 191, 36, 0.3);
        border-radius: 14px;
        padding: 16px;
        margin-bottom: 24px;
    }
    body:not(.dark-mode) .wpay-link-box {
        background: rgba(251, 191, 36, 0.06);
        border-color: rgba(251, 191, 36, 0.25);
    }
    .wpay-link-label {
        display: flex; align-items: center; gap: 8px;
        color: rgba(255,255,255,0.85);
        font-size: 0.88rem;
        font-weight: 700;
        margin-bottom: 10px;
    }
    body:not(.dark-mode) .wpay-link-label { color: #1e293b; }
    .wpay-link-label i { color: #fbbf24; }
    .wpay-link-row { display: flex; gap: 8px; flex-wrap: wrap; }
    .wpay-link-input {
        flex: 1; min-width: 160px;
        padding: 10px 12px;
        background: rgba(15, 23, 42, 0.8);
        border: 1px solid rgba(6, 182, 212, 0.3);
        border-radius: 10px;
        color: #fff;
        font-size: 0.85rem;
        font-family: monospace;
    }
    body:not(.dark-mode) .wpay-link-input {
        background: #fff;
        border-color: rgba(6, 182, 212, 0.25);
        color: #0f172a;
    }
    .wpay-copy-btn {
        padding: 10px 18px;
        background: rgba(6, 182, 212, 0.15);
        color: #06b6d4;
        border: 1px solid #06b6d4;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 700;
        font-size: 0.85rem;
        transition: all 0.25s ease;
        white-space: nowrap;
    }
    .wpay-copy-btn:hover { background: rgba(6, 182, 212, 0.28); }

    .wpay-secondary-btn {
        display: flex; align-items: center; justify-content: center; gap: 8px;
        width: 100%;
        padding: 13px 20px;
        background: rgba(59, 130, 246, 0.12);
        color: #3b82f6;
        border: 2px solid #3b82f6;
        border-radius: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.25s ease;
        margin-bottom: 24px;
    }
    .wpay-secondary-btn:hover { background: rgba(59, 130, 246, 0.22); color: #3b82f6; }

    .wpay-whatsapp {
        padding: 20px;
        background: rgba(37, 211, 102, 0.1);
        border: 1px solid rgba(37, 211, 102, 0.3);
        border-radius: 14px;
        text-align: center;
    }
    body:not(.dark-mode) .wpay-whatsapp { background: rgba(37, 211, 102, 0.06); }
    .wpay-whatsapp i.fa-whatsapp {
        font-size: 1.8rem; color: #25d366; margin-bottom: 8px; display: block;
    }
    .wpay-whatsapp-text {
        color: rgba(255,255,255,0.8);
        font-size: 0.88rem;
        line-height: 1.6;
        margin-bottom: 14px;
    }
    body:not(.dark-mode) .wpay-whatsapp-text { color: #475569; }
    .wpay-whatsapp-text strong { color: #25d366; display: block; margin-bottom: 3px; }
    .wpay-whatsapp-btn {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 11px 22px;
        background: #25d366;
        color: #fff;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.88rem;
        text-decoration: none;
        box-shadow: 0 6px 18px rgba(37, 211, 102, 0.35);
        transition: transform 0.2s ease;
    }
    .wpay-whatsapp-btn:hover { transform: translateY(-2px); color: #fff; }

    /* ── Retour ───────────────────────────────────────────── */
    .wpay-back {
        text-align: center;
        margin-top: 28px;
    }
    .wpay-back a {
        display: inline-flex; align-items: center; gap: 8px;
        color: rgba(255,255,255,0.6);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.92rem;
        transition: color 0.2s ease;
    }
    body:not(.dark-mode) .wpay-back a { color: #64748b; }
    .wpay-back a:hover { color: #06b6d4; }

    @media (max-width: 640px) {
        .wpay-card { padding: 26px 20px; }
    }
</style>
@endpush

@section('content')
@php
    $__typeLabel = trans('app.donations.wave_payment.type_donation');
    $__paymentable = $payment->paymentable;
    if ($__paymentable instanceof \App\Models\CoursePurchase) {
        $__typeLabel = 'Cours : ' . \Illuminate\Support\Str::limit($__paymentable->course->title ?? '', 40);
    } elseif ($__paymentable instanceof \App\Models\Subscription) {
        $__typeLabel = 'Abonnement ' . ucfirst($__paymentable->plan_type ?? '');
    } elseif ($__paymentable instanceof \App\Models\DocumentPurchase) {
        $__typeLabel = 'Document : ' . \Illuminate\Support\Str::limit($__paymentable->document->title ?? '', 40);
    } elseif ($__paymentable instanceof \App\Models\EpreuvePurchase) {
        $__typeLabel = 'Épreuve : ' . \Illuminate\Support\Str::limit($__paymentable->epreuve->title ?? '', 40);
    } elseif ($__paymentable instanceof \App\Models\CorrigePurchase) {
        $__typeLabel = 'Corrigé : ' . \Illuminate\Support\Str::limit($__paymentable->epreuve->title ?? '', 40);
    } elseif ($__paymentable instanceof \App\Models\BundlePurchase) {
        $__typeLabel = 'Pack : ' . \Illuminate\Support\Str::limit($__paymentable->bundle->name ?? '', 40);
    }

    $sitePhone = \App\Models\SiteSetting::get('contact_phone');
    $waNumber = $sitePhone ? preg_replace('/[^0-9]/', '', $sitePhone) : null;
    $waText = rawurlencode("Bonjour, je viens d'effectuer un paiement Wave (réf. " . $payment->payment_reference . ") et c'est urgent. Pouvez-vous valider et m'envoyer mon document ?");
@endphp

<!-- Hero -->
<section class="wpay-hero">
    <div class="wpay-hero-icon">💰</div>
    <h1>{{ trans('app.donations.wave_payment.page_title') }}</h1>
    <p>{{ trans('app.donations.wave_payment.description') }}</p>
</section>

<div class="wpay-container">
    <div class="wpay-grid">

        <!-- Card 1 : Récapitulatif -->
        <div class="wpay-card">
            <h2 class="wpay-card-title">
                <i class="fas fa-receipt"></i>
                {{ trans('app.donations.wave_payment.details_title') }}
            </h2>

            <div class="wpay-detail-item">
                <span class="wpay-detail-label">{{ trans('app.donations.wave_payment.type') }}</span>
                <span class="wpay-detail-value">{{ $__typeLabel }}</span>
            </div>
            <div class="wpay-detail-item">
                <span class="wpay-detail-label">{{ trans('app.donations.wave_payment.amount') }}</span>
                <span class="wpay-detail-value amount">{{ number_format($payment->amount, 0, ',', ' ') }} {{ $payment->currency }}</span>
            </div>
            <div class="wpay-detail-item">
                <span class="wpay-detail-label">{{ trans('app.donations.wave_payment.method') }}</span>
                <span class="wpay-detail-value"><i class="fas fa-mobile-alt" style="color:#06b6d4;margin-right:6px;"></i>Wave</span>
            </div>
            <div class="wpay-detail-item">
                <span class="wpay-detail-label">{{ trans('app.donations.wave_payment.reference') }}</span>
                <span class="wpay-detail-value ref">{{ $payment->payment_reference }}</span>
            </div>

            <div class="wpay-instructions">
                <div class="wpay-instructions-title">
                    <i class="fas fa-info-circle"></i>
                    {{ trans('app.donations.wave_payment.instructions_title') }}
                </div>
                <ol>
                    <li>{{ trans('app.donations.wave_payment.instruction1') }}</li>
                    <li>{{ trans('app.donations.wave_payment.instruction2') }}</li>
                    <li>{{ trans('app.donations.wave_payment.instruction3') }}</li>
                    <li>{{ trans('app.donations.wave_payment.instruction4') }}</li>
                    <li>{{ trans('app.donations.wave_payment.instruction5') }}</li>
                </ol>
            </div>
        </div>

        <!-- Card 2 : Paiement -->
        <div class="wpay-card">
            <h2 class="wpay-card-title">
                <i class="fas fa-mobile-alt"></i>
                {{ trans('app.donations.wave_payment.pay_button') }}
            </h2>

            @if($waveLink)
            <a href="{{ $waveLink }}" target="_blank" class="wpay-pay-btn">
                <i class="fas fa-mobile-alt"></i>
                {{ trans('app.donations.wave_payment.pay_button') }}
                <i class="fas fa-arrow-right"></i>
            </a>

            <div class="wpay-link-box">
                <p class="wpay-link-label">
                    <i class="fas fa-link"></i>
                    {{ trans('app.donations.wave_payment.link_label') }}
                </p>
                <div class="wpay-link-row">
                    <input type="text" id="wave_link_input" value="{{ $waveLink }}" readonly class="wpay-link-input">
                    <button type="button" onclick="copyWaveLink()" class="wpay-copy-btn">
                        <i class="fas fa-copy"></i>
                        {{ trans('app.donations.wave_payment.copy_button') }}
                    </button>
                </div>
            </div>
            @endif

            <a href="{{ route('payment.confirm', $payment->id) }}" class="wpay-secondary-btn">
                <i class="fas fa-check-circle"></i>
                {{ trans('app.donations.wave_payment.already_paid') }}
            </a>

            @if($waNumber)
            <div class="wpay-whatsapp">
                <i class="fab fa-whatsapp"></i>
                <div class="wpay-whatsapp-text">
                    <strong>C'est urgent&nbsp;?</strong>
                    Après avoir payé, envoyez-nous un message WhatsApp pour une validation rapide&nbsp;:
                </div>
                <a href="https://wa.me/{{ $waNumber }}?text={{ $waText }}" target="_blank" rel="noopener" class="wpay-whatsapp-btn">
                    <i class="fab fa-whatsapp"></i>
                    Écrire sur WhatsApp
                </a>
            </div>
            @endif
        </div>

    </div>

    <div class="wpay-back">
        <a href="{{ route('monetization.index') }}">
            <i class="fas fa-arrow-left"></i>
            {{ trans('app.donations.wave_payment.back_button') }}
        </a>
    </div>
</div>

<script>
    const copiedText = '{{ trans('app.donations.wave_payment.copied') }}';
    const copyButtonText = '{{ trans('app.donations.wave_payment.copy_button') }}';

    function copyWaveLink() {
        const input = document.getElementById('wave_link_input');
        input.select();
        input.setSelectionRange(0, 99999);

        const finish = (button) => {
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check"></i> ' + copiedText;
            button.style.background = 'rgba(16, 185, 129, 0.2)';
            button.style.borderColor = '#10b981';
            button.style.color = '#10b981';
            setTimeout(() => {
                button.innerHTML = originalHTML;
                button.style.background = '';
                button.style.borderColor = '';
                button.style.color = '';
            }, 2000);
        };

        const button = event.target.closest('button');

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(input.value).then(() => finish(button)).catch(() => {
                try { document.execCommand('copy'); finish(button); } catch (e) {}
            });
        } else {
            try { document.execCommand('copy'); finish(button); } catch (e) {}
        }
    }
</script>
@endsection
