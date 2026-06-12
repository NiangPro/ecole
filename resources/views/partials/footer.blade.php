@php
    $siteSettings = \Illuminate\Support\Facades\Cache::remember('site_settings', 3600, function () {
        return \App\Models\SiteSetting::first();
    });
    $siteName = $siteSettings->site_name ?? 'NiangProgrammeur';
@endphp

<footer class="ft" id="footer" role="contentinfo" aria-label="Pied de page">

  {{-- Ligne déco top --}}
  <div class="ft__topline" aria-hidden="true"></div>

  <div class="ft__inner">

    {{-- GRILLE PRINCIPALE --}}
    <div class="ft__grid">

      {{-- COL 1 — Marque --}}
      <div class="ft__col ft__col--brand">
        <a href="{{ route('home') }}" class="ft__logo-wrap">
          <img src="{{ \App\Models\SiteSetting::logoUrl() }}" alt="Logo {{ $siteName }}" class="ft__logo-img" width="44" height="44">
          <span class="ft__logo-name">{{ $siteName }}</span>
        </a>
        <p class="ft__tagline">
          {{ $siteSettings->site_description ?? 'Plateforme de formation gratuite en développement web. Apprenez à coder et lancez votre carrière.' }}
        </p>

        {{-- Réseaux sociaux --}}
        <div class="ft__socials">
          @if($siteSettings?->facebook_url)
          <a href="{{ $siteSettings->facebook_url }}" target="_blank" rel="noopener noreferrer" class="ft__social" aria-label="Facebook">
            <i class="fab fa-facebook-f"></i>
          </a>
          @endif
          @if($siteSettings?->tiktok_url)
          <a href="{{ $siteSettings->tiktok_url }}" target="_blank" rel="noopener noreferrer" class="ft__social" aria-label="TikTok">
            <i class="fab fa-tiktok"></i>
          </a>
          @endif
          @if($siteSettings?->youtube_url)
          <a href="{{ $siteSettings->youtube_url }}" target="_blank" rel="noopener noreferrer" class="ft__social" aria-label="YouTube">
            <i class="fab fa-youtube"></i>
          </a>
          @endif
          @if($siteSettings?->linkedin_url)
          <a href="{{ $siteSettings->linkedin_url }}" target="_blank" rel="noopener noreferrer" class="ft__social" aria-label="LinkedIn">
            <i class="fab fa-linkedin-in"></i>
          </a>
          @endif
          @if($siteSettings?->instagram_url)
          <a href="{{ $siteSettings->instagram_url }}" target="_blank" rel="noopener noreferrer" class="ft__social" aria-label="Instagram">
            <i class="fab fa-instagram"></i>
          </a>
          @endif
          @if($siteSettings?->github_url)
          <a href="{{ $siteSettings->github_url }}" target="_blank" rel="noopener noreferrer" class="ft__social" aria-label="GitHub">
            <i class="fab fa-github"></i>
          </a>
          @endif
        </div>
      </div>

      {{-- COL 2 — Navigation --}}
      <div class="ft__col">
        <h3 class="ft__col-title">Navigation</h3>
        <nav class="ft__nav" aria-label="Liens rapides">
          <a href="{{ route('home') }}"             class="ft__link"><i class="fas fa-home"></i> Accueil</a>
          <a href="{{ route('formations.all') }}"   class="ft__link"><i class="fas fa-graduation-cap"></i> Formations</a>
          <a href="{{ route('emplois') }}"          class="ft__link"><i class="fas fa-newspaper"></i> Articles</a>
          <a href="{{ route('documents.index') }}"  class="ft__link"><i class="fas fa-file-download"></i> Documents</a>
          <a href="{{ route('exercices') }}"        class="ft__link"><i class="fas fa-code"></i> Exercices</a>
          <a href="{{ route('quiz') }}"             class="ft__link"><i class="fas fa-question-circle"></i> Quiz</a>
          <a href="{{ route('about') }}"            class="ft__link"><i class="fas fa-info-circle"></i> À propos</a>
          <a href="{{ route('contact') }}"          class="ft__link"><i class="fas fa-envelope"></i> Contact</a>
        </nav>
      </div>

      {{-- COL 3 — Formations --}}
      <div class="ft__col">
        <h3 class="ft__col-title">Technologies</h3>
        <nav class="ft__nav" aria-label="Formations">
          <a href="{{ route('formations.html5') }}"       class="ft__link">🌐 HTML5</a>
          <a href="{{ route('formations.css3') }}"        class="ft__link">🎨 CSS3</a>
          <a href="{{ route('formations.javascript') }}"  class="ft__link">⚡ JavaScript</a>
          <a href="{{ route('formations.php') }}"         class="ft__link">🐘 PHP</a>
          <a href="{{ route('formations.python') }}"      class="ft__link">🐍 Python</a>
          <a href="{{ route('formations.bootstrap') }}"   class="ft__link">🅱 Bootstrap</a>
          <a href="{{ route('formations.ia') }}"          class="ft__link">🤖 IA & Machine Learning</a>
          <a href="{{ route('formations.git') }}"         class="ft__link">🌿 Git & GitHub</a>
        </nav>
      </div>

      {{-- COL 4 — Newsletter --}}
      <div class="ft__col">
        <h3 class="ft__col-title">Newsletter</h3>
        <p class="ft__newsletter-desc">
          {{ trans('app.footer.newsletter_description') ?? 'Recevez nos dernières formations et opportunités directement dans votre boîte mail.' }}
        </p>
        <form id="newsletterForm" class="ft__newsletter-form" novalidate>
          @csrf
          <div class="ft__input-wrap">
            <i class="fas fa-envelope ft__input-icon"></i>
            <input
              type="email"
              name="email"
              id="newsletterEmail"
              placeholder="votre@email.com"
              class="ft__input"
              required
              autocomplete="email"
            >
          </div>
          <button type="submit" class="ft__newsletter-btn" id="newsletterBtn">
            <i class="fas fa-paper-plane"></i>
            S'abonner
          </button>
        </form>
        <div id="newsletterMessage" class="ft__newsletter-msg" role="alert" aria-live="polite"></div>

        {{-- Contact rapide --}}
        @if($siteSettings?->contact_email)
        <div class="ft__contact">
          <a href="mailto:{{ $siteSettings->contact_email }}" class="ft__contact-link">
            <i class="fas fa-envelope-open"></i>
            {{ $siteSettings->contact_email }}
          </a>
          @if($siteSettings->contact_phone)
          <a href="tel:{{ $siteSettings->contact_phone }}" class="ft__contact-link">
            <i class="fas fa-phone"></i>
            {{ $siteSettings->contact_phone }}
          </a>
          @endif
        </div>
        @endif
      </div>

    </div>{{-- /.ft__grid --}}

    {{-- BAS DU FOOTER --}}
    <div class="ft__bottom">
      <div class="ft__legal">
        <a href="{{ route('legal') }}"              class="ft__legal-link">Mentions légales</a>
        <span class="ft__legal-sep" aria-hidden="true"></span>
        <a href="{{ route('privacy-policy') }}"     class="ft__legal-link">Confidentialité</a>
        <span class="ft__legal-sep" aria-hidden="true"></span>
        <a href="{{ route('terms') }}"              class="ft__legal-link">CGU</a>
        <span class="ft__legal-sep" aria-hidden="true"></span>
        <a href="{{ route('faq') }}"                class="ft__legal-link">FAQ</a>
        <span class="ft__legal-sep" aria-hidden="true"></span>
        <a href="{{ route('monetization.donations') }}" class="ft__legal-link ft__legal-link--heart">
          <i class="fas fa-heart"></i> Faire un don
        </a>
      </div>
      <p class="ft__copyright">
        &copy; {{ date('Y') }} <strong>{{ $siteName }}</strong> — Tous droits réservés.
      </p>
    </div>

  </div>{{-- /.ft__inner --}}
