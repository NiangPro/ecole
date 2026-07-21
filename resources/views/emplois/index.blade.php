@extends('layouts.app')

@push('page_css')
@vite('resources/css/features/emplois.css')
@endpush

@section('title', 'Emplois & Opportunités | NiangProgrammeur')
@section('meta_description', 'Offre emploi Sénégal — trouvez les meilleures offres d\'emploi, bourses d\'études, concours et opportunités de carrière publiées chaque jour.')
@section('meta_keywords', 'emploi Sénégal, offres d\'emploi, bourses d\'études, opportunités carrière, recrutement Sénégal, emploi Dakar')
@section('canonical', route('emplois'))
@push('meta')
  <meta property="og:type" content="website">
  <meta property="og:url" content="{{ route('emplois') }}">
  <meta property="og:title" content="Emplois & Opportunités | NiangProgrammeur">
  <meta property="og:description" content="Découvrez les meilleures offres d'emploi, bourses d'études, opportunités de carrière et candidatures spontanées au Sénégal.">
@endpush

@section('content')
<div class="em-page">

  {{-- ── HERO ───────────────────────────────────────────────── --}}
  <div class="em-hero">
    <div class="em-hero__eyebrow">
      <span class="em-hero__dot"></span>
      Mis à jour quotidiennement
    </div>
    <h1 class="em-hero__title">
      Offre emploi<br><span>Sénégal</span>
    </h1>
    <p class="em-hero__sub">
      Offres d'emploi, bourses d'études, concours et opportunités de carrière au Sénégal et à l'international — toutes les infos au même endroit.
    </p>
    <div class="em-hero__actions">
      <a href="{{ route('emplois.offres') }}" class="em-btn--primary">
        <i class="fas fa-briefcase"></i>
        Explorer les offres
      </a>
      <a href="{{ route('emplois.bourses') }}" class="em-btn--secondary">
        <i class="fas fa-graduation-cap"></i>
        Voir les bourses
      </a>
    </div>
  </div>

  {{-- ── STATS ──────────────────────────────────────────────── --}}
  <div class="em-stats">
    <div class="em-stats__inner">
      <div class="em-stat">
        <div class="em-stat__num">{{ \App\Models\JobArticle::where('status', 'published')->count() }}+</div>
        <div class="em-stat__lbl">Articles publiés</div>
      </div>
      <div class="em-stat">
        <div class="em-stat__num">{{ \App\Models\Category::where('is_active', true)->count() }}</div>
        <div class="em-stat__lbl">Catégories actives</div>
      </div>
      <div class="em-stat">
        <div class="em-stat__num">{{ \App\Models\JobArticle::getTotalInflatedViews() }}+</div>
        <div class="em-stat__lbl">Vues totales</div>
      </div>
      <div class="em-stat">
        <div class="em-stat__num">{{ \App\Models\JobArticle::where('status', 'published')->whereDate('published_at', '>=', now()->subDays(30))->count() }}</div>
        <div class="em-stat__lbl">Nouveaux (30 jours)</div>
      </div>
    </div>
  </div>

  {{-- ── MAIN ───────────────────────────────────────────────── --}}
  <div class="em-main">
    <div class="em-container">

      {{-- Categories --}}
      <div class="em-section-head">
        <h2 class="em-section-title">
          <i class="fas fa-th-large"></i>
          Toutes les catégories
        </h2>
        <a href="{{ route('emplois.offres') }}" class="em-see-all">
          Voir tout <i class="fas fa-arrow-right" style="font-size:.65rem"></i>
        </a>
      </div>

      <div class="em-grid">
        @forelse($categories as $category)
        <a href="{{ route('emplois.offres') }}?category={{ $category->slug }}" class="em-cat-card">

          {{-- Image --}}
          <div class="em-cat-card__img-wrap">
            @if($category->image)
              <img src="{{ $category->image_type === 'internal' ? \Illuminate\Support\Facades\Storage::url($category->image) : $category->image }}"
                   alt="{{ $category->name }}"
                   width="400" height="180"
                   loading="lazy" decoding="async"
                   class="em-cat-card__img">
              <div class="em-cat-card__img-overlay"></div>
            @else
              <div class="em-cat-card__no-img">
                <i class="{{ $category->icon ?? 'fas fa-folder' }}"></i>
              </div>
            @endif
          </div>

          {{-- Body --}}
          <div class="em-cat-card__body">
            <div class="em-cat-card__icon">
              <i class="{{ $category->icon ?? 'fas fa-folder' }}"></i>
            </div>
            <div class="em-cat-card__name">{{ $category->name }}</div>
            @if($category->description)
            <div class="em-cat-card__desc">{{ $category->description }}</div>
            @endif
            <span class="em-cat-card__cta">
              Voir les articles
              <span class="em-cat-card__count">{{ $category->published_articles_count ?? $category->articles_count ?? 0 }}</span>
            </span>
          </div>

        </a>
        @empty
        <div class="em-empty">
          <div class="em-empty__icon"><i class="fas fa-folder-open"></i></div>
          <div class="em-empty__title">Aucune catégorie disponible</div>
          <div class="em-empty__sub">Les catégories seront bientôt disponibles.</div>
        </div>
        @endforelse
      </div>

      {{-- Recent articles --}}
      @if($recentArticles && $recentArticles->count() > 0)
      <div class="em-section-head">
        <h2 class="em-section-title">
          <i class="fas fa-newspaper"></i>
          Articles récents
        </h2>
        <a href="{{ route('emplois.all-articles') }}" class="em-see-all">
          Tous les articles <i class="fas fa-arrow-right" style="font-size:.65rem"></i>
        </a>
      </div>

      <div class="em-articles-grid">
        @foreach($recentArticles as $article)
        @php $recentArticleUrl = route('emplois.article', $article->slug); @endphp
        <div class="em-art-card">
          <a href="{{ $recentArticleUrl }}" class="em-art-card__link" aria-label="{{ $article->title }}"></a>

          <div class="em-art-card__share" data-share-card>
            <button type="button" class="em-art-card__share-toggle" data-share-toggle aria-haspopup="true" aria-expanded="false" aria-label="Partager cet article">
              <i class="fas fa-share-alt"></i>
            </button>
            <div class="em-art-card__share-menu" data-share-menu>
              <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($recentArticleUrl) }}" target="_blank" rel="noopener noreferrer">
                <i class="fab fa-facebook-f"></i> Facebook
              </a>
              <a href="https://twitter.com/intent/tweet?url={{ urlencode($recentArticleUrl) }}&text={{ urlencode($article->title) }}" target="_blank" rel="noopener noreferrer">
                <i class="fab fa-twitter"></i> X (Twitter)
              </a>
              <a href="https://wa.me/?text={{ urlencode($article->title . ' ' . $recentArticleUrl) }}" target="_blank" rel="noopener noreferrer">
                <i class="fab fa-whatsapp"></i> WhatsApp
              </a>
              <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode($recentArticleUrl) }}&title={{ urlencode($article->title) }}" target="_blank" rel="noopener noreferrer">
                <i class="fab fa-linkedin-in"></i> LinkedIn
              </a>
              <button type="button" data-copy-link="{{ $recentArticleUrl }}">
                <i class="fas fa-link"></i> Copier le lien
              </button>
            </div>
          </div>

          <div class="em-art-card__img-wrap">
            @if($article->cover_image)
              <img src="{{ $article->cover_type === 'internal' ? \Illuminate\Support\Facades\Storage::url($article->cover_image) : $article->cover_image }}"
                   alt="{{ $article->title }}"
                   width="600" height="400"
                   loading="lazy"
                   decoding="async"
                   class="em-art-card__img"
                   onerror="this.parentElement.innerHTML='<div class=\'em-art-card__no-img\'><i class=\'fas fa-newspaper\'></i></div>'">
              <div class="em-art-card__overlay">
                @if($article->category)
                <span class="em-art-card__cat-badge">
                  <i class="fas fa-tag"></i>
                  {{ $article->category->name }}
                </span>
                @endif
              </div>
            @else
              <div class="em-art-card__no-img"><i class="fas fa-newspaper"></i></div>
              @if($article->category)
              <div class="em-art-card__overlay">
                <span class="em-art-card__cat-badge">
                  <i class="fas fa-tag"></i>
                  {{ $article->category->name }}
                </span>
              </div>
              @endif
            @endif
          </div>

          <div class="em-art-card__body">
            <div class="em-art-card__title">{{ $article->title }}</div>
            @if($article->excerpt || $article->content)
            <div class="em-art-card__excerpt">{{ Str::limit($article->excerpt ?? strip_tags($article->content), 110) }}</div>
            @endif
            <div class="em-art-card__meta">
              @if($article->published_at)
              <span><i class="fas fa-calendar"></i> {{ $article->published_at->format('d/m/Y') }}</span>
              @endif
              <span><i class="fas fa-eye"></i> {{ $article->featured_display_views ?? $article->views }}</span>
            </div>
          </div>

        </div>
        @endforeach
      </div>
      @endif

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
