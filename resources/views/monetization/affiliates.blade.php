@extends('layouts.app')

@section('title', trans('app.affiliates.title'))
@section('meta_description', trans('app.affiliates.meta_description'))

@section('content')
<div class="affiliates-page">
    <!-- Animated Background -->
    <div class="affiliates-bg-animation">
        <div class="bg-gradient-orb orb-1"></div>
        <div class="bg-gradient-orb orb-2"></div>
        <div class="bg-gradient-orb orb-3"></div>
    </div>

    <div class="affiliates-container">
        <!-- Hero Section -->
        <section class="affiliates-hero" data-aos="fade-up">
            <div class="hero-icon-wrapper">
                <div class="hero-icon-glow"></div>
                <i class="fas fa-users hero-icon"></i>
            </div>
            <h1 class="hero-title">
                <span class="title-gradient">{{ trans('app.affiliates.hero_title') }}</span>
            </h1>
            <p class="hero-subtitle">
                {!! trans('app.affiliates.hero_subtitle', ['commission' => '<strong>10%</strong>']) !!}
            </p>
            @if($userAffiliate)
            <div class="hero-badge">
                <i class="fas fa-check-circle"></i>
                <span>{{ trans('app.affiliates.already_affiliate') }}</span>
            </div>
            @endif
        </section>

        @if($userAffiliate)
        <!-- Already Affiliate Card -->
        <section class="affiliates-section" data-aos="fade-up" data-aos-delay="100">
            <div class="success-card">
                <div class="success-icon-wrapper">
                    <div class="success-icon-pulse"></div>
                    <i class="fas fa-check-circle success-icon"></i>
                </div>
                <h2 class="success-title">{{ trans('app.affiliates.success_title') }}</h2>
                <p class="success-text">
                    {{ trans('app.affiliates.success_text') }}
                </p>
                <a href="{{ route('dashboard.affiliates') }}" class="success-btn">
                    <span>{{ trans('app.affiliates.access_dashboard') }}</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </section>
        @else
        <!-- Main Content Grid -->
        <section class="affiliates-section" data-aos="fade-up" data-aos-delay="100">
            <div class="affiliates-grid">
                <!-- Left Column: Benefits -->
                <div class="benefits-column">
                    <div class="section-header">
                        <h2 class="section-title">
                            <span class="title-icon">✨</span>
                            {{ trans('app.affiliates.benefits_title') }}
                        </h2>
                        <p class="section-description">
                            {{ trans('app.affiliates.benefits_description') }}
                        </p>
                    </div>

                    <div class="benefits-list">
                        <div class="benefit-item" data-aos="fade-right" data-aos-delay="200">
                            <div class="benefit-icon-wrapper">
                                <i class="fas fa-percentage benefit-icon"></i>
                            </div>
                            <div class="benefit-content">
                                <h3 class="benefit-title">{{ trans('app.affiliates.benefit_commission_title') }}</h3>
                                <p class="benefit-text">{{ trans('app.affiliates.benefit_commission_text', ['commission' => '10%']) }}</p>
                            </div>
                        </div>

                        <div class="benefit-item" data-aos="fade-right" data-aos-delay="300">
                            <div class="benefit-icon-wrapper">
                                <i class="fas fa-clock benefit-icon"></i>
                            </div>
                            <div class="benefit-content">
                                <h3 class="benefit-title">{{ trans('app.affiliates.benefit_payments_title') }}</h3>
                                <p class="benefit-text">{{ trans('app.affiliates.benefit_payments_text') }}</p>
                            </div>
                        </div>

                        <div class="benefit-item" data-aos="fade-right" data-aos-delay="400">
                            <div class="benefit-icon-wrapper">
                                <i class="fas fa-chart-line benefit-icon"></i>
                            </div>
                            <div class="benefit-content">
                                <h3 class="benefit-title">{{ trans('app.affiliates.benefit_tracking_title') }}</h3>
                                <p class="benefit-text">{{ trans('app.affiliates.benefit_tracking_text') }}</p>
                            </div>
                        </div>

                        <div class="benefit-item" data-aos="fade-right" data-aos-delay="500">
                            <div class="benefit-icon-wrapper">
                                <i class="fas fa-link benefit-icon"></i>
                            </div>
                            <div class="benefit-content">
                                <h3 class="benefit-title">{{ trans('app.affiliates.benefit_link_title') }}</h3>
                                <p class="benefit-text">{{ trans('app.affiliates.benefit_link_text') }}</p>
                            </div>
                        </div>

                        <div class="benefit-item" data-aos="fade-right" data-aos-delay="600">
                            <div class="benefit-icon-wrapper">
                                <i class="fas fa-headset benefit-icon"></i>
                            </div>
                            <div class="benefit-content">
                                <h3 class="benefit-title">{{ trans('app.affiliates.benefit_support_title') }}</h3>
                                <p class="benefit-text">{{ trans('app.affiliates.benefit_support_text') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Form -->
                <div class="form-column" data-aos="fade-left" data-aos-delay="200">
                    <div class="form-card">
                        <div class="form-header">
                            <div class="form-icon-wrapper">
                                <i class="fas fa-rocket form-icon"></i>
                            </div>
                            <h3 class="form-title">{{ trans('app.affiliates.form_title') }}</h3>
                            <p class="form-subtitle">{{ trans('app.affiliates.form_subtitle') }}</p>
                        </div>

                        @if(!Auth::check())
                        <div class="auth-alert">
                            <div class="alert-icon-wrapper">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="alert-content">
                                <h4 class="alert-title">{{ trans('app.affiliates.auth_required_title') }}</h4>
                                <p class="alert-text">{{ trans('app.affiliates.auth_required_text') }}</p>
                            </div>
                            <a href="{{ route('login') }}" class="alert-btn">
                                <i class="fas fa-sign-in-alt"></i>
                                <span>{{ trans('app.affiliates.login_button') }}</span>
                            </a>
                        </div>
                        @else
                        <form action="{{ route('monetization.affiliates.become') }}" method="POST" class="affiliate-form">
                            @csrf

                            <div class="form-group">
                                <label class="form-label">
                                    {{ trans('app.affiliates.name_label') }}
                                    <span class="required-star">*</span>
                                </label>
                                <div class="input-wrapper">
                                    <i class="fas fa-user input-icon"></i>
                                    <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required class="form-input" placeholder="{{ trans('app.affiliates.name_placeholder') }}">
                                </div>
                                @error('name')
                                <div class="form-error">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    {{ trans('app.affiliates.email_label') }}
                                    <span class="required-star">*</span>
                                </label>
                                <div class="input-wrapper">
                                    <i class="fas fa-envelope input-icon"></i>
                                    <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required class="form-input" placeholder="{{ trans('app.affiliates.email_placeholder') }}">
                                </div>
                                @error('email')
                                <div class="form-error">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <button type="submit" class="submit-btn">
                                <span class="btn-text">{{ trans('app.affiliates.submit_button') }}</span>
                                <span class="btn-icon">
                                    <i class="fas fa-arrow-right"></i>
                                </span>
                                <div class="btn-shine"></div>
                            </button>

                            <div class="form-footer">
                                <i class="fas fa-shield-alt"></i>
                                <span>{{ trans('app.affiliates.form_footer') }}</span>
                            </div>
                        </form>
                        @endif
                    </div>

                    <!-- Stats Cards -->
                    <div class="stats-mini-grid" style="margin-top: 30px;">
                        <div class="stat-mini-card" data-aos="zoom-in" data-aos-delay="700">
                            <div class="stat-mini-value">10%</div>
                            <div class="stat-mini-label">{{ trans('app.affiliates.stat_commission') }}</div>
                        </div>
                        <div class="stat-mini-card" data-aos="zoom-in" data-aos-delay="800">
                            <div class="stat-mini-value">24/7</div>
                            <div class="stat-mini-label">{{ trans('app.affiliates.stat_available') }}</div>
                        </div>
                        <div class="stat-mini-card" data-aos="zoom-in" data-aos-delay="900">
                            <div class="stat-mini-value">100%</div>
                            <div class="stat-mini-label">{{ trans('app.affiliates.stat_free') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif

        <!-- How It Works Section -->
        <section class="affiliates-section how-it-works" data-aos="fade-up" data-aos-delay="300">
            <div class="section-header-center">
                <h2 class="section-title-center">
                    <span class="title-gradient">{{ trans('app.affiliates.how_it_works_title') }}</span>
                </h2>
                <p class="section-description-center">
                    {{ trans('app.affiliates.how_it_works_description') }}
                </p>
            </div>

            <div class="steps-grid">
                <div class="step-card" data-aos="zoom-in" data-aos-delay="100">
                    <div class="step-number">01</div>
                    <div class="step-icon-wrapper">
                        <div class="step-icon-glow"></div>
                        <i class="fas fa-user-plus step-icon"></i>
                    </div>
                    <h3 class="step-title">{{ trans('app.affiliates.step_1_title') }}</h3>
                    <p class="step-text">{{ trans('app.affiliates.step_1_text') }}</p>
                    <div class="step-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>

                <div class="step-card" data-aos="zoom-in" data-aos-delay="200">
                    <div class="step-number">02</div>
                    <div class="step-icon-wrapper">
                        <div class="step-icon-glow"></div>
                        <i class="fas fa-share-alt step-icon"></i>
                    </div>
                    <h3 class="step-title">{{ trans('app.affiliates.step_2_title') }}</h3>
                    <p class="step-text">{{ trans('app.affiliates.step_2_text') }}</p>
                    <div class="step-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>

                <div class="step-card" data-aos="zoom-in" data-aos-delay="300">
                    <div class="step-number">03</div>
                    <div class="step-icon-wrapper">
                        <div class="step-icon-glow"></div>
                        <i class="fas fa-shopping-cart step-icon"></i>
                    </div>
                    <h3 class="step-title">{{ trans('app.affiliates.step_3_title') }}</h3>
                    <p class="step-text">{{ trans('app.affiliates.step_3_text') }}</p>
                    <div class="step-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>

                <div class="step-card" data-aos="zoom-in" data-aos-delay="400">
                    <div class="step-number">04</div>
                    <div class="step-icon-wrapper">
                        <div class="step-icon-glow"></div>
                        <i class="fas fa-money-bill-wave step-icon"></i>
                    </div>
                    <h3 class="step-title">{{ trans('app.affiliates.step_4_title') }}</h3>
                    <p class="step-text">{{ trans('app.affiliates.step_4_text') }}</p>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="affiliates-section cta-section" data-aos="fade-up" data-aos-delay="400">
            <div class="cta-card">
                <div class="cta-content">
                    <h2 class="cta-title">{{ trans('app.affiliates.cta_title') }}</h2>
                    <p class="cta-text">{{ trans('app.affiliates.cta_text') }}</p>
                    @if(!$userAffiliate && Auth::check())
                    <a href="#form" class="cta-btn">
                        <span>{{ trans('app.affiliates.cta_button') }}</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                    @elseif(!Auth::check())
                    <a href="{{ route('login') }}" class="cta-btn">
                        <span>{{ trans('app.affiliates.cta_login') }}</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                    @endif
                </div>
                <div class="cta-decoration">
                    <div class="cta-orb cta-orb-1"></div>
                    <div class="cta-orb cta-orb-2"></div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- AOS Animation Library -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        offset: 100
    });
</script>

<style>
/* ============================================
   BASE & VARIABLES
   ============================================ */
:root {
    --primary: #06b6d4;
    --primary-dark: #0891b2;
    --primary-light: #22d3ee;
    --success: #10b981;
    --warning: #fbbf24;
    --error: #ef4444;
    --text-primary: #1e293b;
    --text-secondary: #64748b;
    --bg-primary: #ffffff;
    --bg-secondary: #f8fafc;
    --border-color: rgba(6, 182, 212, 0.2);
    --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 20px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 40px rgba(0, 0, 0, 0.15);
    --shadow-glow: 0 0 30px rgba(6, 182, 212, 0.3);
}

body.dark-mode {
    --text-primary: #ffffff;
    --text-secondary: rgba(255, 255, 255, 0.7);
    --bg-primary: rgba(15, 23, 42, 0.8);
    --bg-secondary: rgba(30, 41, 59, 0.6);
    --border-color: rgba(6, 182, 212, 0.3);
}

/* ============================================
   PAGE CONTAINER
   ============================================ */
.affiliates-page {
    min-height: 100vh;
    padding: 80px 20px;
    position: relative;
    overflow-x: hidden;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 50%, #f1f5f9 100%);
    transition: background 0.3s ease;
}

body.dark-mode .affiliates-page {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
}

/* ============================================
   ANIMATED BACKGROUND
   ============================================ */
.affiliates-bg-animation {
    position: fixed;
    inset: 0;
    pointer-events: none;
    z-index: 0;
    overflow: hidden;
}

.bg-gradient-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    opacity: 0.4;
    animation: float 20s ease-in-out infinite;
}

.orb-1 {
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(6, 182, 212, 0.4), transparent);
    top: -200px;
    left: -200px;
    animation-delay: 0s;
}

