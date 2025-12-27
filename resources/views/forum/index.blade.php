@extends('layouts.app')

@section('title', 'Forum - NiangProgrammeur')
@section('meta_description', 'Rejoignez la communauté NiangProgrammeur et discutez de développement web, programmation et technologies.')

@push('styles')
<style>
.forum-page {
    min-height: 100vh;
    padding: 0;
    background: linear-gradient(135deg, #0a0e27 0%, #1a1f3a 50%, #0f172a 100%);
    position: relative;
    overflow-x: hidden;
}

body.dark-mode .forum-page {
    background: linear-gradient(135deg, #0a0e27 0%, #1a1f3a 50%, #0f172a 100%);
}

body.light-mode .forum-page,
body:not(.dark-mode) .forum-page {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 50%, #f1f5f9 100%);
}

/* Animated Background */
.forum-page::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: 
        radial-gradient(circle at 20% 30%, rgba(6, 182, 212, 0.15) 0%, transparent 50%),
        radial-gradient(circle at 80% 70%, rgba(20, 184, 166, 0.15) 0%, transparent 50%),
        radial-gradient(circle at 50% 50%, rgba(139, 92, 246, 0.1) 0%, transparent 50%);
    animation: bgFloat 20s ease infinite;
    pointer-events: none;
    z-index: 0;
}

@keyframes bgFloat {
    0%, 100% { transform: translate(0, 0) scale(1); opacity: 1; }
    33% { transform: translate(30px, -30px) scale(1.1); opacity: 0.9; }
    66% { transform: translate(-20px, 20px) scale(0.95); opacity: 0.95; }
}

.forum-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 4rem 1.5rem;
    position: relative;
    z-index: 1;
}

/* Hero Section */
.forum-hero {
    text-align: center;
    margin-bottom: 4rem;
    position: relative;
}

.forum-hero::after {
    content: '';
    position: absolute;
    bottom: -2rem;
    left: 50%;
    transform: translateX(-50%);
    width: 200px;
    height: 3px;
    background: linear-gradient(90deg, transparent, #06b6d4, #14b8a6, #06b6d4, transparent);
    background-size: 200% 100%;
    animation: shimmer 3s linear infinite;
    border-radius: 2px;
}

@keyframes shimmer {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

.forum-hero h1 {
    font-size: clamp(2.5rem, 5vw, 4rem);
    font-weight: 900;
    background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 50%, #8b5cf6 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 1rem;
    line-height: 1.2;
    animation: fadeInUp 0.8s ease;
}

body.light-mode .forum-hero h1 {
    background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 50%, #8b5cf6 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.forum-hero p {
    font-size: clamp(1rem, 2vw, 1.25rem);
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 2rem;
    animation: fadeInUp 0.8s ease 0.2s both;
}

body.light-mode .forum-hero p,
body:not(.dark-mode) .forum-hero p {
    color: rgba(30, 41, 59, 0.8);
}

body.dark-mode .forum-hero p {
    color: rgba(255, 255, 255, 0.8);
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Stats Cards */
.forum-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1.5rem;
    margin-bottom: 4rem;
    animation: fadeInUp 0.8s ease 0.4s both;
}

.stat-card {
    background: rgba(30, 41, 59, 0.6);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(6, 182, 212, 0.2);
    padding: 2rem 1.5rem;
    border-radius: 20px;
    text-align: center;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
}

body.light-mode .stat-card,
body:not(.dark-mode) .stat-card {
    background: rgba(255, 255, 255, 0.9);
    border-color: rgba(6, 182, 212, 0.3);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

body.dark-mode .stat-card {
    background: rgba(30, 41, 59, 0.6);
    border-color: rgba(6, 182, 212, 0.2);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(6, 182, 212, 0.1), transparent);
    transition: left 0.6s ease;
}

.stat-card:hover::before {
    left: 100%;
}

.stat-card:hover {
    transform: translateY(-8px) scale(1.02);
    border-color: rgba(6, 182, 212, 0.5);
    box-shadow: 0 16px 48px rgba(6, 182, 212, 0.3);
}

.stat-card-icon {
    width: 60px;
    height: 60px;
    margin: 0 auto 1rem;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    color: #06b6d4;
    border: 2px solid rgba(6, 182, 212, 0.3);
    transition: all 0.3s ease;
}

.stat-card:hover .stat-card-icon {
    transform: rotate(360deg) scale(1.1);
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.3), rgba(20, 184, 166, 0.3));
    border-color: #06b6d4;
}

.stat-value {
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 900;
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 0.5rem;
    line-height: 1;
}

.stat-label {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.95rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
}

body.light-mode .stat-label,
body:not(.dark-mode) .stat-label {
    color: rgba(100, 116, 139, 1);
}

