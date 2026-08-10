@extends('layouts.app')

@push('page_css')
@vite('resources/css/features/login.css')
@endpush

@section('title', 'Vérification du code | NiangProgrammeur')

@section('content')
<div class="lg-page">

  {{-- ── PANNEAU GAUCHE — Branding ─────────────────────── --}}
  <div class="lg-brand">
    <div class="lg-brand__content">

      <h2 class="lg-brand__headline">
        Un dernier<br>
        <span class="lg-brand__headline-grad">code</span><br>
        et c'est fait
      </h2>
      <p class="lg-brand__sub">
        Entrez le code à 6 chiffres reçu par e-mail ({{ $email }}) et choisissez votre nouveau mot de passe.
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
          Vérifiez
          <span>votre code</span>
        </h1>
        <p class="lg-form-subtitle">Code envoyé à <strong>{{ $email }}</strong>. Valable 10 minutes.</p>
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

      <form action="{{ route('password.otp.verify') }}" method="POST" class="lg-form">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">

        <div class="lg-field">
          <label class="lg-label" for="lg-otp">
            Code de vérification
          </label>
          <div class="lg-input-wrap">
            <input
              type="text"
              id="lg-otp"
              name="otp"
              required
              inputmode="numeric"
              autocomplete="one-time-code"
              maxlength="6"
              pattern="[0-9]{6}"
              class="lg-input"
              placeholder="123456"
              style="letter-spacing:.5rem;font-weight:700;text-align:center;padding-left:1rem;"
            >
          </div>
        </div>

        <div class="lg-field">
          <label class="lg-label" for="lg-new-password">
            Nouveau mot de passe
          </label>
          <div class="lg-input-wrap">
            <input
              type="password"
              id="lg-new-password"
              name="password"
              required
              minlength="8"
              autocomplete="new-password"
              class="lg-input lg-input--pwd"
              placeholder="••••••••"
            >
            <i class="fas fa-lock lg-input-icon"></i>
            <button type="button" class="lg-pwd-toggle" onclick="votTogglePwd('lg-new-password', this)" aria-label="Afficher le mot de passe">
              <i class="fas fa-eye"></i>
            </button>
          </div>
        </div>

        <div class="lg-field">
          <label class="lg-label" for="lg-new-password-confirm">
            Confirmer le mot de passe
          </label>
          <div class="lg-input-wrap">
            <input
              type="password"
              id="lg-new-password-confirm"
              name="password_confirmation"
              required
              minlength="8"
              autocomplete="new-password"
              class="lg-input lg-input--pwd"
              placeholder="••••••••"
            >
            <i class="fas fa-lock lg-input-icon"></i>
            <button type="button" class="lg-pwd-toggle" onclick="votTogglePwd('lg-new-password-confirm', this)" aria-label="Afficher le mot de passe">
              <i class="fas fa-eye"></i>
            </button>
          </div>
        </div>

        <button type="submit" class="lg-submit">
          <i class="fas fa-key"></i>
          Réinitialiser le mot de passe
        </button>

        <div class="lg-register">
          <span>Pas reçu de code ?</span>
          <a href="{{ route('password.request') }}">Renvoyer</a>
        </div>

      </form>

    </div>
  </div>

</div>

<script>
function votTogglePwd(inputId, btn) {
  const input = document.getElementById(inputId);
  const icon = btn.querySelector('i');
  if (input.type === 'password') {
    input.type = 'text';
    icon.className = 'fas fa-eye-slash';
  } else {
    input.type = 'password';
    icon.className = 'fas fa-eye';
  }
}
</script>
@endsection
