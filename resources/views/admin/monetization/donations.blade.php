@extends('admin.layout')

@section('title', 'Gestion des Donations - Admin')

@section('content')
<div class="donations-admin">
    <!-- Header Section -->
    <div class="donations-header">
        <div class="donations-header-content">
            <div class="donations-header-text">
                <h1 class="donations-title">
                    <span class="donations-icon-wrapper">
                        <i class="fas fa-heart donations-icon"></i>
                    </span>
                    Gestion des Donations
                </h1>
                <p class="donations-subtitle">
                    Gérez toutes les donations reçues
                </p>
            </div>
            <div class="donations-header-actions">
                <a href="{{ route('admin.monetization.donations.create') }}" class="header-btn header-btn-primary">
                    <i class="fas fa-plus-circle"></i>
                    <span>Nouvelle Donation</span>
                </a>
                <a href="{{ route('admin.monetization.donations.statistics') }}" class="header-btn header-btn-secondary">
                    <i class="fas fa-chart-bar"></i>
                    <span>Statistiques</span>
                </a>
                <a href="{{ route('admin.monetization.donations.export', request()->all()) }}" class="header-btn header-btn-export">
                    <i class="fas fa-download"></i>
                    <span>Exporter CSV</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Messages Flash -->
    @if(session('success'))
    <div class="alert alert-success">
        <div class="alert-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="alert-content">
            <strong>Succès !</strong>
            <p>{{ session('success') }}</p>
        </div>
        <button class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    @if(session('info'))
    <div class="alert alert-info">
        <div class="alert-icon">
            <i class="fas fa-info-circle"></i>
        </div>
        <div class="alert-content">
            <strong>Information</strong>
            <p>{{ session('info') }}</p>
        </div>
        <button class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="donations-stats">
        <div class="stat-card stat-total">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-heart"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['total'] ?? 0 }}</div>
                <div class="stat-label">Total</div>
            </div>
        </div>
        <div class="stat-card stat-completed">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['completed'] ?? 0 }}</div>
                <div class="stat-label">Complétées</div>
                <div class="stat-amount">{{ number_format($stats['total_amount'] ?? 0, 0, ',', ' ') }} FCFA</div>
            </div>
        </div>
        <div class="stat-card stat-pending">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['pending'] ?? 0 }}</div>
                <div class="stat-label">En Attente</div>
                <div class="stat-amount">{{ number_format($stats['pending_amount'] ?? 0, 0, ',', ' ') }} FCFA</div>
            </div>
        </div>
        <div class="stat-card stat-failed">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['failed'] ?? 0 }}</div>
                <div class="stat-label">Échouées</div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filters-section">
        <div class="filters-header">
            <i class="fas fa-filter"></i>
            <span>Filtres de recherche</span>
        </div>
        <form method="GET" action="{{ route('admin.monetization.donations.index') }}" class="filters-form">
            <div class="filters-grid">
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-search"></i>
                        Recherche
                    </label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, email, référence..." class="filter-input">
                </div>
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-tag"></i>
                        Statut
                    </label>
                    <select name="status" class="filter-select">
                        <option value="">Tous</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Complétées</option>
                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Échouées</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Annulées</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-calendar-alt"></i>
                        Date Début
                    </label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="filter-input">
                </div>
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-calendar-check"></i>
                        Date Fin
                    </label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="filter-input">
                </div>
                <div class="filter-actions">
                    <button type="submit" class="filter-btn filter-btn-primary">
                        <i class="fas fa-search"></i>
                        <span>Filtrer</span>
                    </button>
                    @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                    <a href="{{ route('admin.monetization.donations.index') }}" class="filter-btn filter-btn-secondary">
                        <i class="fas fa-redo"></i>
                        <span>Réinitialiser</span>
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Donations Table -->
    @if($donations->count() > 0)
    <div class="donations-table-wrapper">
        <div class="table-container">
            <table class="donations-table">
                <thead>
                    <tr>
                        <th>
                            <div class="table-header-cell">
                                <i class="fas fa-user"></i>
                                <span>Donateur</span>
                            </div>
                        </th>
                        <th>
                            <div class="table-header-cell">
                                <i class="fas fa-money-bill-wave"></i>
                                <span>Montant</span>
                            </div>
                        </th>
                        <th>
                            <div class="table-header-cell">
                                <i class="fas fa-tag"></i>
                                <span>Statut</span>
                            </div>
                        </th>
                        <th>
                            <div class="table-header-cell">
                                <i class="fas fa-credit-card"></i>
                                <span>Méthode</span>
                            </div>
                        </th>
                        <th>
                            <div class="table-header-cell">
                                <i class="fas fa-calendar"></i>
                                <span>Date</span>
                            </div>
                        </th>
                        <th>
                            <div class="table-header-cell">
                                <i class="fas fa-cog"></i>
                                <span>Actions</span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($donations as $donation)
                    <tr class="table-row donation-row donation-{{ $donation->status }}">
                        <td>
                            <div class="table-cell-content">
                                <div class="donor-info">
                                    <div class="donor-avatar">
                                        <i class="fas fa-heart"></i>
                                    </div>
                                    <div class="donor-details">
                                        <div class="donor-name">
                                            {{ $donation->display_name }}
                                            @if($donation->is_anonymous)
                                            <span class="anonymous-badge">
                                                <i class="fas fa-user-secret"></i>
                                            </span>
                                            @endif
                                        </div>
                                        @if($donation->donor_email)
                                        <div class="donor-email">{{ $donation->donor_email }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="table-cell-content">
                                <div class="amount-cell">
                                    <span class="amount-value">{{ number_format($donation->amount, 0, ',', ' ') }}</span>
                                    <span class="amount-currency">{{ $donation->currency }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="table-cell-content">
                                <span class="status-badge status-{{ $donation->status }}">
                                    @if($donation->status === 'completed')
                                        <i class="fas fa-check-circle"></i>
                                        <span>Confirmé</span>
                                    @elseif($donation->status === 'pending')
                                        <i class="fas fa-clock"></i>
                                        <span>En attente</span>
                                    @elseif($donation->status === 'failed')
                                        <i class="fas fa-times-circle"></i>
                                        <span>Échoué</span>
                                    @else
                                        <i class="fas fa-ban"></i>
                                        <span>Annulé</span>
                                    @endif
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="table-cell-content">
                                <div class="payment-method">
                                    <i class="fas fa-credit-card"></i>
                                    <span>{{ ucfirst(str_replace('_', ' ', $donation->payment_method ?? 'N/A')) }}</span>
                                </div>
                                @if($donation->payment_reference)
                                <div class="payment-reference">
                                    <i class="fas fa-hashtag"></i>
                                    <span>{{ $donation->payment_reference }}</span>
                                </div>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="table-cell-content">
                                <div class="date-cell">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>{{ $donation->created_at->format('d/m/Y') }}</span>
                                </div>
                                <div class="time-cell">
                                    <i class="fas fa-clock"></i>
                                    <span>{{ $donation->created_at->format('H:i') }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="table-cell-content">
                                <div class="table-actions">
                                    <a href="{{ route('admin.monetization.donations.show', $donation->id) }}" class="table-action-btn action-view" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.monetization.donations.edit', $donation->id) }}" class="table-action-btn action-edit" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($donation->status !== 'completed')
                                    <form action="{{ route('admin.monetization.donations.complete', $donation->id) }}" method="POST" class="table-action-form">
                                        @csrf
                                        <button type="submit" class="table-action-btn action-complete" title="Marquer comme complétée">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    @endif
                                    @if($donation->status !== 'failed')
                                    <form action="{{ route('admin.monetization.donations.fail', $donation->id) }}" method="POST" class="table-action-form">
                                        @csrf
                                        <button type="submit" class="table-action-btn action-fail" title="Marquer comme échouée">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                    @endif
                                    @if($donation->status !== 'completed')
                                    <form action="{{ route('admin.monetization.donations.destroy', $donation->id) }}" method="POST" class="table-action-form" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette donation ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="table-action-btn action-delete" title="Supprimer">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @if($donation->message)
                    <tr class="message-row">
                        <td colspan="6">
                            <div class="donation-message-row">
                                <i class="fas fa-quote-left message-icon"></i>
                                <span class="message-text">{{ $donation->message }}</span>
                            </div>
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="pagination-wrapper">
        {{ $donations->links() }}
    </div>
    @else
    <!-- Empty State -->
    <div class="empty-state">
        <div class="empty-state-icon">
            <i class="fas fa-heart"></i>
        </div>
        <h3 class="empty-state-title">Aucune donation</h3>
        <p class="empty-state-text">
            @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                Aucune donation ne correspond à vos critères de recherche.
            @else
                Aucune donation n'a été enregistrée pour le moment.
            @endif
        </p>
        <a href="{{ route('admin.monetization.donations.create') }}" class="empty-state-btn">
            <i class="fas fa-plus-circle"></i>
            <span>Créer une Donation</span>
        </a>
    </div>
    @endif
</div>

<style>
.donations-admin {
    padding: 2rem;
    max-width: 1600px;
    margin: 0 auto;
}

/* Header */
.donations-header {
    margin-bottom: 2rem;
}

.donations-header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 2rem;
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.1) 100%);
    border: 2px solid rgba(239, 68, 68, 0.3);
    border-radius: 24px;
    padding: 2.5rem;
    position: relative;
    overflow: hidden;
}

