@extends('dashboard.layout')

@section('dashboard-content')
@php
    // S'assurer que la locale est définie
    if (!app()->getLocale()) {
        app()->setLocale(session('language', 'fr'));
    }
    $pageTitle = trans('app.profile.dashboard.activities.title');
    $pageDescription = trans('app.profile.dashboard.activities.description');
@endphp

@if(isset($recentActivities) && $recentActivities->count() > 0)
<div style="display: grid; gap: 0.75rem;">
    @foreach($recentActivities as $activity)
    <div class="activity-item content-card" style="padding: 1rem; background: rgba(6, 182, 212, 0.05); border: 1px solid rgba(6, 182, 212, 0.2); border-radius: 8px; display: flex; align-items: center; gap: 1rem; margin-bottom: 0;">
        <div class="activity-icon" style="width: 40px; height: 40px; border-radius: 8px; background: rgba(6, 182, 212, 0.2); display: flex; align-items: center; justify-content: center; color: #06b6d4; flex-shrink: 0;">
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
            <div class="dashboard-text-primary" style="font-weight: 500; color: #2c3e50; margin-bottom: 0.125rem; font-size: 0.9rem;">{{ $activity->activity_name }}</div>
            <div class="dashboard-text-secondary" style="font-size: 0.8rem; color: #64748b;">
                <i class="fas fa-clock"></i> {{ $activity->created_at->format(app()->getLocale() === 'fr' ? 'd/m/Y' : 'm/d/Y') }} {{ app()->getLocale() === 'fr' ? 'à' : 'at' }} {{ $activity->created_at->format('H:i') }} • {{ $activity->created_at->diffForHumans() }}
            </div>
        </div>
    </div>
    @endforeach
</div>

@if(method_exists($recentActivities, 'links'))
<div style="margin-top: 2rem; display: flex; justify-content: center;">
    {{ $recentActivities->links() }}
</div>
@endif
@else
<div class="content-card" style="text-align: center; padding: 3rem 2rem;">
    <div class="dashboard-empty-icon" style="width: 80px; height: 80px; margin: 0 auto 1.25rem; border-radius: 50%; background: rgba(6, 182, 212, 0.2); display: flex; align-items: center; justify-content: center; color: #06b6d4; font-size: 2rem;">
        <i class="fas fa-history"></i>
    </div>
    <h3 class="dashboard-text-primary" style="font-size: 1.5rem; font-weight: 600; color: #2c3e50; margin: 0 0 0.5rem 0;">{{ trans('app.profile.dashboard.activities.no_activities') }}</h3>
    <p class="dashboard-text-secondary" style="color: #64748b; margin: 0;">{{ trans('app.profile.dashboard.activities.no_activities_desc') }}</p>
</div>
@endif

<style>
    /* Dark Mode Styles pour la page Activities */
    body.dark-mode .dashboard-text-primary {
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    body.dark-mode .dashboard-text-secondary {
        color: rgba(255, 255, 255, 0.6) !important;
    }
    
    body.dark-mode .activity-item {
        background: rgba(6, 182, 212, 0.1) !important;
        border-color: rgba(6, 182, 212, 0.3) !important;
    }
    
    body.dark-mode .activity-icon {
        background: rgba(6, 182, 212, 0.3) !important;
        color: #06b6d4 !important;
    }
    
    body.dark-mode .dashboard-empty-icon {
        background: rgba(6, 182, 212, 0.3) !important;
        color: #06b6d4 !important;
    }
    
    /* Styles pour les cartes d'activités en dark mode */
    body.dark-mode .content-card {
        background: rgba(15, 23, 42, 0.6) !important;
        border-color: rgba(4, 170, 109, 0.2) !important;
    }
    
    body.dark-mode .content-card:hover {
        border-color: rgba(4, 170, 109, 0.3) !important;
        box-shadow: 0 8px 25px rgba(4, 170, 109, 0.15) !important;
    }
    
    /* Styles pour la pagination en dark mode */
    body.dark-mode .pagination {
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    body.dark-mode .pagination a,
    body.dark-mode .pagination span {
        color: rgba(255, 255, 255, 0.9) !important;
        background: rgba(15, 23, 42, 0.6) !important;
        border-color: rgba(4, 170, 109, 0.2) !important;
    }
    
    body.dark-mode .pagination a:hover {
        background: rgba(4, 170, 109, 0.2) !important;
        border-color: rgba(4, 170, 109, 0.3) !important;
    }
    
    body.dark-mode .pagination .active span {
        background: rgba(6, 182, 212, 0.3) !important;
        border-color: rgba(6, 182, 212, 0.5) !important;
        color: #06b6d4 !important;
    }
</style>
@endsection
