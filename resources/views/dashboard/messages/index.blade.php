@extends('dashboard.layout')

@section('dashboard-content')
@php
    if (!app()->getLocale()) {
        app()->setLocale(session('language', 'fr'));
    }
    $pageTitle = 'Messagerie';
    $pageDescription = 'Consultez et gérez vos conversations';
@endphp

<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2 class="card-title dashboard-text-primary">
                <i class="fas fa-envelope"></i>
                {{ $pageTitle }}
            </h2>
            <p class="dashboard-text-secondary" style="margin: 0.5rem 0 0 0;">
                {{ $pageDescription }}
            </p>
        </div>
        <div style="display: flex; gap: 1rem; align-items: center;">
            @if($unreadCount > 0)
            <span class="badge-unread" style="
                background: linear-gradient(135deg, #ef4444, #dc2626);
                color: white;
                padding: 0.4rem 0.8rem;
                border-radius: 20px;
                font-size: 0.85rem;
                font-weight: 700;
            ">
                {{ $unreadCount }} non lu{{ $unreadCount > 1 ? 's' : '' }}
            </span>
            @endif
            <a href="{{ route('dashboard.messages.create') }}" class="btn-primary-sm" style="
                background: linear-gradient(135deg, #06b6d4, #14b8a6);
                color: white;
                padding: 0.6rem 1.2rem;
                border-radius: 8px;
                border: none;
                font-weight: 600;
                font-size: 0.85rem;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                text-decoration: none;
            ">
                <i class="fas fa-plus"></i> Nouveau message
            </a>
        </div>
    </div>

    @if($conversations->count() > 0)
        <div style="display: grid; gap: 1rem;">
            @foreach($conversations as $conversation)
                @php
                    $otherUser = $conversation->getOtherUser(auth()->id());
                    $lastMessage = $conversation->lastMessage;
                    $unreadMessages = \App\Models\Message::where('conversation_id', $conversation->id)
                        ->where('receiver_id', auth()->id())
                        ->where('is_read', false)
                        ->where('receiver_deleted', false)
                        ->count();
                @endphp
                <a href="{{ route('dashboard.messages.show', $conversation->id) }}" style="text-decoration: none; display: block;">
                    <div class="conversation-item" style="
                        padding: 1.25rem;
                        background: {{ $unreadMessages > 0 ? 'linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.05))' : 'rgba(6, 182, 212, 0.05)' }};
                        border: 2px solid {{ $unreadMessages > 0 ? 'rgba(6, 182, 212, 0.4)' : 'rgba(6, 182, 212, 0.2)' }};
                        border-left: 4px solid {{ $unreadMessages > 0 ? '#06b6d4' : 'rgba(6, 182, 212, 0.3)' }};
                        border-radius: 12px;
                        display: flex;
                        gap: 1rem;
                        transition: all 0.3s ease;
                        cursor: pointer;
                    " onmouseover="this.style.transform='translateX(5px)'; this.style.boxShadow='0 5px 15px rgba(6, 182, 212, 0.2)';" 
                       onmouseout="this.style.transform='translateX(0)'; this.style.boxShadow='none';">
                        <div class="user-avatar" style="
                            width: 60px;
                            height: 60px;
                            border-radius: 50%;
                            background: linear-gradient(135deg, #06b6d4, #14b8a6);
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            color: white;
                            font-size: 1.5rem;
                            font-weight: 700;
                            flex-shrink: 0;
                        ">
                            {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <h3 class="dashboard-text-primary" style="font-size: 1rem; font-weight: 700; margin: 0;">
                                    {{ $otherUser->name }}
                                </h3>
                                @if($lastMessage)
                                <span style="font-size: 0.8rem; color: rgba(100, 116, 139, 1);">
                                    {{ $lastMessage->created_at->diffForHumans() }}
                                </span>
                                @endif
                            </div>
                            @if($lastMessage)
                            <p class="dashboard-text-secondary" style="
                                font-size: 0.9rem;
                                margin: 0;
                                line-height: 1.5;
                                overflow: hidden;
                                text-overflow: ellipsis;
                                white-space: nowrap;
                                {{ $unreadMessages > 0 ? 'font-weight: 600;' : '' }}
                            ">
                                {{ $lastMessage->body }}
                            </p>
                            @else
                            <p class="dashboard-text-secondary" style="font-size: 0.9rem; margin: 0; font-style: italic;">
                                Aucun message
                            </p>
                            @endif
                        </div>
                        @if($unreadMessages > 0)
                        <div style="display: flex; align-items: center;">
                            <span class="badge-unread" style="
                                background: linear-gradient(135deg, #ef4444, #dc2626);
                                color: white;
                                padding: 0.3rem 0.6rem;
                                border-radius: 20px;
                                font-size: 0.75rem;
                                font-weight: 700;
                                min-width: 24px;
                                text-align: center;
                            ">
                                {{ $unreadMessages }}
                            </span>
                        </div>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>

        <div style="margin-top: 2rem;">
            {{ $conversations->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 3rem 1rem;">
            <div style="font-size: 4rem; color: rgba(6, 182, 212, 0.3); margin-bottom: 1rem;">
                <i class="fas fa-inbox"></i>
            </div>
            <h3 class="dashboard-text-primary" style="font-size: 1.25rem; margin-bottom: 0.5rem;">
                Aucune conversation
            </h3>
            <p class="dashboard-text-secondary" style="margin-bottom: 1.5rem;">
                Vous n'avez pas encore de messages. Commencez une nouvelle conversation !
            </p>
            <a href="{{ route('dashboard.messages.create') }}" class="btn-primary-sm" style="
                background: linear-gradient(135deg, #06b6d4, #14b8a6);
                color: white;
                padding: 0.75rem 1.5rem;
                border-radius: 8px;
                border: none;
                font-weight: 600;
                font-size: 0.9rem;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                text-decoration: none;
            ">
                <i class="fas fa-plus"></i> Nouveau message
            </a>
        </div>
    @endif
</div>

@endsection


