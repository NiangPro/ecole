@extends('layouts.app')

@section('title', $article->meta_title ?? $article->title . ' | NiangProgrammeur')
@section('meta_description', $article->meta_description ?? $article->excerpt ?? substr(strip_tags($article->content), 0, 160))

@php
    $articleImage = $article->cover_image 
        ? ($article->cover_type === 'internal' 
            ? url(\Illuminate\Support\Facades\Storage::url($article->cover_image)) 
            : $article->cover_image)
        : asset('images/logo.png');
@endphp

@push('meta')
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ route('emplois.article', $article->slug) }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ route('emplois.article', $article->slug) }}">
    <meta property="og:title" content="{{ $article->meta_title ?? $article->title }}">
    <meta property="og:description" content="{{ $article->meta_description ?? $article->excerpt ?? substr(strip_tags($article->content), 0, 160) }}">
    <meta property="og:image" content="{{ $articleImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="NiangProgrammeur">
    <meta property="og:locale" content="fr_FR">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ route('emplois.article', $article->slug) }}">
    <meta name="twitter:title" content="{{ $article->meta_title ?? $article->title }}">
    <meta name="twitter:description" content="{{ $article->meta_description ?? $article->excerpt ?? substr(strip_tags($article->content), 0, 160) }}">
    <meta name="twitter:image" content="{{ $articleImage }}">
    
    <!-- Meta Tags SEO -->
    <meta name="keywords" content="{{ $article->meta_keywords ? implode(', ', is_array($article->meta_keywords) ? $article->meta_keywords : json_decode($article->meta_keywords, true) ?? []) : '' }}">
    <meta name="author" content="NiangProgrammeur">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="googlebot" content="index, follow">
    <meta name="bingbot" content="index, follow">
    
    <!-- Article Meta -->
    <meta property="article:published_time" content="{{ $article->published_at ? $article->published_at->toIso8601String() : $article->created_at->toIso8601String() }}">
    <meta property="article:modified_time" content="{{ $article->updated_at->toIso8601String() }}">
    <meta property="article:section" content="{{ $article->category->name ?? 'Emploi' }}">
    <meta property="article:author" content="NiangProgrammeur">
    @if($article->category)
    <meta property="article:tag" content="{{ $article->category->name }}">
    @endif
@endpush

