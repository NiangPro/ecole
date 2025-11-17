@extends('layouts.app')

@section('title', 'Emplois & Opportunités | NiangProgrammeur')
@section('meta_description', 'Découvrez les meilleures offres d\'emploi, bourses d\'études, opportunités de carrière et candidatures spontanées au Sénégal.')

@section('styles')
<style>
    * {
        box-sizing: border-box;
    }
    
    .jobs-hero {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.95) 0%, rgba(30, 41, 59, 0.95) 100%),
                    url('https://images.unsplash.com/photo-1521737604893-d14cc237f11d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80') center/cover no-repeat;
        background-attachment: fixed;
        padding: 140px 20px 100px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    body:not(.dark-mode) .jobs-hero {
        background: linear-gradient(135deg, rgba(30, 41, 59, 0.4) 0%, rgba(51, 65, 85, 0.5) 100%),
                    url('https://images.unsplash.com/photo-1521737604893-d14cc237f11d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80') center/cover no-repeat !important;
    }
    
    .jobs-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 30%, rgba(6, 182, 212, 0.25), transparent 50%),
            radial-gradient(circle at 80% 70%, rgba(20, 184, 166, 0.25), transparent 50%),
            rgba(15, 23, 42, 0.7);
        pointer-events: none;
    }
    
    body:not(.dark-mode) .jobs-hero::before {
        background: 
            radial-gradient(circle at 20% 30%, rgba(6, 182, 212, 0.2), transparent 50%),
            radial-gradient(circle at 80% 70%, rgba(20, 184, 166, 0.2), transparent 50%),
            rgba(30, 41, 59, 0.6) !important;
    }
    
    .jobs-hero h1 {
        font-size: clamp(3rem, 6vw, 5rem);
        font-weight: 900;
        background: linear-gradient(135deg, #06b6d4, #14b8a6, #06b6d4);
        background-size: 200% auto;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 25px;
        position: relative;
        z-index: 1;
        animation: gradient 3s ease infinite;
    }
    
    @keyframes gradient {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }
    
    .jobs-hero p {
        font-size: 1.4rem;
        color: rgba(255, 255, 255, 0.95);
        max-width: 800px;
        margin: 0 auto;
        position: relative;
        z-index: 1;
        line-height: 1.8;
        text-shadow: 0 1px 5px rgba(0, 0, 0, 0.2);
    }
    
    body:not(.dark-mode) .jobs-hero p {
        color: rgba(255, 255, 255, 0.95) !important;
        text-shadow: 0 1px 5px rgba(0, 0, 0, 0.3) !important;
    }
    
    .jobs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 30px;
        max-width: 1400px;
        margin: 80px auto;
        padding: 0 20px;
    }
    
    .job-card {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.95), rgba(30, 41, 59, 0.95));
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 24px;
        padding: 0;
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        text-decoration: none;
        display: block;
        height: 100%;
    }
    
    body:not(.dark-mode) .job-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95)) !important;
        border-color: rgba(6, 182, 212, 0.25) !important;
        box-shadow: 0 10px 40px rgba(6, 182, 212, 0.1) !important;
    }
    
    .job-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, #06b6d4, #14b8a6, #06b6d4);
        background-size: 200% auto;
        transform: scaleX(0);
        transition: transform 0.5s ease;
        animation: gradient 3s ease infinite;
    }
    
    .job-card:hover {
        transform: translateY(-15px) scale(1.02);
        border-color: rgba(6, 182, 212, 0.6);
        box-shadow: 0 25px 70px rgba(6, 182, 212, 0.4);
    }
    
    .job-card:hover::before {
        transform: scaleX(1);
    }
    
    .job-card-image {
        width: 100%;
        height: 220px;
        object-fit: cover;
        display: block;
        position: relative;
    }
    
    .job-card-image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, transparent 0%, rgba(15, 23, 42, 0.9) 100%);
    }
    
    .job-card-content {
        padding: 30px;
        position: relative;
    }
    
    .job-icon {
        width: 70px;
        height: 70px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-bottom: 20px;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.25), rgba(20, 184, 166, 0.25));
        color: #06b6d4;
        box-shadow: 0 8px 20px rgba(6, 182, 212, 0.3);
    }
    
    .job-card h3 {
        font-size: 1.75rem;
        font-weight: 800;
        color: #fff;
        margin-bottom: 15px;
        line-height: 1.3;
    }
    
    body:not(.dark-mode) .job-card h3 {
        color: rgba(30, 41, 59, 0.9) !important;
    }
    
    .job-card p {
        color: rgba(255, 255, 255, 0.75);
        line-height: 1.8;
        margin-bottom: 25px;
        font-size: 1rem;
    }
    
    body:not(.dark-mode) .job-card p {
        color: rgba(30, 41, 59, 0.7) !important;
    }
    
    .job-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 18px;
        background: rgba(6, 182, 212, 0.15);
        color: #06b6d4;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 700;
        border: 1px solid rgba(6, 182, 212, 0.3);
        transition: all 0.3s ease;
    }
    
    .job-card:hover .job-badge {
        background: rgba(6, 182, 212, 0.25);
        border-color: rgba(6, 182, 212, 0.5);
        transform: translateX(5px);
    }
    
    .job-card-count {
        display: inline-block;
        margin-left: 8px;
        padding: 2px 8px;
        background: rgba(6, 182, 212, 0.3);
        border-radius: 12px;
        font-size: 0.85rem;
    }
    
    .stats-section {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.6), rgba(30, 41, 59, 0.6));
        padding: 80px 20px;
        margin-top: 60px;
        border-top: 2px solid rgba(6, 182, 212, 0.2);
    }
    
    body:not(.dark-mode) .stats-section {
        background: linear-gradient(135deg, rgba(248, 250, 252, 0.6), rgba(241, 245, 249, 0.6)) !important;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 40px;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .stat-card {
        text-align: center;
        padding: 40px 30px;
        background: rgba(15, 23, 42, 0.5);
        border-radius: 20px;
        border: 1px solid rgba(6, 182, 212, 0.2);
        transition: all 0.3s ease;
    }
    
    body:not(.dark-mode) .stat-card {
        background: rgba(255, 255, 255, 0.9) !important;
        border-color: rgba(6, 182, 212, 0.25) !important;
        box-shadow: 0 10px 40px rgba(6, 182, 212, 0.1) !important;
    }
    
    .stat-card:hover {
        transform: translateY(-10px);
        border-color: rgba(6, 182, 212, 0.5);
        box-shadow: 0 15px 40px rgba(6, 182, 212, 0.2);
    }
    
    .stat-number {
        font-size: 3.5rem;
        font-weight: 900;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 15px;
        line-height: 1;
    }
    
    .stat-label {
        color: rgba(255, 255, 255, 0.8);
        font-size: 1.1rem;
        font-weight: 600;
    }
    
    body:not(.dark-mode) .stat-label {
        color: rgba(30, 41, 59, 0.7) !important;
    }
    
    .recent-articles {
        max-width: 1400px;
        margin: 80px auto;
        padding: 0 20px;
    }
    
    .recent-articles h2 {
        font-size: 2.5rem;
        font-weight: 900;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 40px;
        text-align: center;
    }
    
    .articles-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
    }
    
    .article-mini-card {
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        display: block;
        position: relative;
        height: 100%;
    }
    
    body:not(.dark-mode) .article-mini-card {
        background: rgba(255, 255, 255, 0.9) !important;
        border-color: rgba(6, 182, 212, 0.25) !important;
        box-shadow: 0 10px 40px rgba(6, 182, 212, 0.1) !important;
    }
    
    .article-mini-card-image-wrapper {
        position: relative;
        width: 100%;
        height: 200px;
        overflow: hidden;
    }
    
    .article-mini-card-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .article-mini-card:hover .article-mini-card-image {
        transform: scale(1.15);
    }
    
    .article-mini-card-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, transparent 0%, rgba(0, 0, 0, 0.3) 50%, rgba(0, 0, 0, 0.85) 100%);
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 20px;
        transition: background 0.3s ease;
    }
    
    .article-mini-card:hover .article-mini-card-overlay {
        background: linear-gradient(to bottom, transparent 0%, rgba(6, 182, 212, 0.2) 30%, rgba(0, 0, 0, 0.9) 100%);
    }
    
    .article-mini-card h4 {
        color: #fff;
        font-size: 1.1rem;
        font-weight: 800;
        margin-bottom: 8px;
        line-height: 1.4;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.7);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .article-mini-card-content {
        padding: 20px;
    }
    
    .article-mini-card p {
        color: rgba(255, 255, 255, 0.75);
        font-size: 0.9rem;
        line-height: 1.6;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    body:not(.dark-mode) .article-mini-card p {
        color: rgba(30, 41, 59, 0.7) !important;
    }
    
    body:not(.dark-mode) .article-mini-card h4 {
        color: rgba(255, 255, 255, 0.95) !important;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.7) !important;
    }
    
    body:not(.dark-mode) .text-gray-400 {
        color: rgba(30, 41, 59, 0.6) !important;
    }
    
    .article-mini-card:hover {
        transform: translateY(-8px);
        border-color: rgba(6, 182, 212, 0.6);
        box-shadow: 0 15px 40px rgba(6, 182, 212, 0.3);
    }
    
    @media (max-width: 768px) {
        .jobs-grid {
            grid-template-columns: 1fr;
            gap: 25px;
            margin: 60px auto;
        }
        
        .jobs-hero {
            padding: 120px 20px 80px;
            background-attachment: scroll;
        }
        
        .jobs-hero h1 {
            font-size: 2.5rem;
        }
        
        .jobs-hero p {
            font-size: 1.1rem;
        }
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="jobs-hero">
    <div class="container mx-auto">
        <h1>🚀 Emplois & Opportunités</h1>
        <p>Trouvez votre prochaine opportunité de carrière au Sénégal. Nous publions les meilleures offres d'emploi, bourses d'études et opportunités professionnelles disponibles.</p>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ \App\Models\JobArticle::where('status', 'published')->count() }}+</div>
            <div class="stat-label">Articles publiés</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ \App\Models\Category::where('is_active', true)->count() }}</div>
            <div class="stat-label">Catégories actives</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ \App\Models\JobArticle::where('status', 'published')->sum('views') }}+</div>
            <div class="stat-label">Vues totales</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ \App\Models\JobArticle::where('status', 'published')->whereDate('published_at', '>=', now()->subDays(30))->count() }}</div>
            <div class="stat-label">Nouveaux articles (30j)</div>
        </div>
    </div>
