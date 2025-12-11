@extends('admin.layout')

@section('title', 'Gestion des Plans d\'Abonnement - Admin')

@section('content')
<div class="subscription-plans-admin">
    <!-- Header Section -->
    <div class="plans-header">
        <div class="plans-header-content">
            <div class="plans-header-text">
                <h1 class="plans-title">
                    <span class="plans-icon-wrapper">
                        <i class="fas fa-crown plans-icon"></i>
                    </span>
                    Plans d'Abonnement
                </h1>
                <p class="plans-subtitle">
                    Gérez et configurez les plans d'abonnement premium de votre plateforme
                </p>
            </div>
            <a href="{{ route('admin.monetization.subscription-plans.create') }}" class="create-plan-btn">
                <i class="fas fa-plus-circle"></i>
                <span>Créer un Plan</span>
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
    @if($plans->count() > 0)
    <div class="plans-stats">
        <div class="stat-card">
            <div class="stat-icon stat-total">
                <i class="fas fa-list"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $plans->count() }}</div>
                <div class="stat-label">Plans Total</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-active">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $plans->where('is_active', true)->count() }}</div>
                <div class="stat-label">Plans Actifs</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-featured">
                <i class="fas fa-star"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $plans->where('is_featured', true)->count() }}</div>
                <div class="stat-label">Plans Mis en Avant</div>
            </div>
        </div>
    </div>
    @endif

    <!-- Plans Grid -->
    @if($plans->count() > 0)
    <div class="plans-grid">
        @foreach($plans as $plan)
        <div class="plan-card {{ $plan->is_featured ? 'plan-featured' : '' }} {{ !$plan->is_active ? 'plan-inactive' : '' }}">
            <!-- Card Header -->
            <div class="plan-card-header">
                <div class="plan-header-left">
                    <div class="plan-icon-circle">
                        <i class="fas fa-crown"></i>
                    </div>
                    <div class="plan-title-section">
                        <h3 class="plan-name">{{ $plan->name }}</h3>
                        <p class="plan-slug">{{ $plan->slug }}</p>
                    </div>
                </div>
                <div class="plan-badges">
                    @if($plan->is_featured)
                    <span class="badge badge-featured">
                        <i class="fas fa-star"></i>
                        <span>Mis en avant</span>
                    </span>
                    @endif
                    @if($plan->is_active)
                    <span class="badge badge-active">
                        <i class="fas fa-check-circle"></i>
                        <span>Actif</span>
                    </span>
                    @else
                    <span class="badge badge-inactive">
                        <i class="fas fa-pause-circle"></i>
                        <span>Inactif</span>
                    </span>
                    @endif
                    @if($plan->badge)
                    <span class="badge badge-custom">
                        {{ $plan->badge }}
                    </span>
                    @endif
                </div>
            </div>

            <!-- Price Section -->
            <div class="plan-price-section">
                <div class="plan-price-main">
                    <span class="plan-price-amount">{{ number_format($plan->price, 0, ',', ' ') }}</span>
                    <span class="plan-price-currency">{{ $plan->currency }}</span>
                </div>
                <div class="plan-price-period">
                    <i class="fas fa-calendar-alt"></i>
                    <span>{{ $plan->billing_period === 'yearly' ? 'par an' : 'par mois' }}</span>
                </div>
            </div>

            <!-- Description -->
            @if($plan->description)
            <div class="plan-description">
                <p>{{ $plan->description }}</p>
            </div>
            @endif

            <!-- Features -->
            @if(!empty($plan->features))
            <div class="plan-features">
                <div class="plan-features-header">
                    <i class="fas fa-list-check"></i>
                    <span>Fonctionnalités ({{ count($plan->features) }})</span>
                </div>
                <ul class="plan-features-list">
                    @foreach($plan->features as $feature)
                    <li class="plan-feature-item">
                        <i class="fas fa-check feature-check"></i>
                        <span>{{ $feature }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Plan Details Grid -->
            <div class="plan-details-grid">
                <div class="plan-detail-item">
                    <div class="detail-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="detail-content">
                        <div class="detail-label">Durée</div>
                        <div class="detail-value">{{ $plan->duration_days }} jours</div>
                    </div>
                </div>
                <div class="plan-detail-item">
                    <div class="detail-icon">
                        <i class="fas fa-sort-numeric-up"></i>
                    </div>
                    <div class="detail-content">
                        <div class="detail-label">Ordre</div>
                        <div class="detail-value">#{{ $plan->order }}</div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="plan-actions">
                <a href="{{ route('admin.monetization.subscription-plans.show', $plan->id) }}" class="action-btn action-view">
                    <i class="fas fa-eye"></i>
                    <span>Voir</span>
                </a>
                <a href="{{ route('admin.monetization.subscription-plans.edit', $plan->id) }}" class="action-btn action-edit">
                    <i class="fas fa-edit"></i>
                    <span>Modifier</span>
                </a>
                <form action="{{ route('admin.monetization.subscription-plans.destroy', $plan->id) }}" method="POST" class="action-form" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce plan ?');">
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
    @else
    <!-- Empty State -->
    <div class="empty-state">
        <div class="empty-state-icon">
            <i class="fas fa-crown"></i>
        </div>
        <h3 class="empty-state-title">Aucun plan d'abonnement</h3>
        <p class="empty-state-text">
            Créez votre premier plan d'abonnement pour commencer à monétiser votre plateforme.
        </p>
        <a href="{{ route('admin.monetization.subscription-plans.create') }}" class="empty-state-btn">
            <i class="fas fa-plus-circle"></i>
            <span>Créer un Plan</span>
        </a>
    </div>
    @endif
</div>

<style>
.subscription-plans-admin {
    padding: 2rem;
    max-width: 1600px;
    margin: 0 auto;
}

/* Header */
.plans-header {
    margin-bottom: 2rem;
}

.plans-header-content {
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

body.light-mode .plans-header-content {
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.08) 0%, rgba(20, 184, 166, 0.08) 100%);
    border-color: rgba(6, 182, 212, 0.4);
}

