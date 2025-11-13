@extends('admin.layout')

@section('title', 'Modifier Catégorie')

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h3 class="text-3xl font-bold mb-2">Modifier Catégorie</h3>
        <p class="text-gray-400">Modifiez les informations de la catégorie</p>
    </div>
    <a href="{{ route('admin.jobs.categories.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition">
        <i class="fas fa-arrow-left mr-2"></i>Retour
    </a>
</div>

<div class="content-section">
    <form action="{{ route('admin.jobs.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="mb-6">
            <label class="block text-cyan-400 mb-2 font-semibold">Nom de la catégorie *</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                   class="input-admin" placeholder="Ex: Offres d'emploi">
            @error('name')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-cyan-400 mb-2 font-semibold">Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $category->slug) }}"
                   class="input-admin" placeholder="Sera généré automatiquement si vide">
            @error('slug')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-cyan-400 mb-2 font-semibold">Description</label>
            <textarea name="description" rows="4" class="input-admin" placeholder="Description de la catégorie">{{ old('description', $category->description) }}</textarea>
        </div>

        <div class="mb-6">
            <label class="block text-cyan-400 mb-2 font-semibold">Icône (Font Awesome)</label>
            <input type="text" name="icon" value="{{ old('icon', $category->icon) }}"
                   class="input-admin" placeholder="Ex: fas fa-briefcase">
            <p class="text-gray-400 text-sm mt-1">Utilisez les classes Font Awesome (ex: fas fa-briefcase)</p>
        </div>

        <div class="mb-6">
            <label class="block text-cyan-400 mb-2 font-semibold">Image de la catégorie</label>
            <select name="image_type" id="categoryImageType" class="input-admin mb-3">
                <option value="internal" {{ old('image_type', $category->image_type ?? 'internal') === 'internal' ? 'selected' : '' }}>Interne (upload)</option>
                <option value="external" {{ old('image_type', $category->image_type ?? '') === 'external' ? 'selected' : '' }}>Externe (URL)</option>
            </select>
            <div id="categoryInternalImage" style="display: {{ old('image_type', $category->image_type ?? 'internal') === 'internal' ? 'block' : 'none' }};">
                <input type="file" name="image_file" accept="image/*" class="input-admin">
                @if($category->image_type === 'internal' && $category->image)
                    <p class="text-gray-400 text-sm mt-1">Image actuelle: <a href="{{ \Illuminate\Support\Facades\Storage::url($category->image) }}" target="_blank" class="text-cyan-400 hover:underline">{{ basename($category->image) }}</a></p>
                @else
                    <p class="text-gray-400 text-sm mt-1">Formats acceptés: JPG, PNG, GIF</p>
                @endif
            </div>
            <div id="categoryExternalImage" style="display: {{ old('image_type', $category->image_type ?? '') === 'external' ? 'block' : 'none' }};">
                <input type="url" name="image" value="{{ old('image', $category->image ?? '') }}"
                       class="input-admin" placeholder="https://example.com/image.jpg">
                <p class="text-gray-400 text-sm mt-1">Entrez l'URL complète de l'image</p>
            </div>
            <div id="categoryImagePreview" class="mt-4 {{ $category->image ? '' : 'hidden' }}">
                <img id="categoryPreviewImg" src="{{ $category->image ? ($category->image_type === 'internal' ? \Illuminate\Support\Facades\Storage::url($category->image) : $category->image) : '' }}" alt="Aperçu" class="w-full max-w-xs rounded-lg border border-cyan-500/20">
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-cyan-400 mb-2 font-semibold">Ordre d'affichage</label>
            <input type="number" name="order" value="{{ old('order', $category->order) }}" min="0"
                   class="input-admin">
        </div>

        <div class="mb-6">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                       class="w-5 h-5 rounded bg-cyan-400/20 border-cyan-400/50 text-cyan-400 focus:ring-cyan-400">
                <span class="text-cyan-400 font-semibold">Catégorie active</span>
            </label>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="btn-primary">
                <i class="fas fa-save mr-2"></i>Mettre à jour
            </button>
            <a href="{{ route('admin.jobs.categories.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition">
                Annuler
            </a>
        </div>
    </form>
</div>

@section('scripts')
<script>
    const categoryImageType = document.getElementById('categoryImageType');
    const categoryInternalImage = document.getElementById('categoryInternalImage');
    const categoryExternalImage = document.getElementById('categoryExternalImage');
    
    if (categoryImageType) {
        categoryImageType.addEventListener('change', function() {
            if (this.value === 'internal') {
                categoryInternalImage.style.display = 'block';
                categoryExternalImage.style.display = 'none';
                document.querySelector('input[name="image"]').value = '';
            } else {
                categoryInternalImage.style.display = 'none';
                categoryExternalImage.style.display = 'block';
                document.querySelector('input[name="image_file"]').value = '';
            }
        });
    }
    
    const categoryImageFile = document.querySelector('input[name="image_file"]');
    if (categoryImageFile) {
        categoryImageFile.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const preview = document.getElementById('categoryImagePreview');
                    const previewImg = document.getElementById('categoryPreviewImg');
                    if (preview && previewImg) {
                        previewImg.src = event.target.result;
                        preview.classList.remove('hidden');
                    }
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    }
    
    const categoryImageUrl = document.querySelector('input[name="image"]');
    if (categoryImageUrl) {
        categoryImageUrl.addEventListener('input', function() {
            if (this.value) {
                const preview = document.getElementById('categoryImagePreview');
                const previewImg = document.getElementById('categoryPreviewImg');
                if (preview && previewImg) {
                    previewImg.src = this.value;
                    preview.classList.remove('hidden');
                }
            }
        });
    }
</script>
@endsection

