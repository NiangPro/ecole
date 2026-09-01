@extends('admin.layout')

@section('title', 'Catégories Emplois')

@section('content')
<div class="categories-admin" id="categoriesAdmin">
    <!-- Header Section -->
    <div class="categories-header">
        <div class="categories-header-glow"></div>
        <div class="categories-header-content">
            <div class="categories-header-text">
                <div class="categories-eyebrow">
                    <i class="fas fa-briefcase"></i>
                    <span>Module Emplois</span>
                </div>
                <h1 class="categories-title">
                    <span class="categories-icon-wrapper">
                        <i class="fas fa-folder-tree categories-icon"></i>
                    </span>
                    Catégories Emplois
                </h1>
                <p class="categories-subtitle">
                    Organisez et pilotez les catégories de vos articles d'emplois
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

    @if($categories->count() > 0)
    <!-- Toolbar -->
    <div class="categories-toolbar">
        <div class="toolbar-search">
            <i class="fas fa-search"></i>
            <input type="text" id="categorySearch" placeholder="Rechercher une catégorie..." autocomplete="off">
            <button type="button" class="toolbar-search-clear" id="categorySearchClear" title="Effacer" style="display:none;">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="toolbar-filters">
            <div class="filter-chips" id="statusFilterChips">
                <button type="button" class="filter-chip active" data-filter="all">Toutes</button>
                <button type="button" class="filter-chip" data-filter="active">Actives</button>
                <button type="button" class="filter-chip" data-filter="inactive">Inactives</button>
            </div>

            <select id="categorySort" class="toolbar-select">
                <option value="order">Trier : Ordre</option>
                <option value="name">Trier : Nom (A-Z)</option>
                <option value="articles">Trier : Articles</option>
            </select>

            <div class="view-toggle" id="viewToggle">
                <button type="button" class="view-toggle-btn active" data-view="grid" title="Vue grille">
                    <i class="fas fa-th-large"></i>
                </button>
                <button type="button" class="view-toggle-btn" data-view="list" title="Vue liste">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Categories List -->
    <div class="categories-list view-grid" id="categoriesList">
        @foreach($categories as $category)
        @php
            $articlesCount = $category->articles()->count();
        @endphp
        <div class="category-card {{ $category->is_active ? 'category-active' : 'category-inactive' }}"
             data-name="{{ Str::lower($category->name) }}"
             data-status="{{ $category->is_active ? 'active' : 'inactive' }}"
             data-order="{{ $category->order }}"
             data-articles="{{ $articlesCount }}">
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
                        <span class="category-slug-pill">
                            <i class="fas fa-link"></i>{{ $category->slug }}
                        </span>
                        @if($category->description)
                        <p class="category-description">{{ Str::limit($category->description, 90) }}</p>
                        @endif
                    </div>
                </div>
                <div class="category-status-badge status-{{ $category->is_active ? 'active' : 'inactive' }}">
                    @if($category->is_active)
                        <i class="fas fa-circle"></i>
                        <span>Active</span>
                    @else
                        <i class="fas fa-circle"></i>
                        <span>Inactive</span>
                    @endif
                </div>
            </div>

            <!-- Card Body -->
            <div class="category-card-body">
                <div class="category-details-grid">
                    <div class="category-detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Articles</div>
                            <div class="detail-value articles-value">{{ $articlesCount }}</div>
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
                    <button type="submit" class="action-btn action-delete" title="Supprimer">
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

    <!-- No results state (client-side filtering) -->
    <div class="empty-state" id="noResultsState" style="display:none;">
        <div class="empty-state-icon">
            <i class="fas fa-search"></i>
        </div>
        <h3 class="empty-state-title">Aucun résultat</h3>
        <p class="empty-state-text">
            Aucune catégorie ne correspond à votre recherche ou aux filtres sélectionnés.
        </p>
        <button type="button" class="empty-state-btn" id="resetFiltersBtn">
            <i class="fas fa-rotate-left"></i>
            <span>Réinitialiser les filtres</span>
        </button>
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

/* ============ Header ============ */
.categories-header {
    margin-bottom: 2rem;
    position: relative;
}

.categories-header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 2rem;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.12) 0%, rgba(20, 184, 166, 0.1) 45%, rgba(139, 92, 246, 0.08) 100%);
    border: 1px solid rgba(6, 182, 212, 0.25);
    border-radius: 28px;
    padding: 2.5rem;
    position: relative;
    overflow: hidden;
    backdrop-filter: blur(20px);
}

body.light-mode .categories-header-content {
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.08) 0%, rgba(20, 184, 166, 0.06) 45%, rgba(139, 92, 246, 0.05) 100%);
    border-color: rgba(6, 182, 212, 0.3);
}