body.light-mode .donations-header-content {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.08) 0%, rgba(220, 38, 38, 0.08) 100%);
    border-color: rgba(239, 68, 68, 0.4);
}

.donations-header-content::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(239, 68, 68, 0.1) 0%, transparent 70%);
    animation: rotate 20s linear infinite;
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.donations-header-text {
    position: relative;
    z-index: 1;
}

.donations-title {
    font-family: 'Poppins', sans-serif;
    font-size: 2.5rem;
    font-weight: 900;
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 50%, #ef4444 100%);
    background-size: 200% auto;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: shimmer 3s linear infinite;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

@keyframes shimmer {
    to { background-position: 200% center; }
}

.donations-icon-wrapper {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(220, 38, 38, 0.2));
    border-radius: 16px;
    border: 2px solid rgba(239, 68, 68, 0.3);
}

.donations-icon {
    font-size: 1.8rem;
    color: #ef4444;
}

.donations-subtitle {
    font-size: 1.1rem;
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
}

body.light-mode .donations-subtitle {
    color: #64748b;
}

.donations-header-actions {
    position: relative;
    z-index: 1;
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.header-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.9rem;
    text-decoration: none;
    transition: all 0.3s ease;
}

.header-btn-primary {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
}

.header-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
}

.header-btn-secondary {
    background: rgba(139, 92, 246, 0.2);
    border: 1px solid rgba(139, 92, 246, 0.4);
    color: #8b5cf6;
}

