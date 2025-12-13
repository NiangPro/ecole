@extends('admin.layout')

@section('title', 'Gestion des Abonnements - Admin')

@section('content')
<div class="subscriptions-admin">
    <!-- Header Section -->
    <div class="subscriptions-header">
        <div class="subscriptions-header-content">
            <div class="subscriptions-header-text">
                <h1 class="subscriptions-title">
                    <span class="subscriptions-icon-wrapper">
                        <i class="fas fa-crown subscriptions-icon"></i>
                    </span>
                Gestion des Abonnements
            </h1>
                <p class="subscriptions-subtitle">
                    Gérez et surveillez tous les abonnements premium de votre plateforme
            </p>
        </div>
            <a href="{{ route('admin.monetization.subscription-plans.index') }}" class="manage-plans-btn">
                <i class="fas fa-cog"></i>
                <span>Gérer les Plans</span>
            </a>
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

    @if(session('error'))
    <div class="alert alert-error">
        <div class="alert-icon">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <div class="alert-content">
            <strong>Erreur !</strong>
            <p>{{ session('error') }}</p>
        </div>
        <button class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    <!-- Stats Cards -->
    @if(isset($stats))
    <div class="subscriptions-stats">
        <div class="stat-card stat-total">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-list"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['total'] }}</div>
                <div class="stat-label">Total</div>
            </div>
        </div>
        <div class="stat-card stat-active">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['active'] }}</div>
                <div class="stat-label">Actifs</div>
            </div>
        </div>
        <div class="stat-card stat-pending">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['pending'] }}</div>
                <div class="stat-label">En attente</div>
            </div>
        </div>
        <div class="stat-card stat-cancelled">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['cancelled'] }}</div>
                <div class="stat-label">Annulés</div>
            </div>
        </div>
        <div class="stat-card stat-expired">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-hourglass-end"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['expired'] }}</div>
                <div class="stat-label">Expirés</div>
            </div>
        </div>
    </div>
    @endif

    <!-- Filters Section -->
    <div class="filters-section">
        <div class="filters-header">
            <i class="fas fa-filter"></i>
            <span>Filtres de recherche</span>
        </div>
        <form method="GET" action="{{ route('admin.monetization.subscriptions') }}" class="filters-form">
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
                        <option value="">Tous les statuts</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actif</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Annulé</option>
                        <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expiré</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-calendar-alt"></i>
                        Plan
                    </label>
                    <select name="plan_type" class="filter-select">
                        <option value="">Tous les plans</option>
                        <option value="monthly" {{ request('plan_type') === 'monthly' ? 'selected' : '' }}>Mensuel</option>
                        <option value="yearly" {{ request('plan_type') === 'yearly' ? 'selected' : '' }}>Annuel</option>
                    </select>
                </div>
                <div class="filter-actions">
                    <button type="submit" class="filter-btn filter-btn-primary">
                        <i class="fas fa-search"></i>
                        <span>Filtrer</span>
                    </button>
                    <a href="{{ route('admin.monetization.subscriptions') }}" class="filter-btn filter-btn-secondary">
                        <i class="fas fa-redo"></i>
                        <span>Réinitialiser</span>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Subscriptions List -->
    @if($subscriptions->count() > 0)
    <div class="subscriptions-list">
                    @foreach($subscriptions as $subscription)
        <div class="subscription-card {{ $subscription->status === 'active' ? 'subscription-active' : '' }} {{ $subscription->status === 'pending' ? 'subscription-pending' : '' }} {{ $subscription->status === 'cancelled' ? 'subscription-cancelled' : '' }} {{ $subscription->status === 'expired' ? 'subscription-expired' : '' }}">
            <!-- Card Header -->
            <div class="subscription-card-header">
                <div class="subscription-header-left">
                    <div class="subscription-avatar">
                        @if($subscription->user)
                            <i class="fas fa-user"></i>
                        @else
                            <i class="fas fa-user-slash"></i>
                        @endif
                    </div>
                    <div class="subscription-user-info">
                        <h3 class="subscription-user-name">
                            {{ $subscription->user ? $subscription->user->name : 'Utilisateur supprimé' }}
                        </h3>
                            @if($subscription->user)
                        <p class="subscription-user-email">{{ $subscription->user->email }}</p>
                            @endif
                        <div class="subscription-id">#{{ $subscription->id }}</div>
                    </div>
                </div>
                <div class="subscription-status-badge status-{{ $subscription->status }}">
                            @if($subscription->status === 'active')
                        <i class="fas fa-check-circle"></i>
                        <span>Actif</span>
                            @elseif($subscription->status === 'pending')
                        <i class="fas fa-clock"></i>
                        <span>En attente</span>
                            @elseif($subscription->status === 'cancelled')
                        <i class="fas fa-times-circle"></i>
                        <span>Annulé</span>
                            @else
                        <i class="fas fa-hourglass-end"></i>
                        <span>Expiré</span>
                            @endif
                </div>
            </div>

            <!-- Card Body -->
            <div class="subscription-card-body">
                <div class="subscription-details-grid">
                    <div class="subscription-detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Plan</div>
                            <div class="detail-value">
                                <span class="plan-badge plan-{{ $subscription->plan_type }}">
                                    {{ $subscription->plan_type === 'monthly' ? 'Mensuel' : 'Annuel' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="subscription-detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Montant</div>
                            <div class="detail-value price-value">
                                {{ number_format($subscription->amount, 0, ',', ' ') }} {{ $subscription->currency }}
                            </div>
                        </div>
                    </div>
                    <div class="subscription-detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-play-circle"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Date de début</div>
                            <div class="detail-value">
                            {{ $subscription->start_date ? $subscription->start_date->format('d/m/Y') : 'N/A' }}
                            </div>
                        </div>
                    </div>
                    <div class="subscription-detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-stop-circle"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Date de fin</div>
                            <div class="detail-value">
                            {{ $subscription->end_date ? $subscription->end_date->format('d/m/Y') : 'Illimité' }}
                            </div>
                        </div>
                    </div>
                    @if($subscription->payment_reference)
                    <div class="subscription-detail-item full-width">
                        <div class="detail-icon">
                            <i class="fas fa-hashtag"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Référence de paiement</div>
                            <div class="detail-value reference-value">
                                {{ $subscription->payment_reference }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Card Actions -->
            <div class="subscription-card-actions">
                <a href="{{ route('admin.monetization.subscriptions.show', $subscription->id) }}" class="action-btn action-view">
                    <i class="fas fa-eye"></i>
                    <span>Détails</span>
                </a>
                @if($subscription->status === 'pending')
                <form action="{{ route('admin.monetization.subscriptions.activate', $subscription->id) }}" method="POST" class="action-form">
                    @csrf
                    <button type="submit" class="action-btn action-activate">
                        <i class="fas fa-check"></i>
                        <span>Activer</span>
                    </button>
                </form>
                @endif
                @if($subscription->status === 'active')
                <button type="button" onclick="showCancelModal({{ $subscription->id }})" class="action-btn action-cancel">
                    <i class="fas fa-times"></i>
                    <span>Annuler</span>
                </button>
                @endif
            </div>
        </div>
                    @endforeach
        </div>

        <!-- Pagination -->
    <div class="pagination-wrapper">
            {{ $subscriptions->links() }}
        </div>
    @else
    <!-- Empty State -->
    <div class="empty-state">
        <div class="empty-state-icon">
            <i class="fas fa-crown"></i>
    </div>
        <h3 class="empty-state-title">Aucun abonnement trouvé</h3>
        <p class="empty-state-text">
            @if(request()->hasAny(['search', 'status', 'plan_type']))
                Aucun abonnement ne correspond à vos critères de recherche.
    @else
            Aucun abonnement n'a été créé pour le moment.
            @endif
        </p>
        @if(request()->hasAny(['search', 'status', 'plan_type']))
        <a href="{{ route('admin.monetization.subscriptions') }}" class="empty-state-btn">
            <i class="fas fa-redo"></i>
            <span>Réinitialiser les filtres</span>
        </a>
        @endif
    </div>
    @endif
</div>

<!-- Modal pour annuler un abonnement -->
<div id="cancelModal" class="cancel-modal">
    <div class="cancel-modal-content">
        <div class="cancel-modal-header">
            <div class="cancel-modal-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 class="cancel-modal-title">Annuler l'abonnement</h3>
        </div>
        <form id="cancelForm" method="POST" action="" class="cancel-modal-form">
            @csrf
            <div class="cancel-modal-body">
                <label class="cancel-modal-label">
                    Raison de l'annulation (optionnel)
                </label>
                <textarea name="reason" rows="4" class="cancel-modal-textarea" placeholder="Expliquez la raison de l'annulation..."></textarea>
            </div>
            <div class="cancel-modal-actions">
                <button type="button" onclick="closeCancelModal()" class="cancel-modal-btn cancel-modal-btn-secondary">
                    <i class="fas fa-times"></i>
                    <span>Annuler</span>
                </button>
                <button type="submit" class="cancel-modal-btn cancel-modal-btn-primary">
                    <i class="fas fa-check"></i>
                    <span>Confirmer l'annulation</span>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.subscriptions-admin {
    padding: 2rem;
    max-width: 1600px;
    margin: 0 auto;
}

/* Header */
.subscriptions-header {
    margin-bottom: 2rem;
}

.subscriptions-header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 2rem;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(20, 184, 166, 0.1) 100%);
    border: 2px solid rgba(6, 182, 212, 0.3);
    border-radius: 24px;
    padding: 2.5rem;
    position: relative;
    overflow: hidden;
}

