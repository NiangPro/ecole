@extends('admin.layout')

@section('title', 'Gestion des Donations - Admin')

@push('styles')
<style>
    /* Adaptation Dark Mode */
    body.light-mode .donations-page {
        background: linear-gradient(to bottom right, rgba(248, 250, 252, 1), rgba(241, 245, 249, 1));
    }

    /* Titres et textes */
    body.light-mode .donations-page h1,
    body.light-mode .donations-page h3,
    body.light-mode .donations-page .page-title {
        color: rgba(30, 41, 59, 0.9) !important;
    }

    body.light-mode .donations-page p,
    body.light-mode .donations-page .page-subtitle {
        color: rgba(30, 41, 59, 0.7) !important;
    }

    /* Labels */
    body.light-mode .donations-page label,
    body.light-mode .donations-page .filter-label {
        color: rgba(30, 41, 59, 0.9) !important;
    }

    /* Cartes de statistiques */
    body.light-mode .donations-page .stat-card {
        background: rgba(255, 255, 255, 0.9) !important;
        border-color: rgba(6, 182, 212, 0.2) !important;
    }

    body.light-mode .donations-page .stat-card .stat-value {
        color: rgba(30, 41, 59, 0.9) !important;
    }

    body.light-mode .donations-page .stat-card .stat-label {
        color: rgba(30, 41, 59, 0.6) !important;
    }

    /* Montants dans les stat-cards */
    body.light-mode .donations-page .stat-card .stat-amount {
        color: rgba(30, 41, 59, 0.8) !important;
    }

    /* Tableau */
    body.light-mode .donations-page table th,
    body.light-mode .donations-page table td,
    body.light-mode .donations-page .table-cell {
        color: rgba(30, 41, 59, 0.9) !important;
    }

    body.light-mode .donations-page table thead tr {
        border-bottom-color: rgba(6, 182, 212, 0.2) !important;
    }

    body.light-mode .donations-page table tbody tr {
        border-bottom-color: rgba(6, 182, 212, 0.1) !important;
    }

    /* Montants dans le tableau */
    body.light-mode .donations-page .table-amount {
        color: rgba(239, 68, 68, 0.9) !important;
    }

    /* Badges de statut */
    body.light-mode .donations-page .status-badge.status-completed {
        background: rgba(16, 185, 129, 0.1) !important;
        border-color: rgba(16, 185, 129, 0.3) !important;
        color: rgba(5, 150, 105, 0.9) !important;
    }

    body.light-mode .donations-page .status-badge.status-pending {
        background: rgba(251, 191, 36, 0.1) !important;
        border-color: rgba(251, 191, 36, 0.3) !important;
        color: rgba(217, 119, 6, 0.9) !important;
    }

    body.light-mode .donations-page .status-badge.status-failed {
        background: rgba(239, 68, 68, 0.1) !important;
        border-color: rgba(239, 68, 68, 0.3) !important;
        color: rgba(220, 38, 38, 0.9) !important;
    }

    body.light-mode .donations-page .status-badge.status-cancelled {
        background: rgba(107, 114, 128, 0.1) !important;
        border-color: rgba(107, 114, 128, 0.3) !important;
        color: rgba(71, 85, 105, 0.9) !important;
    }

    /* Badge Anonyme */
    body.light-mode .donations-page .badge-anonymous {
        background: rgba(107, 114, 128, 0.1) !important;
        border-color: rgba(107, 114, 128, 0.3) !important;
        color: rgba(71, 85, 105, 0.9) !important;
    }

    /* Messages dans le tableau */
    body.light-mode .donations-page .table-message {
        color: rgba(30, 41, 59, 0.7) !important;
    }

    /* Cartes de filtres et tableau */
    body.light-mode .donations-page .filter-card,
    body.light-mode .donations-page .table-card {
        background: rgba(255, 255, 255, 0.9) !important;
        border-color: rgba(6, 182, 212, 0.2) !important;
    }

    /* Inputs et selects */
    body.light-mode .donations-page input,
    body.light-mode .donations-page select,
    body.light-mode .donations-page textarea,
    body.light-mode .donations-page .filter-input,
    body.light-mode .donations-page .filter-select {
        background: rgba(255, 255, 255, 0.95) !important;
        color: rgba(30, 41, 59, 0.9) !important;
        border-color: rgba(6, 182, 212, 0.3) !important;
    }

    body.light-mode .donations-page input::placeholder,
    body.light-mode .donations-page textarea::placeholder,
    body.light-mode .donations-page .filter-input::placeholder {
        color: rgba(30, 41, 59, 0.5) !important;
    }

    /* Options des selects */
    body.light-mode .donations-page select option,
    body.light-mode .donations-page .filter-select option {
        background: white !important;
        color: rgba(30, 41, 59, 0.9) !important;
    }

    /* Boutons */
    body.light-mode .donations-page button[style*="background: linear-gradient"] {
        box-shadow: 0 2px 8px rgba(6, 182, 212, 0.2) !important;
    }

    body.light-mode .donations-page a[style*="background: rgba(107, 114, 128"] {
        background: rgba(107, 114, 128, 0.1) !important;
        border-color: rgba(107, 114, 128, 0.3) !important;
        color: rgba(71, 85, 105, 0.9) !important;
    }

    /* État vide */
    body.light-mode .donations-page .empty-state {
        background: rgba(255, 255, 255, 0.9) !important;
        border-color: rgba(6, 182, 212, 0.2) !important;
    }

    body.light-mode .donations-page .empty-state h3 {
        color: rgba(30, 41, 59, 0.9) !important;
    }

    body.light-mode .donations-page .empty-state p {
        color: rgba(30, 41, 59, 0.7) !important;
    }

    body.light-mode .donations-page .empty-state i[style*="color: rgba(239, 68, 68"] {
        color: rgba(239, 68, 68, 0.6) !important;
    }

    /* Alertes */
    body.light-mode .donations-page .alert-success {
        background: rgba(16, 185, 129, 0.1) !important;
        border-color: rgba(16, 185, 129, 0.3) !important;
        color: rgba(5, 150, 105, 0.9) !important;
    }

    body.light-mode .donations-page .alert-info {
        background: rgba(59, 130, 246, 0.1) !important;
        border-color: rgba(59, 130, 246, 0.3) !important;
        color: rgba(37, 99, 235, 0.9) !important;
    }

    /* Boutons de liens */
    body.light-mode .donations-page .btn-link {
        color: rgba(30, 41, 59, 0.8) !important;
    }

    body.light-mode .donations-page .btn-link:hover {
        color: #06b6d4 !important;
    }

    /* Pagination */
    body.light-mode .donations-page .pagination a,
    body.light-mode .donations-page .pagination span {
        color: rgba(30, 41, 59, 0.9) !important;
    }

    body.light-mode .donations-page .pagination .active span {
        background: rgba(6, 182, 212, 0.2) !important;
        border-color: rgba(6, 182, 212, 0.3) !important;
        color: rgba(6, 182, 212, 0.9) !important;
    }
