@extends('admin.layout')

@section('title', 'Nouvelle Publicité')

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h3 class="text-3xl font-bold mb-2">Nouvelle Publicité</h3>
        <p class="text-gray-400">Créez une nouvelle publicité à afficher sur votre site</p>
    </div>
    <a href="{{ route('admin.ads.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition">
        <i class="fas fa-arrow-left mr-2"></i>Retour
    </a>
</div>

<form action="{{ route('admin.ads.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne principale -->
        <div class="lg:col-span-2 space-y-6">
            <div class="content-section">
                <label class="block text-cyan-400 mb-2 font-semibold">Nom de la publicité *</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="input-admin" placeholder="Ex: Bannière Homepage">
                @error('name')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="content-section">
                <label class="block text-cyan-400 mb-2 font-semibold">Description</label>
                <textarea name="description" rows="3" class="input-admin" placeholder="Description optionnelle de la publicité">{{ old('description') }}</textarea>
            </div>

            <div class="content-section">
                <h4 class="text-cyan-400 font-bold mb-4 border-b border-cyan-500/20 pb-2">Image de la publicité</h4>
                <div class="space-y-4">
                    <div>
                        <label class="block text-cyan-400 mb-2 font-semibold">Type d'image *</label>
                        <select name="image_type" id="adImageType" class="input-admin" required>
                            <option value="external" {{ old('image_type', 'external') === 'external' ? 'selected' : '' }}>Externe (URL)</option>
                            <option value="internal" {{ old('image_type') === 'internal' ? 'selected' : '' }}>Interne (upload)</option>
                        </select>
                    </div>
                    <div id="adInternalImage" style="display: {{ old('image_type') === 'internal' ? 'block' : 'none' }};">
                        <label class="block text-cyan-400 mb-2 font-semibold">Fichier image</label>
                        <input type="file" name="image_file" accept="image/*" class="input-admin">
                        <p class="text-gray-400 text-sm mt-1">Formats acceptés: JPG, PNG, GIF</p>
                    </div>
                    <div id="adExternalImage" style="display: {{ old('image_type', 'external') === 'external' ? 'block' : 'none' }};">
                        <label class="block text-cyan-400 mb-2 font-semibold">URL de l'image</label>
                        <input type="url" name="image_url" value="{{ old('image') }}"
                               class="input-admin" placeholder="https://example.com/image.jpg">
                        <p class="text-gray-400 text-sm mt-1">Entrez l'URL complète de l'image</p>
                    </div>
                    <div>
                        <label class="block text-cyan-400 mb-2 font-semibold">URL de destination (lien au clic) *</label>
                        <input type="url" name="link_url" value="{{ old('link_url') }}" required
                               class="input-admin" placeholder="https://example.com">
                        <p class="text-gray-400 text-sm mt-1">URL vers laquelle rediriger lors du clic sur l'image</p>
                    </div>
                    <div id="adImagePreview" class="mt-4 hidden">
                        <img id="adPreviewImg" src="" alt="Aperçu" class="w-full rounded-lg border border-cyan-500/20 max-h-64 object-contain">
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <div class="content-section">
                <h4 class="text-cyan-400 font-bold mb-4 border-b border-cyan-500/20 pb-2">Paramètres</h4>
                
                <div class="mb-4">
                    <label class="block text-cyan-400 mb-2 font-semibold">Position *</label>
                    <select name="position" id="adPosition" required class="input-admin">
                        <option value="sidebar" {{ old('position') === 'sidebar' ? 'selected' : '' }}>Sidebar</option>
                        <option value="content" {{ old('position') === 'content' ? 'selected' : '' }}>Contenu</option>
                        <option value="header" {{ old('position') === 'header' ? 'selected' : '' }}>En-tête</option>
                        <option value="footer" {{ old('position') === 'footer' ? 'selected' : '' }}>Pied de page</option>
                    </select>
                    @error('position')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-cyan-400 mb-2 font-semibold">Emplacement spécifique</label>
                    <select name="location" id="adLocation" class="input-admin">
                        <option value="">Aucun (général)</option>
                        <option value="homepage_after_exercises" {{ old('location') === 'homepage_after_exercises' ? 'selected' : '' }}>Page d'accueil - Après Exercices & Quiz</option>
                        <option value="article_sidebar" {{ old('location') === 'article_sidebar' ? 'selected' : '' }}>Articles - Sidebar</option>
                    </select>
                    <p class="text-gray-400 text-sm mt-1">Sélectionnez un emplacement spécifique pour cette publicité</p>
                </div>

                <div class="mb-4">
                    <label class="block text-cyan-400 mb-2 font-semibold">Statut *</label>
                    <select name="status" required class="input-admin">
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Actif</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactif</option>
                    </select>
                    @error('status')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-cyan-400 mb-2 font-semibold">Ordre d'affichage</label>
                    <input type="number" name="order" value="{{ old('order', 0) }}" min="0"
                           class="input-admin">
                    <p class="text-gray-400 text-sm mt-1">Plus petit = affiché en premier</p>
                </div>

                <div class="mb-4">
                    <label class="block text-cyan-400 mb-2 font-semibold">Date de début</label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}"
                           class="input-admin">
                </div>

                <div class="mb-4">
                    <label class="block text-cyan-400 mb-2 font-semibold">Date de fin</label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}"
                           class="input-admin">
                </div>
            </div>

            <div class="flex gap-2 pt-4">
                <button type="submit" class="btn-primary flex-1">
                    <i class="fas fa-save mr-2"></i>Créer
                </button>
                <a href="{{ route('admin.ads.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition">
                    Annuler
                </a>
            </div>
        </div>
    </div>
</form>

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
