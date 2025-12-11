@extends('layouts.app')

@php
    // S'assurer que la locale est définie avant toute traduction
    if (!app()->getLocale()) {
        app()->setLocale(session('language', 'fr'));
    }
@endphp

@section('title', ($pageTitle ?? 'Dashboard') . ' | NiangProgrammeur')

@section('styles')
<style>
    * {
        box-sizing: border-box;
    }
    
    /* Dashboard Container - EXACTEMENT comme formations/html5 */
    .dashboard-wrapper {
        max-width: 1400px;
        margin: 0 auto;
        background: #ffffff;
        width: 100%;
        min-height: calc(100vh - 70px);
    }
    
    body.dark-mode .dashboard-wrapper {
        background: #0a0a0f;
    }
    
    .content-wrapper {
        display: flex;
        gap: 20px;
        padding: 20px;
        width: 100%;
        margin: 0;
        align-items: flex-start;
        position: relative;
    }
    
    /* Sidebar - Solution garantie avec sticky + fallback fixed */
    .dashboard-sidebar {
        width: 280px;
        flex-shrink: 0;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        padding: 15px 25px 25px 25px;
        border-radius: 15px;
        position: -webkit-sticky;
        position: sticky;
        top: 80px; /* 60px navbar (body padding-top) + 20px content-wrapper padding */
        align-self: flex-start;
        height: calc(100vh - 80px);
        max-height: calc(100vh - 80px);
        overflow-y: auto;
        overflow-x: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(4, 170, 109, 0.1);
        z-index: 1000;
        will-change: position, top;
    }
    
    /* FORCER le sidebar à ne PAS être sticky en mobile - EXACTEMENT comme formations/html5 */
    @media (max-width: 992px) {
        .dashboard-sidebar,
        .dashboard-sidebar#dashboardSidebar,
        aside.dashboard-sidebar,
        .content-wrapper .dashboard-sidebar {
            position: fixed !important;
            top: auto !important;
            align-self: auto !important;
            flex-shrink: 0 !important;
            width: 85% !important;
            max-width: 400px !important;
        }
    }
    
    body.dark-mode .dashboard-sidebar {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.95) 0%, rgba(30, 41, 59, 0.9) 100%);
        border-color: rgba(4, 170, 109, 0.3);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
    
    .dashboard-sidebar::-webkit-scrollbar {
        width: 6px;
    }
    
    .dashboard-sidebar::-webkit-scrollbar-track {
        background: transparent;
        border-radius: 10px;
    }
    
    .dashboard-sidebar::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #06b6d4 0%, #0891b2 100%);
        border-radius: 10px;
    }
    
    .dashboard-sidebar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #0891b2 0%, #0e7490 100%);
    }
    
    /* Sidebar Header */
    .sidebar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid rgba(6, 182, 212, 0.2);
    }
    
    .sidebar-header h3 {
        color: #06b6d4;
        font-size: 20px;
        margin: 0;
        font-weight: 700;
        letter-spacing: -0.5px;
    }
    
    body.dark-mode .sidebar-header h3 {
        color: #06b6d4;
        border-bottom-color: rgba(4, 170, 109, 0.3);
    }
    
    .sidebar-user-card {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .sidebar-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.1rem;
        font-weight: 700;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(6, 182, 212, 0.3);
    }
    
    .sidebar-user-info h3 {
        font-size: 0.95rem;
        font-weight: 700;
        color: #2c3e50;
        margin: 0 0 0.125rem 0;
        line-height: 1.3;
    }
    
    body.dark-mode .sidebar-user-info h3 {
        color: rgba(255, 255, 255, 0.9);
    }
    
    .sidebar-user-info p {
        font-size: 0.8rem;
        color: #64748b;
        margin: 0;
    }
    
    body.dark-mode .sidebar-user-info p {
        color: rgba(255, 255, 255, 0.6);
    }
    
    /* Sidebar Navigation - Style formations/html5 */
    .sidebar-nav {
        padding: 0;
    }
    
    .nav-section {
        margin-bottom: 1.5rem;
    }
    
    .nav-section-title {
        font-size: 0.75rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        padding: 0 0 10px 0;
        margin-bottom: 10px;
        border-bottom: 1px solid rgba(6, 182, 212, 0.1);
    }
    
    body.dark-mode .nav-section-title {
        color: rgba(255, 255, 255, 0.5);
    }
    
    .nav-item {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        color: #2c3e50;
        text-decoration: none;
        border-radius: 10px;
        margin-bottom: 6px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 14px;
        font-weight: 500;
        position: relative;
        overflow: hidden;
        gap: 10px;
    }
    
    body.dark-mode .nav-item {
        color: rgba(255, 255, 255, 0.8);
    }
    
    .nav-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 3px;
        background: #06b6d4;
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }
    
    .nav-item i {
        width: 20px;
        text-align: center;
        font-size: 1rem;
        flex-shrink: 0;
    }
    
    .nav-item:hover {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(6, 182, 212, 0.05) 100%);
        color: #06b6d4;
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(6, 182, 212, 0.15);
    }
    
    body.dark-mode .nav-item:hover {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.2) 0%, rgba(6, 182, 212, 0.1) 100%);
        color: #06b6d4;
    }
    
    .nav-item:hover::before {
        transform: scaleY(1);
    }
    
    .nav-item.active {
        background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 100%);
        color: white;
        font-weight: 600;
        box-shadow: 0 6px 20px rgba(6, 182, 212, 0.3);
        transform: translateX(5px);
    }
    
    .nav-item.active::before {
        transform: scaleY(1);
        background: white;
    }
    
    .nav-item.active i {
        color: white;
    }
    
    .nav-badge {
        margin-left: auto;
        padding: 2px 8px;
        background: rgba(255, 255, 255, 0.3);
        color: white;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 700;
        min-width: 20px;
        text-align: center;
    }
    
    /* Main Content */
    .dashboard-main {
        flex: 1 1 auto;
        min-width: 0;
        background: white;
        padding: 30px;
        border-radius: 15px;
        overflow-x: hidden;
        max-width: 100%;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }
    
    body.dark-mode .dashboard-main {
        background: rgba(15, 23, 42, 0.5);
    }
    
    /* Page Header */
    .page-header {
        margin-bottom: 2.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid rgba(4, 170, 109, 0.1);
    }
    
    .page-title {
        color: #2c3e50;
        font-size: 2.5rem;
        font-weight: 800;
        margin: 0 0 0.75rem 0;
        line-height: 1.2;
        letter-spacing: -0.02em;
    }
    
    body.dark-mode .page-title {
        color: #ffffff;
    }
    
    .page-subtitle {
        color: #64748b;
        font-size: 1.1rem;
        margin: 0;
        font-weight: 400;
        line-height: 1.6;
    }
    
    body.dark-mode .page-subtitle {
        color: rgba(255, 255, 255, 0.7);
    }
    
    /* Content Cards - Design Éducatif */
    .content-card {
        background: white;
        border: 1px solid rgba(4, 170, 109, 0.1);
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    body.dark-mode .content-card {
        background: rgba(15, 23, 42, 0.6);
        border-color: rgba(4, 170, 109, 0.2);
    }
    
    .content-card:hover {
        border-color: rgba(4, 170, 109, 0.3);
        box-shadow: 0 8px 25px rgba(4, 170, 109, 0.15);
        transform: translateY(-3px);
    }
    
    .card-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2c3e50;
        margin: 0 0 1.5rem 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid rgba(4, 170, 109, 0.1);
    }
    
    body.dark-mode .card-title {
        color: #ffffff;
        border-bottom-color: rgba(4, 170, 109, 0.3);
    }
    
    .card-title i {
        font-size: 1.3rem;
        color: #06b6d4;
    }
    
    /* Mobile Styles */
    @media (max-width: 992px) {
        .content-wrapper {
            flex-direction: column;
        }
        
        /* Sidebar caché par défaut en mobile - FORCER avec toutes les propriétés */
        .dashboard-sidebar,
        .dashboard-sidebar#dashboardSidebar,
        aside.dashboard-sidebar {
            display: none !important;
            position: fixed !important;
            bottom: 0 !important;
            left: 0 !important;
            width: 85% !important;
            max-width: 400px !important;
            height: 70vh !important;
            max-height: 600px !important;
            border-radius: 20px 20px 0 0 !important;
            transform: translateY(100%) !important;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.4s ease !important;
            z-index: 9999 !important;
            box-shadow: 0 -10px 50px rgba(0, 0, 0, 0.3) !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            opacity: 0 !important;
            visibility: hidden !important;
            top: auto !important;
            align-self: auto !important;
            flex-shrink: 0 !important;
        }
        
        .dashboard-sidebar.active,
        .dashboard-sidebar#dashboardSidebar.active,
        aside.dashboard-sidebar.active {
            display: block !important;
            transform: translateY(0) !important;
            opacity: 1 !important;
            visibility: visible !important;
        }
        
        /* Bouton burger visible en mobile - FORCER */
        .sidebar-toggle-btn,
        button.sidebar-toggle-btn,
        #sidebarToggle {
            display: flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            position: fixed;
            bottom: 20px;
            left: 20px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #04AA6D, #038f5a);
            border: none;
            border-radius: 50%;
            color: white;
            font-size: 24px;
            cursor: pointer;
            z-index: 10000;
            box-shadow: 0 8px 25px rgba(4, 170, 109, 0.4);
            transition: all 0.3s ease;
            align-items: center;
            justify-content: center;
        }
        
        .sidebar-toggle-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 12px 35px rgba(4, 170, 109, 0.6);
        }
        
        .sidebar-overlay {
            display: none !important;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
            z-index: 9998;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .sidebar-overlay.active {
            display: block !important;
            opacity: 1;
        }
        
        .dashboard-main {
            width: 100%;
            padding: 20px;
        }
    }
    
    @media (max-width: 768px) {
        .dashboard-sidebar {
            width: 100% !important;
            max-width: 100% !important;
            height: 80vh;
            max-height: 80vh;
            border-radius: 25px 25px 0 0;
        }
        
        .sidebar-toggle-btn {
            display: flex !important;
            width: 55px;
            height: 55px;
            font-size: 22px;
            bottom: 15px;
            left: 15px;
        }
    }
