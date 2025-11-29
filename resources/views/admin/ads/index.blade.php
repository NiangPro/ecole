@extends('admin.layout')

@section('title', 'Gestion des Publicités')

@section('styles')
<style>
    /* Styles pour la page Ads Index */
    .ads-index-page h3 {
        color: #fff;
        transition: color 0.3s ease;
    }
    
    body.light-mode .ads-index-page h3 {
        color: #1e293b;
    }
    
    .ads-index-page .text-gray-400 {
        color: rgba(156, 163, 175, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .ads-index-page .text-gray-400 {
        color: rgba(100, 116, 139, 1);
    }
    
    .ads-index-page .text-gray-300 {
        color: rgba(209, 213, 219, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .ads-index-page .text-gray-300 {
        color: rgba(30, 41, 59, 0.8);
    }
    
    .ads-index-page .text-white {
        color: #fff;
        transition: color 0.3s ease;
    }
    
    body.light-mode .ads-index-page .text-white {
        color: #1e293b;
    }
    
    .ads-index-page .bg-gray-700 {
        background: rgba(55, 65, 81, 1);
        transition: background 0.3s ease;
    }
    
    body.light-mode .ads-index-page .bg-gray-700 {
        background: rgba(241, 245, 249, 1);
    }
    
    .ads-index-page .hover\:bg-cyan-500\/5:hover {
        background: rgba(6, 182, 212, 0.05);
    }
    
    body.light-mode .ads-index-page .hover\:bg-cyan-500\/5:hover {
        background: rgba(6, 182, 212, 0.1);
    }
    
    .ads-index-page .bg-green-500\/10 {
        background: rgba(34, 197, 94, 0.1);
        border-color: rgba(34, 197, 94, 0.3);
    }
    
    body.light-mode .ads-index-page .bg-green-500\/10 {
        background: rgba(34, 197, 94, 0.15);
        border-color: rgba(34, 197, 94, 0.4);
    }
</style>
@endsection

@section('content')
<div class="ads-index-page">
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h3 class="text-3xl font-bold mb-2">Gestion des Publicités</h3>
        <p class="text-gray-400">Gérez les publicités affichées sur votre site</p>
    </div>
    <a href="{{ route('admin.ads.create') }}" class="btn-primary">
        <i class="fas fa-plus mr-2"></i>Nouvelle Publicité
    </a>
</div>

@if(session('success'))
    <div class="bg-green-500/10 border border-green-500/30 text-green-400 px-6 py-4 rounded-lg mb-6 flex items-center gap-3">
        <i class="fas fa-check-circle text-xl"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<div class="content-section">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-cyan-500/20">
                    <th class="text-left p-4 text-cyan-400 font-semibold">Nom</th>
                    <th class="text-left p-4 text-cyan-400 font-semibold">Position</th>
                    <th class="text-left p-4 text-cyan-400 font-semibold">Statut</th>
                    <th class="text-left p-4 text-cyan-400 font-semibold">Ordre</th>
                    <th class="text-left p-4 text-cyan-400 font-semibold">Impressions</th>
                    <th class="text-left p-4 text-cyan-400 font-semibold">Clics</th>
                    <th class="text-right p-4 text-cyan-400 font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ads as $ad)
                <tr class="border-b border-cyan-500/10 hover:bg-cyan-500/5 transition">
                    <td class="p-4">
                        <div class="font-semibold text-white">{{ $ad->name }}</div>
                        @if($ad->description)
                        <div class="text-sm text-gray-400 mt-1">{{ Str::limit($ad->description, 50) }}</div>
                        @endif
                    </td>
                    <td class="p-4">
                        <span class="px-3 py-1 bg-cyan-500/20 text-cyan-400 rounded-full text-sm font-semibold">
                            @if($ad->position === 'sidebar')
                                Sidebar
                            @elseif($ad->position === 'content')
                                Contenu
                            @elseif($ad->position === 'header')
                                En-tête
                            @else
                                Pied de page
                            @endif
                        </span>
                    </td>
                    <td class="p-4">
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            {{ $ad->status === 'active' ? 'bg-green-500/20 text-green-400' : 'bg-gray-500/20 text-gray-400' }}">
                            {{ $ad->status === 'active' ? 'Actif' : 'Inactif' }}
                        </span>
                    </td>
                    <td class="p-4 text-gray-300">{{ $ad->order }}</td>
                    <td class="p-4 text-gray-300">{{ number_format($ad->impressions) }}</td>
                    <td class="p-4 text-gray-300">{{ number_format($ad->clicks) }}</td>
                    <td class="p-4">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.ads.show', $ad->id) }}" class="px-3 py-1 bg-blue-500/20 hover:bg-blue-500/30 text-blue-400 rounded transition" title="Voir">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.ads.edit', $ad->id) }}" class="px-3 py-1 bg-cyan-500/20 hover:bg-cyan-500/30 text-cyan-400 rounded transition" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.ads.destroy', $ad->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette publicité ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded transition" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-8 text-center text-gray-400">
                        <i class="fas fa-ad text-4xl mb-4 block"></i>
                        <p>Aucune publicité créée pour le moment.</p>
                        <a href="{{ route('admin.ads.create') }}" class="text-cyan-400 hover:underline mt-2 inline-block">
                            Créer votre première publicité
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($ads->hasPages())
    <div class="mt-6">
        {{ $ads->links() }}
    </div>
    @endif
</div>
</div>
@endsection

