@extends('layouts.app')

@section('title', 'Forum Communautaire - NiangProgrammeur')
@section('meta_description', 'Rejoignez la communauté NiangProgrammeur : posez vos questions, partagez vos connaissances et apprenez avec des milliers de développeurs passionnés.')

@section('styles')
<style>
/* ============================================================
   FORUM INDEX — fm-* design system
   Charte NiangProgrammeur — accent cyan/teal — unlayered
   ============================================================ */

.fm-page {
  --fm-bg:      #f8fafc;
  --fm-surface: #ffffff;
  --fm-border:  #e2e8f0;
  --fm-accent:  oklch(52% 0.18 200);
  --fm-accent2: oklch(50% 0.15 178);
  --fm-text:    #0f172a;
  --fm-muted:   #64748b;
  --fm-shadow:  0 1px 3px rgba(0,0,0,.05), 0 4px 16px rgba(0,0,0,.06);

  background: var(--fm-bg);
  color: var(--fm-text);
  font-family: 'Inter', system-ui, sans-serif;
  min-block-size: 100dvh;
  padding-block-end: 4rem;
}

/* ── HERO ─────────────────────────────────────────────────── */
.fm-hero {
  background: var(--fm-surface);
  border-block-end: 1px solid var(--fm-border);
  padding-block-start: calc(var(--spacing-navbar, 76px) + 3rem) !important;
  padding-block-end: 3rem;
  padding-inline: 1.5rem;
  position: relative;
  overflow: hidden;
}

/* Animated top accent line */
.fm-hero::before {
  content: '';
  position: absolute;
  inset-block-start: 0;
  inset-inline: 0;
  block-size: 3px;
  background: linear-gradient(90deg,
    oklch(52% 0.18 200),
    oklch(50% 0.15 178),
    oklch(52% 0.18 200));
  background-size: 200% 100%;
  animation: fm-shimmer 4s linear infinite;
}

@keyframes fm-shimmer {
  0%   { background-position:  200% 0; }
  100% { background-position: -200% 0; }
}

/* Subtle orb background */
.fm-hero::after {
  content: '';
  position: absolute;
  inset: 0;
  background:
    radial-gradient(ellipse 55% 70% at 90% 50%, oklch(52% 0.18 200 / 5%) 0%, transparent 60%),
    radial-gradient(ellipse 35% 50% at 5% 100%,  oklch(50% 0.15 178 / 4%) 0%, transparent 50%);
  pointer-events: none;
}

.fm-hero__inner {
  max-inline-size: 64rem;
  margin-inline: auto;
  position: relative;
  z-index: 1;
}

.fm-hero__eyebrow {
  display: inline-flex;
  align-items: center;
  gap: .4rem;
  padding: .3rem .9rem;
  border-radius: 100px;
  font-size: .72rem;
  font-weight: 700;
  letter-spacing: .08em;
  text-transform: uppercase;
  background: oklch(52% 0.18 200 / 8%);
  border: 1px solid oklch(52% 0.18 200 / 25%);
  color: oklch(52% 0.18 200);
  margin-block-end: 1.2rem;
}

.fm-hero__title {
  font-family: 'Poppins', system-ui, sans-serif;
  font-size: clamp(2rem, 5vw, 3.2rem);
  font-weight: 900;
  line-height: 1.1;
  color: var(--fm-text);
  margin-block-end: .75rem;
}

.fm-hero__title span {
  background: linear-gradient(135deg, oklch(52% 0.18 200), oklch(50% 0.15 178));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.fm-hero__sub {
  font-size: 1.05rem;
  color: var(--fm-muted);
  line-height: 1.65;
  margin-block-end: 2rem;
  max-inline-size: 44rem;
}

/* Search bar */
.fm-search {
  max-inline-size: 50rem;
}

.fm-search__wrap {
  display: flex;
  align-items: center;
  background: var(--fm-bg);
  border: 1.5px solid var(--fm-border);
  border-radius: 14px;
  overflow: hidden;
  transition: border-color .2s, box-shadow .2s;
}

.fm-search__wrap:focus-within {
  border-color: oklch(52% 0.18 200 / 60%);
  box-shadow: 0 0 0 3px oklch(52% 0.18 200 / 10%);
}

.fm-search__ico {
  padding-inline: 1.1rem;
  color: var(--fm-muted);
  font-size: .88rem;
  flex-shrink: 0;
}

.fm-search__input {
  flex: 1;
  padding: .9rem 0;
  border: none;
  background: transparent;
  font-size: .95rem;
  color: var(--fm-text);
  outline: none;
  font-family: inherit;
}

.fm-search__input::placeholder { color: var(--fm-muted); }

.fm-search__btn {
  margin: .3rem;
  padding: .65rem 1.35rem;
  background: linear-gradient(135deg, oklch(52% 0.18 200), oklch(50% 0.15 178));
  color: #fff;
  border: none;
  border-radius: 10px;
  font-size: .84rem;
  font-weight: 700;
  cursor: pointer;
  font-family: inherit;
  transition: transform .2s, box-shadow .2s;
  flex-shrink: 0;
}

.fm-search__btn:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 14px oklch(52% 0.18 200 / 30%);
}