.header-btn-secondary:hover {
    background: rgba(139, 92, 246, 0.3);
    transform: translateY(-2px);
}

.header-btn-export {
    background: rgba(16, 185, 129, 0.2);
    border: 1px solid rgba(16, 185, 129, 0.4);
    color: #10b981;
}

.header-btn-export:hover {
    background: rgba(16, 185, 129, 0.3);
    transform: translateY(-2px);
}

/* Alerts */
.alert {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem 1.5rem;
    border-radius: 16px;
    margin-bottom: 2rem;
    animation: slideIn 0.3s ease;
    position: relative;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.alert-success {
    background: rgba(16, 185, 129, 0.15);
    border: 2px solid rgba(16, 185, 129, 0.3);
    color: #10b981;
}

.alert-info {
    background: rgba(59, 130, 246, 0.15);
    border: 2px solid rgba(59, 130, 246, 0.3);
    color: #3b82f6;
}

.alert-icon {
    font-size: 1.5rem;
}

.alert-content {
    flex: 1;
}

.alert-content strong {
    display: block;
    margin-bottom: 0.25rem;
    font-weight: 700;
}

.alert-content p {
    margin: 0;
    opacity: 0.9;
}

.alert-close {
    background: transparent;
    border: none;
    color: inherit;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 8px;
    transition: background 0.2s;
}

.alert-close:hover {
    background: rgba(255, 255, 255, 0.1);
}

/* Stats */
.donations-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8));
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 20px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

body.light-mode .stat-card {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95));
    border-color: rgba(6, 182, 212, 0.3);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    transition: width 0.3s;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(6, 182, 212, 0.3);
}

.stat-card:hover::before {
    width: 100%;
    opacity: 0.1;
}

