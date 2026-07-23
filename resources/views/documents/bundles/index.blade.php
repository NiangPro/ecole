@extends('layouts.app')

@section('title', 'Packs de Documents | NiangProgrammeur')
@section('meta_description', 'Découvrez nos packs de documents à prix réduit. Économisez en achetant plusieurs documents ensemble.')

@section('styles')
<style>
    * { box-sizing: border-box; }

    body:not(.dark-mode) { background: #f6f8fb !important; }
    body.dark-mode { background: #06070d !important; }

    /* ── Hero ────────────────────────────────────────────────────────── */
    .bnds-hero {
        position: relative;
        overflow: hidden;
        padding: clamp(110px, 14vw, 150px) 20px clamp(70px, 8vw, 90px);
        background: radial-gradient(circle at 15% 20%, rgba(6, 182, 212, 0.35), transparent 55%),
                    radial-gradient(circle at 85% 80%, rgba(20, 184, 166, 0.30), transparent 55%),
                    linear-gradient(135deg, #0b1120 0%, #111827 55%, #0b1120 100%);
        text-align: center;
    }
    body:not(.dark-mode) .bnds-hero {
        background: radial-gradient(circle at 15% 20%, rgba(6, 182, 212, 0.18), transparent 55%),
                    radial-gradient(circle at 85% 80%, rgba(20, 184, 166, 0.16), transparent 55%),
                    linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #0f172a 100%);
    }
    .bnds-hero::after {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255,255,255,0.08) 1px, transparent 1px);
        background-size: 26px 26px;
        opacity: 0.5;
        pointer-events: none;
    }
    .bnds-hero-inner { position: relative; z-index: 2; max-width: 900px; margin: 0 auto; }
    .bnds-hero-eyebrow {
        display: inline-flex; align-items: center; gap: 8px; padding: 8px 18px; border-radius: 999px;
        background: rgba(6, 182, 212, 0.15); border: 1px solid rgba(6, 182, 212, 0.4);
        color: #22d3ee; font-weight: 700; font-size: 0.8rem; letter-spacing: 0.5px; text-transform: uppercase;
        margin-bottom: 22px;
    }
    .bnds-hero-title { font-size: clamp(2.1rem, 5vw, 3.6rem); font-weight: 900; color: #fff; line-height: 1.15; margin-bottom: 18px; text-shadow: 0 4px 24px rgba(0,0,0,0.35); }
    .bnds-hero-desc { font-size: clamp(1rem, 1.6vw, 1.15rem); color: rgba(255,255,255,0.82); line-height: 1.65; max-width: 720px; margin: 0 auto 30px; }
    .bnds-hero-stat {
        display: inline-flex; align-items: center; gap: 10px; padding: 12px 26px; border-radius: 16px;
        background: rgba(255,255,255,0.08); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.15);
        color: #fff; font-weight: 700;
    }
    .bnds-hero-stat i { color: #22d3ee; }

    /* ── Container / Grid ────────────────────────────────────────────── */
    .bnds-container { max-width: 1560px; margin: -46px auto 0; padding: 0 20px 90px; position: relative; z-index: 3; }
    .bnds-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 26px; margin-top: 20px; }
    @media (max-width: 1300px) { .bnds-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 980px)  { .bnds-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 620px)  { .bnds-grid { grid-template-columns: 1fr; gap: 20px; } }

    /* ── Card ─────────────────────────────────────────────────────────── */
    .bnd-card {
        position: relative; display: flex; flex-direction: column; border-radius: 22px;
        background: rgba(15, 23, 42, 0.75); border: 1px solid rgba(6, 182, 212, 0.18);
        box-shadow: 0 14px 40px rgba(0,0,0,0.25); backdrop-filter: blur(18px);
        transition: transform 0.45s cubic-bezier(0.175,0.885,0.32,1.275), box-shadow 0.45s ease, border-color 0.45s ease;
        overflow: visible;
    }
    body:not(.dark-mode) .bnd-card { background: rgba(255,255,255,0.96); border-color: rgba(6, 182, 212, 0.15); box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08); }
    .bnd-card:hover { transform: translateY(-10px); border-color: rgba(6, 182, 212, 0.55); box-shadow: 0 26px 60px rgba(6, 182, 212, 0.3); }

    .bnd-card-link { position: absolute; inset: 0; z-index: 1; border-radius: 22px; }

    .bnd-card-media {
        position: relative; height: 170px; border-radius: 22px 22px 0 0; overflow: hidden;
        background: linear-gradient(135deg, rgba(6,182,212,0.25), rgba(20,184,166,0.25));
    }
    .bnd-card-media img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.7s cubic-bezier(0.175,0.885,0.32,1.275); pointer-events: none; }
    .bnd-card:hover .bnd-card-media img { transform: scale(1.1); }
    .bnd-card-media-fallback { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; pointer-events: none; }
    .bnd-card-media-fallback i { font-size: 3rem; color: rgba(255,255,255,0.45); }
    .bnd-card-media::before { content: ''; position: absolute; inset: 0; background: linear-gradient(to top, rgba(2,6,23,0.75) 0%, transparent 55%); z-index: 1; pointer-events: none; }

    .bnd-card-badge {
        position: absolute; left: 14px; bottom: 12px; z-index: 2;
        display: inline-flex; align-items: center; gap: 6px; padding: 6px 13px; border-radius: 999px;
        background: rgba(6, 182, 212, 0.9); color: #04141a; font-size: 0.72rem; font-weight: 800;
        text-transform: uppercase; letter-spacing: 0.4px;
    }
    .bnd-card-discount {
        position: absolute; right: 14px; bottom: 12px; z-index: 2;
        display: inline-flex; align-items: center; gap: 6px; padding: 6px 13px; border-radius: 999px;
        background: rgba(16,185,129,0.92); color: #04241a; font-size: 0.72rem; font-weight: 800;
    }

    /* ── Share button + popover ──────────────────────────────────────── */
    .bnd-card-share { position: absolute; top: 12px; right: 12px; z-index: 6; pointer-events: auto; }
    .bnd-card-share-toggle {
        width: 38px; height: 38px; border: none; outline: none; box-shadow: none; -webkit-appearance: none; appearance: none;
        padding: 0; background: transparent; color: #fff; text-shadow: 0 2px 6px rgba(0,0,0,0.5);
        display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease; font-size: 1.1rem;
    }
    .bnd-card-share-toggle:focus, .bnd-card-share-toggle:focus-visible { outline: none; box-shadow: none; }
    .bnd-card-share-toggle:hover, .bnd-card-share.is-open .bnd-card-share-toggle { color: #06b6d4; transform: scale(1.15); }

    .bnd-card-share-menu {
        position: absolute; top: 46px; right: 0; display: flex; flex-direction: column; gap: 6px; padding: 10px;
        border-radius: 16px; background: rgba(15, 23, 42, 0.97); border: 1px solid rgba(6, 182, 212, 0.3);
        box-shadow: 0 18px 45px rgba(0,0,0,0.45); opacity: 0; visibility: hidden;
        transform: translateY(-8px) scale(0.95); transition: all 0.22s ease; min-width: 168px;
    }
    body:not(.dark-mode) .bnd-card-share-menu { background: #ffffff; border-color: rgba(6, 182, 212, 0.2); box-shadow: 0 18px 45px rgba(15, 23, 42, 0.18); }
    .bnd-card-share.is-open .bnd-card-share-menu { opacity: 1; visibility: visible; transform: translateY(0) scale(1); }

    .bnd-card-share-menu a, .bnd-card-share-menu button {
        display: flex; align-items: center; gap: 10px; padding: 8px 10px; border-radius: 10px; border: none;
        background: transparent; color: #cbd5e1; font-size: 0.85rem; font-weight: 600; text-decoration: none;
        cursor: pointer; text-align: left; width: 100%;
    }
    body:not(.dark-mode) .bnd-card-share-menu a, body:not(.dark-mode) .bnd-card-share-menu button { color: #334155; }
    .bnd-card-share-menu a:hover, .bnd-card-share-menu button:hover { background: rgba(6, 182, 212, 0.15); color: #06b6d4; }
    .bnd-card-share-menu i { width: 18px; text-align: center; }
    .bnd-card-share-menu i.fa-facebook-f { color: #1877f2; }
    .bnd-card-share-menu i.fa-twitter { color: #cbd5e1; }
    .bnd-card-share-menu i.fa-whatsapp { color: #25d366; }
    .bnd-card-share-menu i.fa-linkedin-in { color: #0a66c2; }
    .bnd-card-share-menu i.fa-link { color: #06b6d4; }

    /* ── Body ─────────────────────────────────────────────────────────── */
    .bnd-card-body { position: relative; z-index: 2; padding: 20px 20px 18px; display: flex; flex-direction: column; flex: 1; pointer-events: none; }
    .bnd-card-title {
        font-family: 'Poppins', sans-serif; font-size: 1.05rem; font-weight: 800; color: #fff; line-height: 1.4;
        margin-bottom: 8px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
        transition: color 0.3s ease;
    }
    body:not(.dark-mode) .bnd-card-title { color: #0f172a; }
    .bnd-card:hover .bnd-card-title { color: #06b6d4; }

    .bnd-card-excerpt {
        font-size: 0.85rem; color: rgba(255,255,255,0.62); line-height: 1.6; margin-bottom: 14px;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; flex: 1;
    }
    body:not(.dark-mode) .bnd-card-excerpt { color: #64748b; }

    .bnd-card-price-row { display: flex; align-items: baseline; gap: 10px; margin-bottom: 10px; flex-wrap: wrap; }
    .bnd-card-price { font-size: 1.4rem; font-weight: 900; color: #06b6d4; }
    .bnd-card-price-old { font-size: 0.82rem; color: rgba(255,255,255,0.45); text-decoration: line-through; }
    body:not(.dark-mode) .bnd-card-price-old { color: #94a3b8; }

    .bnd-card-meta {
        display: flex; align-items: center; gap: 14px; padding-top: 14px; margin-top: auto;
        border-top: 1px solid rgba(6, 182, 212, 0.15); color: rgba(255,255,255,0.55); font-size: 0.78rem; flex-wrap: wrap;
    }
    body:not(.dark-mode) .bnd-card-meta { border-top-color: rgba(6, 182, 212, 0.12); color: #64748b; }
    .bnd-card-meta span { display: inline-flex; align-items: center; gap: 6px; }
    .bnd-card-meta i { color: #06b6d4; }

    .bnd-card-cta {
        margin-top: 14px; display: inline-flex; align-items: center; gap: 8px; align-self: flex-start;
        padding: 10px 18px; border-radius: 12px; background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: #04141a; font-weight: 700; font-size: 0.82rem; transition: all 0.35s cubic-bezier(0.175,0.885,0.32,1.275);
        pointer-events: none;
    }
    .bnd-card:hover .bnd-card-cta { gap: 12px; box-shadow: 0 10px 26px rgba(6, 182, 212, 0.4); }

    /* ── Pagination ───────────────────────────────────────────────────── */
    .bnds-pagination { margin-top: 40px; display: flex; justify-content: center; }

    /* ── Empty state ──────────────────────────────────────────────────── */
    .bnds-empty {
        text-align: center; padding: 110px 20px; background: rgba(15, 23, 42, 0.5); border-radius: 28px;
        border: 2px dashed rgba(6, 182, 212, 0.3); backdrop-filter: blur(20px); margin-top: 20px;
    }
    body:not(.dark-mode) .bnds-empty { background: rgba(255,255,255,0.9); border-color: rgba(6, 182, 212, 0.25); }
    .bnds-empty i { font-size: 3.5rem; color: rgba(6, 182, 212, 0.4); margin-bottom: 18px; }
    .bnds-empty h3 { font-size: 1.3rem; font-weight: 800; color: #fff; margin-bottom: 10px; }
    body:not(.dark-mode) .bnds-empty h3 { color: #0f172a; }
    .bnds-empty p { color: rgba(255,255,255,0.6); }
    body:not(.dark-mode) .bnds-empty p { color: #64748b; }
</style>
@endsection

@section('content')

<!-- Hero -->
<section class="bnds-hero">
    <div class="bnds-hero-inner">
        <span class="bnds-hero-eyebrow"><i class="fas fa-box-open"></i> Économisez en groupant vos achats</span>
        <h1 class="bnds-hero-title">📦 Packs de Documents</h1>
        <p class="bnds-hero-desc">
            Plusieurs documents et épreuves réunis à prix réduit. Un seul achat, une seule archive
            à télécharger, un maximum d'économies.
        </p>
        <span class="bnds-hero-stat">
            <i class="fas fa-box"></i> {{ $bundles->total() }} pack{{ $bundles->total() > 1 ? 's' : '' }} disponible{{ $bundles->total() > 1 ? 's' : '' }}
        </span>
    </div>
</section>

<!-- Bundles Container -->
<div class="bnds-container">
    @if($bundles->count() > 0)
    <div class="bnds-grid">
        @foreach($bundles as $bundle)
        @php
            $bundleUrl = route('bundles.show', $bundle->slug);
            $shareTitle = urlencode($bundle->name);
            $coverUrl = null;
            if ($bundle->cover_image) {
                $coverUrl = $bundle->cover_type === 'internal'
                    ? \Illuminate\Support\Facades\URL::temporarySignedRoute('bundle.cover.signed', now()->addHours(24), ['id' => $bundle->id])
                    : $bundle->cover_image;
            }
        @endphp
        <article class="bnd-card">
            <a href="{{ $bundleUrl }}" class="bnd-card-link" aria-label="{{ $bundle->name }}"></a>

            <div class="bnd-card-share" data-share-card>
                <button type="button" class="bnd-card-share-toggle" data-share-toggle aria-haspopup="true" aria-expanded="false" aria-label="Partager ce pack">
                    <i class="fas fa-share-alt"></i>
                </button>
                <div class="bnd-card-share-menu" data-share-menu>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($bundleUrl) }}" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode($bundleUrl) }}&text={{ $shareTitle }}" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-twitter"></i> X (Twitter)
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($bundle->name . ' ' . $bundleUrl) }}" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode($bundleUrl) }}&title={{ $shareTitle }}" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-linkedin-in"></i> LinkedIn
                    </a>
                    <button type="button" data-copy-link="{{ $bundleUrl }}">
                        <i class="fas fa-link"></i> Copier le lien
                    </button>
                </div>
            </div>

            <div class="bnd-card-media">
                @if($coverUrl)
                <img src="{{ $coverUrl }}" alt="{{ $bundle->name }}" width="400" height="170" loading="lazy" decoding="async" onerror="this.style.display='none'">
                @else
                <div class="bnd-card-media-fallback"><i class="fas fa-box"></i></div>
                @endif

                <span class="bnd-card-badge"><i class="fas fa-layer-group"></i> {{ $bundle->items->count() }} document{{ $bundle->items->count() > 1 ? 's' : '' }}</span>
                @if($bundle->savings > 0)
                <span class="bnd-card-discount"><i class="fas fa-tag"></i> -{{ $bundle->getDiscountPercentage() }}%</span>
                @endif
            </div>

            <div class="bnd-card-body">
                <h3 class="bnd-card-title">{{ $bundle->name }}</h3>
                @if($bundle->description)
                <p class="bnd-card-excerpt">{{ \Illuminate\Support\Str::limit($bundle->description, 100) }}</p>
                @endif

                <div class="bnd-card-price-row">
                    <span class="bnd-card-price">{{ number_format($bundle->current_price, 0, ',', ' ') }} FCFA</span>
                    @if($bundle->hasDiscount())
                    <span class="bnd-card-price-old">{{ number_format($bundle->price, 0, ',', ' ') }} FCFA</span>
                    @endif
                </div>

                <div class="bnd-card-meta">
                    <span><i class="fas fa-shopping-bag"></i> {{ number_format($bundle->sales_count) }} vente{{ $bundle->sales_count > 1 ? 's' : '' }}</span>
                </div>
                <span class="bnd-card-cta">Voir le pack <i class="fas fa-arrow-right"></i></span>
            </div>
        </article>
        @endforeach
    </div>

    @if($bundles->hasPages())
    <div class="bnds-pagination">
        {{ $bundles->links() }}
    </div>
    @endif

    @else
    <div class="bnds-empty">
        <i class="fas fa-box"></i>
        <h3>Aucun pack disponible</h3>
        <p>Revenez bientôt pour découvrir nos packs exclusifs !</p>
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
