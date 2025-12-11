@extends('admin.layout')

@section('title', 'Détails de l\'Abonnement #' . $subscription->id . ' - Admin')

@section('content')
<div class="subscription-detail-admin">
    <!-- Header Section -->
    <div class="subscription-detail-header">
        <a href="{{ route('admin.monetization.subscriptions') }}" class="back-link">
            <i class="fas fa-arrow-left"></i>
            <span>Retour aux abonnements</span>
        </a>
        <div class="subscription-detail-header-content">
            <div class="subscription-detail-header-text">
                <h1 class="subscription-detail-title">
                    <span class="subscription-detail-icon-wrapper">
                        <i class="fas fa-crown subscription-detail-icon"></i>
                    </span>
                    Abonnement #{{ $subscription->id }}
                </h1>
                <div class="subscription-detail-status-badge status-{{ $subscription->status }}">
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

    <!-- Main Content Grid -->
    <div class="subscription-detail-grid">
        <!-- Informations principales -->
        <div class="subscription-detail-card subscription-main-card">
            <div class="card-header">
                <div class="card-header-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <h2 class="card-title">Informations de l'abonnement</h2>
            </div>
            
            <div class="card-body">
                <div class="info-section">
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-tag"></i>
                            <span>Plan</span>
                        </div>
                        <div class="info-value">
                            <span class="plan-badge plan-{{ $subscription->plan_type }}">
                                {{ $subscription->plan_type === 'monthly' ? 'Mensuel' : 'Annuel' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-money-bill-wave"></i>
                            <span>Montant</span>
                        </div>
                        <div class="info-value price-value">
                            {{ number_format($subscription->amount, 0, ',', ' ') }} {{ $subscription->currency }}
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-play-circle"></i>
                            <span>Date de début</span>
                        </div>
                        <div class="info-value">
                            {{ $subscription->start_date ? $subscription->start_date->format('d/m/Y') : 'N/A' }}
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-stop-circle"></i>
                            <span>Date de fin</span>
                        </div>
                        <div class="info-value">
                            {{ $subscription->end_date ? $subscription->end_date->format('d/m/Y') : 'Illimité' }}
                        </div>
                    </div>
                    
                    @if($subscription->next_billing_date)
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-calendar-check"></i>
                            <span>Prochain paiement</span>
                        </div>
                        <div class="info-value">
                            {{ $subscription->next_billing_date->format('d/m/Y') }}
                        </div>
                    </div>
                    @endif
                    
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-credit-card"></i>
                            <span>Méthode de paiement</span>
                        </div>
                        <div class="info-value">
                            {{ ucfirst(str_replace('_', ' ', $subscription->payment_method ?? 'N/A')) }}
                        </div>
                    </div>
                    
                    @if($subscription->payment_reference)
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-hashtag"></i>
                            <span>Référence de paiement</span>
                        </div>
                        <div class="info-value reference-value">
                            {{ $subscription->payment_reference }}
                        </div>
                    </div>
                    @endif
                    
                    @if($subscription->notes)
                    <div class="info-item full-width">
                        <div class="info-label">
                            <i class="fas fa-sticky-note"></i>
                            <span>Notes</span>
                        </div>
                        <div class="info-value notes-value">
                            {{ $subscription->notes }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Informations utilisateur -->
        <div class="subscription-detail-card subscription-user-card">
            <div class="card-header">
                <div class="card-header-icon">
                    <i class="fas fa-user"></i>
                </div>
                <h2 class="card-title">Utilisateur</h2>
            </div>
            
            <div class="card-body">
                @if($subscription->user)
                <div class="user-section">
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="user-info">
                        <h3 class="user-name">{{ $subscription->user->name }}</h3>
                        <p class="user-email">{{ $subscription->user->email }}</p>
                        <div class="user-premium-badge">
                            @if($subscription->user->is_premium)
                                <i class="fas fa-crown"></i>
                                <span>Premium</span>
                            @else
                                <i class="fas fa-user"></i>
                                <span>Standard</span>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('admin.users.edit', $subscription->user->id) }}" class="user-profile-btn">
                        <i class="fas fa-external-link-alt"></i>
                        <span>Voir le profil</span>
                    </a>
                </div>
                @else
                <div class="user-not-found">
                    <i class="fas fa-user-slash"></i>
                    <p>Utilisateur non trouvé</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Paiement associé -->
    @if($subscription->payment)
    <div class="subscription-detail-card subscription-payment-card">
        <div class="card-header">
            <div class="card-header-icon">
                <i class="fas fa-receipt"></i>
            </div>
            <h2 class="card-title">Paiement associé</h2>
        </div>
        
        <div class="card-body">
            <div class="payment-details-grid">
                <div class="payment-detail-item">
                    <div class="payment-detail-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="payment-detail-content">
                        <div class="payment-detail-label">Statut</div>
                        <div class="payment-detail-value">
                            @if($subscription->payment->status === 'completed')
                                <span class="payment-status-badge status-completed">
                                    <i class="fas fa-check"></i>
                                    <span>Complété</span>
                                </span>
                            @elseif($subscription->payment->status === 'pending')
                                <span class="payment-status-badge status-pending">
                                    <i class="fas fa-clock"></i>
                                    <span>En attente</span>
                                </span>
                            @else
                                <span class="payment-status-badge status-failed">
                                    <i class="fas fa-times"></i>
                                    <span>Échoué</span>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="payment-detail-item">
                    <div class="payment-detail-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="payment-detail-content">
                        <div class="payment-detail-label">Montant</div>
                        <div class="payment-detail-value payment-amount">
                            {{ number_format($subscription->payment->amount, 0, ',', ' ') }} {{ $subscription->payment->currency }}
                        </div>
                    </div>
                </div>
                <div class="payment-detail-item">
                    <div class="payment-detail-icon">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <div class="payment-detail-content">
                        <div class="payment-detail-label">Date</div>
                        <div class="payment-detail-value">
                            {{ $subscription->payment->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Actions -->
    <div class="subscription-detail-card subscription-actions-card">
        <div class="card-header">
            <div class="card-header-icon">
                <i class="fas fa-bolt"></i>
            </div>
            <h2 class="card-title">Actions</h2>
        </div>
        
        <div class="card-body">
            <div class="actions-grid">
                @if($subscription->status === 'pending')
                <form action="{{ route('admin.monetization.subscriptions.activate', $subscription->id) }}" method="POST" class="action-form">
                    @csrf
                    <button type="submit" class="action-btn action-activate">
                        <i class="fas fa-check-circle"></i>
                        <span>Activer l'abonnement</span>
                    </button>
                </form>
                @endif
                
                @if($subscription->status === 'active')
                <button type="button" onclick="showCancelModal()" class="action-btn action-cancel">
                    <i class="fas fa-times-circle"></i>
                    <span>Annuler l'abonnement</span>
                </button>
                @endif
                
                <a href="{{ route('admin.monetization.subscriptions') }}" class="action-btn action-back">
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour annuler -->
<div id="cancelModal" class="cancel-modal">
    <div class="cancel-modal-content">
        <div class="cancel-modal-header">
            <div class="cancel-modal-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 class="cancel-modal-title">Annuler l'abonnement</h3>
        </div>
        <form method="POST" action="{{ route('admin.monetization.subscriptions.deactivate', $subscription->id) }}" class="cancel-modal-form">
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
.subscription-detail-admin {
    padding: 2rem;
    max-width: 1600px;
    margin: 0 auto;
}

/* Back Link */
.back-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: #06b6d4;
    text-decoration: none;
    font-weight: 600;
    margin-bottom: 1.5rem;
    transition: all 0.3s ease;
}

.back-link:hover {
    color: #14b8a6;
    transform: translateX(-4px);
}

/* Header */
.subscription-detail-header {
    margin-bottom: 2rem;
}

.subscription-detail-header-content {
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(20, 184, 166, 0.1) 100%);
    border: 2px solid rgba(6, 182, 212, 0.3);
    border-radius: 24px;
    padding: 2.5rem;
    position: relative;
    overflow: hidden;
}

body.light-mode .subscription-detail-header-content {
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.08) 0%, rgba(20, 184, 166, 0.08) 100%);
    border-color: rgba(6, 182, 212, 0.4);
}

