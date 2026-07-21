@extends('admin.layout')

@section('title', 'Modifier Document')

@section('styles')
<style>
    .cover-wrapper-edit {
        border-radius: 16px;
        overflow: hidden;
        border: 2px solid rgba(6, 182, 212, 0.3);
        box-shadow: 0 10px 30px rgba(6, 182, 212, 0.2);
        transition: all 0.3s ease;
        max-width: 100%;
    }
    .cover-wrapper-edit:hover {
        box-shadow: 0 15px 40px rgba(6, 182, 212, 0.4);
    }
    .cover-wrapper-edit img {
        width: 100%;
        height: auto;
        display: block;
    }
</style>
@endsection

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h3 class="text-3xl font-bold mb-2">Modifier Document</h3>
        <p class="text-gray-400">Modifiez les informations du document</p>
    </div>
    <a href="{{ route('admin.documents.documents.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition">
        <i class="fas fa-arrow-left mr-2"></i>Retour
    </a>
</div>

@if($errors->any())
    <div class="mb-4 p-4 bg-red-500/20 border border-red-500/50 rounded-lg text-red-400">
        <strong>Erreurs de validation :</strong>
        <ul class="mt-2 list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="content-section">
    <form action="{{ route('admin.documents.documents.update', $document->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="space-y-6">
                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Titre *</label>
                    <input type="text" name="title" value="{{ old('title', $document->title) }}" required
                           class="input-admin">
                    @error('title')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $document->slug) }}"
                           class="input-admin">
                    @error('slug')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Catégorie *</label>
                    <select name="category_id" required class="input-admin">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $document->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Résumé (excerpt)</label>
                    <textarea name="excerpt" rows="3" class="input-admin">{{ old('excerpt', $document->excerpt) }}</textarea>
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Description</label>
                    <textarea name="description" rows="5" class="input-admin">{{ old('description', $document->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Tags (séparés par des virgules)</label>
                    <input type="text" name="tags" value="{{ old('tags', is_array($document->tags) ? implode(', ', $document->tags) : '') }}"
                           class="input-admin">
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Fichier du document</label>
                    @if($document->file_name)
                        <div class="mb-2 p-2 bg-gray-700/50 rounded text-sm text-gray-300">
                            <i class="fas fa-file mr-2"></i>{{ $document->file_name }} ({{ number_format($document->file_size / 1024, 2) }} KB)
                        </div>
                    @endif
                    <input type="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar"
                           class="input-admin">
                    <p class="text-gray-400 text-sm mt-1">Laisser vide pour conserver le fichier actuel</p>
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Image de couverture</label>
                    @if($document->cover_image)
                        <div class="cover-wrapper-edit mb-4">
                            <img loading="lazy" src="{{ $document->cover_type === 'internal' ? \Illuminate\Support\Facades\URL::temporarySignedRoute('document.cover.signed', now()->addHours(24), ['id' => $document->id]) : $document->cover_image }}" 
                                 alt="{{ $document->title }}">
                        </div>
                    @endif
                    <select name="cover_type" id="coverType" class="input-admin mb-3">
                        <option value="internal" {{ old('cover_type', $document->cover_type) === 'internal' ? 'selected' : '' }}>Interne (upload)</option>
                        <option value="external" {{ old('cover_type', $document->cover_type) === 'external' ? 'selected' : '' }}>Externe (URL)</option>
                    </select>
                    <div id="coverInternal" style="display: {{ old('cover_type', $document->cover_type) === 'internal' ? 'block' : 'none' }};">
                        <input type="file" name="cover_image_file" id="coverImageFile" accept="image/*" class="input-admin">
                        <div id="coverUploadPreview" class="mt-3 hidden">
                            <p class="text-cyan-400 text-sm mb-2">Aperçu :</p>
                            <img loading="lazy" id="coverPreviewImg" src="" alt="Aperçu" class="w-40 h-40 object-cover rounded-lg border border-cyan-500/20">
                        </div>
                    </div>
                    <div id="coverExternal" style="display: {{ old('cover_type', $document->cover_type) === 'external' ? 'block' : 'none' }};">
                        <input type="url" name="cover_image_url" value="{{ old('cover_image_url', $document->cover_type === 'external' ? $document->cover_image : '') }}"
                               class="input-admin" placeholder="https://example.com/image.jpg">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-cyan-400 mb-2 font-semibold">Prix (FCFA) *</label>
                        <input type="number" name="price" value="{{ old('price', $document->price) }}" required min="0" step="0.01"
                               class="input-admin">
                    </div>
                    <div>
                        <label class="block text-cyan-400 mb-2 font-semibold">Prix réduit (FCFA)</label>
                        <input type="number" name="discount_price" value="{{ old('discount_price', $document->discount_price) }}" min="0" step="0.01"
                               class="input-admin">
                    </div>
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Statut *</label>
                    <select name="status" required class="input-admin">
                        <option value="draft" {{ old('status', $document->status) === 'draft' ? 'selected' : '' }}>Brouillon</option>
                        <option value="published" {{ old('status', $document->status) === 'published' ? 'selected' : '' }}>Publié</option>
                        <option value="archived" {{ old('status', $document->status) === 'archived' ? 'selected' : '' }}>Archivé</option>
                    </select>
                </div>

                <div class="space-y-3">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $document->is_featured) ? 'checked' : '' }}
                               class="w-5 h-5 rounded bg-cyan-500/20 border-cyan-500/50 text-cyan-500">
                        <span class="text-cyan-400 font-semibold">Document en vedette</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $document->is_active) ? 'checked' : '' }}
                               class="w-5 h-5 rounded bg-cyan-500/20 border-cyan-500/50 text-cyan-500">
                        <span class="text-cyan-400 font-semibold">Document actif</span>
                    </label>
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Meta Title (SEO)</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $document->meta_title) }}"
                           class="input-admin">
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Meta Description (SEO)</label>
                    <textarea name="meta_description" rows="2" class="input-admin">{{ old('meta_description', $document->meta_description) }}</textarea>
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Meta Keywords (SEO)</label>
                    <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $document->meta_keywords) }}"
                           class="input-admin">
                </div>
            </div>
        </div>

        <div class="flex gap-4 mt-8">
            <button type="submit" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-black font-semibold rounded-lg transition">
                <i class="fas fa-save mr-2"></i>Enregistrer
            </button>
            <a href="{{ route('admin.documents.documents.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition">
                Annuler
            </a>
        </div>
    </form>
</div>

<script>
document.getElementById('coverType').addEventListener('change', function() {
    const internal = document.getElementById('coverInternal');
    const external = document.getElementById('coverExternal');
    const preview = document.getElementById('coverUploadPreview');
    if (this.value === 'internal') {
        internal.style.display = 'block';
        external.style.display = 'none';
    } else {
        internal.style.display = 'none';
        external.style.display = 'block';
        preview.classList.add('hidden');
    }
});

document.getElementById('coverImageFile').addEventListener('change', function(e) {
    const preview = document.getElementById('coverUploadPreview');
    const previewImg = document.getElementById('coverPreviewImg');
    const file = e.target.files[0];
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(ev) {
            previewImg.src = ev.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('hidden');
        previewImg.src = '';
    }
});
</script>
@endsection


