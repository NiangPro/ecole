@extends('admin.layout')

@section('title', 'Achats Documents')

@section('styles')
<style>
    /* Modern Scrollbar */
    .purchases-table-wrapper {
        overflow-x: auto;
        position: relative;
        border-radius: 12px;
    }

    .purchases-table-wrapper::-webkit-scrollbar {
        height: 12px;
    }

    .purchases-table-wrapper::-webkit-scrollbar-track {
        background: rgba(6, 182, 212, 0.1);
        border-radius: 10px;
    }

    .purchases-table-wrapper::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border-radius: 10px;
        border: 2px solid rgba(6, 182, 212, 0.1);
        transition: background 0.3s ease;
    }

    .purchases-table-wrapper::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #14b8a6, #06b6d4);
    }

    /* Firefox Scrollbar */
    .purchases-table-wrapper {
        scrollbar-width: thin;
        scrollbar-color: #06b6d4 rgba(6, 182, 212, 0.1);
    }

    /* Table Styles */
    .purchases-table {
        width: 100%;
        min-width: 1000px;
    }

    .purchases-table thead {
        background: rgba(6, 182, 212, 0.05);
    }

    .purchases-table th {
        padding: 1rem;
        text-align: left;
        color: #06b6d4;
        font-weight: 700;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid rgba(6, 182, 212, 0.2);
        white-space: nowrap;
    }

    .purchases-table td {
        padding: 1rem;
        border-bottom: 1px solid rgba(6, 182, 212, 0.1);
        color: rgba(255, 255, 255, 0.8);
    }

    .purchases-table tbody tr {
        transition: all 0.3s ease;
    }

    .purchases-table tbody tr:hover {
        background: rgba(6, 182, 212, 0.05);
        transform: translateX(4px);
    }

    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.75rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-badge.completed {
        background: rgba(34, 197, 94, 0.2);
        color: #22c55e;
        border: 1px solid rgba(34, 197, 94, 0.3);
    }

    .status-badge.pending {
        background: rgba(245, 158, 11, 0.2);
        color: #f59e0b;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    .status-badge.cancelled {
        background: rgba(107, 114, 128, 0.2);
        color: #9ca3af;
        border: 1px solid rgba(107, 114, 128, 0.3);
    }

    .status-badge.failed {
        background: rgba(239, 68, 68, 0.2);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    /* Action Buttons */
    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        transition: all 0.3s ease;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .action-btn.view {
        background: rgba(59, 130, 246, 0.2);
        color: #3b82f6;
    }

    .action-btn.view:hover {
        background: rgba(59, 130, 246, 0.3);
    }

    .action-btn.approve {
        background: rgba(34, 197, 94, 0.2);
        color: #22c55e;
    }

    .action-btn.approve:hover {
        background: rgba(34, 197, 94, 0.3);
    }

    .action-btn.cancel {
        background: rgba(239, 68, 68, 0.2);
        color: #ef4444;
    }

    .action-btn.cancel:hover {
        background: rgba(239, 68, 68, 0.3);
    }

    /* Download Count */
    .download-count {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.5rem 0.875rem;
        background: rgba(6, 182, 212, 0.1);
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.875rem;
        border: 1px solid rgba(6, 182, 212, 0.2);
        transition: all 0.3s ease;
    }

    .download-count:hover {
        background: rgba(6, 182, 212, 0.15);
        border-color: rgba(6, 182, 212, 0.4);
        transform: translateY(-1px);
    }

    .download-count i {
        color: #06b6d4;
        font-size: 0.875rem;
    }

    .download-number {
        color: var(--text-primary);
        font-weight: 800;
    }

    .download-separator {
        color: rgba(255, 255, 255, 0.5);
        margin: 0 0.125rem;
    }

    .download-limit {
        color: #06b6d4;
        font-weight: 700;
    }

    .download-limit-reached {
        display: inline-flex;
        align-items: center;
        margin-left: 0.5rem;
        color: #f59e0b;
        font-size: 0.875rem;
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.6; }
    }
    
    /* Dark Mode Styles */
    body.light-mode h3 {
        color: #1e293b;
    }
    
    body.light-mode p {
        color: #64748b;
    }
    
    body.light-mode .content-section {
        background: #ffffff;
        border-color: rgba(6, 182, 212, 0.2);
    }
    
    body.light-mode .input-admin {
        background: #f8f9fa;
        border-color: rgba(6, 182, 212, 0.3);
        color: #1e293b;
    }
    
    body.light-mode .input-admin:focus {
        background: #ffffff;
        border-color: #06b6d4;
    }
    
    body.light-mode .purchases-table th {
        color: #06b6d4;
    }
    
    body.light-mode .purchases-table td {
        color: #1e293b;
    }
    
    body.light-mode .purchases-table tbody tr:hover {
        background: rgba(6, 182, 212, 0.05);
    }
    
    body.light-mode .text-gray-400 {
        color: #64748b;
    }
    
    body.light-mode .text-gray-300 {
        color: #475569;
    }
    
    body.light-mode .download-number {
        color: #1e293b;
    }
