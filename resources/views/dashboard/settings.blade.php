@extends('dashboard.layout')

@section('dashboard-content')
@php
    // S'assurer que la locale est définie
    if (!app()->getLocale()) {
        app()->setLocale(session('language', 'fr'));
    }
    $pageTitle = trans('app.profile.dashboard.settings.title') ?? 'Paramètres';
    $pageDescription = trans('app.profile.dashboard.settings.description') ?? 'Gérez vos préférences et les informations de votre compte';
@endphp

<div style="max-width: 800px;">
    <div class="content-card">
        <h2 class="card-title dashboard-text-primary">
            <i class="fas fa-user"></i>
            {{ trans('app.profile.dashboard.profile.account_info') }}
        </h2>
        <div style="display: grid; gap: 1.25rem;">
            <div>
                <label class="dashboard-text-secondary" style="display: block; color: #64748b; margin-bottom: 0.5rem; font-weight: 500; font-size: 0.9rem;">{{ trans('app.profile.dashboard.profile.full_name') }}</label>
                <div class="settings-info-field" style="padding: 0.875rem 1rem; background: rgba(6, 182, 212, 0.05); border: 1px solid rgba(6, 182, 212, 0.2); border-radius: 6px; color: #2c3e50; font-size: 0.95rem;">
                    {{ $user->name }}
                </div>
            </div>
            <div>
                <label class="dashboard-text-secondary" style="display: block; color: #64748b; margin-bottom: 0.5rem; font-weight: 500; font-size: 0.9rem;">{{ trans('app.profile.dashboard.profile.email') }}</label>
                <div class="settings-info-field" style="padding: 0.875rem 1rem; background: rgba(6, 182, 212, 0.05); border: 1px solid rgba(6, 182, 212, 0.2); border-radius: 6px; color: #2c3e50; font-size: 0.95rem;">
                    {{ $user->email }}
                </div>
            </div>
            @if($user->phone)
            <div>
                <label class="dashboard-text-secondary" style="display: block; color: #64748b; margin-bottom: 0.5rem; font-weight: 500; font-size: 0.9rem;">{{ trans('app.profile.dashboard.profile.phone') }}</label>
                <div class="settings-info-field" style="padding: 0.875rem 1rem; background: rgba(6, 182, 212, 0.05); border: 1px solid rgba(6, 182, 212, 0.2); border-radius: 6px; color: #2c3e50; font-size: 0.95rem;">
                    {{ $user->phone }}
                </div>
            </div>
            @endif
            <div>
                <label class="dashboard-text-secondary" style="display: block; color: #64748b; margin-bottom: 0.5rem; font-weight: 500; font-size: 0.9rem;">{{ trans('app.profile.dashboard.profile.member_since') }}</label>
                <div class="settings-info-field" style="padding: 0.875rem 1rem; background: rgba(6, 182, 212, 0.05); border: 1px solid rgba(6, 182, 212, 0.2); border-radius: 6px; color: #2c3e50; font-size: 0.95rem;">
                    {{ $user->created_at->format(app()->getLocale() === 'fr' ? 'd F Y' : 'F d, Y') }}
                </div>
            </div>
        </div>
    </div>
    
    <div class="content-card" style="margin-top: 1.5rem;">
        <h2 class="card-title dashboard-text-primary">
            <i class="fas fa-cog"></i>
            {{ trans('app.profile.dashboard.settings.preferences') ?? 'Préférences' }}
        </h2>
        <p class="dashboard-text-secondary" style="color: #64748b; line-height: 1.6; font-size: 0.9rem;">{{ trans('app.profile.dashboard.settings.coming_soon') ?? 'Les paramètres avancés seront disponibles prochainement. Vous pourrez modifier vos préférences, notifications et paramètres de confidentialité.' }}</p>
    </div>
</div>

<style>
    /* Dark Mode Styles pour la page Settings */
    body.dark-mode .dashboard-text-primary {
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    body.dark-mode .dashboard-text-secondary {
        color: rgba(255, 255, 255, 0.6) !important;
    }
    
    body.dark-mode .settings-info-field {
        background: rgba(6, 182, 212, 0.1) !important;
        border-color: rgba(6, 182, 212, 0.3) !important;
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    body.dark-mode .card-title {
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    /* Styles pour les cartes en dark mode */
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
