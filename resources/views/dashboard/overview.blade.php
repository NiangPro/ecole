@extends('dashboard.layout')

@section('dashboard-content')
@php
    // S'assurer que la locale est définie
    if (!app()->getLocale()) {
        app()->setLocale(session('language', 'fr'));
    }
    $pageTitle = trans('app.profile.dashboard.overview.title');
    $pageDescription = trans('app.profile.dashboard.overview.description');
@endphp

<!-- Stats Grid - Design Éducatif -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
    <!-- Heures d'apprentissage -->
    <div class="content-card" style="margin-bottom: 0;">
        <div style="display: flex; align-items: center; gap: 1.25rem; margin-bottom: 1.5rem;">
            <div style="width: 70px; height: 70px; border-radius: 15px; background: linear-gradient(135deg, rgba(4, 170, 109, 0.15), rgba(4, 170, 109, 0.05)); display: flex; align-items: center; justify-content: center; color: #04AA6D; flex-shrink: 0;">
                <i class="fas fa-clock" style="font-size: 2rem;"></i>
            </div>
            <div style="flex: 1;">
                <div style="font-size: 2.5rem; font-weight: 800; color: #2c3e50; margin-bottom: 0.25rem; line-height: 1;">
                    {{ $stats['total_time_hours'] ?? 0 }}<span style="font-size: 1.5rem; color: #04AA6D;">h</span>
                </div>
                <div style="color: #64748b; font-size: 0.95rem; font-weight: 500;" class="dashboard-text-secondary">{{ trans('app.profile.dashboard.overview.hours_learning') }}</div>
                <div style="color: #94a3b8; font-size: 0.85rem; margin-top: 0.25rem;" class="dashboard-text-tertiary">{{ $stats['total_time_minutes'] ?? 0 }} {{ trans('app.profile.dashboard.overview.minutes') }}</div>
            </div>
        </div>
    </div>
    
    <!-- Formations complétées -->
    <div class="content-card" style="margin-bottom: 0;">
        <div style="display: flex; align-items: center; gap: 1.25rem; margin-bottom: 1.5rem;">
            <div style="width: 70px; height: 70px; border-radius: 15px; background: linear-gradient(135deg, rgba(4, 170, 109, 0.15), rgba(4, 170, 109, 0.05)); display: flex; align-items: center; justify-content: center; color: #04AA6D; flex-shrink: 0;">
                <i class="fas fa-graduation-cap" style="font-size: 2rem;"></i>
            </div>
            <div style="flex: 1;">
                <div style="font-size: 2.5rem; font-weight: 800; color: #2c3e50; margin-bottom: 0.25rem; line-height: 1;">
                    {{ $stats['completed_formations'] ?? 0 }}
                </div>
                <div style="color: #64748b; font-size: 0.95rem; font-weight: 500;" class="dashboard-text-secondary">{{ trans('app.profile.dashboard.overview.completed_formations') }}</div>
                <div style="color: #94a3b8; font-size: 0.85rem; margin-top: 0.25rem;" class="dashboard-text-tertiary">{{ $formationProgress->count() ?? 0 }} {{ trans('app.profile.dashboard.overview.total') }}</div>
            </div>
        </div>
    </div>
    
    <!-- Exercices complétés -->
    <div class="content-card" style="margin-bottom: 0;">
        <div style="display: flex; align-items: center; gap: 1.25rem; margin-bottom: 1.5rem;">
            <div style="width: 70px; height: 70px; border-radius: 15px; background: linear-gradient(135deg, rgba(4, 170, 109, 0.15), rgba(4, 170, 109, 0.05)); display: flex; align-items: center; justify-content: center; color: #04AA6D; flex-shrink: 0;">
                <i class="fas fa-code" style="font-size: 2rem;"></i>
            </div>
            <div style="flex: 1;">
                <div style="font-size: 2.5rem; font-weight: 800; color: #2c3e50; margin-bottom: 0.25rem; line-height: 1;">
                    {{ $stats['completed_exercises'] ?? 0 }}
                </div>
                <div style="color: #64748b; font-size: 0.95rem; font-weight: 500;" class="dashboard-text-secondary">{{ trans('app.profile.dashboard.overview.completed_exercises') }}</div>
                <div style="color: #94a3b8; font-size: 0.85rem; margin-top: 0.25rem;" class="dashboard-text-tertiary">{{ $exerciseProgress->count() ?? 0 }} {{ trans('app.profile.dashboard.overview.total') }}</div>
            </div>
        </div>
    </div>
    
    <!-- Quiz passés -->
    <div class="content-card" style="margin-bottom: 0;">
        <div style="display: flex; align-items: center; gap: 1.25rem; margin-bottom: 1.5rem;">
            <div style="width: 70px; height: 70px; border-radius: 15px; background: linear-gradient(135deg, rgba(4, 170, 109, 0.15), rgba(4, 170, 109, 0.05)); display: flex; align-items: center; justify-content: center; color: #04AA6D; flex-shrink: 0;">
                <i class="fas fa-question-circle" style="font-size: 2rem;"></i>
            </div>
            <div style="flex: 1;">
                <div style="font-size: 2.5rem; font-weight: 800; color: #2c3e50; margin-bottom: 0.25rem; line-height: 1;">
                    {{ $stats['quiz_count'] ?? 0 }}
                </div>
                <div style="color: #64748b; font-size: 0.95rem; font-weight: 500;" class="dashboard-text-secondary">{{ trans('app.profile.dashboard.overview.quiz_passed') }}</div>
                <div style="color: #94a3b8; font-size: 0.85rem; margin-top: 0.25rem;" class="dashboard-text-tertiary">{{ trans('app.profile.dashboard.overview.average_score') }}: {{ $stats['average_quiz_score'] ?? 0 }}%</div>
            </div>
        </div>
    </div>
