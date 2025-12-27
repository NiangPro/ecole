@extends('admin.layout')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'Détails Document')

@section('styles')
<style>
    /* Variables CSS - Design System */
    :root {
        --primary: #06b6d4;
        --secondary: #14b8a6;
        --accent: #8b5cf6;
        --success: #22c55e;
        --warning: #f59e0b;
        --danger: #ef4444;
        --bg-dark: #0a0e27;
        --bg-card: rgba(30, 41, 59, 0.6);
        --text-primary: #ffffff;
        --text-secondary: rgba(255, 255, 255, 0.8);
        --text-muted: rgba(255, 255, 255, 0.6);
        --border: rgba(6, 182, 212, 0.2);
    }

    /* Page Container */
    .document-show-page {
        min-height: 100vh;
        background: var(--bg-dark);
        padding: 2rem 0;
        position: relative;
        overflow-x: hidden;
    }

    /* Animated Background */
    .document-show-page::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: 
            radial-gradient(circle at 20% 30%, rgba(6, 182, 212, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 70%, rgba(20, 184, 166, 0.1) 0%, transparent 50%);
        animation: bgFloat 20s ease infinite;
        pointer-events: none;
        z-index: 0;
    }

    @keyframes bgFloat {
        0%, 100% { transform: translate(0, 0) scale(1); }
        50% { transform: translate(20px, -20px) scale(1.1); }
    }

    .document-show-page > * {
        position: relative;
        z-index: 1;
    }

    /* Hero Section */
    .page-hero {
        background: linear-gradient(135deg, 
            rgba(6, 182, 212, 0.1) 0%, 
            rgba(20, 184, 166, 0.1) 100%);
        backdrop-filter: blur(30px);
        -webkit-backdrop-filter: blur(30px);
        border: 1px solid var(--border);
        border-radius: 24px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 60px rgba(6, 182, 212, 0.15);
        position: relative;
        overflow: hidden;
    }

    .page-hero::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, 
            transparent, 
            rgba(255, 255, 255, 0.05), 
            transparent);
        animation: shimmer 3s infinite;
    }

    @keyframes shimmer {
        0% { left: -100%; }
        100% { left: 100%; }
    }

    .page-title {
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 900;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
        line-height: 1.2;
    }

    .page-subtitle {
        color: var(--text-secondary);
        font-size: 1.125rem;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 0.875rem 1.75rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.875rem;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        position: relative;
        overflow: hidden;
    }

    .btn-action::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.5s, height 0.5s;
    }

    .btn-action:hover::before {
        width: 300px;
        height: 300px;
    }

    .btn-action span {
        position: relative;
        z-index: 1;
    }

    .btn-edit {
        background: linear-gradient(135deg, var(--warning), #d97706);
        color: white;
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
    }

    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(245, 158, 11, 0.5);
    }

    .btn-back {
        background: var(--bg-card);
        color: white;
        border: 1px solid var(--border);
    }

    .btn-back:hover {
        border-color: var(--primary);
        transform: translateY(-2px);
    }

    /* Modern Cards */
    .modern-card {
        background: var(--bg-card);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .modern-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, 
            transparent, 
            var(--primary), 
            var(--secondary), 
            transparent);
        background-size: 200% 100%;
        animation: borderFlow 3s linear infinite;
    }

    @keyframes borderFlow {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    .modern-card:hover {
        transform: translateY(-5px);
        border-color: rgba(6, 182, 212, 0.4);
        box-shadow: 0 12px 40px rgba(6, 182, 212, 0.2);
    }

    .card-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .card-header i {
        font-size: 1.5rem;
        color: var(--primary);
    }

    .card-header h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary);
        margin: 0;
    }

    /* Info Items */
    .info-row {
        padding: 1rem;
        background: rgba(15, 23, 42, 0.4);
        border-radius: 12px;
        border: 1px solid rgba(6, 182, 212, 0.1);
        margin-bottom: 0.75rem;
        transition: all 0.3s ease;
    }

    .info-row:hover {
        background: rgba(15, 23, 42, 0.6);
        border-color: rgba(6, 182, 212, 0.3);
        transform: translateX(5px);
    }

    .info-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--primary);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-value {
        font-size: 1rem;
        color: var(--text-primary);
        font-weight: 500;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .stat-box {
        background: linear-gradient(135deg, 
            rgba(6, 182, 212, 0.15), 
            rgba(20, 184, 166, 0.15));
        border: 1px solid rgba(6, 182, 212, 0.3);
        border-radius: 16px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-box::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, 
            rgba(6, 182, 212, 0.2) 0%, 
            transparent 70%);
        animation: pulse 3s ease infinite;
        opacity: 0;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0; }
        50% { transform: scale(1.2); opacity: 0.5; }
    }

    .stat-box:hover {
        transform: translateY(-5px);
        border-color: rgba(6, 182, 212, 0.5);
        box-shadow: 0 10px 30px rgba(6, 182, 212, 0.3);
    }

    .stat-box:hover::before {
        opacity: 1;
    }

    .stat-icon {
        font-size: 2.5rem;
        color: var(--primary);
        margin-bottom: 0.75rem;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.25rem;
    }

    .stat-text {
        font-size: 0.75rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
    }

    /* Badges */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .badge-success {
        background: rgba(34, 197, 94, 0.2);
        color: #4ade80;
        border: 1px solid rgba(34, 197, 94, 0.3);
    }

    .badge-warning {
        background: rgba(148, 163, 184, 0.2);
        color: #cbd5e1;
        border: 1px solid rgba(148, 163, 184, 0.3);
    }

    .badge-danger {
        background: rgba(239, 68, 68, 0.2);
        color: #f87171;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    /* Tags */
    .tags-container {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .tag {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: rgba(6, 182, 212, 0.15);
        border: 1px solid rgba(6, 182, 212, 0.3);
        border-radius: 20px;
        color: var(--primary);
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .tag:hover {
        background: rgba(6, 182, 212, 0.25);
        transform: translateY(-2px);
    }

    /* Price Display */
    .price-card {
        background: linear-gradient(135deg, 
            rgba(6, 182, 212, 0.2), 
            rgba(20, 184, 166, 0.2));
        border: 2px solid rgba(6, 182, 212, 0.4);
        border-radius: 16px;
        padding: 2rem;
        text-align: center;
    }

    .price-main {
        font-size: 2.5rem;
        font-weight: 900;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
    }

    .price-old {
        font-size: 1.25rem;
        color: var(--text-muted);
        text-decoration: line-through;
    }

    /* Cover Image */
    .cover-wrapper {
        border-radius: 16px;
        overflow: hidden;
        border: 2px solid rgba(6, 182, 212, 0.3);
        box-shadow: 0 10px 30px rgba(6, 182, 212, 0.2);
        transition: all 0.3s ease;
    }

    .cover-wrapper:hover {
        transform: scale(1.02);
        box-shadow: 0 15px 40px rgba(6, 182, 212, 0.4);
    }

    .cover-wrapper img {
        width: 100%;
        height: auto;
        display: block;
    }

    /* Action Buttons in Card */
    .action-card .btn-action {
        width: 100%;
        justify-content: center;
    }

    .btn-publish {
        background: linear-gradient(135deg, var(--success), #16a34a);
        color: white;
        box-shadow: 0 4px 15px rgba(34, 197, 94, 0.3);
    }

    .btn-publish:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(34, 197, 94, 0.5);
    }

    .btn-unpublish {
        background: linear-gradient(135deg, var(--warning), #d97706);
        color: white;
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
    }

    .btn-unpublish:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(245, 158, 11, 0.5);
    }

    /* Flash Message */
    .flash-success {
        background: rgba(34, 197, 94, 0.15);
        border: 1px solid rgba(34, 197, 94, 0.3);
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        color: #4ade80;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .document-show-page {
            padding: 1rem 0;
        }

        .page-hero {
            padding: 1.5rem;
        }

        .modern-card {
            padding: 1.5rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-action {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endsection

@section('content')
<div class="document-show-page">
    <div class="container mx-auto px-4">
        <!-- Flash Message -->
        @if(session('success'))
        <div class="flash-success">
            <i class="fas fa-check-circle text-xl"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        <!-- Hero Section -->
        <div class="page-hero">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex-1">
                    <h1 class="page-title">{{ $document->title }}</h1>
                    <p class="page-subtitle">Détails et statistiques du document</p>
                </div>
                <div class="action-buttons">
                    <a href="{{ route('admin.documents.documents.edit', $document->id) }}" 
                       class="btn-action btn-edit">
                        <i class="fas fa-edit"></i>
                        <span>Modifier</span>
                    </a>
                    <a href="{{ route('admin.documents.documents.index') }}" 
                       class="btn-action btn-back">
                        <i class="fas fa-arrow-left"></i>
                        <span>Retour</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informations Générales -->
                <div class="modern-card">
                    <div class="card-header">
                        <i class="fas fa-info-circle"></i>
                        <h3>Informations Générales</h3>
                    </div>
                    <div class="space-y-2">
                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-heading"></i>
                                Titre
                            </div>
                            <div class="info-value">{{ $document->title }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-link"></i>
                                Slug
                            </div>
                            <div class="info-value font-mono text-sm opacity-75">{{ $document->slug }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-folder"></i>
                                Catégorie
                            </div>
                            <div class="info-value">
                                <span class="badge badge-success">
                                    <i class="fas fa-tag"></i>
                                    {{ $document->category->name }}
                                </span>
                            </div>
                        </div>
                        @if($document->description)
                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-align-left"></i>
                                Description
                            </div>
                            <div class="info-value">{{ $document->description }}</div>
                        </div>
                        @endif
                        @if($document->excerpt)
                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-file-alt"></i>
                                Résumé
                            </div>
                            <div class="info-value">{{ $document->excerpt }}</div>
                        </div>
                        @endif
                        @if($document->tags && count($document->tags) > 0)
                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-tags"></i>
                                Tags
                            </div>
                            <div class="tags-container mt-2">
                                @foreach($document->tags as $tag)
                                    <span class="tag">
                                        <i class="fas fa-hashtag"></i>
                                        {{ $tag }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Informations Fichier -->
                <div class="modern-card">
                    <div class="card-header">
                        <i class="fas fa-file"></i>
                        <h3>Informations Fichier</h3>
                    </div>
                    <div class="space-y-2">
                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-file-alt"></i>
                                Nom du fichier
                            </div>
                            <div class="info-value">{{ $document->file_name }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-file-code"></i>
                                Type
                            </div>
                            <div class="info-value">
                                <span class="badge badge-success">
                                    {{ strtoupper($document->file_extension) }}
                                </span>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-weight"></i>
                                Taille
                            </div>
                            <div class="info-value">{{ number_format($document->file_size / 1024, 2) }} KB</div>
                        </div>
                    </div>
                </div>

                <!-- Image de Couverture -->
                @if($document->cover_image)
                <div class="modern-card">
                    <div class="card-header">
                        <i class="fas fa-image"></i>
                        <h3>Image de Couverture</h3>
                    </div>
                    <div class="cover-wrapper">
                        @if($document->cover_type === 'internal')
                            <img src="{{ asset('storage/' . $document->cover_image) }}" alt="{{ $document->title }}">
                        @else
                            <img src="{{ $document->cover_image }}" alt="{{ $document->title }}">
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Statistiques -->
                <div class="modern-card">
                    <div class="card-header">
                        <i class="fas fa-chart-line"></i>
                        <h3>Statistiques</h3>
                    </div>
                    <div class="stats-grid">
                        <div class="stat-box">
                            <div class="stat-icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="stat-number">{{ $document->sales_count }}</div>
                            <div class="stat-text">Ventes</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-icon">
                                <i class="fas fa-eye"></i>
                            </div>
                            <div class="stat-number">{{ $document->views_count }}</div>
                            <div class="stat-text">Vues</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-icon">
                                <i class="fas fa-download"></i>
                            </div>
                            <div class="stat-number">{{ $document->download_count }}</div>
                            <div class="stat-text">Téléchargements</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="stat-number" style="font-size: 1.125rem; line-height: 1.4;">
                                {{ Str::limit($document->author->name, 12) }}
                            </div>
                            <div class="stat-text">Auteur</div>
                        </div>
                    </div>
                </div>

                <!-- Prix -->
                <div class="modern-card">
                    <div class="card-header">
                        <i class="fas fa-dollar-sign"></i>
                        <h3>Prix</h3>
                    </div>
                    <div class="price-card">
                        @if($document->hasDiscount())
                            <div class="price-old">{{ number_format($document->price, 0, ',', ' ') }} FCFA</div>
                            <div class="price-main">{{ number_format($document->current_price, 0, ',', ' ') }} FCFA</div>
                            <div class="text-cyan-400 text-sm font-bold mt-3">
                                <i class="fas fa-percent"></i> 
                                Réduction de {{ number_format($document->getDiscountPercentage(), 0) }}%
                            </div>
                        @else
                            <div class="price-main">{{ number_format($document->price, 0, ',', ' ') }} FCFA</div>
                        @endif
                    </div>
                </div>

                <!-- Statut -->
                <div class="modern-card">
                    <div class="card-header">
                        <i class="fas fa-info-circle"></i>
                        <h3>Statut</h3>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <div class="info-label mb-2">Statut de publication</div>
                            <div>
                                @if($document->status === 'published')
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle"></i>
                                        Publié
                                    </span>
                                @elseif($document->status === 'draft')
                                    <span class="badge badge-warning">
                                        <i class="fas fa-file-alt"></i>
                                        Brouillon
                                    </span>
                                @else
                                    <span class="badge badge-danger">
                                        <i class="fas fa-archive"></i>
                                        Archivé
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-calendar-plus"></i>
                                Créé le
                            </div>
                            <div class="info-value">{{ $document->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        @if($document->published_at)
                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-calendar-check"></i>
                                Publié le
                            </div>
                            <div class="info-value">{{ $document->published_at->format('d/m/Y H:i') }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="modern-card action-card">
                    <div class="card-header">
                        <i class="fas fa-bolt"></i>
                        <h3>Actions</h3>
                    </div>
                    <div class="space-y-3">
                        @if($document->status === 'draft')
                            <form action="{{ route('admin.documents.documents.publish', $document->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-action btn-publish">
                                    <i class="fas fa-check"></i>
                                    <span>Publier le document</span>
                                </button>
                            </form>
                        @elseif($document->status === 'published')
                            <form action="{{ route('admin.documents.documents.unpublish', $document->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-action btn-unpublish">
                                    <i class="fas fa-times"></i>
                                    <span>Dépublier le document</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
