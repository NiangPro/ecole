@extends('layouts.app')

@section('title', 'Cours Payants - NiangProgrammeur')

@section('content')
<div class="courses-page">
    <!-- Hero Section avec recherche -->
    <div class="courses-hero">
        <div class="courses-hero-content">
            <div class="courses-hero-badge">
                <i class="fas fa-graduation-cap"></i>
                <span>Formations Premium</span>
            </div>
            <h1 class="courses-hero-title">
                Développez vos compétences avec nos <span class="gradient-text">cours payants</span>
            </h1>
            <p class="courses-hero-subtitle">
                Accédez à des formations approfondies et développez votre expertise professionnelle
            </p>
            
            <!-- Barre de recherche -->
            <form method="GET" action="{{ route('monetization.courses') }}" class="courses-search-form">
                <div class="courses-search-wrapper">
                    <i class="fas fa-search courses-search-icon"></i>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Rechercher un cours..." 
                        class="courses-search-input"
                    >
                    <button type="submit" class="courses-search-btn">
                        <span>Rechercher</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="courses-container">
        <!-- Sidebar Filtres -->
        <aside class="courses-filters-sidebar">
            <div class="filters-header">
                <h3><i class="fas fa-filter"></i> Filtres</h3>
                <button class="filters-reset-btn" onclick="resetFilters()">
                    <i class="fas fa-redo"></i> Réinitialiser
                </button>
            </div>

            <form method="GET" action="{{ route('monetization.courses') }}" id="filtersForm" class="filters-form">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                <!-- Filtre Prix -->
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-money-bill-wave"></i> Prix (FCFA)
                    </label>
                    <div class="price-range-inputs">
                        <input 
                            type="number" 
                            name="price_min" 
                            value="{{ request('price_min', $stats['min_price']) }}"
                            placeholder="Prix minimum (FCFA)" 
                            class="price-input"
                            min="0"
                        >
                        <input 
                            type="number" 
                            name="price_max" 
                            value="{{ request('price_max', $stats['max_price']) }}"
                            placeholder="Prix maximum (FCFA)" 
                            class="price-input"
                            min="0"
                        >
                    </div>
                    <div class="price-range-slider">
                        <input 
                            type="range" 
                            id="priceRange" 
                            min="{{ $stats['min_price'] }}" 
                            max="{{ $stats['max_price'] }}" 
                            value="{{ request('price_max', $stats['max_price']) }}"
                            class="range-slider"
                        >
                    </div>
                </div>

                <!-- Filtre Durée -->
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-clock"></i> Durée (heures)
                    </label>
                    <div class="duration-range-inputs">
                        <input 
                            type="number" 
                            name="duration_min" 
                            value="{{ request('duration_min', $stats['min_duration']) }}"
                            placeholder="Durée minimum (heures)" 
                            class="duration-input"
                            min="0"
                        >
                        <input 
                            type="number" 
                            name="duration_max" 
                            value="{{ request('duration_max', $stats['max_duration']) }}"
                            placeholder="Durée maximum (heures)" 
                            class="duration-input"
                            min="0"
                        >
                    </div>
                </div>

                <!-- Filtre Note -->
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-star"></i> Note minimum
                    </label>
                    <div class="rating-filter">
                        <input 
                            type="range" 
                            name="rating_min" 
                            value="{{ request('rating_min', 0) }}"
                            min="0" 
                            max="5" 
                            step="0.5"
                            class="rating-slider"
                        >
                        <div class="rating-display">
                            <span id="ratingValue">{{ request('rating_min', 0) }}</span>
                            <div class="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= (request('rating_min', 0) / 1) ? 'active' : '' }}"></i>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filtre Promotions -->
                <div class="filter-group">
                    <label class="filter-checkbox-label">
                        <input 
                            type="checkbox" 
                            name="discount_only" 
                            value="1"
                            {{ request('discount_only') == '1' ? 'checked' : '' }}
                            class="filter-checkbox"
                        >
                        <span class="filter-checkbox-custom"></span>
                        <span class="filter-checkbox-text">
                            <i class="fas fa-tag"></i> Promotions uniquement
                        </span>
                    </label>
                </div>

                <button type="submit" class="filters-apply-btn">
                    <i class="fas fa-check"></i> Appliquer les filtres
                </button>
            </form>
        </aside>

        <!-- Contenu Principal -->
        <main class="courses-main">
            <!-- Header avec tri et résultats -->
            <div class="courses-header">
                <div class="courses-results">
                    <span class="results-count">{{ $courses->total() }}</span>
                    <span class="results-text">cours trouvés</span>
                </div>
                
                <div class="courses-sort">
                    <label for="sortSelect" class="sort-label">
                        <i class="fas fa-sort"></i> Trier par :
                    </label>
                    <select name="sort" id="sortSelect" class="sort-select" onchange="updateSort(this.value)">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Plus récents</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Meilleures notes</option>
                        <option value="students" {{ request('sort') == 'students' ? 'selected' : '' }}>Plus populaires</option>
                        <option value="duration" {{ request('sort') == 'duration' ? 'selected' : '' }}>Plus longs</option>
                    </select>
                </div>
            </div>

            <!-- Grille de cours (4 par ligne) -->
            @if($courses->count() > 0)
            <div class="courses-grid">
                @foreach($courses as $course)
                <article class="course-card">
                    <a href="{{ route('monetization.course.show', $course->slug) }}" class="course-card-link">
                        <div class="course-card-inner">
                            <!-- Badge Promotion -->
                            @if($course->hasDiscount())
                            <div class="course-discount-badge">
                                <span class="discount-percent">-{{ $course->discount_percentage }}%</span>
                                <span class="discount-label">OFF</span>
                            </div>
                            @endif

                            <!-- Image du cours -->
                            <div class="course-image-wrapper">
                                @if($course->cover_image)
                                    @if(($course->cover_type ?? 'internal') === 'internal')
                                        <img src="{{ asset('storage/' . $course->cover_image) }}" alt="{{ $course->title }}" class="course-image">
                                    @else
                                        <img src="{{ $course->cover_image }}" alt="{{ $course->title }}" class="course-image" onerror="this.parentElement.innerHTML='<div class=\'course-image-placeholder\'><i class=\'fas fa-graduation-cap\'></i></div>'">
                                    @endif
                                @else
                                    <div class="course-image-placeholder">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                @endif
                                <div class="course-image-overlay">
                                    <div class="course-overlay-content">
                                        <span class="course-view-btn">Voir le cours</span>
                                        <i class="fas fa-arrow-right"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Contenu du cours -->
                            <div class="course-content">
                                <h3 class="course-title">{{ $course->title }}</h3>

                                <!-- Métadonnées -->
                                <div class="course-meta">
                                    @if($course->duration_hours)
                                    <div class="course-meta-item">
                                        <i class="fas fa-clock"></i>
                                        <span>{{ $course->duration_hours }}h</span>
                                    </div>
                                    @endif
                                    
                                    @if($course->students_count > 0)
                                    <div class="course-meta-item">
                                        <i class="fas fa-users"></i>
                                        <span>{{ number_format($course->students_count, 0, ',', ' ') }}</span>
                                    </div>
                                    @endif
                                    
                                    @if($course->rating > 0)
                                    <div class="course-meta-item course-rating">
                                        <i class="fas fa-star"></i>
                                        <span>{{ number_format($course->rating, 1) }}</span>
                                        @if($course->reviews_count > 0)
                                        <span class="reviews-count">({{ $course->reviews_count }})</span>
                                        @endif
                                    </div>
                                    @endif
                                </div>

                                <!-- Prix -->
                                <div class="course-price-section">
                                    @if($course->hasDiscount())
                                    <div class="course-price-discount">
                                        <span class="course-price-current">{{ number_format($course->discount_price, 0, ',', ' ') }} FCFA</span>
                                        <span class="course-price-old">{{ number_format($course->price, 0, ',', ' ') }} FCFA</span>
                                    </div>
                                    @else
                                    <div class="course-price-normal">
                                        <span class="course-price-current">{{ number_format($course->price, 0, ',', ' ') }} FCFA</span>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Effet shine -->
                            <div class="course-shine"></div>
                        </div>
                    </a>
                </article>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($courses->hasPages())
            <div class="courses-pagination">
                {{ $courses->links() }}
            </div>
            @endif

            @else
            <!-- État vide -->
            <div class="courses-empty">
                <div class="courses-empty-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3 class="courses-empty-title">Aucun cours trouvé</h3>
                <p class="courses-empty-text">
                    Essayez de modifier vos critères de recherche ou vos filtres
                </p>
                <a href="{{ route('monetization.courses') }}" class="courses-empty-btn">
                    <i class="fas fa-redo"></i> Réinitialiser
                </a>
            </div>
            @endif
        </main>
    </div>
