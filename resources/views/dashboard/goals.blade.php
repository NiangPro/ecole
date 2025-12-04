@extends('dashboard.layout')

@section('dashboard-content')
@php
    // S'assurer que la locale est définie
    if (!app()->getLocale()) {
        app()->setLocale(session('language', 'fr'));
    }
    $pageTitle = trans('app.profile.dashboard.goals.title');
    $pageDescription = trans('app.profile.dashboard.goals.description');
@endphp

@if(isset($goals) && $goals->count() > 0)
<div style="margin-bottom: 1.25rem; text-align: right;">
    <button onclick="document.getElementById('createGoalModal').style.display='block'" class="dashboard-button-primary" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #06b6d4, #0891b2); color: white; border: none; border-radius: 6px; text-decoration: none; font-weight: 500; font-size: 0.9rem; cursor: pointer; box-shadow: 0 4px 6px rgba(6, 182, 212, 0.3);">
        <i class="fas fa-plus"></i>
        {{ trans('app.profile.dashboard.goals.create_goal') }}
    </button>
</div>
<div style="display: grid; gap: 1.25rem;">
    @foreach($goals as $goal)
    <div class="content-card" style="margin-bottom: 0;">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1.25rem;">
            <div style="flex: 1;">
                <h3 class="dashboard-text-primary" style="font-size: 1.25rem; font-weight: 600; color: #2c3e50; margin: 0 0 0.5rem 0;">{{ $goal->title }}</h3>
                @if($goal->description)
                <p class="dashboard-text-secondary" style="color: #64748b; margin: 0; font-size: 0.9rem; line-height: 1.5;">{{ $goal->description }}</p>
                @endif
            </div>
            <div class="goal-status-badge" style="padding: 0.375rem 0.75rem; background: {{ $goal->completed ? 'rgba(16, 185, 129, 0.2)' : 'rgba(6, 182, 212, 0.2)' }}; border-radius: 6px;">
                <span class="goal-status-text" style="font-size: 0.8rem; font-weight: 600; color: {{ $goal->completed ? '#10b981' : '#06b6d4' }};">
                    {{ $goal->completed ? trans('app.profile.dashboard.goals.completed') : trans('app.profile.dashboard.goals.in_progress') }}
                </span>
            </div>
        </div>
        <div style="margin-bottom: 1rem;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; color: #64748b; font-size: 0.875rem;" class="dashboard-text-secondary">
                <span>{{ trans('app.profile.dashboard.goals.progress') }}</span>
                <span class="dashboard-text-primary" style="font-weight: 600; color: #2c3e50;">{{ $goal->current_value }}/{{ $goal->target_value }} {{ $goal->unit ?? '' }}</span>
            </div>
            <div class="goal-progress-bar-bg" style="width: 100%; height: 8px; background: rgba(6, 182, 212, 0.1); border-radius: 4px; overflow: hidden;">
                <div class="goal-progress-bar-fill" style="height: 100%; width: {{ $goal->progress_percentage }}%; background: linear-gradient(90deg, #06b6d4, #22d3ee); transition: width 0.6s ease;"></div>
            </div>
        </div>
        @if($goal->deadline)
        <div class="dashboard-text-secondary" style="display: flex; align-items: center; gap: 0.5rem; color: #64748b; font-size: 0.875rem;">
            <i class="fas fa-calendar-alt"></i>
            <span>{{ trans('app.profile.dashboard.goals.deadline') }} : {{ $goal->deadline->format(app()->getLocale() === 'fr' ? 'd/m/Y' : 'm/d/Y') }}</span>
        </div>
        @endif
    </div>
    @endforeach
</div>
@else
<div class="content-card" style="text-align: center; padding: 3rem 2rem;">
    <div class="dashboard-empty-icon" style="width: 80px; height: 80px; margin: 0 auto 1.25rem; border-radius: 50%; background: rgba(6, 182, 212, 0.2); display: flex; align-items: center; justify-content: center; color: #06b6d4; font-size: 2rem;">
        <i class="fas fa-bullseye"></i>
    </div>
    <h3 class="dashboard-text-primary" style="font-size: 1.5rem; font-weight: 600; color: #2c3e50; margin: 0 0 0.5rem 0;">{{ trans('app.profile.dashboard.goals.no_goals') }}</h3>
    <p class="dashboard-text-secondary" style="color: #64748b; margin: 0 0 1.5rem 0;">{{ trans('app.profile.dashboard.goals.no_goals_desc') }}</p>
    <button onclick="document.getElementById('createGoalModal').style.display='block'" class="dashboard-button-primary" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #06b6d4, #0891b2); color: white; border: none; border-radius: 6px; text-decoration: none; font-weight: 500; cursor: pointer; box-shadow: 0 4px 6px rgba(6, 182, 212, 0.3);">
        <i class="fas fa-plus"></i>
        {{ trans('app.profile.dashboard.goals.create_goal') }}
    </button>
</div>
@endif

<style>
    /* Dark Mode Styles pour la page Goals */
    body.dark-mode .dashboard-text-primary {
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    body.dark-mode .dashboard-text-secondary {
        color: rgba(255, 255, 255, 0.6) !important;
    }
    
    body.dark-mode .goal-progress-bar-bg {
        background: rgba(6, 182, 212, 0.2) !important;
    }
    
    body.dark-mode .goal-progress-bar-fill {
        background: linear-gradient(90deg, #06b6d4, #22d3ee) !important;
    }
    
    body.dark-mode .dashboard-empty-icon {
        background: rgba(6, 182, 212, 0.3) !important;
        color: #06b6d4 !important;
    }
    
    /* Styles pour les cartes d'objectifs en dark mode */
    body.dark-mode .content-card {
        background: rgba(15, 23, 42, 0.6) !important;
        border-color: rgba(4, 170, 109, 0.2) !important;
    }
    
    body.dark-mode .content-card:hover {
        border-color: rgba(4, 170, 109, 0.3) !important;
        box-shadow: 0 8px 25px rgba(4, 170, 109, 0.15) !important;
    }
    
    /* Styles dynamiques pour les badges de statut */
    body.dark-mode .goal-status-badge[style*="rgba(16, 185, 129"] {
        background: rgba(16, 185, 129, 0.3) !important;
    }
    
    body.dark-mode .goal-status-badge[style*="rgba(6, 182, 212"] {
        background: rgba(6, 182, 212, 0.3) !important;
    }
    
    body.dark-mode .goal-status-text[style*="color: #10b981"] {
        color: #10b981 !important;
    }
    
    body.dark-mode .goal-status-text[style*="color: #06b6d4"] {
        color: #06b6d4 !important;
    }
    
    body.dark-mode .dashboard-button-primary {
        box-shadow: 0 4px 6px rgba(6, 182, 212, 0.4) !important;
    }
    
    body.dark-mode .dashboard-button-primary:hover {
        box-shadow: 0 6px 12px rgba(6, 182, 212, 0.5) !important;
        transform: translateY(-2px);
    }
</style>
@endsection