.categories-header-glow {
    position: absolute;
    inset: 0;
    pointer-events: none;
    overflow: hidden;
    border-radius: 28px;
}

.categories-header-glow::before,
.categories-header-glow::after {
    content: '';
    position: absolute;
    width: 420px;
    height: 420px;
    border-radius: 50%;
    filter: blur(90px);
    opacity: 0.35;
}

.categories-header-glow::before {
    background: #06b6d4;
    top: -180px;
    right: -120px;
    animation: floatBlob 12s ease-in-out infinite;
}

.categories-header-glow::after {
    background: #8b5cf6;
    bottom: -200px;
    left: -100px;
    animation: floatBlob 14s ease-in-out infinite reverse;
}

@keyframes floatBlob {
    0%, 100% { transform: translate(0, 0) scale(1); }
    50% { transform: translate(30px, -20px) scale(1.1); }
}

.categories-header-text {
    position: relative;
    z-index: 1;
}

.categories-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.35rem 0.85rem;
    background: rgba(6, 182, 212, 0.15);
    border: 1px solid rgba(6, 182, 212, 0.35);
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: #06b6d4;
    margin-bottom: 1rem;
}

.categories-title {
    font-family: 'Poppins', sans-serif;
    font-size: 2.5rem;
    font-weight: 900;
    background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 40%, #8b5cf6 80%, #06b6d4 100%);
    background-size: 200% auto;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: shimmer 4s linear infinite;
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
    border-radius: 18px;
    border: 2px solid rgba(6, 182, 212, 0.3);
    box-shadow: 0 8px 24px rgba(6, 182, 212, 0.25);
}

.categories-icon {
    font-size: 1.8rem;
    color: #06b6d4;
}

.categories-subtitle {
    font-size: 1.05rem;
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
    white-space: nowrap;
}

.create-category-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(6, 182, 212, 0.6);
}

.create-category-btn i {
    font-size: 1.2rem;
}

/* ============ Alerts ============ */
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
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
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

.alert-icon { font-size: 1.5rem; }
.alert-content { flex: 1; }
.alert-content strong { display: block; margin-bottom: 0.25rem; font-weight: 700; }
.alert-content p { margin: 0; opacity: 0.9; }

.alert-close {
    background: transparent;
    border: none;
    color: inherit;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 8px;
    transition: background 0.2s;
}

.alert-close:hover { background: rgba(255, 255, 255, 0.1); }

/* ============ Stats ============ */
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

.stat-card:hover::before { width: 100%; opacity: 0.1; }

