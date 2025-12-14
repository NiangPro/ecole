@extends('layouts.app')

@php
    if (!app()->getLocale()) {
        app()->setLocale(session('language', 'fr'));
    }
@endphp

@section('title', trans('app.monetization.title'))
@section('meta_description', trans('app.monetization.meta_description'))
@section('meta_keywords', 'monétisation, abonnement, cours payants, contenu premium, niangprogrammeur')

@section('content')
<div class="monetization-page" style="background: #ffffff !important;">
    <div class="monetization-container">
        
        <!-- Hero Section -->
        <div class="monetization-hero">
            <h1 class="monetization-title">
                {{ trans('app.monetization.hero_title') }}
            </h1>
            <p class="monetization-subtitle">
                {{ trans('app.monetization.hero_subtitle') }}
            </p>
        </div>

        <!-- Plans d'Abonnement -->
        @if($subscriptionPlans->count() > 0)
        <section class="monetization-section">
            <h2 class="section-title">
                <i class="fas fa-crown section-icon"></i>
                {{ trans('app.monetization.subscription_plans.title') }}
            </h2>
            
            <div class="subscription-plans-grid">
                @foreach($subscriptionPlans as $planSlug => $plan)
                <div class="subscription-plan-card {{ $plan['is_featured'] ? 'featured' : '' }}">
                    @if($plan['is_featured'])
                    <div style="position: absolute; top: 20px; right: -30px; background: linear-gradient(135deg, #fbbf24, #f59e0b); color: white; padding: 8px 40px; transform: rotate(45deg); font-weight: 700; font-size: 0.85rem; box-shadow: 0 4px 10px rgba(251, 191, 36, 0.4);">
                        {{ $plan['badge'] ?? trans('app.monetization.subscription_plans.badge_popular') }}
                    </div>
                    @endif
                    
                    <div style="position: absolute; top: -50px; right: -50px; width: 150px; height: 150px; background: radial-gradient(circle, rgba(6, 182, 212, 0.2), transparent); border-radius: 50%;"></div>
                    
                    <div class="plan-header">
                        <h3 class="plan-name">
                            {{ $plan['name'] }}
                        </h3>
                        <div class="plan-price-wrapper">
                            <span class="plan-price">{{ number_format($plan['price'], 0, ',', ' ') }}</span>
                            <span class="plan-currency"> {{ $plan['currency'] }}</span>
                        </div>
                        <p class="plan-period">
                            {{ $plan['billing_period'] === 'yearly' ? trans('app.monetization.subscription_plans.per_year') : trans('app.monetization.subscription_plans.per_month') }}
                        </p>
                        @if($plan['description'])
                        <p class="plan-description">
                            {{ $plan['description'] }}
                        </p>
                        @endif
                    </div>

                    @if(!empty($plan['features']))
                    <ul class="plan-features">
                        @foreach($plan['features'] as $feature)
                        <li class="plan-feature-item">
                            <i class="fas fa-check-circle plan-feature-icon"></i>
                            <span>{{ $feature }}</span>
                        </li>
                        @endforeach
                    </ul>
                    @endif

                    @auth
                    <form action="{{ route('payment.subscription') }}" method="POST" style="margin-top: 30px;">
                        @csrf
                        <input type="hidden" name="plan_type" value="{{ $planSlug }}">
                        <input type="hidden" name="payment_method" value="mobile_money">
                        @if(request()->has('ref'))
                        <input type="hidden" name="ref_code" value="{{ request()->get('ref') }}">
                        @endif
                        <button type="submit" class="subscription-btn" style="width: 100%; padding: 15px; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: white; border: none; border-radius: 12px; font-size: 1.1rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);">
                            <i class="fas fa-credit-card" style="margin-right: 8px;"></i>
                            {{ trans('app.monetization.subscription_plans.subscribe_now') }}
                        </button>
                    </form>
                    @else
                    <a href="{{ route('login') }}" class="subscription-btn" style="display: block; width: 100%; padding: 15px; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: white; border: none; border-radius: 12px; font-size: 1.1rem; font-weight: 600; text-align: center; text-decoration: none; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);">
                        <i class="fas fa-sign-in-alt" style="margin-right: 8px;"></i>
                        {{ trans('app.monetization.subscription_plans.login_to_subscribe') }}
                    </a>
                    @endauth
                </div>
                @endforeach
            </div>
        </section>
        @endif

        <!-- Cours Payants -->
        @if($paidCourses->count() > 0)
        <section class="monetization-section">
            <div class="courses-header">
                <h2 class="section-title">
                    <i class="fas fa-graduation-cap section-icon"></i>
                    {{ trans('app.monetization.paid_courses.title') }}
                </h2>
                <a href="{{ route('monetization.courses') }}" class="view-all-btn">
                    {{ trans('app.monetization.paid_courses.view_all') }} <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="paid-courses-grid">
                @foreach($paidCourses as $course)
                <a href="{{ route('monetization.course.show', $course->slug) }}" class="paid-course-card-link">
                    <div class="paid-course-card">
                        <!-- Badge de réduction -->
                        @if($course->hasDiscount())
                        <div style="position: absolute; top: 16px; right: 16px; background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 8px 16px; border-radius: 20px; font-weight: 800; font-size: 0.9rem; z-index: 10; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.5); display: flex; align-items: center; gap: 6px;">
                            <i class="fas fa-tag"></i>
                            <span>-{{ $course->discount_percentage }}%</span>
                        </div>
                        @endif
                        
                        <!-- Image du cours -->
                        <div style="width: 100%; height: 180px; position: relative; overflow: hidden; background: linear-gradient(135deg, #06b6d4, #14b8a6);">
                            @if($course->cover_image)
                                @if(($course->cover_type ?? 'internal') === 'internal')
                                    <img src="{{ asset('storage/' . $course->cover_image) }}" alt="{{ $course->localized_title }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;" loading="lazy">
                                @else
                                    <img src="{{ $course->cover_image }}" alt="{{ $course->localized_title }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;" loading="lazy" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div style="width: 100%; height: 100%; display: none; align-items: center; justify-content: center; background: linear-gradient(135deg, #06b6d4, #14b8a6);">
                                        <i class="fas fa-graduation-cap" style="font-size: 3rem; color: white; opacity: 0.5;"></i>
                                    </div>
                                @endif
                            @else
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #06b6d4, #14b8a6);">
                                    <i class="fas fa-graduation-cap" style="font-size: 3rem; color: white; opacity: 0.5;"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="course-card-content">
                            <h3 class="course-card-title">
                                {{ $course->localized_title }}
                            </h3>
                            @if($course->localized_description)
                            <p class="course-card-description">
                                {{ $course->localized_description }}
                            </p>
                            @endif
                            
                            <div class="course-card-footer">
                                <div class="course-price-wrapper">
                                    @if($course->hasDiscount())
                                    <span class="course-price-discount">{{ number_format($course->discount_price, 0, ',', ' ') }} FCFA</span>
                                    <span class="course-price-original">{{ number_format($course->price, 0, ',', ' ') }} FCFA</span>
                                    @else
                                    <span class="course-price">{{ number_format($course->price, 0, ',', ' ') }} FCFA</span>
                                    @endif
                                </div>
                                <i class="fas fa-arrow-right course-arrow-icon"></i>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </section>
        @endif

    </div>
