@extends('layouts.app')

@section('title', (app()->getLocale() === 'fr' ? 'Articles Vedettes' : 'Featured Articles') . ' | NiangProgrammeur')
@section('meta_description', app()->getLocale() === 'fr' ? 'Découvrez tous nos articles vedettes : les articles les plus populaires et les plus pertinents sur les emplois, carrières et opportunités au Sénégal.' : 'Discover all our featured articles: the most popular and relevant articles about jobs, careers and opportunities in Senegal.')
@section('meta_keywords', app()->getLocale() === 'fr' ? 'articles vedettes, articles populaires, emploi Sénégal, carrières' : 'featured articles, popular articles, jobs Senegal, careers')
@push('meta')
    <link rel="canonical" href="{{ route('articles.vedettes') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('articles.vedettes') }}">
    <meta property="og:title" content="{{ (app()->getLocale() === 'fr' ? 'Articles Vedettes' : 'Featured Articles') . ' | NiangProgrammeur' }}">
    <meta property="og:description" content="{{ app()->getLocale() === 'fr' ? 'Découvrez tous nos articles vedettes' : 'Discover all our featured articles' }}">
@endpush

@section('styles')
<style>
    * {
        box-sizing: border-box;
    }
    
    /* Body Background */
    body:not(.dark-mode) {
        background: #f8fafc !important;
    }
    
    body.dark-mode {
        background: #0f172a !important;
    }
    
    /* Hero Section - Style similaire à la page d'accueil avec couleurs du logo */
    .featured-hero {
        position: relative;
        z-index: 2;
        width: 100%;
        min-height: 65vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 80px 40px 60px;
        margin: 0;
        overflow: hidden;
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.85) 0%, rgba(30, 41, 59, 0.9) 100%),
                    url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSlMIi0cIHooI5yOkZr-0eEb40Akg2Je4bRfg&s') center/cover no-repeat;
        background-attachment: fixed;
    }
    
    body:not(.dark-mode) .featured-hero {
        background: linear-gradient(135deg, rgba(248, 250, 252, 0.85) 0%, rgba(241, 245, 249, 0.9) 100%),
                    url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSlMIi0cIHooI5yOkZr-0eEb40Akg2Je4bRfg&s') center/cover no-repeat;
        background-attachment: fixed;
    }
    
    @media (max-width: 768px) {
        .featured-hero {
            background-attachment: scroll !important;
            min-height: 55vh;
            padding: 60px 20px 40px;
        }
    }
    
    /* Overlay pour améliorer la lisibilité */
    .featured-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 50% 50%, rgba(6, 182, 212, 0.1) 0%, rgba(15, 23, 42, 0.8) 100%);
        z-index: 0;
    }
    
    body:not(.dark-mode) .featured-hero::before {
        background: radial-gradient(circle at 50% 50%, rgba(6, 182, 212, 0.1) 0%, rgba(248, 250, 252, 0.7) 100%);
    }
    
    .featured-hero > * {
        position: relative;
        z-index: 1;
    }
    
    .featured-hero-content {
        max-width: 1200px;
        margin: 0 auto;
        width: 100%;
        text-align: center;
        animation: fadeInUp 1s ease-out;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(40px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Titre Principal - Style avec couleurs du logo */
    .featured-hero-title {
        font-family: 'Inter', sans-serif;
        font-size: clamp(2.5rem, 5vw, 4rem);
        font-weight: 900;
        line-height: 1.2;
        margin-bottom: 25px;
        color: #ffffff;
        letter-spacing: -0.02em;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        position: relative;
    }
    
    body:not(.dark-mode) .featured-hero-title {
        color: #1e293b;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    
    .title-gradient {
        background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        display: inline-block;
    }
    
    .featured-hero-title i {
        color: #06b6d4;
        margin-right: 12px;
        display: inline-block;
    }
    
    body:not(.dark-mode) .featured-hero-title i {
        color: #06b6d4;
    }
    
    /* Sous-titre descriptif */
    .featured-hero-subtitle {
        font-size: clamp(1.1rem, 2.5vw, 1.4rem);
        font-weight: 400;
        color: rgba(255, 255, 255, 0.95);
        margin-bottom: 40px;
        line-height: 1.6;
        max-width: 1100px;
        margin-left: auto;
        margin-right: auto;
        text-shadow: 0 1px 5px rgba(0, 0, 0, 0.2);
    }
    
    body:not(.dark-mode) .featured-hero-subtitle {
        color: rgba(30, 41, 59, 0.8);
        text-shadow: none;
    }
    
    /* Stats Section - Style comme page formations */
    .featured-stats-section {
        margin: -60px auto 0;
        max-width: 1400px;
        padding: 0 20px;
        position: relative;
        z-index: 10;
    }
    
    .featured-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 24px;
        margin-bottom: 80px;
    }
    
    .featured-stat-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 24px;
        padding: 32px 24px;
        text-align: center;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
    }
    
    body.dark-mode .featured-stat-card {
        background: rgba(15, 23, 42, 0.9);
        border-color: rgba(6, 182, 212, 0.3);
    }
    
    .featured-stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.05), rgba(20, 184, 166, 0.05));
        opacity: 0;
        transition: opacity 0.4s ease;
    }
    
    .featured-stat-card:hover::before {
        opacity: 1;
    }
    
    .featured-stat-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 16px 48px rgba(6, 182, 212, 0.2);
        border-color: rgba(6, 182, 212, 0.4);
    }
    
    body.dark-mode .featured-stat-card:hover {
        box-shadow: 0 16px 48px rgba(6, 182, 212, 0.3);
    }
    
    .featured-stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 8px;
        line-height: 1.2;
    }
    
    .featured-stat-label {
        font-size: 0.95rem;
        color: #64748b;
        font-weight: 500;
    }
    
    body.dark-mode .featured-stat-label {
        color: rgba(255, 255, 255, 0.7);
    }
    
    @media (max-width: 768px) {
        .featured-stats-section {
            margin: -40px auto 0;
            padding: 0 20px;
        }
        
        .featured-stats-grid {
            gap: 16px;
            margin-bottom: 60px;
        }
        
        .featured-stat-card {
            padding: 24px 20px;
        }
        
        .featured-stat-number {
            font-size: 2rem;
        }
    }
    
    /* Articles Container */
    .featured-articles-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 60px 20px 100px;
    }
    
    /* Grid Layout - 4 colonnes fixes */
    .featured-articles-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
    }
    
    @media (max-width: 1400px) {
        .featured-articles-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    @media (max-width: 1024px) {
        .featured-articles-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
    }
    
    @media (max-width: 640px) {
        .featured-articles-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
    }
    
    /* Card Design - Réaliste et Moderne */
    .featured-article-card {
        position: relative;
        background: #ffffff;
        border-radius: 16px;
        overflow: hidden;
        text-decoration: none;
        display: block;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.1);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    body.dark-mode .featured-article-card {
        background: #1e293b;
        border-color: rgba(255, 255, 255, 0.05);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3), 0 1px 2px rgba(0, 0, 0, 0.2);
    }
    
    .featured-article-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1), 0 4px 10px rgba(0, 0, 0, 0.08);
    }
    
    body.dark-mode .featured-article-card:hover {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5), 0 4px 10px rgba(0, 0, 0, 0.3);
    }
    
    /* Featured Badge - Subtile */
    .featured-badge {
        position: absolute;
        top: 16px;
        right: 16px;
        z-index: 2;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.95), rgba(20, 184, 166, 0.95));
        backdrop-filter: blur(10px);
        color: #ffffff;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 2px 8px rgba(6, 182, 212, 0.3);
    }
    
    .featured-badge i {
        font-size: 0.7rem;
    }
    
    /* Image Wrapper - Réaliste */
    .featured-image-wrapper {
        position: relative;
        width: 100%;
        height: 200px;
        overflow: hidden;
        background: #f1f5f9;
    }
    
    body.dark-mode .featured-image-wrapper {
        background: #334155;
    }
    
    .featured-article-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .featured-article-card:hover .featured-article-image {
        transform: scale(1.05);
    }
    
    /* Content Section */
    .featured-article-content {
        padding: 20px;
    }
    
    .featured-category {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(6, 182, 212, 0.1);
        color: #06b6d4;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-bottom: 12px;
        transition: all 0.2s ease;
    }
    
    body.dark-mode .featured-category {
        background: rgba(6, 182, 212, 0.2);
    }
    
    .featured-article-card:hover .featured-category {
        background: rgba(6, 182, 212, 0.15);
    }
    
    body.dark-mode .featured-article-card:hover .featured-category {
        background: rgba(6, 182, 212, 0.25);
    }
    
    .featured-article-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 10px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: color 0.2s ease;
    }
    
    body.dark-mode .featured-article-title {
        color: #f1f5f9;
    }
    
    .featured-article-card:hover .featured-article-title {
        color: #06b6d4;
    }
    
    .featured-article-excerpt {
        font-size: 0.875rem;
        color: #64748b;
        line-height: 1.6;
        margin-bottom: 16px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    body.dark-mode .featured-article-excerpt {
        color: #94a3b8;
    }
    
    .featured-article-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 16px;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    body.dark-mode .featured-article-footer {
        border-top-color: rgba(255, 255, 255, 0.05);
    }
    
    .featured-article-meta {
        display: flex;
        align-items: center;
        gap: 16px;
        color: #94a3b8;
        font-size: 0.8125rem;
    }
    
    body.dark-mode .featured-article-meta {
        color: #64748b;
    }
    
    .featured-article-meta span {
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .featured-article-meta i {
        font-size: 0.75rem;
        color: #cbd5e1;
    }
    
    body.dark-mode .featured-article-meta i {
        color: #475569;
    }
    
    .featured-read-more {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #06b6d4;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }
    
    .featured-read-more i {
        font-size: 0.75rem;
        transition: transform 0.2s ease;
    }
    
    .featured-article-card:hover .featured-read-more {
        gap: 8px;
    }
    
    .featured-article-card:hover .featured-read-more i {
        transform: translateX(3px);
    }
    
    /* No Articles */
    .no-featured-articles {
        text-align: center;
        padding: 100px 20px;
        background: #ffffff;
        border-radius: 16px;
        border: 2px dashed #e2e8f0;
    }
    
    body.dark-mode .no-featured-articles {
        background: #1e293b;
        border-color: #334155;
    }
    
    .no-featured-articles i {
        font-size: 4rem;
        color: #cbd5e1;
        margin-bottom: 24px;
        display: block;
    }
    
    body.dark-mode .no-featured-articles i {
        color: #475569;
    }
    
    .no-featured-articles h3 {
        font-size: 1.5rem;
        color: #1e293b;
        margin-bottom: 12px;
        font-weight: 700;
    }
    
    body.dark-mode .no-featured-articles h3 {
        color: #f1f5f9;
    }
    
    .no-featured-articles p {
        color: #64748b;
        font-size: 1rem;
    }
    
    body.dark-mode .no-featured-articles p {
        color: #94a3b8;
    }
    
    /* Pagination Wrapper */
    .featured-pagination-wrapper {
        margin-top: 60px;
        display: flex;
        justify-content: center;
    }
    
    /* Section Title */
    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 32px;
        letter-spacing: -0.5px;
    }
    
    body.dark-mode .section-title {
        color: #f1f5f9;
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="featured-hero">
    <div class="featured-hero-content">
        <h1 class="featured-hero-title">
            <i class="fas fa-fire"></i>
            {{ app()->getLocale() === 'fr' ? 'Articles' : 'Articles' }} <span class="title-gradient">{{ app()->getLocale() === 'fr' ? 'Vedettes' : 'Featured' }}</span>
        </h1>
        
        <p class="featured-hero-subtitle">
            {{ app()->getLocale() === 'fr' ? 'Découvrez tous nos articles les plus populaires et les plus pertinents sur les emplois, carrières et opportunités au Sénégal' : 'Discover all our most popular and relevant articles about jobs, careers and opportunities in Senegal' }}
        </p>
    </div>
</section>

<!-- Stats Section -->
<div class="featured-stats-section">
    <div class="featured-stats-grid">
        <div class="featured-stat-card">
            <div class="featured-stat-number">{{ $articles->total() }}</div>
            <div class="featured-stat-label">{{ app()->getLocale() === 'fr' ? 'Articles' : 'Articles' }}</div>
        </div>
        <div class="featured-stat-card">
            <div class="featured-stat-number">{{ $articles->currentPage() }}</div>
            <div class="featured-stat-label">{{ app()->getLocale() === 'fr' ? 'Page' : 'Page' }}</div>
        </div>
        <div class="featured-stat-card">
            <div class="featured-stat-number">{{ $articles->perPage() }}</div>
            <div class="featured-stat-label">{{ app()->getLocale() === 'fr' ? 'Par page' : 'Per page' }}</div>
        </div>
    </div>
</div>

<!-- Articles Container -->
<div class="featured-articles-container">
    @if($articles && $articles->count() > 0)
    <div class="featured-articles-grid">
        @foreach($articles as $article)
        <a href="{{ route('emplois.article', $article->slug) }}" class="featured-article-card">
            <!-- Featured Badge -->
            <div class="featured-badge">
                <i class="fas fa-fire"></i>
                <span>{{ app()->getLocale() === 'fr' ? 'Vedette' : 'Featured' }}</span>
            </div>
            
            <!-- Image -->
            <div class="featured-image-wrapper">
                @if($article->cover_image)
                    @if($article->cover_type === 'external')
                        <img src="{{ $article->cover_image }}" 
                             alt="{{ $article->title }}" 
                             class="featured-article-image"
                             loading="lazy"
                             onerror="this.style.display='none'">
                    @else
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($article->cover_image) }}" 
                             alt="{{ $article->title }}" 
                             class="featured-article-image"
                             loading="lazy"
                             onerror="this.style.display='none'">
                    @endif
                @else
                    <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #f1f5f9, #e2e8f0); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-image" style="font-size: 2.5rem; color: #cbd5e1;"></i>
                    </div>
                @endif
            </div>
            
            <!-- Content -->
            <div class="featured-article-content">
                @if($article->category)
                <div class="featured-category">
                    <i class="fas fa-tag"></i>
                    <span>{{ $article->category->name }}</span>
                </div>
                @endif
                
                <h3 class="featured-article-title">{{ $article->title }}</h3>
                
                @if($article->excerpt)
                <p class="featured-article-excerpt">{{ $article->excerpt }}</p>
                @endif
                
                <div class="featured-article-footer">
                    <div class="featured-article-meta">
                        <span>
                            <i class="fas fa-eye"></i>
                            {{ $article->featured_display_views }}
                        </span>
                    </div>
                    <span class="featured-read-more">
                        {{ app()->getLocale() === 'fr' ? 'Lire' : 'Read' }}
                        <i class="fas fa-arrow-right"></i>
                    </span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    
    @if($articles->hasPages())
    <div class="featured-pagination-wrapper">
        {{ $articles->links() }}
    </div>
    @endif
    
    @else
    <div class="no-featured-articles">
        <i class="fas fa-fire"></i>
        <h3>{{ app()->getLocale() === 'fr' ? 'Aucun article vedette disponible' : 'No featured articles available' }}</h3>
        <p>{{ app()->getLocale() === 'fr' ? 'Il n\'y a pas encore d\'articles vedettes publiés.' : 'There are no featured articles published yet.' }}</p>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    // Smooth scroll behavior
    document.documentElement.style.scrollBehavior = 'smooth';
</script>
@endsection
