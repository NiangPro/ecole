@extends('layouts.app')

@section('title', __('homepage.title'))
@section('meta_description', __('homepage.meta_description'))

@push('page_css')
@vite('resources/css/features/homepage.css')
@endpush

@push('preload_images')
<link rel="preload" as="image" href="{{ asset('images/hero-bg-mobile.webp') }}" type="image/webp" fetchpriority="high" media="(max-width: 768px)">
@php $heroBgDesktop = public_path('images/hero-bg.webp'); @endphp
@if(file_exists($heroBgDesktop) && filesize($heroBgDesktop) > 0)
<link rel="preload" as="image" href="{{ asset('images/hero-bg.webp') }}" type="image/webp" fetchpriority="high" media="(min-width: 769px)">
@endif
@endpush

{{-- ─────────────────────────────────────────────────────────
     HERO
     ───────────────────────────────────────────────────────── --}}
@section('content')

  {{-- HERO --}}
  <section class="hp-hero">
    <div>
      <span class="hp-hero__eyebrow">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
        {{ __('homepage.hero.eyebrow') }}
      </span>

      <h1 class="hp-hero__title">
        {{ __('homepage.hero.title_1') }}<br>
        <span class="accent">{{ __('homepage.hero.title_2') }}</span>
      </h1>

      <p class="hp-hero__subtitle">
        {{ __('homepage.hero.subtitle') }}
      </p>

      <div class="hp-hero__actions">
        <a href="{{ route('formations.all') }}" class="hp-btn-primary">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 3l14 9-14 9V3z"/></svg>
          {{ __('homepage.hero.btn_start') }}
        </a>
        <a href="{{ route('documents.index') }}" class="hp-btn-secondary">
          {{ __('homepage.hero.btn_browse') }}
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
      </div>
    </div>
  </section>

  @include('partials.urgency-banner')

  {{-- ─────────────────────────────────────────────────────────
       DOCUMENTS VEDETTES
       ───────────────────────────────────────────────────────── --}}
  @if($featuredDocuments->isNotEmpty())
  <section class="hp-section">
    <div class="hp-container">

      <div class="hp-section-header" style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
        <div>
          <span class="hp-section-eyebrow">{{ __('homepage.docs.eyebrow') }}</span>
          <h2 class="hp-section-title">{{ __('homepage.docs.title') }}</h2>
          <p class="hp-section-subtitle">{{ __('homepage.docs.subtitle') }}</p>
        </div>
        <div class="hp-docs-carousel-actions">
          <button type="button" class="hp-docs-nav" id="hpDocsPrev" aria-label="Précédent"><i class="fas fa-chevron-left"></i></button>
          <button type="button" class="hp-docs-nav" id="hpDocsNext" aria-label="Suivant"><i class="fas fa-chevron-right"></i></button>
          <a href="{{ route('documents.index') }}" class="hp-section-action">
            {{ __('homepage.docs.view_all') }}
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </a>
        </div>
      </div>

      <div class="hp-docs-carousel" id="hpDocsViewport">
        @foreach($featuredDocuments as $document)
        <article class="hp-doc-card">
          <a href="{{ route('documents.show', $document->slug) }}" class="hp-doc-cover">
            @if($document->cover_image)
              @if($document->cover_type === 'internal')
                <img src="{{ \Illuminate\Support\Facades\URL::temporarySignedRoute('document.cover.signed', now()->addHours(24), ['id' => $document->id]) }}"
                     alt="{{ $document->title }}"
                     width="280" height="200"
                     loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                     {{ $loop->first ? 'fetchpriority="high"' : '' }}
                     decoding="async">
              @else
                <img src="{{ $document->cover_image }}"
                     alt="{{ $document->title }}"
                     width="280" height="200"
                     loading="lazy"
                     decoding="async">
              @endif
            @else
              <div class="hp-doc-placeholder">📄</div>
            @endif

            @if($document->is_featured)
              <span class="hp-doc-badge">{{ __('homepage.docs.featured') }}</span>
            @endif

            @if($document->price && $document->price > 0)
              <div class="document-price-overlay">
                @if($document->hasDiscount())
                  <span class="document-price-old">{{ number_format($document->price, 0, ',', ' ') }} FCFA</span>
                @endif
                <span class="document-price-current">{{ number_format($document->hasDiscount() ? $document->discount_price : $document->price, 0, ',', ' ') }} FCFA</span>
              </div>
            @else
              <div class="hp-doc-price">
                <span class="hp-doc-price-current">{{ __('homepage.docs.free') }}</span>
              </div>
            @endif
          </a>

          <div class="hp-doc-body">
            <a href="{{ route('documents.show', $document->slug) }}" class="hp-doc-title">
              {{ $document->title }}
            </a>
            @if(($document->reviews_count ?? 0) > 0)
            <div class="doc-rating-row">
              <span class="doc-rating-stars">
                @for($i = 1; $i <= 5; $i++)
                  <i class="fas fa-star{{ $i <= round($document->average_rating) ? '' : '-o' }}"></i>
                @endfor
              </span>
              <span class="doc-rating-val">{{ number_format($document->average_rating, 1) }}</span>
              <span class="doc-rating-count">({{ $document->reviews_count }})</span>
            </div>
            @endif
            <div class="hp-doc-footer">
              <span class="hp-doc-category">{{ $document->category?->name ?? __('homepage.docs.general') }}</span>
              <a href="{{ route('documents.show', $document->slug) }}" class="hp-doc-arrow" aria-label="Voir le document">→</a>
            </div>
          </div>
        </article>
        @endforeach
      </div>

    </div>
  </section>

  @push('scripts')
  <script>
  document.addEventListener('DOMContentLoaded', function () {
      var viewport = document.getElementById('hpDocsViewport');
      if (!viewport) return;

      var prevBtn = document.getElementById('hpDocsPrev');
      var nextBtn = document.getElementById('hpDocsNext');

      function step() {
          var card = viewport.querySelector('.hp-doc-card');
          var gap = 20;
          return card ? card.getBoundingClientRect().width + gap : viewport.clientWidth * 0.8;
      }

      function updateNavState() {
          var max = viewport.scrollWidth - viewport.clientWidth - 2;
          prevBtn.disabled = viewport.scrollLeft <= 0;
          nextBtn.disabled = viewport.scrollLeft >= max;
      }

      prevBtn.addEventListener('click', function () {
          viewport.scrollBy({ left: -step(), behavior: 'smooth' });
      });
      nextBtn.addEventListener('click', function () {
          viewport.scrollBy({ left: step(), behavior: 'smooth' });
      });
      viewport.addEventListener('scroll', updateNavState, { passive: true });
      window.addEventListener('resize', updateNavState);
      updateNavState();
  });
  </script>
  @endpush
  @endif


  {{-- ─────────────────────────────────────────────────────────
       PACKS (BUNDLES)
       ───────────────────────────────────────────────────────── --}}
  @if($featuredBundles->isNotEmpty())
  <section class="hp-section hp-section--alt">
    <div class="hp-container">

      <div class="hp-section-header" style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
        <div>
          <span class="hp-section-eyebrow">Économisez</span>
          <h2 class="hp-section-title">Packs de documents</h2>
          <p class="hp-section-subtitle">Plusieurs documents réunis à prix réduit</p>
        </div>
        <a href="{{ route('bundles.index') }}" class="hp-section-action">
          Voir tous les packs
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
      </div>

      <div class="hp-docs-grid">
        @foreach($featuredBundles as $bundle)
        <article class="hp-doc-card">
          <a href="{{ route('bundles.show', $bundle->slug) }}" class="hp-doc-cover">
            @if($bundle->cover_image)
              <img src="{{ $bundle->cover_image }}" alt="{{ $bundle->name }}" width="280" height="200" loading="lazy" decoding="async">
            @else
              <div class="hp-doc-placeholder">📦</div>
            @endif

            @if($bundle->savings > 0)
              <span class="hp-doc-badge">-{{ $bundle->getDiscountPercentage() }}%</span>
            @endif

            <div class="hp-doc-price">
              <span class="hp-doc-price-current">{{ number_format($bundle->current_price, 0, ',', ' ') }} FCFA</span>
              @if($bundle->hasDiscount())
                <span class="hp-doc-price-old">{{ number_format($bundle->price, 0, ',', ' ') }}</span>
              @endif
            </div>
          </a>

          <div class="hp-doc-body">
            <a href="{{ route('bundles.show', $bundle->slug) }}" class="hp-doc-title">
              {{ $bundle->name }}
            </a>
            <div class="hp-doc-footer">
              <span class="hp-doc-category">{{ $bundle->items->count() }} document{{ $bundle->items->count() > 1 ? 's' : '' }}</span>
              <a href="{{ route('bundles.show', $bundle->slug) }}" class="hp-doc-arrow" aria-label="Voir le pack">→</a>
            </div>
          </div>
        </article>
        @endforeach
      </div>

    </div>
  </section>
  @endif


  {{-- ─────────────────────────────────────────────────────────
       AVIS RÉCENTS (PREUVE SOCIALE)
       ───────────────────────────────────────────────────────── --}}
  @if($latestReviews->isNotEmpty())
  <section class="hp-section hp-reviews-section">
    <div class="hp-container">

      <div class="hp-section-header hp-reviews-header">
        <div>
          <span class="hp-section-eyebrow">Preuve sociale</span>
          <h2 class="hp-section-title">Ils ont réussi avec nous</h2>
          <p class="hp-section-subtitle">Ce que disent les étudiants qui utilisent nos corrigés et documents</p>
        </div>

        <div class="hp-reviews-header-side">
          @if($reviewsStats['count'] > 0)
          <div class="hp-reviews-stat" role="img" aria-label="Note moyenne {{ number_format($reviewsStats['average'], 1) }} sur 5, basée sur {{ $reviewsStats['count'] }} avis vérifiés">
            <div class="hp-reviews-stat-score">{{ number_format($reviewsStats['average'], 1) }}<span>/5</span></div>
            <div class="hp-reviews-stat-stars" aria-hidden="true">
              @for($i = 1; $i <= 5; $i++)
                <i class="fas fa-star{{ $i <= round($reviewsStats['average']) ? '' : '-o' }}"></i>
              @endfor
            </div>
            <div class="hp-reviews-stat-count">{{ number_format($reviewsStats['count']) }} avis vérifiés</div>
          </div>
          @endif
          <div class="hp-docs-nav-group">
            <button type="button" class="hp-docs-nav" id="hpReviewsPrev" aria-label="Témoignage précédent"><i class="fas fa-chevron-left"></i></button>
            <button type="button" class="hp-docs-nav" id="hpReviewsNext" aria-label="Témoignage suivant"><i class="fas fa-chevron-right"></i></button>
          </div>
        </div>
      </div>

      <div class="hp-reviews-carousel-wrap">
        <div class="hp-reviews-carousel" id="hpReviewsViewport">
          @foreach($latestReviews as $review)
          <article class="hp-review-card" style="--stagger: {{ $loop->index }};">
            <i class="fas fa-quote-right hp-review-quote-icon" aria-hidden="true"></i>

            <div class="hp-review-stars" aria-label="Note : {{ $review->rating }} sur 5">
              @for($i = 1; $i <= 5; $i++)
                <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}" aria-hidden="true"></i>
              @endfor
            </div>

            @if($review->comment)
            <p class="hp-review-comment">&laquo;&nbsp;{{ \Illuminate\Support\Str::limit($review->comment, 140) }}&nbsp;&raquo;</p>
            @endif

            <div class="hp-review-footer">
              <div class="hp-review-avatar" aria-hidden="true">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($review->display_name, 0, 1)) }}</div>
              <div class="hp-review-meta">
                <span class="hp-review-author">
                  {{ $review->display_name }}
                  @if($review->is_verified_purchase)
                  <i class="fas fa-check-circle hp-review-verified" title="Achat vérifié" aria-label="Achat vérifié"></i>
                  @endif
                </span>
                @if($review->document)
                <a href="{{ route('documents.show', $review->document->slug) }}" class="hp-review-doc-link">
                  <i class="fas fa-file-alt" aria-hidden="true"></i>
                  {{ \Illuminate\Support\Str::limit($review->document->title, 36) }}
                </a>
                @endif
              </div>
            </div>
          </article>
          @endforeach
        </div>
      </div>

      <div class="hp-reviews-dots" id="hpReviewsDots" role="tablist" aria-label="Navigation des témoignages">
        @foreach($latestReviews as $review)
        <button type="button" class="hp-reviews-dot" role="tab" aria-label="Témoignage {{ $loop->iteration }}"></button>
        @endforeach
      </div>

    </div>
  </section>

  @push('scripts')
  <script>
  document.addEventListener('DOMContentLoaded', function () {
      var viewport = document.getElementById('hpReviewsViewport');
      if (!viewport) return;

      var prevBtn = document.getElementById('hpReviewsPrev');
      var nextBtn = document.getElementById('hpReviewsNext');
      var dotsWrap = document.getElementById('hpReviewsDots');
      var dots = dotsWrap ? Array.from(dotsWrap.querySelectorAll('.hp-reviews-dot')) : [];
      var cards = Array.from(viewport.querySelectorAll('.hp-review-card'));
      var autoplayMs = 5000;
      var autoplayTimer = null;
      var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

      function cardStep() {
          var card = viewport.querySelector('.hp-review-card');
          var gap = 24;
          return card ? card.getBoundingClientRect().width + gap : viewport.clientWidth * 0.85;
      }

      function activeIndex() {
          var pos = viewport.scrollLeft;
          var step = cardStep();
          return step ? Math.round(pos / step) : 0;
      }

      function updateState() {
          var max = viewport.scrollWidth - viewport.clientWidth - 2;
          if (prevBtn) prevBtn.disabled = viewport.scrollLeft <= 0;
          if (nextBtn) nextBtn.disabled = viewport.scrollLeft >= max;

          var idx = activeIndex();
          dots.forEach(function (dot, i) {
              dot.classList.toggle('is-active', i === idx);
          });
      }

      function goTo(index) {
          var clamped = Math.max(0, Math.min(index, cards.length - 1));
          viewport.scrollTo({ left: clamped * cardStep(), behavior: 'smooth' });
      }

      function stopAutoplay() {
          if (autoplayTimer) {
              clearInterval(autoplayTimer);
              autoplayTimer = null;
          }
      }

      function startAutoplay() {
          if (reducedMotion || cards.length <= 1) return;
          stopAutoplay();
          autoplayTimer = setInterval(function () {
              var max = viewport.scrollWidth - viewport.clientWidth - 2;
              if (viewport.scrollLeft >= max) {
                  viewport.scrollTo({ left: 0, behavior: 'smooth' });
              } else {
                  viewport.scrollBy({ left: cardStep(), behavior: 'smooth' });
              }
          }, autoplayMs);
      }

      if (prevBtn) prevBtn.addEventListener('click', function () {
          stopAutoplay();
          viewport.scrollBy({ left: -cardStep(), behavior: 'smooth' });
      });
      if (nextBtn) nextBtn.addEventListener('click', function () {
          stopAutoplay();
          viewport.scrollBy({ left: cardStep(), behavior: 'smooth' });
      });
      dots.forEach(function (dot, i) {
          dot.addEventListener('click', function () {
              stopAutoplay();
              goTo(i);
          });
      });

      viewport.addEventListener('scroll', updateState, { passive: true });
      viewport.addEventListener('mouseenter', stopAutoplay);
      viewport.addEventListener('mouseleave', startAutoplay);
      viewport.addEventListener('touchstart', stopAutoplay, { passive: true });
      window.addEventListener('resize', updateState);

      updateState();
      startAutoplay();
  });
  </script>
  @endpush
  @endif


  {{-- ─────────────────────────────────────────────────────────
       CATÉGORIES / TECHNOLOGIES
       ───────────────────────────────────────────────────────── --}}
  @if($categories->isNotEmpty())
  <section class="hp-section hp-section--alt">
    <div class="hp-container">

      <div class="hp-section-header" style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
        <div>
          <span class="hp-section-eyebrow">{{ __('homepage.categories.eyebrow') }}</span>
          <h2 class="hp-section-title">{{ __('homepage.categories.title') }}</h2>
          <p class="hp-section-subtitle">{{ __('homepage.categories.subtitle') }}</p>
        </div>
      </div>

      <div class="hp-tech-grid">
        @foreach($categories as $category)
        <a href="{{ route('emplois.category', $category->slug) }}" class="hp-tech-card">
          <div class="hp-tech-icon">
            @if($category->image)
              <img src="{{ $category->image_type === 'internal' ? \Illuminate\Support\Facades\Storage::url($category->image) : $category->image }}" alt="{{ $category->name }}" loading="lazy" width="44" height="44">
            @else
              💻
            @endif
          </div>
          <div>
            <div class="hp-tech-name">{{ $category->name }}</div>
            <div class="hp-tech-count">
              @php $count = $category->published_articles_count ?? 0; @endphp
              {{ $count }} {{ $count > 1 ? __('homepage.categories.article_other') : __('homepage.categories.article_one') }}
            </div>
          </div>
          <span class="hp-tech-arrow">→</span>
        </a>
        @endforeach
      </div>

    </div>
  </section>
  @endif

  {{-- ─────────────────────────────────────────────────────────
       ÉPREUVES & CORRIGÉS RÉCENTS
       ───────────────────────────────────────────────────────── --}}
  @if($latestEpreuves->isNotEmpty())
  <section class="hp-section hp-section--alt">
    <div class="hp-container">

      <div class="hp-section-header" style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
        <div>
          <span class="hp-section-eyebrow">{{ __('homepage.epreuves.eyebrow') }}</span>
          <h2 class="hp-section-title">{{ __('homepage.epreuves.title') }}</h2>
          <p class="hp-section-subtitle">{{ __('homepage.epreuves.subtitle') }}</p>
        </div>
        <a href="{{ route('epreuves.index') }}" class="hp-section-action">{{ __('homepage.epreuves.view_all') }} →</a>
      </div>

      <div class="hp-epreuves-grid">
        @foreach($latestEpreuves as $epreuve)
        <a href="{{ route('epreuves.show', $epreuve->slug) }}" class="epreuve-card">
          <div class="epreuve-card-badges">
            @if($epreuve->exam === 'concours')
              @if($epreuve->level)
                <span class="epreuve-badge epreuve-badge--concours"><i class="fas fa-award" style="margin-right:0.3rem;"></i>{{ $epreuve->level_label }}</span>
              @else
                <span class="epreuve-badge epreuve-badge--concours"><i class="fas fa-award" style="margin-right:0.3rem;"></i>Concours</span>
              @endif
            @elseif($epreuve->exam)
              <span class="epreuve-badge epreuve-badge--exam">{{ $epreuve->exam_label }}</span>
            @elseif($epreuve->level)
              <span class="epreuve-badge epreuve-badge--exam">{{ $epreuve->level_label }}</span>
            @endif
            <span class="epreuve-badge epreuve-badge--type">{{ $epreuve->type_label }}</span>
            @if($epreuve->hasCorrige() || $epreuve->type === 'corrige' || $epreuve->type === 'epreuve_corrige')
              @if($epreuve->exam !== 'concours' && !$epreuve->isCorrigeFree())
                <span class="epreuve-badge epreuve-badge--corrige"><i class="fas fa-tag" style="margin-right:0.25rem;"></i>Corrigé — {{ $epreuve->corrige_price_formatted }}</span>
              @else
                <span class="epreuve-badge epreuve-badge--corrige">Corrigé inclus</span>
              @endif
            @endif
            @if($epreuve->exam === 'concours' && !$epreuve->isFree())
              <span class="epreuve-badge epreuve-badge--price"><i class="fas fa-tag" style="margin-right:0.25rem;"></i>{{ $epreuve->price_formatted }}</span>
            @endif
          </div>
          <div class="epreuve-card-title">{{ $epreuve->title }}</div>
          <div class="epreuve-card-meta">
            @if($epreuve->matiere)<span><i class="fas fa-book"></i>{{ $epreuve->matiere->name }}</span>@endif
            @if($epreuve->serie)<span><i class="fas fa-layer-group"></i>Série {{ $epreuve->serie }}</span>@endif
            @if($epreuve->year)<span><i class="fas fa-calendar"></i>{{ $epreuve->year_label }}</span>@endif
            <span><i class="fas fa-download"></i>{{ number_format($epreuve->downloads_count, 0, ',', ' ') }}</span>
          </div>
        </a>
        @endforeach
      </div>

    </div>
  </section>
  @endif

  {{-- ─────────────────────────────────────────────────────────
       ARTICLES VEDETTES + SIDEBAR
       ───────────────────────────────────────────────────────── --}}
  @if($featuredArticles->isNotEmpty() || $latestJobs->isNotEmpty())
  <section class="hp-section">
    <div class="hp-container">

      <div class="hp-section-header" style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
        <div>
          <span class="hp-section-eyebrow">{{ __('homepage.featured.eyebrow') }}</span>
          <h2 class="hp-section-title">{{ __('homepage.featured.title') }}</h2>
          <p class="hp-section-subtitle">{{ __('homepage.featured.subtitle') }}</p>
        </div>
        <a href="{{ route('emplois') }}" class="hp-section-action">
          {{ __('homepage.featured.view_all') }}
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
      </div>

      <div class="hp-articles-layout">

        {{-- GRILLE ARTICLES --}}
        <div class="hp-articles-grid">
          @php $articlesList = $featuredArticles->isNotEmpty() ? $featuredArticles : $latestJobs->take(4); @endphp
          @foreach($articlesList as $article)
          @php $hpArticleUrl = route('emplois.article', $article->slug); @endphp
          <div class="hp-article-card">
            <a href="{{ $hpArticleUrl }}" class="hp-article-card-link" aria-label="{{ $article->title }}"></a>
            @include('partials.hp-article-share', ['shareUrl' => $hpArticleUrl, 'shareTitle' => $article->title])
            @if($article->cover_image)
            <div class="hp-article-img">
              @if($article->cover_type === 'external')
                <img src="{{ $article->cover_image }}" alt="{{ $article->title }}" width="400" height="225" loading="lazy" decoding="async">
              @else
                <img src="{{ asset('storage/' . $article->cover_image) }}" alt="{{ $article->title }}" width="400" height="225" loading="lazy" decoding="async">
              @endif
            </div>
            @endif
            <div class="hp-article-body">
              <span class="hp-article-category">{{ $article->category?->name ?? __('homepage.featured.general') }}</span>
              <h3 class="hp-article-title">{{ $article->title }}</h3>
              <div class="hp-article-meta">
                <span>{{ $article->published_at ? \Carbon\Carbon::parse($article->published_at)->diffForHumans() : __('homepage.featured.recently') }}</span>
                <span>·</span>
                <span>{{ $article->featured_display_views }} {{ __('homepage.featured.views') }}</span>
              </div>
            </div>
          </div>
          @endforeach
        </div>

        {{-- SIDEBAR --}}
        @if($careerAdviceArticles->isNotEmpty())
        <aside class="hp-sidebar">
          <div class="hp-sidebar-title">{{ __('homepage.featured.sidebar') }}</div>
          @foreach($careerAdviceArticles->take(6) as $i => $article)
          <a href="{{ route('emplois.article', $article->slug) }}" class="hp-sidebar-item">
            <span class="hp-sidebar-num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
            <div>
              <div class="hp-sidebar-item-title">{{ $article->title }}</div>
              <div class="hp-sidebar-item-meta">
                {{ $article->published_at ? \Carbon\Carbon::parse($article->published_at)->diffForHumans() : __('homepage.featured.recently') }}
              </div>
            </div>
          </a>
          @endforeach
        </aside>
        @endif

      </div>
    </div>
  </section>
  @endif


  {{-- ─────────────────────────────────────────────────────────
       COURS PAYANTS
       ───────────────────────────────────────────────────────── --}}
  @if($paidCourses->isNotEmpty())
  <section class="hp-section hp-section--alt">
    <div class="hp-container">

      <div class="hp-section-header" style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
        <div>
          <span class="hp-section-eyebrow">{{ __('homepage.courses.eyebrow') }}</span>
          <h2 class="hp-section-title">{{ __('homepage.courses.title') }}</h2>
          <p class="hp-section-subtitle">{{ __('homepage.courses.subtitle') }}</p>
        </div>
        <a href="{{ route('monetization.courses') }}" class="hp-section-action">
          {{ __('homepage.courses.view_all') }}
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
      </div>

      <div class="hp-courses-grid">
        @foreach($paidCourses as $course)
        @php
          $isOnSale = $course->discount_price
            && $course->discount_price < $course->price
            && (!$course->discount_start || now()->gte($course->discount_start))
            && (!$course->discount_end   || now()->lte($course->discount_end));
          $displayPrice = $isOnSale ? $course->discount_price : $course->price;
        @endphp
        <article class="hp-course-card">
          <a href="{{ route('monetization.course.show', $course->slug) }}" class="hp-course-card-link" aria-label="{{ $course->title }}"></a>
          <div class="hp-course-cover">
            @if($course->cover_image)
              @if(($course->cover_type ?? 'internal') === 'internal')
                <img src="{{ asset('storage/' . $course->cover_image) }}" alt="{{ $course->title }}" width="340" height="192" loading="lazy" decoding="async">
              @else
                <img src="{{ $course->cover_image }}" alt="{{ $course->title }}" width="340" height="192" loading="lazy" decoding="async">
              @endif
            @endif
            <span class="hp-course-level-badge">{{ __('homepage.courses.premium') }}</span>
          </div>

          <div class="hp-course-body">
            <h3 class="hp-course-title">{{ $course->title }}</h3>
            @if($course->description)
              <p class="hp-course-desc">{{ $course->description }}</p>
            @endif

            <div class="hp-course-meta">
              @if($course->duration_hours)
              <span class="hp-course-meta-item">
                <i>⏱</i> {{ $course->duration_hours }}h
              </span>
              @endif
              @if($course->students_count)
              <span class="hp-course-meta-item">
                <i>👥</i> {{ number_format($course->students_count) }} {{ __('homepage.courses.students') }}
              </span>
              @endif
              @if($course->rating)
              <span class="hp-course-meta-item">
                <i>⭐</i> {{ number_format($course->rating, 1) }}
              </span>
              @endif
            </div>

            <div class="hp-course-footer">
              <div>
                @if($isOnSale)
                  <span class="hp-course-price-old">{{ number_format($course->price, 0, ',', ' ') }} {{ $course->currency ?? 'FCFA' }}</span>
                @endif
                <span class="hp-course-price">{{ number_format($displayPrice, 0, ',', ' ') }} {{ $course->currency ?? 'FCFA' }}</span>
              </div>
              <span class="hp-course-cta">
                {{ __('homepage.courses.enroll') }}
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
              </span>
            </div>
          </div>
        </article>
        @endforeach
      </div>

    </div>
  </section>
  @endif

  {{-- ─────────────────────────────────────────────────────────
       PUBLICITÉS NATIVES ($homepageAds)
       ───────────────────────────────────────────────────────── --}}
  @if($homepageAds->isNotEmpty())
  <section class="hp-section">
    <div class="hp-container">
      @foreach($homepageAds->take(1) as $ad)
      <div class="hp-native-ad" style="margin-block-end:1.5rem;">
        <span class="hp-native-ad__label">{{ __('homepage.ads.sponsored') }}</span>

        @if($ad->image)
        <div class="hp-native-ad__img-wrap">
          @if(($ad->image_type ?? 'internal') === 'internal')
            <img src="{{ asset('storage/' . $ad->image) }}" alt="{{ $ad->name }}" width="640" height="360" loading="lazy" decoding="async">
          @else
            <img src="{{ $ad->image }}" alt="{{ $ad->name }}" width="640" height="360" loading="lazy" decoding="async">
          @endif
        </div>
        @endif

        <div class="hp-native-ad__content">
          <h3 class="hp-native-ad__title">{{ $ad->name }}</h3>
          @if($ad->description)
            <p class="hp-native-ad__desc">{{ $ad->description }}</p>
          @endif
          @if($ad->link_url)
            <a href="{{ $ad->link_url }}" class="hp-native-ad__cta" target="_blank" rel="noopener sponsored">
              {{ __('homepage.ads.learn_more') }}
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
          @endif
        </div>
      </div>
      @endforeach
    </div>
  </section>
  @endif

  {{-- ─────────────────────────────────────────────────────────
       ARTICLES SPONSORISÉS ($sponsoredArticles)
       ───────────────────────────────────────────────────────── --}}
  @if($sponsoredArticles->isNotEmpty())
  <section class="hp-section hp-section--alt">
    <div class="hp-container">

      <div class="hp-section-header">
        <span class="hp-section-eyebrow">{{ __('homepage.ads.sponsored') }}</span>
        <h2 class="hp-section-title">{{ __('homepage.ads.title') }}</h2>
      </div>

      <div class="hp-articles-grid--3col">
        @foreach($sponsoredArticles->take(3) as $article)
        @php $hpArticleUrl = route('emplois.article', $article->slug); @endphp
        <div class="hp-article-card">
          <a href="{{ $hpArticleUrl }}" class="hp-article-card-link" aria-label="{{ $article->title }}"></a>
          @include('partials.hp-article-share', ['shareUrl' => $hpArticleUrl, 'shareTitle' => $article->title])
          @if($article->cover_image)
          <div class="hp-article-img">
            @if($article->cover_type === 'external')
              <img src="{{ $article->cover_image }}" alt="{{ $article->title }}" width="400" height="225" loading="lazy" decoding="async">
            @else
              <img src="{{ asset('storage/' . $article->cover_image) }}" alt="{{ $article->title }}" width="400" height="225" loading="lazy" decoding="async">
            @endif
          </div>
          @endif
          <div class="hp-article-body">
            <span class="hp-article-category">{{ $article->category?->name ?? __('homepage.ads.sponsored') }}</span>
            <h3 class="hp-article-title">{{ $article->title }}</h3>
            @if($article->excerpt)
              <p style="font-size:0.8125rem;color:var(--text-muted);line-height:1.5;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                {{ $article->excerpt }}
              </p>
            @endif
            <div class="hp-article-meta">
              <span>{{ $article->published_at ? \Carbon\Carbon::parse($article->published_at)->diffForHumans() : __('homepage.featured.recently') }}</span>
            </div>
          </div>
        </div>
        @endforeach
      </div>

    </div>
  </section>
  @endif

  {{-- ─────────────────────────────────────────────────────────
       DERNIERS ARTICLES (latestJobs)
       ───────────────────────────────────────────────────────── --}}
  @if($latestJobs->isNotEmpty())
  <section class="hp-section">
    <div class="hp-container">

      <div class="hp-section-header" style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
        <div>
          <span class="hp-section-eyebrow">{{ __('homepage.latest.eyebrow') }}</span>
          <h2 class="hp-section-title">{{ __('homepage.latest.title') }}</h2>
          <p class="hp-section-subtitle">{{ __('homepage.latest.subtitle') }}</p>
        </div>
        <a href="{{ route('emplois') }}" class="hp-section-action">
          {{ __('homepage.latest.view_all') }}
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
      </div>

      <div class="hp-articles-grid--4col">
        @foreach($latestJobs->take(8) as $article)
        @php $hpArticleUrl = route('emplois.article', $article->slug); @endphp
        <div class="hp-article-card">
          <a href="{{ $hpArticleUrl }}" class="hp-article-card-link" aria-label="{{ $article->title }}"></a>
          @include('partials.hp-article-share', ['shareUrl' => $hpArticleUrl, 'shareTitle' => $article->title])
          @if($article->cover_image)
          <div class="hp-article-img">
            @if($article->cover_type === 'external')
              <img src="{{ $article->cover_image }}" alt="{{ $article->title }}" width="400" height="225" loading="lazy" decoding="async">
            @else
              <img src="{{ asset('storage/' . $article->cover_image) }}" alt="{{ $article->title }}" width="400" height="225" loading="lazy" decoding="async">
            @endif
          </div>
          @endif
          <div class="hp-article-body">
            <span class="hp-article-category">{{ $article->category?->name ?? __('homepage.latest.general') }}</span>
            <h3 class="hp-article-title">{{ $article->title }}</h3>
            <div class="hp-article-meta">
              <span>{{ $article->published_at ? \Carbon\Carbon::parse($article->published_at)->diffForHumans() : __('homepage.latest.recently') }}</span>
              <span>·</span>
              <span>{{ $article->featured_display_views }} {{ __('homepage.latest.views') }}</span>
            </div>
          </div>
        </div>
        @endforeach
      </div>

    </div>
  </section>
  @endif

  {{-- ─────────────────────────────────────────────────────────
       SIDEBAR ADS ($sidebarAds)
       ───────────────────────────────────────────────────────── --}}
  @if($sidebarAds->isNotEmpty())
  <section class="hp-section hp-section--alt">
    <div class="hp-container">
      <div class="hp-section-header">
        <span class="hp-section-eyebrow">{{ __('homepage.partners.eyebrow') }}</span>
        <h2 class="hp-section-title">{{ __('homepage.partners.title') }}</h2>
      </div>
      @foreach($sidebarAds as $ad)
      <div class="hp-native-ad" style="margin-block-end:1.5rem;">
        <span class="hp-native-ad__label">{{ __('homepage.partners.label') }}</span>
        @if($ad->image)
        <div class="hp-native-ad__img-wrap">
          @if(($ad->image_type ?? 'internal') === 'internal')
            <img src="{{ asset('storage/' . $ad->image) }}" alt="{{ $ad->name }}" width="640" height="360" loading="lazy" decoding="async">
          @else
            <img src="{{ $ad->image }}" alt="{{ $ad->name }}" width="640" height="360" loading="lazy" decoding="async">
          @endif
        </div>
        @endif
        <div class="hp-native-ad__content">
          <h3 class="hp-native-ad__title">{{ $ad->name }}</h3>
          @if($ad->description)
            <p class="hp-native-ad__desc">{{ $ad->description }}</p>
          @endif
          @if($ad->link_url)
            <a href="{{ $ad->link_url }}" class="hp-native-ad__cta" target="_blank" rel="noopener sponsored">
              {{ __('homepage.partners.discover') }}
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
          @endif
        </div>
      </div>
      @endforeach
    </div>
  </section>
  @endif

  {{-- ─────────────────────────────────────────────────────────
       CTA BANNER
       ───────────────────────────────────────────────────────── --}}
  <div class="hp-cta-banner">
    <div class="hp-container">
      <h2 class="hp-cta-banner__title">{{ __('homepage.cta.title') }}</h2>
      <p class="hp-cta-banner__subtitle">
        {{ __('homepage.cta.subtitle') }}
      </p>
      <div style="display:flex;align-items:center;justify-content:center;gap:1rem;flex-wrap:wrap;">
        <a href="{{ route('formations.all') }}" class="hp-btn-primary">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
          {{ __('homepage.cta.btn_start') }}
        </a>
        <a href="{{ route('documents.index') }}" class="hp-btn-secondary hp-btn-secondary--adaptive">
          {{ __('homepage.cta.btn_dl') }}
        </a>
      </div>
    </div>
  </div>

