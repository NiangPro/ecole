@extends('layouts.app')

@section('title', $category->name . ' - Forum - NiangProgrammeur')

@push('styles')
<style>
.forum-page {
    min-height: 100vh;
    padding: 2rem 1rem;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
}

body.dark-mode .forum-page {
    background: linear-gradient(135deg, #0a0a0f 0%, #1a1a2e 100%);
}

.forum-container {
    max-width: 1200px;
    margin: 0 auto;
}

.category-header-section {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

body.dark-mode .category-header-section {
    background: rgba(15, 23, 42, 0.8);
    border: 1px solid rgba(6, 182, 212, 0.2);
}

.topic-card {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    margin-bottom: 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    border-left: 4px solid;
}

body.dark-mode .topic-card {
    background: rgba(15, 23, 42, 0.8);
    border-color: rgba(6, 182, 212, 0.3);
}

.topic-card.pinned {
    border-left-color: #fbbf24;
    background: linear-gradient(135deg, rgba(251, 191, 36, 0.1), rgba(251, 191, 36, 0.05));
}

.topic-card:hover {
    transform: translateX(5px);
    box-shadow: 0 5px 15px rgba(6, 182, 212, 0.2);
}

.topic-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 1rem;
}

.topic-title {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.topic-title a {
    text-decoration: none;
    color: inherit;
}

.topic-meta {
    display: flex;
    gap: 1.5rem;
    font-size: 0.85rem;
    color: rgba(100, 116, 139, 1);
}

.create-topic-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    color: white;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}
</style>
@endpush

@section('content')
<div class="forum-page">
    <div class="forum-container">
        <div class="category-header-section">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <div>
                    <h1 style="font-size: 2rem; font-weight: 800; margin-bottom: 0.5rem;">
                        <i class="{{ $category->icon ?? 'fas fa-folder' }}" style="color: {{ $category->color ?? '#06b6d4' }};"></i>
                        {{ $category->name }}
                    </h1>
                    @if($category->description)
                    <p style="color: rgba(100, 116, 139, 1);">{{ $category->description }}</p>
                    @endif
                </div>
                @auth
                <a href="{{ route('forum.create', ['category_id' => $category->id]) }}" class="create-topic-btn">
                    <i class="fas fa-plus"></i> Nouveau topic
                </a>
                @endauth
            </div>
            <a href="{{ route('forum.index') }}" style="color: #06b6d4; text-decoration: none;">
                <i class="fas fa-arrow-left"></i> Retour au forum
            </a>
        </div>

        @forelse($topics as $topic)
        <div class="topic-card {{ $topic->is_pinned ? 'pinned' : '' }}">
            <div class="topic-header">
                <div style="flex: 1;">
                    @if($topic->is_pinned)
                    <span style="background: #fbbf24; color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600; margin-right: 0.5rem;">
                        <i class="fas fa-thumbtack"></i> Épinglé
                    </span>
                    @endif
                    @if($topic->is_locked)
                    <span style="background: #ef4444; color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600; margin-right: 0.5rem;">
                        <i class="fas fa-lock"></i> Verrouillé
                    </span>
                    @endif
                    <h3 class="topic-title">
                        <a href="{{ route('forum.show', [$category->slug, $topic->slug]) }}">
                            {{ $topic->title }}
                        </a>
                    </h3>
                    <div class="topic-meta">
                        <span><i class="fas fa-user"></i> {{ $topic->user->name }}</span>
                        <span><i class="fas fa-clock"></i> {{ $topic->created_at->diffForHumans() }}</span>
                        <span><i class="fas fa-comments"></i> {{ $topic->replies_count }} réponses</span>
                        <span><i class="fas fa-eye"></i> {{ $topic->views }} vues</span>
                    </div>
                </div>
            </div>
            @if($topic->last_reply_user_id)
            <div style="padding-top: 1rem; border-top: 1px solid rgba(6, 182, 212, 0.1); font-size: 0.85rem; color: rgba(100, 116, 139, 1);">
                Dernière réponse par <strong>{{ $topic->lastReplyUser->name ?? 'Utilisateur' }}</strong> {{ $topic->last_reply_at ? $topic->last_reply_at->diffForHumans() : '' }}
            </div>
            @endif
        </div>
        @empty
        <div style="text-align: center; padding: 3rem; background: white; border-radius: 12px;">
            <i class="fas fa-inbox" style="font-size: 4rem; color: rgba(6, 182, 212, 0.3); margin-bottom: 1rem;"></i>
            <p style="color: rgba(100, 116, 139, 1); margin-bottom: 1rem;">Aucun topic dans cette catégorie pour le moment.</p>
            @auth
            <a href="{{ route('forum.create', ['category_id' => $category->id]) }}" class="create-topic-btn">
                <i class="fas fa-plus"></i> Créer le premier topic
            </a>
            @endauth
        </div>
        @endforelse

        @if($topics->hasPages())
        <div style="margin-top: 2rem;">
            {{ $topics->links() }}
        </div>
        @endif
    </div>
</div>
@endsection


