@extends('dashboard.layout')

@section('dashboard-content')
@php
    // S'assurer que la locale est définie
    if (!app()->getLocale()) {
        app()->setLocale(session('language', 'fr'));
    }
    $pageTitle = trans('app.profile.dashboard.formations.title');
    $pageDescription = trans('app.profile.dashboard.formations.description');
@endphp

@if(isset($formationProgress) && $formationProgress->count() > 0)
<div style="display: grid; gap: 1.25rem;">
    @foreach($formationProgress as $progress)
    <div class="content-card" style="margin-bottom: 0;">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1.25rem;">
            <div style="flex: 1;">
                <h3 style="font-size: 1.25rem; font-weight: 600; color: #2c3e50; margin: 0 0 0.5rem 0;" class="dashboard-text-primary">
                    {{ ucfirst(str_replace('-', ' ', $progress->formation_slug)) }}
                </h3>
                <div style="display: flex; gap: 1.5rem; color: #64748b; font-size: 0.875rem; margin-top: 0.5rem;" class="dashboard-text-secondary">
                    <span><i class="fas fa-clock"></i> {{ $progress->time_spent_minutes }} {{ trans('app.profile.dashboard.overview.minutes') }}</span>
                    @if($progress->completed_at)
                        <span style="color: #10b981;"><i class="fas fa-check-circle"></i> {{ trans('app.profile.dashboard.formations.completed_on') }} {{ $progress->completed_at->format('d/m/Y') }}</span>
                    @else
                        <span style="color: #06b6d4;"><i class="fas fa-hourglass-half"></i> {{ trans('app.profile.dashboard.formations.in_progress') }}</span>
                    @endif
                </div>
            </div>
            <div style="padding: 0.5rem 1rem; background: rgba(6, 182, 212, 0.2); border-radius: 6px;" class="dashboard-progress-badge">
                <div style="font-size: 1.5rem; font-weight: 700; color: #06b6d4;">{{ $progress->progress_percentage }}%</div>
            </div>
        </div>
        <div style="width: 100%; height: 8px; background: rgba(6, 182, 212, 0.1); border-radius: 4px; overflow: hidden; margin-bottom: 1rem;" class="dashboard-progress-bar-bg">
            <div style="height: 100%; width: {{ $progress->progress_percentage }}%; background: linear-gradient(90deg, #06b6d4, #22d3ee); transition: width 0.6s ease;" class="dashboard-progress-bar-fill"></div>
        </div>
        <div style="text-align: right;">
            <a href="{{ route('formations.' . $progress->formation_slug) }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1.25rem; background: linear-gradient(135deg, #06b6d4, #0891b2); color: white; border-radius: 6px; text-decoration: none; font-weight: 500; font-size: 0.9rem; transition: all 0.2s ease; box-shadow: 0 4px 6px rgba(6, 182, 212, 0.3);" class="dashboard-button-primary">
                {{ $progress->progress_percentage === 100 ? trans('app.profile.dashboard.formations.review') : trans('app.profile.dashboard.formations.continue') }}
                <i class="fas fa-arrow-right" style="font-size: 0.8rem;"></i>
            </a>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="content-card" style="text-align: center; padding: 3rem 2rem;">
    <div style="width: 80px; height: 80px; margin: 0 auto 1.25rem; border-radius: 50%; background: rgba(6, 182, 212, 0.2); display: flex; align-items: center; justify-content: center; color: #06b6d4; font-size: 2rem;" class="dashboard-empty-icon">
        <i class="fas fa-graduation-cap"></i>
    </div>
    <h3 style="font-size: 1.5rem; font-weight: 600; color: #2c3e50; margin: 0 0 0.5rem 0;" class="dashboard-text-primary">{{ trans('app.profile.dashboard.formations.no_formations') }}</h3>
    <p style="color: #64748b; margin: 0 0 1.5rem 0;" class="dashboard-text-secondary">{{ trans('app.profile.dashboard.formations.no_formations_desc') }}</p>
    <a href="{{ route('formations.all') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #06b6d4, #0891b2); color: white; border-radius: 6px; text-decoration: none; font-weight: 500; box-shadow: 0 4px 6px rgba(6, 182, 212, 0.3);" class="dashboard-button-primary">
        <i class="fas fa-book-open"></i>
        {{ trans('app.profile.dashboard.formations.view_formations') }}
    </a>
</div>
@endif

<style>
    /* Dark Mode Styles pour la page Formations */
    body.dark-mode .dashboard-text-primary {
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    body.dark-mode .dashboard-text-secondary {
        color: rgba(255, 255, 255, 0.6) !important;
    }
    
    body.dark-mode .dashboard-text-secondary span[style*="color: #10b981"] {
        color: #10b981 !important;
    }
    
    body.dark-mode .dashboard-text-secondary span[style*="color: #06b6d4"] {
        color: #06b6d4 !important;
    }
    
    body.dark-mode .dashboard-progress-badge {
        background: rgba(6, 182, 212, 0.3) !important;
    }
    
    body.dark-mode .dashboard-progress-badge div {
        color: #06b6d4 !important;
    }
    
    body.dark-mode .dashboard-progress-bar-bg {
        background: rgba(6, 182, 212, 0.2) !important;
    }
    
    body.dark-mode .dashboard-progress-bar-fill {
        background: linear-gradient(90deg, #06b6d4, #22d3ee) !important;
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
    
    /* Styles pour les cartes de formation en dark mode */
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