.stat-total::before {
    background: linear-gradient(180deg, #ef4444, #dc2626);
}

.stat-completed::before {
    background: linear-gradient(180deg, #10b981, #059669);
}

.stat-pending::before {
    background: linear-gradient(180deg, #fbbf24, #f59e0b);
}

.stat-failed::before {
    background: linear-gradient(180deg, #ef4444, #dc2626);
}

.stat-icon-wrapper {
    position: relative;
    z-index: 1;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.stat-total .stat-icon {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(220, 38, 38, 0.2));
    color: #ef4444;
}

.stat-completed .stat-icon {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(5, 150, 105, 0.2));
    color: #10b981;
}

.stat-pending .stat-icon {
    background: linear-gradient(135deg, rgba(251, 191, 36, 0.2), rgba(245, 158, 11, 0.2));
    color: #fbbf24;
}

.stat-failed .stat-icon {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(220, 38, 38, 0.2));
    color: #ef4444;
}

.stat-content {
    flex: 1;
    position: relative;
    z-index: 1;
}

.stat-value {
    font-size: 2rem;
    font-weight: 800;
    color: white;
    line-height: 1;
    margin-bottom: 0.25rem;
}

body.light-mode .stat-value {
    color: #1e293b;
}

.stat-label {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 0.25rem;
}

body.light-mode .stat-label {
    color: #64748b;
}

.stat-amount {
    font-size: 1rem;
    font-weight: 700;
    color: rgba(255, 255, 255, 0.9);
}

body.light-mode .stat-amount {
    color: #334155;
}

.stat-completed .stat-amount {
    color: #10b981;
}

.stat-pending .stat-amount {
    color: #fbbf24;
}

/* Filters */
.filters-section {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8));
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 20px;
    padding: 1.5rem;
    margin-bottom: 2rem;
}

body.light-mode .filters-section {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95));
    border-color: rgba(6, 182, 212, 0.3);
}

.filters-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
    color: rgba(255, 255, 255, 0.9);
    font-weight: 700;
    font-size: 1.1rem;
}

body.light-mode .filters-header {
    color: #1e293b;
}

.filters-form {
    display: block;
}

.filters-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.filter-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: rgba(255, 255, 255, 0.8);
    font-weight: 600;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

body.light-mode .filter-label {
    color: #475569;
}

.filter-input,
.filter-select {
    width: 100%;
    padding: 0.75rem 1rem;
    background: rgba(30, 41, 59, 0.95);
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 12px;
    color: white;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

body.light-mode .filter-input,
body.light-mode .filter-select {
    background: rgba(255, 255, 255, 0.9);
    border-color: rgba(6, 182, 212, 0.3);
    color: #1e293b;
}

.filter-input:focus,
.filter-select:focus {
    outline: none;
    border-color: #06b6d4;
    background: rgba(6, 182, 212, 0.1);
}

body.light-mode .filter-input:focus,
body.light-mode .filter-select:focus {
    background: rgba(255, 255, 255, 1);
}

.filter-actions {
    display: flex;
    gap: 0.75rem;
}

.filter-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.3s ease;
}

.filter-btn-primary {
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    color: white;
    box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
}

.filter-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(6, 182, 212, 0.4);
}

.filter-btn-secondary {
    background: rgba(107, 114, 128, 0.2);
    border: 1px solid rgba(107, 114, 128, 0.4);
    color: white;
}

body.light-mode .filter-btn-secondary {
    background: rgba(107, 114, 128, 0.1);
    color: #475569;
}

.filter-btn-secondary:hover {
    background: rgba(107, 114, 128, 0.3);
}

/* Donations Table */
.donations-table-wrapper {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8));
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 24px;
    padding: 2rem;
    margin-bottom: 2rem;
    overflow: visible;
}

body.light-mode .donations-table-wrapper {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95));
    border-color: rgba(6, 182, 212, 0.3);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.table-container {
    overflow-x: auto;
    overflow-y: visible;
}

/* Scrollbar moderne */
.table-container::-webkit-scrollbar {
    height: 12px;
}

.table-container::-webkit-scrollbar-track {
    background: rgba(30, 41, 59, 0.5);
    border-radius: 10px;
    margin: 0 2rem;
}

body.light-mode .table-container::-webkit-scrollbar-track {
    background: rgba(241, 245, 249, 0.8);
}

.table-container::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    border-radius: 10px;
    border: 2px solid rgba(30, 41, 59, 0.5);
    transition: all 0.3s ease;
}

body.light-mode .table-container::-webkit-scrollbar-thumb {
    border-color: rgba(241, 245, 249, 0.8);
}

.table-container::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #14b8a6, #06b6d4);
    box-shadow: 0 0 10px rgba(6, 182, 212, 0.5);
}

/* Firefox */
.table-container {
    scrollbar-width: thin;
    scrollbar-color: #06b6d4 rgba(30, 41, 59, 0.5);
}

body.light-mode .table-container {
    scrollbar-color: #06b6d4 rgba(241, 245, 249, 0.8);
}

.donations-table {
    width: 100%;
    min-width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    table-layout: auto;
}

.donations-table thead {
    background: rgba(6, 182, 212, 0.1);
}

