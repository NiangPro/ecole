@extends('admin.layout')

@section('title', 'Gestion des Commentaires | Admin')

@section('content')
<div class="comments-admin">
    <!-- Header Section -->
    <div class="comments-header">
        <div class="comments-header-content">
            <div class="comments-header-text">
                <h1 class="comments-title">
                    <span class="comments-icon-wrapper">
                        <i class="fas fa-comments comments-icon"></i>
                    </span>
                    Gestion des Commentaires
                </h1>
                <p class="comments-subtitle">
                    Approuvez, rejetez ou supprimez les commentaires de votre plateforme
                </p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="comments-stats">
        <div class="stat-card stat-total">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-comments"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($stats['total']) }}</div>
                <div class="stat-label">Total</div>
            </div>
        </div>
        <div class="stat-card stat-pending">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($stats['pending']) }}</div>
                <div class="stat-label">En attente</div>
            </div>
        </div>
        <div class="stat-card stat-approved">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($stats['approved']) }}</div>
                <div class="stat-label">Approuvés</div>
            </div>
        </div>
        <div class="stat-card stat-rejected">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($stats['rejected']) }}</div>
                <div class="stat-label">Rejetés</div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filters-section">
        <div class="filters-header">
            <i class="fas fa-filter"></i>
            <span>Filtres de recherche</span>
        </div>
        <form method="GET" action="{{ route('admin.comments.index') }}" class="filters-form">
            <div class="filters-grid">
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-search"></i>
                        Recherche
                    </label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Nom, email, téléphone, contenu..." class="filter-input">
                </div>
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-tag"></i>
                        Statut
                    </label>
                    <select name="status" class="filter-select">
                        <option value="">Tous les statuts</option>
                        <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approuvés</option>
                        <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Rejetés</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-sort"></i>
                        Trier par
                    </label>
                    <select name="sort" class="filter-select">
                        <option value="created_at" {{ $sortBy == 'created_at' ? 'selected' : '' }}>Date</option>
                        <option value="name" {{ $sortBy == 'name' ? 'selected' : '' }}>Nom</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-sort-amount-down"></i>
                        Ordre
                    </label>
                    <select name="order" class="filter-select">
                        <option value="desc" {{ $sortOrder == 'desc' ? 'selected' : '' }}>Décroissant</option>
                        <option value="asc" {{ $sortOrder == 'asc' ? 'selected' : '' }}>Croissant</option>
                    </select>
                </div>
                <div class="filter-actions">
                    <button type="submit" class="filter-btn filter-btn-primary">
                        <i class="fas fa-search"></i>
                        <span>Filtrer</span>
                    </button>
                    @if($search || $status)
                    <a href="{{ route('admin.comments.index') }}" class="filter-btn filter-btn-secondary">
                        <i class="fas fa-redo"></i>
                        <span>Réinitialiser</span>
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Comments Table -->
    @if($comments->count() > 0)
    <div class="comments-table-wrapper">
        <table class="comments-table">
            <thead>
                <tr>
                    <th class="table-col-avatar">Auteur</th>
                    <th class="table-col-content">Contenu</th>
                    <th class="table-col-contact">Contact</th>
                    <th class="table-col-article">Article</th>
                    <th class="table-col-date">Date</th>
                    <th class="table-col-status">Statut</th>
                    <th class="table-col-actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($comments as $comment)
                <tr class="table-row table-row-{{ $comment->status }}">
                    <td class="table-cell table-cell-author">
                        <div class="cell-author-wrapper">
                            <div class="cell-avatar cell-avatar-{{ $comment->status }}">
                                {{ strtoupper(substr($comment->author_name, 0, 1)) }}
                            </div>
                            <div class="cell-author-info">
                                <div class="cell-author-name">{{ $comment->author_name }}</div>
                                @if($comment->parent)
                                <div class="cell-reply-info">
                                    <i class="fas fa-reply"></i>
                                    {{ $comment->parent->author_name }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="table-cell table-cell-content">
                        <div class="cell-content-text">{{ Str::limit($comment->content, 100) }}</div>
                    </td>
                    <td class="table-cell table-cell-contact">
                        <div class="cell-contact-info">
                            @if($comment->phone)
                            <div class="cell-contact-item">
                                <i class="fas fa-phone"></i>
                                <a href="tel:{{ $comment->phone }}" class="cell-link">{{ $comment->phone }}</a>
                            </div>
                            @endif
                            <div class="cell-contact-item">
                                <i class="fas fa-envelope"></i>
                                <a href="mailto:{{ $comment->author_email }}" class="cell-link">{{ Str::limit($comment->author_email, 20) }}</a>
                            </div>
                        </div>
                    </td>
                    <td class="table-cell table-cell-article">
                        <div class="cell-article-text">
                            @if($comment->commentable)
                                <i class="fas fa-file-alt"></i>
                                {{ Str::limit($comment->commentable->title ?? 'N/A', 30) }}
                            @else
                                <span class="cell-no-data">N/A</span>
                            @endif
                        </div>
                    </td>
                    <td class="table-cell table-cell-date">
                        <div class="cell-date-text">
                            <i class="fas fa-calendar"></i>
                            {{ $comment->created_at->format('d/m/Y') }}
                            <br>
                            <span class="cell-time">{{ $comment->created_at->format('H:i') }}</span>
                        </div>
                    </td>
                    <td class="table-cell table-cell-status">
                        <span class="cell-status-badge cell-status-{{ $comment->status }}">
                            @if($comment->status === 'pending')
                                <i class="fas fa-clock"></i>
                                <span>En attente</span>
                            @elseif($comment->status === 'approved')
                                <i class="fas fa-check-circle"></i>
                                <span>Approuvé</span>
                            @else
                                <i class="fas fa-times-circle"></i>
                                <span>Rejeté</span>
                            @endif
                        </span>
                    </td>
                    <td class="table-cell table-cell-actions">
                        <div class="cell-actions-wrapper">
                            <button onclick="showCommentDetails({{ $comment->id }})" class="cell-action-btn cell-action-view" title="Détails">
                                <i class="fas fa-eye"></i>
                            </button>
                            
                            @if($comment->status === 'pending')
                            <form action="{{ route('admin.comments.approve', $comment->id) }}" method="POST" class="cell-action-form">
                                @csrf
                                <button type="submit" class="cell-action-btn cell-action-approve" title="Approuver">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.comments.reject', $comment->id) }}" method="POST" class="cell-action-form">
                                @csrf
                                <button type="submit" class="cell-action-btn cell-action-reject" title="Rejeter">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                            @elseif($comment->status === 'rejected')
                            <form action="{{ route('admin.comments.approve', $comment->id) }}" method="POST" class="cell-action-form">
                                @csrf
                                <button type="submit" class="cell-action-btn cell-action-approve" title="Approuver">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            @else
                            <form action="{{ route('admin.comments.reject', $comment->id) }}" method="POST" class="cell-action-form">
                                @csrf
                                <button type="submit" class="cell-action-btn cell-action-reject" title="Rejeter">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                            @endif
                            
                            @if($comment->phone)
                            <button onclick="openWhatsApp({{ $comment->id }})" class="cell-action-btn cell-action-contact" title="WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </button>
                            @endif
                            
                            <button onclick="openEmail({{ $comment->id }})" class="cell-action-btn cell-action-contact" title="Email">
                                <i class="fas fa-envelope"></i>
                            </button>
                            
                            @if($comment->status !== 'approved')
                            @auth
                            @if(Auth::user()->isAdmin())
                            <form action="{{ route('admin.comments.delete', $comment->id) }}" method="POST" class="cell-action-form" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="cell-action-btn cell-action-delete" title="Supprimer">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                            @endif
                            @endauth
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination-wrapper">
        {{ $comments->links() }}
    </div>
    @else
    <!-- Empty State -->
    <div class="empty-state">
        <div class="empty-state-icon">
            <i class="fas fa-comments"></i>
        </div>
        <h3 class="empty-state-title">Aucun commentaire trouvé</h3>
        <p class="empty-state-text">
            @if($search || $status)
                Aucun commentaire ne correspond à vos critères de recherche.
            @else
                Aucun commentaire n'a été créé pour le moment.
            @endif
        </p>
        @if($search || $status)
        <a href="{{ route('admin.comments.index') }}" class="empty-state-btn">
            <i class="fas fa-redo"></i>
            <span>Réinitialiser les filtres</span>
        </a>
        @endif
    </div>
    @endif
</div>

<!-- Modal pour les détails du commentaire -->
<div id="commentModal" class="comment-modal">
    <div class="comment-modal-content">
        <div class="comment-modal-header">
            <div class="comment-modal-icon">
                <i class="fas fa-comment-dots"></i>
            </div>
            <h3 class="comment-modal-title">Détails du commentaire</h3>
            <button onclick="closeCommentModal()" class="comment-modal-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="comment-modal-body" id="commentDetails"></div>
    </div>
</div>

<style>
.comments-admin {
    padding: 2rem;
    max-width: 1600px;
    margin: 0 auto;
}

/* Header */
.comments-header {
    margin-bottom: 2rem;
}

.comments-header-content {
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(20, 184, 166, 0.1) 100%);
    border: 2px solid rgba(6, 182, 212, 0.3);
    border-radius: 24px;
    padding: 2.5rem;
    position: relative;
    overflow: hidden;
}

