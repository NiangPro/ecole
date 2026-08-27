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

        <button type="button" class="bnd-buy-btn" onclick="openWaveDirectModal()">
            <i class="fas fa-lock-open"></i> Acheter le pack — {{ number_format($bundle->current_price, 0, ',', ' ') }} FCFA
        </button>

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

{{-- ══════════ MODAL PAIEMENT WAVE DIRECT (PACK) ══════════ --}}
<div id="wave-direct-modal" class="wd-overlay" style="display:none;">
    <div class="wd-modal">
        <div class="wd-head">
            <div class="wd-head-icon"><i class="fas fa-wave-square"></i></div>
            <h2 class="wd-title">Paiement Wave</h2>
            <button type="button" class="wd-close" onclick="closeWaveDirectModal()" aria-label="Fermer">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="wd-body">
            {{-- ÉTAPE 1 : Formulaire --}}
            <div id="wd-step-form">
                <p class="wd-desc">Renseignez vos informations pour recevoir votre pack.</p>
                <div class="wd-amount">
                    <span>Montant à payer</span>
                    <strong>{{ number_format($bundle->current_price, 0, ',', ' ') }} FCFA</strong>
                </div>

                <form id="wd-form" onsubmit="return false;">
                    @csrf
                    <div class="wd-field">
                        <label for="wd-name">Nom complet <span>*</span></label>
                        <input type="text" id="wd-name" name="customer_name" required placeholder="Votre nom complet" autocomplete="name">
                    </div>
                    <div class="wd-field">
                        <label for="wd-email">Email <span>*</span></label>
                        <input type="email" id="wd-email" name="customer_email" required placeholder="votre@email.com" autocomplete="email">
                    </div>
                    <div class="wd-field">
                        <label for="wd-phone">Numéro de téléphone <span>*</span></label>
                        <input type="tel" id="wd-phone" name="customer_phone" required placeholder="+221 77 000 00 00" autocomplete="tel">
                    </div>
                    <p class="wd-error" id="wd-error" style="display:none;"></p>
                    <button type="button" class="wd-confirm-btn" id="wd-confirm-btn" onclick="confirmWaveDirect()">
                        <i class="fas fa-check-circle"></i>
                        <span>Confirmer et payer avec Wave</span>
                    </button>
                </form>
            </div>

            {{-- ÉTAPE 2 : Confirmation / infos --}}
            <div id="wd-step-success" style="display:none;">
                <div class="wd-success-icon"><i class="fas fa-clock"></i></div>
                <h3 class="wd-success-title">Paiement enregistré (en attente)</h3>
                <p class="wd-success-text">
                    Un nouvel onglet Wave s'est ouvert avec le <strong>QR code à payer</strong>.
                    Une fois le paiement effectué, votre pack vous sera envoyé par email.
                </p>
                <div class="wd-info-box">
                    <i class="fas fa-hourglass-half"></i>
                    <div>
                        L'envoi du document prend généralement <strong>jusqu'à 4 heures</strong>.
                        Si c'est urgent, appelez-nous directement :
                        <a href="tel:{{ $siteSettings->contact_phone ?? '+221783123657' }}" class="wd-phone-link">
                            <i class="fas fa-phone"></i> {{ $siteSettings->contact_phone ?? '+221 78 312 36 57' }}
                        </a>
                    </div>
                </div>
                <a href="#" id="wd-reopen-wave" target="_blank" rel="noopener" class="wd-reopen-btn">
                    <i class="fas fa-mobile-alt"></i> Rouvrir la page de paiement Wave
                </a>
                <button type="button" class="wd-done-btn" onclick="closeWaveDirectModal()">Fermer</button>
            </div>
        </div>
    </div>
</div>

