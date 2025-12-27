@extends('admin.layout')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'Modifier Catégorie')

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h3 class="text-3xl font-bold mb-2">Modifier Catégorie</h3>
        <p class="text-gray-400">Modifiez les informations de la catégorie</p>
    </div>
    <a href="{{ route('admin.documents.categories.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition">
        <i class="fas fa-arrow-left mr-2"></i>Retour
    </a>
</div>

<div class="content-section">
    <form action="{{ route('admin.documents.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="space-y-6">
                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Nom de la catégorie *</label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                           class="input-admin">
                    @error('name')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $category->slug) }}"
                           class="input-admin">
                    @error('slug')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Description</label>
                    <textarea name="description" rows="4" class="input-admin">{{ old('description', $category->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Catégorie parente</label>
                    <select name="parent_id" class="input-admin">
                        <option value="">Aucune (catégorie principale)</option>
                        @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Icône (Font Awesome)</label>
                    <input type="text" name="icon" value="{{ old('icon', $category->icon) }}"
                           class="input-admin" placeholder="Ex: fas fa-book">
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Image de la catégorie</label>
                    @if($category->image)
                        <div class="mb-3">
                            <img src="{{ $category->image_type === 'internal' ? Storage::url($category->image) : $category->image }}" 
                                 alt="{{ $category->name }}" 
                                 class="w-32 h-32 object-cover rounded-lg border border-cyan-500/20">
                        </div>
                    @endif
                    <select name="image_type" id="categoryImageType" class="input-admin mb-3">
                        <option value="internal" {{ old('image_type', $category->image_type) === 'internal' ? 'selected' : '' }}>Interne (upload)</option>
                        <option value="external" {{ old('image_type', $category->image_type) === 'external' ? 'selected' : '' }}>Externe (URL)</option>
                    </select>
                    <div id="categoryInternalImage" style="display: {{ old('image_type', $category->image_type) === 'internal' ? 'block' : 'none' }};">
                        <input type="file" name="image_file" accept="image/*" class="input-admin">
                    </div>
                    <div id="categoryExternalImage" style="display: {{ old('image_type', $category->image_type) === 'external' ? 'block' : 'none' }};">
                        <input type="url" name="image" value="{{ old('image', $category->image_type === 'external' ? $category->image : '') }}"
                               class="input-admin" placeholder="https://example.com/image.jpg">
                    </div>
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Ordre d'affichage</label>
                    <input type="number" name="order" value="{{ old('order', $category->order) }}" min="0"
                           class="input-admin">
                </div>

                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                               class="w-5 h-5 rounded bg-cyan-500/20 border-cyan-500/50 text-cyan-500 focus:ring-cyan-500">
                        <span class="text-cyan-400 font-semibold">Catégorie active</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex gap-4 mt-8">
            <button type="submit" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-black font-semibold rounded-lg transition">
                <i class="fas fa-save mr-2"></i>Enregistrer
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


