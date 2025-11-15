@extends('admin.layout')

@section('title', 'Nouvelle Publicité')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@400;500;600;700;800;900&display=swap');
    
    .ads-form-wrapper {
        max-width: 1400px;
        margin: 0 auto;
    }
    
    .form-hero {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(20, 184, 166, 0.1) 100%);
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 24px;
        padding: 50px;
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
    }
    
    .form-hero::before {
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
    
    .form-hero-content {
        position: relative;
        z-index: 1;
    }
    
    .form-hero h1 {
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
    
    .form-hero p {
        font-size: 1.1rem;
        color: rgba(255, 255, 255, 0.7);
        font-weight: 400;
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
    }
    
    .form-main {
        display: flex;
        flex-direction: column;
        gap: 25px;
    }
    
    .form-card {
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 20px;
        padding: 35px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
    }
    
    .form-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(6, 182, 212, 0.1), transparent);
        transition: left 0.6s;
    }
    
    .form-card:hover::before {
        left: 100%;
    }
    
    .form-card:hover {
        border-color: rgba(6, 182, 212, 0.5);
        box-shadow: 0 15px 40px rgba(6, 182, 212, 0.2);
        transform: translateY(-3px);
    }
    
    .card-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
        padding-bottom: 20px;
        border-bottom: 2px solid rgba(6, 182, 212, 0.2);
    }
    
    .card-icon {
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
    
    .card-title {
        font-family: 'Poppins', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #fff;
        margin: 0;
    }
    
    .field-group {
        margin-bottom: 25px;
    }
    
    .field-label {
        display: block;
        font-weight: 600;
        color: #06b6d4;
        margin-bottom: 10px;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .field-label .required {
        color: #ef4444;
        font-size: 1.2rem;
    }
    
    .field-input {
        width: 100%;
        padding: 0.7rem 1rem;
        background: rgba(10, 10, 26, 0.8);
        border: 2px solid rgba(6, 182, 212, 0.25);
        border-radius: 10px;
        color: #fff;
        font-size: 0.9rem;
        font-family: 'Inter', sans-serif;
        transition: all 0.3s ease;
    }
    
    .field-input:focus {
        outline: none;
        border-color: #06b6d4;
        background: rgba(10, 10, 26, 0.95);
        box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.15);
    }
    
    .field-input::placeholder {
        color: rgba(255, 255, 255, 0.3);
    }
    
    .field-select {
        width: 100%;
        padding: 16px 20px;
        background: rgba(10, 10, 26, 0.8);
        border: 2px solid rgba(6, 182, 212, 0.25);
        border-radius: 14px;
        color: #fff;
        font-size: 1rem;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        transition: all 0.3s ease;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3E%3Cpath fill='%2306b6d4' d='M8 11L3 6h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 16px center;
        background-size: 16px;
        padding-right: 50px;
    }
    
    .field-select:focus {
        outline: none;
        border-color: #06b6d4;
        background-color: rgba(10, 10, 26, 0.95);
        box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.15);
    }
    
    .field-select option {
        background: #0a0a0f;
        color: #fff;
        padding: 12px;
    }
    
    .field-help {
        color: rgba(255, 255, 255, 0.5);
        font-size: 0.85rem;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .field-help i {
        color: #06b6d4;
        font-size: 0.9rem;
    }
    
    .image-preview-box {
        margin-top: 20px;
        border-radius: 16px;
        overflow: hidden;
        border: 2px solid rgba(6, 182, 212, 0.3);
        background: rgba(10, 10, 26, 0.6);
        position: relative;
    }
    
    .image-preview-box img {
        width: 100%;
        max-height: 450px;
        object-fit: contain;
        display: block;
    }
    
    .sidebar-panel {
        display: flex;
        flex-direction: column;
        gap: 25px;
    }
    
    .sidebar-card {
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 20px;
        padding: 30px;
        transition: all 0.3s ease;
    }
    
    .sidebar-card:hover {
        border-color: rgba(6, 182, 212, 0.4);
    }
    
    .location-warning {
        background: rgba(239, 68, 68, 0.1);
        border: 2px solid rgba(239, 68, 68, 0.3);
        border-radius: 12px;
        padding: 15px;
        margin-top: 15px;
    }
    
    .location-warning i {
        color: #ef4444;
        margin-right: 8px;
    }
    
    .location-warning-text {
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.9rem;
        line-height: 1.6;
    }
    
    .action-buttons {
        display: flex;
        gap: 15px;
        margin-top: 40px;
        padding-top: 30px;
        border-top: 2px solid rgba(6, 182, 212, 0.2);
    }
    
    .btn-save {
        flex: 1;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border: none;
        padding: 18px 32px;
        border-radius: 14px;
        color: #000;
        font-weight: 700;
        font-size: 1.05rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 4px 20px rgba(6, 182, 212, 0.3);
        font-family: 'Inter', sans-serif;
    }
    
    .btn-save:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(6, 182, 212, 0.5);
    }
    
    .btn-cancel {
        padding: 18px 32px;
        background: rgba(100, 100, 100, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.1);
        border-radius: 14px;
        color: #fff;
        font-weight: 600;
        font-size: 1.05rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        font-family: 'Inter', sans-serif;
    }
    
    .btn-cancel:hover {
        background: rgba(100, 100, 100, 0.3);
        border-color: rgba(255, 255, 255, 0.2);
    }
    
    .error-message {
        color: #ef4444;
        font-size: 0.9rem;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .error-message i {
        font-size: 0.85rem;
    }
    
    @media (max-width: 1200px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .form-hero {
            padding: 35px;
        }
        
        .form-hero h1 {
            font-size: 2.2rem;
        }
    }
    
    @media (max-width: 768px) {
        .form-hero {
            padding: 25px;
        }
        
        .form-hero h1 {
            font-size: 1.8rem;
        }
        
        .form-card {
            padding: 25px;
        }
        
        .action-buttons {
            flex-direction: column;
        }
    }
</style>
@endsection

@section('content')
<div class="ads-form-wrapper">
    <!-- Hero Header -->
    <div class="form-hero">
        <div class="form-hero-content">
            <h1><i class="fas fa-magic mr-3"></i>Créer une Publicité</h1>
            <p>Créez une publicité moderne et attrayante pour promouvoir vos services sur le site</p>
        </div>
    </div>

    <form action="{{ route('admin.ads.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-grid">
            <!-- Colonne principale -->
            <div class="form-main">
                <!-- Informations de base -->
                <div class="form-card">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <h2 class="card-title">Informations de base</h2>
                    </div>
                    
                    <div class="field-group">
                        <label class="field-label">
                            <span>Nom de la publicité</span>
                            <span class="required">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="field-input" placeholder="Ex: Bannière Yobali - Accueil">
                        @error('name')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label">Description</label>
                        <textarea name="description" rows="4" class="field-input" 
                                  placeholder="Description optionnelle de la publicité">{{ old('description') }}</textarea>
                    </div>
                </div>

                <!-- Image de la publicité -->
                <div class="form-card">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-image"></i>
                        </div>
                        <h2 class="card-title">Image de la publicité</h2>
                    </div>
                    
                    <div class="field-group">
                        <label class="field-label">
                            <span>Type d'image</span>
                            <span class="required">*</span>
                        </label>
                        <select name="image_type" id="adImageType" class="field-select" required>
                            <option value="external" {{ old('image_type', 'external') === 'external' ? 'selected' : '' }}>Externe (URL)</option>
                            <option value="internal" {{ old('image_type') === 'internal' ? 'selected' : '' }}>Interne (upload)</option>
                        </select>
                    </div>
                    
                    <div id="adInternalImage" style="display: {{ old('image_type') === 'internal' ? 'block' : 'none' }};" class="field-group">
                        <label class="field-label">Fichier image</label>
                        <input type="file" name="image_file" accept="image/*" class="field-input">
                        <p class="field-help">
                            <i class="fas fa-info-circle"></i>
                            Formats acceptés: JPG, PNG, GIF (max 5MB)
                        </p>
                    </div>
                    
                    <div id="adExternalImage" style="display: {{ old('image_type', 'external') === 'external' ? 'block' : 'none' }};" class="field-group">
                        <label class="field-label">URL de l'image</label>
                        <input type="url" name="image_url" value="{{ old('image') }}"
                               class="field-input" placeholder="https://example.com/image.jpg">
                        <p class="field-help">
                            <i class="fas fa-link"></i>
                            Entrez l'URL complète de l'image
                        </p>
                    </div>
                    
                    <div class="field-group">
                        <label class="field-label">
                            <span>URL de destination (lien au clic)</span>
                            <span class="required">*</span>
                        </label>
                        <input type="url" name="link_url" value="{{ old('link_url') }}" required
                               class="field-input" placeholder="https://example.com">
                        <p class="field-help">
                            <i class="fas fa-external-link-alt"></i>
                            URL vers laquelle rediriger lors du clic sur l'image
                        </p>
                    </div>
                    
                    <div id="adImagePreview" class="image-preview-box hidden">
                        <img id="adPreviewImg" src="" alt="Aperçu">
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="sidebar-panel">
                <!-- Paramètres d'affichage -->
                <div class="sidebar-card">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <h2 class="card-title">Paramètres</h2>
                    </div>
                    
                    <div class="field-group">
                        <label class="field-label">
                            <span>Position</span>
                            <span class="required">*</span>
                        </label>
                        <select name="position" id="adPosition" required class="field-select">
                            <option value="sidebar" {{ old('position') === 'sidebar' ? 'selected' : '' }}>Sidebar</option>
                            <option value="content" {{ old('position', 'content') === 'content' ? 'selected' : '' }}>Contenu</option>
                            <option value="header" {{ old('position') === 'header' ? 'selected' : '' }}>En-tête</option>
                            <option value="footer" {{ old('position') === 'footer' ? 'selected' : '' }}>Pied de page</option>
                        </select>
                        @error('position')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label">
                            <span>Emplacement spécifique</span>
                            <i class="fas fa-exclamation-triangle" style="color: #fbbf24;"></i>
                        </label>
                        <select name="location" id="adLocation" class="field-select">
                            <option value="">Aucun (général)</option>
                            <option value="homepage_after_exercises" {{ old('location') === 'homepage_after_exercises' ? 'selected' : '' }}>
                                🏠 Page d'accueil - Après Exercices & Quiz
                            </option>
                            <option value="article_sidebar" {{ old('location') === 'article_sidebar' ? 'selected' : '' }}>
                                📄 Articles - Sidebar
                            </option>
                        </select>
                        <div class="location-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <div class="location-warning-text">
                                <strong>Important :</strong> L'emplacement détermine précisément où la publicité sera affichée. Choisissez avec soin !
                            </div>
                        </div>
                    </div>
                    
                    <div class="field-group">
                        <label class="field-label">
                            <span>Statut</span>
                            <span class="required">*</span>
                        </label>
                        <select name="status" required class="field-select">
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Actif</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactif</option>
                        </select>
                        @error('status')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label">Ordre d'affichage</label>
                        <input type="number" name="order" value="{{ old('order', 0) }}" min="0"
                               class="field-input">
                        <p class="field-help">
                            <i class="fas fa-sort-numeric-down"></i>
                            Plus petit = affiché en premier
                        </p>
                    </div>
                    
                    <div class="field-group">
                        <label class="field-label">Date de début</label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}"
                               class="field-input">
                    </div>

                    <div class="field-group">
                        <label class="field-label">Date de fin</label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}"
                               class="field-input">
                    </div>
                </div>

                <!-- Actions -->
                <div class="sidebar-card">
                    <div class="action-buttons" style="margin-top: 0; padding-top: 0; border-top: none;">
                        <a href="{{ route('admin.ads.index') }}" class="btn-cancel">
                            <i class="fas fa-times"></i>
                            Annuler
                        </a>
                        <button type="submit" class="btn-save">
                            <i class="fas fa-save"></i>
                            Créer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    const adImageType = document.getElementById('adImageType');
    const adInternalImage = document.getElementById('adInternalImage');
    const adExternalImage = document.getElementById('adExternalImage');
    const adImageFile = document.querySelector('input[name="image_file"]');
    const adImageUrl = document.querySelector('input[name="image_url"]');
    const adImagePreview = document.getElementById('adImagePreview');
    const adPreviewImg = document.getElementById('adPreviewImg');
    
    function updateAdImageVisibility() {
        if (adImageType.value === 'internal') {
            adInternalImage.style.display = 'block';
            adExternalImage.style.display = 'none';
            adImageUrl.value = '';
            adImagePreview.classList.add('hidden');
            adPreviewImg.src = '';
        } else {
            adInternalImage.style.display = 'none';
            adExternalImage.style.display = 'block';
            adImageFile.value = '';
            adImagePreview.classList.add('hidden');
            adPreviewImg.src = '';
        }
    }
    
    if (adImageType) {
        adImageType.addEventListener('change', updateAdImageVisibility);
        updateAdImageVisibility();
    }
    
    if (adImageFile) {
        adImageFile.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    adPreviewImg.src = event.target.result;
                    adImagePreview.classList.remove('hidden');
                };
                reader.readAsDataURL(e.target.files[0]);
            } else {
                adImagePreview.classList.add('hidden');
                adPreviewImg.src = '';
            }
        });
    }
    
    if (adImageUrl) {
        adImageUrl.addEventListener('input', function() {
            if (this.value) {
                adPreviewImg.src = this.value;
                adImagePreview.classList.remove('hidden');
            } else {
                adImagePreview.classList.add('hidden');
                adPreviewImg.src = '';
            }
        });
    }
</script>
@endsection