.subscription-detail-header-content::before {
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

.subscription-detail-header-text {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1.5rem;
}

.subscription-detail-title {
    font-family: 'Poppins', sans-serif;
    font-size: 2.5rem;
    font-weight: 900;
    background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 50%, #06b6d4 100%);
    background-size: 200% auto;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: shimmer 3s linear infinite;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 1rem;
}

@keyframes shimmer {
    to { background-position: 200% center; }
}

.subscription-detail-icon-wrapper {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
    border-radius: 16px;
    border: 2px solid rgba(6, 182, 212, 0.3);
}

.subscription-detail-icon {
    font-size: 1.8rem;
    color: #06b6d4;
}

.subscription-detail-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 700;
}

.status-active {
    background: rgba(16, 185, 129, 0.2);
    border: 2px solid rgba(16, 185, 129, 0.4);
    color: #10b981;
}

.status-pending {
    background: rgba(251, 191, 36, 0.2);
    border: 2px solid rgba(251, 191, 36, 0.4);
    color: #fbbf24;
}

.status-cancelled {
    background: rgba(239, 68, 68, 0.2);
    border: 2px solid rgba(239, 68, 68, 0.4);
    color: #ef4444;
}

.status-expired {
    background: rgba(107, 114, 128, 0.2);
    border: 2px solid rgba(107, 114, 128, 0.4);
    color: #6b7280;
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

/* Grid */
.subscription-detail-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

@media (max-width: 1024px) {
    .subscription-detail-grid {
        grid-template-columns: 1fr;
    }
}

/* Cards */
.subscription-detail-card {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8));
    border: 2px solid rgba(6, 182, 212, 0.3);
    border-radius: 24px;
    padding: 2rem;
    transition: all 0.3s ease;
}

