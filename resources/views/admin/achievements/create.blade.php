@extends('admin.layout')

@section('title', 'Nouvelle Réalisation')

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h3 class="text-3xl font-bold mb-2">Nouvelle Réalisation</h3>
        <p class="text-gray-400">Créez une nouvelle réalisation</p>
    </div>
    <a href="{{ route('admin.achievements.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition">
        <i class="fas fa-arrow-left mr-2"></i>Retour
    </a>
</div>

<div class="content-section">
    <form action="{{ route('admin.achievements.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="space-y-6">
                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Titre *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           class="input-admin" placeholder="Ex: Site E-commerce Laravel">
                    @error('title')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Description</label>
                    <textarea name="description" rows="4" class="input-admin" placeholder="Description de la réalisation">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Icône (Font Awesome)</label>
                    <input type="text" name="icon" value="{{ old('icon') }}"
                           class="input-admin" placeholder="Ex: fas fa-code">
                    <p class="text-gray-400 text-sm mt-1">Utilisez les classes Font Awesome</p>
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Lien URL</label>
                    <input type="url" name="link_url" value="{{ old('link_url') }}"
                           class="input-admin" placeholder="https://example.com">
                    <p class="text-gray-400 text-sm mt-1">Lien vers le projet (optionnel)</p>
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Image</label>
                    <select name="image_type" id="achievementImageType" class="input-admin mb-3">
                        <option value="internal" {{ old('image_type', 'internal') === 'internal' ? 'selected' : '' }}>Interne (upload)</option>
                        <option value="external" {{ old('image_type') === 'external' ? 'selected' : '' }}>Externe (URL)</option>
                    </select>
                    <div id="achievementInternalImage" style="display: {{ old('image_type', 'internal') === 'internal' ? 'block' : 'none' }};">
                        <input type="file" name="image_file" accept="image/*" class="input-admin">
                        <p class="text-gray-400 text-sm mt-1">Formats acceptés: JPG, PNG, GIF</p>
                    </div>
                    <div id="achievementExternalImage" style="display: {{ old('image_type') === 'external' ? 'block' : 'none' }};">
                        <input type="url" name="image_url" id="achievementImageUrl" value="{{ old('image') }}"
                               class="input-admin" placeholder="https://example.com/image.jpg">
                        <p class="text-gray-400 text-sm mt-1">Entrez l'URL complète de l'image</p>
                    </div>
                    <div id="achievementImagePreview" class="mt-4 hidden">
                        <img id="achievementPreviewImg" src="" alt="Aperçu" class="w-full max-w-xs rounded-lg border border-cyan-500/20">
                    </div>
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Ordre d'affichage</label>
                    <input type="number" name="order" value="{{ old('order', 0) }}" min="0"
                           class="input-admin">
                </div>

                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_visible" value="1" {{ old('is_visible', true) ? 'checked' : '' }}
                               class="w-5 h-5 rounded bg-cyan-500/20 border-cyan-500/50 text-cyan-500 focus:ring-cyan-500">
                        <span class="text-cyan-400 font-semibold">Afficher sur le site</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex gap-4 mt-8">
            <button type="submit" class="btn-primary">
                <i class="fas fa-save mr-2"></i>Créer la réalisation
            </button>
            <a href="{{ route('admin.achievements.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition">
                Annuler
            </a>
        </div>
    </form>
</div>

@section('scripts')
<script>
    const achievementImageType = document.getElementById('achievementImageType');
    const achievementInternalImage = document.getElementById('achievementInternalImage');
    const achievementExternalImage = document.getElementById('achievementExternalImage');
    const achievementImageFile = document.querySelector('input[name="image_file"]');
    const achievementImageUrl = document.getElementById('achievementImageUrl');
    
    if (achievementImageType) {
        achievementImageType.addEventListener('change', function() {
            if (this.value === 'internal') {
                if (achievementInternalImage) achievementInternalImage.style.display = 'block';
                if (achievementExternalImage) achievementExternalImage.style.display = 'none';
                if (achievementImageUrl) achievementImageUrl.value = '';
            } else {
                if (achievementInternalImage) achievementInternalImage.style.display = 'none';
                if (achievementExternalImage) achievementExternalImage.style.display = 'block';
                if (achievementImageFile) achievementImageFile.value = '';
            }
        });
    }
    
    if (achievementImageFile) {
        achievementImageFile.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const preview = document.getElementById('achievementImagePreview');
                    const previewImg = document.getElementById('achievementPreviewImg');
                    if (preview && previewImg) {
                        previewImg.src = event.target.result;
                        preview.classList.remove('hidden');
                    }
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    }
    
    if (achievementImageUrl) {
        achievementImageUrl.addEventListener('input', function() {
            if (this.value) {
                const preview = document.getElementById('achievementImagePreview');
                const previewImg = document.getElementById('achievementPreviewImg');
                if (preview && previewImg) {
                    previewImg.src = this.value;
                    preview.classList.remove('hidden');
                }
            } else {
                const preview = document.getElementById('achievementImagePreview');
                if (preview) preview.classList.add('hidden');
            }
        });
    }
</script>
@endsection
@endsection

