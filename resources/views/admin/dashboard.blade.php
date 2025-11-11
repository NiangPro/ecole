@extends('admin.layout')

@section('content')
<!-- Titre et actions rapides -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h3 class="text-3xl font-bold mb-2">Tableau de bord</h3>
        <p class="text-gray-400">Bienvenue sur votre panneau d'administration</p>
    </div>
    <div class="flex flex-wrap gap-3">
        <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-black font-semibold rounded-lg transition">
            <i class="fas fa-user-plus mr-2"></i>Nouvel utilisateur
        </a>
        <a href="{{ route('admin.statistics') }}" class="px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white font-semibold rounded-lg transition">
            <i class="fas fa-chart-bar mr-2"></i>Statistiques
        </a>
    </div>
</div>

<!-- Statistiques principales -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <i class="fas fa-graduation-cap text-4xl text-cyan-400"></i>
        </div>
        <div class="stat-number">8</div>
        <p class="text-gray-400 mt-2">Formations</p>
        <p class="text-xs text-gray-500 mt-1">Disponibles</p>
    </div>
    
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <i class="fas fa-eye text-4xl text-teal-400"></i>
        </div>
        <div class="stat-number">{{ number_format(\App\Models\Statistic::whereDate('visit_date', \Carbon\Carbon::today())->count()) }}</div>
        <p class="text-gray-400 mt-2">Visites aujourd'hui</p>
        <p class="text-xs text-gray-500 mt-1">+{{ number_format(\App\Models\Statistic::whereDate('visit_date', \Carbon\Carbon::yesterday())->count()) }} hier</p>
    </div>
    
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <i class="fas fa-chart-line text-4xl text-purple-400"></i>
        </div>
        <div class="stat-number">{{ number_format(\App\Models\Statistic::whereMonth('visit_date', \Carbon\Carbon::now()->month)->count()) }}</div>
        <p class="text-gray-400 mt-2">Visites ce mois</p>
        <p class="text-xs text-gray-500 mt-1">Mois en cours</p>
    </div>
    
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <i class="fas fa-users text-4xl text-green-400"></i>
        </div>
        <div class="stat-number">{{ number_format(\App\Models\User::count()) }}</div>
        <p class="text-gray-400 mt-2">Utilisateurs</p>
        <p class="text-xs text-gray-500 mt-1">{{ \App\Models\User::where('is_active', true)->count() }} actifs</p>
    </div>
</div>

<!-- Graphique et top pages -->
<div class="grid lg:grid-cols-2 gap-6 mb-8">
    <!-- Graphique des visites -->
    <div class="content-section">
        <h4 class="text-xl font-bold mb-6 flex items-center gap-2">
            <i class="fas fa-chart-area text-cyan-400"></i>
            Visites (7 derniers jours)
        </h4>
        <div class="bg-black/30 rounded-lg p-6">
            <canvas id="visitsChart" height="200"></canvas>
        </div>
    </div>
    
    <!-- Top 5 pages -->
    <div class="content-section">
        <h4 class="text-xl font-bold mb-6 flex items-center gap-2">
            <i class="fas fa-fire text-orange-400"></i>
            Pages les plus visitées
        </h4>
        <div class="space-y-3">
            @php
                $topPages = \App\Models\Statistic::getTopPages(5, 'month');
            @endphp
            @forelse($topPages as $index => $page)
            <div class="flex items-center gap-4 p-4 bg-black/30 rounded-lg hover:bg-black/40 transition">
                <div class="w-10 h-10 bg-gradient-to-br from-cyan-500 to-teal-500 rounded-lg flex items-center justify-center font-bold text-lg">
                    {{ $index + 1 }}
                </div>
                <div class="flex-1">
                    <p class="font-semibold">{{ $page->page_title ?? 'Sans titre' }}</p>
                    <p class="text-xs text-gray-400">{{ Str::limit($page->page_url, 40) }}</p>
                </div>
                <span class="text-xl font-bold text-cyan-400">{{ number_format($page->visits) }}</span>
            </div>
            @empty
            <p class="text-center text-gray-400 py-8">Aucune donnée disponible</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Activité récente et actions rapides -->