</section>

<!-- Categories Grid -->
<div class="jobs-grid">
    @forelse($categories as $category)
    <a href="{{ route('emplois.offres') }}?category={{ $category->slug }}" class="job-card">
        @if($category->image)
        <div style="position: relative; height: 220px; overflow: hidden;">
            <img src="{{ $category->image_type === 'internal' ? \Illuminate\Support\Facades\Storage::url($category->image) : $category->image }}"
                 alt="{{ $category->name }} - Catégorie d'emploi"
                 loading="lazy" 
                 class="job-card-image">
            <div class="job-card-image-overlay"></div>
        </div>
        @endif
        <div class="job-card-content">
            <div class="job-icon">
                @if($category->icon)
                    <i class="{{ $category->icon }}"></i>
                @else
                    <i class="fas fa-folder"></i>
                @endif
            </div>
            <h3>{{ $category->name }}</h3>
            <p>{{ $category->description }}</p>
            <span class="job-badge">
                Voir les articles
                <span class="job-card-count">{{ $category->published_articles_count ?? 0 }}</span>
            </span>
        </div>
    </a>
    @empty
    <div class="col-span-full text-center py-20">
        <i class="fas fa-folder-open text-6xl text-gray-400 mb-4 block"></i>
        <p class="text-gray-400 text-xl">Aucune catégorie disponible</p>
    </div>
    @endforelse
