<div class="comments-section" style="margin-top: 25px; padding-top: 25px; border-top: 2px solid rgba(6, 182, 212, 0.2);">
    <h3 class="comments-title" style="font-size: 1.3rem; font-weight: 800; color: #06b6d4; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
        <i class="fas fa-comments"></i>
        Commentaires ({{ $comments->count() }})
    </h3>

    <!-- Formulaire de commentaire -->
    <div class="comment-form-wrapper" style="background: rgba(51, 65, 85, 0.5); border: 2px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 20px; margin-bottom: 25px;">
        <form action="{{ route('comments.store') }}" method="POST" class="comment-form" id="comment-form">
            @csrf
            <input type="hidden" name="commentable_type" value="{{ get_class($commentable) }}">
            <input type="hidden" name="commentable_id" value="{{ $commentable->id }}">
            
            <!-- Honeypot field (invisible pour les humains, rempli par les bots) -->
            <div style="position: absolute; left: -9999px; opacity: 0; pointer-events: none; visibility: hidden;" aria-hidden="true">
                <label for="website">Website (ne pas remplir)</label>
                <input type="text" name="website" id="website" autocomplete="off" tabindex="-1">
            </div>
            
            <!-- Temps de remplissage du formulaire (anti-bot) -->
            <input type="hidden" name="_form_time" id="form-time" value="{{ microtime(true) }}">
            
            <div style="display: flex; flex-direction: column; gap: 15px; margin-bottom: 15px;">
                <div class="form-group">
                    <label style="display: block; color: rgba(255, 255, 255, 0.9); font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">
                        Nom <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required maxlength="255" 
                           placeholder="Votre nom" 
                           style="width: 100%; padding: 10px 12px; background: rgba(51, 65, 85, 0.7); border: 1px solid rgba(6, 182, 212, 0.4); border-radius: 10px; color: #fff; font-size: 0.9rem;">
                    @error('name')
                        <p style="color: #ef4444; font-size: 0.75rem; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label style="display: block; color: rgba(255, 255, 255, 0.9); font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">
                        Email <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required maxlength="255" 
                           placeholder="votre@email.com" 
                           style="width: 100%; padding: 10px 12px; background: rgba(51, 65, 85, 0.7); border: 1px solid rgba(6, 182, 212, 0.4); border-radius: 10px; color: #fff; font-size: 0.9rem;">
                    @error('email')
                        <p style="color: #ef4444; font-size: 0.75rem; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label style="display: block; color: rgba(255, 255, 255, 0.9); font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">
                        Téléphone
                    </label>
                    <input type="text" name="phone" value="{{ old('phone') }}" maxlength="20" 
                           placeholder="+221 XX XXX XX XX" 
                           style="width: 100%; padding: 10px 12px; background: rgba(51, 65, 85, 0.7); border: 1px solid rgba(6, 182, 212, 0.4); border-radius: 10px; color: #fff; font-size: 0.9rem;">
                    @error('phone')
                        <p style="color: #ef4444; font-size: 0.75rem; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="form-group" style="margin-bottom: 15px;">
                <label style="display: block; color: rgba(255, 255, 255, 0.9); font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">
                    Commentaire <span style="color: #ef4444;">*</span>
                </label>
                <textarea name="content" rows="3" required minlength="10" maxlength="2000" 
                          placeholder="Partagez votre avis..." 
                          style="width: 100%; padding: 12px; background: rgba(51, 65, 85, 0.7); border: 1px solid rgba(6, 182, 212, 0.4); border-radius: 10px; color: #fff; font-size: 0.9rem; resize: vertical; font-family: inherit;">{{ old('content') }}</textarea>
                @error('content')
                    <p style="color: #ef4444; font-size: 0.75rem; margin-top: 4px;">{{ $message }}</p>
                @enderror
            </div>
            
            <button type="submit" onclick="event.preventDefault(); if (typeof executeRecaptcha === 'function') { executeRecaptcha('comment-form', function() { document.getElementById('comment-form').submit(); }); } else { document.getElementById('comment-form').submit(); }" style="width: 100%; padding: 10px 20px; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: #fff; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 0.9rem;">
                <i class="fas fa-paper-plane"></i>
                Publier
            </button>
        </form>
    </div>

    <!-- 3 Derniers commentaires approuvés -->
    @if(isset($latestComments) && $latestComments->count() > 0)
    <div style="margin-top: 30px; padding-top: 30px; border-top: 2px solid rgba(6, 182, 212, 0.3);">
        <h4 style="font-size: 1.15rem; font-weight: 800; color: #06b6d4; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; padding-bottom: 10px; border-bottom: 1px solid rgba(6, 182, 212, 0.2);">
            <div style="width: 35px; height: 35px; background: linear-gradient(135deg, #06b6d4, #14b8a6); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-comments" style="color: #fff; font-size: 0.9rem;"></i>
            </div>
            <span>Derniers commentaires</span>
        </h4>
        <div style="display: flex; flex-direction: column; gap: 16px;">
            @foreach($latestComments as $latestComment)
            <div style="background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.1)); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 18px; position: relative; overflow: hidden; transition: all 0.3s ease;" onmouseover="this.style.borderColor='rgba(6, 182, 212, 0.5)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(6, 182, 212, 0.2)';" onmouseout="this.style.borderColor='rgba(6, 182, 212, 0.3)'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                <!-- Effet de brillance en arrière-plan -->
                <div style="position: absolute; top: -50%; right: -50%; width: 200%; height: 200%; background: radial-gradient(circle, rgba(6, 182, 212, 0.1) 0%, transparent 70%); pointer-events: none;"></div>
                
                <div style="display: flex; align-items: flex-start; gap: 12px; position: relative; z-index: 1;">
                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #06b6d4, #14b8a6); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #fff; font-size: 0.9rem; flex-shrink: 0; box-shadow: 0 4px 8px rgba(6, 182, 212, 0.3);">
                        {{ strtoupper(substr($latestComment->author_name, 0, 1)) }}
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; flex-wrap: wrap; gap: 8px;">
                            <h5 style="font-size: 0.9rem; font-weight: 700; color: #fff; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $latestComment->author_name }}</h5>
                            <div style="display: flex; align-items: center; gap: 8px; font-size: 0.7rem; color: rgba(255, 255, 255, 0.6);">
                                <i class="fas fa-clock"></i>
                                <span>{{ $latestComment->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <p style="color: rgba(255, 255, 255, 0.9); line-height: 1.6; font-size: 0.85rem; word-wrap: break-word; margin: 0; padding: 12px; background: rgba(51, 65, 85, 0.4); border-radius: 10px; border-left: 3px solid #06b6d4;">{!! nl2br(e($latestComment->content)) !!}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Liste des commentaires -->
    
</div>

<script>
function replyToComment(commentId) {
    const form = document.getElementById('reply-form-' + commentId);
    if (form) {
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
        if (form.style.display === 'block') {
            form.querySelector('textarea').focus();
        }
    }
}

function cancelReply(commentId) {
    const form = document.getElementById('reply-form-' + commentId);
    if (form) {
        form.style.display = 'none';
        form.querySelector('textarea').value = '';
        form.querySelectorAll('input[type="text"], input[type="email"]').forEach(input => input.value = '');
    }
}

function likeComment(commentId) {
    fetch(`/comments/${commentId}/like`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('likes-' + commentId).textContent = data.likes;
    })
    .catch(err => console.log('Erreur:', err));
}
</script>
