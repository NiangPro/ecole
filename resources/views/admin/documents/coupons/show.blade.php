@extends('admin.layout')

@section('title', $coupon->code)

@section('styles')
<style>
    body.light-mode h3 { color: #1e293b; }
    body.light-mode p { color: #64748b; }
    body.light-mode .content-section { background: #ffffff; border-color: rgba(6, 182, 212, 0.2); }
</style>
@endsection

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h3 class="text-3xl font-bold mb-2" style="font-family: monospace;">{{ $coupon->code }}</h3>
        <p class="text-gray-400">{{ $coupon->name ?? 'Code promo' }}</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.documents.coupons.edit', $coupon->id) }}" class="px-4 py-2 bg-yellow-500/20 hover:bg-yellow-500/30 text-yellow-400 font-semibold rounded-lg transition">
            <i class="fas fa-edit mr-2"></i>Modifier
        </a>
        <a href="{{ route('admin.documents.coupons.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition">
            <i class="fas fa-arrow-left mr-2"></i>Retour
        </a>
    </div>
</div>

<div class="content-section">
    <div class="grid md:grid-cols-2 gap-6 text-sm">
        <div class="flex justify-between border-b border-cyan-500/10 pb-3">
            <span class="text-gray-400">Réduction</span>
            <span class="font-semibold">{{ $coupon->type === 'percentage' ? $coupon->value . '%' : number_format($coupon->value, 0, ',', ' ') . ' FCFA' }}</span>
        </div>
        <div class="flex justify-between border-b border-cyan-500/10 pb-3">
            <span class="text-gray-400">Montant minimum</span>
            <span class="font-semibold">{{ $coupon->minimum_amount ? number_format($coupon->minimum_amount, 0, ',', ' ') . ' FCFA' : 'Aucun' }}</span>
        </div>
        <div class="flex justify-between border-b border-cyan-500/10 pb-3">
            <span class="text-gray-400">Document restreint</span>
            <span class="font-semibold">{{ $coupon->document->title ?? 'Tous les documents' }}</span>
        </div>
        <div class="flex justify-between border-b border-cyan-500/10 pb-3">
            <span class="text-gray-400">Catégorie restreinte</span>
            <span class="font-semibold">{{ $coupon->category->name ?? 'Toutes les catégories' }}</span>
        </div>
        <div class="flex justify-between border-b border-cyan-500/10 pb-3">
            <span class="text-gray-400">Utilisation</span>
            <span class="font-semibold">{{ $coupon->usage_count }}{{ $coupon->usage_limit ? ' / ' . $coupon->usage_limit : ' (illimitée)' }}</span>
        </div>
        <div class="flex justify-between border-b border-cyan-500/10 pb-3">
            <span class="text-gray-400">Limite par utilisateur</span>
            <span class="font-semibold">{{ $coupon->user_limit ?? 1 }}</span>
        </div>
        <div class="flex justify-between border-b border-cyan-500/10 pb-3">
            <span class="text-gray-400">Validité</span>
            <span class="font-semibold">
                @if($coupon->starts_at || $coupon->expires_at)
                    @if($coupon->starts_at){{ \Carbon\Carbon::parse($coupon->starts_at)->format('d/m/Y') }}@endif
                    @if($coupon->expires_at) → {{ \Carbon\Carbon::parse($coupon->expires_at)->format('d/m/Y') }}@endif
                @else
                    Illimitée
                @endif
            </span>
        </div>
        <div class="flex justify-between border-b border-cyan-500/10 pb-3">
            <span class="text-gray-400">Statut</span>
            <span class="{{ $coupon->is_active ? 'text-green-400' : 'text-gray-400' }} font-semibold">{{ $coupon->is_active ? 'Actif' : 'Inactif' }}</span>
        </div>
    </div>
    @if($coupon->description)
    <h4 class="text-xl font-bold mt-6 mb-2">Description</h4>
    <p class="text-gray-400">{{ $coupon->description }}</p>
    @endif
</div>
@endsection
