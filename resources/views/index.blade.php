@extends('layouts.app')

@section('title', 'NiangProgrammeur — Formation Gratuite en Développement Web')
@section('meta_description', 'Plateforme de formation gratuite en développement web. Apprenez HTML5, CSS3, JavaScript, PHP, Laravel, Bootstrap, Git, WordPress et Intelligence Artificielle.')

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
        Plateforme #1 de formation web en Afrique
      </span>

      <h1 class="hp-hero__title">
        Apprends à coder.<br>
        <span class="accent">Lance ta carrière.</span>
      </h1>

      <p class="hp-hero__subtitle">
        Formations gratuites, documents téléchargeables, exercices pratiques et offres d'emploi tech — tout en un seul endroit.
      </p>

      <div class="hp-hero__actions">
        <a href="{{ route('formations.all') }}" class="hp-btn-primary">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 3l14 9-14 9V3z"/></svg>
          Commencer gratuitement
        </a>
        <a href="{{ route('documents.index') }}" class="hp-btn-secondary">
          Parcourir les ressources
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
      </div>
    </div>
  </section>

  {{-- STATS STRIP --}}
  <div class="hp-stats">
    <div class="hp-stat-item">
      <div class="hp-stat-number">10 000+</div>
      <div class="hp-stat-label">Apprenants actifs</div>
    </div>
    <div class="hp-stat-item">
      <div class="hp-stat-number">{{ $categories->count() }}+</div>
      <div class="hp-stat-label">Technologies enseignées</div>
    </div>
    <div class="hp-stat-item">
      <div class="hp-stat-number">{{ $featuredDocuments->count() + 50 }}+</div>
      <div class="hp-stat-label">Ressources disponibles</div>
    </div>
    <div class="hp-stat-item">
      <div class="hp-stat-number">100%</div>
      <div class="hp-stat-label">Formations gratuites</div>
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
          <span class="hp-section-eyebrow">Ressources</span>
          <h2 class="hp-section-title">Documents téléchargeables</h2>
          <p class="hp-section-subtitle">Fiches de révision, templates et guides pratiques.</p>
        </div>
        <a href="{{ route('documents.index') }}" class="hp-section-action">
          Voir tout
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
              <span class="hp-doc-badge">Vedette</span>
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
                <span class="hp-doc-price-current">Gratuit</span>
              @endif
            </div>
          </a>

          <div class="hp-doc-body">
            <a href="{{ route('documents.show', $document->slug) }}" class="hp-doc-title">
              {{ $document->title }}
            </a>
            <div class="hp-doc-footer">
              <span class="hp-doc-category">{{ $document->category?->name ?? 'Général' }}</span>
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
          <span class="hp-section-eyebrow">Catégories</span>
          <h2 class="hp-section-title">Parcourir par catégorie</h2>
          <p class="hp-section-subtitle">Explorez nos articles classés par domaine et trouvez ce qui vous intéresse.</p>
        </div>
      </div>

      <div class="hp-tech-grid">
        @php
          $techIcons = [
            'html'        => '🌐', 'css'         => '🎨', 'javascript'  => '⚡',
            'php'         => '🐘', 'laravel'     => '🔴', 'python'      => '🐍',
            'react'       => '⚛️',  'vue'         => '💚', 'nodejs'      => '🟢',
            'mysql'       => '🗄️',  'git'         => '🌿', 'wordpress'   => '🔵',
            'bootstrap'   => '🅱️',  'typescript'  => '🔷', 'ia'          => '🤖',
            'default'     => '💻',
          ];
        @endphp

        @foreach($categories as $category)
        @php
          $slug = strtolower($category->slug ?? '');
          $icon = '💻';
          foreach($techIcons as $key => $emoji) {
            if(str_contains($slug, $key)) { $icon = $emoji; break; }
          }
        @endphp
        <a href="{{ route('emplois.category', $category->slug) }}" class="hp-tech-card">
          <div class="hp-tech-icon">{{ $icon }}</div>
          <div>
            <div class="hp-tech-name">{{ $category->name }}</div>
            <div class="hp-tech-count">{{ $category->published_articles_count ?? 0 }} article{{ ($category->published_articles_count ?? 0) > 1 ? 's' : '' }}</div>
          </div>
          <span class="hp-tech-arrow">→</span>
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
          <span class="hp-section-eyebrow">Articles</span>
          <h2 class="hp-section-title">À la une</h2>
          <p class="hp-section-subtitle">Les dernières actualités tech, tutoriels et offres d'emploi.</p>
        </div>
        <a href="{{ route('emplois') }}" class="hp-section-action">
          Tous les articles
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
              <span class="hp-article-category">{{ $article->category?->name ?? 'Général' }}</span>
              <h3 class="hp-article-title">{{ $article->title }}</h3>
              <div class="hp-article-meta">
                <span>{{ $article->published_at ? \Carbon\Carbon::parse($article->published_at)->diffForHumans() : 'Récemment' }}</span>
                <span>·</span>
                <span>{{ $article->featured_display_views }} vues</span>
              </div>
            </div>
          </a>
          @endforeach
        </div>

        {{-- SIDEBAR --}}
        @if($careerAdviceArticles->isNotEmpty())
        <aside class="hp-sidebar">
          <div class="hp-sidebar-title">Conseils & Carrière</div>
          @foreach($careerAdviceArticles->take(6) as $i => $article)
          <a href="{{ route('emplois.article', $article->slug) }}" class="hp-sidebar-item">
            <span class="hp-sidebar-num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
            <div>
              <div class="hp-sidebar-item-title">{{ $article->title }}</div>
              <div class="hp-sidebar-item-meta">
                {{ $article->published_at ? \Carbon\Carbon::parse($article->published_at)->diffForHumans() : 'Récemment' }}
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
          <span class="hp-section-eyebrow">Formations premium</span>
          <h2 class="hp-section-title">Cours certifiants</h2>
          <p class="hp-section-subtitle">Allez plus loin avec nos formations structurées et accompagnées.</p>
        </div>
        <a href="{{ route('formations.all') }}" class="hp-section-action">
          Voir tous les cours
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
            <span class="hp-course-level-badge">Premium</span>
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
                <i>👥</i> {{ number_format($course->students_count) }} élèves
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
              <a href="{{ route('monetization.index') }}" class="hp-course-cta">S'inscrire</a>
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
        <span class="hp-native-ad__label">Sponsorisé</span>

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
              En savoir plus
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
        <span class="hp-section-eyebrow">Sponsorisé</span>
        <h2 class="hp-section-title">Contenu mis en avant</h2>
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
            <span class="hp-article-category">{{ $article->category?->name ?? 'Sponsorisé' }}</span>
            <h3 class="hp-article-title">{{ $article->title }}</h3>
            @if($article->excerpt)
              <p style="font-size:0.8125rem;color:var(--text-muted);line-height:1.5;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                {{ $article->excerpt }}
              </p>
            @endif
            <div class="hp-article-meta">
              <span>{{ $article->published_at ? \Carbon\Carbon::parse($article->published_at)->diffForHumans() : 'Récemment' }}</span>
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
          <span class="hp-section-eyebrow">Actualité</span>
          <h2 class="hp-section-title">Derniers articles</h2>
          <p class="hp-section-subtitle">Restez à jour avec l'écosystème tech.</p>
        </div>
        <a href="{{ route('emplois') }}" class="hp-section-action">
          Voir tout
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
            <span class="hp-article-category">{{ $article->category?->name ?? 'Général' }}</span>
            <h3 class="hp-article-title">{{ $article->title }}</h3>
            <div class="hp-article-meta">
              <span>{{ $article->published_at ? \Carbon\Carbon::parse($article->published_at)->diffForHumans() : 'Récemment' }}</span>
              <span>·</span>
              <span>{{ $article->featured_display_views }} vues</span>
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
        <span class="hp-section-eyebrow">Partenaires</span>
        <h2 class="hp-section-title">Nos partenaires</h2>
      </div>
      @foreach($sidebarAds as $ad)
      <div class="hp-native-ad" style="margin-block-end:1.5rem;">
        <span class="hp-native-ad__label">Partenaire</span>
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
              Découvrir
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
      <h2 class="hp-cta-banner__title">Prêt à lancer ta carrière dans le dev ?</h2>
      <p class="hp-cta-banner__subtitle">
        Rejoins des milliers d'apprenants qui ont transformé leur vie grâce au code. C'est gratuit, c'est maintenant.
      </p>
      <div style="display:flex;align-items:center;justify-content:center;gap:1rem;flex-wrap:wrap;">
        <a href="{{ route('formations.all') }}" class="hp-btn-primary">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
          Démarrer maintenant
        </a>
        <a href="{{ route('documents.index') }}" class="hp-btn-secondary" style="border-color:oklch(65% 0.20 200 / 30%);color:oklch(65% 0.20 200);">
          Télécharger des ressources
        </a>
      </div>
    </div>
  </div>

@endsection
