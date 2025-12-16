@extends('admin.layout')

@section('title', isset($article) ? 'Modifier Article' : 'Nouvel Article')

@section('styles')
<style>
    /* Fonts chargées via preload dans admin.layout - pas de @import bloquant */
    
    .article-form-wrapper {
        max-width: 1600px;
        margin: 0 auto;
    }
    
    .form-hero {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(20, 184, 166, 0.1) 100%);
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 24px;
        padding: 50px;
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
    }
    
    .form-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(6, 182, 212, 0.1) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }
    
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    .form-hero-content {
        position: relative;
        z-index: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .btn-back {
        padding: 12px 24px;
        background: rgba(6, 182, 212, 0.1);
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 12px;
        color: #06b6d4;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }
    
    .btn-back:hover {
        background: rgba(6, 182, 212, 0.2);
        border-color: rgba(6, 182, 212, 0.5);
        transform: translateY(-2px);
    }
    
    .form-hero h1 {
        font-family: 'Poppins', sans-serif;
        font-size: 3rem;
        font-weight: 900;
        background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 50%, #06b6d4 100%);
        background-size: 200% auto;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: shimmer 3s linear infinite;
        margin-bottom: 15px;
    }
    
    @keyframes shimmer {
        to { background-position: 200% center; }
    }
    
    .form-hero p {
        font-size: 1.1rem;
        color: rgba(255, 255, 255, 0.7);
        font-weight: 400;
        transition: color 0.3s ease;
    }
    
    body.light-mode .form-hero {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.05) 0%, rgba(20, 184, 166, 0.05) 100%);
        border-color: rgba(6, 182, 212, 0.4);
    }
    
    body.light-mode .form-hero p {
        color: rgba(30, 41, 59, 0.8);
    }
    
    body.light-mode .btn-back {
        background: rgba(6, 182, 212, 0.08);
        border-color: rgba(6, 182, 212, 0.4);
    }
    
    body.light-mode .btn-back:hover {
        background: rgba(6, 182, 212, 0.15);
        border-color: rgba(6, 182, 212, 0.6);
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
    }
    
    .form-card {
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 20px;
        padding: 35px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
        margin-bottom: 25px;
    }
    
    body.light-mode .form-card {
        background: rgba(255, 255, 255, 0.8);
        border-color: rgba(6, 182, 212, 0.3);
    }
    
    body.light-mode .form-card:hover {
        border-color: rgba(6, 182, 212, 0.5);
        box-shadow: 0 20px 60px rgba(6, 182, 212, 0.15);
    }
    
    .form-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(6, 182, 212, 0.1), transparent);
        transition: left 0.6s;
    }
    
    .form-card:hover::before {
        left: 100%;
    }
    
    .form-card:hover {
        border-color: rgba(6, 182, 212, 0.5);
        transform: translateY(-5px);
        box-shadow: 0 20px 60px rgba(6, 182, 212, 0.2);
    }
    
    .form-section-title {
        font-family: 'Poppins', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #06b6d4;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid rgba(6, 182, 212, 0.3);
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .form-section-title i {
        font-size: 1.3rem;
    }
    
    .form-group {
        margin-bottom: 25px;
    }
    
    .form-label {
        display: block;
        font-family: 'Inter', sans-serif;
        font-size: 0.95rem;
        font-weight: 600;
        color: #06b6d4;
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 8px 12px;
        background: rgba(15, 23, 42, 0.8);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 10px;
        color: #fff;
        font-size: 0.85rem;
        font-family: 'Inter', sans-serif;
        transition: all 0.3s ease;
    }
    
    body.light-mode .form-input,
    body.light-mode .form-select,
    body.light-mode .form-textarea {
        background: rgba(255, 255, 255, 0.9);
        border-color: rgba(6, 182, 212, 0.3);
        color: #1e293b;
    }
    
    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #06b6d4;
        background: rgba(15, 23, 42, 0.95);
        box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.1);
    }
    
    body.light-mode .form-input:focus,
    body.light-mode .form-select:focus,
    body.light-mode .form-textarea:focus {
        background: rgba(255, 255, 255, 1);
        box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.15);
    }
    
    body.light-mode .form-select option {
        background: #ffffff;
        color: #1e293b;
    }
    
    .form-textarea {
        resize: vertical;
        min-height: 120px;
    }
    
    .form-textarea-large {
        min-height: 400px;
        font-family: 'Courier New', monospace;
        line-height: 1.8;
    }
    
    .form-help {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.5);
        margin-top: 8px;
        font-style: italic;
        transition: color 0.3s ease;
    }
    
    body.light-mode .form-help {
        color: rgba(30, 41, 59, 0.6);
    }
    
    body.light-mode .form-help a {
        color: #06b6d4;
    }
    
    body.light-mode .form-help a:hover {
        color: #14b8a6;
    }
    
    .form-error {
        color: #ef4444;
        font-size: 0.85rem;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .editor-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px;
        background: rgba(6, 182, 212, 0.05);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 12px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }
    
    body.light-mode .editor-toolbar {
        background: rgba(6, 182, 212, 0.08);
        border-color: rgba(6, 182, 212, 0.3);
    }
    
    .editor-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    
    .editor-btn {
        padding: 8px 12px;
        background: rgba(6, 182, 212, 0.1);
        border: 1px solid rgba(6, 182, 212, 0.3);
        border-radius: 8px;
        color: #06b6d4;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.85rem;
    }
    
    .editor-btn:hover {
        background: rgba(6, 182, 212, 0.2);
        border-color: rgba(6, 182, 212, 0.5);
        transform: translateY(-2px);
    }
    
    .word-count {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.6);
        font-weight: 600;
        transition: color 0.3s ease;
    }
    
    body.light-mode .word-count {
        color: rgba(30, 41, 59, 0.7);
    }
    
    .seo-card {
        background: rgba(15, 23, 42, 0.6);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }
    
    body.light-mode .seo-card {
        background: rgba(255, 255, 255, 0.7);
        border-color: rgba(6, 182, 212, 0.3);
    }
    
    .seo-score {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    
    .seo-score-value {
        font-size: 2.5rem;
        font-weight: 900;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .seo-progress {
        width: 100%;
        height: 12px;
        background: rgba(15, 23, 42, 0.8);
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 20px;
        transition: background 0.3s ease;
    }
    
    body.light-mode .seo-progress {
        background: rgba(241, 245, 249, 0.8);
    }
    
    .seo-progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #ef4444, #f59e0b, #22c55e);
        border-radius: 10px;
        transition: width 0.3s ease;
    }
    
    .seo-detail-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid rgba(6, 182, 212, 0.1);
        transition: border-color 0.3s ease;
    }
    
    body.light-mode .seo-detail-item {
        border-bottom-color: rgba(6, 182, 212, 0.2);
    }
    
    body.light-mode .seo-detail-item span {
        color: rgba(30, 41, 59, 0.7) !important;
    }
    
    body.light-mode .seo-detail-item span[style*="color: rgba(255, 255, 255, 0.5)"] {
        color: rgba(30, 41, 59, 0.6) !important;
    }
    
    body.light-mode .seo-detail-item span[style*="color: rgba(255, 255, 255, 0.7)"] {
        color: rgba(30, 41, 59, 0.7) !important;
    }
    
    body.light-mode span[style*="color: rgba(255, 255, 255, 0.9)"] {
        color: rgba(30, 41, 59, 0.9) !important;
    }
    
    .seo-detail-item:last-child {
        border-bottom: none;
    }
    
    .seo-check {
        font-size: 1.2rem;
    }
    
    .seo-check.success {
        color: #22c55e;
    }
    
    .seo-check.warning {
        color: #f59e0b;
    }
    
    .seo-check.error {
        color: #ef4444;
    }
    
    .form-actions {
        display: flex;
        gap: 15px;
        margin-top: 40px;
        padding-top: 30px;
        border-top: 2px solid rgba(6, 182, 212, 0.2);
        transition: border-color 0.3s ease;
    }
    
    body.light-mode .form-actions {
        border-top-color: rgba(6, 182, 212, 0.3);
    }
    
    .btn-submit {
        flex: 1;
        padding: 12px 24px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: #000;
        border: none;
        border-radius: 12px;
        font-family: 'Poppins', sans-serif;
        font-size: 0.95rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(6, 182, 212, 0.5);
    }
    
    @media (max-width: 1200px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }
    
        @media (max-width: 768px) {
        .form-hero {
            padding: 25px;
        }
        
        .form-hero-content {
            flex-direction: column;
            align-items: flex-start;
            gap: 20px;
        }
        
        .form-hero h1 {
            font-size: 2rem;
        }
        
        .form-card {
            padding: 25px;
        }
        
        .editor-toolbar {
            flex-direction: column;
            gap: 15px;
        }
    }
