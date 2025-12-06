@extends('layouts.app')

@push('preload_images')
<link rel="preload" as="image" href="https://images.unsplash.com/photo-1498050108023-c5249f4df085?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2072&q=80" fetchpriority="high">
@endpush

@section('title', trans('app.formations.html5.title') . ' | NiangProgrammeur')

@section('styles')
<style>
    * {
        box-sizing: border-box;
    }
    html {
        overflow-x: hidden;
        scroll-behavior: smooth;
        height: 100%;
    }
    body {
        background-color: #fff !important;
        color: #000 !important;
        overflow-x: hidden !important;
        overflow-y: auto !important;
        height: 100%;
    }
    .tutorial-header {
        position: relative;
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.85) 0%, rgba(30, 41, 59, 0.9) 100%),
                    url('https://images.unsplash.com/photo-1498050108023-c5249f4df085?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2072&q=80') center/cover no-repeat;
        background-attachment: fixed;
        color: white;
        padding: 120px 20px 80px;
        text-align: center;
        width: 100%;
        margin: 0;
        overflow: hidden;
        min-height: 50vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .tutorial-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 30%, rgba(4, 170, 109, 0.2) 0%, transparent 50%),
            radial-gradient(circle at 80% 70%, rgba(4, 170, 109, 0.15) 0%, transparent 50%);
        pointer-events: none;
    }
    
    .tutorial-header::after {
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
    
    .tutorial-header-content {
        position: relative;
        z-index: 2;
        max-width: 1200px;
        margin: 0 auto;
        width: 100%;
    }
    
    .tutorial-header h1 {
        font-size: clamp(2.5rem, 6vw, 4.5rem);
        font-weight: 800;
        margin-bottom: 20px;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        animation: fadeInUp 0.8s ease;
        letter-spacing: -0.02em;
        line-height: 1.1;
    }
    
    .tutorial-header p {
        font-size: clamp(1.1rem, 2.5vw, 1.4rem);
        color: rgba(255, 255, 255, 0.95);
        margin-bottom: 0;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        animation: fadeInUp 1s ease;
        line-height: 1.6;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
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
    
    body.dark-mode .tutorial-header {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(30, 41, 59, 0.95) 100%),
                    url('https://images.unsplash.com/photo-1498050108023-c5249f4df085?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2072&q=80') center/cover no-repeat;
        background-attachment: fixed;
    }
    .tutorial-content {
        max-width: 1400px;
        margin: 0 auto;
        background: white;
        width: 100%;
        min-height: calc(100vh - 70px);
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
    .sidebar {
        width: 280px;
        flex-shrink: 0;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        padding: 15px 25px 25px 25px;
        border-radius: 15px;
        position: -webkit-sticky;
        position: sticky;
        top: 60px;
        align-self: flex-start;
        height: calc(100vh - 60px);
        max-height: calc(100vh - 60px);
        overflow-y: auto;
        overflow-x: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(4, 170, 109, 0.1);
        z-index: 10;
    }
    
    /* FORCER le sidebar à ne PAS être sticky en mobile - PROTECTION MAXIMALE */
    @media (max-width: 992px) {
        .sidebar,
        .sidebar#tutorialSidebar,
        aside.sidebar,
        .content-wrapper .sidebar {
            position: fixed !important;
            top: auto !important;
            align-self: auto !important;
            flex-shrink: 0 !important;
            width: 85% !important;
            max-width: 400px !important;
        }
    }
    
    .sidebar::-webkit-scrollbar {
        width: 6px;
    }
    .sidebar::-webkit-scrollbar-track {
        background: transparent;
        border-radius: 10px;
    }
    .sidebar::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #04AA6D 0%, #038f5a 100%);
        border-radius: 10px;
    }
    .sidebar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #038f5a 0%, #027049 100%);
    }
    .sidebar h3 {
        color: #04AA6D;
        font-size: 20px;
        margin-bottom: 20px;
        font-weight: 700;
        letter-spacing: -0.5px;
        padding-bottom: 15px;
        border-bottom: 2px solid rgba(4, 170, 109, 0.2);
    }
    .sidebar a {
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
    }
    .sidebar a::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 3px;
        background: #04AA6D;
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }
    .sidebar a:hover {
        background: linear-gradient(135deg, rgba(4, 170, 109, 0.1) 0%, rgba(4, 170, 109, 0.05) 100%);
        color: #04AA6D;
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(4, 170, 109, 0.15);
    }
    .sidebar a:hover::before {
        transform: scaleY(1);
    }
    .sidebar a.active {
        background: linear-gradient(135deg, #04AA6D 0%, #038f5a 100%);
        color: white;
        font-weight: 600;
        box-shadow: 0 6px 20px rgba(4, 170, 109, 0.3);
        transform: translateX(5px);
    }
    .sidebar a.active::before {
        transform: scaleY(1);
        background: white;
    }
    
    .sidebar-close-btn {
        display: none;
        align-items: center;
        justify-content: center;
        background: rgba(4, 170, 109, 0.1) !important;
        border: 2px solid rgba(4, 170, 109, 0.3) !important;
        transition: all 0.3s ease;
    }
    
    .sidebar-close-btn:hover {
        background: rgba(4, 170, 109, 0.2) !important;
        border-color: rgba(4, 170, 109, 0.5) !important;
        transform: rotate(90deg) scale(1.1);
    }
    .main-content {
        flex: 1 1 auto;
        min-width: 0;
        background: white;
        padding: 30px;
        border-radius: 5px;
        overflow-x: hidden;
        max-width: 100%;
    }
    .main-content h1 {
        color: #000;
        font-size: 42px;
        margin-bottom: 10px;
    }
    .main-content h2 {
        color: #000;
        font-size: 32px;
        margin-top: 30px;
        margin-bottom: 15px;
    }
    .main-content p {
        color: #000;
        line-height: 1.8;
        margin-bottom: 15px;
        font-size: 16px;
    }
    .example-box {
        background-color: #E7E9EB;
        border-left: 4px solid #04AA6D;
        padding: 20px;
        margin: 20px 0;
        border-radius: 5px;
    }
    .example-box h3 {
        color: #000;
        margin-bottom: 10px;
    }
    .code-box {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        padding: 20px;
        border-radius: 10px;
        font-family: 'Courier New', monospace;
        overflow-x: auto;
        word-wrap: break-word;
        margin: 15px 0;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 0 20px rgba(4, 170, 109, 0.1);
        position: relative;
        max-width: 100%;
        width: 100%;
        box-sizing: border-box;
    }
    
    /* Bouton de copie - Même taille que le label HTML */
    .copy-code-btn {
        position: absolute;
        top: 10px;
        right: 80px; /* Espacement pour éviter la superposition avec "HTML" */
        background: #04AA6D;
        color: white;
        border: none;
        padding: 2px 10px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        z-index: 10;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        white-space: nowrap;
        height: auto;
        line-height: 1.4;
    }
    
    .copy-code-btn:hover {
        background: #038f5a;
        transform: translateY(-1px);
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.3);
    }
    
    .copy-code-btn:active {
        transform: translateY(0);
    }
    
    .copy-code-btn.copied {
        background: rgba(34, 197, 94, 0.9);
        padding: 2px 10px;
    }
    
    .copy-code-btn.copied:hover {
        background: rgba(34, 197, 94, 1);
    }
    
    .copy-code-btn i {
        font-size: 12px;
    }
    .code-box code {
        display: block;
        max-width: 100%;
        overflow-wrap: break-word;
    }
    .code-box::before {
        content: 'HTML';
        position: absolute;
        top: 10px;
        right: 15px;
        background: #04AA6D;
        color: white;
        padding: 2px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
    }
    .code-box code {
        color: #e2e8f0;
        line-height: 1.6;
    }
    .code-tag {
        color: #f472b6;
    }
    .code-attr {
        color: #60a5fa;
    }
    .code-value {
        color: #fbbf24;
    }
    .code-text {
        color: #a3e635;
    }
    .code-comment {
        color: #94a3b8;
        font-style: italic;
    }
    .try-it-btn {
        background-color: #04AA6D;
        color: white;
        padding: 10px 25px;
        border: none;
        border-radius: 25px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 10px;
        transition: all 0.3s;
    }
    .try-it-btn:hover {
        background-color: #059862;
    }
    .note-box {
        background-color: #ffffcc;
        border-left: 4px solid #ffeb3b;
        padding: 15px;
        margin: 20px 0;
        border-radius: 5px;
    }
    .nav-buttons {
        display: flex;
        justify-content: space-between;
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px solid #ddd;
    }
    .nav-btn {
        background-color: #04AA6D;
        color: white;
        padding: 12px 30px;
        text-decoration: none;
        border-radius: 25px;
        transition: all 0.3s;
    }
    .nav-btn:hover {
        background-color: #059862;
    }
    /* Menu Burger Mobile - Caché par défaut sur desktop */
    .sidebar-toggle-btn {
        display: none !important;
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
    
    .sidebar-toggle-btn.active {
        background: linear-gradient(135deg, #038f5a, #027049);
        transform: rotate(90deg);
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
    
    @media (max-width: 992px) {
        .content-wrapper {
            flex-direction: column;
        }
        
        /* Sidebar caché par défaut en mobile - FORCER avec toutes les propriétés */
        .sidebar,
        .sidebar#tutorialSidebar,
        aside.sidebar {
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
        
        /* Sidebar visible quand actif */
        .sidebar.active,
        .sidebar#tutorialSidebar.active,
        aside.sidebar.active {
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
        }
        
        .main-content {
            width: 100%;
            padding: 20px;
        }
    }
    
    @media (max-width: 768px) {
        .sidebar {
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
    
    /* Dark Mode Styles */
    body.dark-mode {
        background-color: #0a0a0f !important;
        color: #e5e7eb !important;
    }
    
    body.dark-mode .tutorial-content {
        background: #0a0a0f;
    }
    
    body.dark-mode .sidebar {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.95) 0%, rgba(30, 41, 59, 0.9) 100%);
        border-color: rgba(4, 170, 109, 0.3);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
    
    body.dark-mode .sidebar h3 {
        color: #04AA6D;
        border-bottom-color: rgba(4, 170, 109, 0.3);
    }
    
    body.dark-mode .sidebar a {
        color: rgba(255, 255, 255, 0.8);
    }
    
    body.dark-mode .sidebar a:hover {
        background: linear-gradient(135deg, rgba(4, 170, 109, 0.2) 0%, rgba(4, 170, 109, 0.1) 100%);
        color: #04AA6D;
    }
    
    body.dark-mode .sidebar a.active::before {
        background: rgba(255, 255, 255, 0.2);
    }
    
    body.dark-mode .main-content {
        background: rgba(15, 23, 42, 0.5);
    }
    
    body.dark-mode .main-content h1,
    body.dark-mode .main-content h2,
    body.dark-mode .main-content h3 {
        color: #ffffff !important;
    }
    
    body.dark-mode .main-content p {
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    body.dark-mode .main-content ul,
    body.dark-mode .main-content ol {
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    body.dark-mode .main-content li {
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    body.dark-mode .example-box {
        background-color: rgba(30, 41, 59, 0.8);
        border-left-color: #04AA6D;
    }
    
    body.dark-mode .example-box h3 {
        color: #ffffff !important;
    }
    
    body.dark-mode .example-box p {
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    body.dark-mode .note-box {
        background-color: rgba(251, 191, 36, 0.15);
        border-left-color: #fbbf24;
    }
    
    body.dark-mode .note-box p {
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    body.dark-mode .nav-buttons {
        border-top-color: rgba(255, 255, 255, 0.2);
    }
    
    body.dark-mode [style*="color: #000"],
    body.dark-mode [style*="color:#000"] {
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    /* Dark mode pour la section progression/CTA */
    body.dark-mode [style*="background: linear-gradient(135deg, rgba(4, 170, 109"] {
        background: linear-gradient(135deg, rgba(4, 170, 109, 0.2) 0%, rgba(4, 170, 109, 0.1) 100%) !important;
        border-color: rgba(4, 170, 109, 0.4) !important;
    }
    
    body.dark-mode [style*="color: #2c3e50"] {
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    body.dark-mode [style*="background: white"] {
        background: rgba(15, 23, 42, 0.8) !important;
        border-color: rgba(4, 170, 109, 0.4) !important;
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    body.dark-mode [style*="background: linear-gradient(135deg, rgba(4, 170, 109, 0.15)"] {
        background: linear-gradient(135deg, rgba(4, 170, 109, 0.25) 0%, rgba(6, 182, 212, 0.15) 100%) !important;
        border-color: rgba(4, 170, 109, 0.5) !important;
    }
    
    /* Animations pour les notifications */
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<div class="tutorial-header">
    <div class="tutorial-header-content">
        <h1>
            <i class="fab fa-html5" style="margin-right: 15px;"></i>
            {{ trans('app.formations.html5.title') }}
        </h1>
        <p>{{ trans('app.formations.html5.subtitle') }}</p>
        <div style="margin-top: 30px; display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
            <button data-favorite 
                    data-favorite-type="formation" 
                    data-favorite-slug="html5" 
                    data-favorite-name="{{ trans('app.formations.html5.title') }}"
                    style="background: rgba(255, 255, 255, 0.2); border: 2px solid rgba(255, 255, 255, 0.4); color: white; padding: 12px 24px; border-radius: 10px; cursor: pointer; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease; backdrop-filter: blur(10px);">
                <i class="far fa-heart"></i>
                <span>Ajouter aux favoris</span>
            </button>
        </div>
    </div>
</div>

<!-- Progression ou Message d'incitation -->
@auth
    @if($progress)
    <div style="max-width: 1200px; margin: 20px auto; padding: 0 20px;">
        <div style="background: linear-gradient(135deg, rgba(4, 170, 109, 0.1) 0%, rgba(4, 170, 109, 0.05) 100%); border: 2px solid rgba(4, 170, 109, 0.3); border-radius: 15px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(4, 170, 109, 0.1);">
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                <div style="flex: 1; min-width: 250px;">
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: #04AA6D; margin: 0 0 0.75rem 0; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-chart-line"></i>
                        {{ trans('app.formations.progress.title') ?? 'Votre Progression' }}
                    </h3>
                    <div style="width: 100%; height: 12px; background: rgba(4, 170, 109, 0.2); border-radius: 6px; overflow: hidden; margin-bottom: 0.75rem;">
                        <div style="height: 100%; width: {{ $progress->progress_percentage }}%; background: linear-gradient(90deg, #04AA6D, #06b6d4); transition: width 0.6s ease; border-radius: 6px;"></div>
                    </div>
                    <div style="display: flex; gap: 1.5rem; flex-wrap: wrap; font-size: 0.9rem; color: #2c3e50;">
                        <span style="display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-percentage" style="color: #04AA6D;"></i>
                            <strong>{{ $progress->progress_percentage }}%</strong> {{ trans('app.formations.progress.completed') }}
                        </span>
                        <span style="display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-clock" style="color: #06b6d4;"></i>
                            <strong>{{ $progress->time_spent_minutes }}</strong> {{ trans('app.profile.dashboard.overview.minutes') }}
                        </span>
                        @if($progress->completed_at)
                            <span style="display: flex; align-items: center; gap: 0.5rem; color: #10b981;">
                                <i class="fas fa-check-circle"></i>
                                {{ trans('app.formations.progress.completed_on') }} {{ $progress->completed_at->format('d/m/Y') }}
                            </span>
                        @endif
                    </div>
                </div>
                <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                    <a href="{{ route('dashboard.formations') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #04AA6D, #038f5a); color: white; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.95rem; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(4, 170, 109, 0.3);">
                        <i class="fas fa-tachometer-alt"></i>
                        {{ trans('app.formations.progress.view_dashboard') }}
                    </a>
                    @if($progress->progress_percentage < 100)
                    @php
                        // Déterminer la section de reprise : utiliser section_id si disponible, sinon la première section non complétée, sinon #intro
                        $continueSection = '#intro';
                        if ($progress->section_id) {
                            $continueSection = '#' . $progress->section_id;
                        } else {
                            // Trouver la première section non complétée
                            $completedSections = $progress->completed_sections ?? [];
                            $allSections = ['intro', 'editors', 'basic', 'elements', 'attributes', 'headings', 'paragraphs', 'styles', 'formatting', 'quotations', 'comments', 'colors', 'links', 'images', 'tables', 'lists', 'forms', 'media', 'canvas', 'svg', 'apis', 'semantic'];
                            foreach ($allSections as $section) {
                                if (!in_array($section, $completedSections)) {
                                    $continueSection = '#' . $section;
                                    break;
                                }
                            }
                        }
                    @endphp
                    <a href="{{ $continueSection }}" data-no-loader="true" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: white; color: #04AA6D; border: 2px solid #04AA6D; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.95rem; transition: all 0.3s ease;">
                        <i class="fas fa-arrow-down"></i>
                        {{ trans('app.formations.progress.continue') }}
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
@else
    <div style="max-width: 1200px; margin: 20px auto; padding: 0 20px;">
        <div style="background: linear-gradient(135deg, rgba(4, 170, 109, 0.15) 0%, rgba(6, 182, 212, 0.1) 100%); border: 2px solid rgba(4, 170, 109, 0.4); border-radius: 15px; padding: 2rem; box-shadow: 0 8px 25px rgba(4, 170, 109, 0.2); position: relative; overflow: hidden;">
            <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: radial-gradient(circle, rgba(4, 170, 109, 0.2) 0%, transparent 70%); border-radius: 50%;"></div>
            <div style="position: relative; z-index: 2;">
                <div style="display: flex; align-items: start; gap: 1.5rem; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 300px;">
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #04AA6D, #06b6d4); display: flex; align-items: center; justify-content: center; color: white; font-size: 1.75rem; box-shadow: 0 4px 15px rgba(4, 170, 109, 0.4);">
                                <i class="fas fa-rocket"></i>
                            </div>
                            <h3 style="font-size: 1.5rem; font-weight: 700; color: #2c3e50; margin: 0;">
                                {{ trans('app.formations.cta.title') }}
                            </h3>
                        </div>
                        <p style="font-size: 1.1rem; color: #2c3e50; margin-bottom: 1.5rem; line-height: 1.7;">
                            {{ trans('app.formations.cta.description') }}
                        </p>
                        <div style="display: flex; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; color: #2c3e50; font-size: 0.95rem;">
                                <i class="fas fa-check-circle" style="color: #04AA6D;"></i>
                                <span>{{ trans('app.formations.cta.benefit1') }}</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem; color: #2c3e50; font-size: 0.95rem;">
                                <i class="fas fa-check-circle" style="color: #04AA6D;"></i>
                                <span>{{ trans('app.formations.cta.benefit2') }}</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem; color: #2c3e50; font-size: 0.95rem;">
                                <i class="fas fa-check-circle" style="color: #04AA6D;"></i>
                                <span>{{ trans('app.formations.cta.benefit3') }}</span>
                            </div>
                        </div>
                        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                            <a href="{{ route('register') }}" style="display: inline-flex; align-items: center; gap: 0.75rem; padding: 1rem 2rem; background: linear-gradient(135deg, #04AA6D, #038f5a); color: white; border-radius: 10px; text-decoration: none; font-weight: 700; font-size: 1.1rem; transition: all 0.3s ease; box-shadow: 0 6px 20px rgba(4, 170, 109, 0.4);">
                                <i class="fas fa-user-plus"></i>
                                {{ trans('app.formations.cta.create_account') }}
                            </a>
                            <a href="{{ route('login') }}" style="display: inline-flex; align-items: center; gap: 0.75rem; padding: 1rem 2rem; background: white; color: #04AA6D; border: 2px solid #04AA6D; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 1.1rem; transition: all 0.3s ease;">
                                <i class="fas fa-sign-in-alt"></i>
                                {{ trans('app.formations.cta.login') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endauth

<!-- Content -->
<div class="tutorial-content">
    <div class="content-wrapper">
        <!-- Sidebar Toggle Button (Mobile) -->
        <button class="sidebar-toggle-btn" id="sidebarToggle" aria-label="{{ trans('app.formations.html5.menu_open') }}">
            <i class="fas fa-bars" id="sidebarToggleIcon"></i>
        </button>
        
        <!-- Sidebar Overlay (Mobile) -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        
        <!-- Sidebar -->
        <aside class="sidebar" id="tutorialSidebar">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid rgba(4, 170, 109, 0.2);">
                <h3 style="margin: 0;">{{ trans('app.formations.html5.title') }}</h3>
                <button class="sidebar-close-btn" id="sidebarClose" style="display: none; background: none; border: none; color: #04AA6D; font-size: 24px; cursor: pointer; padding: 5px; width: 35px; height: 35px; border-radius: 50%; transition: all 0.3s ease;" aria-label="{{ trans('app.formations.html5.menu_close') }}">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <a href="#intro" class="active">{{ trans('app.formations.html5.sidebar_menu')[0] }}</a>
            <a href="#editors">{{ trans('app.formations.html5.sidebar_menu')[1] }}</a>
            <a href="#basic">{{ trans('app.formations.html5.sidebar_menu')[2] }}</a>
            <a href="#elements">{{ trans('app.formations.html5.sidebar_menu')[3] }}</a>
            <a href="#attributes">{{ trans('app.formations.html5.sidebar_menu')[4] }}</a>
            <a href="#headings">{{ trans('app.formations.html5.sidebar_menu')[5] }}</a>
            <a href="#paragraphs">{{ trans('app.formations.html5.sidebar_menu')[6] }}</a>
            <a href="#styles">{{ trans('app.formations.html5.sidebar_menu')[7] }}</a>
            <a href="#formatting">{{ trans('app.formations.html5.sidebar_menu')[8] }}</a>
            <a href="#quotations">{{ trans('app.formations.html5.sidebar_menu')[9] }}</a>
            <a href="#comments">{{ trans('app.formations.html5.sidebar_menu')[10] }}</a>
            <a href="#colors">{{ trans('app.formations.html5.sidebar_menu')[11] }}</a>
            <a href="#links">{{ trans('app.formations.html5.sidebar_menu')[12] }}</a>
            <a href="#images">{{ trans('app.formations.html5.sidebar_menu')[13] }}</a>
            <a href="#tables">{{ trans('app.formations.html5.sidebar_menu')[14] }}</a>
            <a href="#lists">{{ trans('app.formations.html5.sidebar_menu')[15] }}</a>
            <a href="#forms">{{ trans('app.formations.html5.sidebar_menu')[16] }}</a>
            <a href="#media">{{ trans('app.formations.html5.sidebar_menu')[17] }}</a>
            <a href="#canvas">{{ trans('app.formations.html5.sidebar_menu')[18] }}</a>
            <a href="#svg">{{ trans('app.formations.html5.sidebar_menu')[19] }}</a>
            <a href="#apis">{{ trans('app.formations.html5.sidebar_menu')[20] }}</a>
            <a href="#semantic">{{ trans('app.formations.html5.sidebar_menu')[21] }}</a>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <h1 id="intro">{{ trans('app.formations.html5.intro_title') }}</h1>
            <p>{{ trans('app.formations.html5.intro_text') }}</p>

            <h3>{{ trans('app.formations.html5.why_learn_title') }}</h3>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                @foreach(trans('app.formations.html5.why_learn_items') as $item)
                <li>✅ <strong>{{ explode(' - ', $item)[0] }}</strong>@if(isset(explode(' - ', $item)[1])) - {{ explode(' - ', $item)[1] }}@endif</li>
                @endforeach
            </ul>

            <h2 id="editors">{{ trans('app.formations.html5.editors_title') }}</h2>
            <p>{{ trans('app.formations.html5.editors_text') }}</p>
            
            <h3>{{ trans('app.formations.html5.editors_recommended') }}</h3>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                @foreach(trans('app.formations.html5.editors_list') as $editor)
                <li>✅ <strong>{{ explode(' - ', $editor)[0] }}</strong>@if(isset(explode(' - ', $editor)[1])) - {{ explode(' - ', $editor)[1] }}@endif</li>
                @endforeach
            </ul>

            <div class="example-box">
                <h3>{{ trans('app.formations.html5.editors_install_title') }}</h3>
                <p>1. {{ trans('app.formations.html5.editors_install_steps')[0] }} <a href="https://code.visualstudio.com" target="_blank" class="text-cyan-600 hover:underline">code.visualstudio.com</a></p>
                <p>2. {{ trans('app.formations.html5.editors_install_steps')[1] }}</p>
                <p>3. {{ trans('app.formations.html5.editors_install_steps')[2] }} <code>.html</code></p>
                <p>4. {{ trans('app.formations.html5.editors_install_steps')[3] }}</p>
            </div>

            <div class="note-box">
                <p style="color: #000;"><strong>{{ trans('app.formations.html5.editors_tip') }}</strong></p>
            </div>

            <h2 id="basic">{{ trans('app.formations.html5.basic_title') }}</h2>
            <p>{{ trans('app.formations.html5.basic_text') }}</p>

            <h3>{{ trans('app.formations.html5.basic_structure_title') }}</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;!DOCTYPE html&gt;</span><br>
                        <span class="code-tag">&lt;html</span> <span class="code-attr">lang</span>=<span class="code-value">"fr"</span><span class="code-tag">&gt;</span><br>
                        <span class="code-tag">&lt;head&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;meta</span> <span class="code-attr">charset</span>=<span class="code-value">"UTF-8"</span><span class="code-tag">&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;title&gt;</span><span class="code-text">Ma Page</span><span class="code-tag">&lt;/title&gt;</span><br>
                        <span class="code-tag">&lt;/head&gt;</span><br>
                        <span class="code-tag">&lt;body&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-comment">&lt;!-- Votre contenu ici --&gt;</span><br>
                        <span class="code-tag">&lt;/body&gt;</span><br>
                        <span class="code-tag">&lt;/html&gt;</span>
                    </code>
                </div>
            </div>

            <h3>{{ trans('app.formations.html5.basic_tags_title') }}</h3>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li><code>&lt;!DOCTYPE html&gt;</code> - {{ trans('app.formations.html5.basic_tags_list')[0] }}</li>
                <li><code>&lt;html&gt;</code> - {{ trans('app.formations.html5.basic_tags_list')[1] }}</li>
                <li><code>&lt;head&gt;</code> - {{ trans('app.formations.html5.basic_tags_list')[2] }}</li>
                <li><code>&lt;body&gt;</code> - {{ trans('app.formations.html5.basic_tags_list')[3] }}</li>
                <li><code>&lt;meta&gt;</code> - {{ trans('app.formations.html5.basic_tags_list')[4] }}</li>
                <li><code>&lt;title&gt;</code> - {{ trans('app.formations.html5.basic_tags_list')[5] }}</li>
            </ul>

            <h2>{{ trans('app.formations.html5.first_doc_title') }}</h2>
            <p>{{ trans('app.formations.html5.first_doc_text') }}</p>
            
            <div class="example-box">
                <h3>{{ trans('app.formations.html5.first_doc_example_title') }}</h3>
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;!DOCTYPE html&gt;</span><br>
                        <span class="code-tag">&lt;html</span> <span class="code-attr">lang</span>=<span class="code-value">"fr"</span><span class="code-tag">&gt;</span><br>
                        <span class="code-tag">&lt;head&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;meta</span> <span class="code-attr">charset</span>=<span class="code-value">"UTF-8"</span><span class="code-tag">&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;meta</span> <span class="code-attr">name</span>=<span class="code-value">"viewport"</span> <span class="code-attr">content</span>=<span class="code-value">"width=device-width, initial-scale=1.0"</span><span class="code-tag">&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;title&gt;</span><span class="code-text">Ma Première Page HTML5</span><span class="code-tag">&lt;/title&gt;</span><br>
                        <span class="code-tag">&lt;/head&gt;</span><br>
                        <span class="code-tag">&lt;body&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;h1&gt;</span><span class="code-text">Bienvenue sur ma page !</span><span class="code-tag">&lt;/h1&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;p&gt;</span><span class="code-text">Ceci est mon premier paragraphe HTML5.</span><span class="code-tag">&lt;/p&gt;</span><br>
                        <span class="code-tag">&lt;/body&gt;</span><br>
                        <span class="code-tag">&lt;/html&gt;</span>
                    </code>
                </div>
            </div>

            <div class="note-box">
                <p><strong>{{ trans('app.formations.html5.first_doc_explanation_title') }}</strong></p>
                <ul style="margin-left: 20px; line-height: 1.8; color: #000;">
                    <li><code>&lt;!DOCTYPE html&gt;</code> - {{ trans('app.formations.html5.first_doc_explanation_list')[0] }}</li>
                    <li><code>&lt;html&gt;</code> - {{ trans('app.formations.html5.first_doc_explanation_list')[1] }}</li>
                    <li><code>&lt;head&gt;</code> - {{ trans('app.formations.html5.first_doc_explanation_list')[2] }}</li>
                    <li><code>&lt;body&gt;</code> - {{ trans('app.formations.html5.first_doc_explanation_list')[3] }}</li>
                </ul>
            </div>

            <h2 id="elements">{{ trans('app.formations.html5.elements_title') }}</h2>
            <p>{!! trans('app.formations.html5.elements_text') !!}</p>
            
            <div class="code-box">
                <code>
                    <span class="code-tag">&lt;nombalise&gt;</span><span class="code-text">Le contenu va ici...</span><span class="code-tag">&lt;/nombalise&gt;</span>
                </code>
            </div>

            <h3>{{ trans('app.formations.html5.elements_common_title') }}</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;h1&gt;</span><span class="code-text">Titre principal</span><span class="code-tag">&lt;/h1&gt;</span><br>
                        <span class="code-tag">&lt;h2&gt;</span><span class="code-text">Sous-titre</span><span class="code-tag">&lt;/h2&gt;</span><br>
                        <span class="code-tag">&lt;p&gt;</span><span class="code-text">Un paragraphe de texte.</span><span class="code-tag">&lt;/p&gt;</span><br>
                        <span class="code-tag">&lt;a</span> <span class="code-attr">href</span>=<span class="code-value">"https://example.com"</span><span class="code-tag">&gt;</span><span class="code-text">Un lien</span><span class="code-tag">&lt;/a&gt;</span><br>
                        <span class="code-tag">&lt;img</span> <span class="code-attr">src</span>=<span class="code-value">"image.jpg"</span> <span class="code-attr">alt</span>=<span class="code-value">"Description"</span><span class="code-tag">&gt;</span><br>
                        <span class="code-tag">&lt;div&gt;</span><span class="code-text">Un conteneur</span><span class="code-tag">&lt;/div&gt;</span><br>
                        <span class="code-tag">&lt;span&gt;</span><span class="code-text">Un élément en ligne</span><span class="code-tag">&lt;/span&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="attributes">{{ trans('app.formations.html5.attributes_title') }}</h2>
            <p>{{ trans('app.formations.html5.attributes_text') }}</p>
            
            <div class="example-box">
                <h3>{{ trans('app.formations.html5.attributes_example_title') }}</h3>
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;a</span> <span class="code-attr">href</span>=<span class="code-value">"https://devformation.com"</span> <span class="code-attr">target</span>=<span class="code-value">"_blank"</span> <span class="code-attr">title</span>=<span class="code-value">"Visitez DevFormation"</span><span class="code-tag">&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-text">Cliquez ici</span><br>
                        <span class="code-tag">&lt;/a&gt;</span><br><br>
                        <span class="code-tag">&lt;img</span> <span class="code-attr">src</span>=<span class="code-value">"logo.png"</span> <span class="code-attr">alt</span>=<span class="code-value">"Logo DevFormation"</span> <span class="code-attr">width</span>=<span class="code-value">"200"</span> <span class="code-attr">height</span>=<span class="code-value">"100"</span><span class="code-tag">&gt;</span><br><br>
                        <span class="code-tag">&lt;div</span> <span class="code-attr">id</span>=<span class="code-value">"header"</span> <span class="code-attr">class</span>=<span class="code-value">"container"</span><span class="code-tag">&gt;</span><span class="code-text">Contenu</span><span class="code-tag">&lt;/div&gt;</span>
                    </code>
                </div>
            </div>

            <h3>{{ trans('app.formations.html5.attributes_common_title') }}</h3>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li><code>href</code> - {{ trans('app.formations.html5.attributes_list')[0] }}</li>
                <li><code>src</code> - {{ trans('app.formations.html5.attributes_list')[1] }}</li>
                <li><code>alt</code> - {{ trans('app.formations.html5.attributes_list')[2] }}</li>
                <li><code>id</code> - {{ trans('app.formations.html5.attributes_list')[3] }}</li>
                <li><code>class</code> - {{ trans('app.formations.html5.attributes_list')[4] }}</li>
                <li><code>style</code> - {{ trans('app.formations.html5.attributes_list')[5] }}</li>
                <li><code>title</code> - {{ trans('app.formations.html5.attributes_list')[6] }}</li>
            </ul>

            <h2 id="headings">{{ trans('app.formations.html5.headings_title') }}</h2>
            <p>{!! trans('app.formations.html5.headings_text') !!}</p>
            
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;h1&gt;</span><span class="code-text">Titre de niveau 1 (le plus important)</span><span class="code-tag">&lt;/h1&gt;</span><br>
                        <span class="code-tag">&lt;h2&gt;</span><span class="code-text">Titre de niveau 2</span><span class="code-tag">&lt;/h2&gt;</span><br>
                        <span class="code-tag">&lt;h3&gt;</span><span class="code-text">Titre de niveau 3</span><span class="code-tag">&lt;/h3&gt;</span><br>
                        <span class="code-tag">&lt;h4&gt;</span><span class="code-text">Titre de niveau 4</span><span class="code-tag">&lt;/h4&gt;</span><br>
                        <span class="code-tag">&lt;h5&gt;</span><span class="code-text">Titre de niveau 5</span><span class="code-tag">&lt;/h5&gt;</span><br>
                        <span class="code-tag">&lt;h6&gt;</span><span class="code-text">Titre de niveau 6 (le moins important)</span><span class="code-tag">&lt;/h6&gt;</span>
                    </code>
                </div>
            </div>

            <div class="note-box">
                <p style="color: #000;"><strong>{!! trans('app.formations.html5.headings_important') !!}</strong></p>
            </div>

            <h2 id="paragraphs">{{ trans('app.formations.html5.paragraphs_title') }}</h2>
            <p>{!! trans('app.formations.html5.paragraphs_text') !!}</p>
            
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;p&gt;</span><span class="code-text">Ceci est un paragraphe.</span><span class="code-tag">&lt;/p&gt;</span><br>
                        <span class="code-tag">&lt;p&gt;</span><span class="code-text">Ceci est un autre paragraphe.</span><span class="code-tag">&lt;/p&gt;</span><br>
                        <span class="code-tag">&lt;p&gt;</span><span class="code-text">Les paragraphes peuvent contenir </span><span class="code-tag">&lt;strong&gt;</span><span class="code-text">du texte en gras</span><span class="code-tag">&lt;/strong&gt;</span><span class="code-text"> et </span><span class="code-tag">&lt;em&gt;</span><span class="code-text">en italique</span><span class="code-tag">&lt;/em&gt;</span><span class="code-text">.</span><span class="code-tag">&lt;/p&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="styles">{{ trans('app.formations.html5.styles_title') }}</h2>
            <p>{{ trans('app.formations.html5.styles_text') }}</p>

            <h3>{{ trans('app.formations.html5.styles_inline_title') }}</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;p</span> <span class="code-attr">style</span>=<span class="code-value">"color: blue; font-size: 20px;"</span><span class="code-tag">&gt;</span><span class="code-text">Texte bleu</span><span class="code-tag">&lt;/p&gt;</span>
                    </code>
                </div>
            </div>

            <h3>{!! trans('app.formations.html5.styles_internal_title') !!}</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;head&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;style&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-text">p { color: red; }</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;/style&gt;</span><br>
                        <span class="code-tag">&lt;/head&gt;</span>
                    </code>
                </div>
            </div>

            <h3>{{ trans('app.formations.html5.styles_external_title') }}</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;head&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;link</span> <span class="code-attr">rel</span>=<span class="code-value">"stylesheet"</span> <span class="code-attr">href</span>=<span class="code-value">"styles.css"</span><span class="code-tag">&gt;</span><br>
                        <span class="code-tag">&lt;/head&gt;</span>
                    </code>
                </div>
            </div>

            <div class="note-box">
                <p><strong>{{ trans('app.formations.html5.styles_best_practice') }}</strong></p>
            </div>

            <h2 id="formatting">{{ trans('app.formations.html5.formatting_title') }}</h2>
            <p>{{ trans('app.formations.html5.formatting_text') }}</p>
            
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;b&gt;</span><span class="code-text">Texte en gras</span><span class="code-tag">&lt;/b&gt;</span><br>
                        <span class="code-tag">&lt;strong&gt;</span><span class="code-text">Texte important (gras)</span><span class="code-tag">&lt;/strong&gt;</span><br>
                        <span class="code-tag">&lt;i&gt;</span><span class="code-text">Texte en italique</span><span class="code-tag">&lt;/i&gt;</span><br>
                        <span class="code-tag">&lt;em&gt;</span><span class="code-text">Texte accentué (italique)</span><span class="code-tag">&lt;/em&gt;</span><br>
                        <span class="code-tag">&lt;mark&gt;</span><span class="code-text">Texte surligné</span><span class="code-tag">&lt;/mark&gt;</span><br>
                        <span class="code-tag">&lt;small&gt;</span><span class="code-text">Texte plus petit</span><span class="code-tag">&lt;/small&gt;</span><br>
                        <span class="code-tag">&lt;del&gt;</span><span class="code-text">Texte supprimé</span><span class="code-tag">&lt;/del&gt;</span><br>
                        <span class="code-tag">&lt;ins&gt;</span><span class="code-text">Texte inséré</span><span class="code-tag">&lt;/ins&gt;</span><br>
                        <span class="code-tag">&lt;sub&gt;</span><span class="code-text">Texte en indice</span><span class="code-tag">&lt;/sub&gt;</span><br>
                        <span class="code-tag">&lt;sup&gt;</span><span class="code-text">Texte en exposant</span><span class="code-tag">&lt;/sup&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="quotations">{{ trans('app.formations.html5.quotations_title') }}</h2>
            <p>{{ trans('app.formations.html5.quotations_text') }}</p>

            <h3>{!! trans('app.formations.html5.quotations_short_title') !!}</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;p&gt;</span><span class="code-text">Einstein a dit : </span><span class="code-tag">&lt;q&gt;</span><span class="code-text">L'imagination est plus importante que le savoir</span><span class="code-tag">&lt;/q&gt;</span><span class="code-tag">&lt;/p&gt;</span>
                    </code>
                </div>
            </div>

            <h3>{!! trans('app.formations.html5.quotations_long_title') !!}</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;blockquote</span> <span class="code-attr">cite</span>=<span class="code-value">"https://source.com"</span><span class="code-tag">&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-text">Ceci est une citation longue qui sera affichée avec une indentation.</span><br>
                        <span class="code-tag">&lt;/blockquote&gt;</span>
                    </code>
                </div>
            </div>

            <h3>{{ trans('app.formations.html5.quotations_other_title') }}</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;abbr</span> <span class="code-attr">title</span>=<span class="code-value">"HyperText Markup Language"</span><span class="code-tag">&gt;</span><span class="code-text">HTML</span><span class="code-tag">&lt;/abbr&gt;</span><br>
                        <span class="code-tag">&lt;cite&gt;</span><span class="code-text">Le Petit Prince</span><span class="code-tag">&lt;/cite&gt;</span><br>
                        <span class="code-tag">&lt;address&gt;</span><span class="code-text">123 Rue Example, Dakar</span><span class="code-tag">&lt;/address&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="comments">{{ trans('app.formations.html5.comments_title') }}</h2>
            <p>{{ trans('app.formations.html5.comments_text') }}</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-comment">&lt;!-- Ceci est un commentaire sur une ligne --&gt;</span><br><br>
                        <span class="code-comment">&lt;!--</span><br>
                        <span class="code-comment">&nbsp;&nbsp;Ceci est un commentaire</span><br>
                        <span class="code-comment">&nbsp;&nbsp;sur plusieurs lignes</span><br>
                        <span class="code-comment">--&gt;</span><br><br>
                        <span class="code-tag">&lt;p&gt;</span><span class="code-text">Contenu visible</span><span class="code-tag">&lt;/p&gt;</span><br>
                        <span class="code-comment">&lt;!-- &lt;p&gt;Contenu commenté (caché)&lt;/p&gt; --&gt;</span>
                    </code>
                </div>
            </div>

            <div class="note-box">
                <p><strong>{{ trans('app.formations.html5.comments_useful_title') }}</strong></p>
                <ul style="margin-left: 20px; line-height: 1.8; color: #000;">
                    @foreach(trans('app.formations.html5.comments_useful_list') as $item)
                    <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>

            <h2 id="colors">{{ trans('app.formations.html5.colors_title') }}</h2>
            <p>{{ trans('app.formations.html5.colors_text') }}</p>

            <h3>{{ trans('app.formations.html5.colors_names_title') }}</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;p</span> <span class="code-attr">style</span>=<span class="code-value">"color: red;"</span><span class="code-tag">&gt;</span><span class="code-text">Texte rouge</span><span class="code-tag">&lt;/p&gt;</span><br>
                        <span class="code-tag">&lt;p</span> <span class="code-attr">style</span>=<span class="code-value">"color: blue;"</span><span class="code-tag">&gt;</span><span class="code-text">Texte bleu</span><span class="code-tag">&lt;/p&gt;</span>
                    </code>
                </div>
            </div>

            <h3>{{ trans('app.formations.html5.colors_hex_title') }}</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;p</span> <span class="code-attr">style</span>=<span class="code-value">"color: #FF0000;"</span><span class="code-tag">&gt;</span><span class="code-text">Rouge</span><span class="code-tag">&lt;/p&gt;</span><br>
                        <span class="code-tag">&lt;p</span> <span class="code-attr">style</span>=<span class="code-value">"color: #00FF00;"</span><span class="code-tag">&gt;</span><span class="code-text">Vert</span><span class="code-tag">&lt;/p&gt;</span><br>
                        <span class="code-tag">&lt;p</span> <span class="code-attr">style</span>=<span class="code-value">"color: #0000FF;"</span><span class="code-tag">&gt;</span><span class="code-text">Bleu</span><span class="code-tag">&lt;/p&gt;</span>
                    </code>
                </div>
            </div>

            <h3>{{ trans('app.formations.html5.colors_rgb_title') }}</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;p</span> <span class="code-attr">style</span>=<span class="code-value">"color: rgb(255, 0, 0);"</span><span class="code-tag">&gt;</span><span class="code-text">Rouge RGB</span><span class="code-tag">&lt;/p&gt;</span><br>
                        <span class="code-tag">&lt;p</span> <span class="code-attr">style</span>=<span class="code-value">"color: rgba(0, 0, 255, 0.5);"</span><span class="code-tag">&gt;</span><span class="code-text">Bleu transparent</span><span class="code-tag">&lt;/p&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="links">{{ trans('app.formations.html5.links_title') }}</h2>
            <p>{!! trans('app.formations.html5.links_text') !!}</p>
            
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;a</span> <span class="code-attr">href</span>=<span class="code-value">"https://devformation.com"</span><span class="code-tag">&gt;</span><span class="code-text">Visitez DevFormation</span><span class="code-tag">&lt;/a&gt;</span><br>
                        <span class="code-tag">&lt;a</span> <span class="code-attr">href</span>=<span class="code-value">"contact.html"</span><span class="code-tag">&gt;</span><span class="code-text">Page de contact</span><span class="code-tag">&lt;/a&gt;</span><br>
                        <span class="code-tag">&lt;a</span> <span class="code-attr">href</span>=<span class="code-value">"#section1"</span><span class="code-tag">&gt;</span><span class="code-text">Aller à la section 1</span><span class="code-tag">&lt;/a&gt;</span><br>
                        <span class="code-tag">&lt;a</span> <span class="code-attr">href</span>=<span class="code-value">"mailto:contact@devformation.com"</span><span class="code-tag">&gt;</span><span class="code-text">Envoyez un email</span><span class="code-tag">&lt;/a&gt;</span><br>
                        <span class="code-tag">&lt;a</span> <span class="code-attr">href</span>=<span class="code-value">"tel:+221783123657"</span><span class="code-tag">&gt;</span><span class="code-text">Appelez-nous</span><span class="code-tag">&lt;/a&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="images">{{ trans('app.formations.html5.images_title') }}</h2>
            <p>{!! trans('app.formations.html5.images_text') !!}</p>
            
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;img</span> <span class="code-attr">src</span>=<span class="code-value">"photo.jpg"</span> <span class="code-attr">alt</span>=<span class="code-value">"Description de l'image"</span><span class="code-tag">&gt;</span><br>
                        <span class="code-tag">&lt;img</span> <span class="code-attr">src</span>=<span class="code-value">"logo.png"</span> <span class="code-attr">alt</span>=<span class="code-value">"Logo"</span> <span class="code-attr">width</span>=<span class="code-value">"300"</span> <span class="code-attr">height</span>=<span class="code-value">"200"</span><span class="code-tag">&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="tables">{{ trans('app.formations.html5.tables_title') }}</h2>
            <p>{{ trans('app.formations.html5.tables_text') }}</p>

            <h3>{{ trans('app.formations.html5.tables_structure_title') }}</h3>
            <p>{{ trans('app.formations.html5.tables_structure_text') }}</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li><code>&lt;table&gt;</code> - {{ trans('app.formations.html5.tables_structure_list')[0] }}</li>
                <li><code>&lt;thead&gt;</code> - {{ trans('app.formations.html5.tables_structure_list')[1] }}</li>
                <li><code>&lt;tbody&gt;</code> - {{ trans('app.formations.html5.tables_structure_list')[2] }}</li>
                <li><code>&lt;tfoot&gt;</code> - {{ trans('app.formations.html5.tables_structure_list')[3] }}</li>
                <li><code>&lt;tr&gt;</code> - {{ trans('app.formations.html5.tables_structure_list')[4] }}</li>
                <li><code>&lt;th&gt;</code> - {{ trans('app.formations.html5.tables_structure_list')[5] }}</li>
                <li><code>&lt;td&gt;</code> - {{ trans('app.formations.html5.tables_structure_list')[6] }}</li>
            </ul>

            <h3>{{ trans('app.formations.html5.tables_example_title') }}</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;table</span> <span class="code-attr">border</span>=<span class="code-value">"1"</span><span class="code-tag">&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;thead&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;tr&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;th&gt;</span><span class="code-text">Nom</span><span class="code-tag">&lt;/th&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;th&gt;</span><span class="code-text">Âge</span><span class="code-tag">&lt;/th&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;th&gt;</span><span class="code-text">Ville</span><span class="code-tag">&lt;/th&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;/tr&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;/thead&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;tbody&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;tr&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;td&gt;</span><span class="code-text">Jean Dupont</span><span class="code-tag">&lt;/td&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;td&gt;</span><span class="code-text">25</span><span class="code-tag">&lt;/td&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;td&gt;</span><span class="code-text">Dakar</span><span class="code-tag">&lt;/td&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;/tr&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;tr&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;td&gt;</span><span class="code-text">Marie Martin</span><span class="code-tag">&lt;/td&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;td&gt;</span><span class="code-text">30</span><span class="code-tag">&lt;/td&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;td&gt;</span><span class="code-text">Thiès</span><span class="code-tag">&lt;/td&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;/tr&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;/tbody&gt;</span><br>
                        <span class="code-tag">&lt;/table&gt;</span>
                    </code>
                </div>
            </div>

            <h3>{{ trans('app.formations.html5.tables_merge_title') }}</h3>
            <div class="example-box">
                <h4 style="color: #000;">{{ trans('app.formations.html5.tables_merge_subtitle') }}</h4>
                <div class="code-box">
                    <code>
                        <span class="code-comment">&lt;!-- Fusion horizontale (colspan) --&gt;</span><br>
                        <span class="code-tag">&lt;td</span> <span class="code-attr">colspan</span>=<span class="code-value">"2"</span><span class="code-tag">&gt;</span><span class="code-text">Cette cellule occupe 2 colonnes</span><span class="code-tag">&lt;/td&gt;</span><br><br>
                        <span class="code-comment">&lt;!-- Fusion verticale (rowspan) --&gt;</span><br>
                        <span class="code-tag">&lt;td</span> <span class="code-attr">rowspan</span>=<span class="code-value">"3"</span><span class="code-tag">&gt;</span><span class="code-text">Cette cellule occupe 3 lignes</span><span class="code-tag">&lt;/td&gt;</span>
                    </code>
                </div>
            </div>

            <div class="note-box">
                <p style="color: #000;"><strong>{{ trans('app.formations.html5.tables_best_practices_title') }}</strong></p>
                <ul style="margin-left: 20px; line-height: 1.8; color: #000;">
                    @foreach(trans('app.formations.html5.tables_best_practices_list') as $item)
                    <li>{!! $item !!}</li>
                    @endforeach
                </ul>
            </div>

            <h2 id="lists">{{ trans('app.formations.html5.lists_title') }}</h2>
            <p>{{ trans('app.formations.html5.lists_text') }}</p>

            <h3>{!! trans('app.formations.html5.lists_unordered_title') !!}</h3>
            <p>{{ trans('app.formations.html5.lists_unordered_text') }}</p>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;ul&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;li&gt;</span><span class="code-text">HTML5</span><span class="code-tag">&lt;/li&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;li&gt;</span><span class="code-text">CSS3</span><span class="code-tag">&lt;/li&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;li&gt;</span><span class="code-text">JavaScript</span><span class="code-tag">&lt;/li&gt;</span><br>
                        <span class="code-tag">&lt;/ul&gt;</span>
                    </code>
                </div>
                <p style="color: #000; margin-top: 10px;"><strong>Résultat :</strong> • HTML5 • CSS3 • JavaScript</p>
            </div>

            <h3>{!! trans('app.formations.html5.lists_ordered_title') !!}</h3>
            <p>{{ trans('app.formations.html5.lists_ordered_text') }}</p>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;ol&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;li&gt;</span><span class="code-text">Ouvrir l'éditeur</span><span class="code-tag">&lt;/li&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;li&gt;</span><span class="code-text">Écrire le code HTML</span><span class="code-tag">&lt;/li&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;li&gt;</span><span class="code-text">Enregistrer le fichier</span><span class="code-tag">&lt;/li&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;li&gt;</span><span class="code-text">Ouvrir dans le navigateur</span><span class="code-tag">&lt;/li&gt;</span><br>
                        <span class="code-tag">&lt;/ol&gt;</span>
                    </code>
                </div>
                <p style="color: #000; margin-top: 10px;"><strong>Résultat :</strong> 1. Ouvrir l'éditeur 2. Écrire le code HTML 3. Enregistrer...</p>
            </div>

            <h3>{{ trans('app.formations.html5.lists_ordered_attrs_title') }}</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-comment">&lt;!-- Type de numérotation --&gt;</span><br>
                        <span class="code-tag">&lt;ol</span> <span class="code-attr">type</span>=<span class="code-value">"A"</span><span class="code-tag">&gt;</span><span class="code-text"> &lt;!-- A, B, C... --&gt;</span><br>
                        <span class="code-tag">&lt;ol</span> <span class="code-attr">type</span>=<span class="code-value">"a"</span><span class="code-tag">&gt;</span><span class="code-text"> &lt;!-- a, b, c... --&gt;</span><br>
                        <span class="code-tag">&lt;ol</span> <span class="code-attr">type</span>=<span class="code-value">"I"</span><span class="code-tag">&gt;</span><span class="code-text"> &lt;!-- I, II, III... --&gt;</span><br>
                        <span class="code-tag">&lt;ol</span> <span class="code-attr">type</span>=<span class="code-value">"i"</span><span class="code-tag">&gt;</span><span class="code-text"> &lt;!-- i, ii, iii... --&gt;</span><br><br>
                        <span class="code-comment">&lt;!-- Commencer à un numéro spécifique --&gt;</span><br>
                        <span class="code-tag">&lt;ol</span> <span class="code-attr">start</span>=<span class="code-value">"5"</span><span class="code-tag">&gt;</span><span class="code-text"> &lt;!-- Commence à 5 --&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;li&gt;</span><span class="code-text">Élément 5</span><span class="code-tag">&lt;/li&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;li&gt;</span><span class="code-text">Élément 6</span><span class="code-tag">&lt;/li&gt;</span><br>
                        <span class="code-tag">&lt;/ol&gt;</span>
                    </code>
                </div>
            </div>

            <h3>{!! trans('app.formations.html5.lists_definition_title') !!}</h3>
            <p>{{ trans('app.formations.html5.lists_definition_text') }}</p>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;dl&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;dt&gt;</span><span class="code-text">HTML</span><span class="code-tag">&lt;/dt&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;dd&gt;</span><span class="code-text">Langage de balisage pour créer des pages web</span><span class="code-tag">&lt;/dd&gt;</span><br><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;dt&gt;</span><span class="code-text">CSS</span><span class="code-tag">&lt;/dt&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;dd&gt;</span><span class="code-text">Langage de style pour la présentation des pages web</span><span class="code-tag">&lt;/dd&gt;</span><br>
                        <span class="code-tag">&lt;/dl&gt;</span>
                    </code>
                </div>
            </div>

            <h3>{{ trans('app.formations.html5.lists_nested_title') }}</h3>
            <p>{{ trans('app.formations.html5.lists_nested_text') }}</p>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;ul&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;li&gt;</span><span class="code-text">Technologies Front-end</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;ul&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;li&gt;</span><span class="code-text">HTML5</span><span class="code-tag">&lt;/li&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;li&gt;</span><span class="code-text">CSS3</span><span class="code-tag">&lt;/li&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;li&gt;</span><span class="code-text">JavaScript</span><span class="code-tag">&lt;/li&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;/ul&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;/li&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;li&gt;</span><span class="code-text">Technologies Back-end</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;ul&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;li&gt;</span><span class="code-text">PHP</span><span class="code-tag">&lt;/li&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;li&gt;</span><span class="code-text">Python</span><span class="code-tag">&lt;/li&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;/ul&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;/li&gt;</span><br>
                        <span class="code-tag">&lt;/ul&gt;</span>
                    </code>
                </div>
            </div>

            <div class="note-box">
                <p style="color: #000;"><strong>{{ trans('app.formations.html5.lists_when_title') }}</strong></p>
                <ul style="margin-left: 20px; line-height: 1.8; color: #000;">
                    <li><strong>&lt;ul&gt;</strong> - {{ trans('app.formations.html5.lists_when_list')[0] }}</li>
                    <li><strong>&lt;ol&gt;</strong> - {{ trans('app.formations.html5.lists_when_list')[1] }}</li>
                    <li><strong>&lt;dl&gt;</strong> - {{ trans('app.formations.html5.lists_when_list')[2] }}</li>
                </ul>
            </div>

            <h2 id="forms">{{ trans('app.formations.html5.forms_title') }}</h2>
            <p>{{ trans('app.formations.html5.forms_text') }}</p>

            <h3>{{ trans('app.formations.html5.forms_structure_title') }}</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;form</span> <span class="code-attr">action</span>=<span class="code-value">"/traitement.php"</span> <span class="code-attr">method</span>=<span class="code-value">"post"</span><span class="code-tag">&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;label</span> <span class="code-attr">for</span>=<span class="code-value">"nom"</span><span class="code-tag">&gt;</span><span class="code-text">Nom:</span><span class="code-tag">&lt;/label&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;input</span> <span class="code-attr">type</span>=<span class="code-value">"text"</span> <span class="code-attr">id</span>=<span class="code-value">"nom"</span> <span class="code-attr">name</span>=<span class="code-value">"nom"</span> <span class="code-attr">required</span><span class="code-tag">&gt;</span><br><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;label</span> <span class="code-attr">for</span>=<span class="code-value">"email"</span><span class="code-tag">&gt;</span><span class="code-text">Email:</span><span class="code-tag">&lt;/label&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;input</span> <span class="code-attr">type</span>=<span class="code-value">"email"</span> <span class="code-attr">id</span>=<span class="code-value">"email"</span> <span class="code-attr">name</span>=<span class="code-value">"email"</span><span class="code-tag">&gt;</span><br><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;button</span> <span class="code-attr">type</span>=<span class="code-value">"submit"</span><span class="code-tag">&gt;</span><span class="code-text">Envoyer</span><span class="code-tag">&lt;/button&gt;</span><br>
                        <span class="code-tag">&lt;/form&gt;</span>
                    </code>
                </div>
            </div>

            <h3>{{ trans('app.formations.html5.forms_input_types_title') }}</h3>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                @foreach(trans('app.formations.html5.forms_input_types_list') as $index => $type)
                <li><code>{{ ['text', 'email', 'password', 'number', 'tel', 'date', 'checkbox', 'radio', 'file'][$index] }}</code> - {{ $type }}</li>
                @endforeach
            </ul>

            <h3>{{ trans('app.formations.html5.forms_other_title') }}</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-comment">&lt;!-- Zone de texte multiligne --&gt;</span><br>
                        <span class="code-tag">&lt;textarea</span> <span class="code-attr">name</span>=<span class="code-value">"message"</span> <span class="code-attr">rows</span>=<span class="code-value">"5"</span><span class="code-tag">&gt;&lt;/textarea&gt;</span><br><br>
                        <span class="code-comment">&lt;!-- Liste déroulante --&gt;</span><br>
                        <span class="code-tag">&lt;select</span> <span class="code-attr">name</span>=<span class="code-value">"pays"</span><span class="code-tag">&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;option</span> <span class="code-attr">value</span>=<span class="code-value">"sn"</span><span class="code-tag">&gt;</span><span class="code-text">Sénégal</span><span class="code-tag">&lt;/option&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;option</span> <span class="code-attr">value</span>=<span class="code-value">"fr"</span><span class="code-tag">&gt;</span><span class="code-text">France</span><span class="code-tag">&lt;/option&gt;</span><br>
                        <span class="code-tag">&lt;/select&gt;</span>
                    </code>
                </div>
            </div>

            <div class="note-box">
                <p style="color: #000;"><strong>{{ trans('app.formations.html5.forms_best_practices_title') }}</strong></p>
                <ul style="margin-left: 20px; line-height: 1.8; color: #000;">
                    @foreach(trans('app.formations.html5.forms_best_practices_list') as $item)
                    <li>{!! $item !!}</li>
                    @endforeach
                </ul>
            </div>

            <h2 id="media">{{ trans('app.formations.html5.media_title') }}</h2>
            <p>{{ trans('app.formations.html5.media_text') }}</p>

            <h3>{{ trans('app.formations.html5.media_video_title') }}</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;video</span> <span class="code-attr">width</span>=<span class="code-value">"640"</span> <span class="code-attr">height</span>=<span class="code-value">"360"</span> <span class="code-attr">controls</span><span class="code-tag">&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;source</span> <span class="code-attr">src</span>=<span class="code-value">"video.mp4"</span> <span class="code-attr">type</span>=<span class="code-value">"video/mp4"</span><span class="code-tag">&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-text">Votre navigateur ne supporte pas la vidéo.</span><br>
                        <span class="code-tag">&lt;/video&gt;</span>
                    </code>
                </div>
            </div>

            <h3>{{ trans('app.formations.html5.media_audio_title') }}</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;audio</span> <span class="code-attr">controls</span><span class="code-tag">&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;source</span> <span class="code-attr">src</span>=<span class="code-value">"audio.mp3"</span> <span class="code-attr">type</span>=<span class="code-value">"audio/mpeg"</span><span class="code-tag">&gt;</span><br>
                        <span class="code-tag">&lt;/audio&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="canvas">{{ trans('app.formations.html5.canvas_title') }}</h2>
            <p>{!! trans('app.formations.html5.canvas_text') !!}</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;canvas</span> <span class="code-attr">id</span>=<span class="code-value">"monCanvas"</span> <span class="code-attr">width</span>=<span class="code-value">"400"</span> <span class="code-attr">height</span>=<span class="code-value">"200"</span><span class="code-tag">&gt;&lt;/canvas&gt;</span><br>
                        <span class="code-tag">&lt;script&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-text">const ctx = document.getElementById('monCanvas').getContext('2d');</span><br>
                        &nbsp;&nbsp;<span class="code-text">ctx.fillStyle = '#04AA6D';</span><br>
                        &nbsp;&nbsp;<span class="code-text">ctx.fillRect(20, 20, 150, 100);</span><br>
                        <span class="code-tag">&lt;/script&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="svg">{{ trans('app.formations.html5.svg_title') }}</h2>
            <p>{{ trans('app.formations.html5.svg_text') }}</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;svg</span> <span class="code-attr">width</span>=<span class="code-value">"200"</span> <span class="code-attr">height</span>=<span class="code-value">"200"</span><span class="code-tag">&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;circle</span> <span class="code-attr">cx</span>=<span class="code-value">"100"</span> <span class="code-attr">cy</span>=<span class="code-value">"100"</span> <span class="code-attr">r</span>=<span class="code-value">"80"</span> <span class="code-attr">fill</span>=<span class="code-value">"#04AA6D"</span><span class="code-tag">/&gt;</span><br>
                        <span class="code-tag">&lt;/svg&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="apis">{{ trans('app.formations.html5.apis_title') }}</h2>
            <p>{{ trans('app.formations.html5.apis_text') }}</p>

            <h3>{{ trans('app.formations.html5.apis_geolocation_title') }}</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;script&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-text">navigator.geolocation.getCurrentPosition(function(pos) {</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-text">console.log(pos.coords.latitude);</span><br>
                        &nbsp;&nbsp;<span class="code-text">});</span><br>
                        <span class="code-tag">&lt;/script&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="semantic">{{ trans('app.formations.html5.semantic_title') }}</h2>
            <p>{{ trans('app.formations.html5.semantic_text') }}</p>
            
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;header&gt;</span><span class="code-text">En-tête de la page</span><span class="code-tag">&lt;/header&gt;</span><br>
                        <span class="code-tag">&lt;nav&gt;</span><span class="code-text">Menu de navigation</span><span class="code-tag">&lt;/nav&gt;</span><br>
                        <span class="code-tag">&lt;main&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;article&gt;</span><span class="code-text">Contenu principal</span><span class="code-tag">&lt;/article&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;aside&gt;</span><span class="code-text">Contenu secondaire</span><span class="code-tag">&lt;/aside&gt;</span><br>
                        <span class="code-tag">&lt;/main&gt;</span><br>
                        <span class="code-tag">&lt;footer&gt;</span><span class="code-text">Pied de page</span><span class="code-tag">&lt;/footer&gt;</span>
                    </code>
                </div>
            </div>

            <h2>{{ trans('app.formations.html5.next_steps_title') }}</h2>
            <p>{{ trans('app.formations.html5.explore_chapters') }}</p>
            
            <div class="example-box" style="background-color: #d4edda; border-left-color: #28a745;">
                <h3 style="color: #000;">{{ trans('app.formations.html5.learned_title') }}</h3>
                <ul style="margin-left: 20px; line-height: 2; color: #000;">
                    @foreach(trans('app.formations.html5.learned_list') as $item)
                    <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>

            <!-- Navigation Buttons -->
            <div class="nav-buttons">
                <a href="{{ route('home') }}" class="nav-btn">❮ {{ trans('app.formations.html5.home') }}</a>
                <a href="{{ route('formations.css3') }}" class="nav-btn">{{ trans('app.formations.html5.next') }}: CSS3 ❯</a>
            </div>
        </main>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // SCRIPT DE GESTION MOBILE - DOIT S'EXÉCUTER EN PREMIER
    (function() {
        'use strict';
        
        // FLAG pour éviter les boucles infinies
        let isApplyingStyles = false;
        let hasInitialized = false;
        
        function isMobile() {
            return window.innerWidth <= 992;
        }
        
        function forceMobileSidebarState() {
            // Éviter les appels récursifs
            if (isApplyingStyles) {
                return;
            }
            
            const sidebar = document.getElementById('tutorialSidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            if (!sidebar || !sidebarToggle) {
                return;
            }
            
            isApplyingStyles = true;
            
            try {
                if (isMobile()) {
                    // FORCER le sidebar à être caché avec styles inline
                    if (!sidebar.classList.contains('active')) {
                        const currentDisplay = window.getComputedStyle(sidebar).display;
                        if (currentDisplay !== 'none') {
                            sidebar.style.cssText = 'display: none !important; position: fixed !important; bottom: 0 !important; left: 0 !important; width: 85% !important; max-width: 400px !important; height: 70vh !important; max-height: 600px !important; border-radius: 20px 20px 0 0 !important; transform: translateY(100%) !important; z-index: 9999 !important; opacity: 0 !important; visibility: hidden !important; top: auto !important; align-self: auto !important;';
                        }
                    }
                    
                    // FORCER le bouton burger à être visible
                    const toggleDisplay = window.getComputedStyle(sidebarToggle).display;
                    if (toggleDisplay === 'none' || toggleDisplay === '') {
                        sidebarToggle.style.cssText = 'display: flex !important; visibility: visible !important; opacity: 1 !important; position: fixed !important; bottom: 20px !important; left: 20px !important; width: 60px !important; height: 60px !important; background: linear-gradient(135deg, #04AA6D, #038f5a) !important; border: none !important; border-radius: 50% !important; color: white !important; font-size: 24px !important; cursor: pointer !important; z-index: 10000 !important; box-shadow: 0 8px 25px rgba(4, 170, 109, 0.4) !important; align-items: center !important; justify-content: center !important;';
                    }
                    
                    // FORCER l'overlay à être caché
                    if (sidebarOverlay && !sidebarOverlay.classList.contains('active')) {
                        sidebarOverlay.style.cssText = 'display: none !important; opacity: 0 !important; visibility: hidden !important;';
                    }
                } else {
                    // Desktop : restaurer les styles normaux
                    if (sidebar.classList.contains('active')) {
                        // Ne pas modifier si actif (peut être ouvert manuellement)
                        return;
                    }
                    sidebar.style.cssText = '';
                    sidebarToggle.style.cssText = 'display: none !important;';
                }
            } finally {
                // Réinitialiser le flag après un court délai
                setTimeout(function() {
                    isApplyingStyles = false;
                }, 50);
            }
        }
        
        // Fonction d'initialisation unique
        function initMobileSidebar() {
            if (hasInitialized) {
                return;
            }
            hasInitialized = true;
            
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    forceMobileSidebarState();
                    // Une seule réexécution après un court délai
                    setTimeout(forceMobileSidebarState, 300);
                });
            } else {
                forceMobileSidebarState();
                setTimeout(forceMobileSidebarState, 300);
            }
        }
        
        // Initialiser une seule fois
        initMobileSidebar();
        
        // Surveiller les changements de taille (avec debounce)
        let resizeTimer;
        let lastWidth = window.innerWidth;
        window.addEventListener('resize', function() {
            const currentWidth = window.innerWidth;
            // Ne réagir que si on change vraiment de mode (mobile/desktop)
            const wasMobile = lastWidth <= 992;
            const isNowMobile = currentWidth <= 992;
            
            if (wasMobile !== isNowMobile) {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    hasInitialized = false; // Réinitialiser pour permettre la réinitialisation
                    initMobileSidebar();
                    lastWidth = currentWidth;
                }, 200);
            }
        });
    })();
</script>
<script src="{{ asset('js/sidebar-sticky.js') }}"></script>
<script>
    // Masquer les logs de sidebar-navigation avant le chargement du script
    (function() {
        const originalLog = console.log;
        console.log = function(...args) {
            const message = args.join(' ').toLowerCase();
            if (message.includes('sidebar navigation')) {
                return; // Ne pas afficher
            }
            originalLog.apply(console, args);
        };
    })();
</script>
<script src="{{ asset('js/sidebar-navigation.js') }}?v=2.1"></script>
<script>
    // Gestion du menu burger mobile - S'exécute après les autres scripts
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('tutorialSidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const sidebarClose = document.getElementById('sidebarClose');
        const toggleIcon = document.getElementById('sidebarToggleIcon');
        
        // Fonction pour vérifier si on est en mobile
        function isMobile() {
            return window.innerWidth <= 992;
        }
        
        // S'assurer que le sidebar est caché par défaut en mobile
        function initSidebar() {
            if (isMobile() && sidebar) {
                sidebar.classList.remove('active');
                if (sidebarOverlay) sidebarOverlay.classList.remove('active');
                if (sidebarClose) sidebarClose.style.display = 'none';
                if (sidebarToggle) sidebarToggle.classList.remove('active');
                
                // FORCER le sidebar à être caché avec styles inline
                sidebar.style.cssText = 'display: none !important; position: fixed !important; bottom: 0 !important; left: 0 !important; width: 85% !important; max-width: 400px !important; height: 70vh !important; max-height: 600px !important; border-radius: 20px 20px 0 0 !important; transform: translateY(100%) !important; z-index: 9999 !important; opacity: 0 !important; visibility: hidden !important; top: auto !important; align-self: auto !important;';
                
                // FORCER le bouton burger à être visible
                if (sidebarToggle) {
                    sidebarToggle.style.cssText = 'display: flex !important; visibility: visible !important; opacity: 1 !important; position: fixed !important; bottom: 20px !important; left: 20px !important; width: 60px !important; height: 60px !important; background: linear-gradient(135deg, #04AA6D, #038f5a) !important; border: none !important; border-radius: 50% !important; color: white !important; font-size: 24px !important; cursor: pointer !important; z-index: 10000 !important; box-shadow: 0 8px 25px rgba(4, 170, 109, 0.4) !important; align-items: center !important; justify-content: center !important;';
                }
            } else if (!isMobile() && sidebar) {
                // Sur desktop, s'assurer que le sidebar est visible normalement
                sidebar.classList.remove('active');
                sidebar.style.cssText = '';
                if (sidebarToggle) {
                    sidebarToggle.style.cssText = 'display: none !important;';
                }
            }
        }
        
        function openSidebar() {
            if (!sidebar || !sidebarOverlay) return;
            sidebar.classList.add('active');
            sidebar.style.cssText = 'display: block !important; position: fixed !important; bottom: 0 !important; left: 0 !important; width: 85% !important; max-width: 400px !important; height: 70vh !important; max-height: 600px !important; border-radius: 20px 20px 0 0 !important; transform: translateY(0) !important; z-index: 9999 !important; opacity: 1 !important; visibility: visible !important; box-shadow: 0 -10px 50px rgba(0, 0, 0, 0.3) !important; overflow-y: auto !important; overflow-x: hidden !important;';
            sidebarOverlay.classList.add('active');
            if (sidebarOverlay) sidebarOverlay.style.cssText = 'display: block !important; opacity: 1 !important; visibility: visible !important;';
            if (sidebarClose) sidebarClose.style.display = 'flex';
            if (sidebarToggle) sidebarToggle.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        function closeSidebar() {
            if (!sidebar || !sidebarOverlay) return;
            sidebar.classList.remove('active');
            if (isMobile()) {
                sidebar.style.cssText = 'display: none !important; position: fixed !important; bottom: 0 !important; left: 0 !important; width: 85% !important; max-width: 400px !important; height: 70vh !important; max-height: 600px !important; border-radius: 20px 20px 0 0 !important; transform: translateY(100%) !important; z-index: 9999 !important; opacity: 0 !important; visibility: hidden !important;';
            }
            sidebarOverlay.classList.remove('active');
            if (sidebarOverlay) sidebarOverlay.style.cssText = 'display: none !important; opacity: 0 !important; visibility: hidden !important;';
            if (sidebarClose) sidebarClose.style.display = 'none';
            if (sidebarToggle) sidebarToggle.classList.remove('active');
            document.body.style.overflow = '';
        }
        
        // Initialiser le sidebar au chargement (une seule fois)
        let sidebarInitialized = false;
        function initSidebarOnce() {
            if (sidebarInitialized) return;
            sidebarInitialized = true;
            initSidebar();
        }
        
        // Initialiser après un court délai pour laisser les autres scripts s'exécuter
        setTimeout(initSidebarOnce, 100);
        
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                if (sidebar && sidebar.classList.contains('active')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });
        }
        
        if (sidebarClose) {
            sidebarClose.addEventListener('click', function(e) {
                e.stopPropagation();
                closeSidebar();
            });
        }
        
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', closeSidebar);
        }
        
        // Fermer le sidebar quand on clique sur un lien
        if (sidebar) {
            const sidebarLinks = sidebar.querySelectorAll('a');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (isMobile()) {
                        setTimeout(closeSidebar, 300);
                    }
                });
            });
        }
        
        // Fermer avec la touche Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && sidebar && sidebar.classList.contains('active')) {
                closeSidebar();
            }
        });
        
        // Gérer le redimensionnement (avec debounce)
        let resizeTimer;
        let lastWidth = window.innerWidth;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                const currentWidth = window.innerWidth;
                const wasMobile = lastWidth <= 992;
                const isNowMobile = currentWidth <= 992;
                
                // Réinitialiser seulement si on change de mode
                if (wasMobile !== isNowMobile) {
                    sidebarInitialized = false;
                    initSidebarOnce();
                    lastWidth = currentWidth;
                }
            }, 200);
        });
    });
    
    // Fonction pour copier le code
    function copyCodeToClipboard(button, codeElement) {
        // Extraire le texte brut du code (sans les balises HTML)
        const codeText = codeElement.innerText || codeElement.textContent;
        
        // Copier dans le presse-papiers
        navigator.clipboard.writeText(codeText).then(function() {
            // Changer le bouton pour indiquer le succès
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check"></i>';
            button.classList.add('copied');
            button.setAttribute('title', 'Copié !');
            
            // Revenir à l'état initial après 2 secondes
            setTimeout(function() {
                button.innerHTML = originalHTML;
                button.classList.remove('copied');
                button.setAttribute('title', 'Copier le code');
            }, 2000);
        }).catch(function(err) {
            console.error('Erreur lors de la copie:', err);
            // Fallback pour les navigateurs plus anciens
            const textArea = document.createElement('textarea');
            textArea.value = codeText;
            textArea.style.position = 'fixed';
            textArea.style.opacity = '0';
            document.body.appendChild(textArea);
            textArea.select();
            try {
                document.execCommand('copy');
                const originalHTML = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check"></i>';
                button.classList.add('copied');
                button.setAttribute('title', 'Copié !');
                setTimeout(function() {
                    button.innerHTML = originalHTML;
                    button.classList.remove('copied');
                    button.setAttribute('title', 'Copier le code');
                }, 2000);
            } catch (err) {
                console.error('Erreur lors de la copie (fallback):', err);
                alert('Impossible de copier le code. Veuillez le sélectionner manuellement.');
            }
            document.body.removeChild(textArea);
        });
    }
    
    // Ajouter les boutons de copie à tous les blocs de code
    document.addEventListener('DOMContentLoaded', function() {
        const codeBoxes = document.querySelectorAll('.code-box');
        
        codeBoxes.forEach(function(codeBox) {
            // Vérifier si le bouton n'existe pas déjà
            if (codeBox.querySelector('.copy-code-btn')) {
                return;
            }
            
            const codeElement = codeBox.querySelector('code');
            if (!codeElement) {
                return;
            }
            
            // Créer le bouton de copie
            const copyButton = document.createElement('button');
            copyButton.className = 'copy-code-btn';
            copyButton.innerHTML = '<i class="fas fa-copy"></i>';
            copyButton.setAttribute('aria-label', 'Copier le code');
            copyButton.setAttribute('title', 'Copier le code');
            
            // Ajouter l'événement de clic
            copyButton.addEventListener('click', function() {
                copyCodeToClipboard(copyButton, codeElement);
            });
            
            // Ajouter le bouton au code-box
            codeBox.appendChild(copyButton);
        });
    });
    
    // Système de suivi automatique de progression
    @auth
    (function() {
        const formationSlug = 'html5';
        const sections = [
            'intro', 'editors', 'basic', 'elements', 'attributes', 'headings', 
            'paragraphs', 'styles', 'formatting', 'quotations', 'comments', 
            'colors', 'links', 'images', 'tables', 'lists', 'forms', 
            'media', 'canvas', 'svg', 'apis', 'semantic'
        ];
        
        let completedSections = new Set();
        let progressData = null;
        
        // Charger la progression actuelle
        function loadProgress() {
            fetch(`/api/formation-progress/${formationSlug}`)
                .then(response => response.json())
                .then(data => {
                    progressData = data;
                    if (data.completed_sections) {
                        completedSections = new Set(data.completed_sections);
                    }
                    updateProgressDisplay();
                })
                .catch(err => console.error('Erreur chargement progression:', err));
        }
        
        // Mettre à jour l'affichage de la progression
        function updateProgressDisplay() {
            const progressCard = document.querySelector('[style*="Votre Progression"]')?.closest('[style*="max-width: 1200px"]');
            if (progressCard && progressData) {
                const progressBar = progressCard.querySelector('[style*="width:"]');
                const progressText = progressCard.querySelector('strong');
                if (progressBar) {
                    progressBar.style.width = progressData.progress_percentage + '%';
                }
                if (progressText) {
                    progressText.textContent = progressData.progress_percentage + '%';
                }
            }
        }
        
        // Marquer une section comme complétée
        function markSectionAsCompleted(sectionId) {
            if (completedSections.has(sectionId)) {
                return; // Déjà complétée
            }
            
            completedSections.add(sectionId);
            
            // Envoyer la mise à jour au serveur
            fetch('/api/formation-progress/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    formation_slug: formationSlug,
                    section_id: sectionId,
                    completed: true
                })
            })
            .then(response => response.json())
            .then(data => {
                progressData = data;
                updateProgressDisplay();
                
                // Afficher une notification si la formation est complétée
                if (data.progress_percentage === 100) {
                    showCompletionNotification();
                }
            })
            .catch(err => console.error('Erreur mise à jour progression:', err));
        }
        
        // Afficher une notification de complétion
        function showCompletionNotification() {
            const notification = document.createElement('div');
            notification.style.cssText = 'position: fixed; top: 20px; right: 20px; background: linear-gradient(135deg, #04AA6D, #06b6d4); color: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 8px 25px rgba(4, 170, 109, 0.4); z-index: 10000; max-width: 350px; animation: slideInRight 0.5s ease;';
            notification.innerHTML = `
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 50px; height: 50px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div>
                        <h4 style="margin: 0 0 0.5rem 0; font-size: 1.1rem;">🎉 Formation Complétée !</h4>
                        <p style="margin: 0; font-size: 0.9rem; opacity: 0.9;">Félicitations ! Vous avez terminé la formation HTML5.</p>
                    </div>
                </div>
            `;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'slideOutRight 0.5s ease';
                setTimeout(() => notification.remove(), 500);
            }, 5000);
        }
        
        // Utiliser IntersectionObserver pour détecter quand une section est vue
        const observerOptions = {
            root: null,
            rootMargin: '-10% 0px -10% 0px',
            threshold: 0.3
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const sectionId = entry.target.id;
                    if (sectionId && sections.includes(sectionId) && !completedSections.has(sectionId)) {
                        // Attendre 5 secondes avant de marquer comme complétée (temps de lecture minimum)
                        setTimeout(() => {
                            // Vérifier que la section est toujours visible
                            const rect = entry.target.getBoundingClientRect();
                            if (rect.top < window.innerHeight && rect.bottom > 0) {
                                markSectionAsCompleted(sectionId);
                                observer.unobserve(entry.target);
                            }
                        }, 5000);
                    }
                }
            });
        }, observerOptions);
        
        // Observer toutes les sections après le chargement
        document.addEventListener('DOMContentLoaded', function() {
            sections.forEach(sectionId => {
                const section = document.getElementById(sectionId);
                if (section) {
                    observer.observe(section);
                }
            });
            
            // Charger la progression au chargement de la page
            loadProgress();
        });
        
        // Mettre à jour toutes les 30 secondes pour synchroniser
        setInterval(loadProgress, 30000);
    })();
    @endauth
    
</script>
@endsection
