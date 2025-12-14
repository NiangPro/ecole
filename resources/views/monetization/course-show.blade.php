@extends('layouts.app')

@section('title', $course->localized_title . ' - NiangProgrammeur')

@section('content')
<div class="course-detail-page">
    <!-- Hero Section avec Image -->
    <div class="course-hero">
        <div class="course-hero-background">
            @if($course->cover_image)
                @if(($course->cover_type ?? 'internal') === 'internal')
                    <img src="{{ asset('storage/' . $course->cover_image) }}" alt="{{ $course->localized_title }}" class="course-hero-image">
                @else
                    <img src="{{ $course->cover_image }}" alt="{{ $course->localized_title }}" class="course-hero-image" onerror="this.parentElement.innerHTML='<div class=\'course-hero-placeholder\'></div>'">
                @endif
            @else
                <div class="course-hero-placeholder"></div>
            @endif
            <div class="course-hero-overlay"></div>
        </div>
        
        <div class="course-hero-content">
            <div class="course-hero-container">
                <!-- Badge Promotion -->
                @if($course->hasDiscount())
                <div class="course-hero-badge">
                    <span class="badge-icon">🔥</span>
                    <span class="badge-text">PROMOTION -{{ $course->discount_percentage }}%</span>
                </div>
                @endif

                <!-- Titre et Description -->
                <div class="course-hero-text">
                    <h1 class="course-hero-title">{{ $course->localized_title }}</h1>
                    @if($course->localized_description)
                    <p class="course-hero-description">{{ $course->localized_description }}</p>
                    @endif
                </div>

                <!-- Stats -->
                <div class="course-hero-stats">
                    @if($course->rating > 0)
                    <div class="course-stat-item">
                        <div class="stat-icon stat-rating">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ number_format($course->rating, 1) }}</div>
                            <div class="stat-label">{{ $course->reviews_count }} avis</div>
                        </div>
                    </div>
                    @endif

                    @if($course->students_count > 0)
                    <div class="course-stat-item">
                        <div class="stat-icon stat-students">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ number_format($course->students_count, 0, ',', ' ') }}</div>
                            <div class="stat-label">Étudiants</div>
                        </div>
                    </div>
                    @endif

                    @if($course->duration_hours)
                    <div class="course-stat-item">
                        <div class="stat-icon stat-duration">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ $course->duration_hours }}h</div>
                            <div class="stat-label">Durée</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="course-main-container">
        <div class="course-main-grid">
            <!-- Contenu Principal - Sections Gauche/Droite -->
            <main class="course-main-content">
                <div class="course-sections-layout">
                    <!-- Colonne de Gauche -->
                    <div class="course-sections-left">
                        <!-- Card d'Achat -->
                        <div class="course-purchase-card">
                            @if($hasPurchased || $isPremium)
                            <div class="purchase-status">
                                <div class="purchase-status-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="purchase-status-content">
                                    <h3 class="purchase-status-title">
                                        @if($hasPurchased)
                                        Cours acheté
                                        @else
                                        Accès Premium
                                        @endif
                                    </h3>
                                    <p class="purchase-status-text">
                                        @if($hasPurchased)
                                        Vous avez déjà accès à ce cours
                                        @else
                                        Ce cours est inclus dans votre abonnement
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('dashboard.paid-courses.show', $course->id) }}" class="course-access-btn">
                                <i class="fas fa-play"></i>
                                <span>Accéder au cours</span>
                            </a>
                            @else
                            <div class="purchase-pricing">
                                @if($course->hasDiscount())
                                <div class="pricing-discount">
                                    <span class="pricing-old">{{ number_format($course->price, 0, ',', ' ') }} FCFA</span>
                                    <span class="pricing-badge">-{{ $course->discount_percentage }}%</span>
                                </div>
                                <div class="pricing-current">
                                    <span class="pricing-amount">{{ number_format($course->current_price, 0, ',', ' ') }}</span>
                                    <span class="pricing-currency">FCFA</span>
                                </div>
                                <div class="pricing-savings">
                                    <i class="fas fa-piggy-bank"></i>
                                    <span>Économisez {{ number_format($course->price - $course->current_price, 0, ',', ' ') }} FCFA</span>
                                </div>
                                @else
                                <div class="pricing-current">
                                    <span class="pricing-amount">{{ number_format($course->price, 0, ',', ' ') }}</span>
                                    <span class="pricing-currency">FCFA</span>
                                </div>
                                @endif
                            </div>

                            @auth
                            <form action="{{ route('payment.course', $course->id) }}" method="POST" class="purchase-form">
                                @csrf
                                <input type="hidden" name="payment_method" value="mobile_money">
                                @if(request()->has('ref'))
                                <input type="hidden" name="ref_code" value="{{ request()->get('ref') }}">
                                @endif
                                <button type="submit" class="course-purchase-btn">
                                    <i class="fas fa-shopping-cart"></i>
                                    <span>Acheter maintenant</span>
                                </button>
                            </form>
                            @else
                            <a href="{{ route('login') }}" class="course-purchase-btn">
                                <i class="fas fa-sign-in-alt"></i>
                                <span>Se connecter pour acheter</span>
                            </a>
                            @endauth

                            <div class="purchase-guarantee">
                                <i class="fas fa-shield-alt"></i>
                                <span>Garantie satisfait ou remboursé</span>
                            </div>
                            @endif
                        </div>

                        <!-- Section 1: Ce que vous allez apprendre -->
                        @if($course->what_you_learn && count($course->what_you_learn) > 0)
                        <section class="course-section-modern learn-section">
                            <div class="section-modern-header">
                                <div class="section-modern-icon-wrapper">
                                    <div class="section-modern-icon learn-icon">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                </div>
                                <h2 class="section-modern-title">Ce que vous allez apprendre</h2>
                                <p class="section-modern-subtitle">Compétences que vous maîtriserez</p>
                            </div>
                            <div class="section-modern-content">
                                <div class="learn-items-modern">
                                    @foreach($course->what_you_learn as $index => $item)
                                    <div class="learn-item-modern" style="animation-delay: {{ $index * 0.1 }}s">
                                        <div class="learn-item-modern-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <span class="learn-item-modern-text">{{ $item }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </section>
                        @endif

                        <!-- Section 2: Prérequis -->
                        @if($course->requirements && count($course->requirements) > 0)
                        <section class="course-section-modern requirements-section">
                            <div class="section-modern-header">
                                <div class="section-modern-icon-wrapper">
                                    <div class="section-modern-icon requirements-icon">
                                        <i class="fas fa-clipboard-check"></i>
                                    </div>
                                </div>
                                <h2 class="section-modern-title">Prérequis</h2>
                                <p class="section-modern-subtitle">Ce dont vous avez besoin</p>
                            </div>
                            <div class="section-modern-content">
                                <div class="requirements-modern">
                                    @foreach($course->requirements as $index => $requirement)
                                    <div class="requirement-item-modern" style="animation-delay: {{ $index * 0.1 }}s">
                                        <div class="requirement-item-modern-badge">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <span class="requirement-item-modern-text">{{ $requirement }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </section>
                        @endif

                    </div>

                    <!-- Colonne de Droite - Programme -->
                    <div class="course-sections-right">
                        <section class="course-section-modern program-section">
                            <div class="section-modern-header">
                                <div class="section-modern-icon-wrapper">
                                    <div class="section-modern-icon program-icon">
                                        <i class="fas fa-list-ul"></i>
                                    </div>
                                </div>
                                <h2 class="section-modern-title">Programme du cours</h2>
                                <p class="section-modern-subtitle">Structure de la formation</p>
                            </div>
                            <div class="section-modern-content">
                                @if($course->chapters && $course->chapters->count() > 0)
                                <div class="program-timeline">
                                    @foreach($course->chapters as $index => $chapter)
                                    <div class="program-module" data-chapter-id="{{ $chapter->id }}">
                                        <div class="program-module-number">{{ $index + 1 }}</div>
                                        <div class="program-module-content">
                                            <h3 class="program-module-title">{{ $chapter->title }}</h3>
                                            @if($chapter->description)
                                            <p class="program-module-description">{{ $chapter->description }}</p>
                                            @endif
                                            <div class="program-module-meta">
                                                @if($chapter->duration_minutes)
                                                <span class="program-module-duration">
                                                    <i class="fas fa-clock"></i>
                                                    {{ $chapter->duration_minutes }} min
                                                </span>
                                                @endif
                                            </div>
                                            @if($chapter->content)
                                            <div class="program-module-content-toggle" onclick="toggleChapterContent({{ $chapter->id }})">
                                                <i class="fas fa-chevron-down"></i>
                                                <span>Voir le contenu</span>
                                            </div>
                                            <div class="program-module-full-content" id="chapter-content-{{ $chapter->id }}" style="display: none;">
                                                <div class="program-module-full-content-inner">
                                                    {!! nl2br(e($chapter->content)) !!}
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="program-module-arrow">
                                            <i class="fas fa-chevron-right"></i>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <div class="course-empty-state-modern">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-list-ul"></i>
                                    </div>
                                    <h3>Programme en préparation</h3>
                                    <p>Le programme détaillé du cours sera disponible prochainement.</p>
                                </div>
                                @endif
                            </div>
                        </section>

                        <!-- Formations Payantes Recommandées -->
                        @if(isset($relatedCourses) && $relatedCourses->count() > 0)
                        <section class="course-section-modern related-courses-section">
                            <div class="section-modern-header">
                                <div class="section-modern-icon-wrapper">
                                    <div class="section-modern-icon related-icon">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                </div>
                                <h2 class="section-modern-title">Autres formations</h2>
                                <p class="section-modern-subtitle">Découvrez nos autres cours</p>
                            </div>
                            <div class="section-modern-content">
                                <div class="related-courses-list">
                                    @foreach($relatedCourses as $index => $relatedCourse)
                                    <a href="{{ route('monetization.course.show', $relatedCourse->slug) }}" class="related-course-item">
                                        <div class="related-course-thumbnail">
                                            @if($relatedCourse->cover_image)
                                                @if(($relatedCourse->cover_type ?? 'internal') === 'internal')
                                                    <img src="{{ asset('storage/' . $relatedCourse->cover_image) }}" alt="{{ $relatedCourse->title }}" class="related-course-thumbnail-image" onerror="this.parentElement.innerHTML='<div class=\'related-course-thumbnail-placeholder\'><i class=\'fas fa-graduation-cap\'></i></div><div class=\'related-course-number\'>{{ $index + 1 }}</div>'">
                                                @else
                                                    <img src="{{ $relatedCourse->cover_image }}" alt="{{ $relatedCourse->title }}" class="related-course-thumbnail-image" onerror="this.parentElement.innerHTML='<div class=\'related-course-thumbnail-placeholder\'><i class=\'fas fa-graduation-cap\'></i></div><div class=\'related-course-number\'>{{ $index + 1 }}</div>'">
                                                @endif
                                            @else
                                                <div class="related-course-thumbnail-placeholder">
                                                    <i class="fas fa-graduation-cap"></i>
                                                </div>
                                            @endif
                                            <div class="related-course-number">{{ $index + 1 }}</div>
                                        </div>
                                        <div class="related-course-info">
                                            <h3 class="related-course-info-title">{{ $relatedCourse->title }}</h3>
                                            <div class="related-course-info-meta">
                                                @if($relatedCourse->duration_hours)
                                                <span class="related-course-info-duration">{{ $relatedCourse->duration_hours }}h</span>
                                                @endif
                                                @if($relatedCourse->hasDiscount())
                                                <span class="related-course-info-price">{{ number_format($relatedCourse->current_price, 0, ',', ' ') }} FCFA</span>
                                                @else
                                                <span class="related-course-info-price">{{ number_format($relatedCourse->price, 0, ',', ' ') }} FCFA</span>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                        </section>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>

<!-- Styles -->
<style>
    /* ============================================
       BASE & VARIABLES
       ============================================ */
    .course-detail-page {
        min-height: 100vh;
        background: linear-gradient(135deg, #f8fafc 0%, #ffffff 50%, #f8fafc 100%);
        position: relative;
        overflow-x: hidden;
        transition: background 0.3s ease;
    }

    body.dark-mode .course-detail-page {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
    }

    .course-detail-page::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 30%, rgba(6, 182, 212, 0.05) 0%, transparent 50%),
            radial-gradient(circle at 80% 70%, rgba(20, 184, 166, 0.05) 0%, transparent 50%);
        pointer-events: none;
        z-index: 0;
        animation: gradientPulse 20s ease infinite;
    }

    body.dark-mode .course-detail-page::before {
        background: 
            radial-gradient(circle at 20% 30%, rgba(6, 182, 212, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 70%, rgba(20, 184, 166, 0.1) 0%, transparent 50%);
    }

    @keyframes gradientPulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    /* ============================================
       HERO SECTION
       ============================================ */
    .course-hero {
        position: relative;
        min-height: 500px;
        display: flex;
        align-items: flex-end;
        padding: 0;
        margin-bottom: 60px;
        overflow: hidden;
    }

    .course-hero-background {
        position: absolute;
        inset: 0;
        z-index: 1;
    }

    .course-hero-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: brightness(0.4);
    }

    .course-hero-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
    }

    .course-hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(
            to bottom,
            transparent 0%,
            rgba(15, 23, 42, 0.3) 50%,
            rgba(15, 23, 42, 0.95) 100%
        );
    }

    .course-hero-content {
        position: relative;
        z-index: 2;
        width: 100%;
        padding: 60px 20px 40px;
    }

    .course-hero-container {
        max-width: 1400px;
        margin: 0 auto;
    }

    .course-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 24px;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        border-radius: 50px;
        color: white;
        font-weight: 700;
        font-size: 0.95rem;
        margin-bottom: 25px;
        box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
        animation: fadeInDown 0.6s ease;
    }

    .badge-icon {
        font-size: 1.2rem;
        animation: pulse 2s ease infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    .course-hero-text {
        margin-bottom: 40px;
        animation: fadeInUp 0.8s ease;
    }

    .course-hero-title {
        font-size: clamp(2.5rem, 5vw, 4rem);
        font-weight: 900;
        color: white;
        margin-bottom: 20px;
        line-height: 1.2;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
    }

    .course-hero-description {
        font-size: 1.3rem;
        color: rgba(255, 255, 255, 0.9);
        line-height: 1.7;
        max-width: 800px;
    }

    .course-hero-stats {
        display: flex;
        gap: 30px;
        flex-wrap: wrap;
        animation: fadeInUp 1s ease;
    }

    .course-stat-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 20px 30px;
        background: rgba(30, 41, 59, 0.8);
        border: 1px solid rgba(6, 182, 212, 0.3);
        border-radius: 16px;
        backdrop-filter: blur(20px);
        transition: all 0.3s ease;
    }

    .course-stat-item:hover {
        transform: translateY(-5px);
        border-color: #06b6d4;
        box-shadow: 0 10px 30px rgba(6, 182, 212, 0.3);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .stat-rating {
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        color: white;
    }

    .stat-students {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .stat-duration {
        background: linear-gradient(135deg, #06b6d4, #0891b2);
        color: white;
    }

    .stat-content {
        display: flex;
        flex-direction: column;
    }

    .stat-value {
        font-size: 1.8rem;
        font-weight: 900;
        color: white;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.7);
        margin-top: 5px;
    }

    /* ============================================
       MAIN CONTAINER
       ============================================ */
    .course-main-container {
        position: relative;
        z-index: 1;
        padding: 0 20px 60px;
    }

    .course-main-grid {
        max-width: 1600px;
        margin: 0 auto;
    }

    /* ============================================
       PURCHASE CARD
       ============================================ */
    .course-purchase-card {
        background: white;
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 24px;
        padding: 35px;
        backdrop-filter: blur(20px);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        animation: fadeInUp 0.8s ease;
    }

    body.dark-mode .course-purchase-card {
        background: rgba(30, 41, 59, 0.8);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    .purchase-status {
        text-align: center;
        padding: 30px 20px;
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(5, 150, 105, 0.2));
        border: 2px solid #10b981;
        border-radius: 16px;
        margin-bottom: 25px;
    }

    .purchase-status-icon {
        font-size: 4rem;
        color: #10b981;
        margin-bottom: 15px;
        animation: scaleIn 0.5s ease;
    }

    .purchase-status-title {
        font-size: 1.4rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 8px;
    }

    body.dark-mode .purchase-status-title {
        color: white;
    }

    .purchase-status-text {
        font-size: 1rem;
        color: #475569;
    }

    body.dark-mode .purchase-status-text {
        color: rgba(255, 255, 255, 0.8);
    }

    .purchase-pricing {
        text-align: center;
        margin-bottom: 30px;
    }

    .pricing-discount {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        margin-bottom: 15px;
    }

    .pricing-old {
        font-size: 1.1rem;
        color: #94a3b8;
        text-decoration: line-through;
    }

    body.dark-mode .pricing-old {
        color: rgba(255, 255, 255, 0.5);
    }

    .pricing-badge {
        padding: 6px 14px;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        border-radius: 8px;
        color: white;
        font-weight: 700;
        font-size: 0.9rem;
    }

    .pricing-current {
        display: flex;
        align-items: baseline;
        justify-content: center;
        gap: 8px;
        margin-bottom: 15px;
    }

    .pricing-amount {
        font-size: 3rem;
        font-weight: 900;
        color: #06b6d4;
        line-height: 1;
    }

    .pricing-currency {
        font-size: 1.3rem;
        font-weight: 700;
        color: #64748b;
    }

    body.dark-mode .pricing-currency {
        color: rgba(255, 255, 255, 0.7);
    }

    .pricing-savings {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 20px;
        background: rgba(16, 185, 129, 0.2);
        border: 1px solid #10b981;
        border-radius: 10px;
        color: #10b981;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .purchase-form {
        margin-bottom: 15px;
    }

    .course-access-btn,
    .course-purchase-btn {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        padding: 18px 30px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border: none;
        border-radius: 14px;
        color: white;
        font-size: 1.1rem;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 8px 25px rgba(6, 182, 212, 0.4);
        position: relative;
        overflow: hidden;
    }

    .course-access-btn::before,
    .course-purchase-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.6s ease;
    }

    .course-access-btn:hover::before,
    .course-purchase-btn:hover::before {
        left: 100%;
    }

    .course-access-btn:hover,
    .course-purchase-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(6, 182, 212, 0.6);
    }

    .course-access-btn i,
    .course-purchase-btn i {
        font-size: 1.2rem;
    }

    .purchase-guarantee {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-top: 20px;
        padding: 15px;
        background: rgba(6, 182, 212, 0.08);
        border-radius: 12px;
        color: #475569;
        font-size: 0.9rem;
        font-weight: 600;
    }

    body.dark-mode .purchase-guarantee {
        background: rgba(6, 182, 212, 0.1);
        color: rgba(255, 255, 255, 0.8);
    }

    .purchase-guarantee i {
        color: #06b6d4;
    }

    /* ============================================
       MAIN CONTENT - SECTIONS GAUCHE/DROITE
       ============================================ */
    .course-main-content {
        display: flex;
        flex-direction: column;
    }

    .course-sections-layout {
        display: grid;
        grid-template-columns: 1fr 550px;
        gap: 30px;
        align-items: start;
    }

    .course-sections-left {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    .course-sections-right {
        position: sticky;
        top: 120px;
        height: fit-content;
    }

    /* ============================================
       SECTIONS MODERNES
       ============================================ */
    .course-section-modern {
        background: white;
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 24px;
        padding: 35px;
        backdrop-filter: blur(20px);
        position: relative;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        animation: fadeInUp 0.8s ease;
        animation-fill-mode: both;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    body.dark-mode .course-section-modern {
        background: rgba(30, 41, 59, 0.7);
        border-color: rgba(6, 182, 212, 0.2);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .course-section-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #06b6d4, #14b8a6, #06b6d4);
        background-size: 200% 100%;
        animation: gradientFlow 3s ease infinite;
    }

    .course-section-modern:hover {
        transform: translateY(-8px);
        border-color: #06b6d4;
        box-shadow: 0 20px 60px rgba(6, 182, 212, 0.3);
    }

    .course-section-modern:nth-child(1) { animation-delay: 0.1s; }
    .course-section-modern:nth-child(2) { animation-delay: 0.2s; }
    .course-section-modern:nth-child(3) { animation-delay: 0.3s; }
    .course-section-modern:nth-child(4) { animation-delay: 0.4s; }

    .section-modern-header {
        margin-bottom: 30px;
    }

    .section-modern-icon-wrapper {
        margin-bottom: 20px;
    }

    .section-modern-icon {
        width: 70px;
        height: 70px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .section-modern-icon::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), transparent);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .course-section-modern:hover .section-modern-icon::before {
        opacity: 1;
    }

    .learn-icon {
        background: linear-gradient(135deg, #10b981, #059669);
        box-shadow: 0 10px 30px rgba(16, 185, 129, 0.4);
    }

    .program-icon {
        background: linear-gradient(135deg, #06b6d4, #0891b2);
        box-shadow: 0 10px 30px rgba(6, 182, 212, 0.4);
    }

    .requirements-icon {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        box-shadow: 0 10px 30px rgba(245, 158, 11, 0.4);
    }

    .content-icon {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        box-shadow: 0 10px 30px rgba(139, 92, 246, 0.4);
    }

    .section-modern-title {
        font-size: 1.8rem;
        font-weight: 900;
        color: #0f172a;
        margin-bottom: 8px;
        line-height: 1.2;
    }

    body.dark-mode .section-modern-title {
        color: white;
    }

    .section-modern-subtitle {
        font-size: 1rem;
        color: #64748b;
        font-weight: 600;
    }

    body.dark-mode .section-modern-subtitle {
        color: rgba(255, 255, 255, 0.6);
    }

    .section-modern-content {
        color: #334155;
    }

    body.dark-mode .section-modern-content {
        color: rgba(255, 255, 255, 0.9);
    }

    /* ============================================
       LEARN ITEMS MODERNES
       ============================================ */
    .learn-items-modern {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .learn-item-modern {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 18px 20px;
        background: #f8fafc;
        border: 1px solid rgba(16, 185, 129, 0.3);
        border-left: 4px solid #10b981;
        border-radius: 14px;
        transition: all 0.3s ease;
        animation: fadeInLeft 0.6s ease;
        animation-fill-mode: both;
    }

    body.dark-mode .learn-item-modern {
        background: rgba(15, 23, 42, 0.6);
    }

    .learn-item-modern:hover {
        transform: translateX(8px);
        border-color: #10b981;
        background: rgba(16, 185, 129, 0.1);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.2);
    }

    .learn-item-modern-icon {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        flex-shrink: 0;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }

    .learn-item-modern-text {
        color: #1e293b;
        font-size: 1rem;
        line-height: 1.6;
        font-weight: 500;
    }

    body.dark-mode .learn-item-modern-text {
        color: rgba(255, 255, 255, 0.95);
    }

    /* ============================================
       PROGRAMME TIMELINE
       ============================================ */
    .program-timeline {
        display: flex;
        flex-direction: column;
        gap: 20px;
        position: relative;
    }

    .program-timeline::before {
        content: '';
        position: absolute;
        left: 35px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(180deg, #06b6d4, #14b8a6);
        opacity: 0.3;
    }

    .program-module {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 25px;
        background: #f8fafc;
        border: 1px solid rgba(6, 182, 212, 0.3);
        border-radius: 16px;
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
    }

    body.dark-mode .program-module {
        background: rgba(15, 23, 42, 0.6);
    }

    .program-module:hover {
        transform: translateX(10px);
        border-color: #06b6d4;
        background: rgba(6, 182, 212, 0.1);
        box-shadow: 0 10px 30px rgba(6, 182, 212, 0.2);
    }

    .program-module-number {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: 900;
        flex-shrink: 0;
        box-shadow: 0 8px 20px rgba(6, 182, 212, 0.4);
        position: relative;
        z-index: 2;
    }

    .program-module-content {
        flex: 1;
    }

    .program-module-title {
        font-size: 1.2rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 10px;
    }

    body.dark-mode .program-module-title {
        color: white;
    }

    .program-module-description {
        font-size: 0.95rem;
        color: #64748b;
        line-height: 1.6;
        margin-bottom: 12px;
    }

    body.dark-mode .program-module-description {
        color: rgba(255, 255, 255, 0.7);
    }

    .program-module-meta {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .program-module-duration,
    .program-module-lessons {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.9rem;
        color: #64748b;
        font-weight: 600;
    }

    body.dark-mode .program-module-duration,
    body.dark-mode .program-module-lessons {
        color: rgba(255, 255, 255, 0.7);
    }

    .program-module-duration i,
    .program-module-lessons i {
        color: #06b6d4;
    }

    .program-module-arrow {
        color: rgba(6, 182, 212, 0.5);
        font-size: 1.2rem;
        transition: all 0.3s ease;
    }

    .program-module:hover .program-module-arrow {
        color: #06b6d4;
        transform: translateX(5px);
    }

    .program-module-content-toggle {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 15px;
        padding: 10px 15px;
        background: rgba(6, 182, 212, 0.1);
        border: 1px solid rgba(6, 182, 212, 0.3);
        border-radius: 8px;
        color: #06b6d4;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s ease;
        user-select: none;
    }

    .program-module-content-toggle:hover {
        background: rgba(6, 182, 212, 0.2);
        border-color: #06b6d4;
    }

    .program-module-content-toggle i {
        transition: transform 0.3s ease;
    }

    .program-module-content-toggle.active i {
        transform: rotate(180deg);
    }

    body.dark-mode .program-module-content-toggle {
        background: rgba(6, 182, 212, 0.15);
        color: #06b6d4;
    }

    .program-module-full-content {
        margin-top: 15px;
        padding: 20px;
        background: rgba(6, 182, 212, 0.05);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 12px;
        animation: fadeInDown 0.4s ease;
    }

    body.dark-mode .program-module-full-content {
        background: rgba(6, 182, 212, 0.1);
        border-color: rgba(6, 182, 212, 0.3);
    }

    .program-module-full-content-inner {
        color: #334155;
        line-height: 1.8;
        font-size: 0.95rem;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    body.dark-mode .program-module-full-content-inner {
        color: rgba(255, 255, 255, 0.9);
    }

    body.light-mode .program-module-full-content-inner {
        color: #334155;
    }

    /* ============================================
       REQUIREMENTS MODERNES
       ============================================ */
    .requirements-modern {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .requirement-item-modern {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 20px;
        background: #f8fafc;
        border: 1px solid rgba(245, 158, 11, 0.3);
        border-left: 4px solid #f59e0b;
        border-radius: 14px;
        transition: all 0.3s ease;
        animation: fadeInLeft 0.6s ease;
        animation-fill-mode: both;
    }

    body.dark-mode .requirement-item-modern {
        background: rgba(15, 23, 42, 0.6);
    }

    .requirement-item-modern:hover {
        transform: translateX(8px);
        border-color: #f59e0b;
        background: rgba(245, 158, 11, 0.1);
        box-shadow: 0 8px 25px rgba(245, 158, 11, 0.2);
    }

    .requirement-item-modern-badge {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        flex-shrink: 0;
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
    }

    .requirement-item-modern-text {
        color: #1e293b;
        font-size: 1rem;
        line-height: 1.6;
        font-weight: 500;
    }

    body.dark-mode .requirement-item-modern-text {
        color: rgba(255, 255, 255, 0.95);
    }

    /* ============================================
       CONTENU MODERNE
       ============================================ */
    .course-content-modern {
        font-size: 1.1rem;
        line-height: 1.9;
        white-space: pre-line;
        color: #334155;
    }

    body.dark-mode .course-content-modern {
        color: rgba(255, 255, 255, 0.9);
    }

    .course-empty-state-modern {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 25px;
        border-radius: 20px;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: rgba(6, 182, 212, 0.6);
    }

    .course-empty-state-modern h3 {
        font-size: 1.5rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 10px;
    }

    body.dark-mode .course-empty-state-modern h3 {
        color: white;
    }

    .course-empty-state-modern p {
        color: #64748b;
        font-size: 1rem;
    }

    body.dark-mode .course-empty-state-modern p {
        color: rgba(255, 255, 255, 0.6);
    }

    /* ============================================
       RELATED COURSES - Style Conseils Carrière
       ============================================ */
    .related-courses-section {
        margin-top: 30px;
    }

    .related-courses-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .related-course-item {
        display: flex;
        gap: 15px;
        align-items: flex-start;
        padding: 12px;
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.3s ease;
        background: transparent;
    }

    .related-course-item:hover {
        background: rgba(6, 182, 212, 0.05);
    }

    body.dark-mode .related-course-item:hover {
        background: rgba(6, 182, 212, 0.1);
    }

    .related-course-thumbnail {
        position: relative;
        flex-shrink: 0;
        width: 140px;
        height: 100px;
        border-radius: 10px;
        overflow: hidden;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
    }

    .related-course-thumbnail-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .related-course-item:hover .related-course-thumbnail-image {
        transform: scale(1.05);
    }

    .related-course-thumbnail-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
    }

    .related-course-thumbnail-placeholder i {
        font-size: 2rem;
        color: rgba(255, 255, 255, 0.3);
    }

    .related-course-number {
        position: absolute;
        top: 8px;
        left: 8px;
        width: 28px;
        height: 28px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.9rem;
        box-shadow: 0 2px 8px rgba(6, 182, 212, 0.4);
        z-index: 5;
    }

    .related-course-info {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 8px;
        min-width: 0;
    }

    .related-course-info-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.4;
        margin: 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    body.dark-mode .related-course-info-title {
        color: rgba(255, 255, 255, 0.95);
    }

    .related-course-info-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .related-course-info-duration {
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 600;
    }

    body.dark-mode .related-course-info-duration {
        color: rgba(255, 255, 255, 0.6);
    }

    .related-course-info-price {
        font-size: 0.9rem;
        font-weight: 700;
        color: #06b6d4;
    }

    .related-icon {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        box-shadow: 0 10px 30px rgba(139, 92, 246, 0.4);
    }

    /* ============================================
       ANIMATIONS
       ============================================ */
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
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

    @keyframes fadeInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.5);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes gradientFlow {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    @keyframes fadeInLeft {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* ============================================
       RESPONSIVE
       ============================================ */
    @media (max-width: 1200px) {
        .course-sections-layout {
            grid-template-columns: 1fr;
        }

        .course-sections-right {
            position: relative;
            top: 0;
        }
    }

    @media (max-width: 1024px) {
        .course-main-grid {
            grid-template-columns: 1fr;
        }

        .course-purchase-card {
            position: relative;
            top: 0;
        }

        .course-sections-layout {
            grid-template-columns: 1fr;
        }

        .course-hero-stats {
            justify-content: center;
        }
    }

    @media (max-width: 768px) {
        .course-hero {
            min-height: 400px;
        }

        .course-hero-content {
            padding: 40px 15px 30px;
        }

        .course-hero-stats {
            flex-direction: column;
            gap: 15px;
        }

        .course-stat-item {
            width: 100%;
            justify-content: center;
        }

        .course-section-modern {
            padding: 25px 20px;
        }

        .program-timeline::before {
            display: none;
        }

        .program-module {
            flex-direction: column;
            align-items: flex-start;
        }

        .program-module-number {
            align-self: flex-start;
        }

        .course-main-container {
            padding: 0 15px 40px;
        }
    }

    /* ============================================
       LIGHT MODE - ADAPTATION COMPLÈTE
       ============================================ */
    body.light-mode .course-detail-page {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 50%, #ffffff 100%) !important;
    }

    body.light-mode .course-detail-page::before {
        opacity: 1;
        background: 
            radial-gradient(circle at 20% 30%, rgba(6, 182, 212, 0.05) 0%, transparent 50%),
            radial-gradient(circle at 80% 70%, rgba(20, 184, 166, 0.05) 0%, transparent 50%);
    }

    body.light-mode .course-hero-image {
        filter: brightness(0.7);
    }

    body.light-mode .course-hero-placeholder {
        background: linear-gradient(135deg, #e0f2fe, #bae6fd);
    }

    body.light-mode .course-hero-overlay {
        background: linear-gradient(
            to bottom,
            transparent 0%,
            rgba(255, 255, 255, 0.2) 50%,
            rgba(255, 255, 255, 0.95) 100%
        );
    }

    body.light-mode .course-hero-badge {
        box-shadow: 0 8px 25px rgba(239, 68, 68, 0.3);
    }

    body.light-mode .course-hero-title {
        color: #0f172a;
        text-shadow: none;
    }

    body.light-mode .course-hero-description {
        color: #475569;
    }

    body.light-mode .course-stat-item {
        background: white;
        border-color: rgba(6, 182, 212, 0.3);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    body.light-mode .course-stat-item:hover {
        box-shadow: 0 10px 30px rgba(6, 182, 212, 0.15);
    }

    body.light-mode .stat-value {
        color: #0f172a;
    }

    body.light-mode .stat-label {
        color: #64748b;
    }

    body.light-mode .course-purchase-card {
        background: white;
        border-color: rgba(6, 182, 212, 0.3);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
    }

    body.light-mode .purchase-status {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.1));
        border-color: rgba(16, 185, 129, 0.4);
    }

    body.light-mode .purchase-status-icon {
        color: #059669;
    }

    body.light-mode .purchase-status-title {
        color: #0f172a;
    }

    body.light-mode .purchase-status-text {
        color: #475569;
    }

    body.light-mode .pricing-old {
        color: #94a3b8;
    }

    body.light-mode .pricing-amount {
        color: #0891b2;
    }

    body.light-mode .pricing-currency {
        color: #64748b;
    }

    body.light-mode .pricing-savings {
        background: rgba(16, 185, 129, 0.1);
        border-color: rgba(16, 185, 129, 0.3);
        color: #059669;
    }

    body.light-mode .purchase-guarantee {
        background: rgba(6, 182, 212, 0.08);
        color: #475569;
    }

    body.light-mode .purchase-guarantee i {
        color: #0891b2;
    }

    body.light-mode .course-section-modern {
        background: white;
        border-color: rgba(6, 182, 212, 0.2);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    body.light-mode .course-section-modern:hover {
        box-shadow: 0 20px 60px rgba(6, 182, 212, 0.15);
    }

    body.light-mode .course-section-modern::before {
        background: linear-gradient(90deg, #06b6d4, #14b8a6, #06b6d4);
    }

    body.light-mode .section-modern-title {
        color: #0f172a;
    }

    body.light-mode .section-modern-subtitle {
        color: #64748b;
    }

    body.light-mode .learn-item-modern {
        background: #f8fafc;
        border-color: rgba(16, 185, 129, 0.2);
        border-left-color: #10b981;
    }

    body.light-mode .learn-item-modern:hover {
        background: rgba(16, 185, 129, 0.05);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.1);
    }

    body.light-mode .learn-item-modern-text {
        color: #1e293b;
    }

    body.light-mode .requirement-item-modern {
        background: #f8fafc;
        border-color: rgba(245, 158, 11, 0.2);
        border-left-color: #f59e0b;
    }

    body.light-mode .requirement-item-modern:hover {
        background: rgba(245, 158, 11, 0.05);
        box-shadow: 0 8px 25px rgba(245, 158, 11, 0.1);
    }

    body.light-mode .requirement-item-modern-text {
        color: #1e293b;
    }

    body.light-mode .program-module {
        background: #f8fafc;
        border-color: rgba(6, 182, 212, 0.2);
    }

    body.light-mode .program-module:hover {
        background: rgba(6, 182, 212, 0.05);
        box-shadow: 0 10px 30px rgba(6, 182, 212, 0.1);
    }

    body.light-mode .program-module-title {
        color: #0f172a;
    }

    body.light-mode .program-module-description {
        color: #64748b;
    }

    body.light-mode .program-module-duration,
    body.light-mode .program-module-lessons {
        color: #64748b;
    }

    body.light-mode .program-module-duration i,
    body.light-mode .program-module-lessons i {
        color: #0891b2;
    }

    body.light-mode .program-module-arrow {
        color: rgba(6, 182, 212, 0.4);
    }

    body.light-mode .program-module:hover .program-module-arrow {
        color: #0891b2;
    }

    body.light-mode .program-timeline::before {
        background: linear-gradient(180deg, #06b6d4, #14b8a6);
        opacity: 0.2;
    }

    body.light-mode .course-content-modern {
        color: #334155;
    }

    body.light-mode .empty-state-icon {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.1));
        color: rgba(6, 182, 212, 0.5);
    }

    body.light-mode .course-empty-state-modern h3 {
        color: #0f172a;
    }

    body.light-mode .course-empty-state-modern p {
        color: #64748b;
    }

    body.light-mode .related-course-item:hover {
        background: rgba(6, 182, 212, 0.05);
    }

    body.light-mode .related-course-info-title {
        color: #1e293b;
    }

    body.light-mode .related-course-info-duration {
        color: #64748b;
    }
</style>

<script>
function toggleChapterContent(chapterId) {
    const contentDiv = document.getElementById('chapter-content-' + chapterId);
    const toggleButton = contentDiv.previousElementSibling;
    
    if (contentDiv.style.display === 'none') {
        contentDiv.style.display = 'block';
        toggleButton.classList.add('active');
        toggleButton.querySelector('span').textContent = 'Masquer le contenu';
    } else {
        contentDiv.style.display = 'none';
        toggleButton.classList.remove('active');
        toggleButton.querySelector('span').textContent = 'Voir le contenu';
    }
}
</script>
@endsection
