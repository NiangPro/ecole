@extends('admin.layout')

@section('title', "Annonces AdSense - {$formationName}")

@section('styles')
<style>
    .formation-adsense-form h3 {
        color: #fff;
        transition: color 0.3s ease;
    }
    
    body.light-mode .formation-adsense-form h3 {
        color: #1e293b;
    }
    
    .formation-adsense-form .text-gray-300 {
        color: rgba(209, 213, 219, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .formation-adsense-form .text-gray-300 {
        color: rgba(30, 41, 59, 0.8);
    }
    
    .formation-adsense-form .text-gray-400 {
        color: rgba(156, 163, 175, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .formation-adsense-form .text-gray-400 {
        color: rgba(100, 116, 139, 1);
    }
</style>
@endsection

@section('content')
<div class="formation-adsense-form">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.formation-adsense.index') }}" class="text-gray-400 hover:text-gray-300">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h3 class="text-3xl font-bold">Annonces AdSense - {{ $formationName }}</h3>
    </div>

    @if(session('success'))
        <div class="bg-green-500/10 border border-green-500/30 text-green-400 px-6 py-4 rounded-lg mb-6 flex items-center gap-3">
            <i class="fas fa-check-circle text-xl"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500/10 border border-red-500/30 text-red-400 px-6 py-4 rounded-lg mb-6 flex items-center gap-3">
            <i class="fas fa-exclamation-circle text-xl"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if($slug === 'all')
    <div class="bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 px-6 py-4 rounded-lg mb-6">
        <div class="flex items-center gap-3">
            <i class="fas fa-info-circle text-xl"></i>
            <div>
                <strong>Annonces Globales</strong>
                <p class="text-sm mt-1 text-gray-300">Les annonces configurées ici s'afficheront dans <strong>toutes</strong> les formations gratuites.</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Formulaire pour ajouter une annonce -->
    <div class="content-section mb-6">
        <h4 class="text-xl font-bold mb-4 text-gray-300">Ajouter une annonce</h4>
        <form action="{{ route('admin.formation-adsense.store') }}" method="POST">
            @csrf
            <input type="hidden" name="formation_slug" value="{{ $slug }}">
            
            <div class="grid md:grid-cols-4 gap-4 mb-4">
                <div>
                    <label class="block text-gray-300 mb-2 font-semibold text-sm">
                        Unité AdSense <span class="text-red-400">*</span>
                    </label>
                    <select name="adsense_unit_id" class="input-admin" required>
                        <option value="">Sélectionner une unité</option>
                        @foreach($adsenseUnits as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->ad_slot }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-300 mb-2 font-semibold text-sm">
                        Position <span class="text-red-400">*</span>
                    </label>
                    <select name="position" class="input-admin" required>
                        <option value="header">Header (En-tête)</option>
                        <option value="content" selected>Content (Dans le contenu)</option>
                        <option value="sidebar">Sidebar (Barre latérale)</option>
                        <option value="footer">Footer (Pied de page)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-300 mb-2 font-semibold text-sm">
                        Ordre
                    </label>
                    <input type="number" name="order" class="input-admin" value="0" min="0">
                </div>

                <div>
                    <label class="block text-gray-300 mb-2 font-semibold text-sm">
                        Statut <span class="text-red-400">*</span>
                    </label>
                    <select name="status" class="input-admin" required>
                        <option value="active" selected>Actif</option>
                        <option value="inactive">Inactif</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn-primary">
                <i class="fas fa-plus mr-2"></i>Ajouter l'annonce
            </button>
        </form>
    </div>

    <!-- Liste des annonces configurées -->
    <div class="content-section">
        <h4 class="text-xl font-bold mb-4 text-gray-300">Annonces configurées</h4>
        
        @if($currentAds->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-700">
                        <th class="text-left py-4 px-4 text-gray-300 font-semibold">Unité</th>
                        <th class="text-left py-4 px-4 text-gray-300 font-semibold">Position</th>
                        <th class="text-left py-4 px-4 text-gray-300 font-semibold">Ordre</th>
                        <th class="text-left py-4 px-4 text-gray-300 font-semibold">Statut</th>
                        <th class="text-right py-4 px-4 text-gray-300 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($currentAds as $ad)
                    <tr class="border-b border-gray-700/50 hover:bg-gray-800/30 transition">
                        <td class="py-4 px-4">
                            <div class="font-semibold text-gray-300">{{ $ad->adsenseUnit->name }}</div>
                            <code class="text-xs text-cyan-400">{{ $ad->adsenseUnit->ad_slot }}</code>
                        </td>
                        <td class="py-4 px-4">
                            <span class="px-3 py-1 bg-cyan-500/20 text-cyan-400 rounded-full text-xs font-semibold">
                                {{ ucfirst($ad->position) }}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-gray-300">{{ $ad->order }}</td>
                        <td class="py-4 px-4">
                            @if($ad->status === 'active')
                            <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-xs font-semibold">
                                Actif
                            </span>
                            @else
                            <span class="px-3 py-1 bg-gray-500/20 text-gray-400 rounded-full text-xs font-semibold">
                                Inactif
                            </span>
                            @endif
                        </td>
                        <td class="py-4 px-4">
                            <div class="flex justify-end gap-2">
                                <form action="{{ route('admin.formation-adsense.destroy', $ad->id) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette association ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="px-3 py-2 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded-lg transition"
                                            title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12">
            <i class="fas fa-ad text-6xl text-gray-400 mb-4"></i>
            <p class="text-gray-400">Aucune annonce configurée pour cette formation</p>
        </div>
        @endif
    </div>
</div>
@endsection

