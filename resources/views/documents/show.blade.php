@extends('layouts.app')

@section('title', ($document->meta_title ?? $document->title) . ' - Documents - NiangProgrammeur')
@section('meta_description', $document->meta_description ?? $document->excerpt ?? $document->description ?? 'Découvrez ce document sur NiangProgrammeur')

@push('head')
@php
    $documentUrl = url()->current();
    $documentImage = $document->cover_image 
        ? ($document->cover_type === 'internal' 
            ? asset('storage/' . $document->cover_image) 
            : $document->cover_image)
        : asset('images/logo.png');
    // S'assurer que l'image est une URL absolue
    if (!preg_match('/^https?:\/\//', $documentImage)) {
        $documentImage = url($documentImage);
    }
    $documentDescription = htmlspecialchars($document->meta_description ?? $document->excerpt ?? $document->description ?? 'Découvrez ce document sur NiangProgrammeur', ENT_QUOTES, 'UTF-8');
    $documentTitle = htmlspecialchars($document->meta_title ?? $document->title, ENT_QUOTES, 'UTF-8');
    $documentPrice = $document->discount_price ?? $document->price;
    $documentCurrency = 'XOF'; // Franc CFA
@endphp

<!-- Meta Keywords -->
@if($document->meta_keywords)
<meta name="keywords" content="{{ is_array($document->meta_keywords) ? implode(', ', $document->meta_keywords) : $document->meta_keywords }}">
@endif

<!-- Canonical URL -->
<link rel="canonical" href="{{ $documentUrl }}">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="product">
<meta property="og:url" content="{{ $documentUrl }}">
<meta property="og:title" content="{{ $documentTitle }}">
<meta property="og:description" content="{{ $documentDescription }}">
<meta property="og:image" content="{{ $documentImage }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="{{ $documentTitle }}">
<meta property="og:site_name" content="NiangProgrammeur">
<meta property="og:locale" content="fr_FR">
@if($document->price)
<meta property="product:price:amount" content="{{ $documentPrice }}">
<meta property="product:price:currency" content="{{ $documentCurrency }}">
@endif
@if($document->category)
<meta property="product:category" content="{{ $document->category->name }}">
@endif

<!-- Twitter Cards -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ $documentUrl }}">
<meta name="twitter:title" content="{{ $documentTitle }}">
<meta name="twitter:description" content="{{ $documentDescription }}">
<meta name="twitter:image" content="{{ $documentImage }}">
<meta name="twitter:image:alt" content="{{ $documentTitle }}">
<meta name="twitter:site" content="@niangprogrammeur">
<meta name="twitter:creator" content="@niangprogrammeur">

<!-- Schema.org JSON-LD - Product Schema -->
@php
    $productSchema = [
        "@context" => "https://schema.org",
        "@type" => "Product",
        "name" => $document->title,
        "description" => $documentDescription,
        "image" => $documentImage,
        "url" => $documentUrl,
        "brand" => [
            "@type" => "Brand",
            "name" => "NiangProgrammeur"
        ],
        "aggregateRating" => [
            "@type" => "AggregateRating",
            "ratingValue" => "4.5",
            "reviewCount" => $document->sales_count ?? 0
        ]
    ];
    
    if ($document->category) {
        $productSchema["category"] = $document->category->name;
    }
    
    if ($document->price) {
        $productSchema["offers"] = [
            "@type" => "Offer",
            "url" => $documentUrl,
            "priceCurrency" => $documentCurrency,
            "price" => (string)$documentPrice,
            "priceValidUntil" => now()->addYear()->format('Y-m-d'),
            "availability" => "https://schema.org/InStock",
            "seller" => [
                "@type" => "Organization",
                "name" => "NiangProgrammeur"
            ]
        ];
    }
@endphp
<script type="application/ld+json">
{!! json_encode($productSchema, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_PRETTY_PRINT) !!}
</script>

