@extends('layouts.app')

@section('title', $query ? "« {$query} » — Recherche | NiangProgrammeur" : 'Recherche | NiangProgrammeur')
@section('meta_description', 'Recherchez parmi nos formations, articles emploi et ressources pour développeurs.')
@push('meta')
<link rel="canonical" href="{{ route('search', ['q' => $query]) }}">
@endpush

@section('styles')
<style>
/* ============================================================
   SEARCH PAGE — sr-* design system
   Charte NiangProgrammeur — accent cyan/teal — unlayered
   ============================================================ */

.sr-page {
  --sr-bg:      #f8fafc;
  --sr-surface: #ffffff;
  --sr-border:  #e2e8f0;
  --sr-accent:  oklch(52% 0.18 200);
  --sr-accent2: oklch(50% 0.15 178);
  --sr-text:    #0f172a;
  --sr-muted:   #64748b;
  --sr-shadow:  0 1px 3px rgba(0,0,0,.05), 0 4px 16px rgba(0,0,0,.06);
  --sr-formation: oklch(52% 0.18 200);
  --sr-article:   oklch(50% 0.18 145);

  background: var(--sr-bg);
  color: var(--sr-text);
  font-family: 'Inter', system-ui, sans-serif;
  min-block-size: 100dvh;
  padding-block-end: 4rem;
}

/* ── HERO ─────────────────────────────────────────────────── */
.sr-hero {
  background: var(--sr-surface);
  border-block-end: 1px solid var(--sr-border);
  padding-block-start: calc(var(--spacing-navbar, 76px) + 3rem) !important;
  padding-block-end: 2.5rem;
  padding-inline: 1.5rem;
  position: relative;
  overflow: hidden;
}

.sr-hero::before {
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
  animation: sr-shimmer 4s linear infinite;
}

@keyframes sr-shimmer {
  0%   { background-position:  200% 0; }
  100% { background-position: -200% 0; }
}

.sr-hero::after {
  content: '';
  position: absolute;
  inset: 0;
  background:
    radial-gradient(ellipse 50% 80% at 95% 40%, oklch(52% 0.18 200 / 5%) 0%, transparent 55%),
    radial-gradient(ellipse 35% 50% at 0%  90%, oklch(50% 0.15 178 / 4%) 0%, transparent 50%);
  pointer-events: none;
}

.sr-hero__inner {
  max-inline-size: 56rem;
  margin-inline: auto;
  position: relative;
  z-index: 1;
}

.sr-hero__eyebrow {
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
  margin-block-end: 1.1rem;
}

.sr-hero__title {
  font-family: 'Poppins', system-ui, sans-serif;
  font-size: clamp(1.8rem, 4vw, 2.8rem);
  font-weight: 900;
  line-height: 1.1;
  color: var(--sr-text);
  margin-block-end: .5rem;
}

.sr-hero__title span {
  background: linear-gradient(135deg, oklch(52% 0.18 200), oklch(50% 0.15 178));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.sr-hero__count {
  font-size: .95rem;
  color: var(--sr-muted);
  margin-block-end: 1.75rem;
  min-block-size: 1.4em;
}

.sr-hero__count strong { color: var(--sr-text); font-weight: 700; }
.sr-hero__count em    { color: oklch(52% 0.18 200); font-style: normal; font-weight: 700; }

/* Search bar */
.sr-search {
  max-inline-size: 52rem;
}

.sr-search__wrap {
  display: flex;
  align-items: center;
  background: var(--sr-bg);
  border: 1.5px solid var(--sr-border);
  border-radius: 14px;
  overflow: hidden;
  transition: border-color .2s, box-shadow .2s;
}

.sr-search__wrap:focus-within {
  border-color: oklch(52% 0.18 200 / 65%);
  box-shadow: 0 0 0 3px oklch(52% 0.18 200 / 10%);
}

.sr-search__ico {
  padding-inline: 1.15rem;
  color: var(--sr-muted);
  font-size: .9rem;
  flex-shrink: 0;
}

.sr-search__input {
  flex: 1;
  padding: .95rem 0;
  border: none;
  background: transparent;
  font-size: 1rem;
  color: var(--sr-text);
  outline: none;
  font-family: inherit;
}

.sr-search__input::placeholder { color: var(--sr-muted); }

.sr-search__btn {
  margin: .3rem;
  padding: .7rem 1.5rem;
  background: linear-gradient(135deg, oklch(52% 0.18 200), oklch(50% 0.15 178));
  color: #fff;
  border: none;
  border-radius: 10px;
  font-size: .85rem;
  font-weight: 700;
  cursor: pointer;
  font-family: inherit;
  transition: transform .2s, box-shadow .2s;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  gap: .4rem;
}

.sr-search__btn:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 14px oklch(52% 0.18 200 / 30%);
}

