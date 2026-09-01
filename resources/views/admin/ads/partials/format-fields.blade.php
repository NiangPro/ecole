{{--
    Sélecteur de format de publicité (Image / Vidéo YouTube), partagé entre
    create.blade.php et edit.blade.php. Attend une variable $ad (null en création).
--}}
@php
    $isVideoAd = isset($ad) && $ad && $ad->isVideoAd();
    $video = $isVideoAd ? $ad->video_data : null;
    $currentFormat = old('ad_format', $isVideoAd ? 'video' : 'image');
    $currentImageType = old('image_type', ($ad->image_type ?? null) ?: 'external');
@endphp

<div class="form-card">
    <div class="card-header">
        <div class="card-icon"><i class="fas fa-photo-film"></i></div>
        <h2 class="card-title">Format de la publicité</h2>
    </div>

    <div class="format-toggle" id="adFormatToggle">
        <button type="button" class="format-toggle-btn {{ $currentFormat === 'image' ? 'active' : '' }}" data-format="image">
            <i class="fas fa-image"></i>
            <span>Image</span>
        </button>
        <button type="button" class="format-toggle-btn {{ $currentFormat === 'video' ? 'active' : '' }}" data-format="video">
            <i class="fab fa-youtube"></i>
            <span>Vidéo YouTube</span>
        </button>
    </div>
    <input type="hidden" name="ad_format" id="adFormatInput" value="{{ $currentFormat }}">

    {{-- ── Panneau Image ──────────────────────────────────────── --}}
    <div id="adFormatPanelImage" class="format-panel" style="display: {{ $currentFormat === 'image' ? 'block' : 'none' }};">
        <div class="field-group">
            <label class="field-label">
                <span>Type d'image</span>
                <span class="required">*</span>
            </label>
            <select name="image_type" id="adImageType" class="field-select">
                <option value="external" {{ $currentImageType === 'external' ? 'selected' : '' }}>Externe (URL)</option>
                <option value="internal" {{ $currentImageType === 'internal' ? 'selected' : '' }}>Interne (upload)</option>
            </select>
        </div>

        <div id="adInternalImage" style="display: {{ $currentImageType === 'internal' ? 'block' : 'none' }};" class="field-group">
            <label class="field-label">Fichier image</label>
            <input type="file" name="image_file" accept="image/*" class="field-input">
            @if(isset($ad) && $ad && $ad->image_type === 'internal' && $ad->image)
                <p class="field-help">
                    <i class="fas fa-check-circle"></i>
                    Image actuelle : <a href="{{ \Illuminate\Support\Facades\Storage::url($ad->image) }}" target="_blank" style="color: #06b6d4; text-decoration: none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">{{ basename($ad->image) }}</a>
                </p>
            @else
                <p class="field-help">
                    <i class="fas fa-info-circle"></i>
                    Formats acceptés: JPG, PNG, GIF (max 5MB)
                </p>
            @endif
        </div>

        <div id="adExternalImage" style="display: {{ $currentImageType === 'external' ? 'block' : 'none' }};" class="field-group">
            <label class="field-label">URL de l'image</label>
            <input type="url" name="image_url" value="{{ old('image', (isset($ad) && $ad && $ad->image_type === 'external') ? $ad->image : '') }}"
                   class="field-input" placeholder="https://example.com/image.jpg">
            <p class="field-help">
                <i class="fas fa-link"></i>
                Entrez l'URL complète de l'image
            </p>
        </div>

        <div id="adImagePreview" class="image-preview-box {{ (isset($ad) && $ad && $ad->image) ? '' : 'hidden' }}">
            <img loading="lazy" id="adPreviewImg"
                 src="{{ (isset($ad) && $ad && $ad->image) ? ($ad->image_type === 'internal' ? \Illuminate\Support\Facades\Storage::url($ad->image) : $ad->image) : '' }}"
                 alt="Aperçu">
        </div>
    </div>

    {{-- ── Panneau Vidéo YouTube ──────────────────────────────── --}}
    <div id="adFormatPanelVideo" class="format-panel" style="display: {{ $currentFormat === 'video' ? 'block' : 'none' }};">
        <div class="field-group">
            <label class="field-label">
                <span>Lien de la vidéo YouTube</span>
                <span class="required">*</span>
            </label>
            <div class="youtube-url-row">
                <input type="url" name="youtube_url" id="adYoutubeUrl"
                       value="{{ old('youtube_url', $video['youtube_url'] ?? '') }}"
                       class="field-input" placeholder="https://www.youtube.com/watch?v=... ou https://youtu.be/...">
                <button type="button" id="adYoutubeAnalyze" class="btn-analyze">
                    <i class="fas fa-magnifying-glass"></i>
                    <span>Analyser</span>
                </button>
            </div>
            <p class="field-help">
                <i class="fas fa-info-circle"></i>
                Collez le lien d'une vidéo YouTube publique (ou "Short"). Miniature, titre et auteur sont récupérés automatiquement — le lecteur ne se charge sur le site qu'au clic du visiteur, avec lecture automatique en boucle à ce moment-là (aucun poids ajouté aux autres pages).
            </p>
            @error('youtube_url')
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div id="adYoutubeError" class="youtube-error" style="display:none;">
            <i class="fas fa-triangle-exclamation"></i>
            <span id="adYoutubeErrorText"></span>
        </div>

        <div id="adYoutubePreview" class="youtube-preview-box" style="display: {{ $video ? 'flex' : 'none' }};">
            <div class="youtube-preview-thumb" id="adYoutubeThumb"
                 style="{{ ($video['thumbnail_url'] ?? null) ? "background-image:url('" . $video['thumbnail_url'] . "')" : '' }}"></div>
            <div class="youtube-preview-info">
                <strong id="adYoutubeTitle">{{ $video['title'] ?? '' }}</strong>
                <span id="adYoutubeAuthor">{{ ($video['author_name'] ?? null) ? $video['author_name'] : '' }}</span>
            </div>
        </div>
    </div>

    <div class="field-group" style="margin-bottom: 0;">
        <label class="field-label">
            <span>URL de destination (lien au clic)</span>
        </label>
        <input type="url" name="link_url" value="{{ old('link_url', $ad->link_url ?? '') }}"
               class="field-input" placeholder="https://example.com">
        <p class="field-help">
            <i class="fas fa-external-link-alt"></i>
            <span id="adLinkUrlHelp">Requis pour une publicité image. Optionnel pour une vidéo YouTube (utilise le lien de la vidéo par défaut si laissé vide).</span>
        </p>
    </div>
</div>
