@extends('admin.layout')

@section('title', 'Détails de l\'Inscription - Admin')

@section('content')
<div style="padding: 2rem;">
    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
        <div>
            <h1 style="font-size: 2.5rem; font-weight: 800; color: white; margin-bottom: 0.5rem;">
                <i class="fas fa-graduation-cap" style="color: #06b6d4; margin-right: 15px;"></i>
                Détails de l'Inscription
            </h1>
            <p style="color: rgba(255, 255, 255, 0.7); font-size: 1.1rem;">
                Informations complètes sur l'inscription à la formation
            </p>
        </div>
        <a href="{{ route('admin.monetization.payments') }}" style="padding: 12px 24px; background: rgba(6, 182, 212, 0.2); color: #06b6d4; border: 2px solid #06b6d4; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
            <i class="fas fa-arrow-left" style="margin-right: 8px;"></i>
            Retour aux paiements
        </a>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
        <!-- Informations du Cours -->
        <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 30px;">
            <h2 style="color: white; font-size: 1.5rem; font-weight: 700; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-book" style="color: #06b6d4;"></i>
                Informations du Cours
            </h2>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <div>
                    <span style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; font-weight: 600;">Titre du cours:</span>
                    <div style="color: white; font-size: 1.1rem; font-weight: 700; margin-top: 5px;">{{ $course->title }}</div>
                </div>
                @if($course->description)
                <div>
                    <span style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; font-weight: 600;">Description:</span>
                    <div style="color: rgba(255, 255, 255, 0.8); margin-top: 5px; line-height: 1.6;">{{ $course->description }}</div>
                </div>
                @endif
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <span style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; font-weight: 600;">Prix:</span>
                        <div style="color: #06b6d4; font-size: 1.2rem; font-weight: 700; margin-top: 5px;">
                            {{ number_format($course->price, 0, ',', ' ') }} {{ $course->currency ?? 'XOF' }}
                        </div>
                    </div>
                    @if($course->duration_hours)
                    <div>
                        <span style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; font-weight: 600;">Durée:</span>
                        <div style="color: rgba(255, 255, 255, 0.8); font-size: 1rem; font-weight: 600; margin-top: 5px;">
                            {{ $course->duration_hours }} heures
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Informations de l'Utilisateur -->
        <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 30px;">
            <h2 style="color: white; font-size: 1.5rem; font-weight: 700; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-user" style="color: #06b6d4;"></i>
                Informations de l'Utilisateur
            </h2>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <div>
                    <span style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; font-weight: 600;">Nom:</span>
                    <div style="color: white; font-size: 1.1rem; font-weight: 700; margin-top: 5px;">{{ $payment->user->name }}</div>
                </div>
                <div>
                    <span style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; font-weight: 600;">Email:</span>
                    <div style="color: rgba(255, 255, 255, 0.8); margin-top: 5px;">{{ $payment->user->email }}</div>
                </div>
                @if($payment->user->phone)
                <div>
                    <span style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; font-weight: 600;">Téléphone:</span>
                    <div style="color: rgba(255, 255, 255, 0.8); margin-top: 5px;">{{ $payment->user->phone }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Informations du Paiement -->
    <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 30px; margin-bottom: 20px;">
        <h2 style="color: white; font-size: 1.5rem; font-weight: 700; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-credit-card" style="color: #06b6d4;"></i>
            Informations du Paiement
        </h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <div>
                <span style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; font-weight: 600;">Montant:</span>
                <div style="color: #06b6d4; font-size: 1.5rem; font-weight: 700; margin-top: 5px;">
                    {{ number_format($payment->amount, 0, ',', ' ') }} {{ $payment->currency }}
                </div>
            </div>
            <div>
                <span style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; font-weight: 600;">Méthode de paiement:</span>
                <div style="color: rgba(255, 255, 255, 0.8); font-size: 1rem; font-weight: 600; margin-top: 5px;">
                    {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                </div>
            </div>
            <div>
                <span style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; font-weight: 600;">Statut:</span>
                <div style="margin-top: 5px;">
                    @if($payment->status === 'completed')
                        <span style="padding: 4px 10px; background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; border-radius: 6px; color: #10b981; font-size: 0.9rem; font-weight: 600;">Confirmé</span>
                    @elseif($payment->status === 'pending')
                        <span style="padding: 4px 10px; background: rgba(251, 191, 36, 0.2); border: 1px solid #fbbf24; border-radius: 6px; color: #fbbf24; font-size: 0.9rem; font-weight: 600;">En attente</span>
                    @elseif($payment->status === 'failed')
                        <span style="padding: 4px 10px; background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; border-radius: 6px; color: #ef4444; font-size: 0.9rem; font-weight: 600;">Échoué</span>
                    @else
                        <span style="padding: 4px 10px; background: rgba(107, 114, 128, 0.2); border: 1px solid #6b7280; border-radius: 6px; color: #6b7280; font-size: 0.9rem; font-weight: 600;">{{ ucfirst($payment->status) }}</span>
                    @endif
                </div>
            </div>
            <div>
                <span style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; font-weight: 600;">Référence:</span>
                <div style="color: rgba(255, 255, 255, 0.8); font-family: monospace; font-size: 0.95rem; margin-top: 5px;">
                    {{ $payment->payment_reference ?? 'N/A' }}
                </div>
            </div>
            <div>
                <span style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; font-weight: 600;">Date de création:</span>
                <div style="color: rgba(255, 255, 255, 0.8); margin-top: 5px;">
                    {{ $payment->created_at->format('d/m/Y à H:i') }}
                </div>
            </div>
            @if($payment->paid_at)
            <div>
                <span style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; font-weight: 600;">Date de paiement:</span>
                <div style="color: rgba(255, 255, 255, 0.8); margin-top: 5px;">
                    {{ $payment->paid_at->format('d/m/Y à H:i') }}
                </div>
            </div>
            @endif
        </div>
        @if($payment->failure_reason)
        <div style="margin-top: 20px; padding: 15px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 8px;">
            <div style="color: #ef4444; font-weight: 600; margin-bottom: 5px;">
                <i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i>
                Raison de l'échec:
            </div>
            <div style="color: rgba(255, 255, 255, 0.8);">{{ $payment->failure_reason }}</div>
        </div>
        @endif
    </div>

    <!-- Informations de l'Achat -->
    <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 30px; margin-bottom: 20px;">
        <h2 style="color: white; font-size: 1.5rem; font-weight: 700; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-shopping-cart" style="color: #06b6d4;"></i>
            Informations de l'Achat
        </h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <div>
                <span style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; font-weight: 600;">Statut de l'achat:</span>
                <div style="margin-top: 5px;">
                    @if($purchase->status === 'completed')
                        <span style="padding: 4px 10px; background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; border-radius: 6px; color: #10b981; font-size: 0.9rem; font-weight: 600;">Complété</span>
                    @elseif($purchase->status === 'pending')
                        <span style="padding: 4px 10px; background: rgba(251, 191, 36, 0.2); border: 1px solid #fbbf24; border-radius: 6px; color: #fbbf24; font-size: 0.9rem; font-weight: 600;">En attente</span>
                    @elseif($purchase->status === 'failed')
                        <span style="padding: 4px 10px; background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; border-radius: 6px; color: #ef4444; font-size: 0.9rem; font-weight: 600;">Échoué</span>
                    @else
                        <span style="padding: 4px 10px; background: rgba(107, 114, 128, 0.2); border: 1px solid #6b7280; border-radius: 6px; color: #6b7280; font-size: 0.9rem; font-weight: 600;">{{ ucfirst($purchase->status) }}</span>
                    @endif
                </div>
            </div>
            <div>
                <span style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; font-weight: 600;">Montant payé:</span>
                <div style="color: #06b6d4; font-size: 1.2rem; font-weight: 700; margin-top: 5px;">
                    {{ number_format($purchase->amount_paid, 0, ',', ' ') }} {{ $purchase->currency }}
                </div>
            </div>
            @if($purchase->purchased_at)
            <div>
                <span style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; font-weight: 600;">Date d'achat:</span>
                <div style="color: rgba(255, 255, 255, 0.8); margin-top: 5px;">
                    {{ $purchase->purchased_at->format('d/m/Y à H:i') }}
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Actions -->
    @if($payment->status === 'pending')
    <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 30px;">
        <h2 style="color: white; font-size: 1.5rem; font-weight: 700; margin-bottom: 20px;">
            Actions
        </h2>
        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
            <form action="{{ route('admin.monetization.payments.course.accept', $payment->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir accepter cette inscription ? L\'utilisateur aura immédiatement accès au cours.');">
                @csrf
                <button type="submit" style="padding: 12px 24px; background: rgba(16, 185, 129, 0.2); color: #10b981; border: 2px solid #10b981; border-radius: 10px; font-size: 1rem; font-weight: 700; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 10px;" onmouseover="this.style.background='rgba(16, 185, 129, 0.3)'" onmouseout="this.style.background='rgba(16, 185, 129, 0.2)'">
                    <i class="fas fa-check-circle"></i>
                    Accepter l'inscription
                </button>
            </form>
            <button type="button" onclick="showRejectModal()" style="padding: 12px 24px; background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 2px solid #ef4444; border-radius: 10px; font-size: 1rem; font-weight: 700; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 10px;" onmouseover="this.style.background='rgba(239, 68, 68, 0.3)'" onmouseout="this.style.background='rgba(239, 68, 68, 0.2)'">
                <i class="fas fa-times-circle"></i>
                Refuser l'inscription
            </button>
        </div>
    </div>
    @endif
</div>

<!-- Modal pour refuser -->
<div id="rejectModal" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.7); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: rgba(30, 41, 59, 0.95); border: 2px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 30px; max-width: 500px; width: 90%;">
        <h3 style="color: white; font-size: 1.5rem; font-weight: 700; margin-bottom: 20px;">
            <i class="fas fa-times-circle" style="color: #ef4444; margin-right: 10px;"></i>
            Refuser l'inscription
        </h3>
        <form action="{{ route('admin.monetization.payments.course.reject', $payment->id) }}" method="POST">
            @csrf
            <div style="margin-bottom: 20px;">
                <label style="display: block; color: rgba(255, 255, 255, 0.9); font-weight: 600; margin-bottom: 8px;">
                    Raison du refus (optionnel)
                </label>
                <textarea name="reason" rows="4" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem; resize: vertical;" placeholder="Expliquez la raison du refus..."></textarea>
            </div>
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" onclick="closeRejectModal()" style="padding: 10px 20px; background: rgba(107, 114, 128, 0.2); color: #9ca3af; border: 1px solid #6b7280; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                    Annuler
                </button>
                <button type="submit" style="padding: 10px 20px; background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid #ef4444; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(239, 68, 68, 0.3)'" onmouseout="this.style.background='rgba(239, 68, 68, 0.2)'">
                    Confirmer le refus
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showRejectModal() {
    document.getElementById('rejectModal').style.display = 'flex';
}

function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
    document.querySelector('#rejectModal textarea').value = '';
}

document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});
</script>
@endsection


