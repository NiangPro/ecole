@extends('layouts.app')

@section('title', $topic->title . ' - Forum - NiangProgrammeur')

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

.topic-header-card {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

body.dark-mode .topic-header-card {
    background: rgba(15, 23, 42, 0.8);
    border: 1px solid rgba(6, 182, 212, 0.2);
}

.topic-title-large {
    font-size: 2rem;
    font-weight: 800;
    margin-bottom: 1rem;
    line-height: 1.3;
}

.topic-meta-info {
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
    padding-top: 1rem;
    border-top: 1px solid rgba(6, 182, 212, 0.1);
    font-size: 0.9rem;
    color: rgba(100, 116, 139, 1);
}

.topic-body {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

body.dark-mode .topic-body {
    background: rgba(15, 23, 42, 0.8);
    border: 1px solid rgba(6, 182, 212, 0.2);
}

.reply-card {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-left: 4px solid transparent;
    transition: all 0.3s ease;
}

body.dark-mode .reply-card {
    background: rgba(15, 23, 42, 0.8);
    border-color: rgba(6, 182, 212, 0.3);
}

.reply-card.best-answer {
    border-left-color: #10b981;
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05));
}

.reply-card:hover {
    transform: translateX(5px);
    box-shadow: 0 5px 15px rgba(6, 182, 212, 0.2);
}

.reply-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid rgba(6, 182, 212, 0.1);
}

.reply-author {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.author-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1.25rem;
}

.reply-actions {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.vote-buttons {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(6, 182, 212, 0.1);
    padding: 0.5rem;
    border-radius: 8px;
}

.vote-btn {
    background: none;
    border: none;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 4px;
    transition: all 0.2s ease;
    color: rgba(100, 116, 139, 1);
}

.vote-btn:hover {
    background: rgba(6, 182, 212, 0.2);
    color: #06b6d4;
}

.vote-btn.active {
    color: #06b6d4;
    background: rgba(6, 182, 212, 0.3);
}

.vote-count {
    font-weight: 700;
    color: #06b6d4;
    min-width: 30px;
    text-align: center;
}

.reply-form-card {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-top: 2rem;
}

body.dark-mode .reply-form-card {
    background: rgba(15, 23, 42, 0.8);
    border: 1px solid rgba(6, 182, 212, 0.2);
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: rgba(30, 41, 59, 1);
}

body.dark-mode .form-label {
    color: rgba(226, 232, 240, 1);
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: #06b6d4;
    box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.1);
}

textarea.form-control {
    min-height: 150px;
    resize: vertical;
}

.btn-primary {
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    color: white;
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(6, 182, 212, 0.3);
}

.best-answer-badge {
    background: #10b981;
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-left: 0.5rem;
}

.locked-notice {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.3);
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
    color: #ef4444;
    text-align: center;
}
</style>
@endpush