body.light-mode .donations-table thead {
    background: rgba(6, 182, 212, 0.05);
}

.donations-table th {
    padding: 1.25rem 1rem;
    text-align: left;
    font-weight: 700;
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.9);
    border-bottom: 2px solid rgba(6, 182, 212, 0.3);
    white-space: nowrap;
}

.donations-table th:last-child {
    white-space: normal;
    min-width: fit-content;
    width: auto;
}

body.light-mode .donations-table th {
    color: #1e293b;
}

.table-header-cell {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.table-header-cell i {
    color: #06b6d4;
    font-size: 1rem;
}

.donations-table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid rgba(6, 182, 212, 0.1);
}

.donations-table tbody tr:hover {
    background: rgba(6, 182, 212, 0.05);
    transform: scale(1.01);
}

body.light-mode .donations-table tbody tr:hover {
    background: rgba(6, 182, 212, 0.03);
}

.donation-row.donation-completed {
    border-left: 4px solid #10b981;
}

.donation-row.donation-pending {
    border-left: 4px solid #fbbf24;
}

.donation-row.donation-failed,
.donation-row.donation-cancelled {
    border-left: 4px solid #ef4444;
    opacity: 0.85;
}

.donations-table td {
    padding: 1.25rem 1rem;
    vertical-align: middle;
    white-space: nowrap;
}

.donations-table td:last-child {
    white-space: normal;
    min-width: fit-content;
    width: auto;
}

.table-cell-content {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

/* Donor Info */
.donor-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.donor-avatar {
    width: 45px;
    height: 45px;
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(220, 38, 38, 0.2));
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: #ef4444;
    border: 2px solid rgba(239, 68, 68, 0.3);
    flex-shrink: 0;
}

.donor-details {
    flex: 1;
}

.donor-name {
    font-weight: 700;
    font-size: 1rem;
    color: white;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.25rem;
}

body.light-mode .donor-name {
    color: #1e293b;
}

.anonymous-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.2rem 0.5rem;
    background: rgba(107, 114, 128, 0.2);
    border: 1px solid rgba(107, 114, 128, 0.4);
    border-radius: 6px;
    font-size: 0.7rem;
    color: #6b7280;
}

.donor-email {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.7);
}

body.light-mode .donor-email {
    color: #64748b;
}

/* Amount */
.amount-cell {
    display: flex;
    align-items: baseline;
    gap: 0.5rem;
}

.amount-value {
    font-size: 1.25rem;
    font-weight: 800;
    color: #ef4444;
}

.amount-currency {
    font-size: 0.9rem;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.7);
}

body.light-mode .amount-currency {
    color: #64748b;
}

/* Status Badge */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.75rem;
    border-radius: 10px;
    font-size: 0.85rem;
    font-weight: 700;
}

.status-completed {
    background: rgba(16, 185, 129, 0.2);
    border: 1px solid rgba(16, 185, 129, 0.4);
    color: #10b981;
}

.status-pending {
    background: rgba(251, 191, 36, 0.2);
    border: 1px solid rgba(251, 191, 36, 0.4);
    color: #fbbf24;
}

.status-failed {
    background: rgba(239, 68, 68, 0.2);
    border: 1px solid rgba(239, 68, 68, 0.4);
    color: #ef4444;
}

.status-cancelled {
    background: rgba(107, 114, 128, 0.2);
    border: 1px solid rgba(107, 114, 128, 0.4);
    color: #6b7280;
}

/* Payment Method */
.payment-method {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    color: white;
    margin-bottom: 0.25rem;
}

body.light-mode .payment-method {
    color: #1e293b;
}

.payment-method i {
    color: #06b6d4;
    font-size: 0.9rem;
}

.payment-reference {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.7);
}

body.light-mode .payment-reference {
    color: #64748b;
}

.payment-reference i {
    color: #06b6d4;
    font-size: 0.8rem;
}

/* Date */
.date-cell {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    color: white;
    margin-bottom: 0.25rem;
}

body.light-mode .date-cell {
    color: #1e293b;
}

.date-cell i {
    color: #06b6d4;
    font-size: 0.9rem;
}

.time-cell {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.7);
}

body.light-mode .time-cell {
    color: #64748b;
}

.time-cell i {
    color: #06b6d4;
    font-size: 0.8rem;
}

/* Table Actions */
.table-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: nowrap;
    justify-content: flex-start;
    min-width: fit-content;
}

.table-action-form {
    display: inline-flex;
    flex-shrink: 0;
}

