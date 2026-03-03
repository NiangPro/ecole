@extends('layouts.app')

@section('title', ($document->seo_title ?: $document->title) . ' - Papiers administratifs Sénégal - NiangProgrammeur')
@section('meta_description', $document->seo_description ?: ($document->summary ?? 'Fiche pratique pour le document administratif : ' . $document->title))

@push('head')
@if($document->cover_image)
@php
    $coverUrl = $document->cover_type === 'internal' ? asset('storage/' . $document->cover_image) : $document->cover_image;
    if (!preg_match('/^https?:\/\//', $coverUrl)) { $coverUrl = url($coverUrl); }
@endphp
<meta property="og:image" content="{{ $coverUrl }}">
<meta name="twitter:image" content="{{ $coverUrl }}">
@endif
@if($document->seo_keywords)
<meta name="keywords" content="{{ $document->seo_keywords }}">
@endif
@endpush

@push('styles')
<style>
/* ----- Base layout ----- */
.admin-doc-show-page {
    min-height: 100vh;
    padding: 0 0 4rem;
    position: relative;
    overflow: hidden;
}
.admin-doc-show-hero {
    position: relative;
    width: 100%;
    min-height: 280px;
    display: flex;
    align-items: flex-end;
    padding: 0 1.5rem 2rem;
    margin-bottom: -2rem;
}
.admin-doc-show-hero-bg {
    position: absolute;
    inset: 0;
    background:
        linear-gradient(180deg, transparent 0%, transparent 45%, rgba(0,0,0,0.75) 100%);
    z-index: 1;
}
.admin-doc-show-hero-bg-img {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    z-index: 0;
}
.admin-doc-show-hero-inner {
    position: relative;
    z-index: 2;
    max-width: 900px;
    width: 100%;
    margin: 0 auto;
}
.admin-doc-show-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 0 1.5rem;
    position: relative;
    z-index: 3;
}
.admin-doc-show-card {
    border-radius: 1.25rem;
    padding: 2rem 2rem 1.75rem;
    border: 1px solid transparent;
    box-shadow: 0 20px 50px rgba(15, 23, 42, 0.08);
}
.admin-doc-show-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.3rem 0.85rem;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 1rem;
    border: 1px solid transparent;
}
.admin-doc-show-title {
    font-size: 2rem;
    font-weight: 800;
    letter-spacing: -0.03em;
    line-height: 1.2;
    margin-bottom: 0.75rem;
    width: 100%;
}
.admin-doc-show-summary {
    font-size: 1.05rem;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}
.admin-doc-show-meta-bar {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 2rem;
}
.admin-doc-show-meta-item {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 999px;
    font-size: 0.9rem;
    font-weight: 500;
    border: 1px solid transparent;
}
.admin-doc-show-meta-item i {
    opacity: 0.9;
}
.admin-doc-section {
    margin-bottom: 2rem;
    padding-bottom: 1.75rem;
    border-bottom: 1px solid transparent;
}
.admin-doc-section:last-of-type {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}
.admin-doc-section-title {
    font-size: 1rem;
    font-weight: 700;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.6rem;
    text-transform: uppercase;
    letter-spacing: 0.06em;
}
.admin-doc-section-title i {
    font-size: 1.1rem;
    opacity: 0.9;
}
.admin-doc-section p {
    margin: 0.35rem 0;
    line-height: 1.7;
}
.admin-doc-list {
    margin: 0.5rem 0 0 0;
    padding-left: 1.5rem;
    line-height: 1.7;
}
.admin-doc-list li {
    margin-bottom: 0.4rem;
}
.admin-doc-back-wrap {
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid transparent;
}
.admin-doc-back {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.65rem 1.25rem;
    border-radius: 999px;
    font-size: 0.9rem;
    font-weight: 600;
    text-decoration: none;
    border: 1px solid transparent;
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}
.admin-doc-back:hover {
    transform: translateX(-2px);
}

