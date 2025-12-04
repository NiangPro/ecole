@extends('dashboard.layout')

@section('dashboard-content')
@php
    if (!app()->getLocale()) {
        app()->setLocale(session('language', 'fr'));
    }
    $pageTitle = trans('app.profile.dashboard.badges.title') ?? 'Badges';
    $pageDescription = trans('app.profile.dashboard.badges.description') ?? 'Vos badges et accomplissements';
@endphp

<div class="content-card">
    <h2 class="card-title dashboard-text-primary">
        <i class="fas fa-trophy"></i>
        {{ trans('app.profile.dashboard.badges.title') ?? 'Mes Badges' }}
    </h2>
    <p class="dashboard-text-secondary" style="margin-bottom: 2rem;">
        {{ trans('app.profile.dashboard.badges.description') ?? 'Découvrez tous vos badges et accomplissements' }}
    </p>

    @if(isset($allBadges) && $allBadges->count() > 0)
    @foreach($badgesByType as $type => $badges)
        @if($badges->count() > 0)
        <div style="margin-bottom: 2.5rem;">
            <h3 class="dashboard-text-primary" style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 2px solid rgba(4, 170, 109, 0.2);">
                {{ trans("app.profile.dashboard.badges.types.{$type}") ?? ucfirst($type) }}
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.25rem;">
                @foreach($badges as $badge)
                    @php
                        $isEarned = in_array($badge->id, $earnedBadgeIds);
                        $userBadge = $earnedBadges->firstWhere('id', $badge->id);
                    @endphp
                    <div class="badge-card" style="
                        padding: 1.5rem;
                        background: {{ $isEarned ? 'linear-gradient(135deg, ' . $badge->color . '15, ' . $badge->color . '05)' : 'linear-gradient(135deg, rgba(100, 116, 139, 0.1), rgba(100, 116, 139, 0.05))' }};
                        border: 2px solid {{ $isEarned ? $badge->color : 'rgba(100, 116, 139, 0.3)' }};
                        border-radius: 12px;
                        text-align: center;
                        transition: all 0.3s ease;
                        opacity: {{ $isEarned ? '1' : '0.5' }};
                    ">
                        <div style="
                            width: 80px;
                            height: 80px;
                            margin: 0 auto 1rem;
                            border-radius: 50%;
                            background: {{ $isEarned ? 'linear-gradient(135deg, ' . $badge->color . ', ' . $badge->color . 'dd)' : 'rgba(100, 116, 139, 0.3)' }};
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            color: {{ $isEarned ? '#ffffff' : 'rgba(100, 116, 139, 0.5)' }};
                            font-size: 2rem;
                            box-shadow: {{ $isEarned ? '0 4px 15px ' . $badge->color . '40' : 'none' }};
                        ">
                            <i class="fas {{ $badge->icon }}"></i>
                        </div>
                        <h4 class="dashboard-text-primary" style="font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem;">
                            {{ $badge->name }}
                        </h4>
                        <p class="dashboard-text-secondary" style="font-size: 0.85rem; margin-bottom: 0.75rem;">
                            {{ $badge->description }}
                        </p>
                        @if($isEarned && $userBadge)
                            <div style="font-size: 0.75rem; color: {{ $badge->color }}; font-weight: 600;">
                                <i class="fas fa-check-circle"></i>
                                {{ trans('app.profile.dashboard.badges.earned_on') ?? 'Obtenu le' }} {{ $userBadge->earned_at->format('d/m/Y') }}
                            </div>
                        @else
                            <div style="font-size: 0.75rem; color: #64748b; font-weight: 500;">
                                <i class="fas fa-lock"></i>
                                {{ trans('app.profile.dashboard.badges.not_earned') ?? 'Non obtenu' }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    @endforeach
    @else
    <div class="content-card" style="text-align: center; padding: 3rem 2rem;">
        <div class="dashboard-empty-icon" style="width: 80px; height: 80px; margin: 0 auto 1.25rem; border-radius: 50%; background: rgba(6, 182, 212, 0.2); display: flex; align-items: center; justify-content: center; color: #06b6d4; font-size: 2rem;">
            <i class="fas fa-trophy"></i>
        </div>
        <h3 class="dashboard-text-primary" style="font-size: 1.5rem; font-weight: 600; color: #2c3e50; margin: 0 0 0.5rem 0;">Aucun badge disponible</h3>
        <p class="dashboard-text-secondary" style="color: #64748b; margin: 0 0 1.5rem 0;">
            Les badges n'ont pas encore été initialisés. Veuillez exécuter le seeder des badges.
        </p>
        <div style="background: rgba(234, 179, 8, 0.1); border: 1px solid rgba(234, 179, 8, 0.3); border-radius: 8px; padding: 1rem; text-align: left; max-width: 600px; margin: 0 auto;">
            <p style="margin: 0 0 0.5rem 0; font-weight: 600; color: #ca8a04;">
                <i class="fas fa-info-circle"></i> Pour les administrateurs :
            </p>
            <code style="display: block; background: rgba(0, 0, 0, 0.1); padding: 0.5rem; border-radius: 4px; font-size: 0.85rem; color: #1e293b;">
                php artisan db:seed --class=BadgeSeeder
            </code>
        </div>
    </div>
    @endif
</div>

<style>
    body.dark-mode .badge-card {
        background: rgba(15, 23, 42, 0.6) !important;
    }
    
    body.dark-mode .badge-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(4, 170, 109, 0.2) !important;
    }
</style>
@endsection