<div class="grid lg:grid-cols-3 gap-6 mb-8">
    <!-- Activité récente -->
    <div class="lg:col-span-2 content-section">
        <h4 class="text-xl font-bold mb-6 flex items-center gap-2">
            <i class="fas fa-history text-purple-400"></i>
            Activité récente
        </h4>
        <div class="space-y-3">
            @php
                $recentUsers = \App\Models\User::orderBy('created_at', 'desc')->limit(5)->get();
            @endphp
            @foreach($recentUsers as $user)
            <div class="flex items-center gap-4 p-4 bg-black/30 rounded-lg hover:bg-black/40 transition">
                <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-teal-500 rounded-full flex items-center justify-center font-bold">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="flex-1">
                    <p class="font-semibold">{{ $user->name }}</p>
                    <p class="text-sm text-gray-400">Nouvel utilisateur inscrit</p>
                </div>
                <span class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</span>
            </div>
            @endforeach
        </div>
    </div>
    
    <!-- Actions rapides -->
    <div class="content-section">
        <h4 class="text-xl font-bold mb-6 flex items-center gap-2">
            <i class="fas fa-bolt text-yellow-400"></i>
            Actions rapides
        </h4>
        <div class="space-y-3">
            <a href="{{ route('admin.users') }}" class="flex items-center gap-3 p-4 bg-black/30 rounded-lg hover:bg-cyan-500/20 transition group">
                <div class="w-10 h-10 bg-cyan-500/20 rounded-lg flex items-center justify-center group-hover:bg-cyan-500/30 transition">
                    <i class="fas fa-users text-cyan-400"></i>
                </div>
                <div class="flex-1">
                    <p class="font-semibold">Utilisateurs</p>
                    <p class="text-xs text-gray-400">Gérer les comptes</p>
                </div>
                <i class="fas fa-chevron-right text-gray-600 group-hover:text-cyan-400 transition"></i>
            </a>
            
            <a href="{{ route('admin.statistics') }}" class="flex items-center gap-3 p-4 bg-black/30 rounded-lg hover:bg-purple-500/20 transition group">
                <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center group-hover:bg-purple-500/30 transition">
                    <i class="fas fa-chart-bar text-purple-400"></i>
                </div>
                <div class="flex-1">
                    <p class="font-semibold">Statistiques</p>
                    <p class="text-xs text-gray-400">Voir les analytics</p>
                </div>
                <i class="fas fa-chevron-right text-gray-600 group-hover:text-purple-400 transition"></i>
            </a>
            
            <a href="{{ route('admin.adsense') }}" class="flex items-center gap-3 p-4 bg-black/30 rounded-lg hover:bg-green-500/20 transition group">
                <div class="w-10 h-10 bg-green-500/20 rounded-lg flex items-center justify-center group-hover:bg-green-500/30 transition">
                    <i class="fab fa-google text-green-400"></i>
                </div>
                <div class="flex-1">
                    <p class="font-semibold">AdSense</p>
                    <p class="text-xs text-gray-400">Configuration</p>
                </div>
                <i class="fas fa-chevron-right text-gray-600 group-hover:text-green-400 transition"></i>
            </a>
            
            <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 p-4 bg-black/30 rounded-lg hover:bg-orange-500/20 transition group">
                <div class="w-10 h-10 bg-orange-500/20 rounded-lg flex items-center justify-center group-hover:bg-orange-500/30 transition">
                    <i class="fas fa-cog text-orange-400"></i>
                </div>
                <div class="flex-1">
                    <p class="font-semibold">Paramètres</p>
                    <p class="text-xs text-gray-400">Configuration du site</p>
                </div>
                <i class="fas fa-chevron-right text-gray-600 group-hover:text-orange-400 transition"></i>
            </a>
        </div>
    </div>
</div>

<!-- Statistiques par pays et navigateurs -->
<div class="grid md:grid-cols-2 gap-6">
    <!-- Top pays -->
    <div class="content-section">
        <h4 class="text-xl font-bold mb-6 flex items-center gap-2">
            <i class="fas fa-globe text-cyan-400"></i>
            Top 5 Pays
        </h4>
        <div class="space-y-3">
            @php
                $topCountries = \App\Models\Statistic::getByCountry('month', null, null);
            @endphp
            @foreach($topCountries->take(5) as $country)
            <div class="flex items-center justify-between p-3 bg-black/30 rounded-lg">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">{{ \App\Services\GeoIPService::getCountryFlag($country->country) }}</span>
                    <span class="font-semibold">{{ \App\Services\GeoIPService::getCountryName($country->country) }}</span>
                </div>
                <span class="text-cyan-400 font-bold">{{ number_format($country->visits) }}</span>
            </div>
            @endforeach
        </div>
    </div>
    
    <!-- Top navigateurs -->
    <div class="content-section">
        <h4 class="text-xl font-bold mb-6 flex items-center gap-2">
            <i class="fas fa-browser text-purple-400"></i>
            Top Navigateurs
        </h4>
        <div class="space-y-3">
            @php
                $topBrowsers = \App\Models\Statistic::getByBrowser('month', null, null);
            @endphp
            @foreach($topBrowsers as $browser)
            <div class="flex items-center justify-between p-3 bg-black/30 rounded-lg">
                <div class="flex items-center gap-3">
                    <i class="fab fa-{{ strtolower($browser->browser) == 'chrome' ? 'chrome' : (strtolower($browser->browser) == 'firefox' ? 'firefox' : (strtolower($browser->browser) == 'safari' ? 'safari' : 'internet-explorer')) }} text-xl text-gray-400"></i>
                    <span class="font-semibold">{{ $browser->browser }}</span>
                </div>
                <span class="text-purple-400 font-bold">{{ number_format($browser->visits) }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('visitsChart').getContext('2d');
    
    @php
        $last7Days = \App\Models\Statistic::getDailyStats(7);
    @endphp
    
    const dailyStats = @json($last7Days);
    
    const labels = dailyStats.map(stat => {
        const date = new Date(stat.date);
        return date.toLocaleDateString('fr-FR', { day: '2-digit', month: 'short' });
    });
    
    const data = dailyStats.map(stat => stat.visits);
    
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
                pointRadius: 5,
                pointHoverRadius: 7
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