.orb-2 {
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(16, 185, 129, 0.3), transparent);
    bottom: -150px;
    right: -150px;
    animation-delay: 7s;
}

.orb-3 {
    width: 350px;
    height: 350px;
    background: radial-gradient(circle, rgba(139, 92, 246, 0.3), transparent);
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    animation-delay: 14s;
}

@keyframes float {
    0%, 100% { transform: translate(0, 0) scale(1); }
    33% { transform: translate(30px, -30px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
}

body.dark-mode .bg-gradient-orb {
    opacity: 0.2;
}

/* ============================================
   CONTAINER
   ============================================ */
.affiliates-container {
    max-width: 1400px;
    margin: 0 auto;
    position: relative;
    z-index: 1;
}

/* ============================================
   HERO SECTION
   ============================================ */
.affiliates-hero {
    text-align: center;
    margin-bottom: 40px;
    padding: 40px 20px;
}

.hero-icon-wrapper {
    position: relative;
    display: inline-block;
    margin-bottom: 30px;
}

.hero-icon-glow {
    position: absolute;
    inset: -20px;
    background: radial-gradient(circle, rgba(6, 182, 212, 0.4), transparent);
    border-radius: 50%;
    animation: pulse-glow 3s ease-in-out infinite;
    z-index: -1;
}

@keyframes pulse-glow {
    0%, 100% { transform: scale(1); opacity: 0.6; }
    50% { transform: scale(1.2); opacity: 0.3; }
}

.hero-icon {
    font-size: 5rem;
    color: var(--primary);
    position: relative;
    z-index: 1;
    animation: icon-float 4s ease-in-out infinite;
}

@keyframes icon-float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.hero-title {
    font-size: clamp(2.5rem, 6vw, 4.5rem);
    font-weight: 900;
    margin-bottom: 20px;
    line-height: 1.1;
}

.title-gradient {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 50%, var(--success) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    display: inline-block;
}

.hero-subtitle {
    font-size: clamp(1.1rem, 2vw, 1.4rem);
    color: var(--text-secondary);
    max-width: 800px;
    margin: 0 auto 30px;
    line-height: 1.7;
}

.hero-subtitle strong {
    color: var(--primary);
    font-weight: 700;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 24px;
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(5, 150, 105, 0.2));
    border: 2px solid var(--success);
    border-radius: 50px;
    color: var(--success);
    font-weight: 600;
    font-size: 1rem;
    animation: slideInDown 0.6s ease;
}

/* ============================================
   SECTIONS
   ============================================ */
.affiliates-section {
    margin-bottom: 50px;
}

.section-header {
    margin-bottom: 40px;
}

.section-title {
    font-size: clamp(1.8rem, 4vw, 2.5rem);
    font-weight: 800;
    color: var(--text-primary);
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 15px;
}

.title-icon {
    font-size: 2rem;
}

.section-description {
    font-size: 1.1rem;
    color: var(--text-secondary);
    line-height: 1.7;
}

.section-header-center {
    text-align: center;
    margin-bottom: 30px;
}

.section-title-center {
    font-size: clamp(2rem, 5vw, 3rem);
    font-weight: 900;
    margin-bottom: 20px;
}

.section-description-center {
    font-size: 1.2rem;
    color: var(--text-secondary);
    max-width: 600px;
    margin: 0 auto;
}

/* ============================================
   SUCCESS CARD
   ============================================ */
.success-card {
    max-width: 700px;
    margin: 0 auto;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95));
    backdrop-filter: blur(20px);
    border: 2px solid var(--border-color);
    border-radius: 32px;
    padding: 60px 40px;
    text-align: center;
    box-shadow: var(--shadow-lg);
    position: relative;
    overflow: hidden;
}

