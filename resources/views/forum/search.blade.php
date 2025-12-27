@extends('layouts.app')

@section('title', 'Recherche - Forum - NiangProgrammeur')

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

.search-header {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

body.dark-mode .search-header {
    background: rgba(15, 23, 42, 0.8);
    border: 1px solid rgba(6, 182, 212, 0.2);
}

.search-form {
    display: flex;
    gap: 1rem;
}

.search-input {
    flex: 1;
    padding: 0.75rem 1rem;
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 8px;
    font-size: 1rem;
}

body.dark-mode .search-input {
    background: rgba(30, 41, 59, 0.5);
    color: rgba(226, 232, 240, 1);
    border-color: rgba(6, 182, 212, 0.3);
}

.search-input:focus {
    outline: none;
    border-color: #06b6d4;
    box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.1);
}

.search-btn {
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    color: white;
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.search-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(6, 182, 212, 0.3);
}

.topic-card {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    margin-bottom: 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    border-left: 4px solid #06b6d4;
}

body.dark-mode .topic-card {
    background: rgba(15, 23, 42, 0.8);
    border-color: rgba(6, 182, 212, 0.3);
}

.topic-card:hover {
    transform: translateX(5px);
    box-shadow: 0 5px 15px rgba(6, 182, 212, 0.2);
}

.topic-title {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 0.75rem;
}

.topic-title a {
    text-decoration: none;
    color: inherit;
}

.topic-excerpt {
    color: rgba(100, 116, 139, 1);
    margin-bottom: 1rem;
    line-height: 1.6;
}

.topic-meta {
    display: flex;
    gap: 1.5rem;
    font-size: 0.85rem;
    color: rgba(100, 116, 139, 1);
    padding-top: 1rem;
    border-top: 1px solid rgba(6, 182, 212, 0.1);
}

.category-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: rgba(6, 182, 212, 0.1);
    color: #06b6d4;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-right: 0.5rem;
}

.no-results {
    text-align: center;
    padding: 3rem;
    background: white;
    border-radius: 12px;
}

body.dark-mode .no-results {
    background: rgba(15, 23, 42, 0.8);
    border: 1px solid rgba(6, 182, 212, 0.2);
}
</style>
@endpush

@section('content')
<div class="forum-page">
    <div class="forum-container">
        <div style="margin-bottom: 1rem;">
            <a href="{{ route('forum.index') }}" style="color: #06b6d4; text-decoration: none;">
                <i class="fas fa-arrow-left"></i> Retour au forum
            </a>
        </div>

        <div class="search-header">
            <h1 style="font-size: 2rem; font-weight: 800; margin-bottom: 1.5rem;">
                <i class="fas fa-search" style="color: #06b6d4;"></i> Recherche dans le forum
            </h1>
            <form action="{{ route('forum.search') }}" method="GET" class="search-form">
                <input type="text" name="q" class="search-input" 
                       value="{{ $query }}" placeholder="Rechercher un topic..." required>
                <button type="submit" class="search-btn">
                    <i class="fas fa-search"></i> Rechercher
                </button>
            </form>
        </div>

        @if($query)
        <div style="margin-bottom: 1.5rem; color: rgba(100, 116, 139, 1);">
            <strong>{{ $topics->total() }}</strong> résultat(s) pour "<strong>{{ $query }}</strong>"
        </div>
        @endif

        @forelse($topics as $topic)
        <div class="topic-card">
            <div class="topic-title">
                <span class="category-badge">{{ $topic->category->name }}</span>
                <a href="{{ route('forum.show', [$topic->category->slug, $topic->slug]) }}">
                    {{ $topic->title }}
                </a>
            </div>
            <div class="topic-excerpt">
                {{ \Illuminate\Support\Str::limit(strip_tags($topic->body), 200) }}
            </div>
            <div class="topic-meta">
                <span><i class="fas fa-user"></i> {{ $topic->user->name }}</span>
                <span><i class="fas fa-clock"></i> {{ $topic->created_at->diffForHumans() }}</span>
                <span><i class="fas fa-comments"></i> {{ $topic->replies_count }} réponses</span>
                <span><i class="fas fa-eye"></i> {{ $topic->views }} vues</span>
            </div>
        </div>
        @empty
        <div class="no-results">
            <i class="fas fa-search" style="font-size: 4rem; color: rgba(6, 182, 212, 0.3); margin-bottom: 1rem;"></i>
            <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">Aucun résultat trouvé</h2>
            <p style="color: rgba(100, 116, 139, 1); margin-bottom: 1.5rem;">
                @if($query)
                Aucun topic ne correspond à votre recherche "{{ $query }}".
                @else
                Entrez un terme de recherche pour commencer.
                @endif
            </p>
            <a href="{{ route('forum.index') }}" style="color: #06b6d4; text-decoration: none; font-weight: 600;">
                <i class="fas fa-arrow-left"></i> Retour au forum
            </a>
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