</div>

<!-- Formations en cours -->
@if(isset($formationProgress) && $formationProgress->where('progress_percentage', '<', 100)->count() > 0)
<div class="content-card" style="margin-bottom: 2.5rem;">
    <h2 class="card-title dashboard-text-primary">
        <i class="fas fa-book-open"></i>
        {{ trans('app.profile.dashboard.overview.ongoing_formations') ?? 'Formations en cours' }}
    </h2>
    <div style="display: grid; gap: 1.25rem;">
        @foreach($formationProgress->where('progress_percentage', '<', 100)->take(5) as $progress)
        <div style="padding: 1.25rem; background: linear-gradient(135deg, rgba(6, 182, 212, 0.05), rgba(6, 182, 212, 0.02)); border: 1px solid rgba(6, 182, 212, 0.2); border-radius: 12px; transition: all 0.3s ease;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                <div style="flex: 1;">
                    <h3 class="dashboard-text-primary" style="font-size: 1.15rem; font-weight: 600; color: #2c3e50; margin: 0 0 0.5rem 0;">
                        {{ ucfirst(str_replace('-', ' ', $progress->formation_slug)) }}
                    </h3>
                    <div class="dashboard-text-secondary" style="display: flex; gap: 1.5rem; color: #64748b; font-size: 0.875rem; margin-top: 0.5rem;">
                        <span><i class="fas fa-clock"></i> {{ $progress->time_spent_minutes }} {{ trans('app.profile.dashboard.overview.minutes') }}</span>
                        <span style="color: #06b6d4;"><i class="fas fa-hourglass-half"></i> {{ trans('app.profile.dashboard.formations.in_progress') }}</span>
                    </div>
                </div>
                <div class="dashboard-progress-badge" style="padding: 0.5rem 1rem; background: rgba(6, 182, 212, 0.2); border-radius: 6px;">
                    <div style="font-size: 1.5rem; font-weight: 700; color: #06b6d4;">{{ $progress->progress_percentage }}%</div>
                </div>
            </div>
            <div class="dashboard-progress-bar-bg" style="width: 100%; height: 10px; background: rgba(6, 182, 212, 0.1); border-radius: 5px; overflow: hidden; margin-bottom: 1rem;">
                <div class="dashboard-progress-bar-fill" style="height: 100%; width: {{ $progress->progress_percentage }}%; background: linear-gradient(90deg, #06b6d4, #22d3ee); transition: width 0.6s ease;"></div>
            </div>
            <div style="text-align: right;">
                <a href="{{ route('formations.' . $progress->formation_slug) }}" class="dashboard-button-primary" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1.25rem; background: linear-gradient(135deg, #06b6d4, #0891b2); color: white; border-radius: 6px; text-decoration: none; font-weight: 500; font-size: 0.9rem; transition: all 0.2s ease; box-shadow: 0 4px 6px rgba(6, 182, 212, 0.3);">
                    {{ trans('app.profile.dashboard.formations.continue') }}
                    <i class="fas fa-arrow-right" style="font-size: 0.8rem;"></i>
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @if($formationProgress->where('progress_percentage', '<', 100)->count() > 5)
    <div style="margin-top: 1.5rem; text-align: center;">
        <a href="{{ route('dashboard.formations') }}" style="display: inline-flex; align-items: center; gap: 0.75rem; padding: 0.875rem 1.75rem; background: linear-gradient(135deg, #06b6d4, #0891b2); color: white; border-radius: 12px; text-decoration: none; font-weight: 600; font-size: 1rem; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);">
            {{ trans('app.profile.dashboard.overview.view_all_formations') ?? 'Voir toutes les formations' }}
            <i class="fas fa-arrow-right"></i>
        </a>
    </div>
    @endif
</div>
@endif

<!-- Graphiques de progression -->
@if(isset($chartData))
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
    <!-- Graphique 1: Activité sur 30 jours -->
    <div class="content-card">
        <h2 class="card-title dashboard-text-primary">
            <i class="fas fa-chart-line"></i>
            {{ trans('app.profile.dashboard.statistics.activity_over_time') }}
        </h2>
        <div style="height: 300px; position: relative;">
            <canvas id="activityChart" height="300"></canvas>
        </div>
    </div>
    
    <!-- Graphique 2: Répartition des activités -->
    <div class="content-card">
        <h2 class="card-title dashboard-text-primary">
            <i class="fas fa-chart-pie"></i>
            {{ trans('app.profile.dashboard.statistics.activity_distribution') ?? 'Répartition des activités' }}
        </h2>
        <div style="height: 300px; position: relative;">
            <canvas id="distributionChart" height="300"></canvas>
        </div>
    </div>
    
    <!-- Graphique 3: Progression par formation -->
    @if(isset($chartData['formation_progress']) && count($chartData['formation_progress']['labels']) > 0)
    <div class="content-card">
        <h2 class="card-title dashboard-text-primary">
            <i class="fas fa-chart-bar"></i>
            {{ trans('app.profile.dashboard.statistics.formation_progress') ?? 'Progression par formation' }}
        </h2>
        <div style="height: 300px; position: relative;">
            <canvas id="formationChart" height="300"></canvas>
        </div>
    </div>
    @endif
    
    <!-- Graphique 4: Scores des quiz -->
    @if(isset($chartData['quiz_scores']) && count($chartData['quiz_scores']['labels']) > 0)
    <div class="content-card">
        <h2 class="card-title dashboard-text-primary">
            <i class="fas fa-chart-bar"></i>
            {{ trans('app.profile.dashboard.statistics.quiz_scores') ?? 'Scores des quiz' }}
        </h2>
        <div style="height: 300px; position: relative;">
            <canvas id="quizChart" height="300"></canvas>
        </div>
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    (function() {
        const isDarkMode = document.body.classList.contains('dark-mode');
        const textColor = isDarkMode ? 'rgba(255, 255, 255, 0.6)' : '#94a3b8';
        const gridColor = isDarkMode ? 'rgba(6, 182, 212, 0.2)' : 'rgba(6, 182, 212, 0.1)';
        const backgroundColor = isDarkMode ? 'rgba(6, 182, 212, 0.2)' : 'rgba(6, 182, 212, 0.1)';
        
        // Graphique 1: Activité sur 30 jours
        const activityCtx = document.getElementById('activityChart');
        if (activityCtx) {
            const activityData = @json($chartData['progression_over_time'] ?? []);
            new Chart(activityCtx, {
                type: 'line',
                data: {
                    labels: activityData.labels || [],
                    datasets: [{
                        label: '{{ trans('app.profile.dashboard.statistics.activities') ?? 'Activités' }}',
                        data: activityData.data || [],
                        borderColor: '#06b6d4',
                        backgroundColor: backgroundColor,
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
                            ticks: { color: textColor, font: { size: 12 } },
                            grid: { color: gridColor }
                        },
                        x: {
                            ticks: { color: textColor, font: { size: 12 } },
                            grid: { color: gridColor }
                        }
                    }
                }
            });
        }
        
        // Graphique 2: Répartition des activités
        const distributionCtx = document.getElementById('distributionChart');
        if (distributionCtx) {
            const distributionData = @json($chartData['activity_distribution'] ?? []);
            new Chart(distributionCtx, {
                type: 'doughnut',
                data: {
                    labels: distributionData.labels || [],
                    datasets: [{
                        data: distributionData.data || [],
                        backgroundColor: [
                            'rgba(6, 182, 212, 0.8)',
                            'rgba(4, 170, 109, 0.8)',
                            'rgba(239, 68, 68, 0.8)',
                        ],
                        borderColor: isDarkMode ? 'rgba(15, 23, 42, 0.8)' : '#ffffff',
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color: textColor, font: { size: 12 } }
                        }
                    }
                }
            });
        }
        
        // Graphique 3: Progression par formation
        const formationCtx = document.getElementById('formationChart');
        if (formationCtx) {
            const formationData = @json($chartData['formation_progress'] ?? []);
            new Chart(formationCtx, {
                type: 'bar',
                data: {
                    labels: formationData.labels || [],
                    datasets: [{
                        label: '{{ trans('app.profile.dashboard.statistics.progress') ?? 'Progression' }} (%)',
                        data: formationData.data || [],
                        backgroundColor: 'rgba(6, 182, 212, 0.8)',
                        borderColor: '#06b6d4',
                        borderWidth: 1,
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
                            max: 100,
                            ticks: { color: textColor, font: { size: 12 } },
                            grid: { color: gridColor }
                        },
                        x: {
                            ticks: { color: textColor, font: { size: 12 }, maxRotation: 45, minRotation: 45 },
                            grid: { display: false }
                        }
                    }
                }
            });
        }
        
        // Graphique 4: Scores des quiz
        const quizCtx = document.getElementById('quizChart');
        if (quizCtx) {
            const quizData = @json($chartData['quiz_scores'] ?? []);
            new Chart(quizCtx, {
                type: 'bar',
                data: {
                    labels: quizData.labels || [],
                    datasets: [{
                        label: '{{ trans('app.profile.dashboard.statistics.score') ?? 'Score' }} (%)',
                        data: quizData.data || [],
                        backgroundColor: 'rgba(4, 170, 109, 0.8)',
                        borderColor: '#04AA6D',
                        borderWidth: 1,
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
                            max: 100,
                            ticks: { color: textColor, font: { size: 12 } },
                            grid: { color: gridColor }
                        },
                        x: {
                            ticks: { color: textColor, font: { size: 12 }, maxRotation: 45, minRotation: 45 },
                            grid: { display: false }
                        }
                    }
                }
            });
        }
    })();