body.dark-mode .success-card {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.9), rgba(51, 65, 85, 0.9));
}

.success-card::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(16, 185, 129, 0.1), transparent);
    animation: rotate 20s linear infinite;
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.success-icon-wrapper {
    position: relative;
    display: inline-block;
    margin-bottom: 30px;
}

.success-icon-pulse {
    position: absolute;
    inset: -15px;
    background: radial-gradient(circle, rgba(16, 185, 129, 0.4), transparent);
    border-radius: 50%;
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 0.6; }
    50% { transform: scale(1.3); opacity: 0.2; }
}

.success-icon {
    font-size: 5rem;
    color: var(--success);
    position: relative;
    z-index: 1;
}

.success-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--text-primary);
    margin-bottom: 15px;
}

.success-text {
    font-size: 1.2rem;
    color: var(--text-secondary);
    margin-bottom: 40px;
    line-height: 1.7;
}

.success-btn {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 18px 36px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    border-radius: 16px;
    text-decoration: none;
    font-weight: 700;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 20px rgba(6, 182, 212, 0.4);
    position: relative;
    overflow: hidden;
}

.success-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.5s ease;
}

.success-btn:hover::before {
    left: 100%;
}

.success-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(6, 182, 212, 0.5);
}

/* ============================================
   GRID LAYOUT
   ============================================ */