/* ── FILTERS STRIP ────────────────────────────────────────── */
.sr-strip {
  background: var(--sr-surface);
  border-block-end: 1px solid var(--sr-border);
  padding-block: .85rem;
  padding-inline: 1.5rem;
}

.sr-strip__inner {
  max-inline-size: 56rem;
  margin-inline: auto;
  display: flex;
  align-items: center;
  gap: .75rem;
  flex-wrap: wrap;
}

.sr-filters {
  display: contents;
}

.sr-filter-lbl {
  font-size: .75rem;
  font-weight: 700;
  color: var(--sr-muted);
  text-transform: uppercase;
  letter-spacing: .05em;
  flex-shrink: 0;
}

.sr-select {
  padding: .5rem .85rem;
  background: var(--sr-bg);
  border: 1.5px solid var(--sr-border);
  border-radius: 9px;
  color: var(--sr-text);
  font-size: .82rem;
  font-family: inherit;
  outline: none;
  cursor: pointer;
  transition: border-color .2s;
  min-inline-size: 0;
}

.sr-select:focus {
  border-color: oklch(52% 0.18 200 / 50%);
}

.sr-filter-btn {
  padding: .5rem 1.1rem;
  background: linear-gradient(135deg, oklch(52% 0.18 200), oklch(50% 0.15 178));
  color: #fff;
  border: none;
  border-radius: 9px;
  font-size: .82rem;
  font-weight: 700;
  cursor: pointer;
  font-family: inherit;
  transition: transform .15s, box-shadow .15s;
  display: flex;
  align-items: center;
  gap: .35rem;
  flex-shrink: 0;
}

.sr-filter-btn:hover { transform: translateY(-1px); box-shadow: 0 3px 10px oklch(52% 0.18 200 / 28%); }

.sr-clear {
  display: inline-flex;
  align-items: center;
  gap: .35rem;
  font-size: .78rem;
  font-weight: 600;
  color: var(--sr-muted);
  text-decoration: none;
  padding: .4rem .75rem;
  border-radius: 8px;
  border: 1px solid var(--sr-border);
  transition: color .2s, border-color .2s;
  flex-shrink: 0;
}

.sr-clear:hover { color: oklch(52% 0.20 25); border-color: oklch(52% 0.20 25 / 40%); }

/* ── MAIN ─────────────────────────────────────────────────── */
.sr-main {
  padding-block: 2.5rem;
  padding-inline: 1.5rem;
}

.sr-container {
  max-inline-size: 56rem;
  margin-inline: auto;
}

/* Section header */
.sr-section { margin-block-end: 2.5rem; }

.sr-section__hd {
  display: flex;
  align-items: center;
  gap: .75rem;
  margin-block-end: 1.1rem;
  padding-block-end: .75rem;
  border-block-end: 1.5px solid var(--sr-border);
}

.sr-section__icon {
  width: 34px;
  height: 34px;
  border-radius: 9px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: .82rem;
  flex-shrink: 0;
}

.sr-section--formations .sr-section__icon { background: oklch(52% 0.18 200 / 10%); color: oklch(52% 0.18 200); }
.sr-section--articles   .sr-section__icon { background: oklch(50% 0.18 145 / 10%); color: oklch(50% 0.18 145); }

.sr-section__title {
  font-family: 'Poppins', system-ui, sans-serif;
  font-size: 1rem;
  font-weight: 800;
  color: var(--sr-text);
  flex: 1;
}

.sr-section__badge {
  display: inline-flex;
  align-items: center;
  padding: .2rem .65rem;
  border-radius: 100px;
  font-size: .72rem;
  font-weight: 700;
}

.sr-section--formations .sr-section__badge { background: oklch(52% 0.18 200 / 10%); color: oklch(52% 0.18 200); }
.sr-section--articles   .sr-section__badge { background: oklch(50% 0.18 145 / 10%); color: oklch(50% 0.18 145); }

