<script>
    (function() {
        // ── Toggle Image / Vidéo YouTube ─────────────────────────
        const formatToggle = document.getElementById('adFormatToggle');
        const formatInput = document.getElementById('adFormatInput');
        const panelImage = document.getElementById('adFormatPanelImage');
        const panelVideo = document.getElementById('adFormatPanelVideo');
        const linkUrlHelp = document.getElementById('adLinkUrlHelp');
        const imageTypeSelect = document.getElementById('adImageType');
        const youtubeUrlInput = document.getElementById('adYoutubeUrl');

        function setFormat(format) {
            formatInput.value = format;
            formatToggle.querySelectorAll('.format-toggle-btn').forEach(function(btn) {
                btn.classList.toggle('active', btn.dataset.format === format);
            });
            panelImage.style.display = format === 'image' ? 'block' : 'none';
            panelVideo.style.display = format === 'video' ? 'block' : 'none';

            // image_type n'est requis (attribut HTML) que pour le format image,
            // sinon le navigateur bloque la soumission d'un formulaire vidéo.
            if (imageTypeSelect) imageTypeSelect.required = (format === 'image');
            if (youtubeUrlInput) youtubeUrlInput.required = (format === 'video');

            if (linkUrlHelp) {
                linkUrlHelp.textContent = format === 'video'
                    ? "Optionnel pour une vidéo YouTube (utilise le lien de la vidéo par défaut si laissé vide)."
                    : "URL vers laquelle rediriger lors du clic sur l'image.";
            }
        }

        if (formatToggle) {
            formatToggle.querySelectorAll('.format-toggle-btn').forEach(function(btn) {
                btn.addEventListener('click', function() { setFormat(btn.dataset.format); });
            });
            setFormat(formatInput.value);
        }

        // ── Aperçu image (upload / URL) ──────────────────────────
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
            } else {
                adInternalImage.style.display = 'none';
                adExternalImage.style.display = 'block';
            }
        }

        if (adImageType) {
            adImageType.addEventListener('change', updateAdImageVisibility);
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

        // ── Aperçu vidéo YouTube (AJAX oEmbed) ───────────────────
        const analyzeBtn = document.getElementById('adYoutubeAnalyze');
        const previewBox = document.getElementById('adYoutubePreview');
        const errorBox = document.getElementById('adYoutubeError');
        const errorText = document.getElementById('adYoutubeErrorText');
        const thumbEl = document.getElementById('adYoutubeThumb');
        const titleEl = document.getElementById('adYoutubeTitle');
        const authorEl = document.getElementById('adYoutubeAuthor');

        function analyzeYoutubeUrl() {
            const url = youtubeUrlInput.value.trim();
            if (!url) return;

            errorBox.style.display = 'none';
            analyzeBtn.disabled = true;
            analyzeBtn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i><span>Analyse...</span>';

            fetch('{{ route("admin.ads.youtube-preview") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ youtube_url: url })
            })
                .then(function(res) { return res.json().then(function(body) { return { ok: res.ok, body: body }; }); })
                .then(function(result) {
                    if (!result.ok || !result.body.success) {
                        throw new Error(result.body.message || "Impossible de récupérer cette vidéo.");
                    }
                    const data = result.body.data;
                    thumbEl.style.backgroundImage = data.thumbnail_url ? "url('" + data.thumbnail_url + "')" : '';
                    titleEl.textContent = data.title || '';
                    authorEl.textContent = data.author_name || '';
                    previewBox.style.display = 'flex';
                })
                .catch(function(err) {
                    previewBox.style.display = 'none';
                    errorText.textContent = err.message;
                    errorBox.style.display = 'flex';
                })
                .finally(function() {
                    analyzeBtn.disabled = false;
                    analyzeBtn.innerHTML = '<i class="fas fa-magnifying-glass"></i><span>Analyser</span>';
                });
        }

        if (analyzeBtn) {
            analyzeBtn.addEventListener('click', analyzeYoutubeUrl);
        }
        if (youtubeUrlInput) {
            youtubeUrlInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') { e.preventDefault(); analyzeYoutubeUrl(); }
            });
        }
    })();
</script>
