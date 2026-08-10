@extends('layouts.app')

@push('page_css')
@vite('resources/css/features/login.css')
@endpush

@section('title', 'Mot de passe oublié | NiangProgrammeur')

@section('content')
<div class="lg-page">

  {{-- ── PANNEAU GAUCHE — Branding ─────────────────────── --}}
  <div class="lg-brand">
    <div class="lg-brand__content">

      <h2 class="lg-brand__headline">
        Récupérez<br>
        <span class="lg-brand__headline-grad">l'accès</span><br>
        à votre compte
      </h2>
      <p class="lg-brand__sub">
        Entrez votre adresse e-mail, nous vous envoyons un code de vérification pour réinitialiser votre mot de passe.
      </p>

    </div>
  </div>

  {{-- ── PANNEAU DROIT — Formulaire ──────────────────────── --}}
  <div class="lg-form-panel">
    <div class="lg-form-wrap">

      {{-- Logo mobile --}}
      <a href="{{ route('home') }}" class="lg-mobile-logo">
        <div class="lg-mobile-logo__icon">NP</div>
        <span class="lg-mobile-logo__name">NiangProgrammeur</span>
      </a>

      <div class="lg-form-header">
        <h1 class="lg-form-title">
          Mot de passe
          <span>oublié</span>
        </h1>
        <p class="lg-form-subtitle">Recevez un code de vérification par e-mail pour réinitialiser votre mot de passe.</p>
      </div>

      {{-- Alertes --}}
      @if(session('success'))
      <div class="lg-alert lg-alert--success">
        <i class="fas fa-check-circle" style="margin-top:.1rem;flex-shrink:0"></i>
        <span>{{ session('success') }}</span>
      </div>
      @endif

      @if($errors->any())
      <div class="lg-alert lg-alert--error">
        <i class="fas fa-exclamation-circle" style="margin-top:.15rem;flex-shrink:0"></i>
        <ul>
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif

      <form action="{{ route('password.otp.send') }}" method="POST" class="lg-form">
        @csrf

        <div class="lg-field">
          <label class="lg-label" for="lg-email">
            Adresse e-mail
          </label>
          <div class="lg-input-wrap">
            <input
              type="email"
              id="lg-email"
              name="email"
              value="{{ old('email') }}"
              required
              autocomplete="email"
              class="lg-input"
              placeholder="votre@email.com"
            >
            <i class="fas fa-envelope lg-input-icon"></i>
          </div>
        </div>

        <button type="submit" class="lg-submit">
          <i class="fas fa-paper-plane"></i>
          Envoyer le code
        </button>

        <div class="lg-register">
          <a href="{{ route('login') }}">
            <i class="fas fa-arrow-left"></i>
            Retour à la connexion
          </a>
        </div>

      </form>

    </div>
  </div>

</div>
@endsection