</script>
@endif

<!-- Recommandations -->
@if(isset($recommendations) && count($recommendations) > 0)
<div class="content-card">
    <h2 class="card-title">
        <i class="fas fa-lightbulb"></i>
        {{ trans('app.profile.dashboard.overview.recommendations_title') }}
    </h2>
    <div style="display: grid; gap: 1.25rem;">
        @foreach($recommendations as $recommendation)
        @php
            $priorityClass = $recommendation['priority'] ?? 'medium';
            $priorityColors = [
                'high' => ['bg' => 'rgba(239, 68, 68, 0.1)', 'border' => 'rgba(239, 68, 68, 0.3)', 'text' => '#ef4444', 'icon' => 'fa-exclamation-circle'],
                'medium' => ['bg' => 'rgba(4, 170, 109, 0.1)', 'border' => 'rgba(4, 170, 109, 0.3)', 'text' => '#04AA6D', 'icon' => 'fa-info-circle'],
                'low' => ['bg' => 'rgba(100, 116, 139, 0.1)', 'border' => 'rgba(100, 116, 139, 0.3)', 'text' => '#64748b', 'icon' => 'fa-lightbulb'],
            ];
            $colors = $priorityColors[$priorityClass] ?? $priorityColors['medium'];
            $icon = $recommendation['icon'] ?? 'fa-star';
        @endphp
        <div style="padding: 1.5rem; background: linear-gradient(135deg, {{ $colors['bg'] }}, rgba(255, 255, 255, 0.5)); border: 1px solid {{ $colors['border'] }}; border-radius: 12px; border-left: 4px solid {{ $colors['text'] }}; position: relative;">
            <div style="display: flex; align-items: start; gap: 1rem; margin-bottom: 0.75rem;">
                <div style="width: 45px; height: 45px; border-radius: 12px; background: {{ $colors['bg'] }}; display: flex; align-items: center; justify-content: center; color: {{ $colors['text'] }}; flex-shrink: 0; font-size: 1.25rem;">
                    <i class="fas {{ $icon }}"></i>
                </div>
                <div style="flex: 1;">
                    <h3 style="font-size: 1.15rem; font-weight: 700; color: #2c3e50; margin: 0 0 0.5rem 0; display: flex; align-items: center; gap: 0.5rem;">
                        {{ $recommendation['title'] }}
                        @if($priorityClass === 'high')
                            <span style="padding: 0.25rem 0.5rem; background: rgba(239, 68, 68, 0.2); color: #ef4444; border-radius: 6px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase;">{{ trans('app.profile.dashboard.overview.priority') }}</span>
                        @endif
                    </h3>
                    <p style="color: #64748b; margin: 0; font-size: 0.95rem; line-height: 1.7;">{{ $recommendation['description'] }}</p>
                </div>
            </div>
            <div style="display: flex; gap: 0.75rem; flex-wrap: wrap; margin-top: 1rem;">
                @foreach($recommendation['items'] as $item)
                @php
                    $route = '';
                    $itemName = ucfirst(str_replace('-', ' ', $item));
                    if($recommendation['type'] === 'formation' || $recommendation['type'] === 'continue') {
                        $route = route('formations.' . $item);
                    } elseif($recommendation['type'] === 'exercise') {
                        $route = route('exercices') . '?lang=' . $item;
                    } elseif($recommendation['type'] === 'quiz') {
                        $route = route('quiz') . '?lang=' . $item;
                    }
                @endphp
                @if($route)
                <a href="{{ $route }}" style="padding: 0.5rem 1rem; background: white; border: 1px solid {{ $colors['border'] }}; border-radius: 8px; color: {{ $colors['text'] }}; font-size: 0.85rem; font-weight: 600; text-decoration: none; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-arrow-right" style="font-size: 0.75rem;"></i>
                    {{ $itemName }}
                </a>
                @else
                <span style="padding: 0.5rem 1rem; background: white; border: 1px solid {{ $colors['border'] }}; border-radius: 8px; color: {{ $colors['text'] }}; font-size: 0.85rem; font-weight: 600;">
                    {{ $itemName }}
                </span>
                @endif
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Activités récentes -->
@if(isset($recentActivities) && $recentActivities->count() > 0)
<div class="content-card">
    <h2 class="card-title">
        <i class="fas fa-history"></i>
        {{ trans('app.profile.dashboard.overview.recent_activities') }}
    </h2>
    <div style="display: grid; gap: 1rem;">
        @foreach($recentActivities->take(5) as $activity)
        <div style="display: flex; align-items: center; gap: 1.25rem; padding: 1.25rem; background: linear-gradient(135deg, rgba(4, 170, 109, 0.05), rgba(4, 170, 109, 0.02)); border: 1px solid rgba(4, 170, 109, 0.1); border-radius: 12px; transition: all 0.3s ease;">
            <div style="width: 50px; height: 50px; border-radius: 12px; background: linear-gradient(135deg, rgba(4, 170, 109, 0.2), rgba(4, 170, 109, 0.1)); display: flex; align-items: center; justify-content: center; color: #04AA6D; flex-shrink: 0; font-size: 1.5rem;">
                @if($activity->activity_type === 'formation')
                    <i class="fas fa-book"></i>
                @elseif($activity->activity_type === 'exercise')
                    <i class="fas fa-code"></i>
                @elseif($activity->activity_type === 'quiz')
                    <i class="fas fa-question-circle"></i>
                @else
                    <i class="fas fa-check-circle"></i>
                @endif
            </div>
            <div style="flex: 1;">
                <div style="font-weight: 600; color: #2c3e50; margin-bottom: 0.25rem; font-size: 1rem;">{{ $activity->activity_name }}</div>
                <div style="font-size: 0.85rem; color: #64748b;">
                    <i class="fas fa-clock" style="margin-right: 0.5rem;"></i>
                    {{ $activity->created_at->diffForHumans() }}
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div style="margin-top: 1.5rem; text-align: center;">
        <a href="{{ route('dashboard.activities') }}" style="display: inline-flex; align-items: center; gap: 0.75rem; padding: 0.875rem 1.75rem; background: linear-gradient(135deg, #04AA6D, #038f5a); color: white; border-radius: 12px; text-decoration: none; font-weight: 600; font-size: 1rem; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(4, 170, 109, 0.3);">
            {{ trans('app.profile.dashboard.overview.view_all_activities') }}
            <i class="fas fa-arrow-right"></i>
        </a>
    </div>
