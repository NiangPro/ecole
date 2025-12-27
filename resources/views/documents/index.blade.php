@extends('layouts.app')

@section('title', 'Documents - NiangProgrammeur')
@section('meta_description', 'Découvrez notre collection de documents numériques : guides, tutoriels, templates et ressources pour développeurs.')

@push('styles')
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.documents-page {
    min-height: 100vh;
    padding: 0;
    background: #ffffff;
    position: relative;
    overflow-x: visible;
    overflow-y: visible;
}

.documents-page::before {
    display: none;
}

.documents-hero {
    text-align: center;
    padding: 3rem 1rem 2rem;
    margin-bottom: 2rem;
    position: relative;
    z-index: 1;
    background: #ffffff;
    border-bottom: 1px solid #e2e8f0;
}

.documents-hero h1 {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 1rem;
    color: #1e293b;
    letter-spacing: -0.02em;
}

.documents-hero p {
    font-size: 1.125rem;
    color: #64748b;
    max-width: 700px;
    margin: 0 auto;
    line-height: 1.6;
}

.documents-container {
    max-width: 1600px;
    margin: 0 auto;
    padding: 0 2rem 4rem;
    display: flex;
    gap: 2.5rem;
    align-items: flex-start;
    position: relative;
    z-index: 1;
}

/* Sidebar Sticky Ultra Moderne - Exactement comme html5.blade.php */
.documents-filters {
    width: 320px;
    flex-shrink: 0;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08), 0 1px 3px rgba(0, 0, 0, 0.1);
    position: -webkit-sticky;
    position: sticky;
    top: 60px;
    align-self: flex-start;
    height: calc(100vh - 60px);
    max-height: calc(100vh - 60px);
    overflow-y: auto;
    overflow-x: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 10;
}

.documents-filters::-webkit-scrollbar {
    width: 6px;
}

.documents-filters::-webkit-scrollbar-track {
    background: transparent;
    border-radius: 10px;
}

.documents-filters::-webkit-scrollbar-thumb {
    background: linear-gradient(180deg, #06b6d4 0%, #14b8a6 100%);
    border-radius: 10px;
}

.documents-filters::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(180deg, #14b8a6 0%, #0891b2 100%);
}

.documents-filters::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #06b6d4, #14b8a6, #0891b2, #0d9488, #06b6d4);
    background-size: 200% 100%;
    animation: shimmer 3s linear infinite;
}

@keyframes shimmer {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

.documents-filters h3 {
    font-size: 1.5rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.documents-filters h3 i {
    color: #06b6d4;
}

.filter-group {
    margin-bottom: 1.75rem;
}

.filter-group label {
    display: block;
    font-weight: 700;
    color: #475569;
    margin-bottom: 0.75rem;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.filter-group select {
    width: 100%;
    padding: 0.875rem 1rem;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    font-size: 0.9375rem;
    color: #1e293b;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%231e293b' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    padding-right: 2.5rem;
}

.filter-group select:hover {
    background: #f8fafc;
    border-color: #06b6d4;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(6, 182, 212, 0.15);
}

.filter-group select:focus {
    outline: none;
    background: #ffffff;
    border-color: #06b6d4;
    box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.1);
}

.filter-group select option {
    background: #ffffff;
    color: #1e293b;
    padding: 0.5rem;
}

/* Contenu Principal */
.documents-main {
    position: relative;
}

.featured-section {
    margin-bottom: 4rem;
}

.section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #e2e8f0;
}

.section-title {
    font-size: 2rem;
    font-weight: 800;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.section-title i {
    color: #06b6d4;
    animation: starPulse 2s ease-in-out infinite;
}

@keyframes starPulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.documents-main {
    flex: 1;
    min-width: 0;
}

.documents-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
}

/* Carte Document Style Prepa.sn - Design Fidèle */
.document-card {
    position: relative;
    background: #ffffff;
    border-radius: 12px;
    overflow: hidden;
    height: 100%;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08), 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e0e0e0;
}

.document-card:hover {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12), 0 4px 8px rgba(0, 0, 0, 0.08);
    transform: translateY(-2px);
}

/* Image wrapper - Style Prepa.sn */
.document-cover-wrapper {
    position: relative;
    width: 100%;
    height: 200px;
    overflow: hidden;
    background: #fafafa;
}

.document-cover {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.document-cover-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #e0e0e0;
    color: #999999;
    font-size: 2.5rem;
}