</footer>

<style>
/* ── FOOTER ──────────────────────────────────────────────────── */
.ft {
  position: relative;
  background: oklch(8% 0.01 230);
  color: oklch(65% 0 0);
  font-size: 0.9rem;
}

body:not(.dark-mode) .ft {
  background: oklch(14% 0.015 230);
}

.ft__topline {
  height: 2px;
  background: linear-gradient(90deg, transparent 0%, oklch(65% 0.20 200) 40%, oklch(65% 0.18 170) 60%, transparent 100%);
  opacity: 0.6;
}

.ft__inner {
  max-width: 76rem;
  margin-inline: auto;
  padding-block: 4rem;
  padding-inline: 1.5rem;
}

/* GRILLE 4 colonnes */
.ft__grid {
  display: grid;
  grid-template-columns: 1.6fr 1fr 1fr 1.4fr;
  gap: 3rem;
  margin-block-end: 3rem;
}

@media (max-width: 1024px) {
  .ft__grid { grid-template-columns: 1fr 1fr; }
}

@media (max-width: 600px) {
  .ft__grid { grid-template-columns: 1fr; gap: 2rem; }
}

/* Logo */
.ft__logo-wrap {
  display: inline-flex;
  align-items: center;
  gap: 0.75rem;
  text-decoration: none;
  margin-block-end: 1rem;
}