.table-action-btn {
    min-width: 36px;
    width: auto;
    height: 36px;
    padding: 0 0.75rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    border: none;
    border-radius: 10px;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    white-space: nowrap;
}

.action-view {
    background: rgba(59, 130, 246, 0.15);
    border: 1px solid rgba(59, 130, 246, 0.3);
    color: #3b82f6;
}

.action-view:hover {
    background: rgba(59, 130, 246, 0.25);
    transform: translateY(-2px);
}

.action-edit {
    background: rgba(6, 182, 212, 0.15);
    border: 1px solid rgba(6, 182, 212, 0.3);
    color: #06b6d4;
}

.action-edit:hover {
    background: rgba(6, 182, 212, 0.25);
    transform: translateY(-2px);
}

.action-complete {
    background: rgba(16, 185, 129, 0.15);
    border: 1px solid rgba(16, 185, 129, 0.3);
    color: #10b981;
}

.action-complete:hover {
    background: rgba(16, 185, 129, 0.25);
    transform: translateY(-2px);
}

.action-fail {
    background: rgba(239, 68, 68, 0.15);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #ef4444;
}

.action-fail:hover {
    background: rgba(239, 68, 68, 0.25);
    transform: translateY(-2px);
}

.action-delete {
    background: rgba(239, 68, 68, 0.15);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #ef4444;
}

.action-delete:hover {
    background: rgba(239, 68, 68, 0.25);
    transform: translateY(-2px);
}

/* Message Row */
.message-row {
    background: rgba(6, 182, 212, 0.05);
}

body.light-mode .message-row {
    background: rgba(6, 182, 212, 0.03);
}

.donation-message-row {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    border-left: 3px solid rgba(6, 182, 212, 0.3);
}

.message-icon {
    color: #06b6d4;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.message-text {
    color: rgba(255, 255, 255, 0.9);
    font-style: italic;
    line-height: 1.6;
}

body.light-mode .message-text {
    color: #334155;
}

/* Pagination */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.6), rgba(51, 65, 85, 0.6));
    border: 2px dashed rgba(239, 68, 68, 0.3);
    border-radius: 24px;
}

body.light-mode .empty-state {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.8), rgba(248, 250, 252, 0.8));
    border-color: rgba(239, 68, 68, 0.4);
}

.empty-state-icon {
    width: 120px;
    height: 120px;
    margin: 0 auto 2rem;
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(220, 38, 38, 0.1));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 4rem;
    color: rgba(239, 68, 68, 0.5);
    border: 3px dashed rgba(239, 68, 68, 0.3);
}

.empty-state-title {
    font-size: 1.75rem;
    font-weight: 800;
    color: white;
    margin-bottom: 1rem;
}

body.light-mode .empty-state-title {
    color: #1e293b;
}

.empty-state-text {
    font-size: 1.1rem;
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 2rem;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

body.light-mode .empty-state-text {
    color: #64748b;
}

.empty-state-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 2rem;
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    border: none;
    border-radius: 16px;
    font-weight: 700;
    font-size: 1rem;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 20px rgba(239, 68, 68, 0.4);
}

.empty-state-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(239, 68, 68, 0.6);
}

/* Responsive */
@media (max-width: 768px) {
    .donations-title {
        font-size: 1.75rem;
    }
    
    .donations-icon-wrapper {
        width: 50px;
        height: 50px;
    }
    
    .donations-icon {
        font-size: 1.5rem;
    }
    
    .donations-header-actions {
        flex-direction: column;
        width: 100%;
    }
    
    .header-btn {
        width: 100%;
        justify-content: center;
    }
    
    .filters-grid {
        grid-template-columns: 1fr;
    }
    
    .filter-actions {
        flex-direction: column;
    }
    
    .filter-btn {
        width: 100%;
        justify-content: center;
    }
    
    .donations-table-wrapper {
        padding: 1rem;
    }
    
    .donations-table {
        font-size: 0.85rem;
    }
    
    .donations-table th,
    .donations-table td {
        padding: 0.75rem 0.5rem;
    }
    
    .donor-avatar {
        width: 35px;
        height: 35px;
        font-size: 1rem;
    }
    
    .donor-name {
        font-size: 0.9rem;
    }
    
    .amount-value {
        font-size: 1.1rem;
    }
    
    .table-actions {
        flex-wrap: wrap;
    }
    
    .table-action-btn {
        width: 32px;
        height: 32px;
        font-size: 0.8rem;
    }
}
</style>
@endsection