</div>

<style>
/* Forcer le fond blanc en mode clair - Solution robuste */
body:not(.dark-mode) #main-content {
    background: #ffffff !important;
}

body:not(.dark-mode) .monetization-page {
    background: #ffffff !important;
}

/* Container - Styles optimisés */
.monetization-page {
    min-height: 100vh;
    padding: 40px 20px;
    background: #ffffff !important;
    transition: background 0.3s ease;
}

body.dark-mode .monetization-page {
    background: linear-gradient(to bottom right, #0f172a, #1e293b, #0f172a) !important;
}

.monetization-container {
    max-width: 1200px;
    margin: 0 auto;
}

/* Hero Section */
.monetization-hero {
    text-align: center;
    margin-bottom: 60px;
    padding: 60px 20px;
}

.monetization-title {
    font-size: 3rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 20px;
    text-shadow: 0 4px 20px rgba(6, 182, 212, 0.2);
    transition: color 0.3s ease;
}

body.dark-mode .monetization-title {
    color: white;
    text-shadow: 0 4px 20px rgba(6, 182, 212, 0.3);
}

.monetization-subtitle {
    font-size: 1.25rem;
    color: #475569;
    max-width: 700px;
    margin: 0 auto;
    transition: color 0.3s ease;
}

body.dark-mode .monetization-subtitle {
    color: rgba(255, 255, 255, 0.8);
}

/* Sections */
.monetization-section {
    margin-bottom: 80px;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #1e293b;
    text-align: center;
    margin-bottom: 50px;
    transition: color 0.3s ease;
}

body.dark-mode .section-title {
    color: white;
}

.section-icon {
    color: #fbbf24;
    margin-right: 10px;
}

.courses-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 40px;
    flex-wrap: wrap;
    gap: 20px;
}

.courses-header .section-title {
    text-align: left;
    margin-bottom: 0;
}

.view-all-btn {
    padding: 12px 24px;
    background: rgba(6, 182, 212, 0.2);
    color: #06b6d4;
    border: 2px solid #06b6d4;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.view-all-btn {
    background: rgba(6, 182, 212, 0.1);
    color: #0891b2;
    border-color: #06b6d4;
}

body.dark-mode .view-all-btn {
    background: rgba(6, 182, 212, 0.2);
    color: #06b6d4;
}

.view-all-btn:hover {
    background: rgba(6, 182, 212, 0.2);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
}

body.dark-mode .view-all-btn:hover {
    background: rgba(6, 182, 212, 0.3);
}

/* Subscription Plans */
.subscription-plans-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    margin-bottom: 40px;
}

.subscription-plan-card {
    background: #ffffff !important;
    border: 2px solid rgba(6, 182, 212, 0.3);
    border-radius: 20px;
    padding: 40px 30px;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

body.dark-mode .subscription-plan-card {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8)) !important;
    box-shadow: none;
}

