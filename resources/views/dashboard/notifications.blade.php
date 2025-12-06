@extends('dashboard.layout')

@section('dashboard-content')
@php
    if (!app()->getLocale()) {
        app()->setLocale(session('language', 'fr'));
    }
    $pageTitle = trans('app.profile.dashboard.notifications.title') ?? 'Mes Notifications';
    $pageDescription = trans('app.profile.dashboard.notifications.description') ?? 'Consultez toutes vos notifications';
@endphp

<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2 class="card-title dashboard-text-primary">
                <i class="fas fa-bell"></i>
                {{ $pageTitle }}
            </h2>
            <p class="dashboard-text-secondary" style="margin: 0.5rem 0 0 0;">
                {{ $pageDescription }}
            </p>
        </div>
        @if($notifications->where('is_read', false)->count() > 0)
        <button onclick="markAllAsRead()" 
                class="btn-primary-sm" 
                style="background: linear-gradient(135deg, #06b6d4, #14b8a6); color: white; padding: 0.6rem 1.2rem; border-radius: 8px; border: none; font-weight: 600; font-size: 0.85rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-check-double"></i> Tout marquer comme lu
        </button>
        @endif
    </div>

    @if($notifications->count() > 0)
        <div style="display: grid; gap: 1rem;">
            @foreach($notifications as $notification)
                <div class="notification-item-dashboard {{ $notification->is_read ? 'read' : 'unread' }}" 
                     style="
                        padding: 1.25rem;
                        background: {{ $notification->is_read ? 'rgba(6, 182, 212, 0.05)' : 'linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.05))' }};
                        border: 2px solid {{ $notification->is_read ? 'rgba(6, 182, 212, 0.2)' : 'rgba(6, 182, 212, 0.4)' }};
                        border-left: 4px solid {{ $notification->is_read ? 'rgba(6, 182, 212, 0.3)' : '#06b6d4' }};
                        border-radius: 12px;
                        display: flex;
                        gap: 1rem;
                        transition: all 0.3s ease;
                        cursor: {{ $notification->link ? 'pointer' : 'default' }};
                    "
                    @if($notification->link)
                    onclick="window.location.href='{{ $notification->link }}'; markAsRead({{ $notification->id }});"
                    @endif
                >
                    <div class="notification-icon-dashboard" style="
                        width: 50px;
                        height: 50px;
                        border-radius: 50%;
                        background: rgba(6, 182, 212, 0.15);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: #06b6d4;
                        font-size: 1.3rem;
                        flex-shrink: 0;
                    ">
                        <i class="fas {{ $notification->type === 'comment' ? 'fa-comment' : ($notification->type === 'reply' ? 'fa-reply' : ($notification->type === 'favorite' ? 'fa-heart' : 'fa-info-circle')) }}"></i>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <h3 class="dashboard-text-primary" style="font-size: 1rem; font-weight: 700; margin: 0 0 0.5rem 0;">
                            {{ $notification->title }}
                        </h3>
                        <p class="dashboard-text-secondary" style="font-size: 0.9rem; margin: 0 0 0.5rem 0; line-height: 1.5;">
                            {{ $notification->message }}
                        </p>
                        <div style="display: flex; align-items: center; gap: 1rem; font-size: 0.8rem; color: rgba(100, 116, 139, 1);">
                            <span>
                                <i class="fas fa-clock"></i> 
                                {{ $notification->created_at->diffForHumans() }}
                            </span>
                            @if(!$notification->is_read)
                            <span style="color: #06b6d4; font-weight: 600;">
                                <i class="fas fa-circle" style="font-size: 0.5rem;"></i> Non lu
                            </span>
                            @endif
                        </div>
                    </div>
                    @if(!$notification->is_read)
                    <button onclick="event.stopPropagation(); markAsRead({{ $notification->id }})" 
                            style="
                                background: rgba(6, 182, 212, 0.1);
                                border: 2px solid rgba(6, 182, 212, 0.3);
                                color: #06b6d4;
                                padding: 0.5rem;
                                border-radius: 8px;
                                cursor: pointer;
                                flex-shrink: 0;
                                width: 40px;
                                height: 40px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                            "
                            title="Marquer comme lu">
                        <i class="fas fa-check"></i>
                    </button>
                    @endif
                </div>
            @endforeach
        </div>

        <div style="margin-top: 40px;">
            {{ $notifications->links() }}
        </div>
    @else
        <div class="content-card" style="text-align: center; padding: 3rem 2rem;">
            <div class="dashboard-empty-icon" style="width: 80px; height: 80px; margin: 0 auto 1.25rem; border-radius: 50%; background: rgba(6, 182, 212, 0.2); display: flex; align-items: center; justify-content: center; color: #06b6d4; font-size: 2rem;">
                <i class="fas fa-bell-slash"></i>
            </div>
            <h3 class="dashboard-text-primary" style="font-size: 1.5rem; font-weight: 600; margin: 0 0 0.5rem 0;">Aucune notification</h3>
            <p class="dashboard-text-secondary" style="color: #64748b; margin: 0;">Vous n'avez pas encore de notifications</p>
        </div>
    @endif
</div>

<script>
async function markAsRead(id) {
    try {
        const response = await fetch(`/api/notifications/${id}/read`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        });

        if (response.ok) {
            const item = document.querySelector(`[onclick*="markAsRead(${id})"]`)?.closest('.notification-item-dashboard');
            if (item) {
                item.classList.remove('unread');
                item.classList.add('read');
                item.style.background = 'rgba(6, 182, 212, 0.05)';
                item.style.borderColor = 'rgba(6, 182, 212, 0.2)';
                item.style.borderLeftColor = 'rgba(6, 182, 212, 0.3)';
                
                const button = item.querySelector('button');
                if (button) button.remove();
            }
        }
    } catch (error) {
        console.error('Erreur:', error);
    }
}

async function markAllAsRead() {
    if (!confirm('Marquer toutes les notifications comme lues ?')) {
        return;
    }

    try {
        const response = await fetch('/api/notifications/read-all', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        });

        if (response.ok) {
            if (window.feedbackManager) {
                window.feedbackManager.showSuccess('Toutes les notifications ont été marquées comme lues');
            }
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        }
    } catch (error) {
        console.error('Erreur:', error);
        if (window.feedbackManager) {
            window.feedbackManager.showError('Une erreur est survenue');
        }
    }
}
</script>

<style>
body.dark-mode .notification-item-dashboard {
    background: rgba(15, 23, 42, 0.6) !important;
}

body.dark-mode .notification-item-dashboard.unread {
    background: rgba(15, 23, 42, 0.8) !important;
    border-color: rgba(6, 182, 212, 0.5) !important;
}

.notification-item-dashboard:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 15px rgba(6, 182, 212, 0.2);
}
</style>
@endsection