</style>
@endpush

@section('content')
<div class="donations-page" style="padding: 2rem;">
    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
        <div>
            <h1 class="page-title" style="font-size: 2.5rem; font-weight: 800; color: white; margin-bottom: 0.5rem;">
                <i class="fas fa-heart" style="color: #06b6d4; margin-right: 15px;"></i>
                Gestion des Donations
            </h1>
            <p class="page-subtitle" style="color: rgba(255, 255, 255, 0.7); font-size: 1.1rem;">
                Gérez toutes les donations reçues
            </p>
        </div>
        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
            <a href="{{ route('admin.monetization.donations.create') }}" class="btn-link" style="padding: 12px 24px; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: white; border: none; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);">
                <i class="fas fa-plus" style="margin-right: 8px;"></i>
                Nouvelle Donation
            </a>
            <a href="{{ route('admin.monetization.donations.statistics') }}" class="btn-link" style="padding: 12px 24px; background: rgba(139, 92, 246, 0.2); color: #8b5cf6; border: 2px solid #8b5cf6; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
                <i class="fas fa-chart-bar" style="margin-right: 8px;"></i>
                Statistiques
            </a>
            <a href="{{ route('admin.monetization.donations.export', request()->all()) }}" class="btn-link" style="padding: 12px 24px; background: rgba(16, 185, 129, 0.2); color: #10b981; border: 2px solid #10b981; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
                <i class="fas fa-download" style="margin-right: 8px;"></i>
                Exporter CSV
            </a>
            <a href="{{ route('admin.monetization.dashboard') }}" class="btn-link" style="padding: 12px 24px; background: rgba(6, 182, 212, 0.2); color: #06b6d4; border: 2px solid #06b6d4; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
                <i class="fas fa-arrow-left" style="margin-right: 8px;"></i>
                Retour
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert-success" style="padding: 15px 20px; background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; border-radius: 12px; color: #10b981; margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('info'))
    <div class="alert-info" style="padding: 15px 20px; background: rgba(59, 130, 246, 0.2); border: 1px solid #3b82f6; border-radius: 12px; color: #3b82f6; margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-info-circle"></i>
        <span>{{ session('info') }}</span>
    </div>
    @endif

    <!-- Statistiques Rapides -->
    <div class="stats-container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div class="stat-card" style="background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2)); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 20px;">
            <div class="stat-label" style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 8px;">Total</div>
            <div class="stat-value" style="font-size: 1.75rem; font-weight: 800; color: #06b6d4;">{{ $stats['total'] ?? 0 }}</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(5, 150, 105, 0.2)); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 16px; padding: 20px;">
            <div class="stat-label" style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 8px;">Complétées</div>
            <div class="stat-value" style="font-size: 1.75rem; font-weight: 800; color: #10b981;">{{ $stats['completed'] ?? 0 }}</div>
            <div class="stat-amount" style="font-size: 1.1rem; font-weight: 700; color: #10b981; margin-top: 5px;">
                {{ number_format($stats['total_amount'] ?? 0, 0, ',', ' ') }} FCFA
            </div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, rgba(251, 191, 36, 0.2), rgba(245, 158, 11, 0.2)); border: 1px solid rgba(251, 191, 36, 0.3); border-radius: 16px; padding: 20px;">
            <div class="stat-label" style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 8px;">En Attente</div>
            <div class="stat-value" style="font-size: 1.75rem; font-weight: 800; color: #fbbf24;">{{ $stats['pending'] ?? 0 }}</div>
            <div class="stat-amount" style="font-size: 1.1rem; font-weight: 700; color: #fbbf24; margin-top: 5px;">
                {{ number_format($stats['pending_amount'] ?? 0, 0, ',', ' ') }} FCFA
            </div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(220, 38, 38, 0.2)); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 16px; padding: 20px;">
            <div class="stat-label" style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 8px;">Échouées</div>
            <div class="stat-value" style="font-size: 1.75rem; font-weight: 800; color: #ef4444;">{{ $stats['failed'] ?? 0 }}</div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="filter-card" style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 25px; margin-bottom: 30px;">
        <form method="GET" action="{{ route('admin.monetization.donations.index') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; align-items: end;">
            <div>
                <label class="filter-label" style="display: block; color: white; font-weight: 600; margin-bottom: 8px; font-size: 0.9rem;">Recherche</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, email, référence..." class="filter-input" style="width: 100%; padding: 10px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem;">
            </div>
            <div>
                <label class="filter-label" style="display: block; color: white; font-weight: 600; margin-bottom: 8px; font-size: 0.9rem;">Statut</label>
                <select name="status" class="filter-select" style="width: 100%; padding: 10px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem;">
                    <option value="">Tous</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Complétées</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Échouées</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Annulées</option>
                </select>
            </div>
            <div>
                <label class="filter-label" style="display: block; color: white; font-weight: 600; margin-bottom: 8px; font-size: 0.9rem;">Date Début</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="filter-input" style="width: 100%; padding: 10px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem;">
            </div>
            <div>
                <label class="filter-label" style="display: block; color: white; font-weight: 600; margin-bottom: 8px; font-size: 0.9rem;">Date Fin</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="filter-input" style="width: 100%; padding: 10px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem;">
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="submit" style="flex: 1; padding: 10px 20px; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                    <i class="fas fa-search" style="margin-right: 6px;"></i>
                    Filtrer
                </button>
                <a href="{{ route('admin.monetization.donations.index') }}" style="padding: 10px 20px; background: rgba(107, 114, 128, 0.2); color: #6b7280; border: 1px solid #6b7280; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>

    @if($donations->count() > 0)
    <div class="table-card" style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 30px;">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid rgba(6, 182, 212, 0.3);">
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">Donateur</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">Montant</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">Statut</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">Méthode</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">Date</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($donations as $donation)
                    <tr style="border-bottom: 1px solid rgba(6, 182, 212, 0.1);">
                        <td class="table-cell" style="padding: 15px; color: rgba(255, 255, 255, 0.8); font-weight: 600;">
                            {{ $donation->display_name }}
                            @if($donation->is_anonymous)
                            <span class="badge-anonymous" style="padding: 2px 8px; background: rgba(107, 114, 128, 0.2); border: 1px solid #6b7280; border-radius: 4px; color: #6b7280; font-size: 0.75rem; margin-left: 8px;">Anonyme</span>
                            @endif
                        </td>
                        <td class="table-cell table-amount" style="padding: 15px; color: #ef4444; font-weight: 700; font-size: 1.1rem;">
                            {{ number_format($donation->amount, 0, ',', ' ') }} {{ $donation->currency }}
                        </td>
                        <td class="table-cell" style="padding: 15px;">
                            @if($donation->status === 'completed')
                                <span class="status-badge status-completed" style="padding: 4px 10px; background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; border-radius: 6px; color: #10b981; font-size: 0.85rem; font-weight: 600;">Confirmé</span>
                            @elseif($donation->status === 'pending')
                                <span class="status-badge status-pending" style="padding: 4px 10px; background: rgba(251, 191, 36, 0.2); border: 1px solid #fbbf24; border-radius: 6px; color: #fbbf24; font-size: 0.85rem; font-weight: 600;">En attente</span>
                            @elseif($donation->status === 'failed')
                                <span class="status-badge status-failed" style="padding: 4px 10px; background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; border-radius: 6px; color: #ef4444; font-size: 0.85rem; font-weight: 600;">Échoué</span>
                            @else
                                <span class="status-badge status-cancelled" style="padding: 4px 10px; background: rgba(107, 114, 128, 0.2); border: 1px solid #6b7280; border-radius: 6px; color: #6b7280; font-size: 0.85rem; font-weight: 600;">Annulé</span>
                            @endif
                        </td>
                        <td class="table-cell" style="padding: 15px; color: rgba(255, 255, 255, 0.8);">
                            {{ ucfirst(str_replace('_', ' ', $donation->payment_method ?? 'N/A')) }}
                        </td>
                        <td class="table-cell" style="padding: 15px; color: rgba(255, 255, 255, 0.8); font-size: 0.9rem;">
                            {{ $donation->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td style="padding: 15px;">
                            <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                <a href="{{ route('admin.monetization.donations.show', $donation->id) }}" style="padding: 4px 8px; background: rgba(59, 130, 246, 0.2); color: #3b82f6; border: 1px solid #3b82f6; border-radius: 4px; text-decoration: none; font-size: 0.75rem; transition: all 0.3s ease; display: inline-flex; align-items: center; justify-content: center; min-width: 28px; height: 28px;" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.monetization.donations.edit', $donation->id) }}" style="padding: 4px 8px; background: rgba(251, 191, 36, 0.2); color: #fbbf24; border: 1px solid #fbbf24; border-radius: 4px; text-decoration: none; font-size: 0.75rem; transition: all 0.3s ease; display: inline-flex; align-items: center; justify-content: center; min-width: 28px; height: 28px;" title="Éditer">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($donation->status !== 'completed')
                                <form action="{{ route('admin.monetization.donations.complete', $donation->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" style="padding: 4px 8px; background: rgba(16, 185, 129, 0.2); color: #10b981; border: 1px solid #10b981; border-radius: 4px; cursor: pointer; font-size: 0.75rem; transition: all 0.3s ease; display: inline-flex; align-items: center; justify-content: center; min-width: 28px; height: 28px;" title="Marquer comme complétée">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                @endif
                                @if($donation->status !== 'failed')
                                <form action="{{ route('admin.monetization.donations.fail', $donation->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" style="padding: 4px 8px; background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid #ef4444; border-radius: 4px; cursor: pointer; font-size: 0.75rem; transition: all 0.3s ease; display: inline-flex; align-items: center; justify-content: center; min-width: 28px; height: 28px;" title="Marquer comme échouée">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                                @endif
                                @if($donation->status !== 'completed')
                                <form action="{{ route('admin.monetization.donations.destroy', $donation->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette donation ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="padding: 4px 8px; background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid #ef4444; border-radius: 4px; cursor: pointer; font-size: 0.75rem; transition: all 0.3s ease; display: inline-flex; align-items: center; justify-content: center; min-width: 28px; height: 28px;" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @if($donation->message)
                    <tr>
                        <td colspan="6" class="table-message" style="padding: 10px 15px; color: rgba(255, 255, 255, 0.7); font-style: italic; font-size: 0.9rem; border-bottom: 1px solid rgba(6, 182, 212, 0.1);">
                            <i class="fas fa-quote-left" style="margin-right: 8px; color: #06b6d4;"></i>
                            "{{ \Illuminate\Support\Str::limit($donation->message, 100) }}"
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div style="margin-top: 30px; display: flex; justify-content: center;">
            {{ $donations->links() }}
        </div>
    </div>
    @else
    <div class="empty-state" style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 60px; text-align: center;">
        <i class="fas fa-heart" style="font-size: 4rem; color: rgba(239, 68, 68, 0.5); margin-bottom: 20px;"></i>
        <h3 style="font-size: 1.5rem; font-weight: 700; color: white; margin-bottom: 10px;">
            Aucune donation
        </h3>
        <p style="color: rgba(255, 255, 255, 0.7); margin-bottom: 25px;">
            Aucune donation ne correspond à vos critères de recherche.
        </p>
        <a href="{{ route('admin.monetization.donations.create') }}" style="display: inline-block; padding: 12px 24px; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: white; border: none; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);">
            <i class="fas fa-plus" style="margin-right: 8px;"></i>
            Créer une donation
        </a>
    </div>
    @endif
</div>

<style>
    @media (max-width: 768px) {
        table { font-size: 0.85rem; }
        th, td { padding: 10px !important; }
        .filters-form { grid-template-columns: 1fr !important; }
    }
</style>
@endsection
