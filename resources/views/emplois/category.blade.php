@extends('layouts.app')

@push('page_css')
@vite('resources/css/features/emplois.css')
@endpush

@section('title', $category->name . ' | NiangProgrammeur')
@section('meta_description', $category->description ?? 'Découvrez les articles dans la catégorie ' . $category->name . ' sur NiangProgrammeur.')
@section('meta_keywords', $category->name . ', emploi Sénégal, recrutement, opportunités')
@section('canonical', route('emplois.category', $category->slug))
@push('meta')
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('emplois.category', $category->slug) }}">
    <meta property="og:title" content="{{ $category->name . ' | NiangProgrammeur' }}">
    <meta property="og:description" content="{{ $category->description ?? 'Découvrez les articles dans la catégorie ' . $category->name . ' sur NiangProgrammeur.' }}">
    @if($category->image)
    <meta property="og:image" content="{{ $category->image_type === 'internal' ? \Illuminate\Support\Facades\Storage::url($category->image) : $category->image }}">
    @endif
@endpush

@section('styles')
<style>
    * { box-sizing: border-box; }

    body:not(.dark-mode) { background: #ffffff !important; }
    body.dark-mode        { background: #0a0a0f !important; }

    .offers-hero {
        position: relative;
        height: 500px;
        overflow: hidden;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
        filter: brightness(0.7);
    }
    body:not(.dark-mode) .offers-hero { filter: brightness(0.6) !important; }

    .offers-hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to bottom, transparent 0%, rgba(51,65,85,.85) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 60px 20px;
    }
    body:not(.dark-mode) .offers-hero-overlay {
        background: linear-gradient(to bottom, transparent 0%, rgba(30,41,59,.7) 100%) !important;
    }

    .offers-hero-content {
        max-width: 1200px;
        width: 100%;
        margin: 0 auto;
        position: relative;
        z-index: 1;
        text-align: center;
    }

    .offers-hero-title {
        font-size: clamp(2rem,4vw,3.5rem);
        font-weight: 900;
        color: #fff;
        margin-bottom: 15px;
        line-height: 1.2;
        text-shadow: 0 2px 10px rgba(0,0,0,.3);
    }
    body:not(.dark-mode) .offers-hero-title {
        color: rgba(255,255,255,.95) !important;
        text-shadow: 0 2px 10px rgba(0,0,0,.5) !important;
    }

    .offers-hero-description {
        font-size: 1.1rem;
        color: rgba(255,255,255,.9);
        line-height: 1.6;
        text-shadow: 0 1px 5px rgba(0,0,0,.3);
    }
    body:not(.dark-mode) .offers-hero-description {
        color: rgba(255,255,255,.95) !important;
        text-shadow: 0 1px 5px rgba(0,0,0,.4) !important;
    }

    .offers-hero-fallback {
        background: linear-gradient(135deg,#0f172a 0%,#1e293b 50%,#0f172a 100%);
        padding: 100px 20px 60px;
        text-align: center;
    }
    body:not(.dark-mode) .offers-hero-fallback {
        background: linear-gradient(135deg,rgba(30,41,59,.4) 0%,rgba(51,65,85,.5) 50%,rgba(30,41,59,.4) 100%) !important;
    }

    @media (max-width:768px) { .offers-hero { height: 300px; } }

    .offers-container {
        max-width: 1600px;
        margin: 0 auto;
        padding: 80px 20px;
    }

    /* Breadcrumb */
    .cat-breadcrumb {
        display: flex;
        align-items: center;
        gap: .5rem;
        font-size: .875rem;
        color: rgba(255,255,255,.7);
        margin-bottom: 1.5rem;
    }
    .cat-breadcrumb a { color: #06b6d4; text-decoration: none; }
    .cat-breadcrumb a:hover { text-decoration: underline; }
    body:not(.dark-mode) .cat-breadcrumb { color: rgba(30,41,59,.6) !important; }

    .articles-grid {
        display: grid;
        grid-template-columns: repeat(3,1fr);
        gap: 30px;
        margin-top: 50px;
    }

    .article-card {
        background: rgba(15,23,42,.7);
        backdrop-filter: blur(20px);
        border: 2px solid rgba(6,182,212,.2);
        border-radius: 24px;
        overflow: hidden;
        transition: all .6s cubic-bezier(.175,.885,.32,1.275);
        text-decoration: none;
        display: block;
        height: 100%;
        position: relative;
        box-shadow: 0 10px 40px rgba(0,0,0,.2);
        will-change: transform;
    }
    @media (max-width:768px) { .article-card { backdrop-filter: blur(10px); } }
    body:not(.dark-mode) .article-card {
        background: rgba(255,255,255,.9) !important;
        border-color: rgba(6,182,212,.25) !important;
        box-shadow: 0 10px 40px rgba(6,182,212,.1) !important;
    }

    .article-card::before {
        content: '';
        position: absolute;
        top: 0; left: -100%;
        width: 100%; height: 100%;
        background: linear-gradient(90deg,transparent,rgba(6,182,212,.1),transparent);
        transition: left .6s;
        z-index: 1;
    }
    .article-card:hover::before { left: 100%; }
    .article-card:hover {
        transform: translateY(-12px) scale(1.02);
        border-color: rgba(6,182,212,.6);
        box-shadow: 0 25px 70px rgba(6,182,212,.4);
    }

    .article-card-image-wrapper {
        position: relative;
        width: 100%;
        height: 300px;
        overflow: hidden;
        background: linear-gradient(135deg,rgba(6,182,212,.2),rgba(20,184,166,.2));
    }

    .article-card-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform .8s cubic-bezier(.175,.885,.32,1.275);
    }
    .article-card:hover .article-card-image { transform: scale(1.2) rotate(2deg); }

    .article-card-overlay {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        background: linear-gradient(to top,rgba(0,0,0,.95) 0%,rgba(0,0,0,.6) 50%,transparent 100%);
        padding: 30px 25px 25px;
        transition: all .4s ease;
        z-index: 2;
    }
    .article-card:hover .article-card-overlay {
        background: linear-gradient(to top,rgba(6,182,212,.95) 0%,rgba(0,0,0,.7) 50%,transparent 100%);
    }

    .article-card-category {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: rgba(6,182,212,.3);
        color: #06b6d4;
        border-radius: 20px;
        font-size: .8rem;
        font-weight: 700;
        margin-bottom: 15px;
        border: 1px solid rgba(6,182,212,.5);
        backdrop-filter: blur(10px);
        transition: all .3s ease;
    }
    .article-card:hover .article-card-category { background: rgba(255,255,255,.2); color: #fff; transform: translateX(5px); }
    body:not(.dark-mode) .article-card-category { background: rgba(6,182,212,.15) !important; border-color: rgba(6,182,212,.3) !important; }

    .article-card-title {
        font-size: 1.3rem;
        font-weight: 800;
        color: #fff;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-shadow: 0 2px 15px rgba(0,0,0,.5);
        transition: all .3s ease;
    }
    body:not(.dark-mode) .article-card-title { color: rgba(255,255,255,.95) !important; text-shadow: 0 2px 15px rgba(0,0,0,.7) !important; }
    .article-card:hover .article-card-title { text-shadow: 0 4px 20px rgba(6,182,212,.8); }

    .article-card-content { padding: 25px; position: relative; z-index: 2; }

    .article-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 20px;
        border-top: 1px solid rgba(6,182,212,.2);
        flex-wrap: wrap;
        gap: 15px;
    }

    .article-card-meta {
        display: flex;
        align-items: center;
        gap: 15px;
        color: rgba(255,255,255,.6);
        font-size: .9rem;
        flex-wrap: wrap;
    }
    body:not(.dark-mode) .article-card-meta { color: rgba(30,41,59,.6) !important; }
    .article-card-meta i { color: #06b6d4; font-size: 1rem; }

    .article-card-button {
        padding: 12px 24px;
        background: linear-gradient(135deg,#06b6d4,#14b8a6);
        color: #000;
        border-radius: 14px;
        font-weight: 700;
        font-size: .9rem;
        transition: all .4s cubic-bezier(.175,.885,.32,1.275);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
        box-shadow: 0 4px 15px rgba(6,182,212,.3);
    }
    .article-card:hover .article-card-button { transform: translateX(8px) scale(1.05); box-shadow: 0 8px 30px rgba(6,182,212,.6); gap: 12px; }
    body:not(.dark-mode) .article-card-button { background: linear-gradient(135deg,#06b6d4,#14b8a6) !important; color: #000 !important; }

    .no-articles {
        text-align: center;
        padding: 120px 20px;
        background: rgba(15,23,42,.5);
        border-radius: 32px;
        border: 2px dashed rgba(6,182,212,.3);
        backdrop-filter: blur(20px);
    }
    body:not(.dark-mode) .no-articles { background: rgba(255,255,255,.9) !important; border-color: rgba(6,182,212,.25) !important; }
    .no-articles i { font-size: 6rem; color: rgba(6,182,212,.4); margin-bottom: 30px; display: block; animation: float 3s ease-in-out infinite; }
    @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-20px)} }
    .no-articles h3 { font-size: 2.5rem; color: #fff; margin-bottom: 15px; font-weight: 900; background: linear-gradient(135deg,#06b6d4,#14b8a6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    .no-articles p { color: rgba(255,255,255,.7); font-size: 1.2rem; }
    body:not(.dark-mode) .no-articles p { color: rgba(30,41,59,.7) !important; }

    .pagination-wrapper { margin-top: 80px; }

    @media (max-width:1200px) { .articles-grid { grid-template-columns: repeat(2,1fr); } }
    @media (max-width:768px) {
        .articles-grid { grid-template-columns: 1fr; gap: 25px; }
        .article-card-content { padding: 20px; }
        .article-card-footer { flex-direction: column; align-items: stretch; }
        .article-card-button { width: 100%; justify-content: center; }
    }
</style>
@endsection

@section('content')
@php
    $categoryImage = null;
    if ($category->image) {
        $categoryImage = $category->image_type === 'internal'
            ? \Illuminate\Support\Facades\Storage::url($category->image)
            : $category->image;
    }
@endphp

<!-- Hero -->
@if($categoryImage)
<div class="offers-hero" style="background-image: url('{{ $categoryImage }}');">
    <div class="offers-hero-overlay">
        <div class="offers-hero-content">
            <nav class="cat-breadcrumb" aria-label="Fil d'ariane">
                <a href="{{ route('emplois') }}">Emplois</a>
                <span>›</span>
                <span>{{ $category->name }}</span>
            </nav>
            <h1 class="offers-hero-title">{{ $category->name }}</h1>
            @if($category->description)
            <p class="offers-hero-description">{{ $category->description }}</p>
            @endif
        </div>
    </div>
</div>
@else
<div class="offers-hero-fallback">
    <nav class="cat-breadcrumb" style="justify-content:center;" aria-label="Fil d'ariane">
        <a href="{{ route('emplois') }}">Emplois</a>
        <span>›</span>
        <span>{{ $category->name }}</span>
    </nav>
    <h1 class="offers-hero-title" style="max-width:1200px;margin:0 auto;">{{ $category->name }}</h1>
    @if($category->description)
    <p class="offers-hero-description" style="max-width:1200px;margin:20px auto 0;">{{ $category->description }}</p>
    @endif
</div>
@endif

<!-- Articles -->
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
                     width="600" height="400"
                     loading="lazy"
                     decoding="async"
                     onerror="this.style.display='none'">
                @else
                <div class="article-card-image" style="display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,rgba(6,182,212,.3),rgba(20,184,166,.3));">
                    <i class="fas fa-image" style="font-size:4rem;color:rgba(6,182,212,.5);"></i>
                </div>
                @endif

                <div class="article-card-overlay">
                    <span class="article-card-category">
                        <i class="fas fa-folder"></i> {{ $article->category->name }}
                    </span>
                    <h3 class="article-card-title">{{ $article->title }}</h3>
                </div>
            </div>

            <div class="article-card-content">
                <div class="article-card-footer">
                    <div class="article-card-meta">
                        @if($article->published_at)
                        <span><i class="fas fa-calendar"></i> {{ $article->published_at->format('d/m/Y') }}</span>
                        @endif
                        <span><i class="fas fa-eye"></i> {{ $article->featured_display_views }}</span>
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
