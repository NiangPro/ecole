@extends('layouts.app')

@section('title', $epreuve->title . ' - PDF gratuit - NiangProgrammeur')
@section('meta_description', ($epreuve->description ? Str::limit(strip_tags($epreuve->description), 150) : $epreuve->title . ' à télécharger gratuitement en PDF.' . ($epreuve->matiere ? ' ' . $epreuve->matiere->name . '.' : '') . ($epreuve->year ? ' Session ' . $epreuve->year_label . '.' : '') . ' Examens du Sénégal.'))

@php
    // $adsSettings et $adsUnit sont passées (et cachées) depuis le contrôleur
    $adsClientId = null;
    if ($adsSettings && $adsSettings->adsense_code && preg_match('/ca-pub-([0-9]+)/', $adsSettings->adsense_code, $cm)) {
        $adsClientId = 'ca-pub-' . $cm[1];
    }
    $adsSlot = $adsUnit?->ad_slot;
    $downloadUrl = route('epreuves.download', ['id' => $epreuve->id]);
    $isPaidEpreuve = !$epreuve->isFree() && !$epreuve->isCorrige();
    $showAdGate = !$epreuve->isCorrige() && !$isPaidEpreuve && $adsClientId && $adsSlot;
    $corrigePrice = $epreuve->hasCorrige() ? $epreuve->getCorrigePrice() : null;

    $breadcrumbs = [
        ['name' => 'Accueil', 'url' => route('home')],
        ['name' => 'Épreuves', 'url' => route('epreuves.index')],
    ];
    if ($epreuve->exam) {
        $breadcrumbs[] = ['name' => $epreuve->exam_label, 'url' => route('epreuves.exam', $epreuve->exam)];
    }
    $breadcrumbs[] = ['name' => $epreuve->title, 'url' => url()->current()];
@endphp

@push('page_css')
@vite('resources/css/features/epreuves-show.css')
@endpush

@push('styles')
@include('epreuves.partials.documents-carousel-styles')
@endpush

