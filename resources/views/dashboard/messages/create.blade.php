@extends('dashboard.layout')

@section('dashboard-content')
@php
    if (!app()->getLocale()) {
        app()->setLocale(session('language', 'fr'));
    }
    $pageTitle = 'Nouveau message';
    $pageDescription = 'Envoyez un message à un utilisateur';
@endphp

<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2 class="card-title dashboard-text-primary">
                <i class="fas fa-envelope"></i>
                {{ $pageTitle }}
            </h2>
            <p class="dashboard-text-secondary" style="margin: 0.5rem 0 0 0;">
                {{ $pageDescription }}
            </p>
        </div>
        <a href="{{ route('dashboard.messages.index') }}" style="
            color: #06b6d4;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
        ">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <form action="{{ route('dashboard.messages.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <div>
                <label for="receiver_id" style="
                    display: block;
                    margin-bottom: 0.5rem;
                    font-weight: 600;
                    color: inherit;
                ">
                    Destinataire <span style="color: #ef4444;">*</span>
                </label>
                <select name="receiver_id" id="receiver_id" required style="
                    width: 100%;
                    padding: 0.875rem 1rem;
                    border: 2px solid rgba(6, 182, 212, 0.2);
                    border-radius: 12px;
                    font-size: 0.95rem;
                    font-family: inherit;
                    background: white;
                    transition: all 0.3s ease;
                " onfocus="this.style.borderColor='#06b6d4';" onblur="this.style.borderColor='rgba(6, 182, 212, 0.2)';">
                    <option value="">Sélectionner un utilisateur</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ $receiver && $receiver->id == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('receiver_id')
                <div style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div>
                <label for="subject" style="
                    display: block;
                    margin-bottom: 0.5rem;
                    font-weight: 600;
                    color: inherit;
                ">
                    Sujet (optionnel)
                </label>
                <input type="text" name="subject" id="subject" value="{{ old('subject') }}" style="
                    width: 100%;
                    padding: 0.875rem 1rem;
                    border: 2px solid rgba(6, 182, 212, 0.2);
                    border-radius: 12px;
                    font-size: 0.95rem;
                    font-family: inherit;
                    transition: all 0.3s ease;
                " onfocus="this.style.borderColor='#06b6d4';" onblur="this.style.borderColor='rgba(6, 182, 212, 0.2)';" placeholder="Sujet du message">
                @error('subject')
                <div style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div>
                <label for="body" style="
                    display: block;
                    margin-bottom: 0.5rem;
                    font-weight: 600;
                    color: inherit;
                ">
                    Message <span style="color: #ef4444;">*</span>
                </label>
                <textarea name="body" id="body" rows="8" required style="
                    width: 100%;
                    padding: 1rem;
                    border: 2px solid rgba(6, 182, 212, 0.2);
                    border-radius: 12px;
                    font-size: 0.95rem;
                    font-family: inherit;
                    resize: vertical;
                    transition: all 0.3s ease;
                " onfocus="this.style.borderColor='#06b6d4';" onblur="this.style.borderColor='rgba(6, 182, 212, 0.2)';" placeholder="Tapez votre message...">{{ old('body') }}</textarea>
                @error('body')
                <div style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div>
                <label for="attachments" style="
                    display: block;
                    margin-bottom: 0.5rem;
                    font-weight: 600;
                    color: inherit;
                ">
                    Pièces jointes (optionnel, max 5 fichiers, 10MB chacun)
                </label>
                <div style="
                    padding: 1rem;
                    border: 2px dashed rgba(6, 182, 212, 0.3);
                    border-radius: 12px;
                    text-align: center;
                    background: rgba(6, 182, 212, 0.02);
                    transition: all 0.3s ease;
                " onmouseover="this.style.borderColor='#06b6d4'; this.style.background='rgba(6, 182, 212, 0.05)';" 
                   onmouseout="this.style.borderColor='rgba(6, 182, 212, 0.3)'; this.style.background='rgba(6, 182, 212, 0.02)';">
                    <input type="file" name="attachments[]" id="attachments" multiple accept="image/*,.pdf,.doc,.docx,.txt" style="display: none;" onchange="updateFileList(this)">
                    <label for="attachments" style="
                        display: inline-flex;
                        align-items: center;
                        gap: 0.5rem;
                        padding: 0.75rem 1.5rem;
                        background: linear-gradient(135deg, #06b6d4, #14b8a6);
                        color: white;
                        border-radius: 8px;
                        cursor: pointer;
                        font-weight: 600;
                        transition: all 0.3s ease;
                    " onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='translateY(0)';">
                        <i class="fas fa-paperclip"></i> Choisir des fichiers
                    </label>
                    <p style="margin: 0.5rem 0 0 0; font-size: 0.85rem; color: rgba(100, 116, 139, 1);">
                        Formats acceptés: Images, PDF, DOC, DOCX, TXT
                    </p>
                </div>
                <div id="file-list" style="margin-top: 1rem; font-size: 0.85rem; color: rgba(100, 116, 139, 1);"></div>
                @error('attachments.*')
                <div style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1rem;">
                <a href="{{ route('dashboard.messages.index') }}" style="
                    padding: 0.75rem 1.5rem;
                    border: 2px solid rgba(6, 182, 212, 0.3);
                    border-radius: 8px;
                    color: #06b6d4;
                    text-decoration: none;
                    font-weight: 600;
                    font-size: 0.9rem;
                    transition: all 0.3s ease;
                " onmouseover="this.style.background='rgba(6, 182, 212, 0.1)';" onmouseout="this.style.background='transparent';">
                    Annuler
                </a>
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
    function updateFileList(input) {
        const fileList = document.getElementById('file-list');
        if (input.files.length > 0) {
            let html = '<div style="display: flex; flex-direction: column; gap: 0.5rem; padding: 1rem; background: rgba(6, 182, 212, 0.05); border-radius: 8px;">';
            html += '<div style="font-weight: 600; margin-bottom: 0.5rem;">Fichiers sélectionnés:</div>';
            for (let i = 0; i < input.files.length; i++) {
                const file = input.files[i];
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                html += `<div style="display: flex; align-items: center; justify-content: space-between; padding: 0.5rem; background: white; border-radius: 6px;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-file"></i>
                        <span>${file.name}</span>
                        <span style="color: rgba(100, 116, 139, 1); font-size: 0.85rem;">(${fileSize} MB)</span>
                    </div>
                </div>`;
            }
            html += '</div>';
            fileList.innerHTML = html;
        } else {
            fileList.innerHTML = '';
        }
    }
</script>
@endpush

@endsection