.stat-total::before { background: linear-gradient(180deg, #06b6d4, #14b8a6); }
.stat-active::before { background: linear-gradient(180deg, #10b981, #059669); }
.stat-inactive::before { background: linear-gradient(180deg, #6b7280, #4b5563); }
.stat-articles::before { background: linear-gradient(180deg, #8b5cf6, #7c3aed); }

.stat-icon-wrapper { position: relative; z-index: 1; }

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.stat-total .stat-icon { background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2)); color: #06b6d4; }
.stat-active .stat-icon { background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(5, 150, 105, 0.2)); color: #10b981; }
.stat-inactive .stat-icon { background: linear-gradient(135deg, rgba(107, 114, 128, 0.2), rgba(75, 85, 99, 0.2)); color: #6b7280; }
.stat-articles .stat-icon { background: linear-gradient(135deg, rgba(139, 92, 246, 0.2), rgba(124, 58, 237, 0.2)); color: #8b5cf6; }

.stat-content { flex: 1; position: relative; z-index: 1; }

.stat-value {
    font-size: 2rem;
    font-weight: 800;
    color: white;
    line-height: 1;
    margin-bottom: 0.25rem;
}

body.light-mode .stat-value { color: #1e293b; }

.stat-label { font-size: 0.9rem; color: rgba(255, 255, 255, 0.7); }
body.light-mode .stat-label { color: #64748b; }

/* ============ Toolbar ============ */
.categories-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
    background: rgba(10, 10, 26, 0.5);
    backdrop-filter: blur(16px);
    border: 1px solid rgba(6, 182, 212, 0.2);
    border-radius: 18px;
    padding: 1rem 1.25rem;
    margin-bottom: 1.5rem;
}

body.light-mode .categories-toolbar {
    background: rgba(255, 255, 255, 0.85);
    border-color: rgba(6, 182, 212, 0.25);
}

.toolbar-search {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    background: rgba(6, 182, 212, 0.06);
    border: 1px solid rgba(6, 182, 212, 0.25);
    border-radius: 12px;
    padding: 0.65rem 1rem;
    flex: 1;
    min-width: 220px;
    max-width: 360px;
    transition: all 0.2s ease;
}

.toolbar-search:focus-within {
    border-color: #06b6d4;
    background: rgba(6, 182, 212, 0.1);
    box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.15);
}

.toolbar-search i { color: #06b6d4; font-size: 0.9rem; }

.toolbar-search input {
    background: transparent;
    border: none;
    outline: none;
    color: white;
    font-size: 0.9rem;
    flex: 1;
    min-width: 0;
}

.toolbar-search input::placeholder { color: rgba(255, 255, 255, 0.4); }
body.light-mode .toolbar-search input { color: #1e293b; }
body.light-mode .toolbar-search input::placeholder { color: #94a3b8; }
body.light-mode .toolbar-search { background: rgba(6, 182, 212, 0.04); }

.toolbar-search-clear {
    background: none;
    border: none;
    color: rgba(255, 255, 255, 0.5);
    cursor: pointer;
    padding: 0.15rem;
    display: flex;
}

.toolbar-search-clear:hover { color: #ef4444; }

.toolbar-filters {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.filter-chips {
    display: flex;
    gap: 0.4rem;
    background: rgba(6, 182, 212, 0.05);
    border: 1px solid rgba(6, 182, 212, 0.15);
    border-radius: 999px;
    padding: 0.25rem;
}

body.light-mode .filter-chips { background: rgba(6, 182, 212, 0.04); }

.filter-chip {
    background: transparent;
    border: none;
    color: rgba(255, 255, 255, 0.65);
    padding: 0.45rem 0.9rem;
    border-radius: 999px;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
}

body.light-mode .filter-chip { color: #64748b; }

.filter-chip:hover { color: #06b6d4; }

.filter-chip.active {
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    color: #fff;
    box-shadow: 0 4px 12px rgba(6, 182, 212, 0.35);
}

.toolbar-select {
    background: rgba(15, 23, 42, 0.8);
    border: 1px solid rgba(6, 182, 212, 0.25);
    border-radius: 10px;
    color: white;
    padding: 0.55rem 2rem 0.55rem 0.85rem;
    font-size: 0.85rem;
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2306b6d4' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 10px;
}

body.light-mode .toolbar-select {
    background-color: rgba(255, 255, 255, 0.9);
    color: #1e293b;
}

.toolbar-select:focus { outline: none; border-color: #06b6d4; }

.view-toggle {
    display: flex;
    background: rgba(6, 182, 212, 0.05);
    border: 1px solid rgba(6, 182, 212, 0.2);
    border-radius: 10px;
    padding: 0.2rem;
    gap: 0.2rem;
}

body.light-mode .view-toggle { background: rgba(6, 182, 212, 0.04); }

.view-toggle-btn {
    background: transparent;
    border: none;
    color: rgba(255, 255, 255, 0.5);
    width: 34px;
    height: 34px;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

body.light-mode .view-toggle-btn { color: #94a3b8; }

.view-toggle-btn:hover { color: #06b6d4; }

.view-toggle-btn.active {
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    color: #fff;
}

/* ============ Categories List ============ */
.categories-list {
    display: grid;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.categories-list.view-grid {
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
}

.categories-list.view-list {
    grid-template-columns: 1fr;
}

/* ============ Category Card ============ */
.category-card {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.75), rgba(51, 65, 85, 0.75));
    backdrop-filter: blur(14px);
    border: 1px solid rgba(6, 182, 212, 0.25);
    border-radius: 22px;
    padding: 1.75rem;
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

body.light-mode .category-card {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95));
    border-color: rgba(6, 182, 212, 0.25);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
}

.category-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #06b6d4, #14b8a6);
    opacity: 0;
    transition: opacity 0.3s;
}

.category-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 40px rgba(6, 182, 212, 0.25);
    border-color: rgba(6, 182, 212, 0.5);
}

.category-card:hover::before { opacity: 1; }

.category-active { border-color: rgba(16, 185, 129, 0.35); }
.category-active::before { background: linear-gradient(90deg, #10b981, #059669); }

.category-inactive { border-color: rgba(107, 114, 128, 0.35); opacity: 0.82; }
.category-inactive::before { background: linear-gradient(90deg, #6b7280, #4b5563); }

.category-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.25rem;
    gap: 0.75rem;
}

.category-header-left {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    flex: 1;
    min-width: 0;
}

.category-image-wrapper {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    overflow: hidden;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
    border: 2px solid rgba(6, 182, 212, 0.3);
    flex-shrink: 0;
    position: relative;
}

.category-image { width: 100%; height: 100%; object-fit: cover; }

.category-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    color: #06b6d4;
}

.category-icon-circle {
    width: 64px;
    height: 64px;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    color: #06b6d4;
    border: 2px solid rgba(6, 182, 212, 0.3);
    flex-shrink: 0;
}

.category-title-section { flex: 1; min-width: 0; }

.category-name {
    font-size: 1.25rem;
    font-weight: 800;
    color: white;
    margin: 0 0 0.4rem 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

body.light-mode .category-name { color: #1e293b; }

.category-slug-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.72rem;
    font-weight: 600;
    color: #06b6d4;
    background: rgba(6, 182, 212, 0.1);
    border: 1px solid rgba(6, 182, 212, 0.2);
    padding: 0.15rem 0.55rem;
    border-radius: 999px;
    margin-bottom: 0.5rem;
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.category-description {
    font-size: 0.88rem;
    color: rgba(255, 255, 255, 0.65);
    margin: 0;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

body.light-mode .category-description { color: #64748b; }

.category-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.4rem 0.85rem;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 700;
    flex-shrink: 0;
}

.category-status-badge i { font-size: 0.5rem; }

.status-active {
    background: rgba(16, 185, 129, 0.15);
    border: 1px solid rgba(16, 185, 129, 0.35);
    color: #10b981;
}

.status-active i { animation: pulseDot 2s ease-in-out infinite; }

@keyframes pulseDot {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.4; }
}

.status-inactive {
    background: rgba(107, 114, 128, 0.15);
    border: 1px solid rgba(107, 114, 128, 0.35);
    color: #9ca3af;
}

.category-card-body { margin-bottom: 1.25rem; flex: 1; }

.category-details-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.75rem;
}

.category-detail-item {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    padding: 0.75rem;
    background: rgba(6, 182, 212, 0.05);
    border-radius: 12px;
}

body.light-mode .category-detail-item { background: rgba(6, 182, 212, 0.03); }

.detail-icon {
    width: 34px;
    height: 34px;
    background: rgba(6, 182, 212, 0.12);
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #06b6d4;
    font-size: 0.9rem;
    flex-shrink: 0;
}

.detail-content { flex: 1; min-width: 0; }

.detail-label {
    font-size: 0.68rem;
    color: rgba(255, 255, 255, 0.55);
    margin-bottom: 0.15rem;
    text-transform: uppercase;
    letter-spacing: 0.03em;
}

body.light-mode .detail-label { color: #94a3b8; }

.detail-value {
    font-size: 0.95rem;
    font-weight: 700;
    color: white;
}

body.light-mode .detail-value { color: #1e293b; }

.articles-value { color: #8b5cf6; font-size: 1rem; }

.category-card-actions {
    display: flex;
    gap: 0.6rem;
    padding-top: 1.1rem;
    border-top: 1px solid rgba(6, 182, 212, 0.15);
}

.action-form { flex: 1; }

.action-btn {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.65rem 1rem;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.25s ease;
    text-decoration: none;
}

.action-edit {
    flex: 1;
    background: rgba(6, 182, 212, 0.15);
    border: 1px solid rgba(6, 182, 212, 0.3);
    color: #06b6d4;
}

.action-edit:hover {
    background: rgba(6, 182, 212, 0.25);
    transform: translateY(-2px);
}

.action-delete {
    background: rgba(239, 68, 68, 0.12);
    border: 1px solid rgba(239, 68, 68, 0.25);
    color: #ef4444;
}

.action-delete:hover {
    background: rgba(239, 68, 68, 0.22);
    transform: translateY(-2px);
}

/* ---- List view overrides ---- */
.view-list .category-card {
    flex-direction: row;
    align-items: center;
    padding: 1.25rem 1.75rem;
    gap: 1.5rem;
}

.view-list .category-card-header {
    margin-bottom: 0;
    flex: 1.4;
    min-width: 260px;
}

.view-list .category-card-body { margin-bottom: 0; flex: 1.2; min-width: 220px; }
.view-list .category-details-grid { grid-template-columns: repeat(2, minmax(120px, 1fr)); }
.view-list .category-description { display: none; }
.view-list .category-card-actions { padding-top: 0; border-top: none; flex: 0 0 auto; width: 260px; }

/* ============ Empty State ============ */
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

body.light-mode .empty-state-title { color: #1e293b; }

.empty-state-text {
    font-size: 1.1rem;
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 2rem;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

body.light-mode .empty-state-text { color: #64748b; }

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
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 20px rgba(6, 182, 212, 0.4);
}

.empty-state-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(6, 182, 212, 0.6);
}

/* ============ Responsive ============ */
@media (max-width: 768px) {
    .categories-title { font-size: 1.75rem; }
    .categories-icon-wrapper { width: 50px; height: 50px; }
    .categories-icon { font-size: 1.5rem; }
    .create-category-btn { width: 100%; justify-content: center; }

    .categories-toolbar { flex-direction: column; align-items: stretch; }
    .toolbar-search { max-width: none; }
    .toolbar-filters { justify-content: space-between; }
    .filter-chips { flex: 1; justify-content: space-between; }

    .categories-list.view-grid { grid-template-columns: 1fr; }

    .view-list .category-card { flex-direction: column; align-items: stretch; }
    .view-list .category-card-header { min-width: 0; }
    .view-list .category-card-body { min-width: 0; }
    .view-list .category-details-grid { grid-template-columns: 1fr; }
    .view-list .category-description { display: -webkit-box; }
    .view-list .category-card-actions { width: 100%; padding-top: 1.1rem; border-top: 1px solid rgba(6, 182, 212, 0.15); }

    .category-card-actions { flex-direction: column; }
}
</style>

<script>
(function() {
    const list = document.getElementById('categoriesList');
    if (!list) return;

    const cards = Array.from(list.querySelectorAll('.category-card'));
    const searchInput = document.getElementById('categorySearch');
    const searchClear = document.getElementById('categorySearchClear');
    const sortSelect = document.getElementById('categorySort');
    const chips = document.querySelectorAll('.filter-chip');
    const viewButtons = document.querySelectorAll('.view-toggle-btn');
    const noResults = document.getElementById('noResultsState');
    const resetBtn = document.getElementById('resetFiltersBtn');

    let state = { query: '', status: 'all', sort: 'order' };

    function applyFilters() {
        let visibleCount = 0;

        cards.forEach(function(card) {
            const matchesQuery = !state.query || card.dataset.name.includes(state.query);
            const matchesStatus = state.status === 'all' || card.dataset.status === state.status;
            const visible = matchesQuery && matchesStatus;
            card.style.display = visible ? '' : 'none';
            if (visible) visibleCount++;
        });

        noResults.style.display = visibleCount === 0 ? '' : 'none';
        list.style.display = visibleCount === 0 ? 'none' : '';
    }

    function applySort() {
        const sorted = cards.slice().sort(function(a, b) {
            if (state.sort === 'name') {
                return a.dataset.name.localeCompare(b.dataset.name);
            }
            if (state.sort === 'articles') {
                return parseInt(b.dataset.articles, 10) - parseInt(a.dataset.articles, 10);
            }
            return parseInt(a.dataset.order, 10) - parseInt(b.dataset.order, 10);
        });
        sorted.forEach(function(card) { list.appendChild(card); });
    }

    searchInput.addEventListener('input', function() {
        state.query = searchInput.value.trim().toLowerCase();
        searchClear.style.display = state.query ? 'flex' : 'none';
        applyFilters();
    });

    searchClear.addEventListener('click', function() {
        searchInput.value = '';
        state.query = '';
        searchClear.style.display = 'none';
        applyFilters();
        searchInput.focus();
    });

    chips.forEach(function(chip) {
        chip.addEventListener('click', function() {
            chips.forEach(function(c) { c.classList.remove('active'); });
            chip.classList.add('active');
            state.status = chip.dataset.filter;
            applyFilters();
        });
    });

    sortSelect.addEventListener('change', function() {
        state.sort = sortSelect.value;
        applySort();
    });

    viewButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            viewButtons.forEach(function(b) { b.classList.remove('active'); });
            btn.classList.add('active');
            list.classList.remove('view-grid', 'view-list');
            list.classList.add('view-' + btn.dataset.view);
            try { localStorage.setItem('jobCategoriesView', btn.dataset.view); } catch (e) {}
        });
    });

    resetBtn.addEventListener('click', function() {
        searchInput.value = '';
        state.query = '';
        state.status = 'all';
        searchClear.style.display = 'none';
        chips.forEach(function(c) { c.classList.remove('active'); });
        document.querySelector('.filter-chip[data-filter="all"]').classList.add('active');
        applyFilters();
    });

    try {
        const savedView = localStorage.getItem('jobCategoriesView');
        if (savedView === 'list' || savedView === 'grid') {
            list.classList.remove('view-grid', 'view-list');
            list.classList.add('view-' + savedView);
            viewButtons.forEach(function(b) {
                b.classList.toggle('active', b.dataset.view === savedView);
            });
        }
    } catch (e) {}

    applySort();
})();
</script>
@endsection