@section('content')
<div class="forum-page">
    <div class="forum-container">
        <div style="margin-bottom: 1rem;">
            <a href="{{ route('forum.category', $category->slug) }}" style="color: #06b6d4; text-decoration: none;">
                <i class="fas fa-arrow-left"></i> Retour à {{ $category->name }}
            </a>
        </div>

        <div class="topic-header-card">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                <h1 class="topic-title-large">{{ $topic->title }}</h1>
                <div>
                    @if($topic->is_pinned)
                    <span style="background: #fbbf24; color: white; padding: 0.25rem 0.75rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600; margin-right: 0.5rem;">
                        <i class="fas fa-thumbtack"></i> Épinglé
                    </span>
                    @endif
                    @if($topic->is_locked)
                    <span style="background: #ef4444; color: white; padding: 0.25rem 0.75rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600;">
                        <i class="fas fa-lock"></i> Verrouillé
                    </span>
                    @endif
                </div>
            </div>
            <div class="topic-meta-info">
                <span><i class="fas fa-user"></i> {{ $topic->user->name }}</span>
                <span><i class="fas fa-clock"></i> {{ $topic->created_at->diffForHumans() }}</span>
                <span><i class="fas fa-eye"></i> {{ $topic->views }} vues</span>
                <span><i class="fas fa-comments"></i> {{ $topic->replies_count }} réponses</span>
            </div>
        </div>

        <div class="topic-body">
            <div class="reply-header">
                <div class="reply-author">
                    <div class="author-avatar">{{ substr($topic->user->name, 0, 1) }}</div>
                    <div>
                        <div style="font-weight: 700;">{{ $topic->user->name }}</div>
                        <div style="font-size: 0.85rem; color: rgba(100, 116, 139, 1);">{{ $topic->created_at->format('d/m/Y à H:i') }}</div>
                    </div>
                </div>
            </div>
            <div style="line-height: 1.7; color: rgba(30, 41, 59, 1);">
                {!! nl2br(e($topic->body)) !!}
            </div>
        </div>

        @if($topic->is_locked)
        <div class="locked-notice">
            <i class="fas fa-lock"></i> Ce topic est verrouillé. Les nouvelles réponses ne sont plus autorisées.
        </div>
        @endif

        <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem;">
            Réponses ({{ $replies->total() }})
        </h2>

        @forelse($replies as $reply)
        <div class="reply-card {{ $reply->is_best_answer ? 'best-answer' : '' }}" id="reply-{{ $reply->id }}">
            <div class="reply-header">
                <div class="reply-author">
                    <div class="author-avatar">{{ substr($reply->user->name, 0, 1) }}</div>
                    <div>
                        <div style="font-weight: 700;">
                            {{ $reply->user->name }}
                            @if($reply->is_best_answer)
                            <span class="best-answer-badge">
                                <i class="fas fa-check-circle"></i> Meilleure réponse
                            </span>
                            @endif
                        </div>
                        <div style="font-size: 0.85rem; color: rgba(100, 116, 139, 1);">{{ $reply->created_at->format('d/m/Y à H:i') }}</div>
                    </div>
                </div>
                <div class="reply-actions">
                    @auth
                    @if($topic->user_id == Auth::id() && !$reply->is_best_answer && !$topic->is_locked)
                    <form action="{{ route('forum.mark-best-answer', $reply->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" style="background: #10b981; color: white; border: none; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; font-size: 0.85rem;">
                            <i class="fas fa-check-circle"></i> Meilleure réponse
                        </button>
                    </form>
                    @endif
                    @if($reply->user_id == Auth::id() || Auth::user()->isAdmin())
                    <form action="{{ route('forum.delete-reply', $reply->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette réponse ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background: #ef4444; color: white; border: none; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; font-size: 0.85rem;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    @endif
                    @endauth
                </div>
            </div>
            <div style="line-height: 1.7; color: rgba(30, 41, 59, 1); margin-bottom: 1rem;">
                {!! nl2br(e($reply->body)) !!}
            </div>
            @auth
            <div class="vote-buttons">
                <button class="vote-btn {{ isset($reply->user_vote) && $reply->user_vote->type == 'upvote' ? 'active' : '' }}" 
                        onclick="voteReply({{ $reply->id }}, 'upvote')">
                    <i class="fas fa-arrow-up"></i>
                </button>
                <span class="vote-count">{{ $reply->votes_count }}</span>
                <button class="vote-btn {{ isset($reply->user_vote) && $reply->user_vote->type == 'downvote' ? 'active' : '' }}" 
                        onclick="voteReply({{ $reply->id }}, 'downvote')">
                    <i class="fas fa-arrow-down"></i>
                </button>
            </div>
            @endauth
        </div>
        @empty
        <div style="text-align: center; padding: 3rem; background: white; border-radius: 12px;">
            <i class="fas fa-comments" style="font-size: 4rem; color: rgba(6, 182, 212, 0.3); margin-bottom: 1rem;"></i>
            <p style="color: rgba(100, 116, 139, 1);">Aucune réponse pour le moment. Soyez le premier à répondre !</p>
        </div>
        @endforelse

        @if($replies->hasPages())
        <div style="margin-top: 2rem;">
            {{ $replies->links() }}
        </div>
        @endif

        @auth
        @if(!$topic->is_locked)
        <div class="reply-form-card">
            <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1.5rem;">Ajouter une réponse</h3>
            <form action="{{ route('forum.reply', [$category->slug, $topic->slug]) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Votre réponse</label>
                    <textarea name="body" class="form-control" rows="6" required minlength="5" placeholder="Écrivez votre réponse ici..."></textarea>
                    @error('body')
                    <p style="color: #ef4444; font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-paper-plane"></i> Publier la réponse
                </button>
            </form>
        </div>
        @endif
        @else
        <div style="text-align: center; padding: 2rem; background: white; border-radius: 12px; margin-top: 2rem;">
            <p style="color: rgba(100, 116, 139, 1); margin-bottom: 1rem;">
                <a href="{{ route('login') }}" style="color: #06b6d4; font-weight: 600; text-decoration: none;">Connectez-vous</a> pour répondre à ce topic
            </p>
        </div>
        @endauth
    </div>
</div>

@push('scripts')
<script>
function voteReply(replyId, type) {
    fetch(`/forum/reply/${replyId}/vote`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ type: type })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Recharger la page pour mettre à jour les votes
            location.reload();
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue lors du vote.');
    });
}
</script>
@endpush
@endsection