</style>
@endsection

@section('content')
<div class="dashboard-wrapper">
    <div class="content-wrapper">
        <!-- Sidebar Toggle Button (Mobile) -->
        <button class="sidebar-toggle-btn" id="sidebarToggle" style="display: none;" aria-label="{{ trans('app.profile.sidebar.open_menu') }}">
            <i class="fas fa-bars" id="sidebarToggleIcon"></i>
        </button>
        
        <!-- Sidebar Overlay (Mobile) -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        
        <!-- Sidebar -->
        <aside class="dashboard-sidebar" id="dashboardSidebar">
            <div class="sidebar-header">
                <div class="sidebar-user-card">
                    <div class="sidebar-avatar">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="sidebar-user-info">
                        <h3>{{ Auth::user()->name ?? 'Utilisateur' }}</h3>
                        <p>{{ Auth::user()->email ?? '' }}</p>
                    </div>
                </div>
                <button class="sidebar-close-btn" id="sidebarClose" style="display: none; background: none; border: none; color: #04AA6D; font-size: 24px; cursor: pointer; padding: 5px; width: 35px; height: 35px; border-radius: 50%; transition: all 0.3s ease;" aria-label="{{ trans('app.profile.sidebar.close_menu') }}">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <p class="nav-section-title">{{ trans('app.profile.sidebar.navigation') }}</p>
                    
                    <a href="{{ route('dashboard.overview') }}" class="nav-item {{ request()->routeIs('dashboard.overview') || request()->routeIs('dashboard.index') ? 'active' : '' }}" data-no-loader="true">
                        <i class="fas fa-home"></i>
                        <span>{{ trans('app.profile.sidebar.overview') }}</span>
                    </a>
                    
                    <a href="{{ route('dashboard.formations') }}" class="nav-item {{ request()->routeIs('dashboard.formations') ? 'active' : '' }}" data-no-loader="true">
                        <i class="fas fa-book"></i>
                        <span>{{ trans('app.profile.sidebar.formations') }}</span>
                    </a>
                    
                    <a href="{{ route('dashboard.paid-courses') }}" class="nav-item {{ request()->routeIs('dashboard.paid-courses.*') ? 'active' : '' }}" data-no-loader="true">
                        <i class="fas fa-graduation-cap"></i>
                        <span>Cours Payants</span>
                    </a>
                    
                    <a href="{{ route('dashboard.subscriptions') }}" class="nav-item {{ request()->routeIs('dashboard.subscriptions') ? 'active' : '' }}" data-no-loader="true">
                        <i class="fas fa-crown"></i>
                        <span>Mes Abonnements</span>
                    </a>
                    
                    <a href="{{ route('dashboard.exercices') }}" class="nav-item {{ request()->routeIs('dashboard.exercices') ? 'active' : '' }}" data-no-loader="true">
                        <i class="fas fa-code"></i>
                        <span>{{ trans('app.profile.sidebar.exercises') }}</span>
                    </a>
                    
                    <a href="{{ route('dashboard.quiz') }}" class="nav-item {{ request()->routeIs('dashboard.quiz') ? 'active' : '' }}" data-no-loader="true">
                        <i class="fas fa-question-circle"></i>
                        <span>{{ trans('app.profile.sidebar.quiz') }}</span>
                    </a>
                    
                    <a href="{{ route('dashboard.goals') }}" class="nav-item {{ request()->routeIs('dashboard.goals') ? 'active' : '' }}" data-no-loader="true">
                        <i class="fas fa-bullseye"></i>
                        <span>{{ trans('app.profile.sidebar.goals') }}</span>
                        @if(isset($goals) && $goals->where('completed', false)->count() > 0)
                            <span class="nav-badge">{{ $goals->where('completed', false)->count() }}</span>
                        @endif
                    </a>
                    
                    <a href="{{ route('dashboard.activities') }}" class="nav-item {{ request()->routeIs('dashboard.activities') ? 'active' : '' }}" data-no-loader="true">
                        <i class="fas fa-history"></i>
                        <span>{{ trans('app.profile.sidebar.activity') }}</span>
                    </a>
                    
                    <a href="{{ route('dashboard.statistics') }}" class="nav-item {{ request()->routeIs('dashboard.statistics') ? 'active' : '' }}" data-no-loader="true">
                        <i class="fas fa-chart-line"></i>
                        <span>{{ trans('app.profile.dashboard.statistics.title') }}</span>
                    </a>
                    
                    <a href="{{ route('dashboard.badges') }}" class="nav-item {{ request()->routeIs('dashboard.badges') ? 'active' : '' }}" data-no-loader="true">
                        <i class="fas fa-trophy"></i>
                        <span>{{ trans('app.profile.sidebar.badges') ?? 'Badges' }}</span>
                    </a>
                    
                    <a href="{{ route('dashboard.certificates') }}" class="nav-item {{ request()->routeIs('dashboard.certificates') ? 'active' : '' }}" data-no-loader="true">
                        <i class="fas fa-certificate"></i>
                        <span>{{ trans('app.profile.sidebar.certificates') ?? 'Certificats' }}</span>
                    </a>
                    
                    <a href="{{ route('dashboard.favorites') }}" class="nav-item {{ request()->routeIs('dashboard.favorites') ? 'active' : '' }}" data-no-loader="true">
                        <i class="fas fa-heart"></i>
                        <span>{{ trans('app.profile.sidebar.favorites') ?? 'Favoris' }}</span>
                    </a>
                    
                    <a href="{{ route('dashboard.notifications') }}" class="nav-item {{ request()->routeIs('dashboard.notifications') ? 'active' : '' }}" data-no-loader="true">
                        <i class="fas fa-bell"></i>
                        <span>{{ trans('app.profile.sidebar.notifications') ?? 'Notifications' }}</span>
                        @php
                            $unreadCount = Auth::check() ? \App\Models\Notification::countUnread(Auth::id()) : 0;
                        @endphp
                        @if($unreadCount > 0)
                        <span class="notification-count-badge" style="
                            background: #ef4444;
                            color: white;
                            border-radius: 50%;
                            width: 20px;
                            height: 20px;
                            display: inline-flex;
                            align-items: center;
                            justify-content: center;
                            font-size: 0.7rem;
                            font-weight: 700;
                            margin-left: auto;
                        ">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
                        @endif
                    </a>
                </div>
                
                <div class="nav-section">
                    <p class="nav-section-title">{{ trans('app.profile.sidebar.account') }}</p>
                    
                    <a href="{{ route('dashboard.profile') }}" class="nav-item {{ request()->routeIs('dashboard.profile') ? 'active' : '' }}" data-no-loader="true">
                        <i class="fas fa-user"></i>
                        <span>{{ trans('app.profile.sidebar.profile') }}</span>
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-item" style="width: 100%; border: none; background: none; cursor: pointer; color: #ef4444; text-align: left; padding-left: 16px;">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>{{ trans('app.profile.sidebar.logout') }}</span>
                        </button>
                    </form>
                </div>
            </nav>
            
            @stack('sidebar-extra')
        </aside>
        
        <!-- Main Content -->
        <main class="dashboard-main">
            <div class="page-header">
                <h1 class="page-title">{{ $pageTitle ?? trans('app.profile.dashboard.default_title') ?? 'Tableau de Bord' }}</h1>
                <p class="page-subtitle">{{ $pageDescription ?? trans('app.profile.dashboard.default_description') ?? 'Suivez votre progression et vos performances' }}</p>
            </div>
            
            @yield('dashboard-content')
        </main>
    </div>