</div>
@endif

<style>
    /* Dark mode pour overview */
    body.dark-mode .content-card {
        background: rgba(15, 23, 42, 0.6);
        border-color: rgba(4, 170, 109, 0.2);
    }
    
    body.dark-mode .dashboard-text-secondary,
    body.dark-mode div[style*="color: #64748b"] {
        color: rgba(255, 255, 255, 0.7) !important;
    }
    
    body.dark-mode .dashboard-text-tertiary,
    body.dark-mode div[style*="color: #94a3b8"] {
        color: rgba(255, 255, 255, 0.5) !important;
    }
    
    body.dark-mode div[style*="color: #2c3e50"] {
        color: #ffffff !important;
    }
    
    body.dark-mode h3[style*="color: #2c3e50"] {
        color: #ffffff !important;
    }
    
    body.dark-mode p[style*="color: #64748b"] {
        color: rgba(255, 255, 255, 0.7) !important;
    }
    
    body.dark-mode a[style*="background: white"] {
        background: rgba(15, 23, 42, 0.8) !important;
        border-color: rgba(4, 170, 109, 0.3) !important;
    }
    
    body.dark-mode span[style*="background: white"] {
        background: rgba(15, 23, 42, 0.8) !important;
        border-color: rgba(4, 170, 109, 0.3) !important;
    }
    
    body.dark-mode .dashboard-progress-badge {
        background: rgba(6, 182, 212, 0.3) !important;
    }
    
    body.dark-mode .dashboard-progress-badge div[style*="color: #06b6d4"] {
        color: #06b6d4 !important;
    }
    
    body.dark-mode .dashboard-progress-bar-bg {
        background: rgba(6, 182, 212, 0.2) !important;
    }
    
    body.dark-mode .dashboard-progress-bar-fill {
        background: linear-gradient(90deg, #06b6d4, #22d3ee) !important;
    }
    
    body.dark-mode .card-title {
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    body.dark-mode .dashboard-button-primary {
        box-shadow: 0 4px 6px rgba(6, 182, 212, 0.4) !important;
    }
    
    body.dark-mode .dashboard-button-primary:hover {
        box-shadow: 0 6px 12px rgba(6, 182, 212, 0.5) !important;
        transform: translateY(-2px);
    }
    
    body.dark-mode div[style*="background: linear-gradient(135deg, rgba(6, 182, 212"] {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.15), rgba(6, 182, 212, 0.05)) !important;
        border-color: rgba(6, 182, 212, 0.3) !important;
    }
</style>
@endsection