</style>
@endsection

@section('content')
<div class="article-form-wrapper">
    <!-- Hero Section -->
    <div class="form-hero">
        <div class="form-hero-content">
            <div>
                <h1><i class="fas fa-{{ isset($article) ? 'edit' : 'plus' }}"></i> {{ isset($article) ? 'Modifier Article' : 'Nouvel Article' }}</h1>
                <p>{{ isset($article) ? 'Modifiez l\'article d\'emploi' : 'Créez un nouvel article d\'emploi' }}</p>
            </div>
            <a href="{{ route('admin.jobs.articles.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                Retour à la liste
            </a>
        </div>
    </div>
    
    <form action="{{ isset($article) ? route('admin.jobs.articles.update', $article->id) : route('admin.jobs.articles.store') }}" method="POST" enctype="multipart/form-data" id="articleForm">
        @csrf
        @if(isset($article))
            @method('PUT')
        @endif
        
        <div class="form-grid">
            <!-- Colonne principale (contenu) -->
            <div>
                <!-- Titre -->
                <div class="form-card">
                    <div class="form-section-title">
                        <i class="fas fa-heading"></i>
                        <span>Titre de l'article</span>
                    </div>
                    <div class="form-group">
                        <input type="text" name="title" id="articleTitle" value="{{ old('title', $article->title ?? '') }}" required
                               class="form-input" style="font-size: 1.5rem; font-weight: 700;" placeholder="Entrez le titre de l'article">
                        @error('title')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Éditeur de contenu -->
                <div class="form-card">
                    <div class="form-section-title">
                        <i class="fas fa-file-alt"></i>
                        <span>Contenu de l'article</span>
                    </div>
                    <div class="editor-toolbar">
                        <div class="editor-buttons">
                            <button type="button" onclick="formatText('bold')" class="editor-btn" title="Gras">
                                <i class="fas fa-bold"></i>
                            </button>
                            <button type="button" onclick="formatText('italic')" class="editor-btn" title="Italique">
                                <i class="fas fa-italic"></i>
                            </button>
                            <button type="button" onclick="formatText('underline')" class="editor-btn" title="Souligné">
                                <i class="fas fa-underline"></i>
                            </button>
                            <button type="button" onclick="insertLink()" class="editor-btn" title="Lien">
                                <i class="fas fa-link"></i>
                            </button>
                            <button type="button" onclick="insertList('ul')" class="editor-btn" title="Liste">
                                <i class="fas fa-list-ul"></i>
                            </button>
                            <button type="button" onclick="insertList('ol')" class="editor-btn" title="Liste numérotée">
                                <i class="fas fa-list-ol"></i>
                            </button>
                        </div>
                        <div class="word-count">
                            <span id="wordCount">0</span> <span id="wordLabel">mots</span>
                        </div>
                    </div>
                    <textarea name="content" id="articleContent" required
                              class="form-textarea form-textarea-large" 
                              placeholder="Commencez à écrire votre article...">{{ old('content', $article->content ?? '') }}</textarea>
                    @error('content')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Extrait -->
                <div class="form-card">
                    <div class="form-section-title">
                        <i class="fas fa-align-left"></i>
                        <span>Extrait</span>
                    </div>
                    <div class="form-group">
                        <textarea name="excerpt" id="articleExcerpt" rows="4" 
                                  class="form-textarea" 
                                  placeholder="Résumé court de l'article (optionnel)">{{ old('excerpt', $article->excerpt ?? '') }}</textarea>
                        <div class="form-help">L'extrait est utilisé dans les aperçus et les résultats de recherche.</div>
                    </div>
                </div>

                <!-- Analyse SEO et Lisibilité détaillée -->
                <div class="form-card">
                    <div class="form-section-title">
                        <i class="fas fa-chart-line"></i>
                        <span>Analyse SEO & Lisibilité</span>
                    </div>
                    <div class="seo-card">
                        <div class="seo-score">
                            <span style="font-weight: 700; color: #06b6d4; font-size: 1.2rem;">Score SEO</span>
                            <span id="seoScoreDetail" class="seo-score-value">0/100</span>
                        </div>
                        <div class="seo-progress">
                            <div id="seoBarDetail" class="seo-progress-bar" style="width: 0%"></div>
                        </div>
                        <div id="seoDetails" class="space-y-2">
                            <div class="seo-detail-item">
                                <span style="color: rgba(255, 255, 255, 0.7);">Titre (30-60 caractères)</span>
                                <span id="titleCheck" class="seo-check error"><i class="fas fa-times"></i></span>
                            </div>
                            <div class="seo-detail-item">
                                <span style="color: rgba(255, 255, 255, 0.7);">Meta Title (30-60 caractères)</span>
                                <span id="metaTitleCheck" class="seo-check error"><i class="fas fa-times"></i></span>
                            </div>
                            <div class="seo-detail-item">
                                <span style="color: rgba(255, 255, 255, 0.7);">Meta Description (120-160 caractères)</span>
                                <span id="metaDescCheck" class="seo-check error"><i class="fas fa-times"></i></span>
                            </div>
                            <div class="seo-detail-item">
                                <span style="color: rgba(255, 255, 255, 0.7);">Contenu (≥300 mots)</span>
                                <span id="contentCheck" class="seo-check error"><i class="fas fa-times"></i></span>
                            </div>
                            <div class="seo-detail-item">
                                <span style="color: rgba(255, 255, 255, 0.7);">Extrait (≥100 caractères)</span>
                                <span id="excerptCheck" class="seo-check error"><i class="fas fa-times"></i></span>
                            </div>
                            <div class="seo-detail-item">
                                <span style="color: rgba(255, 255, 255, 0.7);">Image de couverture</span>
                                <span id="imageCheck" class="seo-check error"><i class="fas fa-times"></i></span>
                            </div>
                            <div class="seo-detail-item">
                                <span style="color: rgba(255, 255, 255, 0.7);">Mots-clés (3-10)</span>
                                <span id="keywordsCheck" class="seo-check error"><i class="fas fa-times"></i></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="seo-card">
                        <div class="seo-score">
                            <span style="font-weight: 700; color: #06b6d4; font-size: 1.2rem;">Score Lisibilité</span>
                            <span id="readabilityScoreDetail" class="seo-score-value">0/100</span>
                        </div>
                        <div class="seo-progress">
                            <div id="readabilityBarDetail" class="seo-progress-bar" style="width: 0%"></div>
                        </div>
                        <div id="readabilityDetails" class="space-y-2">
                            <div class="seo-detail-item">
                                <span style="color: rgba(255, 255, 255, 0.7);">Mots par phrase (10-15 idéal)</span>
                                <span id="wordsPerSentence" style="color: rgba(255, 255, 255, 0.5);">-</span>
                            </div>
                            <div class="seo-detail-item">
                                <span style="color: rgba(255, 255, 255, 0.7);">Phrases par paragraphe (≤5 idéal)</span>
                                <span id="sentencesPerParagraph" style="color: rgba(255, 255, 255, 0.5);">-</span>
                            </div>
                            <div class="seo-detail-item">
                                <span style="color: rgba(255, 255, 255, 0.7);">Nombre de paragraphes</span>
                                <span id="paragraphCount" style="color: rgba(255, 255, 255, 0.5);">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar (métadonnées) -->
            <div>
                <!-- Publier -->
                <div class="form-card">
                    <div class="form-section-title">
                        <i class="fas fa-paper-plane"></i>
                        <span>Publier</span>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Statut</label>
                        <select name="status" id="articleStatus" class="form-select">
                            <option value="draft" {{ old('status', $article->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Brouillon</option>
                            <option value="published" {{ old('status', $article->status ?? '') === 'published' ? 'selected' : '' }}>Publié</option>
                            <option value="archived" {{ old('status', $article->status ?? '') === 'archived' ? 'selected' : '' }}>Archivé</option>
                        </select>
                    </div>
                    
                    <!-- Article sponsorisé -->
                    <div class="form-group" style="margin-top: 20px; display: flex; gap: 30px; flex-wrap: wrap;">
                        <label class="form-label">
                            <input type="checkbox" name="is_sponsored" value="1" 
                                   {{ old('is_sponsored', isset($article) && $article->is_sponsored ? true : false) ? 'checked' : '' }}
                                   class="form-checkbox" style="width: 20px; height: 20px; margin-right: 10px; cursor: pointer;">
                            <span style="font-weight: 600; color: rgba(255, 255, 255, 0.9); transition: color 0.3s ease;">
                                <i class="fas fa-star" style="color: #f59e0b; margin-right: 6px;"></i>
                                Article sponsorisé
                            </span>
                        </label>
                        <label class="form-label">
                            <input type="checkbox" name="is_featured" value="1" 
                                   {{ old('is_featured', isset($article) && $article->is_featured ? true : false) ? 'checked' : '' }}
                                   class="form-checkbox" style="width: 20px; height: 20px; margin-right: 10px; cursor: pointer;">
                            <span style="font-weight: 600; color: rgba(255, 255, 255, 0.9); transition: color 0.3s ease;">
                                <i class="fas fa-fire" style="color: #ef4444; margin-right: 6px;"></i>
                                Article vedette
                            </span>
                        </label>
                        </label>
                        <div class="form-help" style="margin-top: 8px; padding-left: 30px;">
                            Les articles sponsorisés apparaissent dans la section "Articles Premium" de la page d'accueil.
                        </div>
                    </div>
                    
                    <div class="form-actions" style="margin-top: 20px; padding-top: 20px; border-top: 2px solid rgba(6, 182, 212, 0.2);">
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i>
                            <span>Enregistrer</span>
                        </button>
                    </div>
                </div>

                <!-- Catégorie -->
                <div class="form-card">
                    <div class="form-section-title">
                        <i class="fas fa-folder"></i>
                        <span>Catégorie</span>
                    </div>
                    <div class="form-group">
                        <select name="category_id" required class="form-select">
                            <option value="">Sélectionnez une catégorie</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $article->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Image de couverture -->
                <div class="form-card">
                    <div class="form-section-title">
                        <i class="fas fa-image"></i>
                        <span>Image de couverture</span>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Type d'image</label>
                        <select name="cover_type" id="coverType" class="form-select">
                            <option value="internal" {{ old('cover_type', $article->cover_type ?? 'internal') === 'internal' ? 'selected' : '' }}>Interne (upload)</option>
                            <option value="external" {{ old('cover_type', $article->cover_type ?? '') === 'external' ? 'selected' : '' }}>Externe (URL)</option>
                        </select>
                    </div>
                    <div id="internalImage" style="display: {{ old('cover_type', $article->cover_type ?? 'internal') === 'internal' ? 'block' : 'none' }};">
                        <div class="form-group">
                            <label class="form-label">Fichier image</label>
                            <input type="file" name="cover_image_file" id="coverImageFile" accept="image/*" class="form-input">
                            @if(isset($article) && $article->cover_type === 'internal' && $article->cover_image)
                                <div class="form-help">
                                    Image actuelle: <a href="{{ \Illuminate\Support\Facades\Storage::url($article->cover_image) }}" target="_blank" style="color: #06b6d4; text-decoration: none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">{{ basename($article->cover_image) }}</a>
                                </div>
                            @else
                                <div class="form-help">Formats acceptés: JPG, PNG, GIF</div>
                            @endif
                        </div>
                    </div>
                    <div id="externalImage" style="display: {{ old('cover_type', $article->cover_type ?? '') === 'external' ? 'block' : 'none' }};">
                        <div class="form-group">
                            <label class="form-label">URL de l'image</label>
                            <input type="url" name="cover_image_url" id="coverImageUrl" value="{{ old('cover_image', $article->cover_image ?? '') }}"
                                   class="form-input" placeholder="https://example.com/image.jpg">
                            <div class="form-help">Entrez l'URL complète de l'image</div>
                        </div>
                    </div>
                    <div id="coverPreview" class="mt-4 {{ (isset($article) && $article->cover_image) ? '' : 'hidden' }}" style="text-align: center;">
                        <img id="previewImg" src="{{ isset($article) && $article->cover_image ? ($article->cover_type === 'internal' ? \Illuminate\Support\Facades\Storage::url($article->cover_image) : $article->cover_image) : '' }}" alt="Aperçu" style="max-width: 100%; border-radius: 12px; border: 2px solid rgba(6, 182, 212, 0.3);">
                    </div>
                </div>

                <!-- SEO et Lisibilité -->
                <div class="form-card">
                    <div class="form-section-title">
                        <i class="fas fa-chart-bar"></i>
                        <span>SEO & Lisibilité</span>
                    </div>
                    <div class="form-group">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                            <span style="color: #06b6d4; font-weight: 600;">Score SEO</span>
                            <span id="seoScore" style="font-size: 1.8rem; font-weight: 900; color: #06b6d4;">0</span>
                        </div>
                        <div class="seo-progress">
                            <div id="seoBar" class="seo-progress-bar" style="width: 0%"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                            <span style="color: #06b6d4; font-weight: 600;">Lisibilité</span>
                            <span id="readabilityScore" style="font-size: 1.8rem; font-weight: 900; color: #06b6d4;">0</span>
                        </div>
                        <div class="seo-progress">
                            <div id="readabilityBar" class="seo-progress-bar" style="width: 0%"></div>
                        </div>
                    </div>
                </div>

                <!-- Métadonnées SEO -->
                <div class="form-card">
                    <div class="form-section-title">
                        <i class="fas fa-tags"></i>
                        <span>Métadonnées SEO</span>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Meta Title</label>
                        <input type="text" name="meta_title" id="metaTitle" value="{{ old('meta_title', $article->meta_title ?? '') }}"
                               class="form-input" maxlength="60" placeholder="Titre SEO (max 60 caractères)">
                        <div class="form-help"><span id="metaTitleLength">0</span>/60 caractères</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Meta Description</label>
                        <textarea name="meta_description" id="metaDescription" rows="3"
                                  class="form-textarea" maxlength="160" placeholder="Description SEO (max 160 caractères)">{{ old('meta_description', $article->meta_description ?? '') }}</textarea>
                        <div class="form-help"><span id="metaDescLength">0</span>/160 caractères</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Mots-clés</label>
                        <input type="text" name="meta_keywords" value="{{ old('meta_keywords', isset($article) && $article->meta_keywords ? implode(', ', $article->meta_keywords) : '') }}"
                               class="form-input" placeholder="mot-clé1, mot-clé2, mot-clé3">
                        <div class="form-help">Séparez les mots-clés par des virgules</div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@section('scripts')
<script src="{{ asset('js/article-editor.js') }}"></script>
<script>
    // S'assurer que le script s'exécute après le chargement du DOM
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser les scores à 0 pour un nouvel article
        setTimeout(function() {
            const seoScoreDetail = document.getElementById('seoScoreDetail');
            const seoBarDetail = document.getElementById('seoBarDetail');
            const readabilityScoreDetail = document.getElementById('readabilityScoreDetail');
            const readabilityBarDetail = document.getElementById('readabilityBarDetail');
            const seoScore = document.getElementById('seoScore');
            const seoBar = document.getElementById('seoBar');
            const readabilityScore = document.getElementById('readabilityScore');
            const readabilityBar = document.getElementById('readabilityBar');
            
            // Initialiser à 0 pour un nouvel article
            if (seoScoreDetail) seoScoreDetail.textContent = '0/100';
            if (seoBarDetail) seoBarDetail.style.width = '0%';
            if (readabilityScoreDetail) readabilityScoreDetail.textContent = '0/100';
            if (readabilityBarDetail) readabilityBarDetail.style.width = '0%';
            if (seoScore) seoScore.textContent = '0';
            if (seoBar) seoBar.style.width = '0%';
            if (readabilityScore) readabilityScore.textContent = '0';
            if (readabilityBar) readabilityBar.style.width = '0%';
            
            const wordCountSpan = document.getElementById('wordCount');
            const contentTextarea = document.getElementById('articleContent');
            
            if (wordCountSpan && contentTextarea) {
                contentTextarea.dispatchEvent(new Event('input'));
            }
            
            const titleInput = document.getElementById('articleTitle');
            if (titleInput) {
                titleInput.dispatchEvent(new Event('input'));
            }
        }, 500);
    });
</script>
@endsection
@endsection
