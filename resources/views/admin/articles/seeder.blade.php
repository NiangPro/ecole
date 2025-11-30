@extends('admin.layout')

@section('title', 'Générateur d\'Articles Ultra Moderne')

@section('styles')
<style>
    /* Fonts chargées via preload dans admin.layout - pas de @import bloquant */
    
    .seeder-hero {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.15) 0%, rgba(20, 184, 166, 0.15) 50%, rgba(139, 92, 246, 0.15) 100%);
        border: 2px solid rgba(6, 182, 212, 0.4);
        border-radius: 32px;
        padding: 60px 50px;
        margin-bottom: 50px;
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(20px);
        box-shadow: 0 20px 60px rgba(6, 182, 212, 0.2), inset 0 1px 0 rgba(255, 255, 255, 0.1);
    }
    
    .seeder-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(6, 182, 212, 0.2) 0%, transparent 70%);
        animation: rotate 25s linear infinite;
    }
    
    .seeder-hero::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -30%;
        width: 150%;
        height: 150%;
        background: radial-gradient(circle, rgba(139, 92, 246, 0.15) 0%, transparent 70%);
        animation: rotate 30s linear infinite reverse;
    }
    
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    .seeder-hero-content {
        position: relative;
        z-index: 1;
    }
    
    .seeder-hero h1 {
        font-family: 'Poppins', sans-serif;
        font-size: 3.5rem;
        font-weight: 900;
        background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 30%, #8b5cf6 60%, #ec4899 100%);
        background-size: 300% auto;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: shimmer 4s linear infinite;
        margin-bottom: 15px;
        text-shadow: 0 0 40px rgba(6, 182, 212, 0.3);
    }
    
    @keyframes shimmer {
        0%, 100% { background-position: 0% center; }
        50% { background-position: 100% center; }
    }
    
    .seeder-hero p {
        font-size: 1.3rem;
        color: rgba(255, 255, 255, 0.85);
        font-weight: 500;
    }
    
    .stat-card-ultra {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.8) 0%, rgba(30, 41, 59, 0.8) 100%);
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 24px;
        padding: 30px;
        position: relative;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(10px);
    }
    
    .stat-card-ultra::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #06b6d4, #14b8a6, #8b5cf6);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.4s ease;
    }
    
    .stat-card-ultra:hover {
        transform: translateY(-8px);
        border-color: rgba(6, 182, 212, 0.6);
        box-shadow: 0 20px 40px rgba(6, 182, 212, 0.3), 0 0 60px rgba(6, 182, 212, 0.1);
    }
    
    .stat-card-ultra:hover::before {
        transform: scaleX(1);
    }
    
    .stat-icon-ultra {
        width: 70px;
        height: 70px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-bottom: 20px;
        position: relative;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
        border: 2px solid rgba(6, 182, 212, 0.3);
    }
    
    .stat-icon-ultra::after {
        content: '';
        position: absolute;
        inset: -2px;
        border-radius: 20px;
        padding: 2px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6, #8b5cf6);
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        opacity: 0;
        transition: opacity 0.4s;
    }
    
    .stat-card-ultra:hover .stat-icon-ultra::after {
        opacity: 1;
    }
    
    .stat-number-ultra {
        font-family: 'Poppins', sans-serif;
        font-size: 2.5rem;
        font-weight: 900;
        background: linear-gradient(135deg, #ffffff 0%, rgba(255, 255, 255, 0.8) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 8px;
    }
    
    .stat-label-ultra {
        font-size: 1rem;
        color: rgba(255, 255, 255, 0.7);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .form-section-ultra {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(30, 41, 59, 0.9) 100%);
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 32px;
        padding: 50px;
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(20px);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.1);
    }
    
    .form-section-ultra::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(6, 182, 212, 0.1) 0%, transparent 70%);
        animation: pulse 8s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.8; }
    }
    
    .section-title-ultra {
        font-family: 'Poppins', sans-serif;
        font-size: 1.8rem;
        font-weight: 800;
        color: #06b6d4;
        margin-bottom: 35px;
        display: flex;
        align-items: center;
        gap: 15px;
        position: relative;
        z-index: 1;
    }
    
    .section-title-ultra i {
        font-size: 2rem;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .form-group-ultra {
        margin-bottom: 30px;
        position: relative;
        z-index: 1;
    }
    
    .form-label-ultra {
        display: block;
        font-size: 1.1rem;
        font-weight: 700;
        color: #06b6d4;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .form-label-ultra i {
        font-size: 1.2rem;
    }
    
    .form-input-ultra {
        width: 100%;
        padding: 18px 24px;
        background: rgba(15, 23, 42, 0.8);
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 16px;
        color: white;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        font-family: 'Inter', sans-serif;
    }
    
    .form-input-ultra:focus {
        outline: none;
        border-color: #06b6d4;
        box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.2), 0 0 30px rgba(6, 182, 212, 0.3);
        background: rgba(15, 23, 42, 0.95);
        transform: translateY(-2px);
    }
    
    .form-input-ultra::placeholder {
        color: rgba(255, 255, 255, 0.4);
    }
    
    .form-help-ultra {
        margin-top: 8px;
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.6);
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .btn-seed-ultra {
        width: 100%;
        padding: 22px 40px;
        background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 50%, #8b5cf6 100%);
        background-size: 200% auto;
        border: none;
        border-radius: 20px;
        color: white;
        font-size: 1.3rem;
        font-weight: 800;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(6, 182, 212, 0.4);
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .btn-seed-ultra::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s;
    }
    
    .btn-seed-ultra:hover {
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 20px 50px rgba(6, 182, 212, 0.6), 0 0 80px rgba(139, 92, 246, 0.4);
        background-position: right center;
    }
    
    .btn-seed-ultra:hover::before {
        left: 100%;
    }
    
    .btn-seed-ultra:active {
        transform: translateY(-2px) scale(0.98);
    }
    
    .btn-seed-ultra i {
        font-size: 1.5rem;
        animation: bounce 2s infinite;
    }
    
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }
    
    .info-card-ultra {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.15) 0%, rgba(139, 92, 246, 0.15) 100%);
        border: 2px solid rgba(6, 182, 212, 0.4);
        border-radius: 24px;
        padding: 35px;
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(10px);
    }
    
    .info-card-ultra::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.05) 0%, rgba(139, 92, 246, 0.05) 100%);
        opacity: 0;
        transition: opacity 0.3s;
    }
    
    .info-card-ultra:hover::before {
        opacity: 1;
    }
    
    .alert-ultra {
        border-radius: 20px;
        padding: 25px 30px;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 15px;
        font-size: 1.1rem;
        font-weight: 600;
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(10px);
        animation: slideIn 0.5s ease-out;
    }
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .alert-success-ultra {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.2) 0%, rgba(16, 185, 129, 0.2) 100%);
        border: 2px solid rgba(34, 197, 94, 0.5);
        color: #22c55e;
        box-shadow: 0 10px 30px rgba(34, 197, 94, 0.2);
    }
    
    .alert-error-ultra {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.2) 0%, rgba(220, 38, 38, 0.2) 100%);
        border: 2px solid rgba(239, 68, 68, 0.5);
        color: #ef4444;
        box-shadow: 0 10px 30px rgba(239, 68, 68, 0.2);
    }
    
    .alert-ultra i {
        font-size: 1.5rem;
    }
    
    @media (max-width: 768px) {
        .seeder-hero h1 {
            font-size: 2.5rem;
        }
        
        .form-section-ultra {
            padding: 30px 20px;
        }
        
        .stat-card-ultra {
            padding: 20px;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-6">
    <!-- Hero Section Ultra Moderne -->
    <div class="seeder-hero">
        <div class="seeder-hero-content">
            <h1>
                <i class="fas fa-seedling"></i>
                Générateur d'Articles Ultra Moderne
            </h1>
            <p>Recherchez les articles les plus récents sur le web, reformulez-les avec optimisation SEO et visibilité, et créez automatiquement des articles d'emploi avec images illustratives</p>
        </div>
    </div>

    <!-- Alertes -->
    @if(session('success'))
    <div class="alert-ultra alert-success-ultra">
        <i class="fas fa-check-circle"></i>
        <div>{!! nl2br(e(session('success'))) !!}</div>
    </div>
    @endif

    @if(session('error'))
    <div class="alert-ultra alert-error-ultra">
        <i class="fas fa-exclamation-circle"></i>
        <div>{{ session('error') }}</div>
    </div>
    @endif

    <!-- Statistiques Ultra Modernes -->
    <div class="form-section-ultra mb-8">
        <h4 class="section-title-ultra">
            <i class="fas fa-chart-line"></i>
            Statistiques en Temps Réel
        </h4>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px;">
            <div class="stat-card-ultra">
                <div class="stat-icon-ultra" style="color: #06b6d4;">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-number-ultra">{{ number_format($stats['total']) }}</div>
                <div class="stat-label-ultra">Total Articles</div>
            </div>
            <div class="stat-card-ultra">
                <div class="stat-icon-ultra" style="color: #22c55e;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-number-ultra">{{ number_format($stats['published']) }}</div>
                <div class="stat-label-ultra">Publiés</div>
            </div>
            <div class="stat-card-ultra">
                <div class="stat-icon-ultra" style="color: #f59e0b;">
                    <i class="fas fa-edit"></i>
                </div>
                <div class="stat-number-ultra">{{ number_format($stats['draft']) }}</div>
                <div class="stat-label-ultra">Brouillons</div>
            </div>
            <div class="stat-card-ultra">
                <div class="stat-icon-ultra" style="color: #8b5cf6;">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stat-number-ultra">{{ number_format($stats['today']) }}</div>
                <div class="stat-label-ultra">Aujourd'hui</div>
            </div>
            <div class="stat-card-ultra">
                <div class="stat-icon-ultra" style="color: #ec4899;">
                    <i class="fas fa-calendar-week"></i>
                </div>
                <div class="stat-number-ultra">{{ number_format($stats['this_week']) }}</div>
                <div class="stat-label-ultra">Cette Semaine</div>
            </div>
            <div class="stat-card-ultra">
                <div class="stat-icon-ultra" style="color: #14b8a6;">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stat-number-ultra">{{ number_format($stats['this_month']) }}</div>
                <div class="stat-label-ultra">Ce Mois</div>
            </div>
        </div>
    </div>

    <!-- Formulaire Ultra Moderne -->
    <div class="form-section-ultra mb-8">
        <h4 class="section-title-ultra">
            <i class="fas fa-magic"></i>
            Générer des Articles depuis le Web
        </h4>
        <p style="color: rgba(255, 255, 255, 0.8); margin-bottom: 30px; font-size: 1.05rem; line-height: 1.7; position: relative; z-index: 1;">
            <i class="fas fa-info-circle" style="color: #06b6d4; margin-right: 8px;"></i>
            Le système recherche automatiquement les articles les plus récents sur le web, les reformule en tenant compte du SEO et de la visibilité, 
            et génère des articles avec des images illustratives. Les articles sont optimisés pour un score SEO et de lisibilité élevés.
        </p>
        <form action="{{ route('admin.jobs.seeder.seed') }}" method="POST" id="seederForm">
            @csrf
            <div class="form-group-ultra">
                <label for="count" class="form-label-ultra">
                    <i class="fas fa-hashtag"></i>
                    Nombre d'articles à créer
                </label>
                <input type="number" 
                       id="count" 
                       name="count" 
                       value="5" 
                       min="1" 
                       max="50" 
                       required
                       class="form-input-ultra"
                       placeholder="Entrez le nombre d'articles (1-50)">
                <div class="form-help-ultra">
                    <i class="fas fa-info-circle"></i>
                    Entre 1 et 50 articles peuvent être créés en une seule fois
                </div>
            </div>

            <div class="form-group-ultra">
                <label for="category_id" class="form-label-ultra">
                    <i class="fas fa-folder"></i>
                    Catégorie (optionnel)
                </label>
                <select id="category_id" 
                        name="category_id" 
                        class="form-input-ultra">
                    <option value="">Toutes les catégories actives</option>
                    @foreach(\App\Models\Category::where('is_active', true)->get() as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <div class="form-help-ultra">
                    <i class="fas fa-lightbulb"></i>
                    Si non spécifié, les articles seront répartis équitablement sur toutes les catégories actives
                </div>
            </div>

            <div class="form-group-ultra">
                <label for="days" class="form-label-ultra">
                    <i class="fas fa-calendar-minus"></i>
                    Date de publication (jours dans le passé)
                </label>
                <input type="number" 
                       id="days" 
                       name="days" 
                       value="3" 
                       min="0" 
                       max="30" 
                       class="form-input-ultra"
                       placeholder="Nombre de jours dans le passé">
                <div class="form-help-ultra">
                    <i class="fas fa-clock"></i>
                    Les articles seront datés de X jours dans le passé (0 = aujourd'hui, max 30 jours)
                </div>
            </div>

            <button type="submit" class="btn-seed-ultra">
                <i class="fas fa-seedling"></i>
                <span>Générer les Articles</span>
            </button>
        </form>
    </div>

    <!-- Information Planification -->
    <div class="info-card-ultra">
        <h4 class="section-title-ultra" style="margin-bottom: 25px;">
            <i class="fas fa-clock"></i>
            Planification Automatique
        </h4>
        <div style="position: relative; z-index: 1;">
            <p style="color: rgba(255, 255, 255, 0.95); margin-bottom: 20px; font-size: 1.1rem; line-height: 1.8;">
                <i class="fas fa-info-circle text-cyan-400" style="margin-right: 10px;"></i>
                <strong style="color: #06b6d4;">Les articles sont créés automatiquement tous les jours à 4h du matin.</strong>
            </p>
            <div style="background: rgba(0, 0, 0, 0.3); border-radius: 12px; padding: 20px; margin-bottom: 15px;">
                <p style="color: rgba(255, 255, 255, 0.8); margin-bottom: 10px; font-family: 'Courier New', monospace; font-size: 0.95rem;">
                    <span style="color: #06b6d4;">$</span> php artisan articles:seed --count=5 --days=3
                </p>
            </div>
            <p style="color: rgba(255, 255, 255, 0.7); font-size: 0.95rem;">
                <i class="fas fa-code text-purple-400" style="margin-right: 8px;"></i>
                Pour modifier cette planification, éditez le fichier <code style="background: rgba(0, 0, 0, 0.4); padding: 4px 8px; border-radius: 6px; color: #8b5cf6;">routes/console.php</code>
            </p>
        </div>
    </div>
</div>

<script>
    // Animation du formulaire
    document.getElementById('seederForm').addEventListener('submit', function(e) {
        const btn = this.querySelector('.btn-seed-ultra');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Génération en cours...</span>';
        btn.disabled = true;
        btn.style.opacity = '0.7';
    });
</script>
@endsection
