@extends('layouts.app')

@push('page_css')
@vite('resources/css/features/emplois.css')
@endpush

@section('title', ($category ? $category->name : 'Offres d\'Emploi') . ' | NiangProgrammeur')
@section('meta_description', ($category && $category->slug === 'bourses-etudes')
    ? 'Bourse d\'études Sénégal — toutes les bourses nationales et internationales disponibles : candidature, conditions, délais et montants.'
    : ($category ? ($category->description ?? 'Découvrez les meilleures ' . $category->name . ' au Sénégal.') : 'Offre emploi Sénégal — consultez les meilleures offres de recrutement publiées chaque jour.'))
@section('meta_keywords', $category ? ($category->name . ', emploi Sénégal, recrutement, offres d\'emploi') : 'emploi Sénégal, offres d\'emploi, recrutement, opportunités carrière')
@section('canonical', route('emplois.offres', ['category' => $category->slug ?? null]))
@push('meta')
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('emplois.offres', ['category' => $category->slug ?? null]) }}">
    <meta property="og:title" content="{{ ($category ? $category->name : 'Offres d\'Emploi') . ' | NiangProgrammeur' }}">
    <meta property="og:description" content="{{ $category ? ($category->description ?? 'Découvrez les meilleures offres d\'emploi dans la catégorie ' . $category->name . ' au Sénégal.') : 'Consultez les meilleures offres d\'emploi publiées au Sénégal.' }}">
    @if($category && $category->image)
    <meta property="og:image" content="{{ $category->image_type === 'internal' ? \Illuminate\Support\Facades\Storage::url($category->image) : $category->image }}">
    @endif
@endpush