body.light-mode .comments-header-content {
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.08) 0%, rgba(20, 184, 166, 0.08) 100%);
    border-color: rgba(6, 182, 212, 0.4);
}

.comments-header-content::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(6, 182, 212, 0.1) 0%, transparent 70%);
    animation: rotate 20s linear infinite;
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.comments-header-text {
    position: relative;
    z-index: 1;
}

.comments-title {
    font-family: 'Poppins', sans-serif;
    font-size: 2.5rem;
    font-weight: 900;
    background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 50%, #06b6d4 100%);
    background-size: 200% auto;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: shimmer 3s linear infinite;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

@keyframes shimmer {
    to { background-position: 200% center; }
}

.comments-icon-wrapper {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
    border-radius: 16px;
    border: 2px solid rgba(6, 182, 212, 0.3);
}

.comments-icon {
    font-size: 1.8rem;
    color: #06b6d4;
}

.comments-subtitle {
    font-size: 1.1rem;
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
}

body.light-mode .comments-subtitle {
    color: #64748b;
}

/* Stats */
.comments-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8));
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 20px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

body.light-mode .stat-card {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95));
    border-color: rgba(6, 182, 212, 0.3);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    transition: width 0.3s;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(6, 182, 212, 0.3);
}

.stat-card:hover::before {
    width: 100%;
    opacity: 0.1;
}