/* Prix étiquette oblique sur l'image */
.document-price-overlay {
    position: absolute;
    top: 20px;
    right: -40px;
    background: #06b6d4;
    color: white;
    padding: 0.625rem 3rem;
    font-size: 1rem;
    font-weight: 700;
    z-index: 10;
    transform: rotate(45deg);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.125rem;
    min-width: 180px;
    text-align: center;
    line-height: 1.2;
}

.document-price-overlay .document-price-old {
    font-size: 0.7rem;
    color: rgba(255, 255, 255, 0.8);
    text-decoration: line-through;
    font-weight: 500;
    display: block;
}

.document-price-overlay .document-price-current {
    font-size: 1rem;
    font-weight: 700;
    color: white;
    white-space: nowrap;
    display: block;
}

.document-card-body {
    padding: 1rem;
    flex: 1;
    display: flex;
    flex-direction: column;
    background: #ffffff;
}

/* Titre - Style Prepa.sn (en haut) */
.document-title {
    font-size: 1rem;
    font-weight: 600;
    color: #212121;
    margin-bottom: 0.75rem;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 2.8em;
}

.document-title a {
    color: #212121;
    text-decoration: none;
}

.document-title a:hover {
    color: #06b6d4;
}


/* Description - Style Prepa.sn */
.document-excerpt {
    font-size: 0.875rem;
    color: #757575;
    margin-bottom: 1rem;
    flex: 1;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.5;
    min-height: 2.625em;
}

/* Badge catégorie/niveau - Style Prepa.sn */
.document-category {
    display: inline-block;
    font-size: 0.75rem;
    font-weight: 500;
    color: #757575;
    margin-bottom: 0.75rem;
    padding: 0.125rem 0.5rem;
    background-color: transparent;
    border: none;
    width: fit-content;
}

.document-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
    padding-top: 0.75rem;
    border-top: 1px solid #e0e0e0;
    gap: 0.75rem;
}

.document-discount-badge {
    position: absolute;
    top: 0.5rem;
    left: 0.5rem;
    background-color: #f44336;
    color: white;
    font-size: 0.75rem;
    font-weight: 700;
    padding: 0.25rem 0.5rem;
    border-radius: 0;
    z-index: 10;
}

.document-btn {
    width: 40px;
    height: 40px;
    padding: 0;
    background-color: #06b6d4;
    color: white;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.document-btn i {
    font-size: 0.875rem;
}

.document-btn:hover {
    background-color: #0891b2;
    color: white;
    transform: scale(1.1);
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    margin-top: 2rem;
}

.empty-state i {
    font-size: 4rem;
    color: #cbd5e1;
    margin-bottom: 1.5rem;
    display: block;
}

.empty-state p {
    color: #64748b;
    font-size: 1.25rem;
    font-weight: 600;
}

/* Pagination Moderne */
.pagination-wrapper {
    margin-top: 3rem;
    display: flex;
    justify-content: center;
}

.pagination-wrapper :deep(.pagination) {
    display: flex;
    gap: 0.5rem;
    list-style: none;
    padding: 0;
}

.pagination-wrapper :deep(.page-item) {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.pagination-wrapper :deep(.page-item:hover) {
    background: #f8fafc;
    border-color: #06b6d4;
    transform: translateY(-2px);
}

.pagination-wrapper :deep(.page-item.active) {
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    border-color: #06b6d4;
}

.pagination-wrapper :deep(.page-link) {
    padding: 0.75rem 1.25rem;
    color: #475569;
    text-decoration: none;
    display: block;
    font-weight: 600;
}

.pagination-wrapper :deep(.page-item.active .page-link) {
    color: white;
}

/* Responsive */
@media (max-width: 1200px) {
    .documents-filters {
        width: 280px;
    }
    
    .documents-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
    }
}

@media (max-width: 968px) {
    .documents-container {
        flex-direction: column;
        padding: 0 1rem 2rem;
    }
    
    .documents-filters {
        width: 100%;
        position: static;
        height: auto;
        max-height: none;
        margin-bottom: 2rem;
    }
    
    .documents-hero h1 {
        font-size: 2.5rem;
    }
    
    .documents-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
}

@media (max-width: 640px) {
    .documents-hero {
        padding: 3rem 1rem 2rem;
    }
    
    .documents-hero h1 {
        font-size: 2rem;
    }
    
    .documents-hero p {
        font-size: 1rem;
    }
    
    .documents-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .section-title {
        font-size: 1.5rem;
    }
}

/* ============================================
   DARK MODE ADAPTATIONS COMPLÈTES
   ============================================ */
body.dark-mode .documents-page {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 25%, #0f172a 50%, #1e293b 75%, #0f172a 100%) !important;
    background-size: 400% 400%;
}

body.dark-mode .documents-page::before {
    background: rgba(15, 23, 42, 0.95);
    backdrop-filter: blur(2px);
}

body.dark-mode .documents-hero {
    background: linear-gradient(135deg, rgba(15, 23, 42, 0.95) 0%, rgba(30, 41, 59, 0.9) 100%);
    border-bottom: 1px solid rgba(6, 182, 212, 0.2);
}

body.dark-mode .documents-hero h1 {
    background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 50%, #0891b2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-shadow: 0 0 40px rgba(6, 182, 212, 0.5);
}

body.dark-mode .documents-hero p {
    color: rgba(255, 255, 255, 0.9);
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
}

/* Sidebar Dark Mode */
body.dark-mode .documents-filters {
    background: rgba(30, 41, 59, 0.8);
    backdrop-filter: blur(20px) saturate(180%);
    -webkit-backdrop-filter: blur(20px) saturate(180%);
    border: 1px solid rgba(6, 182, 212, 0.3);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4),
                0 0 0 1px rgba(6, 182, 212, 0.2) inset;
}