body.dark-mode .stat-label {
    color: rgba(255, 255, 255, 0.7);
}

/* Create Topic Button */
.create-topic-section {
    margin-bottom: 3rem;
    animation: fadeInUp 0.8s ease 0.6s both;
}

.create-topic-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 2rem;
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    color: white;
    border-radius: 16px;
    text-decoration: none;
    font-weight: 700;
    font-size: 1.1rem;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 8px 24px rgba(6, 182, 212, 0.4);
    position: relative;
    overflow: hidden;
    border: 2px solid transparent;
}

.create-topic-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s ease;
}

.create-topic-btn:hover::before {
    left: 100%;
}

.create-topic-btn:hover {
    transform: translateY(-4px) scale(1.05);
    box-shadow: 0 16px 40px rgba(6, 182, 212, 0.6);
    border-color: rgba(255, 255, 255, 0.3);
}

.create-topic-btn i {
    font-size: 1.25rem;
    transition: transform 0.3s ease;
}

.create-topic-btn:hover i {
    transform: rotate(90deg) scale(1.2);
}

.login-prompt {
    padding: 1.5rem 2rem;
    background: rgba(6, 182, 212, 0.1);
    border: 2px solid rgba(6, 182, 212, 0.3);
    border-radius: 16px;
    text-align: center;
    backdrop-filter: blur(10px);
}

body.light-mode .login-prompt,
body:not(.dark-mode) .login-prompt {
    background: rgba(6, 182, 212, 0.05);
    border-color: rgba(6, 182, 212, 0.2);
}

body.dark-mode .login-prompt {
    background: rgba(6, 182, 212, 0.1);
    border-color: rgba(6, 182, 212, 0.3);
}

body.dark-mode .login-prompt p {
    color: rgba(255, 255, 255, 0.9);
}

.login-prompt a {
    color: #06b6d4;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.3s ease;
    border-bottom: 2px solid transparent;
}

.login-prompt a:hover {
    border-bottom-color: #06b6d4;
    transform: translateY(-2px);
}

/* Category Grid */
.category-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
    animation: fadeInUp 0.8s ease 0.8s both;
}

@media (max-width: 968px) {
    .category-grid {
        grid-template-columns: 1fr;
    }
}

.category-card {
    background: rgba(30, 41, 59, 0.6);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    padding: 2rem;
    border: 1px solid rgba(6, 182, 212, 0.2);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    cursor: pointer;
}

