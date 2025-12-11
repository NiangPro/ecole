@extends('admin.layout')

@section('title', 'Statistiques du site')

@section('content')
<div class="statistics-admin">
    <!-- Header Section -->
    <div class="statistics-header">
        <div class="statistics-header-content">
            <div class="statistics-header-text">
                <h1 class="statistics-title">
                    <span class="statistics-icon-wrapper">
                        <i class="fas fa-chart-line statistics-icon"></i>
                    </span>
                    Statistiques du site
                </h1>
                <p class="statistics-subtitle">
                    Analysez les performances et le trafic de votre site
                </p>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filters-section">
        <div class="filters-header">
            <i class="fas fa-filter"></i>
            <span>Filtres de période</span>
        </div>
        <div class="filters-content">
            <div class="filter-tabs">
                <a href="{{ route('admin.statistics', ['filter' => 'day', 'year' => $year]) }}" 
                   class="filter-tab {{ $filter == 'day' ? 'filter-tab-active' : '' }}">
                    <i class="fas fa-calendar-day"></i>
                    <span>Jour</span>
                </a>
                <a href="{{ route('admin.statistics', ['filter' => 'month', 'year' => $year]) }}" 
                   class="filter-tab {{ $filter == 'month' ? 'filter-tab-active' : '' }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Mois</span>
                </a>
                <a href="{{ route('admin.statistics', ['filter' => 'year', 'year' => $year]) }}" 
                   class="filter-tab {{ $filter == 'year' ? 'filter-tab-active' : '' }}">
                    <i class="fas fa-calendar"></i>
                    <span>Année</span>
                </a>
            </div>
            
            @if($availableYears->count() > 0)
            <div class="filter-selects">
                <div class="filter-select-group">
                    <label class="filter-select-label">
                        <i class="fas fa-calendar"></i>
                        Année
                    </label>
                    <select onchange="window.location.href='{{ route('admin.statistics') }}?filter={{ $filter }}&year=' + this.value + '&month={{ $month }}'" 
                            class="filter-select">
                        @foreach($availableYears as $availableYear)
                            <option value="{{ $availableYear }}" {{ $year == $availableYear ? 'selected' : '' }}>
                                {{ $availableYear }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                @if($filter == 'month')
                <div class="filter-select-group">
                    <label class="filter-select-label">
                        <i class="fas fa-calendar-check"></i>
                        Mois
                    </label>
                    <select onchange="window.location.href='{{ route('admin.statistics') }}?filter=month&year={{ $year }}&month=' + this.value" 
                            class="filter-select">
                        <option value="1" {{ $month == 1 ? 'selected' : '' }}>Janvier</option>
                        <option value="2" {{ $month == 2 ? 'selected' : '' }}>Février</option>
                        <option value="3" {{ $month == 3 ? 'selected' : '' }}>Mars</option>
                        <option value="4" {{ $month == 4 ? 'selected' : '' }}>Avril</option>
                        <option value="5" {{ $month == 5 ? 'selected' : '' }}>Mai</option>
                        <option value="6" {{ $month == 6 ? 'selected' : '' }}>Juin</option>
                        <option value="7" {{ $month == 7 ? 'selected' : '' }}>Juillet</option>
                        <option value="8" {{ $month == 8 ? 'selected' : '' }}>Août</option>
                        <option value="9" {{ $month == 9 ? 'selected' : '' }}>Septembre</option>
                        <option value="10" {{ $month == 10 ? 'selected' : '' }}>Octobre</option>
                        <option value="11" {{ $month == 11 ? 'selected' : '' }}>Novembre</option>
                        <option value="12" {{ $month == 12 ? 'selected' : '' }}>Décembre</option>
                    </select>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="statistics-stats">
        <div class="stat-card stat-visits">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-eye"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($totalVisits) }}</div>
                <div class="stat-label">Visites totales</div>
                <div class="stat-period">
                    @if($filter == 'day') Aujourd'hui
                    @elseif($filter == 'month') Ce mois
                    @else Cette année
                    @endif
                </div>
            </div>
        </div>
        
        <div class="stat-card stat-unique">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($uniqueVisitors) }}</div>
                <div class="stat-label">Visiteurs uniques</div>
                <div class="stat-period">Basé sur IP</div>
            </div>
        </div>
        
        <div class="stat-card stat-pages">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($totalPages) }}</div>
                <div class="stat-label">Pages vues</div>
                <div class="stat-period">Pages différentes</div>
            </div>
        </div>
        
        <div class="stat-card stat-average">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $avgPerDay }}</div>
                <div class="stat-label">Moyenne / jour</div>
                <div class="stat-period">Visites quotidiennes</div>
            </div>
        </div>
        
        <div class="stat-card stat-average-month">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($avgPerMonth ?? 0) }}</div>
                <div class="stat-label">Moyenne / mois</div>
                <div class="stat-period">Visites mensuelles</div>
            </div>
        </div>
        
        <div class="stat-card stat-average-year">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-calendar"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($avgPerYear ?? 0) }}</div>
                <div class="stat-label">Moyenne / an</div>
                <div class="stat-period">Visites annuelles</div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="charts-section">
        <!-- Main Chart -->
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-header-left">
                    <div class="chart-icon">
                        <i class="fas fa-chart-area"></i>
                    </div>
                    <div>
                        <h3 class="chart-title">
                            @if($filter == 'year')
                                Évolution des visites par mois ({{ $year }})
                            @else
                                Évolution des visites (30 derniers jours)
                            @endif
                        </h3>
                        <p class="chart-subtitle">Tendance des visites sur la période sélectionnée</p>
                    </div>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="visitsChart"></canvas>
            </div>
        </div>

        <!-- Weekly Chart -->
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-header-left">
                    <div class="chart-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div>
                        <h3 class="chart-title">Vues du mois actuel par semaine</h3>
                        <p class="chart-subtitle">Répartition hebdomadaire des visites</p>
                    </div>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="weeklyChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Pages Section -->
    <div class="top-pages-section">
        <div class="section-header">
            <div class="section-header-left">
                <div class="section-icon">
                    <i class="fas fa-fire"></i>
                </div>
                <div>
                    <h3 class="section-title">Pages les plus visitées</h3>
                    <p class="section-subtitle">Les pages les plus populaires de votre site</p>
                </div>
            </div>
        </div>
        <div class="top-pages-list">
            @forelse($topPages as $index => $page)
            <div class="top-page-item">
                <div class="page-rank">
                    <span class="rank-number">{{ $index + 1 }}</span>
                </div>
                <div class="page-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="page-info">
                    <h4 class="page-title">{{ $page->page_title ?? 'Sans titre' }}</h4>
                    <p class="page-url">{{ $page->page_url }}</p>
                </div>
                <div class="page-stats">
                    <div class="page-visits">
                        <span class="visits-number">{{ number_format($page->visits) }}</span>
                        <span class="visits-label">visites</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h3 class="empty-state-title">Aucune donnée disponible</h3>
                <p class="empty-state-text">Aucune donnée disponible pour cette période</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Analytics Grid -->
    <div class="analytics-grid">
        <!-- Browsers -->
        <div class="analytics-card">
            <div class="analytics-header">
                <div class="analytics-icon">
                    <i class="fas fa-window-maximize"></i>
                </div>
                <h3 class="analytics-title">Navigateurs</h3>
            </div>
            <div class="analytics-list">
                @forelse($browsersStats as $browser)
                <div class="analytics-item">
                    <div class="analytics-item-left">
                        <i class="fab fa-{{ strtolower($browser->browser) == 'chrome' ? 'chrome' : (strtolower($browser->browser) == 'firefox' ? 'firefox' : (strtolower($browser->browser) == 'safari' ? 'safari' : (strtolower($browser->browser) == 'edge' ? 'edge' : 'internet-explorer'))) }} browser-icon"></i>
                        <span class="analytics-item-label">{{ $browser->browser }}</span>
                    </div>
                    <span class="analytics-item-value">{{ number_format($browser->visits) }}</span>
                </div>
                @empty
                <div class="analytics-empty">
                    <i class="fas fa-inbox"></i>
                    <span>Aucune donnée</span>
                </div>
                @endforelse
            </div>
        </div>
        
        <!-- Countries -->
        <div class="analytics-card">
            <div class="analytics-header">
                <div class="analytics-icon">
                    <i class="fas fa-globe"></i>
                </div>
                <h3 class="analytics-title">Pays des visiteurs</h3>
            </div>
            <div class="analytics-list">
                @forelse($countriesStats as $country)
                <div class="analytics-item">
                    <div class="analytics-item-left">
                        <span class="country-flag">{{ \App\Services\GeoIPService::getCountryFlag($country->country) }}</span>
                        <span class="analytics-item-label">{{ \App\Services\GeoIPService::getCountryName($country->country) }}</span>
                    </div>
                    <span class="analytics-item-value">{{ number_format($country->visits) }}</span>
                </div>
                @empty
                <div class="analytics-empty">
                    <i class="fas fa-inbox"></i>
                    <span>Aucune donnée</span>
                </div>
                @endforelse
            </div>
        </div>
        
        <!-- Sources -->
        <div class="analytics-card">
            <div class="analytics-header">
                <div class="analytics-icon">
                    <i class="fas fa-share-alt"></i>
                </div>
                <h3 class="analytics-title">Sources de trafic</h3>
            </div>
            <div class="analytics-list">
                @forelse($sourcesStats as $source)
                <div class="analytics-item">
                    <div class="analytics-item-left">
                        <i class="fas fa-{{ $source->source == 'Direct' ? 'link' : (strtolower($source->source) == 'google' ? 'search' : (strtolower($source->source) == 'facebook' ? 'facebook' : (strtolower($source->source) == 'twitter' ? 'twitter' : 'globe'))) }} source-icon"></i>
                        <span class="analytics-item-label">{{ $source->source }}</span>
                    </div>
                    <span class="analytics-item-value">{{ number_format($source->visits) }}</span>
                </div>
                @empty
                <div class="analytics-empty">
                    <i class="fas fa-inbox"></i>
                    <span>Aucune donnée</span>
                </div>
                @endforelse
            </div>
        </div>
        
        <!-- Devices -->
        <div class="analytics-card">
            <div class="analytics-header">
                <div class="analytics-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3 class="analytics-title">Appareils utilisés</h3>
            </div>
            <div class="analytics-list">
                @forelse($devicesStats as $device)
                <div class="analytics-item">
                    <div class="analytics-item-left">
                        <i class="fas fa-{{ strtolower($device->device) == 'mobile' ? 'mobile-alt' : (strtolower($device->device) == 'tablet' ? 'tablet-alt' : 'desktop') }} device-icon"></i>
                        <span class="analytics-item-label">{{ ucfirst($device->device) }}</span>
                    </div>
                    <span class="analytics-item-value">{{ number_format($device->visits) }}</span>
                </div>
                @empty
                <div class="analytics-empty">
                    <i class="fas fa-inbox"></i>
                    <span>Aucune donnée</span>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