.affiliates-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    align-items: start;
}

@media (max-width: 1024px) {
    .affiliates-grid {
        grid-template-columns: 1fr;
    }
}

/* ============================================
   BENEFITS COLUMN
   ============================================ */
.benefits-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin-bottom: 40px;
}

.benefit-item {
    display: flex;
    gap: 20px;
    padding: 25px;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(248, 250, 252, 0.9));
    backdrop-filter: blur(20px);
    border: 2px solid var(--border-color);
    border-radius: 20px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

body.dark-mode .benefit-item {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8));
}

.benefit-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(180deg, var(--primary), var(--success));
    transform: scaleY(0);
    transition: transform 0.3s ease;
}

.benefit-item:hover::before {
    transform: scaleY(1);
}

.benefit-item:hover {
    transform: translateX(10px);
    box-shadow: var(--shadow-md);
    border-color: var(--primary);
}

.benefit-icon-wrapper {
    flex-shrink: 0;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(16, 185, 129, 0.2));
    border-radius: 16px;
    border: 2px solid var(--primary);
}

.benefit-icon {
    font-size: 1.5rem;
    color: var(--primary);
}

.benefit-content {
    flex: 1;
}

.benefit-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 8px;
}

.benefit-text {
    font-size: 1rem;
    color: var(--text-secondary);
    line-height: 1.6;
}

