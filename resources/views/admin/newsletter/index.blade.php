@extends('admin.layout')

@section('title', 'Gestion Newsletter - Admin')

@section('content')
<div class="newsletter-admin">
    <!-- Header Section -->
    <div class="newsletter-header">
        <div class="newsletter-header-content">
            <div class="newsletter-header-text">
                <h1 class="newsletter-title">
                    <span class="newsletter-icon-wrapper">
                        <i class="fas fa-envelope-open-text newsletter-icon"></i>
                    </span>
                    Gestion Newsletter
                </h1>
                <p class="newsletter-subtitle">
                    Gérez vos abonnés à la newsletter
                </p>
            </div>
            <a href="{{ route('admin.newsletter.export', request()->query()) }}" class="export-btn">
                <i class="fas fa-download"></i>
                <span>Exporter CSV</span>
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
    <div class="newsletter-stats">
        <div class="stat-card stat-total">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalAll }}</div>
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
                <div class="stat-value">{{ $totalSubscribers }}</div>
                <div class="stat-label">Actifs</div>
            </div>
        </div>
        <div class="stat-card stat-inactive">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalInactive }}</div>
                <div class="stat-label">Inactifs</div>
            </div>
        </div>
        <div class="stat-card stat-rate">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalAll > 0 ? round(($totalSubscribers / $totalAll) * 100, 1) : 0 }}%</div>
                <div class="stat-label">Taux d'activation</div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filters-section">
        <div class="filters-header">
            <i class="fas fa-filter"></i>
            <span>Filtres et Recherche</span>
        </div>
        <form method="GET" action="{{ route('admin.newsletter.index') }}" class="filters-form">
            <div class="filters-grid">
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-search"></i>
                        Recherche
                    </label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher par email..." class="filter-input">
                </div>
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-tag"></i>
                        Statut
                    </label>
                    <select name="status" class="filter-select">
                        <option value="">Tous les statuts</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actifs</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactifs</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-sort"></i>
                        Trier par
                    </label>
                    <select name="sort_by" class="filter-select">
                        <option value="created_at" {{ request('sort_by', 'created_at') == 'created_at' ? 'selected' : '' }}>Date d'inscription</option>
                        <option value="email" {{ request('sort_by') == 'email' ? 'selected' : '' }}>Email</option>
                        <option value="subscribed_at" {{ request('sort_by') == 'subscribed_at' ? 'selected' : '' }}>Date d'abonnement</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-sort-amount-down"></i>
                        Ordre
                    </label>
                    <select name="sort_order" class="filter-select">
                        <option value="desc" {{ request('sort_order', 'desc') == 'desc' ? 'selected' : '' }}>Décroissant</option>
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Croissant</option>
                    </select>
                </div>
                <div class="filter-actions">
                    <button type="submit" class="filter-btn filter-btn-primary">
                        <i class="fas fa-search"></i>
                        <span>Filtrer</span>
                    </button>
                    @if(request()->hasAny(['search', 'status', 'sort_by', 'sort_order']))
                    <a href="{{ route('admin.newsletter.index') }}" class="filter-btn filter-btn-secondary">
                        <i class="fas fa-redo"></i>
                        <span>Réinitialiser</span>
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Bulk Actions -->
    <form id="bulk-action-form" method="POST" action="{{ route('admin.newsletter.bulk-action') }}" class="bulk-actions-section">
        @csrf
        <div class="bulk-actions-header">
            <div class="bulk-actions-left">
                <input type="checkbox" id="select-all" class="bulk-checkbox" onchange="toggleSelectAll()">
                <label for="select-all" class="bulk-checkbox-label">
                    <span id="selected-count">0 sélectionné</span>
                </label>
            </div>
            <div class="bulk-actions-right">
                <select name="action" id="bulk-action-select" class="bulk-action-select">
                    <option value="">Actions en masse</option>
                    <option value="activate">Activer</option>
                    <option value="deactivate">Désactiver</option>
                    <option value="delete">Supprimer</option>
                </select>
                <button type="button" onclick="applyBulkAction()" class="bulk-action-btn">
                    <i class="fas fa-check"></i>
                    <span>Appliquer</span>
                </button>
            </div>
        </div>
    </form>

    <!-- Subscribers List -->
    @if($subscribers->count() > 0)
    <div class="subscribers-list">
        @foreach($subscribers as $subscriber)
        <div class="subscriber-card {{ $subscriber->is_active ? 'subscriber-active' : 'subscriber-inactive' }}">
            <!-- Card Header -->
            <div class="subscriber-card-header">
                <div class="subscriber-header-left">
                    <input type="checkbox" name="ids[]" value="{{ $subscriber->id }}" class="row-checkbox bulk-checkbox" form="bulk-action-form">
                    <div class="subscriber-avatar">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="subscriber-email-info">
                        <h3 class="subscriber-email">{{ $subscriber->email }}</h3>
                    </div>
                </div>
                <div class="subscriber-status-badge status-{{ $subscriber->is_active ? 'active' : 'inactive' }}">
                    @if($subscriber->is_active)
                        <i class="fas fa-check-circle"></i>
                        <span>Actif</span>
                    @else
                        <i class="fas fa-times-circle"></i>
                        <span>Inactif</span>
                    @endif
                </div>
            </div>

            <!-- Card Body -->
            <div class="subscriber-card-body">
                <div class="subscriber-details-grid">
                    <div class="subscriber-detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-calendar-plus"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Date d'inscription</div>
                            <div class="detail-value">
                                {{ $subscriber->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="subscriber-detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Date d'abonnement</div>
                            <div class="detail-value">
                                {{ $subscriber->subscribed_at ? $subscriber->subscribed_at->format('d/m/Y H:i') : 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Actions -->
            <div class="subscriber-card-actions">
                <form action="{{ route('admin.newsletter.toggle', $subscriber->id) }}" method="POST" class="action-form">
                    @csrf
                    <button type="submit" class="action-btn action-toggle" title="{{ $subscriber->is_active ? 'Désactiver' : 'Activer' }}">
                        <i class="fas fa-sync-alt"></i>
                        <span>{{ $subscriber->is_active ? 'Désactiver' : 'Activer' }}</span>
                    </button>
                </form>
                <form action="{{ route('admin.newsletter.destroy', $subscriber->id) }}" method="POST" class="action-form" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet abonné ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-btn action-delete">
                        <i class="fas fa-trash-alt"></i>
                        <span>Supprimer</span>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="pagination-wrapper">
        {{ $subscribers->links() }}
    </div>
    @else
    <!-- Empty State -->
    <div class="empty-state">
        <div class="empty-state-icon">
            <i class="fas fa-inbox"></i>
        </div>
        <h3 class="empty-state-title">Aucun abonné trouvé</h3>
        <p class="empty-state-text">
            @if(request()->hasAny(['search', 'status']))
                Aucun abonné ne correspond à vos critères de recherche.
            @else
                Aucun abonné n'a été enregistré pour le moment.
            @endif
        </p>
        @if(request()->hasAny(['search', 'status']))
        <a href="{{ route('admin.newsletter.index') }}" class="empty-state-btn">
            <i class="fas fa-redo"></i>
            <span>Réinitialiser les filtres</span>
        </a>
        @endif
    </div>
    @endif
</div>

<style>
.newsletter-admin {
    padding: 2rem;
    max-width: 1600px;
    margin: 0 auto;
}

/* Header */
.newsletter-header {
    margin-bottom: 2rem;
}

.newsletter-header-content {
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

body.light-mode .newsletter-header-content {
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.08) 0%, rgba(20, 184, 166, 0.08) 100%);
    border-color: rgba(6, 182, 212, 0.4);
}

.newsletter-header-content::before {
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

.newsletter-header-text {
    position: relative;
    z-index: 1;
}

.newsletter-title {
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

.newsletter-icon-wrapper {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
    border-radius: 16px;
    border: 2px solid rgba(6, 182, 212, 0.3);
}

.newsletter-icon {
    font-size: 1.8rem;
    color: #06b6d4;
}

.newsletter-subtitle {
    font-size: 1.1rem;
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
}

body.light-mode .newsletter-subtitle {
    color: #64748b;
}

.export-btn {
    position: relative;
    z-index: 1;
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 2rem;
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    color: white;
    border: none;
    border-radius: 16px;
    font-weight: 700;
    font-size: 1rem;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 20px rgba(139, 92, 246, 0.4);
}

.export-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(139, 92, 246, 0.6);
}

.export-btn i {
    font-size: 1.2rem;
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
.newsletter-stats {
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

.stat-inactive::before {
    background: linear-gradient(180deg, #ef4444, #dc2626);
}

.stat-rate::before {
    background: linear-gradient(180deg, #8b5cf6, #7c3aed);
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

.stat-inactive .stat-icon {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(220, 38, 38, 0.2));
    color: #ef4444;
}

.stat-rate .stat-icon {
    background: linear-gradient(135deg, rgba(139, 92, 246, 0.2), rgba(124, 58, 237, 0.2));
    color: #8b5cf6;
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

/* Bulk Actions */
.bulk-actions-section {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8));
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 20px;
    padding: 1.5rem;
    margin-bottom: 2rem;
}

body.light-mode .bulk-actions-section {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95));
    border-color: rgba(6, 182, 212, 0.3);
}

.bulk-actions-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.bulk-actions-left {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.bulk-checkbox {
    width: 20px;
    height: 20px;
    cursor: pointer;
    accent-color: #06b6d4;
}

.bulk-checkbox-label {
    color: rgba(255, 255, 255, 0.9);
    font-weight: 600;
    cursor: pointer;
}

body.light-mode .bulk-checkbox-label {
    color: #1e293b;
}

.bulk-actions-right {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.bulk-action-select {
    padding: 0.75rem 1rem;
    background: rgba(15, 23, 42, 0.8);
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 12px;
    color: white;
    font-size: 0.9rem;
    min-width: 180px;
    cursor: pointer;
    transition: all 0.3s ease;
}

body.light-mode .bulk-action-select {
    background: rgba(255, 255, 255, 0.9);
    border-color: rgba(6, 182, 212, 0.3);
    color: #1e293b;
}

.bulk-action-select:focus {
    outline: none;
    border-color: #06b6d4;
}

.bulk-action-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
}

.bulk-action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(6, 182, 212, 0.4);
}

/* Subscribers List */
.subscribers-list {
    display: grid;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

/* Subscriber Card */
.subscriber-card {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8));
    border: 2px solid rgba(6, 182, 212, 0.3);
    border-radius: 24px;
    padding: 2rem;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

body.light-mode .subscriber-card {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95));
    border-color: rgba(6, 182, 212, 0.3);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.subscriber-card::before {
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

.subscriber-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(6, 182, 212, 0.4);
    border-color: rgba(6, 182, 212, 0.6);
}

.subscriber-card:hover::before {
    opacity: 1;
}

.subscriber-active {
    border-color: rgba(16, 185, 129, 0.5);
}

.subscriber-active::before {
    background: linear-gradient(90deg, #10b981, #059669);
}

.subscriber-inactive {
    border-color: rgba(239, 68, 68, 0.5);
    opacity: 0.8;
}

.subscriber-inactive::before {
    background: linear-gradient(90deg, #ef4444, #dc2626);
}

.subscriber-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    gap: 1rem;
}

.subscriber-header-left {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex: 1;
}

.subscriber-avatar {
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

.subscriber-email-info {
    flex: 1;
}

.subscriber-email {
    font-size: 1.25rem;
    font-weight: 800;
    color: white;
    margin: 0;
    word-break: break-all;
}

body.light-mode .subscriber-email {
    color: #1e293b;
}

.subscriber-status-badge {
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

.status-inactive {
    background: rgba(239, 68, 68, 0.2);
    border: 1px solid rgba(239, 68, 68, 0.4);
    color: #ef4444;
}

.subscriber-card-body {
    margin-bottom: 1.5rem;
}

.subscriber-details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.subscriber-detail-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background: rgba(6, 182, 212, 0.05);
    border-radius: 12px;
}

body.light-mode .subscriber-detail-item {
    background: rgba(6, 182, 212, 0.03);
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

.subscriber-card-actions {
    display: flex;
    gap: 0.75rem;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(6, 182, 212, 0.2);
    flex-wrap: wrap;
}

.action-form {
    flex: 1;
    min-width: 120px;
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
    min-width: 120px;
}

.action-toggle {
    background: rgba(59, 130, 246, 0.15);
    border: 1px solid rgba(59, 130, 246, 0.3);
    color: #3b82f6;
}

.action-toggle:hover {
    background: rgba(59, 130, 246, 0.25);
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

/* Responsive */
@media (max-width: 768px) {
    .newsletter-title {
        font-size: 1.75rem;
    }
    
    .newsletter-icon-wrapper {
        width: 50px;
        height: 50px;
    }
    
    .newsletter-icon {
        font-size: 1.5rem;
    }
    
    .export-btn {
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
    
    .bulk-actions-header {
        flex-direction: column;
        align-items: stretch;
    }
    
    .bulk-actions-right {
        flex-direction: column;
    }
    
    .bulk-action-select,
    .bulk-action-btn {
        width: 100%;
    }
    
    .subscriber-card-header {
        flex-direction: column;
    }
    
    .subscriber-details-grid {
        grid-template-columns: 1fr;
    }
    
    .subscriber-card-actions {
        flex-direction: column;
    }
    
    .action-btn {
        width: 100%;
    }
}
</style>

<script>
function toggleSelectAll() {
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.row-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    updateSelectedCount();
}

function updateSelectedCount() {
    const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
    const count = checkedBoxes.length;
    document.getElementById('selected-count').textContent = count > 0 ? `${count} sélectionné${count > 1 ? 's' : ''}` : '0 sélectionné';
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
        if (!confirm(`Êtes-vous sûr de vouloir supprimer ${checkedBoxes.length} abonné(s) ? Cette action est irréversible.`)) {
            return;
        }
    } else if (action === 'activate') {
        if (!confirm(`Activer ${checkedBoxes.length} abonné(s) ?`)) {
            return;
        }
    } else if (action === 'deactivate') {
        if (!confirm(`Désactiver ${checkedBoxes.length} abonné(s) ?`)) {
            return;
        }
    }
    
    form.submit();
}

// Mettre à jour le compteur et le checkbox "select-all" quand les checkboxes individuelles changent
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.row-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = document.querySelectorAll('.row-checkbox:checked').length === document.querySelectorAll('.row-checkbox').length;
            document.getElementById('select-all').checked = allChecked;
            updateSelectedCount();
        });
    });
    
    // Mettre à jour le compteur initial
    updateSelectedCount();
});
</script>
@endsection