/* ── RESULT CARD ──────────────────────────────────────────── */
.sr-list {
  display: flex;
  flex-direction: column;
  gap: .85rem;
}

.sr-card {
  display: flex;
  align-items: flex-start;
  gap: 1.1rem;
  background: var(--sr-surface);
  border: 1.5px solid var(--sr-border);
  border-radius: 16px;
  padding: 1.25rem;
  text-decoration: none;
  color: inherit;
  box-shadow: var(--sr-shadow);
  transition: box-shadow .22s, border-color .22s, transform .22s;
  position: relative;
  overflow: hidden;
}

.sr-card::before {
  content: '';
  position: absolute;
  inset-inline-start: 0;
  inset-block: 0;
  inline-size: 3px;
  border-radius: 16px 0 0 16px;
  opacity: 0;
  transition: opacity .22s;
}

.sr-section--formations .sr-card::before { background: oklch(52% 0.18 200); }
.sr-section--articles   .sr-card::before { background: oklch(50% 0.18 145); }

.sr-card:hover {
  box-shadow: 0 6px 28px rgba(0,0,0,.09);
  border-color: oklch(52% 0.18 200 / 30%);
  transform: translateX(4px);
  color: inherit;
  text-decoration: none;
}

.sr-card:hover::before { opacity: 1; }

/* Thumbnail */
.sr-card__thumb {
  width: 76px;
  height: 76px;
  border-radius: 12px;
  overflow: hidden;
  flex-shrink: 0;
  background: var(--sr-bg);
  border: 1px solid var(--sr-border);
  display: flex;
  align-items: center;
  justify-content: center;
}

.sr-card__thumb img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform .35s;
}

.sr-card:hover .sr-card__thumb img { transform: scale(1.08); }

.sr-card__thumb-ico {
  font-size: 1.6rem;
  opacity: .35;
}

.sr-section--formations .sr-card__thumb-ico { color: oklch(52% 0.18 200); }
.sr-section--articles   .sr-card__thumb-ico { color: oklch(50% 0.18 145); }

/* Content */
.sr-card__body { flex: 1; min-inline-size: 0; }

.sr-card__type {
  display: inline-flex;
  align-items: center;
  gap: .3rem;
  padding: .18rem .6rem;
  border-radius: 100px;
  font-size: .68rem;
  font-weight: 700;
  letter-spacing: .05em;
  text-transform: uppercase;
  margin-block-end: .45rem;
  border: 1px solid;
}

.sr-section--formations .sr-card__type {
  background: oklch(52% 0.18 200 / 8%);
  border-color: oklch(52% 0.18 200 / 25%);
  color: oklch(52% 0.18 200);
}

.sr-section--articles .sr-card__type {
  background: oklch(50% 0.18 145 / 8%);
  border-color: oklch(50% 0.18 145 / 25%);
  color: oklch(50% 0.18 145);
}

.sr-card__title {
  font-family: 'Poppins', system-ui, sans-serif;
  font-size: .98rem;
  font-weight: 800;
  color: var(--sr-text);
  line-height: 1.3;
  margin-block-end: .4rem;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  transition: color .2s;
}

.sr-card:hover .sr-card__title { color: oklch(52% 0.18 200); }

.sr-card__excerpt {
  font-size: .8rem;
  color: var(--sr-muted);
  line-height: 1.55;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  margin-block-end: .6rem;
}

.sr-card__meta {
  display: flex;
  flex-wrap: wrap;
  gap: .75rem;
  align-items: center;
}

.sr-meta-item {
  display: inline-flex;
  align-items: center;
  gap: .3rem;
  font-size: .72rem;
  font-weight: 600;
  color: var(--sr-muted);
}

.sr-meta-item i { font-size: .65rem; color: oklch(52% 0.18 200); }

/* Arrow */
.sr-card__arrow {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  background: oklch(52% 0.18 200 / 8%);
  border: 1px solid oklch(52% 0.18 200 / 20%);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: .65rem;
  color: var(--sr-muted);
  flex-shrink: 0;
  align-self: center;
  transition: background .2s, color .2s, transform .2s;
}

.sr-card:hover .sr-card__arrow {
  background: oklch(52% 0.18 200);
  color: #fff;
  transform: translateX(2px);
}

/* ── EMPTY / NO-QUERY STATE ───────────────────────────────── */
.sr-empty {
  background: var(--sr-surface);
  border: 1.5px dashed var(--sr-border);
  border-radius: 18px;
  padding: 4rem 2rem;
  text-align: center;
}

