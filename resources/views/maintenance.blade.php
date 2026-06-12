<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ __('maintenance.title') }}</title>
  <meta name="robots" content="noindex, nofollow">
  <link rel="icon" type="image/png" href="{{ \App\Models\SiteSetting::logoUrl() }}">
  <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@700;800;900&display=swap">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --cyan:   #06b6d4;
      --teal:   #14b8a6;
      --dark:   #0f172a;
      --darker: #060e1e;
      --card:   rgba(255,255,255,.04);
      --border: rgba(255,255,255,.08);
    }

    body {
      min-height: 100vh;
      background: var(--darker);
      font-family: 'Inter', sans-serif;
      color: #e2e8f0;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 2rem 1rem;
      overflow-x: hidden;
    }

    /* ── Animated background ── */
    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background:
        radial-gradient(ellipse 80% 50% at 50% -20%, oklch(65% .20 200 / .18) 0%, transparent 70%),
        radial-gradient(ellipse 60% 40% at 80% 80%, oklch(65% .18 170 / .12) 0%, transparent 60%);
      pointer-events: none;
      z-index: 0;
    }

    /* Animated grid */
    body::after {
      content: '';
      position: fixed;
      inset: 0;
      background-image:
        linear-gradient(rgba(6,182,212,.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(6,182,212,.04) 1px, transparent 1px);
      background-size: 60px 60px;
      pointer-events: none;
      z-index: 0;
      animation: gridMove 20s linear infinite;
    }
    @keyframes gridMove { to { background-position: 60px 60px; } }

    /* ── Admin banner ── */
    .adm-banner {
      position: fixed;
      top: 0; left: 0; right: 0;
      background: linear-gradient(90deg, oklch(65% .20 200), oklch(65% .18 170));
      color: #000;
      font-size: .82rem;
      font-weight: 600;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
      padding: .6rem 1.5rem;
      z-index: 100;
      flex-wrap: wrap;
    }
    .adm-banner a {
      color: #000;
      font-weight: 700;
      text-decoration: none;
      background: rgba(0,0,0,.15);
      padding: .25rem .75rem;
      border-radius: 999px;
      white-space: nowrap;
    }
    .adm-banner a:hover { background: rgba(0,0,0,.3); }

    /* ── Card ── */
    .card {
      position: relative;
      z-index: 1;
      width: 100%;
      max-width: 560px;
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 24px;
      padding: 3rem 2.5rem;
      text-align: center;
      backdrop-filter: blur(20px);
      box-shadow: 0 32px 80px rgba(0,0,0,.4);
    }

    /* ── Icon ── */
    .icon-wrap {
      width: 88px;
      height: 88px;
      border-radius: 50%;
      background: linear-gradient(135deg, oklch(65% .20 200 / .15), oklch(65% .18 170 / .15));
      border: 2px solid oklch(65% .20 200 / .3);
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 2rem;
      animation: pulse 3s ease-in-out infinite;
    }
    @keyframes pulse {
      0%,100% { box-shadow: 0 0 0 0 oklch(65% .20 200 / .3); }
      50%      { box-shadow: 0 0 0 16px oklch(65% .20 200 / 0); }
    }
    .icon-wrap i { font-size: 2.25rem; color: var(--cyan); }

    /* ── Logo ── */
    .logo {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: .6rem;
      margin-bottom: 2rem;
      text-decoration: none;
      color: inherit;
    }
    .logo img { width: 36px; height: 36px; border-radius: 8px; }
    .logo span { font-family: 'Poppins', sans-serif; font-weight: 800; font-size: 1.1rem; }

    h1 {
      font-family: 'Poppins', sans-serif;
      font-size: clamp(1.75rem, 4vw, 2.5rem);
      font-weight: 900;
      background: linear-gradient(135deg, #fff 0%, oklch(65% .20 200) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      line-height: 1.2;
      margin-bottom: .5rem;
    }

    .subheading {
      font-size: 1.05rem;
      color: rgba(255,255,255,.55);
      margin-bottom: 1.75rem;
    }

    /* ── Message ── */
    .message {
      background: rgba(6,182,212,.06);
      border: 1px solid rgba(6,182,212,.18);
      border-radius: 14px;
      padding: 1.25rem 1.5rem;
      font-size: .9375rem;
      color: rgba(255,255,255,.8);
      line-height: 1.7;
      margin-bottom: 2rem;
      text-align: left;
    }

    /* ── Countdown ── */
    .countdown-row {
      display: flex;
      align-items: center;
      gap: .5rem;
      justify-content: center;
      font-size: .82rem;
      color: rgba(255,255,255,.45);
      margin-bottom: 2rem;
    }
    .countdown-row i { color: var(--cyan); }
    .countdown-row strong { color: rgba(255,255,255,.7); }

    /* ── Info blocks ── */
    .info-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
      margin-bottom: 2rem;
    }
    @media (max-width: 420px) { .info-grid { grid-template-columns: 1fr; } }

    .info-block {
      background: rgba(255,255,255,.03);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 1.125rem 1rem;
      text-align: left;
    }
    .info-block__title {
      font-size: .75rem;
      font-weight: 700;
      color: var(--cyan);
      text-transform: uppercase;
      letter-spacing: .06em;
      margin-bottom: .4rem;
    }
    .info-block__desc {
      font-size: .8125rem;
      color: rgba(255,255,255,.5);
      line-height: 1.5;
      margin-bottom: .75rem;
    }
    .info-block a {
      font-size: .8rem;
      font-weight: 600;
      color: var(--cyan);
      text-decoration: none;
    }
    .info-block a:hover { text-decoration: underline; }

    /* ── Social ── */
    .socials {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: .6rem;
      flex-wrap: wrap;
    }
    .social-btn {
      width: 38px; height: 38px;
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      background: rgba(255,255,255,.05);
      border: 1px solid var(--border);
      color: rgba(255,255,255,.6);
      text-decoration: none;
      font-size: .9rem;
      transition: all .2s ease;
    }
    .social-btn:hover { background: var(--cyan); color: #000; border-color: var(--cyan); transform: translateY(-2px); }

    /* ── Lang switcher ── */
    .lang-row {
      display: flex;
      justify-content: center;
      gap: .5rem;
      margin-top: 1.75rem;
    }
    .lang-btn {
      padding: .35rem .9rem;
      border-radius: 999px;
      font-size: .78rem;
      font-weight: 600;
      text-decoration: none;
      border: 1px solid var(--border);
      color: rgba(255,255,255,.5);
      transition: all .2s ease;
    }
    .lang-btn:hover, .lang-btn.active {
      border-color: var(--cyan);
      color: var(--cyan);
      background: oklch(65% .20 200 / .08);
    }
  </style>
</head>
<body>
@php
  // Appliquer la locale depuis la session
  $locale = session('language', 'fr');
  if (in_array($locale, ['fr', 'en'])) { app()->setLocale($locale); }

  $siteSettings = $settings ?? \App\Models\SiteSetting::first();
@endphp

{{-- Bannière admin --}}
@auth
  @if(auth()->user()->isAdmin())
  <div class="adm-banner">
    <span><i class="fas fa-shield-alt" style="margin-right:.4rem"></i>{{ __('maintenance.admin_notice') }}</span>
    <a href="{{ route('admin.settings') }}"><i class="fas fa-tools" style="margin-right:.3rem"></i>{{ __('maintenance.admin_panel') }}</a>
  </div>
  @endif
@endauth

<div class="card" style="{{ auth()->check() && auth()->user()->isAdmin() ? 'margin-top:3.5rem' : '' }}">

  {{-- Logo --}}
  <a href="{{ route('home') }}" class="logo">
    <img src="{{ \App\Models\SiteSetting::logoUrl() }}" alt="Logo">
    <span>NiangProgrammeur</span>
  </a>

  {{-- Icône animée --}}
  <div class="icon-wrap">
    <i class="fas fa-tools"></i>
  </div>

  <h1>{{ __('maintenance.heading') }}</h1>
  <p class="subheading">{{ __('maintenance.subheading') }}</p>

  {{-- Message personnalisé ou par défaut --}}
  <div class="message">
    {{ $siteSettings->maintenance_message ?: __('maintenance.default_msg') }}
  </div>

  {{-- Date de retour si définie --}}
  @if($siteSettings->maintenance_ends_at)
  <div class="countdown-row">
    <i class="fas fa-clock"></i>
    <span>{{ __('maintenance.back_soon') }} :</span>
    <strong>{{ \Carbon\Carbon::parse($siteSettings->maintenance_ends_at)->locale($locale)->isoFormat('D MMM YYYY [à] HH[h]mm') }}</strong>
  </div>
  @endif

  {{-- Blocs info --}}
  <div class="info-grid">
    <div class="info-block">
      <div class="info-block__title">{{ __('maintenance.follow_us') }}</div>
      <div class="info-block__desc">{{ __('maintenance.follow_desc') }}</div>
      <div class="socials">
        @if($siteSettings?->facebook_url)
        <a href="{{ $siteSettings->facebook_url }}" target="_blank" rel="noopener" class="social-btn" title="Facebook"><i class="fab fa-facebook-f"></i></a>
        @endif
        @if($siteSettings?->tiktok_url)
        <a href="{{ $siteSettings->tiktok_url }}" target="_blank" rel="noopener" class="social-btn" title="TikTok"><i class="fab fa-tiktok"></i></a>
        @endif
        @if($siteSettings?->youtube_url)
        <a href="{{ $siteSettings->youtube_url }}" target="_blank" rel="noopener" class="social-btn" title="YouTube"><i class="fab fa-youtube"></i></a>
        @endif
        @if($siteSettings?->github_url)
        <a href="{{ $siteSettings->github_url }}" target="_blank" rel="noopener" class="social-btn" title="GitHub"><i class="fab fa-github"></i></a>
        @endif
      </div>
    </div>

    <div class="info-block">
      <div class="info-block__title">{{ __('maintenance.contact') }}</div>
      <div class="info-block__desc">{{ __('maintenance.contact_desc') }}</div>
      @if($siteSettings?->contact_email)
      <a href="mailto:{{ $siteSettings->contact_email }}">
        <i class="fas fa-envelope" style="margin-right:.3rem"></i>{{ $siteSettings->contact_email }}
      </a>
      @endif
    </div>
  </div>

  {{-- Sélecteur de langue --}}
  <div class="lang-row">
    <a href="{{ route('language.set', 'fr') }}?redirect=/maintenance" class="lang-btn {{ $locale === 'fr' ? 'active' : '' }}">🇫🇷 Français</a>
    <a href="{{ route('language.set', 'en') }}?redirect=/maintenance" class="lang-btn {{ $locale === 'en' ? 'active' : '' }}">🇬🇧 English</a>
  </div>

</div>
</body>
</html>
