@extends('layouts.app')

@section('title', 'Offres d\'Emploi | NiangProgrammeur')
@section('meta_description', 'Consultez les meilleures offres d\'emploi publiées au Sénégal. Nous ne recrutons pas mais publions les offres de recrutement existantes.')

@section('styles')
<style>
    * {
        box-sizing: border-box;
    }
    
    .offers-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
        padding: 120px 20px 80px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .offers-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 50% 50%, rgba(6, 182, 212, 0.15), transparent 70%);
    }
    
    .offers-hero h1 {
        font-size: clamp(2.5rem, 5vw, 4rem);
        font-weight: 900;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 20px;
        position: relative;
        z-index: 1;
    }
    
    .offers-hero p {
        font-size: 1.3rem;
        color: rgba(255, 255, 255, 0.85);
        max-width: 700px;
        margin: 0 auto;
        position: relative;
        z-index: 1;
    }
    
    .offers-container {
        max-width: 1600px;
        margin: 0 auto;
        padding: 60px 20px;
    }
    
    .articles-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 25px;
        margin-top: 40px;
    }
    
    .article-card {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.98), rgba(30, 41, 59, 0.98));
        border: 2px solid rgba(6, 182, 212, 0.25);
        border-radius: 28px;
        overflow: hidden;
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        display: block;
        height: 100%;
        position: relative;
        backdrop-filter: blur(10px);
    }
    
    .article-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, #06b6d4, #14b8a6, #06b6d4);
        background-size: 200% 100%;
        transform: scaleX(0);
        transition: transform 0.6s ease;
        animation: shimmer 3s infinite;
    }
    
    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }
    
    .article-card:hover {
        transform: translateY(-15px) scale(1.02);
        border-color: rgba(6, 182, 212, 0.7);
        box-shadow: 0 30px 80px rgba(6, 182, 212, 0.4);
    }
    
    .article-card:hover::before {
        transform: scaleX(1);
    }
    
    .article-card-image-wrapper {
        position: relative;
        width: 100%;
        height: 280px;
        overflow: hidden;
    }
    
    .article-card-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
        transition: transform 0.5s ease;
    }
    
    .article-card:hover .article-card-image {
        transform: scale(1.15);
    }
    
    .article-card-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.95) 0%, rgba(0, 0, 0, 0.7) 50%, transparent 100%);
        padding: 25px 20px 20px;
        transform: translateY(0);
        transition: transform 0.3s ease;
    }
    
    .article-card:hover .article-card-overlay {
        transform: translateY(-5px);
    }
    
    .article-card-category {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        background: rgba(6, 182, 212, 0.25);
        color: #06b6d4;
        border-radius: 18px;
        font-size: 0.75rem;
        font-weight: 700;
        margin-bottom: 12px;
        border: 1px solid rgba(6, 182, 212, 0.4);
        backdrop-filter: blur(10px);
    }
    
    .article-card-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: #fff;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
    }
    
    .article-card-content {
        padding: 20px;
    }
    
    .article-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 20px;
        border-top: 1px solid rgba(6, 182, 212, 0.2);
    }
    
    .article-card-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.85rem;
        flex-wrap: wrap;
    }
    
    .article-card-meta i {
        color: #06b6d4;
        font-size: 0.9rem;
    }
    
    .article-card-button {
        padding: 10px 22px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: #000;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.85rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }
    
    .article-card:hover .article-card-button {
        transform: translateX(5px);
        box-shadow: 0 8px 25px rgba(6, 182, 212, 0.5);
    }
    
    .no-articles {
        text-align: center;
        padding: 100px 20px;
        background: rgba(15, 23, 42, 0.5);
        border-radius: 28px;
        border: 2px dashed rgba(6, 182, 212, 0.3);
    }
    
    .no-articles i {
        font-size: 5rem;
        color: rgba(6, 182, 212, 0.5);
        margin-bottom: 25px;
    }
    
    .no-articles h3 {
        font-size: 2rem;
        color: #fff;
        margin-bottom: 12px;
        font-weight: 800;
    }
    
    .no-articles p {
        color: rgba(255, 255, 255, 0.7);
        font-size: 1.1rem;
    }
    
    .pagination-wrapper {
        margin-top: 60px;
        display: flex;
        justify-content: center;
    }
    
    @media (max-width: 1400px) {
        .articles-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    @media (max-width: 1024px) {
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
            padding: 100px 20px 60px;
        }
        
        .article-card-content {
            padding: 24px;
        }
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="offers-hero">
    <div class="container mx-auto">
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
                     alt="{{ $article->title }}" 
                     class="article-card-image"
                     onerror="this.src='https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=600&h=400&fit=crop'">
                @else
                <div class="article-card-image" style="display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));">
                    <i class="fas fa-image text-6xl text-cyan-400/50"></i>
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
