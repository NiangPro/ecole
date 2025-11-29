@extends('admin.layout')

@section('title', 'Gestion Newsletter - Admin')

@section('styles')
<style>
    /* Scrollbar personnalisée */
    body {
        scrollbar-width: thin;
        scrollbar-color: rgba(6, 182, 212, 0.5) rgba(15, 23, 42, 0.3);
    }
    
    body::-webkit-scrollbar {
        width: 12px;
    }
    
    body::-webkit-scrollbar-track {
        background: rgba(15, 23, 42, 0.3);
        border-radius: 10px;
    }
    
    body::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #06b6d4, #14b8a6);
        border-radius: 10px;
        border: 2px solid rgba(15, 23, 42, 0.3);
        transition: all 0.3s ease;
    }
    
    body::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #14b8a6, #06b6d4);
        box-shadow: 0 0 10px rgba(6, 182, 212, 0.5);
    }
    
    .content-section {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 16px;
        padding: 30px;
        margin-bottom: 25px;
    }
    
    .input-admin {
        width: 100%;
        padding: 12px 16px;
        background: rgba(15, 23, 42, 0.8);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 10px;
        color: #fff;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }
    
    .input-admin:focus {
        outline: none;
        border-color: #06b6d4;
        background: rgba(15, 23, 42, 0.95);
        box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.1);
    }
    
    .stat-card {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.1));
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 16px;
        padding: 25px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(6, 182, 212, 0.1) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }
    
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    .stat-card:hover {
        border-color: rgba(6, 182, 212, 0.5);
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(6, 182, 212, 0.25);
    }
    
    .stat-card-content {
        position: relative;
        z-index: 1;
    }
    
    .checkbox-admin {
        width: 20px;
        height: 20px;
        cursor: pointer;
        accent-color: #06b6d4;
    }
    
    .table-row-hover:hover {
        background: rgba(6, 182, 212, 0.05);
    }
    
    body.light-mode::-webkit-scrollbar-track {
        background: rgba(241, 245, 249, 0.5);
    }
    
    body.light-mode .content-section {
        background: rgba(255, 255, 255, 0.8);
        border-color: rgba(6, 182, 212, 0.3);
    }
    
    body.light-mode .input-admin {
        background: rgba(255, 255, 255, 0.9);
        border-color: rgba(6, 182, 212, 0.3);
        color: #1e293b;
    }
    
    body.light-mode .input-admin:focus {
        background: rgba(255, 255, 255, 1);
        box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.15);
    }
    
    body.light-mode .input-admin option {
        background: #ffffff;
        color: #1e293b;
    }
    
    body.light-mode .stat-card {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.1));
        border-color: rgba(6, 182, 212, 0.4);
    }
    
    body.light-mode .stat-card:hover {
        border-color: rgba(6, 182, 212, 0.6);
        box-shadow: 0 15px 40px rgba(6, 182, 212, 0.15);
    }
    
    .newsletter-page h3 {
        color: #fff;
        transition: color 0.3s ease;
    }
    
    body.light-mode .newsletter-page h3 {
        color: #1e293b;
    }
    
    .newsletter-page h4 {
        color: #fff;
        transition: color 0.3s ease;
    }
    
    body.light-mode .newsletter-page h4 {
        color: #1e293b;
    }
    
    .newsletter-page .text-gray-400 {
        color: rgba(156, 163, 175, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .newsletter-page .text-gray-400 {
        color: rgba(100, 116, 139, 1);
    }
    
    .newsletter-page .text-gray-300 {
        color: rgba(209, 213, 219, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .newsletter-page .text-gray-300 {
        color: rgba(30, 41, 59, 0.8);
    }
    
    .newsletter-page .text-white {
        color: #fff;
        transition: color 0.3s ease;
    }
    
    body.light-mode .newsletter-page .text-white {
        color: #1e293b;
    }
    
    .newsletter-page .bg-gray-600 {
        background: rgba(75, 85, 99, 1);
        transition: background 0.3s ease;
    }
    
    body.light-mode .newsletter-page .bg-gray-600 {
        background: rgba(148, 163, 184, 1);
    }
    
    .newsletter-page .bg-green-500\/20 {
        background: rgba(34, 197, 94, 0.2);
        border-color: rgba(34, 197, 94, 0.5);
    }
    
    body.light-mode .newsletter-page .bg-green-500\/20 {
        background: rgba(34, 197, 94, 0.15);
        border-color: rgba(34, 197, 94, 0.4);
    }
    
    .newsletter-page .bg-red-500\/20 {
        background: rgba(239, 68, 68, 0.2);
        border-color: rgba(239, 68, 68, 0.5);
    }
    
    body.light-mode .newsletter-page .bg-red-500\/20 {
        background: rgba(239, 68, 68, 0.15);
        border-color: rgba(239, 68, 68, 0.4);
    }
    
    body.light-mode .table-row-hover:hover {
        background: rgba(6, 182, 212, 0.1);
    }
</style>
@endsection

@section('content')
<div class="newsletter-page">
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h3 class="text-3xl font-bold mb-2 flex items-center gap-3">
            <i class="fas fa-envelope-open-text text-cyan-400"></i>
            Gestion Newsletter
        </h3>
        <p class="text-gray-400">Gérez vos abonnés à la newsletter</p>
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

<!-- Statistiques -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="stat-card">
        <div class="stat-card-content">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-gray-400 text-sm mb-1">Total Abonnés</p>
                    <p class="text-3xl font-bold text-cyan-400">{{ $totalAll }}</p>
                </div>
                <div class="w-16 h-16 bg-cyan-500/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-3xl text-cyan-400"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="stat-card" style="background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(16, 185, 129, 0.1)); border-color: rgba(34, 197, 94, 0.3);">
        <div class="stat-card-content">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-gray-400 text-sm mb-1">Actifs</p>
                    <p class="text-3xl font-bold text-green-400">{{ $totalSubscribers }}</p>
                </div>
                <div class="w-16 h-16 bg-green-500/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-3xl text-green-400"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="stat-card" style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(245, 101, 101, 0.1)); border-color: rgba(239, 68, 68, 0.3);">
        <div class="stat-card-content">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-gray-400 text-sm mb-1">Inactifs</p>
                    <p class="text-3xl font-bold text-red-400">{{ $totalInactive }}</p>
                </div>
                <div class="w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-times-circle text-3xl text-red-400"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="stat-card" style="background: linear-gradient(135deg, rgba(168, 85, 247, 0.1), rgba(147, 51, 234, 0.1)); border-color: rgba(168, 85, 247, 0.3);">
        <div class="stat-card-content">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-gray-400 text-sm mb-1">Taux d'activation</p>
                    <p class="text-3xl font-bold text-purple-400">
                        {{ $totalAll > 0 ? round(($totalSubscribers / $totalAll) * 100, 1) : 0 }}%
                    </p>
                </div>
                <div class="w-16 h-16 bg-purple-500/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-line text-3xl text-purple-400"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtres et Actions -->
<div class="content-section mb-6">
    <h4 class="text-xl font-bold mb-4 flex items-center gap-2">
        <i class="fas fa-filter text-cyan-400"></i>
        Filtres et Recherche
    </h4>
    <form id="filter-form" method="GET" action="{{ route('admin.newsletter.index') }}" class="grid md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
        <div>
            <label class="block text-gray-300 mb-2 text-sm font-semibold">Recherche</label>
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Rechercher par email..." 
                   class="input-admin">
        </div>
        
        <div>
            <label class="block text-gray-300 mb-2 text-sm font-semibold">Statut</label>
            <select name="status" class="input-admin">
                <option value="">Tous les statuts</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actifs</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactifs</option>
            </select>
        </div>
        
        <div>
            <label class="block text-gray-300 mb-2 text-sm font-semibold">Trier par</label>
            <select name="sort_by" class="input-admin">
                <option value="created_at" {{ request('sort_by', 'created_at') == 'created_at' ? 'selected' : '' }}>Date d'inscription</option>
                <option value="email" {{ request('sort_by') == 'email' ? 'selected' : '' }}>Email</option>
                <option value="subscribed_at" {{ request('sort_by') == 'subscribed_at' ? 'selected' : '' }}>Date d'abonnement</option>
            </select>
        </div>
        
        <div>
            <label class="block text-gray-300 mb-2 text-sm font-semibold">Ordre</label>
            <select name="sort_order" class="input-admin">
                <option value="desc" {{ request('sort_order', 'desc') == 'desc' ? 'selected' : '' }}>Décroissant</option>
                <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Croissant</option>
            </select>
        </div>
        
        <div class="flex flex-wrap gap-3 md:col-span-2 lg:col-span-4">
            <button type="submit" class="px-6 py-2 bg-cyan-500 hover:bg-cyan-600 text-black font-semibold rounded-lg transition flex items-center gap-2">
                <i class="fas fa-search"></i>
                Filtrer
            </button>
            <a href="{{ route('admin.newsletter.index') }}" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition flex items-center gap-2">
                <i class="fas fa-redo"></i>
                Réinitialiser
            </a>
            <a href="{{ route('admin.newsletter.export', request()->query()) }}" class="px-6 py-2 bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 text-white font-semibold rounded-lg transition flex items-center gap-2">
                <i class="fas fa-download"></i>
                Exporter CSV
            </a>
        </div>
    </form>
</div>

<!-- Actions en masse -->
<form id="bulk-action-form" method="POST" action="{{ route('admin.newsletter.bulk-action') }}" class="mb-6">
    @csrf
    <div class="content-section">
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-xl font-bold flex items-center gap-2">
                <i class="fas fa-list text-cyan-400"></i>
                Liste des abonnés
            </h4>
            <div class="flex items-center gap-3">
                <select name="action" id="bulk-action-select" class="input-admin" style="width: auto; min-width: 150px;">
                    <option value="">Actions en masse</option>
                    <option value="activate">Activer</option>
                    <option value="deactivate">Désactiver</option>
                    <option value="delete">Supprimer</option>
                </select>
                <button type="button" onclick="applyBulkAction()" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-black font-semibold rounded-lg transition">
                    Appliquer
                </button>
            </div>
        </div>
        
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-cyan-500/20">
                        <th class="text-left p-4 text-cyan-400">
                            <input type="checkbox" id="select-all" class="checkbox-admin" onchange="toggleSelectAll()">
                        </th>
                        <th class="text-left p-4 text-cyan-400">Email</th>
                        <th class="text-left p-4 text-cyan-400">Date d'inscription</th>
                        <th class="text-left p-4 text-cyan-400">Date d'abonnement</th>
                        <th class="text-left p-4 text-cyan-400">Statut</th>
                        <th class="text-right p-4 text-cyan-400">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscribers as $subscriber)
                    <tr class="border-b border-cyan-500/10 table-row-hover transition">
                        <td class="p-4">
                            <input type="checkbox" name="ids[]" value="{{ $subscriber->id }}" class="checkbox-admin row-checkbox">
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-cyan-500/20 rounded-full flex items-center justify-center">
                                    <i class="fas fa-envelope text-cyan-400"></i>
                                </div>
                                <span class="text-white font-medium">{{ $subscriber->email }}</span>
                            </div>
                        </td>
                        <td class="p-4 text-gray-400">
                            {{ $subscriber->created_at->format('d/m/Y à H:i') }}
                        </td>
                        <td class="p-4 text-gray-400">
                            {{ $subscriber->subscribed_at ? $subscriber->subscribed_at->format('d/m/Y à H:i') : '-' }}
                        </td>
                        <td class="p-4">
                            @if($subscriber->is_active)
                                <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-sm font-semibold flex items-center gap-1 w-fit">
                                    <i class="fas fa-check-circle"></i>
                                    Actif
                                </span>
                            @else
                                <span class="px-3 py-1 bg-red-500/20 text-red-400 rounded-full text-sm font-semibold flex items-center gap-1 w-fit">
                                    <i class="fas fa-times-circle"></i>
                                    Inactif
                                </span>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="flex justify-end gap-2">
                                <form action="{{ route('admin.newsletter.toggle', $subscriber->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-blue-500/20 hover:bg-blue-500/30 text-blue-400 rounded transition" title="{{ $subscriber->is_active ? 'Désactiver' : 'Activer' }}">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.newsletter.destroy', $subscriber->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet abonné ?')">
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
                        <td colspan="6" class="p-12 text-center text-gray-400">
                            <i class="fas fa-inbox text-5xl mb-4 block"></i>
                            <p class="text-lg">Aucun abonné trouvé</p>
                            @if(request()->hasAny(['search', 'status']))
                                <p class="text-sm mt-2">Essayez de modifier vos filtres de recherche</p>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($subscribers->hasPages())
        <div class="mt-6 pt-6 border-t border-cyan-500/20">
            {{ $subscribers->links() }}
        </div>
        @endif
    </div>
</form>

<script>
function toggleSelectAll() {
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.row-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

function applyBulkAction() {
    const form = document.getElementById('bulk-action-form');
    const action = document.getElementById('bulk-action-select').value;
    const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
    
    if (!action) {
        alert('Veuillez sélectionner une action');
        return;
    }
    
    if (checkedBoxes.length === 0) {
        alert('Veuillez sélectionner au moins un abonné');
        return;
    }
    
    if (action === 'delete') {
        if (!confirm(`Êtes-vous sûr de vouloir supprimer ${checkedBoxes.length} abonné(s) ?`)) {
            return;
        }
    }
    
    form.submit();
}

// Mettre à jour le checkbox "select-all" quand les checkboxes individuelles changent
document.querySelectorAll('.row-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const allChecked = document.querySelectorAll('.row-checkbox:checked').length === document.querySelectorAll('.row-checkbox').length;
        document.getElementById('select-all').checked = allChecked;
    });
    });
</script>
</div>
@endsection
