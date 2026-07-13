{{-- Bouton de partage flottant pour les cards .hp-article-card (homepage) --}}
<div class="hp-article-share" data-share-card>
  <button type="button" class="hp-article-share-toggle" data-share-toggle aria-haspopup="true" aria-expanded="false" aria-label="Partager cet article">
    <i class="fas fa-share-alt"></i>
  </button>
  <div class="hp-article-share-menu" data-share-menu>
    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" target="_blank" rel="noopener noreferrer">
      <i class="fab fa-facebook-f"></i> Facebook
    </a>
    <a href="https://twitter.com/intent/tweet?url={{ urlencode($shareUrl) }}&text={{ urlencode($shareTitle) }}" target="_blank" rel="noopener noreferrer">
      <i class="fab fa-twitter"></i> X (Twitter)
    </a>
    <a href="https://wa.me/?text={{ urlencode($shareTitle . ' ' . $shareUrl) }}" target="_blank" rel="noopener noreferrer">
      <i class="fab fa-whatsapp"></i> WhatsApp
    </a>
    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode($shareUrl) }}&title={{ urlencode($shareTitle) }}" target="_blank" rel="noopener noreferrer">
      <i class="fab fa-linkedin-in"></i> LinkedIn
    </a>
    <button type="button" data-copy-link="{{ $shareUrl }}">
      <i class="fas fa-link"></i> Copier le lien
    </button>
  </div>
</div>