.stat-total::before {
    background: linear-gradient(180deg, #06b6d4, #14b8a6);
}

.stat-pending::before {
    background: linear-gradient(180deg, #fbbf24, #f59e0b);
}

.stat-approved::before {
    background: linear-gradient(180deg, #10b981, #059669);
}

.stat-rejected::before {
    background: linear-gradient(180deg, #ef4444, #dc2626);
}

.stat-icon-wrapper {
    position: relative;
    z-index: 1;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.stat-total .stat-icon {
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
    color: #06b6d4;
}

.stat-pending .stat-icon {
    background: linear-gradient(135deg, rgba(251, 191, 36, 0.2), rgba(245, 158, 11, 0.2));
    color: #fbbf24;
}

.stat-approved .stat-icon {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(5, 150, 105, 0.2));
    color: #10b981;
}

.stat-rejected .stat-icon {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(220, 38, 38, 0.2));
    color: #ef4444;
}

.stat-content {
    flex: 1;
    position: relative;
    z-index: 1;
}

.stat-value {
    font-size: 2rem;
    font-weight: 800;
    color: white;
    line-height: 1;
    margin-bottom: 0.25rem;
}

body.light-mode .stat-value {
    color: #1e293b;
}

.stat-label {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.7);
}

body.light-mode .stat-label {
    color: #64748b;
}

/* Filters */
.filters-section {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8));
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 20px;
    padding: 1.5rem;
    margin-bottom: 2rem;
}