/* ============================================
   STATS MINI GRID
   ============================================ */
.stats-mini-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-top: 30px;
}

.form-column .stats-mini-grid {
    margin-top: 30px;
}

.stat-mini-card {
    text-align: center;
    padding: 25px 20px;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(16, 185, 129, 0.1));
    border: 2px solid var(--primary);
    border-radius: 20px;
    transition: all 0.3s ease;
}

.stat-mini-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-glow);
}

.stat-mini-value {
    font-size: 2rem;
    font-weight: 900;
    color: var(--primary);
    margin-bottom: 8px;
}

.stat-mini-label {
    font-size: 0.9rem;
    color: var(--text-secondary);
    font-weight: 600;
}

/* ============================================
   FORM COLUMN
   ============================================ */
.form-card {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95));
    backdrop-filter: blur(20px);
    border: 2px solid var(--border-color);
    border-radius: 32px;
    padding: 40px;
    box-shadow: var(--shadow-lg);
    position: sticky;
    top: 100px;
}

body.dark-mode .form-card {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.9), rgba(51, 65, 85, 0.9));
}

.form-header {
    text-align: center;
    margin-bottom: 35px;
}

.form-icon-wrapper {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(16, 185, 129, 0.2));
    border-radius: 20px;
    border: 2px solid var(--primary);
}

