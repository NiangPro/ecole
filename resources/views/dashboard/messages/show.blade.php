@extends('dashboard.layout')

@section('dashboard-content')
@php
    if (!app()->getLocale()) {
        app()->setLocale(session('language', 'fr'));
    }
    $pageTitle = 'Conversation avec ' . $otherUser->name;
@endphp

<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 2px solid rgba(6, 182, 212, 0.2);">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <a href="{{ route('dashboard.messages.index') }}" style="
                color: #06b6d4;
                font-size: 1.2rem;
                text-decoration: none;
                display: flex;
                align-items: center;
                justify-content: center;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background: rgba(6, 182, 212, 0.1);
                transition: all 0.3s ease;
            " onmouseover="this.style.background='rgba(6, 182, 212, 0.2)';" onmouseout="this.style.background='rgba(6, 182, 212, 0.1)';">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="user-avatar" style="
                width: 50px;
                height: 50px;
                border-radius: 50%;
                background: linear-gradient(135deg, #06b6d4, #14b8a6);
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 1.25rem;
                font-weight: 700;
            ">
                {{ strtoupper(substr($otherUser->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="card-title dashboard-text-primary" style="margin: 0; font-size: 1.25rem;">
                    {{ $otherUser->name }}
                </h2>
                <p class="dashboard-text-secondary" style="margin: 0.25rem 0 0 0; font-size: 0.85rem;">
                    {{ $otherUser->email }}
                </p>
            </div>
        </div>
        <form action="{{ route('dashboard.messages.delete-conversation', $conversation->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette conversation ?');">
            @csrf
            @method('POST')
            <button type="submit" style="
                background: rgba(239, 68, 68, 0.1);
                color: #ef4444;
                padding: 0.5rem 1rem;
                border-radius: 8px;
                border: 1px solid rgba(239, 68, 68, 0.3);
                font-weight: 600;
                font-size: 0.85rem;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
            ">
                <i class="fas fa-trash"></i> Supprimer
            </button>
        </form>
    </div>

    <div id="messages-container" style="
        max-height: 500px;
        overflow-y: auto;
        padding: 1rem;
        margin-bottom: 2rem;
        background: rgba(6, 182, 212, 0.02);
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    ">
        @foreach($messages as $message)
            <div class="message-item" style="
                display: flex;
                flex-direction: column;
                align-items: {{ $message->sender_id == auth()->id() ? 'flex-end' : 'flex-start' }};
                gap: 0.5rem;
            ">
                <div style="
                    max-width: 70%;
                    padding: 1rem 1.25rem;
                    background: {{ $message->sender_id == auth()->id() ? 'linear-gradient(135deg, #06b6d4, #14b8a6)' : 'rgba(6, 182, 212, 0.1)' }};
                    color: {{ $message->sender_id == auth()->id() ? 'white' : 'inherit' }};
                    border-radius: {{ $message->sender_id == auth()->id() ? '18px 18px 4px 18px' : '18px 18px 18px 4px' }};
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                ">
                    @if($message->subject && $message->sender_id != auth()->id())
                    <div style="font-weight: 700; margin-bottom: 0.5rem; font-size: 0.9rem;">
                        {{ $message->subject }}
                    </div>
                    @endif
                    <div style="line-height: 1.6; white-space: pre-wrap; word-wrap: break-word;">
                        {{ $message->body }}
                    </div>
                    @if($message->attachments->count() > 0)
                    <div style="margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px solid rgba(255, 255, 255, 0.2); display: flex; flex-direction: column; gap: 0.5rem;">
                        @foreach($message->attachments as $attachment)
                        <a href="{{ route('dashboard.messages.download-attachment', $attachment->id) }}" style="
                            display: flex;
                            align-items: center;
                            gap: 0.5rem;
                            padding: 0.5rem;
                            background: rgba(255, 255, 255, 0.2);
                            border-radius: 8px;
                            text-decoration: none;
                            color: inherit;
                            font-size: 0.85rem;
                        ">
                            <i class="fas fa-paperclip"></i>
                            <span>{{ $attachment->file_name }}</span>
                            <span style="opacity: 0.7;">({{ $attachment->formatted_size }})</span>
                        </a>
                        @endforeach
                    </div>
                    @endif
                    <div style="
                        margin-top: 0.5rem;
                        font-size: 0.75rem;
                        opacity: 0.7;
                        display: flex;
                        align-items: center;
                        gap: 0.5rem;
                    ">
                        <span>{{ $message->created_at->format('d/m/Y H:i') }}</span>
                        @if($message->sender_id == auth()->id())
                            @if($message->is_read)
                            <i class="fas fa-check-double" style="color: #06b6d4;"></i>
                            @else
                            <i class="fas fa-check"></i>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <form id="reply-form" action="{{ route('dashboard.messages.reply', $conversation->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <div>
                <textarea name="body" id="message-body" rows="4" required style="
                    width: 100%;
                    padding: 1rem;
                    border: 2px solid rgba(6, 182, 212, 0.2);
                    border-radius: 12px;
                    font-size: 0.95rem;
                    font-family: inherit;
                    resize: vertical;
                    transition: all 0.3s ease;
                " onfocus="this.style.borderColor='#06b6d4';" onblur="this.style.borderColor='rgba(6, 182, 212, 0.2)';" placeholder="Tapez votre message..."></textarea>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <label for="attachments" style="
                        display: inline-flex;
                        align-items: center;
                        gap: 0.5rem;
                        padding: 0.5rem 1rem;
                        background: rgba(6, 182, 212, 0.1);
                        color: #06b6d4;
                        border-radius: 8px;
                        cursor: pointer;
                        font-size: 0.85rem;
                        font-weight: 600;
                        transition: all 0.3s ease;
                    " onmouseover="this.style.background='rgba(6, 182, 212, 0.2)';" onmouseout="this.style.background='rgba(6, 182, 212, 0.1)';">
                        <i class="fas fa-paperclip"></i> Pièces jointes
                    </label>
                    <input type="file" name="attachments[]" id="attachments" multiple accept="image/*,.pdf,.doc,.docx,.txt" style="display: none;" onchange="updateFileList(this)">
                    <div id="file-list" style="margin-top: 0.5rem; font-size: 0.8rem; color: rgba(100, 116, 139, 1);"></div>
                </div>
                <button type="submit" style="
                    background: linear-gradient(135deg, #06b6d4, #14b8a6);
                    color: white;
                    padding: 0.75rem 1.5rem;
                    border-radius: 8px;
                    border: none;
                    font-weight: 600;
                    font-size: 0.9rem;
                    cursor: pointer;
                    display: inline-flex;
                    align-items: center;
                    gap: 0.5rem;
                    transition: all 0.3s ease;
                " onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 5px 15px rgba(6, 182, 212, 0.3)';" 
                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                    <i class="fas fa-paper-plane"></i> Envoyer
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Scroll vers le bas au chargement
    window.addEventListener('load', function() {
        const container = document.getElementById('messages-container');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    });

    // Gestion des fichiers
    function updateFileList(input) {
        const fileList = document.getElementById('file-list');
        if (input.files.length > 0) {
            let html = '<div style="display: flex; flex-direction: column; gap: 0.25rem;">';
            for (let i = 0; i < input.files.length; i++) {
                html += `<div style="display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-file"></i>
                    <span>${input.files[i].name}</span>
                </div>`;
            }
            html += '</div>';
            fileList.innerHTML = html;
        } else {
            fileList.innerHTML = '';
        }
    }

    // Soumission du formulaire via AJAX
    document.getElementById('reply-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi...';
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Recharger la page pour afficher le nouveau message
                window.location.reload();
            } else {
                alert('Erreur lors de l\'envoi du message.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de l\'envoi du message.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });
</script>
@endpush

@endsection