.ft__logo-img {
  width: 44px;
  height: 44px;
  border-radius: 10px;
  filter: drop-shadow(0 0 8px oklch(65% 0.20 200 / 60%));
}

.ft__logo-name {
  font-family: 'Poppins', sans-serif;
  font-weight: 800;
  font-size: 1.1rem;
  background: linear-gradient(135deg, oklch(65% 0.20 200), oklch(65% 0.18 170));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  color: transparent;
}

.ft__tagline {
  font-size: 0.85rem;
  color: oklch(55% 0 0);
  line-height: 1.65;
  margin-block-end: 1.5rem;
}

/* Réseaux sociaux */
.ft__socials {
  display: flex;
  flex-wrap: wrap;
  gap: 0.625rem;
}

.ft__social {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  border-radius: 8px;
  background: oklch(65% 0.20 200 / 8%);
  border: 1px solid oklch(65% 0.20 200 / 20%);
  color: oklch(65% 0.20 200);
  text-decoration: none;
  font-size: 0.875rem;
  transition: background 0.2s ease, transform 0.2s ease, border-color 0.2s ease;
}

.ft__social:hover {
  background: oklch(65% 0.20 200);
  color: oklch(10% 0 0);
  border-color: oklch(65% 0.20 200);
  transform: translateY(-3px);
}

/* Titre de colonne */
.ft__col-title {
  font-family: 'Poppins', sans-serif;
  font-size: 0.75rem;
  font-weight: 700;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: oklch(65% 0.20 200);
  margin-block-end: 1.25rem;
  padding-block-end: 0.75rem;
  border-bottom: 1px solid oklch(65% 0.20 200 / 15%);
}

/* Liens de navigation */
.ft__nav {
  display: flex;
  flex-direction: column;
  gap: 0.125rem;
}

.ft__link {
  display: flex;
  align-items: center;
  gap: 0.625rem;
  padding-block: 0.5rem;
  color: oklch(58% 0 0);
  text-decoration: none;
  font-size: 0.875rem;
  border-radius: 6px;
  transition: color 0.15s ease, padding-left 0.15s ease;
}

.ft__link i {
  width: 14px;
  color: oklch(65% 0.20 200 / 50%);
  font-size: 0.75rem;
  flex-shrink: 0;
}

.ft__link:hover {
  color: oklch(65% 0.20 200);
  padding-inline-start: 0.5rem;
}

.ft__link:hover i { color: oklch(65% 0.20 200); }

/* Newsletter */
.ft__newsletter-desc {
  font-size: 0.85rem;
  color: oklch(55% 0 0);
  line-height: 1.6;
  margin-block-end: 1rem;
}

.ft__newsletter-form {
  display: flex;
  flex-direction: column;
  gap: 0.625rem;
}

.ft__input-wrap {
  position: relative;
}

.ft__input-icon {
  position: absolute;
  left: 0.875rem;
  top: 50%;
  translate: 0 -50%;
  color: oklch(65% 0.20 200 / 50%);
  font-size: 0.8rem;
  pointer-events: none;
}

