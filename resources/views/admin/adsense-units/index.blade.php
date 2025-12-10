@extends('admin.layout')

@section('title', 'Gestion des Unités AdSense')

@section('styles')
<style>
    .adsense-units-page h3 {
        color: #fff;
        transition: color 0.3s ease;
    }
    
    body.light-mode .adsense-units-page h3 {
        color: #1e293b;
    }
    
    .adsense-units-page .text-gray-300 {
        color: rgba(209, 213, 219, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .adsense-units-page .text-gray-300 {
        color: rgba(30, 41, 59, 0.8);
    }
    
    .adsense-units-page .text-gray-400 {
        color: rgba(156, 163, 175, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .adsense-units-page .text-gray-400 {
        color: rgba(100, 116, 139, 1);
    }
</style>
@endsection

@section('content')
<div class="adsense-units-page">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <h3 class="text-3xl font-bold">Gestion des Unités AdSense</h3>
        <div class="flex gap-3">
            <a href="{{ route('admin.adsense') }}" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 rounded-lg font-semibold transition">
                <i class="fas fa-cog mr-2"></i>Configuration AdSense
            </a>
            <a href="{{ route('admin.adsense-units.create') }}" class="btn-primary">
                <i class="fas fa-plus mr-2"></i>Nouvelle Unité
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-500/10 border border-green-500/30 text-green-400 px-6 py-4 rounded-lg mb-6 flex items-center gap-3">
            <i class="fas fa-check-circle text-xl"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($units->count() > 0)
    <div class="content-section">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-700">
                        <th class="text-left py-4 px-4 text-gray-300 font-semibold">Nom</th>
                        <th class="text-left py-4 px-4 text-gray-300 font-semibold">Position</th>
                        <th class="text-left py-4 px-4 text-gray-300 font-semibold">Slot ID</th>
                        <th class="text-left py-4 px-4 text-gray-300 font-semibold">Format</th>
                        <th class="text-left py-4 px-4 text-gray-300 font-semibold">Statut</th>
                        <th class="text-left py-4 px-4 text-gray-300 font-semibold">Ordre</th>
                        <th class="text-right py-4 px-4 text-gray-300 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($units as $unit)
                    <tr class="border-b border-gray-700/50 hover:bg-gray-800/30 transition">
                        <td class="py-4 px-4">
                            <div class="font-semibold text-gray-300">{{ $unit->name }}</div>
                            @if($unit->description)
                            <div class="text-sm text-gray-400 mt-1">{{ Str::limit($unit->description, 50) }}</div>
                            @endif
                        </td>
                        <td class="py-4 px-4">
                            <span class="px-3 py-1 bg-cyan-500/20 text-cyan-400 rounded-full text-xs font-semibold">
                                {{ ucfirst($unit->position) }}
                            </span>
                            @if($unit->location)
                            <div class="text-xs text-gray-400 mt-1">{{ $unit->location }}</div>
                            @endif
                        </td>
                        <td class="py-4 px-4">
                            <code class="text-cyan-400 text-sm">{{ $unit->ad_slot }}</code>
                        </td>
                        <td class="py-4 px-4">
                            <span class="text-gray-300">{{ ucfirst($unit->ad_format) }}</span>
                            @if($unit->size)
                            <div class="text-xs text-gray-400 mt-1">{{ $unit->size }}</div>
                            @endif
                        </td>
                        <td class="py-4 px-4">
                            @if($unit->status === 'active')
                            <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-xs font-semibold">
                                <i class="fas fa-check-circle mr-1"></i>Actif
                            </span>
                            @else
                            <span class="px-3 py-1 bg-gray-500/20 text-gray-400 rounded-full text-xs font-semibold">
                                <i class="fas fa-pause-circle mr-1"></i>Inactif
                            </span>
                            @endif
                        </td>
                        <td class="py-4 px-4">
                            <span class="text-gray-300">{{ $unit->order }}</span>
                        </td>
                        <td class="py-4 px-4">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.adsense-units.show', $unit->id) }}" 
                                   class="px-3 py-2 bg-blue-500/20 hover:bg-blue-500/30 text-blue-400 rounded-lg transition"
                                   title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.adsense-units.edit', $unit->id) }}" 
                                   class="px-3 py-2 bg-yellow-500/20 hover:bg-yellow-500/30 text-yellow-400 rounded-lg transition"
                                   title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.adsense-units.destroy', $unit->id) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette unité ?');">
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
    </div>
    @else
    <div class="content-section text-center py-12">
        <i class="fas fa-ad text-6xl text-gray-400 mb-4"></i>
        <h4 class="text-xl font-semibold text-gray-300 mb-2">Aucune unité publicitaire</h4>
        <p class="text-gray-400 mb-6">Créez votre première unité publicitaire AdSense pour commencer à monétiser votre site.</p>
        <a href="{{ route('admin.adsense-units.create') }}" class="btn-primary inline-block">
            <i class="fas fa-plus mr-2"></i>Créer une unité
        </a>
    </div>
    @endif
</div>
@endsection

