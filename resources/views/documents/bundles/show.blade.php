@extends('layouts.app')

@section('title', $bundle->name . ' - Packs | NiangProgrammeur')
@section('meta_description', $bundle->description ?? 'Découvrez ce pack de documents avec des réductions exclusives.')

@push('styles')
<style>
:root {
  --bnd-cyan: #06b6d4; --bnd-teal: #14b8a6;
  --bnd-bg1: #ecfeff; --bnd-bg2: #f0f9ff; --bnd-bg3: #f8fafc;
  --bnd-card: #ffffff; --bnd-border: rgba(15,23,42,0.08);
  --bnd-text: #0f172a; --bnd-muted: #64748b;
}

.bnd-page {
  min-height: 100vh;
  padding: calc(var(--spacing-navbar, 76px) + 1.5rem) 1.25rem 4rem;
  background: linear-gradient(180deg, var(--bnd-bg1) 0%, var(--bnd-bg2) 30%, var(--bnd-bg3) 65%);
}
.bnd-wrap { max-width: 1180px; margin: 0 auto; }

.bnd-crumbs { display:flex; align-items:center; flex-wrap:wrap; gap:.4rem; font-size:.82rem; color:var(--bnd-muted); margin-bottom:1.5rem; }
.bnd-crumbs a { color:var(--bnd-muted); text-decoration:none; }
.bnd-crumbs a:hover { color:var(--bnd-cyan); }
.bnd-crumbs .cur { color:var(--bnd-text); font-weight:600; }

.bnd-hero {
  position: relative; overflow: hidden;
  background: linear-gradient(135deg, var(--bnd-cyan), var(--bnd-teal));
  border-radius: 24px; padding: 2.5rem; color: #fff; margin-bottom: 2rem;
  box-shadow: 0 20px 50px rgba(6,182,212,0.25);
}
.bnd-hero::after {
  content: ''; position: absolute; top: -60px; right: -60px; width: 260px; height: 260px;
  background: rgba(255,255,255,0.12); border-radius: 50%;
}
.bnd-hero.has-cover {
  background-image:
    linear-gradient(135deg, rgba(6,182,212,0.82), rgba(20,184,166,0.82)),
    var(--bnd-hero-cover);
  background-size: cover;
  background-position: center;
}
.bnd-hero-eyebrow {
  display:inline-flex; align-items:center; gap:.4rem; padding:.35rem .9rem; border-radius:50px;
  background: rgba(255,255,255,0.18); font-size:.78rem; font-weight:800; letter-spacing:.04em;
  text-transform: uppercase; margin-bottom: 1rem;
}
.bnd-hero-title { font-size: clamp(1.6rem, 3.4vw, 2.4rem); font-weight: 900; margin: 0 0 .75rem; position:relative; z-index:1; }
.bnd-hero-desc { font-size: .98rem; opacity: .92; line-height: 1.6; max-width: 640px; position:relative; z-index:1; }
.bnd-hero-stats { display:flex; flex-wrap:wrap; gap:.6rem; margin-top: 1.5rem; position:relative; z-index:1; }
.bnd-hero-stat {
  display:inline-flex; align-items:center; gap:.4rem; padding:.5rem 1rem; border-radius:50px;
  background: rgba(255,255,255,0.16); backdrop-filter: blur(6px); font-size:.85rem; font-weight:700;
}

.bnd-grid { display: grid; grid-template-columns: 1fr 380px; gap: 2rem; align-items: start; }
@media (max-width: 900px) { .bnd-grid { grid-template-columns: 1fr; } }

.bnd-section-title { font-size: 1.2rem; font-weight: 900; color: var(--bnd-text); margin-bottom: 1.1rem; display:flex; align-items:center; gap:.5rem; }
.bnd-section-title i { color: var(--bnd-cyan); }

