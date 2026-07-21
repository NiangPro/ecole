@extends('admin.layout')

@section('title', 'Catégories Emplois')

@section('content')
<div class="categories-admin">
    <!-- Header Section -->
    <div class="categories-header">
        <div class="categories-header-content">
            <div class="categories-header-text">
                <h1 class="categories-title">
                    <span class="categories-icon-wrapper">
                        <i class="fas fa-folder categories-icon"></i>
                    </span>
                    Catégories Emplois
                </h1>
                <p class="categories-subtitle">
                    Gérez les catégories d'articles d'emplois
                </p>
            </div>
            <a href="{{ route('admin.jobs.categories.create') }}" class="create-category-btn">
                <i class="fas fa-plus-circle"></i>
                <span>Nouvelle catégorie</span>
            </a>
        </div>
    </div>

    <!-- Messages Flash -->
    @if(session('success'))
    <div class="alert alert-success">
        <div class="alert-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="alert-content">
            <strong>Succès !</strong>
            <p>{{ session('success') }}</p>
        </div>
        <button class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-error">
        <div class="alert-icon">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <div class="alert-content">
            <strong>Erreur !</strong>
            <p>{{ session('error') }}</p>
        </div>
        <button class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="categories-stats">
        <div class="stat-card stat-total">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-folder"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $categories->count() }}</div>
                <div class="stat-label">Total</div>
            </div>
        </div>
        <div class="stat-card stat-active">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $categories->where('is_active', true)->count() }}</div>
                <div class="stat-label">Actives</div>
            </div>
        </div>
        <div class="stat-card stat-inactive">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-pause-circle"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $categories->where('is_active', false)->count() }}</div>
                <div class="stat-label">Inactives</div>
            </div>
        </div>
        <div class="stat-card stat-articles">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $categories->sum(function($cat) { return $cat->articles()->count(); }) }}</div>
                <div class="stat-label">Articles</div>
            </div>
        </div>
    </div>

    <!-- Categories List -->
    @if($categories->count() > 0)
    <div class="categories-list">
        @foreach($categories as $category)
        <div class="category-card {{ $category->is_active ? 'category-active' : 'category-inactive' }}">
            <!-- Card Header -->
            <div class="category-card-header">
                <div class="category-header-left">
                    @if($category->image)
                    <div class="category-image-wrapper">
                        <img loading="lazy" src="{{ $category->image_type === 'internal' ? \Illuminate\Support\Facades\Storage::url($category->image) : $category->image }}" 
                             alt="{{ $category->name }}" 
                             class="category-image"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="category-image-placeholder" style="display: none;">
                            <i class="{{ $category->icon ?? 'fas fa-folder' }}"></i>
                        </div>
                    </div>
                    @elseif($category->icon)
                    <div class="category-icon-circle">
                        <i class="{{ $category->icon }}"></i>
                    </div>
                    @else
                    <div class="category-icon-circle">
                        <i class="fas fa-folder"></i>
                    </div>
                    @endif
                    <div class="category-title-section">
                        <h3 class="category-name">{{ $category->name }}</h3>
                        @if($category->description)
                        <p class="category-description">{{ Str::limit($category->description, 80) }}</p>
                        @endif
                    </div>
                </div>
                <div class="category-status-badge status-{{ $category->is_active ? 'active' : 'inactive' }}">
                    @if($category->is_active)
                        <i class="fas fa-check-circle"></i>
                        <span>Active</span>
                    @else
                        <i class="fas fa-pause-circle"></i>
                        <span>Inactive</span>
                    @endif
                </div>
            </div>

            <!-- Card Body -->
            <div class="category-card-body">
                <div class="category-details-grid">
                    <div class="category-detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-link"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Slug</div>
                            <div class="detail-value">{{ $category->slug }}</div>
                        </div>
                    </div>
                    
                    <div class="category-detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Articles</div>
                            <div class="detail-value articles-value">{{ $category->articles()->count() }}</div>
                        </div>
                    </div>
                    
                    <div class="category-detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-sort-numeric-up"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Ordre</div>
                            <div class="detail-value">#{{ $category->order }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Actions -->
            <div class="category-card-actions">
                <a href="{{ route('admin.jobs.categories.edit', $category->id) }}" class="action-btn action-edit">
                    <i class="fas fa-edit"></i>
                    <span>Modifier</span>
                </a>
                @auth
                @if(Auth::user()->isAdmin())
                <form action="{{ route('admin.jobs.categories.destroy', $category->id) }}" method="POST" class="action-form" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-btn action-delete">
                        <i class="fas fa-trash-alt"></i>
                        <span>Supprimer</span>
                    </button>
                </form>
                @endif
                @endauth
            </div>
        </div>
        @endforeach
    </div>
    @else
    <!-- Empty State -->
    <div class="empty-state">
        <div class="empty-state-icon">
            <i class="fas fa-folder-open"></i>
        </div>
        <h3 class="empty-state-title">Aucune catégorie</h3>
        <p class="empty-state-text">
            Créez votre première catégorie pour organiser vos articles d'emplois.
        </p>
        <a href="{{ route('admin.jobs.categories.create') }}" class="empty-state-btn">
            <i class="fas fa-plus-circle"></i>
            <span>Créer une Catégorie</span>
        </a>
    </div>
    @endif
