@extends('layouts.app')

@php
    if (!app()->getLocale()) {
        app()->setLocale(session('language', 'fr'));
    }
@endphp

@section('title', trans('app.docs.title'))
@section('meta_description', trans('app.docs.meta_description'))
@section('meta_keywords', trans('app.docs.meta_keywords'))

@section('styles')
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<style>
    body {
        overflow-x: hidden;
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.98) 0%, rgba(30, 41, 59, 0.98) 100%);
    }
    
    body.light-mode {
        background: linear-gradient(135deg, rgba(248, 250, 252, 0.98) 0%, rgba(241, 245, 249, 0.98) 100%);
    }
    
    /* S'assurer que le conteneur parent permet le sticky */
    main, .main-content, #app, .content-wrapper {
        overflow: visible !important;
        position: relative !important;
    }
    
    /* Forcer le sticky même si le parent a overflow */
    .docs-container-wrapper {
        overflow: visible !important;
        position: relative !important;
        width: 100% !important;
    }
    
    /* Forcer sur tous les parents possibles */
    html, body {
        overflow-x: hidden !important;
    }
    
    body {
        overflow-y: auto !important;
    }
    
    /* Hero Section */
    .docs-hero {
        position: relative;
        padding: 6rem 2rem 4rem;
        text-align: center;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(20, 184, 166, 0.1) 100%);
        border-bottom: 2px solid rgba(6, 182, 212, 0.2);
        overflow: hidden;
    }
    
    .docs-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(6, 182, 212, 0.15) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }
    
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    .docs-hero-content {
        position: relative;
        z-index: 1;
        max-width: 800px;
        margin: 0 auto;
    }
    
    .docs-hero-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto 2rem;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
        border: 3px solid rgba(6, 182, 212, 0.4);
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: #06b6d4;
        animation: float 3s ease-in-out infinite;
        box-shadow: 0 10px 40px rgba(6, 182, 212, 0.3);
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    
    .docs-hero-title {
        font-size: 3.5rem;
        font-weight: 900;
        background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 50%, #06b6d4 100%);
        background-size: 200% auto;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: shimmer 3s linear infinite;
        margin-bottom: 1rem;
        line-height: 1.2;
    }
    
    @keyframes shimmer {
        to { background-position: 200% center; }
    }
    
    .docs-hero-subtitle {
        font-size: 1.25rem;
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 2rem;
        line-height: 1.6;
    }
    
    body.light-mode .docs-hero-subtitle {
        color: #64748b;
    }
    
    .docs-hero-badges {
        display: flex;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
        margin-top: 2rem;
    }
    
    .docs-hero-badge {
        padding: 0.5rem 1.25rem;
        background: rgba(6, 182, 212, 0.15);
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 50px;
        color: #06b6d4;
        font-weight: 600;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .docs-hero-badge:hover {
        background: rgba(6, 182, 212, 0.25);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(6, 182, 212, 0.3);
    }
    
    /* Wrapper pour permettre le sticky */
    .docs-container-wrapper {
        position: relative;
        overflow: visible;
        width: 100%;
    }
    
    /* Container - Utilise flex comme dans les formations */
    .docs-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 3rem 2rem;
        display: flex;
        gap: 3rem;
        align-items: flex-start;
        position: relative;
        overflow: visible;
        width: 100%;
    }
    
    /* Sidebar - Structure similaire aux formations */
    .docs-sidebar {
        width: 320px;
        flex-shrink: 0;
        position: -webkit-sticky;
        position: sticky;
        top: 80px;
        align-self: flex-start;
        height: calc(100vh - 100px);
        max-height: calc(100vh - 100px);
        overflow-y: auto;
        overflow-x: hidden;
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.95), rgba(30, 41, 59, 0.95));
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 24px;
        padding: 2rem;
        backdrop-filter: blur(30px) saturate(180%);
        -webkit-backdrop-filter: blur(30px) saturate(180%);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(6, 182, 212, 0.1) inset;
        z-index: 100;
    }
    
    body.light-mode .docs-sidebar {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.98), rgba(248, 250, 252, 0.98));
        border-color: rgba(6, 182, 212, 0.3);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1), 0 0 0 1px rgba(6, 182, 212, 0.1) inset;
    }
    
    /* Bouton toggle sidebar (mobile) */
    .docs-sidebar-toggle-btn {
        display: none !important;
        position: fixed;
        bottom: 20px;
        left: 20px;
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border: none;
        border-radius: 50%;
        color: white;
        font-size: 24px;
        cursor: pointer;
        z-index: 10000;
        box-shadow: 0 8px 25px rgba(6, 182, 212, 0.4);
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    .docs-sidebar-toggle-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 12px 35px rgba(6, 182, 212, 0.6);
    }
    
    .docs-sidebar-toggle-btn.active {
        background: linear-gradient(135deg, #14b8a6, #06b6d4);
        transform: rotate(90deg);
    }
    
    /* Overlay pour mobile */
    .docs-sidebar-overlay {
        display: none !important;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(5px);
        z-index: 9998;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .docs-sidebar-overlay.active {
        display: block !important;
        opacity: 1;
    }
    
    /* Bouton de fermeture dans le sidebar */
    .docs-sidebar-close-btn {
        display: none;
        align-items: center;
        justify-content: center;
        background: rgba(6, 182, 212, 0.1) !important;
        border: 2px solid rgba(6, 182, 212, 0.3) !important;
        transition: all 0.3s ease;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        color: #06b6d4;
        font-size: 20px;
        cursor: pointer;
    }
    
    .docs-sidebar-close-btn:hover {
        background: rgba(6, 182, 212, 0.2) !important;
        border-color: rgba(6, 182, 212, 0.5) !important;
        transform: rotate(90deg) scale(1.1);
    }
    
    body.light-mode .docs-sidebar {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.98), rgba(248, 250, 252, 0.98));
        border-color: rgba(6, 182, 212, 0.3);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1), 0 0 0 1px rgba(6, 182, 212, 0.1) inset;
    }
    
    .docs-sidebar::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #06b6d4, #14b8a6, #06b6d4);
        background-size: 200% 100%;
        animation: shimmer-border 3s linear infinite;
        border-radius: 24px 24px 0 0;
    }
    
    @keyframes shimmer-border {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    
    .docs-sidebar::-webkit-scrollbar {
        width: 10px;
    }
    
    .docs-sidebar::-webkit-scrollbar-track {
        background: rgba(6, 182, 212, 0.1);
        border-radius: 10px;
        margin: 1rem 0;
    }
    
    .docs-sidebar::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, rgba(6, 182, 212, 0.6), rgba(20, 184, 166, 0.6));
        border-radius: 10px;
        border: 2px solid transparent;
        background-clip: padding-box;
        transition: all 0.3s ease;
    }
    
    .docs-sidebar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, rgba(6, 182, 212, 0.8), rgba(20, 184, 166, 0.8));
        background-clip: padding-box;
    }
    
    .docs-sidebar-title {
        font-size: 1.5rem;
        font-weight: 900;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid rgba(6, 182, 212, 0.3);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .docs-sidebar-title i {
        font-size: 1.75rem;
    }
    
    .docs-nav {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .docs-nav-item {
        margin-bottom: 0.5rem;
    }
    
    .docs-nav-link {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.25rem;
        color: rgba(255, 255, 255, 0.85);
        text-decoration: none;
        border-radius: 14px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 0.95rem;
        font-weight: 500;
        position: relative;
        overflow: hidden;
        border: 1px solid transparent;
    }
    
    body.light-mode .docs-nav-link {
        color: #475569;
    }
    
    .docs-nav-link::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(180deg, #06b6d4, #14b8a6);
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }
    
    .docs-nav-link:hover {
        background: rgba(6, 182, 212, 0.12);
        color: #06b6d4;
        transform: translateX(8px);
        border-color: rgba(6, 182, 212, 0.3);
        box-shadow: 0 4px 15px rgba(6, 182, 212, 0.2);
    }
    
    .docs-nav-link:hover::before {
        transform: scaleY(1);
    }
    
    .docs-nav-link.active {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.15));
        color: #06b6d4;
        border-color: rgba(6, 182, 212, 0.4);
        font-weight: 700;
        box-shadow: 0 4px 20px rgba(6, 182, 212, 0.3);
    }
    
    .docs-nav-link.active::before {
        transform: scaleY(1);
    }
    
    .docs-nav-link i {
        width: 24px;
        text-align: center;
        font-size: 1.1rem;
        transition: transform 0.3s ease;
    }
    
    .docs-nav-link:hover i {
        transform: scale(1.2);
    }
    
    /* Content */
    .docs-content {
        flex: 1;
        min-width: 0;
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.7), rgba(30, 41, 59, 0.7));
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 24px;
        padding: 4rem;
        backdrop-filter: blur(30px) saturate(180%);
        -webkit-backdrop-filter: blur(30px) saturate(180%);
        box-shadow: 0 25px 70px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(6, 182, 212, 0.1) inset;
        position: relative;
        overflow: visible;
    }
    
    .docs-content::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #06b6d4, #14b8a6, #06b6d4);
        background-size: 200% 100%;
        animation: shimmer-border 3s linear infinite;
    }
    
    body.light-mode .docs-content {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.98), rgba(248, 250, 252, 0.98));
        border-color: rgba(6, 182, 212, 0.3);
        box-shadow: 0 25px 70px rgba(0, 0, 0, 0.1), 0 0 0 1px rgba(6, 182, 212, 0.1) inset;
    }
    
    .docs-section {
        margin-bottom: 5rem;
        scroll-margin-top: 120px;
        position: relative;
    }
    
    .docs-section::after {
        content: '';
        position: absolute;
        bottom: -2rem;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(6, 182, 212, 0.3), transparent);
    }
    
    .docs-section:last-child::after {
        display: none;
    }
    
    .docs-section-title {
        font-size: 2.5rem;
        font-weight: 900;
        background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 50%, #06b6d4 100%);
        background-size: 200% auto;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: shimmer 3s linear infinite;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 3px solid rgba(6, 182, 212, 0.3);
        display: flex;
        align-items: center;
        gap: 1.25rem;
        position: relative;
    }
    
    .docs-section-title::after {
        content: '';
        position: absolute;
        bottom: -3px;
        left: 0;
        width: 100px;
        height: 3px;
        background: linear-gradient(90deg, #06b6d4, #14b8a6);
        border-radius: 3px;
    }
    
    .docs-section-title i {
        font-size: 2rem;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
        padding: 1rem;
        border-radius: 16px;
        border: 2px solid rgba(6, 182, 212, 0.3);
    }
    
    .docs-subsection {
        margin-bottom: 3rem;
        padding: 2rem;
        background: rgba(6, 182, 212, 0.05);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 20px;
        transition: all 0.4s ease;
        position: relative;
        overflow: hidden;
    }
    
    .docs-subsection::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, transparent, rgba(6, 182, 212, 0.5), transparent);
        transform: scaleX(0);
        transition: transform 0.4s ease;
    }
    
    .docs-subsection:hover {
        background: rgba(6, 182, 212, 0.08);
        border-color: rgba(6, 182, 212, 0.4);
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(6, 182, 212, 0.2);
    }
    
    .docs-subsection:hover::before {
        transform: scaleX(1);
    }
    
    body.light-mode .docs-subsection {
        background: rgba(6, 182, 212, 0.03);
        border-color: rgba(6, 182, 212, 0.2);
    }
    
    body.light-mode .docs-subsection:hover {
        background: rgba(6, 182, 212, 0.06);
    }
    
    .docs-subsection-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: #06b6d4;
        margin-bottom: 1.25rem;
        margin-top: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .docs-subsection-title::before {
        content: '';
        width: 6px;
        height: 6px;
        background: #06b6d4;
        border-radius: 50%;
        box-shadow: 0 0 10px rgba(6, 182, 212, 0.8);
    }
    
    .docs-text {
        color: rgba(255, 255, 255, 0.9);
        line-height: 1.9;
        margin-bottom: 1.5rem;
        font-size: 1.05rem;
    }
    
    body.light-mode .docs-text {
        color: #334155;
    }
    
    .docs-list {
        list-style: none;
        padding: 0;
        margin: 1.5rem 0;
    }
    
    .docs-list-item {
        padding: 1.25rem 1.5rem;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.12), rgba(20, 184, 166, 0.08));
        border-left: 4px solid #06b6d4;
        border-radius: 12px;
        color: rgba(255, 255, 255, 0.95);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .docs-list-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 0;
        background: linear-gradient(90deg, rgba(6, 182, 212, 0.2), transparent);
        transition: width 0.3s ease;
    }
    
    .docs-list-item:hover {
        transform: translateX(8px);
        box-shadow: 0 6px 20px rgba(6, 182, 212, 0.25);
        border-left-color: #14b8a6;
    }
    
    .docs-list-item:hover::before {
        width: 100%;
    }
    
    body.light-mode .docs-list-item {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.06), rgba(20, 184, 166, 0.04));
        color: #334155;
    }
    
    .docs-card {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.12), rgba(20, 184, 166, 0.08));
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 20px;
        padding: 2rem;
        margin: 2rem 0;
        position: relative;
        overflow: hidden;
        transition: all 0.4s ease;
    }
    
    .docs-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(6, 182, 212, 0.1) 0%, transparent 70%);
        animation: rotate 15s linear infinite;
    }
    
    .docs-card:hover {
        border-color: rgba(6, 182, 212, 0.5);
        box-shadow: 0 15px 40px rgba(6, 182, 212, 0.3);
        transform: translateY(-5px);
    }
    
    body.light-mode .docs-card {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.06), rgba(20, 184, 166, 0.04));
        border-color: rgba(6, 182, 212, 0.3);
    }
    
    .docs-card-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #06b6d4;
        margin-bottom: 1rem;
        position: relative;
        z-index: 1;
    }
    
    .docs-card-content {
        position: relative;
        z-index: 1;
    }
    
    .docs-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1.25rem;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.15));
        border: 2px solid rgba(6, 182, 212, 0.4);
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 700;
        color: #06b6d4;
        margin-right: 0.75rem;
        margin-bottom: 0.75rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .docs-badge::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
    }
    
    .docs-badge:hover {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.3), rgba(20, 184, 166, 0.25));
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 8px 20px rgba(6, 182, 212, 0.4);
    }
    
    .docs-badge:hover::before {
        left: 100%;
    }
    
    .docs-badges-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
        margin-top: 1.5rem;
    }
    
    .docs-step-list {
        list-style: none;
        padding: 0;
        margin: 1.5rem 0;
        counter-reset: step-counter;
    }
    
    .docs-step-item {
        counter-increment: step-counter;
        padding: 1.5rem 1.5rem 1.5rem 4rem;
        margin-bottom: 1.5rem;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.08));
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 16px;
        color: rgba(255, 255, 255, 0.95);
        position: relative;
        transition: all 0.4s ease;
    }
    
    .docs-step-item::before {
        content: counter(step-counter);
        position: absolute;
        left: 1.5rem;
        top: 1.5rem;
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.1rem;
        color: white;
        box-shadow: 0 4px 15px rgba(6, 182, 212, 0.4);
    }
    
    .docs-step-item:hover {
        transform: translateX(10px);
        border-color: rgba(6, 182, 212, 0.5);
        box-shadow: 0 8px 25px rgba(6, 182, 212, 0.3);
    }
    
    body.light-mode .docs-step-item {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.06), rgba(20, 184, 166, 0.04));
        color: #334155;
    }
    
    /* Mobile - Sidebar comme formations */
    @media (max-width: 992px) {
        .docs-sidebar,
        .docs-sidebar#docsSidebar,
        aside.docs-sidebar {
            position: fixed !important;
            top: auto !important;
            align-self: auto !important;
            flex-shrink: 0 !important;
            width: 85% !important;
            max-width: 400px !important;
            display: none !important;
            bottom: 0 !important;
            left: 0 !important;
            height: 70vh !important;
            max-height: 600px !important;
            border-radius: 20px 20px 0 0 !important;
            transform: translateY(100%) !important;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.4s ease !important;
            z-index: 9999 !important;
            box-shadow: 0 -10px 50px rgba(0, 0, 0, 0.3) !important;
            overflow-y: auto !important;
            opacity: 0 !important;
            visibility: hidden !important;
        }
        
        .docs-sidebar.active,
        .docs-sidebar#docsSidebar.active,
        aside.docs-sidebar.active {
            display: block !important;
            transform: translateY(0) !important;
            opacity: 1 !important;
            visibility: visible !important;
        }
        
        .docs-sidebar-toggle-btn,
        button.docs-sidebar-toggle-btn,
        #docsSidebarToggle {
            display: flex !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
        
        .docs-sidebar-close-btn {
            display: flex !important;
        }
    }
    
    /* Responsive - Tablette */
    @media (max-width: 968px) {
        .docs-hero {
            padding: 4rem 1.5rem 3rem;
        }
        
        .docs-hero-icon {
            width: 80px;
            height: 80px;
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
        }
        
        .docs-hero-title {
            font-size: 2.5rem;
        }
        
        .docs-hero-subtitle {
            font-size: 1.1rem;
        }
        
        .docs-hero-badges {
            gap: 0.75rem;
        }
        
        .docs-hero-badge {
            font-size: 0.85rem;
            padding: 0.4rem 1rem;
        }
        
        .docs-container {
            flex-direction: column;
            padding: 2rem 1rem;
            gap: 2rem;
        }
        
        .docs-sidebar {
            position: relative;
            top: 0;
            width: 100%;
            height: auto;
            max-height: 60vh;
            margin-bottom: 2rem;
        }
        
        .docs-content {
            padding: 2rem 1.5rem;
        }
        
        .docs-section-title {
            font-size: 2rem;
        }
        
        .docs-subsection-title {
            font-size: 1.6rem;
        }
    }
    
    /* Responsive - Mobile */
    @media (max-width: 640px) {
        .docs-hero {
            padding: 3rem 1rem 2rem;
        }
        
        .docs-hero-icon {
            width: 70px;
            height: 70px;
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        
        .docs-hero-title {
            font-size: 2rem;
            line-height: 1.3;
        }
        
        .docs-hero-subtitle {
            font-size: 1rem;
            line-height: 1.5;
        }
        
        .docs-hero-badges {
            flex-direction: column;
            align-items: stretch;
            gap: 0.5rem;
        }
        
        .docs-hero-badge {
            font-size: 0.8rem;
            padding: 0.5rem 1rem;
            justify-content: center;
        }
        
        .docs-container {
            padding: 1.5rem 0.75rem;
            gap: 1.5rem;
        }
        
        .docs-sidebar {
            max-height: 50vh;
            padding: 1.5rem;
            border-radius: 16px;
        }
        
        .docs-sidebar-title {
            font-size: 1.25rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
        }
        
        .docs-sidebar-title i {
            font-size: 1.5rem;
        }
        
        .docs-nav-link {
            padding: 0.875rem 1rem;
            font-size: 0.9rem;
            gap: 0.75rem;
        }
        
        .docs-nav-link i {
            width: 20px;
            font-size: 1rem;
        }
        
        .docs-content {
            padding: 1.5rem 1rem;
            border-radius: 16px;
        }
        
        .docs-section {
            margin-bottom: 3rem;
            scroll-margin-top: 80px;
        }
        
        .docs-section-title {
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
        }
        
        .docs-section-title i {
            font-size: 1.5rem;
        }
        
        .docs-subsection {
            margin-bottom: 2rem;
        }
        
        .docs-subsection-title {
            font-size: 1.4rem;
            margin-bottom: 1rem;
            margin-top: 2rem;
        }
        
        .docs-text {
            font-size: 1rem;
            line-height: 1.7;
            margin-bottom: 1.25rem;
        }
        
        .docs-list-item {
            padding: 0.875rem 1rem;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        
        .docs-code-block {
            padding: 1rem 1.25rem;
            font-size: 0.85rem;
            border-radius: 12px;
            margin: 1.5rem 0;
        }
        
        .docs-card {
            padding: 1.5rem;
            border-radius: 16px;
            margin: 1.5rem 0;
        }
        
        .docs-card-title {
            font-size: 1.2rem;
            margin-bottom: 0.875rem;
        }
        
        .docs-badge {
            font-size: 0.8rem;
            padding: 0.4rem 0.875rem;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }
        
        .docs-badges-grid {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }
        
        .docs-step-item {
            padding: 1.25rem 1.25rem 1.25rem 3.5rem;
            margin-bottom: 1rem;
            border-radius: 12px;
        }
        
        .docs-step-item::before {
            width: 35px;
            height: 35px;
            left: 1rem;
            top: 1.25rem;
            font-size: 1rem;
        }
    }
    
    /* Responsive - Très petit mobile */
    @media (max-width: 480px) {
        .docs-hero {
            padding: 2.5rem 0.75rem 1.5rem;
        }
        
        .docs-hero-icon {
            width: 60px;
            height: 60px;
            font-size: 1.75rem;
        }
        
        .docs-hero-title {
            font-size: 1.75rem;
        }
        
        .docs-hero-subtitle {
            font-size: 0.95rem;
        }
        
        .docs-container {
            padding: 1rem 0.5rem;
        }
        
        .docs-sidebar {
            padding: 1.25rem;
        }
        
        .docs-content {
            padding: 1.25rem 0.75rem;
        }
        
        .docs-section-title {
            font-size: 1.5rem;
        }
        
        .docs-subsection-title {
            font-size: 1.25rem;
        }
        
        .docs-text {
            font-size: 0.95rem;
        }
        
        .docs-nav-link {
            padding: 0.75rem;
            font-size: 0.85rem;
        }
        
        .docs-card {
            padding: 1.25rem;
        }
        
        .docs-step-item {
            padding: 1rem 1rem 1rem 3rem;
        }
        
        .docs-step-item::before {
            width: 30px;
            height: 30px;
            font-size: 0.9rem;
        }
    }
    
    /* Améliorations générales mobile */
    @media (max-width: 768px) {
        /* Empêcher le débordement horizontal */
        .docs-container-wrapper,
        .docs-container,
        .docs-content {
            max-width: 100%;
            overflow-x: hidden;
        }
        
        /* Améliorer la lisibilité */
        .docs-text {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        /* Code blocks scrollables */
        .docs-code-block {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        /* Cards responsive */
        .docs-card {
            margin-left: 0;
            margin-right: 0;
        }
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="docs-hero">
    <div class="docs-hero-content">
        <div class="docs-hero-icon">
            <i class="fas fa-book-open"></i>
        </div>
        <h1 class="docs-hero-title" data-aos="fade-up">{{ trans('app.docs.hero.title') }}</h1>
        <p class="docs-hero-subtitle" data-aos="fade-up" data-aos-delay="100">
            {{ trans('app.docs.hero.subtitle') }}
        </p>
        <div class="docs-hero-badges" data-aos="fade-up" data-aos-delay="200">
            <div class="docs-hero-badge">
                <i class="fas fa-graduation-cap"></i>
                <span>{{ trans('app.docs.hero.badge_1') }}</span>
            </div>
            <div class="docs-hero-badge">
                <i class="fas fa-code"></i>
                <span>{{ trans('app.docs.hero.badge_2') }}</span>
            </div>
            <div class="docs-hero-badge">
                <i class="fas fa-chart-line"></i>
                <span>{{ trans('app.docs.hero.badge_3') }}</span>
            </div>
        </div>
    </div>
</section>

<div class="docs-container-wrapper">
<div class="docs-container">
    <!-- Sidebar Toggle Button (Mobile) -->
    <button class="docs-sidebar-toggle-btn" id="docsSidebarToggle" aria-label="{{ trans('app.docs.sidebar.menu_open') }}">
        <i class="fas fa-bars" id="docsSidebarToggleIcon"></i>
    </button>
    
    <!-- Sidebar Overlay (Mobile) -->
    <div class="docs-sidebar-overlay" id="docsSidebarOverlay"></div>
    
    <!-- Sidebar Navigation -->
    <aside class="docs-sidebar" id="docsSidebar">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">
            <h2 class="docs-sidebar-title" style="margin: 0;">
                <i class="fas fa-book"></i>
                {{ trans('app.docs.sidebar.title') }}
            </h2>
            <button class="docs-sidebar-close-btn" id="docsSidebarClose" aria-label="{{ trans('app.docs.sidebar.menu_close') }}">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <nav>
            <ul class="docs-nav">
                <li class="docs-nav-item">
                    <a href="#introduction" class="docs-nav-link active">
                        <i class="fas fa-home"></i>
                        <span>{{ trans('app.docs.sidebar.introduction') }}</span>
                    </a>
                </li>
                <li class="docs-nav-item">
                    <a href="#inscription" class="docs-nav-link">
                        <i class="fas fa-user-plus"></i>
                        <span>{{ trans('app.docs.sidebar.inscription') }}</span>
                    </a>
                </li>
                <li class="docs-nav-item">
                    <a href="#formations" class="docs-nav-link">
                        <i class="fas fa-graduation-cap"></i>
                        <span>{{ trans('app.docs.sidebar.formations') }}</span>
                    </a>
                </li>
                <li class="docs-nav-item">
                    <a href="#exercices" class="docs-nav-link">
                        <i class="fas fa-laptop-code"></i>
                        <span>{{ trans('app.docs.sidebar.exercices') }}</span>
                    </a>
                </li>
                <li class="docs-nav-item">
                    <a href="#quiz" class="docs-nav-link">
                        <i class="fas fa-question-circle"></i>
                        <span>{{ trans('app.docs.sidebar.quiz') }}</span>
                    </a>
                </li>
                <li class="docs-nav-item">
                    <a href="#dashboard" class="docs-nav-link">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>{{ trans('app.docs.sidebar.dashboard') }}</span>
                    </a>
                </li>
                <li class="docs-nav-item">
                    <a href="#emplois" class="docs-nav-link">
                        <i class="fas fa-briefcase"></i>
                        <span>{{ trans('app.docs.sidebar.emplois') }}</span>
                    </a>
                </li>
                <li class="docs-nav-item">
                    <a href="#favoris" class="docs-nav-link">
                        <i class="fas fa-heart"></i>
                        <span>{{ trans('app.docs.sidebar.favoris') }}</span>
                    </a>
                </li>
                <li class="docs-nav-item">
                    <a href="#affiliation" class="docs-nav-link">
                        <i class="fas fa-users"></i>
                        <span>{{ trans('app.docs.sidebar.affiliation') }}</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>
    
    <!-- Main Content -->
    <main class="docs-content">
        <!-- Introduction -->
        <section id="introduction" class="docs-section" data-aos="fade-up">
            <h1 class="docs-section-title">
                <i class="fas fa-home"></i>
                <span>{{ trans('app.docs.introduction.title') }}</span>
            </h1>
            <div class="docs-subsection">
                <p class="docs-text">
                    {!! trans('app.docs.introduction.welcome') !!}
                </p>
                <div class="docs-card">
                    <h3 class="docs-card-title">{{ trans('app.docs.introduction.what_you_can_do') }}</h3>
                    <div class="docs-card-content">
                        <ul class="docs-list">
                            <li class="docs-list-item">{{ trans('app.docs.introduction.feature_1') }}</li>
                            <li class="docs-list-item">{{ trans('app.docs.introduction.feature_2') }}</li>
                            <li class="docs-list-item">{{ trans('app.docs.introduction.feature_3') }}</li>
                            <li class="docs-list-item">{{ trans('app.docs.introduction.feature_4') }}</li>
                            <li class="docs-list-item">{{ trans('app.docs.introduction.feature_5') }}</li>
                            <li class="docs-list-item">{{ trans('app.docs.introduction.feature_6') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Inscription -->
        <section id="inscription" class="docs-section" data-aos="fade-up">
            <h2 class="docs-section-title">
                <i class="fas fa-user-plus"></i>
                <span>{{ trans('app.docs.inscription.title') }}</span>
            </h2>
            <div class="docs-subsection">
                <h3 class="docs-subsection-title">{{ trans('app.docs.inscription.create_account') }}</h3>
                <p class="docs-text">
                    {{ trans('app.docs.inscription.create_account_text') }}
                </p>
                <ol class="docs-step-list">
                    <li class="docs-step-item">{!! trans('app.docs.inscription.step_1') !!}</li>
                    <li class="docs-step-item">{!! trans('app.docs.inscription.step_2') !!}</li>
                    <li class="docs-step-item">{!! trans('app.docs.inscription.step_3') !!}</li>
                    <li class="docs-step-item">{!! trans('app.docs.inscription.step_4') !!}</li>
                </ol>
                <div class="docs-card">
                    <h3 class="docs-card-title">{{ trans('app.docs.inscription.tip_title') }}</h3>
                    <p class="docs-text" style="margin: 0;">
                        {{ trans('app.docs.inscription.tip_text') }}
                    </p>
                </div>
            </div>
            <div class="docs-subsection">
                <h3 class="docs-subsection-title">{{ trans('app.docs.inscription.login') }}</h3>
                <p class="docs-text">
                    {{ trans('app.docs.inscription.login_text') }}
                </p>
                <ol class="docs-step-list">
                    <li class="docs-step-item">{!! trans('app.docs.inscription.login_step_1') !!}</li>
                    <li class="docs-step-item">{!! trans('app.docs.inscription.login_step_2') !!}</li>
                    <li class="docs-step-item">{!! trans('app.docs.inscription.login_step_3') !!}</li>
                    <li class="docs-step-item">{!! trans('app.docs.inscription.login_step_4') !!}</li>
                </ol>
            </div>
        </section>
        
        <!-- Formations -->
        <section id="formations" class="docs-section" data-aos="fade-up">
            <h2 class="docs-section-title">
                <i class="fas fa-graduation-cap"></i>
                <span>{{ trans('app.docs.formations.title') }}</span>
            </h2>
            <div class="docs-subsection">
                <h3 class="docs-subsection-title">{{ trans('app.docs.formations.access') }}</h3>
                <p class="docs-text">
                    {{ trans('app.docs.formations.access_text') }}
                </p>
                <ol class="docs-step-list">
                    <li class="docs-step-item">{!! trans('app.docs.formations.access_step_1') !!}</li>
                    <li class="docs-step-item">{!! trans('app.docs.formations.access_step_2') !!}</li>
                    <li class="docs-step-item">{!! trans('app.docs.formations.access_step_3') !!}</li>
                </ol>
            </div>
            <div class="docs-subsection">
                <h3 class="docs-subsection-title">{{ trans('app.docs.formations.navigate') }}</h3>
                <p class="docs-text">
                    {{ trans('app.docs.formations.navigate_text') }}
                </p>
                <ul class="docs-list">
                    <li class="docs-list-item">{!! trans('app.docs.formations.navigate_item_1') !!}</li>
                    <li class="docs-list-item">{!! trans('app.docs.formations.navigate_item_2') !!}</li>
                    <li class="docs-list-item">{!! trans('app.docs.formations.navigate_item_3') !!}</li>
                </ul>
            </div>
            <div class="docs-subsection">
                <h3 class="docs-subsection-title">{{ trans('app.docs.formations.progress') }}</h3>
                <p class="docs-text">
                    {{ trans('app.docs.formations.progress_text') }}
                </p>
                <ul class="docs-list">
                    <li class="docs-list-item">{{ trans('app.docs.formations.progress_item_1') }}</li>
                    <li class="docs-list-item">{{ trans('app.docs.formations.progress_item_2') }}</li>
                    <li class="docs-list-item">{{ trans('app.docs.formations.progress_item_3') }}</li>
                </ul>
            </div>
            <div class="docs-subsection">
                <h3 class="docs-subsection-title">{{ trans('app.docs.formations.available') }}</h3>
                <p class="docs-text">
                    {{ trans('app.docs.formations.available_text') }}
                </p>
                <div class="docs-badges-grid">
                    <div class="docs-badge">HTML5</div>
                    <div class="docs-badge">CSS3</div>
                    <div class="docs-badge">JavaScript</div>
                    <div class="docs-badge">PHP</div>
                    <div class="docs-badge">Python</div>
                    <div class="docs-badge">Java</div>
                    <div class="docs-badge">SQL</div>
                    <div class="docs-badge">C / C++</div>
                    <div class="docs-badge">C#</div>
                    <div class="docs-badge">Dart</div>
                    <div class="docs-badge">Go</div>
                    <div class="docs-badge">Rust</div>
                    <div class="docs-badge">Ruby</div>
                    <div class="docs-badge">Git</div>
                    <div class="docs-badge">Bootstrap</div>
                    <div class="docs-badge">WordPress</div>
                    <div class="docs-badge">Intelligence Artificielle</div>
                    <div class="docs-badge">Cybersécurité</div>
                    <div class="docs-badge">Data Science</div>
                    <div class="docs-badge">Big Data</div>
                </div>
            </div>
        </section>
        
        <!-- Exercices -->
        <section id="exercices" class="docs-section" data-aos="fade-up">
            <h2 class="docs-section-title">
                <i class="fas fa-laptop-code"></i>
                <span>{{ trans('app.docs.exercices.title') }}</span>
            </h2>
            <div class="docs-subsection">
                <h3 class="docs-subsection-title">{{ trans('app.docs.exercices.access') }}</h3>
                <ol class="docs-step-list">
                    <li class="docs-step-item">{!! trans('app.docs.exercices.access_step_1') !!}</li>
                    <li class="docs-step-item">{!! trans('app.docs.exercices.access_step_2') !!}</li>
                    <li class="docs-step-item">{!! trans('app.docs.exercices.access_step_3') !!}</li>
                </ol>
            </div>
            <div class="docs-subsection">
                <h3 class="docs-subsection-title">{{ trans('app.docs.exercices.complete') }}</h3>
                <p class="docs-text">
                    {{ trans('app.docs.exercices.complete_text') }}
                </p>
                <ul class="docs-list">
                    <li class="docs-list-item">{!! trans('app.docs.exercices.complete_item_1') !!}</li>
                    <li class="docs-list-item">{!! trans('app.docs.exercices.complete_item_2') !!}</li>
                    <li class="docs-list-item">{!! trans('app.docs.exercices.complete_item_3') !!}</li>
                    <li class="docs-list-item">{!! trans('app.docs.exercices.complete_item_4') !!}</li>
                </ul>
            </div>
            <div class="docs-subsection">
                <h3 class="docs-subsection-title">{{ trans('app.docs.exercices.track_progress') }}</h3>
                <p class="docs-text">
                    {{ trans('app.docs.exercices.track_progress_text') }}
                </p>
                <ul class="docs-list">
                    <li class="docs-list-item">{{ trans('app.docs.exercices.track_progress_item_1') }}</li>
                    <li class="docs-list-item">{{ trans('app.docs.exercices.track_progress_item_2') }}</li>
                    <li class="docs-list-item">{{ trans('app.docs.exercices.track_progress_item_3') }}</li>
                </ul>
            </div>
        </section>
        
        <!-- Quiz -->
        <section id="quiz" class="docs-section" data-aos="fade-up">
            <h2 class="docs-section-title">
                <i class="fas fa-question-circle"></i>
                <span>{{ trans('app.docs.quiz.title') }}</span>
            </h2>
            <div class="docs-subsection">
                <h3 class="docs-subsection-title">{{ trans('app.docs.quiz.access') }}</h3>
                <ol class="docs-step-list">
                    <li class="docs-step-item">{!! trans('app.docs.quiz.access_step_1') !!}</li>
                    <li class="docs-step-item">{!! trans('app.docs.quiz.access_step_2') !!}</li>
                    <li class="docs-step-item">{!! trans('app.docs.quiz.access_step_3') !!}</li>
                </ol>
            </div>
            <div class="docs-subsection">
                <h3 class="docs-subsection-title">{{ trans('app.docs.quiz.take') }}</h3>
                <ol class="docs-step-list">
                    <li class="docs-step-item">{{ trans('app.docs.quiz.take_step_1') }}</li>
                    <li class="docs-step-item">{{ trans('app.docs.quiz.take_step_2') }}</li>
                    <li class="docs-step-item">{{ trans('app.docs.quiz.take_step_3') }}</li>
                    <li class="docs-step-item">{{ trans('app.docs.quiz.take_step_4') }}</li>
                </ol>
            </div>
            <div class="docs-subsection">
                <h3 class="docs-subsection-title">{{ trans('app.docs.quiz.results') }}</h3>
                <p class="docs-text">
                    {{ trans('app.docs.quiz.results_text') }}
                </p>
                <ul class="docs-list">
                    <li class="docs-list-item">{!! trans('app.docs.quiz.results_item_1') !!}</li>
                    <li class="docs-list-item">{!! trans('app.docs.quiz.results_item_2') !!}</li>
                    <li class="docs-list-item">{!! trans('app.docs.quiz.results_item_3') !!}</li>
                </ul>
            </div>
        </section>
        
        <!-- Dashboard -->
        <section id="dashboard" class="docs-section" data-aos="fade-up">
            <h2 class="docs-section-title">
                <i class="fas fa-tachometer-alt"></i>
                <span>{{ trans('app.docs.dashboard.title') }}</span>
            </h2>
            <div class="docs-subsection">
                <h3 class="docs-subsection-title">{{ trans('app.docs.dashboard.access') }}</h3>
                <p class="docs-text">
                    {!! trans('app.docs.dashboard.access_text') !!}
                </p>
            </div>
            <div class="docs-subsection">
                <h3 class="docs-subsection-title">{{ trans('app.docs.dashboard.overview') }}</h3>
                <p class="docs-text">
                    {{ trans('app.docs.dashboard.overview_text') }}
                </p>
                <ul class="docs-list">
                    <li class="docs-list-item">{!! trans('app.docs.dashboard.overview_item_1') !!}</li>
                    <li class="docs-list-item">{!! trans('app.docs.dashboard.overview_item_2') !!}</li>
                    <li class="docs-list-item">{!! trans('app.docs.dashboard.overview_item_3') !!}</li>
                    <li class="docs-list-item">{!! trans('app.docs.dashboard.overview_item_4') !!}</li>
                    <li class="docs-list-item">{!! trans('app.docs.dashboard.overview_item_5') !!}</li>
                </ul>
            </div>
            <div class="docs-subsection">
                <h3 class="docs-subsection-title">{{ trans('app.docs.dashboard.sections') }}</h3>
                <ul class="docs-list">
                    <li class="docs-list-item">{!! trans('app.docs.dashboard.sections_item_1') !!}</li>
                    <li class="docs-list-item">{!! trans('app.docs.dashboard.sections_item_2') !!}</li>
                    <li class="docs-list-item">{!! trans('app.docs.dashboard.sections_item_3') !!}</li>
                    <li class="docs-list-item">{!! trans('app.docs.dashboard.sections_item_4') !!}</li>
                    <li class="docs-list-item">{!! trans('app.docs.dashboard.sections_item_5') !!}</li>
                    <li class="docs-list-item">{!! trans('app.docs.dashboard.sections_item_6') !!}</li>
                    <li class="docs-list-item">{!! trans('app.docs.dashboard.sections_item_7') !!}</li>
                    <li class="docs-list-item">{!! trans('app.docs.dashboard.sections_item_8') !!}</li>
                    <li class="docs-list-item">{!! trans('app.docs.dashboard.sections_item_9') !!}</li>
                    <li class="docs-list-item">{!! trans('app.docs.dashboard.sections_item_10') !!}</li>
                    <li class="docs-list-item">{!! trans('app.docs.dashboard.sections_item_11') !!}</li>
                </ul>
            </div>
        </section>
        
        <!-- Emplois -->
        <section id="emplois" class="docs-section" data-aos="fade-up">
            <h2 class="docs-section-title">
                <i class="fas fa-briefcase"></i>
                <span>{{ trans('app.docs.emplois.title') }}</span>
            </h2>
            <div class="docs-subsection">
                <h3 class="docs-subsection-title">{{ trans('app.docs.emplois.access') }}</h3>
                <ol class="docs-step-list">
                    <li class="docs-step-item">{!! trans('app.docs.emplois.access_step_1') !!}</li>
                    <li class="docs-step-item">{!! trans('app.docs.emplois.access_step_2') !!}</li>
                </ol>
            </div>
            <div class="docs-subsection">
                <h3 class="docs-subsection-title">{{ trans('app.docs.emplois.filter') }}</h3>
                <p class="docs-text">
                    {{ trans('app.docs.emplois.filter_text') }}
                </p>
                <ul class="docs-list">
                    <li class="docs-list-item">{!! trans('app.docs.emplois.filter_item_1') !!}</li>
                    <li class="docs-list-item">{!! trans('app.docs.emplois.filter_item_2') !!}</li>
                    <li class="docs-list-item">{!! trans('app.docs.emplois.filter_item_3') !!}</li>
                </ul>
            </div>
            <div class="docs-subsection">
                <h3 class="docs-subsection-title">{{ trans('app.docs.emplois.view') }}</h3>
                <p class="docs-text">
                    {{ trans('app.docs.emplois.view_text') }}
                </p>
                <ul class="docs-list">
                    <li class="docs-list-item">{{ trans('app.docs.emplois.view_item_1') }}</li>
                    <li class="docs-list-item">{{ trans('app.docs.emplois.view_item_2') }}</li>
                    <li class="docs-list-item">{{ trans('app.docs.emplois.view_item_3') }}</li>
                    <li class="docs-list-item">{{ trans('app.docs.emplois.view_item_4') }}</li>
                </ul>
            </div>
        </section>
        
        <!-- Favoris -->
        <section id="favoris" class="docs-section" data-aos="fade-up">
            <h2 class="docs-section-title">
                <i class="fas fa-heart"></i>
                <span>{{ trans('app.docs.favoris.title') }}</span>
            </h2>
            <div class="docs-subsection">
                <h3 class="docs-subsection-title">{{ trans('app.docs.favoris.add') }}</h3>
                <p class="docs-text">
                    {{ trans('app.docs.favoris.add_text') }}
                </p>
                <ul class="docs-list">
                    <li class="docs-list-item">{!! trans('app.docs.favoris.add_item_1') !!}</li>
                    <li class="docs-list-item">{!! trans('app.docs.favoris.add_item_2') !!}</li>
                </ul>
            </div>
            <div class="docs-subsection">
                <h3 class="docs-subsection-title">{{ trans('app.docs.favoris.view') }}</h3>
                <ol class="docs-step-list">
                    <li class="docs-step-item">{{ trans('app.docs.favoris.view_step_1') }}</li>
                    <li class="docs-step-item">{!! trans('app.docs.favoris.view_step_2') !!}</li>
                    <li class="docs-step-item">{{ trans('app.docs.favoris.view_step_3') }}</li>
                </ol>
            </div>
            <div class="docs-subsection">
                <h3 class="docs-subsection-title">{{ trans('app.docs.favoris.remove') }}</h3>
                <p class="docs-text">
                    {!! trans('app.docs.favoris.remove_text') !!}
                </p>
            </div>
        </section>
        
        <!-- Affiliation -->
        <section id="affiliation" class="docs-section" data-aos="fade-up">
            <h2 class="docs-section-title">
                <i class="fas fa-users"></i>
                <span>{{ trans('app.docs.affiliation.title') }}</span>
            </h2>
            <div class="docs-subsection">
                <h3 class="docs-subsection-title">{{ trans('app.docs.affiliation.become') }}</h3>
                <p class="docs-text">
                    {{ trans('app.docs.affiliation.become_text') }}
                </p>
                <ol class="docs-step-list">
                    <li class="docs-step-item">{!! trans('app.docs.affiliation.become_step_1') !!}</li>
                    <li class="docs-step-item">{{ trans('app.docs.affiliation.become_step_2') }}</li>
                    <li class="docs-step-item">{{ trans('app.docs.affiliation.become_step_3') }}</li>
                </ol>
            </div>
            <div class="docs-subsection">
                <h3 class="docs-subsection-title">{{ trans('app.docs.affiliation.how_it_works') }}</h3>
                <ol class="docs-step-list">
                    <li class="docs-step-item">{!! trans('app.docs.affiliation.how_it_works_step_1') !!}</li>
                    <li class="docs-step-item">{!! trans('app.docs.affiliation.how_it_works_step_2') !!}</li>
                    <li class="docs-step-item">{!! trans('app.docs.affiliation.how_it_works_step_3') !!}</li>
                    <li class="docs-step-item">{!! trans('app.docs.affiliation.how_it_works_step_4') !!}</li>
                </ol>
            </div>
            <div class="docs-subsection">
                <h3 class="docs-subsection-title">{{ trans('app.docs.affiliation.dashboard_title') }}</h3>
                <p class="docs-text">
                    {{ trans('app.docs.affiliation.dashboard_text') }}
                </p>
                <ul class="docs-list">
                    <li class="docs-list-item">{{ trans('app.docs.affiliation.dashboard_item_1') }}</li>
                    <li class="docs-list-item">{{ trans('app.docs.affiliation.dashboard_item_2') }}</li>
                    <li class="docs-list-item">{{ trans('app.docs.affiliation.dashboard_item_3') }}</li>
                    <li class="docs-list-item">{{ trans('app.docs.affiliation.dashboard_item_4') }}</li>
                </ul>
            </div>
        </section>
    </main>
</div>
</div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    // Initialiser AOS
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        offset: 100
    });
    
    // Navigation active
    document.querySelectorAll('.docs-nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetSection = document.getElementById(targetId);
            
            if (targetSection) {
                // Retirer active de tous les liens
                document.querySelectorAll('.docs-nav-link').forEach(l => l.classList.remove('active'));
                // Ajouter active au lien cliqué
                this.classList.add('active');
                
                // Scroll vers la section
                const offsetTop = targetSection.offsetTop - 100;
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
                
                // Fermer le sidebar en mobile après le scroll
                if (window.innerWidth <= 992) {
                    setTimeout(() => {
                        const sidebar = document.getElementById('docsSidebar');
                        const sidebarOverlay = document.getElementById('docsSidebarOverlay');
                        const sidebarClose = document.getElementById('docsSidebarClose');
                        const sidebarToggle = document.getElementById('docsSidebarToggle');
                        if (sidebar) sidebar.classList.remove('active');
                        if (sidebarOverlay) sidebarOverlay.classList.remove('active');
                        if (sidebarClose) sidebarClose.style.display = 'none';
                        if (sidebarToggle) sidebarToggle.classList.remove('active');
                        document.body.style.overflow = '';
                    }, 300);
                }
            }
        });
    });
    
    // Mettre à jour le lien actif lors du scroll
    let ticking = false;
    window.addEventListener('scroll', () => {
        if (!ticking) {
            window.requestAnimationFrame(() => {
                const sections = document.querySelectorAll('.docs-section');
                const navLinks = document.querySelectorAll('.docs-nav-link');
                
                let current = '';
                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.clientHeight;
                    if (window.scrollY + 150 >= sectionTop && window.scrollY + 150 < sectionTop + sectionHeight) {
                        current = section.getAttribute('id');
                    }
                });
                
                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === '#' + current) {
                        link.classList.add('active');
                    }
                });
                
                ticking = false;
            });
            ticking = true;
        }
    });

    // SCRIPT DE GESTION MOBILE SIDEBAR (comme formations/html5)
    (function() {
        'use strict';
        
        const sidebar = document.getElementById('docsSidebar');
        const sidebarToggle = document.getElementById('docsSidebarToggle');
        const sidebarOverlay = document.getElementById('docsSidebarOverlay');
        const sidebarClose = document.getElementById('docsSidebarClose');
        const toggleIcon = document.getElementById('docsSidebarToggleIcon');
        
        function isMobile() {
            return window.innerWidth <= 992;
        }
        
        function openSidebar() {
            if (sidebar) {
                sidebar.classList.add('active');
                if (sidebarOverlay) sidebarOverlay.classList.add('active');
                if (sidebarClose) sidebarClose.style.display = 'flex';
                if (sidebarToggle) sidebarToggle.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }
        
        function closeSidebar() {
            if (sidebar) {
                sidebar.classList.remove('active');
                if (sidebarOverlay) sidebarOverlay.classList.remove('active');
                if (sidebarClose) sidebarClose.style.display = 'none';
                if (sidebarToggle) sidebarToggle.classList.remove('active');
                document.body.style.overflow = '';
            }
        }
        
        function initSidebar() {
            if (isMobile() && sidebar) {
                sidebar.classList.remove('active');
                if (sidebarOverlay) sidebarOverlay.classList.remove('active');
                if (sidebarClose) sidebarClose.style.display = 'none';
                if (sidebarToggle) sidebarToggle.classList.remove('active');
            }
        }
        
        // Initialiser au chargement
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initSidebar);
        } else {
            initSidebar();
        }
        
        // Événements
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
        
        // Gérer le redimensionnement
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (!isMobile()) {
                    closeSidebar();
                }
                initSidebar();
            }, 100);
        });
    })();

    // SCRIPT POUR FORCER LE STICKY DU SIDEBAR (comme dans les formations)
    (function() {
        'use strict';
        
        function isMobile() {
            return window.innerWidth <= 992;
        }
        
        function forceSticky() {
            if (isMobile()) return;
            
            const sidebar = document.querySelector('.docs-sidebar');
            const container = document.querySelector('.docs-container');
            const wrapper = document.querySelector('.docs-container-wrapper');
            const body = document.body;
            const html = document.documentElement;
            
            if (!sidebar || !container) return;
            
            // ÉTAPE 1 : Forcer overflow: visible sur TOUS les parents (CRITIQUE pour sticky)
            const parents = [body, html, wrapper, container];
            parents.forEach(parent => {
                if (parent) {
                    const computed = window.getComputedStyle(parent);
                    if (computed.overflow !== 'visible' && computed.overflow !== 'auto') {
                        parent.style.setProperty('overflow', 'visible', 'important');
                    }
                    if (computed.overflowY !== 'visible' && computed.overflowY !== 'auto') {
                        parent.style.setProperty('overflow-y', 'visible', 'important');
                    }
                    if (computed.overflowX === 'hidden') {
                        parent.style.setProperty('overflow-x', 'visible', 'important');
                    }
                }
            });
            
            // ÉTAPE 2 : Forcer position: relative sur les conteneurs parents
            if (wrapper) {
                wrapper.style.setProperty('position', 'relative', 'important');
                wrapper.style.setProperty('overflow', 'visible', 'important');
            }
            if (container) {
                container.style.setProperty('position', 'relative', 'important');
                container.style.setProperty('overflow', 'visible', 'important');
                container.style.setProperty('display', 'flex', 'important');
                container.style.setProperty('align-items', 'flex-start', 'important');
            }
            
            // ÉTAPE 3 : Forcer sticky sur sidebar avec !important
            const navbarHeight = 60;
            const topValue = navbarHeight + 20; // navbar + padding
            
            sidebar.style.setProperty('position', 'sticky', 'important');
            sidebar.style.setProperty('top', topValue + 'px', 'important');
            sidebar.style.setProperty('align-self', 'flex-start', 'important');
            sidebar.style.setProperty('flex-shrink', '0', 'important');
            sidebar.style.setProperty('width', '320px', 'important');
            
            // ÉTAPE 4 : Ajuster la hauteur
            const viewportHeight = window.innerHeight;
            const maxHeight = viewportHeight - topValue;
            sidebar.style.setProperty('max-height', maxHeight + 'px', 'important');
            sidebar.style.setProperty('height', 'auto', 'important');
            sidebar.style.setProperty('overflow-y', 'auto', 'important');
        }
        
        function init() {
            if (isMobile()) return;
            
            // Exécuter immédiatement et plusieurs fois pour s'assurer
            forceSticky();
            setTimeout(forceSticky, 50);
            setTimeout(forceSticky, 100);
            setTimeout(forceSticky, 200);
            setTimeout(forceSticky, 500);
            setTimeout(forceSticky, 1000);
        }
        
        // Écouter le scroll pour maintenir le sticky
        let scrollTicking = false;
        function onScroll() {
            if (isMobile()) return;
            if (!scrollTicking) {
                window.requestAnimationFrame(() => {
                    forceSticky();
                    scrollTicking = false;
                });
                scrollTicking = true;
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
        
        window.addEventListener('load', function() {
            if (!isMobile()) {
                init();
            }
        });
    })();
</script>
@endsection