body.light-mode .filters-section {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95));
    border-color: rgba(6, 182, 212, 0.3);
}

.filters-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
    color: rgba(255, 255, 255, 0.9);
    font-weight: 700;
    font-size: 1.1rem;
}

body.light-mode .filters-header {
    color: #1e293b;
}

.filters-form {
    display: block;
}

.filters-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.filter-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: rgba(255, 255, 255, 0.8);
    font-weight: 600;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

body.light-mode .filter-label {
    color: #475569;
}

.filter-input,
.filter-select {
    width: 100%;
    padding: 0.75rem 1rem;
    background: rgba(15, 23, 42, 0.8);
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 12px;
    color: white;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

body.light-mode .filter-input,
body.light-mode .filter-select {
    background: rgba(255, 255, 255, 0.9);
    border-color: rgba(6, 182, 212, 0.3);
    color: #1e293b;
}

.filter-input:focus,
.filter-select:focus {
    outline: none;
    border-color: #06b6d4;
    background: rgba(6, 182, 212, 0.1);
}

body.light-mode .filter-input:focus,
body.light-mode .filter-select:focus {
    background: rgba(255, 255, 255, 1);
}

.filter-actions {
    display: flex;
    gap: 0.75rem;
}

.filter-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.3s ease;
}

.filter-btn-primary {
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    color: white;
    box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
}

.filter-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(6, 182, 212, 0.4);
}

.filter-btn-secondary {
    background: rgba(107, 114, 128, 0.2);
    border: 1px solid rgba(107, 114, 128, 0.4);
    color: white;
}

body.light-mode .filter-btn-secondary {
    background: rgba(107, 114, 128, 0.1);
    color: #475569;
}

.filter-btn-secondary:hover {
    background: rgba(107, 114, 128, 0.3);
}

/* Comments Table */
.comments-table-wrapper {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8));
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 20px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    overflow-x: auto;
    overflow-y: hidden;
    position: relative;
    /* Scrollbar moderne pour Firefox */
    scrollbar-width: thin;
    scrollbar-color: rgba(6, 182, 212, 0.6) rgba(6, 182, 212, 0.1);
}

body.light-mode .comments-table-wrapper {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95));
    border-color: rgba(6, 182, 212, 0.3);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    scrollbar-color: rgba(6, 182, 212, 0.5) rgba(6, 182, 212, 0.1);
}

/* Scrollbar moderne pour WebKit (Chrome, Safari, Edge) */
.comments-table-wrapper::-webkit-scrollbar {
    height: 14px;
}

.comments-table-wrapper::-webkit-scrollbar-track {
    background: rgba(6, 182, 212, 0.1);
    border-radius: 10px;
    margin: 0 1rem;
    border: 1px solid rgba(6, 182, 212, 0.1);
}

body.light-mode .comments-table-wrapper::-webkit-scrollbar-track {
    background: rgba(6, 182, 212, 0.08);
    border-color: rgba(6, 182, 212, 0.15);
}

.comments-table-wrapper::-webkit-scrollbar-thumb {
    background: linear-gradient(90deg, rgba(6, 182, 212, 0.6), rgba(20, 184, 166, 0.6));
    border-radius: 10px;
    border: 2px solid transparent;
    background-clip: padding-box;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: inset 0 0 6px rgba(6, 182, 212, 0.3);
}

.comments-table-wrapper::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(90deg, rgba(6, 182, 212, 0.85), rgba(20, 184, 166, 0.85));
    background-clip: padding-box;
    box-shadow: inset 0 0 8px rgba(6, 182, 212, 0.5), 0 2px 4px rgba(6, 182, 212, 0.2);
    transform: scaleY(1.1);
}