</div>

<style>
.categories-admin {
    padding: 2rem;
    max-width: 1600px;
    margin: 0 auto;
}

/* Header */
.categories-header {
    margin-bottom: 2rem;
}

.categories-header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 2rem;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(20, 184, 166, 0.1) 100%);
    border: 2px solid rgba(6, 182, 212, 0.3);
    border-radius: 24px;
    padding: 2.5rem;
    position: relative;
    overflow: hidden;
}

body.light-mode .categories-header-content {
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.08) 0%, rgba(20, 184, 166, 0.08) 100%);
    border-color: rgba(6, 182, 212, 0.4);
}

.categories-header-content::before {
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

.categories-header-text {
    position: relative;
    z-index: 1;
}

.categories-title {
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

.categories-icon-wrapper {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
    border-radius: 16px;
    border: 2px solid rgba(6, 182, 212, 0.3);
}

.categories-icon {
    font-size: 1.8rem;
    color: #06b6d4;
}

.categories-subtitle {
    font-size: 1.1rem;
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
}

body.light-mode .categories-subtitle {
    color: #64748b;
}

.create-category-btn {
    position: relative;
    z-index: 1;
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
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 20px rgba(6, 182, 212, 0.4);
}

.create-category-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(6, 182, 212, 0.6);
}

.create-category-btn i {
    font-size: 1.2rem;
}

/* Alerts */
.alert {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem 1.5rem;
    border-radius: 16px;
    margin-bottom: 2rem;
    animation: slideIn 0.3s ease;
    position: relative;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.alert-success {
    background: rgba(16, 185, 129, 0.15);
    border: 2px solid rgba(16, 185, 129, 0.3);
    color: #10b981;
}

.alert-error {
    background: rgba(239, 68, 68, 0.15);
    border: 2px solid rgba(239, 68, 68, 0.3);
    color: #ef4444;
}

.alert-icon {
    font-size: 1.5rem;
}

.alert-content {
    flex: 1;
}

.alert-content strong {
    display: block;
    margin-bottom: 0.25rem;
    font-weight: 700;
}

.alert-content p {
    margin: 0;
    opacity: 0.9;
}

.alert-close {
    background: transparent;
    border: none;
    color: inherit;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 8px;
    transition: background 0.2s;
}

.alert-close:hover {
    background: rgba(255, 255, 255, 0.1);
}

/* Stats */
.categories-stats {
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

.stat-active::before {
    background: linear-gradient(180deg, #10b981, #059669);
}

.stat-inactive::before {
    background: linear-gradient(180deg, #6b7280, #4b5563);
}

.stat-articles::before {
    background: linear-gradient(180deg, #8b5cf6, #7c3aed);
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

.stat-active .stat-icon {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(5, 150, 105, 0.2));
    color: #10b981;
}

.stat-inactive .stat-icon {
    background: linear-gradient(135deg, rgba(107, 114, 128, 0.2), rgba(75, 85, 99, 0.2));
    color: #6b7280;
}

.stat-articles .stat-icon {
    background: linear-gradient(135deg, rgba(139, 92, 246, 0.2), rgba(124, 58, 237, 0.2));
    color: #8b5cf6;
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

/* Categories List */
.categories-list {
    display: grid;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

/* Category Card */
.category-card {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8));
    border: 2px solid rgba(6, 182, 212, 0.3);
    border-radius: 24px;
    padding: 2rem;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

body.light-mode .category-card {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95));
    border-color: rgba(6, 182, 212, 0.3);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.category-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #06b6d4, #14b8a6);
    opacity: 0;
    transition: opacity 0.3s;
}

.category-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(6, 182, 212, 0.4);
    border-color: rgba(6, 182, 212, 0.6);
}

.category-card:hover::before {
    opacity: 1;
}

.category-active {
    border-color: rgba(16, 185, 129, 0.5);
}

.category-active::before {
    background: linear-gradient(90deg, #10b981, #059669);
}

.category-inactive {
    border-color: rgba(107, 114, 128, 0.5);
    opacity: 0.8;
}

.category-inactive::before {
    background: linear-gradient(90deg, #6b7280, #4b5563);
}

.category-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    gap: 1rem;
}

.category-header-left {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex: 1;
}

.category-image-wrapper {
    width: 80px;
    height: 80px;
    border-radius: 16px;
    overflow: hidden;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
    border: 2px solid rgba(6, 182, 212, 0.3);
    flex-shrink: 0;
    position: relative;
}

.category-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.category-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: #06b6d4;
}

.category-icon-circle {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: #06b6d4;
    border: 2px solid rgba(6, 182, 212, 0.3);
    flex-shrink: 0;
}

.category-title-section {
    flex: 1;
}

.category-name {
    font-size: 1.5rem;
    font-weight: 800;
    color: white;
    margin: 0 0 0.5rem 0;
}

body.light-mode .category-name {
    color: #1e293b;
}

.category-description {
    font-size: 0.95rem;
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
    line-height: 1.5;
}

body.light-mode .category-description {
    color: #64748b;
}

.category-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 12px;
    font-size: 0.85rem;
    font-weight: 700;
}

.status-active {
    background: rgba(16, 185, 129, 0.2);
    border: 1px solid rgba(16, 185, 129, 0.4);
    color: #10b981;
}

.status-inactive {
    background: rgba(107, 114, 128, 0.2);
    border: 1px solid rgba(107, 114, 128, 0.4);
    color: #6b7280;
}

.category-card-body {
    margin-bottom: 1.5rem;
}

.category-details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.category-detail-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background: rgba(6, 182, 212, 0.05);
    border-radius: 12px;
}

body.light-mode .category-detail-item {
    background: rgba(6, 182, 212, 0.03);
}

.detail-icon {
    width: 40px;
    height: 40px;
    background: rgba(6, 182, 212, 0.1);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #06b6d4;
    font-size: 1rem;
}

.detail-content {
    flex: 1;
}

.detail-label {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.6);
    margin-bottom: 0.25rem;
}

body.light-mode .detail-label {
    color: #94a3b8;
}

.detail-value {
    font-size: 1rem;
    font-weight: 700;
    color: white;
}

body.light-mode .detail-value {
    color: #1e293b;
}

.articles-value {
    color: #8b5cf6;
    font-size: 1.1rem;
}

.category-card-actions {
    display: flex;
    gap: 0.75rem;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(6, 182, 212, 0.2);
    flex-wrap: wrap;
}

.action-form {
    flex: 1;
    min-width: 120px;
}

.action-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    min-width: 120px;
}

.action-edit {
    background: rgba(6, 182, 212, 0.15);
    border: 1px solid rgba(6, 182, 212, 0.3);
    color: #06b6d4;
}

.action-edit:hover {
    background: rgba(6, 182, 212, 0.25);
    transform: translateY(-2px);
}

.action-delete {
    background: rgba(239, 68, 68, 0.15);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #ef4444;
}

.action-delete:hover {
    background: rgba(239, 68, 68, 0.25);
    transform: translateY(-2px);
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

/* Responsive */
@media (max-width: 768px) {
    .categories-title {
        font-size: 1.75rem;
    }
    
    .categories-icon-wrapper {
        width: 50px;
        height: 50px;
    }
    
    .categories-icon {
        font-size: 1.5rem;
    }
    
    .create-category-btn {
        width: 100%;
        justify-content: center;
    }
    
    .category-card-header {
        flex-direction: column;
    }
    
    .category-details-grid {
        grid-template-columns: 1fr;
    }
    
    .category-card-actions {
        flex-direction: column;
    }
    
    .action-btn {
        width: 100%;
    }
}
</style>
@endsection
