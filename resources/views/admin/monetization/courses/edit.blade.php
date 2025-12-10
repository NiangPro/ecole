@extends('admin.layout')

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
</style>
@endpush

@section('content')
<div class="course-form-page" style="padding: 2rem;">
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 2.5rem; font-weight: 800; color: white; margin-bottom: 0.5rem;">
            <i class="fas fa-edit" style="color: #06b6d4; margin-right: 15px;"></i>
            Modifier le Cours : {{ $course->title }}
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
                                Titre du Cours <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="text" name="title" value="{{ old('title', $course->title) }}" required style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;" placeholder="Ex: Formation Complète Laravel">
                            @error('title')
                            <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                            @enderror
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
                                Description
                            </label>
                            <textarea name="description" rows="4" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem; resize: vertical;" placeholder="Description courte du cours...">{{ old('description', $course->description) }}</textarea>
                            @error('description')
                            <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                Contenu Complet
                            </label>
                            <textarea name="content" rows="10" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem; resize: vertical;" placeholder="Contenu détaillé du cours...">{{ old('content', $course->content) }}</textarea>
                            @error('content')
                            <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                            @enderror
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

                <!-- Ce que vous apprendrez -->
                <div>
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: white; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">
                        <i class="fas fa-check-circle" style="color: #06b6d4; margin-right: 10px;"></i>
                        Ce que vous apprendrez
                    </h3>
                    <div id="whatYouLearnContainer">
                        @php
                            $whatYouLearn = old('what_you_learn', $course->what_you_learn ?? []);
                            if (is_string($whatYouLearn)) {
                                $whatYouLearn = json_decode($whatYouLearn, true) ?? [];
                            }
                            if (empty($whatYouLearn)) {
                                $whatYouLearn = [''];
                            }
                        @endphp
                        @foreach($whatYouLearn as $item)
                        <div class="learn-item" style="display: flex; gap: 10px; margin-bottom: 10px;">
                            <input type="text" name="what_you_learn[]" value="{{ $item }}" placeholder="Ex: Créer des applications Laravel" style="flex: 1; padding: 10px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem;">
                            <button type="button" onclick="removeLearnItem(this)" style="padding: 10px 15px; background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid #ef4444; border-radius: 8px; cursor: pointer;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        @endforeach
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
                        @php
                            $requirements = old('requirements', $course->requirements ?? []);
                            if (is_string($requirements)) {
                                $requirements = json_decode($requirements, true) ?? [];
                            }
                            if (empty($requirements)) {
                                $requirements = [''];
                            }
                        @endphp
                        @foreach($requirements as $item)
                        <div class="requirement-item" style="display: flex; gap: 10px; margin-bottom: 10px;">
                            <input type="text" name="requirements[]" value="{{ $item }}" placeholder="Ex: Connaissances de base en PHP" style="flex: 1; padding: 10px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem;">
                            <button type="button" onclick="removeRequirementItem(this)" style="padding: 10px 15px; background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid #ef4444; border-radius: 8px; cursor: pointer;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" onclick="addRequirementItem()" style="padding: 10px 20px; background: rgba(16, 185, 129, 0.2); color: #10b981; border: 2px solid #10b981; border-radius: 8px; cursor: pointer; font-weight: 600;">
                        <i class="fas fa-plus" style="margin-right: 6px;"></i>
                        Ajouter un prérequis
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
                            <div class="chapter-item" style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 12px; padding: 20px;">
                                <input type="hidden" name="chapters[{{ $loop->index }}][id]" value="{{ $chapter->id }}">
                                <div style="display: grid; gap: 15px;">
                                    <div>
                                        <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                            Titre du Chapitre <span style="color: #ef4444;">*</span>
                                        </label>
                                        <input type="text" name="chapters[{{ $loop->index }}][title]" value="{{ $chapter->title }}" required style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;" placeholder="Ex: Introduction à Laravel">
                                    </div>
                                    <div>
                                        <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                            Description
                                        </label>
                                        <textarea name="chapters[{{ $loop->index }}][description]" rows="3" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem; resize: vertical;" placeholder="Description du chapitre...">{{ $chapter->description }}</textarea>
                                    </div>
                                    <div>
                                        <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                            Contenu du Chapitre
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
                                        <textarea name="chapters[{{ $loop->index }}][content]" rows="8" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem; resize: vertical; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;" placeholder="Contenu détaillé du chapitre...">{{ $chapter->content }}</textarea>
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
                            <div class="chapter-item" style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 12px; padding: 20px;">
                                <input type="hidden" name="chapters[0][id]" value="">
                                <div style="display: grid; gap: 15px;">
                                    <div>
                                        <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                            Titre du Chapitre <span style="color: #ef4444;">*</span>
                                        </label>
                                        <input type="text" name="chapters[0][title]" required style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;" placeholder="Ex: Introduction à Laravel">
                                    </div>
                                    <div>
                                        <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                            Description
                                        </label>
                                        <textarea name="chapters[0][description]" rows="3" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem; resize: vertical;" placeholder="Description du chapitre..."></textarea>
                                    </div>
                                    <div>
                                        <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                            Contenu du Chapitre
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
                                        <textarea name="chapters[0][content]" rows="8" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem; resize: vertical; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;" placeholder="Contenu détaillé du chapitre..."></textarea>
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
    if (document.querySelectorAll('.learn-item').length > 1) {
        button.parentElement.remove();
    }
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
    if (document.querySelectorAll('.requirement-item').length > 1) {
        button.parentElement.remove();
    }
}

let chapterIndex = {{ $course->chapters && $course->chapters->count() > 0 ? $course->chapters->count() : 1 }};

function addChapter() {
    const container = document.getElementById('chaptersContainer');
    const div = document.createElement('div');
    div.className = 'chapter-item';
    div.style.cssText = 'background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 12px; padding: 20px;';
    div.innerHTML = `
        <input type="hidden" name="chapters[${chapterIndex}][id]" value="">
        <div style="display: grid; gap: 15px;">
            <div>
                <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                    Titre du Chapitre <span style="color: #ef4444;">*</span>
                </label>
                <input type="text" name="chapters[${chapterIndex}][title]" required style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;" placeholder="Ex: Introduction à Laravel">
            </div>
            <div>
                <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                    Description
                </label>
                <textarea name="chapters[${chapterIndex}][description]" rows="3" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem; resize: vertical;" placeholder="Description du chapitre..."></textarea>
            </div>
            <div>
                <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                    Contenu du Chapitre
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
                        <strong>Important :</strong> Pour le format markdown, utilisez <code style="background: rgba(0,0,0,0.3); padding: 2px 6px; border-radius: 3px;">\`\`\`langue</code> sur une ligne, puis votre code, puis <code style="background: rgba(0,0,0,0.3); padding: 2px 6px; border-radius: 3px;">\`\`\`</code> sur une nouvelle ligne.
                    </div>
                </div>
                <textarea name="chapters[${chapterIndex}][content]" rows="8" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem; resize: vertical; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;" placeholder="Contenu détaillé du chapitre..."></textarea>
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
}

function removeChapter(button) {
    const chapterItems = document.querySelectorAll('.chapter-item');
    if (chapterItems.length > 1) {
        button.closest('.chapter-item').remove();
    } else {
        alert('Vous devez avoir au moins un chapitre.');
    }
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

