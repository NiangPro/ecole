@extends('layouts.app')

@section('title', 'Recherche' . ($query ? " : {$query}" : '') . ' | NiangProgrammeur')
@section('meta_description', 'Recherchez parmi nos formations et articles d\'emploi.')
@push('meta')
    <link rel="canonical" href="{{ route('search', ['q' => $query]) }}">
@endpush

@section('styles')
<style>
    /* Fonts chargées via preload dans layouts.app - pas de @import bloquant */
    
    * {
        box-sizing: border-box;
    }
    
    /* Body background */
    body:not(.dark-mode) {
        background: #ffffff !important;
    }
    
    body.dark-mode {
        background: #0a0a0f !important;
    }
    
    /* Search Hero Section - Ultra Moderne */
    .search-hero {
        padding: 140px 20px 80px;
        text-align: center;
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.95) 0%, rgba(30, 41, 59, 0.95) 100%);
        border-bottom: 2px solid rgba(6, 182, 212, 0.2);
        position: relative;
        overflow: hidden;
    }
    
    body:not(.dark-mode) .search-hero {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.05) 0%, rgba(20, 184, 166, 0.05) 100%) !important;
        border-bottom-color: rgba(6, 182, 212, 0.15) !important;
    }
    
    .search-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 50% 50%, rgba(6, 182, 212, 0.1), transparent 70%);
        pointer-events: none;
    }
    
    body:not(.dark-mode) .search-hero::before {
        background: radial-gradient(circle at 50% 50%, rgba(6, 182, 212, 0.05), transparent 70%) !important;
    }
    
    .search-hero-content {
        position: relative;
        z-index: 1;
        max-width: 800px;
        margin: 0 auto;
    }
    
    .search-hero h1 {
        font-family: 'Inter', sans-serif;
        font-size: clamp(2.5rem, 5vw, 4rem);
        font-weight: 900;
        margin-bottom: 30px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: -0.02em;
    }
    
    /* Search Form - Glassmorphism */
    .search-form-large {
        max-width: 700px;
        margin: 0 auto;
        position: relative;
    }
    
    .search-input-large {
        width: 100%;
        padding: 20px 70px 20px 25px;
        background: rgba(15, 23, 42, 0.8);
        backdrop-filter: blur(20px);
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 16px;
        color: #fff;
        font-size: 1.1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        outline: none;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }
    
    body:not(.dark-mode) .search-input-large {
        background: rgba(255, 255, 255, 0.9) !important;
        border-color: rgba(6, 182, 212, 0.3) !important;
        color: rgba(30, 41, 59, 0.9) !important;
        box-shadow: 0 10px 40px rgba(6, 182, 212, 0.1) !important;
    }
    
    .search-input-large:focus {
        border-color: #06b6d4;
        box-shadow: 0 0 30px rgba(6, 182, 212, 0.4);
        transform: translateY(-2px);
    }
    
    body:not(.dark-mode) .search-input-large:focus {
        box-shadow: 0 0 30px rgba(6, 182, 212, 0.3) !important;
    }
    
    .search-input-large::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }
    
    body:not(.dark-mode) .search-input-large::placeholder {
        color: rgba(30, 41, 59, 0.5) !important;
    }
    
    .search-button-large {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border: none;
        color: #000;
        padding: 14px 24px;
        border-radius: 12px;
        cursor: pointer;
        font-weight: 700;
        font-size: 1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
    }
    
    .search-button-large:hover {
        transform: translateY(-50%) scale(1.05);
        box-shadow: 0 8px 25px rgba(6, 182, 212, 0.5);
    }
    
    .search-button-large:active {
        transform: translateY(-50%) scale(0.98);
    }
    
    /* Results Section */
    .results-section {
        max-width: 1400px;
        margin: 80px auto;
        padding: 0 20px;
    }
    
    .results-header {
        margin-bottom: 40px;
    }
    
    .results-count {
        color: rgba(255, 255, 255, 0.8);
        font-size: 1.2rem;
        font-weight: 500;
    }
    
    body:not(.dark-mode) .results-count {
        color: rgba(30, 41, 59, 0.8) !important;
    }
    
    .results-count strong {
        color: #06b6d4;
        font-weight: 700;
    }
    
    /* Filters - Modern Design */
    .filters-container {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: center;
        margin-top: 20px;
        padding: 20px;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 16px;
    }
    
    body:not(.dark-mode) .filters-container {
        background: rgba(255, 255, 255, 0.9) !important;
        border-color: rgba(6, 182, 212, 0.25) !important;
        box-shadow: 0 10px 40px rgba(6, 182, 212, 0.1) !important;
    }
    
    .filter-select {
        padding: 10px 16px;
        background: rgba(15, 23, 42, 0.8);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(6, 182, 212, 0.3);
        border-radius: 10px;
        color: #fff;
        font-size: 0.95rem;
        outline: none;
        cursor: pointer;
        transition: all 0.3s ease;
        flex: 1;
        min-width: 150px;
    }
    
    body:not(.dark-mode) .filter-select {
        background: rgba(255, 255, 255, 0.9) !important;
        border-color: rgba(6, 182, 212, 0.3) !important;
        color: rgba(30, 41, 59, 0.9) !important;
    }
    
    .filter-select:focus {
        border-color: #06b6d4;
        box-shadow: 0 0 15px rgba(6, 182, 212, 0.3);
    }
    
    .filter-button {
        padding: 10px 20px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border: none;
        border-radius: 10px;
        color: #000;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
        box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
    }
    
    .filter-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(6, 182, 212, 0.4);
    }
    
    /* Results Grid - Ultra Modern Cards */
    .results-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 30px;
        margin-top: 40px;
    }
    
    @media (max-width: 768px) {
        .results-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
    }
    
    .result-card {
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(20px);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 24px;
        padding: 0;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        display: block;
        color: inherit;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    }
    
    .result-card-image-wrapper {
        width: 100%;
        height: 200px;
        overflow: hidden;
        position: relative;
    }
    
    .result-card-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .result-card:hover .result-card-image {
        transform: scale(1.1);
    }
    
    .result-card-image-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
    }
    
    .result-card-image-placeholder i {
        font-size: 4rem;
        color: rgba(6, 182, 212, 0.4);
    }
    
    .result-card-content {
        padding: 30px;
    }
    
    body:not(.dark-mode) .result-card {
        background: rgba(255, 255, 255, 0.9) !important;
        border-color: rgba(6, 182, 212, 0.25) !important;
        box-shadow: 0 10px 40px rgba(6, 182, 212, 0.1) !important;
    }
    
    .result-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(6, 182, 212, 0.1), transparent);
        transition: left 0.6s;
    }
    
    .result-card:hover {
        border-color: rgba(6, 182, 212, 0.6);
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 60px rgba(6, 182, 212, 0.3);
    }
    
    body:not(.dark-mode) .result-card:hover {
        box-shadow: 0 20px 60px rgba(6, 182, 212, 0.2) !important;
    }
    
    .result-card:hover::before {
        left: 100%;
    }
    
    .result-type {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
        color: #06b6d4;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 16px;
        border: 1px solid rgba(6, 182, 212, 0.3);
    }
    
    body:not(.dark-mode) .result-type {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.1)) !important;
        border-color: rgba(6, 182, 212, 0.25) !important;
    }
    
    .result-title {
        font-size: 1.4rem;
        font-weight: 800;
        color: #fff;
        margin-bottom: 12px;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    body:not(.dark-mode) .result-title {
        color: rgba(30, 41, 59, 0.9) !important;
    }
    
    .result-description {
        color: rgba(255, 255, 255, 0.75);
        line-height: 1.7;
        margin-bottom: 20px;
        font-size: 0.95rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    body:not(.dark-mode) .result-description {
        color: rgba(30, 41, 59, 0.7) !important;
    }
    
    .result-meta {
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.9rem;
        padding-top: 16px;
        border-top: 1px solid rgba(6, 182, 212, 0.2);
    }
    
    body:not(.dark-mode) .result-meta {
        color: rgba(30, 41, 59, 0.6) !important;
        border-top-color: rgba(6, 182, 212, 0.15) !important;
    }
    
    .result-meta span {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .result-meta i {
        color: #06b6d4;
        font-size: 0.85rem;
    }
    
    /* No Results */
    .no-results {
        text-align: center;
        padding: 100px 20px;
        background: rgba(15, 23, 42, 0.5);
        backdrop-filter: blur(20px);
        border: 2px dashed rgba(6, 182, 212, 0.3);
        border-radius: 24px;
    }
    
    body:not(.dark-mode) .no-results {
        background: rgba(255, 255, 255, 0.9) !important;
        border-color: rgba(6, 182, 212, 0.25) !important;
    }
    
    .no-results-icon {
        font-size: 5rem;
        color: rgba(6, 182, 212, 0.4);
        margin-bottom: 30px;
        animation: pulse 2s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.4; }
        50% { transform: scale(1.1); opacity: 0.6; }
    }
    
    .no-results h2 {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 20px;
        color: rgba(255, 255, 255, 0.9);
    }
    
    body:not(.dark-mode) .no-results h2 {
        color: rgba(30, 41, 59, 0.9) !important;
    }
    
    .no-results p {
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 40px;
        font-size: 1.1rem;
    }
    
    body:not(.dark-mode) .no-results p {
        color: rgba(30, 41, 59, 0.7) !important;
    }
    
    .no-results-button {
        display: inline-block;
        padding: 14px 28px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: #000;
        font-weight: 700;
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
    }
    
    .no-results-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(6, 182, 212, 0.5);
    }
</style>
@endsection

@section('content')
<!-- Search Hero -->
<section class="search-hero">
    <div class="search-hero-content">
        <h1>🔍 Recherche</h1>
        <form action="{{ route('search') }}" method="GET" class="search-form-large" role="search">
            <input type="search" name="q" value="{{ $query }}" 
                   placeholder="Rechercher une formation, un article..." 
                   class="search-input-large"
                   aria-label="Rechercher sur le site"
                   autofocus>
            <button type="submit" class="search-button-large" aria-label="Lancer la recherche">
                <i class="fas fa-search"></i> Rechercher
            </button>
        </form>
    </div>
</section>

<!-- Results Section -->
@if($query)
<section class="results-section">
    <div class="results-header">
        <p class="results-count">
            <strong>{{ $results['total'] }}</strong> résultat(s) pour "<strong>{{ $query }}</strong>"
        </p>
        
        <!-- Filtres avancés -->
        <form action="{{ route('search') }}" method="GET" class="filters-container">
            <input type="hidden" name="q" value="{{ $query }}">
            
            <select name="category" class="filter-select">
                <option value="">Toutes les catégories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $category == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            
            <select name="date" class="filter-select">
                <option value="">Toutes les dates</option>
                <option value="today" {{ $dateFilter == 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                <option value="week" {{ $dateFilter == 'week' ? 'selected' : '' }}>Cette semaine</option>
                <option value="month" {{ $dateFilter == 'month' ? 'selected' : '' }}>Ce mois</option>
                <option value="year" {{ $dateFilter == 'year' ? 'selected' : '' }}>Cette année</option>
            </select>
            
            <select name="sort" class="filter-select">
                <option value="relevance" {{ $sortBy == 'relevance' ? 'selected' : '' }}>Pertinence</option>
                <option value="date" {{ $sortBy == 'date' ? 'selected' : '' }}>Plus récent</option>
                <option value="views" {{ $sortBy == 'views' ? 'selected' : '' }}>Plus vus</option>
                <option value="title" {{ $sortBy == 'title' ? 'selected' : '' }}>Titre (A-Z)</option>
            </select>
            
            <button type="submit" class="filter-button">
                <i class="fas fa-filter"></i> Appliquer
            </button>
        </form>
    </div>
    
    @if($results['total'] > 0)
    <div class="results-grid">
        @foreach($results['formations'] as $formation)
        <a href="{{ $formation['url'] }}" class="result-card">
            <div class="result-card-image-wrapper">
                @php
                    $formationImages = [
                        'HTML5' => 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?w=600&h=400&fit=crop',
                        'CSS3' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&h=400&fit=crop',
                        'JavaScript' => 'https://images.unsplash.com/photo-1579468118864-1b9ea3c0db4a?w=600&h=400&fit=crop',
                        'PHP' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=600&h=400&fit=crop',
                        'Bootstrap' => 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=600&h=400&fit=crop',
                        'Git' => 'https://images.unsplash.com/photo-1618401471353-b98afee0b2eb?w=600&h=400&fit=crop',
                        'WordPress' => 'https://images.unsplash.com/photo-1531297484001-80022131f5a1?w=600&h=400&fit=crop',
                        'Intelligence Artificielle' => 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=600&h=400&fit=crop',
                    ];
                    $formationImage = $formationImages[$formation['name']] ?? 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=600&h=400&fit=crop';
                @endphp
                <img src="{{ $formationImage }}" 
                     alt="{{ $formation['name'] }} - Formation"
                     class="result-card-image"
                     loading="lazy"
                     onerror="this.parentElement.innerHTML='<div class=\'result-card-image-placeholder\'><i class=\'fas fa-graduation-cap\'></i></div>'">
            </div>
            <div class="result-card-content">
                <span class="result-type">
                    <i class="fas fa-graduation-cap"></i>
                    Formation
                </span>
                <h3 class="result-title">{{ $formation['name'] }}</h3>
                <p class="result-description">{{ $formation['description'] }}</p>
                <div class="result-meta">
                    <span><i class="fas fa-clock"></i> Gratuit</span>
                    <span><i class="fas fa-book"></i> Cours complet</span>
                </div>
            </div>
        </a>
        @endforeach
        
        @foreach($results['articles'] as $article)
        <a href="{{ route('emplois.article', $article->slug) }}" class="result-card">
            <div class="result-card-image-wrapper">
                @if($article->cover_image)
                <img src="{{ $article->cover_type === 'internal' ? \Illuminate\Support\Facades\Storage::url($article->cover_image) : $article->cover_image }}" 
                     alt="{{ $article->title }} - {{ $article->category->name }}" 
                     class="result-card-image"
                     loading="lazy"
                     onerror="this.parentElement.innerHTML='<div class=\'result-card-image-placeholder\'><i class=\'fas fa-briefcase\'></i></div>'">
                @else
                <div class="result-card-image-placeholder">
                    <i class="fas fa-briefcase"></i>
                </div>
                @endif
            </div>
            <div class="result-card-content">
                <span class="result-type">
                    <i class="fas fa-briefcase"></i>
                    Article d'emploi
                </span>
                <h3 class="result-title">{{ $article->title }}</h3>
                <p class="result-description">{{ $article->excerpt ?? Str::limit(strip_tags($article->content), 150) }}</p>
                <div class="result-meta">
                    <span><i class="fas fa-folder"></i> {{ $article->category->name }}</span>
                    <span><i class="fas fa-calendar"></i> {{ $article->published_at ? $article->published_at->format('d/m/Y') : $article->created_at->format('d/m/Y') }}</span>
                    @if($article->views > 0)
                    <span><i class="fas fa-eye"></i> {{ $article->views }} vues</span>
                    @endif
                </div>
            </div>
        </a>
        @endforeach
    </div>
    @else
    <div class="no-results">
        <div class="no-results-icon">
            <i class="fas fa-search"></i>
        </div>
        <h2>Aucun résultat trouvé</h2>
        <p>Essayez avec d'autres mots-clés ou parcourez nos formations ci-dessous.</p>
        <a href="{{ route('home') }}#technologies" class="no-results-button">
            <i class="fas fa-arrow-left"></i> Voir toutes les formations
        </a>
    </div>
    @endif
</section>
@else
<section class="results-section">
    <div class="no-results">
        <div class="no-results-icon">
            <i class="fas fa-search"></i>
        </div>
        <h2>Recherchez sur NiangProgrammeur</h2>
        <p>Entrez un mot-clé dans la barre de recherche ci-dessus pour trouver des formations ou des articles d'emploi.</p>
    </div>
</section>
@endif
@endsection
