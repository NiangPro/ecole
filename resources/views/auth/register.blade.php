@extends('layouts.app')

@section('title', trans('app.auth.register.title') . ' | NiangProgrammeur')

@section('styles')
<style>
    /* Polices Inter/Poppins déjà chargées via layouts.app */

    .register-page {
        position: relative;
        min-height: 100dvh;
        overflow: hidden;
        padding-block-start: var(--spacing-navbar, 76px);
        display: flex;
        background: #0b1120;
    }

    body:not(.dark-mode) .register-page {
        background: #f5f8fb;
    }

    /* ── Fond animé (mesh blobs) ─────────────────────────────────────── */
    .register-blobs {
        position: absolute;
        inset: 0;
        overflow: hidden;
        pointer-events: none;
        z-index: 0;
    }

    .register-blob {
        position: absolute;
        border-radius: 50%;
        filter: blur(90px);
        opacity: .35;
        animation: register-blob-float 24s ease-in-out infinite;
        will-change: transform;
    }

    body:not(.dark-mode) .register-blob { opacity: .22; }

    .register-blob--1 { width: 520px; height: 520px; top: -180px; left: -140px; background: #06b6d4; }
    .register-blob--2 { width: 460px; height: 460px; bottom: -200px; right: -120px; background: #8b5cf6; animation-delay: -8s; }
    .register-blob--3 { width: 380px; height: 380px; top: 45%; left: 55%; background: #14b8a6; animation-delay: -16s; }

    @keyframes register-blob-float {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33%      { transform: translate(50px, -35px) scale(1.08); }
        66%      { transform: translate(-35px, 30px) scale(.94); }
    }

    @media (prefers-reduced-motion: reduce) {
        .register-blob { animation: none; }
    }

    .register-shell {
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: 1520px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
    }

    @media (min-width: 1024px) {
        .register-shell {
            flex-direction: row;
            align-items: stretch;
            min-height: calc(100dvh - var(--spacing-navbar, 76px));
        }
    }

    /* ── Panneau de marque (desktop uniquement) ─────────────────────── */
    .register-brand {
        display: none;
        order: 1;
        flex-direction: column;
        justify-content: flex-start;
        padding: 72px 56px 64px;
    }

    @media (min-width: 1024px) {
        .register-brand { display: flex; flex: .95 1 0; }
    }

    .register-brand-logo {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        width: fit-content;
        margin-bottom: 52px;
    }

    .register-brand-logo img { width: 42px; height: 42px; border-radius: 12px; }

    .register-brand-logo span {
        font-family: 'Poppins', sans-serif;
        font-weight: 800;
        font-size: 1.2rem;
        color: #fff;
    }

    body:not(.dark-mode) .register-brand-logo span { color: #0f172a; }

    .register-brand-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        width: fit-content;
        padding: 8px 16px;
        border-radius: 999px;
        background: rgba(6, 182, 212, .15);
        border: 1px solid rgba(6, 182, 212, .35);
        color: #22d3ee;
        font-size: .78rem;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
        margin-bottom: 22px;
    }

    .register-brand h2 {
        font-family: 'Poppins', sans-serif;
        font-weight: 900;
        font-size: clamp(1.9rem, 2.6vw, 2.6rem);
        line-height: 1.15;
        color: #fff;
        max-width: 19ch;
        margin-bottom: 18px;
    }

    body:not(.dark-mode) .register-brand h2 { color: #0f172a; }

    .register-brand > p {
        color: rgba(255, 255, 255, .62);
        font-size: 1.05rem;
        line-height: 1.65;
        max-width: 42ch;
        margin-bottom: 36px;
    }

    body:not(.dark-mode) .register-brand > p { color: rgba(30, 41, 59, .65); }

    .register-brand-features { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 16px; }

    .register-brand-features li {
        display: flex;
        align-items: center;
        gap: 12px;
        color: rgba(255, 255, 255, .85);
        font-weight: 500;
    }

    body:not(.dark-mode) .register-brand-features li { color: rgba(30, 41, 59, .8); }

    .register-brand-features svg { flex-shrink: 0; width: 22px; height: 22px; color: #22d3ee; }

    /* ── Panneau formulaire ──────────────────────────────────────────── */
    .register-form-panel {
        order: 2;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 18px 60px;
    }

    @media (min-width: 1024px) {
        .register-form-panel { flex: 1.15 1 0; padding: 56px 48px; }
    }

    .register-card {
        width: 100%;
        max-width: 660px;
        background: rgba(15, 23, 42, .78);
        backdrop-filter: blur(24px) saturate(160%);
        -webkit-backdrop-filter: blur(24px) saturate(160%);
        border: 1px solid rgba(255, 255, 255, .08);
        border-radius: 28px;
        padding: 40px;
        box-shadow: 0 30px 80px rgba(0, 0, 0, .45);
    }

    body:not(.dark-mode) .register-card {
        background: rgba(255, 255, 255, .85);
        border-color: rgba(6, 182, 212, .18);
        box-shadow: 0 30px 80px rgba(15, 23, 42, .12);
    }

    .register-card h1 {
        font-family: 'Poppins', sans-serif;
        font-weight: 900;
        font-size: 1.9rem;
        color: #fff;
        margin-bottom: 8px;
    }

    body:not(.dark-mode) .register-card h1 { color: #0f172a; }

    .register-subtitle { color: rgba(255, 255, 255, .55); margin-bottom: 28px; font-size: .98rem; }

    body:not(.dark-mode) .register-subtitle { color: rgba(30, 41, 59, .6); }

    /* ── Champs ──────────────────────────────────────────────────────── */
    .field { margin-bottom: 20px; }

    .field-row { display: flex; flex-direction: column; }
    .field-row .field { flex: 1; min-width: 0; }

    @media (min-width: 1400px) {
        .field-row { flex-direction: row; gap: 16px; }
    }

    .field label {
        display: block;
        font-size: .85rem;
        font-weight: 600;
        color: rgba(255, 255, 255, .85);
        margin-bottom: 8px;
    }

    body:not(.dark-mode) .field label { color: rgba(30, 41, 59, .85); }

    .field-input-wrap { position: relative; }

    .field-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        width: 19px;
        height: 19px;
        color: rgba(255, 255, 255, .35);
        pointer-events: none;
    }

    body:not(.dark-mode) .field-icon { color: rgba(15, 23, 42, .35); }

    .field-input {
        width: 100%;
        padding: 13px 16px 13px 44px;
        background: rgba(255, 255, 255, .05);
        border: 1.5px solid rgba(255, 255, 255, .1);
        border-radius: 14px;
        color: #fff;
        font-size: .95rem;
        font-family: 'Inter', sans-serif;
        transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
    }

    body:not(.dark-mode) .field-input {
        background: rgba(15, 23, 42, .03);
        border-color: rgba(15, 23, 42, .12);
        color: #0f172a;
    }

    .field-input::placeholder { color: rgba(255, 255, 255, .3); }
    body:not(.dark-mode) .field-input::placeholder { color: rgba(15, 23, 42, .35); }

    .field-input:focus {
        outline: none;
        border-color: #06b6d4;
        background: rgba(6, 182, 212, .06);
        box-shadow: 0 0 0 4px rgba(6, 182, 212, .12);
    }

    .field-toggle {
        position: absolute;
        right: 6px;
        top: 50%;
        transform: translateY(-50%);
        width: 34px;
        height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        color: rgba(255, 255, 255, .4);
        transition: color .2s ease, background .2s ease;
    }

    .field-toggle:hover { color: #22d3ee; background: rgba(6, 182, 212, .12); }
    body:not(.dark-mode) .field-toggle { color: rgba(15, 23, 42, .4); }
    .field-toggle svg { width: 19px; height: 19px; }

    .field-help { font-size: .78rem; color: rgba(255, 255, 255, .4); margin-top: 6px; }
    body:not(.dark-mode) .field-help { color: rgba(15, 23, 42, .45); }

    .field-error { display: flex; align-items: center; gap: 6px; font-size: .8rem; color: #f87171; margin-top: 6px; font-weight: 500; }

    /* ── Téléphone ───────────────────────────────────────────────────── */
    .phone-row { display: flex; gap: 10px; }
    .phone-row .field-input-wrap { flex: 1; }

    .phone-select {
        flex-shrink: 0;
        width: 118px;
        padding: 13px 30px 13px 14px;
        background: rgba(255, 255, 255, .05);
        border: 1.5px solid rgba(255, 255, 255, .1);
        border-radius: 14px;
        color: #fff;
        font-size: .9rem;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2306b6d4' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 11px;
        transition: border-color .2s ease, box-shadow .2s ease;
    }

    body:not(.dark-mode) .phone-select {
        background-color: rgba(15, 23, 42, .03);
        border-color: rgba(15, 23, 42, .12);
        color: #0f172a;
    }

    .phone-select:focus {
        outline: none;
        border-color: #06b6d4;
        box-shadow: 0 0 0 4px rgba(6, 182, 212, .12);
    }

    .phone-row .field-input { padding-left: 16px; }

    /* ── Alerte erreurs ──────────────────────────────────────────────── */
    .register-alert {
        display: flex;
        gap: 10px;
        padding: 14px 16px;
        border-radius: 14px;
        margin-bottom: 22px;
        background: rgba(239, 68, 68, .1);
        border: 1px solid rgba(239, 68, 68, .25);
        color: #f87171;
        font-size: .88rem;
    }

    .register-alert svg { flex-shrink: 0; width: 20px; height: 20px; margin-top: 1px; }
    .register-alert ul { margin: 0; padding-left: 18px; }

    /* ── Google / séparateur ─────────────────────────────────────────── */
    .register-google-btn {
        width: 100%;
        padding: 13px;
        background: rgba(255, 255, 255, .95);
        color: #1f2937;
        border: 1px solid rgba(6, 182, 212, .2);
        border-radius: 14px;
        font-weight: 600;
        font-size: .95rem;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-bottom: 20px;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .register-google-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(6, 182, 212, .22); }

    .register-social-group {
        display: flex;
        flex-direction: row;
        gap: 10px;
        margin-bottom: 20px;
    }

    .register-social-btn {
        flex: 1;
        padding: 13px;
        background: rgba(255, 255, 255, .95);
        border: 1px solid rgba(6, 182, 212, .2);
        border-radius: 14px;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .register-social-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(6, 182, 212, .22); }

    .register-divider {
        display: flex;
        align-items: center;
        gap: 12px;
        color: rgba(255, 255, 255, .4);
        font-size: .8rem;
        margin-bottom: 20px;
    }

    body:not(.dark-mode) .register-divider { color: rgba(30, 41, 59, .45); }

    .register-divider::before, .register-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: rgba(6, 182, 212, .2);
    }

    /* ── Bouton d'inscription ────────────────────────────────────────── */
    .register-submit {
        width: 100%;
        padding: 15px;
        border: none;
        border-radius: 14px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: #04141a;
        font-weight: 800;
        font-size: .98rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        transition: transform .2s ease, box-shadow .2s ease, opacity .2s ease;
        margin-top: 4px;
        margin-bottom: 26px;
        box-shadow: 0 10px 30px rgba(6, 182, 212, .28);
    }

    .register-submit:hover { transform: translateY(-2px); box-shadow: 0 14px 36px rgba(6, 182, 212, .4); }
    .register-submit:disabled { opacity: .7; cursor: not-allowed; transform: none; }
    .register-submit svg { width: 18px; height: 18px; }

    .register-submit .spinner {
        width: 17px;
        height: 17px;
        border-radius: 50%;
        border: 2px solid rgba(4, 20, 26, .3);
        border-top-color: #04141a;
        animation: register-spin .7s linear infinite;
    }

    @keyframes register-spin { to { transform: rotate(360deg); } }

    /* ── Pied de carte ───────────────────────────────────────────────── */
    .register-footer { text-align: center; padding-top: 22px; border-top: 1px solid rgba(255, 255, 255, .08); }
    body:not(.dark-mode) .register-footer { border-top-color: rgba(15, 23, 42, .1); }

    .register-footer p { color: rgba(255, 255, 255, .5); margin-bottom: 14px; font-size: .9rem; }
    body:not(.dark-mode) .register-footer p { color: rgba(15, 23, 42, .55); }

    .register-footer a {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 26px;
        border-radius: 12px;
        border: 1.5px solid rgba(6, 182, 212, .3);
        color: #22d3ee;
        text-decoration: none;
        font-weight: 700;
        font-size: .9rem;
        transition: all .2s ease;
    }

    .register-footer a:hover { background: rgba(6, 182, 212, .1); transform: translateY(-2px); }
    .register-footer a svg { width: 16px; height: 16px; }

    @media (max-width: 480px) {
        .register-card { padding: 28px 22px; border-radius: 22px; }
        .phone-row { flex-direction: column; }
        .phone-select { width: 100%; }
    }
</style>
@endsection

@section('content')
<div class="register-page">
    <div class="register-blobs" aria-hidden="true">
        <span class="register-blob register-blob--1"></span>
        <span class="register-blob register-blob--2"></span>
        <span class="register-blob register-blob--3"></span>
    </div>

    <div class="register-shell">
        <main class="register-form-panel">
            <div class="register-card">
                <h1>{{ trans('app.auth.register.title') }}</h1>
                <p class="register-subtitle">{{ trans('app.auth.register.subtitle') }}</p>

                @if($errors->any())
                    <div class="register-alert" role="alert">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="9"/>
                            <line x1="12" y1="7.5" x2="12" y2="13"/>
                            <circle cx="12" cy="16.5" r=".75" fill="currentColor" stroke="none"/>
                        </svg>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="register-social-group">
                    <a href="{{ route('auth.google.redirect') }}" class="register-social-btn" aria-label="Continuer avec Google" title="Continuer avec Google">
                        <svg width="20" height="20" viewBox="0 0 18 18" aria-hidden="true">
                            <path fill="#4285F4" d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844a4.14 4.14 0 0 1-1.796 2.716v2.259h2.908c1.702-1.567 2.684-3.875 2.684-6.615z"/>
                            <path fill="#34A853" d="M9 18c2.43 0 4.467-.806 5.956-2.184l-2.908-2.259c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 0 0 9 18z"/>
                            <path fill="#FBBC05" d="M3.964 10.706A5.41 5.41 0 0 1 3.68 9c0-.593.102-1.17.284-1.706V4.962H.957A8.996 8.996 0 0 0 0 9c0 1.452.348 2.827.957 4.038l3.007-2.332z"/>
                            <path fill="#EA4335" d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0A8.997 8.997 0 0 0 .957 4.962L3.964 7.294C4.672 5.167 6.656 3.58 9 3.58z"/>
                        </svg>
                    </a>

                    <a href="{{ route('auth.github.redirect') }}" class="register-social-btn" aria-label="Continuer avec GitHub" title="Continuer avec GitHub">
                        <i class="fab fa-github social-icon-github" style="font-size:20px"></i>
                    </a>

                    <a href="{{ route('auth.facebook.redirect') }}" class="register-social-btn" aria-label="Continuer avec Facebook" title="Continuer avec Facebook">
                        <i class="fab fa-facebook social-icon-facebook" style="font-size:20px"></i>
                    </a>
                </div>

                <div class="register-divider">ou</div>

                <form action="{{ route('register.post') }}" method="POST" id="registerForm">
                    @csrf

                    <div class="field-row">
                    <div class="field">
                        <label for="reg-first-name">{{ trans('app.auth.register.first_name') }} *</label>
                        <div class="field-input-wrap">
                            <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="8" r="3.25"/>
                                <path d="M5 19.5c0-3.6 3.13-6.5 7-6.5s7 2.9 7 6.5"/>
                            </svg>
                            <input type="text" id="reg-first-name" name="first_name" value="{{ old('first_name') }}" required
                                   autocomplete="given-name" class="field-input"
                                   placeholder="{{ trans('app.auth.register.first_name') }}">
                        </div>
                        @error('first_name')<div class="field-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="field">
                        <label for="reg-last-name">{{ trans('app.auth.register.last_name') }} *</label>
                        <div class="field-input-wrap">
                            <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="8" r="3.25"/>
                                <path d="M5 19.5c0-3.6 3.13-6.5 7-6.5s7 2.9 7 6.5"/>
                            </svg>
                            <input type="text" id="reg-last-name" name="last_name" value="{{ old('last_name') }}" required
                                   autocomplete="family-name" class="field-input"
                                   placeholder="{{ trans('app.auth.register.last_name') }}">
                        </div>
                        @error('last_name')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    </div>

                    <div class="field">
                        <label for="reg-email">{{ trans('app.auth.register.email') }} *</label>
                        <div class="field-input-wrap">
                            <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3.5" y="5.5" width="17" height="13" rx="2.5"/>
                                <path d="M4.5 7l7.5 6 7.5-6"/>
                            </svg>
                            <input type="email" id="reg-email" name="email" value="{{ old('email') }}" required
                                   autocomplete="email" class="field-input" placeholder="votre@email.com">
                        </div>
                        @error('email')<div class="field-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="field">
                        <label for="phone_number">{{ trans('app.auth.register.phone') }}</label>
                        <div class="phone-row">
                            <select name="phone_country" id="phone_country" class="phone-select">
                                <option value="+221" data-flag="🇸🇳">🇸🇳 +221</option>
                                <option value="+33" data-flag="🇫🇷">🇫🇷 +33</option>
                                <option value="+1" data-flag="🇺🇸">🇺🇸 +1</option>
                                <option value="+44" data-flag="🇬🇧">🇬🇧 +44</option>
                                <option value="+212" data-flag="🇲🇦">🇲🇦 +212</option>
                                <option value="+225" data-flag="🇨🇮">🇨🇮 +225</option>
                                <option value="+226" data-flag="🇧🇫">🇧🇫 +226</option>
                                <option value="+227" data-flag="🇳🇪">🇳🇪 +227</option>
                                <option value="+228" data-flag="🇹🇬">🇹🇬 +228</option>
                                <option value="+229" data-flag="🇧🇯">🇧🇯 +229</option>
                                <option value="+230" data-flag="🇲🇺">🇲🇺 +230</option>
                                <option value="+231" data-flag="🇱🇷">🇱🇷 +231</option>
                                <option value="+232" data-flag="🇸🇱">🇸🇱 +232</option>
                                <option value="+233" data-flag="🇬🇭">🇬🇭 +233</option>
                                <option value="+234" data-flag="🇳🇬">🇳🇬 +234</option>
                                <option value="+235" data-flag="🇹🇩">🇹🇩 +235</option>
                                <option value="+236" data-flag="🇨🇫">🇨🇫 +236</option>
                                <option value="+237" data-flag="🇨🇲">🇨🇲 +237</option>
                                <option value="+238" data-flag="🇨🇻">🇨🇻 +238</option>
                                <option value="+239" data-flag="🇸🇹">🇸🇹 +239</option>
                                <option value="+240" data-flag="🇬🇶">🇬🇶 +240</option>
                                <option value="+241" data-flag="🇬🇦">🇬🇦 +241</option>
                                <option value="+242" data-flag="🇨🇬">🇨🇬 +242</option>
                                <option value="+243" data-flag="🇨🇩">🇨🇩 +243</option>
                                <option value="+244" data-flag="🇦🇴">🇦🇴 +244</option>
                                <option value="+245" data-flag="🇬🇼">🇬🇼 +245</option>
                                <option value="+246" data-flag="🇮🇴">🇮🇴 +246</option>
                                <option value="+248" data-flag="🇸🇨">🇸🇨 +248</option>
                                <option value="+249" data-flag="🇸🇩">🇸🇩 +249</option>
                                <option value="+250" data-flag="🇷🇼">🇷🇼 +250</option>
                                <option value="+251" data-flag="🇪🇹">🇪🇹 +251</option>
                                <option value="+252" data-flag="🇸🇴">🇸🇴 +252</option>
                                <option value="+253" data-flag="🇩🇯">🇩🇯 +253</option>
                                <option value="+254" data-flag="🇰🇪">🇰🇪 +254</option>
                                <option value="+255" data-flag="🇹🇿">🇹🇿 +255</option>
                                <option value="+256" data-flag="🇺🇬">🇺🇬 +256</option>
                                <option value="+257" data-flag="🇧🇮">🇧🇮 +257</option>
                                <option value="+258" data-flag="🇲🇿">🇲🇿 +258</option>
                                <option value="+260" data-flag="🇿🇲">🇿🇲 +260</option>
                                <option value="+261" data-flag="🇲🇬">🇲🇬 +261</option>
                                <option value="+262" data-flag="🇷🇪">🇷🇪 +262</option>
                                <option value="+263" data-flag="🇿🇼">🇿🇼 +263</option>
                                <option value="+264" data-flag="🇳🇦">🇳🇦 +264</option>
                                <option value="+265" data-flag="🇲🇼">🇲🇼 +265</option>
                                <option value="+266" data-flag="🇱🇸">🇱🇸 +266</option>
                                <option value="+267" data-flag="🇧🇼">🇧🇼 +267</option>
                                <option value="+268" data-flag="🇸🇿">🇸🇿 +268</option>
                                <option value="+269" data-flag="🇰🇲">🇰🇲 +269</option>
                                <option value="+27" data-flag="🇿🇦">🇿🇦 +27</option>
                                <option value="+32" data-flag="🇧🇪">🇧🇪 +32</option>
                                <option value="+34" data-flag="🇪🇸">🇪🇸 +34</option>
                                <option value="+39" data-flag="🇮🇹">🇮🇹 +39</option>
                                <option value="+49" data-flag="🇩🇪">🇩🇪 +49</option>
                                <option value="+7" data-flag="🇷🇺">🇷🇺 +7</option>
                                <option value="+81" data-flag="🇯🇵">🇯🇵 +81</option>
                                <option value="+82" data-flag="🇰🇷">🇰🇷 +82</option>
                                <option value="+86" data-flag="🇨🇳">🇨🇳 +86</option>
                                <option value="+90" data-flag="🇹🇷">🇹🇷 +90</option>
                                <option value="+91" data-flag="🇮🇳">🇮🇳 +91</option>
                                <option value="+92" data-flag="🇵🇰">🇵🇰 +92</option>
                                <option value="+213" data-flag="🇩🇿">🇩🇿 +213</option>
                                <option value="+216" data-flag="🇹🇳">🇹🇳 +216</option>
                                <option value="+218" data-flag="🇱🇾">🇱🇾 +218</option>
                                <option value="+220" data-flag="🇬🇲">🇬🇲 +220</option>
                                <option value="+223" data-flag="🇲🇱">🇲🇱 +223</option>
                                <option value="+224" data-flag="🇬🇳">🇬🇳 +224</option>
                                <option value="+290" data-flag="🇸🇭">🇸🇭 +290</option>
                                <option value="+291" data-flag="🇪🇷">🇪🇷 +291</option>
                                <option value="+297" data-flag="🇦🇼">🇦🇼 +297</option>
                                <option value="+298" data-flag="🇫🇴">🇫🇴 +298</option>
                                <option value="+299" data-flag="🇬🇱">🇬🇱 +299</option>
                                <option value="+350" data-flag="🇬🇮">🇬🇮 +350</option>
                                <option value="+351" data-flag="🇵🇹">🇵🇹 +351</option>
                                <option value="+352" data-flag="🇱🇺">🇱🇺 +352</option>
                                <option value="+353" data-flag="🇮🇪">🇮🇪 +353</option>
                                <option value="+354" data-flag="🇮🇸">🇮🇸 +354</option>
                                <option value="+355" data-flag="🇦🇱">🇦🇱 +355</option>
                                <option value="+356" data-flag="🇲🇹">🇲🇹 +356</option>
                                <option value="+357" data-flag="🇨🇾">🇨🇾 +357</option>
                                <option value="+358" data-flag="🇫🇮">🇫🇮 +358</option>
                                <option value="+359" data-flag="🇧🇬">🇧🇬 +359</option>
                                <option value="+36" data-flag="🇭🇺">🇭🇺 +36</option>
                                <option value="+370" data-flag="🇱🇹">🇱🇹 +370</option>
                                <option value="+371" data-flag="🇱🇻">🇱🇻 +371</option>
                                <option value="+372" data-flag="🇪🇪">🇪🇪 +372</option>
                                <option value="+373" data-flag="🇲🇩">🇲🇩 +373</option>
                                <option value="+374" data-flag="🇦🇲">🇦🇲 +374</option>
                                <option value="+375" data-flag="🇧🇾">🇧🇾 +375</option>
                                <option value="+376" data-flag="🇦🇩">🇦🇩 +376</option>
                                <option value="+377" data-flag="🇲🇨">🇲🇨 +377</option>
                                <option value="+378" data-flag="🇸🇲">🇸🇲 +378</option>
                                <option value="+380" data-flag="🇺🇦">🇺🇦 +380</option>
                                <option value="+381" data-flag="🇷🇸">🇷🇸 +381</option>
                                <option value="+382" data-flag="🇲🇪">🇲🇪 +382</option>
                                <option value="+383" data-flag="🇽🇰">🇽🇰 +383</option>
                                <option value="+385" data-flag="🇭🇷">🇭🇷 +385</option>
                                <option value="+386" data-flag="🇸🇮">🇸🇮 +386</option>
                                <option value="+387" data-flag="🇧🇦">🇧🇦 +387</option>
                                <option value="+389" data-flag="🇲🇰">🇲🇰 +389</option>
                                <option value="+420" data-flag="🇨🇿">🇨🇿 +420</option>
                                <option value="+421" data-flag="🇸🇰">🇸🇰 +421</option>
                                <option value="+423" data-flag="🇱🇮">🇱🇮 +423</option>
                                <option value="+500" data-flag="🇫🇰">🇫🇰 +500</option>
                                <option value="+501" data-flag="🇧🇿">🇧🇿 +501</option>
                                <option value="+502" data-flag="🇬🇹">🇬🇹 +502</option>
                                <option value="+503" data-flag="🇸🇻">🇸🇻 +503</option>
                                <option value="+504" data-flag="🇭🇳">🇭🇳 +504</option>
                                <option value="+505" data-flag="🇳🇮">🇳🇮 +505</option>
                                <option value="+506" data-flag="🇨🇷">🇨🇷 +506</option>
                                <option value="+507" data-flag="🇵🇦">🇵🇦 +507</option>
                                <option value="+508" data-flag="🇵🇲">🇵🇲 +508</option>
                                <option value="+509" data-flag="🇭🇹">🇭🇹 +509</option>
                                <option value="+590" data-flag="🇬🇵">🇬🇵 +590</option>
                                <option value="+591" data-flag="🇧🇴">🇧🇴 +591</option>
                                <option value="+592" data-flag="🇬🇾">🇬🇾 +592</option>
                                <option value="+593" data-flag="🇪🇨">🇪🇨 +593</option>
                                <option value="+594" data-flag="🇬🇫">🇬🇫 +594</option>
                                <option value="+595" data-flag="🇵🇾">🇵🇾 +595</option>
                                <option value="+596" data-flag="🇲🇶">🇲🇶 +596</option>
                                <option value="+597" data-flag="🇸🇷">🇸🇷 +597</option>
                                <option value="+598" data-flag="🇺🇾">🇺🇾 +598</option>
                                <option value="+599" data-flag="🇧🇶">🇧🇶 +599</option>
                                <option value="+670" data-flag="🇹🇱">🇹🇱 +670</option>
                                <option value="+672" data-flag="🇦🇶">🇦🇶 +672</option>
                                <option value="+673" data-flag="🇧🇳">🇧🇳 +673</option>
                                <option value="+674" data-flag="🇳🇷">🇳🇷 +674</option>
                                <option value="+675" data-flag="🇵🇬">🇵🇬 +675</option>
                                <option value="+676" data-flag="🇹🇴">🇹🇴 +676</option>
                                <option value="+677" data-flag="🇸🇧">🇸🇧 +677</option>
                                <option value="+678" data-flag="🇻🇺">🇻🇺 +678</option>
                                <option value="+679" data-flag="🇫🇯">🇫🇯 +679</option>
                                <option value="+680" data-flag="🇵🇼">🇵🇼 +680</option>
                                <option value="+681" data-flag="🇼🇫">🇼🇫 +681</option>
                                <option value="+682" data-flag="🇨🇰">🇨🇰 +682</option>
                                <option value="+683" data-flag="🇳🇺">🇳🇺 +683</option>
                                <option value="+685" data-flag="🇼🇸">🇼🇸 +685</option>
                                <option value="+686" data-flag="🇰🇮">🇰🇮 +686</option>
                                <option value="+687" data-flag="🇳🇨">🇳🇨 +687</option>
                                <option value="+688" data-flag="🇹🇻">🇹🇻 +688</option>
                                <option value="+689" data-flag="🇵🇫">🇵🇫 +689</option>
                                <option value="+850" data-flag="🇰🇵">🇰🇵 +850</option>
                                <option value="+852" data-flag="🇭🇰">🇭🇰 +852</option>
                                <option value="+853" data-flag="🇲🇴">🇲🇴 +853</option>
                                <option value="+855" data-flag="🇰🇭">🇰🇭 +855</option>
                                <option value="+856" data-flag="🇱🇦">🇱🇦 +856</option>
                                <option value="+880" data-flag="🇧🇩">🇧🇩 +880</option>
                                <option value="+886" data-flag="🇹🇼">🇹🇼 +886</option>
                                <option value="+960" data-flag="🇲🇻">🇲🇻 +960</option>
                                <option value="+961" data-flag="🇱🇧">🇱🇧 +961</option>
                                <option value="+962" data-flag="🇯🇴">🇯🇴 +962</option>
                                <option value="+963" data-flag="🇸🇾">🇸🇾 +963</option>
                                <option value="+964" data-flag="🇮🇶">🇮🇶 +964</option>
                                <option value="+965" data-flag="🇰🇼">🇰🇼 +965</option>
                                <option value="+966" data-flag="🇸🇦">🇸🇦 +966</option>
                                <option value="+967" data-flag="🇾🇪">🇾🇪 +967</option>
                                <option value="+968" data-flag="🇴🇲">🇴🇲 +968</option>
                                <option value="+970" data-flag="🇵🇸">🇵🇸 +970</option>
                                <option value="+971" data-flag="🇦🇪">🇦🇪 +971</option>
                                <option value="+972" data-flag="🇮🇱">🇮🇱 +972</option>
                                <option value="+973" data-flag="🇧🇭">🇧🇭 +973</option>
                                <option value="+974" data-flag="🇶🇦">🇶🇦 +974</option>
                                <option value="+975" data-flag="🇧🇹">🇧🇹 +975</option>
                                <option value="+976" data-flag="🇲🇳">🇲🇳 +976</option>
                                <option value="+977" data-flag="🇳🇵">🇳🇵 +977</option>
                                <option value="+992" data-flag="🇹🇯">🇹🇯 +992</option>
                                <option value="+993" data-flag="🇹🇲">🇹🇲 +993</option>
                                <option value="+994" data-flag="🇦🇿">🇦🇿 +994</option>
                                <option value="+995" data-flag="🇬🇪">🇬🇪 +995</option>
                                <option value="+996" data-flag="🇰🇬">🇰🇬 +996</option>
                                <option value="+998" data-flag="🇺🇿">🇺🇿 +998</option>
                            </select>
                            <div class="field-input-wrap">
                                <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="6" y="2.5" width="12" height="19" rx="2.5"/>
                                    <line x1="10" y1="18.5" x2="14" y2="18.5"/>
                                </svg>
                                <input type="tel" id="phone_number" name="phone" value="{{ old('phone') }}"
                                       autocomplete="tel" class="field-input"
                                       placeholder="{{ trans('app.auth.register.phone_number') }}">
                            </div>
                        </div>
                        <input type="hidden" name="phone_full" id="phone_full">
                        @error('phone')<div class="field-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="field-row">
                    <div class="field">
                        <label for="reg-password">{{ trans('app.auth.register.password') }} *</label>
                        <div class="field-input-wrap">
                            <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="5" y="10.5" width="14" height="9.5" rx="2.5"/>
                                <path d="M8 10.5V7.75a4 4 0 0 1 8 0v2.75"/>
                            </svg>
                            <input type="password" id="reg-password" name="password" required minlength="6"
                                   autocomplete="new-password" class="field-input" placeholder="••••••••">
                            <button type="button" class="field-toggle" data-toggle-target="reg-password" aria-label="Afficher le mot de passe">
                                <svg class="icon-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M2.5 12Q7 4.5 12 4.5Q17 4.5 21.5 12Q17 19.5 12 19.5Q7 19.5 2.5 12Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                <svg class="icon-eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" hidden>
                                    <path d="M2.5 12Q7 4.5 12 4.5Q17 4.5 21.5 12Q17 19.5 12 19.5Q7 19.5 2.5 12Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                    <line x1="4" y1="4" x2="20" y2="20"/>
                                </svg>
                            </button>
                        </div>
                        <div class="field-help">6 caractères minimum</div>
                        @error('password')<div class="field-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="field">
                        <label for="reg-password-confirm">{{ trans('app.auth.register.confirm_password') }} *</label>
                        <div class="field-input-wrap">
                            <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="5" y="10.5" width="14" height="9.5" rx="2.5"/>
                                <path d="M8 10.5V7.75a4 4 0 0 1 8 0v2.75"/>
                            </svg>
                            <input type="password" id="reg-password-confirm" name="password_confirmation" required minlength="6"
                                   autocomplete="new-password" class="field-input" placeholder="••••••••">
                            <button type="button" class="field-toggle" data-toggle-target="reg-password-confirm" aria-label="Afficher le mot de passe">
                                <svg class="icon-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M2.5 12Q7 4.5 12 4.5Q17 4.5 21.5 12Q17 19.5 12 19.5Q7 19.5 2.5 12Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                <svg class="icon-eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" hidden>
                                    <path d="M2.5 12Q7 4.5 12 4.5Q17 4.5 21.5 12Q17 19.5 12 19.5Q7 19.5 2.5 12Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                    <line x1="4" y1="4" x2="20" y2="20"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    </div>

                    <button type="submit" class="register-submit" id="registerSubmit">
                        <span class="btn-label">{{ trans('app.auth.register.button') }}</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M4 12h16M14 6l6 6-6 6"/>
                        </svg>
                    </button>
                </form>

                <div class="register-footer">
                    <p>{{ trans('app.auth.register.has_account') }}</p>
                    <a href="{{ route('login') }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M4 12h16M14 6l6 6-6 6"/>
                        </svg>
                        {{ trans('app.auth.register.login') }}
                    </a>
                </div>
            </div>
        </main>

        <aside class="register-brand">
            <span class="register-brand-eyebrow">Communauté Sénégal</span>
            <h2>Rejoins des milliers d'étudiants qui préparent leurs examens sereinement</h2>
            <p>Épreuves corrigées, bourses d'études, offres d'emploi et ressources pédagogiques : tout ce qu'il te faut pour avancer, réuni au même endroit.</p>
            <ul class="register-brand-features">
                <li>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M8.5 12.5l2.5 2.5 5-5"/>
                    </svg>
                    Épreuves et corrigés officiels
                </li>
                <li>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M8.5 12.5l2.5 2.5 5-5"/>
                    </svg>
                    Bourses d'études &amp; offres d'emploi
                </li>
                <li>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M8.5 12.5l2.5 2.5 5-5"/>
                    </svg>
                    Ressources pédagogiques illimitées
                </li>
                <li>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M8.5 12.5l2.5 2.5 5-5"/>
                    </svg>
                    Compte gratuit, sans engagement
                </li>
            </ul>
        </aside>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // ── Combinaison indicatif + numéro de téléphone ──────────────────
    const countrySelect = document.getElementById('phone_country');
    const phoneInput = document.getElementById('phone_number');
    const phoneFullInput = document.getElementById('phone_full');

    function updateFullPhone() {
        const phoneNumber = phoneInput.value.trim();
        phoneFullInput.value = phoneNumber ? countrySelect.value + phoneNumber : '';
    }

    countrySelect.addEventListener('change', updateFullPhone);
    phoneInput.addEventListener('input', updateFullPhone);

    const existingPhone = phoneInput.value;
    if (existingPhone && existingPhone.startsWith('+')) {
        for (const option of countrySelect.options) {
            if (existingPhone.startsWith(option.value)) {
                countrySelect.value = option.value;
                phoneInput.value = existingPhone.substring(option.value.length);
                break;
            }
        }
    }
    updateFullPhone();

    // ── Afficher/masquer les mots de passe ───────────────────────────
    document.querySelectorAll('.field-toggle[data-toggle-target]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const input = document.getElementById(btn.dataset.toggleTarget);
            if (!input) return;
            const showing = input.type === 'password';
            input.type = showing ? 'text' : 'password';
            btn.querySelector('.icon-eye').hidden = showing;
            btn.querySelector('.icon-eye-off').hidden = !showing;
            btn.setAttribute('aria-label', showing ? 'Masquer le mot de passe' : 'Afficher le mot de passe');
        });
    });

    // ── Retour visuel pendant la soumission ───────────────────────────
    const form = document.getElementById('registerForm');
    const submitBtn = document.getElementById('registerSubmit');
    form.addEventListener('submit', function () {
        if (!form.checkValidity()) return;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner"></span><span>Création du compte...</span>';
    });
});
</script>
@endsection
