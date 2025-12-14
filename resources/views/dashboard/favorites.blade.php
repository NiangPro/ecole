@extends('dashboard.layout')

@section('dashboard-content')
@php
    if (!app()->getLocale()) {
        app()->setLocale(session('language', 'fr'));
    }
    $pageTitle = trans('app.profile.dashboard.favorites.title') ?? 'Mes Favoris';
    $pageDescription = trans('app.profile.dashboard.favorites.description') ?? 'Retrouvez vos formations et articles favoris';
@endphp

<div class="content-card">
    <h2 class="card-title dashboard-text-primary">
        <i class="fas fa-heart"></i>
        {{ $pageTitle }}
    </h2>
    <p class="dashboard-text-secondary" style="margin-bottom: 2rem;">
        {{ $pageDescription }}
    </p>

    @if($favorites->count() > 0)
        <div style="display: grid; gap: 1.5rem;">
            @foreach($favorites as $favorite)
                <div class="favorite-item" style="
                    padding: 1.5rem;
                    background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.05));
                    border: 2px solid rgba(6, 182, 212, 0.3);
                    border-radius: 12px;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 1rem;
                    transition: all 0.3s ease;
                ">
                    <div style="flex: 1; min-width: 0;">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                            <i class="fas {{ $favorite->favoritable_type === 'formation' ? 'fa-graduation-cap' : 'fa-newspaper' }}" 
                               style="color: #06b6d4; font-size: 1.5rem;"></i>
                            <h3 class="dashboard-text-primary" style="font-size: 1.1rem; font-weight: 600; margin: 0;">
                                {{ $favorite->favoritable_name }}
                            </h3>
                        </div>
                        <div class="dashboard-text-secondary" style="font-size: 0.9rem; display: flex; gap: 1rem; flex-wrap: wrap;">
                            <span>
                                <i class="fas fa-tag"></i> 
                                {{ $favorite->favoritable_type === 'formation' ? trans('app.profile.dashboard.favorites.formation') : trans('app.profile.dashboard.favorites.article') }}
                            </span>
                            <span>
                                <i class="fas fa-calendar"></i> 
                                {{ trans('app.profile.dashboard.favorites.added_on') }} {{ $favorite->created_at->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                    <div style="display: flex; gap: 0.75rem; align-items: center;">
                        <a href="{{ $favorite->favoritable_type === 'formation' ? route('formations.' . $favorite->favoritable_slug) : route('emplois.article', $favorite->favoritable_slug) }}" 
                           class="btn-primary-sm" 
                           style="background: linear-gradient(135deg, #06b6d4, #14b8a6); color: white; padding: 0.6rem 1.2rem; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-eye"></i> {{ trans('app.profile.dashboard.favorites.view') }}
                        </a>
                        <button onclick="removeFavorite('{{ $favorite->favoritable_type }}', '{{ $favorite->favoritable_slug }}')" 
                                class="btn-secondary-sm" 
                                style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 0.6rem 1.2rem; border-radius: 8px; border: 2px solid rgba(239, 68, 68, 0.3); font-weight: 600; font-size: 0.85rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-trash"></i> {{ trans('app.profile.dashboard.favorites.remove') }}
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top: 40px;">
            {{ $favorites->links() }}
        </div>
    @else
        <div class="content-card" style="text-align: center; padding: 3rem 2rem;">
            <div class="dashboard-empty-icon" style="width: 80px; height: 80px; margin: 0 auto 1.25rem; border-radius: 50%; background: rgba(6, 182, 212, 0.2); display: flex; align-items: center; justify-content: center; color: #06b6d4; font-size: 2rem;">
                <i class="fas fa-heart"></i>
            </div>
            <h3 class="dashboard-text-primary" style="font-size: 1.5rem; font-weight: 600; margin: 0 0 0.5rem 0;">{{ trans('app.profile.dashboard.favorites.no_favorites') }}</h3>
            <p class="dashboard-text-secondary" style="color: #64748b; margin: 0 0 1.5rem 0;">{{ trans('app.profile.dashboard.favorites.no_favorites_text') }}</p>
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('formations.all') }}" class="btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: white; border: none; border-radius: 6px; text-decoration: none; font-weight: 500; cursor: pointer; box-shadow: 0 4px 6px rgba(6, 182, 212, 0.3);">
                    <i class="fas fa-graduation-cap"></i>
                    {{ trans('app.profile.dashboard.favorites.view_formations') }}
                </a>
                <a href="{{ route('emplois.offres') }}" class="btn-secondary" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: rgba(100, 116, 139, 0.2); color: rgba(209, 213, 219, 1); border: 2px solid rgba(100, 116, 139, 0.3); border-radius: 6px; text-decoration: none; font-weight: 500; cursor: pointer;">
                    <i class="fas fa-newspaper"></i>
                    {{ trans('app.profile.dashboard.favorites.view_articles') }}
                </a>
            </div>
        </div>
    @endif
</div>

<script>
async function removeFavorite(type, slug) {
    if (!confirm('{{ trans('app.profile.dashboard.favorites.remove_confirm') }}')) {
        return;
    }

    try {
        const response = await fetch('/api/favorites/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({ type, slug, name: '' })
        });

        const data = await response.json();

        if (data.success && !data.is_favorite) {
            if (window.feedbackManager) {
                window.feedbackManager.showSuccess('{{ trans('app.profile.dashboard.favorites.removed_success') }}');
            }
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            if (window.feedbackManager) {
                window.feedbackManager.showError('{{ trans('app.profile.dashboard.favorites.remove_error') }}');
            }
        }
    } catch (error) {
        console.error('Erreur:', error);
        if (window.feedbackManager) {
            window.feedbackManager.showError('{{ trans('app.profile.dashboard.favorites.remove_error') }}');
        }
    }
}
</script>

<style>
body.dark-mode .favorite-item {
    background: rgba(15, 23, 42, 0.6) !important;
    border-color: rgba(6, 182, 212, 0.4) !important;
}

.favorite-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(6, 182, 212, 0.2) !important;
}
</style>
@endsection


