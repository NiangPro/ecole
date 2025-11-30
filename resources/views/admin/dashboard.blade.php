@extends('admin.layout')

@section('title', 'Dashboard Admin')

@section('styles')
<style>
    /* Fonts chargées via preload dans admin.layout - pas de @import bloquant */
    
    .dashboard-hero {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(20, 184, 166, 0.1) 100%);
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 24px;
        padding: 40px;
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
        transition: background 0.3s ease, border-color 0.3s ease;
    }
    
    body.light-mode .dashboard-hero {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.08) 0%, rgba(20, 184, 166, 0.08) 100%);
        border-color: rgba(6, 182, 212, 0.4);
    }
    
    .dashboard-hero::before {
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
    
    .dashboard-hero-content {
        position: relative;
        z-index: 1;
    }
    
    .dashboard-hero h1 {
        font-family: 'Poppins', sans-serif;
        font-size: 2.5rem;
        font-weight: 900;
        background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 50%, #06b6d4 100%);
        background-size: 200% auto;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: shimmer 3s linear infinite;
        margin-bottom: 10px;
    }
    
    @keyframes shimmer {
        to { background-position: 200% center; }
    }
    
    .dashboard-hero p {
        font-size: 1.1rem;
        color: rgba(255, 255, 255, 0.7);
        transition: color 0.3s ease;
    }
    
    body.light-mode .dashboard-hero p {
        color: rgba(30, 41, 59, 0.8);
    }
    
    /* Alertes publicités expirantes */
    .expiring-ads-alert {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(220, 38, 38, 0.15));
        border: 2px solid rgba(239, 68, 68, 0.4);
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }
    
    .expiring-ads-alert::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(239, 68, 68, 0.1), transparent);
        animation: slide 3s infinite;
    }
    
    @keyframes slide {
        0% { left: -100%; }
        100% { left: 100%; }
    }
    
    .expiring-ads-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .expiring-ads-icon {
        width: 50px;
        height: 50px;
        background: rgba(239, 68, 68, 0.2);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #ef4444;
        border: 2px solid rgba(239, 68, 68, 0.4);
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    .expiring-ads-title {
        font-family: 'Poppins', sans-serif;
        font-size: 1.4rem;
        font-weight: 700;
        color: #ef4444;
    }
    
    .expiring-ads-list {
        display: grid;
        gap: 15px;
    }
    
    .expiring-ad-item {
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(239, 68, 68, 0.3);
        border-radius: 14px;
        padding: 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.3s ease;
    }
    
    body.light-mode .expiring-ad-item {
        background: rgba(255, 255, 255, 0.8);
        border-color: rgba(239, 68, 68, 0.4);
    }
    
    .expiring-ad-item:hover {
        background: rgba(15, 23, 42, 0.8);
        border-color: rgba(239, 68, 68, 0.5);
        transform: translateX(5px);
    }
    
    body.light-mode .expiring-ad-item:hover {
        background: rgba(255, 255, 255, 0.95);
        border-color: rgba(239, 68, 68, 0.6);
    }
    
    .expiring-ad-info {
        flex: 1;
    }
    
    .expiring-ad-name {
        font-weight: 700;
        color: #fff;
        margin-bottom: 5px;
        transition: color 0.3s ease;
    }
    
    body.light-mode .expiring-ad-name {
        color: #1e293b;
    }
    
    .expiring-ad-date {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.6);
        display: flex;
        align-items: center;
        gap: 8px;
        transition: color 0.3s ease;
    }
    
    body.light-mode .expiring-ad-date {
        color: rgba(30, 41, 59, 0.7);
    }
    
    .expiring-ad-badge {
        padding: 6px 14px;
        background: rgba(239, 68, 68, 0.2);
        color: #ef4444;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 700;
        border: 1px solid rgba(239, 68, 68, 0.4);
    }
    
    .expiring-ad-link {
        padding: 8px 16px;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: #fff;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .expiring-ad-link:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(239, 68, 68, 0.4);
    }
    
    /* Stats Cards Modernes */
    .stats-grid-modern {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin-bottom: 40px;
    }
    
    @media (max-width: 1400px) {
        .stats-grid-modern {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    @media (max-width: 1024px) {
        .stats-grid-modern {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 640px) {
        .stats-grid-modern {
            grid-template-columns: 1fr;
        }
    }
    
    .stat-card-modern {
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(20px);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 16px;
        padding: 20px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
    }
    
    body.light-mode .stat-card-modern {
        background: rgba(255, 255, 255, 0.8);
        border-color: rgba(6, 182, 212, 0.3);
    }
    
    .stat-card-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(6, 182, 212, 0.1), transparent);
        transition: left 0.6s;
    }
    
    .stat-card-modern:hover::before {
        left: 100%;
    }
    
    .stat-card-modern:hover {
        transform: translateY(-8px);
        border-color: rgba(6, 182, 212, 0.5);
        box-shadow: 0 20px 50px rgba(6, 182, 212, 0.3);
    }
    
    .stat-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }
    
    .stat-card-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
        border: 1px solid rgba(6, 182, 212, 0.3);
    }
    
    .stat-card-number {
        font-family: 'Poppins', sans-serif;
        font-size: 1.9rem;
        font-weight: 900;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        line-height: 1.2;
        margin-bottom: 6px;
    }
    
    .stat-card-label {
        color: rgba(255, 255, 255, 0.8);
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 4px;
        transition: color 0.3s ease;
    }
    
    body.light-mode .stat-card-label {
        color: rgba(30, 41, 59, 0.9);
    }
    
    .stat-card-subtext {
        color: rgba(255, 255, 255, 0.5);
        font-size: 0.75rem;
        line-height: 1.3;
        transition: color 0.3s ease;
    }
    
    body.light-mode .stat-card-subtext {
        color: rgba(30, 41, 59, 0.6);
    }
    
    /* Content Sections Modernes */
    .content-section-modern {
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(20px);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 24px;
        padding: 35px;
        transition: background 0.3s ease, border-color 0.3s ease;
    }
    
    body.light-mode .content-section-modern {
        background: rgba(255, 255, 255, 0.8);
        border-color: rgba(6, 182, 212, 0.3);
    }
        transition: all 0.3s ease;
        width: 100%;
        box-sizing: border-box;
    }
    
    .content-section-modern:hover {
        border-color: rgba(6, 182, 212, 0.4);
        box-shadow: 0 15px 40px rgba(6, 182, 212, 0.2);
    }
    
    .section-title-modern {
        font-family: 'Poppins', sans-serif;
        font-size: 1.5rem;
        font-weight: 800;
        color: #06b6d4;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .section-title-modern i {
        font-size: 1.3rem;
    }
    
    /* Quick Actions Modernes */
    .quick-actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }
    
    .quick-action-card {
        background: rgba(15, 23, 42, 0.6);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 16px;
        padding: 25px;
        text-decoration: none;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    body.light-mode .quick-action-card {
        background: rgba(255, 255, 255, 0.8);
        border-color: rgba(6, 182, 212, 0.3);
    }
    
    .quick-action-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(6, 182, 212, 0.1), transparent);
        transition: left 0.6s;
    }
    
    .quick-action-card:hover::before {
        left: 100%;
    }
    
    .quick-action-card:hover {
        transform: translateY(-8px) scale(1.02);
        border-color: rgba(6, 182, 212, 0.5);
        box-shadow: 0 20px 50px rgba(6, 182, 212, 0.3);
    }
    
    .quick-action-icon {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-bottom: 15px;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
        border: 1px solid rgba(6, 182, 212, 0.3);
    }
    
    .quick-action-title {
        font-weight: 700;
        color: #fff;
        margin-bottom: 5px;
        font-size: 1rem;
        transition: color 0.3s ease;
    }
    
    body.light-mode .quick-action-title {
        color: #1e293b;
    }
    
    .quick-action-desc {
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.85rem;
        transition: color 0.3s ease;
    }
    
    body.light-mode .quick-action-desc {
        color: rgba(30, 41, 59, 0.7);
    }
    
    /* Top Pages Modernes */
    .top-page-item {
        background: rgba(15, 23, 42, 0.5);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 14px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        transition: all 0.3s ease;
        margin-bottom: 12px;
    }
    
    body.light-mode .top-page-item {
        background: rgba(255, 255, 255, 0.8);
        border-color: rgba(6, 182, 212, 0.3);
    }
    
    .top-page-item:hover {
        background: rgba(15, 23, 42, 0.7);
        border-color: rgba(6, 182, 212, 0.4);
        transform: translateX(5px);
    }
    
    body.light-mode .top-page-item:hover {
        background: rgba(255, 255, 255, 0.95);
        border-color: rgba(6, 182, 212, 0.5);
    }
    
    .top-page-rank {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        font-size: 1.2rem;
        color: #000;
        flex-shrink: 0;
    }
    
    .top-page-info {
        flex: 1;
        min-width: 0;
    }
    
    .top-page-title {
        font-weight: 700;
        color: #fff;
        margin-bottom: 5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        transition: color 0.3s ease;
    }
    
    body.light-mode .top-page-title {
        color: #1e293b;
    }
    
    .top-page-url {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.5);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        transition: color 0.3s ease;
    }
    
    body.light-mode .top-page-url {
        color: rgba(30, 41, 59, 0.7);
    }
    
    .top-page-visits {
        font-size: 1.3rem;
        font-weight: 900;
        color: #06b6d4;
        flex-shrink: 0;
    }
    
    @media (max-width: 1024px) {
        .stats-grid-modern {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }
        
        .quick-actions-grid {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        }
    }
    
    /* Styles pour classes Tailwind en dark mode */
    body.light-mode .text-gray-400 {
        color: #64748b !important;
    }
    
    body.light-mode .text-gray-500 {
        color: #94a3b8 !important;
    }
    
    body.light-mode .text-white {
        color: #1e293b !important;
    }
    
    /* Styles pour Articles Récents et Plus Vus */
    .article-item-card {
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 12px;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.3s ease;
    }
    
    body.light-mode .article-item-card {
        background: rgba(255, 255, 255, 0.9);
        border-color: rgba(6, 182, 212, 0.3);
    }
    
    .article-item-card:hover {
        border-color: rgba(6, 182, 212, 0.4);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(6, 182, 212, 0.2);
    }
    
    body.light-mode .article-item-card:hover {
        box-shadow: 0 4px 12px rgba(6, 182, 212, 0.15);
    }
    
    .article-item-title {
        font-size: 1rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 5px;
        transition: color 0.3s ease;
    }
    
    body.light-mode .article-item-title {
        color: #1e293b;
    }
    
    .article-item-title a {
        color: inherit;
        text-decoration: none;
    }
    
    .article-item-title a:hover {
        color: #06b6d4;
    }
    
    .article-item-meta {
        display: flex;
        gap: 15px;
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.6);
        transition: color 0.3s ease;
    }
    
    body.light-mode .article-item-meta {
        color: rgba(30, 41, 59, 0.7);
    }
    
    .article-status-badge {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .article-status-published {
        background: rgba(34, 197, 94, 0.2);
        color: #22c55e;
    }
    
    .article-status-draft {
        background: rgba(239, 68, 68, 0.2);
        color: #ef4444;
    }
    
    .article-status-top {
        background: rgba(245, 158, 11, 0.2);
        color: #f59e0b;
    }
    
    /* Graphique des visites */
    .visits-chart-container {
        background: rgba(0, 0, 0, 0.3);
        border-radius: 16px;
        padding: 25px;
        height: 400px;
        width: 100%;
        transition: background 0.3s ease;
    }
    
    body.light-mode .visits-chart-container {
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(6, 182, 212, 0.2);
    }
    
    @media (max-width: 768px) {
        .dashboard-hero {
            padding: 25px;
        }
        
        .dashboard-hero h1 {
            font-size: 1.8rem;
        }
        
        .stats-grid-modern {
            grid-template-columns: 1fr;
        }
        
        .expiring-ad-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        
        .expiring-ad-link {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<div class="dashboard-hero">
    <div class="dashboard-hero-content">
        <h1><i class="fas fa-tachometer-alt mr-3"></i>Tableau de Bord</h1>
        <p>Bienvenue sur votre panneau d'administration - Gérez votre plateforme efficacement</p>
    </div>
</div>

<!-- Alertes Publicités Expirantes -->
@if(isset($expiringAds) && $expiringAds->count() > 0)
<div class="expiring-ads-alert">
    <div class="expiring-ads-header">
        <div class="expiring-ads-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div>
            <h3 class="expiring-ads-title">Publicités bientôt expirées</h3>
            <p style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem;">{{ $expiringAds->count() }} publicité(s) vont expirer dans les 7 prochains jours</p>
        </div>
    </div>
    <div class="expiring-ads-list">
        @foreach($expiringAds as $ad)
        <div class="expiring-ad-item">
            <div class="expiring-ad-info">
                <div class="expiring-ad-name">{{ $ad->name }}</div>
                <div class="expiring-ad-date">
                    <i class="fas fa-calendar-times"></i>
                    Expire le {{ $ad->end_date->format('d/m/Y') }} ({{ $ad->end_date->diffForHumans() }})
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 15px;">
                <span class="expiring-ad-badge">
                    {{ $ad->end_date->diffInDays(now()) }} jour(s)
                </span>
                <a href="{{ route('admin.ads.edit', $ad->id) }}" class="expiring-ad-link">
                    <i class="fas fa-edit"></i>
                    Modifier
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Statistiques Principales -->
<div class="stats-grid-modern">
    <div class="stat-card-modern">
        <div class="stat-card-header">
            <div class="stat-card-icon" style="color: #06b6d4;">
                <i class="fas fa-graduation-cap"></i>
            </div>
        </div>
        <div class="stat-card-number">8</div>
        <div class="stat-card-label">Formations</div>
        <div class="stat-card-subtext">Disponibles</div>
    </div>
    
    <div class="stat-card-modern">
        <div class="stat-card-header">
            <div class="stat-card-icon" style="color: #14b8a6;">
                <i class="fas fa-eye"></i>
            </div>
        </div>
        <div class="stat-card-number">{{ number_format($stats['today']['visits']) }}</div>
        <div class="stat-card-label">Visites aujourd'hui</div>
        <div class="stat-card-subtext">
            @if($stats['visitsGrowth'] > 0)
                <span style="color: #22c55e;">+{{ $stats['visitsGrowth'] }}%</span> vs hier
            @elseif($stats['visitsGrowth'] < 0)
                <span style="color: #ef4444;">{{ $stats['visitsGrowth'] }}%</span> vs hier
            @else
                Stable
            @endif
        </div>
    </div>
    
    <div class="stat-card-modern">
        <div class="stat-card-header">
            <div class="stat-card-icon" style="color: #a855f7;">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
        <div class="stat-card-number">{{ number_format($stats['thisMonth']['visits']) }}</div>
        <div class="stat-card-label">Visites ce mois</div>
        <div class="stat-card-subtext">
            @if($stats['monthGrowth'] > 0)
                <span style="color: #22c55e;">+{{ $stats['monthGrowth'] }}%</span> vs mois dernier
            @elseif($stats['monthGrowth'] < 0)
                <span style="color: #ef4444;">{{ $stats['monthGrowth'] }}%</span> vs mois dernier
            @else
                Stable
            @endif
        </div>
    </div>
    
    <div class="stat-card-modern">
        <div class="stat-card-header">
            <div class="stat-card-icon" style="color: #22c55e;">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="stat-card-number">{{ number_format($stats['totalUsers']) }}</div>
        <div class="stat-card-label">Utilisateurs</div>
        <div class="stat-card-subtext">{{ number_format($stats['activeUsers']) }} actifs</div>
    </div>
    
    <div class="stat-card-modern">
        <div class="stat-card-header">
            <div class="stat-card-icon" style="color: #3b82f6;">
                <i class="fas fa-envelope"></i>
            </div>
        </div>
        <div class="stat-card-number">{{ number_format($stats['totalNewsletter']) }}</div>
        <div class="stat-card-label">Newsletter</div>
        <div class="stat-card-subtext">{{ number_format($stats['activeNewsletter']) }} actifs</div>
    </div>
    
    <div class="stat-card-modern">
        <div class="stat-card-header">
            <div class="stat-card-icon" style="color: #f59e0b;">
                <i class="fas fa-ad"></i>
            </div>
        </div>
        <div class="stat-card-number">{{ number_format($stats['activeAds']) }}</div>
        <div class="stat-card-label">Publicités actives</div>
        <div class="stat-card-subtext">{{ number_format($stats['totalAds']) }} au total</div>
    </div>
    
    <div class="stat-card-modern">
        <div class="stat-card-header">
            <div class="stat-card-icon" style="color: #ec4899;">
                <i class="fas fa-file-alt"></i>
            </div>
        </div>
        <div class="stat-card-number">{{ number_format($stats['publishedArticles']) }}</div>
        <div class="stat-card-label">Articles publiés</div>
        <div class="stat-card-subtext">{{ number_format($stats['draftArticles']) }} brouillons</div>
    </div>
    
    <div class="stat-card-modern">
        <div class="stat-card-header">
            <div class="stat-card-icon" style="color: #10b981;">
                <i class="fas fa-folder"></i>
            </div>
        </div>
        <div class="stat-card-number">{{ number_format($stats['totalCategories']) }}</div>
        <div class="stat-card-label">Catégories actives</div>
        <div class="stat-card-subtext">Catégories disponibles</div>
    </div>
    
    @if($stats['unreadMessages'] > 0)
    <div class="stat-card-modern" style="border: 2px solid rgba(239, 68, 68, 0.4); background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(220, 38, 38, 0.1));">
        <div class="stat-card-header">
            <div class="stat-card-icon" style="color: #ef4444;">
                <i class="fas fa-envelope-open-text"></i>
            </div>
        </div>
        <div class="stat-card-number">{{ number_format($stats['unreadMessages']) }}</div>
        <div class="stat-card-label">Messages non lus</div>
        <div class="stat-card-subtext">
            <a href="{{ route('admin.messages') }}" style="color: #06b6d4; text-decoration: none;">Voir les messages</a>
        </div>
    </div>
    @endif
</div>

@if(isset($stats['recentArticles']) && $stats['recentArticles']->count() > 0)
<!-- Articles Récents -->
<div class="content-section-modern mb-8">
    <h4 class="section-title-modern">
        <i class="fas fa-clock"></i>
        Articles Récents
    </h4>
    <div style="display: grid; gap: 15px;">
        @foreach($stats['recentArticles'] as $article)
        <div class="article-item-card">
            <div style="flex: 1;">
                <h5 class="article-item-title">
                    <a href="{{ route('admin.jobs.articles.edit', $article->id) }}">{{ $article->title }}</a>
                </h5>
                <div class="article-item-meta">
                    <span><i class="fas fa-folder mr-1"></i>{{ $article->category->name ?? 'Non catégorisé' }}</span>
                    <span><i class="fas fa-eye mr-1"></i>{{ number_format($article->views) }} vues</span>
                    <span><i class="fas fa-calendar mr-1"></i>{{ $article->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
            <div>
                <span class="article-status-badge {{ $article->status === 'published' ? 'article-status-published' : 'article-status-draft' }}">
                    {{ $article->status === 'published' ? 'Publié' : 'Brouillon' }}
                </span>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

@if(isset($stats['topArticles']) && $stats['topArticles']->count() > 0)
<!-- Articles les Plus Vus -->
<div class="content-section-modern mb-8">
    <h4 class="section-title-modern">
        <i class="fas fa-fire"></i>
        Articles les Plus Vus
    </h4>
    <div style="display: grid; gap: 15px;">
        @foreach($stats['topArticles'] as $article)
        <div class="article-item-card">
            <div style="flex: 1;">
                <h5 class="article-item-title">
                    <a href="{{ route('admin.jobs.articles.edit', $article->id) }}">{{ $article->title }}</a>
                </h5>
                <div class="article-item-meta">
                    <span><i class="fas fa-folder mr-1"></i>{{ $article->category->name ?? 'Non catégorisé' }}</span>
                    <span><i class="fas fa-eye mr-1" style="color: #f59e0b;"></i><strong style="color: #f59e0b;">{{ number_format($article->views) }}</strong> vues</span>
                    <span><i class="fas fa-calendar mr-1"></i>{{ $article->published_at ? $article->published_at->format('d/m/Y') : $article->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
            <div>
                <span class="article-status-badge article-status-top">
                    🔥 Top
                </span>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Graphique des visites - Ligne entière -->
<div class="content-section-modern mb-8" style="width: 100%;">
    <h4 class="section-title-modern">
        <i class="fas fa-chart-area"></i>
        Visites (7 derniers jours)
    </h4>
    <div class="visits-chart-container">
        <canvas id="visitsChart" height="350" style="width: 100% !important; max-width: 100%;"></canvas>
    </div>
</div>

<!-- Top Pages - Ligne entière -->
<div class="content-section-modern mb-8">
    <h4 class="section-title-modern">
        <i class="fas fa-fire"></i>
        Pages les plus visitées
    </h4>
    <div>
        @php
            $topPages = \App\Models\Statistic::getTopPages(5, 'month');
        @endphp
        @forelse($topPages as $index => $page)
        <div class="top-page-item">
            <div class="top-page-rank">{{ $index + 1 }}</div>
            <div class="top-page-info">
                <div class="top-page-title">{{ $page->page_title ?? 'Sans titre' }}</div>
                <div class="top-page-url">{{ Str::limit($page->page_url, 50) }}</div>
            </div>
            <div class="top-page-visits">{{ number_format($page->visits) }}</div>
        </div>
        @empty
        <p class="text-center text-gray-400 py-8">Aucune donnée disponible</p>
        @endforelse
    </div>
</div>

<!-- Actions Rapides -->
@auth
@if(Auth::user()->isAdmin())
<div class="content-section-modern mb-8">
    <h4 class="section-title-modern">
        <i class="fas fa-bolt"></i>
        Actions Rapides
    </h4>
    <div class="quick-actions-grid">
        <a href="{{ route('admin.users') }}" class="quick-action-card">
            <div class="quick-action-icon" style="color: #06b6d4;">
                <i class="fas fa-users"></i>
            </div>
            <div class="quick-action-title">Utilisateurs</div>
            <div class="quick-action-desc">Gérer les comptes</div>
        </a>
        
        <a href="{{ route('admin.statistics') }}" class="quick-action-card">
            <div class="quick-action-icon" style="color: #a855f7;">
                <i class="fas fa-chart-bar"></i>
            </div>
            <div class="quick-action-title">Statistiques</div>
            <div class="quick-action-desc">Voir les analytics</div>
        </a>
        
        <a href="{{ route('admin.ads.index') }}" class="quick-action-card">
            <div class="quick-action-icon" style="color: #f59e0b;">
                <i class="fas fa-ad"></i>
            </div>
            <div class="quick-action-title">Publicités</div>
            <div class="quick-action-desc">Gérer les annonces</div>
        </a>
        
        <a href="{{ route('admin.adsense') }}" class="quick-action-card">
            <div class="quick-action-icon" style="color: #22c55e;">
                <i class="fab fa-google"></i>
            </div>
            <div class="quick-action-title">AdSense</div>
            <div class="quick-action-desc">Configuration</div>
        </a>
        
        <a href="{{ route('admin.newsletter.index') }}" class="quick-action-card">
            <div class="quick-action-icon" style="color: #3b82f6;">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="quick-action-title">Newsletter</div>
            <div class="quick-action-desc">{{ \App\Models\Newsletter::where('is_active', true)->count() }} abonnés</div>
        </a>
        
        <a href="{{ route('admin.messages') }}" class="quick-action-card">
            <div class="quick-action-icon" style="color: #ef4444;">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="quick-action-title">Messages</div>
            <div class="quick-action-desc">{{ \App\Models\ContactMessage::unread()->count() }} non lus</div>
        </a>
        
        <a href="{{ route('admin.jobs.articles.index') }}" class="quick-action-card">
            <div class="quick-action-icon" style="color: #06b6d4;">
                <i class="fas fa-newspaper"></i>
            </div>
            <div class="quick-action-title">Articles Emplois</div>
            <div class="quick-action-desc">Gérer les articles</div>
        </a>
        
        <a href="{{ route('admin.bing.submission') }}" class="quick-action-card">
            <div class="quick-action-icon" style="background: linear-gradient(135deg, #0078d4 0%, #106ebe 100%);">
                <i class="fab fa-microsoft"></i>
            </div>
            <div class="quick-action-content">
                <h4>Soumission Bing</h4>
                <p>Soumettre les URLs à Bing</p>
            </div>
        </a>
        <a href="{{ route('admin.settings') }}" class="quick-action-card">
            <div class="quick-action-icon" style="color: #f97316;">
                <i class="fas fa-cog"></i>
            </div>
            <div class="quick-action-title">Paramètres</div>
            <div class="quick-action-desc">Configuration</div>
        </a>
    </div>
</div>
@endif
@endauth

<!-- Statistiques par pays et navigateurs -->
<div class="grid md:grid-cols-2 gap-6">
    <!-- Top pays -->
    <div class="content-section-modern">
        <h4 class="section-title-modern">
            <i class="fas fa-globe"></i>
            Top 5 Pays
        </h4>
        <div>
            @php
                $topCountries = \App\Models\Statistic::getByCountry('month', null, null);
            @endphp
            @foreach($topCountries->take(5) as $country)
            <div class="top-page-item">
                <div class="top-page-rank" style="background: linear-gradient(135deg, #06b6d4, #14b8a6);">
                    <span style="font-size: 1.5rem;">{{ \App\Services\GeoIPService::getCountryFlag($country->country) }}</span>
                </div>
                <div class="top-page-info">
                    <div class="top-page-title">{{ \App\Services\GeoIPService::getCountryName($country->country) }}</div>
                </div>
                <div class="top-page-visits">{{ number_format($country->visits) }}</div>
            </div>
            @endforeach
        </div>
    </div>
    
    <!-- Top navigateurs -->
    <div class="content-section-modern">
        <h4 class="section-title-modern">
            <i class="fas fa-browser"></i>
            Top Navigateurs
        </h4>
        <div>
            @php
                $topBrowsers = \App\Models\Statistic::getByBrowser('month', null, null);
            @endphp
            @foreach($topBrowsers as $browser)
            <div class="top-page-item">
                <div class="top-page-rank" style="background: linear-gradient(135deg, #a855f7, #9333ea);">
                    <i class="fab fa-{{ strtolower($browser->browser) == 'chrome' ? 'chrome' : (strtolower($browser->browser) == 'firefox' ? 'firefox' : (strtolower($browser->browser) == 'safari' ? 'safari' : 'internet-explorer')) }}"></i>
                </div>
                <div class="top-page-info">
                    <div class="top-page-title">{{ $browser->browser }}</div>
                </div>
                <div class="top-page-visits">{{ number_format($browser->visits) }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('visitsChart');
    if (ctx) {
        @php
            $last7Days = \App\Models\Statistic::getDailyStats(7);
        @endphp
        
        const dailyStats = @json($last7Days);
        
        const labels = dailyStats.map(stat => {
            const date = new Date(stat.date);
            return date.toLocaleDateString('fr-FR', { day: '2-digit', month: 'short' });
        });
        
        const data = dailyStats.map(stat => stat.visits);
        
        // Détecter le mode clair/sombre
        const isLightMode = document.body.classList.contains('light-mode');
        
        // Couleurs adaptées au mode
        const textColor = isLightMode ? '#64748b' : '#9ca3af';
        const gridColor = isLightMode ? 'rgba(100, 116, 139, 0.2)' : 'rgba(156, 163, 175, 0.1)';
        const tooltipBg = isLightMode ? 'rgba(255, 255, 255, 0.95)' : 'rgba(0, 0, 0, 0.9)';
        const tooltipTextColor = isLightMode ? '#1e293b' : '#fff';
        const pointBorderColor = isLightMode ? '#fff' : '#fff';
        
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Visites',
                    data: data,
                    borderColor: '#06b6d4',
                    backgroundColor: isLightMode ? 'rgba(6, 182, 212, 0.15)' : 'rgba(6, 182, 212, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#06b6d4',
                    pointBorderColor: pointBorderColor,
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 0,
                        right: 0,
                        top: 0,
                        bottom: 0
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: tooltipBg,
                        titleColor: '#06b6d4',
                        bodyColor: tooltipTextColor,
                        borderColor: '#06b6d4',
                        borderWidth: 2,
                        padding: 15,
                        displayColors: false,
                        cornerRadius: 10
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: textColor,
                            precision: 0,
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            color: gridColor
                        }
                    },
                    x: {
                        ticks: {
                            color: textColor,
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            color: gridColor
                        }
                    }
                }
            }
        });
        
        // Mettre à jour le graphique quand le mode change
        document.addEventListener('DOMContentLoaded', function() {
            const darkModeToggle = document.getElementById('dark-mode-toggle');
            if (darkModeToggle) {
                darkModeToggle.addEventListener('click', function() {
                    setTimeout(function() {
                        const isLight = document.body.classList.contains('light-mode');
                        const newTextColor = isLight ? '#64748b' : '#9ca3af';
                        const newGridColor = isLight ? 'rgba(100, 116, 139, 0.2)' : 'rgba(156, 163, 175, 0.1)';
                        const newTooltipBg = isLight ? 'rgba(255, 255, 255, 0.95)' : 'rgba(0, 0, 0, 0.9)';
                        const newTooltipTextColor = isLight ? '#1e293b' : '#fff';
                        const newBgColor = isLight ? 'rgba(6, 182, 212, 0.15)' : 'rgba(6, 182, 212, 0.1)';
                        
                        if (chart) {
                            chart.data.datasets[0].backgroundColor = newBgColor;
                            chart.options.scales.y.ticks.color = newTextColor;
                            chart.options.scales.x.ticks.color = newTextColor;
                            chart.options.scales.y.grid.color = newGridColor;
                            chart.options.scales.x.grid.color = newGridColor;
                            chart.options.plugins.tooltip.backgroundColor = newTooltipBg;
                            chart.options.plugins.tooltip.bodyColor = newTooltipTextColor;
                            chart.update();
                        }
                    }, 100);
                });
            }
        });
    }
</script>
@endsection