body.light-mode .subscriptions-header-content {
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.08) 0%, rgba(20, 184, 166, 0.08) 100%);
    border-color: rgba(6, 182, 212, 0.4);
}

.subscriptions-header-content::before {
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

.subscriptions-header-text {
    position: relative;
    z-index: 1;
}

.subscriptions-title {
    font-family: 'Poppins', sans-serif;
    font-size: 2.5rem;
    font-weight: 900;
    background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 50%, #06b6d4 100%);
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

.subscriptions-icon-wrapper {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
    border-radius: 16px;
    border: 2px solid rgba(6, 182, 212, 0.3);
}

.subscriptions-icon {
    font-size: 1.8rem;
    color: #06b6d4;
}

.subscriptions-subtitle {
    font-size: 1.1rem;
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
}

body.light-mode .subscriptions-subtitle {
    color: #64748b;
}

.manage-plans-btn {
    position: relative;
    z-index: 1;
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 2rem;
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    color: white;
    border: none;
    border-radius: 16px;
    font-weight: 700;
    font-size: 1rem;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 20px rgba(6, 182, 212, 0.4);
}

.manage-plans-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(6, 182, 212, 0.6);
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

.alert-error {
    background: rgba(239, 68, 68, 0.15);
    border: 2px solid rgba(239, 68, 68, 0.3);
    color: #ef4444;
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
.subscriptions-stats {
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
    background: linear-gradient(180deg, #06b6d4, #14b8a6);
}

.stat-active::before {
    background: linear-gradient(180deg, #10b981, #059669);
}

.stat-pending::before {
    background: linear-gradient(180deg, #fbbf24, #f59e0b);
}

.stat-cancelled::before {
    background: linear-gradient(180deg, #ef4444, #dc2626);
}

.stat-expired::before {
    background: linear-gradient(180deg, #6b7280, #4b5563);
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
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
    color: #06b6d4;
}

.stat-active .stat-icon {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(5, 150, 105, 0.2));
    color: #10b981;
}

.stat-pending .stat-icon {
    background: linear-gradient(135deg, rgba(251, 191, 36, 0.2), rgba(245, 158, 11, 0.2));
    color: #fbbf24;
}

.stat-cancelled .stat-icon {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(220, 38, 38, 0.2));
    color: #ef4444;
}

.stat-expired .stat-icon {
    background: linear-gradient(135deg, rgba(107, 114, 128, 0.2), rgba(75, 85, 99, 0.2));
    color: #6b7280;
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
}

body.light-mode .stat-label {
    color: #64748b;
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
    background: rgba(15, 23, 42, 0.8);
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

/* Subscriptions List */
.subscriptions-list {
    display: grid;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

/* Subscription Card */
.subscription-card {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8));
    border: 2px solid rgba(6, 182, 212, 0.3);
    border-radius: 24px;
    padding: 2rem;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

body.light-mode .subscription-card {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95));
    border-color: rgba(6, 182, 212, 0.3);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.subscription-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #06b6d4, #14b8a6);
    opacity: 0;
    transition: opacity 0.3s;
}

.subscription-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(6, 182, 212, 0.4);
    border-color: rgba(6, 182, 212, 0.6);
}

.subscription-card:hover::before {
    opacity: 1;
}

.subscription-active {
    border-color: rgba(16, 185, 129, 0.5);
}

.subscription-active::before {
    background: linear-gradient(90deg, #10b981, #059669);
}

.subscription-pending {
    border-color: rgba(251, 191, 36, 0.5);
}

.subscription-pending::before {
    background: linear-gradient(90deg, #fbbf24, #f59e0b);
}

.subscription-cancelled {
    border-color: rgba(239, 68, 68, 0.5);
}

.subscription-cancelled::before {
    background: linear-gradient(90deg, #ef4444, #dc2626);
}

.subscription-expired {
    border-color: rgba(107, 114, 128, 0.5);
}

.subscription-expired::before {
    background: linear-gradient(90deg, #6b7280, #4b5563);
}

.subscription-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    gap: 1rem;
}

.subscription-header-left {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex: 1;
}

.subscription-avatar {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #06b6d4;
    border: 2px solid rgba(6, 182, 212, 0.3);
}

.subscription-user-info {
    flex: 1;
}

.subscription-user-name {
    font-size: 1.25rem;
    font-weight: 800;
    color: white;
    margin: 0 0 0.25rem 0;
}

body.light-mode .subscription-user-name {
    color: #1e293b;
}

.subscription-user-email {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.6);
    margin: 0 0 0.25rem 0;
}

body.light-mode .subscription-user-email {
    color: #64748b;
}

.subscription-id {
    font-family: 'Courier New', monospace;
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.5);
}

body.light-mode .subscription-id {
    color: #94a3b8;
}

.subscription-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 12px;
    font-size: 0.85rem;
    font-weight: 700;
}

.status-active {
    background: rgba(16, 185, 129, 0.2);
    border: 1px solid rgba(16, 185, 129, 0.4);
    color: #10b981;
}

.status-pending {
    background: rgba(251, 191, 36, 0.2);
    border: 1px solid rgba(251, 191, 36, 0.4);
    color: #fbbf24;
}

.status-cancelled {
    background: rgba(239, 68, 68, 0.2);
    border: 1px solid rgba(239, 68, 68, 0.4);
    color: #ef4444;
}

.status-expired {
    background: rgba(107, 114, 128, 0.2);
    border: 1px solid rgba(107, 114, 128, 0.4);
    color: #6b7280;
}

.subscription-card-body {
    margin-bottom: 1.5rem;
}

.subscription-details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.subscription-detail-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background: rgba(6, 182, 212, 0.05);
    border-radius: 12px;
}

body.light-mode .subscription-detail-item {
    background: rgba(6, 182, 212, 0.03);
}

.subscription-detail-item.full-width {
    grid-column: 1 / -1;
}

.detail-icon {
    width: 40px;
    height: 40px;
    background: rgba(6, 182, 212, 0.1);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #06b6d4;
    font-size: 1rem;
}

.detail-content {
    flex: 1;
}

.detail-label {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.6);
    margin-bottom: 0.25rem;
}

body.light-mode .detail-label {
    color: #94a3b8;
}

.detail-value {
    font-size: 1rem;
    font-weight: 700;
    color: white;
}

body.light-mode .detail-value {
    color: #1e293b;
}

.price-value {
    color: #06b6d4;
    font-size: 1.1rem;
}

.plan-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 700;
    text-transform: uppercase;
}

.plan-monthly {
    background: rgba(6, 182, 212, 0.2);
    border: 1px solid rgba(6, 182, 212, 0.4);
    color: #06b6d4;
}

.plan-yearly {
    background: rgba(251, 191, 36, 0.2);
    border: 1px solid rgba(251, 191, 36, 0.4);
    color: #fbbf24;
}

.reference-value {
    font-family: 'Courier New', monospace;
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.8);
}