.sr-empty__ico {
  width: 64px;
  height: 64px;
  border-radius: 18px;
  background: oklch(52% 0.18 200 / 8%);
  border: 1px solid oklch(52% 0.18 200 / 20%);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  color: oklch(52% 0.18 200);
  margin: 0 auto 1.25rem;
}

.sr-empty__title {
  font-family: 'Poppins', system-ui, sans-serif;
  font-size: 1.25rem;
  font-weight: 800;
  color: var(--sr-text);
  margin-block-end: .5rem;
}

.sr-empty__sub {
  font-size: .9rem;
  color: var(--sr-muted);
  line-height: 1.6;
  margin-block-end: 1.75rem;
  max-inline-size: 36rem;
  margin-inline: auto;
}

.sr-btn {
  display: inline-flex;
  align-items: center;
  gap: .4rem;
  padding: .7rem 1.5rem;
  background: linear-gradient(135deg, oklch(52% 0.18 200), oklch(50% 0.15 178));
  color: #fff;
  border-radius: 10px;
  font-size: .85rem;
  font-weight: 700;
  text-decoration: none;
  box-shadow: 0 4px 14px oklch(52% 0.18 200 / 28%);
  transition: transform .2s, box-shadow .2s;
}

.sr-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 22px oklch(52% 0.18 200 / 38%);
  color: #fff;
}

/* Popular suggestions chips */
.sr-suggestions {
  display: flex;
  flex-wrap: wrap;
  gap: .5rem;
  justify-content: center;
  margin-block-start: 1.25rem;
}

.sr-suggestion {
  display: inline-flex;
  align-items: center;
  gap: .35rem;
  padding: .4rem .9rem;
  background: var(--sr-bg);
  border: 1.5px solid var(--sr-border);
  border-radius: 100px;
  font-size: .78rem;
  font-weight: 600;
  color: var(--sr-text);
  text-decoration: none;
  transition: border-color .2s, background .2s, color .2s;
}

.sr-suggestion:hover {
  border-color: oklch(52% 0.18 200 / 50%);
  background: oklch(52% 0.18 200 / 6%);
  color: oklch(52% 0.18 200);
}

.sr-suggestion i { font-size: .7rem; color: oklch(52% 0.18 200); }

/* ── RESPONSIVE ───────────────────────────────────────────── */
@media (max-width: 600px) {
  .sr-card { flex-direction: column; gap: .85rem; }
  .sr-card__thumb { width: 100%; height: 160px; border-radius: 10px; }
  .sr-card__arrow { display: none; }
  .sr-strip__inner { gap: .5rem; }
  .sr-select { min-inline-size: 120px; }
}
</style>
@endsection

