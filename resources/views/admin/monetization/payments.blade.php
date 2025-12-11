@extends('admin.layout')

@section('title', 'Gestion des Paiements - Admin')

@section('content')
<div class="payments-admin">
    <!-- Header Section -->
    <div class="payments-header">
        <div class="payments-header-content">
            <div class="payments-header-text">
                <h1 class="payments-title">
                    <span class="payments-icon-wrapper">
                        <i class="fas fa-credit-card payments-icon"></i>
                    </span>
                    Gestion des Paiements
                </h1>
                <p class="payments-subtitle">
                    Gérez tous les paiements de la plateforme
                </p>
            </div>
            <div class="payments-header-actions">
                <a href="{{ route('admin.monetization.dashboard') }}" class="header-btn header-btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour au dashboard</span>
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
    <div class="payments-stats">
        <div class="stat-card stat-total">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-credit-card"></i>
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
                <div class="stat-label">Complétés</div>
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
        <div class="stat-card stat-processing">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-spinner"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['processing'] ?? 0 }}</div>
                <div class="stat-label">En Traitement</div>
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
                <div class="stat-label">Échoués</div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filters-section">
        <div class="filters-header">
            <i class="fas fa-filter"></i>
            <span>Filtres de recherche</span>
        </div>
        <form method="GET" action="{{ route('admin.monetization.payments') }}" class="filters-form">
            <div class="filters-grid">
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-search"></i>
                        Recherche
                    </label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Référence, montant, utilisateur..." class="filter-input">
                </div>
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-tag"></i>
                        Statut
                    </label>
                    <select name="status" class="filter-select">
                        <option value="">Tous</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>En traitement</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Complétés</option>
                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Échoués</option>
                        <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>Remboursés</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-list"></i>
                        Type
                    </label>
                    <select name="type" class="filter-select">
                        <option value="">Tous</option>
                        <option value="Subscription" {{ request('type') === 'Subscription' ? 'selected' : '' }}>Abonnement</option>
                        <option value="CoursePurchase" {{ request('type') === 'CoursePurchase' ? 'selected' : '' }}>Cours</option>
                        <option value="Donation" {{ request('type') === 'Donation' ? 'selected' : '' }}>Donation</option>
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
                    @if(request()->hasAny(['search', 'status', 'type', 'date_from', 'date_to']))
                    <a href="{{ route('admin.monetization.payments') }}" class="filter-btn filter-btn-secondary">
                        <i class="fas fa-redo"></i>
                        <span>Réinitialiser</span>
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Payments Table -->
    @if($payments->count() > 0)
    <div class="payments-table-wrapper">
        <div class="table-container">
            <table class="payments-table">
                <thead>
                    <tr>
                        <th>
                            <div class="table-header-cell">
                                <i class="fas fa-hashtag"></i>
                                <span>ID</span>
                            </div>
                        </th>
                        <th>
                            <div class="table-header-cell">
                                <i class="fas fa-user"></i>
                                <span>Utilisateur</span>
                            </div>
                        </th>
                        <th>
                            <div class="table-header-cell">
                                <i class="fas fa-list"></i>
                                <span>Type</span>
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
                                <i class="fas fa-credit-card"></i>
                                <span>Méthode</span>
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
                                <i class="fas fa-hashtag"></i>
                                <span>Référence</span>
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
                    @foreach($payments as $payment)
                    <tr class="table-row payment-row payment-{{ $payment->status }}">
                        <td>
                            <div class="table-cell-content">
                                <span class="payment-id">#{{ $payment->id }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="table-cell-content">
                                <div class="user-info">
                                    <div class="user-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="user-details">
                                        <div class="user-name">
                                            {{ $payment->user ? $payment->user->name : 'N/A' }}
                                        </div>
                                        @if($payment->user)
                                        <div class="user-email">{{ $payment->user->email }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="table-cell-content">
                                @if(class_basename($payment->paymentable_type) === 'Subscription')
                                    <span class="type-badge type-subscription">
                                        <i class="fas fa-crown"></i>
                                        <span>Abonnement</span>
                                    </span>
                                @elseif(class_basename($payment->paymentable_type) === 'CoursePurchase')
                                    <span class="type-badge type-course">
                                        <i class="fas fa-graduation-cap"></i>
                                        <span>Cours</span>
                                    </span>
                                @elseif(class_basename($payment->paymentable_type) === 'Donation')
                                    <span class="type-badge type-donation">
                                        <i class="fas fa-heart"></i>
                                        <span>Don</span>
                                    </span>
                                @else
                                    <span class="type-badge type-other">
                                        <i class="fas fa-question"></i>
                                        <span>Autre</span>
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="table-cell-content">
                                <div class="amount-cell">
                                    <span class="amount-value">{{ number_format($payment->amount, 0, ',', ' ') }}</span>
                                    <span class="amount-currency">{{ $payment->currency }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="table-cell-content">
                                <div class="payment-method">
                                    <i class="fas fa-credit-card"></i>
                                    <span>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span>
                                </div>
                                @if($payment->payment_gateway)
                                <div class="payment-gateway">
                                    <i class="fas fa-server"></i>
                                    <span>{{ $payment->payment_gateway }}</span>
                                </div>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="table-cell-content">
                                <span class="status-badge status-{{ $payment->status }}">
                                    @if($payment->status === 'completed')
                                        <i class="fas fa-check-circle"></i>
                                        <span>Confirmé</span>
                                    @elseif($payment->status === 'pending')
                                        <i class="fas fa-clock"></i>
                                        <span>En attente</span>
                                    @elseif($payment->status === 'processing')
                                        <i class="fas fa-spinner"></i>
                                        <span>En traitement</span>
                                    @elseif($payment->status === 'failed')
                                        <i class="fas fa-times-circle"></i>
                                        <span>Échoué</span>
                                    @elseif($payment->status === 'refunded')
                                        <i class="fas fa-undo"></i>
                                        <span>Remboursé</span>
                                    @else
                                        <i class="fas fa-question"></i>
                                        <span>{{ ucfirst($payment->status) }}</span>
                                    @endif
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="table-cell-content">
                                <div class="payment-reference">
                                    <i class="fas fa-hashtag"></i>
                                    <span>{{ $payment->payment_reference ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="table-cell-content">
                                <div class="date-cell">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>{{ $payment->created_at->format('d/m/Y') }}</span>
                                </div>
                                <div class="time-cell">
                                    <i class="fas fa-clock"></i>
                                    <span>{{ $payment->created_at->format('H:i') }}</span>
                                </div>
                                @if($payment->paid_at)
                                <div class="paid-cell">
                                    <i class="fas fa-check-double"></i>
                                    <span>Payé: {{ $payment->paid_at->format('d/m/Y H:i') }}</span>
                                </div>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="table-cell-content">
                                <div class="table-actions">
                                    @if(class_basename($payment->paymentable_type) === 'CoursePurchase')
                                        @if($payment->status === 'pending')
                                            <a href="{{ route('admin.monetization.payments.course.show', $payment->id) }}" class="table-action-btn action-view" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('admin.monetization.payments.course.accept', $payment->id) }}" method="POST" class="table-action-form" onsubmit="return confirm('Êtes-vous sûr de vouloir accepter cette inscription ?');">
                                                @csrf
                                                <button type="submit" class="table-action-btn action-complete" title="Accepter">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <button type="button" onclick="showRejectModal({{ $payment->id }})" class="table-action-btn action-reject" title="Refuser">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @else
                                            <a href="{{ route('admin.monetization.payments.course.show', $payment->id) }}" class="table-action-btn action-view" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endif
                                    @else
                                        <span class="no-action">-</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @if($payment->failure_reason)
                    <tr class="message-row failure-row">
                        <td colspan="9">
                            <div class="payment-failure-row">
                                <i class="fas fa-exclamation-triangle failure-icon"></i>
                                <div class="failure-content">
                                    <strong>Raison de l'échec:</strong>
                                    <span>{{ $payment->failure_reason }}</span>
                                </div>
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
        {{ $payments->links() }}
    </div>
    @else
    <!-- Empty State -->
    <div class="empty-state">
        <div class="empty-state-icon">
            <i class="fas fa-credit-card"></i>
        </div>
        <h3 class="empty-state-title">Aucun paiement</h3>
        <p class="empty-state-text">
            @if(request()->hasAny(['search', 'status', 'type', 'date_from', 'date_to']))
                Aucun paiement ne correspond à vos critères de recherche.
            @else
                Aucun paiement n'a été enregistré pour le moment.
            @endif
        </p>
    </div>
    @endif
</div>

<!-- Modal pour refuser un paiement -->
<div id="rejectModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fas fa-times-circle"></i>
                Refuser l'inscription
            </h3>
            <button class="modal-close" onclick="closeRejectModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="rejectForm" method="POST" action="">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">
                        Raison du refus (optionnel)
                    </label>
                    <textarea name="reason" rows="4" class="form-textarea" placeholder="Expliquez la raison du refus..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeRejectModal()" class="modal-btn modal-btn-secondary">
                    Annuler
                </button>
                <button type="submit" class="modal-btn modal-btn-danger">
                    Confirmer le refus
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showRejectModal(paymentId) {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    form.action = '{{ route("admin.monetization.payments.course.reject", ":id") }}'.replace(':id', paymentId);
    modal.classList.add('active');
}

function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    modal.classList.remove('active');
    document.getElementById('rejectForm').reset();
}

// Fermer le modal en cliquant en dehors
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});
</script>

