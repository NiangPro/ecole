@extends('admin.layout')

@php
use Illuminate\Support\Str;
@endphp

@section('title', 'Packs de Documents')

@section('styles')
<style>
    body.light-mode h3 { color: #1e293b; }
    body.light-mode p { color: #64748b; }
    body.light-mode .content-section { background: #ffffff; border-color: rgba(6, 182, 212, 0.2); }
    body.light-mode .bg-gray-800\/50 { background: #f8f9fa; border-color: rgba(6, 182, 212, 0.3); }
    body.light-mode .bg-gray-800\/50:hover { border-color: rgba(6, 182, 212, 0.5); }
    body.light-mode .bg-gray-800\/50 h4 { color: #1e293b; }
    body.light-mode .bg-gray-800\/50 p { color: #64748b; }
    body.light-mode .text-gray-400 { color: #64748b; }
</style>
@endsection

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h3 class="text-3xl font-bold mb-2">Packs de Documents</h3>
        <p class="text-gray-400">Créez des offres groupées à prix réduit</p>
    </div>
    <a href="{{ route('admin.documents.bundles.create') }}" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-black font-semibold rounded-lg transition">
        <i class="fas fa-plus mr-2"></i>Nouveau pack
    </a>
</div>

@if(session('success'))
    <div class="mb-4 p-4 bg-green-500/20 border border-green-500/50 rounded-lg text-green-400 flex items-center gap-2">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
@endif

<div class="content-section">
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($bundles as $bundle)
        <div class="p-6 bg-gray-800/50 rounded-lg border border-cyan-500/20 hover:border-cyan-500/40 transition">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h4 class="font-bold text-lg">{{ $bundle->name }}</h4>
                    <p class="text-sm text-gray-400">{{ $bundle->items->count() }} document{{ $bundle->items->count() > 1 ? 's' : '' }}</p>
                </div>
                <div class="flex flex-col gap-1 items-end">
                    <span class="px-2 py-1 rounded text-xs {{ $bundle->is_active ? 'bg-green-500/20 text-green-400' : 'bg-gray-500/20 text-gray-400' }}">
                        {{ $bundle->is_active ? 'Actif' : 'Inactif' }}
                    </span>
                    @if($bundle->is_featured)
                    <span class="px-2 py-1 rounded text-xs bg-yellow-500/20 text-yellow-400">
                        <i class="fas fa-star mr-1"></i>Vedette
                    </span>
                    @endif
                </div>
            </div>

            @if($bundle->description)
                <p class="text-gray-400 text-sm mb-4">{{ Str::limit($bundle->description, 100) }}</p>
            @endif

            <div class="flex items-center justify-between text-sm mb-4">
                <span class="font-bold text-cyan-400">
                    {{ number_format($bundle->current_price, 0, ',', ' ') }} FCFA
                    @if($bundle->hasDiscount())
                        <span class="text-gray-400 line-through font-normal">{{ number_format($bundle->price, 0, ',', ' ') }}</span>
                    @endif
                </span>
                <span class="text-gray-400"><i class="fas fa-shopping-cart mr-1"></i>{{ $bundle->sales_count }} vente{{ $bundle->sales_count > 1 ? 's' : '' }}</span>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.documents.bundles.show', $bundle->id) }}"
                   class="flex-1 px-3 py-2 bg-cyan-500/20 hover:bg-cyan-500/30 text-cyan-400 rounded transition text-center text-sm">
                    <i class="fas fa-eye mr-1"></i>Voir
                </a>
                <a href="{{ route('admin.documents.bundles.edit', $bundle->id) }}"
                   class="flex-1 px-3 py-2 bg-yellow-500/20 hover:bg-yellow-500/30 text-yellow-400 rounded transition text-center text-sm">
                    <i class="fas fa-edit mr-1"></i>Modifier
                </a>
                <form action="{{ route('admin.documents.bundles.destroy', $bundle->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce pack ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-3 py-2 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded transition text-sm">
                        <i class="fas fa-trash mr-1"></i>Supprimer
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-full p-8 text-center text-gray-400">
            <i class="fas fa-box text-4xl mb-4 block"></i>
            <p>Aucun pack créé pour le moment</p>
        </div>
        @endforelse
    </div>

    @if($bundles->hasPages())
        <div class="mt-6">
            {{ $bundles->links() }}
        </div>
    @endif
</div>
@endsection
