@extends('layouts.app')

@section('title', $epreuve->title . ' - PDF gratuit - NiangProgrammeur')
@section('meta_description', ($epreuve->description ? Str::limit(strip_tags($epreuve->description), 150) : $epreuve->title . ' à télécharger gratuitement en PDF.' . ($epreuve->matiere ? ' ' . $epreuve->matiere->name . '.' : '') . ($epreuve->year ? ' Session ' . $epreuve->year_label . '.' : '') . ' Examens du Sénégal.'))

@php
    // Données AdSense pour l'interstitiel de téléchargement
    $adsSettings = \App\Models\AdSenseSetting::first();
    $adsClientId = null;
    if ($adsSettings && $adsSettings->adsense_code && preg_match('/ca-pub-([0-9]+)/', $adsSettings->adsense_code, $cm)) {
        $adsClientId = 'ca-pub-' . $cm[1];
    }
    $adsUnit = \App\Models\AdSenseUnit::getUnitsForPosition('content')->first();
    $adsSlot = $adsUnit?->ad_slot;
    $downloadUrl = route('epreuves.download', ['id' => $epreuve->id]);
    $isPaidEpreuve = !$epreuve->isFree() && !$epreuve->isCorrige();
    $showAdGate = !$epreuve->isCorrige() && !$isPaidEpreuve && $adsClientId && $adsSlot;
    $corrigePrice = $epreuve->hasCorrige() ? $epreuve->getCorrigePrice() : null;
@endphp

@section('styles')
<style>
.ep2 {
    --ep2-bg1:#ecfdf5; --ep2-bg2:#f0f9ff; --ep2-bg3:#f8fafc;
    --ep2-accent:#059669; --ep2-accent2:#0891b2;
    --ep2-card:#ffffff; --ep2-border:rgba(15,23,42,0.08);
    --ep2-text:#0f172a; --ep2-muted:#64748b; --ep2-soft:#475569;
    min-height:100vh; color:var(--ep2-text);
    padding: calc(var(--spacing-navbar, 76px) + 2rem) 1.25rem 4.5rem;
    background:
        radial-gradient(900px 500px at 12% -8%, rgba(5,150,105,0.12), transparent 60%),
        radial-gradient(800px 480px at 100% 0%, rgba(8,145,178,0.10), transparent 55%),
        linear-gradient(180deg, var(--ep2-bg1) 0%, var(--ep2-bg2) 30%, var(--ep2-bg3) 65%);
}
.ep2-wrap { max-width:1180px; margin:0 auto; }

/* Fil d'ariane */
.ep2-crumbs { display:flex; align-items:center; flex-wrap:wrap; gap:0.4rem; font-size:0.82rem; color:var(--ep2-muted); margin-bottom:1.5rem; }
.ep2-crumbs a { color:var(--ep2-muted); text-decoration:none; transition:color .15s; }
.ep2-crumbs a:hover { color:var(--ep2-accent); }
.ep2-crumbs .sep { opacity:0.5; }
.ep2-crumbs .cur { color:var(--ep2-text); font-weight:600; }

/* Hero */
.ep2-hero {
    position:relative; overflow:hidden;
    background:linear-gradient(150deg, rgba(255,255,255,0.95), rgba(248,250,252,0.9));
    border:1px solid var(--ep2-border); border-radius:24px;
    padding:2rem 2rem 1.75rem; margin-bottom:1.5rem;
    box-shadow:0 18px 50px rgba(15,23,42,0.07); backdrop-filter:blur(8px);
}
.ep2-hero::before { content:''; position:absolute; inset:0 0 auto 0; height:4px;
    background:linear-gradient(90deg, var(--ep2-accent), var(--ep2-accent2)); }