<style>
.wd-overlay{position:fixed;inset:0;z-index:10000;display:flex;align-items:center;justify-content:center;padding:1rem;background:rgba(15,23,42,.65);backdrop-filter:blur(4px);animation:wdFade .2s ease;}
@keyframes wdFade{from{opacity:0}to{opacity:1}}
.wd-modal{width:100%;max-width:440px;max-height:92vh;overflow:hidden;display:flex;flex-direction:column;background:#fff;border-radius:20px;box-shadow:0 25px 80px rgba(0,0,0,.35);animation:wdPop .25s cubic-bezier(.16,1,.3,1);}
@keyframes wdPop{from{opacity:0;transform:translateY(16px) scale(.97)}to{opacity:1;transform:none}}
.wd-head{position:relative;display:flex;flex-direction:column;align-items:center;gap:.6rem;padding:1.6rem 1.5rem 1.2rem;border-bottom:1px solid #eef2f7;}
.wd-head-icon{width:54px;height:54px;border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;color:#fff;background:linear-gradient(135deg,#06b6d4,#0891b2);box-shadow:0 8px 20px rgba(6,182,212,.35);}
.wd-title{margin:0;font-size:1.25rem;font-weight:800;color:#0f172a;}
.wd-close{position:absolute;top:1rem;right:1rem;width:34px;height:34px;border:none;border-radius:50%;background:#f1f5f9;color:#64748b;cursor:pointer;font-size:.9rem;transition:.2s;}
.wd-close:hover{background:#e2e8f0;color:#0f172a;}
.wd-body{padding:1.4rem 1.5rem 1.6rem;overflow-y:auto;}
.wd-desc{margin:0 0 1rem;color:#64748b;font-size:.92rem;text-align:center;}
.wd-amount{display:flex;align-items:center;justify-content:space-between;padding:.85rem 1.1rem;margin-bottom:1.2rem;border-radius:14px;background:rgba(6,182,212,.08);border:1px solid rgba(6,182,212,.2);}
.wd-amount span{color:#475569;font-size:.9rem;}
.wd-amount strong{color:#0891b2;font-size:1.25rem;font-weight:800;}
.wd-field{margin-bottom:1rem;}
.wd-field label{display:block;margin-bottom:.4rem;font-size:.85rem;font-weight:600;color:#334155;}
.wd-field label span{color:#ef4444;}
.wd-field input{width:100%;padding:.8rem 1rem;border:1.5px solid #e2e8f0;border-radius:12px;font-size:.95rem;color:#0f172a;background:#fff;transition:.2s;box-sizing:border-box;}
.wd-field input:focus{outline:none;border-color:#06b6d4;box-shadow:0 0 0 3px rgba(6,182,212,.12);}
.wd-error{margin:.2rem 0 .8rem;padding:.6rem .8rem;border-radius:10px;background:#fef2f2;color:#dc2626;font-size:.85rem;}
.wd-confirm-btn{width:100%;display:flex;align-items:center;justify-content:center;gap:.6rem;padding:.95rem;margin-top:.4rem;border:none;border-radius:14px;font-size:1rem;font-weight:700;color:#fff;cursor:pointer;background:linear-gradient(135deg,#06b6d4,#0891b2);box-shadow:0 8px 22px rgba(6,182,212,.35);transition:.2s;}
.wd-confirm-btn:hover{transform:translateY(-2px);box-shadow:0 12px 28px rgba(6,182,212,.45);}
.wd-confirm-btn:disabled{opacity:.7;cursor:not-allowed;transform:none;}
.wd-success-icon{width:64px;height:64px;margin:.2rem auto 1rem;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.7rem;color:#f59e0b;background:rgba(245,158,11,.12);}
.wd-success-title{margin:0 0 .6rem;text-align:center;font-size:1.15rem;font-weight:800;color:#0f172a;}
.wd-success-text{margin:0 0 1.2rem;text-align:center;color:#64748b;font-size:.92rem;line-height:1.5;}
.wd-info-box{display:flex;gap:.8rem;padding:1rem;margin-bottom:1.2rem;border-radius:14px;background:rgba(245,158,11,.08);border-left:3px solid #f59e0b;color:#475569;font-size:.9rem;line-height:1.5;}
.wd-info-box>i{color:#f59e0b;font-size:1.1rem;margin-top:.15rem;}
.wd-phone-link{display:inline-flex;align-items:center;gap:.4rem;margin-top:.5rem;padding:.5rem .9rem;border-radius:10px;background:#0f172a;color:#fff !important;font-weight:700;text-decoration:none;font-size:.95rem;}
.wd-phone-link:hover{background:#1e293b;}
.wd-reopen-btn{width:100%;display:flex;align-items:center;justify-content:center;gap:.6rem;padding:.85rem;margin-bottom:.7rem;border-radius:12px;font-weight:700;text-decoration:none;color:#0891b2;background:rgba(6,182,212,.08);border:1.5px solid rgba(6,182,212,.3);transition:.2s;}
.wd-reopen-btn:hover{background:rgba(6,182,212,.15);}
.wd-done-btn{width:100%;padding:.8rem;border:none;border-radius:12px;background:#f1f5f9;color:#475569;font-weight:600;cursor:pointer;transition:.2s;}
.wd-done-btn:hover{background:#e2e8f0;}
body.dark-mode .wd-modal{background:#1e293b;}
body.dark-mode .wd-head{border-bottom-color:rgba(255,255,255,.08);}
body.dark-mode .wd-title,body.dark-mode .wd-success-title{color:#f1f5f9;}
body.dark-mode .wd-field label{color:#cbd5e1;}
body.dark-mode .wd-field input{background:#0f172a;border-color:#334155;color:#f1f5f9;}
body.dark-mode .wd-close{background:#334155;color:#cbd5e1;}
body.dark-mode .wd-done-btn{background:#334155;color:#cbd5e1;}
</style>
@endsection

@push('scripts')
<script>
    const WD_CHECKOUT_URL = '{{ route('bundles.payment.checkout', $bundle->slug) }}';

    function openWaveDirectModal() {
        const modal = document.getElementById('wave-direct-modal');
        if (!modal) return;
        document.getElementById('wd-step-form').style.display = 'block';
        document.getElementById('wd-step-success').style.display = 'none';
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeWaveDirectModal() {
        const modal = document.getElementById('wave-direct-modal');
        if (!modal) return;
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }

    function confirmWaveDirect() {
        const name  = document.getElementById('wd-name').value.trim();
        const email = document.getElementById('wd-email').value.trim();
        const phone = document.getElementById('wd-phone').value.trim();
        const errorEl = document.getElementById('wd-error');
        const btn = document.getElementById('wd-confirm-btn');

        errorEl.style.display = 'none';

        if (!name || !email || !phone) {
            errorEl.textContent = 'Merci de remplir tous les champs.';
            errorEl.style.display = 'block';
            return;
        }
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            errorEl.textContent = 'Merci de saisir une adresse email valide.';
            errorEl.style.display = 'block';
            return;
        }

        const originalContent = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Traitement en cours...</span>';

        // Ouvrir l'onglet immédiatement (évite le blocage popup) puis le rediriger vers Wave
        const waveTab = window.open('', '_blank');

        const fd = new FormData();
        fd.append('_token', '{{ csrf_token() }}');
        fd.append('payment_method', 'wave');
        fd.append('customer_name', name);
        fd.append('customer_email', email);
        fd.append('customer_phone', phone);

        fetch(WD_CHECKOUT_URL, {
            method: 'POST',
            body: fd,
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json().then(data => ({ ok: r.ok, data })))
        .then(({ ok, data }) => {
            if (ok && data.success && data.already_purchased && data.download_url) {
                // Déjà acheté → rediriger vers le téléchargement
                if (waveTab) waveTab.close();
                window.location.href = data.download_url;
                return;
            }
            if (ok && data.success && data.wave_link) {
                if (waveTab) {
                    waveTab.location.href = data.wave_link;
                } else {
                    window.open(data.wave_link, '_blank');
                }
                const reopen = document.getElementById('wd-reopen-wave');
                if (reopen) reopen.href = data.wave_link;
                document.getElementById('wd-step-form').style.display = 'none';
                document.getElementById('wd-step-success').style.display = 'block';
            } else {
                if (waveTab) waveTab.close();
                errorEl.textContent = data.message || 'Une erreur est survenue. Veuillez réessayer.';
                errorEl.style.display = 'block';
                btn.disabled = false;
                btn.innerHTML = originalContent;
            }
        })
        .catch(() => {
            if (waveTab) waveTab.close();
            errorEl.textContent = 'Une erreur réseau est survenue. Veuillez réessayer.';
            errorEl.style.display = 'block';
            btn.disabled = false;
            btn.innerHTML = originalContent;
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const wdOverlay = document.getElementById('wave-direct-modal');
        if (wdOverlay) {
            wdOverlay.addEventListener('click', function(e) {
                if (e.target === this) closeWaveDirectModal();
            });
        }
    });
</script>
@endpush