.plans-header-content::before {
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

.plans-header-text {
    position: relative;
    z-index: 1;
}

.plans-title {
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

.plans-icon-wrapper {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
    border-radius: 16px;
    border: 2px solid rgba(6, 182, 212, 0.3);
}

.plans-icon {
    font-size: 1.8rem;
    color: #06b6d4;
}

.plans-subtitle {
    font-size: 1.1rem;
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
}

body.light-mode .plans-subtitle {
    color: #64748b;
}

.create-plan-btn {
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

.create-plan-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(6, 182, 212, 0.6);
}

.create-plan-btn i {
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
.plans-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
}

body.light-mode .stat-card {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95));
    border-color: rgba(6, 182, 212, 0.3);
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(6, 182, 212, 0.3);
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

.stat-total {
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
    color: #06b6d4;
}

.stat-active {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(5, 150, 105, 0.2));
    color: #10b981;
}

.stat-featured {
    background: linear-gradient(135deg, rgba(251, 191, 36, 0.2), rgba(245, 158, 11, 0.2));
    color: #fbbf24;
}

.stat-content {
    flex: 1;
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

/* Plans Grid */
.plans-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 2rem;
}

@media (max-width: 768px) {
    .plans-grid {
        grid-template-columns: 1fr;
    }
}

/* Plan Card */
.plan-card {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8));
    border: 2px solid rgba(6, 182, 212, 0.3);
    border-radius: 24px;
    padding: 2rem;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

body.light-mode .plan-card {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95));
    border-color: rgba(6, 182, 212, 0.3);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.plan-card::before {
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

.plan-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(6, 182, 212, 0.4);
    border-color: rgba(6, 182, 212, 0.6);
}

.plan-card:hover::before {
    opacity: 1;
}

.plan-featured {
    border-color: rgba(251, 191, 36, 0.5);
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.9), rgba(51, 65, 85, 0.9));
}

body.light-mode .plan-featured {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.98), rgba(252, 248, 240, 0.98));
}

.plan-featured::before {
    background: linear-gradient(90deg, #fbbf24, #f59e0b);
}

.plan-inactive {
    opacity: 0.7;
}

.plan-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    gap: 1rem;
}

.plan-header-left {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex: 1;
}

.plan-icon-circle {
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

.plan-title-section {
    flex: 1;
}

.plan-name {
    font-size: 1.5rem;
    font-weight: 800;
    color: white;
    margin: 0 0 0.25rem 0;
}

body.light-mode .plan-name {
    color: #1e293b;
}

.plan-slug {
    font-family: 'Courier New', monospace;
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.5);
    margin: 0;
}