/* ── STATS + CTA STRIP ────────────────────────────────────── */
.fm-strip {
  background: var(--fm-surface);
  border-block-end: 1px solid var(--fm-border);
  padding-block: 1rem;
  padding-inline: 1.5rem;
}

.fm-strip__inner {
  max-inline-size: 64rem;
  margin-inline: auto;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1.5rem;
  flex-wrap: wrap;
}

.fm-stats {
  display: flex;
  gap: 0;
  align-items: stretch;
}

.fm-stat {
  display: flex;
  align-items: center;
  gap: .65rem;
  padding-inline: 1.5rem;
  border-inline-end: 1px solid var(--fm-border);
}

.fm-stat:first-child { padding-inline-start: 0; }
.fm-stat:last-child  { border-inline-end: none; }

.fm-stat__icon {
  width: 34px;
  height: 34px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: .8rem;
  flex-shrink: 0;
}

.fm-stat--topics  .fm-stat__icon { background: oklch(52% 0.18 200 / 10%); color: oklch(52% 0.18 200); }
.fm-stat--replies .fm-stat__icon { background: oklch(50% 0.15 178 / 10%); color: oklch(50% 0.15 178); }
.fm-stat--members .fm-stat__icon { background: oklch(52% 0.18 145 / 10%); color: oklch(50% 0.18 145); }

.fm-stat__val {
  font-family: 'Poppins', system-ui, sans-serif;
  font-size: 1.15rem;
  font-weight: 900;
  color: var(--fm-text);
  line-height: 1;
}

.fm-stat__lbl {
  font-size: .7rem;
  font-weight: 600;
  color: var(--fm-muted);
  text-transform: uppercase;
  letter-spacing: .04em;
}

.fm-btn-create {
  display: inline-flex;
  align-items: center;
  gap: .5rem;
  padding: .65rem 1.35rem;
  background: linear-gradient(135deg, oklch(52% 0.18 200), oklch(50% 0.15 178));
  color: #fff;
  border-radius: 10px;
  font-size: .84rem;
  font-weight: 700;
  text-decoration: none;
  box-shadow: 0 4px 14px oklch(52% 0.18 200 / 28%);
  transition: transform .2s, box-shadow .2s;
}

.fm-btn-create:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 22px oklch(52% 0.18 200 / 38%);
  color: #fff;
}

.fm-login-chip {
  display: inline-flex;
  align-items: center;
  gap: .5rem;
  padding: .55rem 1.1rem;
  background: oklch(52% 0.18 200 / 6%);
  border: 1px solid oklch(52% 0.18 200 / 20%);
  border-radius: 10px;
  font-size: .82rem;
  color: var(--fm-muted);
}

.fm-login-chip a {
  color: oklch(52% 0.18 200);
  font-weight: 700;
  text-decoration: none;
}

.fm-login-chip a:hover { text-decoration: underline; }

/* ── MAIN CONTENT ─────────────────────────────────────────── */
.fm-main {
  padding-block: 2.5rem;
  padding-inline: 1.5rem;
}

.fm-container {
  max-inline-size: 64rem;
  margin-inline: auto;
}

.fm-section-title {
  font-family: 'Poppins', system-ui, sans-serif;
  font-size: 1.05rem;
  font-weight: 800;
  color: var(--fm-text);
  margin-block-end: 1.25rem;
  display: flex;
  align-items: center;
  gap: .5rem;
}

.fm-section-title i { color: var(--fm-accent); font-size: .9rem; }

/* ── CATEGORY GRID ────────────────────────────────────────── */
.fm-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1.25rem;
}

@media (max-width: 740px) {
  .fm-grid { grid-template-columns: 1fr; }
}

/* ── CATEGORY CARD ─────────────────────────────────────────── */
.fm-card {
  background: var(--fm-surface);
  border: 1.5px solid var(--fm-border);
  border-radius: 18px;
  padding: 1.5rem;
  text-decoration: none;
  color: inherit;
  display: flex;
  flex-direction: column;
  gap: 1rem;
  box-shadow: var(--fm-shadow);
  transition: box-shadow .25s, border-color .25s, transform .25s;
  position: relative;
  overflow: hidden;
}