@push('scripts')
@include('epreuves.partials.documents-carousel-script')
@endpush


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

        @include('epreuves.partials.documents-carousel')

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

        {{-- Avis --}}
        <section class="ep2-reviews">
            <h2 class="ep2-reviews-title">
                <i class="fas fa-star"></i>
                Avis
                @if(isset($reviews) && $reviews->total() > 0)
                    ({{ $reviews->total() }})
                @endif
            </h2>

            @if(isset($reviews) && $reviews->count() > 0)
                @foreach($reviews as $review)
                    <div class="ep2-review-item">
                        <div class="ep2-review-head">
                            <span class="ep2-review-author">{{ $review->display_name }}</span>
                            <span class="ep2-review-date">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="ep2-review-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                            @endfor
                        </div>
                        @if($review->comment)
                            <p class="ep2-review-comment">{{ $review->comment }}</p>
                        @endif
                    </div>
                @endforeach
                <div style="margin-top: 1rem;">
                    {{ $reviews->links() }}
                </div>
            @else
                <p class="ep2-reviews-empty">Aucun avis pour le moment. Soyez le premier à donner votre avis !</p>
            @endif

            @auth
            <form action="{{ route('epreuves.reviews.store', $epreuve->id) }}" method="POST" class="ep2-review-form">
                @csrf
                <p class="ep2-review-form-title">Laisser un avis</p>

                <div class="ep2-star-rating" data-rating="0">
                    @for($i = 1; $i <= 5; $i++)
                        <label class="ep2-rating-star" data-rating="{{ $i }}" data-for="ep2-rating-star-{{ $i }}">
                            <i class="far fa-star"></i>
                        </label>
                        <input type="radio" class="ep2-rating-radio" id="ep2-rating-star-{{ $i }}" name="rating" value="{{ $i }}" required>
                    @endfor
                </div>

                <textarea name="comment" placeholder="Votre commentaire (facultatif)"></textarea>

                <button type="submit" class="ep2-review-submit">
                    <i class="fas fa-paper-plane"></i> Envoyer mon avis
                </button>
            </form>
            @else
            <div class="ep2-review-form" style="text-align:center;">
                <p class="ep2-review-form-title" style="margin-bottom:.5rem;">Connectez-vous pour laisser un avis</p>
                <a href="{{ route('login') }}" class="ep2-review-submit">
                    <i class="fas fa-sign-in-alt"></i> Se connecter
                </a>
            </div>
            @endauth
        </section>

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
            <div class="corrige-modal-price">{{ number_format($corrigePrice, 0, ',', ' ') }} FCFA</div>
        </div>

        @if($errors->any())
            <div class="corrige-modal-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('epreuves.corrige.checkout', ['id' => $epreuve->id]) }}" class="corrige-modal-form">
            @csrf
            @if(\App\Models\SiteSetting::get('orange_money_enabled'))
            <p class="corrige-modal-hint">Moyen de paiement :</p>
            <div style="display:flex;gap:1rem;margin-bottom:1rem;">
                <label style="display:flex;align-items:center;gap:.4rem;">
                    <input type="radio" name="payment_method" value="wave" checked> Wave
                </label>
                <label style="display:flex;align-items:center;gap:.4rem;">
                    <input type="radio" name="payment_method" value="orange_money"> Orange Money
                </label>
            </div>
            @else
            <input type="hidden" name="payment_method" value="wave">
            @endif
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
                <i class="fas fa-lock-open"></i> Payer {{ number_format($corrigePrice, 0, ',', ' ') }} FCFA
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
            <div class="corrige-modal-price" style="background:rgba(220,38,38,0.1);color:#b91c1c;">{{ $epreuve->price_formatted }}</div>
        </div>

        @if($errors->hasBag('epreuve_pay') || session('paywall'))
            <div class="corrige-modal-error">{{ session('paywall') ?? $errors->getBag('epreuve_pay')->first() }}</div>
        @endif

        <form method="POST" action="{{ route('epreuves.pay.checkout', ['id' => $epreuve->id]) }}" class="corrige-modal-form">
            @csrf
            @if(\App\Models\SiteSetting::get('orange_money_enabled'))
            <p class="corrige-modal-hint">Moyen de paiement :</p>
            <div style="display:flex;gap:1rem;margin-bottom:1rem;">
                <label style="display:flex;align-items:center;gap:.4rem;">
                    <input type="radio" name="payment_method" value="wave" checked> Wave
                </label>
                <label style="display:flex;align-items:center;gap:.4rem;">
                    <input type="radio" name="payment_method" value="orange_money"> Orange Money
                </label>
            </div>
            @else
            <input type="hidden" name="payment_method" value="wave">
            @endif
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
                <i class="fas fa-lock-open"></i> Payer {{ $epreuve->price_formatted }}
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

    // Toast pour les erreurs de téléchargement (flash session)
    @if(session('download_error'))
    document.addEventListener('DOMContentLoaded', function () {
        var toast = document.createElement('div');
        toast.className = 'dl-rate-alert';
        toast.innerHTML = '<i class="fas fa-clock"></i><span>' + {!! json_encode(session('download_error')) !!} + '</span>';
        document.body.appendChild(toast);
        requestAnimationFrame(function () {
            requestAnimationFrame(function () { toast.classList.add('is-visible'); });
        });
        setTimeout(function () {
            toast.classList.remove('is-visible');
            setTimeout(function () { if (toast.parentNode) toast.parentNode.removeChild(toast); }, 450);
        }, 7000);
    });
    @endif

    // Interstitiel publicitaire avant le téléchargement du PDF
    document.addEventListener('DOMContentLoaded', function () {
        var btn = document.getElementById('epreuveDownloadBtn');
        var adModal = document.getElementById('dlAdModal');
        if (!btn || !adModal || !btn.dataset.adGate) return;

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

    // Système de notation étoiles (avis)
    (function() {
        function initEp2StarRating() {
            document.querySelectorAll('.ep2-star-rating').forEach(function(container) {
                if (container.dataset.initialized === 'true') return;
                container.dataset.initialized = 'true';
                const stars = container.querySelectorAll('.ep2-rating-star');
                const radios = container.querySelectorAll('.ep2-rating-radio');
                if (!stars.length || !radios.length) return;
                let selectedRating = 0, hoverRating = 0;
                function updateDisplay() {
                    const r = hoverRating || selectedRating;
                    stars.forEach(function(star) {
                        const v = parseInt(star.dataset.rating);
                        const icon = star.querySelector('i');
                        star.style.color = v <= r ? '#f59e0b' : '#e2e8f0';
                        star.style.transform = v <= r ? 'scale(1.15)' : 'scale(1)';
                        if (icon) icon.className = v <= r ? 'fas fa-star' : 'far fa-star';
                    });
                }
                stars.forEach(function(star) {
                    const v = parseInt(star.dataset.rating);
                    const radio = document.getElementById(star.dataset.for);
                    if (!radio) return;
                    star.addEventListener('click', function(e) {
                        e.preventDefault(); e.stopPropagation();
                        radio.checked = true; selectedRating = v; hoverRating = 0;
                        container.dataset.rating = selectedRating; updateDisplay();
                        radio.dispatchEvent(new Event('change', { bubbles: true }));
                    });
                    star.addEventListener('mouseenter', function() { hoverRating = v; updateDisplay(); });
                    star.addEventListener('mouseleave', function() { hoverRating = 0; updateDisplay(); });
                });
                radios.forEach(function(radio) {
                    radio.addEventListener('change', function() {
                        selectedRating = parseInt(this.value); hoverRating = 0;
                        container.dataset.rating = selectedRating; updateDisplay();
                    });
                });
                updateDisplay();
            });
        }
        if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initEp2StarRating);
        else initEp2StarRating();
        setTimeout(initEp2StarRating, 500);
    })();
</script>
@endpush
