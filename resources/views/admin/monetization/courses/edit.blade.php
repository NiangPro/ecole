@extends('admin.layout')

@php
    use Illuminate\Support\Str;
@endphp

@section('title', 'Modifier un Cours Payant - Admin')

@push('styles')
<style>
    body.light-mode .course-form-page h1,
    body.light-mode .course-form-page h3 {
        color: rgba(30, 41, 59, 0.9) !important;
    }

    body.light-mode .course-form-page p,
    body.light-mode .course-form-page label {
        color: rgba(30, 41, 59, 0.8) !important;
    }

    body.light-mode .course-form-page .form-card {
        background: rgba(255, 255, 255, 0.9) !important;
        border-color: rgba(6, 182, 212, 0.2) !important;
    }

    body.light-mode .course-form-page input,
    body.light-mode .course-form-page select,
    body.light-mode .course-form-page textarea {
        background: rgba(255, 255, 255, 0.95) !important;
        color: rgba(30, 41, 59, 0.9) !important;
        border-color: rgba(6, 182, 212, 0.3) !important;
    }

    body.light-mode .course-form-page input::placeholder,
    body.light-mode .course-form-page textarea::placeholder {
        color: rgba(30, 41, 59, 0.5) !important;
    }

    /* Styles pour l'accordéon des chapitres */
    .chapter-accordion-header:hover {
        background: rgba(6, 182, 212, 0.15) !important;
    }

    .chapter-accordion-content {
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            max-height: 0;
        }
        to {
            opacity: 1;
            max-height: 5000px;
        }
    }

    body.light-mode .chapter-item {
        background: rgba(255, 255, 255, 0.9) !important;
        border-color: rgba(6, 182, 212, 0.2) !important;
    }

    body.light-mode .chapter-accordion-header {
        background: rgba(6, 182, 212, 0.05) !important;
        border-bottom-color: rgba(6, 182, 212, 0.2) !important;
    }

    body.light-mode .chapter-accordion-header h4 {
        color: rgba(30, 41, 59, 0.9) !important;
    }

    body.light-mode .chapter-accordion-header p {
        color: rgba(30, 41, 59, 0.7) !important;
    }
</style>
@endpush

