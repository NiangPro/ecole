@extends('dashboard.layout')

@section('dashboard-content')
@php
    // S'assurer que la locale est définie
    if (!app()->getLocale()) {
        app()->setLocale(session('language', 'fr'));
    }
    $pageTitle = trans('app.profile.dashboard.statistics.title');
    $pageDescription = trans('app.profile.dashboard.statistics.description');
@endphp

@if(isset($chartData))
<div class="content-card">
    <h2 class="card-title dashboard-text-primary">
        <i class="fas fa-chart-area"></i>
        {{ trans('app.profile.dashboard.statistics.activity_over_time') }}
    </h2>
    <div style="height: 350px; position: relative;">
        <canvas id="progressChart" height="350"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('progressChart');
    if (ctx) {
        const chartData = @json($chartData['progression_over_time'] ?? []);
        const isDarkMode = document.body.classList.contains('dark-mode');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels || [],
                datasets: [{
                    label: '{{ trans('app.profile.dashboard.statistics.activities') ?? 'Activités' }}',
                    data: chartData.data || [],
                    borderColor: '#06b6d4',
                    backgroundColor: isDarkMode ? 'rgba(6, 182, 212, 0.2)' : 'rgba(6, 182, 212, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#06b6d4',
                    pointBorderColor: isDarkMode ? '#ffffff' : '#1e293b',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { 
                            color: isDarkMode ? 'rgba(255, 255, 255, 0.6)' : '#94a3b8',
                            font: { size: 12 }
                        },
                        grid: { 
                            color: isDarkMode ? 'rgba(6, 182, 212, 0.2)' : 'rgba(6, 182, 212, 0.1)'
                        }
                    },
                    x: {
                        ticks: { 
                            color: isDarkMode ? 'rgba(255, 255, 255, 0.6)' : '#94a3b8',
                            font: { size: 12 }
                        },
                        grid: { 
                            color: isDarkMode ? 'rgba(6, 182, 212, 0.2)' : 'rgba(6, 182, 212, 0.1)'
                        }
                    }
                }
            }
        });
    }
</script>
@endif

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem;">
    <div class="content-card" style="margin-bottom: 0;">
        <div class="dashboard-text-secondary" style="font-size: 0.875rem; color: #64748b; margin-bottom: 0.75rem; font-weight: 500;">{{ trans('app.profile.dashboard.statistics.total_time') }}</div>
        <div style="font-size: 2rem; font-weight: 700; color: #06b6d4;">{{ $stats['total_time_hours'] ?? 0 }}h</div>
        <div class="dashboard-text-secondary" style="color: #64748b; font-size: 0.8rem; margin-top: 0.5rem;">{{ $stats['total_time_minutes'] ?? 0 }} {{ trans('app.profile.dashboard.overview.minutes') }}</div>
    </div>
    <div class="content-card" style="margin-bottom: 0;">
        <div class="dashboard-text-secondary" style="font-size: 0.875rem; color: #64748b; margin-bottom: 0.75rem; font-weight: 500;">{{ trans('app.profile.dashboard.statistics.completion_rate') }}</div>
        <div style="font-size: 2rem; font-weight: 700; color: #06b6d4;">{{ $stats['average_completion'] ?? 0 }}%</div>
        <div class="dashboard-text-secondary" style="color: #64748b; font-size: 0.8rem; margin-top: 0.5rem;">{{ trans('app.profile.dashboard.statistics.average_progress') }}</div>
    </div>
    <div class="content-card" style="margin-bottom: 0;">
        <div class="dashboard-text-secondary" style="font-size: 0.875rem; color: #64748b; margin-bottom: 0.75rem; font-weight: 500;">{{ trans('app.profile.dashboard.statistics.average_quiz_score') }}</div>
        <div style="font-size: 2rem; font-weight: 700; color: #06b6d4;">{{ $stats['average_quiz_score'] ?? 0 }}%</div>
        <div class="dashboard-text-secondary" style="color: #64748b; font-size: 0.8rem; margin-top: 0.5rem;">{{ $stats['quiz_count'] ?? 0 }} {{ trans('app.profile.dashboard.statistics.quiz_passed') }}</div>
    </div>
</div>

<style>
    /* Dark Mode Styles pour la page Statistics */
    body.dark-mode .dashboard-text-primary {
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    body.dark-mode .dashboard-text-secondary {
        color: rgba(255, 255, 255, 0.6) !important;
    }
    
    /* Styles pour les cartes de statistiques en dark mode */
    body.dark-mode .content-card {
        background: rgba(15, 23, 42, 0.6) !important;
        border-color: rgba(4, 170, 109, 0.2) !important;
    }
    
    body.dark-mode .content-card:hover {
        border-color: rgba(4, 170, 109, 0.3) !important;
        box-shadow: 0 8px 25px rgba(4, 170, 109, 0.15) !important;
    }
    
    body.dark-mode .card-title {
        color: rgba(255, 255, 255, 0.9) !important;
    }
</style>
@endsection