.form-icon {
    font-size: 2rem;
    color: var(--primary);
}

.form-title {
    font-size: 2rem;
    font-weight: 800;
    color: var(--text-primary);
    margin-bottom: 10px;
}

.form-subtitle {
    font-size: 1rem;
    color: var(--text-secondary);
}

/* ============================================
   AUTH ALERT
   ============================================ */
.auth-alert {
    padding: 30px;
    background: linear-gradient(135deg, rgba(251, 191, 36, 0.1), rgba(245, 158, 11, 0.1));
    border: 2px solid var(--warning);
    border-radius: 20px;
    text-align: center;
}

.alert-icon-wrapper {
    width: 60px;
    height: 60px;
    margin: 0 auto 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(251, 191, 36, 0.2);
    border-radius: 50%;
    border: 2px solid var(--warning);
}

.alert-icon-wrapper i {
    font-size: 1.5rem;
    color: var(--warning);
}

.alert-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 10px;
}

.alert-text {
    font-size: 1rem;
    color: var(--text-secondary);
    margin-bottom: 25px;
}

.alert-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 14px 28px;
    background: var(--warning);
    color: white;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 700;
    transition: all 0.3s ease;
}

.alert-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(251, 191, 36, 0.4);
}

/* ============================================
   FORM ELEMENTS
   ============================================ */
.affiliate-form {
    display: flex;
    flex-direction: column;
    gap: 25px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.form-label {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 5px;
}

.required-star {
    color: var(--error);
}

.input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.input-icon {
    position: absolute;
    left: 18px;
    color: var(--primary);
    font-size: 1.1rem;
    z-index: 1;
}

.form-input {
    width: 100%;
    padding: 16px 18px 16px 50px;
    background: rgba(255, 255, 255, 0.8);
    border: 2px solid var(--border-color);
    border-radius: 12px;
    color: var(--text-primary);
    font-size: 1rem;
    transition: all 0.3s ease;
}

body.dark-mode .form-input {
    background: rgba(15, 23, 42, 0.6);
}

.form-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.1);
}

.form-error {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--error);
    font-size: 0.85rem;
    font-weight: 600;
}

.submit-btn {
    position: relative;
    padding: 18px 36px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    border: none;
    border-radius: 16px;
    font-weight: 700;
    font-size: 1.1rem;
    cursor: pointer;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 4px 20px rgba(6, 182, 212, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
}

.submit-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(6, 182, 212, 0.5);
}

.btn-shine {
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.5s ease;
}

.submit-btn:hover .btn-shine {
    left: 100%;
}

.form-footer {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 15px;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.form-footer i {
    color: var(--success);
}

/* ============================================
   HOW IT WORKS
   ============================================ */
.how-it-works {
    padding: 30px 20px;
}

.steps-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 30px;
    position: relative;
}

.step-card {
    position: relative;
    padding: 40px 30px;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(248, 250, 252, 0.9));
    backdrop-filter: blur(20px);
    border: 2px solid var(--border-color);
    border-radius: 24px;
    text-align: center;
    transition: all 0.3s ease;
    overflow: hidden;
}

