@extends('layouts.app')

@section('title', $course->localized_title . ' - NiangProgrammeur')
@section('meta_description', $course->localized_description ?? 'Découvrez ce cours premium sur NiangProgrammeur')

@push('head')
<!-- Structured Data Course pour SEO -->
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "Course",
    "name": "{{ addslashes($course->localized_title) }}",
    "description": "{{ addslashes($course->localized_description ?? '') }}",
    "provider": {
        "@@type": "Organization",
        "name": "NiangProgrammeur",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('images/logo.png') }}"
    },
    "image": "{{ $course->cover_image ? (($course->cover_type ?? 'internal') === 'internal' ? asset('storage/' . $course->cover_image) : $course->cover_image) : asset('images/logo.png') }}",
    "courseCode": "{{ $course->slug }}",
    "educationalCredentialAwarded": "Certificate",
    "hasCourseInstance": {
        "@@type": "CourseInstance",
        "courseMode": "online",
        "duration": "PT{{ $course->duration_hours ?? 0 }}H"
    },
    @if($course->rating > 0)
    "aggregateRating": {
        "@@type": "AggregateRating",
        "ratingValue": "{{ $course->rating }}",
        "bestRating": "5",
        "worstRating": "1",
        "ratingCount": "{{ $course->reviews_count }}"
    },
    @endif
    "offers": {
        "@@type": "Offer",
        "price": "{{ $course->current_price }}",
        "priceCurrency": "{{ $course->currency ?? 'XOF' }}",
        "availability": "https://schema.org/InStock",
        "url": "{{ route('monetization.course.show', $course->slug) }}",
        "seller": {
            "@@type": "Organization",
            "name": "NiangProgrammeur"
        }
    }
}
</script>
@endpush