.statistics-admin {
    padding: 2rem;
    max-width: 1600px;
    margin: 0 auto;
}

/* Header */
.statistics-header {
    margin-bottom: 2rem;
}

.statistics-header-content {
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(20, 184, 166, 0.1) 100%);
    border: 2px solid rgba(6, 182, 212, 0.3);
    border-radius: 24px;
    padding: 2.5rem;
    position: relative;
    overflow: hidden;
}

body.light-mode .statistics-header-content {
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.08) 0%, rgba(20, 184, 166, 0.08) 100%);
    border-color: rgba(6, 182, 212, 0.4);
}

.statistics-header-content::before {
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

.statistics-header-text {
    position: relative;
    z-index: 1;
}

.statistics-title {
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

.statistics-icon-wrapper {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
    border-radius: 16px;
    border: 2px solid rgba(6, 182, 212, 0.3);
}

.statistics-icon {
    font-size: 1.8rem;
    color: #06b6d4;
}

.statistics-subtitle {
    font-size: 1.1rem;
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
}

body.light-mode .statistics-subtitle {
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
    
.filters-content {
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.filter-tabs {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.filter-tab {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: rgba(107, 114, 128, 0.2);
    border: 1px solid rgba(107, 114, 128, 0.4);
    border-radius: 12px;
    color: white;
    text-decoration: none;
        font-weight: 600;
    font-size: 0.9rem;
        transition: all 0.3s ease;
    }
    
body.light-mode .filter-tab {
    background: rgba(107, 114, 128, 0.1);
    color: #475569;
}

.filter-tab:hover {
    background: rgba(107, 114, 128, 0.3);
    transform: translateY(-2px);
}

.filter-tab-active {
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    border-color: rgba(6, 182, 212, 0.5);
    color: white;
    box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
}

.filter-selects {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.filter-select-group {
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 0.75rem;
    min-width: 200px;
}

.filter-select-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: rgba(255, 255, 255, 0.8);
        font-weight: 600;
    font-size: 0.9rem;
    white-space: nowrap;
}

body.light-mode .filter-select-label {
    color: #475569;
}

.filter-select {
    padding: 0.75rem 1rem;
    background: rgba(30, 41, 59, 0.95);
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 12px;
    color: white;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    min-width: 150px;
}

body.light-mode .filter-select {
        background: rgba(255, 255, 255, 0.9);
        border-color: rgba(6, 182, 212, 0.3);
    color: #1e293b;
    }
    
.filter-select:focus {
    outline: none;
        border-color: #06b6d4;
    background: rgba(30, 41, 59, 1);
    box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.2);
}

body.light-mode .filter-select:focus {
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
    
/* Stats Cards */
.statistics-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
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

.stat-visits::before {
    background: linear-gradient(180deg, #06b6d4, #14b8a6);
}

.stat-unique::before {
    background: linear-gradient(180deg, #10b981, #059669);
}

.stat-pages::before {
    background: linear-gradient(180deg, #8b5cf6, #7c3aed);
}

.stat-average::before {
    background: linear-gradient(180deg, #f59e0b, #d97706);
}

.stat-average-month::before {
    background: linear-gradient(180deg, #3b82f6, #2563eb);
}

.stat-average-year::before {
    background: linear-gradient(180deg, #ec4899, #db2777);
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

.stat-visits .stat-icon {
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
    color: #06b6d4;
}

.stat-unique .stat-icon {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(5, 150, 105, 0.2));
    color: #10b981;
}

.stat-pages .stat-icon {
    background: linear-gradient(135deg, rgba(139, 92, 246, 0.2), rgba(124, 58, 237, 0.2));
    color: #8b5cf6;
}

.stat-average .stat-icon {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.2), rgba(217, 119, 6, 0.2));
    color: #f59e0b;
}

.stat-average-month .stat-icon {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(37, 99, 235, 0.2));
    color: #3b82f6;
}

.stat-average-year .stat-icon {
    background: linear-gradient(135deg, rgba(236, 72, 153, 0.2), rgba(219, 39, 119, 0.2));
    color: #ec4899;
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

.stat-period {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.6);
}

body.light-mode .stat-period {
    color: #94a3b8;
}

/* Charts Section */
.charts-section {
    display: grid;
    gap: 2rem;
    margin-bottom: 2rem;
}

.chart-card {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8));
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 24px;
    padding: 2rem;
    transition: all 0.3s ease;
}

body.light-mode .chart-card {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95));
    border-color: rgba(6, 182, 212, 0.3);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.chart-header {
    margin-bottom: 1.5rem;
}

.chart-header-left {
        display: flex;
        align-items: center;
    gap: 1rem;
}

.chart-icon {
    width: 50px;
    height: 50px;
    background: rgba(6, 182, 212, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #06b6d4;
}

.chart-title {
    font-size: 1.5rem;
    font-weight: 800;
    color: white;
    margin: 0 0 0.25rem 0;
}

body.light-mode .chart-title {
        color: #1e293b;
    }
    
.chart-subtitle {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
}

body.light-mode .chart-subtitle {
    color: #64748b;
}

.chart-container {
    position: relative;
    height: 300px;
    background: rgba(0, 0, 0, 0.2);
    border-radius: 12px;
    padding: 1rem;
}

body.light-mode .chart-container {
    background: rgba(6, 182, 212, 0.05);
    border: 1px solid rgba(6, 182, 212, 0.2);
}

/* Top Pages */
.top-pages-section {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8));
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 24px;
    padding: 2rem;
    margin-bottom: 2rem;
}

body.light-mode .top-pages-section {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95));
    border-color: rgba(6, 182, 212, 0.3);
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
    background: rgba(251, 146, 60, 0.2);
    border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    font-size: 1.5rem;
    color: #fb923c;
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

.top-pages-list {
    display: grid;
    gap: 1rem;
    max-height: 500px;
    overflow-y: auto;
    padding-right: 0.5rem;
}

.top-pages-list::-webkit-scrollbar {
    width: 8px;
}

.top-pages-list::-webkit-scrollbar-track {
    background: rgba(15, 23, 42, 0.3);
    border-radius: 10px;
}

.top-pages-list::-webkit-scrollbar-thumb {
    background: linear-gradient(180deg, #06b6d4, #14b8a6);
    border-radius: 10px;
}

.top-page-item {
    display: flex;
    align-items: center;
            gap: 1rem;
    padding: 1.25rem;
    background: rgba(6, 182, 212, 0.05);
    border: 1px solid rgba(6, 182, 212, 0.2);
    border-radius: 16px;
    transition: all 0.3s ease;
}

body.light-mode .top-page-item {
    background: rgba(6, 182, 212, 0.03);
}

.top-page-item:hover {
    background: rgba(6, 182, 212, 0.1);
    transform: translateX(4px);
    border-color: rgba(6, 182, 212, 0.4);
}

.page-rank {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
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

.page-icon {
    width: 50px;
    height: 50px;
    background: rgba(6, 182, 212, 0.1);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: #06b6d4;
    flex-shrink: 0;
}

.page-info {
    flex: 1;
    min-width: 0;
}

.page-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: white;
    margin: 0 0 0.25rem 0;
    word-break: break-word;
}

body.light-mode .page-title {
    color: #1e293b;
}

.page-url {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.6);
    margin: 0;
            word-break: break-all;
        }
        
body.light-mode .page-url {
    color: #94a3b8;
}

.page-stats {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-shrink: 0;
}

.page-visits {
    text-align: right;
}

.visits-number {
    display: block;
            font-size: 1.5rem;
    font-weight: 800;
    color: #06b6d4;
    line-height: 1;
}

.visits-label {
    display: block;
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.6);
    margin-top: 0.25rem;
}

body.light-mode .visits-label {
    color: #94a3b8;
}

/* Analytics Grid */
.analytics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.analytics-card {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8));
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 24px;
    padding: 2rem;
    transition: all 0.3s ease;
}

