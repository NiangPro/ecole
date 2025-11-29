@extends('admin.layout')

@section('styles')
<style>
    /* Styles pour la page Statistics */
    .statistics-page h3 {
        color: #fff;
        transition: color 0.3s ease;
    }
    
    body.light-mode .statistics-page h3 {
        color: #1e293b;
    }
    
    /* Filtres */
    .filter-btn {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .filter-btn.active {
        background: #06b6d4;
        color: #000;
    }
    
    .filter-btn:not(.active) {
        background: rgba(55, 65, 81, 0.7);
        color: rgba(209, 213, 219, 1);
    }
    
    .filter-btn:not(.active):hover {
        background: rgba(75, 85, 99, 0.7);
    }
    
    body.light-mode .filter-btn:not(.active) {
        background: rgba(241, 245, 249, 0.8);
        color: rgba(30, 41, 59, 0.9);
    }
    
    body.light-mode .filter-btn:not(.active):hover {
        background: rgba(226, 232, 240, 0.9);
    }
    
    /* Selects */
    .statistics-select {
        padding: 0.5rem 1rem;
        background: rgba(55, 65, 81, 0.7);
        color: rgba(209, 213, 219, 1);
        border-radius: 0.5rem;
        font-weight: 600;
        border: 1px solid rgba(75, 85, 99, 0.6);
        transition: all 0.3s ease;
    }
    
    .statistics-select:focus {
        border-color: #06b6d4;
        outline: none;
    }
    
    body.light-mode .statistics-select {
        background: rgba(255, 255, 255, 0.9);
        color: #1e293b;
        border-color: rgba(6, 182, 212, 0.3);
    }
    
    body.light-mode .statistics-select:focus {
        border-color: #06b6d4;
        background: rgba(255, 255, 255, 1);
    }
    
    body.light-mode .statistics-select option {
        background: #ffffff;
        color: #1e293b;
    }
    
    /* Stat Cards */
    .stat-card {
        background: rgba(10, 10, 26, 0.6);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 16px;
        padding: 2rem;
        transition: all 0.3s ease;
    }
    
    body.light-mode .stat-card {
        background: rgba(255, 255, 255, 0.8);
        border-color: rgba(6, 182, 212, 0.3);
    }
    
    .stat-number {
        font-size: 2.5rem;
        font-weight: 900;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    /* Content Sections */
    .content-section {
        background: rgba(10, 10, 26, 0.6);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }
    
    body.light-mode .content-section {
        background: rgba(255, 255, 255, 0.8);
        border-color: rgba(6, 182, 212, 0.3);
    }
    
    .content-section h4 {
        color: #fff;
        transition: color 0.3s ease;
    }
    
    body.light-mode .content-section h4 {
        color: #1e293b;
    }
    
    /* Graphique containers */
    .chart-container {
        background: rgba(0, 0, 0, 0.3);
        border-radius: 0.5rem;
        padding: 1.5rem;
        transition: background 0.3s ease;
    }
    
    body.light-mode .chart-container {
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(6, 182, 212, 0.2);
    }
    
    /* Pages les plus visitées */
    .page-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        background: rgba(0, 0, 0, 0.3);
        border-radius: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .page-item:hover {
        background: rgba(0, 0, 0, 0.4);
    }
    
    body.light-mode .page-item {
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(6, 182, 212, 0.2);
    }
    
    body.light-mode .page-item:hover {
        background: rgba(255, 255, 255, 1);
        border-color: rgba(6, 182, 212, 0.3);
    }
    
    .page-item p {
        color: #fff;
        transition: color 0.3s ease;
    }
    
    body.light-mode .page-item p {
        color: #1e293b;
    }
    
    .page-item .text-gray-400 {
        color: rgba(156, 163, 175, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .page-item .text-gray-400 {
        color: rgba(100, 116, 139, 1);
    }
    
    .page-item .text-gray-500 {
        color: rgba(107, 114, 128, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .page-item .text-gray-500 {
        color: rgba(148, 163, 184, 1);
    }
    
    /* Pays, Navigateurs, Sources */
    .stat-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem;
        background: rgba(0, 0, 0, 0.3);
        border-radius: 0.5rem;
        transition: all 0.3s ease;
    }
    
    body.light-mode .stat-item {
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(6, 182, 212, 0.2);
    }
    
    .stat-item span {
        color: #fff;
        transition: color 0.3s ease;
    }
    
    body.light-mode .stat-item span {
        color: #1e293b;
    }
    
    .stat-item .text-gray-400 {
        color: rgba(156, 163, 175, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .stat-item .text-gray-400 {
        color: rgba(100, 116, 139, 1);
    }
    
    /* Icon containers */
    .icon-container {
        width: 3rem;
        height: 3rem;
        background: rgba(6, 182, 212, 0.2);
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.3s ease;
    }
    
    body.light-mode .icon-container {
        background: rgba(6, 182, 212, 0.1);
    }
    
    /* Styles pour text-gray dans les stat cards */
    body.light-mode .stat-card .text-gray-400 {
        color: rgba(100, 116, 139, 1) !important;
    }
    
    body.light-mode .stat-card .text-gray-500 {
        color: rgba(148, 163, 184, 1) !important;
    }
    
    /* Styles responsive pour mobile */
    @media (max-width: 768px) {
        /* Graphiques - Réduire la hauteur et améliorer la lisibilité */
        .chart-container {
            padding: 1rem;
            overflow-x: auto;
            height: 250px !important;
        }
        
        .chart-container canvas {
            max-width: 100%;
        }
        
        /* Pages les plus visitées - Layout responsive */
        .page-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
            padding: 1rem;
        }
        
        .page-item > div:first-child {
            width: 100%;
        }
        
        .page-item > div:last-child {
            width: 100%;
            text-align: left;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .page-item .icon-container {
            width: 2.5rem;
            height: 2.5rem;
        }
        
        .page-item p {
            font-size: 0.9rem;
        }
        
        .page-item .text-sm {
            font-size: 0.8rem;
            word-break: break-all;
        }
        
        .page-item .text-2xl {
            font-size: 1.5rem;
        }
        
        /* Content sections - Réduire le padding */
        .content-section {
            padding: 1.5rem;
        }
        
        .content-section h4 {
            font-size: 1.1rem;
        }
        
        /* Stat cards - Ajuster les tailles */
        .stat-card {
            padding: 1.5rem;
        }
        
        .stat-number {
            font-size: 2rem;
        }
        
        /* Grid responsive pour les stats par pays/navigateurs/sources */
        .grid.md\\:grid-cols-3 {
            grid-template-columns: 1fr;
        }
    }
    
    @media (max-width: 640px) {
        /* Graphiques encore plus petits sur très petits écrans */
        .chart-container {
            padding: 0.75rem;
            height: 200px !important;
        }
        
        /* Pages les plus visitées - Layout encore plus compact */
        .page-item {
            padding: 0.75rem;
            gap: 0.75rem;
        }
        
        .page-item .icon-container {
            width: 2rem;
            height: 2rem;
        }
        
        .page-item .icon-container i {
            font-size: 1rem;
        }
        
        .page-item p {
            font-size: 0.85rem;
        }
        
        .page-item .text-sm {
            font-size: 0.75rem;
        }
        
        .page-item .text-2xl {
            font-size: 1.25rem;
        }
        
        /* Filtres - Stack vertical */
        .flex.flex-col.sm\\:flex-row {
            flex-direction: column;
        }
        
        .flex.gap-3 {
            flex-wrap: wrap;
        }
        
        .filter-btn {
            font-size: 0.875rem;
            padding: 0.4rem 0.75rem;
        }
        
        .statistics-select {
            font-size: 0.875rem;
            padding: 0.4rem 0.75rem;
        }
    }
</style>
@endsection

@section('content')
<div class="statistics-page">
<div class="mb-8">
    <!-- En-tête avec titre et bouton -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
        <h3 class="text-3xl font-bold">Statistiques du site</h3>
        
        {{-- Bouton "Vider la table" commenté --}}
        {{--
        <form action="{{ route('admin.statistics.truncate') }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir vider complètement la table statistics ? Cette action est irréversible.');" class="w-full sm:w-auto">
            @csrf
            <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition">
                <i class="fas fa-trash mr-2"></i>Vider la table
            </button>
        </form>
        --}}
    </div>
    
    <!-- Filtres -->
    <div class="flex flex-col sm:flex-row gap-3 w-full">
        <div class="flex gap-3">
            <a href="{{ route('admin.statistics', ['filter' => 'day', 'year' => $year]) }}" 
               class="filter-btn {{ $filter == 'day' ? 'active' : '' }}">
                <i class="fas fa-calendar-day mr-2"></i>Jour
            </a>
            <a href="{{ route('admin.statistics', ['filter' => 'month', 'year' => $year]) }}" 
               class="filter-btn {{ $filter == 'month' ? 'active' : '' }}">
                <i class="fas fa-calendar-alt mr-2"></i>Mois
            </a>
            <a href="{{ route('admin.statistics', ['filter' => 'year', 'year' => $year]) }}" 
               class="filter-btn {{ $filter == 'year' ? 'active' : '' }}">
                <i class="fas fa-calendar mr-2"></i>Année
            </a>
        </div>
        
        @if($availableYears->count() > 0)
        <div class="flex gap-3">
            <select onchange="window.location.href='{{ route('admin.statistics') }}?filter={{ $filter }}&year=' + this.value + '&month={{ $month }}'" 
                    class="statistics-select">
                @foreach($availableYears as $availableYear)
                    <option value="{{ $availableYear }}" {{ $year == $availableYear ? 'selected' : '' }}>
                        {{ $availableYear }}
                    </option>
                @endforeach
            </select>
            
            @if($filter == 'month')
            <select onchange="window.location.href='{{ route('admin.statistics') }}?filter=month&year={{ $year }}&month=' + this.value" 
                    class="statistics-select">
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
            @endif
        </div>
        @endif
    </div>
</div>

<!-- Statistiques principales -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <i class="fas fa-eye text-4xl text-cyan-400"></i>
        </div>
        <div class="stat-number">{{ number_format($totalVisits) }}</div>
        <p class="text-gray-400 mt-2" style="transition: color 0.3s ease;">Visites totales</p>
        <p class="text-xs text-gray-500 mt-1" style="transition: color 0.3s ease;">
            @if($filter == 'day') Aujourd'hui
            @elseif($filter == 'month') Ce mois
            @else Cette année
            @endif
        </p>
    </div>
    
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <i class="fas fa-users text-4xl text-teal-400"></i>
        </div>
        <div class="stat-number">{{ number_format($uniqueVisitors) }}</div>
        <p class="text-gray-400 mt-2" style="transition: color 0.3s ease;">Visiteurs uniques</p>
        <p class="text-xs text-gray-500 mt-1" style="transition: color 0.3s ease;">Basé sur IP</p>
    </div>
    
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <i class="fas fa-file-alt text-4xl text-purple-400"></i>
        </div>
        <div class="stat-number">{{ number_format($totalPages) }}</div>
        <p class="text-gray-400 mt-2" style="transition: color 0.3s ease;">Pages vues</p>
        <p class="text-xs text-gray-500 mt-1" style="transition: color 0.3s ease;">Pages différentes</p>
    </div>
    
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <i class="fas fa-chart-line text-4xl text-green-400"></i>
        </div>
        <div class="stat-number">{{ $avgPerDay }}</div>
        <p class="text-gray-400 mt-2" style="transition: color 0.3s ease;">Moyenne / jour</p>
        <p class="text-xs text-gray-500 mt-1" style="transition: color 0.3s ease;">Visites quotidiennes</p>
    </div>
</div>

<!-- Graphique des visites -->
<div class="content-section mb-8">
    <h4 class="text-xl font-bold mb-6">
        @if($filter == 'year')
            Évolution des visites par mois ({{ $year }})
        @else
            Évolution des visites (30 derniers jours)
        @endif
    </h4>
    <div class="chart-container" style="position: relative; height: 300px;">
        <canvas id="visitsChart"></canvas>
    </div>
</div>

<!-- Graphique des vues hebdomadaires du mois actuel -->
<div class="content-section mb-8">
    <h4 class="text-xl font-bold mb-6">Vues du mois actuel par semaine</h4>
    <div class="chart-container" style="position: relative; height: 300px;">
        <canvas id="weeklyChart"></canvas>
    </div>
</div>

<!-- Pages les plus visitées -->
<div class="content-section mb-8">
    <h4 class="text-xl font-bold mb-6">Pages les plus visitées</h4>
    <div class="top-pages-scroll" style="max-height: 350px; overflow-y: auto; padding-right: 10px;">
        <div class="space-y-3">
            @forelse($topPages as $page)
            <div class="page-item">
                <div class="flex items-center gap-4 flex-1">
                    <div class="icon-container">
                        <i class="fas fa-file-alt text-cyan-400 text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold">{{ $page->page_title ?? 'Sans titre' }}</p>
                        <p class="text-sm text-gray-400">{{ $page->page_url }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-cyan-400">{{ number_format($page->visits) }}</p>
                    <p class="text-xs text-gray-500">visites</p>
                </div>
            </div>
            @empty
            <div class="text-center py-12 text-gray-400" style="transition: color 0.3s ease;">
                <i class="fas fa-chart-bar text-5xl mb-4 opacity-50"></i>
                <p>Aucune donnée disponible pour cette période</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Statistiques par Pays, Navigateurs, Sources -->
<div class="grid md:grid-cols-3 gap-6 mb-8">
    <!-- Pays -->
    <div class="content-section">
        <h4 class="text-xl font-bold mb-6 flex items-center gap-2">
            <i class="fas fa-globe text-cyan-400"></i>
            Pays des visiteurs
        </h4>
        <div class="space-y-3">
            @forelse($countriesStats as $country)
            <div class="stat-item">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">{{ \App\Services\GeoIPService::getCountryFlag($country->country) }}</span>
                    <span class="font-semibold">{{ \App\Services\GeoIPService::getCountryName($country->country) }}</span>
                </div>
                <span class="text-cyan-400 font-bold">{{ number_format($country->visits) }}</span>
            </div>
            @empty
            <p class="text-gray-400 text-center py-8">Aucune donnée</p>
            @endforelse
        </div>
    </div>
    
    <!-- Navigateurs -->
    <div class="content-section">
        <h4 class="text-xl font-bold mb-6 flex items-center gap-2">
            <i class="fas fa-browser text-purple-400"></i>
            Navigateurs
        </h4>
        <div class="space-y-3">
            @forelse($browsersStats as $browser)
            <div class="stat-item">
                <div class="flex items-center gap-3">
                    <i class="fab fa-{{ strtolower($browser->browser) == 'chrome' ? 'chrome' : (strtolower($browser->browser) == 'firefox' ? 'firefox' : (strtolower($browser->browser) == 'safari' ? 'safari' : (strtolower($browser->browser) == 'edge' ? 'edge' : 'internet-explorer'))) }} text-xl text-gray-400"></i>
                    <span class="font-semibold">{{ $browser->browser }}</span>
                </div>
                <span class="text-purple-400 font-bold">{{ number_format($browser->visits) }}</span>
            </div>
            @empty
            <p class="text-gray-400 text-center py-8">Aucune donnée</p>
            @endforelse
        </div>
    </div>
    
    <!-- Sources -->
    <div class="content-section">
        <h4 class="text-xl font-bold mb-6 flex items-center gap-2">
            <i class="fas fa-share-alt text-green-400"></i>
            Sources de trafic
        </h4>
        <div class="space-y-3">
            @forelse($sourcesStats as $source)
            <div class="stat-item">
                <div class="flex items-center gap-3">
                    <i class="fas fa-{{ $source->source == 'Direct' ? 'link' : (strtolower($source->source) == 'google' ? 'search' : (strtolower($source->source) == 'facebook' ? 'facebook' : (strtolower($source->source) == 'twitter' ? 'twitter' : 'globe'))) }} text-xl text-gray-400"></i>
                    <span class="font-semibold">{{ $source->source }}</span>
                </div>
                <span class="text-green-400 font-bold">{{ number_format($source->visits) }}</span>
            </div>
            @empty
            <p class="text-gray-400 text-center py-8">Aucune donnée</p>
            @endforelse
        </div>
        </div>
    </div>
</div>

<style>
    .top-pages-scroll {
        scrollbar-width: thin;
        scrollbar-color: rgba(6, 182, 212, 0.5) rgba(15, 23, 42, 0.3);
    }
    
    .top-pages-scroll::-webkit-scrollbar {
        width: 8px;
    }
    
    .top-pages-scroll::-webkit-scrollbar-track {
        background: rgba(15, 23, 42, 0.3);
        border-radius: 10px;
    }
    
    .top-pages-scroll::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #06b6d4, #14b8a6);
        border-radius: 10px;
        border: 2px solid rgba(15, 23, 42, 0.3);
    }
    
    .top-pages-scroll::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #14b8a6, #06b6d4);
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Attendre que le DOM soit chargé
    document.addEventListener('DOMContentLoaded', function() {
        // Vérifier que les éléments existent
        const visitsChartElement = document.getElementById('visitsChart');
        if (!visitsChartElement) return;
        
    // Graphique principal
        const ctx = visitsChartElement.getContext('2d');
    const dailyStats = @json($dailyStats);
    const filter = '{{ $filter }}';
    
    let labels, data;
    
    if (filter === 'year') {
        // Graphique mensuel
        const monthNames = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
        const monthlyData = new Array(12).fill(0);
        
        dailyStats.forEach(stat => {
            monthlyData[stat.month - 1] = stat.visits;
        });
        
        labels = monthNames;
        data = monthlyData;
    } else {
        // Graphique quotidien
        labels = dailyStats.map(stat => {
            const date = new Date(stat.date);
            return date.toLocaleDateString('fr-FR', { day: '2-digit', month: 'short' });
        });
        data = dailyStats.map(stat => stat.visits);
    }
    
        // Détecter le mode clair/sombre
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
    
    // Graphique hebdomadaire du mois actuel
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
        
        // Mettre à jour les graphiques quand le mode change
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
