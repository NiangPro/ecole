@extends('admin.layout')

@section('styles')
<style>
    /* Styles pour la page AdSense */
    .adsense-page h3 {
        color: #fff;
        transition: color 0.3s ease;
    }
    
    body.light-mode .adsense-page h3 {
        color: #1e293b;
    }
    
    .adsense-page h4 {
        color: #fff;
        transition: color 0.3s ease;
    }
    
    body.light-mode .adsense-page h4 {
        color: #1e293b;
    }
    
    .adsense-page .text-gray-300 {
        color: rgba(209, 213, 219, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .adsense-page .text-gray-300 {
        color: rgba(30, 41, 59, 0.8);
    }
    
    .adsense-page .text-gray-400 {
        color: rgba(156, 163, 175, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .adsense-page .text-gray-400 {
        color: rgba(100, 116, 139, 1);
    }
    
    .adsense-page .text-gray-500 {
        color: rgba(107, 114, 128, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .adsense-page .text-gray-500 {
        color: rgba(148, 163, 184, 1);
    }
    
    .adsense-page .bg-black\/30 {
        background: rgba(0, 0, 0, 0.3);
        transition: background 0.3s ease;
    }
    
    body.light-mode .adsense-page .bg-black\/30 {
        background: rgba(255, 255, 255, 0.9);
    }
    
    .adsense-page .hover\:bg-black\/40:hover {
        background: rgba(0, 0, 0, 0.4);
    }
    
    body.light-mode .adsense-page .hover\:bg-black\/40:hover {
        background: rgba(255, 255, 255, 1);
    }
    
    .adsense-page .bg-gray-600 {
        background: rgba(75, 85, 99, 1);
        transition: background 0.3s ease;
    }
    
    body.light-mode .adsense-page .bg-gray-600 {
        background: rgba(148, 163, 184, 1);
    }
    
    .adsense-page .bg-green-500\/20 {
        background: rgba(34, 197, 94, 0.2);
        border-color: rgba(34, 197, 94, 0.3);
    }
    
    body.light-mode .adsense-page .bg-green-500\/20 {
        background: rgba(34, 197, 94, 0.15);
        border-color: rgba(34, 197, 94, 0.4);
    }
    
    .adsense-page .bg-gray-500\/20 {
        background: rgba(107, 114, 128, 0.2);
    }
    
    body.light-mode .adsense-page .bg-gray-500\/20 {
        background: rgba(148, 163, 184, 0.15);
    }
    
    .adsense-page .text-cyan-400 {
        color: #06b6d4;
    }
    
    .adsense-page .font-semibold {
        color: rgba(255, 255, 255, 0.9);
        transition: color 0.3s ease;
    }
    
    body.light-mode .adsense-page .font-semibold {
        color: rgba(30, 41, 59, 0.9);
    }
</style>
@endsection

@section('content')
<div class="adsense-page">
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <h3 class="text-3xl font-bold">Configuration Google AdSense</h3>
    <a href="{{ route('admin.adsense.check') }}" class="btn-primary">
        <i class="fas fa-check-circle mr-2"></i>Vérifier l'éligibilité
    </a>
</div>

@if(session('success'))
    <div class="bg-green-500/10 border border-green-500/30 text-green-400 px-6 py-4 rounded-lg mb-6 flex items-center gap-3">
        <i class="fas fa-check-circle text-xl"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<div class="content-section">
    <h4 class="text-xl font-bold mb-6">Paramètres AdSense</h4>
    
    <form action="{{ route('admin.adsense.update') }}" method="POST">
        @csrf
        
        <div class="mb-6">
            <label class="block text-gray-300 mb-2 font-semibold">Publisher ID</label>
            <input type="text" name="publisher_id" class="input-admin" placeholder="pub-XXXXXXXXXXXXXXXX" value="{{ $settings->publisher_id ?? '' }}">
            @error('publisher_id')
                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mb-6">
            <label class="block text-gray-300 mb-2 font-semibold">Code AdSense (Head)</label>
            <textarea name="adsense_code" class="input-admin" placeholder="Collez votre code AdSense ici..." rows="6">{{ $settings->adsense_code ?? '' }}</textarea>
            @error('adsense_code')
                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
            @enderror
            <p class="text-gray-500 text-sm mt-2">
                <i class="fas fa-info-circle mr-1"></i>
                Ce code sera inséré dans la section &lt;head&gt; de toutes les pages
            </p>
        </div>
        
        <div class="mb-6">
            <label class="block text-gray-300 mb-2 font-semibold">Emplacements publicitaires</label>
            <div class="space-y-3">
                <label class="flex items-center gap-3 p-4 bg-black/30 rounded-lg cursor-pointer hover:bg-black/40 transition">
                    <input type="checkbox" name="header_banner" class="w-5 h-5" {{ ($settings->header_banner ?? true) ? 'checked' : '' }}>
                    <div class="flex-1">
                        <span class="font-semibold">Bannière en-tête (728x90)</span>
                        <p class="text-sm text-gray-400">Affichée en haut de chaque page</p>
                    </div>
                </label>
                <label class="flex items-center gap-3 p-4 bg-black/30 rounded-lg cursor-pointer hover:bg-black/40 transition">
                    <input type="checkbox" name="sidebar_banner" class="w-5 h-5" {{ ($settings->sidebar_banner ?? true) ? 'checked' : '' }}>
                    <div class="flex-1">
                        <span class="font-semibold">Sidebar (300x250)</span>
                        <p class="text-sm text-gray-400">Affichée dans la barre latérale</p>
                    </div>
                </label>
                <label class="flex items-center gap-3 p-4 bg-black/30 rounded-lg cursor-pointer hover:bg-black/40 transition">
                    <input type="checkbox" name="footer_banner" class="w-5 h-5" {{ ($settings->footer_banner ?? false) ? 'checked' : '' }}>
                    <div class="flex-1">
                        <span class="font-semibold">Bannière pied de page (728x90)</span>
                        <p class="text-sm text-gray-400">Affichée en bas de chaque page</p>
                    </div>
                </label>
            </div>
        </div>
        
        <div class="flex gap-4">
            <button type="submit" class="btn-primary">
                <i class="fas fa-save mr-2"></i>Enregistrer les modifications
            </button>
            <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 rounded-lg font-semibold transition">
                <i class="fas fa-times mr-2"></i>Annuler
            </a>
        </div>
    </form>
</div>

<div class="content-section mt-6">
    <h4 class="text-xl font-bold mb-4">Aperçu de la configuration</h4>
    <div class="grid md:grid-cols-2 gap-6">
        <div class="p-4 bg-black/30 rounded-lg">
            <p class="text-gray-400 text-sm mb-2">Publisher ID</p>
            <p class="font-mono text-cyan-400">{{ $settings->publisher_id ?? 'Non configuré' }}</p>
        </div>
        <div class="p-4 bg-black/30 rounded-lg">
            <p class="text-gray-400 text-sm mb-2">Code AdSense</p>
            <p class="font-mono text-cyan-400">{{ ($settings && $settings->adsense_code) ? 'Configuré' : 'Non configuré' }}</p>
        </div>
        <div class="p-4 bg-black/30 rounded-lg">
            <p class="text-gray-400 text-sm mb-2">Bannières actives</p>
            <div class="flex gap-2 mt-2">
                @if($settings && $settings->header_banner)
                    <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-xs">En-tête</span>
                @endif
                @if($settings && $settings->sidebar_banner)
                    <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-xs">Sidebar</span>
                @endif
                @if($settings && $settings->footer_banner)
                    <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-xs">Footer</span>
                @endif
                @if(!$settings || (!$settings->header_banner && !$settings->sidebar_banner && !$settings->footer_banner))
                    <span class="px-3 py-1 bg-gray-500/20 text-gray-400 rounded-full text-xs">Aucune</span>
                @endif
            </div>
        </div>
    </div>
</div>
</div>
@endsection