<style>
.payments-admin {
    padding: 2rem;
    max-width: 1600px;
    margin: 0 auto;
}

/* Header */
.payments-header {
    margin-bottom: 2rem;
}

.payments-header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 2rem;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(37, 99, 235, 0.1) 100%);
    border: 2px solid rgba(59, 130, 246, 0.3);
    border-radius: 24px;
    padding: 2.5rem;
    position: relative;
    overflow: hidden;
}

body.light-mode .payments-header-content {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.08) 0%, rgba(37, 99, 235, 0.08) 100%);
    border-color: rgba(59, 130, 246, 0.4);
}

.payments-header-content::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, transparent 70%);
    animation: rotate 20s linear infinite;
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.payments-header-text {
    position: relative;
    z-index: 1;
}

.payments-title {
    font-family: 'Poppins', sans-serif;
    font-size: 2.5rem;
    font-weight: 900;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 50%, #3b82f6 100%);
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

.payments-icon-wrapper {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(37, 99, 235, 0.2));
    border-radius: 16px;
    border: 2px solid rgba(59, 130, 246, 0.3);
}

.payments-icon {
    font-size: 1.8rem;
    color: #3b82f6;
}

.payments-subtitle {
    font-size: 1.1rem;
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
}

body.light-mode .payments-subtitle {
    color: #64748b;
}

