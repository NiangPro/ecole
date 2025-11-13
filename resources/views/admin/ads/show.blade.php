@extends('admin.layout')

@section('title', 'Détails Publicité')

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h3 class="text-3xl font-bold mb-2">Détails de la Publicité</h3>
        <p class="text-gray-400">{{ $ad->name }}</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.ads.edit', $ad->id) }}" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-black font-semibold rounded-lg transition">
            <i class="fas fa-edit mr-2"></i>Modifier
        </a>
        <a href="{{ route('admin.ads.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition">
            <i class="fas fa-arrow-left mr-2"></i>Retour
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Informations principales -->
    <div class="lg:col-span-2 space-y-6">
        <div class="content-section">
            <h4 class="text-cyan-400 font-bold mb-4 border-b border-cyan-500/20 pb-2">Informations Générales</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-300">
                <div><span class="font-semibold text-cyan-400">Nom:</span> {{ $ad->name }}</div>
                <div><span class="font-semibold text-cyan-400">Position:</span> 
                    <span class="px-2 py-1 bg-cyan-500/20 text-cyan-400 rounded text-sm">
                        {{ ucfirst($ad->position) }}
                    </span>
                </div>
                <div><span class="font-semibold text-cyan-400">Statut:</span> 
                    <span class="px-2 py-1 rounded text-sm
                        {{ $ad->status === 'active' ? 'bg-green-500/20 text-green-400' : 'bg-gray-500/20 text-gray-400' }}">
                        {{ $ad->status === 'active' ? 'Actif' : 'Inactif' }}
                    </span>
                </div>
                <div><span class="font-semibold text-cyan-400">Ordre:</span> {{ $ad->order }}</div>
                @if($ad->start_date)
                <div><span class="font-semibold text-cyan-400">Date début:</span> {{ $ad->start_date->format('d/m/Y') }}</div>
                @endif
                @if($ad->end_date)
                <div><span class="font-semibold text-cyan-400">Date fin:</span> {{ $ad->end_date->format('d/m/Y') }}</div>
                @endif
            </div>
        </div>

        @if($ad->description)
        <div class="content-section">
            <h4 class="text-cyan-400 font-bold mb-4 border-b border-cyan-500/20 pb-2">Description</h4>
            <p class="text-gray-300">{{ $ad->description }}</p>
        </div>
        @endif

        <div class="content-section">
            <h4 class="text-cyan-400 font-bold mb-4 border-b border-cyan-500/20 pb-2">Code de la Publicité</h4>
            <div class="bg-gray-900 p-4 rounded-lg overflow-x-auto">
                <pre class="text-sm text-gray-300 font-mono">{{ htmlspecialchars($ad->ad_code) }}</pre>
            </div>
        </div>

        <div class="content-section">
            <h4 class="text-cyan-400 font-bold mb-4 border-b border-cyan-500/20 pb-2">Aperçu</h4>
            <div class="bg-gray-900 p-6 rounded-lg border border-cyan-500/20">
                {!! $ad->ad_code !!}
            </div>
        </div>
    </div>

    <!-- Sidebar Statistiques -->
    <div class="space-y-6">
        <div class="content-section">
            <h4 class="text-cyan-400 font-bold mb-4 border-b border-cyan-500/20 pb-2">Statistiques</h4>
            <div class="space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-cyan-400 font-semibold">Impressions</span>
                        <span class="text-2xl font-bold text-cyan-400">{{ number_format($ad->impressions) }}</span>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-cyan-400 font-semibold">Clics</span>
                        <span class="text-2xl font-bold text-cyan-400">{{ number_format($ad->clicks) }}</span>
                    </div>
                </div>
                @if($ad->impressions > 0)
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-cyan-400 font-semibold">CTR (Taux de clic)</span>
                        <span class="text-2xl font-bold text-cyan-400">{{ number_format(($ad->clicks / $ad->impressions) * 100, 2) }}%</span>
                    </div>
                    <div class="w-full h-3 bg-gray-700 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-red-500 via-yellow-500 to-green-500 rounded-full" style="width: {{ min(($ad->clicks / $ad->impressions) * 100, 100) }}%"></div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="content-section">
            <h4 class="text-cyan-400 font-bold mb-4 border-b border-cyan-500/20 pb-2">Dates</h4>
            <div class="space-y-3 text-sm text-gray-300">
                <div><span class="font-semibold text-cyan-400">Créé le:</span> {{ $ad->created_at->format('d/m/Y H:i') }}</div>
                <div><span class="font-semibold text-cyan-400">Modifié le:</span> {{ $ad->updated_at->format('d/m/Y H:i') }}</div>
            </div>
        </div>
    </div>
</div>
@endsection