</div>

<!-- Styles -->
<style>
    /* ============================================
       VARIABLES & BASE
       ============================================ */
    .courses-page {
        min-height: 100vh;
        background: white;
        position: relative;
        overflow-x: hidden;
    }

    .courses-page::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 50%, rgba(6, 182, 212, 0.15) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(20, 184, 166, 0.15) 0%, transparent 50%);
        pointer-events: none;
        animation: gradientShift 15s ease infinite;
    }

    @keyframes gradientShift {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.8; }
    }

    /* ============================================
       HERO SECTION
       ============================================ */
    .courses-hero {
        position: relative;
        padding: 80px 20px 60px;
        text-align: center;
        z-index: 1;
    }

    .courses-hero-content {
        max-width: 900px;
        margin: 0 auto;
    }

    .courses-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 24px;
        background: rgba(6, 182, 212, 0.15);
        border: 1px solid rgba(6, 182, 212, 0.3);
        border-radius: 50px;
        color: #06b6d4;
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 30px;
        backdrop-filter: blur(10px);
        animation: fadeInUp 0.6s ease;
    }

    .courses-hero-title {
        font-size: clamp(2.5rem, 5vw, 4rem);
        font-weight: 900;
        color: rgba(30, 41, 59, 0.95);
        margin-bottom: 20px;
        line-height: 1.2;
        animation: fadeInUp 0.8s ease;
    }

    .gradient-text {
        background: linear-gradient(135deg, #06b6d4, #14b8a6, #06b6d4);
        background-size: 200% 200%;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: gradientFlow 3s ease infinite;
    }

    @keyframes gradientFlow {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    .courses-hero-subtitle {
        font-size: 1.25rem;
        color: rgba(30, 41, 59, 0.7);
        margin-bottom: 40px;
        animation: fadeInUp 1s ease;
    }

    /* Barre de recherche */
    .courses-search-form {
        animation: fadeInUp 1.2s ease;
    }

    .courses-search-wrapper {
        display: flex;
        align-items: center;
        background: white;
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 16px;
        padding: 4px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .courses-search-wrapper:focus-within {
        border-color: #06b6d4;
        box-shadow: 0 0 30px rgba(6, 182, 212, 0.4);
    }

    .courses-search-icon {
        padding: 0 20px;
        color: rgba(6, 182, 212, 0.7);
        font-size: 1.1rem;
    }

    .courses-search-input {
        flex: 1;
        background: transparent;
        border: none;
        outline: none;
        color: rgba(30, 41, 59, 0.95);
        font-size: 1.1rem;
        padding: 16px 0;
    }

    .courses-search-input::placeholder {
        color: #94a3b8;
    }

    .courses-search-btn {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 28px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border: none;
        border-radius: 12px;
        color: white;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .courses-search-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(6, 182, 212, 0.5);
    }

    /* ============================================
       CONTAINER & LAYOUT
       ============================================ */
    .courses-container {
        max-width: 1600px;
        margin: 0 auto;
        padding: 0 20px 60px;
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 40px;
        position: relative;
        z-index: 1;
    }

    /* ============================================
       SIDEBAR FILTRES
       ============================================ */
    .courses-filters-sidebar {
        position: sticky;
        top: 100px;
        height: fit-content;
        background: white;
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 20px;
        padding: 25px;
        max-width: 100%;
        box-sizing: border-box;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }

    .filters-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid rgba(6, 182, 212, 0.2);
    }

    .filters-header h3 {
        font-size: 1.5rem;
        font-weight: 800;
        color: rgba(30, 41, 59, 0.95);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .filters-header h3 i {
        color: #06b6d4;
    }

    .filters-reset-btn {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        background: rgba(239, 68, 68, 0.2);
        border: 1px solid rgba(239, 68, 68, 0.4);
        border-radius: 8px;
        color: #ef4444;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .filters-reset-btn:hover {
        background: rgba(239, 68, 68, 0.3);
        transform: translateY(-2px);
    }

    .filter-group {
        margin-bottom: 30px;
    }

    .filter-label {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 700;
        color: rgba(30, 41, 59, 0.95);
        margin-bottom: 15px;
        font-size: 1rem;
    }

    .filter-label i {
        color: #06b6d4;
        font-size: 1.1rem;
    }

    .price-range-inputs,
    .duration-range-inputs {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 15px;
    }

    .price-input,
    .duration-input {
        width: 100%;
        padding: 12px 16px;
        background: #f8fafc;
        border: 1px solid rgba(6, 182, 212, 0.3);
        border-radius: 10px;
        color: rgba(30, 41, 59, 0.95);
        font-size: 1rem;
        outline: none;
        transition: all 0.3s ease;
        box-sizing: border-box;
    }

    .price-input:focus,
    .duration-input:focus {
        border-color: #06b6d4;
        box-shadow: 0 0 15px rgba(6, 182, 212, 0.3);
    }

    .price-separator,
    .duration-separator {
        display: none;
    }

    .range-slider,
    .rating-slider {
        width: 100%;
        height: 6px;
        background: #e2e8f0;
        border-radius: 10px;
        outline: none;
        -webkit-appearance: none;
    }

    .range-slider::-webkit-slider-thumb,
    .rating-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        width: 20px;
        height: 20px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(6, 182, 212, 0.5);
        transition: all 0.3s ease;
    }

    .range-slider::-webkit-slider-thumb:hover,
    .rating-slider::-webkit-slider-thumb:hover {
        transform: scale(1.2);
    }

    .rating-filter {
        margin-top: 15px;
    }

    .rating-display {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 15px;
        padding: 15px;
        background: #f8fafc;
        border-radius: 12px;
    }

    .rating-display span {
        font-size: 1.5rem;
        font-weight: 800;
        color: #fbbf24;
    }

    .rating-stars {
        display: flex;
        gap: 5px;
    }

    .rating-stars i {
        color: rgba(30, 41, 59, 0.2);
        font-size: 1.2rem;
        transition: all 0.3s ease;
    }

    .rating-stars i.active {
        color: #fbbf24;
    }

    .filter-checkbox-label {
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        padding: 15px;
        background: #f8fafc;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .filter-checkbox-label:hover {
        background: #f1f5f9;
    }

    .filter-checkbox {
        display: none;
    }

    .filter-checkbox-custom {
        width: 24px;
        height: 24px;
        border: 2px solid rgba(6, 182, 212, 0.5);
        border-radius: 6px;
        position: relative;
        transition: all 0.3s ease;
    }

    .filter-checkbox:checked + .filter-checkbox-custom {
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border-color: #06b6d4;
    }

    .filter-checkbox:checked + .filter-checkbox-custom::after {
        content: '✓';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-weight: 900;
        font-size: 0.9rem;
    }

    .filter-checkbox-text {
        color: rgba(30, 41, 59, 0.95);
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-checkbox-text i {
        color: #06b6d4;
    }

    .filters-apply-btn {
        width: 100%;
        padding: 16px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border: none;
        border-radius: 12px;
        color: white;
        font-weight: 700;
        font-size: 1.1rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.3s ease;
        margin-top: 20px;
    }

    .filters-apply-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(6, 182, 212, 0.5);
    }

    /* ============================================
       MAIN CONTENT
       ============================================ */
    .courses-main {
        min-height: 500px;
    }

    .courses-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
        padding: 25px 30px;
        background: white;
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }

    .courses-results {
        display: flex;
        align-items: baseline;
        gap: 8px;
    }

    .results-count {
        font-size: 2rem;
        font-weight: 900;
        color: #06b6d4;
    }

    .results-text {
        font-size: 1.1rem;
        color: rgba(30, 41, 59, 0.7);
        font-weight: 600;
    }

    .courses-sort {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .sort-label {
        color: rgba(30, 41, 59, 0.8);
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .sort-label i {
        color: #06b6d4;
    }

    .sort-select {
        padding: 12px 20px;
        background: #f8fafc;
        border: 1px solid rgba(6, 182, 212, 0.3);
        border-radius: 10px;
        color: rgba(30, 41, 59, 0.95);
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        outline: none;
        transition: all 0.3s ease;
    }

    .sort-select:focus {
        border-color: #06b6d4;
        box-shadow: 0 0 15px rgba(6, 182, 212, 0.3);
    }

    /* ============================================
       COURSES GRID (4 par ligne)
       ============================================ */
    .courses-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 30px;
        margin-bottom: 60px;
    }

    .course-card {
        position: relative;
    }

    .course-card-link {
        text-decoration: none;
        display: block;
        height: 100%;
    }

    .course-card-inner {
        position: relative;
        background: white;
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        height: 100%;
        display: flex;
        flex-direction: column;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }

    .course-card:hover .course-card-inner {
        transform: translateY(-8px);
        border-color: #06b6d4;
        box-shadow: 0 20px 60px rgba(6, 182, 212, 0.4);
    }

    .course-discount-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        z-index: 10;
        display: flex;
        flex-direction: column;
        align-items: center;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        padding: 8px 12px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.5);
    }

    .discount-percent {
        font-size: 1.2rem;
        font-weight: 900;
        color: white;
        line-height: 1;
    }

    .discount-label {
        font-size: 0.7rem;
        font-weight: 700;
        color: rgba(255, 255, 255, 0.9);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .course-image-wrapper {
        position: relative;
        width: 100%;
        height: 220px;
        overflow: hidden;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
    }

    .course-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .course-image-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .course-image-placeholder i {
        font-size: 4rem;
        color: rgba(255, 255, 255, 0.3);
    }

    .course-image-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(255, 255, 255, 0.95), transparent);
        opacity: 0;
        transition: opacity 0.4s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .course-card:hover .course-image-overlay {
        opacity: 1;
    }

    .course-card:hover .course-image {
        transform: scale(1.1);
    }

    .course-overlay-content {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px 28px;
        background: rgba(6, 182, 212, 0.2);
        border: 2px solid #06b6d4;
        border-radius: 12px;
        color: white;
        font-weight: 700;
        font-size: 1.1rem;
        backdrop-filter: blur(10px);
        transform: translateY(20px);
        transition: transform 0.4s ease;
    }

    .course-card:hover .course-overlay-content {
        transform: translateY(0);
    }

    .course-content {
        padding: 25px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .course-title {
        font-size: 1.3rem;
        font-weight: 800;
        color: rgba(30, 41, 59, 0.95);
        margin-bottom: 12px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .course-description {
        color: rgba(30, 41, 59, 0.7);
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 20px;
        flex: 1;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .course-meta {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 20px;
        padding-top: 20px;
        border-top: 1px solid rgba(6, 182, 212, 0.2);
        flex-wrap: wrap;
    }

    .course-meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
        color: rgba(30, 41, 59, 0.7);
        font-size: 0.9rem;
        font-weight: 600;
    }

    .course-meta-item i {
        color: #06b6d4;
        font-size: 1rem;
    }

    .course-rating {
        color: #fbbf24;
    }

    .course-rating i {
        color: #fbbf24;
    }

    .reviews-count {
        color: rgba(30, 41, 59, 0.5);
        font-size: 0.85rem;
    }

    .course-price-section {
        margin-top: auto;
    }

    .course-price-discount {
        display: flex;
        align-items: baseline;
        gap: 12px;
    }

    .course-price-current {
        font-size: 1.8rem;
        font-weight: 900;
        color: #06b6d4;
        line-height: 1;
    }

    .course-price-old {
        font-size: 1.1rem;
        color: rgba(30, 41, 59, 0.4);
        text-decoration: line-through;
    }

    .course-price-normal .course-price-current {
        font-size: 1.8rem;
        font-weight: 900;
        color: #06b6d4;
        line-height: 1;
    }

    .course-shine {
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(
            45deg,
            transparent 30%,
            rgba(255, 255, 255, 0.1) 50%,
            transparent 70%
        );
        transform: rotate(45deg);
        transition: all 0.6s ease;
        pointer-events: none;
    }

    .course-card:hover .course-shine {
        top: 50%;
        left: 50%;
    }

    /* ============================================
       PAGINATION
       ============================================ */
    .courses-pagination {
        display: flex;
        justify-content: center;
        margin-top: 60px;
    }

    .courses-pagination :deep(.pagination) {
        display: flex;
        gap: 10px;
        list-style: none;
        padding: 0;
    }

    .courses-pagination :deep(.page-item) {
        margin: 0;
    }

    .courses-pagination :deep(.page-link) {
        padding: 12px 20px;
        background: white;
        border: 1px solid rgba(6, 182, 212, 0.3);
        border-radius: 10px;
        color: rgba(30, 41, 59, 0.95);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .courses-pagination :deep(.page-link:hover) {
        background: rgba(6, 182, 212, 0.2);
        border-color: #06b6d4;
        transform: translateY(-2px);
    }

    .courses-pagination :deep(.page-item.active .page-link) {
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border-color: #06b6d4;
        color: white;
    }

    /* ============================================
       EMPTY STATE
       ============================================ */
    .courses-empty {
        text-align: center;
        padding: 100px 20px;
        background: white;
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }

    .courses-empty-icon {
        font-size: 5rem;
        color: rgba(6, 182, 212, 0.5);
        margin-bottom: 30px;
    }

    .courses-empty-title {
        font-size: 2rem;
        font-weight: 800;
        color: rgba(30, 41, 59, 0.95);
        margin-bottom: 15px;
    }

    .courses-empty-text {
        font-size: 1.1rem;
        color: rgba(30, 41, 59, 0.7);
        margin-bottom: 30px;
    }

    .courses-empty-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 16px 32px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border-radius: 12px;
        color: white;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .courses-empty-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(6, 182, 212, 0.5);
    }

    /* ============================================
       RESPONSIVE
       ============================================ */
    @media (max-width: 1400px) {
        .courses-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 1024px) {
        .courses-container {
            grid-template-columns: 1fr;
            gap: 30px;
        }

        .courses-filters-sidebar {
            position: relative;
            top: 0;
        }

        .courses-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .courses-hero {
            padding: 60px 20px 40px;
        }

        .courses-hero-title {
            font-size: 2rem;
        }

        .courses-search-wrapper {
            flex-direction: column;
            gap: 10px;
        }

        .courses-search-btn {
            width: 100%;
            justify-content: center;
        }

        .courses-header {
            flex-direction: column;
            gap: 20px;
            align-items: flex-start;
        }

        .courses-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .courses-container {
            padding: 0 15px 40px;
        }
    }

    /* ============================================
       DARK MODE ADAPTATIONS
       ============================================ */
    body.dark-mode .courses-page {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%) !important;
    }
    
    body.dark-mode .courses-page::before {
        opacity: 1;
    }
    
    body.dark-mode .courses-hero-badge {
        background: rgba(6, 182, 212, 0.15);
        border-color: rgba(6, 182, 212, 0.4);
        color: #06b6d4;
    }
    
    body.dark-mode .courses-hero-title {
        color: white;
    }
    
    body.dark-mode .courses-hero-subtitle {
        color: rgba(255, 255, 255, 0.75);
    }
    
    body.dark-mode .courses-search-wrapper {
        background: rgba(30, 41, 59, 0.8);
        border-color: rgba(6, 182, 212, 0.3);
    }
    
    body.dark-mode .courses-search-input {
        color: white;
    }
    
    body.dark-mode .courses-search-input::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }
    
    body.dark-mode .courses-filters-sidebar,
    body.dark-mode .courses-header {
        background: rgba(30, 41, 59, 0.7);
        border-color: rgba(6, 182, 212, 0.3);
    }
    
    body.dark-mode .filters-header h3,
    body.dark-mode .filter-label,
    body.dark-mode .filter-checkbox-text,
    body.dark-mode .sort-label {
        color: white;
    }
    
    body.dark-mode .price-input,
    body.dark-mode .duration-input,
    body.dark-mode .sort-select {
        background: rgba(15, 23, 42, 0.6);
        border-color: rgba(6, 182, 212, 0.3);
        color: white;
    }
    
    body.dark-mode .price-input::placeholder,
    body.dark-mode .duration-input::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }
    
    body.dark-mode .filter-checkbox {
        border-color: rgba(6, 182, 212, 0.3);
    }
    
    body.dark-mode .filter-checkbox:checked {
        background: #06b6d4;
        border-color: #06b6d4;
    }
    
    body.dark-mode .filters-reset-btn {
        background: rgba(239, 68, 68, 0.2);
        border-color: rgba(239, 68, 68, 0.4);
        color: #ef4444;
    }
    
    body.dark-mode .filters-reset-btn:hover {
        background: rgba(239, 68, 68, 0.3);
        border-color: #ef4444;
    }
    
    body.dark-mode .course-card-inner {
        background: rgba(30, 41, 59, 0.6);
        border-color: rgba(6, 182, 212, 0.2);
    }
    
    body.dark-mode .course-card:hover .course-card-inner {
        border-color: #06b6d4;
        box-shadow: 0 20px 60px rgba(6, 182, 212, 0.4);
    }
    
    body.dark-mode .course-title {
        color: white;
    }
    
    body.dark-mode .course-description {
        color: rgba(255, 255, 255, 0.7);
    }
    
    body.dark-mode .course-meta-item {
        color: rgba(255, 255, 255, 0.7);
    }
    
    body.dark-mode .course-meta {
        border-top-color: rgba(6, 182, 212, 0.2);
    }
    
    body.dark-mode .course-price-old {
        color: rgba(255, 255, 255, 0.4);
    }
    
    body.dark-mode .reviews-count {
        color: rgba(255, 255, 255, 0.5);
    }
    
    body.dark-mode .course-image-overlay {
        background: linear-gradient(to top, rgba(15, 23, 42, 0.95), transparent) !important;
    }
    
    body.light-mode .course-image-overlay {
        background: linear-gradient(to top, rgba(255, 255, 255, 0.95), transparent) !important;
    }
    
    body.dark-mode .course-shine {
        background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.1) 50%, transparent 70%);
    }
    
    body.dark-mode .courses-pagination :deep(.page-link) {
        background: rgba(30, 41, 59, 0.6);
        border-color: rgba(6, 182, 212, 0.3);
        color: white;
    }
    
    body.dark-mode .courses-pagination :deep(.page-link:hover) {
        background: rgba(6, 182, 212, 0.2);
        border-color: #06b6d4;
    }
    
    body.dark-mode .courses-empty {
        background: rgba(30, 41, 59, 0.6);
        border-color: rgba(6, 182, 212, 0.3);
    }
    
    body.dark-mode .courses-empty-title {
        color: white;
    }
    
    body.dark-mode .courses-empty-text {
        color: rgba(255, 255, 255, 0.7);
    }
    
    body.dark-mode .courses-empty-btn {
        background: rgba(6, 182, 212, 0.2);
        border-color: rgba(6, 182, 212, 0.4);
        color: #06b6d4;
    }
    
    body.dark-mode .courses-empty-btn:hover {
        background: rgba(6, 182, 212, 0.3);
        border-color: #06b6d4;
    }
    
    body.dark-mode .courses-empty-icon {
        background: rgba(6, 182, 212, 0.15);
        color: #06b6d4;
    }
    
    body.dark-mode .filter-checkbox-label {
        background: rgba(15, 23, 42, 0.4);
    }
    
    body.dark-mode .filter-checkbox-label:hover {
        background: rgba(15, 23, 42, 0.6);
    }
    
    body.dark-mode .filter-checkbox-custom {
        border-color: rgba(6, 182, 212, 0.5);
    }
    
    body.dark-mode .rating-display {
        background: rgba(15, 23, 42, 0.4);
    }
    
    body.dark-mode .rating-stars i {
        color: rgba(255, 255, 255, 0.2);
    }
    
    body.dark-mode .rating-stars i.active {
        color: #fbbf24;
    }
    
    body.dark-mode .range-slider,
    body.dark-mode .rating-slider {
        background: rgba(15, 23, 42, 0.6);
    }
    
    body.dark-mode .filters-header {
        border-bottom-color: rgba(6, 182, 212, 0.2);
    }
    
    body.dark-mode .results-text {
        color: rgba(255, 255, 255, 0.7);
    }
    
    body.dark-mode .filters-apply-btn {
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: white;
    }
    
    body.dark-mode .filters-apply-btn:hover {
        box-shadow: 0 8px 25px rgba(6, 182, 212, 0.5);
    }
    
    body.dark-mode .course-image-placeholder {
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
    }
    
    body.dark-mode .course-image-placeholder i {
        color: white;
        opacity: 0.4;
    }
    
    body.dark-mode .course-discount-badge {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.5);
    }
    
    body.dark-mode .course-overlay-content {
        background: rgba(6, 182, 212, 0.2);
        border-color: #06b6d4;
        color: white;
    }
    
    body.dark-mode .results-count {
        color: #06b6d4;
    }
    
    /* ============================================
       LIGHT MODE
       ============================================ */
    body.light-mode .courses-page {
        background: white !important;
    }

    body.light-mode .courses-page::before {
        opacity: 0.1;
    }
    
    body.dark-mode .courses-page::before {
        opacity: 1;
    }

    body.light-mode .courses-hero-badge {
        background: rgba(6, 182, 212, 0.1);
        border-color: rgba(6, 182, 212, 0.3);
        color: #06b6d4;
    }

    body.light-mode .courses-hero-title {
        color: #1e293b;
    }

    body.light-mode .courses-hero-subtitle {
        color: #64748b;
    }

    body.light-mode .courses-search-wrapper {
        background: white;
        border-color: rgba(6, 182, 212, 0.3);
    }

    body.light-mode .courses-search-input {
        color: #1e293b;
    }

    body.light-mode .courses-search-input::placeholder {
        color: #94a3b8;
    }

    body.light-mode .courses-filters-sidebar,
    body.light-mode .courses-header {
        background: white;
        border-color: rgba(6, 182, 212, 0.2);
    }

    body.light-mode .filters-header h3,
    body.light-mode .filter-label,
    body.light-mode .filter-checkbox-text,
    body.light-mode .sort-label {
        color: #1e293b;
    }

    body.light-mode .price-input,
    body.light-mode .duration-input,
    body.light-mode .sort-select {
        background: #f8fafc;
        border-color: rgba(6, 182, 212, 0.3);
        color: #1e293b;
    }

    body.light-mode .course-card-inner {
        background: white;
        border-color: rgba(6, 182, 212, 0.2);
    }

    body.light-mode .course-title {
        color: #1e293b;
    }

    body.light-mode .course-description {
        color: #64748b;
    }

    body.light-mode .course-meta-item {
        color: #64748b;
    }

    body.light-mode .courses-empty {
        background: white;
        border-color: rgba(6, 182, 212, 0.2);
    }

    body.light-mode .courses-empty-title {
        color: #1e293b;
    }

    body.light-mode .courses-empty-text {
        color: #64748b;
    }

    /* ============================================
       ANIMATIONS
       ============================================ */
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

    .course-card {
        animation: fadeInUp 0.6s ease;
        animation-fill-mode: both;
    }

    .course-card:nth-child(1) { animation-delay: 0.1s; }
    .course-card:nth-child(2) { animation-delay: 0.2s; }
    .course-card:nth-child(3) { animation-delay: 0.3s; }
    .course-card:nth-child(4) { animation-delay: 0.4s; }
    .course-card:nth-child(5) { animation-delay: 0.5s; }
    .course-card:nth-child(6) { animation-delay: 0.6s; }
    .course-card:nth-child(7) { animation-delay: 0.7s; }
    .course-card:nth-child(8) { animation-delay: 0.8s; }
