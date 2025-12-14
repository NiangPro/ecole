@extends('admin.layout')

@section('title', 'Créer un Cours Payant - Admin')

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
</style>
@endpush

@section('content')
<div class="course-form-page" style="padding: 2rem;">
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 2.5rem; font-weight: 800; color: white; margin-bottom: 0.5rem;">
            <i class="fas fa-graduation-cap" style="color: #06b6d4; margin-right: 15px;"></i>
            Créer un Cours Payant
        </h1>
        <p style="color: rgba(255, 255, 255, 0.7); font-size: 1.1rem;">
            Ajouter un nouveau cours payant à la plateforme
        </p>
    </div>

    <div style="display: flex; gap: 20px; margin-bottom: 25px;">
        <a href="{{ route('admin.monetization.courses.index') }}" style="padding: 12px 24px; background: rgba(6, 182, 212, 0.2); color: #06b6d4; border: 2px solid #06b6d4; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
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
        <form action="{{ route('admin.monetization.courses.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

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
                            <input type="text" name="title" value="{{ old('title') }}" required style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;" placeholder="Ex: Formation Complète Laravel">
                            <p style="color: rgba(255, 255, 255, 0.6); font-size: 0.85rem; margin-top: 5px;">Titre par défaut (utilisé si les traductions ne sont pas disponibles)</p>
                            @error('title')
                            <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div>
                                <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                    <i class="fas fa-flag" style="color: #10b981; margin-right: 5px;"></i>
                                    Titre (Français)
                                </label>
                                <input type="text" name="title_fr" value="{{ old('title_fr') }}" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;" placeholder="Ex: Formation Complète Laravel">
                                @error('title_fr')
                                <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                    <i class="fas fa-flag" style="color: #ef4444; margin-right: 5px;"></i>
                                    Titre (English)
                                </label>
                                <input type="text" name="title_en" value="{{ old('title_en') }}" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;" placeholder="Ex: Complete Laravel Course">
                                @error('title_en')
                                <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                Slug (URL) <span style="color: rgba(255, 255, 255, 0.5); font-size: 0.85rem;">(Généré automatiquement si vide)</span>
                            </label>
                            <input type="text" name="slug" value="{{ old('slug') }}" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;" placeholder="formation-complete-laravel">
                            @error('slug')
                            <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                Description (Par défaut)
                            </label>
                            <textarea name="description" rows="4" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem; resize: vertical;" placeholder="Description courte du cours...">{{ old('description') }}</textarea>
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
                                <textarea name="description_fr" rows="4" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem; resize: vertical;" placeholder="Description courte du cours...">{{ old('description_fr') }}</textarea>
                                @error('description_fr')
                                <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                    <i class="fas fa-flag" style="color: #ef4444; margin-right: 5px;"></i>
                                    Description (English)
                                </label>
                                <textarea name="description_en" rows="4" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem; resize: vertical;" placeholder="Short course description...">{{ old('description_en') }}</textarea>
                                @error('description_en')
                                <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                Contenu Complet (Par défaut)
                            </label>
                            <textarea name="content" rows="10" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem; resize: vertical;" placeholder="Contenu détaillé du cours...">{{ old('content') }}</textarea>
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
                                <textarea name="content_fr" rows="10" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem; resize: vertical;" placeholder="Contenu détaillé du cours...">{{ old('content_fr') }}</textarea>
                                @error('content_fr')
                                <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                    <i class="fas fa-flag" style="color: #ef4444; margin-right: 5px;"></i>
                                    Contenu Complet (English)
                                </label>
                                <textarea name="content_en" rows="10" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem; resize: vertical;" placeholder="Detailed course content...">{{ old('content_en') }}</textarea>
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
                                    <option value="internal" {{ old('cover_type', 'internal') === 'internal' ? 'selected' : '' }}>Interne (upload)</option>
                                    <option value="external" {{ old('cover_type') === 'external' ? 'selected' : '' }}>Externe (URL)</option>
                                </select>
                            </div>
                            
                            <div id="internalImage" style="display: {{ old('cover_type', 'internal') === 'internal' ? 'block' : 'none' }};">
                                <input type="file" name="cover_image_file" id="coverImageFile" accept="image/*" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;">
                                <p style="color: rgba(255, 255, 255, 0.6); font-size: 0.85rem; margin-top: 5px;">Formats acceptés: JPEG, PNG, JPG, GIF, WEBP (max 2MB).</p>
                                @error('cover_image_file')
                                <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div id="externalImage" style="display: {{ old('cover_type') === 'external' ? 'block' : 'none' }};">
                                <input type="url" name="cover_image_url" id="coverImageUrl" value="{{ old('cover_image_url') }}" placeholder="https://example.com/image.jpg" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;">
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
                            <input type="number" name="price" value="{{ old('price') }}" required min="0" step="100" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;">
                            @error('price')
                            <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                Devise <span style="color: #ef4444;">*</span>
                            </label>
                            <select name="currency" required style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;">
                                <option value="XOF" {{ old('currency', 'XOF') === 'XOF' ? 'selected' : '' }}>XOF (FCFA)</option>
                                <option value="EUR" {{ old('currency') === 'EUR' ? 'selected' : '' }}>EUR</option>
                                <option value="USD" {{ old('currency') === 'USD' ? 'selected' : '' }}>USD</option>
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
                                <input type="number" name="discount_price" value="{{ old('discount_price') }}" min="0" step="100" style="width: 100%; padding: 10px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem;">
                            </div>
                            <div>
                                <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px; font-size: 0.9rem;">Date Début</label>
                                <input type="date" name="discount_start" value="{{ old('discount_start') }}" style="width: 100%; padding: 10px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem;">
                            </div>
                            <div>
                                <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px; font-size: 0.9rem;">Date Fin</label>
                                <input type="date" name="discount_end" value="{{ old('discount_end') }}" style="width: 100%; padding: 10px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem;">
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
                            <input type="number" name="duration_hours" value="{{ old('duration_hours') }}" min="1" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;" placeholder="Ex: 40">
                            @error('duration_hours')
                            <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                Statut <span style="color: #ef4444;">*</span>
                            </label>
                            <select name="status" required style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;">
                                <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Brouillon</option>
                                <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Publié</option>
                                <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>Archivé</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Ce que vous apprendrez -->
                <div>
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: white; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">
                        <i class="fas fa-check-circle" style="color: #06b6d4; margin-right: 10px;"></i>
                        Ce que vous apprendrez
                    </h3>
                    <div id="whatYouLearnContainer">
                        <div class="learn-item" style="display: flex; gap: 10px; margin-bottom: 10px;">
                            <input type="text" name="what_you_learn[]" placeholder="Ex: Créer des applications Laravel" style="flex: 1; padding: 10px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem;">
                            <button type="button" onclick="removeLearnItem(this)" style="padding: 10px 15px; background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid #ef4444; border-radius: 8px; cursor: pointer;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" onclick="addLearnItem()" style="padding: 10px 20px; background: rgba(16, 185, 129, 0.2); color: #10b981; border: 2px solid #10b981; border-radius: 8px; cursor: pointer; font-weight: 600;">
                        <i class="fas fa-plus" style="margin-right: 6px;"></i>
                        Ajouter un point
                    </button>
                </div>

                <!-- Prérequis -->
                <div>
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: white; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">
                        <i class="fas fa-list-check" style="color: #06b6d4; margin-right: 10px;"></i>
                        Prérequis
                    </h3>
                    <div id="requirementsContainer">
                        <div class="requirement-item" style="display: flex; gap: 10px; margin-bottom: 10px;">
                            <input type="text" name="requirements[]" placeholder="Ex: Connaissances de base en PHP" style="flex: 1; padding: 10px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem;">
                            <button type="button" onclick="removeRequirementItem(this)" style="padding: 10px 15px; background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid #ef4444; border-radius: 8px; cursor: pointer;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" onclick="addRequirementItem()" style="padding: 10px 20px; background: rgba(16, 185, 129, 0.2); color: #10b981; border: 2px solid #10b981; border-radius: 8px; cursor: pointer; font-weight: 600;">
                        <i class="fas fa-plus" style="margin-right: 6px;"></i>
                        Ajouter un prérequis
                    </button>
                </div>

                <!-- Boutons -->
                <div style="display: flex; gap: 15px; margin-top: 20px; padding-top: 20px; border-top: 2px solid rgba(6, 182, 212, 0.3);">
                    <button type="submit" style="flex: 1; padding: 15px 30px; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: white; border: none; border-radius: 12px; font-weight: 700; font-size: 1.1rem; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);">
                        <i class="fas fa-save" style="margin-right: 8px;"></i>
                        Créer le Cours
                    </button>
                    <a href="{{ route('admin.monetization.courses.index') }}" style="padding: 15px 30px; background: rgba(107, 114, 128, 0.2); color: #6b7280; border: 2px solid #6b7280; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
                        Annuler
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function addLearnItem() {
    const container = document.getElementById('whatYouLearnContainer');
    const div = document.createElement('div');
    div.className = 'learn-item';
    div.style.cssText = 'display: flex; gap: 10px; margin-bottom: 10px;';
    div.innerHTML = `
        <input type="text" name="what_you_learn[]" placeholder="Ex: Créer des applications Laravel" style="flex: 1; padding: 10px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem;">
        <button type="button" onclick="removeLearnItem(this)" style="padding: 10px 15px; background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid #ef4444; border-radius: 8px; cursor: pointer;">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(div);
}

function removeLearnItem(button) {
    button.parentElement.remove();
}

function addRequirementItem() {
    const container = document.getElementById('requirementsContainer');
    const div = document.createElement('div');
    div.className = 'requirement-item';
    div.style.cssText = 'display: flex; gap: 10px; margin-bottom: 10px;';
    div.innerHTML = `
        <input type="text" name="requirements[]" placeholder="Ex: Connaissances de base en PHP" style="flex: 1; padding: 10px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem;">
        <button type="button" onclick="removeRequirementItem(this)" style="padding: 10px 15px; background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid #ef4444; border-radius: 8px; cursor: pointer;">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(div);
}

function removeRequirementItem(button) {
    button.parentElement.remove();
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
});
</script>
@endsection

