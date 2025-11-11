@extends('admin.layout')

@section('content')
<div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-8">
    <h3 class="text-3xl font-bold">Statistiques du site</h3>
    
    <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
        <div class="flex gap-3">
            <a href="{{ route('admin.statistics', ['filter' => 'day', 'year' => $year]) }}" 
               class="px-4 py-2 rounded-lg font-semibold transition {{ $filter == 'day' ? 'bg-cyan-500 text-black' : 'bg-gray-700 text-gray-300 hover:bg-gray-600' }}">
                <i class="fas fa-calendar-day mr-2"></i>Jour
            </a>
            <a href="{{ route('admin.statistics', ['filter' => 'month', 'year' => $year]) }}" 
               class="px-4 py-2 rounded-lg font-semibold transition {{ $filter == 'month' ? 'bg-cyan-500 text-black' : 'bg-gray-700 text-gray-300 hover:bg-gray-600' }}">
                <i class="fas fa-calendar-alt mr-2"></i>Mois
            </a>
            <a href="{{ route('admin.statistics', ['filter' => 'year', 'year' => $year]) }}" 
               class="px-4 py-2 rounded-lg font-semibold transition {{ $filter == 'year' ? 'bg-cyan-500 text-black' : 'bg-gray-700 text-gray-300 hover:bg-gray-600' }}">
                <i class="fas fa-calendar mr-2"></i>Année
            </a>
        </div>
        
        @if($availableYears->count() > 0)
        <div class="flex gap-3">
            <select onchange="window.location.href='{{ route('admin.statistics') }}?filter={{ $filter }}&year=' + this.value + '&month={{ $month }}'" 
                    class="px-4 py-2 bg-gray-700 text-gray-300 rounded-lg font-semibold border border-gray-600 focus:border-cyan-500 focus:outline-none">
                @foreach($availableYears as $availableYear)
                    <option value="{{ $availableYear }}" {{ $year == $availableYear ? 'selected' : '' }}>
                        {{ $availableYear }}
                    </option>
                @endforeach
            </select>
            
            @if($filter == 'month')
            <select onchange="window.location.href='{{ route('admin.statistics') }}?filter=month&year={{ $year }}&month=' + this.value" 
                    class="px-4 py-2 bg-gray-700 text-gray-300 rounded-lg font-semibold border border-gray-600 focus:border-cyan-500 focus:outline-none">
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
        <p class="text-gray-400 mt-2">Visites totales</p>
        <p class="text-xs text-gray-500 mt-1">
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
        <p class="text-gray-400 mt-2">Visiteurs uniques</p>
        <p class="text-xs text-gray-500 mt-1">Basé sur IP</p>
    </div>
    
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <i class="fas fa-file-alt text-4xl text-purple-400"></i>
        </div>
        <div class="stat-number">{{ number_format($totalPages) }}</div>
        <p class="text-gray-400 mt-2">Pages vues</p>
        <p class="text-xs text-gray-500 mt-1">Pages différentes</p>
    </div>
    
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <i class="fas fa-chart-line text-4xl text-green-400"></i>
        </div>
        <div class="stat-number">{{ $avgPerDay }}</div>
        <p class="text-gray-400 mt-2">Moyenne / jour</p>
        <p class="text-xs text-gray-500 mt-1">Visites quotidiennes</p>
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
    <div class="bg-black/30 rounded-lg p-6">
        <canvas id="visitsChart" height="80"></canvas>
    </div>
</div>

<!-- Pages les plus visitées -->
<div class="content-section mb-8">
    <h4 class="text-xl font-bold mb-6">Pages les plus visitées</h4>
    <div class="space-y-3">
        @forelse($topPages as $page)
        <div class="flex items-center justify-between p-4 bg-black/30 rounded-lg hover:bg-black/40 transition">
            <div class="flex items-center gap-4 flex-1">
                <div class="w-12 h-12 bg-cyan-500/20 rounded-lg flex items-center justify-center">
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
        <div class="text-center py-12 text-gray-400">
            <i class="fas fa-chart-bar text-5xl mb-4 opacity-50"></i>
            <p>Aucune donnée disponible pour cette période</p>
        </div>
        @endforelse
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
            <div class="flex items-center justify-between p-3 bg-black/30 rounded-lg">
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
            <div class="flex items-center justify-between p-3 bg-black/30 rounded-lg">
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
            <div class="flex items-center justify-between p-3 bg-black/30 rounded-lg">
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('visitsChart').getContext('2d');
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
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Visites',
                data: data,
                borderColor: '#06b6d4',
                backgroundColor: 'rgba(6, 182, 212, 0.1)',
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
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#06b6d4',
                    bodyColor: '#fff',
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
                        color: '#9ca3af',
                        precision: 0
                    },
                    grid: {
                        color: 'rgba(156, 163, 175, 0.1)'
                    }
                },
                x: {
                    ticks: {
                        color: '#9ca3af'
                    },
                    grid: {
                        color: 'rgba(156, 163, 175, 0.1)'
                    }
                }
            }
        }
    });
</script>
@endsection