body.light-mode .analytics-card {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95));
    border-color: rgba(6, 182, 212, 0.3);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.analytics-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid rgba(6, 182, 212, 0.2);
}

.analytics-icon {
    width: 50px;
    height: 50px;
    background: rgba(6, 182, 212, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #06b6d4;
}

.analytics-title {
    font-size: 1.25rem;
    font-weight: 800;
    color: white;
    margin: 0;
}

body.light-mode .analytics-title {
    color: #1e293b;
}

.analytics-list {
    display: grid;
            gap: 0.75rem;
    max-height: 400px;
    overflow-y: auto;
    padding-right: 0.5rem;
}

.analytics-list::-webkit-scrollbar {
    width: 6px;
}

.analytics-list::-webkit-scrollbar-track {
    background: rgba(15, 23, 42, 0.3);
    border-radius: 10px;
}

.analytics-list::-webkit-scrollbar-thumb {
    background: rgba(6, 182, 212, 0.5);
    border-radius: 10px;
}

.analytics-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem;
    background: rgba(6, 182, 212, 0.05);
    border-radius: 12px;
    transition: all 0.3s ease;
}

body.light-mode .analytics-item {
    background: rgba(6, 182, 212, 0.03);
}

.analytics-item:hover {
    background: rgba(6, 182, 212, 0.1);
    transform: translateX(4px);
}

