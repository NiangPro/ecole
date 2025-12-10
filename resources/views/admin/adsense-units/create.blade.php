@extends('admin.layout')

@section('title', 'Créer une Unité AdSense')

@section('styles')
<style>
    .adsense-units-form h3 {
        color: #fff;
        transition: color 0.3s ease;
    }
    
    body.light-mode .adsense-units-form h3 {
        color: #1e293b;
    }
    
    .adsense-units-form .text-gray-300 {
        color: rgba(209, 213, 219, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .adsense-units-form .text-gray-300 {
        color: rgba(30, 41, 59, 0.8);
    }
    
    .adsense-units-form .text-gray-400 {
        color: rgba(156, 163, 175, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .adsense-units-form .text-gray-400 {
        color: rgba(100, 116, 139, 1);
    }
</style>
@endsection

@section('content')
<div class="adsense-units-form">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.adsense-units.index') }}" class="text-gray-400 hover:text-gray-300">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h3 class="text-3xl font-bold">Créer une Unité AdSense</h3>
    </div>

    <div class="content-section">
        <form action="{{ route('admin.adsense-units.store') }}" method="POST">
            @csrf

            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-300 mb-2 font-semibold">
                        Nom de l'unité <span class="text-red-400">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           class="input-admin" 
                           placeholder="Ex: Sidebar - 300x250"
                           value="{{ old('name') }}"
                           required>
                    @error('name')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-400 text-sm mt-2">
                        <i class="fas fa-info-circle mr-1"></i>
                        Nom descriptif pour identifier cette unité
                    </p>
                </div>

                <div>
                    <label class="block text-gray-300 mb-2 font-semibold">
                        Slot ID AdSense <span class="text-red-400">*</span>
                    </label>
                    <input type="text" 
                           name="ad_slot" 
                           class="input-admin" 
                           placeholder="Ex: 1234567890"
                           value="{{ old('ad_slot') }}"
                           required>
                    @error('ad_slot')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-400 text-sm mt-2">
                        <i class="fas fa-info-circle mr-1"></i>
                        ID du slot depuis votre compte AdSense
                    </p>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-300 mb-2 font-semibold">
                    Description
                </label>
                <textarea name="description" 
                          class="input-admin" 
                          rows="3"
                          placeholder="Description de l'emplacement et de l'utilisation de cette unité...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-300 mb-2 font-semibold">
                        Position <span class="text-red-400">*</span>
                    </label>
                    <select name="position" class="input-admin" required>
                        <option value="">Sélectionner une position</option>
                        <option value="header" {{ old('position') === 'header' ? 'selected' : '' }}>Header (En-tête)</option>
                        <option value="sidebar" {{ old('position') === 'sidebar' ? 'selected' : '' }}>Sidebar (Barre latérale)</option>
                        <option value="content" {{ old('position') === 'content' ? 'selected' : '' }}>Content (Dans le contenu)</option>
                        <option value="footer" {{ old('position') === 'footer' ? 'selected' : '' }}>Footer (Pied de page)</option>
                        <option value="in-article" {{ old('position') === 'in-article' ? 'selected' : '' }}>In-Article (Dans l'article)</option>
                    </select>
                    @error('position')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-300 mb-2 font-semibold">
                        Location (optionnel)
                    </label>
                    <input type="text" 
                           name="location" 
                           class="input-admin" 
                           placeholder="Ex: homepage, article, formation"
                           value="{{ old('location') }}">
                    @error('location')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-400 text-sm mt-2">
                        <i class="fas fa-info-circle mr-1"></i>
                        Page ou section spécifique (laisser vide pour toutes les pages)
                    </p>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-gray-300 mb-2 font-semibold">
                        Format <span class="text-red-400">*</span>
                    </label>
                    <select name="ad_format" class="input-admin" required>
                        <option value="auto" {{ old('ad_format', 'auto') === 'auto' ? 'selected' : '' }}>Auto (Adaptatif)</option>
                        <option value="horizontal" {{ old('ad_format') === 'horizontal' ? 'selected' : '' }}>Horizontal</option>
                        <option value="vertical" {{ old('ad_format') === 'vertical' ? 'selected' : '' }}>Vertical</option>
                        <option value="rectangle" {{ old('ad_format') === 'rectangle' ? 'selected' : '' }}>Rectangle</option>
                    </select>
                    @error('ad_format')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-300 mb-2 font-semibold">
                        Taille (optionnel)
                    </label>
                    <input type="text" 
                           name="size" 
                           class="input-admin" 
                           placeholder="Ex: 300x250, 728x90"
                           value="{{ old('size') }}">
                    @error('size')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-300 mb-2 font-semibold">
                        Ordre d'affichage
                    </label>
                    <input type="number" 
                           name="order" 
                           class="input-admin" 
                           placeholder="0"
                           value="{{ old('order', 0) }}"
                           min="0">
                    @error('order')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label class="flex items-center gap-3 p-4 bg-black/30 rounded-lg cursor-pointer hover:bg-black/40 transition">
                    <input type="checkbox" 
                           name="responsive" 
                           class="w-5 h-5" 
                           {{ old('responsive', true) ? 'checked' : '' }}>
                    <div class="flex-1">
                        <span class="font-semibold text-gray-300">Responsive</span>
                        <p class="text-sm text-gray-400">L'annonce s'adapte automatiquement à la taille de l'écran</p>
                    </div>
                </label>
            </div>

            <div class="mb-6">
                <label class="block text-gray-300 mb-2 font-semibold">
                    Statut <span class="text-red-400">*</span>
                </label>
                <select name="status" class="input-admin" required>
                    <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Actif</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactif</option>
                </select>
                @error('status')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-300 mb-2 font-semibold">
                    Code personnalisé (optionnel)
                </label>
                <textarea name="custom_code" 
                          class="input-admin font-mono text-sm" 
                          rows="5"
                          placeholder="Code HTML/JavaScript personnalisé si nécessaire...">{{ old('custom_code') }}</textarea>
                @error('custom_code')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
                <p class="text-gray-400 text-sm mt-2">
                    <i class="fas fa-info-circle mr-1"></i>
                    Utilisez ce champ uniquement si vous avez besoin d'un code personnalisé
                </p>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i>Créer l'unité
                </button>
                <a href="{{ route('admin.adsense-units.index') }}" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 rounded-lg font-semibold transition">
                    <i class="fas fa-times mr-2"></i>Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

