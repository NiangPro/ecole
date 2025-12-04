@extends('dashboard.layout')

@section('dashboard-content')
@php
    if (!app()->getLocale()) {
        app()->setLocale(session('language', 'fr'));
    }
    $pageTitle = trans('app.profile.dashboard.certificates.title') ?? 'Certificats';
    $pageDescription = trans('app.profile.dashboard.certificates.description') ?? 'Vos certificats de complétion';
@endphp

@if(isset($certificates) && $certificates->count() > 0)
<div class="content-card">
    <h2 class="card-title dashboard-text-primary">
        <i class="fas fa-certificate"></i>
        {{ trans('app.profile.dashboard.certificates.title') ?? 'Mes Certificats' }}
    </h2>
    <p class="dashboard-text-secondary" style="margin-bottom: 2rem;">
        {{ trans('app.profile.dashboard.certificates.description') ?? 'Téléchargez vos certificats de complétion' }}
    </p>

    <div style="display: grid; gap: 1.5rem;">
        @foreach($certificates as $certificate)
        <div class="certificate-card" style="
            padding: 1.5rem;
            background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.1));
            border: 2px solid rgba(6, 182, 212, 0.3);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.3s ease;
        ">
            <div style="flex: 1;">
                <h3 class="dashboard-text-primary" style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem;">
                    <i class="fas fa-graduation-cap mr-2" style="color: #06b6d4;"></i>
                    {{ ucfirst(str_replace('-', ' ', $certificate->formation_slug)) }}
                </h3>
                <div class="dashboard-text-secondary" style="font-size: 0.9rem; margin-bottom: 0.5rem;">
                    <i class="fas fa-hashtag mr-2"></i>
                    <strong>{{ trans('app.profile.dashboard.certificates.certificate_number') ?? 'N°' }}:</strong> {{ $certificate->certificate_number }}
                </div>
                <div class="dashboard-text-secondary" style="font-size: 0.875rem;">
                    <i class="fas fa-calendar-check mr-2"></i>
                    {{ trans('app.profile.dashboard.certificates.completed_on') ?? 'Complété le' }}: {{ $certificate->completed_date->format(app()->getLocale() === 'fr' ? 'd/m/Y' : 'm/d/Y') }}
                </div>
                @if($certificate->score)
                <div class="dashboard-text-secondary" style="font-size: 0.875rem; margin-top: 0.25rem;">
                    <i class="fas fa-star mr-2" style="color: #fbbf24;"></i>
                    {{ trans('app.profile.dashboard.certificates.score') ?? 'Score' }}: {{ $certificate->score }}%
                </div>
                @endif
            </div>
            <div style="display: flex; gap: 0.75rem; align-items: center;">
                @if($certificate->hasPdf())
                <a href="{{ route('dashboard.certificates.download', $certificate->id) }}" 
                   class="dashboard-button-primary" 
                   style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.25rem; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: white; border: none; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.9rem; cursor: pointer; box-shadow: 0 4px 12px rgba(6, 182, 212, 0.3); transition: all 0.3s ease;">
                    <i class="fas fa-download"></i>
                    {{ trans('app.profile.dashboard.certificates.download') ?? 'Télécharger' }}
                </a>
                <a href="{{ route('dashboard.certificates.show', $certificate->id) }}" 
                   class="dashboard-button-secondary" 
                   style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.25rem; background: rgba(6, 182, 212, 0.1); color: #06b6d4; border: 2px solid rgba(6, 182, 212, 0.3); border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.9rem; cursor: pointer; transition: all 0.3s ease;">
                    <i class="fas fa-eye"></i>
                    {{ trans('app.profile.dashboard.certificates.view') ?? 'Voir' }}
                </a>
                @else
                <span class="dashboard-text-secondary" style="font-size: 0.875rem; font-style: italic;">
                    {{ trans('app.profile.dashboard.certificates.generating') ?? 'Génération en cours...' }}
                </span>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@else
<div class="content-card" style="text-align: center; padding: 3rem 2rem;">
    <div class="dashboard-empty-icon" style="width: 80px; height: 80px; margin: 0 auto 1.25rem; border-radius: 50%; background: rgba(6, 182, 212, 0.2); display: flex; align-items: center; justify-content: center; color: #06b6d4; font-size: 2rem;">
        <i class="fas fa-certificate"></i>
    </div>
    <h3 class="dashboard-text-primary" style="font-size: 1.5rem; font-weight: 600; color: #2c3e50; margin: 0 0 0.5rem 0;">
        {{ trans('app.profile.dashboard.certificates.no_certificates') ?? 'Aucun certificat disponible' }}
    </h3>
    <p class="dashboard-text-secondary" style="color: #64748b; margin: 0 0 1.5rem 0;">
        {{ trans('app.profile.dashboard.certificates.no_certificates_desc') ?? 'Complétez des formations pour obtenir des certificats' }}
    </p>
    <a href="{{ route('dashboard.formations') }}" class="dashboard-button-primary" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: white; border: none; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.95rem; cursor: pointer; box-shadow: 0 4px 12px rgba(6, 182, 212, 0.3);">
        <i class="fas fa-book"></i>
        {{ trans('app.profile.dashboard.certificates.view_formations') ?? 'Voir les formations' }}
    </a>
</div>
@endif

<style>
    /* Dark Mode Styles pour la page Certificates */
    body.dark-mode .dashboard-text-primary {
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    body.dark-mode .dashboard-text-secondary {
        color: rgba(255, 255, 255, 0.6) !important;
    }
    
    body.dark-mode .certificate-card {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.15), rgba(20, 184, 166, 0.15)) !important;
        border-color: rgba(6, 182, 212, 0.4) !important;
    }
    
    body.dark-mode .certificate-card:hover {
        border-color: rgba(6, 182, 212, 0.6) !important;
        box-shadow: 0 8px 25px rgba(6, 182, 212, 0.2) !important;
        transform: translateY(-2px);
    }
    
    body.dark-mode .dashboard-empty-icon {
        background: rgba(6, 182, 212, 0.3) !important;
        color: #06b6d4 !important;
    }
    
    /* Styles pour les cartes de certificats en dark mode */
    body.dark-mode .content-card {
        background: rgba(15, 23, 42, 0.6) !important;
        border-color: rgba(6, 182, 212, 0.2) !important;
    }
    
    body.dark-mode .content-card:hover {
        border-color: rgba(6, 182, 212, 0.3) !important;
        box-shadow: 0 8px 25px rgba(6, 182, 212, 0.15) !important;
    }
    
    .certificate-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(6, 182, 212, 0.2);
    }
    
    .dashboard-button-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(6, 182, 212, 0.4) !important;
    }
    
    .dashboard-button-secondary:hover {
        background: rgba(6, 182, 212, 0.2) !important;
        border-color: rgba(6, 182, 212, 0.5) !important;
    }
</style>
@endsection

