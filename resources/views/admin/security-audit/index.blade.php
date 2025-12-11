@extends('admin.layout')

@section('title', 'Audit de Sécurité')

@section('content')
<div class="security-audit-admin">
    <!-- Header Section -->
    <div class="audit-header">
        <div class="audit-header-content">
            <div class="audit-header-text">
                <h1 class="audit-title">
                    <span class="audit-icon-wrapper">
                        <i class="fas fa-shield-alt audit-icon"></i>
                    </span>
                    Audit de Sécurité
                </h1>
                <p class="audit-subtitle">
                    Surveillance et analyse des événements de sécurité
                </p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="audit-stats">
        <div class="stat-card stat-total">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-list"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($stats['total']) }}</div>
                <div class="stat-label">Total</div>
            </div>
        </div>
        <div class="stat-card stat-critical">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($stats['critical']) }}</div>
                <div class="stat-label">Critiques</div>
            </div>
        </div>
        <div class="stat-card stat-high">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($stats['high']) }}</div>
                <div class="stat-label">Élevées</div>
            </div>
        </div>
        <div class="stat-card stat-today">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-calendar-day"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($stats['today']) }}</div>
                <div class="stat-label">Aujourd'hui</div>
            </div>
        </div>
        <div class="stat-card stat-24h">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($stats['last_24h']) }}</div>
                <div class="stat-label">24h</div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filters-section">
        <div class="filters-header">
            <i class="fas fa-filter"></i>
            <span>Filtres de recherche</span>
        </div>
        <form method="GET" action="{{ route('admin.security-audit.index') }}" class="filters-form">
            <div class="filters-grid">
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-exclamation-triangle"></i>
                        Sévérité
                    </label>
                    <select name="severity" class="filter-select">
                        <option value="">Toutes</option>
                        <option value="critical" {{ request('severity') === 'critical' ? 'selected' : '' }}>Critique</option>
                        <option value="high" {{ request('severity') === 'high' ? 'selected' : '' }}>Élevée</option>
                        <option value="medium" {{ request('severity') === 'medium' ? 'selected' : '' }}>Moyenne</option>
                        <option value="low" {{ request('severity') === 'low' ? 'selected' : '' }}>Faible</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-tag"></i>
                        Type d'événement
                    </label>
                    <select name="event_type" class="filter-select">
                        <option value="">Tous</option>
                        @foreach(\App\Models\SecurityAudit::EVENT_TYPES as $key => $label)
                            <option value="{{ $key }}" {{ request('event_type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-network-wired"></i>
                        Adresse IP
                    </label>
                    <input type="text" name="ip_address" value="{{ request('ip_address') }}" placeholder="Ex: 192.168.1.1" class="filter-input">
                </div>
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-calendar-alt"></i>
                        Date début
                    </label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="filter-input">
                </div>
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-calendar-check"></i>
                        Date fin
                    </label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="filter-input">
                </div>
            </div>
            <div class="filters-actions">
                <button type="submit" class="btn-filter">
                    <i class="fas fa-search"></i>
                    Filtrer
                </button>
                <a href="{{ route('admin.security-audit.index') }}" class="btn-reset">
                    <i class="fas fa-redo"></i>
                    Réinitialiser
                </a>
                <a href="{{ route('admin.security-audit.export', request()->all()) }}" class="btn-export">
                    <i class="fas fa-download"></i>
                    Exporter CSV
                </a>
            </div>
        </form>
    </div>

    <!-- Top IPs Section -->
    @if($topIps->count() > 0)
    <div class="top-ips-section">
        <div class="section-header">
            <div class="section-header-left">
                <div class="section-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div>
                    <h3 class="section-title">Top 10 IPs suspectes</h3>
                    <p class="section-subtitle">7 derniers jours</p>
                </div>
            </div>
        </div>
        <div class="top-ips-list">
            @foreach($topIps as $index => $ip)
            <div class="top-ip-item">
                <div class="ip-rank">
                    <span class="rank-number">{{ $index + 1 }}</span>
                </div>
                <div class="ip-icon">
                    <i class="fas fa-network-wired"></i>
                </div>
                <div class="ip-info">
                    <h4 class="ip-address">{{ $ip->ip_address }}</h4>
                </div>
                <div class="ip-stats">
                    <div class="ip-events">
                        <span class="events-number">{{ number_format($ip->count) }}</span>
                        <span class="events-label">événements</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Audits Table -->
    <div class="audits-section">
        <div class="section-header">
            <div class="section-header-left">
                <div class="section-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <h3 class="section-title">Événements de sécurité</h3>
                    <p class="section-subtitle">Liste des audits enregistrés</p>
                </div>
            </div>
        </div>
        <div class="audits-table-wrapper">
            <table class="audits-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Sévérité</th>
                        <th>IP</th>
                        <th>Utilisateur</th>
                        <th>Route</th>
                        <th>Message</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($audits as $audit)
                    <tr class="audit-row severity-{{ $audit->severity }}">
                        <td>
                            <div class="audit-date">
                                <i class="fas fa-calendar"></i>
                                <span>{{ $audit->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="audit-time">
                                <i class="fas fa-clock"></i>
                                <span>{{ $audit->created_at->format('H:i:s') }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="audit-type">
                                <i class="fas fa-tag"></i>
                                <span>{{ \App\Models\SecurityAudit::EVENT_TYPES[$audit->event_type] ?? $audit->event_type }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="severity-badge severity-{{ $audit->severity }}">
                                <i class="fas fa-{{ $audit->severity === 'critical' ? 'exclamation-triangle' : ($audit->severity === 'high' ? 'exclamation-circle' : ($audit->severity === 'medium' ? 'info-circle' : 'check-circle')) }}"></i>
                                {{ ucfirst($audit->severity) }}
                            </span>
                        </td>
                        <td>
                            <div class="audit-ip">
                                <i class="fas fa-network-wired"></i>
                                <span>{{ $audit->ip_address }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="audit-user">
                                @if($audit->user)
                                    <i class="fas fa-user"></i>
                                    <span>{{ $audit->user->email }}</span>
                                @else
                                    <i class="fas fa-user-slash"></i>
                                    <span>Anonyme</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="audit-route">
                                <i class="fas fa-route"></i>
                                <span>{{ $audit->route ?? '-' }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="audit-message">
                                <i class="fas fa-comment"></i>
                                <span>{{ \Illuminate\Support\Str::limit($audit->message, 50) }}</span>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('admin.security-audit.show', $audit) }}" class="btn-view">
                                <i class="fas fa-eye"></i>
                                Voir
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="empty-state-cell">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <h3 class="empty-state-title">Aucun audit trouvé</h3>
                                <p class="empty-state-text">Aucun événement de sécurité ne correspond à vos critères</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($audits->hasPages())
        <div class="pagination-wrapper">
            {{ $audits->links() }}
        </div>
        @endif
    </div>
</div>

<style>
.security-audit-admin {
    padding: 2rem;
    max-width: 1800px;
    margin: 0 auto;
}

/* Header */
.audit-header {
    margin-bottom: 2rem;
}

.audit-header-content {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(249, 115, 22, 0.1) 100%);
    border: 2px solid rgba(239, 68, 68, 0.3);
    border-radius: 24px;
    padding: 2.5rem;
    position: relative;
    overflow: hidden;
}

body.light-mode .audit-header-content {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.08) 0%, rgba(249, 115, 22, 0.08) 100%);
    border-color: rgba(239, 68, 68, 0.4);
}

.audit-header-content::before {
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

.audit-header-text {
    position: relative;
    z-index: 1;
}

.audit-title {
    font-family: 'Poppins', sans-serif;
    font-size: 2.5rem;
    font-weight: 900;
    background: linear-gradient(135deg, #ef4444 0%, #f97316 50%, #ef4444 100%);
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

.audit-icon-wrapper {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(249, 115, 22, 0.2));
    border-radius: 16px;
    border: 2px solid rgba(239, 68, 68, 0.3);
}

.audit-icon {
    font-size: 1.8rem;
    color: #ef4444;
}

.audit-subtitle {
    font-size: 1.1rem;
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
}

body.light-mode .audit-subtitle {
    color: #64748b;
}

/* Stats Cards */
.audit-stats {
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

.stat-critical::before {
    background: linear-gradient(180deg, #ef4444, #dc2626);
}

.stat-high::before {
    background: linear-gradient(180deg, #f97316, #ea580c);
}

.stat-today::before {
    background: linear-gradient(180deg, #10b981, #059669);
}

.stat-24h::before {
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

.stat-critical .stat-icon {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(220, 38, 38, 0.2));
    color: #ef4444;
}

.stat-high .stat-icon {
    background: linear-gradient(135deg, rgba(249, 115, 22, 0.2), rgba(234, 88, 12, 0.2));
    color: #f97316;
}

.stat-today .stat-icon {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(5, 150, 105, 0.2));
    color: #10b981;
}

.stat-24h .stat-icon {
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
    font-size: 1rem;
    font-weight: 700;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 0.25rem;
}

body.light-mode .stat-label {
    color: #334155;
}

/* Filters Section */
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
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.filters-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: rgba(255, 255, 255, 0.8);
    font-weight: 600;
    font-size: 0.9rem;
}

body.light-mode .filter-label {
    color: #475569;
}

.filter-label i {
    color: #06b6d4;
}

.filter-select,
.filter-input {
    padding: 0.75rem 1rem;
    background: rgba(30, 41, 59, 0.95);
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 12px;
    color: white;
    font-size: 0.9rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

body.light-mode .filter-select,
body.light-mode .filter-input {
    background: rgba(255, 255, 255, 0.9);
    border-color: rgba(6, 182, 212, 0.3);
    color: #1e293b;
}

.filter-select:focus,
.filter-input:focus {
    outline: none;
    border-color: #06b6d4;
    background: rgba(30, 41, 59, 1);
    box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.2);
}

body.light-mode .filter-select:focus,
body.light-mode .filter-input:focus {
    background: rgba(255, 255, 255, 1);
    box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.15);
}

.filter-select option {
    background: rgba(30, 41, 59, 1);
    color: white;
    padding: 0.5rem;
}

body.light-mode .filter-select option {
    background: rgba(255, 255, 255, 1);
    color: #1e293b;
}

.filters-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.btn-filter,
.btn-reset,
.btn-export {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.9rem;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-filter {
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    color: white;
}

.btn-filter:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
}

.btn-reset {
    background: rgba(107, 114, 128, 0.2);
    border: 1px solid rgba(107, 114, 128, 0.4);
    color: white;
}

body.light-mode .btn-reset {
    background: rgba(107, 114, 128, 0.1);
    color: #475569;
}

.btn-reset:hover {
    background: rgba(107, 114, 128, 0.3);
    transform: translateY(-2px);
}

.btn-export {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.btn-export:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
}

/* Top IPs Section */
.top-ips-section {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8));
    border: 1px solid rgba(249, 115, 22, 0.3);
    border-radius: 24px;
    padding: 2rem;
    margin-bottom: 2rem;
}

body.light-mode .top-ips-section {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95));
    border-color: rgba(249, 115, 22, 0.3);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.section-header {
    margin-bottom: 1.5rem;
}

.section-header-left {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.section-icon {
    width: 50px;
    height: 50px;
    background: rgba(249, 115, 22, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #f97316;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 800;
    color: white;
    margin: 0 0 0.25rem 0;
}

body.light-mode .section-title {
    color: #1e293b;
}

.section-subtitle {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
}

body.light-mode .section-subtitle {
    color: #64748b;
}

.top-ips-list {
    display: grid;
    gap: 1rem;
}

.top-ip-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem;
    background: rgba(249, 115, 22, 0.05);
    border: 1px solid rgba(249, 115, 22, 0.2);
    border-radius: 16px;
    transition: all 0.3s ease;
}

body.light-mode .top-ip-item {
    background: rgba(249, 115, 22, 0.03);
}

.top-ip-item:hover {
    background: rgba(249, 115, 22, 0.1);
    transform: translateX(4px);
    border-color: rgba(249, 115, 22, 0.4);
}

.ip-rank {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #f97316, #ea580c);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.rank-number {
    font-size: 1.25rem;
    font-weight: 800;
    color: white;
}

.ip-icon {
    width: 50px;
    height: 50px;
    background: rgba(249, 115, 22, 0.1);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: #f97316;
    flex-shrink: 0;
}

.ip-info {
    flex: 1;
    min-width: 0;
}

.ip-address {
    font-size: 1.1rem;
    font-weight: 700;
    color: white;
    margin: 0;
    word-break: break-word;
}

body.light-mode .ip-address {
    color: #1e293b;
}

.ip-stats {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-shrink: 0;
}

.ip-events {
    text-align: right;
}

.events-number {
    display: block;
    font-size: 1.5rem;
    font-weight: 800;
    color: #f97316;
    line-height: 1;
}

.events-label {
    display: block;
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.6);
    margin-top: 0.25rem;
}

body.light-mode .events-label {
    color: #94a3b8;
}

/* Audits Section */
.audits-section {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8));
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 24px;
    padding: 2rem;
    margin-bottom: 2rem;
}

body.light-mode .audits-section {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95));
    border-color: rgba(6, 182, 212, 0.3);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.audits-table-wrapper {
    overflow-x: auto;
    margin-top: 1.5rem;
}

.audits-table-wrapper::-webkit-scrollbar {
    height: 8px;
}

.audits-table-wrapper::-webkit-scrollbar-track {
    background: rgba(15, 23, 42, 0.3);
    border-radius: 10px;
}

.audits-table-wrapper::-webkit-scrollbar-thumb {
    background: linear-gradient(180deg, #06b6d4, #14b8a6);
    border-radius: 10px;
}

.audits-table {
    width: 100%;
    border-collapse: collapse;
}

.audits-table thead {
    background: rgba(6, 182, 212, 0.1);
}

.audits-table th {
    padding: 1rem;
    text-align: left;
    font-weight: 700;
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.9);
    border-bottom: 2px solid rgba(6, 182, 212, 0.3);
    white-space: nowrap;
}

body.light-mode .audits-table th {
    color: #1e293b;
}

.audits-table tbody tr {
    border-bottom: 1px solid rgba(6, 182, 212, 0.1);
    transition: all 0.3s ease;
}

.audits-table tbody tr:hover {
    background: rgba(6, 182, 212, 0.05);
}

.audit-row.severity-critical {
    border-left: 4px solid #ef4444;
}

.audit-row.severity-high {
    border-left: 4px solid #f97316;
}

.audit-row.severity-medium {
    border-left: 4px solid #3b82f6;
}

.audit-row.severity-low {
    border-left: 4px solid #10b981;
}

.audits-table td {
    padding: 1rem;
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.8);
}