<!-- Schema.org JSON-LD - Book/DigitalDocument (si applicable) -->
@if(in_array(strtolower($document->file_extension ?? ''), ['pdf', 'epub', 'mobi']))
@php
    $bookSchema = [
        "@context" => "https://schema.org",
        "@type" => "Book",
        "name" => $document->title,
        "description" => $documentDescription,
        "image" => $documentImage,
        "url" => $documentUrl,
        "publisher" => [
            "@type" => "Organization",
            "name" => "NiangProgrammeur",
            "logo" => [
                "@type" => "ImageObject",
                "url" => asset('images/logo.png')
            ]
        ],
        "datePublished" => ($document->published_at ? $document->published_at->format('Y-m-d') : $document->created_at->format('Y-m-d')),
        "dateModified" => $document->updated_at->format('Y-m-d'),
        "inLanguage" => "fr-FR",
        "bookFormat" => "https://schema.org/EBook"
    ];
    
    if ($document->author) {
        $bookSchema["author"] = [
            "@type" => "Person",
            "name" => $document->author->name
        ];
    }
@endphp
<script type="application/ld+json">
{!! json_encode($bookSchema, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_PRETTY_PRINT) !!}
</script>
@endif
@endpush

@push('styles')
<style>
.document-detail-page {
    min-height: 100vh;
    padding: 2rem 1rem;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
}

.document-detail-container {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 2rem;
}

.document-main {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.document-cover-large {
    width: 100%;
    height: 400px;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 2rem;
    background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 5rem;
}

.document-category-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: #e0f2fe;
    color: #06b6d4;
    border-radius: 6px;
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.document-title-large {
    font-size: 2rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 1rem;
    line-height: 1.3;
}

.document-meta {
    display: flex;
    gap: 1.5rem;
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #e2e8f0;
    flex-wrap: wrap;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #64748b;
    font-size: 0.875rem;
}

.meta-item i {
    color: #06b6d4;
}

.document-description {
    color: #475569;
    line-height: 1.7;
    margin-bottom: 2rem;
}

.document-info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    margin-bottom: 2rem;
}

.info-item {
    padding: 1rem;
    background: #f8fafc;
    border-radius: 8px;
}

.info-label {
    font-size: 0.75rem;
    color: #64748b;
    text-transform: uppercase;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.info-value {
    font-size: 1rem;
    color: #1e293b;
    font-weight: 600;
}

.document-sidebar {
    position: sticky;
    top: 2rem;
    height: fit-content;
}

.purchase-card {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
}

.price-section {
    margin-bottom: 2rem;
}

.price-current {
    font-size: 2.5rem;
    font-weight: 800;
    color: #06b6d4;
    margin-bottom: 0.5rem;
}

.price-old {
    font-size: 1.25rem;
    color: #94a3b8;
    text-decoration: line-through;
}

.discount-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: #fef3c7;
    color: #d97706;
    border-radius: 6px;
    font-size: 0.875rem;
    font-weight: 600;
    margin-left: 0.5rem;
}

.purchase-btn {
    width: 100%;
    padding: 1rem;
    background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 100%);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1.125rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-bottom: 1rem;
}

.purchase-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(6, 182, 212, 0.4);
}

.purchase-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Documents Similaires Sidebar */
.related-sidebar-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    margin-top: 2rem;
}

.related-sidebar-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.related-sidebar-title i {
    color: #06b6d4;
}

.related-sidebar-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.related-sidebar-item {
    display: flex;
    gap: 0.75rem;
    padding: 0.75rem;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.related-sidebar-item:hover {
    background: #f8fafc;
    border-color: #e2e8f0;
    transform: translateX(4px);
}

.related-sidebar-image {
    width: 60px;
    height: 60px;
    flex-shrink: 0;
    border-radius: 6px;
    overflow: hidden;
    background: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.related-sidebar-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.related-sidebar-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    font-size: 1.5rem;
}

.related-sidebar-content {
    flex: 1;
    min-width: 0;
}

.related-sidebar-item-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.25rem;
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.related-sidebar-price {
    font-size: 0.875rem;
    font-weight: 700;
    color: #06b6d4;
}

.download-btn {
    width: 100%;
    padding: 1rem;
    background: #10b981;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1.125rem;
    font-weight: 700;
    cursor: pointer;
    text-decoration: none;
    display: block;
    text-align: center;
    transition: all 0.3s ease;
}

.download-btn:hover {
    background: #059669;
    transform: translateY(-2px);
}

.related-section {
    margin-top: 3rem;
    padding-top: 3rem;
    border-top: 1px solid #e2e8f0;
}

.related-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 1.5rem;
}

.related-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1.5rem;
}