.subscription-plan-card.featured {
    transform: scale(1.05);
    border-color: #fbbf24;
}

body.light-mode .subscription-plan-card.featured {
    border-color: #fbbf24;
}

.subscription-plan-card h3 {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 10px;
    transition: color 0.3s ease;
}

body.dark-mode .subscription-plan-card h3 {
    color: white;
}

.subscription-plan-card p {
    color: #64748b;
    transition: color 0.3s ease;
}

body.dark-mode .subscription-plan-card p {
    color: rgba(255, 255, 255, 0.7);
}

.subscription-plan-card li {
    color: #334155;
    transition: color 0.3s ease;
}

body.dark-mode .subscription-plan-card li {
    color: rgba(255, 255, 255, 0.9);
}

/* Plan Card Text Styles */
.plan-header {
    text-align: center;
    margin-bottom: 30px;
}

.plan-name {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 10px;
    transition: color 0.3s ease;
}

body.dark-mode .plan-name {
    color: white;
}

.plan-price-wrapper {
    margin: 20px 0;
}

.plan-price {
    font-size: 3rem;
    font-weight: 800;
    color: #06b6d4;
}

.plan-currency {
    font-size: 1.25rem;
    color: #64748b;
    transition: color 0.3s ease;
}

body.dark-mode .plan-currency {
    color: rgba(255, 255, 255, 0.6);
}

.plan-period {
    color: #64748b;
    font-size: 0.95rem;
    transition: color 0.3s ease;
}

body.dark-mode .plan-period {
    color: rgba(255, 255, 255, 0.7);
}

.plan-description {
    color: #475569;
    font-size: 0.9rem;
    margin-top: 10px;
    transition: color 0.3s ease;
}

body.dark-mode .plan-description {
    color: rgba(255, 255, 255, 0.8);
}

.plan-features {
    list-style: none;
    padding: 0;
    margin-bottom: 30px;
}

.plan-feature-item {
    padding: 12px 0;
    color: #334155;
    display: flex;
    align-items: center;
    transition: color 0.3s ease;
}

body.dark-mode .plan-feature-item {
    color: rgba(255, 255, 255, 0.9);
}

.plan-feature-icon {
    color: #10b981;
    margin-right: 12px;
    font-size: 1.1rem;
}

/* Paid Courses */
.paid-courses-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 25px;
}

.paid-course-card-link {
    text-decoration: none;
    display: block;
}

.paid-course-card {
    background: #ffffff !important;
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

body.dark-mode .paid-course-card {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8)) !important;
    box-shadow: none;
}

.paid-course-card h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 10px;
    line-height: 1.4;
    transition: color 0.3s ease;
}

body.dark-mode .paid-course-card h3 {
    color: white;
}

.paid-course-card p {
    color: #64748b;
    transition: color 0.3s ease;
}

body.dark-mode .paid-course-card p {
    color: rgba(255, 255, 255, 0.7);
}

/* Course Card Text Styles */
.course-card-content {
    padding: 20px;
}

.course-card-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 10px;
    line-height: 1.4;
    transition: color 0.3s ease;
}

body.dark-mode .course-card-title {
    color: white;
}

.course-card-description {
    color: #64748b;
    font-size: 0.9rem;
    margin-bottom: 15px;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    transition: color 0.3s ease;
}

body.dark-mode .course-card-description {
    color: rgba(255, 255, 255, 0.7);
}

.course-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 15px;
}

.course-price-wrapper {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.course-price,
.course-price-discount {
    font-size: 1.5rem;
    font-weight: 700;
    color: #06b6d4;
}

.course-price-original {
    font-size: 1rem;
    color: #94a3b8;
    text-decoration: line-through;
    transition: color 0.3s ease;
}

body.dark-mode .course-price-original {
    color: rgba(255, 255, 255, 0.5);
}

.course-arrow-icon {
    color: #06b6d4;
}

/* Effets hover */
.paid-course-card-link:hover .paid-course-card {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(6, 182, 212, 0.4);
}

.paid-course-card-link:hover .paid-course-card {
    box-shadow: 0 8px 30px rgba(6, 182, 212, 0.2);
}

body.dark-mode .paid-course-card-link:hover .paid-course-card {
    box-shadow: 0 8px 30px rgba(6, 182, 212, 0.4);
}

.paid-course-card-link:hover .paid-course-card img {
    transform: scale(1.1);
}

.subscription-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(6, 182, 212, 0.2) !important;
}

body.dark-mode .subscription-btn:hover {
    box-shadow: 0 6px 20px rgba(6, 182, 212, 0.4) !important;
}

/* Responsive */
    @media (max-width: 768px) {
    .monetization-title { 
        font-size: 2rem !important; 
    }
    .section-title { 
        font-size: 1.75rem !important; 
    }
    .subscription-plan-card.featured {
        transform: none !important;
    }
    .courses-header {
        flex-direction: column;
        align-items: flex-start;
    }
    .courses-header .section-title {
        text-align: center;
        width: 100%;
            }
        }
</style>
@endsection
