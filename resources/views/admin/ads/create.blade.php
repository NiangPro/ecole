@extends('admin.layout')

@section('title', 'Nouvelle Publicité')

@section('styles')
@include('admin.ads.partials.form-styles')
@endsection

@section('content')
<div class="ads-form-wrapper">
    <!-- Hero Header -->
    <div class="form-hero">
        <div class="form-hero-content">
            <h1><i class="fas fa-magic mr-3"></i>Créer une Publicité</h1>
            <p>Créez une publicité moderne et attrayante pour promouvoir vos services sur le site — image ou vidéo YouTube</p>
        </div>
    </div>

    <form action="{{ route('admin.ads.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-grid">
            <!-- Colonne principale -->
            <div class="form-main">
                <!-- Informations de base -->
                <div class="form-card">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <h2 class="card-title">Informations de base</h2>
                    </div>

                    <div class="field-group">
                        <label class="field-label">
                            <span>Nom de la publicité</span>
                            <span class="required">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="field-input" placeholder="Ex: Bannière Yobali - Accueil">
                        @error('name')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label">Description</label>
                        <textarea name="description" rows="4" class="field-input"
                                  placeholder="Description optionnelle de la publicité">{{ old('description') }}</textarea>
                    </div>
                </div>

                <!-- Format : Image ou Vidéo YouTube -->
                @include('admin.ads.partials.format-fields', ['ad' => null])
            </div>

            <!-- Sidebar -->
            <div class="sidebar-panel">
                <!-- Paramètres d'affichage -->
                <div class="sidebar-card">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <h2 class="card-title">Paramètres</h2>
                    </div>

                    <div class="field-group">
                        <label class="field-label">
                            <span>Emplacement d'affichage</span>
                            <span class="required">*</span>
                        </label>
                        <select name="location" id="adLocation" required class="field-select">
                            <option value="" {{ old('location', '') === '' ? 'selected' : '' }}>
                                🌐 Général — Accueil (bloc Partenaires) + sidebar des articles
                            </option>
                            <option value="homepage_after_exercises" {{ old('location') === 'homepage_after_exercises' ? 'selected' : '' }}>
                                🏠 Accueil — bloc unique (après Exercices &amp; Quiz)
                            </option>
                            <option value="article_sidebar" {{ old('location') === 'article_sidebar' ? 'selected' : '' }}>
                                📄 Articles emploi — Sidebar uniquement
                            </option>
                        </select>
                        @error('location')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="location-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <div class="location-warning-text">
                                <strong>Important :</strong> c'est ce champ, et lui seul, qui décide où la publicité apparaît. Choisissez avec soin !
                            </div>
                        </div>
                    </div>

                    <div class="field-group">
                        <label class="field-label">
                            <span>Statut</span>
                            <span class="required">*</span>
                        </label>
                        <select name="status" required class="field-select">
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Actif</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactif</option>
                        </select>
                        @error('status')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label">Ordre d'affichage</label>
                        <input type="number" name="order" value="{{ old('order', 0) }}" min="0"
                               class="field-input">
                        <p class="field-help">
                            <i class="fas fa-sort-numeric-down"></i>
                            Plus petit = affiché en premier
                        </p>
                    </div>

                    <div class="field-group">
                        <label class="field-label">Date de début</label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}"
                               class="field-input">
                    </div>

                    <div class="field-group">
                        <label class="field-label">Date de fin</label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}"
                               class="field-input">
                    </div>
                </div>

                <!-- Actions -->
                <div class="sidebar-card">
                    <div class="action-buttons" style="margin-top: 0; padding-top: 0; border-top: none;">
                        <a href="{{ route('admin.ads.index') }}" class="btn-cancel">
                            <i class="fas fa-times"></i>
                            Annuler
                        </a>
                        <button type="submit" class="btn-save">
                            <i class="fas fa-save"></i>
                            Créer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
@include('admin.ads.partials.format-scripts')
@endsection
