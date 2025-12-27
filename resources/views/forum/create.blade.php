@extends('layouts.app')

@section('title', 'Créer un topic - Forum - NiangProgrammeur')

@push('styles')
<style>
.forum-page {
    min-height: 100vh;
    padding: 2rem 1rem;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
}

body.dark-mode .forum-page {
    background: linear-gradient(135deg, #0a0a0f 0%, #1a1a2e 100%);
}

.forum-container {
    max-width: 800px;
    margin: 0 auto;
}

.create-topic-card {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

body.dark-mode .create-topic-card {
    background: rgba(15, 23, 42, 0.8);
    border: 1px solid rgba(6, 182, 212, 0.2);
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: rgba(30, 41, 59, 1);
}

body.dark-mode .form-label {
    color: rgba(226, 232, 240, 1);
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
    font-family: inherit;
}

body.dark-mode .form-control {
    background: rgba(30, 41, 59, 0.5);
    color: rgba(226, 232, 240, 1);
    border-color: rgba(6, 182, 212, 0.3);
}

.form-control:focus {
    outline: none;
    border-color: #06b6d4;
    box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.1);
}

textarea.form-control {
    min-height: 300px;
    resize: vertical;
}

select.form-control {
    cursor: pointer;
}

.btn-primary {
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    color: white;
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 1rem;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(6, 182, 212, 0.3);
}

.btn-secondary {
    background: rgba(100, 116, 139, 0.2);
    color: rgba(100, 116, 139, 1);
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
    margin-right: 1rem;
}

.btn-secondary:hover {
    background: rgba(100, 116, 139, 0.3);
}

.help-text {
    font-size: 0.85rem;
    color: rgba(100, 116, 139, 1);
    margin-top: 0.5rem;
}

.error-message {
    color: #ef4444;
    font-size: 0.85rem;
    margin-top: 0.5rem;
}
</style>
@endpush

@section('content')
<div class="forum-page">
    <div class="forum-container">
        <div style="margin-bottom: 1.5rem;">
            <a href="{{ route('forum.index') }}" style="color: #06b6d4; text-decoration: none;">
                <i class="fas fa-arrow-left"></i> Retour au forum
            </a>
        </div>

        <div class="create-topic-card">
            <h1 style="font-size: 2rem; font-weight: 800; margin-bottom: 2rem;">
                <i class="fas fa-plus-circle" style="color: #06b6d4;"></i> Créer un nouveau topic
            </h1>

            <form action="{{ route('forum.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="category_id" class="form-label">
                        <i class="fas fa-folder"></i> Catégorie *
                    </label>
                    <select name="category_id" id="category_id" class="form-control" required>
                        <option value="">Sélectionnez une catégorie</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ (old('category_id', $categoryId) == $cat->id) ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="title" class="form-label">
                        <i class="fas fa-heading"></i> Titre du topic *
                    </label>
                    <input type="text" name="title" id="title" class="form-control" 
                           value="{{ old('title') }}" required minlength="5" maxlength="255"
                           placeholder="Ex: Comment utiliser les migrations Laravel ?">
                    <p class="help-text">Minimum 5 caractères, maximum 255 caractères</p>
                    @error('title')
                    <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="body" class="form-label">
                        <i class="fas fa-align-left"></i> Contenu du topic *
                    </label>
                    <textarea name="body" id="body" class="form-control" required minlength="10"
                              placeholder="Décrivez votre question ou votre sujet de discussion en détail...">{{ old('body') }}</textarea>
                    <p class="help-text">Minimum 10 caractères. Soyez clair et précis dans votre description.</p>
                    @error('body')
                    <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <a href="{{ route('forum.index') }}" class="btn-secondary">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-paper-plane"></i> Publier le topic
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

