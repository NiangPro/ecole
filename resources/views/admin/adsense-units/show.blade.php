@extends('admin.layout')

@section('title', 'Détails de l\'Unité AdSense')

@section('content')
<div class="adsense-units-page">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.adsense-units.index') }}" class="text-gray-400 hover:text-gray-300">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h3 class="text-3xl font-bold">Détails de l'Unité AdSense</h3>
    </div>

    <div class="content-section">
        <div class="grid md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-gray-400 mb-2 text-sm font-semibold">Nom</label>
                <p class="text-gray-300 text-lg font-semibold">{{ $unit->name }}</p>
            </div>

            <div>
                <label class="block text-gray-400 mb-2 text-sm font-semibold">Slot ID</label>
                <code class="text-cyan-400 text-lg">{{ $unit->ad_slot }}</code>
            </div>
        </div>

        @if($unit->description)
        <div class="mb-6">
            <label class="block text-gray-400 mb-2 text-sm font-semibold">Description</label>
            <p class="text-gray-300">{{ $unit->description }}</p>
        </div>
        @endif

        <div class="grid md:grid-cols-3 gap-6 mb-6">
            <div>
                <label class="block text-gray-400 mb-2 text-sm font-semibold">Position</label>
                <span class="px-3 py-1 bg-cyan-500/20 text-cyan-400 rounded-full text-sm font-semibold">
                    {{ ucfirst($unit->position) }}
                </span>
            </div>

            <div>
                <label class="block text-gray-400 mb-2 text-sm font-semibold">Format</label>
                <span class="text-gray-300">{{ ucfirst($unit->ad_format) }}</span>
            </div>

            <div>
                <label class="block text-gray-400 mb-2 text-sm font-semibold">Statut</label>
                @if($unit->status === 'active')
                <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-sm font-semibold">
                    <i class="fas fa-check-circle mr-1"></i>Actif
                </span>
                @else
                <span class="px-3 py-1 bg-gray-500/20 text-gray-400 rounded-full text-sm font-semibold">
                    <i class="fas fa-pause-circle mr-1"></i>Inactif
                </span>
                @endif
            </div>
        </div>

        <div class="grid md:grid-cols-3 gap-6 mb-6">
            @if($unit->location)
            <div>
                <label class="block text-gray-400 mb-2 text-sm font-semibold">Location</label>
                <span class="text-gray-300">{{ $unit->location }}</span>
            </div>
            @endif

            @if($unit->size)
            <div>
                <label class="block text-gray-400 mb-2 text-sm font-semibold">Taille</label>
                <span class="text-gray-300">{{ $unit->size }}</span>
            </div>
            @endif

            <div>
                <label class="block text-gray-400 mb-2 text-sm font-semibold">Ordre</label>
                <span class="text-gray-300">{{ $unit->order }}</span>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-gray-400 mb-2 text-sm font-semibold">Responsive</label>
            <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $unit->responsive ? 'bg-green-500/20 text-green-400' : 'bg-gray-500/20 text-gray-400' }}">
                {{ $unit->responsive ? 'Oui' : 'Non' }}
            </span>
        </div>

        @if($unit->custom_code)
        <div class="mb-6">
            <label class="block text-gray-400 mb-2 text-sm font-semibold">Code personnalisé</label>
            <pre class="bg-black/30 p-4 rounded-lg text-sm text-gray-300 overflow-x-auto"><code>{{ $unit->custom_code }}</code></pre>
        </div>
        @endif

        <div class="flex gap-4 mt-8">
            <a href="{{ route('admin.adsense-units.edit', $unit->id) }}" class="btn-primary">
                <i class="fas fa-edit mr-2"></i>Modifier
            </a>
            <a href="{{ route('admin.adsense-units.index') }}" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 rounded-lg font-semibold transition">
                <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
            </a>
        </div>
    </div>
</div>
@endsection