@section('content')
<div class="sr-page">

  {{-- ── HERO ──────────────────────────────────────────────── --}}
  <div class="sr-hero">
    <div class="sr-hero__inner">

      <div class="sr-hero__eyebrow">
        <i class="fas fa-search"></i>
        Recherche globale
      </div>

      <h1 class="sr-hero__title">
        @if($query)
          Résultats pour <span>« {{ $query }} »</span>
        @else
          Rechercher sur <span>NiangProgrammeur</span>
        @endif
      </h1>

      <p class="sr-hero__count">
        @if($query && strlen($query) >= 2)
          <strong>{{ $results['total'] }}</strong>
          résultat{{ $results['total'] > 1 ? 's' : '' }} trouvé{{ $results['total'] > 1 ? 's' : '' }}
          — <em>{{ $results['formations']->count() }}</em> formation{{ $results['formations']->count() > 1 ? 's' : '' }},
          <em>{{ $results['articles']->count() }}</em> article{{ $results['articles']->count() > 1 ? 's' : '' }}
        @elseif($query)
          Saisissez au moins 2 caractères pour lancer la recherche.
        @else
          Formations, articles emploi, ressources…
        @endif
      </p>

      <form class="sr-search" action="{{ route('search') }}" method="GET" role="search">
        <div class="sr-search__wrap">
          <i class="fas fa-search sr-search__ico" aria-hidden="true"></i>
          <input
            class="sr-search__input"
            type="search"
            name="q"
            value="{{ $query }}"
            placeholder="Formation, technologie, emploi…"
            aria-label="Rechercher sur le site"
            autocomplete="off"
            autofocus
          >
          <button class="sr-search__btn" type="submit">
            <i class="fas fa-arrow-right"></i>
            Rechercher
          </button>
        </div>
      </form>

    </div>
  </div>

  {{-- ── FILTERS STRIP ──────────────────────────────────────── --}}
  @if($query && strlen($query) >= 2)
  <div class="sr-strip">
    <div class="sr-strip__inner">
      <form class="sr-filters" action="{{ route('search') }}" method="GET">
        <input type="hidden" name="q" value="{{ $query }}">

        <span class="sr-filter-lbl"><i class="fas fa-sliders-h"></i> Filtrer</span>

        <select name="category" class="sr-select" onchange="this.form.submit()">
          <option value="">Toutes catégories</option>
          @foreach($categories as $cat)
          <option value="{{ $cat->id }}" {{ $category == $cat->id ? 'selected' : '' }}>
            {{ $cat->name }}
          </option>
          @endforeach
        </select>

        <select name="date" class="sr-select" onchange="this.form.submit()">
          <option value="">Toutes dates</option>
          <option value="today" {{ $dateFilter == 'today' ? 'selected' : '' }}>Aujourd'hui</option>
          <option value="week"  {{ $dateFilter == 'week'  ? 'selected' : '' }}>Cette semaine</option>
          <option value="month" {{ $dateFilter == 'month' ? 'selected' : '' }}>Ce mois</option>
          <option value="year"  {{ $dateFilter == 'year'  ? 'selected' : '' }}>Cette année</option>
        </select>

        <select name="sort" class="sr-select" onchange="this.form.submit()">
          <option value="relevance" {{ $sortBy == 'relevance' ? 'selected' : '' }}>Pertinence</option>
          <option value="date"      {{ $sortBy == 'date'      ? 'selected' : '' }}>Plus récent</option>
          <option value="views"     {{ $sortBy == 'views'     ? 'selected' : '' }}>Plus vus</option>
          <option value="title"     {{ $sortBy == 'title'     ? 'selected' : '' }}>Titre A-Z</option>
        </select>

      </form>

      @if($category || $dateFilter || $sortBy !== 'relevance')
      <a href="{{ route('search', ['q' => $query]) }}" class="sr-clear">
        <i class="fas fa-times"></i> Effacer filtres
      </a>
      @endif

    </div>
  </div>
  @endif

  {{-- ── RESULTS ─────────────────────────────────────────────── --}}
  <div class="sr-main">
    <div class="sr-container">

      @if($query && strlen($query) >= 2)

        @if($results['total'] > 0)

          {{-- FORMATIONS --}}
          @if($results['formations']->count() > 0)
          <div class="sr-section sr-section--formations">
            <div class="sr-section__hd">
              <div class="sr-section__icon"><i class="fas fa-graduation-cap"></i></div>
              <span class="sr-section__title">Formations</span>
              <span class="sr-section__badge">{{ $results['formations']->count() }}</span>
            </div>
            <div class="sr-list">
              @foreach($results['formations'] as $formation)
              @php
                $icons = [
                  'HTML5' => 'fab fa-html5', 'CSS3' => 'fab fa-css3-alt',
                  'JavaScript' => 'fab fa-js', 'PHP' => 'fab fa-php',
                  'Bootstrap' => 'fab fa-bootstrap', 'Git' => 'fab fa-git-alt',
                  'WordPress' => 'fab fa-wordpress',
                  'Intelligence Artificielle' => 'fas fa-robot',
                ];
                $fIcon = $icons[$formation['name']] ?? 'fas fa-code';
              @endphp
              <a href="{{ $formation['url'] }}" class="sr-card">
                <div class="sr-card__thumb">
                  <i class="{{ $fIcon }} sr-card__thumb-ico"></i>
                </div>
                <div class="sr-card__body">
                  <div class="sr-card__type">
                    <i class="fas fa-graduation-cap"></i> Formation
                  </div>
                  <div class="sr-card__title">{{ $formation['name'] }}</div>
                  <div class="sr-card__excerpt">{{ $formation['description'] }}</div>
                  <div class="sr-card__meta">
                    <span class="sr-meta-item"><i class="fas fa-check-circle"></i> Gratuit</span>
                    <span class="sr-meta-item"><i class="fas fa-book-open"></i> Cours complet</span>
                    <span class="sr-meta-item"><i class="fas fa-infinity"></i> Accès illimité</span>
                  </div>
                </div>
                <div class="sr-card__arrow"><i class="fas fa-arrow-right"></i></div>
              </a>
              @endforeach
            </div>
          </div>
          @endif

          {{-- ARTICLES --}}
          @if($results['articles']->count() > 0)
          <div class="sr-section sr-section--articles">
            <div class="sr-section__hd">
              <div class="sr-section__icon"><i class="fas fa-briefcase"></i></div>
              <span class="sr-section__title">Articles emploi</span>
              <span class="sr-section__badge">{{ $results['articles']->count() }}</span>
            </div>
            <div class="sr-list">
              @foreach($results['articles'] as $article)
              <a href="{{ route('emplois.article', $article->slug) }}" class="sr-card">

                <div class="sr-card__thumb">
                  @if($article->cover_image)
                  <img
                    src="{{ $article->cover_type === 'internal'
                      ? \Illuminate\Support\Facades\Storage::url($article->cover_image)
                      : $article->cover_image }}"
                    alt="{{ $article->title }}"
                    loading="lazy"
                    onerror="this.parentElement.innerHTML='<i class=\'fas fa-briefcase sr-card__thumb-ico\'></i>'"
                  >
                  @else
                  <i class="fas fa-briefcase sr-card__thumb-ico"></i>
                  @endif
                </div>

                <div class="sr-card__body">
                  <div class="sr-card__type">
                    <i class="fas fa-briefcase"></i> Emploi
                  </div>
                  <div class="sr-card__title">{{ $article->title }}</div>
                  <div class="sr-card__excerpt">
                    {{ $article->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($article->content), 130) }}
                  </div>
                  <div class="sr-card__meta">
                    @if($article->category)
                    <span class="sr-meta-item">
                      <i class="fas fa-folder"></i> {{ $article->category->name }}
                    </span>
                    @endif
                    <span class="sr-meta-item">
                      <i class="fas fa-calendar"></i>
                      {{ ($article->published_at ?? $article->created_at)->format('d/m/Y') }}
                    </span>
                    <span class="sr-meta-item">
                      <i class="fas fa-eye"></i> {{ $article->featured_display_views }}
                    </span>
                  </div>
                </div>

                <div class="sr-card__arrow"><i class="fas fa-arrow-right"></i></div>
              </a>
              @endforeach
            </div>
          </div>
          @endif

        @else

          {{-- Aucun résultat --}}
          <div class="sr-empty">
            <div class="sr-empty__ico"><i class="fas fa-search-minus"></i></div>
            <h2 class="sr-empty__title">Aucun résultat pour « {{ $query }} »</h2>
            <p class="sr-empty__sub">
              Essayez avec d'autres mots-clés, vérifiez l'orthographe
              ou parcourez nos formations disponibles.
            </p>
            <a href="{{ route('home') }}#technologies" class="sr-btn">
              <i class="fas fa-graduation-cap"></i> Voir toutes les formations
            </a>
            <div class="sr-suggestions">
              @foreach(['HTML', 'CSS', 'JavaScript', 'PHP', 'Python', 'Git'] as $sug)
              <a href="{{ route('search', ['q' => $sug]) }}" class="sr-suggestion">
                <i class="fas fa-search"></i> {{ $sug }}
              </a>
              @endforeach
            </div>
          </div>

        @endif

      @else

        {{-- Pas de requête --}}
        <div class="sr-empty">
          <div class="sr-empty__ico"><i class="fas fa-search"></i></div>
          <h2 class="sr-empty__title">Que recherchez-vous ?</h2>
          <p class="sr-empty__sub">
            Entrez un mot-clé ci-dessus pour trouver des formations,
            des articles emploi et des ressources pour développeurs.
          </p>
          <p style="font-size:.82rem; color:var(--sr-muted); margin-block-end:1rem; font-weight:600;">
            SUGGESTIONS POPULAIRES
          </p>
          <div class="sr-suggestions">
            @foreach(['HTML5', 'CSS3', 'JavaScript', 'PHP', 'Python', 'Bootstrap', 'Git', 'WordPress'] as $sug)
            <a href="{{ route('search', ['q' => $sug]) }}" class="sr-suggestion">
              <i class="fas fa-bolt"></i> {{ $sug }}
            </a>
            @endforeach
          </div>
        </div>

      @endif

    </div>
  </div>

</div>
@endsection