@section('content')
<div class="course-form-page" style="padding: 2rem;">
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 2.5rem; font-weight: 800; color: white; margin-bottom: 0.5rem;">
            <i class="fas fa-edit" style="color: #06b6d4; margin-right: 15px;"></i>
            Modifier le Cours : {{ $course->title_fr ?? $course->title }}
        </h1>
        <p style="color: rgba(255, 255, 255, 0.7); font-size: 1.1rem;">
            Modifier les informations de ce cours payant
        </p>
    </div>

    <div style="display: flex; gap: 20px; margin-bottom: 25px;">
        <a href="{{ route('admin.monetization.courses.show', $course->id) }}" style="padding: 12px 24px; background: rgba(6, 182, 212, 0.2); color: #06b6d4; border: 2px solid #06b6d4; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
            <i class="fas fa-eye" style="margin-right: 8px;"></i>
            Voir le cours
        </a>
        <a href="{{ route('admin.monetization.courses.index') }}" style="padding: 12px 24px; background: rgba(107, 114, 128, 0.2); color: #6b7280; border: 2px solid #6b7280; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
            <i class="fas fa-arrow-left" style="margin-right: 8px;"></i>
            Retour à la liste
        </a>
    </div>

    @if($errors->any())
    <div style="padding: 15px 20px; background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; border-radius: 12px; color: #ef4444; margin-bottom: 25px;">
        <strong>Erreurs de validation :</strong>
        <ul style="margin-top: 10px; margin-left: 20px;">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="form-card" style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 40px; max-width: 1000px;">
        <form action="{{ route('admin.monetization.courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div style="display: grid; gap: 30px;">
                <!-- Informations de Base -->
                <div>
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: white; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">
                        <i class="fas fa-info-circle" style="color: #06b6d4; margin-right: 10px;"></i>
                        Informations de Base
                    </h3>
                    
                    <div style="display: grid; gap: 20px;">
                        <div>
                            <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                Titre du Cours (Par défaut) <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="text" name="title" value="{{ old('title', $course->title) }}" required style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;" placeholder="Ex: Formation Complète Laravel">
                            <p style="color: rgba(255, 255, 255, 0.6); font-size: 0.85rem; margin-top: 5px;">Titre par défaut (utilisé si les traductions ne sont pas disponibles)</p>
                            @error('title')
                            <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div>
                                <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                    <i class="fas fa-flag" style="color: #10b981; margin-right: 5px;"></i>
                                    Titre (Français) <span style="color: #ef4444;">*</span>
                                </label>
                                <input type="text" name="title_fr" value="{{ old('title_fr', $course->title_fr) }}" required style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;" placeholder="Ex: Formation Complète Laravel">
                                @error('title_fr')
                                <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                    <i class="fas fa-flag" style="color: #ef4444; margin-right: 5px;"></i>
                                    Titre (English)
                                </label>
                                <input type="text" name="title_en" value="{{ old('title_en', $course->title_en) }}" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;" placeholder="Ex: Complete Laravel Course">
                                @error('title_en')
                                <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                Slug (URL) <span style="color: rgba(255, 255, 255, 0.5); font-size: 0.85rem;">(Généré automatiquement si vide)</span>
                            </label>
                            <input type="text" name="slug" value="{{ old('slug', $course->slug) }}" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;" placeholder="formation-complete-laravel">
                            @error('slug')
                            <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                Description (Par défaut)
                            </label>
                            <textarea name="description" rows="4" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem; resize: vertical;" placeholder="Description courte du cours...">{{ old('description', $course->description) }}</textarea>
                            <p style="color: rgba(255, 255, 255, 0.6); font-size: 0.85rem; margin-top: 5px;">Description par défaut (utilisée si les traductions ne sont pas disponibles)</p>
                            @error('description')
                            <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div>
                                <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                    <i class="fas fa-flag" style="color: #10b981; margin-right: 5px;"></i>
                                    Description (Français)
                                </label>
                                <textarea name="description_fr" rows="4" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem; resize: vertical;" placeholder="Description courte du cours...">{{ old('description_fr', $course->description_fr) }}</textarea>
                                @error('description_fr')
                                <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                    <i class="fas fa-flag" style="color: #ef4444; margin-right: 5px;"></i>
                                    Description (English)
                                </label>
                                <textarea name="description_en" rows="4" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem; resize: vertical;" placeholder="Short course description...">{{ old('description_en', $course->description_en) }}</textarea>
                                @error('description_en')
                                <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                Contenu Complet (Par défaut)
                            </label>
                            <textarea name="content" rows="10" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem; resize: vertical;" placeholder="Contenu détaillé du cours...">{{ old('content', $course->content) }}</textarea>
                            <p style="color: rgba(255, 255, 255, 0.6); font-size: 0.85rem; margin-top: 5px;">Contenu par défaut (utilisé si les traductions ne sont pas disponibles)</p>
                            @error('content')
                            <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div>
                                <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                    <i class="fas fa-flag" style="color: #10b981; margin-right: 5px;"></i>
                                    Contenu Complet (Français)
                                </label>
                                <textarea name="content_fr" rows="10" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem; resize: vertical;" placeholder="Contenu détaillé du cours...">{{ old('content_fr', $course->content_fr) }}</textarea>
                                @error('content_fr')
                                <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                    <i class="fas fa-flag" style="color: #ef4444; margin-right: 5px;"></i>
                                    Contenu Complet (English)
                                </label>
                                <textarea name="content_en" rows="10" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem; resize: vertical;" placeholder="Detailed course content...">{{ old('content_en', $course->content_en) }}</textarea>
                                @error('content_en')
                                <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                Image de Couverture
                            </label>
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                    Type d'image
                                </label>
                                <select name="cover_type" id="coverType" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem; margin-bottom: 15px;">
                                    <option value="internal" {{ old('cover_type', $course->cover_type ?? 'internal') === 'internal' ? 'selected' : '' }}>Interne (upload)</option>
                                    <option value="external" {{ old('cover_type', $course->cover_type ?? 'external') === 'external' ? 'selected' : '' }}>Externe (URL)</option>
                                </select>
                            </div>
                            
                            <div id="internalImage" style="display: {{ old('cover_type', $course->cover_type ?? 'internal') === 'internal' ? 'block' : 'none' }};">
                                @if($course->cover_image && ($course->cover_type ?? 'internal') === 'internal')
                                <div style="margin-bottom: 15px; padding: 15px; background: rgba(15, 23, 42, 0.6); border-radius: 8px;">
                                    <img src="{{ asset('storage/' . $course->cover_image) }}" alt="Image actuelle" style="max-width: 200px; max-height: 200px; border-radius: 8px; margin-bottom: 10px;">
                                    <div>
                                        <label style="display: flex; align-items: center; gap: 8px; color: rgba(255, 255, 255, 0.8); cursor: pointer;">
                                            <input type="checkbox" name="delete_image" value="1" style="cursor: pointer;">
                                            <span>Supprimer l'image actuelle</span>
                                        </label>
                                    </div>
                                </div>
                                @endif
                                <input type="file" name="cover_image_file" id="coverImageFile" accept="image/*" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;">
                                <p style="color: rgba(255, 255, 255, 0.6); font-size: 0.85rem; margin-top: 5px;">Formats acceptés: JPEG, PNG, JPG, GIF, WEBP (max 2MB).</p>
                                @error('cover_image_file')
                                <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div id="externalImage" style="display: {{ old('cover_type', $course->cover_type ?? 'internal') === 'external' ? 'block' : 'none' }};">
                                <input type="url" name="cover_image_url" id="coverImageUrl" value="{{ old('cover_image_url', ($course->cover_type ?? 'internal') === 'external' ? $course->cover_image : '') }}" placeholder="https://example.com/image.jpg" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;">
                                @if($course->cover_image && ($course->cover_type ?? 'internal') === 'external')
                                <div style="margin-top: 15px; padding: 15px; background: rgba(15, 23, 42, 0.6); border-radius: 8px;">
                                    <img src="{{ $course->cover_image }}" alt="Image actuelle" style="max-width: 200px; max-height: 200px; border-radius: 8px; margin-bottom: 10px;" onerror="this.style.display='none'">
                                </div>
                                @endif
                                <p style="color: rgba(255, 255, 255, 0.6); font-size: 0.85rem; margin-top: 5px;">Entrez l'URL complète de l'image (commençant par http:// ou https://).</p>
                                @error('cover_image_url')
                                <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Prix et Réductions -->
                <div>
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: white; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">
                        <i class="fas fa-tag" style="color: #06b6d4; margin-right: 10px;"></i>
                        Prix et Réductions
                    </h3>
                    
                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
                        <div>
                            <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                Prix <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="number" name="price" value="{{ old('price', $course->price) }}" required min="0" step="100" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;">
                            @error('price')
                            <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                Devise <span style="color: #ef4444;">*</span>
                            </label>
                            <select name="currency" required style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;">
                                <option value="XOF" {{ old('currency', $course->currency) === 'XOF' ? 'selected' : '' }}>XOF (FCFA)</option>
                                <option value="EUR" {{ old('currency', $course->currency) === 'EUR' ? 'selected' : '' }}>EUR</option>
                                <option value="USD" {{ old('currency', $course->currency) === 'USD' ? 'selected' : '' }}>USD</option>
                            </select>
                        </div>
                    </div>

                    <div style="margin-top: 20px; padding: 20px; background: rgba(251, 191, 36, 0.1); border: 1px solid rgba(251, 191, 36, 0.3); border-radius: 12px;">
                        <h4 style="color: #fbbf24; font-weight: 600; margin-bottom: 15px;">
                            <i class="fas fa-percent" style="margin-right: 8px;"></i>
                            Réduction (Optionnel)
                        </h4>
                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                            <div>
                                <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px; font-size: 0.9rem;">Prix Réduit</label>
                                <input type="number" name="discount_price" value="{{ old('discount_price', $course->discount_price) }}" min="0" step="100" style="width: 100%; padding: 10px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem;">
                            </div>
                            <div>
                                <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px; font-size: 0.9rem;">Date Début</label>
                                <input type="date" name="discount_start" value="{{ old('discount_start', $course->discount_start ? $course->discount_start->format('Y-m-d') : '') }}" style="width: 100%; padding: 10px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem;">
                            </div>
                            <div>
                                <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px; font-size: 0.9rem;">Date Fin</label>
                                <input type="date" name="discount_end" value="{{ old('discount_end', $course->discount_end ? $course->discount_end->format('Y-m-d') : '') }}" style="width: 100%; padding: 10px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations Complémentaires -->
                <div>
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: white; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">
                        <i class="fas fa-cog" style="color: #06b6d4; margin-right: 10px;"></i>
                        Informations Complémentaires
                    </h3>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div>
                            <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                Durée (heures)
                            </label>
                            <input type="number" name="duration_hours" value="{{ old('duration_hours', $course->duration_hours) }}" min="1" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;" placeholder="Ex: 40">
                            @error('duration_hours')
                            <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                Statut <span style="color: #ef4444;">*</span>
                            </label>
                            <select name="status" required style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;">
                                <option value="draft" {{ old('status', $course->status) === 'draft' ? 'selected' : '' }}>Brouillon</option>
                                <option value="published" {{ old('status', $course->status) === 'published' ? 'selected' : '' }}>Publié</option>
                                <option value="archived" {{ old('status', $course->status) === 'archived' ? 'selected' : '' }}>Archivé</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Ce que vous apprendrez (Multilingue) -->
                <div>
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: white; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">
                        <i class="fas fa-lightbulb" style="color: #06b6d4; margin-right: 10px;"></i>
                        Ce que vous apprendrez (Français)
                    </h3>
                    <div id="whatYouLearnListFr">
                        @php
                            $whatYouLearnFr = old('what_you_learn_fr', $course->what_you_learn_fr ?? []);
                            if (is_string($whatYouLearnFr)) {
                                $whatYouLearnFr = json_decode($whatYouLearnFr, true) ?? [];
                            }
                            if (empty($whatYouLearnFr)) {
                                $whatYouLearnFr = [''];
                            }
                        @endphp
                        @foreach($whatYouLearnFr as $item)
                        <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                            <input type="text" name="what_you_learn_fr[]" value="{{ $item }}" placeholder="Ex: Maîtriser les bases de Laravel" style="flex: 1; padding: 10px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white;">
                            <button type="button" onclick="removeItem(this)" style="background: #ef4444; color: white; border: none; border-radius: 8px; padding: 8px 12px; cursor: pointer;"><i class="fas fa-trash"></i></button>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" onclick="addItem('whatYouLearnListFr', 'what_you_learn_fr[]', 'Ex: Maîtriser les bases de Laravel')" style="margin-top: 10px; padding: 10px 15px; background: #10b981; color: white; border: none; border-radius: 8px; cursor: pointer;">
                        <i class="fas fa-plus" style="margin-right: 5px;"></i> Ajouter un élément
                    </button>
                </div>
                <div>
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: white; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">
                        <i class="fas fa-lightbulb" style="color: #06b6d4; margin-right: 10px;"></i>
                        Ce que vous apprendrez (English)
                    </h3>
                    <div id="whatYouLearnListEn">
                        @php
                            $whatYouLearnEn = old('what_you_learn_en', $course->what_you_learn_en ?? []);
                            if (is_string($whatYouLearnEn)) {
                                $whatYouLearnEn = json_decode($whatYouLearnEn, true) ?? [];
                            }
                            if (empty($whatYouLearnEn)) {
                                $whatYouLearnEn = [''];
                            }
                        @endphp
                        @foreach($whatYouLearnEn as $item)
                        <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                            <input type="text" name="what_you_learn_en[]" value="{{ $item }}" placeholder="Ex: Master Laravel basics" style="flex: 1; padding: 10px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white;">
                            <button type="button" onclick="removeItem(this)" style="background: #ef4444; color: white; border: none; border-radius: 8px; padding: 8px 12px; cursor: pointer;"><i class="fas fa-trash"></i></button>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" onclick="addItem('whatYouLearnListEn', 'what_you_learn_en[]', 'Ex: Master Laravel basics')" style="margin-top: 10px; padding: 10px 15px; background: #10b981; color: white; border: none; border-radius: 8px; cursor: pointer;">
                        <i class="fas fa-plus" style="margin-right: 5px;"></i> Add item
                    </button>
                </div>

                <!-- Prérequis (Multilingue) -->
                <div>
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: white; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">
                        <i class="fas fa-clipboard-list" style="color: #06b6d4; margin-right: 10px;"></i>
                        Prérequis (Français)
                    </h3>
                    <div id="requirementsListFr">
                        @php
                            $requirementsFr = old('requirements_fr', $course->requirements_fr ?? []);
                            if (is_string($requirementsFr)) {
                                $requirementsFr = json_decode($requirementsFr, true) ?? [];
                            }
                            if (empty($requirementsFr)) {
                                $requirementsFr = [''];
                            }
                        @endphp
                        @foreach($requirementsFr as $item)
                        <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                            <input type="text" name="requirements_fr[]" value="{{ $item }}" placeholder="Ex: Connaissances de base en PHP" style="flex: 1; padding: 10px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white;">
                            <button type="button" onclick="removeItem(this)" style="background: #ef4444; color: white; border: none; border-radius: 8px; padding: 8px 12px; cursor: pointer;"><i class="fas fa-trash"></i></button>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" onclick="addItem('requirementsListFr', 'requirements_fr[]', 'Ex: Connaissances de base en PHP')" style="margin-top: 10px; padding: 10px 15px; background: #10b981; color: white; border: none; border-radius: 8px; cursor: pointer;">
                        <i class="fas fa-plus" style="margin-right: 5px;"></i> Ajouter un prérequis
                    </button>
                </div>
                <div>
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: white; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">
                        <i class="fas fa-clipboard-list" style="color: #06b6d4; margin-right: 10px;"></i>
                        Prérequis (English)
                    </h3>
                    <div id="requirementsListEn">
                        @php
                            $requirementsEn = old('requirements_en', $course->requirements_en ?? []);
                            if (is_string($requirementsEn)) {
                                $requirementsEn = json_decode($requirementsEn, true) ?? [];
                            }
                            if (empty($requirementsEn)) {
                                $requirementsEn = [''];
                            }
                        @endphp
                        @foreach($requirementsEn as $item)
                        <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                            <input type="text" name="requirements_en[]" value="{{ $item }}" placeholder="Ex: Basic PHP knowledge" style="flex: 1; padding: 10px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white;">
                            <button type="button" onclick="removeItem(this)" style="background: #ef4444; color: white; border: none; border-radius: 8px; padding: 8px 12px; cursor: pointer;"><i class="fas fa-trash"></i></button>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" onclick="addItem('requirementsListEn', 'requirements_en[]', 'Ex: Basic PHP knowledge')" style="margin-top: 10px; padding: 10px 15px; background: #10b981; color: white; border: none; border-radius: 8px; cursor: pointer;">
                        <i class="fas fa-plus" style="margin-right: 5px;"></i> Add requirement
                    </button>
                </div>

                <!-- Section Programme du Cours (Chapitres) -->
                <div>
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: white; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">
                        <i class="fas fa-list-ol" style="color: #06b6d4; margin-right: 10px;"></i>
                        Programme du Cours (Chapitres)
                    </h3>
                    
                    <div id="chaptersContainer" style="display: flex; flex-direction: column; gap: 15px;">
                        @if($course->chapters && $course->chapters->count() > 0)
                            @foreach($course->chapters as $chapter)
                            <div class="chapter-item" style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 12px; overflow: hidden;">
                                <input type="hidden" name="chapters[{{ $loop->index }}][id]" value="{{ $chapter->id }}">
                                
                                <!-- En-tête de l'accordéon -->
                                <div class="chapter-accordion-header" onclick="toggleChapterAccordion(this)" style="padding: 20px; cursor: pointer; display: flex; align-items: center; justify-content: space-between; background: rgba(6, 182, 212, 0.1); border-bottom: 1px solid rgba(6, 182, 212, 0.3); transition: all 0.3s ease;">
                                    <div style="display: flex; align-items: center; gap: 15px; flex: 1;">
                                        <div style="width: 40px; height: 40px; background: rgba(6, 182, 212, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #06b6d4; font-size: 1.1rem;">
                                            {{ $loop->index + 1 }}
                                        </div>
                                        <div style="flex: 1;">
                                            <h4 style="margin: 0; color: white; font-weight: 600; font-size: 1.1rem;">
                                                {{ $chapter->title_fr ?? $chapter->title ?? 'Chapitre sans titre' }}
                                            </h4>
                                            @if($chapter->description_fr || $chapter->description)
                                            <p style="margin: 5px 0 0 0; color: rgba(255, 255, 255, 0.7); font-size: 0.9rem;">
                                                {{ Str::limit($chapter->description_fr ?? $chapter->description ?? '', 80) }}
                                            </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        @if($chapter->duration_minutes)
                                        <span style="padding: 5px 12px; background: rgba(6, 182, 212, 0.2); border-radius: 6px; color: #06b6d4; font-size: 0.85rem; font-weight: 600;">
                                            <i class="fas fa-clock" style="margin-right: 5px;"></i>{{ $chapter->duration_minutes }} min
                                        </span>
                                        @endif
                                        <i class="fas fa-chevron-down chapter-accordion-icon" style="color: #06b6d4; font-size: 1.2rem; transition: transform 0.3s ease;"></i>
                                    </div>
                                </div>
                                
                                <!-- Contenu de l'accordéon -->
                                <div class="chapter-accordion-content" style="display: none; padding: 20px;">
                                    <div style="display: grid; gap: 15px;">
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                        <div>
                                            <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                                <i class="fas fa-flag" style="color: #10b981; margin-right: 5px;"></i>
                                                Titre du Chapitre (Français) <span style="color: #ef4444;">*</span>
                                            </label>
                                            <input type="text" name="chapters[{{ $loop->index }}][title_fr]" value="{{ $chapter->title_fr ?? $chapter->title }}" required style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;" placeholder="Ex: Introduction à Laravel">
                                        </div>
                                        <div>
                                            <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                                <i class="fas fa-flag" style="color: #ef4444; margin-right: 5px;"></i>
                                                Titre du Chapitre (English)
                                            </label>
                                            <input type="text" name="chapters[{{ $loop->index }}][title_en]" value="{{ $chapter->title_en }}" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;" placeholder="Ex: Introduction to Laravel">
                                        </div>
                                    </div>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                        <div>
                                            <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                                <i class="fas fa-flag" style="color: #10b981; margin-right: 5px;"></i>
                                                Description (Français)
                                            </label>
                                            <textarea name="chapters[{{ $loop->index }}][description_fr]" rows="3" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem; resize: vertical;" placeholder="Description du chapitre...">{{ $chapter->description_fr ?? $chapter->description }}</textarea>
                                        </div>
                                        <div>
                                            <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                                <i class="fas fa-flag" style="color: #ef4444; margin-right: 5px;"></i>
                                                Description (English)
                                            </label>
                                            <textarea name="chapters[{{ $loop->index }}][description_en]" rows="3" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem; resize: vertical;" placeholder="Chapter description...">{{ $chapter->description_en }}</textarea>
                                        </div>
                                    </div>
                                    <div>
                                        <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                            <i class="fas fa-flag" style="color: #10b981; margin-right: 5px;"></i>
                                            Contenu du Chapitre (Français)
                                        </label>
                                        <div style="margin-bottom: 12px; padding: 15px; background: rgba(6, 182, 212, 0.15); border: 1px solid rgba(6, 182, 212, 0.4); border-radius: 8px; font-size: 0.9rem; color: rgba(255, 255, 255, 0.9); line-height: 1.6;">
                                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
                                                <i class="fas fa-code" style="color: #06b6d4; font-size: 1.1rem;"></i>
                                                <strong style="color: #06b6d4; font-size: 1rem;">Directives pour les blocs de code</strong>
                                            </div>
                                            
                                            <div style="margin-bottom: 12px; padding: 10px; background: rgba(0, 0, 0, 0.2); border-radius: 6px; font-family: 'Courier New', monospace; font-size: 0.85rem;">
                                                <div style="color: #10b981; margin-bottom: 8px; font-weight: 600;">✓ Méthode 1 : Format Markdown (Recommandé)</div>
                                                <div style="color: rgba(255, 255, 255, 0.8); white-space: pre-wrap; margin-bottom: 10px;">&#96;&#96;&#96;php
&lt;?php
namespace App\Http\Controllers;

class GreetingController extends Controller
{
    public function showGreeting()
    {
        return "Bonjour !";
    }
}
&#96;&#96;&#96;</div>
                                                
                                                <div style="color: #10b981; margin-top: 15px; margin-bottom: 8px; font-weight: 600;">✓ Méthode 2 : Balise HTML &lt;code&gt;</div>
                                                <div style="color: rgba(255, 255, 255, 0.8); white-space: pre-wrap;">&lt;code class="language-php"&gt;
&lt;?php
namespace App\Http\Controllers;
class GreetingController extends Controller
{
    public function showGreeting()
    {
        return "Bonjour !";
    }
}
&lt;/code&gt;</div>
                                            </div>
                                            
                                            <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid rgba(6, 182, 212, 0.3);">
                                                <div style="color: #fbbf24; font-weight: 600; margin-bottom: 6px;">
                                                    <i class="fas fa-lightbulb" style="margin-right: 5px;"></i>
                                                    Langages supportés :
                                                </div>
                                                <div style="display: flex; flex-wrap: wrap; gap: 8px; font-size: 0.85rem;">
                                                    <span style="padding: 4px 10px; background: rgba(6, 182, 212, 0.2); border-radius: 4px;">php</span>
                                                    <span style="padding: 4px 10px; background: rgba(6, 182, 212, 0.2); border-radius: 4px;">javascript</span>
                                                    <span style="padding: 4px 10px; background: rgba(6, 182, 212, 0.2); border-radius: 4px;">html</span>
                                                    <span style="padding: 4px 10px; background: rgba(6, 182, 212, 0.2); border-radius: 4px;">css</span>
                                                    <span style="padding: 4px 10px; background: rgba(6, 182, 212, 0.2); border-radius: 4px;">sql</span>
                                                    <span style="padding: 4px 10px; background: rgba(6, 182, 212, 0.2); border-radius: 4px;">bash</span>
                                                    <span style="padding: 4px 10px; background: rgba(6, 182, 212, 0.2); border-radius: 4px;">json</span>
                                                    <span style="padding: 4px 10px; background: rgba(6, 182, 212, 0.2); border-radius: 4px;">xml</span>
                                                    <span style="padding: 4px 10px; background: rgba(6, 182, 212, 0.2); border-radius: 4px;">python</span>
                                                    <span style="padding: 4px 10px; background: rgba(6, 182, 212, 0.2); border-radius: 4px;">java</span>
                                                    <span style="padding: 4px 10px; background: rgba(6, 182, 212, 0.2); border-radius: 4px;">text</span>
                                                </div>
                                            </div>
                                            
                                            <div style="margin-top: 12px; padding: 8px; background: rgba(251, 191, 36, 0.15); border-left: 3px solid #fbbf24; border-radius: 4px; font-size: 0.85rem;">
                                                <i class="fas fa-exclamation-triangle" style="color: #fbbf24; margin-right: 5px;"></i>
                                                <strong>Important :</strong> Pour le format markdown, utilisez <code style="background: rgba(0,0,0,0.3); padding: 2px 6px; border-radius: 3px;">&#96;&#96;&#96;langue</code> sur une ligne, puis votre code, puis <code style="background: rgba(0,0,0,0.3); padding: 2px 6px; border-radius: 3px;">&#96;&#96;&#96;</code> sur une nouvelle ligne.
                                            </div>
                                        </div>
                                        <textarea name="chapters[{{ $loop->index }}][content_fr]" rows="8" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem; resize: vertical; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;" placeholder="Contenu détaillé du chapitre en français...">{{ $chapter->content_fr ?? $chapter->content }}</textarea>
                                    </div>
                                    <div>
                                        <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                            <i class="fas fa-flag" style="color: #ef4444; margin-right: 5px;"></i>
                                            Contenu du Chapitre (English)
                                        </label>
                                        <textarea name="chapters[{{ $loop->index }}][content_en]" rows="8" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem; resize: vertical; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;" placeholder="Detailed chapter content in English...">{{ $chapter->content_en }}</textarea>
                                    </div>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                        <div>
                                            <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                                Durée (minutes)
                                            </label>
                                            <input type="number" name="chapters[{{ $loop->index }}][duration_minutes]" value="{{ $chapter->duration_minutes }}" min="1" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;" placeholder="Ex: 45">
                                        </div>
                                        <div style="display: flex; align-items: flex-end;">
                                            <button type="button" onclick="removeChapter(this)" style="width: 100%; padding: 12px; background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid #ef4444; border-radius: 8px; cursor: pointer; font-weight: 600;">
                                                <i class="fas fa-trash" style="margin-right: 8px;"></i>
                                                Supprimer
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="chapter-item" style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 12px; overflow: hidden;">
                                <input type="hidden" name="chapters[0][id]" value="">
                                
                                <!-- En-tête de l'accordéon -->
                                <div class="chapter-accordion-header" onclick="toggleChapterAccordion(this)" style="padding: 20px; cursor: pointer; display: flex; align-items: center; justify-content: space-between; background: rgba(6, 182, 212, 0.1); border-bottom: 1px solid rgba(6, 182, 212, 0.3); transition: all 0.3s ease;">
                                    <div style="display: flex; align-items: center; gap: 15px; flex: 1;">
                                        <div style="width: 40px; height: 40px; background: rgba(6, 182, 212, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #06b6d4; font-size: 1.1rem;">
                                            1
                                        </div>
                                        <div style="flex: 1;">
                                            <h4 style="margin: 0; color: white; font-weight: 600; font-size: 1.1rem;">
                                                Nouveau Chapitre
                                            </h4>
                                            <p style="margin: 5px 0 0 0; color: rgba(255, 255, 255, 0.7); font-size: 0.9rem;">
                                                Cliquez pour modifier les détails
                                            </p>
                                        </div>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <i class="fas fa-chevron-down chapter-accordion-icon" style="color: #06b6d4; font-size: 1.2rem; transition: transform 0.3s ease;"></i>
                                    </div>
                                </div>
                                
                                <!-- Contenu de l'accordéon -->
                                <div class="chapter-accordion-content" style="display: none; padding: 20px;">
                                    <div style="display: grid; gap: 15px;">
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                        <div>
                                            <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                                <i class="fas fa-flag" style="color: #10b981; margin-right: 5px;"></i>
                                                Titre du Chapitre (Français) <span style="color: #ef4444;">*</span>
                                            </label>
                                            <input type="text" name="chapters[0][title_fr]" required style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;" placeholder="Ex: Introduction à Laravel">
                                        </div>
                                        <div>
                                            <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                                <i class="fas fa-flag" style="color: #ef4444; margin-right: 5px;"></i>
                                                Titre du Chapitre (English)
                                            </label>
                                            <input type="text" name="chapters[0][title_en]" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;" placeholder="Ex: Introduction to Laravel">
                                        </div>
                                    </div>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                        <div>
                                            <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                                <i class="fas fa-flag" style="color: #10b981; margin-right: 5px;"></i>
                                                Description (Français)
                                            </label>
                                            <textarea name="chapters[0][description_fr]" rows="3" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem; resize: vertical;" placeholder="Description du chapitre..."></textarea>
                                        </div>
                                        <div>
                                            <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                                <i class="fas fa-flag" style="color: #ef4444; margin-right: 5px;"></i>
                                                Description (English)
                                            </label>
                                            <textarea name="chapters[0][description_en]" rows="3" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem; resize: vertical;" placeholder="Chapter description..."></textarea>
                                        </div>
                                    </div>
                                    <div>
                                        <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                            <i class="fas fa-flag" style="color: #10b981; margin-right: 5px;"></i>
                                            Contenu du Chapitre (Français)
                                        </label>
                                        <div style="margin-bottom: 12px; padding: 15px; background: rgba(6, 182, 212, 0.15); border: 1px solid rgba(6, 182, 212, 0.4); border-radius: 8px; font-size: 0.9rem; color: rgba(255, 255, 255, 0.9); line-height: 1.6;">
                                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
                                                <i class="fas fa-code" style="color: #06b6d4; font-size: 1.1rem;"></i>
                                                <strong style="color: #06b6d4; font-size: 1rem;">Directives pour les blocs de code</strong>
                                            </div>
                                            
                                            <div style="margin-bottom: 12px; padding: 10px; background: rgba(0, 0, 0, 0.2); border-radius: 6px; font-family: 'Courier New', monospace; font-size: 0.85rem;">
                                                <div style="color: #10b981; margin-bottom: 8px; font-weight: 600;">✓ Méthode 1 : Format Markdown (Recommandé)</div>
                                                <div style="color: rgba(255, 255, 255, 0.8); white-space: pre-wrap; margin-bottom: 10px;">&#96;&#96;&#96;php
&lt;?php
namespace App\Http\Controllers;

class GreetingController extends Controller
{
    public function showGreeting()
    {
        return "Bonjour !";
    }
}
&#96;&#96;&#96;</div>
                                                
                                                <div style="color: #10b981; margin-top: 15px; margin-bottom: 8px; font-weight: 600;">✓ Méthode 2 : Balise HTML &lt;code&gt;</div>
                                                <div style="color: rgba(255, 255, 255, 0.8); white-space: pre-wrap;">&lt;code class="language-php"&gt;
&lt;?php
namespace App\Http\Controllers;
class GreetingController extends Controller
{
    public function showGreeting()
    {
        return "Bonjour !";
    }
}
&lt;/code&gt;</div>
                                            </div>
                                            
                                            <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid rgba(6, 182, 212, 0.3);">
                                                <div style="color: #fbbf24; font-weight: 600; margin-bottom: 6px;">
                                                    <i class="fas fa-lightbulb" style="margin-right: 5px;"></i>
                                                    Langages supportés :
                                                </div>
                                                <div style="display: flex; flex-wrap: wrap; gap: 8px; font-size: 0.85rem;">
                                                    <span style="padding: 4px 10px; background: rgba(6, 182, 212, 0.2); border-radius: 4px;">php</span>
                                                    <span style="padding: 4px 10px; background: rgba(6, 182, 212, 0.2); border-radius: 4px;">javascript</span>
                                                    <span style="padding: 4px 10px; background: rgba(6, 182, 212, 0.2); border-radius: 4px;">html</span>
                                                    <span style="padding: 4px 10px; background: rgba(6, 182, 212, 0.2); border-radius: 4px;">css</span>
                                                    <span style="padding: 4px 10px; background: rgba(6, 182, 212, 0.2); border-radius: 4px;">sql</span>
                                                    <span style="padding: 4px 10px; background: rgba(6, 182, 212, 0.2); border-radius: 4px;">bash</span>
                                                    <span style="padding: 4px 10px; background: rgba(6, 182, 212, 0.2); border-radius: 4px;">json</span>
                                                    <span style="padding: 4px 10px; background: rgba(6, 182, 212, 0.2); border-radius: 4px;">xml</span>
                                                    <span style="padding: 4px 10px; background: rgba(6, 182, 212, 0.2); border-radius: 4px;">python</span>
                                                    <span style="padding: 4px 10px; background: rgba(6, 182, 212, 0.2); border-radius: 4px;">java</span>
                                                    <span style="padding: 4px 10px; background: rgba(6, 182, 212, 0.2); border-radius: 4px;">text</span>
                                                </div>
                                            </div>
                                            
                                            <div style="margin-top: 12px; padding: 8px; background: rgba(251, 191, 36, 0.15); border-left: 3px solid #fbbf24; border-radius: 4px; font-size: 0.85rem;">
                                                <i class="fas fa-exclamation-triangle" style="color: #fbbf24; margin-right: 5px;"></i>
                                                <strong>Important :</strong> Pour le format markdown, utilisez <code style="background: rgba(0,0,0,0.3); padding: 2px 6px; border-radius: 3px;">&#96;&#96;&#96;langue</code> sur une ligne, puis votre code, puis <code style="background: rgba(0,0,0,0.3); padding: 2px 6px; border-radius: 3px;">&#96;&#96;&#96;</code> sur une nouvelle ligne.
                                            </div>
                                        </div>
                                        <textarea name="chapters[0][content_fr]" rows="8" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem; resize: vertical; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;" placeholder="Contenu détaillé du chapitre en français..."></textarea>
                                    </div>
                                    <div>
                                        <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                            <i class="fas fa-flag" style="color: #ef4444; margin-right: 5px;"></i>
                                            Contenu du Chapitre (English)
                                        </label>
                                        <textarea name="chapters[0][content_en]" rows="8" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem; resize: vertical; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;" placeholder="Detailed chapter content in English..."></textarea>
                                    </div>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                        <div>
                                            <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                                Durée (minutes)
                                            </label>
                                            <input type="number" name="chapters[0][duration_minutes]" min="1" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;" placeholder="Ex: 45">
                                        </div>
                                        <div style="display: flex; align-items: flex-end;">
                                            <button type="button" onclick="removeChapter(this)" style="width: 100%; padding: 12px; background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid #ef4444; border-radius: 8px; cursor: pointer; font-weight: 600;">
                                                <i class="fas fa-trash" style="margin-right: 8px;"></i>
                                                Supprimer
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <button type="button" onclick="addChapter()" style="margin-top: 20px; padding: 12px 24px; background: rgba(6, 182, 212, 0.2); color: #06b6d4; border: 2px solid #06b6d4; border-radius: 12px; cursor: pointer; font-weight: 600; transition: all 0.3s ease;">
                        <i class="fas fa-plus" style="margin-right: 8px;"></i>
                        Ajouter un Chapitre
                    </button>
                </div>

                <!-- Boutons -->
                <div style="display: flex; gap: 15px; margin-top: 20px; padding-top: 20px; border-top: 2px solid rgba(6, 182, 212, 0.3);">
                    <button type="submit" style="flex: 1; padding: 15px 30px; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: white; border: none; border-radius: 12px; font-weight: 700; font-size: 1.1rem; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);">
                        <i class="fas fa-save" style="margin-right: 8px;"></i>
                        Enregistrer les Modifications
                    </button>
                    <a href="{{ route('admin.monetization.courses.show', $course->id) }}" style="padding: 15px 30px; background: rgba(107, 114, 128, 0.2); color: #6b7280; border: 2px solid #6b7280; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
                        Annuler
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function addItem(containerId, name, placeholder) {
    const container = document.getElementById(containerId);
    const div = document.createElement('div');
    div.style.cssText = 'display: flex; gap: 10px; margin-bottom: 10px;';
    div.innerHTML = `
        <input type="text" name="${name}" value="" placeholder="${placeholder}" style="flex: 1; padding: 10px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white;">
        <button type="button" onclick="removeItem(this)" style="background: #ef4444; color: white; border: none; border-radius: 8px; padding: 8px 12px; cursor: pointer;"><i class="fas fa-trash"></i></button>
    `;
    container.appendChild(div);
}

function removeItem(button) {
    button.parentElement.remove();
}

let chapterIndex = {{ $course->chapters && $course->chapters->count() > 0 ? $course->chapters->count() : 1 }};

function addChapter() {
    const container = document.getElementById('chaptersContainer');
    const div = document.createElement('div');
    div.className = 'chapter-item';
    div.style.cssText = 'background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 12px; overflow: hidden;';
    div.innerHTML = `
        <input type="hidden" name="chapters[${chapterIndex}][id]" value="">
        
        <!-- En-tête de l'accordéon -->
        <div class="chapter-accordion-header" onclick="toggleChapterAccordion(this)" style="padding: 20px; cursor: pointer; display: flex; align-items: center; justify-content: space-between; background: rgba(6, 182, 212, 0.1); border-bottom: 1px solid rgba(6, 182, 212, 0.3); transition: all 0.3s ease;">
            <div style="display: flex; align-items: center; gap: 15px; flex: 1;">
                <div style="width: 40px; height: 40px; background: rgba(6, 182, 212, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #06b6d4; font-size: 1.1rem;">
                    ${chapterIndex + 1}
                </div>
                <div style="flex: 1;">
                    <h4 style="margin: 0; color: white; font-weight: 600; font-size: 1.1rem;">
                        Nouveau Chapitre
                    </h4>
                    <p style="margin: 5px 0 0 0; color: rgba(255, 255, 255, 0.7); font-size: 0.9rem;">
                        Cliquez pour modifier les détails
                    </p>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-chevron-down chapter-accordion-icon" style="color: #06b6d4; font-size: 1.2rem; transition: transform 0.3s ease;"></i>
            </div>
        </div>
        
        <!-- Contenu de l'accordéon -->
        <div class="chapter-accordion-content" style="display: none; padding: 20px;">
            <div style="display: grid; gap: 15px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div>
                    <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                        <i class="fas fa-flag" style="color: #10b981; margin-right: 5px;"></i>
                        Titre du Chapitre (Français) <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="chapters[${chapterIndex}][title_fr]" required style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;" placeholder="Ex: Introduction à Laravel">
                </div>
                <div>
                    <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                        <i class="fas fa-flag" style="color: #ef4444; margin-right: 5px;"></i>
                        Titre du Chapitre (English)
                    </label>
                    <input type="text" name="chapters[${chapterIndex}][title_en]" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;" placeholder="Ex: Introduction to Laravel">
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div>
                    <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                        <i class="fas fa-flag" style="color: #10b981; margin-right: 5px;"></i>
                        Description (Français)
                    </label>
                    <textarea name="chapters[${chapterIndex}][description_fr]" rows="3" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem; resize: vertical;" placeholder="Description du chapitre..."></textarea>
                </div>
                <div>
                    <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                        <i class="fas fa-flag" style="color: #ef4444; margin-right: 5px;"></i>
                        Description (English)
                    </label>
                    <textarea name="chapters[${chapterIndex}][description_en]" rows="3" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem; resize: vertical;" placeholder="Chapter description..."></textarea>
                </div>
            </div>
            <div>
                <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                    <i class="fas fa-flag" style="color: #10b981; margin-right: 5px;"></i>
                    Contenu du Chapitre (Français)
                </label>
                <textarea name="chapters[${chapterIndex}][content_fr]" rows="8" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem; resize: vertical; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;" placeholder="Contenu détaillé du chapitre en français..."></textarea>
            </div>
            <div>
                <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                    <i class="fas fa-flag" style="color: #ef4444; margin-right: 5px;"></i>
                    Contenu du Chapitre (English)
                </label>
                <textarea name="chapters[${chapterIndex}][content_en]" rows="8" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem; resize: vertical; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;" placeholder="Detailed chapter content in English..."></textarea>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div>
                    <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                        Durée (minutes)
                    </label>
                    <input type="number" name="chapters[${chapterIndex}][duration_minutes]" min="1" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;" placeholder="Ex: 45">
                </div>
                <div style="display: flex; align-items: flex-end;">
                    <button type="button" onclick="removeChapter(this)" style="width: 100%; padding: 12px; background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid #ef4444; border-radius: 8px; cursor: pointer; font-weight: 600;">
                        <i class="fas fa-trash" style="margin-right: 8px;"></i>
                        Supprimer
                    </button>
                </div>
            </div>
        </div>
    `;
    container.appendChild(div);
    chapterIndex++;
    
    // Ouvrir automatiquement le nouveau chapitre
    const newChapter = container.lastElementChild;
    const header = newChapter.querySelector('.chapter-accordion-header');
    if (header) {
        toggleChapterAccordion(header);
    }
}

function removeChapter(button) {
    const chapterItems = document.querySelectorAll('.chapter-item');
    if (chapterItems.length > 1) {
        button.closest('.chapter-item').remove();
        // Réorganiser les numéros des chapitres
        updateChapterNumbers();
    } else {
        alert('Vous devez avoir au moins un chapitre.');
    }
}

function toggleChapterAccordion(header) {
    const content = header.nextElementSibling;
    const icon = header.querySelector('.chapter-accordion-icon');
    
    if (content.style.display === 'none') {
        content.style.display = 'block';
        icon.style.transform = 'rotate(180deg)';
        header.style.background = 'rgba(6, 182, 212, 0.15)';
    } else {
        content.style.display = 'none';
        icon.style.transform = 'rotate(0deg)';
        header.style.background = 'rgba(6, 182, 212, 0.1)';
    }
}

function updateChapterNumbers() {
    const chapters = document.querySelectorAll('.chapter-item');
    chapters.forEach((chapter, index) => {
        const numberElement = chapter.querySelector('.chapter-accordion-header > div > div:first-child');
        if (numberElement) {
            numberElement.textContent = index + 1;
        }
    });
}

// Gestion du toggle image interne/externe
document.addEventListener('DOMContentLoaded', function() {
    const coverTypeSelect = document.getElementById('coverType');
    if (coverTypeSelect) {
        coverTypeSelect.addEventListener('change', function() {
            const internalDiv = document.getElementById('internalImage');
            const externalDiv = document.getElementById('externalImage');
            
            if (this.value === 'internal') {
                internalDiv.style.display = 'block';
                externalDiv.style.display = 'none';
            } else {
                internalDiv.style.display = 'none';
                externalDiv.style.display = 'block';
            }
        });
    }
    
    // Ouvrir automatiquement le premier chapitre
    const firstChapter = document.querySelector('.chapter-accordion-header');
    if (firstChapter) {
        toggleChapterAccordion(firstChapter);
    }
});
</script>
@endsection