.comments-table-wrapper::-webkit-scrollbar-thumb:active {
    background: linear-gradient(90deg, #06b6d4, #14b8a6);
    background-clip: padding-box;
    box-shadow: inset 0 0 10px rgba(6, 182, 212, 0.6);
}

body.light-mode .comments-table-wrapper::-webkit-scrollbar-thumb {
    background: linear-gradient(90deg, rgba(6, 182, 212, 0.5), rgba(20, 184, 166, 0.5));
    box-shadow: inset 0 0 6px rgba(6, 182, 212, 0.2);
}

body.light-mode .comments-table-wrapper::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(90deg, rgba(6, 182, 212, 0.75), rgba(20, 184, 166, 0.75));
    box-shadow: inset 0 0 8px rgba(6, 182, 212, 0.4), 0 2px 4px rgba(6, 182, 212, 0.15);
}

body.light-mode .comments-table-wrapper::-webkit-scrollbar-thumb:active {
    background: linear-gradient(90deg, #06b6d4, #14b8a6);
    box-shadow: inset 0 0 10px rgba(6, 182, 212, 0.5);
}

.comments-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    min-width: 1200px;
}

.comments-table thead {
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.15), rgba(20, 184, 166, 0.15));
}

body.light-mode .comments-table thead {
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.1));
}

.comments-table th {
    padding: 1.25rem 1rem;
    text-align: left;
    font-weight: 800;
    font-size: 0.9rem;
    color: #06b6d4;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid rgba(6, 182, 212, 0.3);
    white-space: nowrap;
}

body.light-mode .comments-table th {
    color: #06b6d4;
}

.comments-table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid rgba(6, 182, 212, 0.1);
}

.comments-table tbody tr:last-child {
    border-bottom: none;
}

.comments-table tbody tr:hover {
    background: rgba(6, 182, 212, 0.05);
}

body.light-mode .comments-table tbody tr:hover {
    background: rgba(6, 182, 212, 0.03);
}

/* Lignes colorées selon le statut */
.table-row-pending {
    background: rgba(251, 191, 36, 0.08);
    border-left: 4px solid #fbbf24;
}

body.light-mode .table-row-pending {
    background: rgba(251, 191, 36, 0.05);
}

.table-row-pending:hover {
    background: rgba(251, 191, 36, 0.15);
}

body.light-mode .table-row-pending:hover {
    background: rgba(251, 191, 36, 0.1);
}

.table-row-approved {
    background: rgba(16, 185, 129, 0.08);
    border-left: 4px solid #10b981;
}

body.light-mode .table-row-approved {
    background: rgba(16, 185, 129, 0.05);
}

.table-row-approved:hover {
    background: rgba(16, 185, 129, 0.15);
}

body.light-mode .table-row-approved:hover {
    background: rgba(16, 185, 129, 0.1);
}

.table-row-rejected {
    background: rgba(239, 68, 68, 0.08);
    border-left: 4px solid #ef4444;
}

body.light-mode .table-row-rejected {
    background: rgba(239, 68, 68, 0.05);
}

.table-row-rejected:hover {
    background: rgba(239, 68, 68, 0.15);
}

body.light-mode .table-row-rejected:hover {
    background: rgba(239, 68, 68, 0.1);
}

.comments-table td {
    padding: 1.25rem 1rem;
    vertical-align: middle;
}

/* Colonnes */
.table-col-avatar { width: 180px; }
.table-col-content { width: 250px; }
.table-col-contact { width: 180px; }
.table-col-article { width: 200px; }
.table-col-date { width: 120px; }
.table-col-status { width: 140px; }
.table-col-actions { width: 200px; }

/* Cellules */
.table-cell {
    color: rgba(255, 255, 255, 0.9);
}

body.light-mode .table-cell {
    color: #334155;
}

.cell-author-wrapper {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.cell-avatar {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    font-weight: 800;
    border: 2px solid;
    flex-shrink: 0;
}

.cell-avatar-pending {
    background: linear-gradient(135deg, rgba(251, 191, 36, 0.2), rgba(245, 158, 11, 0.2));
    border-color: rgba(251, 191, 36, 0.4);
    color: #fbbf24;
}

.cell-avatar-approved {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(5, 150, 105, 0.2));
    border-color: rgba(16, 185, 129, 0.4);
    color: #10b981;
}

.cell-avatar-rejected {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(220, 38, 38, 0.2));
    border-color: rgba(239, 68, 68, 0.4);
    color: #ef4444;
}

