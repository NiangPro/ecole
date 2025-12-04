@extends('dashboard.layout')

@section('dashboard-content')
@php
    // S'assurer que la locale est définie
    if (!app()->getLocale()) {
        app()->setLocale(session('language', 'fr'));
    }
    $pageTitle = trans('app.profile.dashboard.exercices.title');
    $pageDescription = trans('app.profile.dashboard.exercices.description');
@endphp

@if(isset($exerciseProgress) && $exerciseProgress->count() > 0)
<div style="display: grid; gap: 0.75rem;">
    @foreach($exerciseProgress->take(30) as $exercise)
    <div class="content-card exercise-item" style="padding: 1rem; background: rgba(6, 182, 212, 0.05); border: 1px solid rgba(6, 182, 212, 0.2); border-radius: 8px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 0; transition: all 0.3s ease; cursor: pointer;" onclick="window.location.href='{{ route('exercices.language', $exercise->language) }}'">
        <div style="display: flex; align-items: center; gap: 1rem; flex: 1;">
            <div class="exercise-icon" style="width: 40px; height: 40px; border-radius: 8px; background: rgba(6, 182, 212, 0.2); display: flex; align-items: center; justify-content: center; color: #06b6d4;">
                <i class="fas fa-code"></i>
            </div>
            <div style="flex: 1;">
                <div class="dashboard-text-primary" style="font-weight: 500; color: #2c3e50; margin-bottom: 0.125rem; font-size: 0.9rem;">
                    {{ trans('app.profile.dashboard.exercices.exercise') }} {{ $exercise->exercise_id }} - {{ ucfirst($exercise->language) }}
                </div>
                <div class="dashboard-text-secondary" style="font-size: 0.8rem; color: #64748b;">
                    @if($exercise->completed)
                        <i class="fas fa-check-circle" style="color: #10b981;"></i> {{ trans('app.profile.dashboard.exercices.completed') }}
                        @if($exercise->completed_at)
                            <span style="margin-left: 0.5rem;">• {{ $exercise->completed_at->format('d/m/Y') }}</span>
                        @endif
                    @else
                        <i class="fas fa-clock" style="color: #06b6d4;"></i> {{ trans('app.profile.dashboard.exercices.in_progress') }}
                    @endif
                </div>
            </div>
        </div>
        <div style="display: flex; align-items: center; gap: 1rem;">
            @if($exercise->completed)
            <div class="exercise-score-badge" style="padding: 0.375rem 0.75rem; background: rgba(16, 185, 129, 0.2); border-radius: 6px;">
                <div style="font-size: 1rem; font-weight: 600; color: #10b981;">{{ $exercise->score }}%</div>
            </div>
            @endif
            <a href="{{ route('exercices.language', $exercise->language) }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: linear-gradient(135deg, #06b6d4, #0891b2); color: white; border-radius: 6px; text-decoration: none; font-weight: 500; font-size: 0.875rem; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(6, 182, 212, 0.3);" onclick="event.stopPropagation();">
                <i class="fas fa-arrow-right"></i>
                {{ trans('app.profile.dashboard.exercices.view') }}
            </a>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="content-card" style="text-align: center; padding: 3rem 2rem;">
    <div class="dashboard-empty-icon" style="width: 80px; height: 80px; margin: 0 auto 1.25rem; border-radius: 50%; background: rgba(6, 182, 212, 0.2); display: flex; align-items: center; justify-content: center; color: #06b6d4; font-size: 2rem;">
        <i class="fas fa-code"></i>
    </div>
    <h3 class="dashboard-text-primary" style="font-size: 1.5rem; font-weight: 600; color: #2c3e50; margin: 0 0 0.5rem 0;">{{ trans('app.profile.dashboard.exercices.no_exercises') }}</h3>
    <p class="dashboard-text-secondary" style="color: #64748b; margin: 0 0 1.5rem 0;">{{ trans('app.profile.dashboard.exercices.no_exercises_desc') }}</p>
    <a href="{{ route('exercices') }}" class="dashboard-button-primary" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #06b6d4, #0891b2); color: white; border-radius: 6px; text-decoration: none; font-weight: 500; box-shadow: 0 4px 6px rgba(6, 182, 212, 0.3);">
        <i class="fas fa-code"></i>
        {{ trans('app.profile.dashboard.exercices.view_exercises') }}
    </a>
</div>
@endif

<style>
    /* Dark Mode Styles pour la page Exercices */
    body.dark-mode .dashboard-text-primary {
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    body.dark-mode .dashboard-text-secondary {
        color: rgba(255, 255, 255, 0.6) !important;
    }
    
    body.dark-mode .dashboard-text-secondary i[style*="color: #10b981"] {
        color: #10b981 !important;
    }
    
    body.dark-mode .dashboard-text-secondary i[style*="color: #06b6d4"] {
        color: #06b6d4 !important;
    }
    
    body.dark-mode .exercise-item {
        background: rgba(6, 182, 212, 0.1) !important;
        border-color: rgba(6, 182, 212, 0.3) !important;
    }
    
    body.dark-mode .exercise-icon {
        background: rgba(6, 182, 212, 0.3) !important;
        color: #06b6d4 !important;
    }
    
    body.dark-mode .exercise-score-badge {
        background: rgba(16, 185, 129, 0.3) !important;
    }
    
    body.dark-mode .exercise-score-badge div {
        color: #10b981 !important;
    }
    
    body.dark-mode .dashboard-empty-icon {
        background: rgba(6, 182, 212, 0.3) !important;
        color: #06b6d4 !important;
    }
    
    body.dark-mode .dashboard-button-primary {
        box-shadow: 0 4px 6px rgba(6, 182, 212, 0.4) !important;
    }
    
    body.dark-mode .dashboard-button-primary:hover {
        box-shadow: 0 6px 12px rgba(6, 182, 212, 0.5) !important;
        transform: translateY(-2px);
    }
    
    /* Styles pour les cartes d'exercice en dark mode */
    body.dark-mode .content-card {
        background: rgba(15, 23, 42, 0.6) !important;
        border-color: rgba(4, 170, 109, 0.2) !important;
    }
    
    body.dark-mode .content-card:hover {
        border-color: rgba(4, 170, 109, 0.3) !important;
        box-shadow: 0 8px 25px rgba(4, 170, 109, 0.15) !important;
    }
</style>
@endsection