@section('content')
<div class="course-detail-page">
    <!-- Hero Section avec Image -->
    <div class="course-hero">
        <div class="course-hero-background">
            @if($course->cover_image)
                @if(($course->cover_type ?? 'internal') === 'internal')
                    <img src="{{ asset('storage/' . $course->cover_image) }}" alt="{{ $course->localized_title }}" class="course-hero-image" width="1200" height="600" loading="eager" fetchpriority="high" decoding="async">
                @else
                    <img src="{{ $course->cover_image }}" alt="{{ $course->localized_title }}" class="course-hero-image" width="1200" height="600" loading="eager" fetchpriority="high" decoding="async" onerror="this.parentElement.innerHTML='<div class=\'course-hero-placeholder\'></div>'">
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
                            @elseif($course->isFree())
                            <div class="purchase-pricing">
                                <div class="pricing-current">
                                    <span class="pricing-amount">Gratuit</span>
                                </div>
                            </div>

                            <a href="{{ route('courses.enroll-free', $course->slug) }}" class="course-purchase-btn">
                                @auth
                                <i class="fas fa-graduation-cap"></i>
                                <span>S'inscrire gratuitement</span>
                                @else
                                <i class="fas fa-sign-in-alt"></i>
                                <span>Se connecter pour s'inscrire</span>
                                @endauth
                            </a>

                            <div class="purchase-guarantee">
                                <i class="fas fa-bolt"></i>
                                <span>Accès immédiat, aucun paiement requis</span>
                            </div>
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
       BASE
       ============================================ */
    .course-detail-page {
        position: relative;
        min-block-size: 100vh;
        background: var(--surface);
        overflow-x: hidden;
    }

    .course-detail-page::before {
        content: '';
        position: fixed;
        inset: 0;
        pointer-events: none;
        z-index: 0;
        background:
            radial-gradient(circle at 15% 20%, oklch(65% 0.20 200 / 6%) 0%, transparent 45%),
            radial-gradient(circle at 85% 75%, oklch(65% 0.18 170 / 6%) 0%, transparent 45%);
    }

    /* ============================================
       HERO
       ============================================ */
    .course-hero {
        position: relative;
        min-block-size: 480px;
        display: flex;
        align-items: flex-end;
        margin-block-end: 56px;
        overflow: hidden;
    }

    .course-hero-background {
        position: absolute;
        inset: 0;
        z-index: 1;
    }

    .course-hero-image {
        inline-size: 100%;
        block-size: 100%;
        object-fit: cover;
        filter: brightness(0.55) saturate(1.05);
    }

    .course-hero-placeholder {
        inline-size: 100%;
        block-size: 100%;
        background: linear-gradient(135deg, var(--color-brand-600), var(--color-teal-600));
    }

    .course-hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(
            to bottom,
            oklch(10% 0.02 230 / 10%) 0%,
            oklch(10% 0.02 230 / 45%) 55%,
            oklch(8% 0.02 230 / 96%) 100%
        );
    }

    .course-hero-content {
        position: relative;
        z-index: 2;
        inline-size: 100%;
        padding-block: 56px 40px;
        padding-inline: 20px;
    }

    .course-hero-container {
        max-inline-size: 1400px;
        margin-inline: auto;
    }

    .course-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding-block: 0.5rem;
        padding-inline: 1.125rem;
        background: linear-gradient(135deg, oklch(68% 0.19 25), oklch(58% 0.20 15));
        border-radius: var(--radius-pill);
        color: oklch(100% 0 0);
        font-weight: 700;
        font-size: 0.875rem;
        margin-block-end: 22px;
        box-shadow: 0 8px 24px oklch(60% 0.20 20 / 40%);
        animation: fade-in-up var(--duration-slow) var(--ease-out);
    }

    .badge-icon { animation: pulse 2s ease infinite; }

    .course-hero-text {
        margin-block-end: 36px;
        animation: fade-in-up 0.6s var(--ease-out);
    }

    .course-hero-title {
        font-size: clamp(2.25rem, 4.5vw, 3.5rem);
        font-weight: 800;
        letter-spacing: -0.02em;
        color: oklch(100% 0 0);
        margin-block-end: 16px;
        line-height: 1.15;
        text-shadow: 0 4px 24px oklch(0% 0 0 / 40%);
    }

    .course-hero-description {
        font-size: 1.1875rem;
        color: oklch(100% 0 0 / 88%);
        line-height: 1.7;
        max-inline-size: 780px;
    }

    .course-hero-stats {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        animation: fade-in-up 0.8s var(--ease-out);
    }

    .course-stat-item {
        display: flex;
        align-items: center;
        gap: 0.875rem;
        padding-block: 0.875rem;
        padding-inline: 1.25rem;
        background: oklch(20% 0.02 230 / 55%);
        border: 1px solid oklch(100% 0 0 / 12%);
        border-radius: 16px;
        backdrop-filter: blur(16px);
        transition: transform var(--duration-normal) var(--ease-spring), border-color var(--duration-normal) ease;

        &:hover {
            transform: translateY(-3px);
            border-color: oklch(100% 0 0 / 25%);
        }
    }

    .stat-icon {
        inline-size: 44px;
        block-size: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
        color: oklch(100% 0 0);
    }

    .stat-rating    { background: linear-gradient(135deg, oklch(78% 0.15 85), oklch(68% 0.17 55)); }
    .stat-students  { background: linear-gradient(135deg, oklch(72% 0.17 145), oklch(60% 0.15 165)); }
    .stat-duration  { background: linear-gradient(135deg, var(--color-brand-500), var(--color-teal-500)); }

    .stat-content { display: flex; flex-direction: column; }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: oklch(100% 0 0);
        line-height: 1.1;
        letter-spacing: -0.01em;
    }

    .stat-label {
        font-size: 0.8125rem;
        color: oklch(100% 0 0 / 65%);
        margin-block-start: 2px;
    }

    /* ============================================
       MAIN LAYOUT
       ============================================ */
    .course-main-container {
        position: relative;
        z-index: 1;
        padding-inline: 20px;
        padding-block-end: 56px;
    }

    .course-main-grid {
        max-inline-size: 1600px;
        margin-inline: auto;
    }

    .course-main-content { display: flex; flex-direction: column; }

    .course-sections-layout {
        display: grid;
        grid-template-columns: 1fr 500px;
        gap: 24px;
        align-items: start;
    }

    .course-sections-left {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .course-sections-right {
        position: sticky;
        inset-block-start: 100px;
        block-size: fit-content;
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    /* ============================================
       CARD D'ACHAT
       ============================================ */
    .course-purchase-card {
        position: relative;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 32px;
        box-shadow: 0 20px 48px oklch(0% 0 0 / 6%);
        animation: fade-in-up 0.6s var(--ease-out);
        overflow: hidden;

        &::before {
            content: '';
            position: absolute;
            inset-block-start: 0;
            inset-inline: 0;
            block-size: 3px;
            background: linear-gradient(90deg, var(--color-brand-500), var(--color-teal-500));
        }
    }

    .purchase-status {
        text-align: center;
        padding-block: 28px;
        padding-inline: 16px;
        background: oklch(72% 0.17 145 / 10%);
        border: 1px solid oklch(72% 0.17 145 / 35%);
        border-radius: 16px;
        margin-block-end: 24px;
    }

    .purchase-status-icon {
        font-size: 3rem;
        color: oklch(60% 0.17 145);
        margin-block-end: 12px;
        animation: scale-in 0.4s var(--ease-spring);
    }

    .purchase-status-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--text);
        margin-block-end: 6px;
    }

    .purchase-status-text {
        font-size: 0.9375rem;
        color: var(--text-muted);
    }

    .purchase-pricing {
        text-align: center;
        margin-block-end: 28px;
    }

    .pricing-discount {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.875rem;
        margin-block-end: 12px;
    }

    .pricing-old {
        font-size: 1.0625rem;
        color: var(--text-muted);
        text-decoration: line-through;
    }

    .pricing-badge {
        padding-block: 0.3125rem;
        padding-inline: 0.75rem;
        background: linear-gradient(135deg, oklch(68% 0.19 25), oklch(58% 0.20 15));
        border-radius: 8px;
        color: oklch(100% 0 0);
        font-weight: 700;
        font-size: 0.8125rem;
    }

    .pricing-current {
        display: flex;
        align-items: baseline;
        justify-content: center;
        gap: 0.5rem;
        margin-block-end: 12px;
    }

    .pricing-amount {
        font-size: 2.75rem;
        font-weight: 900;
        letter-spacing: -0.03em;
        line-height: 1;
        background: linear-gradient(135deg, var(--color-brand-500), var(--color-teal-500));
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .pricing-currency {
        font-size: 1.1875rem;
        font-weight: 700;
        color: var(--text-muted);
    }

    .pricing-savings {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding-block: 0.75rem;
        padding-inline: 1.25rem;
        background: oklch(72% 0.17 145 / 12%);
        border: 1px solid oklch(72% 0.17 145 / 35%);
        border-radius: 10px;
        color: oklch(52% 0.15 150);
        font-weight: 600;
        font-size: 0.875rem;
    }

    .purchase-form { margin-block-end: 14px; }

    .course-access-btn,
    .course-purchase-btn {
        inline-size: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding-block: 1rem;
        padding-inline: 1.75rem;
        background: linear-gradient(135deg, var(--color-brand-500), var(--color-teal-500));
        border: none;
        border-radius: var(--radius-pill);
        color: oklch(10% 0 0);
        font-size: 1.0625rem;
        font-weight: 800;
        text-decoration: none;
        cursor: pointer;
        transition: transform var(--duration-normal) var(--ease-spring), box-shadow var(--duration-normal) ease;
        box-shadow: 0 10px 28px oklch(65% 0.20 200 / 32%);

        &:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 36px oklch(65% 0.20 200 / 44%);
        }
    }

    .purchase-guarantee {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.625rem;
        margin-block-start: 18px;
        padding-block: 0.875rem;
        background: var(--surface-muted);
        border-radius: 12px;
        color: var(--text-muted);
        font-size: 0.875rem;
        font-weight: 600;

        & i { color: var(--color-brand-500); }
    }

    /* ============================================
       SECTIONS (partagé : apprentissage, prérequis,
       programme, formations recommandées)
       ============================================ */
    .course-section-modern {
        position: relative;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 32px;
        transition: transform var(--duration-normal) var(--ease-spring), box-shadow var(--duration-normal) ease, border-color var(--duration-normal) ease;
        animation: fade-in-up 0.6s var(--ease-out);
        animation-fill-mode: both;

        &:hover {
            transform: translateY(-4px);
            border-color: oklch(65% 0.20 200 / 35%);
            box-shadow: 0 24px 48px oklch(65% 0.20 200 / 12%);
        }
    }

    .course-section-modern:nth-child(2) { animation-delay: 0.08s; }
    .course-section-modern:nth-child(3) { animation-delay: 0.16s; }

    .section-modern-header { margin-block-end: 28px; }

    .section-modern-icon-wrapper { margin-block-end: 18px; }

    .section-modern-icon {
        inline-size: 56px;
        block-size: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: oklch(100% 0 0);
        background: linear-gradient(135deg, var(--color-brand-500), var(--color-teal-500));
        box-shadow: 0 10px 24px oklch(65% 0.20 200 / 32%);
    }

    .section-modern-title {
        font-size: 1.5rem;
        font-weight: 800;
        letter-spacing: -0.01em;
        color: var(--text);
        margin-block-end: 6px;
        line-height: 1.25;
    }

    .section-modern-subtitle {
        font-size: 0.9375rem;
        color: var(--text-muted);
        font-weight: 600;
    }

    .section-modern-content { color: var(--text); }

    /* ============================================
       CE QUE VOUS ALLEZ APPRENDRE / PRÉREQUIS
       ============================================ */
    .learn-items-modern,
    .requirements-modern {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .learn-item-modern,
    .requirement-item-modern {
        display: flex;
        align-items: center;
        gap: 14px;
        padding-block: 0.9375rem;
        padding-inline: 1.125rem;
        background: var(--surface-muted);
        border: 1px solid var(--border);
        border-radius: 14px;
        transition: transform var(--duration-fast) ease, border-color var(--duration-fast) ease, background var(--duration-fast) ease;
        animation: fade-in-left 0.5s var(--ease-out);
        animation-fill-mode: both;

        &:hover { transform: translateX(4px); }
    }

    .learn-item-modern:hover {
        border-color: oklch(72% 0.17 145 / 45%);
        background: oklch(72% 0.17 145 / 8%);
    }

    .requirement-item-modern:hover {
        border-color: oklch(78% 0.15 85 / 50%);
        background: oklch(78% 0.15 85 / 8%);
    }

    .learn-item-modern-icon,
    .requirement-item-modern-badge {
        inline-size: 28px;
        block-size: 28px;
        border-radius: 9px;
        color: oklch(100% 0 0);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8125rem;
        flex-shrink: 0;
    }

    .learn-item-modern-icon { background: linear-gradient(135deg, oklch(72% 0.17 145), oklch(60% 0.15 165)); }
    .requirement-item-modern-badge { background: linear-gradient(135deg, oklch(78% 0.15 85), oklch(68% 0.17 55)); }

    .learn-item-modern-text,
    .requirement-item-modern-text {
        color: var(--text);
        font-size: 0.9375rem;
        line-height: 1.6;
        font-weight: 500;
    }

    /* ============================================
       PROGRAMME (TIMELINE)
       ============================================ */
    .program-timeline {
        display: flex;
        flex-direction: column;
        gap: 14px;
        position: relative;
    }

    .program-module {
        display: flex;
        align-items: center;
        gap: 18px;
        padding: 20px;
        background: var(--surface-muted);
        border: 1px solid var(--border);
        border-radius: 16px;
        transition: transform var(--duration-fast) ease, border-color var(--duration-fast) ease, background var(--duration-fast) ease;

        &:hover {
            transform: translateX(6px);
            border-color: oklch(65% 0.20 200 / 40%);
            background: oklch(65% 0.20 200 / 6%);
        }
    }

    .program-module-number {
        inline-size: 42px;
        block-size: 42px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--color-brand-500), var(--color-teal-500));
        color: oklch(100% 0 0);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        font-weight: 800;
        flex-shrink: 0;
    }

    .program-module-content { flex: 1; min-inline-size: 0; }

    .program-module-title {
        font-size: 1.0625rem;
        font-weight: 700;
        color: var(--text);
        margin-block-end: 6px;
    }

    .program-module-description {
        font-size: 0.875rem;
        color: var(--text-muted);
        line-height: 1.6;
        margin-block-end: 10px;
    }

    .program-module-meta { display: flex; gap: 16px; flex-wrap: wrap; }

    .program-module-duration,
    .program-module-lessons {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.8125rem;
        color: var(--text-muted);
        font-weight: 600;

        & i { color: var(--color-brand-500); }
    }

    .program-module-arrow {
        color: var(--text-muted);
        font-size: 1.0625rem;
        transition: transform var(--duration-fast) ease, color var(--duration-fast) ease;
    }

    .program-module:hover .program-module-arrow {
        color: var(--color-brand-500);
        transform: translateX(4px);
    }

    .program-module-content-toggle {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-block-start: 12px;
        padding-block: 0.5rem;
        padding-inline: 0.875rem;
        background: oklch(65% 0.20 200 / 10%);
        border: 1px solid oklch(65% 0.20 200 / 30%);
        border-radius: var(--radius-pill);
        color: var(--color-brand-500);
        font-weight: 600;
        font-size: 0.8125rem;
        cursor: pointer;
        transition: background var(--duration-fast) ease;
        user-select: none;

        &:hover { background: oklch(65% 0.20 200 / 18%); }

        & i { transition: transform var(--duration-fast) ease; }

        &.active i { transform: rotate(180deg); }
    }

    .program-module-full-content {
        margin-block-start: 12px;
        padding: 16px;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 12px;
        animation: fade-in-down 0.3s var(--ease-out);
    }

    .program-module-full-content-inner {
        color: var(--text);
        line-height: 1.75;
        font-size: 0.9375rem;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    /* ============================================
       ÉTAT VIDE
       ============================================ */
    .course-empty-state-modern { text-align: center; padding-block: 48px; padding-inline: 16px; }

    .empty-state-icon {
        inline-size: 72px;
        block-size: 72px;
        margin-inline: auto;
        margin-block-end: 20px;
        border-radius: 18px;
        background: oklch(65% 0.20 200 / 12%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.125rem;
        color: var(--color-brand-500);
    }

    .course-empty-state-modern h3 {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--text);
        margin-block-end: 8px;
    }

    .course-empty-state-modern p { color: var(--text-muted); font-size: 0.9375rem; }

    /* ============================================
       FORMATIONS RECOMMANDÉES
       ============================================ */
    .related-courses-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .related-course-item {
        display: flex;
        gap: 14px;
        align-items: flex-start;
        padding: 10px;
        border-radius: 14px;
        text-decoration: none;
        background: transparent;
        transition: background var(--duration-fast) ease;

        &:hover {
            background: var(--surface-muted);

            & .related-course-thumbnail-image { transform: scale(1.06); }
        }
    }

    .related-course-thumbnail {
        position: relative;
        flex-shrink: 0;
        inline-size: 128px;
        block-size: 92px;
        border-radius: 12px;
        overflow: hidden;
        background: linear-gradient(135deg, var(--color-brand-500), var(--color-teal-500));
    }

    .related-course-thumbnail-image {
        inline-size: 100%;
        block-size: 100%;
        object-fit: cover;
        transition: transform 500ms var(--ease-out);
    }

    .related-course-thumbnail-placeholder {
        inline-size: 100%;
        block-size: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--color-brand-500), var(--color-teal-500));

        & i { font-size: 1.75rem; color: oklch(100% 0 0 / 45%); }
    }

    .related-course-number {
        position: absolute;
        inset-block-start: 6px;
        inset-inline-start: 6px;
        inline-size: 24px;
        block-size: 24px;
        background: oklch(10% 0.02 230 / 75%);
        color: oklch(100% 0 0);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.8125rem;
        backdrop-filter: blur(4px);
    }

    .related-course-info {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 6px;
        min-inline-size: 0;
    }

    .related-course-info-title {
        font-size: 0.9375rem;
        font-weight: 700;
        color: var(--text);
        line-height: 1.4;
        margin: 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .related-course-info-meta {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .related-course-info-duration { font-size: 0.8125rem; color: var(--text-muted); font-weight: 600; }

    .related-course-info-price {
        font-size: 0.875rem;
        font-weight: 700;
        background: linear-gradient(135deg, var(--color-brand-500), var(--color-teal-500));
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    /* ============================================
       ANIMATIONS
       ============================================ */
    @keyframes fade-in-down {
        from { opacity: 0; transform: translateY(-16px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    @keyframes fade-in-up {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    @keyframes fade-in-left {
        from { opacity: 0; transform: translateX(-14px); }
        to   { opacity: 1; transform: translateX(0); }
    }

    @keyframes scale-in {
        from { opacity: 0; transform: scale(0.6); }
        to   { opacity: 1; transform: scale(1); }
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50%      { transform: scale(1.12); }
    }

    /* ============================================
       RESPONSIVE
       ============================================ */
    @media (width <= 1200px) {
        .course-sections-layout { grid-template-columns: 1fr; }
        .course-sections-right  { position: relative; inset-block-start: 0; }
    }

    @media (width <= 768px) {
        .course-hero { min-block-size: 380px; }
        .course-hero-content { padding-block: 36px 28px; padding-inline: 15px; }
        .course-hero-stats { flex-direction: column; }
        .course-stat-item { inline-size: 100%; justify-content: center; }
        .course-section-modern { padding: 22px 18px; }
        .program-module { flex-direction: column; align-items: flex-start; }
        .program-module-number { align-self: flex-start; }
        .course-main-container { padding-inline: 15px; padding-block-end: 40px; }
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