.cell-author-info {
    flex: 1;
    min-width: 0;
}

.cell-author-name {
    font-weight: 700;
    font-size: 0.95rem;
    color: white;
    margin-bottom: 0.25rem;
}

body.light-mode .cell-author-name {
    color: #1e293b;
}

.cell-reply-info {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.6);
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

body.light-mode .cell-reply-info {
    color: #64748b;
}

.cell-content-text {
    font-size: 0.9rem;
    line-height: 1.5;
    color: rgba(255, 255, 255, 0.85);
}

body.light-mode .cell-content-text {
    color: #475569;
}

.cell-contact-info {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.cell-contact-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
}

.cell-contact-item i {
    color: #06b6d4;
    width: 16px;
}

.cell-link {
    color: #06b6d4;
    text-decoration: none;
    transition: color 0.2s;
}

.cell-link:hover {
    color: #14b8a6;
    text-decoration: underline;
}

.cell-article-text {
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: rgba(255, 255, 255, 0.8);
}

body.light-mode .cell-article-text {
    color: #475569;
}

.cell-article-text i {
    color: #06b6d4;
}

.cell-no-data {
    color: rgba(255, 255, 255, 0.5);
    font-style: italic;
}

body.light-mode .cell-no-data {
    color: #94a3b8;
}

.cell-date-text {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.8);
}

body.light-mode .cell-date-text {
    color: #475569;
}

.cell-date-text i {
    color: #06b6d4;
    margin-right: 0.5rem;
}

.cell-time {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.6);
    display: block;
    margin-top: 0.25rem;
}

body.light-mode .cell-time {
    color: #94a3b8;
}

.cell-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.75rem;
    border-radius: 10px;
    font-size: 0.8rem;
    font-weight: 700;
    white-space: nowrap;
}

.cell-status-pending {
    background: rgba(251, 191, 36, 0.2);
    border: 1px solid rgba(251, 191, 36, 0.4);
    color: #fbbf24;
}

.cell-status-approved {
    background: rgba(16, 185, 129, 0.2);
    border: 1px solid rgba(16, 185, 129, 0.4);
    color: #10b981;
}

.cell-status-rejected {
    background: rgba(239, 68, 68, 0.2);
    border: 1px solid rgba(239, 68, 68, 0.4);
    color: #ef4444;
}

.cell-actions-wrapper {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.cell-action-form {
    display: inline-block;
}

.cell-action-btn {
    width: 36px;
    height: 36px;
    border: none;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.cell-action-view {
    background: rgba(59, 130, 246, 0.15);
    border: 1px solid rgba(59, 130, 246, 0.3);
    color: #3b82f6;
}

.cell-action-view:hover {
    background: rgba(59, 130, 246, 0.25);
    transform: translateY(-2px);
}

.cell-action-approve {
    background: rgba(16, 185, 129, 0.15);
    border: 1px solid rgba(16, 185, 129, 0.3);
    color: #10b981;
}

.cell-action-approve:hover {
    background: rgba(16, 185, 129, 0.25);
    transform: translateY(-2px);
}

.cell-action-reject {
    background: rgba(239, 68, 68, 0.15);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #ef4444;
}

.cell-action-reject:hover {
    background: rgba(239, 68, 68, 0.25);
    transform: translateY(-2px);
}

.cell-action-contact {
    background: rgba(6, 182, 212, 0.15);
    border: 1px solid rgba(6, 182, 212, 0.3);
    color: #06b6d4;
}

.cell-action-contact:hover {
    background: rgba(6, 182, 212, 0.25);
    transform: translateY(-2px);
}

.cell-action-delete {
    background: rgba(239, 68, 68, 0.15);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #ef4444;
}

.cell-action-delete:hover {
    background: rgba(239, 68, 68, 0.25);
    transform: translateY(-2px);
}

/* Pagination */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.6), rgba(51, 65, 85, 0.6));
    border: 2px dashed rgba(6, 182, 212, 0.3);
    border-radius: 24px;
}

body.light-mode .empty-state {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.8), rgba(248, 250, 252, 0.8));
    border-color: rgba(6, 182, 212, 0.4);
}