body.light-mode .subscription-detail-card {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95));
    border-color: rgba(6, 182, 212, 0.3);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.subscription-detail-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(6, 182, 212, 0.3);
}

.card-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid rgba(6, 182, 212, 0.2);
}

.card-header-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: #06b6d4;
}

.card-title {
    font-size: 1.5rem;
    font-weight: 800;
    color: white;
    margin: 0;
}

body.light-mode .card-title {
    color: #1e293b;
}

.card-body {
    display: block;
}

/* Info Section */
.info-section {
    display: grid;
    gap: 1.5rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.info-item.full-width {
    grid-column: 1 / -1;
}

.info-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.9rem;
    font-weight: 600;
}

body.light-mode .info-label {
    color: #64748b;
}

.info-value {
    font-size: 1.1rem;
    font-weight: 700;
    color: white;
}

body.light-mode .info-value {
    color: #1e293b;
}

.price-value {
    color: #06b6d4;
    font-size: 1.5rem;
}

.plan-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 12px;
    font-size: 0.9rem;
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
    font-size: 1rem;
    color: rgba(255, 255, 255, 0.8);
}

body.light-mode .reference-value {
    color: #475569;
}

.notes-value {
    background: rgba(15, 23, 42, 0.5);
    padding: 1rem;
    border-radius: 12px;
    white-space: pre-wrap;
    line-height: 1.6;
    font-size: 0.95rem;
}

body.light-mode .notes-value {
    background: rgba(6, 182, 212, 0.05);
}

/* User Section */
.user-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 1rem;
}

.user-avatar {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: #06b6d4;
    border: 3px solid rgba(6, 182, 212, 0.3);
}

.user-info {
    flex: 1;
}

.user-name {
    font-size: 1.5rem;
    font-weight: 800;
    color: white;
    margin: 0 0 0.5rem 0;
}

body.light-mode .user-name {
    color: #1e293b;
}

.user-email {
    font-size: 1rem;
    color: rgba(255, 255, 255, 0.7);
    margin: 0 0 1rem 0;
}

body.light-mode .user-email {
    color: #64748b;
}

.user-premium-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 12px;
    font-size: 0.9rem;
    font-weight: 700;
    background: rgba(251, 191, 36, 0.2);
    border: 1px solid rgba(251, 191, 36, 0.4);
    color: #fbbf24;
    margin-bottom: 1rem;
}

.user-profile-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    color: white;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
}

.user-profile-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(6, 182, 212, 0.4);
}

.user-not-found {
    text-align: center;
    padding: 2rem;
    color: rgba(255, 255, 255, 0.7);
}

.user-not-found i {
    font-size: 3rem;
    margin-bottom: 1rem;
    color: rgba(6, 182, 212, 0.5);
}

/* Payment Section */
.payment-details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.payment-detail-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: rgba(6, 182, 212, 0.05);
    border-radius: 12px;
}

body.light-mode .payment-detail-item {
    background: rgba(6, 182, 212, 0.03);
}

.payment-detail-icon {
    width: 50px;
    height: 50px;
    background: rgba(6, 182, 212, 0.1);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: #06b6d4;
}

.payment-detail-content {
    flex: 1;
}

.payment-detail-label {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.6);
    margin-bottom: 0.5rem;
}

body.light-mode .payment-detail-label {
    color: #94a3b8;
}

.payment-detail-value {
    font-size: 1.1rem;
    font-weight: 700;
    color: white;
}

body.light-mode .payment-detail-value {
    color: #1e293b;
}

.payment-amount {
    color: #06b6d4;
    font-size: 1.25rem;
}

.payment-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 12px;
    font-size: 0.9rem;
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

/* Actions */
.actions-grid {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.action-form {
    flex: 1;
    min-width: 200px;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 1rem 2rem;
    border: none;
    border-radius: 12px;
    font-weight: 700;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    width: 100%;
}

.action-activate {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
}

.action-activate:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
}

.action-cancel {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
}

.action-cancel:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
}

.action-back {
    background: rgba(107, 114, 128, 0.2);
    border: 1px solid rgba(107, 114, 128, 0.4);
    color: white;
}

body.light-mode .action-back {
    background: rgba(107, 114, 128, 0.1);
    color: #475569;
}

.action-back:hover {
    background: rgba(107, 114, 128, 0.3);
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
    .subscription-detail-title {
        font-size: 1.75rem;
    }
    
    .subscription-detail-icon-wrapper {
        width: 50px;
        height: 50px;
    }
    
    .subscription-detail-icon {
        font-size: 1.5rem;
    }
    
    .subscription-detail-status-badge {
        font-size: 0.85rem;
        padding: 0.5rem 1rem;
    }
    
    .actions-grid {
        flex-direction: column;
    }
    
    .action-btn {
        width: 100%;
    }
}
</style>

<script>
function showCancelModal() {
    document.getElementById('cancelModal').style.display = 'flex';
}

function closeCancelModal() {
    document.getElementById('cancelModal').style.display = 'none';
    document.querySelector('#cancelModal textarea').value = '';
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
