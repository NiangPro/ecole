@extends('layouts.app')

@section('title', $category->name . ' - Documents - NiangProgrammeur')
@section('meta_description', $category->description ?? 'Découvrez nos documents dans la catégorie ' . $category->name)

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
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 50%, #f1f5f9 100%);
    position: relative;
    overflow-x: visible;
    overflow-y: visible;
}

/* Hero Section Ultra Moderne */
.category-hero {
    text-align: center;
    padding: 4rem 1rem 3rem;
    margin-bottom: 3rem;
    position: relative;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(20, 184, 166, 0.1) 100%);
    border-bottom: 1px solid rgba(6, 182, 212, 0.1);
}

.category-hero h1 {
    font-size: 3.5rem;
    font-weight: 900;
    background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 50%, #0891b2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 1rem;
    letter-spacing: -0.02em;
    text-shadow: 0 4px 20px rgba(6, 182, 212, 0.3);
}

.category-hero p {
    font-size: 1.25rem;
    color: #64748b;
    max-width: 700px;
    margin: 0 auto;
    line-height: 1.6;
}

.documents-container {
    max-width: 1600px;
    margin: 0 auto;
    padding: 0 2rem 4rem;
    display: grid;
    grid-template-columns: 320px 1fr;
    gap: 2.5rem;
    position: relative;
    z-index: 1;
}

/* Sidebar Filters Ultra Moderne */
.documents-filters {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    padding: 2rem;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1), 0 0 0 1px rgba(6, 182, 212, 0.1);
    border: 1px solid rgba(6, 182, 212, 0.2);
    position: sticky;
    top: 80px;
    align-self: start;
    max-height: calc(100vh - 100px);
    overflow-y: auto;
    overflow-x: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 10;
}

.documents-filters::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #06b6d4, #14b8a6, #06b6d4);
    background-size: 200% 100%;
    animation: shimmer 3s linear infinite;
}

@keyframes shimmer {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

.documents-filters::-webkit-scrollbar {
    width: 6px;
}

.documents-filters::-webkit-scrollbar-track {
    background: rgba(6, 182, 212, 0.1);
    border-radius: 10px;
}

.documents-filters::-webkit-scrollbar-thumb {
    background: linear-gradient(180deg, #06b6d4, #14b8a6);
    border-radius: 10px;
}

.documents-filters h3 {
    font-size: 1.5rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.documents-filters h3 i {
    color: #06b6d4;
    font-size: 1.25rem;
}

.documents-filters a {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.875rem 1.5rem;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.1));
    color: #06b6d4;
    text-decoration: none;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.9375rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 2px solid rgba(6, 182, 212, 0.2);
    width: 100%;
    justify-content: center;
}

.documents-filters a:hover {
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    color: white;
    border-color: transparent;
    transform: translateX(-4px);
    box-shadow: 0 8px 24px rgba(6, 182, 212, 0.4);
}

.documents-filters a i {
    transition: transform 0.3s ease;
}

.documents-filters a:hover i {
    transform: translateX(-4px);
}

/* Documents Grid */
.documents-main {
    position: relative;
}

.documents-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 6rem 2rem;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    border: 1px solid rgba(6, 182, 212, 0.2);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
    margin-top: 2rem;
}

.empty-state i {
    font-size: 5rem;
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 2rem;
    display: block;
}

.empty-state p {
    color: #64748b;
    font-size: 1.25rem;
    font-weight: 600;
}

/* Pagination */
.pagination-wrapper {
    margin-top: 3rem;
    display: flex;
    justify-content: center;
}

@media (max-width: 1200px) {
    .documents-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 968px) {
    .documents-container {
        grid-template-columns: 1fr;
        padding: 0 1rem 2rem;
    }
    
    .documents-filters {
        position: sticky;
        top: 80px;
        max-height: calc(100vh - 100px);
        margin-bottom: 2rem;
    }
    
    .category-hero h1 {
        font-size: 2.5rem;
    }
    
    .documents-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 640px) {
    .category-hero {
        padding: 3rem 1rem 2rem;
    }
    
    .category-hero h1 {
        font-size: 2rem;
    }
    
    .category-hero p {
        font-size: 1rem;
    }
    
    .documents-grid {
        grid-template-columns: 1fr;
    }
}

