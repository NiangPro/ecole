@extends('layouts.app')

@section('title', $article->meta_title ?? $article->title . ' | NiangProgrammeur')
@section('meta_description', $article->meta_description ?? $article->excerpt)

@section('styles')
<style>
    * {
        box-sizing: border-box;
    }
    
    .article-hero {
        position: relative;
        height: 400px;
        overflow: hidden;
    }
    
    .article-hero-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: brightness(0.6);
    }
    
    .article-hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, transparent 0%, rgba(15, 23, 42, 0.95) 100%);
        display: flex;
        align-items: flex-end;
        padding: 60px 20px 40px;
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
    }
    
    .article-hero-meta {
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.95rem;
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
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 24px;
        padding: 50px;
        margin-bottom: 40px;
        line-height: 1.9;
        font-size: 1.1rem;
        color: rgba(255, 255, 255, 0.9);
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
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
        text-decoration: none;
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
    
    .related-card-meta {
        display: flex;
        align-items: center;
        gap: 15px;
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.85rem;
        margin-top: 10px;
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
<div class="article-hero">
    <img src="{{ $article->cover_type === 'internal' ? \Illuminate\Support\Facades\Storage::url($article->cover_image) : $article->cover_image }}" 
         alt="{{ $article->title }}" 
         class="article-hero-image"
         onerror="this.style.display='none'">
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
<div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%); padding: 100px 20px 60px; text-align: center;">
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
    <div style="display: grid; grid-template-columns: {{ isset($sidebarAds) && $sidebarAds->count() > 0 ? '1fr 300px' : '1fr' }}; gap: 30px; align-items: start;">
        <div>
            <a href="{{ route('emplois.offres') }}" class="back-button">
                <i class="fas fa-arrow-left"></i>
                Retour aux offres
            </a>
            
            <div class="article-content">
                {!! nl2br(e($article->content)) !!}
            </div>
    
    @if($relatedArticles && $relatedArticles->count() > 0)
    <div class="related-articles">
        <h2>📰 Articles Similaires</h2>
        <div class="related-grid">
            @foreach($relatedArticles as $related)
            <a href="{{ route('emplois.article', $related->slug) }}" class="related-card">
                @if($related->cover_image)
                <img src="{{ $related->cover_type === 'internal' ? \Illuminate\Support\Facades\Storage::url($related->cover_image) : $related->cover_image }}" 
                     alt="{{ $related->title }}" 
                     class="related-card-image"
                     onerror="this.src='https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=400&h=250&fit=crop'">
                @else
                <div class="related-card-image" style="background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2)); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-image text-4xl text-cyan-400/50"></i>
                </div>
                @endif
                <div class="related-card-content">
                    <h3 class="related-card-title">{{ $related->title }}</h3>
                    <div class="related-card-meta">
                        <span><i class="fas fa-calendar"></i> {{ $related->published_at ? $related->published_at->format('d/m/Y') : '' }}</span>
                        <span><i class="fas fa-eye"></i> {{ $related->views }}</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
        </div>
        
        <!-- Sidebar Publicités -->
        @if(isset($sidebarAds) && $sidebarAds->count() > 0)
        <aside style="position: sticky; top: 80px; align-self: flex-start;">
            @foreach($sidebarAds as $ad)
            <div class="ad-container" style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(6, 182, 212, 0.2); border-radius: 16px; padding: 20px; margin-bottom: 20px; backdrop-filter: blur(10px);">
                <a href="{{ $ad->link_url ?? '#' }}" target="_blank" onclick="trackAdClick({{ $ad->id }})" style="display: block; text-decoration: none;">
                    @if($ad->image)
                    <img src="{{ $ad->image_type === 'internal' ? \Illuminate\Support\Facades\Storage::url($ad->image) : $ad->image }}" 
                         alt="{{ $ad->name }}" 
                         style="width: 100%; height: auto; border-radius: 12px; display: block;"
                         onerror="this.style.display='none'">
                    @endif
                </a>
            </div>
            @php
                $ad->incrementImpressions();
            @endphp
            @endforeach
        </aside>
        @endif
    </div>
</div>

<style>
    @media (max-width: 1200px) {
        .article-container > div[style*="grid-template-columns: 1fr 300px"] {
            grid-template-columns: 1fr !important;
        }
        
        .article-container > div[style*="grid-template-columns: 1fr 300px"] > aside {
            display: none !important;
        }
    }
    
    .ad-container {
        min-height: 250px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .ad-container img {
        max-width: 100%;
        height: auto;
        transition: transform 0.3s ease;
    }
    
    .ad-container:hover img {
        transform: scale(1.05);
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

