@extends('admin.layout')

@section('title', 'Nouveau Document')

@section('styles')
<style>
    /* Dark Mode Styles */
    body.light-mode h3 {
        color: #1e293b;
    }
    
    body.light-mode p {
        color: #64748b;
    }
    
    body.light-mode .content-section {
        background: #ffffff;
        border-color: rgba(6, 182, 212, 0.2);
    }
    
    body.light-mode .input-admin {
        background: #f8f9fa;
        border-color: rgba(6, 182, 212, 0.3);
        color: #1e293b;
    }
    
    body.light-mode .input-admin:focus {
        background: #ffffff;
        border-color: #06b6d4;
    }
    
    body.light-mode label {
        color: #06b6d4;
    }
    
    body.light-mode .text-red-400 {
        color: #dc2626;
    }
    
    body.light-mode .text-gray-400 {
        color: #64748b;
    }
</style>
@endsection

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h3 class="text-3xl font-bold mb-2">Nouveau Document</h3>
        <p class="text-gray-400">Ajoutez un nouveau document à vendre</p>
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
    <form action="{{ route('admin.documents.documents.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="space-y-6">
                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Titre *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           class="input-admin" placeholder="Titre du document">
                    @error('title')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug') }}"
                           class="input-admin" placeholder="Sera généré automatiquement">
                    @error('slug')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Catégorie *</label>
                    <select name="category_id" required class="input-admin">
                        <option value="">Sélectionnez une catégorie</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Résumé (excerpt)</label>
                    <textarea name="excerpt" rows="3" class="input-admin" placeholder="Résumé court du document">{{ old('excerpt') }}</textarea>
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Description</label>
                    <textarea name="description" rows="5" class="input-admin" placeholder="Description complète du document">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Tags (séparés par des virgules)</label>
                    <input type="text" name="tags" value="{{ old('tags') }}"
                           class="input-admin" placeholder="tag1, tag2, tag3">
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Fichier du document *</label>
                    <input type="file" name="file" required accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar"
                           class="input-admin">
                    <p class="text-gray-400 text-sm mt-1">Formats acceptés: PDF, Word, Excel, PowerPoint, TXT, ZIP, RAR (max 100MB)</p>
                    @error('file')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Image de couverture</label>
                    <select name="cover_type" id="coverType" class="input-admin mb-3">
                        <option value="internal" {{ old('cover_type', 'internal') === 'internal' ? 'selected' : '' }}>Interne (upload)</option>
                        <option value="external" {{ old('cover_type') === 'external' ? 'selected' : '' }}>Externe (URL)</option>
                    </select>
                    <div id="coverInternal" style="display: {{ old('cover_type', 'internal') === 'internal' ? 'block' : 'none' }};">
                        <input type="file" name="cover_image_file" accept="image/*" class="input-admin">
                    </div>
                    <div id="coverExternal" style="display: {{ old('cover_type') === 'external' ? 'block' : 'none' }};">
                        <input type="url" name="cover_image_url" value="{{ old('cover_image_url') }}"
                               class="input-admin" placeholder="https://example.com/image.jpg">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-cyan-400 mb-2 font-semibold">Prix (FCFA) *</label>
                        <input type="number" name="price" value="{{ old('price') }}" required min="0" step="0.01"
                               class="input-admin" placeholder="0">
                        @error('price')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-cyan-400 mb-2 font-semibold">Prix réduit (FCFA)</label>
                        <input type="number" name="discount_price" value="{{ old('discount_price') }}" min="0" step="0.01"
                               class="input-admin" placeholder="Optionnel">
                    </div>
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Statut *</label>
                    <select name="status" required class="input-admin">
                        <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Brouillon</option>
                        <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Publié</option>
                        <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>Archivé</option>
                    </select>
                </div>

                <div class="space-y-3">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                               class="w-5 h-5 rounded bg-cyan-500/20 border-cyan-500/50 text-cyan-500">
                        <span class="text-cyan-400 font-semibold">Document en vedette</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               class="w-5 h-5 rounded bg-cyan-500/20 border-cyan-500/50 text-cyan-500">
                        <span class="text-cyan-400 font-semibold">Document actif</span>
                    </label>
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Meta Title (SEO)</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title') }}"
                           class="input-admin" placeholder="Titre SEO">
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Meta Description (SEO)</label>
                    <textarea name="meta_description" rows="2" class="input-admin" placeholder="Description SEO">{{ old('meta_description') }}</textarea>
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Meta Keywords (SEO)</label>
                    <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}"
                           class="input-admin" placeholder="mots, clés, séparés, par, virgules">
                </div>
            </div>
        </div>

        <div class="flex gap-4 mt-8">
            <button type="submit" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-black font-semibold rounded-lg transition">
                <i class="fas fa-save mr-2"></i>Créer le document
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
    if (this.value === 'internal') {
        internal.style.display = 'block';
        external.style.display = 'none';
    } else {
        internal.style.display = 'none';
        external.style.display = 'block';
    }
});
</script>
@endsection