body.light-mode .reference-value {
    color: #475569;
}

.subscription-card-actions {
    display: flex;
    gap: 0.75rem;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(6, 182, 212, 0.2);
}

.action-form {
    flex: 1;
}

.action-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
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

.action-activate {
    background: rgba(16, 185, 129, 0.15);
    border: 1px solid rgba(16, 185, 129, 0.3);
    color: #10b981;
}

.action-activate:hover {
    background: rgba(16, 185, 129, 0.25);
    transform: translateY(-2px);
}

.action-cancel {
    background: rgba(239, 68, 68, 0.15);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #ef4444;
}

.action-cancel:hover {
    background: rgba(239, 68, 68, 0.25);
    transform: translateY(-2px);
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
    border: 2px dashed rgba(6, 182, 212, 0.3);
    border-radius: 24px;
}

body.light-mode .empty-state {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.8), rgba(248, 250, 252, 0.8));
    border-color: rgba(6, 182, 212, 0.4);
}

.empty-state-icon {
    width: 120px;
    height: 120px;
    margin: 0 auto 2rem;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.1));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 4rem;
    color: rgba(6, 182, 212, 0.5);
    border: 3px dashed rgba(6, 182, 212, 0.3);
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
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    color: white;
    border: none;
    border-radius: 16px;
    font-weight: 700;
    font-size: 1rem;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 20px rgba(6, 182, 212, 0.4);
}

