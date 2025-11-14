@extends('admin.layout')

@section('title', isset($article) ? 'Modifier Article' : 'Nouvel Article')

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h3 class="text-3xl font-bold mb-2">{{ isset($article) ? 'Modifier Article' : 'Nouvel Article' }}</h3>
        <p class="text-gray-400">{{ isset($article) ? 'Modifiez l\'article d\'emploi' : 'Créez un nouvel article d\'emploi' }}</p>
    </div>
    <a href="{{ route('admin.jobs.articles.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition">
        <i class="fas fa-arrow-left mr-2"></i>Retour
    </a>
</div>

<form action="{{ isset($article) ? route('admin.jobs.articles.update', $article->id) : route('admin.jobs.articles.store') }}" method="POST" enctype="multipart/form-data" id="articleForm">
    @csrf
    @if(isset($article))
        @method('PUT')
    @endif
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne principale (contenu) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Titre -->
            <div class="content-section">
                <label class="block text-cyan-400 mb-2 font-semibold">Titre de l'article *</label>
                <input type="text" name="title" id="articleTitle" value="{{ old('title', $article->title ?? '') }}" required
                       class="input-admin text-2xl font-bold" placeholder="Entrez le titre de l'article">
                @error('title')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Éditeur de contenu (WordPress Classic Editor style) -->
            <div class="content-section">
                <div class="flex items-center justify-between mb-4 border-b border-cyan-500/20 pb-3">
                    <div class="flex gap-2">
                        <button type="button" onclick="formatText('bold')" class="px-3 py-1 bg-cyan-500/20 hover:bg-cyan-500/30 text-cyan-400 rounded transition" title="Gras">
                            <i class="fas fa-bold"></i>
                        </button>
                        <button type="button" onclick="formatText('italic')" class="px-3 py-1 bg-cyan-500/20 hover:bg-cyan-500/30 text-cyan-400 rounded transition" title="Italique">
                            <i class="fas fa-italic"></i>
                        </button>
                        <button type="button" onclick="formatText('underline')" class="px-3 py-1 bg-cyan-500/20 hover:bg-cyan-500/30 text-cyan-400 rounded transition" title="Souligné">
                            <i class="fas fa-underline"></i>
                        </button>
                        <button type="button" onclick="insertLink()" class="px-3 py-1 bg-cyan-500/20 hover:bg-cyan-500/30 text-cyan-400 rounded transition" title="Lien">
                            <i class="fas fa-link"></i>
                        </button>
                        <button type="button" onclick="insertList('ul')" class="px-3 py-1 bg-cyan-500/20 hover:bg-cyan-500/30 text-cyan-400 rounded transition" title="Liste">
                            <i class="fas fa-list-ul"></i>
                        </button>
                        <button type="button" onclick="insertList('ol')" class="px-3 py-1 bg-cyan-500/20 hover:bg-cyan-500/30 text-cyan-400 rounded transition" title="Liste numérotée">
                            <i class="fas fa-list-ol"></i>
                        </button>
                    </div>
                    <div class="text-sm text-gray-400">
                        <span id="wordCount">0</span> <span id="wordLabel">mots</span>
                    </div>
                </div>
                <textarea name="content" id="articleContent" rows="20" required
                          class="input-admin font-mono text-base leading-relaxed" 
                          placeholder="Commencez à écrire votre article...">{{ old('content', $article->content ?? '') }}</textarea>
                @error('content')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Extrait -->
            <div class="content-section">
                <label class="block text-cyan-400 mb-2 font-semibold">Extrait</label>
                <textarea name="excerpt" id="articleExcerpt" rows="4" 
                          class="input-admin" 
                          placeholder="Résumé court de l'article (optionnel)">{{ old('excerpt', $article->excerpt ?? '') }}</textarea>
                <p class="text-gray-400 text-sm mt-1">L'extrait est utilisé dans les aperçus et les résultats de recherche.</p>
            </div>

            <!-- Analyse SEO et Lisibilité détaillée -->
            <div class="content-section">
                <h4 class="text-cyan-400 font-bold mb-4 border-b border-cyan-500/20 pb-2">📊 Analyse SEO & Lisibilité</h4>
                <div class="space-y-4">
                    <!-- Score SEO -->
                    <div class="bg-gray-800/50 rounded-lg p-4 border border-cyan-500/20">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-cyan-400 font-semibold text-lg">Score SEO</span>
                            <span id="seoScoreDetail" class="text-3xl font-bold text-cyan-400">0/100</span>
                        </div>
                        <div class="w-full h-4 bg-gray-700 rounded-full overflow-hidden mb-4">
                            <div id="seoBarDetail" class="h-full bg-gradient-to-r from-red-500 via-yellow-500 to-green-500 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                        <div id="seoDetails" class="space-y-2 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Titre (30-60 caractères)</span>
                                <span id="titleCheck" class="text-red-400"><i class="fas fa-times"></i></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Meta Title (30-60 caractères)</span>
                                <span id="metaTitleCheck" class="text-red-400"><i class="fas fa-times"></i></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Meta Description (120-160 caractères)</span>
                                <span id="metaDescCheck" class="text-red-400"><i class="fas fa-times"></i></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Contenu (≥300 mots)</span>
                                <span id="contentCheck" class="text-red-400"><i class="fas fa-times"></i></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Extrait (≥100 caractères)</span>
                                <span id="excerptCheck" class="text-red-400"><i class="fas fa-times"></i></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Image de couverture</span>
                                <span id="imageCheck" class="text-red-400"><i class="fas fa-times"></i></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Mots-clés (3-10)</span>
                                <span id="keywordsCheck" class="text-red-400"><i class="fas fa-times"></i></span>
                            </div>
                        </div>
                    </div>

                    <!-- Score Lisibilité -->
                    <div class="bg-gray-800/50 rounded-lg p-4 border border-cyan-500/20">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-cyan-400 font-semibold text-lg">Score Lisibilité</span>
                            <span id="readabilityScoreDetail" class="text-3xl font-bold text-cyan-400">0/100</span>
                        </div>
                        <div class="w-full h-4 bg-gray-700 rounded-full overflow-hidden mb-4">
                            <div id="readabilityBarDetail" class="h-full bg-gradient-to-r from-red-500 via-yellow-500 to-green-500 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                        <div id="readabilityDetails" class="space-y-2 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Mots par phrase (10-15 idéal)</span>
                                <span id="wordsPerSentence" class="text-gray-300">-</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Phrases par paragraphe (≤5 idéal)</span>
                                <span id="sentencesPerParagraph" class="text-gray-300">-</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Nombre de paragraphes</span>
                                <span id="paragraphCount" class="text-gray-300">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar (métadonnées) -->
        <div class="space-y-6">
            <!-- Publier -->
            <div class="content-section">
                <h4 class="text-cyan-400 font-bold mb-4 border-b border-cyan-500/20 pb-2">Publier</h4>
                <div class="space-y-4">
                    <div>
                        <label class="block text-cyan-400 mb-2 font-semibold">Statut</label>
                        <select name="status" id="articleStatus" class="input-admin">
                            <option value="draft" {{ old('status', $article->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Brouillon</option>
                            <option value="published" {{ old('status', $article->status ?? '') === 'published' ? 'selected' : '' }}>Publié</option>
                            <option value="archived" {{ old('status', $article->status ?? '') === 'archived' ? 'selected' : '' }}>Archivé</option>
                        </select>
                    </div>
                    <div class="flex gap-2 pt-4">
                        <button type="submit" class="btn-primary flex-1">
                            <i class="fas fa-save mr-2"></i>Enregistrer
                        </button>
                    </div>
                </div>
            </div>

            <!-- Catégorie -->
            <div class="content-section">
                <h4 class="text-cyan-400 font-bold mb-4 border-b border-cyan-500/20 pb-2">Catégorie</h4>
                <select name="category_id" required class="input-admin">
                    <option value="">Sélectionnez une catégorie</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $article->category_id ?? '') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Image de couverture -->
            <div class="content-section">
                <h4 class="text-cyan-400 font-bold mb-4 border-b border-cyan-500/20 pb-2">Image de couverture</h4>
                <div class="space-y-4">
                    <div>
                        <label class="block text-cyan-400 mb-2 font-semibold">Type d'image</label>
                        <select name="cover_type" id="coverType" class="input-admin">
                            <option value="internal" {{ old('cover_type', $article->cover_type ?? 'internal') === 'internal' ? 'selected' : '' }}>Interne (upload)</option>
                            <option value="external" {{ old('cover_type', $article->cover_type ?? '') === 'external' ? 'selected' : '' }}>Externe (URL)</option>
                        </select>
                    </div>
                    <div id="internalImage" style="display: {{ old('cover_type', $article->cover_type ?? 'internal') === 'internal' ? 'block' : 'none' }};">
                        <label class="block text-cyan-400 mb-2 font-semibold">Fichier image</label>
                        <input type="file" name="cover_image_file" id="coverImageFile" accept="image/*" class="input-admin">
                        @if(isset($article) && $article->cover_type === 'internal' && $article->cover_image)
                            <p class="text-gray-400 text-sm mt-1">Image actuelle: <a href="{{ \Illuminate\Support\Facades\Storage::url($article->cover_image) }}" target="_blank" class="text-cyan-400 hover:underline">{{ basename($article->cover_image) }}</a></p>
                        @else
                            <p class="text-gray-400 text-sm mt-1">Formats acceptés: JPG, PNG, GIF</p>
                        @endif
                    </div>
                    <div id="externalImage" style="display: {{ old('cover_type', $article->cover_type ?? '') === 'external' ? 'block' : 'none' }};">
                        <label class="block text-cyan-400 mb-2 font-semibold">URL de l'image</label>
                        <input type="url" name="cover_image_url" id="coverImageUrl" value="{{ old('cover_image', $article->cover_image ?? '') }}"
                               class="input-admin" placeholder="https://example.com/image.jpg">
                        <p class="text-gray-400 text-sm mt-1">Entrez l'URL complète de l'image</p>
                    </div>
                    <div id="coverPreview" class="mt-4 {{ (isset($article) && $article->cover_image) ? '' : 'hidden' }}">
                        <img id="previewImg" src="{{ isset($article) && $article->cover_image ? ($article->cover_type === 'internal' ? \Illuminate\Support\Facades\Storage::url($article->cover_image) : $article->cover_image) : '' }}" alt="Aperçu" class="w-full rounded-lg border border-cyan-500/20">
                    </div>
                </div>
            </div>

            <!-- SEO et Lisibilité -->
            <div class="content-section">
                <h4 class="text-cyan-400 font-bold mb-4 border-b border-cyan-500/20 pb-2">SEO & Lisibilité</h4>
                <div class="space-y-4">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-cyan-400 font-semibold">Score SEO</span>
                            <span id="seoScore" class="text-2xl font-bold text-cyan-400">0</span>
                        </div>
                        <div class="w-full h-3 bg-gray-700 rounded-full overflow-hidden">
                            <div id="seoBar" class="h-full bg-gradient-to-r from-red-500 via-yellow-500 to-green-500 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-cyan-400 font-semibold">Lisibilité</span>
                            <span id="readabilityScore" class="text-2xl font-bold text-cyan-400">0</span>
                        </div>
                        <div class="w-full h-3 bg-gray-700 rounded-full overflow-hidden">
                            <div id="readabilityBar" class="h-full bg-gradient-to-r from-red-500 via-yellow-500 to-green-500 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Métadonnées SEO -->
            <div class="content-section">
                <h4 class="text-cyan-400 font-bold mb-4 border-b border-cyan-500/20 pb-2">Métadonnées SEO</h4>
                <div class="space-y-4">
                    <div>
                        <label class="block text-cyan-400 mb-2 font-semibold">Meta Title</label>
                        <input type="text" name="meta_title" id="metaTitle" value="{{ old('meta_title', $article->meta_title ?? '') }}"
                               class="input-admin" maxlength="60" placeholder="Titre SEO (max 60 caractères)">
                        <p class="text-gray-400 text-sm mt-1"><span id="metaTitleLength">0</span>/60 caractères</p>
                    </div>
                    <div>
                        <label class="block text-cyan-400 mb-2 font-semibold">Meta Description</label>
                        <textarea name="meta_description" id="metaDescription" rows="3"
                                  class="input-admin" maxlength="160" placeholder="Description SEO (max 160 caractères)">{{ old('meta_description', $article->meta_description ?? '') }}</textarea>
                        <p class="text-gray-400 text-sm mt-1"><span id="metaDescLength">0</span>/160 caractères</p>
                    </div>
                    <div>
                        <label class="block text-cyan-400 mb-2 font-semibold">Mots-clés</label>
                        <input type="text" name="meta_keywords" value="{{ old('meta_keywords', isset($article) && $article->meta_keywords ? implode(', ', $article->meta_keywords) : '') }}"
                               class="input-admin" placeholder="mot-clé1, mot-clé2, mot-clé3">
                        <p class="text-gray-400 text-sm mt-1">Séparez les mots-clés par des virgules</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@section('scripts')
<script src="{{ asset('js/article-editor.js') }}"></script>
<script>
    // S'assurer que le script s'exécute après le chargement du DOM
    document.addEventListener('DOMContentLoaded', function() {
        // Vérifier que le script article-editor.js a bien initialisé
        setTimeout(function() {
            const wordCountSpan = document.getElementById('wordCount');
            const contentTextarea = document.getElementById('articleContent');
            
            if (wordCountSpan && contentTextarea) {
                // Forcer une mise à jour initiale
                contentTextarea.dispatchEvent(new Event('input'));
            }
            
            // Vérifier que l'analyse SEO se met à jour
            const titleInput = document.getElementById('articleTitle');
            if (titleInput) {
                titleInput.dispatchEvent(new Event('input'));
            }
        }, 500);
    });
</script>
@endsection

