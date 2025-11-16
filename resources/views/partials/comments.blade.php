<div class="comments-section" style="margin-top: 25px; padding-top: 25px; border-top: 2px solid rgba(6, 182, 212, 0.2);">
    <h3 class="comments-title" style="font-size: 1.3rem; font-weight: 800; color: #06b6d4; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
        <i class="fas fa-comments"></i>
        Commentaires ({{ $comments->count() }})
    </h3>

    <!-- Formulaire de commentaire -->
    <div class="comment-form-wrapper" style="background: rgba(15, 23, 42, 0.6); border: 2px solid rgba(6, 182, 212, 0.2); border-radius: 16px; padding: 20px; margin-bottom: 25px;">
        <form action="{{ route('comments.store') }}" method="POST" class="comment-form">
            @csrf
            <input type="hidden" name="commentable_type" value="{{ get_class($commentable) }}">
            <input type="hidden" name="commentable_id" value="{{ $commentable->id }}">
            
            
            <div style="display: flex; flex-direction: column; gap: 15px; margin-bottom: 15px;">
                <div class="form-group">
                    <label style="display: block; color: rgba(255, 255, 255, 0.9); font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">
                        Nom <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required maxlength="255" 
                           placeholder="Votre nom" 
                           style="width: 100%; padding: 10px 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 10px; color: #fff; font-size: 0.9rem;">
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
                           style="width: 100%; padding: 10px 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 10px; color: #fff; font-size: 0.9rem;">
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
                           style="width: 100%; padding: 10px 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 10px; color: #fff; font-size: 0.9rem;">
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
                          style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 10px; color: #fff; font-size: 0.9rem; resize: vertical; font-family: inherit;">{{ old('content') }}</textarea>
                @error('content')
                    <p style="color: #ef4444; font-size: 0.75rem; margin-top: 4px;">{{ $message }}</p>
                @enderror
            </div>
            
            <button type="submit" style="width: 100%; padding: 10px 20px; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: #fff; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 0.9rem;">
                <i class="fas fa-paper-plane"></i>
                Publier
            </button>
        </form>
    </div>

    <!-- Liste des commentaires -->
    @if($comments->count() > 0)
    <div class="comments-list" style="space-y: 20px;">
        @foreach($comments as $comment)
        <div class="comment-item" style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(6, 182, 212, 0.2); border-radius: 12px; padding: 15px; margin-bottom: 15px;">
            <div class="comment-header" style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                <div style="width: 35px; height: 35px; background: linear-gradient(135deg, #06b6d4, #14b8a6); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #fff; font-size: 0.9rem; flex-shrink: 0;">
                    {{ strtoupper(substr($comment->author_name, 0, 1)) }}
                </div>
                <div style="flex: 1; min-width: 0;">
                    <h4 style="font-size: 0.85rem; font-weight: 700; color: #fff; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ $comment->author_name }}
                    </h4>
                    <p style="font-size: 0.7rem; color: rgba(255, 255, 255, 0.6);">
                        <i class="fas fa-clock mr-1"></i>{{ $comment->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>
            
            <div class="comment-content" style="color: rgba(255, 255, 255, 0.9); line-height: 1.5; margin-bottom: 12px; font-size: 0.85rem; word-wrap: break-word;">
                {!! nl2br(e($comment->content)) !!}
            </div>
            
            <div class="comment-actions" style="display: flex; align-items: center; gap: 10px;">
                <button onclick="likeComment({{ $comment->id }})" class="comment-like-btn" style="background: transparent; border: 1px solid rgba(6, 182, 212, 0.3); color: rgba(255, 255, 255, 0.7); padding: 6px 12px; border-radius: 6px; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 6px; font-size: 0.8rem;">
                    <i class="fas fa-heart"></i>
                    <span id="likes-{{ $comment->id }}">{{ $comment->likes }}</span>
                </button>
                
                <button onclick="replyToComment({{ $comment->id }})" style="background: transparent; border: 1px solid rgba(6, 182, 212, 0.3); color: rgba(255, 255, 255, 0.7); padding: 6px 12px; border-radius: 6px; cursor: pointer; transition: all 0.3s ease; font-size: 0.8rem;">
                    <i class="fas fa-reply mr-1"></i>Répondre
                </button>
            </div>
            
            <!-- Réponses -->
            @if($comment->replies->count() > 0)
            <div class="comment-replies" style="margin-top: 15px; padding-left: 20px; border-left: 2px solid rgba(6, 182, 212, 0.2);">
                @foreach($comment->replies as $reply)
                <div class="reply-item" style="background: rgba(15, 23, 42, 0.4); border: 1px solid rgba(6, 182, 212, 0.15); border-radius: 10px; padding: 12px; margin-bottom: 10px;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                        <div style="width: 28px; height: 28px; background: linear-gradient(135deg, #06b6d4, #14b8a6); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #fff; font-size: 0.75rem; flex-shrink: 0;">
                            {{ strtoupper(substr($reply->author_name, 0, 1)) }}
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <h5 style="font-size: 0.8rem; font-weight: 700; color: #fff; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $reply->author_name }}</h5>
                            <p style="font-size: 0.65rem; color: rgba(255, 255, 255, 0.6);">{{ $reply->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <p style="color: rgba(255, 255, 255, 0.85); line-height: 1.5; font-size: 0.8rem; word-wrap: break-word;">{!! nl2br(e($reply->content)) !!}</p>
                </div>
                @endforeach
            </div>
            @endif
            
            <!-- Formulaire de réponse (masqué par défaut) -->
            <div id="reply-form-{{ $comment->id }}" style="display: none; margin-top: 15px; padding-top: 15px; border-top: 1px solid rgba(6, 182, 212, 0.2);">
                <form action="{{ route('comments.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="commentable_type" value="{{ get_class($commentable) }}">
                    <input type="hidden" name="commentable_id" value="{{ $commentable->id }}">
                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                    
                    <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 12px;">
                        <input type="text" name="name" required maxlength="255" placeholder="Votre nom" 
                               style="width: 100%; padding: 8px 10px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: #fff; font-size: 0.85rem;">
                        <input type="email" name="email" required maxlength="255" placeholder="votre@email.com" 
                               style="width: 100%; padding: 8px 10px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: #fff; font-size: 0.85rem;">
                        <input type="text" name="phone" maxlength="20" placeholder="+221 XX XXX XX XX" 
                               style="width: 100%; padding: 8px 10px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: #fff; font-size: 0.85rem;">
                    </div>
                    
                    <textarea name="content" rows="2" required minlength="10" maxlength="2000" 
                              placeholder="Écrivez votre réponse..." 
                              style="width: 100%; padding: 10px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: #fff; font-size: 0.85rem; resize: vertical; margin-bottom: 10px;"></textarea>
                    
                    <div style="display: flex; gap: 8px;">
                        <button type="submit" style="flex: 1; padding: 8px 16px; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 0.85rem;">
                            <i class="fas fa-paper-plane mr-1"></i>Répondre
                        </button>
                        <button type="button" onclick="cancelReply({{ $comment->id }})" style="padding: 8px 16px; background: rgba(15, 23, 42, 0.8); color: rgba(255, 255, 255, 0.7); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; cursor: pointer; font-size: 0.85rem;">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div style="text-align: center; padding: 20px; color: rgba(255, 255, 255, 0.6);">
        <i class="fas fa-comments" style="font-size: 2rem; margin-bottom: 10px; opacity: 0.5;"></i>
        <p style="font-size: 0.85rem;">Aucun commentaire pour le moment. Soyez le premier à commenter!</p>
    </div>
    @endif
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