</div>

<!-- Recent Articles -->
@if($recentArticles && $recentArticles->count() > 0)
<div class="recent-articles">
    <h2>📰 Articles Récents</h2>
    <div class="articles-grid">
        @foreach($recentArticles as $article)
        <a href="{{ route('emplois.article', $article->slug) }}" class="article-mini-card">
            <div class="article-mini-card-image-wrapper">
                @if($article->cover_image)
                <img src="{{ $article->cover_type === 'internal' ? \Illuminate\Support\Facades\Storage::url($article->cover_image) : $article->cover_image }}"
                     alt="{{ $article->title }} - {{ $article->category->name }}"
                     loading="lazy" 
                     class="article-mini-card-image"
                     onerror="this.src='https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=400&h=250&fit=crop'">
                @else
                <div class="article-mini-card-image" style="display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));">
                    <i class="fas fa-newspaper text-5xl text-cyan-400/50"></i>
                </div>
                @endif
                <div class="article-mini-card-overlay">
                    <h4>{{ $article->title }}</h4>
                </div>
            </div>
            <div class="article-mini-card-content">
                <p>{{ Str::limit($article->excerpt ?? strip_tags($article->content), 100) }}</p>
                <div class="mt-3 flex items-center gap-2 text-sm text-cyan-400">
                    <i class="fas fa-calendar"></i>
                    <span>{{ $article->published_at ? $article->published_at->format('d/m/Y') : '' }}</span>
                    <span class="mx-2">•</span>
                    <i class="fas fa-eye"></i>
                    <span>{{ $article->views }} vues</span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endif
@endsection