.payments-header-actions {
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

.header-btn-secondary {
    background: rgba(6, 182, 212, 0.2);
    border: 1px solid rgba(6, 182, 212, 0.4);
    color: #06b6d4;
}

.header-btn-secondary:hover {
    background: rgba(6, 182, 212, 0.3);
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
.payments-stats {
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
    background: linear-gradient(180deg, #3b82f6, #2563eb);
}

.stat-completed::before {
    background: linear-gradient(180deg, #10b981, #059669);
}

.stat-pending::before {
    background: linear-gradient(180deg, #fbbf24, #f59e0b);
}

.stat-processing::before {
    background: linear-gradient(180deg, #3b82f6, #2563eb);
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
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(37, 99, 235, 0.2));
    color: #3b82f6;
}

.stat-completed .stat-icon {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(5, 150, 105, 0.2));
    color: #10b981;
}

.stat-pending .stat-icon {
    background: linear-gradient(135deg, rgba(251, 191, 36, 0.2), rgba(245, 158, 11, 0.2));
    color: #fbbf24;
}

.stat-processing .stat-icon {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(37, 99, 235, 0.2));
    color: #3b82f6;
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

/* Payments Table */
.payments-table-wrapper {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8));
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 24px;
    padding: 2rem;
    margin-bottom: 2rem;
    overflow: visible;
}

body.light-mode .payments-table-wrapper {
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

.payments-table {
    width: 100%;
    min-width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    table-layout: auto;
}

.payments-table thead {
    background: rgba(6, 182, 212, 0.1);
}

body.light-mode .payments-table thead {
    background: rgba(6, 182, 212, 0.05);
}

.payments-table th {
    padding: 1.25rem 1rem;
    text-align: left;
    font-weight: 700;
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.9);
    border-bottom: 2px solid rgba(6, 182, 212, 0.3);
    white-space: nowrap;
}

body.light-mode .payments-table th {
    color: #1e293b;
}

.payments-table th:last-child {
    white-space: normal;
    min-width: fit-content;
    width: auto;
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

.payments-table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid rgba(6, 182, 212, 0.1);
}

.payments-table tbody tr:hover {
    background: rgba(6, 182, 212, 0.05);
    transform: scale(1.01);
}

body.light-mode .payments-table tbody tr:hover {
    background: rgba(6, 182, 212, 0.03);
}

.payment-row.payment-completed {
    border-left: 4px solid #10b981;
}

.payment-row.payment-pending {
    border-left: 4px solid #fbbf24;
}

.payment-row.payment-processing {
    border-left: 4px solid #3b82f6;
}

.payment-row.payment-failed,
.payment-row.payment-refunded {
    border-left: 4px solid #ef4444;
    opacity: 0.85;
}

.payments-table td {
    padding: 1.25rem 1rem;
    vertical-align: middle;
    white-space: nowrap;
}

.payments-table td:last-child {
    white-space: normal;
    min-width: fit-content;
    width: auto;
}

.table-cell-content {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

/* Payment ID */
.payment-id {
    font-weight: 700;
    color: rgba(255, 255, 255, 0.8);
    font-family: monospace;
}

body.light-mode .payment-id {
    color: #1e293b;
}

/* User Info */
.user-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.user-avatar {
    width: 45px;
    height: 45px;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(37, 99, 235, 0.2));
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: #3b82f6;
    border: 2px solid rgba(59, 130, 246, 0.3);
    flex-shrink: 0;
}

.user-details {
    flex: 1;
}

.user-name {
    font-weight: 700;
    font-size: 1rem;
    color: white;
    margin-bottom: 0.25rem;
}

body.light-mode .user-name {
    color: #1e293b;
}

.user-email {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.7);
}

body.light-mode .user-email {
    color: #64748b;
}

/* Type Badge */
.type-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.75rem;
    border-radius: 10px;
    font-size: 0.85rem;
    font-weight: 700;
}