.empty-state-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(6, 182, 212, 0.6);
}

/* Cancel Modal */
.cancel-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(5px);
    z-index: 10000;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.cancel-modal-content {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.95), rgba(51, 65, 85, 0.95));
    border: 2px solid rgba(6, 182, 212, 0.3);
    border-radius: 24px;
    padding: 2rem;
    max-width: 500px;
    width: 90%;
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

body.light-mode .cancel-modal-content {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.98), rgba(248, 250, 252, 0.98));
    border-color: rgba(6, 182, 212, 0.4);
}

.cancel-modal-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.cancel-modal-icon {
    width: 50px;
    height: 50px;
    background: rgba(239, 68, 68, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #ef4444;
}

.cancel-modal-title {
    font-size: 1.5rem;
    font-weight: 800;
    color: white;
    margin: 0;
}

body.light-mode .cancel-modal-title {
    color: #1e293b;
}

.cancel-modal-body {
    margin-bottom: 1.5rem;
}

.cancel-modal-label {
    display: block;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 0.75rem;
    font-weight: 600;
}

body.light-mode .cancel-modal-label {
    color: #475569;
}

.cancel-modal-textarea {
    width: 100%;
    padding: 1rem;
    background: rgba(15, 23, 42, 0.8);
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 12px;
    color: white;
    resize: vertical;
    font-family: inherit;
    font-size: 0.9rem;
}

body.light-mode .cancel-modal-textarea {
    background: rgba(255, 255, 255, 0.9);
    border-color: rgba(6, 182, 212, 0.3);
    color: #1e293b;
}

.cancel-modal-textarea:focus {
    outline: none;
    border-color: #06b6d4;
}

.cancel-modal-actions {
    display: flex;
    gap: 0.75rem;
    justify-content: flex-end;
}

.cancel-modal-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.cancel-modal-btn-secondary {
    background: rgba(107, 114, 128, 0.2);
    border: 1px solid rgba(107, 114, 128, 0.4);
    color: white;
}

body.light-mode .cancel-modal-btn-secondary {
    background: rgba(107, 114, 128, 0.1);
    color: #475569;
}

.cancel-modal-btn-secondary:hover {
    background: rgba(107, 114, 128, 0.3);
}

.cancel-modal-btn-primary {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
}

.cancel-modal-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
}

/* Responsive */
    @media (max-width: 768px) {
    .subscriptions-title {
        font-size: 1.75rem;
    }
    
    .subscriptions-icon-wrapper {
        width: 50px;
        height: 50px;
    }
    
    .subscriptions-icon {
        font-size: 1.5rem;
    }
    
    .manage-plans-btn {
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
    
    .subscription-card-header {
        flex-direction: column;
    }
    
    .subscription-details-grid {
        grid-template-columns: 1fr;
    }
    
    .subscription-card-actions {
        flex-direction: column;
    }
    
    .action-btn {
        width: 100%;
    }
    }
</style>

<script>
function showCancelModal(subscriptionId) {
    const form = document.getElementById('cancelForm');
    const route = '{{ route("admin.monetization.subscriptions.deactivate", ":id") }}'.replace(':id', subscriptionId);
    form.action = route;
    document.getElementById('cancelModal').style.display = 'flex';
}

function closeCancelModal() {
    document.getElementById('cancelModal').style.display = 'none';
    document.getElementById('cancelForm').reset();
}

// Fermer la modal en cliquant en dehors
document.getElementById('cancelModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeCancelModal();
    }
});

// Fermer avec Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCancelModal();
    }
});
</script>
@endsection