.bnd-items { display: grid; gap: .9rem; }
.bnd-item {
  display:flex; gap:1rem; padding: 1rem; background: var(--bnd-card); border: 1px solid var(--bnd-border);
  border-radius: 16px; box-shadow: 0 8px 24px rgba(15,23,42,0.05); transition: transform .2s, box-shadow .2s;
}
.bnd-item:hover { transform: translateY(-2px); box-shadow: 0 14px 32px rgba(15,23,42,0.09); }
.bnd-item-cover {
  width: 64px; height: 64px; min-width:64px; border-radius: 12px; overflow: hidden;
  display:flex; align-items:center; justify-content:center; background: linear-gradient(135deg, #a78bfa, #7c3aed); color:#fff; font-size:1.4rem;
}
.bnd-item-cover.is-epreuve { background: linear-gradient(135deg, #06b6d4, #14b8a6); }
.bnd-item-cover img { width:100%; height:100%; object-fit:cover; }
.bnd-item-body { flex: 1; min-width:0; }
.bnd-item-title { font-weight: 700; color: var(--bnd-text); font-size: .95rem; margin-bottom: .3rem; }
.bnd-item-title a { color: inherit; text-decoration: none; }
.bnd-item-title a:hover { color: var(--bnd-cyan); }
.bnd-item-meta { font-size: .78rem; color: var(--bnd-muted); display:flex; align-items:center; gap:.5rem; flex-wrap:wrap; }
.bnd-item-type {
  display:inline-flex; align-items:center; gap:.3rem; padding:.15rem .55rem; border-radius:50px;
  background: rgba(6,182,212,0.1); color: #0891b2; font-size: .68rem; font-weight: 800; text-transform:uppercase;
}
.bnd-item-corrige {
  display:inline-flex; align-items:center; gap:.3rem; padding:.15rem .55rem; border-radius:50px;
  background: rgba(16,185,129,0.12); color: #047857; font-size: .68rem; font-weight: 800; text-transform:uppercase;
}
.bnd-item-price { font-weight: 800; color: var(--bnd-text); white-space:nowrap; }
.bnd-item-free { color: #10b981; font-weight: 800; white-space:nowrap; }

/* Carte achat */
.bnd-buy-card {
  position: sticky; top: calc(var(--spacing-navbar, 76px) + 1rem);
  background: var(--bnd-card); border: 1px solid var(--bnd-border); border-radius: 20px;
  padding: 1.75rem; box-shadow: 0 16px 44px rgba(15,23,42,0.08);
}
.bnd-price-row { display:flex; justify-content:space-between; align-items:center; padding: .5rem 0; font-size:.9rem; color: var(--bnd-muted); }
.bnd-price-row.old span:last-child { text-decoration: line-through; color: var(--bnd-muted); font-weight:700; }
.bnd-price-row.current { border-top: 1px dashed var(--bnd-border); margin-top:.25rem; padding-top: .85rem; }
.bnd-price-row.current span:last-child { font-size: 1.6rem; font-weight: 900; background: linear-gradient(135deg, var(--bnd-cyan), var(--bnd-teal)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.bnd-savings {
  margin: .75rem 0 1.25rem; padding: .65rem .9rem; border-radius: 12px;
  background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.25);
  color: #047857; font-weight: 800; font-size: .85rem; text-align:center;
}
.bnd-buy-form input {
  width:100%; border: 1px solid var(--bnd-border); border-radius: 12px; padding: .75rem 1rem;
  font-size: .9rem; font-family: inherit; color: var(--bnd-text); background:#fff; margin-bottom: .75rem;
}
.bnd-buy-form .bnd-or { text-align:center; font-size:.75rem; color: var(--bnd-muted); margin: -.35rem 0 .75rem; text-transform:uppercase; letter-spacing:.05em; }
.bnd-buy-btn {
  width: 100%; padding: 1rem; border: none; border-radius: 14px; cursor: pointer;
  background: linear-gradient(135deg, var(--bnd-cyan), var(--bnd-teal)); color: #fff; font-weight: 800; font-size: 1rem;
  display:flex; align-items:center; justify-content:center; gap:.55rem;
  box-shadow: 0 12px 28px rgba(6,182,212,0.32); transition: transform .18s, box-shadow .18s;
}
.bnd-buy-btn:hover { transform: translateY(-2px); box-shadow: 0 16px 36px rgba(6,182,212,0.42); }
.bnd-trust { margin-top: 1.1rem; display:grid; gap:.5rem; }
.bnd-trust div { display:flex; align-items:center; gap:.5rem; font-size:.8rem; color: var(--bnd-muted); }
.bnd-trust i { color: var(--bnd-cyan); width:16px; text-align:center; }
.bnd-error { background: rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.3); color:#b91c1c; border-radius:12px; padding:.75rem 1rem; font-size:.85rem; margin-bottom:1rem; }

.bnd-related { margin-top: 3rem; }
.bnd-related-grid { display:grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1rem; }
.bnd-related-card {
  display:block; background: var(--bnd-card); border:1px solid var(--bnd-border); border-radius:16px; overflow:hidden;
  text-decoration:none; color:inherit; transition: transform .2s, box-shadow .2s;
}
.bnd-related-card:hover { transform: translateY(-3px); box-shadow: 0 14px 34px rgba(15,23,42,0.1); }
.bnd-related-cover { height: 120px; background: linear-gradient(135deg, #a78bfa, #7c3aed); display:flex; align-items:center; justify-content:center; color:#fff; font-size:2rem; }
.bnd-related-cover img { width:100%; height:100%; object-fit:cover; }
.bnd-related-body { padding: .9rem 1rem; }
.bnd-related-name { font-weight:700; font-size:.88rem; color: var(--bnd-text); margin-bottom:.3rem; }
.bnd-related-price { font-size:.8rem; color: var(--bnd-cyan); font-weight:800; }

body.dark-mode .bnd-page { background: linear-gradient(180deg,#052e2b,#0b1120 65%); }
body.dark-mode .bnd-item,
body.dark-mode .bnd-buy-card,
body.dark-mode .bnd-related-card { background: rgba(15,23,42,0.75); border-color: rgba(148,163,184,0.15); }
body.dark-mode .bnd-item-title,
body.dark-mode .bnd-section-title,
body.dark-mode .bnd-related-name,
body.dark-mode .bnd-price-row.current span:first-child { color: #f1f5f9; }
body.dark-mode .bnd-buy-form input { background: rgba(2,6,23,0.6); border-color: rgba(148,163,184,0.25); color:#e2e8f0; }
</style>
@endpush

@section('content')
<div class="bnd-page">
  <div class="bnd-wrap">

    <nav class="bnd-crumbs" aria-label="Fil d'ariane">
      <a href="{{ route('home') }}">Accueil</a>
      <span>›</span>
      <a href="{{ route('bundles.index') }}">Packs</a>
      <span>›</span>
      <span class="cur">{{ \Illuminate\Support\Str::limit($bundle->name, 40) }}</span>
    </nav>

    @php
      $bndCoverUrl = null;
      if ($bundle->cover_image) {
          $bndCoverUrl = $bundle->cover_type === 'internal'
              ? \Illuminate\Support\Facades\URL::temporarySignedRoute('bundle.cover.signed', now()->addHours(24), ['id' => $bundle->id])
              : $bundle->cover_image;
      }
    @endphp
    <header class="bnd-hero @if($bndCoverUrl) has-cover @endif" @if($bndCoverUrl) style="--bnd-hero-cover: url('{{ $bndCoverUrl }}');" @endif>
      <span class="bnd-hero-eyebrow"><i class="fas fa-box-open"></i> Pack de documents</span>
      <h1 class="bnd-hero-title">{{ $bundle->name }}</h1>
      @if($bundle->description)
        <p class="bnd-hero-desc">{{ $bundle->description }}</p>
      @endif
      <div class="bnd-hero-stats">
        <span class="bnd-hero-stat"><i class="fas fa-layer-group"></i> {{ $bundle->items->count() }} document{{ $bundle->items->count() > 1 ? 's' : '' }}</span>
        @if($bundle->savings > 0)
          <span class="bnd-hero-stat"><i class="fas fa-tag"></i> -{{ $bundle->getDiscountPercentage() }}% vs achat séparé</span>
        @endif
        <span class="bnd-hero-stat"><i class="fas fa-shopping-bag"></i> {{ number_format($bundle->sales_count) }} vente{{ $bundle->sales_count > 1 ? 's' : '' }}</span>
      </div>
    </header>

    <div class="bnd-grid">
      {{-- Colonne principale : contenu du pack --}}
      <div>
        <h2 class="bnd-section-title"><i class="fas fa-list"></i> Contenu du pack ({{ $bundle->items->count() }})</h2>
        <div class="bnd-items">
          @foreach($bundle->items as $item)
            @php $itemable = $item->itemable; @endphp
            @continue(!$itemable)
            <div class="bnd-item">
              @if($item->item_type === \App\Models\Document::class)
                <div class="bnd-item-cover">
                  @if($itemable->cover_image)
                    @if($itemable->cover_type === 'internal')
                      <img loading="lazy" src="{{ \Illuminate\Support\Facades\URL::temporarySignedRoute('document.cover.signed', now()->addHours(24), ['id' => $itemable->id]) }}" alt="{{ $itemable->title }}">
                    @else
                      <img loading="lazy" src="{{ $itemable->cover_image }}" alt="{{ $itemable->title }}">
                    @endif
                  @else
                    <i class="fas fa-file-alt"></i>
                  @endif
                </div>
                <div class="bnd-item-body">
                  <div class="bnd-item-title"><a href="{{ route('documents.show', $itemable->slug) }}">{{ $itemable->title }}</a></div>
                  <div class="bnd-item-meta">
                    <span class="bnd-item-type"><i class="fas fa-file-alt"></i> Document</span>
                  </div>
                </div>
                <div>
                  @if($itemable->isFree())
                    <span class="bnd-item-free">Gratuit</span>
                  @else
                    <span class="bnd-item-price">{{ number_format($itemable->current_price, 0, ',', ' ') }} FCFA</span>
                  @endif
                </div>
              @else
                <div class="bnd-item-cover is-epreuve"><i class="fas fa-graduation-cap"></i></div>
                <div class="bnd-item-body">
                  <div class="bnd-item-title"><a href="{{ route('epreuves.show', $itemable->slug) }}">{{ $itemable->title }}</a></div>
                  <div class="bnd-item-meta">
                    <span class="bnd-item-type"><i class="fas fa-graduation-cap"></i> Épreuve</span>
                    @if($itemable->year)<span>{{ $itemable->year_label }}</span>@endif
                    @if($itemable->hasCorrige())
                      <span class="bnd-item-corrige"><i class="fas fa-check-circle"></i> Corrigé inclus</span>
                    @endif
                  </div>
                </div>
                <div>
                  @if($itemable->isFree())
                    <span class="bnd-item-free">Gratuit</span>
                  @else
                    <span class="bnd-item-price">{{ number_format($itemable->price, 0, ',', ' ') }} FCFA</span>
                  @endif
                </div>
              @endif
            </div>
          @endforeach
        </div>
      </div>

      {{-- Colonne latérale : achat --}}
      <aside class="bnd-buy-card">
        <div class="bnd-price-row old">
          <span>Prix total individuel</span>
          <span>{{ number_format($bundle->total_individual_price, 0, ',', ' ') }} FCFA</span>
        </div>
        <div class="bnd-price-row current">
          <span>Prix du pack</span>
          <span>{{ number_format($bundle->current_price, 0, ',', ' ') }} FCFA</span>
        </div>

        @if($bundle->savings > 0)
        <div class="bnd-savings"><i class="fas fa-piggy-bank"></i> Vous économisez {{ number_format($bundle->savings, 0, ',', ' ') }} FCFA</div>
        @endif

        @if($errors->any())
          <div class="bnd-error">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('bundles.payment.checkout', $bundle->slug) }}" method="POST" class="bnd-buy-form">
            @csrf
            <input type="text" name="customer_name" value="{{ old('customer_name') }}" placeholder="Votre nom (optionnel)">
            <input type="email" name="customer_email" value="{{ old('customer_email') }}" placeholder="Votre e-mail">
            <div class="bnd-or">ou</div>
            <input type="tel" name="customer_phone" value="{{ old('customer_phone') }}" placeholder="Votre numéro WhatsApp">
            <button type="submit" class="bnd-buy-btn">
                <i class="fas fa-lock-open"></i> Acheter le pack — {{ number_format($bundle->current_price, 0, ',', ' ') }} FCFA
            </button>
        </form>

        <div class="bnd-trust">
          <div><i class="fas fa-check-circle"></i> Paiement Wave sécurisé</div>
          <div><i class="fas fa-envelope"></i> Lien de téléchargement envoyé par e-mail</div>
          @if($bundle->items->contains(fn($item) => $item->itemable instanceof \App\Models\Epreuve && $item->itemable->hasCorrige()))
          <div><i class="fas fa-file-archive"></i> Une seule archive .zip avec épreuves et corrigés inclus</div>
          @else
          <div><i class="fas fa-file-archive"></i> Une seule archive .zip avec tous les documents</div>
          @endif
        </div>
      </aside>
    </div>

    @if($relatedBundles->isNotEmpty())
    <section class="bnd-related">
      <h2 class="bnd-section-title"><i class="fas fa-box"></i> Autres packs</h2>
      <div class="bnd-related-grid">
        @foreach($relatedBundles as $related)
          @php
            $relatedCoverUrl = null;
            if ($related->cover_image) {
                $relatedCoverUrl = $related->cover_type === 'internal'
                    ? \Illuminate\Support\Facades\URL::temporarySignedRoute('bundle.cover.signed', now()->addHours(24), ['id' => $related->id])
                    : $related->cover_image;
            }
          @endphp
          <a href="{{ route('bundles.show', $related->slug) }}" class="bnd-related-card">
            <div class="bnd-related-cover">
              @if($relatedCoverUrl)
                <img loading="lazy" src="{{ $relatedCoverUrl }}" alt="{{ $related->name }}">
              @else
                <i class="fas fa-box"></i>
              @endif
            </div>
            <div class="bnd-related-body">
              <div class="bnd-related-name">{{ \Illuminate\Support\Str::limit($related->name, 40) }}</div>
              <div class="bnd-related-price">{{ number_format($related->current_price, 0, ',', ' ') }} FCFA</div>
            </div>
          </a>
        @endforeach
      </div>
    </section>
    @endif

  </div>
</div>
@endsection