body.light-mode .plan-slug {
    color: #94a3b8;
}

.plan-badges {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    align-items: flex-end;
}

.badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.4rem 0.75rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 700;
    white-space: nowrap;
}

.badge-featured {
    background: rgba(251, 191, 36, 0.2);
    border: 1px solid rgba(251, 191, 36, 0.4);
    color: #fbbf24;
}

.badge-active {
    background: rgba(16, 185, 129, 0.2);
    border: 1px solid rgba(16, 185, 129, 0.4);
    color: #10b981;
}

.badge-inactive {
    background: rgba(107, 114, 128, 0.2);
    border: 1px solid rgba(107, 114, 128, 0.4);
    color: #6b7280;
}

.badge-custom {
    background: rgba(6, 182, 212, 0.2);
    border: 1px solid rgba(6, 182, 212, 0.4);
    color: #06b6d4;
}

/* Price Section */
.plan-price-section {
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.1));
    border: 1px solid rgba(6, 182, 212, 0.2);
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    text-align: center;
}

body.light-mode .plan-price-section {
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.08), rgba(20, 184, 166, 0.08));
    border-color: rgba(6, 182, 212, 0.3);
}

.plan-price-main {
    display: flex;
    align-items: baseline;
    justify-content: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.plan-price-amount {
    font-size: 2.5rem;
    font-weight: 900;
    color: #06b6d4;
    line-height: 1;
}

.plan-price-currency {
    font-size: 1.25rem;
    color: rgba(255, 255, 255, 0.7);
    font-weight: 600;
}

body.light-mode .plan-price-currency {
    color: #64748b;
}

.plan-price-period {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.9rem;
}

body.light-mode .plan-price-period {
    color: #94a3b8;
}

/* Description */
.plan-description {
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: rgba(6, 182, 212, 0.05);
    border-left: 3px solid rgba(6, 182, 212, 0.3);
    border-radius: 8px;
}

body.light-mode .plan-description {
    background: rgba(6, 182, 212, 0.05);
}

.plan-description p {
    margin: 0;
    color: rgba(255, 255, 255, 0.8);
    line-height: 1.6;
}

body.light-mode .plan-description p {
    color: #475569;
}

/* Features */
.plan-features {
    margin-bottom: 1.5rem;
}

.plan-features-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
    color: rgba(255, 255, 255, 0.7);
    font-weight: 600;
    font-size: 0.9rem;
}

body.light-mode .plan-features-header {
    color: #64748b;
}

.plan-features-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    gap: 0.75rem;
}

.plan-feature-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: rgba(255, 255, 255, 0.9);
    font-size: 0.9rem;
}

body.light-mode .plan-feature-item {
    color: #334155;
}

.feature-check {
    color: #10b981;
    font-size: 0.85rem;
}

/* Details Grid */
.plan-details-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: rgba(6, 182, 212, 0.05);
    border-radius: 12px;
}

body.light-mode .plan-details-grid {
    background: rgba(6, 182, 212, 0.03);
}

.plan-detail-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
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

/* Actions */
.plan-actions {
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

.action-edit {
    background: rgba(6, 182, 212, 0.15);
    border: 1px solid rgba(6, 182, 212, 0.3);
    color: #06b6d4;
}

.action-edit:hover {
    background: rgba(6, 182, 212, 0.25);
    transform: translateY(-2px);
}

.action-delete {
    background: rgba(239, 68, 68, 0.15);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #ef4444;
    width: 100%;
}

.action-delete:hover {
    background: rgba(239, 68, 68, 0.25);
    transform: translateY(-2px);
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
    .plans-title {
        font-size: 1.75rem;
    }
    
    .plans-icon-wrapper {
        width: 50px;
        height: 50px;
    }
    
    .plans-icon {
        font-size: 1.5rem;
    }
    
    .create-plan-btn {
        width: 100%;
        justify-content: center;
    }
    
    .plan-card-header {
        flex-direction: column;
    }
    
    .plan-badges {
        flex-direction: row;
        align-items: flex-start;
        flex-wrap: wrap;
    }
    
    .plan-actions {
        flex-direction: column;
    }
    
    .action-btn {
        width: 100%;
    }
}
</style>
@endsection
