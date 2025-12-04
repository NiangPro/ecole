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
</style>
@endsection
