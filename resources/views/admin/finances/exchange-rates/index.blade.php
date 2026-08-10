@extends('admin.layout')

@section('title', 'Taux de change - Admin')

@section('styles')
<style>
    .fx-page { max-width: 900px; margin: 0 auto; }

    /* ---------- Hero ---------- */
    .fx-hero {
        display: flex; align-items: center; gap: 1.25rem; flex-wrap: wrap;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.12) 0%, rgba(20, 184, 166, 0.1) 100%);
        border: 1px solid rgba(6, 182, 212, 0.3);
        border-radius: 22px;
        padding: 2rem 2.25rem;
        margin-bottom: 1.75rem;
        position: relative;
        overflow: hidden;
    }

    .fx-hero::before {
        content: ''; position: absolute; inset: 0;
        background: radial-gradient(circle at 90% 10%, rgba(6, 182, 212, 0.18), transparent 60%);
        pointer-events: none;
    }

    .fx-hero-icon {
        width: 62px; height: 62px; flex-shrink: 0; border-radius: 18px;
        display: flex; align-items: center; justify-content: center; font-size: 1.7rem;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.25), rgba(20, 184, 166, 0.25));
        border: 1px solid rgba(6, 182, 212, 0.35);
        position: relative; z-index: 1;
    }

    .fx-hero-text { position: relative; z-index: 1; flex: 1; min-width: 220px; }

    .fx-hero-text h1 {
        font-family: 'Poppins', sans-serif; font-size: 1.9rem; font-weight: 800;
        background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 60%, #06b6d4 100%);
        background-size: 200% auto;
        -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        margin: 0 0 0.35rem;
    }

    .fx-hero-text p { margin: 0; font-size: 0.95rem; color: rgba(255, 255, 255, 0.65); }
    body.light-mode .fx-hero-text p { color: rgba(30, 41, 59, 0.65); }

    .fx-hero-back {
        position: relative; z-index: 1; flex-shrink: 0;
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.6rem 1.1rem; border-radius: 10px;
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none; font-size: 0.85rem; font-weight: 600;
        transition: all 0.25s ease;
    }

    .fx-hero-back:hover { background: rgba(6, 182, 212, 0.15); border-color: rgba(6, 182, 212, 0.4); color: #06b6d4; }
    body.light-mode .fx-hero-back { background: rgba(255, 255, 255, 0.6); border-color: rgba(100, 116, 139, 0.25); color: #1e293b; }

    /* ---------- Flash ---------- */
    .fx-flash {
        border-radius: 12px; padding: 0.85rem 1.1rem; margin-bottom: 1.5rem; font-size: 0.85rem;
        display: flex; align-items: center; gap: 0.5rem;
        background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); color: #22c55e;
    }

    /* ---------- Rate cards ---------- */
    .fx-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.1rem; margin-bottom: 1.75rem; }

    .fx-card {
        background: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 20px;
        padding: 1.6rem;
        position: relative;
        overflow: hidden;
        transition: all 0.25s ease;
    }

    .fx-card:hover { border-color: rgba(6, 182, 212, 0.4); transform: translateY(-3px); box-shadow: 0 12px 28px rgba(6, 182, 212, 0.15); }
    body.light-mode .fx-card { background: rgba(255, 255, 255, 0.85); border-color: rgba(6, 182, 212, 0.25); }

    .fx-card.is-empty { border-style: dashed; }

    .fx-card-head { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.1rem; }

    .fx-flag {
        width: 42px; height: 42px; border-radius: 50%; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; font-size: 1.4rem;
        background: rgba(6, 182, 212, 0.14); border: 1px solid rgba(6, 182, 212, 0.3);
    }

    .fx-card-head strong { display: block; font-size: 1.05rem; font-weight: 800; color: #fff; }
    body.light-mode .fx-card-head strong { color: #1e293b; }
    .fx-card-head span { font-size: 0.75rem; color: rgba(255, 255, 255, 0.45); }
    body.light-mode .fx-card-head span { color: rgba(30, 41, 59, 0.55); }

    .fx-rate-value { font-family: 'Poppins', sans-serif; font-size: 2rem; font-weight: 800; color: #06b6d4; line-height: 1.1; }
    .fx-rate-unit { font-size: 0.95rem; color: rgba(255, 255, 255, 0.5); font-weight: 600; }
    body.light-mode .fx-rate-unit { color: rgba(30, 41, 59, 0.6); }

    .fx-rate-example {
        margin-top: 0.6rem; font-size: 0.8rem; color: rgba(255, 255, 255, 0.55);
        padding-top: 0.6rem; border-top: 1px solid rgba(6, 182, 212, 0.12);
    }

    body.light-mode .fx-rate-example { color: rgba(30, 41, 59, 0.6); }

    .fx-rate-date { margin-top: 0.5rem; font-size: 0.72rem; color: rgba(255, 255, 255, 0.35); }
    body.light-mode .fx-rate-date { color: rgba(30, 41, 59, 0.45); }

    .fx-empty-text { font-size: 0.85rem; color: rgba(255, 255, 255, 0.45); }
    body.light-mode .fx-empty-text { color: rgba(30, 41, 59, 0.55); }

    /* ---------- Update form ---------- */
    .fx-form-card {
        background: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 20px;
        padding: 1.75rem 2rem;
    }

    body.light-mode .fx-form-card { background: rgba(255, 255, 255, 0.85); border-color: rgba(6, 182, 212, 0.25); }

    .fx-form-card h2 { margin: 0 0 0.35rem; font-size: 1.1rem; font-weight: 800; color: #fff; }
    body.light-mode .fx-form-card h2 { color: #1e293b; }

    .fx-form-hint { font-size: 0.8rem; color: rgba(255, 255, 255, 0.45); margin: 0 0 1.35rem; }
    body.light-mode .fx-form-hint { color: rgba(30, 41, 59, 0.55); }

    .fx-currency-toggle { display: flex; gap: 0.75rem; margin-bottom: 1.25rem; }
    .fx-currency-option { position: relative; flex: 1; cursor: pointer; }
    .fx-currency-radio { position: absolute; opacity: 0; width: 0; height: 0; pointer-events: none; }

    .fx-currency-box {
        display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        padding: 0.85rem; border-radius: 14px;
        border: 2px solid rgba(148, 163, 184, 0.25);
        background: rgba(10, 10, 26, 0.5);
        color: rgba(255, 255, 255, 0.55);
        font-weight: 700; font-size: 0.95rem;
        transition: all 0.2s ease;
    }

    body.light-mode .fx-currency-box { background: rgba(255, 255, 255, 0.7); color: rgba(30, 41, 59, 0.55); border-color: rgba(100, 116, 139, 0.25); }

    .fx-currency-radio:checked + .fx-currency-box {
        border-color: #06b6d4; background: rgba(6, 182, 212, 0.14); color: #06b6d4;
        box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.1);
    }

    .fx-row { display: flex; gap: 0.9rem; align-items: end; flex-wrap: wrap; }

    .fx-field { flex: 1; min-width: 180px; }

    .fx-label {
        display: flex; align-items: center; gap: 0.5rem;
        font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em;
        color: #06b6d4; margin-bottom: 0.55rem;
    }

    .fx-input {
        width: 100%;
        padding: 0.7rem 1rem;
        background: rgba(10, 10, 26, 0.75);
        border: 2px solid rgba(6, 182, 212, 0.22);
        border-radius: 12px;
        color: #fff;
        font-size: 0.95rem;
        font-family: 'Inter', sans-serif;
        transition: all 0.2s ease;
    }

    .fx-input:focus { outline: none; border-color: #06b6d4; box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.15); }
    body.light-mode .fx-input { background: rgba(255, 255, 255, 0.9); border-color: rgba(6, 182, 212, 0.3); color: #1e293b; }

    .fx-peg-btn {
        display: inline-flex; align-items: center; gap: 0.4rem;
        margin-top: 0.55rem;
        padding: 0.3rem 0.7rem; border-radius: 999px;
        font-size: 0.72rem; font-weight: 600;
        background: rgba(6, 182, 212, 0.1); border: 1px solid rgba(6, 182, 212, 0.3);
        color: #06b6d4; cursor: pointer;
    }

    .fx-peg-btn:hover { background: rgba(6, 182, 212, 0.18); }

    .fx-submit {
        padding: 0.75rem 1.6rem; border: none; border-radius: 12px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: #04121a; font-weight: 800; font-size: 0.92rem; cursor: pointer;
        box-shadow: 0 6px 18px rgba(6, 182, 212, 0.3);
        transition: all 0.2s ease;
        flex-shrink: 0;
    }

    .fx-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(6, 182, 212, 0.4); }

    .fx-preview {
        margin-top: 1.1rem; font-size: 0.85rem; color: rgba(255, 255, 255, 0.6);
        min-height: 1.3em;
    }

    body.light-mode .fx-preview { color: rgba(30, 41, 59, 0.65); }
    .fx-preview strong { color: #06b6d4; }

    @media (max-width: 640px) {
        .fx-hero { padding: 1.5rem; }
        .fx-row { flex-direction: column; align-items: stretch; }
    }
</style>
@endsection

@section('content')
<div class="fx-page">

    <div class="fx-hero">
        <div class="fx-hero-icon">💱</div>
        <div class="fx-hero-text">
            <h1>Taux de change</h1>
            <p>Convertit les revenus/dépenses saisis en EUR ou USD vers le XOF dans les rapports.</p>
        </div>
        <a href="{{ route('admin.finances.dashboard') }}" class="fx-hero-back">
            <i class="fas fa-arrow-left"></i> Dashboard
        </a>
    </div>

    @if(session('success'))
    <div class="fx-flash"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    <div class="fx-cards">
        @foreach($rates as $code => $rate)
        <div class="fx-card {{ $rate ? '' : 'is-empty' }}">
            <div class="fx-card-head">
                <div class="fx-flag">{{ $code === 'EUR' ? '🇪🇺' : '🇺🇸' }}</div>
                <div>
                    <strong>{{ $code }}</strong>
                    <span>vers XOF (Franc CFA)</span>
                </div>
            </div>

            @if($rate)
            <div>
                <span class="fx-rate-value">{{ number_format($rate->rate, 4) }}</span>
                <span class="fx-rate-unit">XOF</span>
            </div>
            <div class="fx-rate-example">100 {{ $code }} ≈ <strong>{{ number_format($rate->rate * 100, 0, ',', ' ') }} XOF</strong></div>
            <div class="fx-rate-date">Mis à jour le {{ $rate->rate_date->format('d/m/Y') }}</div>
            @else
            <p class="fx-empty-text">Aucun taux configuré — les conversions {{ $code }} utiliseront 1:1 par défaut.</p>
            @endif
        </div>
        @endforeach
    </div>

    <div class="fx-form-card">
        <h2>Mettre à jour un taux</h2>
        <p class="fx-form-hint">Les taux ne sont pas récupérés automatiquement — à mettre à jour manuellement (ex : taux BCEAO / Xe.com du jour).</p>

        <form method="POST" action="{{ route('admin.finances.exchange-rates.store') }}" id="fxForm">
            @csrf

            <div class="fx-currency-toggle">
                <label class="fx-currency-option">
                    <input type="radio" name="currency_from" value="EUR" class="fx-currency-radio" id="fxEur" checked>
                    <div class="fx-currency-box">🇪🇺 EUR</div>
                </label>
                <label class="fx-currency-option">
                    <input type="radio" name="currency_from" value="USD" class="fx-currency-radio" id="fxUsd">
                    <div class="fx-currency-box">🇺🇸 USD</div>
                </label>
            </div>

            <div class="fx-row">
                <div class="fx-field">
                    <label class="fx-label"><i class="fas fa-coins"></i> Taux vers XOF</label>
                    <input type="number" name="rate" id="fxRate" step="0.0001" min="1" required class="fx-input" placeholder="Ex : 655.9570">
                    <button type="button" id="fxPegBtn" class="fx-peg-btn"><i class="fas fa-link"></i> Utiliser le taux fixe BCEAO (655.957)</button>
                </div>
                <button type="submit" class="fx-submit"><i class="fas fa-check"></i> Mettre à jour</button>
            </div>

            <p class="fx-preview" id="fxPreview"></p>
        </form>
    </div>

</div>

<script>
(function () {
    const eur = document.getElementById('fxEur');
    const usd = document.getElementById('fxUsd');
    const rate = document.getElementById('fxRate');
    const pegBtn = document.getElementById('fxPegBtn');
    const preview = document.getElementById('fxPreview');

    function updatePegVisibility() {
        pegBtn.style.display = eur.checked ? 'inline-flex' : 'none';
    }

    function updatePreview() {
        const value = parseFloat(rate.value) || 0;
        const code = eur.checked ? 'EUR' : 'USD';
        if (value > 0) {
            preview.innerHTML = '1 ' + code + ' = <strong>' + value.toLocaleString('fr-FR') + ' XOF</strong> · 100 ' + code + ' ≈ <strong>' + Math.round(value * 100).toLocaleString('fr-FR') + ' XOF</strong>';
        } else {
            preview.innerHTML = '';
        }
    }

    pegBtn.addEventListener('click', function () {
        rate.value = 655.957;
        updatePreview();
    });

    [eur, usd].forEach(el => el.addEventListener('change', () => { updatePegVisibility(); updatePreview(); }));
    rate.addEventListener('input', updatePreview);

    updatePegVisibility();
    updatePreview();
})();
</script>
@endsection
