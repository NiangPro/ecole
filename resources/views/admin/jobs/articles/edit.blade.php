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
                    <div>
                        <label class="block text-cyan-400 mb-2 font-semibold">Date de publication</label>
                        <input type="datetime-local" name="published_at" value="{{ old('published_at', isset($article) && $article->published_at ? $article->published_at->format('Y-m-d\TH:i') : '') }}"
                               class="input-admin">
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
                    <div id="coverPreview" class="mt-4 {{ (isset($article) && $article->cover_image) ? '' : 'hidden' }}" style="text-align: center;">
                        <img id="previewImg" src="{{ isset($article) && $article->cover_image ? ($article->cover_type === 'internal' ? \Illuminate\Support\Facades\Storage::url($article->cover_image) : $article->cover_image) : '' }}" alt="Aperçu" style="max-width: 100%; border-radius: 12px; border: 2px solid rgba(6, 182, 212, 0.3);">
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

@push('scripts')
<script src="{{ asset('js/article-editor.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser l'aperçu de l'image existante
        const coverPreview = document.getElementById('coverPreview');
        const previewImg = document.getElementById('previewImg');
        const coverTypeSelect = document.getElementById('coverType');
        const coverImageFile = document.getElementById('coverImageFile');
        const coverImageUrl = document.getElementById('coverImageUrl');
        
        // Afficher l'aperçu si une image existe déjà
        @if(isset($article) && $article->cover_image)
            if (coverPreview && previewImg) {
                coverPreview.classList.remove('hidden');
            }
        @endif
        
        // Gérer le changement de type d'image
        if (coverTypeSelect) {
            coverTypeSelect.addEventListener('change', function() {
                const internalDiv = document.getElementById('internalImage');
                const externalDiv = document.getElementById('externalImage');
                
                if (this.value === 'internal') {
                    if (internalDiv) internalDiv.style.display = 'block';
                    if (externalDiv) externalDiv.style.display = 'none';
                    if (coverImageUrl) coverImageUrl.value = '';
                } else {
                    if (internalDiv) internalDiv.style.display = 'none';
                    if (externalDiv) externalDiv.style.display = 'block';
                    if (coverImageFile) coverImageFile.value = '';
                }
                
                // Cacher l'aperçu si on change de type
                if (coverPreview) coverPreview.classList.add('hidden');
                if (previewImg) previewImg.src = '';
            });
        }
        
        // Aperçu pour fichier uploadé
        if (coverImageFile) {
            coverImageFile.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        if (previewImg) {
                            previewImg.src = e.target.result;
                            if (coverPreview) coverPreview.classList.remove('hidden');
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
        
        // Aperçu pour URL externe
        if (coverImageUrl) {
            coverImageUrl.addEventListener('input', function() {
                const url = this.value.trim();
                if (url && url.startsWith('http')) {
                    if (previewImg) {
                        previewImg.src = url;
                        previewImg.onload = function() {
                            if (coverPreview) coverPreview.classList.remove('hidden');
                        };
                        previewImg.onerror = function() {
                            if (coverPreview) coverPreview.classList.add('hidden');
                        };
                    }
                } else {
                    if (coverPreview) coverPreview.classList.add('hidden');
                }
            });
        }
        
        // Initialiser les scores SEO et lisibilité après un court délai
        setTimeout(function() {
            const contentTextarea = document.getElementById('articleContent');
            if (contentTextarea) {
                contentTextarea.dispatchEvent(new Event('input'));
            }
            const titleInput = document.getElementById('articleTitle');
            if (titleInput) {
                titleInput.dispatchEvent(new Event('input'));
            }
        }, 500);
    });
</script>
@endpush
@endsection

