@extends('admin.layout')

@section('title', 'Détails Publicité')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@400;500;600;700;800;900&display=swap');
    
    .ad-details-wrapper {
        max-width: 1600px;
        margin: 0 auto;
    }
    
    .ad-hero {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(20, 184, 166, 0.1) 100%);
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 24px;
        padding: 50px;
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
    }
    
    .ad-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(6, 182, 212, 0.1) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }
    
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    .ad-hero-content {
        position: relative;
        z-index: 1;
    }
    
    .ad-hero h1 {
        font-family: 'Poppins', sans-serif;
        font-size: 3rem;
        font-weight: 900;
        background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 50%, #06b6d4 100%);
        background-size: 200% auto;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: shimmer 3s linear infinite;
        margin-bottom: 15px;
    }
    
    @keyframes shimmer {
        to { background-position: 200% center; }
    }
    
    .ad-hero p {
        font-size: 1.1rem;
        color: rgba(255, 255, 255, 0.7);
        font-weight: 400;
        transition: color 0.3s ease;
    }
    
    body.light-mode .ad-hero {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.05) 0%, rgba(20, 184, 166, 0.05) 100%);
        border-color: rgba(6, 182, 212, 0.4);
    }
    
    body.light-mode .ad-hero p {
        color: rgba(30, 41, 59, 0.8);
    }
    
    .ad-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
    }
    
    .ad-card {
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 20px;
        padding: 35px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
        margin-bottom: 25px;
    }
    
    body.light-mode .ad-card {
        background: rgba(255, 255, 255, 0.8);
        border-color: rgba(6, 182, 212, 0.3);
    }
    
    body.light-mode .ad-card:hover {
        border-color: rgba(6, 182, 212, 0.5);
        box-shadow: 0 15px 40px rgba(6, 182, 212, 0.15);
    }
    
    .ad-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(6, 182, 212, 0.1), transparent);
        transition: left 0.6s;
    }
    
    .ad-card:hover::before {
        left: 100%;
    }
    
    .ad-card:hover {
        border-color: rgba(6, 182, 212, 0.5);
        box-shadow: 0 15px 40px rgba(6, 182, 212, 0.2);
        transform: translateY(-5px);
    }
    
    .card-header-modern {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
        padding-bottom: 20px;
        border-bottom: 2px solid rgba(6, 182, 212, 0.2);
    }
    
    .card-icon-modern {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #06b6d4;
        border: 1px solid rgba(6, 182, 212, 0.3);
    }
    
    .card-title-modern {
        font-family: 'Poppins', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #06b6d4;
        margin: 0;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    
    .info-item {
        padding: 18px;
        background: rgba(10, 10, 26, 0.6);
        border-radius: 12px;
        border: 1px solid rgba(6, 182, 212, 0.2);
        transition: all 0.3s ease;
    }
    
    body.light-mode .info-item {
        background: rgba(255, 255, 255, 0.9);
        border-color: rgba(6, 182, 212, 0.3);
    }
    
    .info-item:hover {
        border-color: rgba(6, 182, 212, 0.4);
        background: rgba(10, 10, 26, 0.8);
        transform: translateX(5px);
    }
    
    body.light-mode .info-item:hover {
        background: rgba(255, 255, 255, 1);
        border-color: rgba(6, 182, 212, 0.5);
    }
    
    .info-label {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.6);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        font-weight: 600;
        transition: color 0.3s ease;
    }
    
    body.light-mode .info-label {
        color: rgba(30, 41, 59, 0.7);
    }
    
    .info-value {
        font-size: 1.1rem;
        color: #fff;
        font-weight: 700;
        transition: color 0.3s ease;
    }
    
    body.light-mode .info-value {
        color: #1e293b;
    }
    
    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .badge-active {
        background: rgba(34, 197, 94, 0.2);
        color: #22c55e;
        border: 1px solid rgba(34, 197, 94, 0.4);
    }
    
    .badge-inactive {
        background: rgba(156, 163, 175, 0.2);
        color: #9ca3af;
        border: 1px solid rgba(156, 163, 175, 0.4);
    }
    
    .badge-position {
        background: rgba(6, 182, 212, 0.2);
        color: #06b6d4;
        border: 1px solid rgba(6, 182, 212, 0.4);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .preview-container {
        background: rgba(10, 10, 26, 0.8);
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 16px;
        padding: 30px;
        margin-top: 25px;
        text-align: center;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    body.light-mode .preview-container {
        background: rgba(255, 255, 255, 0.9);
        border-color: rgba(6, 182, 212, 0.4);
    }
    
    .preview-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #06b6d4, #14b8a6, #06b6d4);
        background-size: 200% auto;
        animation: shimmer 3s linear infinite;
    }
    
    .preview-image {
        max-width: 100%;
        max-height: 500px;
        border-radius: 12px;
        border: 2px solid rgba(6, 182, 212, 0.2);
        transition: all 0.3s ease;
    }
    
    .preview-image:hover {
        border-color: rgba(6, 182, 212, 0.5);
        transform: scale(1.02);
    }
    
    .code-block {
        background: rgba(0, 0, 0, 0.6);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 12px;
        padding: 20px;
        overflow-x: auto;
        font-family: 'Courier New', monospace;
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.9);
        line-height: 1.6;
        position: relative;
        transition: all 0.3s ease;
    }
    
    body.light-mode .code-block {
        background: rgba(255, 255, 255, 0.9);
        border-color: rgba(6, 182, 212, 0.3);
        color: rgba(30, 41, 59, 0.9);
    }
    
    .code-block::before {
        content: '</>';
        position: absolute;
        top: 10px;
        right: 15px;
        color: rgba(6, 182, 212, 0.5);
        font-size: 1.2rem;
        font-weight: 700;
    }
    
    .stats-card-modern {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.15), rgba(20, 184, 166, 0.15));
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 25px;
        position: relative;
        overflow: hidden;
    }
    
    .stats-card-modern::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(6, 182, 212, 0.1) 0%, transparent 70%);
        animation: rotate 15s linear infinite;
    }
    
    .stats-card-content {
        position: relative;
        z-index: 1;
    }
    
    .stats-title-modern {
        font-family: 'Poppins', sans-serif;
        font-size: 1.3rem;
        font-weight: 700;
        color: #06b6d4;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .stats-grid-modern {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }
    
    .stat-box-modern {
        text-align: center;
        padding: 25px;
        background: rgba(10, 10, 26, 0.7);
        border-radius: 16px;
        border: 1px solid rgba(6, 182, 212, 0.2);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    body.light-mode .stat-box-modern {
        background: rgba(255, 255, 255, 0.9);
        border-color: rgba(6, 182, 212, 0.3);
    }
    
    .stat-box-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #06b6d4, #14b8a6);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }
    
    .stat-box-modern:hover::before {
        transform: scaleX(1);
    }
    
    .stat-box-modern:hover {
        border-color: rgba(6, 182, 212, 0.5);
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(6, 182, 212, 0.2);
    }
    
    .stat-value-modern {
        font-size: 2.5rem;
        font-weight: 900;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 10px;
        line-height: 1;
    }
    
    .stat-label-modern {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.7);
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
        transition: color 0.3s ease;
    }
    
    body.light-mode .stat-label-modern {
        color: rgba(30, 41, 59, 0.7);
    }
    
    .ctr-progress {
        width: 100%;
        height: 10px;
        background: rgba(10, 10, 26, 0.8);
        border-radius: 10px;
        overflow: hidden;
        margin-top: 15px;
        border: 1px solid rgba(6, 182, 212, 0.2);
        transition: all 0.3s ease;
    }
    
    body.light-mode .ctr-progress {
        background: rgba(241, 245, 249, 0.8);
        border-color: rgba(6, 182, 212, 0.3);
    }
    
    .ctr-progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #ef4444, #f59e0b, #22c55e);
        border-radius: 10px;
        transition: width 0.5s ease;
        position: relative;
        overflow: hidden;
    }
    
    .ctr-progress-bar::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        animation: shimmer 2s infinite;
    }
    
    .action-buttons-modern {
        display: flex;
        gap: 15px;
        margin-top: 40px;
        padding-top: 30px;
        border-top: 2px solid rgba(6, 182, 212, 0.2);
        transition: border-color 0.3s ease;
    }
    
    body.light-mode .action-buttons-modern {
        border-top-color: rgba(6, 182, 212, 0.3);
    }
    
    .btn-edit-modern {
        flex: 1;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border: none;
        padding: 14px 28px;
        border-radius: 12px;
        color: #000;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
        font-family: 'Inter', sans-serif;
        text-decoration: none;
    }
    
    .btn-edit-modern:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(6, 182, 212, 0.5);
    }
    
    .btn-back-modern {
        padding: 14px 28px;
        background: rgba(100, 100, 100, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        color: #fff;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        font-family: 'Inter', sans-serif;
    }
    
    body.light-mode .btn-back-modern {
        background: rgba(148, 163, 184, 0.2);
        border-color: rgba(148, 163, 184, 0.4);
        color: #1e293b;
    }
    
    .btn-back-modern:hover {
        background: rgba(100, 100, 100, 0.3);
        border-color: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
    }
    
    body.light-mode .btn-back-modern:hover {
        background: rgba(148, 163, 184, 0.3);
        border-color: rgba(148, 163, 184, 0.6);
    }
    
    .dates-card {
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 25px;
        transition: all 0.3s ease;
    }
    
    body.light-mode .dates-card {
        background: rgba(255, 255, 255, 0.8);
        border-color: rgba(6, 182, 212, 0.3);
    }
    
    .date-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px;
        background: rgba(10, 10, 26, 0.6);
        border-radius: 12px;
        margin-bottom: 12px;
        border: 1px solid rgba(6, 182, 212, 0.2);
        transition: all 0.3s ease;
    }
    
    body.light-mode .date-item {
        background: rgba(255, 255, 255, 0.9);
        border-color: rgba(6, 182, 212, 0.3);
    }
    
    .date-item:hover {
        border-color: rgba(6, 182, 212, 0.4);
        transform: translateX(5px);
    }
    
    body.light-mode .date-item:hover {
        border-color: rgba(6, 182, 212, 0.5);
    }
    
    .date-item:last-child {
        margin-bottom: 0;
    }
    
    .date-label {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.7);
        font-weight: 600;
        transition: color 0.3s ease;
    }
    
    body.light-mode .date-label {
        color: rgba(30, 41, 59, 0.7);
    }
    
    .date-value {
        font-size: 1rem;
        color: #06b6d4;
        font-weight: 700;
    }
    
    .ad-description-text {
        color: rgba(255, 255, 255, 0.8);
        transition: color 0.3s ease;
    }
    
    body.light-mode .ad-description-text {
        color: rgba(30, 41, 59, 0.9);
    }
    
    body.light-mode .stats-card-modern {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.1));
        border-color: rgba(6, 182, 212, 0.4);
    }
    
    body.light-mode .card-title-modern {
        color: #06b6d4;
    }
    
    body.light-mode span[style*="color: rgba(255, 255, 255, 0.7)"] {
        color: rgba(30, 41, 59, 0.7) !important;
    }
    
    body.light-mode div[style*="background: rgba(10, 10, 26, 0.6)"] {
        background: rgba(255, 255, 255, 0.9) !important;
    }
    
    body.light-mode div[style*="border-top: 2px solid rgba(6, 182, 212, 0.2)"] {
        border-top-color: rgba(6, 182, 212, 0.3) !important;
    }
    
    @media (max-width: 1200px) {
        .ad-grid {
            grid-template-columns: 1fr;
        }
        
        .ad-hero {
            padding: 35px;
        }
        
        .ad-hero h1 {
            font-size: 2.2rem;
        }
        
        .stats-grid-modern {
            grid-template-columns: 1fr;
        }
        
        .info-grid {
            grid-template-columns: 1fr;
        }
    }
    
    @media (max-width: 768px) {
        .ad-hero {
            padding: 25px;
        }
        
        .ad-hero h1 {
            font-size: 1.8rem;
        }
        
        .ad-card {
            padding: 25px;
        }
        
        .action-buttons-modern {
            flex-direction: column;
        }
    }
