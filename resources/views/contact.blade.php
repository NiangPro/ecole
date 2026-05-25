@extends('layouts.app')

@section('title', 'Contact — NiangProgrammeur')
@section('meta_description', 'Contactez NiangProgrammeur pour vos questions, projets ou collaborations. Formulaire de contact, email et téléphone disponibles. Réponse sous 24h.')
@section('meta_keywords', 'contact NiangProgrammeur, contacter développeur, projet web, collaboration développement, devis site web')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.3/build/css/intlTelInput.css">
@endsection

@section('content')
<div class="ct-page">

  {{-- ── HERO ───────────────────────────────────────────────── --}}
  <div class="ct-hero">
    <div class="ct-hero__eyebrow">
      <span class="ct-hero__dot"></span>
      Disponible & Réactif
    </div>
    <h1 class="ct-hero__title">
      Prenons <span>Contact</span>
    </h1>
    <p class="ct-hero__sub">
      Une question sur nos formations, un projet de développement, ou une idée de collaboration ?
      Je lis tous les messages et réponds sous 24h. N'hésitez pas.
    </p>
    <div class="ct-hero__chips">
      <span class="ct-info-chip">
        <i class="fas fa-clock"></i>
        Réponse &lt; 24h
      </span>
      <a href="mailto:{{ optional($siteSettings)->contact_email ?? 'NiangProgrammeur@gmail.com' }}" class="ct-info-chip">
        <i class="fas fa-envelope"></i>
        {{ optional($siteSettings)->contact_email ?? 'NiangProgrammeur@gmail.com' }}
      </a>
      <a href="tel:{{ str_replace(' ', '', optional($siteSettings)->contact_phone ?? '+221783123657') }}" class="ct-info-chip">
        <i class="fas fa-phone"></i>
        {{ optional($siteSettings)->contact_phone ?? '+221 78 312 36 57' }}
      </a>
    </div>
  </div>

  {{-- ── MAIN GRID ──────────────────────────────────────────── --}}
  <div class="ct-main">
    <div class="ct-grid">

      {{-- ── FORM ─────────────────────────────────────────── --}}
      <div class="ct-form-card">
        <h2 class="ct-form-title">
          <i class="fas fa-paper-plane"></i>
          Envoyez-moi un message
        </h2>

        @if(session('success'))
        <div class="ct-alert--success">
          <i class="fas fa-check-circle"></i>
          <span>{{ session('success') }}</span>
        </div>
        @endif

        <form action="{{ route('contact.send') }}" method="POST" id="contact-form">
          @csrf

          {{-- Honeypot --}}
          <div style="position:absolute;left:-9999px;opacity:0;pointer-events:none;visibility:hidden;" aria-hidden="true">
            <label for="website">Website (ne pas remplir)</label>
            <input type="text" name="website" id="website" autocomplete="off" tabindex="-1">
          </div>

          {{-- Time field (anti-bot) --}}
          <input type="hidden" name="_form_time" id="form-time" value="{{ microtime(true) }}">

          <div class="ct-fields">

            <div class="ct-field">
              <label class="ct-label" for="name">
                <i class="fas fa-user"></i>
                Nom complet
                <span class="ct-label__req">*</span>
              </label>
              <input class="ct-input" type="text" id="name" name="name" required placeholder="Votre nom">
            </div>

            <div class="ct-field">
              <label class="ct-label" for="email">
                <i class="fas fa-envelope"></i>
                Email
                <span class="ct-label__req">*</span>
              </label>
              <input class="ct-input" type="email" id="email" name="email" required placeholder="votre@email.com">
            </div>

            <div class="ct-field">
              <label class="ct-label" for="phone">
                <i class="fas fa-phone"></i>
                Téléphone
              </label>
              <input class="ct-input" type="tel" id="phone" name="phone" placeholder="">
              <input type="hidden" id="phone_country" name="phone_country" value="">
            </div>

            <div class="ct-field">
              <label class="ct-label" for="subject">
                <i class="fas fa-tag"></i>
                Sujet
                <span class="ct-label__req">*</span>
              </label>
              <input class="ct-input" type="text" id="subject" name="subject" required placeholder="Objet de votre message">
            </div>

            <div class="ct-field">
              <label class="ct-label" for="message">
                <i class="fas fa-comment"></i>
                Message
                <span class="ct-label__req">*</span>
              </label>
              <textarea class="ct-textarea" id="message" name="message" required placeholder="Votre message..."></textarea>
            </div>

            <button type="submit" class="ct-submit"
              onclick="event.preventDefault(); if (typeof executeRecaptcha === 'function') { executeRecaptcha('contact-form', function() { document.getElementById('contact-form').submit(); }); } else { document.getElementById('contact-form').submit(); }">
              <i class="fas fa-paper-plane"></i>
              Envoyer le message
            </button>

          </div>
        </form>
      </div>

      {{-- ── SIDEBAR ───────────────────────────────────────── --}}
      <aside class="ct-sidebar">

        {{-- Email --}}
        <div class="ct-contact-card">
          <div class="ct-contact-card__icon ct-contact-card__icon--cyan">
            <i class="fas fa-envelope"></i>
          </div>
          <div class="ct-contact-card__body">
            <div class="ct-contact-card__label">Email</div>
            <div class="ct-contact-card__title">Écrivez-moi</div>
            <div class="ct-contact-card__value">
              Pour toute question, demande de devis ou collaboration. Je réponds généralement en moins de 24h.
            </div>
            <a href="mailto:{{ optional($siteSettings)->contact_email ?? 'NiangProgrammeur@gmail.com' }}" class="ct-contact-card__link">
              {{ optional($siteSettings)->contact_email ?? 'NiangProgrammeur@gmail.com' }}
              <i class="fas fa-arrow-right" style="font-size:.65rem"></i>
            </a>
            <div class="ct-response-badge">
              <i class="fas fa-bolt" style="font-size:.6rem"></i>
              Réponse &lt; 24h
            </div>
          </div>
        </div>

        {{-- Phone --}}
        <div class="ct-contact-card">
          <div class="ct-contact-card__icon ct-contact-card__icon--teal">
            <i class="fas fa-phone"></i>
          </div>
          <div class="ct-contact-card__body">
            <div class="ct-contact-card__label">Téléphone &amp; WhatsApp</div>
            <div class="ct-contact-card__title">Appelez-moi</div>
            <div class="ct-contact-card__value">
              Disponible du lundi au vendredi, 9h–18h (Dakar). WhatsApp disponible pour les messages internationaux.
            </div>
            <a href="tel:{{ str_replace(' ', '', optional($siteSettings)->contact_phone ?? '+221783123657') }}" class="ct-contact-card__link">
              {{ optional($siteSettings)->contact_phone ?? '+221 78 312 36 57' }}
              <i class="fas fa-arrow-right" style="font-size:.65rem"></i>
            </a>
          </div>
        </div>

        {{-- Location --}}
        <div class="ct-contact-card">
          <div class="ct-contact-card__icon ct-contact-card__icon--green">
            <i class="fas fa-map-marker-alt"></i>
          </div>
          <div class="ct-contact-card__body">
            <div class="ct-contact-card__label">Localisation</div>
            <div class="ct-contact-card__title">Dakar, Sénégal</div>
            <div class="ct-contact-card__value">
              {{ optional($siteSettings)->contact_address ?? 'Dakar, Sénégal' }} — Je travaille avec des clients du monde entier à distance.
            </div>
          </div>
        </div>

        {{-- Socials --}}
        <div class="ct-social-card">
          <div class="ct-social-card__title">Suivez-moi sur les réseaux</div>
          <div class="ct-socials">

            @php
              $siteSettings = $siteSettings ?? \Illuminate\Support\Facades\Cache::remember('site_settings', 3600, fn() => \App\Models\SiteSetting::first());
            @endphp

            @if($siteSettings && $siteSettings->facebook_url)
            <a href="{{ $siteSettings->facebook_url }}" target="_blank" rel="noopener" class="ct-social-btn ct-social-btn--facebook" title="Facebook">
              <i class="fab fa-facebook-f"></i>
            </a>
            @endif

            @if($siteSettings && $siteSettings->tiktok_url)
            <a href="{{ $siteSettings->tiktok_url }}" target="_blank" rel="noopener" class="ct-social-btn ct-social-btn--tiktok" title="TikTok">
              <i class="fab fa-tiktok"></i>
            </a>
            @endif

            @if($siteSettings && $siteSettings->linkedin_url)
            <a href="{{ $siteSettings->linkedin_url }}" target="_blank" rel="noopener" class="ct-social-btn ct-social-btn--linkedin" title="LinkedIn">
              <i class="fab fa-linkedin-in"></i>
            </a>
            @endif

            @if($siteSettings && $siteSettings->instagram_url)
            <a href="{{ $siteSettings->instagram_url }}" target="_blank" rel="noopener" class="ct-social-btn ct-social-btn--instagram" title="Instagram">
              <i class="fab fa-instagram"></i>
            </a>
            @endif

            @if($siteSettings && $siteSettings->youtube_url)
            <a href="{{ $siteSettings->youtube_url }}" target="_blank" rel="noopener" class="ct-social-btn ct-social-btn--youtube" title="YouTube">
              <i class="fab fa-youtube"></i>
            </a>
            @endif

            @if($siteSettings && $siteSettings->github_url)
            <a href="{{ $siteSettings->github_url }}" target="_blank" rel="noopener" class="ct-social-btn ct-social-btn--github" title="GitHub">
              <i class="fab fa-github"></i>
            </a>
            @endif

          </div>
        </div>

      </aside>
    </div>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.3/build/js/intlTelInput.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  var phoneInput = document.getElementById('phone');
  if (phoneInput && window.intlTelInput) {
    var iti = window.intlTelInput(phoneInput, {
      initialCountry: 'sn',
      preferredCountries: ['sn', 'fr', 'us', 'gb', 'de', 'es', 'it', 'ma', 'ci', 'cm', 'bf', 'ml', 'ne', 'td', 'mr'],
      utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.3/build/js/utils.js',
      separateDialCode: true,
      nationalMode: false,
      autoPlaceholder: 'aggressive',
      formatOnDisplay: true,
    });

    phoneInput.addEventListener('countrychange', function () {
      var f = document.getElementById('phone_country');
      if (f) f.value = iti.getSelectedCountryData().iso2;
    });

    var f = document.getElementById('phone_country');
    if (f) f.value = iti.getSelectedCountryData().iso2;

    var form = document.getElementById('contact-form');
    if (form) {
      form.addEventListener('submit', function (e) {
        if (phoneInput.value.trim()) {
          if (!iti.isValidNumber()) {
            e.preventDefault();
            alert('Numéro de téléphone invalide. Veuillez vérifier le format.');
            phoneInput.focus();
            return false;
          }
          phoneInput.value = iti.getNumber();
        }
      });
    }
  }
});
</script>
@endsection