@section('styles')
<style>
    * {
        box-sizing: border-box;
    }
    
    /* Body background for article page */
    body:not(.dark-mode) {
        background: #ffffff !important;
    }
    
    body.dark-mode {
        background: #0a0a0f !important;
    }
    
    .article-hero {
        position: relative;
        height: 500px;
        overflow: hidden;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
        filter: brightness(0.7);
    }
    
    body:not(.dark-mode) .article-hero {
        filter: brightness(0.6) !important;
    }
    
    .article-hero-image {
        display: none;
    }
    
    .article-hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, transparent 0%, rgba(51, 65, 85, 0.85) 100%);
        display: flex;
        align-items: flex-end;
        padding: 60px 20px 40px;
    }
    
    body:not(.dark-mode) .article-hero-overlay {
        background: linear-gradient(to bottom, transparent 0%, rgba(30, 41, 59, 0.7) 100%) !important;
    }
    
    .article-hero-content {
        max-width: 1200px;
        width: 100%;
        margin: 0 auto;
        position: relative;
        z-index: 1;
    }
    
    .article-hero-category {
        display: inline-block;
        padding: 8px 18px;
        background: rgba(6, 182, 212, 0.2);
        color: #06b6d4;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 700;
        margin-bottom: 20px;
        border: 1px solid rgba(6, 182, 212, 0.4);
    }
    
    .article-hero-title {
        font-size: clamp(2rem, 4vw, 3.5rem);
        font-weight: 900;
        color: #fff;
        margin-bottom: 15px;
        line-height: 1.2;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    }
    
    body:not(.dark-mode) .article-hero-title {
        color: rgba(255, 255, 255, 0.95) !important;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5) !important;
    }
    
    .article-hero-meta {
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.95rem;
    }
    
    body:not(.dark-mode) .article-hero-meta {
        color: rgba(255, 255, 255, 0.9) !important;
        text-shadow: 0 1px 5px rgba(0, 0, 0, 0.3) !important;
    }
    
    .article-hero-meta i {
        color: #06b6d4;
        margin-right: 5px;
    }
    
    .article-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 60px 20px;
    }
    
    .article-content {
        background: rgba(51, 65, 85, 0.5);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 24px;
        padding: 50px;
        margin-bottom: 40px;
        line-height: 1.9;
        font-size: 1.1rem;
        color: rgba(255, 255, 255, 0.9);
    }
    
    body:not(.dark-mode) .article-content {
        background: rgba(255, 255, 255, 0.9) !important;
        border-color: rgba(6, 182, 212, 0.25) !important;
        color: rgba(30, 41, 59, 0.9) !important;
        box-shadow: 0 10px 40px rgba(6, 182, 212, 0.1) !important;
    }
    
    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        margin: 20px 0;
        display: block;
        loading: lazy;
    }
    
    .article-content h2 {
        font-size: 2rem;
        font-weight: 800;
        color: #06b6d4;
        margin: 40px 0 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid rgba(6, 182, 212, 0.3);
    }
    
    .article-content h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #14b8a6;
        margin: 30px 0 15px;
    }
    
    .article-content p {
        margin-bottom: 20px;
    }
    
    .article-content ul, .article-content ol {
        margin: 20px 0;
        padding-left: 30px;
    }
    
    .article-content li {
        margin-bottom: 10px;
    }
    
    .article-content strong {
        color: #06b6d4;
        font-weight: 700;
    }
    
    .related-articles {
        margin-top: 60px;
    }
    
    .related-articles h2 {
        font-size: 2.2rem;
        font-weight: 900;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 40px;
    }
    
    .related-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }
    
    @media (max-width: 1400px) {
        .related-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    @media (max-width: 1024px) {
        .related-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .related-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .related-card {
        background: rgba(51, 65, 85, 0.5);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    
    body:not(.dark-mode) .related-card {
        background: rgba(255, 255, 255, 0.9) !important;
        border-color: rgba(6, 182, 212, 0.25) !important;
        box-shadow: 0 10px 40px rgba(6, 182, 212, 0.1) !important;
    }
    
    .related-card-image {
        display: block;
    }
    
    .related-card:hover {
        transform: translateY(-8px);
        border-color: rgba(6, 182, 212, 0.5);
        box-shadow: 0 15px 40px rgba(6, 182, 212, 0.25);
    }
    
    .related-card-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    
    .related-card-content {
        padding: 20px;
    }
    
    .related-card-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 10px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    body:not(.dark-mode) .related-card-title {
        color: rgba(30, 41, 59, 0.9) !important;
    }
    
    .related-card-meta {
        display: flex;
        align-items: center;
        gap: 15px;
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.85rem;
        margin-top: 10px;
    }
    
    body:not(.dark-mode) .related-card-meta {
        color: rgba(30, 41, 59, 0.6) !important;
    }
    
    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 24px;
        background: rgba(6, 182, 212, 0.1);
        color: #06b6d4;
        border: 1px solid rgba(6, 182, 212, 0.3);
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        margin-bottom: 30px;
    }
    
    .back-button:hover {
        background: rgba(6, 182, 212, 0.2);
        border-color: rgba(6, 182, 212, 0.5);
        transform: translateX(-5px);
    }
    
    .article-hero-fallback {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
    }
    
    body:not(.dark-mode) .article-hero-fallback {
        background: linear-gradient(135deg, rgba(30, 41, 59, 0.4) 0%, rgba(51, 65, 85, 0.5) 50%, rgba(30, 41, 59, 0.4) 100%) !important;
    }
    
    @media (max-width: 768px) {
        .article-content {
            padding: 30px 20px;
        }
        
        .article-hero {
            height: 300px;
        }
        
        .related-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
@if($article->cover_image)
<div class="article-hero" style="background-image: url('{{ $article->cover_type === 'internal' ? \Illuminate\Support\Facades\Storage::url($article->cover_image) : $article->cover_image }}');">
    <div class="article-hero-overlay">
        <div class="article-hero-content">
            <span class="article-hero-category">
                <i class="fas fa-folder mr-2"></i>{{ $article->category->name }}
            </span>
            <h1 class="article-hero-title">{{ $article->title }}</h1>
            <div class="article-hero-meta">
                <span><i class="fas fa-calendar"></i> {{ $article->published_at ? $article->published_at->format('d F Y') : '' }}</span>
                <span><i class="fas fa-eye"></i> {{ $article->views }} vues</span>
                <span><i class="fas fa-user"></i> NiangProgrammeur</span>
            </div>
        </div>
    </div>
</div>
@else
<div class="article-hero-fallback" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%); padding: 100px 20px 60px; text-align: center;">
    <span class="article-hero-category" style="display: inline-block; margin-bottom: 20px;">
        <i class="fas fa-folder mr-2"></i>{{ $article->category->name }}
    </span>
    <h1 class="article-hero-title" style="max-width: 1200px; margin: 0 auto;">{{ $article->title }}</h1>
    <div class="article-hero-meta" style="justify-content: center; margin-top: 20px;">
        <span><i class="fas fa-calendar"></i> {{ $article->published_at ? $article->published_at->format('d F Y') : '' }}</span>
        <span><i class="fas fa-eye"></i> {{ $article->views }} vues</span>
    </div>
</div>
@endif

<!-- Article Container -->
<div class="article-container">
    <!-- Contenu et Publicité côte à côte -->
    <div style="display: grid; grid-template-columns: {{ isset($sidebarAds) && $sidebarAds->count() > 0 ? '1fr 350px' : '1fr' }}; gap: 40px; align-items: start; margin-bottom: 60px;">
        <div>
            <a href="{{ route('emplois.offres') }}" class="back-button">
                <i class="fas fa-arrow-left"></i>
                Retour aux offres
            </a>
            
            <div class="article-content">
                {!! $article->content !!}
            </div>
            
            @include('partials.share-buttons', ['article' => $article])
        </div>
        
        <!-- Sidebar Publicités Moderne -->
        @if(isset($sidebarAds) && $sidebarAds->count() > 0)
        <aside style="position: sticky; top: 80px; align-self: flex-start;">
            @foreach($sidebarAds as $ad)
            <div class="modern-sidebar-ad">
                <a href="{{ $ad->link_url ?? '#' }}" target="_blank" onclick="trackAdClick({{ $ad->id }})" class="modern-sidebar-ad-link">
                    @if($ad->image)
                    <div class="modern-sidebar-ad-image-wrapper">
                        <img src="{{ $ad->image_type === 'internal' ? \Illuminate\Support\Facades\Storage::url($ad->image) : $ad->image }}" 
                             alt="{{ $ad->name }}" 
                             class="modern-sidebar-ad-image"
                             loading="lazy"
                             onerror="this.style.display='none'">
                        <div class="modern-sidebar-ad-overlay">
                            <div class="modern-sidebar-ad-content">
                                <h4 class="modern-sidebar-ad-title">{{ $ad->name }}</h4>
                                @if($ad->description)
                                <p class="modern-sidebar-ad-description">{{ $ad->description }}</p>
                                @endif
                                <span class="modern-sidebar-ad-cta">Découvrir <i class="fas fa-arrow-right"></i></span>
                            </div>
                        </div>
                    </div>
                    @endif
                </a>
            </div>
            @php
                $ad->incrementImpressions();
            @endphp
            @endforeach
            
            <!-- Section Commentaires (dans la sidebar) -->
            @include('partials.comments', ['commentable' => $article, 'comments' => $comments ?? []])
        </aside>
        @else
        <!-- Si pas de publicités, afficher les commentaires quand même -->
        <aside style="position: sticky; top: 80px; align-self: flex-start;">
            @include('partials.comments', ['commentable' => $article, 'comments' => $comments ?? []])
        </aside>
        @endif
    </div>
    
    <!-- Articles Similaires en ligne entière -->
    @if($relatedArticles && $relatedArticles->count() > 0)
    <div class="related-articles-full">
        <h2 class="related-articles-title">
            <i class="fas fa-newspaper mr-3"></i>
            Articles Similaires
        </h2>
        <div class="related-grid-full">
            @foreach($relatedArticles as $related)
            <a href="{{ route('emplois.article', $related->slug) }}" class="related-card-modern">
                @if($related->cover_image)
                <div class="related-card-modern-image-wrapper">
                        <img src="{{ $related->cover_type === 'internal' ? \Illuminate\Support\Facades\Storage::url($related->cover_image) : $related->cover_image }}"
                             loading="lazy"
                             alt="{{ $related->title }} - {{ $related->category->name ?? 'Article' }}" 
                         class="related-card-modern-image"
                         onerror="this.src='https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=400&h=250&fit=crop'">
                    <div class="related-card-modern-overlay">
                        <span class="related-card-modern-cta">Lire l'article <i class="fas fa-arrow-right"></i></span>
                    </div>
                </div>
                @else
                <div class="related-card-modern-image-wrapper" style="background: linear-gradient(135deg, rgba(6, 182, 212, 0.3), rgba(20, 184, 166, 0.3)); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-image text-5xl text-cyan-400/50"></i>
                </div>
                @endif
                <div class="related-card-modern-content">
                    <h3 class="related-card-modern-title">{{ $related->title }}</h3>
                    <div class="related-card-modern-meta">
                        <span><i class="fas fa-calendar"></i> {{ $related->published_at ? $related->published_at->format('d/m/Y') : '' }}</span>
                        <span><i class="fas fa-eye"></i> {{ $related->views }} vues</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>

<style>
    /* Modern Sidebar Ad */
    .modern-sidebar-ad {
        background: rgba(51, 65, 85, 0.7);
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 20px;
        overflow: hidden;
        margin-bottom: 25px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
    
    body:not(.dark-mode) .modern-sidebar-ad {
        background: rgba(255, 255, 255, 0.9) !important;
        border-color: rgba(6, 182, 212, 0.25) !important;
        box-shadow: 0 10px 30px rgba(6, 182, 212, 0.1) !important;
    }
    
    .modern-sidebar-ad:hover {
        transform: translateY(-5px);
        border-color: rgba(6, 182, 212, 0.6);
        box-shadow: 0 15px 40px rgba(6, 182, 212, 0.4);
    }
    
    .modern-sidebar-ad-link {
        display: block;
        text-decoration: none;
        color: inherit;
    }
    
    .modern-sidebar-ad-image-wrapper {
        position: relative;
        width: 100%;
        min-height: 450px;
        height: auto;
        overflow: hidden;
    }
    
    .modern-sidebar-ad-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .modern-sidebar-ad:hover .modern-sidebar-ad-image {
        transform: scale(1.1);
    }
    
    .modern-sidebar-ad-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(180deg, transparent 0%, rgba(51, 65, 85, 0.7) 50%, rgba(51, 65, 85, 0.85) 100%);
        display: flex;
        align-items: flex-end;
        padding: 25px;
        min-height: 100%;
    }
    
    body:not(.dark-mode) .modern-sidebar-ad-overlay {
        background: linear-gradient(180deg, transparent 0%, rgba(30, 41, 59, 0.6) 50%, rgba(30, 41, 59, 0.8) 100%) !important;
    }
    
    .modern-sidebar-ad-content {
        width: 100%;
    }
    
    .modern-sidebar-ad-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 8px;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        line-height: 1.3;
    }
    
    .modern-sidebar-ad-description {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 15px;
        line-height: 1.6;
        max-height: none;
        overflow: visible;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
    }
    
    .modern-sidebar-ad-cta {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #06b6d4;
        font-weight: 600;
        font-size: 0.9rem;
        transition: gap 0.3s ease;
    }
    
    .modern-sidebar-ad:hover .modern-sidebar-ad-cta {
        gap: 12px;
    }
    
    /* Related Articles Full Width */
    .related-articles-full {
        margin-top: 60px;
        padding-top: 60px;
        border-top: 2px solid rgba(6, 182, 212, 0.2);
    }
    
    .related-articles-title {
        font-size: 2.5rem;
        font-weight: 900;
        color: #fff;
        margin-bottom: 40px;
        text-align: center;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .related-grid-full {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
    }
    
    .related-card-modern {
        background: rgba(51, 65, 85, 0.5);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        text-decoration: none;
    }
    
    body:not(.dark-mode) .related-card-modern {
        background: rgba(255, 255, 255, 0.9) !important;
        border-color: rgba(6, 182, 212, 0.25) !important;
        box-shadow: 0 10px 40px rgba(6, 182, 212, 0.1) !important;
    }
    
    .related-card-modern-link {
        color: inherit;
        display: block;
    }
    
    .related-card-modern:hover {
        transform: translateY(-10px);
        border-color: rgba(6, 182, 212, 0.5);
        box-shadow: 0 20px 50px rgba(6, 182, 212, 0.3);
    }
    
    .related-card-modern-image-wrapper {
        position: relative;
        width: 100%;
        height: 200px;
        overflow: hidden;
    }
    
    .related-card-modern-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .related-card-modern:hover .related-card-modern-image {
        transform: scale(1.1);
    }
    
    .related-card-modern-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(180deg, transparent 0%, rgba(51, 65, 85, 0.8) 100%);
        display: flex;
        align-items: flex-end;
        padding: 15px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .related-card-modern:hover .related-card-modern-overlay {
        opacity: 1;
    }
    
    .related-card-modern-cta {
        color: #06b6d4;
        font-weight: 600;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .related-card-modern-content {
        padding: 20px;
    }
    
    .related-card-modern-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 12px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    body:not(.dark-mode) .related-card-modern-title {
        color: rgba(30, 41, 59, 0.9) !important;
    }
    
    .related-card-modern-meta {
        display: flex;
        align-items: center;
        gap: 15px;
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.85rem;
    }
    
    body:not(.dark-mode) .related-card-modern-meta {
        color: rgba(30, 41, 59, 0.6) !important;
    }
    
    .related-card-modern-meta i {
        color: #06b6d4;
    }
    
    body:not(.dark-mode) .related-articles-title {
        -webkit-text-fill-color: transparent !important;
    }
    
    body:not(.dark-mode) .related-articles-full {
        border-top-color: rgba(6, 182, 212, 0.25) !important;
    }
    
    body:not(.dark-mode) .back-button {
        background: rgba(6, 182, 212, 0.1) !important;
        border-color: rgba(6, 182, 212, 0.3) !important;
    }
    
    body:not(.dark-mode) .back-button:hover {
        background: rgba(6, 182, 212, 0.15) !important;
    }
    
    /* Force text colors in light mode */
    body:not(.dark-mode) .article-content h2,
    body:not(.dark-mode) .article-content h3,
    body:not(.dark-mode) .article-content p,
    body:not(.dark-mode) .article-content li,
    body:not(.dark-mode) .article-content ul,
    body:not(.dark-mode) .article-content ol {
        color: rgba(30, 41, 59, 0.9) !important;
    }
    
    body:not(.dark-mode) .article-content h2 {
        color: #06b6d4 !important;
    }
    
    body:not(.dark-mode) .article-content h3 {
        color: #14b8a6 !important;
    }
    
    body:not(.dark-mode) .article-content strong {
        color: #06b6d4 !important;
    }
    
    /* Buttons and links adaptation */
    body:not(.dark-mode) .back-button {
        color: #06b6d4 !important;
    }
    
    body:not(.dark-mode) .modern-sidebar-ad-title {
        color: rgba(255, 255, 255, 0.95) !important;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.7) !important;
    }
    
    body:not(.dark-mode) .modern-sidebar-ad-description {
        color: rgba(255, 255, 255, 0.9) !important;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.5) !important;
    }
    
    body:not(.dark-mode) .modern-sidebar-ad-cta {
        color: #06b6d4 !important;
    }
    
    /* ============================================
       DARK MODE - SECTIONS PUBLICITÉ & COMMENTAIRES
       ============================================ */
    
    /* Section Commentaires - Dark Mode */
    .comments-section {
        background: rgba(15, 23, 42, 0.6) !important;
        backdrop-filter: blur(20px) !important;
        border-color: rgba(6, 182, 212, 0.2) !important;
    }
    
    body:not(.dark-mode) .comments-section {
        background: rgba(255, 255, 255, 0.9) !important;
        border-color: rgba(6, 182, 212, 0.25) !important;
    }
    
    .comments-title {
        color: #06b6d4 !important;
    }
    
    body:not(.dark-mode) .comments-title {
        color: rgba(30, 41, 59, 0.95) !important;
    }
    
    /* Formulaire de commentaire */
    .comment-form-wrapper {
        background: rgba(51, 65, 85, 0.5) !important;
        border-color: rgba(6, 182, 212, 0.3) !important;
    }
    
    body:not(.dark-mode) .comment-form-wrapper {
        background: rgba(255, 255, 255, 0.9) !important;
        border-color: rgba(6, 182, 212, 0.25) !important;
    }
    
    .comment-form-wrapper label {
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    body:not(.dark-mode) .comment-form-wrapper label {
        color: rgba(30, 41, 59, 0.9) !important;
    }
    
    .comment-form-wrapper input,
    .comment-form-wrapper textarea {
        background: rgba(51, 65, 85, 0.7) !important;
        border-color: rgba(6, 182, 212, 0.4) !important;
        color: #fff !important;
    }
    
    body:not(.dark-mode) .comment-form-wrapper input,
    body:not(.dark-mode) .comment-form-wrapper textarea {
        background: rgba(255, 255, 255, 0.95) !important;
        border-color: rgba(6, 182, 212, 0.3) !important;
        color: rgba(30, 41, 59, 0.9) !important;
    }
    
    .comment-form-wrapper input::placeholder,
    .comment-form-wrapper textarea::placeholder {
        color: rgba(255, 255, 255, 0.5) !important;
    }
    
    body:not(.dark-mode) .comment-form-wrapper input::placeholder,
    body:not(.dark-mode) .comment-form-wrapper textarea::placeholder {
        color: rgba(30, 41, 59, 0.5) !important;
    }
    
    /* Commentaires individuels */
    .comments-section > div > div[style*="background: linear-gradient"] {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.1)) !important;
        border-color: rgba(6, 182, 212, 0.3) !important;
    }
    
    body:not(.dark-mode) .comments-section > div > div[style*="background: linear-gradient"] {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.05), rgba(20, 184, 166, 0.05)) !important;
        border-color: rgba(6, 182, 212, 0.2) !important;
    }
    
    .comments-section h5 {
        color: #fff !important;
    }
    
    body:not(.dark-mode) .comments-section h5 {
        color: rgba(30, 41, 59, 0.95) !important;
    }
    
    .comments-section p {
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    body:not(.dark-mode) .comments-section p {
        color: rgba(30, 41, 59, 0.85) !important;
    }
    
    .comments-section > div > div[style*="background: rgba(51, 65, 85, 0.4)"] {
        background: rgba(51, 65, 85, 0.4) !important;
    }
    
    body:not(.dark-mode) .comments-section > div > div[style*="background: rgba(51, 65, 85, 0.4)"] {
        background: rgba(6, 182, 212, 0.05) !important;
    }
    
    .comments-section span[style*="color: rgba(255, 255, 255, 0.6)"] {
        color: rgba(255, 255, 255, 0.6) !important;
    }
    
    body:not(.dark-mode) .comments-section span[style*="color: rgba(255, 255, 255, 0.6)"] {
        color: rgba(30, 41, 59, 0.6) !important;
    }
    
    /* Titre "Derniers commentaires" */
    .comments-section h4 {
        color: #06b6d4 !important;
    }
    
    body:not(.dark-mode) .comments-section h4 {
        color: rgba(30, 41, 59, 0.95) !important;
    }
    
    @media (max-width: 1400px) {
        .related-grid-full {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    @media (max-width: 1200px) {
        .article-container > div[style*="grid-template-columns: 1fr 350px"] {
            grid-template-columns: 1fr !important;
        }
        
        .article-container > div[style*="grid-template-columns: 1fr 350px"] > aside {
            display: none !important;
        }
        
        .related-grid-full {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .related-grid-full {
            grid-template-columns: 1fr;
        }
        
        .related-articles-title {
            font-size: 2rem;
        }
    }
</style>

<script>
    function trackAdClick(adId) {
        fetch('/api/ads/' + adId + '/click', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Content-Type': 'application/json'
            }
        }).catch(err => console.log('Ad click tracking error:', err));
    }
</script>
@endsection