body.dark-mode .documents-filters::before {
    background: linear-gradient(90deg, #06b6d4, #14b8a6, #0891b2, #0d9488, #06b6d4);
}

body.dark-mode .documents-filters h3 {
    color: white;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
}

body.dark-mode .documents-filters h3 i {
    color: #06b6d4;
    filter: drop-shadow(0 0 10px rgba(6, 182, 212, 0.8));
}

body.dark-mode .filter-group label {
    color: rgba(255, 255, 255, 0.95);
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
}

body.dark-mode .filter-group select {
    background: rgba(15, 23, 42, 0.6);
    border: 1px solid rgba(6, 182, 212, 0.3);
    color: white;
}

body.dark-mode .filter-group select:hover {
    background: rgba(15, 23, 42, 0.8);
    border-color: rgba(6, 182, 212, 0.5);
    box-shadow: 0 4px 12px rgba(6, 182, 212, 0.4);
}

body.dark-mode .filter-group select:focus {
    background: rgba(15, 23, 42, 0.9);
    border-color: #06b6d4;
    box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.4);
}

body.dark-mode .filter-group select option {
    background: #1e293b;
    color: white;
}

/* Section Headers Dark Mode */
body.dark-mode .section-header {
    border-bottom: 2px solid rgba(6, 182, 212, 0.2);
}

body.dark-mode .section-title {
    color: white;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
}

body.dark-mode .section-title i {
    filter: drop-shadow(0 0 12px rgba(251, 191, 36, 0.8));
}

/* Cartes Documents Dark Mode - Style Prepa.sn */
body.dark-mode .document-card {
    background: #1f2937;
    border-color: #374151;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

body.dark-mode .document-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
    border-color: #06b6d4;
}

body.dark-mode .document-cover-wrapper {
    background: #111827;
}

body.dark-mode .document-cover-placeholder {
    background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 100%);
}

body.dark-mode .document-card-body {
    background: #1f2937;
}

body.dark-mode .document-title {
    color: #f3f4f6;
}

body.dark-mode .document-title a {
    color: #f3f4f6;
}

body.dark-mode .document-title a:hover {
    color: #06b6d4;
}

body.dark-mode .document-excerpt {
    color: #9ca3af;
}

body.dark-mode .document-category {
    background-color: #374151;
    color: #d1d5db;
}

body.dark-mode .document-footer {
    border-top-color: #374151;
}

body.dark-mode .document-price-overlay {
    background: rgba(6, 182, 212, 0.95);
}

body.dark-mode .document-price-overlay .document-price-current {
    color: white;
}

body.dark-mode .document-price-overlay .document-price-old {
    color: rgba(255, 255, 255, 0.8);
}

/* Empty State Dark Mode */
body.dark-mode .empty-state {
    background: rgba(30, 41, 59, 0.7);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(6, 182, 212, 0.3);
}

body.dark-mode .empty-state i {
    color: rgba(255, 255, 255, 0.4);
}

body.dark-mode .empty-state p {
    color: rgba(255, 255, 255, 0.9);
}

/* Pagination Dark Mode */
body.dark-mode .pagination-wrapper :deep(.pagination) {
    background: transparent;
}