.fm-card:hover {
  box-shadow: 0 8px 32px rgba(0,0,0,.08);
  border-color: oklch(52% 0.18 200 / 35%);
  transform: translateY(-3px);
  color: inherit;
  text-decoration: none;
}

/* Color accent top bar */
.fm-card__accent {
  position: absolute;
  inset-block-start: 0;
  inset-inline: 0;
  block-size: 3px;
  border-radius: 18px 18px 0 0;
  opacity: .6;
  transition: opacity .25s;
}

.fm-card:hover .fm-card__accent { opacity: 1; }

/* Card header: icon + name + count */
.fm-card__head {
  display: flex;
  align-items: flex-start;
  gap: 1rem;
}

.fm-card__icon {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.25rem;
  border: 1px solid;
  flex-shrink: 0;
  transition: transform .25s;
}

.fm-card:hover .fm-card__icon {
  transform: scale(1.08) rotate(4deg);
}

.fm-card__info { flex: 1; min-inline-size: 0; }

.fm-card__name {
  font-family: 'Poppins', system-ui, sans-serif;
  font-size: 1rem;
  font-weight: 800;
  color: var(--fm-text);
  line-height: 1.25;
  margin-block-end: .3rem;
}

.fm-card__desc {
  font-size: .8rem;
  color: var(--fm-muted);
  line-height: 1.5;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.fm-card__count {
  display: flex;
  flex-direction: column;
  align-items: center;
  flex-shrink: 0;
  padding: .4rem .7rem;
  background: var(--fm-bg);
  border-radius: 10px;
  border: 1px solid var(--fm-border);
  text-align: center;
}

.fm-count__val {
  font-family: 'Poppins', system-ui, sans-serif;
  font-size: 1.1rem;
  font-weight: 900;
  color: var(--fm-text);
  line-height: 1;
}

.fm-count__lbl {
  font-size: .62rem;
  font-weight: 600;
  color: var(--fm-muted);
  text-transform: uppercase;
  letter-spacing: .04em;
}

/* Latest topic preview */
.fm-card__latest {
  display: flex;
  align-items: flex-start;
  gap: .65rem;
  padding: .7rem .9rem;
  background: var(--fm-bg);
  border-radius: 10px;
  border: 1px solid var(--fm-border);
  transition: border-color .2s;
}

.fm-card:hover .fm-card__latest {
  border-color: oklch(52% 0.18 200 / 25%);
}

.fm-card__latest-ico {
  color: var(--fm-accent);
  font-size: .72rem;
  padding-block-start: .18rem;
  flex-shrink: 0;
}

.fm-card__latest-body {
  display: flex;
  flex-direction: column;
  gap: .15rem;
  min-inline-size: 0;
  flex: 1;
}

.fm-card__latest-title {
  font-size: .82rem;
  font-weight: 700;
  color: var(--fm-text);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  line-height: 1.35;
}

.fm-card__latest-meta {
  font-size: .7rem;
  color: var(--fm-muted);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.fm-card__latest-meta strong {
  color: var(--fm-text);
  font-weight: 600;
}

/* Empty / no topics yet */
.fm-card__no-topics {
  display: flex;
  align-items: center;
  gap: .5rem;
  font-size: .78rem;
  color: var(--fm-muted);
  padding: .35rem 0;
  font-style: italic;
}

/* Footer row: replies count + arrow */
.fm-card__footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding-block-start: .25rem;
}

.fm-card__replies {
  display: inline-flex;
  align-items: center;
  gap: .35rem;
  font-size: .75rem;
  color: var(--fm-muted);
  font-weight: 600;
}

.fm-card__replies i { color: var(--fm-accent); font-size: .7rem; }

.fm-card__arrow {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  background: oklch(52% 0.18 200 / 8%);
  border: 1px solid oklch(52% 0.18 200 / 22%);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: .65rem;
  color: var(--fm-accent);
  transition: background .2s, transform .2s, color .2s;
}

.fm-card:hover .fm-card__arrow {
  background: oklch(52% 0.18 200);
  color: #fff;
  transform: translateX(3px);
}

/* ── EMPTY STATE ──────────────────────────────────────────── */
.fm-empty {
  grid-column: 1 / -1;
  text-align: center;
  padding: 4rem 2rem;
  background: var(--fm-surface);
  border: 1.5px dashed var(--fm-border);
  border-radius: 18px;
}

.fm-empty i {
  font-size: 3rem;
  color: var(--fm-muted);
  opacity: .35;
  margin-block-end: 1rem;
  display: block;
}

.fm-empty p { color: var(--fm-muted); font-size: 1rem; }

/* ── CTA BOTTOM ───────────────────────────────────────────── */
.fm-cta {
  padding-inline: 1.5rem;
  padding-block-end: 1rem;
}

.fm-cta__inner {
  max-inline-size: 64rem;
  margin-inline: auto;
  background: var(--fm-surface);
  border: 1px solid oklch(52% 0.18 200 / 20%);
  border-radius: 20px;
  padding: 2rem 2.25rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 2rem;
  flex-wrap: wrap;
  box-shadow: 0 8px 32px oklch(52% 0.18 200 / 6%);
}

.fm-cta__title {
  font-family: 'Poppins', system-ui, sans-serif;
  font-size: 1.2rem;
  font-weight: 800;
  color: var(--fm-text);
  margin-block-end: .3rem;
}

.fm-cta__sub {
  font-size: .88rem;
  color: var(--fm-muted);
  line-height: 1.55;
}

.fm-cta__actions {
  display: flex;
  gap: .75rem;
  flex-wrap: wrap;
  flex-shrink: 0;
}

.fm-btn-primary {
  display: inline-flex;
  align-items: center;
  gap: .4rem;
  padding: .7rem 1.4rem;
  background: linear-gradient(135deg, oklch(52% 0.18 200), oklch(50% 0.15 178));
  color: #fff;
  border-radius: 10px;
  font-size: .85rem;
  font-weight: 700;
  text-decoration: none;
  box-shadow: 0 4px 14px oklch(52% 0.18 200 / 28%);
  transition: transform .2s, box-shadow .2s;
}

.fm-btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 22px oklch(52% 0.18 200 / 38%);
  color: #fff;
}