.empty-state-icon {
    width: 120px;
    height: 120px;
    margin: 0 auto 2rem;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.1));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 4rem;
    color: rgba(6, 182, 212, 0.5);
    border: 3px dashed rgba(6, 182, 212, 0.3);
}

.empty-state-title {
    font-size: 1.75rem;
    font-weight: 800;
    color: white;
    margin-bottom: 1rem;
}

body.light-mode .empty-state-title {
    color: #1e293b;
}

.empty-state-text {
    font-size: 1.1rem;
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 2rem;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

body.light-mode .empty-state-text {
    color: #64748b;
}

.empty-state-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 2rem;
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    color: white;
    border: none;
    border-radius: 16px;
    font-weight: 700;
    font-size: 1rem;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 20px rgba(6, 182, 212, 0.4);
}

.empty-state-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(6, 182, 212, 0.6);
}

/* Comment Modal */
.comment-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(5px);
    z-index: 10000;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.comment-modal-content {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.95), rgba(51, 65, 85, 0.95));
    border: 2px solid rgba(6, 182, 212, 0.3);
    border-radius: 24px;
    padding: 2rem;
    max-width: 700px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

body.light-mode .comment-modal-content {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.98), rgba(248, 250, 252, 0.98));
    border-color: rgba(6, 182, 212, 0.4);
}

.comment-modal-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid rgba(6, 182, 212, 0.2);
}

.comment-modal-icon {
    width: 50px;
    height: 50px;
    background: rgba(6, 182, 212, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #06b6d4;
}

.comment-modal-title {
    flex: 1;
    font-size: 1.5rem;
    font-weight: 800;
    color: white;
    margin: 0;
}

body.light-mode .comment-modal-title {
    color: #1e293b;
}

.comment-modal-close {
    width: 40px;
    height: 40px;
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.3);
    border-radius: 10px;
    color: #ef4444;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.comment-modal-close:hover {
    background: rgba(239, 68, 68, 0.2);
    transform: rotate(90deg);
}

.comment-modal-body {
    display: grid;
    gap: 1rem;
}

.comment-modal-detail {
    padding: 1rem;
    background: rgba(6, 182, 212, 0.05);
    border-radius: 12px;
}

body.light-mode .comment-modal-detail {
    background: rgba(6, 182, 212, 0.03);
}

.comment-modal-detail-label {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.6);
    margin-bottom: 0.5rem;
    font-weight: 600;
}

body.light-mode .comment-modal-detail-label {
    color: #94a3b8;
}

.comment-modal-detail-value {
    font-size: 1rem;
    color: white;
    word-break: break-word;
}

body.light-mode .comment-modal-detail-value {
    color: #1e293b;
}

.comment-modal-content-box {
    background: rgba(15, 23, 42, 0.5);
    border: 1px solid rgba(6, 182, 212, 0.2);
    border-radius: 12px;
    padding: 1.5rem;
    margin-top: 0.5rem;
    white-space: pre-wrap;
    line-height: 1.6;
}

body.light-mode .comment-modal-content-box {
    background: rgba(6, 182, 212, 0.05);
}

/* Responsive */
@media (max-width: 1200px) {
    .comments-table-wrapper {
        overflow-x: auto;
    }
    
    .comments-table {
        min-width: 1000px;
    }
}

@media (max-width: 768px) {
    .comments-title {
        font-size: 1.75rem;
    }
    
    .comments-icon-wrapper {
        width: 50px;
        height: 50px;
    }
    
    .comments-icon {
        font-size: 1.5rem;
    }
    
    .filters-grid {
        grid-template-columns: 1fr;
    }
    
    .filter-actions {
        flex-direction: column;
    }
    
    .filter-btn {
        width: 100%;
        justify-content: center;
    }
    
    .comments-table {
        min-width: 800px;
        font-size: 0.85rem;
    }
    
    .comments-table th,
    .comments-table td {
        padding: 0.75rem 0.5rem;
    }
    
    .cell-avatar {
        width: 35px;
        height: 35px;
        font-size: 0.9rem;
    }
    
    .cell-action-btn {
        width: 32px;
        height: 32px;
        font-size: 0.8rem;
    }
}
</style>

