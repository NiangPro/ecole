{{-- Carousel "Documents & Guides Épreuves" — partagé entre epreuves.index et epreuves.show --}}
@isset($epreuveDocuments)
@if($epreuveDocuments->isNotEmpty())
<section class="epr-doc-section" aria-label="Documents et guides pour les épreuves">
    <div class="epr-doc-header">
        <div>
            <span class="epr-doc-eyebrow"><i class="fas fa-bolt"></i> Ressources premium</span>
            <h2 class="epr-doc-title">Documents &amp; guides pour réussir vos épreuves</h2>
            <p class="epr-doc-subtitle">Fiches de révision, méthodologies et guides pratiques classés dans la catégorie Épreuves.</p>
        </div>
        <div class="epr-doc-actions">
            <button type="button" class="epr-doc-nav" id="eprDocPrev" aria-label="Précédent"><i class="fas fa-chevron-left"></i></button>
            <button type="button" class="epr-doc-nav" id="eprDocNext" aria-label="Suivant"><i class="fas fa-chevron-right"></i></button>
            <a href="{{ route('documents.category', 'epreuves') }}" class="epr-doc-viewall">
                Voir tout
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>

    <div class="epr-doc-viewport" id="eprDocViewport">
        @foreach($epreuveDocuments as $doc)
        <a href="{{ route('documents.show', $doc->slug) }}" class="epr-doc-card">
            <div class="epr-doc-cover">
                @if($doc->cover_image)
                    @if($doc->cover_type === 'internal')
                        <img src="{{ \Illuminate\Support\Facades\URL::temporarySignedRoute('document.cover.signed', now()->addHours(24), ['id' => $doc->id]) }}"
                             alt="{{ $doc->title }}" width="265" height="199" loading="lazy" decoding="async">
                    @else
                        <img src="{{ $doc->cover_image }}" alt="{{ $doc->title }}" width="265" height="199" loading="lazy" decoding="async">
                    @endif
                @else
                    <div class="epr-doc-placeholder">📘</div>
                @endif

                @if($doc->is_featured)
                    <span class="epr-doc-badge"><i class="fas fa-star"></i> Vedette</span>
                @endif

                <div class="epr-doc-price">
                    @if($doc->price && $doc->price > 0)
                        <span>{{ number_format($doc->discount_price ?: $doc->price, 0, ',', ' ') }} FCFA</span>
                        @if($doc->discount_price && $doc->discount_price < $doc->price)
                            <span class="epr-doc-price-old">{{ number_format($doc->price, 0, ',', ' ') }}</span>
                        @endif
                    @else
                        <span>Gratuit</span>
                    @endif
                </div>
            </div>

            <div class="epr-doc-body">
                <span class="epr-doc-cat">{{ $doc->category?->name ?? 'Épreuves' }}</span>
                <span class="epr-doc-name">{{ $doc->title }}</span>
                <div class="epr-doc-meta">
                    @if(($doc->reviews_count ?? 0) > 0)
                        <span class="epr-doc-stars">
                            @for($i = 1; $i <= 5; $i++)<i class="fas fa-star{{ $i <= round($doc->average_rating) ? '' : '-o' }}"></i>@endfor
                        </span>
                    @endif
                    <span><i class="fas fa-download"></i> {{ number_format($doc->download_count, 0, ',', ' ') }}</span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</section>
@endif
@endisset
