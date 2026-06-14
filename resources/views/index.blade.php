@extends('layouts.app')

@section('title', __('homepage.title'))
@section('meta_description', __('homepage.meta_description'))

@push('preload_images')
<link rel="preload" as="image" href="{{ asset('images/hero-bg-mobile.webp') }}" type="image/webp" fetchpriority="high" media="(max-width: 768px)">
<link rel="preload" as="image" href="{{ asset('images/hero-bg.webp') }}" type="image/webp" fetchpriority="high" media="(min-width: 769px)">
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

  {{-- STATS STRIP --}}
  <div class="hp-stats">
    <div class="hp-stat-item">
      <div class="hp-stat-number">10 000+</div>
      <div class="hp-stat-label">{{ __('homepage.stats.learners') }}</div>
    </div>
    <div class="hp-stat-item">
      <div class="hp-stat-number">{{ $categories->count() }}+</div>
      <div class="hp-stat-label">{{ __('homepage.stats.technologies') }}</div>
    </div>
    <div class="hp-stat-item">
      <div class="hp-stat-number">{{ $featuredDocuments->count() + 50 }}+</div>
      <div class="hp-stat-label">{{ __('homepage.stats.resources') }}</div>
    </div>
    <div class="hp-stat-item">
      <div class="hp-stat-number">100%</div>
      <div class="hp-stat-label">{{ __('homepage.stats.free') }}</div>
    </div>
  </div>


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
        <a href="{{ route('documents.index') }}" class="hp-section-action">
          {{ __('homepage.docs.view_all') }}
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
      </div>

      <div class="hp-docs-grid">
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

            <div class="hp-doc-price">
              @if($document->price && $document->price > 0)
                <span class="hp-doc-price-current">
                  {{ $document->discount_price ? number_format($document->discount_price, 0, ',', ' ') : number_format($document->price, 0, ',', ' ') }} FCFA
                </span>
                @if($document->discount_price && $document->discount_price < $document->price)
                  <span class="hp-doc-price-old">{{ number_format($document->price, 0, ',', ' ') }}</span>
                @endif
              @else
                <span class="hp-doc-price-current">{{ __('homepage.docs.free') }}</span>
              @endif
            </div>
          </a>

          <div class="hp-doc-body">
            <a href="{{ route('documents.show', $document->slug) }}" class="hp-doc-title">
              {{ $document->title }}
            </a>
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
              <img src="{{ $category->image }}" alt="{{ $category->name }}" loading="lazy" width="44" height="44">
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
  <section class="hp-section">
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
        <a href="{{ route('epreuves.show', $epreuve->slug) }}" class="hp-epreuve-card{{ $epreuve->hasCorrige() ? ' hp-epreuve-card--corrige' : '' }}">
          <div class="hp-epreuve-top">
            <span class="hp-epreuve-icon"><i class="fas fa-file-pdf"></i></span>
            <span class="hp-epreuve-badge">{{ $epreuve->exam_label ?? $epreuve->level_label ?? __('homepage.epreuves.eyebrow') }}</span>
          </div>
          @if($epreuve->hasCorrige())
          <span class="hp-epreuve-corrige"><i class="fas fa-check-circle"></i> Corrigé inclus</span>
          @endif
          <div class="hp-epreuve-title">{{ \Illuminate\Support\Str::limit($epreuve->title, 70) }}</div>
          <div class="hp-epreuve-meta">
            @if($epreuve->matiere)<span><i class="fas fa-book"></i> {{ $epreuve->matiere->name }}</span>@endif
            @if($epreuve->year)<span><i class="fas fa-calendar"></i> {{ $epreuve->year }}</span>@endif
            <span><i class="fas fa-download"></i> {{ number_format($epreuve->downloads_count, 0, ' ', ' ') }} {{ __('homepage.epreuves.downloads') }}</span>
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
          <a href="{{ route('emplois.article', $article->slug) }}" class="hp-article-card">
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
          </a>
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
        <a href="{{ route('formations.all') }}" class="hp-section-action">
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
              <a href="{{ route('monetization.index') }}" class="hp-course-cta">{{ __('homepage.courses.enroll') }}</a>
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
            <img src="{{ asset('storage/' . $ad->image) }}" alt="{{ $ad->name }}" loading="lazy" decoding="async">
          @else
            <img src="{{ $ad->image }}" alt="{{ $ad->name }}" loading="lazy" decoding="async">
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
        <a href="{{ route('emplois.article', $article->slug) }}" class="hp-article-card">
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
        </a>
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
        <a href="{{ route('emplois.article', $article->slug) }}" class="hp-article-card">
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
        </a>
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
            <img src="{{ asset('storage/' . $ad->image) }}" alt="{{ $ad->name }}" loading="lazy" decoding="async">
          @else
            <img src="{{ $ad->image }}" alt="{{ $ad->name }}" loading="lazy" decoding="async">
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
        <a href="{{ route('documents.index') }}" class="hp-btn-secondary" style="border-color:oklch(65% 0.20 200 / 30%);color:oklch(65% 0.20 200);">
          {{ __('homepage.cta.btn_dl') }}
        </a>
      </div>
    </div>
  </div>

@endsection
