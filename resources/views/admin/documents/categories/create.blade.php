@extends('admin.layout')

@section('title', 'Nouvelle Catégorie')

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
    
    body.light-mode .text-gray-400 {
        color: #64748b;
    }
</style>
@endsection

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h3 class="text-3xl font-bold mb-2">Nouvelle Catégorie</h3>
        <p class="text-gray-400">Créez une nouvelle catégorie de documents</p>
    </div>
    <a href="{{ route('admin.documents.categories.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition">
        <i class="fas fa-arrow-left mr-2"></i>Retour
    </a>
</div>

<div class="content-section">
    <form action="{{ route('admin.documents.categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="space-y-6">
                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Nom de la catégorie *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="input-admin" placeholder="Ex: Guides et Tutoriels">
                    @error('name')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug') }}"
                           class="input-admin" placeholder="Sera généré automatiquement si vide">
                    @error('slug')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Description</label>
                    <textarea name="description" rows="4" class="input-admin" placeholder="Description de la catégorie">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Catégorie parente</label>
                    <select name="parent_id" class="input-admin">
                        <option value="">Aucune (catégorie principale)</option>
                        @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Icône (Font Awesome)</label>
                    <input type="text" name="icon" value="{{ old('icon') }}"
                           class="input-admin" placeholder="Ex: fas fa-book">
                    <p class="text-gray-400 text-sm mt-1">Utilisez les classes Font Awesome</p>
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Image de la catégorie</label>
                    <select name="image_type" id="categoryImageType" class="input-admin mb-3">
                        <option value="internal" {{ old('image_type', 'internal') === 'internal' ? 'selected' : '' }}>Interne (upload)</option>
                        <option value="external" {{ old('image_type') === 'external' ? 'selected' : '' }}>Externe (URL)</option>
                    </select>
                    <div id="categoryInternalImage" style="display: {{ old('image_type', 'internal') === 'internal' ? 'block' : 'none' }};">
                        <input type="file" name="image_file" accept="image/*" class="input-admin">
                    </div>
                    <div id="categoryExternalImage" style="display: {{ old('image_type') === 'external' ? 'block' : 'none' }};">
                        <input type="url" name="image" value="{{ old('image') }}"
                               class="input-admin" placeholder="https://example.com/image.jpg">
                    </div>
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Ordre d'affichage</label>
                    <input type="number" name="order" value="{{ old('order', 0) }}" min="0"
                           class="input-admin">
                </div>

                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               class="w-5 h-5 rounded bg-cyan-500/20 border-cyan-500/50 text-cyan-500 focus:ring-cyan-500">
                        <span class="text-cyan-400 font-semibold">Catégorie active</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex gap-4 mt-8">
            <button type="submit" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-black font-semibold rounded-lg transition">
                <i class="fas fa-save mr-2"></i>Créer la catégorie
            </button>
            <a href="{{ route('admin.documents.categories.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition">
                Annuler
            </a>
        </div>
    </form>
</div>

<script>
document.getElementById('categoryImageType').addEventListener('change', function() {
    const internal = document.getElementById('categoryInternalImage');
    const external = document.getElementById('categoryExternalImage');
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


