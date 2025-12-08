@extends('admin.layout')

@section('title', 'Détails de la Donation - Admin')

@section('content')
<div style="padding: 2rem;">
    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
        <div>
            <h1 style="font-size: 2.5rem; font-weight: 800; color: white; margin-bottom: 0.5rem;">
                <i class="fas fa-heart" style="color: #06b6d4; margin-right: 15px;"></i>
                Détails de la Donation #{{ $donation->id }}
            </h1>
            <p style="color: rgba(255, 255, 255, 0.7); font-size: 1.1rem;">
                Informations complètes sur cette donation
            </p>
        </div>
        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
            <a href="{{ route('admin.monetization.donations.edit', $donation->id) }}" style="padding: 12px 24px; background: rgba(251, 191, 36, 0.2); color: #fbbf24; border: 2px solid #fbbf24; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
                <i class="fas fa-edit" style="margin-right: 8px;"></i>
                Éditer
            </a>
            <a href="{{ route('admin.monetization.donations.index') }}" style="padding: 12px 24px; background: rgba(6, 182, 212, 0.2); color: #06b6d4; border: 2px solid #06b6d4; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
                <i class="fas fa-arrow-left" style="margin-right: 8px;"></i>
                Retour à la liste
            </a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px; margin-bottom: 30px;">
        <!-- Informations Principales -->
        <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 30px;">
            <h2 style="font-size: 1.5rem; font-weight: 700; color: white; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">
                <i class="fas fa-info-circle" style="color: #06b6d4; margin-right: 10px;"></i>
                Informations Donateur
            </h2>

            <div style="display: grid; gap: 20px;">
                <div style="display: flex; justify-content: space-between; padding: 15px; background: rgba(15, 23, 42, 0.6); border-radius: 8px;">
                    <span style="color: rgba(255, 255, 255, 0.7); font-weight: 600;">Nom :</span>
                    <span style="color: white; font-weight: 700; font-size: 1.1rem;">{{ $donation->donor_name }}</span>
                </div>

                <div style="display: flex; justify-content: space-between; padding: 15px; background: rgba(15, 23, 42, 0.6); border-radius: 8px;">
                    <span style="color: rgba(255, 255, 255, 0.7); font-weight: 600;">Email :</span>
                    <span style="color: white;">{{ $donation->donor_email ?? 'N/A' }}</span>
                </div>

                @if($donation->user)
                <div style="display: flex; justify-content: space-between; padding: 15px; background: rgba(15, 23, 42, 0.6); border-radius: 8px;">
                    <span style="color: rgba(255, 255, 255, 0.7); font-weight: 600;">Utilisateur :</span>
                    <span style="color: white;">{{ $donation->user->name }} ({{ $donation->user->email }})</span>
                </div>
                @endif

                <div style="display: flex; justify-content: space-between; padding: 15px; background: rgba(15, 23, 42, 0.6); border-radius: 8px;">
                    <span style="color: rgba(255, 255, 255, 0.7); font-weight: 600;">Anonyme :</span>
                    <span>
                        @if($donation->is_anonymous)
                        <span style="padding: 4px 10px; background: rgba(107, 114, 128, 0.2); border: 1px solid #6b7280; border-radius: 6px; color: #6b7280; font-size: 0.85rem; font-weight: 600;">Oui</span>
                        @else
                        <span style="padding: 4px 10px; background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; border-radius: 6px; color: #10b981; font-size: 0.85rem; font-weight: 600;">Non</span>
                        @endif
                    </span>
                </div>

                <div style="display: flex; justify-content: space-between; padding: 15px; background: rgba(15, 23, 42, 0.6); border-radius: 8px;">
                    <span style="color: rgba(255, 255, 255, 0.7); font-weight: 600;">Afficher sur le mur :</span>
                    <span>
                        @if($donation->show_on_wall)
                        <span style="padding: 4px 10px; background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; border-radius: 6px; color: #10b981; font-size: 0.85rem; font-weight: 600;">Oui</span>
                        @else
                        <span style="padding: 4px 10px; background: rgba(107, 114, 128, 0.2); border: 1px solid #6b7280; border-radius: 6px; color: #6b7280; font-size: 0.85rem; font-weight: 600;">Non</span>
                        @endif
                    </span>
                </div>
            </div>

            @if($donation->message)
            <div style="margin-top: 25px; padding: 20px; background: rgba(6, 182, 212, 0.1); border-left: 4px solid #06b6d4; border-radius: 8px;">
                <h3 style="font-size: 1.1rem; font-weight: 700; color: white; margin-bottom: 10px;">
                    <i class="fas fa-quote-left" style="color: #06b6d4; margin-right: 8px;"></i>
                    Message du Donateur
                </h3>
                <p style="color: rgba(255, 255, 255, 0.9); line-height: 1.6; font-style: italic;">
                    "{{ $donation->message }}"
                </p>
            </div>
            @endif
        </div>

        <!-- Informations Paiement -->
        <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 30px; height: fit-content;">
            <h2 style="font-size: 1.5rem; font-weight: 700; color: white; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">
                <i class="fas fa-credit-card" style="color: #06b6d4; margin-right: 10px;"></i>
                Paiement
            </h2>

            <div style="text-align: center; margin-bottom: 25px;">
                <div style="font-size: 3rem; font-weight: 800; color: #ef4444; margin-bottom: 5px;">
                    {{ number_format($donation->amount, 0, ',', ' ') }}
                </div>
                <div style="color: rgba(255, 255, 255, 0.7); font-size: 1.1rem;">{{ $donation->currency }}</div>
            </div>

            <div style="display: grid; gap: 15px;">
                <div style="display: flex; justify-content: space-between; padding: 12px; background: rgba(15, 23, 42, 0.6); border-radius: 8px;">
                    <span style="color: rgba(255, 255, 255, 0.7); font-weight: 600; font-size: 0.9rem;">Statut :</span>
                    <span>
                        @if($donation->status === 'completed')
                            <span style="padding: 4px 10px; background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; border-radius: 6px; color: #10b981; font-size: 0.85rem; font-weight: 600;">Confirmé</span>
                        @elseif($donation->status === 'pending')
                            <span style="padding: 4px 10px; background: rgba(251, 191, 36, 0.2); border: 1px solid #fbbf24; border-radius: 6px; color: #fbbf24; font-size: 0.85rem; font-weight: 600;">En attente</span>
                        @elseif($donation->status === 'failed')
                            <span style="padding: 4px 10px; background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; border-radius: 6px; color: #ef4444; font-size: 0.85rem; font-weight: 600;">Échoué</span>
                        @else
                            <span style="padding: 4px 10px; background: rgba(107, 114, 128, 0.2); border: 1px solid #6b7280; border-radius: 6px; color: #6b7280; font-size: 0.85rem; font-weight: 600;">Annulé</span>
                        @endif
                    </span>
                </div>

                <div style="display: flex; justify-content: space-between; padding: 12px; background: rgba(15, 23, 42, 0.6); border-radius: 8px;">
                    <span style="color: rgba(255, 255, 255, 0.7); font-weight: 600; font-size: 0.9rem;">Méthode :</span>
                    <span style="color: white; font-weight: 600;">{{ ucfirst(str_replace('_', ' ', $donation->payment_method ?? 'N/A')) }}</span>
                </div>

                <div style="display: flex; justify-content: space-between; padding: 12px; background: rgba(15, 23, 42, 0.6); border-radius: 8px;">
                    <span style="color: rgba(255, 255, 255, 0.7); font-weight: 600; font-size: 0.9rem;">Référence :</span>
                    <span style="color: white; font-family: monospace; font-size: 0.9rem;">{{ $donation->payment_reference ?? 'N/A' }}</span>
                </div>

                <div style="display: flex; justify-content: space-between; padding: 12px; background: rgba(15, 23, 42, 0.6); border-radius: 8px;">
                    <span style="color: rgba(255, 255, 255, 0.7); font-weight: 600; font-size: 0.9rem;">Date création :</span>
                    <span style="color: white; font-size: 0.9rem;">{{ $donation->created_at->format('d/m/Y H:i') }}</span>
                </div>

                @if($donation->completed_at)
                <div style="display: flex; justify-content: space-between; padding: 12px; background: rgba(15, 23, 42, 0.6); border-radius: 8px;">
                    <span style="color: rgba(255, 255, 255, 0.7); font-weight: 600; font-size: 0.9rem;">Date complétion :</span>
                    <span style="color: white; font-size: 0.9rem;">{{ $donation->completed_at->format('d/m/Y H:i') }}</span>
                </div>
                @endif
            </div>

            @if($donation->status !== 'completed')
            <form action="{{ route('admin.monetization.donations.complete', $donation->id) }}" method="POST" style="margin-top: 20px;">
                @csrf
                <button type="submit" style="width: 100%; padding: 12px; background: linear-gradient(135deg, #10b981, #059669); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                    <i class="fas fa-check" style="margin-right: 8px;"></i>
                    Marquer comme Complétée
                </button>
            </form>
            @endif

            @if($donation->status !== 'failed')
            <form action="{{ route('admin.monetization.donations.fail', $donation->id) }}" method="POST" style="margin-top: 10px;">
                @csrf
                <button type="submit" style="width: 100%; padding: 12px; background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid #ef4444; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                    <i class="fas fa-times" style="margin-right: 8px;"></i>
                    Marquer comme Échouée
                </button>
            </form>
            @endif
        </div>
    </div>

    <!-- Informations Paiement Associé -->
    @if($donation->payment)
    <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 30px;">
        <h2 style="font-size: 1.5rem; font-weight: 700; color: white; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">
            <i class="fas fa-receipt" style="color: #06b6d4; margin-right: 10px;"></i>
            Paiement Associé
        </h2>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
            <div>
                <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 5px;">ID Paiement</div>
                <div style="color: white; font-weight: 700; font-size: 1.1rem;">#{{ $donation->payment->id }}</div>
            </div>
            <div>
                <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 5px;">Transaction ID</div>
                <div style="color: white; font-family: monospace; font-size: 0.95rem;">{{ $donation->payment->transaction_id ?? 'N/A' }}</div>
            </div>
            <div>
                <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 5px;">Statut Paiement</div>
                <div>
                    @if($donation->payment->status === 'completed')
                        <span style="padding: 4px 10px; background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; border-radius: 6px; color: #10b981; font-size: 0.85rem; font-weight: 600;">Confirmé</span>
                    @elseif($donation->payment->status === 'pending')
                        <span style="padding: 4px 10px; background: rgba(251, 191, 36, 0.2); border: 1px solid #fbbf24; border-radius: 6px; color: #fbbf24; font-size: 0.85rem; font-weight: 600;">En attente</span>
                    @elseif($donation->payment->status === 'failed')
                        <span style="padding: 4px 10px; background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; border-radius: 6px; color: #ef4444; font-size: 0.85rem; font-weight: 600;">Échoué</span>
                    @else
                        <span style="padding: 4px 10px; background: rgba(107, 114, 128, 0.2); border: 1px solid #6b7280; border-radius: 6px; color: #6b7280; font-size: 0.85rem; font-weight: 600;">{{ ucfirst($donation->payment->status) }}</span>
                    @endif
                </div>
            </div>
            @if($donation->payment->paid_at)
            <div>
                <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 5px;">Date de Paiement</div>
                <div style="color: white; font-size: 0.95rem;">{{ $donation->payment->paid_at->format('d/m/Y H:i') }}</div>
            </div>
            @endif
            @if($donation->payment->failure_reason)
            <div style="grid-column: 1 / -1;">
                <div style="padding: 15px; background: rgba(239, 68, 68, 0.1); border-left: 4px solid #ef4444; border-radius: 8px;">
                    <div style="color: #ef4444; font-weight: 700; margin-bottom: 5px;">Raison de l'échec :</div>
                    <div style="color: rgba(255, 255, 255, 0.9);">{{ $donation->payment->failure_reason }}</div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif
</div>

<style>
    @media (max-width: 968px) {
        .details-grid { grid-template-columns: 1fr !important; }
    }
</style>
@endsection