body.dark-mode .step-card {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8));
}

.step-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.05), rgba(16, 185, 129, 0.05));
    opacity: 0;
    transition: opacity 0.3s ease;
}

.step-card:hover::before {
    opacity: 1;
}

.step-card:hover {
    transform: translateY(-10px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary);
}

.step-number {
    position: absolute;
    top: 20px;
    right: 20px;
    font-size: 3rem;
    font-weight: 900;
    color: rgba(6, 182, 212, 0.1);
    line-height: 1;
}

.step-icon-wrapper {
    position: relative;
    width: 100px;
    height: 100px;
    margin: 0 auto 25px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.step-icon-glow {
    position: absolute;
    inset: -10px;
    background: radial-gradient(circle, rgba(6, 182, 212, 0.3), transparent);
    border-radius: 50%;
    animation: pulse-glow 3s ease-in-out infinite;
}

.step-icon {
    position: relative;
    z-index: 1;
    font-size: 2.5rem;
    color: var(--primary);
}

.step-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 15px;
}

.step-text {
    font-size: 1rem;
    color: var(--text-secondary);
    line-height: 1.7;
    margin-bottom: 20px;
}

.step-arrow {
    position: absolute;
    right: -15px;
    top: 50%;
    transform: translateY(-50%);
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--primary);
    color: white;
    border-radius: 50%;
    font-size: 1rem;
    box-shadow: 0 4px 15px rgba(6, 182, 212, 0.4);
}

.step-card:last-child .step-arrow {
    display: none;
}

@media (max-width: 768px) {
    .step-arrow {
        display: none;
    }
}

/* ============================================
   CTA SECTION
   ============================================ */
.cta-section {
    padding: 30px 20px;
}

.cta-card {
    position: relative;
    max-width: 900px;
    margin: 0 auto;
    padding: 60px 40px;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(16, 185, 129, 0.1));
    border: 2px solid var(--primary);
    border-radius: 32px;
    text-align: center;
    overflow: hidden;
}

.cta-content {
    position: relative;
    z-index: 2;
}

.cta-title {
    font-size: clamp(2rem, 5vw, 3rem);
    font-weight: 900;
    color: var(--text-primary);
    margin-bottom: 20px;
}

.cta-text {
    font-size: 1.2rem;
    color: var(--text-secondary);
    margin-bottom: 35px;
    line-height: 1.7;
}

.cta-btn {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 18px 40px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    border-radius: 16px;
    text-decoration: none;
    font-weight: 700;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 20px rgba(6, 182, 212, 0.4);
}

.cta-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(6, 182, 212, 0.5);
}

.cta-decoration {
    position: absolute;
    inset: 0;
    z-index: 1;
    pointer-events: none;
}

.cta-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(60px);
    opacity: 0.3;
    animation: float 15s ease-in-out infinite;
}

.cta-orb-1 {
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(6, 182, 212, 0.5), transparent);
    top: -100px;
    left: -100px;
}

.cta-orb-2 {
    width: 250px;
    height: 250px;
    background: radial-gradient(circle, rgba(16, 185, 129, 0.5), transparent);
    bottom: -80px;
    right: -80px;
    animation-delay: 7s;
}

/* ============================================
   RESPONSIVE
   ============================================ */
@media (max-width: 1200px) {
    .steps-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .affiliates-page {
        padding: 40px 15px;
    }

    .affiliates-hero {
        margin-bottom: 50px;
        padding: 20px 10px;
    }

    .hero-icon {
        font-size: 3.5rem;
    }

    .affiliates-section {
        margin-bottom: 60px;
    }

    .form-card {
        position: static;
        padding: 30px 20px;
    }

    .stats-mini-grid {
        grid-template-columns: 1fr;
    }

    .steps-grid {
        grid-template-columns: 1fr;
    }

    .step-card {
        padding: 30px 20px;
    }
}

/* ============================================
   ANIMATIONS
   ============================================ */
@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
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
</style>
@endsection
