@extends('layouts.app')

@section('title', 'Toutes les Opportunités d\'Emploi | NiangProgrammeur')
@section('meta_description', 'Découvrez toutes les opportunités d\'emploi, bourses, concours et offres professionnelles au Sénégal. Plus de ' . ($stats['total'] ?? 0) . ' opportunités disponibles.')
@push('meta')
    <link rel="canonical" href="{{ route('emplois.all-articles') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('emplois.all-articles') }}">
    <meta property="og:title" content="Toutes les Opportunités d'Emploi | NiangProgrammeur">
    <meta property="og:description" content="Découvrez toutes les opportunités d'emploi, bourses, concours et offres professionnelles au Sénégal.">
@endpush

@section('styles')
<style>
    * {
        box-sizing: border-box;
    }
    
    body:not(.dark-mode) {
        background: #f8fafc !important;
    }
    
    body.dark-mode {
        background: #0a0a0f !important;
    }
    
    /* Hero Section Ultra Moderne */
    .all-articles-hero {
        position: relative;
        min-height: 400px;
        padding: clamp(80px, 10vw, 120px) 20px clamp(60px, 8vw, 80px);
        background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 50%, #8b5cf6 100%);
        background-size: 200% 200%;
        animation: gradientShift 15s ease infinite;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }
    
    body.dark-mode .all-articles-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
    }
    
    @keyframes gradientShift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }
    
    .all-articles-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 30%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 70%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
        animation: float 20s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translate(0, 0) scale(1); }
        50% { transform: translate(30px, -30px) scale(1.1); }
    }
    
    .all-articles-hero-content {
        position: relative;
        z-index: 2;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .all-articles-hero h1 {
        font-size: clamp(2.5rem, 6vw, 4.5rem);
        font-weight: 900;
        color: #fff;
        margin-bottom: 1.5rem;
        line-height: 1.2;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        animation: fadeInUp 0.8s ease-out;
    }
    
    body.dark-mode .all-articles-hero h1 {
        color: rgba(255, 255, 255, 0.95);
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .all-articles-hero p {
        font-size: clamp(1rem, 2vw, 1.25rem);
        color: rgba(255, 255, 255, 0.95);
        max-width: 800px;
        margin: 0 auto;
        line-height: 1.6;
        animation: fadeInUp 0.8s ease-out 0.2s both;
    }
    
    body.dark-mode .all-articles-hero p {
        color: rgba(255, 255, 255, 0.85);
    }
    
    /* Stats Section */
    .all-articles-stats {
        display: flex;
        gap: 2rem;
        justify-content: center;
        margin-top: 2rem;
        flex-wrap: wrap;
        animation: fadeInUp 0.8s ease-out 0.4s both;
    }
    
    .stat-item {
        text-align: center;
        padding: 1rem 2rem;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    body.dark-mode .stat-item {
        background: rgba(255, 255, 255, 0.05);
        border-color: rgba(255, 255, 255, 0.1);
    }
    
    .stat-value {
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 900;
        color: #fff;
        display: block;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.9);
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
    }
    
    /* Container */
    .all-articles-container {
        max-width: 1600px;
        margin: 0 auto;
        padding: clamp(40px, 6vw, 80px) 20px;
    }
    
    /* Articles Grid - 4 colonnes */
    .all-articles-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: clamp(20px, 3vw, 30px);
        margin-top: 50px;
    }
    
    @media (max-width: 1400px) {
        .all-articles-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    @media (max-width: 968px) {
        .all-articles-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 640px) {
        .all-articles-grid {
            grid-template-columns: 1fr;
        }
    }
    
    /* Article Card Ultra Moderne */
    .all-article-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 2px solid rgba(6, 182, 212, 0.1);
        border-radius: 24px;
        overflow: hidden;
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        text-decoration: none;
        display: flex;
        flex-direction: column;
        height: 100%;
        position: relative;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        will-change: transform;
    }
    
    body.dark-mode .all-article-card {
        background: rgba(15, 23, 42, 0.8);
        border-color: rgba(6, 182, 212, 0.2);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
    }
    
    .all-article-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #06b6d4, #14b8a6, #8b5cf6);
        background-size: 200% 100%;
        animation: shimmer 3s linear infinite;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    @keyframes shimmer {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    
    .all-article-card:hover::before {
        opacity: 1;
    }
    
    .all-article-card:hover {
        transform: translateY(-12px) scale(1.02);
        box-shadow: 0 20px 60px rgba(6, 182, 212, 0.3);
        border-color: rgba(6, 182, 212, 0.4);
    }
    
    body.dark-mode .all-article-card:hover {
        box-shadow: 0 20px 60px rgba(6, 182, 212, 0.4);
    }
    
    /* Article Image */
    .all-article-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3rem;
        position: relative;
        overflow: hidden;
    }
    
    .all-article-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .all-article-card:hover .all-article-image img {
        transform: scale(1.1);
    }
    
    /* Category Badge */
    .all-article-category {
        position: absolute;
        top: 12px;
        right: 12px;
        padding: 6px 12px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        color: #06b6d4;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 2;
    }
    
    body.dark-mode .all-article-category {
        background: rgba(15, 23, 42, 0.95);
        color: #14b8a6;
    }
    
    /* Article Content */
    .all-article-content {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .all-article-title {
        font-size: clamp(1rem, 1.5vw, 1.25rem);
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 0.75rem;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: color 0.3s ease;
    }
    
    body.dark-mode .all-article-title {
        color: rgba(255, 255, 255, 0.95);
    }
    
    .all-article-card:hover .all-article-title {
        color: #06b6d4;
    }
    
    .all-article-excerpt {
        font-size: 0.9rem;
        color: #64748b;
        line-height: 1.6;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex: 1;
    }
    
    body.dark-mode .all-article-excerpt {
        color: rgba(255, 255, 255, 0.7);
    }
    
    /* Article Meta */
    .all-article-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 1rem;
        border-top: 1px solid rgba(6, 182, 212, 0.1);
        margin-top: auto;
    }
    
    body.dark-mode .all-article-meta {
        border-top-color: rgba(6, 182, 212, 0.2);
    }
    
    .all-article-date {
        font-size: 0.8rem;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    body.dark-mode .all-article-date {
        color: rgba(255, 255, 255, 0.6);
    }
    
    .all-article-views {
        font-size: 0.8rem;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    body.dark-mode .all-article-views {
        color: rgba(255, 255, 255, 0.6);
    }
    
    /* Pagination Moderne */
    .all-articles-pagination {
        margin-top: 60px;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .pagination {
        display: flex;
        gap: 0.5rem;
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .pagination li {
        margin: 0;
    }
    
    .pagination a,
    .pagination span {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 44px;
        height: 44px;
        padding: 0 16px;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 12px;
        color: #1e293b;
        text-decoration: none;
        font-weight: 700;
        transition: all 0.3s ease;
    }
    
    body.dark-mode .pagination a,
    body.dark-mode .pagination span {
        background: rgba(15, 23, 42, 0.8);
        border-color: rgba(6, 182, 212, 0.3);
        color: rgba(255, 255, 255, 0.9);
    }
    
    .pagination a:hover {
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border-color: transparent;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(6, 182, 212, 0.3);
    }
    
    .pagination .active span {
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border-color: transparent;
        color: #fff;
    }
    
    .pagination .disabled span {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    /* Empty State */
    .all-articles-empty {
        text-align: center;
        padding: 80px 20px;
        background: rgba(255, 255, 255, 0.5);
        backdrop-filter: blur(10px);
        border-radius: 24px;
        border: 2px dashed rgba(6, 182, 212, 0.3);
        margin-top: 50px;
    }
    
    body.dark-mode .all-articles-empty {
        background: rgba(15, 23, 42, 0.5);
        border-color: rgba(6, 182, 212, 0.2);
    }
    
    .all-articles-empty i {
        font-size: 4rem;
        color: rgba(6, 182, 212, 0.3);
        margin-bottom: 1.5rem;
    }
    
    .all-articles-empty p {
        font-size: 1.1rem;
        color: #64748b;
        margin: 0;
    }
    
    body.dark-mode .all-articles-empty p {
        color: rgba(255, 255, 255, 0.7);
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="all-articles-hero">
    <div class="all-articles-hero-content">
        <h1><i class="fas fa-briefcase"></i> Toutes les Opportunités d'Emploi</h1>
        <p>
            Découvrez toutes les opportunités professionnelles au Sénégal : offres d'emploi, 
            bourses d'études, concours et opportunités de carrière en un seul endroit.
        </p>
        
        @if(isset($stats))
        <div class="all-articles-stats">
            <div class="stat-item">
                <span class="stat-value">{{ number_format($stats['total'] ?? 0) }}</span>
                <span class="stat-label">Opportunités</span>
            </div>
            <div class="stat-item">
                <span class="stat-value">{{ number_format($stats['categories'] ?? 0) }}</span>
                <span class="stat-label">Catégories</span>
            </div>
        </div>
        @endif
    </div>
</section>

<!-- Articles Container -->
<div class="all-articles-container">
    @if($articles->count() > 0)
    <div class="all-articles-grid">
        @foreach($articles as $article)
        <a href="{{ route('emplois.article', $article->slug) }}" class="all-article-card">
            <!-- Article Image -->
            <div class="all-article-image">
                @if($article->cover_image)
                    @if($article->cover_type === 'internal')
                        <img src="{{ asset('storage/' . $article->cover_image) }}" alt="{{ $article->title }}" loading="lazy">
                    @else
                        <img src="{{ $article->cover_image }}" alt="{{ $article->title }}" loading="lazy">
                    @endif
                @else
                    <i class="fas fa-briefcase"></i>
                @endif
                
                @if($article->category)
                <span class="all-article-category">
                    <i class="{{ $article->category->icon ?? 'fas fa-folder' }}"></i> {{ $article->category->name }}
                </span>
                @endif
            </div>
            
            <!-- Article Content -->
            <div class="all-article-content">
                <h3 class="all-article-title">{{ $article->title }}</h3>
                
                @if($article->excerpt)
                <p class="all-article-excerpt">{{ $article->excerpt }}</p>
                @endif
                
                <div class="all-article-meta">
                    <span class="all-article-date">
                        <i class="fas fa-calendar-alt"></i>
                        {{ $article->published_at ? $article->published_at->format('d/m/Y') : $article->created_at->format('d/m/Y') }}
                    </span>
                    <span class="all-article-views">
                        <i class="fas fa-eye"></i>
                        {{ $article->featured_display_views }}
                    </span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    
    <!-- Pagination -->
    @if($articles->hasPages())
    <div class="all-articles-pagination">
        {{ $articles->links() }}
    </div>
    @endif
    @else
    <div class="all-articles-empty">
        <i class="fas fa-inbox"></i>
        <p>Aucune opportunité disponible pour le moment. Revenez bientôt !</p>
    </div>
    @endif
</div>
@endsection