body.light-mode .category-card,
body:not(.dark-mode) .category-card {
    background: rgba(255, 255, 255, 0.9);
    border-color: rgba(6, 182, 212, 0.3);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

body.dark-mode .category-card {
    background: rgba(30, 41, 59, 0.6);
    border-color: rgba(6, 182, 212, 0.2);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
}

.category-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, transparent, var(--category-color, #06b6d4), transparent);
    background-size: 200% 100%;
    animation: shimmer 3s linear infinite;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.category-card:hover::before {
    opacity: 1;
}

.category-card::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: radial-gradient(circle, rgba(6, 182, 212, 0.1), transparent);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.6s ease, height 0.6s ease;
}

.category-card:hover::after {
    width: 300px;
    height: 300px;
}

.category-card:hover {
    transform: translateY(-8px) translateX(4px);
    border-color: rgba(6, 182, 212, 0.5);
    box-shadow: 0 20px 60px rgba(6, 182, 212, 0.3);
}

.category-header {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
    position: relative;
    z-index: 1;
}

.category-icon-wrapper {
    width: 70px;
    height: 70px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
    background: linear-gradient(135deg, var(--category-color, #06b6d4), var(--category-color-secondary, #14b8a6));
    box-shadow: 0 8px 24px rgba(6, 182, 212, 0.4);
    transition: all 0.4s ease;
    position: relative;
    overflow: hidden;
}

.category-icon-wrapper::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transform: rotate(45deg);
    transition: all 0.6s ease;
}

.category-card:hover .category-icon-wrapper::before {
    animation: shine 1s ease;
}

@keyframes shine {
    0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
    100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
}

.category-card:hover .category-icon-wrapper {
    transform: rotate(10deg) scale(1.1);
    box-shadow: 0 12px 32px rgba(6, 182, 212, 0.6);
}

.category-info {
    flex: 1;
}

.category-info h3 {
    font-size: 1.75rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
    color: white;
    transition: all 0.3s ease;
}

body.light-mode .category-info h3,
body:not(.dark-mode) .category-info h3 {
    color: #1e293b;
}

body.dark-mode .category-info h3 {
    color: white;
}

.category-info h3 a {
    text-decoration: none;
    color: inherit;
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    transition: all 0.3s ease;
}

.category-card:hover .category-info h3 a {
    background: linear-gradient(135deg, #14b8a6, #8b5cf6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.category-info p {
    color: rgba(255, 255, 255, 0.7);
    font-size: 1rem;
    line-height: 1.6;
}

body.light-mode .category-info p,
body:not(.dark-mode) .category-info p {
    color: rgba(100, 116, 139, 1);
}

body.dark-mode .category-info p {
    color: rgba(255, 255, 255, 0.7);
}

.category-meta {
    display: flex;
    gap: 2rem;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(6, 182, 212, 0.2);
    position: relative;
    z-index: 1;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.95rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

body.light-mode .meta-item,
body:not(.dark-mode) .meta-item {
    color: rgba(100, 116, 139, 1);
}

body.dark-mode .meta-item {
    color: rgba(255, 255, 255, 0.8);
}

.meta-item i {
    color: #06b6d4;
    font-size: 1.1rem;
    transition: transform 0.3s ease;
}

.category-card:hover .meta-item i {
    transform: scale(1.2) rotate(5deg);
    color: #14b8a6;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 6rem 2rem;
    background: rgba(30, 41, 59, 0.4);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    border: 2px dashed rgba(6, 182, 212, 0.3);
}

body.light-mode .empty-state,
body:not(.dark-mode) .empty-state {
    background: rgba(255, 255, 255, 0.6);
    border-color: rgba(6, 182, 212, 0.2);
}

body.dark-mode .empty-state {
    background: rgba(30, 41, 59, 0.4);
    border-color: rgba(6, 182, 212, 0.3);
}

.empty-state i {
    font-size: 5rem;
    color: rgba(6, 182, 212, 0.3);
    margin-bottom: 1.5rem;
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-20px); }
}

.empty-state p {
    color: rgba(255, 255, 255, 0.7);
    font-size: 1.25rem;
}

body.light-mode .empty-state p,
body:not(.dark-mode) .empty-state p {
    color: rgba(100, 116, 139, 1);
}

body.dark-mode .empty-state p {
    color: rgba(255, 255, 255, 0.7);
}

/* Responsive */
@media (max-width: 768px) {
    .forum-container {
        padding: 2rem 1rem;
    }
    
    .category-header {
        flex-direction: column;
        text-align: center;
    }
    
    .category-icon-wrapper {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
    
    .category-meta {
        flex-direction: column;
        gap: 1rem;
    }
}
</style>
@endpush

@section('content')
<div class="forum-page">
    <div class="forum-container">
        <div class="forum-hero">
            <h1>
                <i class="fas fa-comments" style="display: inline-block; margin-right: 1rem;"></i>
                Forum Communautaire
            </h1>
            <p>
                Rejoignez la communauté et partagez vos connaissances avec d'autres développeurs passionnés
            </p>
        </div>

        <div class="forum-stats">
            <div class="stat-card">
                <div class="stat-card-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <div class="stat-value">{{ number_format($stats['total_topics']) }}</div>
                <div class="stat-label">Topics</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon">
                    <i class="fas fa-reply"></i>
                </div>
                <div class="stat-value">{{ number_format($stats['total_replies']) }}</div>
                <div class="stat-label">Réponses</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-value">{{ number_format($stats['total_users']) }}</div>
                <div class="stat-label">Membres</div>
            </div>
        </div>

        <div class="create-topic-section">
            @auth
            <a href="{{ route('forum.create') }}" class="create-topic-btn">
                <i class="fas fa-plus-circle"></i>
                <span>Créer un nouveau topic</span>
            </a>
            @else
            <div class="login-prompt">
                <p style="margin: 0; color: rgba(255, 255, 255, 0.9);">
                    <i class="fas fa-lock mr-2"></i>
                    <a href="{{ route('login') }}">Connectez-vous</a> pour créer un topic et participer aux discussions
                </p>
            </div>
            @endauth
        </div>

        <div class="category-grid">
            @forelse($categories as $index => $category)
            <div class="category-card" 
                 style="--category-color: {{ $category->color ?? '#06b6d4' }}; --category-color-secondary: {{ $category->color ?? '#14b8a6' }};"
                 onclick="window.location.href='{{ route('forum.category', $category->slug) }}'">
                <div class="category-header">
                    <div class="category-icon-wrapper">
                        <i class="{{ $category->icon ?? 'fas fa-folder' }}"></i>
                    </div>
                    <div class="category-info">
                        <h3>
                            <a href="{{ route('forum.category', $category->slug) }}">
                                {{ $category->name }}
                            </a>
                        </h3>
                        @if($category->description)
                        <p>{{ $category->description }}</p>
                        @endif
                    </div>
                </div>
                <div class="category-meta">
                    <div class="meta-item">
                        <i class="fas fa-comments"></i>
                        <span>{{ $category->topics_count }} topic{{ $category->topics_count > 1 ? 's' : '' }}</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>Aucune catégorie disponible pour le moment.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
