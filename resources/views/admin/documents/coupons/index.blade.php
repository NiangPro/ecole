@extends('admin.layout')

@section('title', 'Codes Promo')

@section('styles')
<style>
    body.light-mode h3 { color: #1e293b; }
    body.light-mode p { color: #64748b; }
    body.light-mode .content-section { background: #ffffff; border-color: rgba(6, 182, 212, 0.2); }
    body.light-mode .text-gray-400 { color: #64748b; }

    .coupons-table-wrapper { overflow-x: auto; border-radius: 12px; }
    .coupons-table { width: 100%; min-width: 900px; }
    .coupons-table thead { background: rgba(6, 182, 212, 0.05); }
    .coupons-table th {
        padding: 1rem; text-align: left; color: #06b6d4; font-weight: 700;
        font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px;
        border-bottom: 2px solid rgba(6, 182, 212, 0.2); white-space: nowrap;
    }
    .coupons-table td { padding: 1rem; border-bottom: 1px solid rgba(6, 182, 212, 0.1); }
    .coupons-table tbody tr:hover { background: rgba(6, 182, 212, 0.05); }
    body.light-mode .coupons-table td { color: rgba(30, 41, 59, 0.8); }

    .status-badge {
        display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.375rem 0.75rem;
        border-radius: 8px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;
    }
    .status-badge.active { background: rgba(34, 197, 94, 0.2); color: #22c55e; }
    .status-badge.inactive { background: rgba(100, 116, 139, 0.2); color: #64748b; }
    .action-btn {
        display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px;
        border-radius: 8px; transition: all 0.3s ease; text-decoration: none; border: none; cursor: pointer;
    }
    .action-btn.edit { background: rgba(245, 158, 11, 0.2); color: #f59e0b; }
    .action-btn.delete { background: rgba(239, 68, 68, 0.2); color: #ef4444; }
</style>
@endsection

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h3 class="text-3xl font-bold mb-2">Codes Promo</h3>
        <p class="text-gray-400">Gérez les coupons de réduction</p>
    </div>
    <a href="{{ route('admin.documents.coupons.create') }}" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-black font-semibold rounded-lg transition">
        <i class="fas fa-plus mr-2"></i>Nouveau code promo
    </a>
</div>

@if(session('success'))
    <div class="mb-4 p-4 bg-green-500/20 border border-green-500/50 rounded-lg text-green-400 flex items-center gap-2">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
@endif

<div class="content-section">
    <div class="coupons-table-wrapper">
        <table class="coupons-table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Réduction</th>
                    <th>Utilisation</th>
                    <th>Validité</th>
                    <th>Statut</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                <tr>
                    <td>
                        <div class="font-bold" style="font-family: monospace;">{{ $coupon->code }}</div>
                        @if($coupon->name)<div class="text-sm text-gray-400">{{ $coupon->name }}</div>@endif
                    </td>
                    <td>
                        {{ $coupon->type === 'percentage' ? $coupon->value . '%' : number_format($coupon->value, 0, ',', ' ') . ' FCFA' }}
                        @if($coupon->minimum_amount)
                        <div class="text-sm text-gray-400">Min : {{ number_format($coupon->minimum_amount, 0, ',', ' ') }} FCFA</div>
                        @endif
                    </td>
                    <td>
                        {{ $coupon->usage_count }}{{ $coupon->usage_limit ? ' / ' . $coupon->usage_limit : '' }}
                    </td>
                    <td class="text-sm">
                        @if($coupon->starts_at || $coupon->expires_at)
                            @if($coupon->starts_at){{ \Carbon\Carbon::parse($coupon->starts_at)->format('d/m/Y') }}@endif
                            @if($coupon->expires_at) → {{ \Carbon\Carbon::parse($coupon->expires_at)->format('d/m/Y') }}@endif
                        @else
                            <span class="text-gray-400">Illimitée</span>
                        @endif
                    </td>
                    <td>
                        <span class="status-badge {{ $coupon->is_active ? 'active' : 'inactive' }}">
                            {{ $coupon->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.documents.coupons.edit', $coupon->id) }}" class="action-btn edit" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.documents.coupons.destroy', $coupon->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce code promo ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-gray-400">
                        <i class="fas fa-tags text-4xl mb-4 block"></i>
                        <p>Aucun code promo créé</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($coupons->hasPages())
        <div class="mt-6">
            {{ $coupons->links() }}
        </div>
    @endif
</div>
@endsection
