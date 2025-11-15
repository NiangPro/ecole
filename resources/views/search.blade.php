@extends('layouts.app')

@section('title', 'Recherche' . ($query ? " : {$query}" : '') . ' | NiangProgrammeur')
@section('meta_description', 'Recherchez parmi nos formations et articles d\'emploi.')

@section('styles')
<style>
    .search-hero {
        padding: 120px 20px 60px;
        text-align: center;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(20, 184, 166, 0.1) 100%);
        border-bottom: 1px solid rgba(6, 182, 212, 0.2);
    }
    
    .search-hero h1 {
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 900;
        margin-bottom: 30px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .search-form-large {
        max-width: 600px;
        margin: 0 auto;
        position: relative;
    }
    
    .search-input-large {
        width: 100%;
        padding: 16px 60px 16px 20px;
        background: rgba(15, 23, 42, 0.8);
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 12px;
        color: #fff;
        font-size: 1rem;
        transition: all 0.3s ease;
        outline: none;
    }
    
    .search-input-large:focus {
        border-color: #06b6d4;
        box-shadow: 0 0 20px rgba(6, 182, 212, 0.4);
    }
    
    .search-button-large {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border: none;
        color: #000;
        padding: 12px 20px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 700;
        transition: all 0.3s ease;
    }
    
    .search-button-large:hover {
        transform: translateY(-50%) scale(1.05);
        box-shadow: 0 4px 15px rgba(6, 182, 212, 0.4);
    }
    
    .results-section {
        max-width: 1200px;
        margin: 60px auto;
        padding: 0 20px;
    }
    
    .results-header {
        margin-bottom: 30px;
    }
    
    .results-count {
        color: rgba(255, 255, 255, 0.7);
        font-size: 1.1rem;
    }
    
    .results-count strong {
        color: #06b6d4;
    }
    
    .results-grid {
        display: grid;
        gap: 25px;
    }
    
    .result-card {
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 16px;
        padding: 25px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: block;
        color: inherit;
    }
    
    .result-card:hover {
        border-color: rgba(6, 182, 212, 0.5);
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(6, 182, 212, 0.2);
    }
    
    .result-type {
        display: inline-block;
        padding: 4px 12px;
        background: rgba(6, 182, 212, 0.2);
        color: #06b6d4;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 12px;
    }
    
    .result-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 10px;
    }
    
    .result-description {
        color: rgba(255, 255, 255, 0.7);
        line-height: 1.6;
        margin-bottom: 15px;
    }
    
    .result-meta {
        display: flex;
        align-items: center;
        gap: 15px;
        color: rgba(255, 255, 255, 0.5);
        font-size: 0.85rem;
    }
    
    .no-results {
        text-align: center;
        padding: 60px 20px;
    }
    
    .no-results-icon {
        font-size: 4rem;
        color: rgba(6, 182, 212, 0.3);
        margin-bottom: 20px;
    }
    
    .no-results h2 {
        font-size: 1.5rem;
        margin-bottom: 15px;
        color: rgba(255, 255, 255, 0.8);
    }
    
    .no-results p {
        color: rgba(255, 255, 255, 0.6);
        margin-bottom: 30px;
    }
</style>
@endsection

@section('content')
<!-- Search Hero -->
<section class="search-hero">
    <div class="container mx-auto">
        <h1>Recherche</h1>
        <form action="{{ route('search') }}" method="GET" class="search-form-large" role="search">
            <input type="search" name="q" value="{{ $query }}" 
                   placeholder="Rechercher une formation, un article..." 
                   class="search-input-large"
                   aria-label="Rechercher sur le site"
                   autofocus>
            <button type="submit" class="search-button-large" aria-label="Lancer la recherche">
                <i class="fas fa-search mr-2"></i>Rechercher
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
    </div>
    
    @if($results['total'] > 0)
    <div class="results-grid">
        @foreach($results['formations'] as $formation)
        <a href="{{ $formation['url'] }}" class="result-card">
            <span class="result-type">
                <i class="fas fa-graduation-cap mr-1"></i>Formation
            </span>
            <h3 class="result-title">{{ $formation['name'] }}</h3>
            <p class="result-description">{{ $formation['description'] }}</p>
            <div class="result-meta">
                <span><i class="fas fa-clock mr-1"></i>Gratuit</span>
                <span><i class="fas fa-book mr-1"></i>Cours complet</span>
            </div>
        </a>
        @endforeach
        
        @foreach($results['articles'] as $article)
        <a href="{{ route('emplois.article', $article->slug) }}" class="result-card">
            <span class="result-type">
                <i class="fas fa-briefcase mr-1"></i>Article d'emploi
            </span>
            <h3 class="result-title">{{ $article->title }}</h3>
            <p class="result-description">{{ $article->excerpt ?? Str::limit(strip_tags($article->content), 150) }}</p>
            <div class="result-meta">
                <span><i class="fas fa-folder mr-1"></i>{{ $article->category->name }}</span>
                <span><i class="fas fa-calendar mr-1"></i>{{ $article->published_at ? $article->published_at->format('d/m/Y') : $article->created_at->format('d/m/Y') }}</span>
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
        <a href="{{ route('home') }}#technologies" class="inline-block px-6 py-3 bg-gradient-to-r from-cyan-500 to-teal-600 text-white font-semibold rounded-lg hover:shadow-lg transition">
            <i class="fas fa-arrow-left mr-2"></i>Voir toutes les formations
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

