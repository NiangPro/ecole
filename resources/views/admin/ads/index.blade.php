@extends('admin.layout')

@section('title', 'Gestion des Publicités')

@section('content')
<div class="ads-admin" id="adsAdmin">
    <!-- Header Section -->
    <div class="ads-header">
        <div class="ads-header-glow"></div>
        <div class="ads-header-content">
            <div class="ads-header-text">
                <div class="ads-eyebrow">
                    <i class="fas fa-bullhorn"></i>
                    <span>Monétisation</span>
                </div>
                <h1 class="ads-title">
                    <span class="ads-icon-wrapper">
                        <i class="fas fa-ad ads-icon"></i>
                    </span>
                    Gestion des Publicités
                </h1>
                <p class="ads-subtitle">
                    Publicités image et vidéo YouTube affichées sur votre site
                </p>
            </div>
            <a href="{{ route('admin.ads.create') }}" class="create-ad-btn">
                <i class="fas fa-plus-circle"></i>
                <span>Nouvelle Publicité</span>
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

    <!-- Stats Cards -->
    <div class="ads-stats">
        <div class="stat-card stat-total">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-ad"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $ads->total() }}</div>
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
                <div class="stat-value">{{ $ads->where('status', 'active')->count() }}</div>
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
                <div class="stat-value">{{ $ads->where('status', 'inactive')->count() }}</div>
                <div class="stat-label">Inactives</div>
            </div>
        </div>
        <div class="stat-card stat-video">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fab fa-youtube"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $ads->filter(fn($a) => $a->isVideoAd())->count() }}</div>
                <div class="stat-label">Vidéos YouTube</div>
            </div>
        </div>
        <div class="stat-card stat-impressions">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-eye"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($ads->sum('impressions')) }}</div>
                <div class="stat-label">Impressions</div>
            </div>
        </div>
        <div class="stat-card stat-clicks">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-mouse-pointer"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($ads->sum('clicks')) }}</div>
                <div class="stat-label">Clics</div>
            </div>
        </div>
    </div>

    @if($ads->count() > 0)
    <!-- Toolbar -->
    <div class="ads-toolbar">
        <div class="toolbar-search">
            <i class="fas fa-search"></i>
            <input type="text" id="adSearch" placeholder="Rechercher une publicité..." autocomplete="off">
            <button type="button" class="toolbar-search-clear" id="adSearchClear" title="Effacer" style="display:none;">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="toolbar-filters">
            <div class="filter-chips" id="statusFilterChips">
                <button type="button" class="filter-chip active" data-filter="all">Toutes</button>
                <button type="button" class="filter-chip" data-filter="active">Actives</button>
                <button type="button" class="filter-chip" data-filter="inactive">Inactives</button>
                <button type="button" class="filter-chip" data-filter="image">Image</button>
                <button type="button" class="filter-chip" data-filter="video">YouTube</button>
            </div>

            <select id="adSort" class="toolbar-select">
                <option value="order">Trier : Ordre</option>
                <option value="name">Trier : Nom (A-Z)</option>
                <option value="impressions">Trier : Impressions</option>
                <option value="clicks">Trier : Clics</option>
            </select>
        </div>
    </div>

    <!-- Ads List -->
    <div class="ads-list" id="adsList">
        @foreach($ads as $ad)
        @php
            $isVideo = $ad->isVideoAd();
            $video = $isVideo ? $ad->video_data : null;
        @endphp
        <div class="ad-card {{ $ad->status === 'active' ? 'ad-active' : 'ad-inactive' }}"
             data-name="{{ Str::lower($ad->name) }}"
             data-status="{{ $ad->status }}"
             data-format="{{ $isVideo ? 'video' : 'image' }}"
             data-order="{{ $ad->order }}"
             data-impressions="{{ $ad->impressions }}"
             data-clicks="{{ $ad->clicks }}">
            <!-- Card Header -->
            <div class="ad-card-header">
                <div class="ad-header-left">
                    @if($isVideo)
                    <div class="ad-image-wrapper ad-youtube-thumb"
                         @if($video['thumbnail_url'] ?? null) style="background-image:url('{{ $video['thumbnail_url'] }}')" @endif>
                        <span class="ad-youtube-badge"><i class="fab fa-youtube"></i></span>
                    </div>
                    @elseif($ad->image)
                    <div class="ad-image-wrapper">
                        @if($ad->image_type === 'internal')
                            <img loading="lazy" src="{{ asset('storage/' . $ad->image) }}" alt="{{ $ad->name }}" class="ad-image">
                        @else
                            <img loading="lazy" src="{{ $ad->image }}" alt="{{ $ad->name }}" class="ad-image" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="ad-image-placeholder" style="display: none;">
                                <i class="fas fa-ad"></i>
                            </div>
                        @endif
                    </div>
                    @else
                    <div class="ad-icon-circle">
                        <i class="fas fa-ad"></i>
                    </div>
                    @endif
                    <div class="ad-title-section">
                        <h3 class="ad-name">{{ $ad->name }}</h3>
                        @if($ad->description)
                        <p class="ad-description">{{ Str::limit($ad->description, 80) }}</p>
                        @endif
                    </div>
                </div>
                <div class="ad-badges">
                    <span class="format-badge format-{{ $isVideo ? 'video' : 'image' }}">
                        @if($isVideo)
                            <i class="fab fa-youtube"></i> YouTube
                        @else
                            <i class="fas fa-image"></i> Image
                        @endif
                    </span>
                    <div class="ad-status-badge status-{{ $ad->status }}">
                        @if($ad->status === 'active')
                            <i class="fas fa-check-circle"></i>
                            <span>Actif</span>
                        @else
                            <i class="fas fa-pause-circle"></i>
                            <span>Inactif</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Card Body -->
            <div class="ad-card-body">
                @php
                    $locationLabels = [
                        '' => "Général (accueil + sidebar articles)",
                        'homepage_after_exercises' => "Accueil — bloc unique",
                        'article_sidebar' => "Articles emploi — Sidebar",
                    ];
                    $locationLabel = $locationLabels[$ad->location ?? ''] ?? $ad->location;
                @endphp
                <div class="ad-details-grid">
                    <div class="ad-detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Emplacement</div>
                            <div class="detail-value">
                                <span class="position-badge position-content">{{ $locationLabel }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="ad-detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-sort-numeric-up"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Ordre</div>
                            <div class="detail-value">#{{ $ad->order }}</div>
                        </div>
                    </div>

                    <div class="ad-detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Impressions</div>
                            <div class="detail-value impressions-value">{{ number_format($ad->impressions) }}</div>
                        </div>
                    </div>

                    <div class="ad-detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-mouse-pointer"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Clics</div>
                            <div class="detail-value clicks-value">{{ number_format($ad->clicks) }}</div>
                        </div>
                    </div>

                    @if($ad->clicks > 0 && $ad->impressions > 0)
                    <div class="ad-detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-percent"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">CTR</div>
                            <div class="detail-value ctr-value">
                                {{ number_format(($ad->clicks / $ad->impressions) * 100, 2) }}%
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($ad->start_date || $ad->end_date)
                    <div class="ad-detail-item full-width">
                        <div class="detail-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Période</div>
                            <div class="detail-value">
                                @if($ad->start_date && $ad->end_date)
                                    {{ $ad->start_date->format('d/m/Y') }} - {{ $ad->end_date->format('d/m/Y') }}
                                @elseif($ad->start_date)
                                    À partir du {{ $ad->start_date->format('d/m/Y') }}
                                @elseif($ad->end_date)
                                    Jusqu'au {{ $ad->end_date->format('d/m/Y') }}
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Card Actions -->
            <div class="ad-card-actions">
                <a href="{{ route('admin.ads.show', $ad->id) }}" class="action-btn action-view">
                    <i class="fas fa-eye"></i>
                    <span>Voir</span>
                </a>
                <a href="{{ route('admin.ads.edit', $ad->id) }}" class="action-btn action-edit">
                    <i class="fas fa-edit"></i>
                    <span>Modifier</span>
                </a>
                <form action="{{ route('admin.ads.destroy', $ad->id) }}" method="POST" class="action-form" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette publicité ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-btn action-delete">
                        <i class="fas fa-trash-alt"></i>
                        <span>Supprimer</span>
                    </button>
                </form>
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
            Aucune publicité ne correspond à votre recherche ou aux filtres sélectionnés.
        </p>
        <button type="button" class="empty-state-btn" id="resetFiltersBtn">
            <i class="fas fa-rotate-left"></i>
            <span>Réinitialiser les filtres</span>
        </button>
    </div>

    <!-- Pagination -->
    <div class="pagination-wrapper">
        {{ $ads->links() }}
    </div>
    @else
    <!-- Empty State -->
    <div class="empty-state">
        <div class="empty-state-icon">
            <i class="fas fa-ad"></i>
        </div>
        <h3 class="empty-state-title">Aucune publicité</h3>
        <p class="empty-state-text">
            Créez votre première publicité — image ou vidéo YouTube — pour commencer à monétiser votre site.
        </p>
        <a href="{{ route('admin.ads.create') }}" class="empty-state-btn">
            <i class="fas fa-plus-circle"></i>
            <span>Créer une Publicité</span>
        </a>
    </div>
    @endif
</div>

<style>
.ads-admin {
    padding: 2rem;
    max-width: 1600px;
    margin: 0 auto;
}

/* ============ Header ============ */
.ads-header {
    margin-bottom: 2rem;
    position: relative;
}

.ads-header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 2rem;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.12) 0%, rgba(239, 68, 68, 0.08) 45%, rgba(20, 184, 166, 0.1) 100%);
    border: 1px solid rgba(6, 182, 212, 0.25);
    border-radius: 28px;
    padding: 2.5rem;
    position: relative;
    overflow: hidden;
    backdrop-filter: blur(20px);
}