<script>
const commentsData = {
    @foreach($comments as $comment)
    {{ $comment->id }}: {
        id: {{ $comment->id }},
        author_name: @json($comment->author_name),
        author_email: @json($comment->author_email),
        phone: @json($comment->phone),
        content: @json($comment->content),
        status: @json($comment->status),
        created_at: @json($comment->created_at->format('d/m/Y à H:i')),
        likes: {{ $comment->likes ?? 0 }},
        article_title: @json($comment->commentable->title ?? 'N/A'),
        article_slug: @json($comment->commentable->slug ?? '#'),
        parent_author: @json($comment->parent->author_name ?? null),
    },
    @endforeach
};

function showCommentDetails(commentId) {
    const comment = commentsData[commentId];
    if (!comment) return;
    
    const modal = document.getElementById('commentModal');
    const details = document.getElementById('commentDetails');
    
    details.innerHTML = `
        <div class="comment-modal-detail">
            <div class="comment-modal-detail-label">Auteur</div>
            <div class="comment-modal-detail-value font-semibold">${comment.author_name}</div>
        </div>
        
        <div class="comment-modal-detail">
            <div class="comment-modal-detail-label">Email</div>
            <div class="comment-modal-detail-value">
                <a href="mailto:${comment.author_email}" style="color: #06b6d4; text-decoration: none;">${comment.author_email}</a>
            </div>
        </div>
        
        ${comment.phone ? `
        <div class="comment-modal-detail">
            <div class="comment-modal-detail-label">Téléphone</div>
            <div class="comment-modal-detail-value">
                <a href="tel:${comment.phone}" style="color: #06b6d4; text-decoration: none;">${comment.phone}</a>
            </div>
        </div>
        ` : ''}
        
        <div class="comment-modal-detail">
            <div class="comment-modal-detail-label">Contenu</div>
            <div class="comment-modal-content-box">${comment.content}</div>
        </div>
        
        <div class="comment-modal-detail">
            <div class="comment-modal-detail-label">Article</div>
            <div class="comment-modal-detail-value">${comment.article_title}</div>
        </div>
        
        ${comment.parent_author ? `
        <div class="comment-modal-detail">
            <div class="comment-modal-detail-label">Réponse à</div>
            <div class="comment-modal-detail-value" style="color: #06b6d4;">${comment.parent_author}</div>
        </div>
        ` : ''}
        
        <div class="comment-modal-detail">
            <div class="comment-modal-detail-label">Date</div>
            <div class="comment-modal-detail-value">${comment.created_at}</div>
        </div>
    `;
    
    modal.style.display = 'flex';
}

function closeCommentModal() {
    document.getElementById('commentModal').style.display = 'none';
}

// Fermer la modal en cliquant en dehors
document.getElementById('commentModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeCommentModal();
    }
});

// Fermer avec Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCommentModal();
    }
});

function openWhatsApp(commentId) {
    const comment = commentsData[commentId];
    if (!comment || !comment.phone) {
        alert('Aucun numéro de téléphone disponible pour ce commentaire');
        return;
    }
    
    const phone = comment.phone.replace(/\s+/g, '').replace(/-/g, '').replace(/\./g, '');
    const message = encodeURIComponent(`Bonjour ${comment.author_name},\n\nMerci pour votre commentaire.`);
    const whatsappUrl = `https://wa.me/${phone}?text=${message}`;
    
    window.open(whatsappUrl, '_blank');
}

function openEmail(commentId) {
    const comment = commentsData[commentId];
    if (!comment || !comment.author_email) {
        alert('Aucune adresse email disponible pour ce commentaire');
        return;
    }
    
    const subject = encodeURIComponent(`Réponse à votre commentaire`);
    const body = encodeURIComponent(`Bonjour ${comment.author_name},\n\nMerci pour votre commentaire.\n\nCordialement,`);
    const emailUrl = `mailto:${comment.author_email}?subject=${subject}&body=${body}`;
    
    window.location.href = emailUrl;
}
</script>
@endsection