.ep2-badges { display:flex; gap:0.45rem; flex-wrap:wrap; margin-bottom:1rem; }
.ep2-badge { font-size:0.7rem; font-weight:800; padding:0.3rem 0.75rem; border-radius:999px; text-transform:uppercase; letter-spacing:0.04em; }
.ep2-badge--exam { background:rgba(5,150,105,0.13); color:#047857; }
.ep2-badge--type { background:rgba(14,165,233,0.13); color:#0369a1; }
.ep2-badge--free { background:rgba(16,185,129,0.13); color:#059669; }
.ep2-title { font-size:clamp(1.5rem, 3.2vw, 2.25rem); font-weight:900; line-height:1.22; letter-spacing:-0.01em; color:var(--ep2-text); margin:0 0 1.1rem; }
.ep2-stats { display:flex; flex-wrap:wrap; gap:0.6rem; }
.ep2-stat { display:inline-flex; align-items:center; gap:0.45rem; padding:0.45rem 0.85rem; border-radius:12px;
    background:rgba(255,255,255,0.85); border:1px solid var(--ep2-border); font-size:0.82rem; font-weight:600; color:var(--ep2-soft); }
.ep2-stat i { color:var(--ep2-accent); }
.ep2-desc { color:var(--ep2-soft); line-height:1.7; margin-top:1.25rem; font-size:0.95rem; }

/* Grille 2 colonnes */
.ep2-grid { display:grid; grid-template-columns:1fr 340px; gap:1.5rem; align-items:start; }
@media (max-width: 920px){ .ep2-grid { grid-template-columns:1fr; } }

/* Visionneuse */
.ep2-viewer { background:var(--ep2-card); border:1px solid var(--ep2-border); border-radius:20px; overflow:hidden; box-shadow:0 14px 44px rgba(15,23,42,0.07); }
.ep2-viewer-bar { display:flex; align-items:center; gap:0.6rem; padding:0.85rem 1.1rem; border-bottom:1px solid var(--ep2-border); font-weight:700; font-size:0.88rem; color:var(--ep2-text); }
.ep2-viewer-bar .dots { display:flex; gap:0.35rem; margin-right:0.3rem; }
.ep2-viewer-bar .dots i { width:10px; height:10px; border-radius:50%; display:inline-block; }
.ep2-viewer-bar .dots i:nth-child(1){ background:#f87171; } .ep2-viewer-bar .dots i:nth-child(2){ background:#fbbf24; } .ep2-viewer-bar .dots i:nth-child(3){ background:#34d399; }
.ep2-viewer-bar .grow { margin-left:auto; font-size:0.72rem; font-weight:600; color:var(--ep2-muted); }
.ep2-viewer iframe { display:block; width:100%; height:78vh; min-height:520px; border:none; background:#f1f5f9; }
/* Poster cliquable (aperçu chargé à la demande) */
.ep2-viewer-poster { height:78vh; min-height:520px; display:grid; place-items:center; cursor:pointer; text-align:center; padding:2rem;
    background:radial-gradient(600px 320px at 50% 30%, rgba(5,150,105,0.08), transparent 60%), #f8fafc; transition:background .2s; }
.ep2-viewer-poster:hover { background:radial-gradient(600px 320px at 50% 30%, rgba(5,150,105,0.14), transparent 60%), #f1f5f9; }
.ep2-viewer-poster:focus-visible { outline:2px solid var(--ep2-accent); outline-offset:-4px; }
.ep2-poster-ico { width:70px; height:70px; margin:0 auto 1rem; border-radius:18px; display:grid; place-items:center; font-size:1.9rem; color:#ef4444; background:rgba(239,68,68,0.1); }
.ep2-poster-title { font-weight:800; font-size:1.15rem; color:var(--ep2-text); margin-bottom:0.35rem; }
.ep2-poster-sub { font-size:0.85rem; color:var(--ep2-muted); margin-bottom:1.25rem; }
.ep2-poster-btn { display:inline-flex; align-items:center; gap:0.5rem; padding:0.7rem 1.5rem; border-radius:12px; font-weight:800; color:#fff;
    background:linear-gradient(135deg, var(--ep2-accent), var(--ep2-accent2)); box-shadow:0 8px 22px rgba(5,150,105,0.3); }
body.dark-mode .ep2-viewer-poster { background:radial-gradient(600px 320px at 50% 30%, rgba(5,150,105,0.12), transparent 60%), #0b1120; }

/* Colonne action (sticky) */
.ep2-side { position:sticky; top:calc(var(--spacing-navbar, 76px) + 1rem); display:flex; flex-direction:column; gap:1rem; }
@media (max-width: 920px){ .ep2-side { position:static; } }
.ep2-card { background:var(--ep2-card); border:1px solid var(--ep2-border); border-radius:20px; padding:1.4rem; box-shadow:0 12px 36px rgba(15,23,42,0.06); }
.ep2-dl-head { display:flex; align-items:center; gap:0.7rem; margin-bottom:1rem; }
.ep2-dl-ico { width:46px; height:46px; flex-shrink:0; border-radius:14px; display:grid; place-items:center; font-size:1.25rem; color:#fff; background:linear-gradient(135deg, var(--ep2-accent), var(--ep2-accent2)); box-shadow:0 6px 18px rgba(5,150,105,0.35); }
.ep2-dl-h { font-weight:800; font-size:1rem; color:var(--ep2-text); }
.ep2-dl-sub { font-size:0.78rem; color:var(--ep2-muted); }
.ep2-dl-btn { display:flex; align-items:center; justify-content:center; gap:0.6rem; width:100%; padding:0.95rem; border-radius:14px; font-weight:800; font-size:1rem; text-decoration:none; color:#fff; cursor:pointer; border:none;
    background:linear-gradient(135deg, var(--ep2-accent), var(--ep2-accent2)); box-shadow:0 10px 26px rgba(5,150,105,0.32); transition:transform .18s, box-shadow .18s; }
.ep2-dl-btn:hover { transform:translateY(-2px); box-shadow:0 14px 34px rgba(5,150,105,0.42); color:#fff; }
.ep2-trust { display:flex; flex-direction:column; gap:0.5rem; margin-top:1rem; }
.ep2-trust div { display:flex; align-items:center; gap:0.55rem; font-size:0.82rem; color:var(--ep2-soft); }
.ep2-trust i { color:var(--ep2-accent); width:16px; text-align:center; }

/* Mini fiche infos */
.ep2-info-title { font-size:0.72rem; font-weight:800; text-transform:uppercase; letter-spacing:0.05em; color:var(--ep2-muted); margin-bottom:0.85rem; }
.ep2-info-row { display:flex; justify-content:space-between; align-items:center; padding:0.55rem 0; border-bottom:1px dashed var(--ep2-border); font-size:0.86rem; }
.ep2-info-row:last-child { border-bottom:none; }
.ep2-info-row span:first-child { color:var(--ep2-muted); }
.ep2-info-row span:last-child { font-weight:700; color:var(--ep2-text); }

/* Similaires */
.ep2-related { margin-top:2.5rem; }
.ep2-related-title { font-size:1.35rem; font-weight:900; margin-bottom:1.25rem; color:var(--ep2-text); display:flex; align-items:center; gap:0.6rem; }
.ep2-related-title i { color:var(--ep2-accent); }
.ep2-related-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(265px, 1fr)); gap:1rem; }
.ep2-related-card { position:relative; display:block; padding:1.2rem; background:var(--ep2-card); border:1px solid var(--ep2-border); border-radius:16px; text-decoration:none; color:inherit; overflow:hidden; transition:transform .2s, border-color .2s, box-shadow .2s; }
.ep2-related-card::before { content:''; position:absolute; inset:0 0 auto 0; height:3px; background:linear-gradient(90deg, var(--ep2-accent), var(--ep2-accent2)); opacity:0; transition:opacity .2s; }
.ep2-related-card:hover { transform:translateY(-3px); border-color:rgba(5,150,105,0.45); box-shadow:0 14px 34px rgba(15,23,42,0.1); }
.ep2-related-card:hover::before { opacity:1; }
.ep2-related-ico { width:36px; height:36px; border-radius:10px; display:grid; place-items:center; background:rgba(5,150,105,0.1); color:var(--ep2-accent); margin-bottom:0.7rem; }
.ep2-related-name { font-weight:700; font-size:0.9rem; line-height:1.45; color:var(--ep2-text); margin-bottom:0.4rem; }
.ep2-related-meta { font-size:0.78rem; color:var(--ep2-muted); }

/* Dark mode */
body.dark-mode .ep2 {
    --ep2-card:rgba(15,23,42,0.78); --ep2-border:rgba(148,163,184,0.16);
    --ep2-text:#f1f5f9; --ep2-muted:#94a3b8; --ep2-soft:#cbd5e1;
    background:
        radial-gradient(900px 500px at 12% -8%, rgba(5,150,105,0.18), transparent 60%),
        radial-gradient(800px 480px at 100% 0%, rgba(8,145,178,0.14), transparent 55%),
        linear-gradient(180deg, #022c22 0%, #0b1120 45%, #0b1120 100%);
}
body.dark-mode .ep2-hero { background:linear-gradient(150deg, rgba(15,23,42,0.85), rgba(2,6,23,0.75)); }
body.dark-mode .ep2-stat { background:rgba(2,6,23,0.5); }
body.dark-mode .ep2-viewer iframe { background:#0b1120; }
</style>
@endsection

@section('content')
<div class="ep2">
    <div class="ep2-wrap">

        {{-- Fil d'ariane --}}
        <nav class="ep2-crumbs" aria-label="Fil d'ariane">
            <a href="{{ route('home') }}">Accueil</a>
            <span class="sep">›</span>
            <a href="{{ route('epreuves.index') }}">Épreuves</a>
            @if($epreuve->exam)
                <span class="sep">›</span>
                <a href="{{ route('epreuves.exam', $epreuve->exam) }}">{{ $epreuve->exam_label }}</a>
            @endif
            <span class="sep">›</span>
            <span class="cur">{{ Str::limit($epreuve->title, 40) }}</span>
        </nav>

        {{-- Hero --}}
        <header class="ep2-hero">
            <div class="ep2-badges">
                @if($epreuve->exam)<span class="ep2-badge ep2-badge--exam">{{ $epreuve->exam_label }}</span>@endif
                @if($epreuve->level)<span class="ep2-badge ep2-badge--exam">{{ $epreuve->level_label }}</span>@endif
                <span class="ep2-badge ep2-badge--type">{{ $epreuve->type_label }}</span>
                @if($isPaidEpreuve)
                    <span class="ep2-badge" style="background:rgba(239,68,68,0.12);color:#b91c1c;"><i class="fas fa-tag" style="margin-right:0.3rem;"></i>{{ $epreuve->price_formatted }}</span>
                @elseif(!$epreuve->isCorrige())
                    <span class="ep2-badge ep2-badge--free">Gratuit</span>
                @endif
            </div>

            <h1 class="ep2-title">{{ $epreuve->title }}</h1>

            <div class="ep2-stats">
                @if($epreuve->matiere)<span class="ep2-stat"><i class="fas fa-book"></i>{{ $epreuve->matiere->name }}</span>@endif
                @if($epreuve->serie)<span class="ep2-stat"><i class="fas fa-layer-group"></i>Série {{ $epreuve->serie }}</span>@endif
                @if($epreuve->year)<span class="ep2-stat"><i class="fas fa-calendar"></i>Session {{ $epreuve->year_label }}</span>@endif
                @if($epreuve->file_size_human)<span class="ep2-stat"><i class="fas fa-file-pdf"></i>{{ $epreuve->file_size_human }}</span>@endif
                <span class="ep2-stat"><i class="fas fa-download"></i>{{ number_format($epreuve->downloads_count, 0, ',', ' ') }} téléch.</span>
            </div>

            @if($epreuve->description)
                <div class="ep2-desc">{!! nl2br(e($epreuve->description)) !!}</div>
            @endif
        </header>

        {{-- Grille principale --}}
        <div class="ep2-grid">

            {{-- Colonne gauche : aperçu PDF --}}
            <main>
                @if($epreuve->isCorrige())
                {{-- Corrigé : pas d'aperçu --}}
                <div class="ep2-card" style="text-align:center; padding:3rem 1.5rem;">
                    <div class="ep2-dl-ico" style="margin:0 auto 1rem;"><i class="fas fa-lock"></i></div>
                    <div class="ep2-dl-h" style="font-size:1.15rem;">Document protégé</div>
                    <p class="ep2-dl-sub" style="margin-top:0.5rem;">Ce corrigé n'est pas consultable en ligne. Il s'obtient via la page de l'épreuve correspondante.</p>
                </div>
                @elseif($isPaidEpreuve)
                {{-- Épreuve payante : aperçu verrouillé --}}
                <div class="ep2-viewer">
                    <div class="ep2-viewer-bar">
                        <span class="dots"><i></i><i></i><i></i></span>
                        <i class="fas fa-lock" style="color:#ef4444;"></i> Contenu verrouillé
                        <span class="grow">Accès payant</span>
                    </div>
                    <div class="ep2-viewer-poster" style="cursor:default;">
                        <div class="ep2-poster-inner">
                            <div class="ep2-poster-ico"><i class="fas fa-lock"></i></div>
                            <div class="ep2-poster-title">Épreuve payante</div>
                            <div class="ep2-poster-sub">L'aperçu et le téléchargement sont réservés aux acheteurs.</div>
                            <span class="ep2-poster-btn" style="background:linear-gradient(135deg,#dc2626,#e11d48);box-shadow:0 8px 22px rgba(220,38,38,0.3);" onclick="document.getElementById('epreuve-pay-modal').classList.add('is-open')"><i class="fas fa-lock-open"></i> Acheter — {{ $epreuve->price_formatted }}</span>
                        </div>
                    </div>
                </div>
                @else
                {{-- Épreuve gratuite : aperçu PDF --}}
                <div class="ep2-viewer">
                    <div class="ep2-viewer-bar">
                        <span class="dots"><i></i><i></i><i></i></span>
                        <i class="fas fa-file-pdf" style="color:#ef4444;"></i> Aperçu du sujet
                        <span class="grow">Lecture seule</span>
                    </div>
                    <div class="ep2-viewer-poster" id="ep2ViewerPoster"
                         data-src="{{ route('epreuves.view', ['id' => $epreuve->id]) }}#toolbar=0&view=FitH"
                         data-title="Aperçu : {{ $epreuve->title }}"
                         role="button" tabindex="0" aria-label="Afficher l'aperçu du PDF">
                        <div class="ep2-poster-inner">
                            <div class="ep2-poster-ico"><i class="fas fa-file-pdf"></i></div>
                            <div class="ep2-poster-title">Afficher l'aperçu du sujet</div>
                            <div class="ep2-poster-sub">{{ $epreuve->matiere?->name }}{{ $epreuve->serie ? ' · Série ' . $epreuve->serie : '' }}{{ $epreuve->year ? ' · ' . $epreuve->year_label : '' }}</div>
                            <span class="ep2-poster-btn"><i class="fas fa-eye"></i> Voir le PDF</span>
                        </div>
                    </div>
                </div>
                @endif
            </main>

            {{-- Colonne droite : actions --}}
            <aside class="ep2-side">

                @unless($epreuve->isCorrige())
                @if($isPaidEpreuve)
                {{-- CTA achat épreuve payante --}}
                <div class="ep2-card">
                    <div class="ep2-dl-head">
                        <div class="ep2-dl-ico" style="background:linear-gradient(135deg,#dc2626,#e11d48);box-shadow:0 6px 18px rgba(220,38,38,0.35);"><i class="fas fa-lock-open"></i></div>
                        <div>
                            <div class="ep2-dl-h">Épreuve payante</div>
                            <div class="ep2-dl-sub">Achetez pour télécharger le PDF</div>
                        </div>
                    </div>
                    <button type="button" class="ep2-dl-btn" style="background:linear-gradient(135deg,#dc2626,#e11d48);box-shadow:0 10px 26px rgba(220,38,38,0.32);"
                            onclick="document.getElementById('epreuve-pay-modal').classList.add('is-open')">
                        <i class="fas fa-lock-open"></i> Acheter — {{ $epreuve->price_formatted }}
                    </button>
                    <div class="ep2-trust">
                        <div><i class="fas fa-check-circle"></i> Paiement Wave sécurisé</div>
                        <div><i class="fas fa-bolt"></i> Lien de téléchargement immédiat</div>
                        <div><i class="fas fa-shield-halved"></i> Document officiel du Sénégal</div>
                    </div>
                </div>
                @else
                {{-- Téléchargement gratuit --}}
                <div class="ep2-card">
                    <div class="ep2-dl-head">
                        <div class="ep2-dl-ico"><i class="fas fa-cloud-arrow-down"></i></div>
                        <div>
                            <div class="ep2-dl-h">Télécharger le sujet</div>
                            <div class="ep2-dl-sub">Format PDF · {{ $epreuve->file_size_human ?? 'léger' }}</div>
                        </div>
                    </div>
                    <a href="{{ $downloadUrl }}" class="ep2-dl-btn" id="epreuveDownloadBtn"
                       @if($showAdGate) data-ad-gate="1" data-download-url="{{ $downloadUrl }}" @endif>
                        <i class="fas fa-download"></i> Télécharger le PDF
                    </a>
                    <div class="ep2-trust">
                        <div><i class="fas fa-check-circle"></i> 100 % gratuit, sans inscription</div>
                        <div><i class="fas fa-bolt"></i> Téléchargement immédiat</div>
                        <div><i class="fas fa-shield-halved"></i> Sujet officiel du Sénégal</div>
                    </div>
                </div>
                @endif
                @endunless

                {{-- CTA corrigé (rouge, attire l'attention) --}}
                @if($epreuve->hasCorrige())
                <div class="corrige-cta" id="corrige" style="margin-bottom:0;">
                    <div class="corrige-cta-icon"><i class="fas fa-circle-check"></i></div>
                    <div class="corrige-cta-body">
                        <div class="corrige-cta-title">Le corrigé est disponible</div>
                        <p class="corrige-cta-text">Correction détaillée en PDF, reçue immédiatement par e-mail ou WhatsApp.</p>
                    </div>
                    <div class="corrige-cta-action">
                        <div class="corrige-cta-price">{{ number_format($corrigePrice, 0, ',', ' ') }} <span>FCFA</span></div>
                        <button type="button" class="corrige-cta-btn" onclick="document.getElementById('corrige-modal').classList.add('is-open')">
                            <i class="fas fa-lock-open"></i> Débloquer le corrigé
                        </button>
                    </div>
                </div>
                @endif

                {{-- Fiche infos --}}
                <div class="ep2-card">
                    <div class="ep2-info-title">Détails du document</div>
                    @if($epreuve->exam_label || $epreuve->level_label)
                        <div class="ep2-info-row"><span>{{ $epreuve->exam ? 'Examen' : 'Classe' }}</span><span>{{ $epreuve->exam_label ?? $epreuve->level_label }}</span></div>
                    @endif
                    @if($epreuve->matiere)<div class="ep2-info-row"><span>Matière</span><span>{{ $epreuve->matiere->name }}</span></div>@endif
                    @if($epreuve->serie)<div class="ep2-info-row"><span>Série</span><span>{{ $epreuve->serie }}</span></div>@endif
                    @if($epreuve->year)<div class="ep2-info-row"><span>Session</span><span>{{ $epreuve->year_label }}</span></div>@endif
                    <div class="ep2-info-row"><span>Type</span><span>{{ $epreuve->type_label }}</span></div>
                    <div class="ep2-info-row"><span>Téléchargements</span><span>{{ number_format($epreuve->downloads_count, 0, ',', ' ') }}</span></div>
                </div>

            </aside>
        </div>

        {{-- Documents similaires --}}
        @if($related->isNotEmpty())
        <section class="ep2-related">
            <h2 class="ep2-related-title"><i class="fas fa-layer-group"></i> Documents similaires</h2>
            <div class="ep2-related-grid">
                @foreach($related as $rel)
                    <a href="{{ route('epreuves.show', $rel->slug) }}" class="ep2-related-card">
                        <div class="ep2-related-ico"><i class="fas fa-file-pdf"></i></div>
                        <div class="ep2-related-name">{{ $rel->title }}</div>
                        <div class="ep2-related-meta">
                            {{ $rel->matiere?->name }}{{ $rel->year ? ' · ' . $rel->year_label : '' }}{{ $rel->serie ? ' · Série ' . $rel->serie : '' }}
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
        @endif

    </div>
</div>

{{-- Modale d'achat du corrigé --}}
@if($epreuve->hasCorrige())
<div class="corrige-modal" id="corrige-modal">
    <div class="corrige-modal-backdrop" onclick="document.getElementById('corrige-modal').classList.remove('is-open')"></div>
    <div class="corrige-modal-box" role="dialog" aria-modal="true" aria-labelledby="corrige-modal-title">
        <button type="button" class="corrige-modal-close" aria-label="Fermer" onclick="document.getElementById('corrige-modal').classList.remove('is-open')">&times;</button>
        <div class="corrige-modal-head">
            <div class="corrige-modal-icon"><i class="fas fa-circle-check"></i></div>
            <h2 class="corrige-modal-title" id="corrige-modal-title">Débloquer le corrigé</h2>
            <p class="corrige-modal-sub">{{ \Illuminate\Support\Str::limit($epreuve->title, 70) }}</p>
            <div class="corrige-modal-price">{{ number_format($corrigePrice, 0, ',', ' ') }} FCFA · paiement Wave</div>
        </div>

        @if($errors->any())
            <div class="corrige-modal-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('epreuves.corrige.checkout', ['id' => $epreuve->id]) }}" class="corrige-modal-form">
            @csrf
            <label class="corrige-field">
                <span>Votre nom (optionnel)</span>
                <input type="text" name="customer_name" value="{{ old('customer_name') }}" placeholder="Ex : Awa Diop">
            </label>
            <p class="corrige-modal-hint">Indiquez un e-mail <strong>ou</strong> un numéro WhatsApp pour recevoir le corrigé :</p>
            <label class="corrige-field">
                <span><i class="fas fa-envelope"></i> E-mail</span>
                <input type="email" name="customer_email" value="{{ old('customer_email') }}" placeholder="vous@exemple.com">
            </label>
            <div class="corrige-field-phone">
                <label class="corrige-field corrige-field--cc">
                    <span>Indicatif</span>
                    <input type="text" name="country_code" value="{{ old('country_code', '+221') }}" placeholder="+221">
                </label>
                <label class="corrige-field" style="flex:1;">
                    <span><i class="fab fa-whatsapp"></i> Téléphone</span>
                    <input type="tel" name="customer_phone" value="{{ old('customer_phone') }}" placeholder="77 123 45 67">
                </label>
            </div>
            <button type="submit" class="corrige-modal-submit">
                <i class="fas fa-lock-open"></i> Payer {{ number_format($corrigePrice, 0, ',', ' ') }} FCFA avec Wave
            </button>
            <p class="corrige-modal-secure"><i class="fas fa-shield-halved"></i> Paiement sécurisé · lien valable 30 jours</p>
        </form>
    </div>
</div>
@endif

{{-- Modale d'achat de l'épreuve payante --}}
@if($isPaidEpreuve)
<div class="corrige-modal" id="epreuve-pay-modal">
    <div class="corrige-modal-backdrop" onclick="document.getElementById('epreuve-pay-modal').classList.remove('is-open')"></div>
    <div class="corrige-modal-box" role="dialog" aria-modal="true" aria-labelledby="epreuve-pay-modal-title">
        <button type="button" class="corrige-modal-close" aria-label="Fermer" onclick="document.getElementById('epreuve-pay-modal').classList.remove('is-open')">&times;</button>
        <div class="corrige-modal-head">
            <div class="corrige-modal-icon" style="background:rgba(220,38,38,0.12);color:#dc2626;"><i class="fas fa-lock-open"></i></div>
            <h2 class="corrige-modal-title" id="epreuve-pay-modal-title">Acheter cette épreuve</h2>
            <p class="corrige-modal-sub">{{ \Illuminate\Support\Str::limit($epreuve->title, 70) }}</p>
            <div class="corrige-modal-price" style="background:rgba(220,38,38,0.1);color:#b91c1c;">{{ $epreuve->price_formatted }} · paiement Wave</div>
        </div>

        @if($errors->hasBag('epreuve_pay') || session('paywall'))
            <div class="corrige-modal-error">{{ session('paywall') ?? $errors->getBag('epreuve_pay')->first() }}</div>
        @endif

        <form method="POST" action="{{ route('epreuves.pay.checkout', ['id' => $epreuve->id]) }}" class="corrige-modal-form">
            @csrf
            <label class="corrige-field">
                <span>Votre nom (optionnel)</span>
                <input type="text" name="customer_name" value="{{ old('customer_name') }}" placeholder="Ex : Awa Diop">
            </label>
            <p class="corrige-modal-hint">Indiquez un e-mail <strong>ou</strong> un numéro WhatsApp pour recevoir le lien de téléchargement :</p>
            <label class="corrige-field">
                <span><i class="fas fa-envelope"></i> E-mail</span>
                <input type="email" name="customer_email" value="{{ old('customer_email') }}" placeholder="vous@exemple.com">
            </label>
            <div class="corrige-field-phone">
                <label class="corrige-field corrige-field--cc">
                    <span>Indicatif</span>
                    <input type="text" name="country_code" value="{{ old('country_code', '+221') }}" placeholder="+221">
                </label>
                <label class="corrige-field" style="flex:1;">
                    <span><i class="fab fa-whatsapp"></i> Téléphone</span>
                    <input type="tel" name="customer_phone" value="{{ old('customer_phone') }}" placeholder="77 123 45 67">
                </label>
            </div>
            <button type="submit" class="corrige-modal-submit" style="background:linear-gradient(135deg,#dc2626,#e11d48);">
                <i class="fas fa-lock-open"></i> Payer {{ $epreuve->price_formatted }} avec Wave
            </button>
            <p class="corrige-modal-secure"><i class="fas fa-shield-halved"></i> Paiement sécurisé · lien valable 30 jours</p>
        </form>
    </div>
</div>
@endif

{{-- Interstitiel publicitaire avant le téléchargement du PDF --}}
@if($showAdGate)
<div class="dl-ad-modal" id="dlAdModal" aria-hidden="true">
    <div class="dl-ad-backdrop" id="dlAdBackdrop"></div>
    <div class="dl-ad-box" role="dialog" aria-modal="true" aria-label="Préparation du téléchargement">
        <button type="button" class="dl-ad-close" id="dlAdClose" aria-label="Fermer">&times;</button>
        <div class="dl-ad-head">
            <div class="dl-ad-title"><i class="fas fa-download"></i> Votre téléchargement est en préparation…</div>
            <div class="dl-ad-sub">Le bouton apparaît dans <span id="dlAdCount">5</span> s</div>
        </div>

        <div class="dl-ad-label">Publicité</div>
        <div class="dl-ad-slot">
            <ins class="adsbygoogle"
                 style="display:block; min-height:250px;"
                 data-ad-client="{{ $adsClientId }}"
                 data-ad-slot="{{ $adsSlot }}"
                 data-ad-format="auto"
                 data-full-width-responsive="true"></ins>
        </div>

        <a href="{{ $downloadUrl }}" class="dl-ad-go is-waiting" id="dlAdGo" aria-disabled="true">
            <span class="dl-ad-go-wait"><i class="fas fa-hourglass-half"></i> Patientez…</span>
            <span class="dl-ad-go-ready"><i class="fas fa-download"></i> Télécharger maintenant</span>
        </a>
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
/* CTA corrigé */
.corrige-cta {
    display: flex; flex-wrap: wrap; align-items: center; gap: 1.1rem;
    background: linear-gradient(135deg, #fef2f2, #fff1f2);
    border: 1px solid rgba(220,38,38,0.3); border-radius: 18px;
    padding: 1.35rem; margin-bottom: 1.5rem;
}
.corrige-cta-icon { width: 50px; height: 50px; flex-shrink: 0; border-radius: 14px; display: grid; place-items: center; font-size: 1.4rem; background: rgba(220,38,38,0.15); color: #dc2626; }
.corrige-cta-body { flex: 1; min-width: 180px; }
.corrige-cta-title { font-weight: 800; font-size: 1.05rem; color: #0f172a; margin-bottom: 0.25rem; }
.corrige-cta-text { color: #475569; font-size: 0.85rem; line-height: 1.5; margin: 0; }
.corrige-cta-action { text-align: center; width:100%; }
.corrige-cta-price { font-size: 1.35rem; font-weight: 800; color: #dc2626; margin-bottom: 0.5rem; }
.corrige-cta-price span { font-size: 0.85rem; font-weight: 600; }
.corrige-cta-btn { display: inline-flex; align-items: center; justify-content:center; gap: 0.5rem; width:100%; padding: 0.8rem 1.4rem; border: none; border-radius: 12px; cursor: pointer; font-weight: 800; color: #fff; background: linear-gradient(135deg, #dc2626, #e11d48); box-shadow: 0 8px 22px rgba(220,38,38,0.35); transition: transform 0.18s; }
.corrige-cta-btn:hover { transform: translateY(-2px); }

/* Modale corrigé */
.corrige-modal { position: fixed; inset: 0; z-index: 1000; display: none; align-items: center; justify-content: center; padding: 1rem; }
.corrige-modal.is-open { display: flex; }
.corrige-modal-backdrop { position: absolute; inset: 0; background: rgba(2,6,23,0.6); backdrop-filter: blur(3px); }
.corrige-modal-box { position: relative; width: 100%; max-width: 440px; background: #fff; border-radius: 20px; padding: 1.75rem; box-shadow: 0 30px 70px rgba(2,6,23,0.4); max-height: 92vh; overflow-y: auto; animation: corrige-pop 0.22s ease; }
@keyframes corrige-pop { from { opacity: 0; transform: scale(0.96) translateY(10px); } to { opacity: 1; transform: none; } }
.corrige-modal-close { position: absolute; top: 0.85rem; right: 1.1rem; background: none; border: none; font-size: 1.6rem; line-height: 1; color: #94a3b8; cursor: pointer; }
.corrige-modal-head { text-align: center; margin-bottom: 1.25rem; }
.corrige-modal-icon { width: 56px; height: 56px; margin: 0 auto 0.75rem; border-radius: 16px; display: grid; place-items: center; font-size: 1.6rem; background: rgba(5,150,105,0.12); color: #059669; }
.corrige-modal-title { font-size: 1.3rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
.corrige-modal-sub { color: #64748b; font-size: 0.85rem; margin: 0 0 0.6rem; }
.corrige-modal-price { display: inline-block; font-weight: 700; font-size: 0.85rem; color: #047857; background: rgba(5,150,105,0.1); padding: 0.3rem 0.85rem; border-radius: 999px; }
.corrige-modal-error { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.35); color: #b91c1c; border-radius: 10px; padding: 0.7rem 0.9rem; font-size: 0.85rem; margin-bottom: 1rem; }
.corrige-modal-hint { font-size: 0.82rem; color: #64748b; margin: 0.25rem 0 0.75rem; }
.corrige-field { display: block; margin-bottom: 0.85rem; }
.corrige-field > span { display: block; font-size: 0.78rem; font-weight: 600; color: #475569; margin-bottom: 0.35rem; }
.corrige-field input { width: 100%; padding: 0.7rem 0.9rem; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.92rem; color: #0f172a; }
.corrige-field input:focus { outline: none; border-color: #059669; box-shadow: 0 0 0 3px rgba(5,150,105,0.12); }
.corrige-field-phone { display: flex; gap: 0.6rem; }
.corrige-field--cc { width: 90px; }
.corrige-modal-submit { width: 100%; margin-top: 0.4rem; padding: 0.9rem; border: none; border-radius: 12px; cursor: pointer; font-weight: 800; color: #fff; background: linear-gradient(135deg, #059669, #0891b2); box-shadow: 0 10px 26px rgba(5,150,105,0.3); }
.corrige-modal-secure { text-align: center; font-size: 0.78rem; color: #94a3b8; margin: 0.85rem 0 0; }

body.dark-mode .corrige-cta { background: linear-gradient(135deg, rgba(220,38,38,0.14), rgba(225,29,72,0.1)); border-color: rgba(248,113,113,0.3); }
body.dark-mode .corrige-cta-title { color: #f1f5f9; } body.dark-mode .corrige-cta-text { color: #94a3b8; }
body.dark-mode .corrige-modal-box { background: #0f172a; }
body.dark-mode .corrige-modal-title { color: #f1f5f9; }
body.dark-mode .corrige-field input { background: rgba(2,6,23,0.6); border-color: rgba(148,163,184,0.25); color: #e2e8f0; }
body.dark-mode .corrige-field > span { color: #94a3b8; }

/* Interstitiel pub avant téléchargement */
.dl-ad-modal { position: fixed; inset: 0; z-index: 1000; display: none; align-items: center; justify-content: center; padding: 1rem; }
.dl-ad-modal.is-open { display: flex; }
.dl-ad-backdrop { position: absolute; inset: 0; background: rgba(2,6,23,0.65); backdrop-filter: blur(3px); }
.dl-ad-box { position: relative; width: 100%; max-width: 480px; background: #fff; border-radius: 20px; padding: 1.5rem; box-shadow: 0 30px 70px rgba(2,6,23,0.45); animation: corrige-pop 0.22s ease; }
.dl-ad-close { position: absolute; top: 0.7rem; right: 1rem; background: none; border: none; font-size: 1.6rem; line-height: 1; color: #94a3b8; cursor: pointer; }
.dl-ad-head { text-align: center; margin-bottom: 1rem; }
.dl-ad-title { font-weight: 800; font-size: 1.05rem; color: #0f172a; }
.dl-ad-sub { font-size: 0.85rem; color: #64748b; margin-top: 0.25rem; }
.dl-ad-sub span { font-weight: 700; color: #059669; }
.dl-ad-label { font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.06em; color: #94a3b8; text-align: center; margin-bottom: 0.4rem; }
.dl-ad-slot { min-height: 250px; display: flex; align-items: center; justify-content: center; background: #f8fafc; border: 1px solid #eef2f7; border-radius: 12px; overflow: hidden; }
.dl-ad-go { display: flex; align-items: center; justify-content: center; gap: 0.55rem; margin-top: 1.1rem; padding: 0.9rem; border-radius: 14px; font-weight: 800; text-decoration: none; transition: all 0.2s; }
.dl-ad-go.is-waiting { background: #e2e8f0; color: #94a3b8; cursor: not-allowed; pointer-events: none; }
.dl-ad-go.is-ready { background: linear-gradient(135deg, #059669, #0891b2); color: #fff; box-shadow: 0 10px 26px rgba(5,150,105,0.3); cursor: pointer; }
.dl-ad-go .dl-ad-go-ready { display: none; }
.dl-ad-go.is-ready .dl-ad-go-wait { display: none; }
.dl-ad-go.is-ready .dl-ad-go-ready { display: inline-flex; align-items: center; gap: 0.55rem; }
body.dark-mode .dl-ad-box { background: #0f172a; }
body.dark-mode .dl-ad-title { color: #f1f5f9; }
body.dark-mode .dl-ad-slot { background: rgba(2,6,23,0.5); border-color: rgba(148,163,184,0.15); }
body.dark-mode .dl-ad-go.is-waiting { background: rgba(148,163,184,0.2); color: #64748b; }
</style>
@endpush

@push('scripts')
<script>
    // Aperçu PDF chargé à la demande : injecte l'iframe au clic sur le poster.
    // Évite les requêtes de plage (range) du lecteur PDF à chaque visite → moins
    // de risque de 429 côté hébergeur et page plus rapide.
    document.addEventListener('DOMContentLoaded', function () {
        var poster = document.getElementById('ep2ViewerPoster');
        if (!poster) return;
        function loadPreview() {
            var iframe = document.createElement('iframe');
            iframe.src = poster.dataset.src;
            iframe.title = poster.dataset.title || 'Aperçu du PDF';
            iframe.loading = 'lazy';
            poster.parentNode.replaceChild(iframe, poster);
        }
        poster.addEventListener('click', loadPreview);
        poster.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); loadPreview(); }
        });
    });

    // Rouvrir les modales si validation échouée / paywall, et fermer avec Échap
    document.addEventListener('DOMContentLoaded', function () {
        var corrigeModal = document.getElementById('corrige-modal');
        var payModal = document.getElementById('epreuve-pay-modal');

        if (corrigeModal) {
            @if($errors->any() && !session('paywall'))
                corrigeModal.classList.add('is-open');
            @endif
        }
        if (payModal) {
            @if(session('paywall'))
                payModal.classList.add('is-open');
            @endif
        }
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                if (corrigeModal) corrigeModal.classList.remove('is-open');
                if (payModal) payModal.classList.remove('is-open');
            }
        });
    });

    // Interstitiel publicitaire avant le téléchargement du PDF
    document.addEventListener('DOMContentLoaded', function () {
        var btn = document.getElementById('epreuveDownloadBtn');
        var adModal = document.getElementById('dlAdModal');
        if (!btn || !adModal || !btn.dataset.adGate) return; // pas de pub → lien direct

        var goBtn = document.getElementById('dlAdGo');
        var countEl = document.getElementById('dlAdCount');
        var countWrap = countEl ? countEl.parentElement : null;
        var closeBtn = document.getElementById('dlAdClose');
        var backdrop = document.getElementById('dlAdBackdrop');
        var COUNTDOWN = 5;
        var adPushed = false;
        var timer = null;

        function closeModal() {
            adModal.classList.remove('is-open');
            adModal.setAttribute('aria-hidden', 'true');
            if (timer) { clearInterval(timer); timer = null; }
        }

        function openModal() {
            adModal.classList.add('is-open');
            adModal.setAttribute('aria-hidden', 'false');

            if (!adPushed) {
                adPushed = true;
                try { (window.adsbygoogle = window.adsbygoogle || []).push({}); } catch (e) {}
            }

            var remaining = COUNTDOWN;
            countEl.textContent = remaining;
            goBtn.classList.add('is-waiting');
            goBtn.classList.remove('is-ready');
            goBtn.setAttribute('aria-disabled', 'true');

            timer = setInterval(function () {
                remaining -= 1;
                if (remaining <= 0) {
                    clearInterval(timer); timer = null;
                    if (countWrap) countWrap.textContent = 'Votre fichier est prêt';
                    goBtn.classList.remove('is-waiting');
                    goBtn.classList.add('is-ready');
                    goBtn.removeAttribute('aria-disabled');
                } else {
                    countEl.textContent = remaining;
                }
            }, 1000);
        }

        btn.addEventListener('click', function (e) {
            e.preventDefault();
            openModal();
        });

        goBtn.addEventListener('click', function (e) {
            if (goBtn.classList.contains('is-waiting')) { e.preventDefault(); return; }
            setTimeout(closeModal, 400);
        });

        if (closeBtn) closeBtn.addEventListener('click', closeModal);
        if (backdrop) backdrop.addEventListener('click', closeModal);
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeModal();
        });
    });
</script>
@endpush