<script>
(function () {
    document.addEventListener('click', function (e) {
        const toggle = e.target.closest('[data-share-toggle]');
        const copyBtn = e.target.closest('[data-copy-link]');

        if (copyBtn) {
            e.preventDefault();
            e.stopPropagation();
            const url = copyBtn.getAttribute('data-copy-link');
            const finish = () => {
                const original = copyBtn.innerHTML;
                copyBtn.innerHTML = '<i class="fas fa-check"></i> Lien copié !';
                setTimeout(() => { copyBtn.innerHTML = original; }, 1800);
            };
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(url).then(finish).catch(finish);
            } else {
                const ta = document.createElement('textarea');
                ta.value = url;
                document.body.appendChild(ta);
                ta.select();
                document.execCommand('copy');
                document.body.removeChild(ta);
                finish();
            }
            return;
        }

        if (toggle) {
            e.preventDefault();
            e.stopPropagation();
            const wrapper = toggle.closest('[data-share-card]');
            const isOpen = wrapper.classList.contains('is-open');
            document.querySelectorAll('[data-share-card].is-open').forEach(function (el) {
                el.classList.remove('is-open');
                el.querySelector('[data-share-toggle]').setAttribute('aria-expanded', 'false');
            });
            if (!isOpen) {
                wrapper.classList.add('is-open');
                toggle.setAttribute('aria-expanded', 'true');
            }
            return;
        }

        if (!e.target.closest('[data-share-menu]')) {
            document.querySelectorAll('[data-share-card].is-open').forEach(function (el) {
                el.classList.remove('is-open');
                el.querySelector('[data-share-toggle]').setAttribute('aria-expanded', 'false');
            });
        }
    });

    document.querySelectorAll('[data-share-menu]').forEach(function (menu) {
        menu.addEventListener('click', function (e) {
            if (e.target.closest('a')) {
                e.stopPropagation();
            }
        });
    });
})();
</script>
@endsection
