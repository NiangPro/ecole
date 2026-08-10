@extends('layouts.app')

@push('page_css')
@vite('resources/css/features/login.css')
@endpush

@section('title', trans('app.auth.login.title') . ' | NiangProgrammeur')

@section('content')
<div class="lg-page">

  {{-- ── PANNEAU GAUCHE — Branding ─────────────────────── --}}
  <div class="lg-brand">
    <div class="lg-brand__content">

      <h2 class="lg-brand__headline">
        Accédez à<br>
        <span class="lg-brand__headline-grad">votre espace</span><br>
        d'apprentissage
      </h2>
      <p class="lg-brand__sub">
        Formations gratuites, articles tech, offres d'emploi et documents téléchargeables — tout en un seul endroit.
      </p>

      <div class="lg-features">
        <div class="lg-feature">
          <div class="lg-feature__icon"><i class="fas fa-play-circle"></i></div>
          <div>
            <div class="lg-feature__text">Formations gratuites</div>
            <div class="lg-feature__sub">HTML, CSS, JS, Laravel, Python…</div>
          </div>
        </div>
        <div class="lg-feature">
          <div class="lg-feature__icon"><i class="fas fa-newspaper"></i></div>
          <div>
            <div class="lg-feature__text">Articles &amp; tutoriels</div>
            <div class="lg-feature__sub">Contenu mis à jour chaque semaine</div>
          </div>
        </div>
        <div class="lg-feature">
          <div class="lg-feature__icon"><i class="fas fa-briefcase"></i></div>
          <div>
            <div class="lg-feature__text">Offres d'emploi tech</div>
            <div class="lg-feature__sub">Dakar, remote et international</div>
          </div>
        </div>
        <div class="lg-feature">
          <div class="lg-feature__icon"><i class="fas fa-file-download"></i></div>
          <div>
            <div class="lg-feature__text">Documents téléchargeables</div>
            <div class="lg-feature__sub">CV, exercices, fiches de révision</div>
          </div>
        </div>
      </div>

      <div class="lg-brand__dots">
        <div class="lg-brand__dot lg-brand__dot--active"></div>
        <div class="lg-brand__dot"></div>
        <div class="lg-brand__dot"></div>
      </div>

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
          {{ trans('app.auth.login.title') }}
          <span>votre compte</span>
        </h1>
        <p class="lg-form-subtitle">{{ trans('app.auth.login.subtitle') }}</p>
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

      {{-- Formulaire --}}
      <form action="{{ route('login.post') }}" method="POST" class="lg-form">
        @csrf

        {{-- E-mail --}}
        <div class="lg-field">
          <label class="lg-label" for="lg-email">
            {{ trans('app.auth.login.email') }}
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

        {{-- Mot de passe --}}
        <div class="lg-field">
          <label class="lg-label" for="lg-password">
            {{ trans('app.auth.login.password') }}
          </label>
          <div class="lg-input-wrap">
            <input
              type="password"
              id="lg-password"
              name="password"
              required
              autocomplete="current-password"
              class="lg-input lg-input--pwd"
              placeholder="••••••••"
            >
            <i class="fas fa-lock lg-input-icon"></i>
            <button type="button" class="lg-pwd-toggle" onclick="lgTogglePwd()"
                    aria-label="{{ trans('app.auth.login.password') }}">
              <i class="fas fa-eye" id="lg-pwd-eye"></i>
            </button>
          </div>
        </div>

        {{-- Se souvenir + mot de passe oublié --}}
        <div class="lg-row">
          <label class="lg-remember">
            <input type="checkbox" name="remember" class="lg-remember__box">
            <span class="lg-remember__label">{{ trans('app.auth.login.remember') }}</span>
          </label>
          @if(Route::has('password.request'))
          <a href="{{ route('password.request') }}" class="lg-forgot">
            {{ trans('app.auth.login.forgot') }}
          </a>
          @endif
        </div>

        {{-- Bouton --}}
        <button type="submit" class="lg-submit">
          <i class="fas fa-sign-in-alt"></i>
          {{ trans('app.auth.login.button') }}
        </button>

        <div class="lg-divider">ou</div>

        <div class="lg-social-group">
          <a href="{{ route('auth.google.redirect') }}" class="lg-social-btn" aria-label="Continuer avec Google" title="Continuer avec Google">
            <svg width="20" height="20" viewBox="0 0 18 18" aria-hidden="true">
              <path fill="#4285F4" d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844a4.14 4.14 0 0 1-1.796 2.716v2.259h2.908c1.702-1.567 2.684-3.875 2.684-6.615z"/>
              <path fill="#34A853" d="M9 18c2.43 0 4.467-.806 5.956-2.184l-2.908-2.259c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 0 0 9 18z"/>
              <path fill="#FBBC05" d="M3.964 10.706A5.41 5.41 0 0 1 3.68 9c0-.593.102-1.17.284-1.706V4.962H.957A8.996 8.996 0 0 0 0 9c0 1.452.348 2.827.957 4.038l3.007-2.332z"/>
              <path fill="#EA4335" d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0A8.997 8.997 0 0 0 .957 4.962L3.964 7.294C4.672 5.167 6.656 3.58 9 3.58z"/>
            </svg>
          </a>

          <a href="{{ route('auth.github.redirect') }}" class="lg-social-btn" aria-label="Continuer avec GitHub" title="Continuer avec GitHub">
            <i class="fab fa-github social-icon-github" style="font-size:20px"></i>
          </a>

          <a href="{{ route('auth.facebook.redirect') }}" class="lg-social-btn" aria-label="Continuer avec Facebook" title="Continuer avec Facebook">
            <i class="fab fa-facebook social-icon-facebook" style="font-size:20px"></i>
          </a>
        </div>

        <div class="lg-register">
          <span>{{ trans('app.auth.login.no_account') }}</span>
          <a href="{{ route('register') }}">{{ trans('app.auth.login.create_account') }}</a>
        </div>

      </form>

      <div class="lg-back">
        <a href="{{ route('home') }}">
          <i class="fas fa-arrow-left"></i>
          {{ trans('app.auth.login.back_home') }}
        </a>
      </div>

    </div>
  </div>

</div>

<script>
function lgTogglePwd() {
  const input = document.getElementById('lg-password');
  const icon  = document.getElementById('lg-pwd-eye');
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