.type-subscription {
    background: rgba(16, 185, 129, 0.2);
    border: 1px solid rgba(16, 185, 129, 0.4);
    color: #10b981;
}

.type-course {
    background: rgba(251, 191, 36, 0.2);
    border: 1px solid rgba(251, 191, 36, 0.4);
    color: #fbbf24;
}

.type-donation {
    background: rgba(239, 68, 68, 0.2);
    border: 1px solid rgba(239, 68, 68, 0.4);
    color: #ef4444;
}

.type-other {
    background: rgba(107, 114, 128, 0.2);
    border: 1px solid rgba(107, 114, 128, 0.4);
    color: #6b7280;
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
    color: #3b82f6;
}

.amount-currency {
    font-size: 0.9rem;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.7);
}

body.light-mode .amount-currency {
    color: #64748b;
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

.payment-gateway {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.7);
}

body.light-mode .payment-gateway {
    color: #64748b;
}

.payment-gateway i {
    color: #06b6d4;
    font-size: 0.8rem;
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

.status-processing {
    background: rgba(59, 130, 246, 0.2);
    border: 1px solid rgba(59, 130, 246, 0.4);
    color: #3b82f6;
}

.status-failed {
    background: rgba(239, 68, 68, 0.2);
    border: 1px solid rgba(239, 68, 68, 0.4);
    color: #ef4444;
}

.status-refunded {
    background: rgba(139, 92, 246, 0.2);
    border: 1px solid rgba(139, 92, 246, 0.4);
    color: #8b5cf6;
}

/* Payment Reference */
.payment-reference {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-family: monospace;
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.8);
}

body.light-mode .payment-reference {
    color: #1e293b;
}

.payment-reference i {
    color: #06b6d4;
    font-size: 0.9rem;
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

.paid-cell {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: #10b981;
    margin-top: 0.25rem;
}

.paid-cell i {
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

.action-complete {
    background: rgba(16, 185, 129, 0.15);
    border: 1px solid rgba(16, 185, 129, 0.3);
    color: #10b981;
}

.action-complete:hover {
    background: rgba(16, 185, 129, 0.25);
    transform: translateY(-2px);
}

.action-reject {
    background: rgba(239, 68, 68, 0.15);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #ef4444;
}

.action-reject:hover {
    background: rgba(239, 68, 68, 0.25);
    transform: translateY(-2px);
}

.no-action {
    color: rgba(255, 255, 255, 0.5);
    font-size: 0.85rem;
}

body.light-mode .no-action {
    color: #94a3b8;
}

/* Failure Row */
.failure-row {
    background: rgba(239, 68, 68, 0.05);
}

body.light-mode .failure-row {
    background: rgba(239, 68, 68, 0.03);
}

.payment-failure-row {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    border-left: 3px solid rgba(239, 68, 68, 0.3);
}

.failure-icon {
    color: #ef4444;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.failure-content {
    flex: 1;
    color: rgba(255, 255, 255, 0.9);
}

body.light-mode .failure-content {
    color: #334155;
}

.failure-content strong {
    margin-right: 0.5rem;
    color: #ef4444;
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
    border: 2px dashed rgba(59, 130, 246, 0.3);
    border-radius: 24px;
}

body.light-mode .empty-state {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.8), rgba(248, 250, 252, 0.8));
    border-color: rgba(59, 130, 246, 0.4);
}

