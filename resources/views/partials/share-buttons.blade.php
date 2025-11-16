@if(isset($article) && $article)
@php
    $shareUrl = route('emplois.article', $article->slug);
    $shareTitle = urlencode($article->title);
    $shareDescription = urlencode($article->meta_description ?? $article->excerpt ?? substr(strip_tags($article->content), 0, 100));
    $shareImage = $article->cover_image 
        ? ($article->cover_type === 'internal' 
            ? url(\Illuminate\Support\Facades\Storage::url($article->cover_image)) 
            : $article->cover_image)
        : url(asset('images/logo.png'));
@endphp
<div class="share-buttons" role="complementary" aria-label="Partager l'article" style="display: flex; align-items: center; gap: 15px; padding: 25px; background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(6, 182, 212, 0.2); border-radius: 16px; margin: 40px 0;">
    <span style="font-weight: 700; color: #06b6d4; font-size: 1rem; margin-right: 10px;">
        <i class="fas fa-share-alt" aria-hidden="true"></i> <span>Partager :</span>
    </span>
    
    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" 
       target="_blank" 
       rel="noopener noreferrer"
       class="share-btn" 
       style="display: inline-flex; align-items: center; justify-content: center; width: 44px; height: 44px; border-radius: 50%; background: #1877f2; color: #fff; text-decoration: none; transition: all 0.3s ease;"
       title="Partager sur Facebook">
        <i class="fab fa-facebook-f" aria-hidden="true"></i>
        <span class="sr-only">Partager sur Facebook</span>
    </a>
    
    <a href="https://twitter.com/intent/tweet?url={{ urlencode($shareUrl) }}&text={{ $shareTitle }}" 
       target="_blank" 
       rel="noopener noreferrer"
       class="share-btn" 
       style="display: inline-flex; align-items: center; justify-content: center; width: 44px; height: 44px; border-radius: 50%; background: #1da1f2; color: #fff; text-decoration: none; transition: all 0.3s ease;"
       aria-label="Partager sur Twitter">
        <i class="fab fa-twitter" aria-hidden="true"></i>
        <span class="sr-only">Partager sur Twitter</span>
    </a>
    
    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode($shareUrl) }}&title={{ $shareTitle }}&summary={{ $shareDescription }}" 
       target="_blank" 
       rel="noopener noreferrer"
       class="share-btn" 
       style="display: inline-flex; align-items: center; justify-content: center; width: 44px; height: 44px; border-radius: 50%; background: #0077b5; color: #fff; text-decoration: none; transition: all 0.3s ease;"
       aria-label="Partager sur LinkedIn">
        <i class="fab fa-linkedin-in" aria-hidden="true"></i>
        <span class="sr-only">Partager sur LinkedIn</span>
    </a>
    
    <a href="https://wa.me/?text={{ urlencode($shareTitle . ' ' . $shareUrl) }}" 
       target="_blank" 
       rel="noopener noreferrer"
       class="share-btn" 
       style="display: inline-flex; align-items: center; justify-content: center; width: 44px; height: 44px; border-radius: 50%; background: #25d366; color: #fff; text-decoration: none; transition: all 0.3s ease;"
       aria-label="Partager sur WhatsApp">
        <i class="fab fa-whatsapp" aria-hidden="true"></i>
        <span class="sr-only">Partager sur WhatsApp</span>
    </a>
    
    <button onclick="copyToClipboard('{{ $shareUrl }}')" 
            class="share-btn" 
            style="display: inline-flex; align-items: center; justify-content: center; width: 44px; height: 44px; border-radius: 50%; background: rgba(6, 182, 212, 0.2); color: #06b6d4; border: 1px solid rgba(6, 182, 212, 0.3); cursor: pointer; transition: all 0.3s ease;"
            aria-label="Copier le lien de l'article">
        <i class="fas fa-link" aria-hidden="true"></i>
        <span class="sr-only">Copier le lien</span>
    </button>
</div>

<style>
    .share-btn:hover {
        transform: translateY(-3px) scale(1.1);
        box-shadow: 0 6px 20px rgba(6, 182, 212, 0.4);
    }
    
    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border-width: 0;
    }
    
    @media (max-width: 768px) {
        .share-buttons {
            flex-wrap: wrap;
            justify-content: center;
        }
    }
</style>

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            // Afficher une notification de succès
            const btn = event.target.closest('.share-btn');
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check"></i>';
            btn.style.background = '#22c55e';
            
            setTimeout(function() {
                btn.innerHTML = originalHTML;
                btn.style.background = '';
            }, 2000);
        }).catch(function(err) {
            console.error('Erreur lors de la copie:', err);
            // Fallback pour les anciens navigateurs
            const textArea = document.createElement('textarea');
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
        });
    }
</script>
@endif

