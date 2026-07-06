@extends('admin.layout')

@section('title', $bundle->name)

@section('styles')
<style>
    body.light-mode h3 { color: #1e293b; }
    body.light-mode p { color: #64748b; }
    body.light-mode .content-section { background: #ffffff; border-color: rgba(6, 182, 212, 0.2); }
    body.light-mode .bg-gray-800\/50 { background: #f8f9fa; border-color: rgba(6, 182, 212, 0.3); }
</style>
@endsection

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h3 class="text-3xl font-bold mb-2">{{ $bundle->name }}</h3>
        <p class="text-gray-400">{{ $bundle->items->count() }} document{{ $bundle->items->count() > 1 ? 's' : '' }} inclus</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.documents.bundles.edit', $bundle->id) }}" class="px-4 py-2 bg-yellow-500/20 hover:bg-yellow-500/30 text-yellow-400 font-semibold rounded-lg transition">
            <i class="fas fa-edit mr-2"></i>Modifier
        </a>
        <a href="{{ route('admin.documents.bundles.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition">
            <i class="fas fa-arrow-left mr-2"></i>Retour
        </a>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="content-section lg:col-span-2">
        <h4 class="text-xl font-bold mb-4">Contenu du pack</h4>
        <div class="space-y-3">
            @foreach($bundle->items as $item)
            @php $itemable = $item->itemable; @endphp
            <div class="p-4 bg-gray-800/50 rounded-lg flex items-center justify-between">
                <span>
                    @if($item->item_type === \App\Models\Epreuve::class)
                        <i class="fas fa-graduation-cap text-purple-400 mr-2" title="Épreuve"></i>
                    @else
                        <i class="fas fa-file text-cyan-400 mr-2" title="Document"></i>
                    @endif
                    {{ $itemable->title ?? 'Contenu supprimé' }}
                </span>
                @if($itemable)
                <span class="text-cyan-400 font-semibold">
                    {{ number_format($item->item_type === \App\Models\Document::class ? $itemable->current_price : $itemable->price, 0, ',', ' ') }} FCFA
                </span>
                @endif
            </div>
            @endforeach
        </div>
        @if($bundle->description)
        <h4 class="text-xl font-bold mt-6 mb-2">Description</h4>
        <p class="text-gray-400">{{ $bundle->description }}</p>
        @endif
    </div>

    <div class="content-section">
        <h4 class="text-xl font-bold mb-4">Résumé</h4>
        <div class="space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-400">Prix normal</span>
                <span>{{ number_format($bundle->price, 0, ',', ' ') }} FCFA</span>
            </div>
            @if($bundle->hasDiscount())
            <div class="flex justify-between">
                <span class="text-gray-400">Prix réduit</span>
                <span class="text-cyan-400 font-semibold">{{ number_format($bundle->discount_price, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Économie</span>
                <span class="text-green-400 font-semibold">{{ number_format($bundle->savings, 0, ',', ' ') }} FCFA</span>
            </div>
            @endif
            <div class="flex justify-between">
                <span class="text-gray-400">Ventes</span>
                <span>{{ $bundle->sales_count }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Statut</span>
                <span class="{{ $bundle->is_active ? 'text-green-400' : 'text-gray-400' }}">{{ $bundle->is_active ? 'Actif' : 'Inactif' }}</span>
            </div>
            @if($bundle->is_featured)
            <div class="flex justify-between">
                <span class="text-gray-400">Vedette</span>
                <span class="text-yellow-400"><i class="fas fa-star"></i></span>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
