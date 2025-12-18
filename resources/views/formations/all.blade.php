@extends('layouts.app')

@section('title', trans('app.formations.title') . ' | NiangProgrammeur')
@section('meta_description', trans('app.formations.subtitle'))
@section('meta_keywords', 'formations programmation, cours développement web, apprendre HTML CSS JavaScript PHP, formations gratuites, tutoriels programmation')

@section('styles')
<style>
    /* Fonts chargées via preload dans le head - pas de @import bloquant */
    
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    :root {
        --primary-gradient: linear-gradient(135deg, #06b6d4 0%, #14b8a6 100%);
        --secondary-gradient: linear-gradient(135deg, #06b6d4 0%, #14b8a6 100%);
        --accent-gradient: linear-gradient(135deg, #14b8a6 0%, #06b6d4 100%);
        --dark-bg: #0a0a0f;
        --dark-card: rgba(15, 23, 42, 0.8);
        --light-bg: #ffffff;
        --light-card: rgba(255, 255, 255, 0.95);
        --text-primary: #1e293b;
        --text-secondary: #64748b;
        --border-color: rgba(6, 182, 212, 0.2);
        --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
        --shadow-md: 0 8px 24px rgba(0, 0, 0, 0.12);
        --shadow-lg: 0 16px 48px rgba(0, 0, 0, 0.16);
        --shadow-xl: 0 24px 64px rgba(0, 0, 0, 0.2);
    }
    
    body {
        font-family: 'Inter', sans-serif;
        background: var(--light-bg) !important;
        color: var(--text-primary) !important;
        overflow-x: hidden;
        line-height: 1.6;
    }
    
    body.dark-mode {
        background: var(--dark-bg) !important;
        color: #ffffff !important;
    }
    
    /* Hero Section Ultra Moderne */
    .hero-section {
        position: relative;
        padding: 80px 20px 50px;
        text-align: center;
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.85) 0%, rgba(30, 41, 59, 0.9) 100%),
                    url('https://images.unsplash.com/photo-1498050108023-c5249f4df085?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2072&q=80') center/cover no-repeat;
        background-attachment: fixed;
        background-size: cover;
        overflow: hidden;
        min-height: 35vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 30%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 70%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
        pointer-events: none;
    }
    
    .hero-section::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="60" height="60" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="60" height="60" patternUnits="userSpaceOnUse"><path d="M 60 0 L 0 0 0 60" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="60" height="60" fill="url(%23grid)"/></svg>');
        opacity: 0.4;
        pointer-events: none;
    }
    
    .hero-content {
        position: relative;
        z-index: 2;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .hero-title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: clamp(2.5rem, 6vw, 5rem);
        font-weight: 700;
        color: white;
        margin-bottom: 24px;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        animation: fadeInUp 0.8s ease;
        letter-spacing: -0.02em;
        line-height: 1.1;
    }
    
    .hero-subtitle {
        font-size: clamp(0.95rem, 2vw, 1.2rem);
        color: rgba(255, 255, 255, 0.95);
        margin-bottom: 32px;
        max-width: 1100px;
        margin-left: auto;
        margin-right: auto;
        animation: fadeInUp 1s ease;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        line-height: 1.6;
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
    
    /* Stats Section Moderne */
    .stats-section {
        margin: -60px auto 0;
        max-width: 1400px;
        padding: 0 20px;
        position: relative;
        z-index: 10;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 24px;
        margin-bottom: 80px;
    }
    
    .stat-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 24px;
        padding: 32px 24px;
        text-align: center;
        box-shadow: var(--shadow-lg);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
    }
    
    body.dark-mode .stat-card {
        background: rgba(15, 23, 42, 0.9);
        border-color: rgba(6, 182, 212, 0.3);
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--secondary-gradient);
        transform: scaleX(0);
        transition: transform 0.4s ease;
    }
    
    .stat-card:hover::before {
        transform: scaleX(1);
    }
    
    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-xl);
    }
    
    .stat-number {
        font-family: 'Space Grotesk', sans-serif;
        font-size: clamp(2rem, 4vw, 3.5rem);
        font-weight: 700;
        background: var(--secondary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 8px;
        display: block;
    }
    
    .stat-label {
        font-size: 0.95rem;
        color: var(--text-secondary);
        font-weight: 500;
    }
    
    body.dark-mode .stat-label {
        color: rgba(255, 255, 255, 0.7);
    }
    
    /* Filtres et Recherche Section */
    .filters-section {
        max-width: 1400px;
        margin: 0 auto 32px;
        padding: 0 20px;
    }
    
    .search-filter-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 24px;
        padding: 32px;
        box-shadow: var(--shadow-md);
        margin-bottom: 24px;
    }
    
    body.dark-mode .search-filter-container {
        background: rgba(15, 23, 42, 0.9);
        border-color: rgba(6, 182, 212, 0.3);
    }
    
    .search-box {
        position: relative;
        margin-bottom: 24px;
    }
    
    .search-input {
        width: 100%;
        padding: 16px 20px 16px 60px;
        font-size: 1rem;
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 16px;
        background: rgba(255, 255, 255, 1);
        color: #1e293b !important;
        transition: all 0.3s ease;
        font-family: 'Inter', sans-serif;
    }
    
    .search-input::placeholder {
        color: #64748b !important;
        opacity: 0.7;
    }
    
    body.dark-mode .search-input {
        background: rgba(30, 41, 59, 0.8);
        border-color: rgba(6, 182, 212, 0.3);
        color: #ffffff !important;
    }
    
    body.dark-mode .search-input::placeholder {
        color: rgba(255, 255, 255, 0.6) !important;
    }
    
    .search-input:focus {
        outline: none;
        border-color: #06b6d4;
        box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.1);
        background: rgba(255, 255, 255, 1);
        color: #1e293b !important;
    }
    
    body.dark-mode .search-input:focus {
        background: rgba(30, 41, 59, 0.9);
        border-color: #06b6d4;
        color: #ffffff !important;
    }
    
    .search-icon {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #06b6d4;
        font-size: 1.1rem;
        pointer-events: none;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 20px;
    }
    
    .filters-row {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        align-items: center;
    }
    
    .filter-group {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        flex: 1;
        min-width: 200px;
    }
    
    .filter-btn {
        padding: 6px 14px;
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.8);
        color: var(--text-primary);
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: 'Inter', sans-serif;
        white-space: nowrap;
    }
    
    body.dark-mode .filter-btn {
        background: rgba(30, 41, 59, 0.6);
        border-color: rgba(6, 182, 212, 0.3);
        color: rgba(255, 255, 255, 0.9);
    }
    
    .filter-btn:hover {
        border-color: #06b6d4;
        background: rgba(6, 182, 212, 0.1);
        transform: translateY(-2px);
    }
    
    .filter-btn.active {
        background: var(--secondary-gradient);
        border-color: transparent;
        color: white;
        box-shadow: var(--shadow-md);
    }
    
    body.dark-mode .filter-btn.active {
        background: var(--secondary-gradient);
        border-color: transparent;
        color: white;
        box-shadow: 0 4px 16px rgba(6, 182, 212, 0.4);
    }
    
    .clear-filters {
        padding: 10px 20px;
        border: 2px solid rgba(239, 68, 68, 0.3);
        border-radius: 12px;
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: 'Inter', sans-serif;
    }
    
    .clear-filters:hover {
        background: rgba(239, 68, 68, 0.2);
        border-color: #ef4444;
    }
    
    .results-count {
        margin-top: 20px;
        font-size: 0.95rem;
        color: var(--text-secondary);
        font-weight: 500;
    }
    
    body.dark-mode .results-count {
        color: rgba(255, 255, 255, 0.7);
    }
    
    /* Formations Grid Ultra Moderne */
    .formations-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px 60px;
    }
    
    .formations-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 32px;
        margin-top: 40px;
    }
    
    .formation-card {
        position: relative;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 28px;
        padding: 40px 32px;
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        cursor: pointer;
        overflow: hidden;
        box-shadow: var(--shadow-md);
        text-decoration: none;
        display: block;
        color: inherit;
        transform-style: preserve-3d;
    }
    
    body.dark-mode .formation-card {
        background: rgba(15, 23, 42, 0.9);
        border-color: rgba(6, 182, 212, 0.3);
    }
    
    .formation-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: var(--secondary-gradient);
        opacity: 0;
        transition: opacity 0.5s ease;
        z-index: 0;
    }
    
    .formation-card:hover::before {
        opacity: 0.05;
    }
    
    .formation-card::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(6, 182, 212, 0.1) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.5s ease;
        pointer-events: none;
    }
    
    .formation-card:hover::after {
        opacity: 1;
    }
    
    .formation-card:hover {
        transform: translateY(-12px) scale(1.02);
        border-color: rgba(6, 182, 212, 0.5);
        box-shadow: var(--shadow-xl);
    }
    
    .formation-card-content {
        position: relative;
        z-index: 1;
    }
    
    .formation-badge {
        position: absolute;
        top: 20px;
        right: 20px;
        background: var(--secondary-gradient);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        box-shadow: var(--shadow-md);
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .formation-icon-wrapper {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 24px;
        position: relative;
        transition: all 0.4s ease;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.1));
    }
    
    .formation-card:hover .formation-icon-wrapper {
        transform: scale(1.1) rotate(5deg);
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
    }
    
    .formation-icon {
        font-size: 3rem;
        transition: all 0.4s ease;
        position: relative;
        z-index: 1;
    }
    
    .formation-card:hover .formation-icon {
        transform: scale(1.15);
        filter: drop-shadow(0 4px 12px rgba(6, 182, 212, 0.4));
    }
    
    .formation-name {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 16px;
        color: var(--text-primary);
        line-height: 1.3;
        transition: color 0.3s ease;
    }
    
    body.dark-mode .formation-name {
        color: #ffffff;
    }
    
    .formation-description {
        color: var(--text-secondary);
        line-height: 1.7;
        margin-bottom: 24px;
        font-size: 0.95rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    body.dark-mode .formation-description {
        color: rgba(255, 255, 255, 0.7);
    }
    
    .formation-link {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: #06b6d4;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        margin-top: auto;
    }
    
    .formation-link i {
        transition: transform 0.3s ease;
    }
    
    .formation-card:hover .formation-link {
        color: #14b8a6;
        gap: 12px;
    }
    
    .formation-card:hover .formation-link i {
        transform: translateX(4px);
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        color: var(--text-secondary);
    }
    
    body.dark-mode .empty-state {
        color: rgba(255, 255, 255, 0.7);
    }
    
    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 24px;
        opacity: 0.5;
    }
    
    .empty-state-text {
        font-size: 1.2rem;
        font-weight: 500;
    }
    
    /* AdSense Compatible Zones */
    .adsense-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
        text-align: center;
    }
    
    .adsense-container:empty,
    .adsense-container:has(.adsense-banner:empty) {
        display: none;
    }
    
    .adsense-banner {
        background: rgba(255, 255, 255, 0.5);
        border: 1px dashed rgba(6, 182, 212, 0.3);
        border-radius: 16px;
        padding: 20px;
        min-height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-secondary);
        font-size: 0.9rem;
    }
    
    .adsense-banner:empty {
        display: none;
    }
    
    body.dark-mode .adsense-banner {
        background: rgba(15, 23, 42, 0.5);
        border-color: rgba(6, 182, 212, 0.3);
        color: rgba(255, 255, 255, 0.5);
    }
    
    /* Responsive Design */
    @media (max-width: 1024px) {
        .formations-grid {
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 24px;
        }
    }
    
        @media (max-width: 768px) {
        .hero-section {
            padding: 70px 20px 40px;
            min-height: 30vh;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }
        
        .formations-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .formation-card {
            padding: 32px 24px;
        }
        
        .search-filter-container {
            padding: 24px;
        }
        
        .filters-row {
            flex-direction: column;
            align-items: stretch;
        }
        
        .filter-group {
            min-width: 100%;
        }
    }
    
    /* Loading Animation */
    .loading-shimmer {
        background: linear-gradient(90deg, 
            rgba(255, 255, 255, 0.1) 0%, 
            rgba(255, 255, 255, 0.2) 50%, 
            rgba(255, 255, 255, 0.1) 100%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
    }
    
    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }
    
    /* Smooth Transitions */
    .formation-card {
        animation: fadeInScale 0.6s ease forwards;
        opacity: 0;
    }
    
    @keyframes fadeInScale {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-content">
        <h1 class="hero-title">
            <i class="fas fa-graduation-cap" style="margin-right: 15px;"></i>
            {{ trans('app.formations.title') }}
        </h1>
        <p class="hero-subtitle">
            {{ trans('app.formations.subtitle') }} {{ trans('app.formations.subtitle_rest') }}
        </p>
    </div>
</section>

<!-- Stats Section -->
<div class="stats-section">
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ count($formations) }}+</div>
            <div class="stat-label">{{ trans('app.formations.stats.formations') }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">100+</div>
            <div class="stat-label">{{ trans('app.formations.stats.exercices') }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">24/7</div>
            <div class="stat-label">{{ trans('app.formations.stats.available') }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">100%</div>
            <div class="stat-label">{{ trans('app.formations.stats.free') }}</div>
        </div>
    </div>
</div>

<!-- Filters and Search Section -->
<div class="filters-section">
    <div class="search-filter-container">
        <div class="search-box">
            <i class="fas fa-search search-icon"></i>
            <input 
                type="text" 
                id="searchInput" 
                class="search-input" 
                placeholder="{{ trans('app.formations.search_placeholder') }}"
                autocomplete="off"
                style="padding-left: 60px;"
            >
        </div>
        
        <div class="filters-row">
            <div class="filter-group">
                <button class="filter-btn active" data-filter="all">
                    {{ trans('app.formations.filter_all') }}
                </button>
                <button class="filter-btn" data-filter="frontend">
                    {{ trans('app.formations.filter_frontend') }}
                </button>
                <button class="filter-btn" data-filter="backend">
                    {{ trans('app.formations.filter_backend') }}
                </button>
                <button class="filter-btn" data-filter="database">
                    {{ trans('app.formations.filter_database') }}
                </button>
                <button class="filter-btn" data-filter="tools">
                    {{ trans('app.formations.filter_tools') }}
                </button>
                <button class="filter-btn" data-filter="ai">
                    {{ trans('app.formations.filter_ai') }}
                </button>
                <button class="filter-btn" data-filter="security">
                    {{ trans('app.formations.filter_security') }}
                </button>
                <button class="filter-btn" data-filter="data">
                    {{ trans('app.formations.filter_data') }}
                </button>
            </div>
            <button class="clear-filters" id="clearFilters">
                <i class="fas fa-times"></i> {{ trans('app.formations.clear_filters') }}
            </button>
        </div>
        
        <div class="results-count" id="resultsCount">
            <span id="resultsText">{{ count($formations) }} {{ trans('app.formations.results_found') }}</span>
        </div>
    </div>
</div>

<!-- Formations Grid -->
<div class="formations-container">
    <div class="formations-grid" id="formationsGrid">
        @foreach($formations as $index => $formation)
        <a 
            href="{{ $formation['route'] }}" 
            class="formation-card" 
            data-name="{{ strtolower($formation['name']) }}"
            data-description="{{ strtolower($formation['description']) }}"
            data-category="{{ $formation['category'] ?? 'all' }}"
            style="animation-delay: {{ $index * 0.1 }}s;"
        >
            <div class="formation-badge">
                <i class="fas fa-star"></i> {{ trans('app.formations.available') }}
            </div>
            <div class="formation-card-content">
                <div class="formation-icon-wrapper">
                    <i class="{{ $formation['icon'] }} formation-icon" style="color: {{ $formation['color'] }};"></i>
                </div>
                <h3 class="formation-name">{{ $formation['name'] }}</h3>
                <p class="formation-description">{{ $formation['description'] }}</p>
                <span class="formation-link">
                    {{ trans('app.formations.start_learning') }}
                    <i class="fas fa-arrow-right"></i>
                </span>
            </div>
        </a>
        @endforeach
    </div>
    
    <!-- Empty State -->
    <div class="empty-state" id="emptyState" style="display: none;">
        <div class="empty-state-icon">
            <i class="fas fa-search"></i>
        </div>
        <div class="empty-state-text">
            {{ trans('app.formations.no_results') }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const filterButtons = document.querySelectorAll('.filter-btn');
        const clearFiltersBtn = document.getElementById('clearFilters');
        const formationCards = document.querySelectorAll('.formation-card');
        const formationsGrid = document.getElementById('formationsGrid');
        const emptyState = document.getElementById('emptyState');
        const resultsText = document.getElementById('resultsText');
        
        let activeFilter = 'all';
        let searchQuery = '';
        
        // Category mapping
        const categoryMap = {
            'html5': 'frontend',
            'css3': 'frontend',
            'javascript': 'frontend',
            'typescript': 'frontend',
            'bootstrap': 'frontend',
            'php': 'backend',
            'python': 'backend',
            'java': 'backend',
            'c': 'backend',
            'cpp': 'backend',
            'csharp': 'backend',
            'dart': 'backend',
            'go': 'backend',
            'rust': 'backend',
            'ruby': 'backend',
            'swift': 'backend',
            'perl': 'backend',
            'sql': 'database',
            'git': 'tools',
            'wordpress': 'tools',
            'ia': 'ai',
            'cybersecurite': 'security',
            'data-science': 'data',
            'big-data': 'data'
        };
        
        // Add category data to cards
        formationCards.forEach(card => {
            const slug = card.getAttribute('href').split('/').pop();
            const category = categoryMap[slug] || 'all';
            card.setAttribute('data-category', category);
        });
        
        // Filter function
        function filterFormations() {
            let visibleCount = 0;
            
            formationCards.forEach((card, index) => {
                const name = card.getAttribute('data-name') || '';
                const description = card.getAttribute('data-description') || '';
                const category = card.getAttribute('data-category') || 'all';
                
                const matchesSearch = !searchQuery || 
                    name.includes(searchQuery.toLowerCase()) || 
                    description.includes(searchQuery.toLowerCase());
                
                const matchesFilter = activeFilter === 'all' || category === activeFilter;
                
                if (matchesSearch && matchesFilter) {
                    card.style.display = 'block';
                    card.style.animationDelay = `${visibleCount * 0.05}s`;
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Show/hide empty state
            if (visibleCount === 0) {
                emptyState.style.display = 'block';
                formationsGrid.style.display = 'none';
            } else {
                emptyState.style.display = 'none';
                formationsGrid.style.display = 'grid';
            }
            
            // Update results count
            const resultsCount = visibleCount;
            const totalCount = {{ count($formations) }};
            resultsText.textContent = `${resultsCount} {{ trans('app.formations.results_found') }}`;
        }
        
        // Search input handler
        searchInput.addEventListener('input', function(e) {
            searchQuery = e.target.value.trim();
            filterFormations();
        });
        
        // Filter button handlers
        filterButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                filterButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                activeFilter = this.getAttribute('data-filter');
                filterFormations();
            });
        });
        
        // Clear filters handler
        clearFiltersBtn.addEventListener('click', function() {
            searchInput.value = '';
            searchQuery = '';
            filterButtons.forEach(b => b.classList.remove('active'));
            document.querySelector('[data-filter="all"]').classList.add('active');
            activeFilter = 'all';
            filterFormations();
        });
        
        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'scale(1)';
                }
            });
        }, observerOptions);
        
        formationCards.forEach(card => {
            observer.observe(card);
        });
        
        // Initialize
        filterFormations();
    });
</script>

<!-- Structured Data for SEO -->
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'CollectionPage',
    'name' => trans('app.formations.title'),
    'description' => trans('app.formations.subtitle'),
    'url' => url('/formations'),
    'mainEntity' => [
        '@type' => 'ItemList',
        'itemListElement' => collect($formations)->map(function($formation, $index) {
            return [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'item' => [
                    '@type' => 'Course',
                    'name' => $formation['name'],
                    'description' => $formation['description'],
                    'url' => $formation['route'],
                    'provider' => [
                        '@type' => 'Organization',
                        'name' => 'NiangProgrammeur',
                        'url' => url('/')
                    ]
                ]
            ];
        })->toArray()
    ]
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endsection