@section('styles')
<style>
    * { box-sizing: border-box; }

    body:not(.dark-mode) { background: #f6f8fb !important; }
    body.dark-mode { background: #06070d !important; }

    /* ── Hero ────────────────────────────────────────────────────────── */
    .jobs-hero {
        position: relative;
        overflow: hidden;
        padding: clamp(110px, 14vw, 150px) 20px clamp(70px, 8vw, 90px);
        background: radial-gradient(circle at 15% 20%, rgba(6, 182, 212, 0.35), transparent 55%),
                    radial-gradient(circle at 85% 80%, rgba(139, 92, 246, 0.30), transparent 55%),
                    linear-gradient(135deg, #0b1120 0%, #111827 55%, #0b1120 100%);
        text-align: center;
    }

    body:not(.dark-mode) .jobs-hero {
        background: radial-gradient(circle at 15% 20%, rgba(6, 182, 212, 0.18), transparent 55%),
                    radial-gradient(circle at 85% 80%, rgba(139, 92, 246, 0.16), transparent 55%),
                    linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #0f172a 100%);
    }

    .jobs-hero::after {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255,255,255,0.08) 1px, transparent 1px);
        background-size: 26px 26px;
        opacity: 0.5;
        pointer-events: none;
    }

    .jobs-hero-inner {
        position: relative;
        z-index: 2;
        max-width: 900px;
        margin: 0 auto;
    }

    .jobs-hero-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 18px;
        border-radius: 999px;
        background: rgba(6, 182, 212, 0.15);
        border: 1px solid rgba(6, 182, 212, 0.4);
        color: #22d3ee;
        font-weight: 700;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-bottom: 22px;
    }

    .jobs-hero-title {
        font-size: clamp(2.1rem, 5vw, 3.6rem);
        font-weight: 900;
        color: #fff;
        line-height: 1.15;
        margin-bottom: 18px;
        text-shadow: 0 4px 24px rgba(0,0,0,0.35);
    }

    .jobs-hero-desc {
        font-size: clamp(1rem, 1.6vw, 1.15rem);
        color: rgba(255,255,255,0.82);
        line-height: 1.65;
        max-width: 720px;
        margin: 0 auto 30px;
    }

    .jobs-hero-stat {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 26px;
        border-radius: 16px;
        background: rgba(255,255,255,0.08);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255,255,255,0.15);
        color: #fff;
        font-weight: 700;
    }

    .jobs-hero-stat i { color: #22d3ee; }

    /* ── Container / Grid ────────────────────────────────────────────── */
    .jobs-container {
        max-width: 1560px;
        margin: -46px auto 0;
        padding: 0 20px 90px;
        position: relative;
        z-index: 3;
    }

    .jobs-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 26px;
        margin-top: 20px;
    }

    @media (max-width: 1300px) { .jobs-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 980px)  { .jobs-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 620px)  { .jobs-grid { grid-template-columns: 1fr; gap: 20px; } }

    /* ── Card ─────────────────────────────────────────────────────────── */
    .job-card {
        position: relative;
        display: flex;
        flex-direction: column;
        border-radius: 22px;
        background: rgba(15, 23, 42, 0.75);
        border: 1px solid rgba(6, 182, 212, 0.18);
        box-shadow: 0 14px 40px rgba(0,0,0,0.25);
        backdrop-filter: blur(18px);
        transition: transform 0.45s cubic-bezier(0.175,0.885,0.32,1.275), box-shadow 0.45s ease, border-color 0.45s ease;
        overflow: visible;
    }

    body:not(.dark-mode) .job-card {
        background: rgba(255,255,255,0.96);
        border-color: rgba(6, 182, 212, 0.15);
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
    }

    .job-card:hover {
        transform: translateY(-10px);
        border-color: rgba(6, 182, 212, 0.55);
        box-shadow: 0 26px 60px rgba(6, 182, 212, 0.3);
    }

    .job-card-link {
        position: absolute;
        inset: 0;
        z-index: 1;
        border-radius: 22px;
    }

    .job-card-media {
        position: relative;
        height: 185px;
        border-radius: 22px 22px 0 0;
        overflow: hidden;
        background: linear-gradient(135deg, rgba(6,182,212,0.25), rgba(139,92,246,0.25));
    }

    .job-card-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.7s cubic-bezier(0.175,0.885,0.32,1.275);
        pointer-events: none;
    }

    .job-card:hover .job-card-media img { transform: scale(1.1); }

    .job-card-media-fallback {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: none;
    }

    .job-card-media-fallback i {
        font-size: 3rem;
        color: rgba(255,255,255,0.45);
    }

    .job-card-media::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(2,6,23,0.75) 0%, transparent 55%);
        z-index: 1;
        pointer-events: none;
    }

    .job-card-category {
        position: absolute;
        left: 14px;
        bottom: 12px;
        z-index: 2;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 13px;
        border-radius: 999px;
        background: rgba(6, 182, 212, 0.9);
        color: #04141a;
        font-size: 0.72rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        max-width: calc(100% - 28px);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        pointer-events: none;
    }

    /* ── Share button + popover ──────────────────────────────────────── */
    .job-card-share {
        position: absolute;
        top: 12px;
        right: 12px;
        z-index: 6;
    }

    .job-card-share-toggle {
        width: 38px;
        height: 38px;
        border: none;
        outline: none;
        box-shadow: none;
        -webkit-appearance: none;
        appearance: none;
        padding: 0;
        background: transparent;
        color: #fff;
        text-shadow: 0 2px 6px rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1.1rem;
    }
    .job-card-share-toggle:focus,
    .job-card-share-toggle:focus-visible {
        outline: none;
        box-shadow: none;
    }

    .job-card-share-toggle:hover,
    .job-card-share.is-open .job-card-share-toggle {
        color: #06b6d4;
        transform: scale(1.15);
    }

    .job-card-share-menu {
        position: absolute;
        top: 46px;
        right: 0;
        display: flex;
        flex-direction: column;
        gap: 6px;
        padding: 10px;
        border-radius: 16px;
        background: rgba(15, 23, 42, 0.97);
        border: 1px solid rgba(6, 182, 212, 0.3);
        box-shadow: 0 18px 45px rgba(0,0,0,0.45);
        opacity: 0;
        visibility: hidden;
        transform: translateY(-8px) scale(0.95);
        transition: all 0.22s ease;
        min-width: 168px;
    }

    body:not(.dark-mode) .job-card-share-menu {
        background: #ffffff;
        border-color: rgba(6, 182, 212, 0.2);
        box-shadow: 0 18px 45px rgba(15, 23, 42, 0.18);
    }

    .job-card-share.is-open .job-card-share-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0) scale(1);
    }

    .job-card-share-menu a,
    .job-card-share-menu button {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 10px;
        border-radius: 10px;
        border: none;
        background: transparent;
        color: #cbd5e1;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        text-align: left;
        width: 100%;
    }

    body:not(.dark-mode) .job-card-share-menu a,
    body:not(.dark-mode) .job-card-share-menu button {
        color: #334155;
    }

    .job-card-share-menu a:hover,
    .job-card-share-menu button:hover {
        background: rgba(6, 182, 212, 0.15);
        color: #06b6d4;
    }

    .job-card-share-menu i {
        width: 18px;
        text-align: center;
    }
    .job-card-share-menu i.fa-facebook-f { color: #1877f2; }
    .job-card-share-menu i.fa-twitter { color: #cbd5e1; }
    .job-card-share-menu i.fa-whatsapp { color: #25d366; }
    .job-card-share-menu i.fa-linkedin-in { color: #0a66c2; }
    .job-card-share-menu i.fa-link { color: #06b6d4; }

    /* ── Body ─────────────────────────────────────────────────────────── */
    .job-card-body {
        position: relative;
        z-index: 2;
        padding: 20px 20px 18px;
        display: flex;
        flex-direction: column;
        flex: 1;
        pointer-events: none; /* clicks fall through to stretched link, except children we re-enable */
    }

    .job-card-title {
        font-family: 'Poppins', sans-serif;
        font-size: 1.05rem;
        font-weight: 800;
        color: #fff;
        line-height: 1.4;
        margin-bottom: 10px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: color 0.3s ease;
    }

    body:not(.dark-mode) .job-card-title { color: #0f172a; }
    .job-card:hover .job-card-title { color: #06b6d4; }

    .job-card-excerpt {
        font-size: 0.85rem;
        color: rgba(255,255,255,0.62);
        line-height: 1.6;
        margin-bottom: 16px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex: 1;
    }

    body:not(.dark-mode) .job-card-excerpt { color: #64748b; }

    .job-card-meta {
        display: flex;
        align-items: center;
        gap: 14px;
        padding-top: 14px;
        margin-top: auto;
        border-top: 1px solid rgba(6, 182, 212, 0.15);
        color: rgba(255,255,255,0.55);
        font-size: 0.78rem;
        flex-wrap: wrap;
    }

    body:not(.dark-mode) .job-card-meta {
        border-top-color: rgba(6, 182, 212, 0.12);
        color: #64748b;
    }

    .job-card-meta span { display: inline-flex; align-items: center; gap: 6px; }
    .job-card-meta i { color: #06b6d4; }

    .job-card-cta {
        margin-top: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        align-self: flex-start;
        padding: 10px 18px;
        border-radius: 12px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: #04141a;
        font-weight: 700;
        font-size: 0.82rem;
        transition: all 0.35s cubic-bezier(0.175,0.885,0.32,1.275);
        pointer-events: none;
    }

    .job-card:hover .job-card-cta {
        gap: 12px;
        box-shadow: 0 10px 26px rgba(6, 182, 212, 0.4);
    }

    /* re-enable pointer events for interactive children living above the stretched link */
    .job-card-share { pointer-events: auto; }

    /* ── Empty state ──────────────────────────────────────────────────── */
    .jobs-empty {
        text-align: center;
        padding: 110px 20px;
        background: rgba(15, 23, 42, 0.5);
        border-radius: 28px;
        border: 2px dashed rgba(6, 182, 212, 0.3);
        backdrop-filter: blur(20px);
        margin-top: 20px;
    }

    body:not(.dark-mode) .jobs-empty {
        background: rgba(255,255,255,0.9);
        border-color: rgba(6, 182, 212, 0.25);
    }

    .jobs-empty i {
        font-size: 4.5rem;
        color: rgba(6, 182, 212, 0.4);
        margin-bottom: 24px;
        display: block;
    }

    .jobs-empty h3 {
        font-family: 'Poppins', sans-serif;
        font-size: 1.8rem;
        font-weight: 900;
        margin-bottom: 10px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .jobs-empty p {
        color: rgba(255,255,255,0.65);
        font-size: 1.05rem;
    }

    body:not(.dark-mode) .jobs-empty p { color: #64748b; }

    .jobs-pagination { margin-top: 60px; }
</style>
@endsection

@section('content')
@php
    $categoryImage = null;
    if ($category && $category->image) {
        $categoryImage = $category->image_type === 'internal'
            ? \Illuminate\Support\Facades\Storage::url($category->image)
            : $category->image;
    }
    $heroTitle = $category && $category->slug === 'bourses-etudes'
        ? "Bourse d'études Sénégal"
        : ($category ? $category->name : 'Offres d\'emploi au Sénégal');
@endphp

<!-- Hero Section -->
<section class="jobs-hero">
    <div class="jobs-hero-inner">
        <span class="jobs-hero-eyebrow"><i class="fas fa-bolt"></i> Mis à jour chaque jour</span>
        <h1 class="jobs-hero-title">
            @if($category && $category->slug === 'bourses-etudes')🎓 @else 💼 @endif {{ $heroTitle }}
        </h1>
        <p class="jobs-hero-desc">
            Découvrez les meilleures offres d'emploi publiées au Sénégal. Nous ne recrutons pas
            directement mais publions les offres de recrutement existantes pour vous aider à
            trouver votre opportunité idéale.
        </p>
        <span class="jobs-hero-stat">
            <i class="fas fa-briefcase"></i> {{ $articles->total() }} offre{{ $articles->total() > 1 ? 's' : '' }} disponible{{ $articles->total() > 1 ? 's' : '' }}
        </span>
    </div>
</section>

<!-- Jobs Container -->
<div class="jobs-container">
    @if($articles && $articles->count() > 0)
    <div class="jobs-grid">
        @foreach($articles as $article)
        @php
            $articleUrl = route('emplois.article', $article->slug);
            $shareTitle = urlencode($article->title);
        @endphp
        <article class="job-card">
            <a href="{{ $articleUrl }}" class="job-card-link" aria-label="{{ $article->title }}"></a>

            <div class="job-card-share" data-share-card>
                <button type="button" class="job-card-share-toggle" data-share-toggle aria-haspopup="true" aria-expanded="false" aria-label="Partager cette offre">
                    <i class="fas fa-share-alt"></i>
                </button>
                <div class="job-card-share-menu" data-share-menu>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($articleUrl) }}" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode($articleUrl) }}&text={{ $shareTitle }}" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-twitter"></i> X (Twitter)
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($article->title . ' ' . $articleUrl) }}" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode($articleUrl) }}&title={{ $shareTitle }}" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-linkedin-in"></i> LinkedIn
                    </a>
                    <button type="button" data-copy-link="{{ $articleUrl }}">
                        <i class="fas fa-link"></i> Copier le lien
                    </button>
                </div>
            </div>

            <div class="job-card-media">
                @if($article->cover_image)
                <img src="{{ $article->cover_type === 'internal' ? \Illuminate\Support\Facades\Storage::url($article->cover_image) : $article->cover_image }}"
                     alt="{{ $article->title }} - {{ $article->category->name ?? '' }}"
                     width="400" height="185"
                     loading="lazy"
                     decoding="async"
                     onerror="this.style.display='none'">
                @else
                <div class="job-card-media-fallback">
                    <i class="fas fa-image"></i>
                </div>
                @endif

                @if($article->category)
                <span class="job-card-category">
                    <i class="{{ $article->category->icon ?? 'fas fa-folder' }}"></i> {{ $article->category->name }}
                </span>
                @endif
            </div>

            <div class="job-card-body">
                <h3 class="job-card-title">{{ $article->title }}</h3>
                @if($article->excerpt)
                <p class="job-card-excerpt">{{ $article->excerpt }}</p>
                @endif
                <div class="job-card-meta">
                    <span><i class="fas fa-calendar"></i> {{ $article->published_at ? $article->published_at->format('d/m/Y') : '' }}</span>
                    <span><i class="fas fa-eye"></i> {{ $article->featured_display_views }}</span>
                </div>
                <span class="job-card-cta">Voir l'offre <i class="fas fa-arrow-right"></i></span>
            </div>
        </article>
        @endforeach
    </div>

    @if($articles->hasPages())
    <div class="jobs-pagination">
        {{ $articles->links() }}
    </div>
    @endif

    @else
    <div class="jobs-empty">
        <i class="fas fa-newspaper"></i>
        <h3>Aucune offre disponible</h3>
        <p>Il n'y a pas encore d'offres publiées dans cette catégorie.</p>
    </div>
    @endif
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

    // Empêche le clic dans le menu de déclencher le lien de la card
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
