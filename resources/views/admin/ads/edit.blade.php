@extends('admin.layout')

@section('title', 'Modifier Publicité')

@section('styles')
@include('admin.ads.partials.form-styles')
@endsection

@section('content')
<div class="ads-form-wrapper">
    <!-- Hero Header -->
    <div class="form-hero">
        <div class="form-hero-content">
            <h1><i class="fas fa-edit mr-3"></i>Modifier la Publicité</h1>
            <p>Mettez à jour les informations de "{{ $ad->name }}"</p>
        </div>
    </div>

    <form action="{{ route('admin.ads.update', $ad->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

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
                        <input type="text" name="name" value="{{ old('name', $ad->name) }}" required
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
                                  placeholder="Description optionnelle de la publicité">{{ old('description', $ad->description) }}</textarea>
                    </div>
                </div>

                <!-- Format : Image ou Vidéo YouTube -->
                @include('admin.ads.partials.format-fields', ['ad' => $ad])
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
                            <option value="" {{ old('location', $ad->location ?? '') === '' ? 'selected' : '' }}>
                                🌐 Général — Accueil (bloc Partenaires) + sidebar des articles
                            </option>
                            <option value="homepage_after_exercises" {{ old('location', $ad->location) === 'homepage_after_exercises' ? 'selected' : '' }}>
                                🏠 Accueil — bloc unique (après Exercices &amp; Quiz)
                            </option>
                            <option value="article_sidebar" {{ old('location', $ad->location) === 'article_sidebar' ? 'selected' : '' }}>
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
                            <option value="active" {{ old('status', $ad->status) === 'active' ? 'selected' : '' }}>Actif</option>
                            <option value="inactive" {{ old('status', $ad->status) === 'inactive' ? 'selected' : '' }}>Inactif</option>
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
                        <input type="number" name="order" value="{{ old('order', $ad->order) }}" min="0"
                               class="field-input">
                        <p class="field-help">
                            <i class="fas fa-sort-numeric-down"></i>
                            Plus petit = affiché en premier
                        </p>
                    </div>

                    <div class="field-group">
                        <label class="field-label">Date de début</label>
                        <input type="date" name="start_date" value="{{ old('start_date', $ad->start_date ? $ad->start_date->format('Y-m-d') : '') }}"
                               class="field-input">
                    </div>

                    <div class="field-group">
                        <label class="field-label">Date de fin</label>
                        <input type="date" name="end_date" value="{{ old('end_date', $ad->end_date ? $ad->end_date->format('Y-m-d') : '') }}"
                               class="field-input">
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="stats-card">
                    <div class="stats-title">
                        <i class="fas fa-chart-line"></i>
                        <span>Statistiques</span>
                    </div>
                    <div class="stats-grid">
                        <div class="stat-box">
                            <div class="stat-value">{{ number_format($ad->impressions) }}</div>
                            <div class="stat-label">Impressions</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-value">{{ number_format($ad->clicks) }}</div>
                            <div class="stat-label">Clics</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-value">{{ $ad->impressions > 0 ? number_format(($ad->clicks / $ad->impressions) * 100, 2) : '0.00' }}%</div>
                            <div class="stat-label">CTR</div>
                        </div>
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
                            Enregistrer
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
