<div class="document-card">
    <!-- Image wrapper -->
    <div class="document-cover-wrapper">
        @if($document->hasDiscount())
            <div class="document-discount-badge">-{{ number_format($document->getDiscountPercentage(), 0) }}%</div>
        @endif
        <a href="{{ route('documents.show', $document->slug) }}">
            @if($document->cover_image)
                @if($document->cover_type === 'internal')
                    <img src="{{ asset('storage/' . $document->cover_image) }}" alt="{{ $document->title }}" class="document-cover">
                @else
                    <img src="{{ $document->cover_image }}" alt="{{ $document->title }}" class="document-cover">
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
        
        <!-- Footer avec catégorie et bouton -->
        <div class="document-footer">
            <div class="document-category">{{ $document->category->name }}</div>
            <a href="{{ route('documents.show', $document->slug) }}" class="document-btn" title="Voir les détails">
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