.fm-btn-ghost {
  display: inline-flex;
  align-items: center;
  gap: .4rem;
  padding: .7rem 1.4rem;
  background: transparent;
  color: var(--fm-text);
  border: 1.5px solid var(--fm-border);
  border-radius: 10px;
  font-size: .85rem;
  font-weight: 700;
  text-decoration: none;
  transition: border-color .2s, transform .2s;
}

.fm-btn-ghost:hover {
  border-color: oklch(52% 0.18 200 / 40%);
  transform: translateY(-2px);
  color: var(--fm-text);
}

/* ── RESPONSIVE ───────────────────────────────────────────── */
@media (max-width: 640px) {
  .fm-stats { gap: 0; }
  .fm-stat  { padding-inline: .9rem; }
  .fm-stat:first-child { padding-inline-start: 0; }

  .fm-card__head { gap: .75rem; }
  .fm-card__icon { width: 40px; height: 40px; font-size: 1.1rem; }

  .fm-cta__inner { flex-direction: column; text-align: center; }
  .fm-cta__actions { justify-content: center; }

  .fm-strip__inner { flex-direction: column; align-items: flex-start; gap: .75rem; }
}
</style>
@endsection

@section('content')
<div class="fm-page">

  {{-- ── HERO ──────────────────────────────────────────────── --}}
  <div class="fm-hero">
    <div class="fm-hero__inner">

      <div class="fm-hero__eyebrow">
        <i class="fas fa-comments"></i>
        Communauté de développeurs
      </div>

      <h1 class="fm-hero__title">
        Forum <span>NiangProgrammeur</span>
      </h1>

      <p class="fm-hero__sub">
        Posez vos questions, partagez vos connaissances et progressez avec
        des milliers de développeurs passionnés.
      </p>

      <form class="fm-search" action="{{ route('forum.search') }}" method="GET" role="search">
        <div class="fm-search__wrap">
          <i class="fas fa-search fm-search__ico" aria-hidden="true"></i>
          <input
            class="fm-search__input"
            type="search"
            name="q"
            placeholder="Rechercher un topic, une question…"
            aria-label="Rechercher dans le forum"
            autocomplete="off"
          >
          <button class="fm-search__btn" type="submit">Rechercher</button>
        </div>
      </form>

    </div>
  </div>

  {{-- ── STATS + CTA STRIP ──────────────────────────────────── --}}
  <div class="fm-strip">
    <div class="fm-strip__inner">

      <div class="fm-stats">
        <div class="fm-stat fm-stat--topics">
          <div class="fm-stat__icon"><i class="fas fa-comments"></i></div>
          <div>
            <div class="fm-stat__val">{{ number_format($stats['total_topics']) }}</div>
            <div class="fm-stat__lbl">Topics</div>
          </div>
        </div>
        <div class="fm-stat fm-stat--replies">
          <div class="fm-stat__icon"><i class="fas fa-reply"></i></div>
          <div>
            <div class="fm-stat__val">{{ number_format($stats['total_replies']) }}</div>
            <div class="fm-stat__lbl">Réponses</div>
          </div>
        </div>
        <div class="fm-stat fm-stat--members">
          <div class="fm-stat__icon"><i class="fas fa-users"></i></div>
          <div>
            <div class="fm-stat__val">{{ number_format($stats['total_users']) }}</div>
            <div class="fm-stat__lbl">Membres actifs</div>
          </div>
        </div>
      </div>

      @auth
        <a href="{{ route('forum.create') }}" class="fm-btn-create">
          <i class="fas fa-plus"></i>
          Nouveau topic
        </a>
      @else
        <div class="fm-login-chip">
          <i class="fas fa-lock"></i>
          <a href="{{ route('login') }}">Connectez-vous</a> pour créer un topic
        </div>
      @endauth

    </div>
  </div>

  {{-- ── CATEGORY GRID ───────────────────────────────────────── --}}
  <div class="fm-main">
    <div class="fm-container">

      <p class="fm-section-title">
        <i class="fas fa-layer-group"></i>
        {{ $categories->count() }} catégorie{{ $categories->count() > 1 ? 's' : '' }}
      </p>

      <div class="fm-grid">
        @forelse($categories as $category)
        @php
          $color  = $category->color ?? '#06b6d4';
          $latest = $category->latestTopic;
        @endphp
        <a href="{{ route('forum.category', $category->slug) }}" class="fm-card">

          {{-- Color top bar --}}
          <div class="fm-card__accent" style="background: {{ $color }};"></div>

          {{-- Header: icon · name · topic count --}}
          <div class="fm-card__head">
            <div class="fm-card__icon"
                 style="background:{{ $color }}1a; color:{{ $color }}; border-color:{{ $color }}33;">
              <i class="{{ $category->icon ?? 'fas fa-folder' }}"></i>
            </div>

            <div class="fm-card__info">
              <div class="fm-card__name">{{ $category->name }}</div>
              @if($category->description)
                <div class="fm-card__desc">{{ $category->description }}</div>
              @endif
            </div>

            <div class="fm-card__count">
              <span class="fm-count__val">{{ $category->topics_count }}</span>
              <span class="fm-count__lbl">topic{{ $category->topics_count > 1 ? 's' : '' }}</span>
            </div>
          </div>

          {{-- Latest topic preview --}}
          @if($latest)
          <div class="fm-card__latest">
            <i class="fas fa-clock fm-card__latest-ico"></i>
            <div class="fm-card__latest-body">
              <span class="fm-card__latest-title">
                {{ \Illuminate\Support\Str::limit($latest->title, 58) }}
              </span>
              <span class="fm-card__latest-meta">
                par <strong>{{ $latest->user->name ?? 'Inconnu' }}</strong>
                &middot;
                {{ ($latest->last_reply_at ?? $latest->created_at)->diffForHumans() }}
              </span>
            </div>
          </div>
          @else
          <div class="fm-card__no-topics">
            <i class="far fa-comment-dots"></i>
            Soyez le premier à poster dans cette catégorie
          </div>
          @endif

          {{-- Footer: replies count + arrow --}}
          <div class="fm-card__footer">
            @php $totalReplies = (int) ($category->topics_sum_replies_count ?? 0); @endphp
            <span class="fm-card__replies">
              <i class="fas fa-reply"></i>
              {{ number_format($totalReplies) }} réponse{{ $totalReplies > 1 ? 's' : '' }}
            </span>
            <span class="fm-card__arrow">
              <i class="fas fa-arrow-right"></i>
            </span>
          </div>

        </a>
        @empty
        <div class="fm-empty">
          <i class="fas fa-inbox"></i>
          <p>Aucune catégorie disponible pour le moment.</p>
        </div>
        @endforelse
      </div>

    </div>
  </div>

  {{-- ── CTA BOTTOM (guests only) ──────────────────────────── --}}
  @guest
  <div class="fm-cta">
    <div class="fm-cta__inner">
      <div>
        <h3 class="fm-cta__title">Rejoignez la communauté</h3>
        <p class="fm-cta__sub">
          Créez un compte gratuit pour poser vos questions,<br>
          répondre aux autres et partager vos projets.
        </p>
      </div>
      <div class="fm-cta__actions">
        <a href="{{ route('register') }}" class="fm-btn-primary">
          <i class="fas fa-user-plus"></i>
          Créer un compte
        </a>
        <a href="{{ route('login') }}" class="fm-btn-ghost">
          Se connecter
        </a>
      </div>
    </div>
  </div>
  @endguest

</div>
@endsection
