@once
@push('styles')
<style>
.doc-rating-row { display: flex; align-items: center; flex-wrap: wrap; gap: .35rem; margin: .25rem 0 .5rem; }
.doc-rating-stars { color: #f59e0b; font-size: .85rem; }
.doc-rating-val { font-weight: 800; font-size: .85rem; }
.doc-rating-count { font-size: .75rem; color: #64748b; }
</style>
@endpush
@endonce
<div class="document-card">
    <!-- Image wrapper -->
    <div class="document-cover-wrapper">
        @if($document->hasDiscount())
            <div class="document-discount-badge">-{{ number_format($document->getDiscountPercentage(), 0) }}%</div>
        @endif
        <a href="{{ route('documents.show', $document->slug) }}">
            @if($document->cover_image)
                @if($document->cover_type === 'internal')
                    <img src="{{ \Illuminate\Support\Facades\URL::temporarySignedRoute('document.cover.signed', now()->addHours(24), ['id' => $document->id]) }}" alt="{{ $document->title }}" class="document-cover" width="280" height="200" loading="lazy" decoding="async">
                @else
                    <img src="{{ $document->cover_image }}" alt="{{ $document->title }}" class="document-cover" width="280" height="200" loading="lazy" decoding="async">
                @endif
            @else
                <div class="document-cover-placeholder">
                    <i class="fas fa-file-{{ $document->file_extension === 'pdf' ? 'pdf' : ($document->file_extension === 'doc' || $document->file_extension === 'docx' ? 'word' : 'alt') }}"></i>
                </div>
            @endif
        </a>
        
        <!-- Prix scotché sur l'image -->
        <div class="document-price-overlay">
            @if($document->hasDiscount())
                <span class="document-price-old">{{ number_format($document->price, 0, ',', ' ') }} FCFA</span>
            @endif
            <span class="document-price-current">{{ number_format($document->hasDiscount() ? $document->discount_price : $document->price, 0, ',', ' ') }} FCFA</span>
        </div>
    </div>
    
    <div class="document-card-body">
        <!-- Titre en haut comme Prepa.sn -->
        <h3 class="document-title">
            <a href="{{ route('documents.show', $document->slug) }}">
                {{ $document->title }}
            </a>
        </h3>
        
        <!-- Description -->
        @if($document->excerpt)
            <p class="document-excerpt">{{ $document->excerpt }}</p>
        @endif

        <!-- Preuve sociale : note moyenne et ventes -->
        @if($document->reviews_count > 0 || ($document->sales_count ?? 0) > 0)
        <div class="doc-rating-row">
            @if($document->reviews_count > 0)
            <span class="doc-rating-stars">
                @for($i = 1; $i <= 5; $i++)
                    <i class="fas fa-star{{ $i <= round($document->average_rating) ? '' : '-o' }}"></i>
                @endfor
            </span>
            <span class="doc-rating-val">{{ number_format($document->average_rating, 1) }}</span>
            <span class="doc-rating-count">({{ $document->reviews_count }})</span>
            @endif
            @if(($document->sales_count ?? 0) > 0)
            <span class="doc-rating-count">
                <i class="fas fa-download"></i> {{ number_format($document->sales_count, 0, ',', ' ') }} ventes
            </span>
            @endif
        </div>
        @endif

        <!-- Footer avec catégorie et bouton -->
        <div class="document-footer">
            <div class="document-category">{{ $document->category->name }}</div>
            <a href="{{ route('documents.show', $document->slug) }}" class="document-btn" title="Voir les détails">
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