</style>
@endsection

@section('content')
<div class="ad-details-wrapper">
    <!-- Hero Section -->
    <div class="ad-hero">
        <div class="ad-hero-content">
            <h1><i class="fas fa-ad mr-3"></i>{{ $ad->name }}</h1>
            <p>Détails et statistiques de la publicité</p>
        </div>
    </div>
    
    <div class="ad-grid">
        <!-- Colonne principale -->
        <div>
            <!-- Informations générales -->
            <div class="ad-card">
                <div class="card-header-modern">
                    <div class="card-icon-modern">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <h2 class="card-title-modern">Informations Générales</h2>
                </div>
                
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Nom</div>
                        <div class="info-value">{{ $ad->name }}</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Position</div>
                        <div class="info-value">
                            <span class="badge-position">{{ ucfirst($ad->position) }}</span>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Statut</div>
                        <div class="info-value">
                            <span class="badge-status {{ $ad->status === 'active' ? 'badge-active' : 'badge-inactive' }}">
                                <i class="fas fa-{{ $ad->status === 'active' ? 'check-circle' : 'times-circle' }}"></i>
                                {{ $ad->status === 'active' ? 'Actif' : 'Inactif' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Ordre d'affichage</div>
                        <div class="info-value">{{ $ad->order }}</div>
                    </div>
                    
                    @if($ad->location)
                    <div class="info-item">
                        <div class="info-label">Emplacement spécifique</div>
                        <div class="info-value">
                            <span class="badge-position">
                                {{ $ad->location === 'homepage_after_exercises' ? '🏠 Page d\'accueil' : '📄 Articles' }}
                            </span>
                        </div>
                    </div>
                    @endif
                </div>
                
                @if($ad->description)
                <div style="margin-top: 25px; padding-top: 25px; border-top: 2px solid rgba(6, 182, 212, 0.2);">
                    <div class="info-label" style="margin-bottom: 12px;">Description</div>
                    <p style="color: rgba(255, 255, 255, 0.8); line-height: 1.8; font-size: 1rem; transition: color 0.3s ease;" class="ad-description-text">{{ $ad->description }}</p>
                </div>
                @endif
                
                @if($ad->start_date || $ad->end_date)
                <div style="margin-top: 25px; padding-top: 25px; border-top: 2px solid rgba(6, 182, 212, 0.2);">
                    <div class="info-label" style="margin-bottom: 15px;">Dates</div>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        @if($ad->start_date)
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background: rgba(10, 10, 26, 0.6); border-radius: 10px; border: 1px solid rgba(6, 182, 212, 0.2);">
                            <span style="color: rgba(255, 255, 255, 0.7); font-weight: 600; font-size: 0.9rem;">Date de début</span>
                            <span style="color: #06b6d4; font-weight: 700;">{{ $ad->start_date->format('d/m/Y') }}</span>
                        </div>
                        @endif
                        @if($ad->end_date)
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background: rgba(10, 10, 26, 0.6); border-radius: 10px; border: 1px solid rgba(6, 182, 212, 0.2);">
                            <span style="color: rgba(255, 255, 255, 0.7); font-weight: 600; font-size: 0.9rem;">Date de fin</span>
                            <span style="color: #06b6d4; font-weight: 700;">{{ $ad->end_date->format('d/m/Y') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Aperçu de l'image -->
            @if($ad->image)
            <div class="ad-card">
                <div class="card-header-modern">
                    <div class="card-icon-modern">
                        <i class="fas fa-image"></i>
                    </div>
                    <h2 class="card-title-modern">Aperçu de la Publicité</h2>
                </div>
                
                <div class="preview-container">
                    <img src="{{ $ad->image_type === 'internal' ? \Illuminate\Support\Facades\Storage::url($ad->image) : $ad->image }}" 
                         alt="{{ $ad->name }}" 
                         class="preview-image"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <div style="display: none; padding: 40px; color: rgba(255, 255, 255, 0.5);">
                        <i class="fas fa-image text-5xl mb-4"></i>
                        <p>Image non disponible</p>
                    </div>
                </div>
                
                @if($ad->link_url)
                <div style="margin-top: 20px; text-align: center;">
                    <a href="{{ $ad->link_url }}" target="_blank" 
                       style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; background: rgba(6, 182, 212, 0.2); border: 2px solid rgba(6, 182, 212, 0.3); border-radius: 12px; color: #06b6d4; text-decoration: none; font-weight: 700; transition: all 0.3s ease;">
                        <i class="fas fa-external-link-alt"></i>
                        Voir l'URL de destination
                    </a>
                </div>
                @endif
            </div>
            @endif
            
            <!-- Code de la publicité -->
            @if($ad->ad_code)
            <div class="ad-card">
                <div class="card-header-modern">
                    <div class="card-icon-modern">
                        <i class="fas fa-code"></i>
                    </div>
                    <h2 class="card-title-modern">Code de la Publicité</h2>
                </div>
                
                <div class="code-block">
                    <pre style="margin: 0; white-space: pre-wrap; word-wrap: break-word;">{{ htmlspecialchars($ad->ad_code) }}</pre>
                </div>
            </div>
            @endif
            
            <!-- Actions -->
            <div class="ad-card">
                <div class="action-buttons-modern" style="margin-top: 0; padding-top: 0; border-top: none;">
                    <a href="{{ route('admin.ads.edit', $ad->id) }}" class="btn-edit-modern">
                        <i class="fas fa-edit"></i>
                        Modifier
                    </a>
                    <a href="{{ route('admin.ads.index') }}" class="btn-back-modern">
                        <i class="fas fa-arrow-left"></i>
                        Retour
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div>
            <!-- Statistiques -->
            <div class="stats-card-modern">
                <div class="stats-card-content">
                    <div class="stats-title-modern">
                        <i class="fas fa-chart-line"></i>
                        <span>Statistiques</span>
                    </div>
                    <div class="stats-grid-modern">
                        <div class="stat-box-modern">
                            <div class="stat-value-modern">{{ number_format($ad->impressions) }}</div>
                            <div class="stat-label-modern">Impressions</div>
                        </div>
                        <div class="stat-box-modern">
                            <div class="stat-value-modern">{{ number_format($ad->clicks) }}</div>
                            <div class="stat-label-modern">Clics</div>
                        </div>
                        <div class="stat-box-modern">
                            <div class="stat-value-modern">
                                {{ $ad->impressions > 0 ? number_format(($ad->clicks / $ad->impressions) * 100, 2) : '0.00' }}%
                            </div>
                            <div class="stat-label-modern">CTR</div>
                            @if($ad->impressions > 0)
                            <div class="ctr-progress">
                                <div class="ctr-progress-bar" style="width: {{ min(($ad->clicks / $ad->impressions) * 100, 100) }}%"></div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Dates -->
            <div class="dates-card">
                <div class="card-header-modern">
                    <div class="card-icon-modern">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h2 class="card-title-modern">Dates</h2>
                </div>
                
                <div class="date-item">
                    <span class="date-label">Créé le</span>
                    <span class="date-value">{{ $ad->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="date-item">
                    <span class="date-label">Modifié le</span>
                    <span class="date-value">{{ $ad->updated_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