body.dark-mode .pagination-wrapper :deep(.page-item) {
    background: rgba(30, 41, 59, 0.7);
    border: 1px solid rgba(6, 182, 212, 0.3);
}

body.dark-mode .pagination-wrapper :deep(.page-item:hover) {
    background: rgba(30, 41, 59, 0.9);
    border-color: rgba(6, 182, 212, 0.5);
}

body.dark-mode .pagination-wrapper :deep(.page-item.active) {
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    border-color: #06b6d4;
}

body.dark-mode .pagination-wrapper :deep(.page-link) {
    color: white;
}

body.dark-mode .pagination-wrapper :deep(.page-item.active .page-link) {
    color: white;
}

/* Messages Flash */
.flash-message {
    position: fixed;
    top: 80px;
    right: 20px;
    z-index: 10000;
    background: white;
    border-radius: 12px;
    padding: 1rem 1.25rem;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15), 0 2px 8px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    min-width: 320px;
    max-width: 500px;
    animation: slideInRight 0.4s ease-out;
    border-left: 4px solid;
}

@keyframes slideInRight {
    from {
        transform: translateX(400px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.flash-content {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex: 1;
}

.flash-content i {
    font-size: 1.25rem;
    flex-shrink: 0;
}

.flash-content span {
    font-size: 0.95rem;
    font-weight: 500;
    line-height: 1.4;
}

.flash-close {
    background: none;
    border: none;
    color: rgba(0, 0, 0, 0.4);
    cursor: pointer;
    padding: 0.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    transition: all 0.2s ease;
    flex-shrink: 0;
}

.flash-close:hover {
    background: rgba(0, 0, 0, 0.05);
    color: rgba(0, 0, 0, 0.6);
}

.flash-success {
    background: #f0fdf4;
    border-left-color: #10b981;
    color: #065f46;
}

.flash-success .flash-content i {
    color: #10b981;
}

.flash-info {
    background: #eff6ff;
    border-left-color: #06b6d4;
    color: #0c4a6e;
}

.flash-info .flash-content i {
    color: #06b6d4;
}

.flash-error {
    background: #fef2f2;
    border-left-color: #ef4444;
    color: #991b1b;
}

.flash-error .flash-content i {
    color: #ef4444;
}

/* Dark Mode pour les messages flash */
body.dark-mode .flash-message {
    background: rgba(30, 41, 59, 0.95);
    backdrop-filter: blur(20px);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(6, 182, 212, 0.1);
}

body.dark-mode .flash-success {
    background: rgba(16, 185, 129, 0.15);
    border-left-color: #10b981;
    color: #6ee7b7;
}

body.dark-mode .flash-success .flash-content i {
    color: #10b981;
    filter: drop-shadow(0 0 8px rgba(16, 185, 129, 0.6));
}

body.dark-mode .flash-info {
    background: rgba(6, 182, 212, 0.15);
    border-left-color: #06b6d4;
    color: #67e8f9;
}

body.dark-mode .flash-info .flash-content i {
    color: #06b6d4;
    filter: drop-shadow(0 0 8px rgba(6, 182, 212, 0.6));
}

body.dark-mode .flash-error {
    background: rgba(239, 68, 68, 0.15);
    border-left-color: #ef4444;
    color: #fca5a5;
}

body.dark-mode .flash-error .flash-content i {
    color: #ef4444;
    filter: drop-shadow(0 0 8px rgba(239, 68, 68, 0.6));
}

body.dark-mode .flash-close {
    color: rgba(255, 255, 255, 0.5);
}

body.dark-mode .flash-close:hover {
    background: rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.8);
}

@media (max-width: 640px) {
    .flash-message {
        right: 10px;
        left: 10px;
        min-width: auto;
        max-width: none;
    }
}
</style>
@endpush

@section('content')
<!-- Messages Flash -->
@if(session('success'))
<div class="flash-message flash-success" id="flashMessage">
    <div class="flash-content">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
    <button class="flash-close" onclick="this.parentElement.remove()">
        <i class="fas fa-times"></i>
    </button>
</div>
@endif

@if(session('info'))
<div class="flash-message flash-info" id="flashMessage">
    <div class="flash-content">
        <i class="fas fa-info-circle"></i>
        <span>{{ session('info') }}</span>
    </div>
    <button class="flash-close" onclick="this.parentElement.remove()">
        <i class="fas fa-times"></i>
    </button>
</div>
@endif

@if(session('error'))
<div class="flash-message flash-error" id="flashMessage">
    <div class="flash-content">
        <i class="fas fa-exclamation-circle"></i>
        <span>{{ session('error') }}</span>
    </div>
    <button class="flash-close" onclick="this.parentElement.remove()">
        <i class="fas fa-times"></i>
    </button>
</div>
@endif

<div class="documents-page">
    <!-- Hero Section -->
    <div class="documents-hero">
        <h1>📚 Documents & Ressources</h1>
        <p>Découvrez notre collection de documents numériques : guides, tutoriels, templates et ressources pour développeurs.</p>
    </div>

    <div class="documents-container">
        <!-- Sidebar Filtres Sticky -->
        <aside class="documents-filters">
            <h3>
                <i class="fas fa-filter"></i> Filtres
            </h3>

            <form method="GET" action="{{ route('documents.index') }}">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                <!-- Filtre Catégorie -->
                <div class="filter-group">
                    <label>Catégorie</label>
                    <select name="category" onchange="this.form.submit()">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->slug }}" {{ request('category') === $category->slug ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtre Tri -->
                <div class="filter-group">
                    <label>Trier par</label>
                    <select name="sort" onchange="this.form.submit()">
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Plus récents</option>
                        <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Plus populaires</option>
                        <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                    </select>
                </div>
            </form>
        </aside>

        <!-- Contenu Principal -->
        <main class="documents-main">
            @if($featuredDocuments->count() > 0)
            <div class="featured-section">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="fas fa-star"></i> Documents en vedette
                    </h2>
                </div>
                <div class="documents-grid">
                    @foreach($featuredDocuments as $document)
                        @include('documents.partials.card', ['document' => $document])
                    @endforeach
                </div>
            </div>
            @endif

            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-file-alt"></i> Tous les documents
                </h2>
            </div>

            @if($documents->count() > 0)
                <div class="documents-grid">
                    @foreach($documents as $document)
                        @include('documents.partials.card', ['document' => $document])
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="pagination-wrapper">
                    {{ $documents->links() }}
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>Aucun document trouvé.</p>
                </div>
            @endif
        </main>
    </div>