/* ----- Light theme ----- */
.admin-doc-show-page { background: linear-gradient(180deg, #f0f9ff 0%, #e0f2fe 25%, #f8fafc 60%); }
.admin-doc-show-hero-bg-img:not([style*="background-image"]) {
    background: linear-gradient(145deg, #0ea5e9 0%, #0284c7 50%, #0369a1 100%);
}
.admin-doc-show-card {
    background: #fff;
    border-color: rgba(226, 232, 240, 0.9);
}
.admin-doc-show-badge {
    background: #ecfeff;
    color: #0e7490;
    border-color: rgba(14, 165, 233, 0.4);
}
.admin-doc-show-title { color: #0f172a; }
.admin-doc-show-summary { color: #475569; }
.admin-doc-show-meta-item {
    background: #f1f5f9;
    color: #334155;
    border-color: rgba(203, 213, 225, 0.8);
}
.admin-doc-section { border-color: rgba(226, 232, 240, 0.8); }
.admin-doc-section-title { color: #0f172a; }
.admin-doc-section-title i { color: #0284c7; }
.admin-doc-section p { color: #475569; }
.admin-doc-list { color: #475569; }
.admin-doc-back-wrap { border-color: rgba(226, 232, 240, 0.8); }
.admin-doc-back {
    background: linear-gradient(135deg, #0ea5e9, #0284c7);
    color: #fff;
    border-color: transparent;
    box-shadow: 0 4px 14px rgba(14, 165, 233, 0.35);
}
.admin-doc-back:hover { box-shadow: 0 6px 20px rgba(14, 165, 233, 0.45); }

/* ----- Dark theme ----- */
body.dark-mode .admin-doc-show-page {
    background:
        radial-gradient(circle at top left, rgba(56, 189, 248, 0.12), transparent 50%),
        radial-gradient(circle at bottom right, rgba(8, 47, 73, 0.4), #020617 70%);
}
body.dark-mode .admin-doc-show-hero-bg-img:not([style*="background-image"]) {
    background: linear-gradient(145deg, #0c4a6e 0%, #075985 50%, #0369a1 100%);
}
body.dark-mode .admin-doc-show-card {
    background: rgba(15, 23, 42, 0.95);
    border-color: rgba(148, 163, 184, 0.35);
    box-shadow: 0 24px 60px rgba(0, 0, 0, 0.5);
}
body.dark-mode .admin-doc-show-badge {
    background: rgba(15, 23, 42, 0.9);
    color: #7dd3fc;
    border-color: rgba(56, 189, 248, 0.5);
}
body.dark-mode .admin-doc-show-title { color: #f9fafb; }
body.dark-mode .admin-doc-show-summary { color: #cbd5f5; }
body.dark-mode .admin-doc-show-meta-item {
    background: rgba(30, 41, 59, 0.8);
    color: #e2e8f0;
    border-color: rgba(71, 85, 105, 0.6);
}
body.dark-mode .admin-doc-section { border-color: rgba(51, 65, 85, 0.6); }
body.dark-mode .admin-doc-section-title { color: #e5e7eb; }
body.dark-mode .admin-doc-section-title i { color: #38bdf8; }
body.dark-mode .admin-doc-section p { color: #cbd5f5; }
body.dark-mode .admin-doc-list { color: #cbd5f5; }
body.dark-mode .admin-doc-back-wrap { border-color: rgba(51, 65, 85, 0.6); }
body.dark-mode .admin-doc-back {
    background: linear-gradient(135deg, #0ea5e9, #0284c7);
    color: #fff;
    border-color: rgba(56, 189, 248, 0.4);
    box-shadow: 0 4px 20px rgba(14, 165, 233, 0.35);
}
body.dark-mode .admin-doc-back:hover { box-shadow: 0 6px 28px rgba(14, 165, 233, 0.5); }

@media (max-width: 640px) {
    .admin-doc-show-hero { min-height: 220px; padding-bottom: 1.5rem; margin-bottom: -1.5rem; }
    .admin-doc-show-card { padding: 1.5rem 1.25rem; }
    .admin-doc-show-title { font-size: 1.5rem; }
    .admin-doc-show-meta-bar { gap: 0.65rem; }
    .admin-doc-show-meta-item { padding: 0.4rem 0.85rem; font-size: 0.85rem; }
}
</style>
@endpush

@section('content')
@php
    $coverUrl = $document->cover_image
        ? ($document->cover_type === 'internal'
            ? \Illuminate\Support\Facades\URL::temporarySignedRoute('admin-docs.cover.signed', now()->addHours(24), ['id' => $document->id])
            : $document->cover_image)
        : null;
@endphp
<div class="admin-doc-show-page">
    <header class="admin-doc-show-hero">
        @if($coverUrl)
            <div class="admin-doc-show-hero-bg-img" style="background-image: url('{{ $coverUrl }}');"></div>
        @else
            <div class="admin-doc-show-hero-bg-img"></div>
        @endif
        <div class="admin-doc-show-hero-bg"></div>
        <div class="admin-doc-show-hero-inner">
            @if($document->category)
                <div class="admin-doc-show-badge">
                    <i class="fas fa-folder-open"></i> {{ $document->category }}
                </div>
            @endif
            <h1 class="admin-doc-show-title" style="color: #fff; text-shadow: 0 2px 20px rgba(0,0,0,0.5);">{{ $document->title }}</h1>
        </div>
    </header>

    <div class="admin-doc-show-container">
        <article class="admin-doc-show-card">
            @if($document->summary)
                <p class="admin-doc-show-summary">{!! $document->summary !!}</p>
            @endif

            <div class="admin-doc-show-meta-bar">
                @if($document->approx_cost)
                    <span class="admin-doc-show-meta-item">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>Coût approximatif : {!! $document->approx_cost !!}</span>
                    </span>
                @endif
                @if($document->approx_delay)
                    <span class="admin-doc-show-meta-item">
                        <i class="fas fa-clock"></i>
                        <span>Délais : {!! $document->approx_delay !!}</span>
                    </span>
                @endif
            </div>

            @if($document->purpose)
                <section class="admin-doc-section">
                    <h2 class="admin-doc-section-title">
                        <i class="fas fa-info-circle"></i> À quoi sert ce document ?
                    </h2>
                    <p>{!! $document->purpose !!}</p>
                </section>
            @endif

            @if($document->target_audience)
                <section class="admin-doc-section">
                    <h2 class="admin-doc-section-title">
                        <i class="fas fa-user-check"></i> Qui peut le demander ?
                    </h2>
                    <p>{!! $document->target_audience !!}</p>
                </section>
            @endif

            @if(is_array($document->required_documents) && count($document->required_documents))
                <section class="admin-doc-section">
                    <h2 class="admin-doc-section-title">
                        <i class="fas fa-list-ul"></i> Pièces à fournir
                    </h2>
                    <ul class="admin-doc-list">
                        @foreach($document->required_documents as $item)
                            <li>{!! $item !!}</li>
                        @endforeach
                    </ul>
                </section>
            @endif

            @if(is_array($document->where_to_apply) && count($document->where_to_apply))
                <section class="admin-doc-section">
                    <h2 class="admin-doc-section-title">
                        <i class="fas fa-building"></i> Où déposer le dossier ?
                    </h2>
                    <ul class="admin-doc-list">
                        @foreach($document->where_to_apply as $place)
                            <li>{!! $place !!}</li>
                        @endforeach
                    </ul>
                </section>
            @endif

            @if($document->tips)
                <section class="admin-doc-section">
                    <h2 class="admin-doc-section-title">
                        <i class="fas fa-lightbulb"></i> Conseils & erreurs à éviter
                    </h2>
                    <p>{!! $document->tips !!}</p>
                </section>
            @endif

            <div class="admin-doc-back-wrap">
                <a href="{{ route('admin-docs.index') }}" class="admin-doc-back">
                    <i class="fas fa-arrow-left"></i>
                    Retour à la liste des papiers administratifs
                </a>
            </div>
        </article>
    </div>
</div>
@endsection
