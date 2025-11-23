@extends('layouts.app')

@section('title', '70 Articles les Plus Récents - Emplois & Opportunités | NiangProgrammeur')
@section('meta_description', 'Découvrez les 70 articles les plus récents sur les emplois, bourses d\'études, opportunités de carrière et candidatures spontanées au Sénégal. URLs optimisées SEO et conformes aux exigences Google AdSense.')
@section('meta_keywords', 'articles emploi Sénégal, offres d\'emploi récentes, bourses d\'études, opportunités carrière, recrutement Sénégal, emploi Dakar, articles SEO')

@push('meta')
    <link rel="canonical" href="{{ route('emplois.recent-articles') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('emplois.recent-articles') }}">
    <meta property="og:title" content="70 Articles les Plus Récents - Emplois & Opportunités | NiangProgrammeur">
    <meta property="og:description" content="Découvrez les 70 articles les plus récents sur les emplois, bourses d'études, opportunités de carrière et candidatures spontanées au Sénégal.">
    <meta name="robots" content="index, follow">
    <meta name="googlebot" content="index, follow">
@endpush

@section('styles')
<style>
    * {
        box-sizing: border-box;
    }
    
    body:not(.dark-mode) {
        background: #ffffff !important;
    }
    
    body.dark-mode {
        background: #ffffff !important;
    }
    
    .recent-articles-hero {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(20, 184, 166, 0.1) 100%);
        border-bottom: 2px solid rgba(6, 182, 212, 0.2);
        padding: 60px 20px 40px;
        text-align: center;
        margin-bottom: 40px;
    }
    
    .recent-articles-hero h1 {
        font-size: 2.5rem;
        font-weight: 900;
        background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 15px;
    }
    
    .recent-articles-hero p {
        font-size: 1.1rem;
        color: rgba(0, 0, 0, 0.7);
        max-width: 800px;
        margin: 0 auto;
    }
    
    body.dark-mode .recent-articles-hero p {
        color: rgba(255, 255, 255, 0.8);
    }
    
    .articles-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px 60px;
    }
    
    .articles-intro {
        background: rgba(6, 182, 212, 0.05);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 16px;
        padding: 30px;
        margin-bottom: 40px;
        text-align: center;
    }
    
    .articles-intro h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #06b6d4;
        margin-bottom: 15px;
    }
    
    .articles-intro p {
        color: rgba(0, 0, 0, 0.7);
        line-height: 1.8;
        font-size: 1rem;
    }
    
    body.dark-mode .articles-intro p {
        color: rgba(255, 255, 255, 0.8);
    }
    
    .articles-list {
        display: grid;
        gap: 20px;
    }
    
    .article-card {
        background: #ffffff;
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 16px;
        padding: 25px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    body.dark-mode .article-card {
        background: rgba(15, 23, 42, 0.6);
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
        transform: translateY(-5px);
        border-color: rgba(6, 182, 212, 0.5);
        box-shadow: 0 10px 30px rgba(6, 182, 212, 0.2);
    }
    
    .article-header {
        display: flex;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 15px;
    }
    
    .article-number {
        min-width: 50px;
        height: 50px;
        border-radius: 12px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        font-size: 1.2rem;
        color: #000;
        flex-shrink: 0;
    }
    
    .article-content {
        flex: 1;
        min-width: 0;
    }
    
    .article-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #000;
        margin-bottom: 10px;
        line-height: 1.4;
    }
    
    body.dark-mode .article-title {
        color: #fff;
    }
    
    .article-title a {
        color: inherit;
        text-decoration: none;
        transition: color 0.3s ease;
    }
    
    .article-title a:hover {
        color: #06b6d4;
    }
    
    .article-url {
        margin-bottom: 12px;
    }
    
    .article-url a {
        color: #06b6d4;
        text-decoration: none;
        font-size: 0.95rem;
        word-break: break-all;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: color 0.3s ease;
    }
    
    .article-url a:hover {
        color: #14b8a6;
        text-decoration: underline;
    }
    
    .article-url a i {
        font-size: 0.85rem;
    }
    
    .article-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: center;
        font-size: 0.9rem;
        color: rgba(0, 0, 0, 0.6);
        margin-bottom: 10px;
    }
    
    body.dark-mode .article-meta {
        color: rgba(255, 255, 255, 0.6);
    }
    
    .article-meta span {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .article-meta i {
        color: #06b6d4;
    }
    
    .article-description {
        font-size: 0.95rem;
        color: rgba(0, 0, 0, 0.7);
        line-height: 1.6;
        margin-top: 10px;
    }
    
    body.dark-mode .article-description {
        color: rgba(255, 255, 255, 0.7);
    }
    
    .seo-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        border: 1px solid rgba(34, 197, 94, 0.3);
    }
    
    .copy-btn {
        padding: 8px 16px;
        background: rgba(6, 182, 212, 0.1);
        border: 1px solid rgba(6, 182, 212, 0.3);
        border-radius: 8px;
        color: #06b6d4;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.9rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 10px;
    }
    
    .copy-btn:hover {
        background: rgba(6, 182, 212, 0.2);
        border-color: rgba(6, 182, 212, 0.5);
        transform: translateY(-2px);
    }
    
    .copy-btn i {
        font-size: 0.85rem;
    }
    
    .seo-info-box {
        background: rgba(6, 182, 212, 0.05);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 16px;
        padding: 25px;
        margin-top: 40px;
        text-align: center;
    }
    
    .seo-info-box h3 {
        font-size: 1.2rem;
        font-weight: 700;
        color: #06b6d4;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    
    .seo-info-box p {
        color: rgba(0, 0, 0, 0.7);
        line-height: 1.8;
        font-size: 0.95rem;
    }
    
    body.dark-mode .seo-info-box p {
        color: rgba(255, 255, 255, 0.7);
    }
    
    @media (max-width: 768px) {
        .recent-articles-hero h1 {
            font-size: 1.8rem;
        }
        
        .article-header {
            flex-direction: column;
            gap: 15px;
        }
        
        .article-number {
            align-self: flex-start;
        }
        
        .article-meta {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<div class="recent-articles-hero">
    <div class="articles-container">
        <h1><i class="fas fa-newspaper mr-3"></i>70 Articles les Plus Récents</h1>
        <p>Découvrez notre sélection des 70 articles les plus récents sur les emplois, bourses d'études, opportunités de carrière et candidatures spontanées au Sénégal. Tous nos articles sont optimisés pour le SEO et conformes aux exigences Google AdSense.</p>
    </div>
</div>

<!-- Articles Container -->
<div class="articles-container">
    <!-- Introduction -->
    <div class="articles-intro">
        <h2><i class="fas fa-info-circle mr-2"></i>Articles Optimisés SEO</h2>
        <p>Cette page présente les 70 articles les plus récents publiés sur notre plateforme. Chaque article dispose d'une URL optimisée pour les moteurs de recherche, d'une meta description et d'un contenu de qualité conforme aux exigences Google AdSense. Tous les articles sont régulièrement mis à jour pour garantir la pertinence et l'actualité des informations.</p>
    </div>
    
    <!-- Articles List -->
    <div class="articles-list">
        @forelse($recentArticles as $index => $article)
        <article class="article-card" itemscope itemtype="https://schema.org/Article">
            <div class="article-header">
                <div class="article-number">{{ $index + 1 }}</div>
                <div class="article-content">
                    <h2 class="article-title">
                        <a href="{{ route('emplois.article', $article->slug) }}" 
                           itemprop="headline"
                           title="Lire l'article : {{ $article->title }}">
                            {{ $article->title }}
                        </a>
                    </h2>
                    
                    <div class="article-url">
                        <a href="{{ route('emplois.article', $article->slug) }}" 
                           target="_blank"
                           rel="noopener noreferrer"
                           itemprop="url"
                           title="Ouvrir l'article dans un nouvel onglet">
                            <i class="fas fa-external-link-alt"></i>
                            {{ url('/emplois/article/' . $article->slug) }}
                        </a>
                    </div>
                    
                    <div class="article-meta">
                        @if($article->category)
                        <span itemprop="articleSection">
                            <i class="fas fa-folder"></i>
                            {{ $article->category->name }}
                        </span>
                        @endif
                        <span itemprop="datePublished" content="{{ $article->published_at ? $article->published_at->toIso8601String() : $article->created_at->toIso8601String() }}">
                            <i class="fas fa-calendar"></i>
                            {{ $article->published_at ? $article->published_at->format('d/m/Y') : $article->created_at->format('d/m/Y') }}
                        </span>
                        <span>
                            <i class="fas fa-eye"></i>
                            {{ number_format($article->views) }} vue(s)
                        </span>
                        @if($article->meta_title)
                        <span class="seo-badge" title="Article optimisé SEO">
                            <i class="fas fa-check-circle"></i>
                            SEO Optimisé
                        </span>
                        @endif
                    </div>
                    
                    @if($article->meta_description)
                    <p class="article-description" itemprop="description">
                        {{ Str::limit($article->meta_description, 150) }}
                    </p>
                    @elseif($article->excerpt)
                    <p class="article-description" itemprop="description">
                        {{ Str::limit($article->excerpt, 150) }}
                    </p>
                    @endif
                    
                    <button class="copy-btn" 
                            onclick="copyToClipboard('{{ url('/emplois/article/' . $article->slug) }}', {{ $index + 1 }})"
                            title="Copier l'URL de l'article">
                        <i class="fas fa-copy"></i>
                        Copier l'URL
                    </button>
                </div>
            </div>
            
            <!-- Schema.org structured data -->
            <meta itemprop="author" content="NiangProgrammeur">
            <meta itemprop="publisher" content="NiangProgrammeur">
            @if($article->category)
            <meta itemprop="articleSection" content="{{ $article->category->name }}">
            @endif
        </article>
        @empty
        <div style="text-align: center; padding: 60px 20px; color: rgba(0, 0, 0, 0.5);">
            <i class="fas fa-newspaper" style="font-size: 4rem; margin-bottom: 20px; opacity: 0.5;"></i>
            <p style="font-size: 1.2rem;">Aucun article publié pour le moment</p>
        </div>
        @endforelse
    </div>
    
    <!-- SEO Information Box -->
    <div class="seo-info-box">
        <h3>
            <i class="fas fa-lightbulb"></i>
            Optimisation SEO & Conformité Google AdSense
        </h3>
        <p>
            Tous les articles présentés sur cette page sont optimisés pour les moteurs de recherche (SEO) et respectent les exigences strictes de Google AdSense. Chaque article dispose d'une URL propre et descriptive, d'une meta description optimisée, d'un contenu de qualité original, et d'une structure claire facilitant la navigation et l'indexation par les moteurs de recherche.
        </p>
    </div>
</div>

<script>
// Fonction pour copier l'URL dans le presse-papiers
function copyToClipboard(url, index) {
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(url).then(function() {
            if (typeof toastr !== 'undefined') {
                toastr.success('URL de l\'article #' + index + ' copiée dans le presse-papiers', 'Succès', {
                    timeOut: 2000
                });
            } else {
                alert('URL de l\'article #' + index + ' copiée dans le presse-papiers');
            }
        }).catch(function(err) {
            console.error('Erreur lors de la copie:', err);
            fallbackCopy(url, index);
        });
    } else {
        fallbackCopy(url, index);
    }
}

// Fallback pour les navigateurs plus anciens
function fallbackCopy(url, index) {
    const textArea = document.createElement('textarea');
    textArea.value = url;
    textArea.style.position = 'fixed';
    textArea.style.opacity = '0';
    textArea.style.left = '-999999px';
    document.body.appendChild(textArea);
    textArea.select();
    try {
        document.execCommand('copy');
        if (typeof toastr !== 'undefined') {
            toastr.success('URL de l\'article #' + index + ' copiée dans le presse-papiers', 'Succès', {
                timeOut: 2000
            });
        } else {
            alert('URL de l\'article #' + index + ' copiée dans le presse-papiers');
        }
    } catch (err) {
        if (typeof toastr !== 'undefined') {
            toastr.error('Impossible de copier l\'URL', 'Erreur');
        } else {
            alert('Impossible de copier l\'URL');
        }
    }
    document.body.removeChild(textArea);
}
</script>
@endsection