</style>

<!-- JavaScript -->
<script>
    // Mise à jour du tri
    function updateSort(value) {
        const form = document.getElementById('filtersForm');
        const url = new URL(window.location.href);
        url.searchParams.set('sort', value);
        
        // Préserver les autres paramètres
        const formData = new FormData(form);
        for (const [key, val] of formData.entries()) {
            if (key !== 'sort') {
                url.searchParams.set(key, val);
            }
        }
        
        window.location.href = url.toString();
    }

    // Réinitialiser les filtres
    function resetFilters() {
        window.location.href = '{{ route("monetization.courses") }}';
    }

    // Mise à jour de l'affichage de la note
    document.addEventListener('DOMContentLoaded', function() {
        const ratingSlider = document.querySelector('input[name="rating_min"]');
        const ratingValue = document.getElementById('ratingValue');
        const ratingStars = document.querySelectorAll('.rating-stars i');

        if (ratingSlider) {
            ratingSlider.addEventListener('input', function() {
                const value = parseFloat(this.value);
                ratingValue.textContent = value;
                
                // Mettre à jour les étoiles
                ratingStars.forEach((star, index) => {
                    if (index < Math.floor(value)) {
                        star.classList.add('active');
                    } else {
                        star.classList.remove('active');
                    }
                });
            });
        }

        // Auto-submit sur changement de slider
        const sliders = document.querySelectorAll('.range-slider, .rating-slider');
        sliders.forEach(slider => {
            slider.addEventListener('change', function() {
                document.getElementById('filtersForm').submit();
            });
        });
    });
</script>
@endsection
