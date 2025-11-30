@extends('admin.layout')

@section('title', 'Articles Emplois')

@section('styles')
<style>
    /* Scrollbar personnalisée pour la page */
    body {
        scrollbar-width: thin;
        scrollbar-color: rgba(6, 182, 212, 0.5) rgba(15, 23, 42, 0.3);
    }
    
    body::-webkit-scrollbar {
        width: 12px;
    }
    
    body::-webkit-scrollbar-track {
        background: rgba(15, 23, 42, 0.3);
        border-radius: 10px;
        transition: background 0.3s ease;
    }
    
    body.light-mode::-webkit-scrollbar-track {
        background: rgba(241, 245, 249, 0.5);
    }
    
    body::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #06b6d4, #14b8a6);
        border-radius: 10px;
        border: 2px solid rgba(15, 23, 42, 0.3);
        transition: all 0.3s ease;
    }
    
    body::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #14b8a6, #06b6d4);
        box-shadow: 0 0 10px rgba(6, 182, 212, 0.5);
    }
    
    /* Scrollbar pour les éléments avec overflow */
    .content-section,
    .overflow-x-auto {
        scrollbar-width: thin;
        scrollbar-color: rgba(6, 182, 212, 0.5) rgba(15, 23, 42, 0.3);
    }
    
    .content-section::-webkit-scrollbar,
    .overflow-x-auto::-webkit-scrollbar {
        width: 10px;
        height: 10px;
    }
    
    .content-section::-webkit-scrollbar-track,
    .overflow-x-auto::-webkit-scrollbar-track {
        background: rgba(15, 23, 42, 0.3);
        border-radius: 10px;
        transition: background 0.3s ease;
    }
    
    body.light-mode .content-section::-webkit-scrollbar-track,
    body.light-mode .overflow-x-auto::-webkit-scrollbar-track {
        background: rgba(241, 245, 249, 0.5);
    }
    
    .content-section::-webkit-scrollbar-thumb,
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border-radius: 10px;
        border: 2px solid rgba(15, 23, 42, 0.3);
    }
    
    .content-section::-webkit-scrollbar-thumb:hover,
    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #14b8a6, #06b6d4);
    }
    
    .article-thumbnail {
        width: 80px;
        height: 60px;
        border-radius: 8px;
        overflow: hidden;
        background: rgba(6, 182, 212, 0.1);
        border: 1px solid rgba(6, 182, 212, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .article-thumbnail.sponsored {
        border-color: rgba(245, 158, 11, 0.5);
        box-shadow: 0 0 10px rgba(245, 158, 11, 0.3);
    }
    
    .article-thumbnail:hover {
        border-color: rgba(6, 182, 212, 0.4);
        transform: scale(1.05);
    }
    
    .article-thumbnail.sponsored:hover {
        border-color: rgba(245, 158, 11, 0.7);
        box-shadow: 0 0 15px rgba(245, 158, 11, 0.5);
    }
    
    .article-thumbnail-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .article-thumbnail:hover .article-thumbnail-img {
        transform: scale(1.1);
    }
    
    /* Overlay étoiles pour articles sponsorisés */
    .sponsored-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.3) 0%, rgba(239, 68, 68, 0.3) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 3px;
        z-index: 2;
        border-radius: 8px;
        backdrop-filter: blur(2px);
    }
    
    .sponsored-star {
        color: #fbbf24;
        font-size: 12px;
        text-shadow: 0 0 8px rgba(251, 191, 36, 0.8), 0 0 4px rgba(251, 191, 36, 0.6);
        animation: starPulse 2s ease-in-out infinite;
    }
    
    .sponsored-star:nth-child(1) {
        animation-delay: 0s;
    }
    
    .sponsored-star:nth-child(2) {
        animation-delay: 0.2s;
    }
    
    .sponsored-star:nth-child(3) {
        animation-delay: 0.4s;
    }
    
    @keyframes starPulse {
        0%, 100% {
            opacity: 0.8;
            transform: scale(1);
        }
        50% {
            opacity: 1;
            transform: scale(1.2);
        }
    }
    
    .article-thumbnail-placeholder {
        width: 80px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(6, 182, 212, 0.1);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 8px;
        color: rgba(6, 182, 212, 0.5);
        font-size: 1.5rem;
        position: relative;
    }
    
    .article-thumbnail-placeholder.sponsored {
        border-color: rgba(245, 158, 11, 0.5);
        background: rgba(245, 158, 11, 0.1);
    }
    
    /* Modal de partage Facebook */
    .share-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.8);
        z-index: 10000;
        align-items: center;
        justify-content: center;
        padding: 20px;
        transition: background 0.3s ease;
    }
    
    body.light-mode .share-modal {
        background: rgba(0, 0, 0, 0.6);
    }
    
    .share-modal.active {
        display: flex;
    }
    
    .share-modal-content {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.95), rgba(30, 41, 59, 0.95));
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 16px;
        max-width: 600px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        transition: all 0.3s ease;
    }
    
    body.light-mode .share-modal-content {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.98), rgba(248, 250, 252, 0.98));
        border-color: rgba(6, 182, 212, 0.4);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    }
    
    .share-modal-header {
        padding: 24px;
        border-bottom: 1px solid rgba(6, 182, 212, 0.2);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .share-modal-header h3 {
        font-size: 1.5rem;
        font-weight: bold;
        color: #06b6d4;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .share-modal-close {
        background: transparent;
        border: none;
        color: #94a3b8;
        font-size: 1.5rem;
        cursor: pointer;
        padding: 5px;
        transition: all 0.3s ease;
    }
    
    .share-modal-close:hover {
        color: #06b6d4;
        transform: rotate(90deg);
    }
    
    .share-modal-preview {
        padding: 24px;
    }
    
    .share-preview-card {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }
    
    body.light-mode .share-preview-card {
        background: rgba(255, 255, 255, 0.8);
        border-color: rgba(6, 182, 212, 0.3);
    }
    
    .share-preview-image {
        width: 100%;
        height: 300px;
        object-fit: cover;
        background: rgba(6, 182, 212, 0.1);
    }
    
    .share-preview-content {
        padding: 20px;
    }
    
    .share-preview-title {
        font-size: 1.25rem;
        font-weight: bold;
        color: #06b6d4;
        margin-bottom: 10px;
    }
    
    .share-preview-description {
        color: #94a3b8;
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 15px;
        transition: color 0.3s ease;
    }
    
    body.light-mode .share-preview-description {
        color: rgba(30, 41, 59, 0.7);
    }
    
    .share-preview-url {
        color: #64748b;
        font-size: 0.85rem;
        word-break: break-all;
        transition: color 0.3s ease;
    }
    
    body.light-mode .share-preview-url {
        color: rgba(100, 116, 139, 0.8);
    }
    
    .share-modal-actions {
        padding: 24px;
        border-top: 1px solid rgba(6, 182, 212, 0.2);
        display: flex;
        gap: 12px;
        justify-content: flex-end;
    }
    
    .btn-share-facebook {
        background: linear-gradient(135deg, #1877f2, #0d5fdb);
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }
    
    .btn-share-facebook:hover {
        background: linear-gradient(135deg, #0d5fdb, #1877f2);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(24, 119, 242, 0.4);
    }
    
    .btn-cancel {
        background: rgba(100, 116, 139, 0.2);
        color: #94a3b8;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }
    
    body.light-mode .btn-cancel {
        background: rgba(148, 163, 184, 0.2);
        color: rgba(30, 41, 59, 0.8);
    }
    
    .btn-cancel:hover {
        background: rgba(100, 116, 139, 0.3);
        color: #cbd5e1;
    }
    
    body.light-mode .btn-cancel:hover {
        background: rgba(148, 163, 184, 0.3);
        color: rgba(30, 41, 59, 1);
    }
    
    /* Styles pour les éléments de la page */
    .articles-index-page h3 {
        color: #fff;
        transition: color 0.3s ease;
    }
    
    body.light-mode .articles-index-page h3 {
        color: #1e293b;
    }
    
    .articles-index-page .text-gray-400 {
        color: rgba(156, 163, 175, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .articles-index-page .text-gray-400 {
        color: rgba(100, 116, 139, 1);
    }
    
    .articles-index-page .text-gray-300 {
        color: rgba(209, 213, 219, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .articles-index-page .text-gray-300 {
        color: rgba(30, 41, 59, 0.8);
    }
    
    .articles-index-page .bg-gray-700 {
        background: rgba(55, 65, 81, 1);
        transition: background 0.3s ease;
    }
    
    body.light-mode .articles-index-page .bg-gray-700 {
        background: rgba(241, 245, 249, 1);
    }
    
    .articles-index-page table th {
        color: #06b6d4;
    }
    
    .articles-index-page table td {
        color: rgba(255, 255, 255, 0.9);
        transition: color 0.3s ease;
    }
    
    body.light-mode .articles-index-page table td {
        color: rgba(30, 41, 59, 0.9);
    }
    
    .articles-index-page .hover\:bg-cyan-500\/5:hover {
        background: rgba(6, 182, 212, 0.05);
    }
    
    body.light-mode .articles-index-page .hover\:bg-cyan-500\/5:hover {
        background: rgba(6, 182, 212, 0.1);
    }
    
    /* Alertes */
    .articles-index-page .bg-green-500\/20 {
        background: rgba(34, 197, 94, 0.2);
        border-color: rgba(34, 197, 94, 0.5);
    }
    
    body.light-mode .articles-index-page .bg-green-500\/20 {
        background: rgba(34, 197, 94, 0.15);
        border-color: rgba(34, 197, 94, 0.4);
    }
    
    .articles-index-page .bg-red-500\/20 {
        background: rgba(239, 68, 68, 0.2);
        border-color: rgba(239, 68, 68, 0.5);
    }
    
    body.light-mode .articles-index-page .bg-red-500\/20 {
        background: rgba(239, 68, 68, 0.15);
        border-color: rgba(239, 68, 68, 0.4);
    }
</style>
@endsection

@section('content')
<div class="articles-index-page">
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h3 class="text-3xl font-bold mb-2">Articles Emplois</h3>
        <p class="text-gray-400">Gérez les articles d'emplois</p>
    </div>
    <div class="flex gap-2">
        <form action="{{ route('admin.jobs.articles.recalculate-scores') }}" method="POST" class="inline" onsubmit="return confirm('Recalculer les scores SEO et lisibilité pour tous les articles ?');">
            @csrf
            <button type="submit" class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-black font-semibold rounded-lg transition">
                <i class="fas fa-sync-alt mr-2"></i>Recalculer les scores
            </button>
        </form>
        <a href="{{ route('admin.jobs.articles.create') }}" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-black font-semibold rounded-lg transition">
            <i class="fas fa-plus mr-2"></i>Nouvel article
        </a>
    </div>
</div>

@if(session('success'))
    <div class="mb-4 p-4 bg-green-500/20 border border-green-500/50 rounded-lg text-green-400 flex items-center gap-2">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 p-4 bg-red-500/20 border border-red-500/50 rounded-lg text-red-400 flex items-center gap-2">
        <i class="fas fa-exclamation-circle"></i>
        {{ session('error') }}
    </div>
@endif

<div class="content-section mb-6">
    <h4 class="text-xl font-bold mb-4 flex items-center gap-2">
        <i class="fas fa-filter text-cyan-400"></i>
        Filtres
    </h4>
    <form method="GET" action="{{ route('admin.jobs.articles.index') }}" class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div>
            <label class="block text-gray-300 mb-2 text-sm font-semibold">Recherche</label>
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Rechercher par titre..." 
                   class="input-admin">
        </div>
        
        <div>
            <label class="block text-gray-300 mb-2 text-sm font-semibold">Catégorie</label>
            <select name="category" class="input-admin">
                <option value="">Toutes les catégories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label class="block text-gray-300 mb-2 text-sm font-semibold">Type</label>
            <select name="sponsored" class="input-admin">
                <option value="">Tous les articles</option>
                <option value="1" {{ request('sponsored') == '1' ? 'selected' : '' }}>Articles sponsorisés</option>
                <option value="0" {{ request('sponsored') == '0' ? 'selected' : '' }}>Articles normaux</option>
            </select>
        </div>
        
        <div>
            <label class="block text-gray-300 mb-2 text-sm font-semibold">Score SEO min</label>
            <input type="number" name="seo_min" value="{{ request('seo_min') }}" 
                   placeholder="0" min="0" max="100"
                   class="input-admin">
        </div>
        
        <div>
            <label class="block text-gray-300 mb-2 text-sm font-semibold">Score Lisibilité min</label>
            <input type="number" name="readability_min" value="{{ request('readability_min') }}" 
                   placeholder="0" min="0" max="100"
                   class="input-admin">
        </div>
        
        <div>
            <label class="block text-gray-300 mb-2 text-sm font-semibold">Vues min</label>
            <input type="number" name="views_min" value="{{ request('views_min') }}" 
                   placeholder="0" min="0"
                   class="input-admin">
        </div>
        
        <div>
            <label class="block text-gray-300 mb-2 text-sm font-semibold">Trier par</label>
            <select name="sort_by" class="input-admin">
                <option value="updated_at" {{ request('sort_by', 'updated_at') == 'updated_at' ? 'selected' : '' }}>Date de modification</option>
                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date de création</option>
                <option value="published_at" {{ request('sort_by') == 'published_at' ? 'selected' : '' }}>Date de publication</option>
                <option value="title" {{ request('sort_by') == 'title' ? 'selected' : '' }}>Titre</option>
                <option value="views" {{ request('sort_by') == 'views' ? 'selected' : '' }}>Vues</option>
                <option value="seo_score" {{ request('sort_by') == 'seo_score' ? 'selected' : '' }}>Score SEO</option>
                <option value="readability_score" {{ request('sort_by') == 'readability_score' ? 'selected' : '' }}>Score Lisibilité</option>
            </select>
        </div>
        
        <div>
            <label class="block text-gray-300 mb-2 text-sm font-semibold">Ordre</label>
            <select name="sort_order" class="input-admin">
                <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Décroissant</option>
                <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Croissant</option>
            </select>
        </div>
        
        <div class="flex items-end gap-2">
            <button type="submit" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-black font-semibold rounded-lg transition flex-1">
                <i class="fas fa-search mr-2"></i>Filtrer
            </button>
            <a href="{{ route('admin.jobs.articles.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition">
                <i class="fas fa-redo mr-2"></i>Réinitialiser
            </a>
        </div>
    </form>
</div>

<div class="content-section">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-cyan-500/20">
                    <th class="text-left p-4 text-cyan-400">Image</th>
                    <th class="text-left p-4 text-cyan-400">Titre</th>
                    <th class="text-left p-4 text-cyan-400">Catégorie</th>
                    <th class="text-left p-4 text-cyan-400">SEO</th>
                    <th class="text-left p-4 text-cyan-400">Lisibilité</th>
                    <th class="text-left p-4 text-cyan-400">Vues</th>
                    <th class="text-right p-4 text-cyan-400">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $article)
                <tr class="border-b border-cyan-500/10 hover:bg-cyan-500/5 transition">
                    <td class="p-4">
                        @if($article->cover_image)
                            <div class="article-thumbnail {{ $article->is_sponsored ? 'sponsored' : '' }}">
                                <img src="{{ $article->cover_type === 'internal' ? \Illuminate\Support\Facades\Storage::url($article->cover_image) : $article->cover_image }}" 
                                     alt="{{ $article->title }}"
                                     class="article-thumbnail-img"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                @if($article->is_sponsored)
                                    <div class="sponsored-overlay">
                                        <i class="fas fa-star sponsored-star"></i>
                                        <i class="fas fa-star sponsored-star"></i>
                                        <i class="fas fa-star sponsored-star"></i>
                                    </div>
                                @endif
                                <div class="article-thumbnail-placeholder {{ $article->is_sponsored ? 'sponsored' : '' }}" style="display: none;">
                                    <i class="fas fa-image"></i>
                                </div>
                            </div>
                        @else
                            <div class="article-thumbnail-placeholder {{ $article->is_sponsored ? 'sponsored' : '' }}">
                                <i class="fas fa-image"></i>
                                @if($article->is_sponsored)
                                    <div class="sponsored-overlay">
                                        <i class="fas fa-star sponsored-star"></i>
                                        <i class="fas fa-star sponsored-star"></i>
                                        <i class="fas fa-star sponsored-star"></i>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </td>
                    <td class="p-4">
                        <div class="font-semibold">{{ $article->title }}</div>
                    </td>
                    <td class="p-4">
                        <span class="px-2 py-1 bg-cyan-500/20 text-cyan-400 rounded text-sm">
                            {{ $article->category->name }}
                        </span>
                    </td>
                    <td class="p-4">
                        <div class="flex items-center gap-2">
                            @php
                                $seoScore = $article->seo_score ?? 0;
                            @endphp
                            <div class="w-16 h-2 bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-red-500 via-yellow-500 to-green-500 rounded-full" style="width: {{ $seoScore }}%"></div>
                            </div>
                            <span class="text-sm text-gray-400">{{ $seoScore }}/100</span>
                        </div>
                    </td>
                    <td class="p-4">
                        <div class="flex items-center gap-2">
                            @php
                                $readabilityScore = $article->readability_score ?? 0;
                            @endphp
                            <div class="w-16 h-2 bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-red-500 via-yellow-500 to-green-500 rounded-full" style="width: {{ $readabilityScore }}%"></div>
                            </div>
                            <span class="text-sm text-gray-400">{{ $readabilityScore }}/100</span>
                        </div>
                    </td>
                    <td class="p-4 text-gray-400">{{ $article->views }}</td>
                    <td class="p-4">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.jobs.articles.show', $article->id) }}" class="px-3 py-1 bg-blue-500/20 hover:bg-blue-500/30 text-blue-400 rounded transition" title="Voir">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.jobs.articles.edit', $article->id) }}" class="px-3 py-1 bg-cyan-500/20 hover:bg-cyan-500/30 text-cyan-400 rounded transition" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            @php
                                $articleImage = $article->cover_image 
                                    ? ($article->cover_type === 'internal' 
                                        ? asset(\Illuminate\Support\Facades\Storage::url($article->cover_image)) 
                                        : $article->cover_image)
                                    : asset('images/logo.png');
                                $articleUrl = str_contains(config('app.url'), 'niangprogrammeur.com') 
                                    ? 'https://www.niangprogrammeur.com/emplois/article/' . $article->slug
                                    : route('emplois.article', $article->slug);
                            @endphp
                            @if($article->status === 'published')
                            <form action="{{ route('admin.jobs.articles.send-newsletter', $article->id) }}" method="POST" class="inline" onsubmit="return confirm('Envoyer cet article à tous les abonnés de la newsletter ?');">
                                @csrf
                                <button type="submit" class="px-3 py-1 bg-green-600/20 hover:bg-green-600/30 text-green-400 rounded transition" title="Envoyer par newsletter">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </form>
                            @endif
                            <button type="button" 
                                    onclick="openShareModal({{ $article->id }}, '{{ addslashes($article->title) }}', '{{ $articleImage }}', '{{ addslashes($article->meta_description ?? $article->excerpt ?? '') }}', '{{ $articleUrl }}')" 
                                    class="px-3 py-1 bg-blue-600/20 hover:bg-blue-600/30 text-blue-300 rounded transition" 
                                    title="Partager sur Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </button>
                            @auth
                            @if(Auth::user()->isAdmin())
                            <form action="{{ route('admin.jobs.articles.destroy', $article->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded transition" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                            @endauth
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-8 text-center text-gray-400">
                        <i class="fas fa-newspaper text-4xl mb-4 block"></i>
                        Aucun article trouvé
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($articles->hasPages())
    <div class="mt-6 flex justify-center">
        {{ $articles->links() }}
    </div>
    @endif
</div>

<!-- Modal de partage Facebook -->
<div id="shareModal" class="share-modal">
    <div class="share-modal-content">
        <div class="share-modal-header">
            <h3>
                <i class="fab fa-facebook-f"></i>
                Partager sur Facebook
            </h3>
            <button type="button" class="share-modal-close" onclick="closeShareModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="share-modal-preview">
            <div class="share-preview-card">
                <img id="sharePreviewImage" src="" alt="Preview" class="share-preview-image">
                <div class="share-preview-content">
                    <div id="sharePreviewTitle" class="share-preview-title"></div>
                    <div id="sharePreviewDescription" class="share-preview-description"></div>
                    <div id="sharePreviewUrl" class="share-preview-url"></div>
                </div>
            </div>
        </div>
        <div class="share-modal-actions">
            <button type="button" class="btn-cancel" onclick="closeShareModal()">
                Annuler
            </button>
            <button type="button" class="btn-share-facebook" onclick="shareOnFacebook()">
                <i class="fab fa-facebook-f"></i>
                Partager sur Facebook
            </button>
        </div>
    </div>
</div>

@php
    $facebookAppId = \App\Models\SiteSetting::get('facebook_app_id', '');
    $baseUrl = str_contains(config('app.url'), 'niangprogrammeur.com') 
        ? 'https://www.niangprogrammeur.com' 
        : config('app.url');
@endphp

<!-- Facebook SDK -->
@if($facebookAppId)
<div id="fb-root"></div>
<script>
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v18.0&appId={{ $facebookAppId }}&autoLogAppEvents=1";
        js.async = true;
        js.defer = true;
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
@endif

<script>
    // Variables globales pour le partage
    let shareData = {
        url: '',
        title: '',
        image: '',
        description: ''
    };
    
    // Ouvrir la modal de partage
    function openShareModal(articleId, title, image, description, url) {
        shareData = {
            url: url,
            title: title,
            image: image,
            description: description || 'Découvrez cet article sur NiangProgrammeur'
        };
        
        // Mettre à jour la prévisualisation
        document.getElementById('sharePreviewImage').src = shareData.image;
        document.getElementById('sharePreviewTitle').textContent = shareData.title;
        document.getElementById('sharePreviewDescription').textContent = shareData.description;
        document.getElementById('sharePreviewUrl').textContent = shareData.url;
        
        // Afficher la modal
        document.getElementById('shareModal').classList.add('active');
    }
    
    // Fermer la modal
    function closeShareModal() {
        document.getElementById('shareModal').classList.remove('active');
    }
    
    // Fermer la modal en cliquant en dehors
    document.addEventListener('DOMContentLoaded', function() {
        const shareModal = document.getElementById('shareModal');
        if (shareModal) {
            shareModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeShareModal();
                }
            });
        }
    });
    
    // Partager sur Facebook
    function shareOnFacebook() {
        const facebookAppId = '{{ $facebookAppId }}';
        const shareUrl = encodeURIComponent(shareData.url);
        
        if (facebookAppId && typeof FB !== 'undefined') {
            // Utiliser l'API Facebook Share Dialog
            FB.ui({
                method: 'share',
                href: shareData.url,
                quote: shareData.title + ' - ' + shareData.description,
            }, function(response) {
                if (response && !response.error_message) {
                    // Partage réussi
                    alert('Article partagé avec succès sur Facebook !');
                    closeShareModal();
                } else {
                    // Erreur ou annulation - utiliser le fallback
                    const facebookShareUrl = 'https://www.facebook.com/sharer/sharer.php?u=' + shareUrl + '&quote=' + encodeURIComponent(shareData.title + ' - ' + shareData.description);
                    window.open(facebookShareUrl, 'Partager sur Facebook', 'width=600,height=400,scrollbars=yes,resizable=yes');
                    closeShareModal();
                }
            });
        } else {
            // Fallback : ouvrir l'URL de partage Facebook
            const facebookShareUrl = 'https://www.facebook.com/sharer/sharer.php?u=' + shareUrl + '&quote=' + encodeURIComponent(shareData.title + ' - ' + shareData.description);
            window.open(facebookShareUrl, 'Partager sur Facebook', 'width=600,height=400,scrollbars=yes,resizable=yes');
            closeShareModal();
        }
    }
</script>
</div>
@endsection