</style>
@endsection

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h3 class="text-3xl font-bold mb-2">Achats Documents</h3>
        <p class="text-gray-400">Gérez les achats de documents</p>
    </div>
</div>

@if(session('success'))
    <div class="mb-4 p-4 bg-green-500/20 border border-green-500/50 rounded-lg text-green-400 flex items-center gap-2">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 p-4 bg-red-500/20 border border-red-500/50 rounded-lg text-red-400 flex items-center gap-2">
        <i class="fas fa-exclamation-circle"></i>
        {{ session('error') }}
    </div>
@endif

<div class="content-section mb-6">
    <form method="GET" action="{{ route('admin.documents.purchases.index') }}" class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
            <label class="block text-gray-300 mb-2 text-sm font-semibold">Statut</label>
            <select name="status" class="input-admin">
                <option value="">Tous les statuts</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Complété</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Échoué</option>
            </select>
        </div>
        <div>
            <label class="block text-gray-300 mb-2 text-sm font-semibold">Date de</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="input-admin">
        </div>
        <div>
            <label class="block text-gray-300 mb-2 text-sm font-semibold">Date à</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="input-admin">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-black font-semibold rounded-lg transition flex-1">
                <i class="fas fa-search mr-2"></i>Filtrer
            </button>
            <a href="{{ route('admin.documents.purchases.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition">
                <i class="fas fa-redo"></i>
            </a>
        </div>
    </form>
</div>

<div class="content-section">
    <div class="purchases-table-wrapper">
        <table class="purchases-table">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Document</th>
                    <th>Montant</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th>Téléchargements</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($purchases as $purchase)
                <tr>
                    <td>
                        <div class="font-semibold">{{ $purchase->customer_name ?? ($purchase->user ? $purchase->user->name : 'N/A') }}</div>
                        <div class="text-sm text-gray-400">{{ $purchase->customer_email ?? ($purchase->user ? $purchase->user->email : 'N/A') }}</div>
                    </td>
                    <td>
                        <div class="font-semibold">{{ $purchase->document->title }}</div>
                    </td>
                    <td>
                        <span class="font-semibold">{{ number_format($purchase->amount_paid, 0, ',', ' ') }} {{ $purchase->currency }}</span>
                    </td>
                    <td>
                        <span class="status-badge 
                            @if($purchase->status === 'completed') completed
                            @elseif($purchase->status === 'pending') pending
                            @elseif($purchase->status === 'cancelled') cancelled
                            @else failed
                            @endif">
                            <i class="fas 
                                @if($purchase->status === 'completed') fa-check-circle
                                @elseif($purchase->status === 'pending') fa-clock
                                @elseif($purchase->status === 'cancelled') fa-times-circle
                                @else fa-exclamation-circle
                                @endif"></i>
                            @if($purchase->status === 'completed') Complété
                            @elseif($purchase->status === 'pending') En attente
                            @elseif($purchase->status === 'cancelled') Annulé
                            @else Échoué
                            @endif
                        </span>
                    </td>
                    <td>
                        <div class="text-sm text-gray-300">{{ $purchase->created_at->format('d/m/Y H:i') }}</div>
                        @if($purchase->purchased_at)
                            <div class="text-xs text-gray-400">Acheté: {{ $purchase->purchased_at->format('d/m/Y H:i') }}</div>
                        @endif
                    </td>
                    <td>
                        <span class="download-count" title="Téléchargements: {{ $purchase->download_count }} sur {{ $purchase->download_limit }}">
                            <i class="fas fa-download"></i>
                            <span class="download-number">{{ $purchase->download_count }}</span>
                            <span class="download-separator">/</span>
                            <span class="download-limit">{{ $purchase->download_limit }}</span>
                        </span>
                        @if($purchase->download_count >= $purchase->download_limit)
                            <span class="download-limit-reached" title="Limite de téléchargements atteinte">
                                <i class="fas fa-exclamation-triangle"></i>
                            </span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.documents.purchases.show', $purchase->id) }}" 
                               class="action-btn view" 
                               title="Voir">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($purchase->status === 'pending')
                                <form action="{{ route('admin.documents.purchases.approve', $purchase->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="action-btn approve" title="Approuver">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.documents.purchases.cancel', $purchase->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="action-btn cancel" title="Annuler">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-8 text-center text-gray-400">
                        <i class="fas fa-shopping-cart text-4xl mb-4 block"></i>
                        <p>Aucun achat trouvé</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($purchases->hasPages())
        <div class="mt-6">
            {{ $purchases->links() }}
        </div>
    @endif
</div>
@endsection


