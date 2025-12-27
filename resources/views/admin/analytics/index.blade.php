@extends('admin.layout')

@section('title', 'Dashboard Analytics')

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h3 class="text-3xl font-bold mb-2">Dashboard Analytics</h3>
        <p class="text-gray-400">Analysez le comportement de vos utilisateurs</p>
    </div>
    
    <div class="flex gap-2">
        <select id="period-select" class="input-admin" onchange="window.location.href='?period=' + this.value">
            <option value="7days" {{ $period === '7days' ? 'selected' : '' }}>7 derniers jours</option>
            <option value="30days" {{ $period === '30days' ? 'selected' : '' }}>30 derniers jours</option>
            <option value="90days" {{ $period === '90days' ? 'selected' : '' }}>90 derniers jours</option>
            <option value="all" {{ $period === 'all' ? 'selected' : '' }}>Tout</option>
        </select>
    </div>
</div>

<!-- Statistiques générales -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
    <div class="content-section">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm mb-1">Total Événements</p>
                <p class="text-3xl font-bold text-cyan-400">{{ number_format($stats['total_events'], 0, ',', ' ') }}</p>
            </div>
            <div class="w-12 h-12 bg-cyan-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-chart-line text-cyan-400 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="content-section">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm mb-1">Vues de Pages</p>
                <p class="text-3xl font-bold text-blue-400">{{ number_format($stats['total_page_views'], 0, ',', ' ') }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-eye text-blue-400 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="content-section">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm mb-1">Clics</p>
                <p class="text-3xl font-bold text-green-400">{{ number_format($stats['total_clicks'], 0, ',', ' ') }}</p>
            </div>
            <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-mouse-pointer text-green-400 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="content-section">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm mb-1">Visiteurs Uniques</p>
                <p class="text-3xl font-bold text-purple-400">{{ number_format($stats['unique_visitors'], 0, ',', ' ') }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-purple-400 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="content-section">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm mb-1">Utilisateurs Connectés</p>
                <p class="text-3xl font-bold text-yellow-400">{{ number_format($stats['unique_users'], 0, ',', ' ') }}</p>
            </div>
            <div class="w-12 h-12 bg-yellow-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-user-check text-yellow-400 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Graphique des événements par jour -->
    <div class="content-section">
        <h4 class="text-xl font-bold mb-4">Événements par Jour</h4>
        <canvas id="events-chart" height="200"></canvas>
    </div>
    
    <!-- Événements par type -->
    <div class="content-section">
        <h4 class="text-xl font-bold mb-4">Événements par Type</h4>
        <div class="space-y-3">
            @foreach($eventsByType as $event)
            <div class="flex items-center justify-between p-3 bg-gray-800 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-cyan-500/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-circle text-cyan-400 text-xs"></i>
                    </div>
                    <span class="font-semibold">{{ $event->event_type }}</span>
                </div>
                <span class="text-cyan-400 font-bold">{{ number_format($event->count, 0, ',', ' ') }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Pages les plus visitées -->
<div class="content-section mb-8">
    <h4 class="text-xl font-bold mb-4">Pages les Plus Visitées</h4>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-700">
                    <th class="text-left p-3 text-gray-400">Page</th>
                    <th class="text-right p-3 text-gray-400">Vues</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topPages as $page)
                <tr class="border-b border-gray-800 hover:bg-gray-800/50">
                    <td class="p-3">
                        <div class="font-semibold">{{ $page->page_title ?: $page->page_url }}</div>
                        <div class="text-sm text-gray-400">{{ Str::limit($page->page_url, 60) }}</div>
                    </td>
                    <td class="p-3 text-right font-bold text-cyan-400">{{ number_format($page->views, 0, ',', ' ') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Devices -->
    <div class="content-section">
        <h4 class="text-xl font-bold mb-4">Répartition par Device</h4>
        <canvas id="devices-chart" height="200"></canvas>
    </div>
    
    <!-- Navigateurs -->
    <div class="content-section">
        <h4 class="text-xl font-bold mb-4">Navigateurs</h4>
        <div class="space-y-3">
            @foreach($browsers as $browser)
            <div class="flex items-center justify-between p-3 bg-gray-800 rounded-lg">
                <span class="font-semibold">{{ $browser->browser ?: 'Unknown' }}</span>
                <span class="text-cyan-400 font-bold">{{ number_format($browser->count, 0, ',', ' ') }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Funnels actifs -->
@if($funnels->count() > 0)
<div class="content-section mb-8">
    <h4 class="text-xl font-bold mb-4">Funnels de Conversion</h4>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($funnels as $funnel)
        <a href="{{ route('admin.analytics.funnel', $funnel->id) }}" class="block p-4 bg-gray-800 rounded-lg hover:bg-gray-700 transition">
            <h5 class="font-bold mb-2">{{ $funnel->name }}</h5>
            <p class="text-sm text-gray-400 mb-3">{{ $funnel->description }}</p>
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-400">Taux de conversion</span>
                <span class="text-cyan-400 font-bold">{{ $funnel->conversion_rate }}%</span>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endif

<!-- Tests A/B actifs -->
@if($abTests->count() > 0)
<div class="content-section mb-8">
    <h4 class="text-xl font-bold mb-4">Tests A/B Actifs</h4>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($abTests as $test)
        <a href="{{ route('admin.analytics.ab-test', $test->id) }}" class="block p-4 bg-gray-800 rounded-lg hover:bg-gray-700 transition">
            <h5 class="font-bold mb-2">{{ $test->name }}</h5>
            <p class="text-sm text-gray-400 mb-3">{{ $test->description }}</p>
            <div class="text-sm">
                <span class="text-gray-400">Objectif:</span>
                <span class="text-cyan-400 font-semibold">{{ $test->goal_event }}</span>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endif

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Graphique des événements par jour
    const eventsCtx = document.getElementById('events-chart');
    if (eventsCtx) {
        new Chart(eventsCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($eventsByDay->pluck('date')) !!},
                datasets: [{
                    label: 'Événements',
                    data: {!! json_encode($eventsByDay->pluck('count')) !!},
                    borderColor: '#06b6d4',
                    backgroundColor: 'rgba(6, 182, 212, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#94a3b8'
                        },
                        grid: {
                            color: 'rgba(148, 163, 184, 0.1)'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#94a3b8'
                        },
                        grid: {
                            color: 'rgba(148, 163, 184, 0.1)'
                        }
                    }
                }
            }
        });
    }
    
    // Graphique des devices
    const devicesCtx = document.getElementById('devices-chart');
    if (devicesCtx) {
        new Chart(devicesCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($devices->pluck('device_type')) !!},
                datasets: [{
                    data: {!! json_encode($devices->pluck('count')) !!},
                    backgroundColor: [
                        '#06b6d4',
                        '#14b8a6',
                        '#f59e0b',
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#94a3b8'
                        }
                    }
                }
            }
        });
    }
</script>
@endpush
@endsection