body.light-mode .ads-header-content {
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.08) 0%, rgba(239, 68, 68, 0.05) 45%, rgba(20, 184, 166, 0.06) 100%);
    border-color: rgba(6, 182, 212, 0.3);
}

.ads-header-glow {
    position: absolute;
    inset: 0;
    pointer-events: none;
    overflow: hidden;
    border-radius: 28px;
}

.ads-header-glow::before,
.ads-header-glow::after {
    content: '';
    position: absolute;
    width: 420px;
    height: 420px;
    border-radius: 50%;
    filter: blur(90px);
    opacity: 0.32;
}

.ads-header-glow::before {
    background: #06b6d4;
    top: -180px;
    right: -120px;
    animation: adsFloatBlob 12s ease-in-out infinite;
}

.ads-header-glow::after {
    background: #ef4444;
    bottom: -200px;
    left: -100px;
    animation: adsFloatBlob 14s ease-in-out infinite reverse;
}

@keyframes adsFloatBlob {
    0%, 100% { transform: translate(0, 0) scale(1); }
    50% { transform: translate(30px, -20px) scale(1.1); }
}

.ads-header-text {
    position: relative;
    z-index: 1;
}

.ads-eyebrow {
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

.ads-title {
    font-family: 'Poppins', sans-serif;
    font-size: 2.5rem;
    font-weight: 900;
    background: linear-gradient(135deg, #06b6d4 0%, #ef4444 50%, #06b6d4 100%);
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

.ads-icon-wrapper {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(239, 68, 68, 0.2));
    border-radius: 18px;
    border: 2px solid rgba(6, 182, 212, 0.3);
    box-shadow: 0 8px 24px rgba(6, 182, 212, 0.25);
}

.ads-icon {
    font-size: 1.8rem;
    color: #06b6d4;
}

.ads-subtitle {
    font-size: 1.05rem;
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
}

body.light-mode .ads-subtitle {
    color: #64748b;
}

.create-ad-btn {
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

.create-ad-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(6, 182, 212, 0.6);
}

.create-ad-btn i {
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
.ads-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
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
.stat-video::before { background: linear-gradient(180deg, #ef4444, #dc2626); }
.stat-impressions::before { background: linear-gradient(180deg, #3b82f6, #2563eb); }
.stat-clicks::before { background: linear-gradient(180deg, #8b5cf6, #7c3aed); }

.stat-icon-wrapper { position: relative; z-index: 1; }

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
}

.stat-total .stat-icon { background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2)); color: #06b6d4; }
.stat-active .stat-icon { background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(5, 150, 105, 0.2)); color: #10b981; }
.stat-inactive .stat-icon { background: linear-gradient(135deg, rgba(107, 114, 128, 0.2), rgba(75, 85, 99, 0.2)); color: #6b7280; }
.stat-video .stat-icon { background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(220, 38, 38, 0.2)); color: #ef4444; }
.stat-impressions .stat-icon { background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(37, 99, 235, 0.2)); color: #3b82f6; }
.stat-clicks .stat-icon { background: linear-gradient(135deg, rgba(139, 92, 246, 0.2), rgba(124, 58, 237, 0.2)); color: #8b5cf6; }

.stat-content { flex: 1; position: relative; z-index: 1; }

.stat-value {
    font-size: 1.85rem;
    font-weight: 800;
    color: white;
    line-height: 1;
    margin-bottom: 0.25rem;
}

body.light-mode .stat-value { color: #1e293b; }

.stat-label { font-size: 0.85rem; color: rgba(255, 255, 255, 0.7); }
body.light-mode .stat-label { color: #64748b; }

/* ============ Toolbar ============ */
.ads-toolbar {
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

body.light-mode .ads-toolbar {
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
    flex-wrap: wrap;
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
    background: linear-gradient(135deg, #06b6d4, #ef4444);
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

/* ============ Ads List ============ */
.ads-list {
    display: grid;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

/* ============ Ad Card ============ */
.ad-card {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8));
    border: 2px solid rgba(6, 182, 212, 0.3);
    border-radius: 24px;
    padding: 2rem;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

body.light-mode .ad-card {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95));
    border-color: rgba(6, 182, 212, 0.3);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.ad-card::before {
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

.ad-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(6, 182, 212, 0.4);
    border-color: rgba(6, 182, 212, 0.6);
}

.ad-card:hover::before { opacity: 1; }

.ad-active { border-color: rgba(16, 185, 129, 0.5); }
.ad-active::before { background: linear-gradient(90deg, #10b981, #059669); }

.ad-inactive { border-color: rgba(107, 114, 128, 0.5); opacity: 0.8; }
.ad-inactive::before { background: linear-gradient(90deg, #6b7280, #4b5563); }

.ad-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    gap: 1rem;
}

.ad-header-left {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex: 1;
    min-width: 0;
}

.ad-image-wrapper {
    width: 80px;
    height: 80px;
    border-radius: 16px;
    overflow: hidden;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
    border: 2px solid rgba(6, 182, 212, 0.3);
    flex-shrink: 0;
    position: relative;
}

.ad-youtube-thumb {
    background-size: cover;
    background-position: center;
    border-color: rgba(239, 68, 68, 0.4);
}

.ad-youtube-badge {
    position: absolute;
    inset-block-end: 4px;
    inset-inline-end: 4px;
    width: 22px;
    height: 22px;
    border-radius: 999px;
    background: #ef4444;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.65rem;
    box-shadow: 0 2px 6px rgba(0,0,0,.4);
}

.ad-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.ad-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: #06b6d4;
}

.ad-icon-circle {
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

.ad-title-section { flex: 1; min-width: 0; }

.ad-name {
    font-size: 1.35rem;
    font-weight: 800;
    color: white;
    margin: 0 0 0.4rem 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

body.light-mode .ad-name { color: #1e293b; }

.ad-description {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

body.light-mode .ad-description { color: #64748b; }

.ad-badges {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.5rem;
    flex-shrink: 0;
}

.format-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.3rem 0.7rem;
    border-radius: 999px;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    white-space: nowrap;
}

.format-image {
    background: rgba(59, 130, 246, 0.15);
    border: 1px solid rgba(59, 130, 246, 0.35);
    color: #3b82f6;
}

.format-video {
    background: rgba(239, 68, 68, 0.15);
    border: 1px solid rgba(239, 68, 68, 0.35);
    color: #ef4444;
}

.ad-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.4rem 0.9rem;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 700;
    white-space: nowrap;
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

.ad-card-body { margin-bottom: 1.5rem; }

.ad-details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1rem;
}

.ad-detail-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background: rgba(6, 182, 212, 0.05);
    border-radius: 12px;
}

body.light-mode .ad-detail-item { background: rgba(6, 182, 212, 0.03); }

.ad-detail-item.full-width { grid-column: 1 / -1; }

.detail-icon {
    width: 38px;
    height: 38px;
    background: rgba(6, 182, 212, 0.1);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #06b6d4;
    font-size: 0.95rem;
    flex-shrink: 0;
}

.detail-content { flex: 1; min-width: 0; }

.detail-label {
    font-size: 0.72rem;
    color: rgba(255, 255, 255, 0.6);
    margin-bottom: 0.25rem;
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

.position-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
}

.position-sidebar { background: rgba(6, 182, 212, 0.2); border: 1px solid rgba(6, 182, 212, 0.4); color: #06b6d4; }
.position-content { background: rgba(139, 92, 246, 0.2); border: 1px solid rgba(139, 92, 246, 0.4); color: #8b5cf6; }
.position-header { background: rgba(251, 191, 36, 0.2); border: 1px solid rgba(251, 191, 36, 0.4); color: #fbbf24; }
.position-footer { background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4); color: #ef4444; }

.impressions-value { color: #3b82f6; }
.clicks-value { color: #8b5cf6; }
.ctr-value { color: #10b981; font-size: 1.05rem; }

.ad-card-actions {
    display: flex;
    gap: 0.6rem;
    padding-top: 1.1rem;
    border-top: 1px solid rgba(6, 182, 212, 0.15);
    flex-wrap: wrap;
}

.action-form { flex: 1; min-width: 120px; }

.action-btn {
    flex: 1;
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
    min-width: 120px;
    width: 100%;
}

.action-view { background: rgba(59, 130, 246, 0.15); border: 1px solid rgba(59, 130, 246, 0.3); color: #3b82f6; }
.action-view:hover { background: rgba(59, 130, 246, 0.25); transform: translateY(-2px); }

.action-edit { background: rgba(6, 182, 212, 0.15); border: 1px solid rgba(6, 182, 212, 0.3); color: #06b6d4; }
.action-edit:hover { background: rgba(6, 182, 212, 0.25); transform: translateY(-2px); }

.action-delete { background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.25); color: #ef4444; }
.action-delete:hover { background: rgba(239, 68, 68, 0.22); transform: translateY(-2px); }

/* ============ Pagination ============ */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

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
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(239, 68, 68, 0.1));
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
    .ads-title { font-size: 1.75rem; }
    .ads-icon-wrapper { width: 50px; height: 50px; }
    .ads-icon { font-size: 1.5rem; }
    .create-ad-btn { width: 100%; justify-content: center; }

    .ads-toolbar { flex-direction: column; align-items: stretch; }
    .toolbar-search { max-width: none; }
    .toolbar-filters { justify-content: space-between; }
    .filter-chips { flex: 1; justify-content: space-between; }

    .ad-card-header { flex-direction: column; }
    .ad-badges { flex-direction: row; align-items: center; width: 100%; justify-content: space-between; }
    .ad-details-grid { grid-template-columns: 1fr; }
    .ad-card-actions { flex-direction: column; }
    .action-btn { width: 100%; }
}
</style>

<script>
(function() {
    const list = document.getElementById('adsList');
    if (!list) return;

    const cards = Array.from(list.querySelectorAll('.ad-card'));
    const searchInput = document.getElementById('adSearch');
    const searchClear = document.getElementById('adSearchClear');
    const sortSelect = document.getElementById('adSort');
    const chips = document.querySelectorAll('.filter-chip');
    const noResults = document.getElementById('noResultsState');
    const resetBtn = document.getElementById('resetFiltersBtn');

    let state = { query: '', filter: 'all', sort: 'order' };

    function matchesFilter(card) {
        if (state.filter === 'all') return true;
        if (state.filter === 'active' || state.filter === 'inactive') return card.dataset.status === state.filter;
        if (state.filter === 'image' || state.filter === 'video') return card.dataset.format === state.filter;
        return true;
    }

    function applyFilters() {
        let visibleCount = 0;
        cards.forEach(function(card) {
            const matchesQuery = !state.query || card.dataset.name.includes(state.query);
            const visible = matchesQuery && matchesFilter(card);
            card.style.display = visible ? '' : 'none';
            if (visible) visibleCount++;
        });
        noResults.style.display = visibleCount === 0 ? '' : 'none';
        list.style.display = visibleCount === 0 ? 'none' : '';
    }

    function applySort() {
        const sorted = cards.slice().sort(function(a, b) {
            if (state.sort === 'name') return a.dataset.name.localeCompare(b.dataset.name);
            if (state.sort === 'impressions') return parseInt(b.dataset.impressions, 10) - parseInt(a.dataset.impressions, 10);
            if (state.sort === 'clicks') return parseInt(b.dataset.clicks, 10) - parseInt(a.dataset.clicks, 10);
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
            state.filter = chip.dataset.filter;
            applyFilters();
        });
    });

    sortSelect.addEventListener('change', function() {
        state.sort = sortSelect.value;
        applySort();
    });

    resetBtn.addEventListener('click', function() {
        searchInput.value = '';
        state.query = '';
        state.filter = 'all';
        searchClear.style.display = 'none';
        chips.forEach(function(c) { c.classList.remove('active'); });
        document.querySelector('.filter-chip[data-filter="all"]').classList.add('active');
        applyFilters();
    });

    applySort();
})();
</script>
@endsection