.ft__input {
  width: 100%;
  padding: 0.75rem 1rem 0.75rem 2.25rem;
  background: oklch(65% 0.20 200 / 5%);
  border: 1px solid oklch(65% 0.20 200 / 20%);
  border-radius: 8px;
  color: oklch(85% 0 0);
  font-size: 0.875rem;
  outline: none;
  transition: border-color 0.2s ease, background 0.2s ease;
}

.ft__input::placeholder { color: oklch(45% 0 0); }

.ft__input:focus {
  border-color: oklch(65% 0.20 200 / 60%);
  background: oklch(65% 0.20 200 / 8%);
}

.ft__newsletter-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  padding: 0.75rem 1.25rem;
  background: linear-gradient(135deg, oklch(65% 0.20 200), oklch(65% 0.18 170));
  color: oklch(10% 0 0);
  border: none;
  border-radius: 8px;
  font-weight: 700;
  font-size: 0.875rem;
  cursor: pointer;
  transition: opacity 0.2s ease, transform 0.2s ease;
}

.ft__newsletter-btn:hover {
  opacity: 0.9;
  transform: translateY(-1px);
}

.ft__newsletter-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none;
}

.ft__newsletter-msg {
  font-size: 0.8125rem;
  margin-block-start: 0.5rem;
  min-height: 1.2em;
}

.ft__newsletter-msg--ok  { color: oklch(72% 0.17 145); }
.ft__newsletter-msg--err { color: oklch(65% 0.22 25); }

/* Contact */
.ft__contact {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  margin-block-start: 1.25rem;
  padding-block-start: 1rem;
  border-block-start: 1px solid oklch(65% 0.20 200 / 12%);
}

.ft__contact-link {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.8125rem;
  color: oklch(55% 0 0);
  text-decoration: none;
  transition: color 0.15s ease;
}

.ft__contact-link i { color: oklch(65% 0.20 200 / 60%); font-size: 0.75rem; }
.ft__contact-link:hover { color: oklch(65% 0.20 200); }

/* BAS DU FOOTER */
.ft__bottom {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 1rem;
  padding-block-start: 2rem;
  border-block-start: 1px solid oklch(65% 0.20 200 / 10%);
}

.ft__legal {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 0.5rem;
}

.ft__legal-link {
  font-size: 0.8125rem;
  color: oklch(48% 0 0);
  text-decoration: none;
  transition: color 0.15s ease;
}

.ft__legal-link:hover { color: oklch(65% 0.20 200); }

.ft__legal-link--heart { color: oklch(60% 0.22 25); }
.ft__legal-link--heart:hover { color: oklch(65% 0.22 25); }
.ft__legal-link--heart i { margin-inline-end: 0.25rem; }

.ft__legal-sep {
  display: inline-block;
  width: 3px;
  height: 3px;
  border-radius: 50%;
  background: oklch(35% 0 0);
}

.ft__copyright {
  font-size: 0.8125rem;
  color: oklch(45% 0 0);
}

.ft__copyright strong { color: oklch(65% 0 0); }
</style>

<script>
document.getElementById('newsletterForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const email   = document.getElementById('newsletterEmail').value;
    const btn     = document.getElementById('newsletterBtn');
    const msg     = document.getElementById('newsletterMessage');
    const csrf    = document.querySelector('input[name="_token"]')?.value ?? document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Envoi…';

    try {
        const res  = await fetch('{{ route("newsletter.subscribe") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            body: JSON.stringify({ email })
        });
        const data = await res.json();

        if (res.ok) {
            msg.className = 'ft__newsletter-msg ft__newsletter-msg--ok';
            msg.innerHTML = '<i class="fas fa-check-circle"></i> ' + (data.message ?? 'Abonnement réussi !');
            this.reset();
        } else {
            const err = data.errors?.email?.[0] ?? data.message ?? 'Une erreur est survenue.';
            msg.className = 'ft__newsletter-msg ft__newsletter-msg--err';
            msg.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + err;
        }
    } catch {
        msg.className = 'ft__newsletter-msg ft__newsletter-msg--err';
        msg.innerHTML = '<i class="fas fa-exclamation-circle"></i> Connexion impossible, réessayez.';
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-paper-plane"></i> S\'abonner';
        setTimeout(() => { msg.innerHTML = ''; msg.className = 'ft__newsletter-msg'; }, 6000);
    }
});
</script>