body.light-mode .audits-table td {
    color: #475569;
}

.audit-date,
.audit-time,
.audit-type,
.audit-ip,
.audit-user,
.audit-route,
.audit-message {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.audit-date i,
.audit-time i,
.audit-type i,
.audit-ip i,
.audit-user i,
.audit-route i,
.audit-message i {
    color: #06b6d4;
    font-size: 0.85rem;
}

.audit-time {
    margin-top: 0.25rem;
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.6);
}

body.light-mode .audit-time {
    color: #94a3b8;
}

.severity-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 700;
    white-space: nowrap;
}

.severity-critical {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.severity-high {
    background: rgba(249, 115, 22, 0.2);
    color: #f97316;
    border: 1px solid rgba(249, 115, 22, 0.3);
}

.severity-medium {
    background: rgba(59, 130, 246, 0.2);
    color: #3b82f6;
    border: 1px solid rgba(59, 130, 246, 0.3);
}

.severity-low {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.btn-view {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: rgba(6, 182, 212, 0.2);
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 8px;
    color: #06b6d4;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.85rem;
    transition: all 0.3s ease;
}

.btn-view:hover {
    background: rgba(6, 182, 212, 0.3);
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(6, 182, 212, 0.2);
}

.empty-state-cell {
    padding: 3rem 1rem;
    text-align: center;
}

.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
}

.empty-state-icon {
    width: 80px;
    height: 80px;
    background: rgba(6, 182, 212, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: rgba(6, 182, 212, 0.5);
}

.empty-state-title {
    font-size: 1.25rem;
    font-weight: 800;
    color: white;
    margin: 0;
}

body.light-mode .empty-state-title {
    color: #1e293b;
}

.empty-state-text {
    font-size: 0.95rem;
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
}

body.light-mode .empty-state-text {
    color: #64748b;
}

.pagination-wrapper {
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(6, 182, 212, 0.2);
}

/* Responsive */
@media (max-width: 768px) {
    .security-audit-admin {
        padding: 1rem;
    }
    
    .audit-title {
        font-size: 1.75rem;
    }
    
    .audit-icon-wrapper {
        width: 50px;
        height: 50px;
    }
    
    .audit-icon {
        font-size: 1.5rem;
    }
    
    .filters-grid {
        grid-template-columns: 1fr;
    }
    
    .filters-actions {
        flex-direction: column;
    }
    
    .btn-filter,
    .btn-reset,
    .btn-export {
        width: 100%;
        justify-content: center;
    }
    
    .audits-table-wrapper {
        overflow-x: scroll;
    }
    
    .audits-table {
        min-width: 1000px;
    }
}
</style>
@endsection