.analytics-item-left {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.country-flag {
    font-size: 1.5rem;
}

.browser-icon,
.source-icon,
.device-icon {
    font-size: 1.25rem;
    color: rgba(255, 255, 255, 0.7);
    width: 24px;
    text-align: center;
}

body.light-mode .browser-icon,
body.light-mode .source-icon,
body.light-mode .device-icon {
    color: #64748b;
}

.analytics-item-label {
    font-weight: 600;
    color: white;
}

body.light-mode .analytics-item-label {
    color: #1e293b;
}

.analytics-item-value {
    font-size: 1.1rem;
    font-weight: 800;
    color: #06b6d4;
}

.analytics-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 3rem 1rem;
    color: rgba(255, 255, 255, 0.6);
    gap: 0.5rem;
}

body.light-mode .analytics-empty {
    color: #94a3b8;
}

.analytics-empty i {
    font-size: 2rem;
    opacity: 0.5;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
}

.empty-state-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem;
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
    margin-bottom: 0.5rem;
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

/* Responsive */
@media (max-width: 768px) {
    .statistics-title {
        font-size: 1.75rem;
    }
    
    .statistics-icon-wrapper {
        width: 50px;
        height: 50px;
    }
    
    .statistics-icon {
        font-size: 1.5rem;
    }
    
    .filter-tabs {
        flex-direction: column;
    }
    
    .filter-tab {
        width: 100%;
        justify-content: center;
    }
    
    .filter-selects {
        flex-direction: column;
    }
    
    .filter-select-group {
        width: 100%;
        flex-direction: column;
        align-items: flex-start;
    }
    
    .chart-container {
        height: 250px !important;
    }
    
    .top-page-item {
        flex-wrap: wrap;
    }
    
    .analytics-grid {
        grid-template-columns: 1fr;
    }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const visitsChartElement = document.getElementById('visitsChart');
        if (!visitsChartElement) return;
        
        const ctx = visitsChartElement.getContext('2d');
    const dailyStats = @json($dailyStats);
    const filter = '{{ $filter }}';
    
    let labels, data;
    
    if (filter === 'year') {
        const monthNames = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
        const monthlyData = new Array(12).fill(0);
        
        dailyStats.forEach(stat => {
            monthlyData[stat.month - 1] = stat.visits;
        });
        
        labels = monthNames;
        data = monthlyData;
    } else {
        labels = dailyStats.map(stat => {
            const date = new Date(stat.date);
            return date.toLocaleDateString('fr-FR', { day: '2-digit', month: 'short' });
        });
        data = dailyStats.map(stat => stat.visits);
    }
    
        const isLightMode = document.body && document.body.classList.contains('light-mode');
    const textColor = isLightMode ? '#64748b' : '#9ca3af';
    const gridColor = isLightMode ? 'rgba(100, 116, 139, 0.2)' : 'rgba(156, 163, 175, 0.1)';
    const tooltipBg = isLightMode ? 'rgba(255, 255, 255, 0.95)' : 'rgba(0, 0, 0, 0.8)';
    const tooltipTextColor = isLightMode ? '#1e293b' : '#fff';
    const bgColor = isLightMode ? 'rgba(6, 182, 212, 0.15)' : 'rgba(6, 182, 212, 0.1)';
    
    const visitsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Visites',
                data: data,
                borderColor: '#06b6d4',
                backgroundColor: bgColor,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#06b6d4',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: tooltipBg,
                    titleColor: '#06b6d4',
                    bodyColor: tooltipTextColor,
                    borderColor: '#06b6d4',
                    borderWidth: 1,
                    padding: 12,
                    displayColors: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: textColor,
                        precision: 0
                    },
                    grid: {
                        color: gridColor
                    }
                },
                x: {
                    ticks: {
                        color: textColor
                    },
                    grid: {
                        color: gridColor
                    }
                }
            }
        }
    });
    
    const weeklyCtx = document.getElementById('weeklyChart');
    if (weeklyCtx) {
        const weeklyStats = @json($weeklyStats);
        const weeklyLabels = weeklyStats.map(stat => stat.label);
        const weeklyData = weeklyStats.map(stat => stat.visits);
        
        const weeklyChart = new Chart(weeklyCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: weeklyLabels,
                datasets: [{
                    label: 'Visites',
                    data: weeklyData,
                    backgroundColor: isLightMode ? 'rgba(6, 182, 212, 0.7)' : 'rgba(6, 182, 212, 0.6)',
                    borderColor: '#06b6d4',
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: tooltipBg,
                        titleColor: '#06b6d4',
                        bodyColor: tooltipTextColor,
                        borderColor: '#06b6d4',
                        borderWidth: 1,
                        padding: 12,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: textColor,
                            precision: 0
                        },
                        grid: {
                            color: gridColor
                        }
                    },
                    x: {
                        ticks: {
                            color: textColor
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
        
        const darkModeToggle = document.getElementById('dark-mode-toggle');
        if (darkModeToggle) {
            darkModeToggle.addEventListener('click', function() {
                setTimeout(function() {
                    const isLight = document.body && document.body.classList.contains('light-mode');
                        const newTextColor = isLight ? '#64748b' : '#9ca3af';
                        const newGridColor = isLight ? 'rgba(100, 116, 139, 0.2)' : 'rgba(156, 163, 175, 0.1)';
                        const newTooltipBg = isLight ? 'rgba(255, 255, 255, 0.95)' : 'rgba(0, 0, 0, 0.8)';
                        const newTooltipTextColor = isLight ? '#1e293b' : '#fff';
                        const newBgColor = isLight ? 'rgba(6, 182, 212, 0.15)' : 'rgba(6, 182, 212, 0.1)';
                        const newBarBg = isLight ? 'rgba(6, 182, 212, 0.7)' : 'rgba(6, 182, 212, 0.6)';
                        
                        if (visitsChart) {
                            visitsChart.data.datasets[0].backgroundColor = newBgColor;
                            visitsChart.options.scales.y.ticks.color = newTextColor;
                            visitsChart.options.scales.x.ticks.color = newTextColor;
                            visitsChart.options.scales.y.grid.color = newGridColor;
                            visitsChart.options.scales.x.grid.color = newGridColor;
                            visitsChart.options.plugins.tooltip.backgroundColor = newTooltipBg;
                            visitsChart.options.plugins.tooltip.bodyColor = newTooltipTextColor;
                            visitsChart.update();
                        }
                        
                        if (weeklyChart) {
                            weeklyChart.data.datasets[0].backgroundColor = newBarBg;
                            weeklyChart.options.scales.y.ticks.color = newTextColor;
                            weeklyChart.options.scales.x.ticks.color = newTextColor;
                            weeklyChart.options.scales.y.grid.color = newGridColor;
                            weeklyChart.options.plugins.tooltip.backgroundColor = newTooltipBg;
                            weeklyChart.options.plugins.tooltip.bodyColor = newTooltipTextColor;
                            weeklyChart.update();
                        }
                    }, 100);
                });
            }
        }
    });
</script>
@endsection
