@extends('layouts.app')

@section('title', 'Offres d\'Emploi | NiangProgrammeur')
@section('meta_description', 'Consultez les meilleures offres d\'emploi publiées au Sénégal. Nous ne recrutons pas mais publions les offres de recrutement existantes.')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@400;500;600;700;800;900&display=swap');
    
    * {
        box-sizing: border-box;
    }
    
    /* Body background for offres page */
    body:not(.dark-mode) {
        background: #ffffff !important;
    }
    
    body.dark-mode {
        background: #0a0a0f !important;
    }
    
    .offers-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
        padding: 140px 20px 100px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    body:not(.dark-mode) .offers-hero {
        background: linear-gradient(135deg, rgba(30, 41, 59, 0.4) 0%, rgba(51, 65, 85, 0.5) 50%, rgba(30, 41, 59, 0.4) 100%) !important;
    }
    
    .offers-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(6, 182, 212, 0.1) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }
    
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    .offers-hero-content {
        position: relative;
        z-index: 1;
        max-width: 900px;
        margin: 0 auto;
    }
    
    .offers-hero h1 {
        font-family: 'Poppins', sans-serif;
        font-size: clamp(3rem, 6vw, 5rem);
        font-weight: 900;
        background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 50%, #06b6d4 100%);
        background-size: 200% auto;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 25px;
        animation: shimmer 3s linear infinite;
    }
    
    @keyframes shimmer {
        to { background-position: 200% center; }
    }
    
    .offers-hero p {
        font-size: 1.3rem;
        color: rgba(255, 255, 255, 0.95);
        line-height: 1.8;
        font-weight: 400;
        text-shadow: 0 1px 5px rgba(0, 0, 0, 0.2);
    }
    
    body:not(.dark-mode) .offers-hero p {
        color: rgba(255, 255, 255, 0.95) !important;
        text-shadow: 0 1px 5px rgba(0, 0, 0, 0.3) !important;
    }
    
    .offers-container {
        max-width: 1600px;
        margin: 0 auto;
        padding: 80px 20px;
    }
    
    .articles-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        margin-top: 50px;
    }
    
    .article-card {
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(20px);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 24px;
        overflow: hidden;
        transition: all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        text-decoration: none;
        display: block;
        height: 100%;
        position: relative;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        will-change: transform;
    }
    
    @media (max-width: 768px) {
        .article-card {
            backdrop-filter: blur(10px);
        }
    }
    
    body:not(.dark-mode) .article-card {
        background: rgba(255, 255, 255, 0.9) !important;
        border-color: rgba(6, 182, 212, 0.25) !important;
        box-shadow: 0 10px 40px rgba(6, 182, 212, 0.1) !important;
    }
    
    .article-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(6, 182, 212, 0.1), transparent);
        transition: left 0.6s;
        z-index: 1;
    }
    
    .article-card:hover::before {
        left: 100%;
    }
    
    .article-card:hover {
        transform: translateY(-12px) scale(1.02);
        border-color: rgba(6, 182, 212, 0.6);
        box-shadow: 0 25px 70px rgba(6, 182, 212, 0.4);
    }
    
    .article-card-image-wrapper {
        position: relative;
        width: 100%;
        height: 300px;
        overflow: hidden;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
    }
    
    .article-card-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    .article-card:hover .article-card-image {
        transform: scale(1.2) rotate(2deg);
    }
    
    .article-card-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.95) 0%, rgba(0, 0, 0, 0.6) 50%, transparent 100%);
        padding: 30px 25px 25px;
        transform: translateY(0);
        transition: all 0.4s ease;
        z-index: 2;
    }
    
    .article-card:hover .article-card-overlay {
        background: linear-gradient(to top, rgba(6, 182, 212, 0.95) 0%, rgba(0, 0, 0, 0.7) 50%, transparent 100%);
        padding-bottom: 30px;
    }
    
    .article-card-category {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: rgba(6, 182, 212, 0.3);
        color: #06b6d4;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        margin-bottom: 15px;
        border: 1px solid rgba(6, 182, 212, 0.5);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }
    
    .article-card:hover .article-card-category {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
        transform: translateX(5px);
    }
    
    .article-card-title {
        font-family: 'Poppins', sans-serif;
        font-size: 1.3rem;
        font-weight: 800;
        color: #fff;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-shadow: 0 2px 15px rgba(0, 0, 0, 0.5);
        transition: all 0.3s ease;
    }
    
    body:not(.dark-mode) .article-card-title {
        color: rgba(255, 255, 255, 0.95) !important;
        text-shadow: 0 2px 15px rgba(0, 0, 0, 0.7) !important;
    }
    
    .article-card:hover .article-card-title {
        text-shadow: 0 4px 20px rgba(6, 182, 212, 0.8);
    }
    
    .article-card-content {
        padding: 25px;
        position: relative;
        z-index: 2;
    }
    
    .article-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 20px;
        border-top: 1px solid rgba(6, 182, 212, 0.2);
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .article-card-meta {
        display: flex;
        align-items: center;
        gap: 15px;
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.9rem;
        flex-wrap: wrap;
    }
    
    body:not(.dark-mode) .article-card-meta {
        color: rgba(30, 41, 59, 0.6) !important;
    }
    
    .article-card-meta i {
        color: #06b6d4;
        font-size: 1rem;
    }
    
    .article-card-button {
        padding: 12px 24px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: #000;
        border-radius: 14px;
        font-weight: 700;
        font-size: 0.9rem;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
        box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
    }
    
    .article-card:hover .article-card-button {
        transform: translateX(8px) scale(1.05);
        box-shadow: 0 8px 30px rgba(6, 182, 212, 0.6);
        gap: 12px;
    }
    
    .no-articles {
        text-align: center;
        padding: 120px 20px;
        background: rgba(15, 23, 42, 0.5);
        border-radius: 32px;
        border: 2px dashed rgba(6, 182, 212, 0.3);
        backdrop-filter: blur(20px);
    }
    
    body:not(.dark-mode) .no-articles {
        background: rgba(255, 255, 255, 0.9) !important;
        border-color: rgba(6, 182, 212, 0.25) !important;
    }
    
    .no-articles i {
        font-size: 6rem;
        color: rgba(6, 182, 212, 0.4);
        margin-bottom: 30px;
        display: block;
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    
    .no-articles h3 {
        font-family: 'Poppins', sans-serif;
        font-size: 2.5rem;
        color: #fff;
        margin-bottom: 15px;
        font-weight: 900;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .no-articles p {
        color: rgba(255, 255, 255, 0.7);
        font-size: 1.2rem;
    }
    
    body:not(.dark-mode) .no-articles p {
        color: rgba(30, 41, 59, 0.7) !important;
    }
    
    /* Pagination moderne */
    .pagination-wrapper {
        margin-top: 80px;
        display: flex;
        justify-content: center;
    }
    
    .pagination {
        display: flex;
        align-items: center;
        gap: 10px;
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .pagination li {
        display: inline-block;
    }
    
    .pagination a,
    .pagination span {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 45px;
        height: 45px;
        padding: 0 15px;
        background: rgba(15, 23, 42, 0.7);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 12px;
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }
    
    body:not(.dark-mode) .pagination a,
    body:not(.dark-mode) .pagination span {
        background: rgba(255, 255, 255, 0.9) !important;
        border-color: rgba(6, 182, 212, 0.25) !important;
        color: rgba(30, 41, 59, 0.8) !important;
    }
    
    /* Buttons adaptation */
    body:not(.dark-mode) .article-card-button {
        background: linear-gradient(135deg, #06b6d4, #14b8a6) !important;
        color: #000 !important;
    }
    
    body:not(.dark-mode) .article-card-category {
        background: rgba(6, 182, 212, 0.15) !important;
        border-color: rgba(6, 182, 212, 0.3) !important;
    }
    
    .pagination a:hover {
        background: rgba(6, 182, 212, 0.2);
        border-color: rgba(6, 182, 212, 0.5);
        color: #06b6d4;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(6, 182, 212, 0.3);
    }
    
    .pagination .active span {
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border-color: rgba(6, 182, 212, 0.6);
        color: #000;
        font-weight: 700;
        box-shadow: 0 4px 15px rgba(6, 182, 212, 0.4);
    }
    
    .pagination .disabled span {
        opacity: 0.4;
        cursor: not-allowed;
    }
    
    @media (max-width: 1200px) {
        .articles-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .articles-grid {
            grid-template-columns: 1fr;
            gap: 25px;
        }
        
        .offers-hero {
            padding: 120px 20px 80px;
        }
        
        .article-card-content {
            padding: 20px;
        }
        
        .article-card-footer {
            flex-direction: column;
            align-items: stretch;
        }
        
        .article-card-button {
            width: 100%;
            justify-content: center;
        }
        
        .pagination {
            flex-wrap: wrap;
            justify-content: center;
        }
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="offers-hero">
    <div class="offers-hero-content">
        <h1>💼 {{ $category ? $category->name : 'Offres d\'Emploi' }}</h1>
        <p>Découvrez les meilleures offres d'emploi publiées au Sénégal. Nous ne recrutons pas directement mais publions les offres de recrutement existantes pour vous aider à trouver votre emploi idéal.</p>
    </div>
</section>

<!-- Offers Container -->
<div class="offers-container">
    @if($articles && $articles->count() > 0)
    <div class="articles-grid">
        @foreach($articles as $article)
        <a href="{{ route('emplois.article', $article->slug) }}" class="article-card">
            <div class="article-card-image-wrapper">
                @if($article->cover_image)
                <img src="{{ $article->cover_type === 'internal' ? \Illuminate\Support\Facades\Storage::url($article->cover_image) : $article->cover_image }}" 
                     alt="{{ $article->title }} - {{ $article->category->name }}" 
                     class="article-card-image"
                     loading="lazy"
                     onerror="this.src='https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=600&h=400&fit=crop'">
                @else
                <div class="article-card-image" style="display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, rgba(6, 182, 212, 0.3), rgba(20, 184, 166, 0.3));">
                    <i class="fas fa-image text-7xl text-cyan-400/50"></i>
                </div>
                @endif
                
                <div class="article-card-overlay">
                    <span class="article-card-category">
                        <i class="fas fa-folder"></i>{{ $article->category->name }}
                    </span>
                    <h3 class="article-card-title">{{ $article->title }}</h3>
                </div>
            </div>
            
            <div class="article-card-content">
                <div class="article-card-footer">
                    <div class="article-card-meta">
                        <span><i class="fas fa-calendar"></i> {{ $article->published_at ? $article->published_at->format('d/m/Y') : '' }}</span>
                        <span><i class="fas fa-eye"></i> {{ $article->views }}</span>
                    </div>
                    <span class="article-card-button">
                        Voir <i class="fas fa-arrow-right"></i>
                    </span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    
    @if($articles->hasPages())
    <div class="pagination-wrapper">
        {{ $articles->links() }}
    </div>
    @endif
    
    @else
    <div class="no-articles">
        <i class="fas fa-newspaper"></i>
        <h3>Aucun article disponible</h3>
        <p>Il n'y a pas encore d'articles publiés dans cette catégorie.</p>
    </div>
    @endif
</div>
@endsection
