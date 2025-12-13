@extends('admin.layout')

@section('title', 'Configuration Ezoic')

@section('styles')
<style>
    .ezoic-page h3 {
        color: #fff;
        transition: color 0.3s ease;
    }
    
    body.light-mode .ezoic-page h3 {
        color: #1e293b;
    }
    
    .ezoic-page h4 {
        color: #fff;
        transition: color 0.3s ease;
    }
    
    body.light-mode .ezoic-page h4 {
        color: #1e293b;
    }
    
    .ezoic-page .text-gray-300 {
        color: rgba(209, 213, 219, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .ezoic-page .text-gray-300 {
        color: rgba(30, 41, 59, 0.8);
    }
    
    .ezoic-page .text-gray-400 {
        color: rgba(156, 163, 175, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .ezoic-page .text-gray-400 {
        color: rgba(100, 116, 139, 1);
    }
    
    .ezoic-page .text-gray-500 {
        color: rgba(107, 114, 128, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .ezoic-page .text-gray-500 {
        color: rgba(148, 163, 184, 1);
    }
    
    .ezoic-page .bg-black\/30 {
        background: rgba(0, 0, 0, 0.3);
        transition: background 0.3s ease;
    }
    
    body.light-mode .ezoic-page .bg-black\/30 {
        background: rgba(255, 255, 255, 0.9);
    }
    
    .ezoic-page .hover\:bg-black\/40:hover {
        background: rgba(0, 0, 0, 0.4);
    }
    
    body.light-mode .ezoic-page .hover\:bg-black\/40:hover {
        background: rgba(255, 255, 255, 1);
    }
    
    .ezoic-page .bg-gray-600 {
        background: rgba(75, 85, 99, 1);
        transition: background 0.3s ease;
    }
    
    body.light-mode .ezoic-page .bg-gray-600 {
        background: rgba(148, 163, 184, 1);
    }
    
    .ezoic-page .bg-green-500\/20 {
        background: rgba(34, 197, 94, 0.2);
        border-color: rgba(34, 197, 94, 0.3);
    }
    
    body.light-mode .ezoic-page .bg-green-500\/20 {
        background: rgba(34, 197, 94, 0.15);
        border-color: rgba(34, 197, 94, 0.4);
    }
    
    .ezoic-page .bg-gray-500\/20 {
        background: rgba(107, 114, 128, 0.2);
    }
    
    body.light-mode .ezoic-page .bg-gray-500\/20 {
        background: rgba(148, 163, 184, 0.15);
    }
    
    .ezoic-page .text-cyan-400 {
        color: #06b6d4;
    }
    
    .ezoic-page .font-semibold {
        color: rgba(255, 255, 255, 0.9);
        transition: color 0.3s ease;
    }
    
    body.light-mode .ezoic-page .font-semibold {
        color: rgba(30, 41, 59, 0.9);
    }
</style>
@endsection

@section('content')
<div class="ezoic-page">
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <h3 class="text-3xl font-bold">Configuration Ezoic</h3>
    <div class="flex gap-3">
        <a href="{{ route('admin.ezoic-units.index') }}" class="px-6 py-3 bg-cyan-600 hover:bg-cyan-700 rounded-lg font-semibold transition">
            <i class="fas fa-ad mr-2"></i>Gérer les Unités
        </a>
    </div>
</div>

@if(session('success'))
    <div class="bg-green-500/10 border border-green-500/30 text-green-400 px-6 py-4 rounded-lg mb-6 flex items-center gap-3">
        <i class="fas fa-check-circle text-xl"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<div class="content-section">
    <h4 class="text-xl font-bold mb-6">Paramètres Ezoic</h4>
    
    <form action="{{ route('admin.ezoic.update') }}" method="POST">
        @csrf
        
        <div class="mb-6">
            <label class="block text-gray-300 mb-2 font-semibold">Site ID <span class="text-gray-500 text-sm">(Optionnel)</span></label>
            <input type="text" name="site_id" class="input-admin" placeholder="Votre Site ID Ezoic (optionnel)" value="{{ $settings->site_id ?? '' }}">
            @error('site_id')
                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
            @enderror
            <p class="text-gray-500 text-sm mt-2">
                <i class="fas fa-info-circle mr-1"></i>
                Votre identifiant de site Ezoic (optionnel). Vous pouvez le trouver dans l'URL de votre tableau de bord Ezoic (ex: ezoic.com/sites/12345678/dashboard) ou dans les paramètres du site. Ce champ n'est pas obligatoire pour l'intégration standard.
            </p>
        </div>
        
        <div class="mb-6">
            <label class="block text-gray-300 mb-2 font-semibold">Privacy Scripts (OBLIGATOIRE - Doit être chargé AVANT le Header Script)</label>
            <textarea name="privacy_scripts" class="input-admin" placeholder="Collez les Privacy Scripts Ezoic ici..." rows="4">{{ $settings->privacy_scripts ?? '' }}</textarea>
            @error('privacy_scripts')
                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
            @enderror
            <p class="text-gray-500 text-sm mt-2">
                <i class="fas fa-info-circle mr-1"></i>
                Les Privacy Scripts doivent être chargés en premier dans le &lt;head&gt;. Format recommandé :
                <br>
                <code class="text-xs">&lt;script data-cfasync="false" src="https://cmp.gatekeeperconsent.com/min.js"&gt;&lt;/script&gt;</code>
                <br>
                <code class="text-xs">&lt;script data-cfasync="false" src="https://the.gatekeeperconsent.com/cmp.min.js"&gt;&lt;/script&gt;</code>
            </p>
        </div>
        
        <div class="mb-6">
            <label class="block text-gray-300 mb-2 font-semibold">Header Script (Doit être chargé APRÈS les Privacy Scripts)</label>
            <textarea name="ezoic_code" class="input-admin" placeholder="Collez le Header Script Ezoic ici..." rows="6">{{ $settings->ezoic_code ?? '' }}</textarea>
            @error('ezoic_code')
                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
            @enderror
            <p class="text-gray-500 text-sm mt-2">
                <i class="fas fa-info-circle mr-1"></i>
                Le Header Script sera inséré dans la section &lt;head&gt; APRÈS les Privacy Scripts. Format recommandé :
                <br>
                <code class="text-xs">&lt;script async src="//www.ezojs.com/ezoic/sa.min.js"&gt;&lt;/script&gt;</code>
                <br>
                <code class="text-xs">&lt;script&gt;window.ezstandalone = window.ezstandalone || {}; ezstandalone.cmd = ezstandalone.cmd || [];&lt;/script&gt;</code>
            </p>
        </div>
        
        <div class="mb-6">
            <label class="block text-gray-300 mb-2 font-semibold">Code Ezoic (Body - début)</label>
            <textarea name="ezoic_body_code" class="input-admin" placeholder="Collez votre code Ezoic pour le début du &lt;body&gt; ici..." rows="6">{{ $settings->ezoic_body_code ?? '' }}</textarea>
            @error('ezoic_body_code')
                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
            @enderror
            <p class="text-gray-500 text-sm mt-2">
                <i class="fas fa-info-circle mr-1"></i>
                Ce code sera inséré juste après l'ouverture de la balise &lt;body&gt;
            </p>
        </div>
        
        <div class="mb-6">
            <label class="block text-gray-300 mb-2 font-semibold">Code Ezoic (Footer)</label>
            <textarea name="ezoic_footer_code" class="input-admin" placeholder="Collez votre code Ezoic pour le footer ici..." rows="6">{{ $settings->ezoic_footer_code ?? '' }}</textarea>
            @error('ezoic_footer_code')
                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
            @enderror
            <p class="text-gray-500 text-sm mt-2">
                <i class="fas fa-info-circle mr-1"></i>
                Ce code sera inséré avant la fermeture de la balise &lt;/body&gt;
            </p>
        </div>
        
        <div class="mb-6">
            <label class="block text-gray-300 mb-2 font-semibold">Emplacements publicitaires</label>
            <div class="space-y-3">
                <label class="flex items-center gap-3 p-4 bg-black/30 rounded-lg cursor-pointer hover:bg-black/40 transition">
                    <input type="checkbox" name="header_banner" class="w-5 h-5" {{ ($settings->header_banner ?? true) ? 'checked' : '' }}>
                    <div class="flex-1">
                        <span class="font-semibold">Bannière en-tête</span>
                        <p class="text-sm text-gray-400">Affichée en haut de chaque page</p>
                    </div>
                </label>
                <label class="flex items-center gap-3 p-4 bg-black/30 rounded-lg cursor-pointer hover:bg-black/40 transition">
                    <input type="checkbox" name="sidebar_banner" class="w-5 h-5" {{ ($settings->sidebar_banner ?? true) ? 'checked' : '' }}>
                    <div class="flex-1">
                        <span class="font-semibold">Sidebar</span>
                        <p class="text-sm text-gray-400">Affichée dans la barre latérale</p>
                    </div>
                </label>
                <label class="flex items-center gap-3 p-4 bg-black/30 rounded-lg cursor-pointer hover:bg-black/40 transition">
                    <input type="checkbox" name="footer_banner" class="w-5 h-5" {{ ($settings->footer_banner ?? false) ? 'checked' : '' }}>
                    <div class="flex-1">
                        <span class="font-semibold">Bannière pied de page</span>
                        <p class="text-sm text-gray-400">Affichée en bas de chaque page</p>
                    </div>
                </label>
                <label class="flex items-center gap-3 p-4 bg-black/30 rounded-lg cursor-pointer hover:bg-black/40 transition">
                    <input type="checkbox" name="in_content" class="w-5 h-5" {{ ($settings->in_content ?? false) ? 'checked' : '' }}>
                    <div class="flex-1">
                        <span class="font-semibold">Dans le contenu</span>
                        <p class="text-sm text-gray-400">Annonces intégrées dans le contenu</p>
                    </div>
                </label>
                <label class="flex items-center gap-3 p-4 bg-black/30 rounded-lg cursor-pointer hover:bg-black/40 transition">
                    <input type="checkbox" name="auto_ads" class="w-5 h-5" {{ ($settings->auto_ads ?? true) ? 'checked' : '' }}>
                    <div class="flex-1">
                        <span class="font-semibold">Annonces automatiques</span>
                        <p class="text-sm text-gray-400">Activer les annonces automatiques Ezoic</p>
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
            <p class="text-gray-400 text-sm mb-2">Site ID</p>
            <p class="font-mono text-cyan-400">{{ $settings->site_id ?? 'Non configuré' }}</p>
        </div>
        <div class="p-4 bg-black/30 rounded-lg">
            <p class="text-gray-400 text-sm mb-2">Privacy Scripts</p>
            <p class="font-mono text-cyan-400">{{ ($settings && $settings->privacy_scripts) ? 'Configuré' : 'Non configuré' }}</p>
        </div>
        <div class="p-4 bg-black/30 rounded-lg">
            <p class="text-gray-400 text-sm mb-2">Header Script</p>
            <p class="font-mono text-cyan-400">{{ ($settings && $settings->ezoic_code) ? 'Configuré' : 'Non configuré' }}</p>
        </div>
        <div class="p-4 bg-black/30 rounded-lg">
            <p class="text-gray-400 text-sm mb-2">Code Body</p>
            <p class="font-mono text-cyan-400">{{ ($settings && $settings->ezoic_body_code) ? 'Configuré' : 'Non configuré' }}</p>
        </div>
        <div class="p-4 bg-black/30 rounded-lg">
            <p class="text-gray-400 text-sm mb-2">Code Footer</p>
            <p class="font-mono text-cyan-400">{{ ($settings && $settings->ezoic_footer_code) ? 'Configuré' : 'Non configuré' }}</p>
        </div>
        <div class="p-4 bg-black/30 rounded-lg">
            <p class="text-gray-400 text-sm mb-2">Emplacements actifs</p>
            <div class="flex gap-2 mt-2 flex-wrap">
                @if($settings && $settings->header_banner)
                    <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-xs">En-tête</span>
                @endif
                @if($settings && $settings->sidebar_banner)
                    <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-xs">Sidebar</span>
                @endif
                @if($settings && $settings->footer_banner)
                    <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-xs">Footer</span>
                @endif
                @if($settings && $settings->in_content)
                    <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-xs">Dans contenu</span>
                @endif
                @if($settings && $settings->auto_ads)
                    <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-xs">Auto</span>
                @endif
                @if(!$settings || (!$settings->header_banner && !$settings->sidebar_banner && !$settings->footer_banner && !$settings->in_content && !$settings->auto_ads))
                    <span class="px-3 py-1 bg-gray-500/20 text-gray-400 rounded-full text-xs">Aucun</span>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

