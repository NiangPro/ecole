@extends('admin.layout')

@section('title', 'Modifier Catégorie')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@400;500;600;700;800;900&display=swap');
    
    .category-form-wrapper {
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
        grid-template-columns: 1fr 1fr;
        gap: 30px;
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
        transform: translateY(-5px);
        box-shadow: 0 20px 60px rgba(6, 182, 212, 0.2);
    }
    
    .form-section-title {
        font-family: 'Poppins', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #06b6d4;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid rgba(6, 182, 212, 0.3);
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .form-section-title i {
        font-size: 1.3rem;
    }
    
    .form-group {
        margin-bottom: 25px;
    }
    
    .form-label {
        display: block;
        font-family: 'Inter', sans-serif;
        font-size: 0.95rem;
        font-weight: 600;
        color: #06b6d4;
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 14px 18px;
        background: rgba(15, 23, 42, 0.8);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 12px;
        color: #fff;
        font-size: 1rem;
        font-family: 'Inter', sans-serif;
        transition: all 0.3s ease;
    }
    
    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #06b6d4;
        background: rgba(15, 23, 42, 0.95);
        box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.1);
    }
    
    .form-select {
        cursor: pointer;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2306b6d4' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 18px center;
        background-size: 12px;
        padding-right: 45px;
        appearance: none;
    }
    
    .form-select option {
        background: #0f172a;
        color: #fff;
        padding: 10px;
    }
    
    .form-textarea {
        resize: vertical;
        min-height: 120px;
    }
    
    .form-help {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.5);
        margin-top: 8px;
        font-style: italic;
    }
    
    .form-error {
        color: #ef4444;
        font-size: 0.85rem;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .form-error::before {
        content: '⚠';
        font-size: 1rem;
    }
    
    .checkbox-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 15px;
        background: rgba(6, 182, 212, 0.05);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 12px;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .checkbox-wrapper:hover {
        background: rgba(6, 182, 212, 0.1);
        border-color: rgba(6, 182, 212, 0.4);
    }
    
    .checkbox-wrapper input[type="checkbox"] {
        width: 22px;
        height: 22px;
        cursor: pointer;
        accent-color: #06b6d4;
    }
    
    .checkbox-wrapper label {
        font-family: 'Inter', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        color: #06b6d4;
        cursor: pointer;
        margin: 0;
    }
    
    .image-preview-wrapper {
        margin-top: 20px;
        padding: 20px;
        background: rgba(6, 182, 212, 0.05);
        border: 2px dashed rgba(6, 182, 212, 0.3);
        border-radius: 12px;
        text-align: center;
    }
    
    .image-preview-wrapper img {
        max-width: 100%;
        max-height: 300px;
        border-radius: 12px;
        border: 2px solid rgba(6, 182, 212, 0.3);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
    
    .form-actions {
        display: flex;
        gap: 15px;
        margin-top: 40px;
        padding-top: 30px;
        border-top: 2px solid rgba(6, 182, 212, 0.2);
    }
    
    .btn-submit {
        flex: 1;
        padding: 16px 32px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: #000;
        border: none;
        border-radius: 12px;
        font-family: 'Poppins', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(6, 182, 212, 0.5);
    }
    
    .btn-cancel {
        padding: 16px 32px;
        background: rgba(107, 114, 128, 0.2);
        color: #fff;
        border: 2px solid rgba(107, 114, 128, 0.3);
        border-radius: 12px;
        font-family: 'Inter', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .btn-cancel:hover {
        background: rgba(107, 114, 128, 0.3);
        border-color: rgba(107, 114, 128, 0.5);
        transform: translateY(-2px);
    }
    
    @media (max-width: 1024px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .form-hero {
            padding: 35px;
        }
        
        .form-hero h1 {
            font-size: 2.5rem;
        }
    }
    
    @media (max-width: 768px) {
        .form-hero {
            padding: 25px;
        }
        
        .form-hero h1 {
            font-size: 2rem;
        }
        
        .form-card {
            padding: 25px;
        }
        
        .form-actions {
            flex-direction: column;
        }
    }
</style>
@endsection

@section('content')
<div class="category-form-wrapper">
    <!-- Hero Section -->
    <div class="form-hero">
        <div class="form-hero-content">
            <h1><i class="fas fa-edit"></i> Modifier Catégorie</h1>
            <p>Mettez à jour les informations de la catégorie d'emplois</p>
        </div>
    </div>
    
    <form action="{{ route('admin.jobs.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-grid">
            <!-- Colonne gauche -->
            <div class="form-card">
                <div class="form-section-title">
                    <i class="fas fa-info-circle"></i>
                    <span>Informations Générales</span>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Nom de la catégorie *</label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                           class="form-input" placeholder="Ex: Offres d'emploi">
                    @error('name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $category->slug) }}"
                           class="form-input" placeholder="Sera généré automatiquement si vide">
                    @error('slug')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                    <div class="form-help">L'identifiant unique de la catégorie dans l'URL</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="4" class="form-textarea" 
                              placeholder="Description de la catégorie">{{ old('description', $category->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Icône (Font Awesome)</label>
                    <input type="text" name="icon" value="{{ old('icon', $category->icon) }}"
                           class="form-input" placeholder="Ex: fas fa-briefcase">
                    <div class="form-help">Utilisez les classes Font Awesome (ex: fas fa-briefcase)</div>
                </div>
            </div>

            <!-- Colonne droite -->
            <div class="form-card">
                <div class="form-section-title">
                    <i class="fas fa-image"></i>
                    <span>Image & Paramètres</span>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Type d'image</label>
                    <select name="image_type" id="categoryImageType" class="form-select">
                        <option value="internal" {{ old('image_type', $category->image_type ?? 'internal') === 'internal' ? 'selected' : '' }}>Interne (upload)</option>
                        <option value="external" {{ old('image_type', $category->image_type ?? '') === 'external' ? 'selected' : '' }}>Externe (URL)</option>
                    </select>
                </div>
                
                <div id="categoryInternalImage" style="display: {{ old('image_type', $category->image_type ?? 'internal') === 'internal' ? 'block' : 'none' }};">
                    <div class="form-group">
                        <label class="form-label">Fichier image</label>
                        <input type="file" name="image_file" accept="image/*" class="form-input">
                        @if($category->image_type === 'internal' && $category->image)
                            <div class="form-help">
                                Image actuelle: <a href="{{ \Illuminate\Support\Facades\Storage::url($category->image) }}" target="_blank" class="text-cyan-400 hover:underline">{{ basename($category->image) }}</a>
                            </div>
                        @else
                            <div class="form-help">Formats acceptés: JPG, PNG, GIF</div>
                        @endif
                    </div>
                </div>
                
                <div id="categoryExternalImage" style="display: {{ old('image_type', $category->image_type ?? '') === 'external' ? 'block' : 'none' }};">
                    <div class="form-group">
                        <label class="form-label">URL de l'image</label>
                        <input type="url" name="image" id="categoryImageUrl" value="{{ old('image', $category->image ?? '') }}"
                               class="form-input" placeholder="https://example.com/image.jpg">
                        <div class="form-help">Entrez l'URL complète de l'image</div>
                    </div>
                </div>
                
                <div id="categoryImagePreview" class="image-preview-wrapper {{ $category->image ? '' : 'hidden' }}">
                    <img id="categoryPreviewImg" src="{{ $category->image ? ($category->image_type === 'internal' ? \Illuminate\Support\Facades\Storage::url($category->image) : $category->image) : '' }}" alt="Aperçu">
                </div>

                <div class="form-group">
                    <label class="form-label">Ordre d'affichage</label>
                    <input type="number" name="order" value="{{ old('order', $category->order) }}" min="0"
                           class="form-input">
                    <div class="form-help">Détermine l'ordre d'affichage dans les listes</div>
                </div>

                <div class="form-group">
                    <div class="checkbox-wrapper">
                        <input type="checkbox" name="is_active" value="1" id="isActive" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                        <label for="isActive">Catégorie active</label>
                    </div>
                    <div class="form-help">Une catégorie inactive ne sera pas visible sur le site</div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i>
                <span>Mettre à jour la catégorie</span>
            </button>
            <a href="{{ route('admin.jobs.categories.index') }}" class="btn-cancel">
                <i class="fas fa-times"></i>
                <span>Annuler</span>
            </a>
        </div>
    </form>
</div>

@section('scripts')
<script>
    const categoryImageType = document.getElementById('categoryImageType');
    const categoryInternalImage = document.getElementById('categoryInternalImage');
    const categoryExternalImage = document.getElementById('categoryExternalImage');
    const categoryImageFile = document.querySelector('input[name="image_file"]');
    const categoryImageUrl = document.getElementById('categoryImageUrl');
    
    if (categoryImageType) {
        categoryImageType.addEventListener('change', function() {
            if (this.value === 'internal') {
                if (categoryInternalImage) categoryInternalImage.style.display = 'block';
                if (categoryExternalImage) categoryExternalImage.style.display = 'none';
                if (categoryImageUrl) categoryImageUrl.value = '';
            } else {
                if (categoryInternalImage) categoryInternalImage.style.display = 'none';
                if (categoryExternalImage) categoryExternalImage.style.display = 'block';
                if (categoryImageFile) categoryImageFile.value = '';
            }
        });
    }
    
    if (categoryImageFile) {
        categoryImageFile.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const preview = document.getElementById('categoryImagePreview');
                    const previewImg = document.getElementById('categoryPreviewImg');
                    if (preview && previewImg) {
                        previewImg.src = event.target.result;
                        preview.classList.remove('hidden');
                    }
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    }
    
    if (categoryImageUrl) {
        categoryImageUrl.addEventListener('input', function() {
            if (this.value) {
                const preview = document.getElementById('categoryImagePreview');
                const previewImg = document.getElementById('categoryPreviewImg');
                if (preview && previewImg) {
                    previewImg.src = this.value;
                    preview.classList.remove('hidden');
                }
            } else {
                const preview = document.getElementById('categoryImagePreview');
                if (preview) preview.classList.add('hidden');
            }
        });
    }
</script>
@endsection
@endsection