</div>

<script>
    // FORCER LE STICKY UNIQUEMENT - Pas de fallback fixed
    (function() {
        'use strict';
        
        function isMobile() {
            return window.innerWidth <= 992;
        }
        
        function forceSticky() {
            if (isMobile()) return;
            
            const sidebar = document.getElementById('dashboardSidebar');
            const contentWrapper = document.querySelector('.content-wrapper');
            const dashboardWrapper = document.querySelector('.dashboard-wrapper');
            const body = document.body;
            const html = document.documentElement;
            
            if (!sidebar || !contentWrapper || !dashboardWrapper) return;
            
            // ÉTAPE 1 : Forcer overflow: visible sur TOUS les parents (CRITIQUE pour sticky)
            const parents = [body, html, dashboardWrapper, contentWrapper];
            parents.forEach(parent => {
                if (parent) {
                    const computed = window.getComputedStyle(parent);
                    // Vérifier overflow
                    if (computed.overflow !== 'visible') {
                        parent.style.overflow = 'visible';
                    }
                    // Vérifier overflow-y
                    if (computed.overflowY !== 'visible') {
                        parent.style.overflowY = 'visible';
                    }
                    // Vérifier overflow-x
                    if (computed.overflowX !== 'visible' && computed.overflowX !== 'hidden') {
                        parent.style.overflowX = 'visible';
                    }
                }
            });
            
            // ÉTAPE 2 : Forcer position: relative sur dashboard-wrapper (parent du sticky)
            dashboardWrapper.style.position = 'relative';
            dashboardWrapper.style.overflow = 'visible';
            
            // ÉTAPE 3 : Forcer flexbox avec align-items: flex-start sur content-wrapper
            contentWrapper.style.display = 'flex';
            contentWrapper.style.alignItems = 'flex-start';
            contentWrapper.style.overflow = 'visible';
            contentWrapper.style.position = 'relative';
            
            // ÉTAPE 4 : Forcer sticky sur sidebar (SANS fallback fixed)
            const navbarHeight = 60;
            const contentPadding = 20;
            const topValue = navbarHeight + contentPadding;
            
            // Retirer toute position fixed qui pourrait avoir été appliquée
            if (sidebar.style.position === 'fixed') {
                sidebar.style.position = '';
                sidebar.style.left = '';
                sidebar.style.width = '';
            }
            
            // Forcer sticky avec !important
            sidebar.style.setProperty('position', 'sticky', 'important');
            sidebar.style.setProperty('top', topValue + 'px', 'important');
            sidebar.style.setProperty('align-self', 'flex-start', 'important');
            sidebar.style.setProperty('flex-shrink', '0', 'important');
            
            // ÉTAPE 5 : Ajuster la hauteur
            const viewportHeight = window.innerHeight;
            const maxHeight = viewportHeight - topValue;
            sidebar.style.setProperty('max-height', maxHeight + 'px', 'important');
            sidebar.style.setProperty('height', 'auto', 'important');
        }
        
        // Exécuter immédiatement et à plusieurs reprises
        function init() {
            if (isMobile()) return;
            
            forceSticky();
            
            // Réessayer plusieurs fois pour s'assurer que ça fonctionne
            setTimeout(forceSticky, 50);
            setTimeout(forceSticky, 100);
            setTimeout(forceSticky, 200);
            setTimeout(forceSticky, 500);
        }
        
        // Écouter le scroll pour maintenir le sticky
        let ticking = false;
        function onScroll() {
            if (isMobile()) return;
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    // Vérifier et forcer sticky à chaque scroll
                    const sidebar = document.getElementById('dashboardSidebar');
                    if (sidebar && sidebar.style.position !== 'sticky') {
                        forceSticky();
                    }
                    ticking = false;
                });
                ticking = true;
            }
        }
        
        window.addEventListener('scroll', onScroll, { passive: true });
        
        // Réinitialiser au resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (!isMobile()) {
                    forceSticky();
                }
            }, 100);
        });
        
        // Initialiser
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }
        
        window.addEventListener('load', init);
    })();
    
    // Script pour le toggle du sidebar en mobile
    function toggleSidebar() {
        try {
            const sidebar = document.getElementById('dashboardSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const closeBtn = document.getElementById('sidebarClose');
            
            if (!sidebar || !sidebar.classList) return;
            
            sidebar.classList.toggle('active');
            if (overlay && overlay.classList) {
                overlay.classList.toggle('active');
            }
            
            if (window.innerWidth <= 992 && closeBtn) {
                if (sidebar.classList.contains('active')) {
                    closeBtn.style.display = 'flex';
                } else {
                    closeBtn.style.display = 'none';
                }
            }
        } catch (error) {
            // Ignorer silencieusement
        }
    }
    
    document.getElementById('sidebarToggle')?.addEventListener('click', toggleSidebar);
    document.getElementById('sidebarOverlay')?.addEventListener('click', toggleSidebar);
    document.getElementById('sidebarClose')?.addEventListener('click', toggleSidebar);
    
    if (window.innerWidth <= 992) {
        document.getElementById('sidebarToggle').style.display = 'flex';
    }
    
    window.addEventListener('resize', function() {
        if (window.innerWidth <= 992) {
            document.getElementById('sidebarToggle').style.display = 'flex';
        } else {
            document.getElementById('sidebarToggle').style.display = 'none';
            document.getElementById('dashboardSidebar').classList.remove('active');
            document.getElementById('sidebarOverlay').classList.remove('active');
        }
    });
</script>
@endsection
