@extends('admin.layout')

@section('content')
<h3 class="text-3xl font-bold mb-8">Configuration Google AdSense</h3>

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
@endsection