@media (max-width: 968px) {
    .document-detail-container {
        grid-template-columns: 1fr;
    }
    
    .document-sidebar {
        position: static;
    }
}

/* ============================================
   DARK MODE ADAPTATIONS COMPLÈTES
   ============================================ */
body.dark-mode .document-detail-page {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%) !important;
}

body.dark-mode .document-main {
    background: rgba(30, 41, 59, 0.8);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(6, 182, 212, 0.3);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
}

body.dark-mode .document-cover-large {
    background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 50%, #0891b2 100%);
}

body.dark-mode .document-category-badge {
    background: rgba(6, 182, 212, 0.2);
    color: #06b6d4;
    border: 1px solid rgba(6, 182, 212, 0.4);
}

body.dark-mode .document-title-large {
    color: white;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
}

body.dark-mode .document-meta {
    border-bottom: 1px solid rgba(6, 182, 212, 0.2);
}

body.dark-mode .meta-item {
    color: rgba(255, 255, 255, 0.8);
}

body.dark-mode .meta-item i {
    color: #06b6d4;
    filter: drop-shadow(0 0 8px rgba(6, 182, 212, 0.6));
}

body.dark-mode .document-description {
    color: rgba(255, 255, 255, 0.9);
}

body.dark-mode .info-item {
    background: rgba(15, 23, 42, 0.6);
    border: 1px solid rgba(6, 182, 212, 0.2);
}

body.dark-mode .info-label {
    color: rgba(255, 255, 255, 0.6);
}

body.dark-mode .info-value {
    color: white;
}

body.dark-mode .purchase-card {
    background: rgba(30, 41, 59, 0.8);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(6, 182, 212, 0.3);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
}

body.dark-mode .price-current {
    color: #06b6d4;
    text-shadow: 0 0 12px rgba(6, 182, 212, 0.6);
}

body.dark-mode .price-old {
    color: rgba(255, 255, 255, 0.4);
}

body.dark-mode .discount-badge {
    background: rgba(251, 191, 36, 0.2);
    color: #fbbf24;
    border: 1px solid rgba(251, 191, 36, 0.4);
}

body.dark-mode .purchase-btn {
    background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 100%);
    box-shadow: 0 4px 15px rgba(6, 182, 212, 0.5);
}

body.dark-mode .purchase-btn:hover {
    box-shadow: 0 8px 25px rgba(6, 182, 212, 0.7);
}

body.dark-mode .download-btn {
    background: #10b981;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
}

body.dark-mode .download-btn:hover {
    background: #059669;
    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.6);
}

body.dark-mode .related-sidebar-card {
    background: rgba(30, 41, 59, 0.8);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(6, 182, 212, 0.3);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
}

body.dark-mode .related-sidebar-title {
    color: white;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
}

body.dark-mode .related-sidebar-title i {
    color: #06b6d4;
    filter: drop-shadow(0 0 8px rgba(6, 182, 212, 0.6));
}

body.dark-mode .related-sidebar-item {
    border-color: rgba(6, 182, 212, 0.2);
}

body.dark-mode .related-sidebar-item:hover {
    background: rgba(15, 23, 42, 0.6);
    border-color: rgba(6, 182, 212, 0.4);
}

body.dark-mode .related-sidebar-image {
    background: #374151;
}

body.dark-mode .related-sidebar-placeholder {
    color: #9ca3af;
}

body.dark-mode .related-sidebar-item-title {
    color: white;
}

body.dark-mode .related-sidebar-price {
    color: #06b6d4;
    text-shadow: 0 0 8px rgba(6, 182, 212, 0.4);
}

body.dark-mode .related-section {
    border-top: 1px solid rgba(6, 182, 212, 0.2);
}

body.dark-mode .related-title {
    color: white;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
}

/* Messages Flash */
.flash-message {
    position: fixed;
    top: 80px;
    right: 20px;
    z-index: 10000;
    background: white;
    border-radius: 12px;
    padding: 1rem 1.25rem;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15), 0 2px 8px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    min-width: 320px;
    max-width: 500px;
    animation: slideInRight 0.4s ease-out;
    border-left: 4px solid;
}