.empty-state-icon {
    width: 120px;
    height: 120px;
    margin: 0 auto 2rem;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(37, 99, 235, 0.1));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 4rem;
    color: rgba(59, 130, 246, 0.5);
    border: 3px dashed rgba(59, 130, 246, 0.3);
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

/* Modal */
.modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.7);
    z-index: 1000;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(4px);
}

.modal.active {
    display: flex;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.modal-content {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.95), rgba(51, 65, 85, 0.95));
    border: 2px solid rgba(6, 182, 212, 0.3);
    border-radius: 24px;
    padding: 0;
    max-width: 500px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    animation: slideUp 0.3s ease;
}

body.light-mode .modal-content {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.98), rgba(248, 250, 252, 0.98));
    border-color: rgba(6, 182, 212, 0.4);
}

@keyframes slideUp {
    from {
        transform: translateY(20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 2rem;
    border-bottom: 1px solid rgba(6, 182, 212, 0.2);
}

.modal-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: white;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

body.light-mode .modal-title {
    color: #1e293b;
}

.modal-title i {
    color: #ef4444;
}

.modal-close {
    background: transparent;
    border: none;
    color: rgba(255, 255, 255, 0.7);
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 8px;
    transition: all 0.2s;
}

body.light-mode .modal-close {
    color: #64748b;
}

.modal-close:hover {
    background: rgba(255, 255, 255, 0.1);
    color: white;
}

body.light-mode .modal-close:hover {
    background: rgba(0, 0, 0, 0.05);
    color: #1e293b;
}

.modal-body {
    padding: 2rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    color: rgba(255, 255, 255, 0.9);
    font-weight: 600;
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

body.light-mode .form-label {
    color: #1e293b;
}

.form-textarea {
    width: 100%;
    padding: 0.75rem 1rem;
    background: rgba(15, 23, 42, 0.6);
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 12px;
    color: white;
    font-size: 0.95rem;
    resize: vertical;
    font-family: inherit;
    transition: all 0.3s ease;
}

body.light-mode .form-textarea {
    background: rgba(255, 255, 255, 0.9);
    border-color: rgba(6, 182, 212, 0.3);
    color: #1e293b;
}

.form-textarea:focus {
    outline: none;
    border-color: #06b6d4;
    background: rgba(6, 182, 212, 0.1);
}

body.light-mode .form-textarea:focus {
    background: rgba(255, 255, 255, 1);
}

.modal-footer {
    display: flex;
    gap: 0.75rem;
    justify-content: flex-end;
    padding: 1.5rem 2rem;
    border-top: 1px solid rgba(6, 182, 212, 0.2);
}

.modal-btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.modal-btn-secondary {
    background: rgba(107, 114, 128, 0.2);
    border: 1px solid rgba(107, 114, 128, 0.4);
    color: white;
}

body.light-mode .modal-btn-secondary {
    background: rgba(107, 114, 128, 0.1);
    color: #475569;
}

.modal-btn-secondary:hover {
    background: rgba(107, 114, 128, 0.3);
}

.modal-btn-danger {
    background: rgba(239, 68, 68, 0.2);
    border: 1px solid rgba(239, 68, 68, 0.4);
    color: #ef4444;
}

.modal-btn-danger:hover {
    background: rgba(239, 68, 68, 0.3);
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 768px) {
    .payments-title {
        font-size: 1.75rem;
    }
    
    .payments-icon-wrapper {
        width: 50px;
        height: 50px;
    }
    
    .payments-icon {
        font-size: 1.5rem;
    }
    
    .payments-header-actions {
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
    
    .payments-table-wrapper {
        padding: 1rem;
    }
    
    .payments-table {
        font-size: 0.85rem;
    }
    
    .payments-table th,
    .payments-table td {
        padding: 0.75rem 0.5rem;
    }
    
    .user-avatar {
        width: 35px;
        height: 35px;
        font-size: 1rem;
    }
    
    .user-name {
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
