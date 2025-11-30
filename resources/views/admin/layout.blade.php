<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin - NiangProgrammeur')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Google Fonts optimisé avec preload et font-display: swap -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Inter - Police principale -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"></noscript>
    <!-- Poppins - Police secondaire -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"></noscript>
    
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script>
        // Appliquer le mode immédiatement pour éviter le flash
        (function() {
            const darkMode = localStorage.getItem('adminDarkMode');
            if (darkMode === 'light') {
                if (document.documentElement) {
                    document.documentElement.classList.add('light-mode');
                }
                if (document.body) {
                    document.body.classList.add('light-mode');
                } else {
                    // Si le body n'existe pas encore, attendre qu'il soit chargé
                    document.addEventListener('DOMContentLoaded', function() {
                        if (document.body) {
                            document.body.classList.add('light-mode');
                        }
                    });
                }
            }
        })();
    </script>
    <style>
        /* Fonts chargées via preload dans le head - pas de @import bloquant */
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html {
            overflow-x: hidden !important;
            overflow-y: auto;
        }
        
        html {
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        html.light-mode {
            background: #f8f9fa;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #0a0a0f;
            color: white;
            overflow-x: hidden !important;
            overflow-y: auto;
            max-width: 100vw;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        body.light-mode {
            background: #f8f9fa;
            color: #1e293b;
        }
        
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: 240px;
            background: linear-gradient(180deg, rgba(10, 10, 26, 0.95) 0%, rgba(0, 0, 0, 0.95) 100%);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(6, 182, 212, 0.2);
            padding: 1.5rem 0;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 100;
            transition: transform 0.3s ease, background 0.3s ease, border-color 0.3s ease;
        }
        
        body.light-mode .sidebar {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.95) 0%, rgba(248, 249, 250, 0.95) 100%);
            border-right-color: rgba(6, 182, 212, 0.3);
        }
        
        body.light-mode .sidebar-item {
            color: #64748b;
        }
        
        body.light-mode .sidebar-item:hover,
        body.light-mode .sidebar-item.active {
            background: rgba(6, 182, 212, 0.1);
            color: #06b6d4;
        }
        
        body.light-mode .logo-admin h1 {
            background: linear-gradient(135deg, #06b6d4, #14b8a6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
        }
        
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #06b6d4, #14b8a6);
            border-radius: 10px;
        }
        
        .logo-admin {
            padding: 0 1.5rem 2rem;
            border-bottom: 1px solid rgba(6, 182, 212, 0.2);
            margin-bottom: 2rem;
        }
        
        .logo-admin h1 {
            background: linear-gradient(135deg, #06b6d4, #14b8a6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 1.5rem;
            font-weight: 900;
        }
        
        .sidebar-item {
            padding: 0.875rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #9ca3af;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            font-size: 0.9rem;
        }
        
        .sidebar-item:hover, .sidebar-item.active {
            background: rgba(6, 182, 212, 0.1);
            color: #06b6d4;
            border-left-color: #06b6d4;
        }
        
        .sidebar-dropdown {
            position: relative;
        }
        
        .sidebar-dropdown-toggle {
            width: 100%;
            background: none;
            border: none;
            cursor: pointer;
            text-align: left;
        }
        
        .sidebar-dropdown.active .sidebar-dropdown-toggle {
            background: rgba(6, 182, 212, 0.1);
            color: #06b6d4;
            border-left-color: #06b6d4;
        }
        
        .sidebar-dropdown-toggle .dropdown-icon {
            transition: transform 0.3s ease;
        }
        
        .sidebar-dropdown.active .sidebar-dropdown-toggle .dropdown-icon {
            transform: rotate(180deg);
        }
        
        .sidebar-dropdown-menu {
            background: rgba(0, 0, 0, 0.3);
            padding-left: 1rem;
            border-left: 2px solid rgba(6, 182, 212, 0.2);
            margin-left: 1.25rem;
            transition: background 0.3s ease, border-color 0.3s ease;
        }
        
        body.light-mode .sidebar-dropdown-menu {
            background: rgba(241, 245, 249, 0.5);
            border-left-color: rgba(6, 182, 212, 0.3);
        }
        
        .sidebar-dropdown-item {
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #9ca3af;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.85rem;
            border-left: 2px solid transparent;
        }
        
        body.light-mode .sidebar-dropdown-item {
            color: rgba(71, 85, 105, 1);
        }
        
        .sidebar-dropdown-item:hover, .sidebar-dropdown-item.active {
            background: rgba(6, 182, 212, 0.1);
            color: #06b6d4;
            border-left-color: #06b6d4;
        }
        
        body.light-mode .sidebar-dropdown-item:hover, 
        body.light-mode .sidebar-dropdown-item.active {
            background: rgba(6, 182, 212, 0.15);
            color: #06b6d4;
            border-left-color: #06b6d4;
        }
        
        .main-content {
            margin-left: 240px;
            padding: 2rem;
            padding-top: calc(2rem + 80px);
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        @media (max-width: 1024px) {
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }
        }
        
        
        .stat-card {
            background: rgba(10, 10, 26, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(6, 182, 212, 0.2);
            border-radius: 16px;
            padding: 2rem;
            transition: all 0.3s ease;
        }
        
        body.light-mode .stat-card {
            background: rgba(255, 255, 255, 0.8);
            border-color: rgba(6, 182, 212, 0.3);
        }
        
        .stat-card:hover {
            border-color: rgba(6, 182, 212, 0.5);
            box-shadow: 0 10px 30px rgba(6, 182, 212, 0.2);
            transform: translateY(-5px);
        }
        
        body.light-mode .stat-card:hover {
            box-shadow: 0 10px 30px rgba(6, 182, 212, 0.15);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 900;
            background: linear-gradient(135deg, #06b6d4, #14b8a6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #06b6d4, #14b8a6);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            color: #000;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(6, 182, 212, 0.4);
        }
        
        .content-section {
            background: rgba(10, 10, 26, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(6, 182, 212, 0.2);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            transition: background 0.3s ease, border-color 0.3s ease;
        }
        
        body.light-mode .content-section {
            background: rgba(255, 255, 255, 0.8);
            border-color: rgba(6, 182, 212, 0.3);
        }
        
        .input-admin {
            background: rgba(6, 182, 212, 0.05);
            border: 2px solid rgba(6, 182, 212, 0.2);
            border-radius: 12px;
            padding: 0.65rem 1rem;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        
        body.light-mode .input-admin {
            background: rgba(255, 255, 255, 0.9);
            border-color: rgba(6, 182, 212, 0.3);
            color: #1e293b;
        }
        
        .input-admin:focus {
            outline: none;
            border-color: #06b6d4;
            background: rgba(6, 182, 212, 0.1);
        }
        
        body.light-mode .input-admin:focus {
            background: rgba(255, 255, 255, 1);
            border-color: #06b6d4;
        }
        
        textarea.input-admin {
            min-height: 120px;
            resize: vertical;
        }
        
        select.input-admin {
            color: white;
            background: rgba(15, 23, 42, 0.8);
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2306b6d4' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 12px;
            padding-right: 2.5rem;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
        }
        
        body.light-mode select.input-admin {
            color: #1e293b;
            background: rgba(255, 255, 255, 0.9);
        }
        
        select.input-admin option {
            background: #0f172a;
            color: white;
            padding: 10px;
        }
        
        body.light-mode select.input-admin option {
            background: #ffffff;
            color: #1e293b;
        }
        
        select.input-admin:focus {
            background-color: rgba(15, 23, 42, 0.9);
        }
        
        body.light-mode select.input-admin:focus {
            background-color: rgba(255, 255, 255, 1);
        }
        
        select.input-admin:focus option {
            background: rgba(6, 182, 212, 0.1);
        }
        
        body.light-mode select.input-admin:focus option {
            background: #ffffff;
        }
        
        .mobile-menu-btn {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 101;
            background: linear-gradient(135deg, #06b6d4, #14b8a6);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            color: #000;
            font-size: 1.25rem;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
        }
        
        @media (max-width: 1024px) {
            .mobile-menu-btn {
                display: block;
            }
            
            .stat-card {
                padding: 1.5rem;
            }
            
            .stat-number {
                font-size: 2rem;
            }
        }
        
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 99;
        }
        
        @media (max-width: 1024px) {
            .sidebar-overlay.active {
                display: block;
            }
        }
        
        .header-admin {
            position: fixed;
            top: 0;
            left: 240px;
            right: 0;
            width: calc(100% - 240px);
            background: rgba(10, 10, 26, 0.98);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(6, 182, 212, 0.2);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 99;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            transition: background 0.3s ease, border-color 0.3s ease;
        }
        
        body.light-mode .header-admin {
            background: rgba(255, 255, 255, 0.98);
            border-bottom-color: rgba(6, 182, 212, 0.3);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        
        body.light-mode .header-title h2 {
            color: #1e293b;
        }
        
        body.light-mode .header-title p {
            color: #64748b;
        }
        
        body.light-mode .user-btn {
            background: rgba(6, 182, 212, 0.1);
            border-color: rgba(6, 182, 212, 0.3);
        }
        
        body.light-mode .user-btn:hover {
            background: rgba(6, 182, 212, 0.2);
        }
        
        body.light-mode .user-btn p {
            color: #1e293b;
        }
        
        body.light-mode .user-btn .text-gray-400 {
            color: #64748b !important;
        }
        
        body.light-mode .user-btn {
            background: rgba(6, 182, 212, 0.1);
            border-color: rgba(6, 182, 212, 0.3);
        }
        
        body.light-mode .user-btn:hover {
            background: rgba(6, 182, 212, 0.2);
        }
        
        body.light-mode .user-btn p {
            color: #1e293b;
        }
        
        body.light-mode .user-btn .text-gray-400 {
            color: #64748b !important;
        }
        
        .user-dropdown {
            position: relative;
            z-index: 100;
        }
        
        .user-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: rgba(6, 182, 212, 0.1);
            border: 1px solid rgba(6, 182, 212, 0.3);
            border-radius: 12px;
            padding: 0.5rem 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .user-btn:hover {
            background: rgba(6, 182, 212, 0.2);
            border-color: rgba(6, 182, 212, 0.5);
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #06b6d4, #14b8a6);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #000;
        }
        
        .dropdown-menu {
            position: absolute;
            top: calc(100% + 0.5rem);
            right: 0;
            min-width: 220px;
            background: rgba(10, 10, 26, 0.98);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(6, 182, 212, 0.3);
            border-radius: 12px;
            padding: 0.5rem;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 9999;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.8);
        }
        
        body.light-mode .dropdown-menu {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        body.light-mode .dropdown-item {
            color: #475569;
        }
        
        body.light-mode .dropdown-item:hover {
            background: rgba(6, 182, 212, 0.1);
            color: #06b6d4;
        }
        
        .user-dropdown.active .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: #9ca3af;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .dropdown-item:hover {
            background: rgba(6, 182, 212, 0.1);
            color: #06b6d4;
        }
        
        .dropdown-item.danger {
            color: #ef4444;
        }
        
        .dropdown-item.danger:hover {
            background: rgba(239, 68, 68, 0.1);
        }
        
        .dropdown-divider {
            height: 1px;
            background: rgba(6, 182, 212, 0.2);
            margin: 0.5rem 0;
        }
        
        @media (max-width: 1024px) {
            .header-admin {
                left: 0;
                width: 100%;
            }
            
            .main-content {
                padding-top: calc(1rem + 100px);
            }
        }
        
        /* Dark Mode Toggle Button */
        .dark-mode-toggle-btn {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(6, 182, 212, 0.1);
            border: 1px solid rgba(6, 182, 212, 0.3);
            color: #06b6d4;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        
        .dark-mode-toggle-btn:hover {
            background: rgba(6, 182, 212, 0.2);
            border-color: rgba(6, 182, 212, 0.5);
            transform: scale(1.1);
        }
        
        body.light-mode .dark-mode-toggle-btn {
            background: rgba(6, 182, 212, 0.1);
            color: #06b6d4;
        }
        
        body.light-mode .dark-mode-toggle-btn:hover {
            background: rgba(6, 182, 212, 0.2);
        }
        
        @media (max-width: 768px) {
            .header-admin {
                flex-direction: row;
                justify-content: flex-end;
                padding: 1rem;
            }
            
            .header-title {
                display: none;
            }
            
            .user-dropdown {
                margin-left: auto;
            }
            
            .main-content {
                padding-top: calc(1rem + 80px);
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
    
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
    
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="logo-admin">
            <div class="flex items-center gap-3 mb-2">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-10 rounded-lg" style="filter: drop-shadow(0 0 10px rgba(6, 182, 212, 0.8));">
                <h1 class="text-xl">ADMIN PANEL</h1>
            </div>
            <p class="text-sm text-gray-400">NiangProgrammeur</p>
        </div>
        
        <nav>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-line text-xl"></i>
                <span>Dashboard</span>
            </a>
            
            @auth
            @if(Auth::user()->isAdmin())
            <a href="{{ route('admin.statistics') }}" class="sidebar-item {{ request()->routeIs('admin.statistics') ? 'active' : '' }}">
                <i class="fas fa-chart-bar text-xl"></i>
                <span>Statistiques</span>
            </a>
            @endif
            @endauth
            
            <!-- Menu Dropdown Emplois -->
            <div class="sidebar-dropdown {{ request()->routeIs('admin.jobs.*') ? 'active' : '' }}">
                <button class="sidebar-item sidebar-dropdown-toggle" onclick="toggleSidebarDropdown('jobs')">
                    <i class="fas fa-briefcase text-xl"></i>
                    <span>Emplois</span>
                    <i class="fas fa-chevron-down ml-auto text-sm dropdown-icon" id="jobs-icon"></i>
                </button>
                <div class="sidebar-dropdown-menu" id="jobs-dropdown" style="display: {{ request()->routeIs('admin.jobs.*') ? 'block' : 'none' }};">
                    <a href="{{ route('admin.jobs.categories.index') }}" class="sidebar-dropdown-item {{ request()->routeIs('admin.jobs.categories.*') ? 'active' : '' }}">
                        <i class="fas fa-folder"></i>
                        <span>Catégories</span>
                    </a>
                    <a href="{{ route('admin.jobs.articles.index') }}" class="sidebar-dropdown-item {{ request()->routeIs('admin.jobs.articles.*') ? 'active' : '' }}">
                        <i class="fas fa-newspaper"></i>
                        <span>Articles</span>
                    </a>
                    {{-- Générateur d'Articles désactivé
                    @auth
                    @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.jobs.seeder.index') }}" class="sidebar-dropdown-item {{ request()->routeIs('admin.jobs.seeder.*') ? 'active' : '' }}">
                        <i class="fas fa-seedling"></i>
                        <span>Générateur d'Articles</span>
                    </a>
                    @endif
                    @endauth
                    --}}
                </div>
            </div>
            
            @auth
            @if(Auth::user()->isAdmin())
            <a href="{{ route('admin.messages') }}" class="sidebar-item {{ request()->routeIs('admin.messages') ? 'active' : '' }}">
                <i class="fas fa-envelope text-xl"></i>
                <span>Messages</span>
                @php
                    $unreadCount = \App\Models\ContactMessage::unread()->count();
                @endphp
                @if($unreadCount > 0)
                    <span class="ml-auto px-2 py-1 bg-red-500 text-white text-xs rounded-full">{{ $unreadCount }}</span>
                @endif
            </a>
            @endif
            @endauth
            
            <a href="{{ route('admin.comments.index') }}" class="sidebar-item {{ request()->routeIs('admin.comments.*') ? 'active' : '' }}">
                <i class="fas fa-comments text-xl"></i>
                <span>Commentaires</span>
                @php
                    $pendingComments = \App\Models\Comment::where('status', 'pending')->count();
                @endphp
                @if($pendingComments > 0)
                    <span class="ml-auto px-2 py-1 bg-yellow-500 text-white text-xs rounded-full">{{ $pendingComments }}</span>
                @endif
            </a>
            
            @auth
            @if(Auth::user()->isAdmin())
            <a href="{{ route('admin.ads.index') }}" class="sidebar-item {{ request()->routeIs('admin.ads.*') ? 'active' : '' }}">
                <i class="fas fa-ad text-xl"></i>
                <span>Publicités</span>
            </a>
            <a href="{{ route('admin.users') }}" class="sidebar-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <i class="fas fa-users text-xl"></i>
                <span>Utilisateurs</span>
            </a>
            <a href="{{ route('admin.newsletter.index') }}" class="sidebar-item {{ request()->routeIs('admin.newsletter.*') ? 'active' : '' }}">
                <i class="fas fa-envelope-open-text text-xl"></i>
                <span>Newsletter</span>
                @php
                    $unreadNewsletters = \App\Models\Newsletter::unread()->where('is_active', true)->count();
                @endphp
                @if($unreadNewsletters > 0)
                    <span class="ml-auto px-2 py-1 bg-blue-500 text-white text-xs rounded-full">{{ $unreadNewsletters }}</span>
                @endif
            </a>
            <a href="{{ route('admin.achievements.index') }}" class="sidebar-item {{ request()->routeIs('admin.achievements.*') ? 'active' : '' }}">
                <i class="fas fa-trophy text-xl"></i>
                <span>Réalisations</span>
            </a>
            
            <!-- Dropdown Configuration -->
            <div class="sidebar-dropdown {{ request()->routeIs('admin.adsense*') || request()->routeIs('admin.backups*') || request()->routeIs('admin.logs.*') || request()->routeIs('admin.bing*') || request()->routeIs('admin.settings') ? 'active' : '' }}">
                <button class="sidebar-item sidebar-dropdown-toggle" onclick="toggleSidebarDropdown('config')">
                    <i class="fas fa-cog text-xl"></i>
                    <span>Configuration</span>
                    <i class="fas fa-chevron-down dropdown-icon ml-auto" id="config-icon"></i>
                </button>
                <div class="sidebar-dropdown-menu" id="config-dropdown" style="display: {{ request()->routeIs('admin.adsense*') || request()->routeIs('admin.backups*') || request()->routeIs('admin.logs.*') || request()->routeIs('admin.bing*') || request()->routeIs('admin.settings') ? 'block' : 'none' }};">
                    <a href="{{ route('admin.adsense') }}" class="sidebar-dropdown-item {{ request()->routeIs('admin.adsense*') ? 'active' : '' }}">
                        <i class="fab fa-google"></i>
                        <span>Google AdSense</span>
                    </a>
                    <a href="{{ route('admin.backups') }}" class="sidebar-dropdown-item {{ request()->routeIs('admin.backups*') ? 'active' : '' }}">
                        <i class="fas fa-database"></i>
                        <span>Sauvegardes</span>
                    </a>
                    <a href="{{ route('admin.logs.index') }}" class="sidebar-dropdown-item {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}">
                        <i class="fas fa-history"></i>
                        <span>Logs</span>
                    </a>
                    <a href="{{ route('admin.bing.submission') }}" class="sidebar-dropdown-item {{ request()->routeIs('admin.bing*') ? 'active' : '' }}">
                        <i class="fas fa-paper-plane"></i>
                        <span>Bing Submission</span>
                    </a>
                    <a href="{{ route('admin.settings') }}" class="sidebar-dropdown-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        <span>Paramètres</span>
                    </a>
                </div>
            </div>
            @endif
            @endauth
            
            <a href="{{ route('admin.logout') }}" class="sidebar-item" style="margin-top: 2rem; color: #ef4444;">
                <i class="fas fa-sign-out-alt text-xl"></i>
                <span>Déconnexion</span>
            </a>
        </nav>
    </aside>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Header with User Dropdown -->
        <div class="header-admin">
            <div class="header-title">
                <h2 class="text-2xl font-bold">Bienvenue, Admin</h2>
                <p class="text-gray-400 mt-1">Gérez votre plateforme de formation</p>
            </div>
            
            <div style="display: flex; align-items: center; gap: 1rem;">
                <!-- Dark Mode Toggle -->
                <button id="dark-mode-toggle" class="dark-mode-toggle-btn" onclick="toggleDarkMode()" title="Basculer le mode sombre">
                    <i class="fas fa-moon" id="dark-mode-icon"></i>
                </button>
                
                <div class="user-dropdown" id="userDropdown">
                    <div class="user-btn" onclick="toggleDropdown()">
                        <div class="user-avatar">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="font-semibold text-sm">@auth{{ explode(' ', Auth::user()->name)[0] }}@else Admin @endauth</p>
                        </div>
                        <i class="fas fa-chevron-down text-sm"></i>
                    </div>
                
                <div class="dropdown-menu">
                    <a href="{{ route('admin.profile') }}" class="dropdown-item">
                        <i class="fas fa-user"></i>
                        <span>Profil</span>
                    </a>
                    <a href="{{ route('home') }}" target="_blank" class="dropdown-item">
                        <i class="fas fa-globe"></i>
                        <span>Voir le site</span>
                    </a>
                    @auth
                    @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.settings') }}" class="dropdown-item">
                        <i class="fas fa-cog"></i>
                        <span>Paramètres</span>
                    </a>
                    @endif
                    @endauth
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('admin.logout') }}" class="dropdown-item danger">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Déconnexion</span>
                    </a>
                </div>
            </div>
            </div>
        </div>
        
        @yield('content')
    </main>
    
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }
        
        function toggleDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('active');
        }
        
        function toggleSidebarDropdown(id) {
            const dropdown = document.getElementById(id + '-dropdown');
            const icon = document.getElementById(id + '-icon');
            const parent = dropdown.closest('.sidebar-dropdown');
            
            if (dropdown.style.display === 'none' || !dropdown.style.display) {
                dropdown.style.display = 'block';
                if (icon) icon.style.transform = 'rotate(180deg)';
                if (parent) parent.classList.add('active');
            } else {
                dropdown.style.display = 'none';
                if (icon) icon.style.transform = 'rotate(0deg)';
                if (parent) parent.classList.remove('active');
            }
        }
        
        // Dark Mode Toggle
        function toggleDarkMode() {
            const body = document.body;
            const html = document.documentElement;
            const isLight = body.classList.toggle('light-mode');
            html.classList.toggle('light-mode');
            const icon = document.getElementById('dark-mode-icon');
            
            // Sauvegarder la préférence
            localStorage.setItem('adminDarkMode', isLight ? 'light' : 'dark');
            
            // Mettre à jour l'icône
            if (isLight) {
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            } else {
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
            }
        }
        
        // Initialiser le dark mode au chargement
        document.addEventListener('DOMContentLoaded', function() {
            const darkMode = localStorage.getItem('adminDarkMode');
            const body = document.body;
            const html = document.documentElement;
            const icon = document.getElementById('dark-mode-icon');
            
            if (darkMode === 'light') {
                body.classList.add('light-mode');
                html.classList.add('light-mode');
                if (icon) {
                    icon.classList.remove('fa-moon');
                    icon.classList.add('fa-sun');
                }
            }
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('userDropdown');
            if (dropdown && !dropdown.contains(event.target)) {
                dropdown.classList.remove('active');
            }
        });
    </script>
    
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        // Configuration Toastr
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        // Afficher les messages de succès
        @if(session('success'))
            toastr.success('{{ session('success') }}', 'Succès');
        @endif

        // Afficher les messages d'erreur
        @if(session('error'))
            toastr.error('{{ session('error') }}', 'Erreur');
        @endif

        // Afficher les messages d'info
        @if(session('info'))
            toastr.info('{{ session('info') }}', 'Information');
        @endif

        // Afficher les messages d'avertissement
        @if(session('warning'))
            toastr.warning('{{ session('warning') }}', 'Attention');
        @endif
    </script>
    
    @yield('scripts')
</body>
</html>
