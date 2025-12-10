@extends('admin.layout')

@section('title', 'Détails du Cours Payant - Admin')

@push('styles')
<style>
    body.light-mode .course-show-page h1,
    body.light-mode .course-show-page h2,
    body.light-mode .course-show-page h3 {
        color: rgba(30, 41, 59, 0.9) !important;
    }

    body.light-mode .course-show-page p,
    body.light-mode .course-show-page .info-label {
        color: rgba(30, 41, 59, 0.8) !important;
    }

    body.light-mode .course-show-page .info-card,
    body.light-mode .course-show-page .stats-card {
        background: rgba(255, 255, 255, 0.9) !important;
        border-color: rgba(6, 182, 212, 0.2) !important;
    }

    body.light-mode .course-show-page .info-value {
        color: rgba(30, 41, 59, 0.9) !important;
    }
</style>
@endpush

@section('content')
<div class="course-show-page" style="padding: 2rem;">
    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
        <div>
            <h1 style="font-size: 2.5rem; font-weight: 800; color: white; margin-bottom: 0.5rem;">
                <i class="fas fa-graduation-cap" style="color: #06b6d4; margin-right: 15px;"></i>
                {{ $course->title }}
            </h1>
            <p style="color: rgba(255, 255, 255, 0.7); font-size: 1.1rem;">
                Détails et statistiques du cours
            </p>
        </div>
        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
            <a href="{{ route('admin.monetization.courses.edit', $course->id) }}" style="padding: 12px 24px; background: rgba(251, 191, 36, 0.2); color: #fbbf24; border: 2px solid #fbbf24; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
                <i class="fas fa-edit" style="margin-right: 8px;"></i>
                Modifier
            </a>
            <form method="POST" action="{{ route('admin.monetization.courses.duplicate', $course->id) }}" style="display: inline;" onsubmit="return confirm('Dupliquer ce cours ?');">
                @csrf
                <button type="submit" style="padding: 12px 24px; background: rgba(139, 92, 246, 0.2); color: #8b5cf6; border: 2px solid #8b5cf6; border-radius: 12px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                    <i class="fas fa-copy" style="margin-right: 8px;"></i>
                    Dupliquer
                </button>
            </form>
            <form method="POST" action="{{ route('admin.monetization.courses.toggle-status', $course->id) }}" style="display: inline;">
                @csrf
                <button type="submit" style="padding: 12px 24px; background: rgba(16, 185, 129, 0.2); color: #10b981; border: 2px solid #10b981; border-radius: 12px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                    <i class="fas fa-toggle-{{ $course->status === 'published' ? 'on' : 'off' }}" style="margin-right: 8px;"></i>
                    {{ $course->status === 'published' ? 'Dépublier' : 'Publier' }}
                </button>
            </form>
            <a href="{{ route('admin.monetization.courses.index') }}" style="padding: 12px 24px; background: rgba(6, 182, 212, 0.2); color: #06b6d4; border: 2px solid #06b6d4; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
                <i class="fas fa-arrow-left" style="margin-right: 8px;"></i>
                Retour
            </a>
        </div>
    </div>

    @if(session('success'))
    <div style="padding: 15px 20px; background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; border-radius: 12px; color: #10b981; margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px; margin-bottom: 30px;">
        <!-- Informations Principales -->
        <div class="info-card" style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 30px;">
            <h2 style="font-size: 1.5rem; font-weight: 700; color: white; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">
                <i class="fas fa-info-circle" style="color: #06b6d4; margin-right: 10px;"></i>
                Informations du Cours
            </h2>

            @if($course->cover_image)
            <div style="margin-bottom: 25px;">
                @if(($course->cover_type ?? 'internal') === 'internal')
                    <img src="{{ asset('storage/' . $course->cover_image) }}" alt="{{ $course->title }}" style="width: 100%; max-height: 300px; object-fit: cover; border-radius: 12px;">
                @else
                    <img src="{{ $course->cover_image }}" alt="{{ $course->title }}" style="width: 100%; max-height: 300px; object-fit: cover; border-radius: 12px;" onerror="this.style.display='none'">
                @endif
            </div>
            @endif

            <div style="display: grid; gap: 20px;">
                <div style="display: flex; justify-content: space-between; padding: 15px; background: rgba(15, 23, 42, 0.6); border-radius: 8px;">
                    <span class="info-label" style="color: rgba(255, 255, 255, 0.7); font-weight: 600;">Titre :</span>
                    <span class="info-value" style="color: white; font-weight: 700; font-size: 1.1rem;">{{ $course->title }}</span>
                </div>

                <div style="display: flex; justify-content: space-between; padding: 15px; background: rgba(15, 23, 42, 0.6); border-radius: 8px;">
                    <span class="info-label" style="color: rgba(255, 255, 255, 0.7); font-weight: 600;">Slug :</span>
                    <span class="info-value" style="color: white; font-family: monospace;">{{ $course->slug }}</span>
                </div>

                <div style="display: flex; justify-content: space-between; padding: 15px; background: rgba(15, 23, 42, 0.6); border-radius: 8px;">
                    <span class="info-label" style="color: rgba(255, 255, 255, 0.7); font-weight: 600;">Statut :</span>
                    <span>
                        @if($course->status === 'published')
                            <span style="padding: 4px 10px; background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; border-radius: 6px; color: #10b981; font-size: 0.85rem; font-weight: 600;">Publié</span>
                        @elseif($course->status === 'draft')
                            <span style="padding: 4px 10px; background: rgba(251, 191, 36, 0.2); border: 1px solid #fbbf24; border-radius: 6px; color: #fbbf24; font-size: 0.85rem; font-weight: 600;">Brouillon</span>
                        @else
                            <span style="padding: 4px 10px; background: rgba(107, 114, 128, 0.2); border: 1px solid #6b7280; border-radius: 6px; color: #6b7280; font-size: 0.85rem; font-weight: 600;">Archivé</span>
                        @endif
                    </span>
                </div>

                @if($course->description)
                <div style="padding: 15px; background: rgba(15, 23, 42, 0.6); border-radius: 8px;">
                    <span class="info-label" style="color: rgba(255, 255, 255, 0.7); font-weight: 600; display: block; margin-bottom: 10px;">Description :</span>
                    <p class="info-value" style="color: rgba(255, 255, 255, 0.9); line-height: 1.6;">{{ $course->description }}</p>
                </div>
                @endif

                @if($course->content)
                <div style="padding: 15px; background: rgba(15, 23, 42, 0.6); border-radius: 8px;">
                    <span class="info-label" style="color: rgba(255, 255, 255, 0.7); font-weight: 600; display: block; margin-bottom: 10px;">Contenu :</span>
                    <div class="info-value" style="color: rgba(255, 255, 255, 0.9); line-height: 1.6; max-height: 300px; overflow-y: auto;">
                        {!! nl2br(e($course->content)) !!}
                    </div>
                </div>
                @endif

                @if($course->duration_hours)
                <div style="display: flex; justify-content: space-between; padding: 15px; background: rgba(15, 23, 42, 0.6); border-radius: 8px;">
                    <span class="info-label" style="color: rgba(255, 255, 255, 0.7); font-weight: 600;">Durée :</span>
                    <span class="info-value" style="color: white; font-weight: 600;">{{ $course->duration_hours }} heures</span>
                </div>
                @endif

                @if($course->what_you_learn && count($course->what_you_learn) > 0)
                <div style="padding: 15px; background: rgba(15, 23, 42, 0.6); border-radius: 8px;">
                    <span class="info-label" style="color: rgba(255, 255, 255, 0.7); font-weight: 600; display: block; margin-bottom: 10px;">
                        <i class="fas fa-check-circle" style="color: #10b981; margin-right: 8px;"></i>
                        Ce que vous apprendrez :
                    </span>
                    <ul style="color: rgba(255, 255, 255, 0.9); line-height: 1.8; margin-left: 20px;">
                        @foreach($course->what_you_learn as $item)
                        <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if($course->requirements && count($course->requirements) > 0)
                <div style="padding: 15px; background: rgba(15, 23, 42, 0.6); border-radius: 8px;">
                    <span class="info-label" style="color: rgba(255, 255, 255, 0.7); font-weight: 600; display: block; margin-bottom: 10px;">
                        <i class="fas fa-list-check" style="color: #06b6d4; margin-right: 8px;"></i>
                        Prérequis :
                    </span>
                    <ul style="color: rgba(255, 255, 255, 0.9); line-height: 1.8; margin-left: 20px;">
                        @foreach($course->requirements as $item)
                        <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div style="display: flex; justify-content: space-between; padding: 15px; background: rgba(15, 23, 42, 0.6); border-radius: 8px;">
                    <span class="info-label" style="color: rgba(255, 255, 255, 0.7); font-weight: 600;">Date de création :</span>
                    <span class="info-value" style="color: white; font-size: 0.9rem;">{{ $course->created_at->format('d/m/Y H:i') }}</span>
                </div>

                <div style="display: flex; justify-content: space-between; padding: 15px; background: rgba(15, 23, 42, 0.6); border-radius: 8px;">
                    <span class="info-label" style="color: rgba(255, 255, 255, 0.7); font-weight: 600;">Dernière modification :</span>
                    <span class="info-value" style="color: white; font-size: 0.9rem;">{{ $course->updated_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- Statistiques et Prix -->
        <div style="display: flex; flex-direction: column; gap: 20px;">
            <!-- Prix -->
            <div class="stats-card" style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 30px;">
                <h2 style="font-size: 1.5rem; font-weight: 700; color: white; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">
                    <i class="fas fa-tag" style="color: #06b6d4; margin-right: 10px;"></i>
                    Prix
                </h2>

                <div style="text-align: center; margin-bottom: 25px;">
                    <div style="font-size: 3rem; font-weight: 800; color: #10b981; margin-bottom: 5px;">
                        {{ number_format($course->current_price, 0, ',', ' ') }}
                    </div>
                    <div style="color: rgba(255, 255, 255, 0.7); font-size: 1.1rem;">{{ $course->currency }}</div>
                    @if($course->hasDiscount())
                    <div style="margin-top: 10px; padding: 8px 15px; background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; border-radius: 8px; display: inline-block;">
                        <span style="text-decoration: line-through; color: rgba(255, 255, 255, 0.5); margin-right: 10px;">
                            {{ number_format($course->price, 0, ',', ' ') }} {{ $course->currency }}
                        </span>
                        <span style="color: #ef4444; font-weight: 700;">-{{ $course->discount_percentage }}%</span>
                    </div>
                    @endif
                </div>

                <div style="display: grid; gap: 15px;">
                    <div style="display: flex; justify-content: space-between; padding: 12px; background: rgba(15, 23, 42, 0.6); border-radius: 8px;">
                        <span style="color: rgba(255, 255, 255, 0.7); font-weight: 600; font-size: 0.9rem;">Prix de base :</span>
                        <span style="color: white; font-weight: 600;">{{ number_format($course->price, 0, ',', ' ') }} {{ $course->currency }}</span>
                    </div>
                    @if($course->discount_price)
                    <div style="display: flex; justify-content: space-between; padding: 12px; background: rgba(15, 23, 42, 0.6); border-radius: 8px;">
                        <span style="color: rgba(255, 255, 255, 0.7); font-weight: 600; font-size: 0.9rem;">Prix réduit :</span>
                        <span style="color: #ef4444; font-weight: 600;">{{ number_format($course->discount_price, 0, ',', ' ') }} {{ $course->currency }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Statistiques -->
            <div class="stats-card" style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 30px;">
                <h2 style="font-size: 1.5rem; font-weight: 700; color: white; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">
                    <i class="fas fa-chart-bar" style="color: #06b6d4; margin-right: 10px;"></i>
                    Statistiques
                </h2>

                <div style="display: grid; gap: 15px;">
                    <div style="display: flex; justify-content: space-between; padding: 15px; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 8px;">
                        <span style="color: rgba(255, 255, 255, 0.7); font-weight: 600;">Ventes totales :</span>
                        <span style="color: #10b981; font-weight: 700; font-size: 1.2rem;">{{ $courseStats['total_sales'] ?? 0 }}</span>
                    </div>

                    <div style="display: flex; justify-content: space-between; padding: 15px; background: rgba(139, 92, 246, 0.1); border: 1px solid rgba(139, 92, 246, 0.3); border-radius: 8px;">
                        <span style="color: rgba(255, 255, 255, 0.7); font-weight: 600;">Revenus totaux :</span>
                        <span style="color: #8b5cf6; font-weight: 700; font-size: 1.2rem;">{{ number_format($courseStats['total_revenue'] ?? 0, 0, ',', ' ') }} FCFA</span>
                    </div>

                    <div style="display: flex; justify-content: space-between; padding: 15px; background: rgba(251, 191, 36, 0.1); border: 1px solid rgba(251, 191, 36, 0.3); border-radius: 8px;">
                        <span style="color: rgba(255, 255, 255, 0.7); font-weight: 600;">En attente :</span>
                        <span style="color: #fbbf24; font-weight: 700;">{{ $courseStats['pending_sales'] ?? 0 }}</span>
                    </div>

                    <div style="display: flex; justify-content: space-between; padding: 15px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 8px;">
                        <span style="color: rgba(255, 255, 255, 0.7); font-weight: 600;">Échouées :</span>
                        <span style="color: #ef4444; font-weight: 700;">{{ $courseStats['failed_sales'] ?? 0 }}</span>
                    </div>

                    <div style="display: flex; justify-content: space-between; padding: 15px; background: rgba(15, 23, 42, 0.6); border-radius: 8px;">
                        <span style="color: rgba(255, 255, 255, 0.7); font-weight: 600;">Étudiants :</span>
                        <span style="color: white; font-weight: 600;">{{ $course->students_count }}</span>
                    </div>

                    <div style="display: flex; justify-content: space-between; padding: 15px; background: rgba(15, 23, 42, 0.6); border-radius: 8px;">
                        <span style="color: rgba(255, 255, 255, 0.7); font-weight: 600;">Note :</span>
                        <span style="color: white; font-weight: 600;">
                            {{ number_format($course->rating, 1) }}/5
                            <span style="color: rgba(255, 255, 255, 0.5); font-size: 0.85rem;">({{ $course->reviews_count }} avis)</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des Achats -->
    @if($course->purchases->count() > 0)
    <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 30px; margin-bottom: 30px;">
        <h2 style="font-size: 1.5rem; font-weight: 700; color: white; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">
            <i class="fas fa-shopping-cart" style="color: #06b6d4; margin-right: 10px;"></i>
            Derniers Achats ({{ $course->purchases->count() }} au total)
        </h2>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid rgba(6, 182, 212, 0.3);">
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">Utilisateur</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">Montant</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">Statut</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($course->purchases as $purchase)
                    <tr style="border-bottom: 1px solid rgba(6, 182, 212, 0.1);">
                        <td style="padding: 15px; color: rgba(255, 255, 255, 0.8); font-weight: 600;">
                            {{ $purchase->user->name ?? 'N/A' }}
                            <div style="font-size: 0.85rem; color: rgba(255, 255, 255, 0.6); margin-top: 4px;">
                                {{ $purchase->user->email ?? '' }}
                            </div>
                        </td>
                        <td style="padding: 15px; color: #10b981; font-weight: 700; font-size: 1.1rem;">
                            {{ number_format($purchase->amount_paid, 0, ',', ' ') }} {{ $purchase->currency }}
                        </td>
                        <td style="padding: 15px;">
                            @if($purchase->status === 'completed')
                                <span style="padding: 4px 10px; background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; border-radius: 6px; color: #10b981; font-size: 0.85rem; font-weight: 600;">Complété</span>
                            @elseif($purchase->status === 'pending')
                                <span style="padding: 4px 10px; background: rgba(251, 191, 36, 0.2); border: 1px solid #fbbf24; border-radius: 6px; color: #fbbf24; font-size: 0.85rem; font-weight: 600;">En attente</span>
                            @else
                                <span style="padding: 4px 10px; background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; border-radius: 6px; color: #ef4444; font-size: 0.85rem; font-weight: 600;">Échoué</span>
                            @endif
                        </td>
                        <td style="padding: 15px; color: rgba(255, 255, 255, 0.8);">
                            {{ $purchase->purchased_at ? $purchase->purchased_at->format('d/m/Y H:i') : $purchase->created_at->format('d/m/Y H:i') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Actions de Suppression -->
    <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 16px; padding: 30px;">
        <h2 style="font-size: 1.5rem; font-weight: 700; color: #ef4444; margin-bottom: 15px;">
            <i class="fas fa-exclamation-triangle" style="margin-right: 10px;"></i>
            Zone de Danger
        </h2>
        <p style="color: rgba(255, 255, 255, 0.7); margin-bottom: 20px;">
            La suppression de ce cours est définitive. Si le cours a des achats associés, il ne pourra pas être supprimé.
        </p>
        <form method="POST" action="{{ route('admin.monetization.courses.destroy', $course->id) }}" onsubmit="return confirm('Êtes-vous ABSOLUMENT sûr de vouloir supprimer ce cours ? Cette action est irréversible !');">
            @csrf
            @method('DELETE')
            <button type="submit" style="padding: 12px 24px; background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 2px solid #ef4444; border-radius: 12px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                <i class="fas fa-trash" style="margin-right: 8px;"></i>
                Supprimer ce Cours
            </button>
        </form>
    </div>
</div>
@endsection