/* Dark Mode */
body.dark-mode .documents-page {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
}

body.dark-mode .category-hero {
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.15) 0%, rgba(20, 184, 166, 0.15) 100%);
    border-bottom-color: rgba(6, 182, 212, 0.3);
}

body.dark-mode .category-hero h1 {
    background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 50%, #0891b2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    text-shadow: 0 0 40px rgba(6, 182, 212, 0.5);
}

body.dark-mode .category-hero p {
    color: rgba(255, 255, 255, 0.8);
}

body.dark-mode .documents-filters {
    background: rgba(30, 41, 59, 0.9);
    border-color: rgba(6, 182, 212, 0.3);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(6, 182, 212, 0.2);
}

body.dark-mode .documents-filters h3 {
    color: white;
}

body.dark-mode .documents-filters a {
    background: rgba(6, 182, 212, 0.15);
    border-color: rgba(6, 182, 212, 0.3);
    color: #06b6d4;
}

body.dark-mode .documents-filters a:hover {
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    color: white;
    box-shadow: 0 8px 24px rgba(6, 182, 212, 0.5);
}

body.dark-mode .empty-state {
    background: rgba(30, 41, 59, 0.9);
    border-color: rgba(6, 182, 212, 0.3);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
}

body.dark-mode .empty-state p {
    color: rgba(255, 255, 255, 0.9);
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
    <div class="category-hero">
        <h1>{{ $category->name }}</h1>
        @if($category->description)
            <p>{{ $category->description }}</p>
        @endif
    </div>

    <div class="documents-container">
        <aside class="documents-filters">
            <h3>
                <i class="fas fa-filter"></i> Navigation
            </h3>
            <a href="{{ route('documents.index') }}">
                <i class="fas fa-arrow-left"></i> Retour aux documents
            </a>
        </aside>

        <main class="documents-main">
            @if($documents->count() > 0)
                <div class="documents-grid">
                    @foreach($documents as $document)
                        @include('documents.partials.card', ['document' => $document])
                    @endforeach
                </div>

                <div class="pagination-wrapper">
                    {{ $documents->links() }}
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>Aucun document dans cette catégorie.</p>
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
    
    function forceStickySidebar() {
        const sidebar = document.querySelector('.documents-filters');
        if (!sidebar) return;
        
        // Forcer les propriétés sticky avec !important via style inline
        sidebar.style.setProperty('position', 'sticky', 'important');
        sidebar.style.setProperty('top', '80px', 'important');
        sidebar.style.setProperty('align-self', 'start', 'important');
        sidebar.style.setProperty('max-height', 'calc(100vh - 100px)', 'important');
        sidebar.style.setProperty('overflow-y', 'auto', 'important');
        
        // S'assurer que les parents n'ont pas overflow qui bloque
        const parents = [document.body, document.documentElement, document.querySelector('.documents-page'), document.querySelector('.documents-container')];
        parents.forEach(parent => {
            if (parent) {
                parent.style.setProperty('overflow', 'visible', 'important');
                parent.style.setProperty('overflow-x', 'visible', 'important');
                parent.style.setProperty('overflow-y', 'visible', 'important');
            }
        });
    }
    
    // Exécuter immédiatement et après chargement
    forceStickySidebar();
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', forceStickySidebar);
    }
    
    // Réexécuter après un court délai pour s'assurer que tout est chargé
    setTimeout(forceStickySidebar, 100);
    setTimeout(forceStickySidebar, 500);
    
    // Réexécuter sur scroll et resize
    window.addEventListener('scroll', forceStickySidebar, { passive: true });
    window.addEventListener('resize', forceStickySidebar);
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
