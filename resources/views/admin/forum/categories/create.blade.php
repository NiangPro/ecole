@extends('admin.layout')

@section('title', 'Créer une Catégorie du Forum')

@section('content')
<div class="forum-categories-page">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.forum.categories.index') }}" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 rounded-lg font-semibold transition">
            <i class="fas fa-arrow-left mr-2"></i>Retour
        </a>
        <h3 class="text-3xl font-bold">Créer une Catégorie</h3>
    </div>

    <div class="content-section">
        <form action="{{ route('admin.forum.categories.store') }}" method="POST">
            @csrf
            
            <div class="mb-6">
                <label class="block text-gray-300 mb-2 font-semibold">
                    <i class="fas fa-tag mr-2"></i>Nom de la catégorie *
                </label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="input-admin" placeholder="Ex: Général">
                @error('name')
                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-300 mb-2 font-semibold">
                    <i class="fas fa-link mr-2"></i>Slug (optionnel)
                </label>
                <input type="text" name="slug" value="{{ old('slug') }}"
                       class="input-admin" placeholder="Sera généré automatiquement si vide">
                <p class="text-gray-500 text-sm mt-2">
                    <i class="fas fa-info-circle mr-1"></i>
                    Le slug sera généré automatiquement à partir du nom si laissé vide.
                </p>
                @error('slug')
                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-300 mb-2 font-semibold">
                    <i class="fas fa-align-left mr-2"></i>Description
                </label>
                <textarea name="description" rows="3" class="input-admin" placeholder="Description de la catégorie...">{{ old('description') }}</textarea>
                @error('description')
                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-300 mb-2 font-semibold">
                        <i class="fas fa-icons mr-2"></i>Icône Font Awesome
                    </label>
                    <input type="text" name="icon" value="{{ old('icon', 'fas fa-folder') }}"
                           class="input-admin" placeholder="Ex: fas fa-comments">
                    <p class="text-gray-500 text-sm mt-2">
                        <i class="fas fa-info-circle mr-1"></i>
                        Classe Font Awesome (ex: fas fa-comments, fab fa-html5)
                    </p>
                    @error('icon')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-300 mb-2 font-semibold">
                        <i class="fas fa-palette mr-2"></i>Couleur
                    </label>
                    <div class="flex gap-2">
                        <input type="color" name="color" value="{{ old('color', '#06b6d4') }}"
                               class="h-12 w-20 rounded border border-gray-600">
                        <input type="text" name="color" value="{{ old('color', '#06b6d4') }}"
                               class="input-admin flex-1" placeholder="#06b6d4">
                    </div>
                    @error('color')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-300 mb-2 font-semibold">
                        <i class="fas fa-sort-numeric-down mr-2"></i>Ordre d'affichage
                    </label>
                    <input type="number" name="order" value="{{ old('order', 0) }}" min="0"
                           class="input-admin" placeholder="0">
                    @error('order')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-300 mb-2 font-semibold">
                        <i class="fas fa-toggle-on mr-2"></i>Statut
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               class="w-5 h-5 rounded border-gray-600 bg-gray-700 text-cyan-500 focus:ring-cyan-500">
                        <span class="text-gray-300">Catégorie active</span>
                    </label>
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i>Créer la catégorie
                </button>
                <a href="{{ route('admin.forum.categories.index') }}" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 rounded-lg font-semibold transition">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