</div>

@push('scripts')
<script>
// Script pour forcer le sticky du sidebar (comme dans html5.blade.php)
(function() {
    'use strict';
    
    function isMobile() {
        return window.innerWidth <= 968;
    }
    
    function forceSticky() {
        if (isMobile()) return;
        
        const sidebar = document.querySelector('.documents-filters');
        const container = document.querySelector('.documents-container');
        const page = document.querySelector('.documents-page');
        const body = document.body;
        const html = document.documentElement;
        
        if (!sidebar || !container) return;
        
        // ÉTAPE 1 : Forcer overflow: visible sur TOUS les parents (CRITIQUE pour sticky)
        const parents = [body, html, page, container];
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
        if (page) {
            page.style.setProperty('position', 'relative', 'important');
            page.style.setProperty('overflow', 'visible', 'important');
        }
        if (container) {
            container.style.setProperty('overflow', 'visible', 'important');
            container.style.setProperty('display', 'flex', 'important');
            container.style.setProperty('align-items', 'flex-start', 'important');
        }
        
        // ÉTAPE 3 : Forcer sticky sur sidebar avec !important
        const navbarHeight = 60;
        const topValue = navbarHeight;
        
        sidebar.style.setProperty('position', 'sticky', 'important');
        sidebar.style.setProperty('-webkit-position', '-webkit-sticky', 'important');
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
        if (!scrollTicking) {
            window.requestAnimationFrame(function() {
                if (!isMobile()) {
                    forceSticky();
                }
                scrollTicking = false;
            });
            scrollTicking = true;
        }
    }
    
    // Écouter le redimensionnement
    let resizeTicking = false;
    function onResize() {
        if (!resizeTicking) {
            window.requestAnimationFrame(function() {
                init();
                resizeTicking = false;
            });
            resizeTicking = true;
        }
    }
    
    // Initialiser
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
    // Écouter les événements
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onResize);
})();

// Auto-hide flash messages après 5 secondes
document.addEventListener('DOMContentLoaded', function() {
    const flashMessages = document.querySelectorAll('.flash-message');
    flashMessages.forEach(function(message) {
        setTimeout(function() {
            message.style.animation = 'slideOutRight 0.4s ease-out';
            setTimeout(function() {
                message.remove();
            }, 400);
        }, 5000);
    });
});

// Animation de sortie
const style = document.createElement('style');
style.textContent = `
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>
@endpush
@endsection
