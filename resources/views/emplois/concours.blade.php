@extends('layouts.app')

@section('title', 'Concours au Sénégal | NiangProgrammeur')
@section('meta_description', 'Découvrez tous les concours publics et privés au Sénégal : fonction publique, entreprises, écoles, forces de sécurité. Informations complètes et actualisées.')
@section('meta_keywords', 'concours sénégal, concours fonction publique, concours ENA, concours entreprises publiques, concours police, concours santé')

@section('styles')
<style>
    /* Fonts chargées via preload dans layouts.app - pas de @import bloquant */
    
    * {
        box-sizing: border-box;
    }
    
    .concours-hero {
        background: linear-gradient(135deg, #334155 0%, #475569 50%, #334155 100%);
        padding: 140px 20px 100px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .concours-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(6, 182, 212, 0.15) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }
    
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    .concours-hero-content {
        position: relative;
        z-index: 1;
        max-width: 900px;
        margin: 0 auto;
    }
    
    .concours-hero h1 {
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
    
    .concours-hero p {
        font-size: 1.3rem;
        color: rgba(255, 255, 255, 0.9);
        line-height: 1.8;
        font-weight: 400;
    }
    
    .concours-container {
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
        background: rgba(51, 65, 85, 0.6);
        backdrop-filter: blur(20px);
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 24px;
        overflow: hidden;
        transition: all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        text-decoration: none;
        display: block;
        height: 100%;
        position: relative;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
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
    }
    
    .article-card:hover::before {
        left: 100%;
    }
    
    .article-card:hover {
        transform: translateY(-10px);
        border-color: rgba(6, 182, 212, 0.6);
        box-shadow: 0 20px 60px rgba(6, 182, 212, 0.3);
    }
    
    .article-card-image {
        width: 100%;
        height: 220px;
        object-fit: cover;
        transition: transform 0.6s ease;
    }
    
    .article-card:hover .article-card-image {
        transform: scale(1.1);
    }
    
    .article-card-content {
        padding: 25px;
    }
    
    .article-card-category {
        display: inline-block;
        padding: 6px 14px;
        background: rgba(6, 182, 212, 0.2);
        color: #06b6d4;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        margin-bottom: 15px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .article-card-title {
        font-size: 1.3rem;
        font-weight: 800;
        color: #fff;
        margin-bottom: 12px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .article-card-excerpt {
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 15px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .article-card-meta {
        display: flex;
        align-items: center;
        gap: 15px;
        color: rgba(255, 255, 255, 0.5);
        font-size: 0.85rem;
        padding-top: 15px;
        border-top: 1px solid rgba(6, 182, 212, 0.2);
    }
    
    .article-card-meta span {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    @media (max-width: 1200px) {
        .articles-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .concours-hero {
            padding: 120px 20px 80px;
        }
        
        .concours-hero h1 {
            font-size: 2.5rem;
        }
        
        .concours-hero p {
            font-size: 1.1rem;
        }
        
        .concours-container {
            padding: 40px 20px;
        }
        
        .articles-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="concours-hero">
    <div class="concours-hero-content">
        <h1>
            <i class="fas fa-trophy mr-3"></i>
            Concours au Sénégal
        </h1>
        <p>
            Découvrez tous les concours publics et privés au Sénégal : fonction publique, entreprises publiques, écoles de formation, forces de sécurité. Informations complètes et actualisées pour réussir vos concours.
        </p>
    </div>
</section>

<!-- Concours Container -->
<div class="concours-container">
    @if($articles && $articles->count() > 0)
    <div class="articles-grid">
        @foreach($articles as $article)
        <a href="{{ route('emplois.article', $article->slug) }}" class="article-card">
            @if($article->cover_image)
            <img src="{{ $article->cover_type === 'internal' ? \Illuminate\Support\Facades\Storage::url($article->cover_image) : $article->cover_image }}" 
                 alt="{{ $article->title }} - {{ $article->category->name }}" 
                 class="article-card-image"
                 loading="lazy"
                 onerror="this.src='https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=600&h=400&fit=crop'">
            @else
            <div class="article-card-image" style="display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, rgba(6, 182, 212, 0.3), rgba(20, 184, 166, 0.3));">
                <i class="fas fa-trophy text-6xl text-cyan-400/50"></i>
            </div>
            @endif
            
            <div class="article-card-content">
                <span class="article-card-category">
                    <i class="fas fa-folder"></i>{{ $article->category->name ?? 'Concours' }}
                </span>
                <h3 class="article-card-title">{{ $article->title }}</h3>
                <p class="article-card-excerpt">{{ $article->excerpt ?? substr(strip_tags($article->content), 0, 150) }}...</p>
                <div class="article-card-meta">
                    <span><i class="fas fa-calendar"></i> {{ $article->published_at ? $article->published_at->format('d/m/Y') : '' }}</span>
                    <span><i class="fas fa-eye"></i> {{ $article->views }} vues</span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    
    <!-- Pagination -->
    @if($articles->hasPages())
    <div class="mt-12 flex justify-center">
        {{ $articles->links() }}
    </div>
    @endif
    @else
    <div style="text-align: center; padding: 60px 20px; color: rgba(255, 255, 255, 0.6);">
        <i class="fas fa-trophy text-6xl mb-6 opacity-50"></i>
        <p style="font-size: 1.2rem;">Aucun concours disponible pour le moment.</p>
        <p style="font-size: 0.95rem; margin-top: 10px;">Revenez bientôt pour découvrir les nouveaux concours !</p>
    </div>
    @endif
</div>
@endsection