@keyframes slideInRight {
    from {
        transform: translateX(400px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.flash-content {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex: 1;
}

.flash-content i {
    font-size: 1.25rem;
    flex-shrink: 0;
}

.flash-content span {
    font-size: 0.95rem;
    font-weight: 500;
    line-height: 1.4;
}

.flash-close {
    background: none;
    border: none;
    color: rgba(0, 0, 0, 0.4);
    cursor: pointer;
    padding: 0.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    transition: all 0.2s ease;
    flex-shrink: 0;
}

.flash-close:hover {
    background: rgba(0, 0, 0, 0.05);
    color: rgba(0, 0, 0, 0.6);
}

.flash-success {
    background: #f0fdf4;
    border-left-color: #10b981;
    color: #065f46;
}

.flash-success .flash-content i {
    color: #10b981;
}

.flash-info {
    background: #eff6ff;
    border-left-color: #06b6d4;
    color: #0c4a6e;
}

.flash-info .flash-content i {
    color: #06b6d4;
}

.flash-error {
    background: #fef2f2;
    border-left-color: #ef4444;
    color: #991b1b;
}

.flash-error .flash-content i {
    color: #ef4444;
}

/* Dark Mode pour les messages flash */
body.dark-mode .flash-message {
    background: rgba(30, 41, 59, 0.95);
    backdrop-filter: blur(20px);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(6, 182, 212, 0.1);
}

body.dark-mode .flash-success {
    background: rgba(16, 185, 129, 0.15);
    border-left-color: #10b981;
    color: #6ee7b7;
}

body.dark-mode .flash-success .flash-content i {
    color: #10b981;
    filter: drop-shadow(0 0 8px rgba(16, 185, 129, 0.6));
}

body.dark-mode .flash-info {
    background: rgba(6, 182, 212, 0.15);
    border-left-color: #06b6d4;
    color: #67e8f9;
}

body.dark-mode .flash-info .flash-content i {
    color: #06b6d4;
    filter: drop-shadow(0 0 8px rgba(6, 182, 212, 0.6));
}

body.dark-mode .flash-error {
    background: rgba(239, 68, 68, 0.15);
    border-left-color: #ef4444;
    color: #fca5a5;
}

body.dark-mode .flash-error .flash-content i {
    color: #ef4444;
    filter: drop-shadow(0 0 8px rgba(239, 68, 68, 0.6));
}

body.dark-mode .flash-close {
    color: rgba(255, 255, 255, 0.5);
}

body.dark-mode .flash-close:hover {
    background: rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.8);
}

@media (max-width: 640px) {
    .flash-message {
        right: 10px;
        left: 10px;
        min-width: auto;
        max-width: none;
    }
}
</style>
@endpush

@section('content')
<!-- Messages Flash -->
@if(session('success'))
<div class="flash-message flash-success" id="flashMessage">
    <div class="flash-content">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
    <button class="flash-close" onclick="this.parentElement.remove()">
        <i class="fas fa-times"></i>
    </button>
</div>
@endif

@if(session('info'))
<div class="flash-message flash-info" id="flashMessage">
    <div class="flash-content">
        <i class="fas fa-info-circle"></i>
        <span>{{ session('info') }}</span>
    </div>
    <button class="flash-close" onclick="this.parentElement.remove()">
        <i class="fas fa-times"></i>
    </button>
</div>
@endif

@if(session('error'))
<div class="flash-message flash-error" id="flashMessage">
    <div class="flash-content">
        <i class="fas fa-exclamation-circle"></i>
        <span>{{ session('error') }}</span>
    </div>
    <button class="flash-close" onclick="this.parentElement.remove()">
        <i class="fas fa-times"></i>
    </button>
</div>
@endif

<div class="document-detail-page">
    <div class="document-detail-container">
        <!-- Contenu Principal -->
        <div class="document-main">
            @if($document->cover_image)
                @if($document->cover_type === 'internal')
                    <img src="{{ asset('storage/' . $document->cover_image) }}" alt="{{ $document->title }}" class="document-cover-large">
                @else
                    <img src="{{ $document->cover_image }}" alt="{{ $document->title }}" class="document-cover-large">
                @endif
            @else
                <div class="document-cover-large">
                    <i class="fas fa-file-{{ $document->file_extension === 'pdf' ? 'pdf' : ($document->file_extension === 'doc' || $document->file_extension === 'docx' ? 'word' : 'alt') }}"></i>
                </div>
            @endif

            <div class="document-category-badge">
                <i class="fas fa-folder"></i> {{ $document->category->name }}
            </div>

            <h1 class="document-title-large">{{ $document->title }}</h1>

            <div class="document-meta">
                <div class="meta-item">
                    <i class="fas fa-user"></i>
                    <span>{{ $document->author->name ?? 'Admin' }}</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-calendar"></i>
                    <span>{{ $document->published_at->format('d/m/Y') }}</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-eye"></i>
                    <span>{{ number_format($document->views_count, 0, ',', ' ') }} vues</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-shopping-cart"></i>
                    <span>{{ number_format($document->sales_count, 0, ',', ' ') }} ventes</span>
                </div>
            </div>

            @if($document->description)
                <div class="document-description">
                    {!! nl2br(e($document->description)) !!}
                </div>
            @endif

            @if($document->tags && count($document->tags) > 0)
                <div style="margin-top: 2rem;">
                    <div style="font-size: 0.875rem; color: #64748b; margin-bottom: 0.5rem; font-weight: 600;">Tags:</div>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                        @foreach($document->tags as $tag)
                            <span style="padding: 0.25rem 0.75rem; background: #e0f2fe; color: #06b6d4; border-radius: 6px; font-size: 0.875rem;">#{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar Achat -->
        <aside class="document-sidebar">
            <div class="purchase-card">
                <!-- Badge Gratuit -->
                @if($document->isFree())
                    <div style="background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 12px; border-radius: 12px; text-align: center; margin-bottom: 1rem; font-weight: 700; font-size: 1.1rem;">
                        <i class="fas fa-gift"></i> GRATUIT
                    </div>
                @endif

                <div class="price-section">
                    @if($document->isFree())
                        <div class="price-current" style="color: #10b981; font-size: 1.5rem;">
                            <i class="fas fa-gift"></i> Gratuit
                        </div>
                    @elseif($document->hasDiscount())
                        <div class="price-old">{{ number_format($document->price, 0, ',', ' ') }} FCFA</div>
                        <div class="price-current">
                            {{ number_format($document->discount_price, 0, ',', ' ') }} FCFA
                            <span class="discount-badge">-{{ $document->getDiscountPercentage() }}%</span>
                        </div>
                    @else
                        <div class="price-current">{{ number_format($document->price, 0, ',', ' ') }} FCFA</div>
                    @endif
                </div>

                <!-- Note moyenne -->
                @if(isset($reviews) && $reviews->count() > 0)
                <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; margin: 1rem 0; padding: 0.75rem; background: rgba(6, 182, 212, 0.1); border-radius: 8px;">
                    <div style="color: #f59e0b;">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star{{ $i <= round($document->average_rating) ? '' : '-o' }}"></i>
                        @endfor
                    </div>
                    <span style="font-weight: 700; color: #1e293b;">{{ number_format($document->average_rating, 1) }}</span>
                    <span style="color: #64748b; font-size: 0.875rem;">({{ $document->reviews_count }})</span>
                </div>
                @endif

                @if($document->isFree())
                    <!-- Téléchargement direct pour documents gratuits -->
                    <a href="{{ route('documents.download-free', $document->id) }}" class="download-btn" style="width: 100%;">
                        <i class="fas fa-download"></i> Télécharger gratuitement
                    </a>
                @elseif($userHasPurchased)
                    <a href="#" class="download-btn">
                        <i class="fas fa-download"></i> Télécharger
                    </a>
                    <p style="text-align: center; color: #64748b; font-size: 0.875rem; margin-top: 1rem;">
                        Vous avez déjà acheté ce document
                    </p>
                @else
                    <form action="{{ route('documents.cart.add', $document->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="purchase-btn">
                            <i class="fas fa-shopping-cart"></i> Ajouter au panier
                        </button>
                    </form>
                    <p style="text-align: center; color: #64748b; font-size: 0.875rem;">
                        <i class="fas fa-shield-alt"></i> Paiement sécurisé
                    </p>
                @endif

                <!-- Bouton Wishlist -->
                @auth
                <div style="margin-top: 1rem;">
                    <button type="button" 
                            onclick="toggleWishlist({{ $document->id }})" 
                            id="wishlist-btn-{{ $document->id }}"
                            class="wishlist-btn"
                            style="width: 100%; padding: 12px; border: 2px solid {{ $inWishlist ? '#ef4444' : '#e2e8f0' }}; background: {{ $inWishlist ? '#fef2f2' : 'transparent' }}; color: {{ $inWishlist ? '#ef4444' : '#64748b' }}; border-radius: 12px; font-weight: 700; cursor: pointer; transition: all 0.3s ease;">
                        <i class="fas fa-heart{{ $inWishlist ? '' : '-o' }}"></i> 
                        <span id="wishlist-text-{{ $document->id }}">{{ $inWishlist ? 'Retirer de la wishlist' : 'Ajouter à la wishlist' }}</span>
                    </button>
                </div>
                @endauth

                <!-- Partage Social -->
                <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e2e8f0;">
                    <p style="font-weight: 700; margin-bottom: 0.75rem; color: #1e293b; font-size: 0.9rem;">Partager :</p>
                    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
                           target="_blank" 
                           style="flex: 1; padding: 8px 12px; background: #1877f2; color: white; border-radius: 8px; text-align: center; text-decoration: none; font-size: 0.875rem; font-weight: 600;">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($document->title) }}" 
                           target="_blank" 
                           style="flex: 1; padding: 8px 12px; background: #1da1f2; color: white; border-radius: 8px; text-align: center; text-decoration: none; font-size: 0.875rem; font-weight: 600;">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($document->title . ' - ' . url()->current()) }}" 
                           target="_blank" 
                           style="flex: 1; padding: 8px 12px; background: #25d366; color: white; border-radius: 8px; text-align: center; text-decoration: none; font-size: 0.875rem; font-weight: 600;">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" 
                           target="_blank" 
                           style="flex: 1; padding: 8px 12px; background: #0077b5; color: white; border-radius: 8px; text-align: center; text-decoration: none; font-size: 0.875rem; font-weight: 600;">
                            <i class="fab fa-linkedin"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Les Nouveautés dans la Sidebar -->
            @if($relatedDocuments->count() > 0)
            <div class="related-sidebar-card">
                <h3 class="related-sidebar-title">
                    <i class="fas fa-star"></i> Les nouveautés
                </h3>
                <div class="related-sidebar-list">
                    @foreach($relatedDocuments as $related)
                    <a href="{{ route('documents.show', $related->slug) }}" class="related-sidebar-item">
                        <div class="related-sidebar-image">
                            @if($related->cover_image)
                                @if($related->cover_type === 'internal')
                                    <img src="{{ asset('storage/' . $related->cover_image) }}" alt="{{ $related->title }}">
                                @else
                                    <img src="{{ $related->cover_image }}" alt="{{ $related->title }}">
                                @endif
                            @else
                                <div class="related-sidebar-placeholder">
                                    <i class="fas fa-file-{{ $related->file_extension === 'pdf' ? 'pdf' : ($related->file_extension === 'doc' || $related->file_extension === 'docx' ? 'word' : 'alt') }}"></i>
                                </div>
                            @endif
                        </div>
                        <div class="related-sidebar-content">
                            <h4 class="related-sidebar-item-title">{{ \Illuminate\Support\Str::limit($related->title, 50) }}</h4>
                            <div class="related-sidebar-price">
                                {{ number_format($related->hasDiscount() ? $related->discount_price : $related->price, 0, ',', ' ') }} FCFA
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </aside>
    </div>

    <!-- Section Avis/Commentaires -->
    <div style="max-width: 1200px; margin: 3rem auto; padding: 0 1rem;">
        <div style="background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
            <h2 style="font-size: 1.75rem; font-weight: 800; margin-bottom: 1.5rem; color: #1e293b; display: flex; align-items: center; gap: 0.75rem;">
                <i class="fas fa-comments" style="color: #06b6d4;"></i>
                Avis et Commentaires
                @if(isset($reviews) && $reviews->count() > 0)
                <span style="font-size: 1rem; color: #64748b; font-weight: 600;">({{ $document->reviews_count }})</span>
                @endif
            </h2>

            <!-- Formulaire d'avis -->
            @auth
            @if($userHasPurchased || $document->isFree())
            <div style="background: #f8fafc; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem; border: 2px solid #e2e8f0;">
                <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 1rem; color: #1e293b;">Laisser un avis</h3>
                <form action="{{ route('documents.reviews.store', $document->id) }}" method="POST">
                    @csrf
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #1e293b;">Note :</label>
                        <div class="star-rating-container" data-rating="0" style="display: flex; gap: 0.5rem; flex-direction: row-reverse; justify-content: flex-end;">
                            @for($i = 5; $i >= 1; $i--)
                            <input type="radio" name="rating" value="{{ $i }}" id="rating-{{ $i }}" required style="display: none;" class="rating-radio">
                            <div class="rating-star" data-rating="{{ $i }}" data-for="rating-{{ $i }}" style="font-size: 1.5rem; color: #e2e8f0; cursor: pointer; transition: all 0.2s ease; user-select: none;">
                                <i class="far fa-star"></i>
                            </div>
                            @endfor
                        </div>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #1e293b;">Commentaire (optionnel) :</label>
                        <textarea name="comment" rows="4" style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 8px; font-family: inherit; resize: vertical;" placeholder="Partagez votre expérience avec ce document..."></textarea>
                    </div>
                    <button type="submit" style="padding: 12px 24px; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: white; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; transition: transform 0.2s;">
                        <i class="fas fa-paper-plane"></i> Publier l'avis
                    </button>
                </form>
            </div>
            @endif
            @endauth

            <!-- Liste des avis -->
            @if(isset($reviews) && $reviews->count() > 0)
            <div class="reviews-list">
                @foreach($reviews as $review)
                <div style="padding: 1.5rem; border-bottom: 1px solid #e2e8f0; background: white; border-radius: 12px; margin-bottom: 1rem;">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.75rem;">
                        <div>
                            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                                <div style="font-weight: 700; color: #1e293b;">{{ $review->display_name }}</div>
                                @if($review->is_verified_purchase)
                                <span style="background: #10b981; color: white; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 600;">
                                    <i class="fas fa-check-circle"></i> Achat vérifié
                                </span>
                                @endif
                            </div>
                            <div style="display: flex; gap: 0.25rem; color: #f59e0b;">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}" style="font-size: 0.875rem;"></i>
                                @endfor
                            </div>
                        </div>
                        <div style="color: #64748b; font-size: 0.875rem;">
                            {{ $review->created_at->format('d/m/Y') }}
                        </div>
                    </div>
                    @if($review->comment)
                    <p style="color: #475569; line-height: 1.6; margin: 0;">{{ $review->comment }}</p>
                    @endif
                </div>
                @endforeach

                <!-- Pagination -->
                <div style="margin-top: 2rem;">
                    {{ $reviews->links() }}
                </div>
            </div>
            @else
            <div style="text-align: center; padding: 3rem; color: #64748b;">
                <i class="fas fa-comments" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                <p>Aucun avis pour le moment. Soyez le premier à laisser un avis !</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-hide flash messages après 5 secondes
    document.addEventListener('DOMContentLoaded', function() {
        const flashMessages = document.querySelectorAll('.flash-message');
        flashMessages.forEach(function(message) {
            setTimeout(function() {
                message.style.animation = 'slideOutRight 0.4s ease-out';
                setTimeout(function() {
                    message.remove();
                }, 400);
            }, 5000);
        });
    });

    // Animation de sortie
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);

    // Système de notation étoiles interactif - Version simplifiée avec divs
    (function() {
        function initStarRating() {
            console.log('Initialisation du système de notation...');
            const containers = document.querySelectorAll('.star-rating-container');
            console.log('Conteneurs trouvés:', containers.length);
            
            containers.forEach(function(container) {
                // Éviter la double initialisation
                if (container.dataset.initialized === 'true') {
                    console.log('Conteneur déjà initialisé, ignoré');
                    return;
                }
                container.dataset.initialized = 'true';
                
                const stars = container.querySelectorAll('.rating-star');
                const radios = container.querySelectorAll('.rating-radio');
                
                console.log('Étoiles trouvées:', stars.length, 'Radios trouvés:', radios.length);
                
                if (stars.length === 0 || radios.length === 0) {
                    console.log('Éléments manquants, abandon');
                    return;
                }
                
                let selectedRating = 0;
                let hoverRating = 0;
                
                // Fonction pour mettre à jour l'affichage
                function updateDisplay() {
                    const ratingToShow = hoverRating || selectedRating;
                    stars.forEach(function(star) {
                        const starValue = parseInt(star.dataset.rating);
                        const icon = star.querySelector('i');
                        
                        if (starValue <= ratingToShow) {
                            star.style.color = '#f59e0b';
                            star.style.transform = 'scale(1.15)';
                            if (icon) icon.className = 'fas fa-star';
                        } else {
                            star.style.color = '#e2e8f0';
                            star.style.transform = 'scale(1)';
                            if (icon) icon.className = 'far fa-star';
                        }
                    });
                }
                
                // Attacher les événements aux étoiles
                stars.forEach(function(star) {
                    const starValue = parseInt(star.dataset.rating);
                    const radioId = star.dataset.for;
                    const radio = document.getElementById(radioId);
                    
                    if (!radio) return;
                    
                    // Clic sur l'étoile
                    star.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        console.log('Clic sur étoile:', starValue);
                        
                        // Sélectionner le radio
                        radio.checked = true;
                        selectedRating = starValue;
                        hoverRating = 0;
                        container.dataset.rating = selectedRating;
                        
                        // Mettre à jour l'affichage
                        updateDisplay();
                        
                        console.log('Note sélectionnée:', selectedRating);
                        
                        // Déclencher l'événement change
                        const changeEvent = new Event('change', { bubbles: true });
                        radio.dispatchEvent(changeEvent);
                    });
                    
                    // Survol
                    star.addEventListener('mouseenter', function() {
                        hoverRating = starValue;
                        updateDisplay();
                    });
                    
                    // Sortie du survol
                    star.addEventListener('mouseleave', function() {
                        hoverRating = 0;
                        updateDisplay();
                    });
                });
                
                // Écouter les changements sur les radios
                radios.forEach(function(radio) {
                    radio.addEventListener('change', function() {
                        selectedRating = parseInt(this.value);
                        hoverRating = 0;
                        container.dataset.rating = selectedRating;
                        updateDisplay();
                    });
                });
                
                // Initialiser l'affichage
                updateDisplay();
                console.log('Système de notation initialisé avec succès');
            });
        }
        
        // Initialiser quand le DOM est prêt
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                console.log('DOM chargé, initialisation du système de notation');
                initStarRating();
                setTimeout(initStarRating, 200);
            });
        } else {
            console.log('DOM déjà chargé, initialisation immédiate');
            initStarRating();
            setTimeout(initStarRating, 200);
        }
        
        // Réessayer après un délai supplémentaire
        setTimeout(function() {
            console.log('Réinitialisation après délai');
            initStarRating();
        }, 500);
    })();

    // Toggle Wishlist (AJAX)
    function toggleWishlist(documentId) {
        const btn = document.getElementById('wishlist-btn-' + documentId);
        const text = document.getElementById('wishlist-text-' + documentId);
        const icon = btn.querySelector('i');
        
        // Désactiver le bouton pendant la requête
        btn.disabled = true;
        btn.style.opacity = '0.6';
        
        fetch('{{ route("wishlist.toggle", ":id") }}'.replace(':id', documentId), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mettre à jour l'interface
                if (data.action === 'added') {
                    btn.style.borderColor = '#ef4444';
                    btn.style.background = '#fef2f2';
                    btn.style.color = '#ef4444';
                    icon.className = 'fas fa-heart';
                    text.textContent = 'Retirer de la wishlist';
                } else {
                    btn.style.borderColor = '#e2e8f0';
                    btn.style.background = 'transparent';
                    btn.style.color = '#64748b';
                    icon.className = 'fas fa-heart-o';
                    text.textContent = 'Ajouter à la wishlist';
                }
                
                // Notification
                showNotification(data.message, 'success');
            } else {
                showNotification(data.message || 'Erreur lors de l\'opération', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Erreur lors de l\'opération', 'error');
        })
        .finally(() => {
            btn.disabled = false;
            btn.style.opacity = '1';
        });
    }

    // Fonction de notification
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = 'flash-message flash-' + (type === 'success' ? 'success' : 'error');
        notification.style.position = 'fixed';
        notification.style.top = '20px';
        notification.style.right = '20px';
        notification.style.zIndex = '10000';
        notification.innerHTML = `
            <div class="flash-content">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                <span>${message}</span>
            </div>
            <button class="flash-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.4s ease-out';
            setTimeout(() => notification.remove(), 400);
        }, 3000);
    }
</script>
@endpush

