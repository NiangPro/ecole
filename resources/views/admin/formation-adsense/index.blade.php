@extends('admin.layout')

@section('title', 'Annonces AdSense par Formation')

@section('styles')
<style>
    .formation-adsense-page h3 {
        color: #fff;
        transition: color 0.3s ease;
    }
    
    body.light-mode .formation-adsense-page h3 {
        color: #1e293b;
    }
    
    .formation-adsense-page .text-gray-300 {
        color: rgba(209, 213, 219, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .formation-adsense-page .text-gray-300 {
        color: rgba(30, 41, 59, 0.8);
    }
    
    .formation-adsense-page .text-gray-400 {
        color: rgba(156, 163, 175, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .formation-adsense-page .text-gray-400 {
        color: rgba(100, 116, 139, 1);
    }
</style>
@endsection

@section('content')
<div class="formation-adsense-page">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <h3 class="text-3xl font-bold">Annonces AdSense par Formation</h3>
        <a href="{{ route('admin.adsense-units.index') }}" class="px-6 py-3 bg-cyan-600 hover:bg-cyan-700 rounded-lg font-semibold transition">
            <i class="fas fa-ad mr-2"></i>Gérer les Unités
        </a>
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

    <div class="content-section">
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($formations as $slug => $name)
            <div class="bg-black/30 rounded-lg p-6 hover:bg-black/40 transition {{ $slug === 'all' ? 'border-2 border-cyan-500/50' : '' }}">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-xl font-semibold text-gray-300">
                        {{ $name }}
                        @if($slug === 'all')
                        <span class="ml-2 px-2 py-1 bg-cyan-500/20 text-cyan-400 rounded text-xs font-semibold">
                            <i class="fas fa-globe mr-1"></i>Global
                        </span>
                        @endif
                    </h4>
                    <span class="px-3 py-1 bg-cyan-500/20 text-cyan-400 rounded-full text-xs font-semibold">
                        {{ $formationAds[$slug]['ads']->count() }} annonce(s)
                    </span>
                </div>
                
                @if($formationAds[$slug]['ads']->count() > 0)
                <div class="mb-4">
                    <p class="text-sm text-gray-400 mb-2">Positions configurées :</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($formationAds[$slug]['ads']->groupBy('position') as $position => $ads)
                        <span class="px-2 py-1 bg-green-500/20 text-green-400 rounded text-xs">
                            {{ ucfirst($position) }} ({{ $ads->count() }})
                        </span>
                        @endforeach
                    </div>
                </div>
                @else
                <p class="text-sm text-gray-400 mb-4">Aucune annonce configurée</p>
                @endif
                
                <a href="{{ route('admin.formation-adsense.show', $slug) }}" 
                   class="block w-full text-center px-4 py-2 bg-cyan-600 hover:bg-cyan-700 rounded-lg font-semibold transition">
                    <i class="fas fa-cog mr-2"></i>Configurer
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

