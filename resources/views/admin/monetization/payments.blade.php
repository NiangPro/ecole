@extends('admin.layout')

@section('title', 'Gestion des Paiements - Admin')

@section('content')
<div style="padding: 2rem;">
    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
        <div>
            <h1 style="font-size: 2.5rem; font-weight: 800; color: white; margin-bottom: 0.5rem;">
                <i class="fas fa-credit-card" style="color: #06b6d4; margin-right: 15px;"></i>
                Gestion des Paiements
            </h1>
            <p style="color: rgba(255, 255, 255, 0.7); font-size: 1.1rem;">
                Gérez tous les paiements de la plateforme
            </p>
        </div>
        <a href="{{ route('admin.monetization.dashboard') }}" style="padding: 12px 24px; background: rgba(6, 182, 212, 0.2); color: #06b6d4; border: 2px solid #06b6d4; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
            <i class="fas fa-arrow-left" style="margin-right: 8px;"></i>
            Retour au dashboard
        </a>
    </div>

    @if(session('success'))
    <div style="background: rgba(16, 185, 129, 0.2); border: 2px solid #10b981; border-radius: 12px; padding: 15px 20px; margin-bottom: 20px; color: #10b981; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div style="background: rgba(239, 68, 68, 0.2); border: 2px solid #ef4444; border-radius: 12px; padding: 15px 20px; margin-bottom: 20px; color: #ef4444; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-exclamation-circle"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    @if($payments->count() > 0)
    <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 30px;">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid rgba(6, 182, 212, 0.3);">
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">ID</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">Utilisateur</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">Type</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">Montant</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">Méthode</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">Statut</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">Référence</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">Date</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                    <tr style="border-bottom: 1px solid rgba(6, 182, 212, 0.1);">
                        <td style="padding: 15px; color: rgba(255, 255, 255, 0.8);">#{{ $payment->id }}</td>
                        <td style="padding: 15px; color: rgba(255, 255, 255, 0.8);">
                            {{ $payment->user ? $payment->user->name : 'N/A' }}
                            @if($payment->user)
                            <div style="font-size: 0.85rem; color: rgba(255, 255, 255, 0.6);">{{ $payment->user->email }}</div>
                            @endif
                        </td>
                        <td style="padding: 15px;">
                            @if(class_basename($payment->paymentable_type) === 'Subscription')
                                <span style="padding: 4px 10px; background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; border-radius: 6px; color: #10b981; font-size: 0.85rem; font-weight: 600;">Abonnement</span>
                            @elseif(class_basename($payment->paymentable_type) === 'CoursePurchase')
                                <span style="padding: 4px 10px; background: rgba(251, 191, 36, 0.2); border: 1px solid #fbbf24; border-radius: 6px; color: #fbbf24; font-size: 0.85rem; font-weight: 600;">Cours</span>
                            @elseif(class_basename($payment->paymentable_type) === 'Donation')
                                <span style="padding: 4px 10px; background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; border-radius: 6px; color: #ef4444; font-size: 0.85rem; font-weight: 600;">Don</span>
                            @else
                                <span style="padding: 4px 10px; background: rgba(107, 114, 128, 0.2); border: 1px solid #6b7280; border-radius: 6px; color: #6b7280; font-size: 0.85rem; font-weight: 600;">Autre</span>
                            @endif
                        </td>
                        <td style="padding: 15px; color: #06b6d4; font-weight: 700; font-size: 1.1rem;">
                            {{ number_format($payment->amount, 0, ',', ' ') }} {{ $payment->currency }}
                        </td>
                        <td style="padding: 15px; color: rgba(255, 255, 255, 0.8);">
                            {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                            @if($payment->payment_gateway)
                            <div style="font-size: 0.85rem; color: rgba(255, 255, 255, 0.6);">{{ $payment->payment_gateway }}</div>
                            @endif
                        </td>
                        <td style="padding: 15px;">
                            @if($payment->status === 'completed')
                                <span style="padding: 4px 10px; background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; border-radius: 6px; color: #10b981; font-size: 0.85rem; font-weight: 600;">Confirmé</span>
                            @elseif($payment->status === 'pending')
                                <span style="padding: 4px 10px; background: rgba(251, 191, 36, 0.2); border: 1px solid #fbbf24; border-radius: 6px; color: #fbbf24; font-size: 0.85rem; font-weight: 600;">En attente</span>
                            @elseif($payment->status === 'processing')
                                <span style="padding: 4px 10px; background: rgba(59, 130, 246, 0.2); border: 1px solid #3b82f6; border-radius: 6px; color: #3b82f6; font-size: 0.85rem; font-weight: 600;">En traitement</span>
                            @elseif($payment->status === 'failed')
                                <span style="padding: 4px 10px; background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; border-radius: 6px; color: #ef4444; font-size: 0.85rem; font-weight: 600;">Échoué</span>
                            @elseif($payment->status === 'refunded')
                                <span style="padding: 4px 10px; background: rgba(139, 92, 246, 0.2); border: 1px solid #8b5cf6; border-radius: 6px; color: #8b5cf6; font-size: 0.85rem; font-weight: 600;">Remboursé</span>
                            @else
                                <span style="padding: 4px 10px; background: rgba(107, 114, 128, 0.2); border: 1px solid #6b7280; border-radius: 6px; color: #6b7280; font-size: 0.85rem; font-weight: 600;">{{ ucfirst($payment->status) }}</span>
                            @endif
                        </td>
                        <td style="padding: 15px; color: rgba(255, 255, 255, 0.8); font-family: monospace; font-size: 0.9rem;">
                            {{ $payment->payment_reference ?? 'N/A' }}
                        </td>
                        <td style="padding: 15px; color: rgba(255, 255, 255, 0.8); font-size: 0.9rem;">
                            {{ $payment->created_at->format('d/m/Y H:i') }}
                            @if($payment->paid_at)
                            <div style="font-size: 0.85rem; color: rgba(255, 255, 255, 0.6);">
                                Payé: {{ $payment->paid_at->format('d/m/Y H:i') }}
                            </div>
                            @endif
                        </td>
                        <td style="padding: 15px;">
                            @if(class_basename($payment->paymentable_type) === 'CoursePurchase')
                                @if($payment->status === 'pending')
                                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                        <a href="{{ route('admin.monetization.payments.course.show', $payment->id) }}" style="padding: 6px 12px; background: rgba(6, 182, 212, 0.2); color: #06b6d4; border: 1px solid #06b6d4; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 600; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(6, 182, 212, 0.3)'" onmouseout="this.style.background='rgba(6, 182, 212, 0.2)'">
                                            <i class="fas fa-eye"></i> Voir
                                        </a>
                                        <form action="{{ route('admin.monetization.payments.course.accept', $payment->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir accepter cette inscription ?');">
                                            @csrf
                                            <button type="submit" style="padding: 6px 12px; background: rgba(16, 185, 129, 0.2); color: #10b981; border: 1px solid #10b981; border-radius: 6px; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(16, 185, 129, 0.3)'" onmouseout="this.style.background='rgba(16, 185, 129, 0.2)'">
                                                <i class="fas fa-check"></i> Accepter
                                            </button>
                                        </form>
                                        <button type="button" onclick="showRejectModal({{ $payment->id }})" style="padding: 6px 12px; background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid #ef4444; border-radius: 6px; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(239, 68, 68, 0.3)'" onmouseout="this.style.background='rgba(239, 68, 68, 0.2)'">
                                            <i class="fas fa-times"></i> Refuser
                                        </button>
                                    </div>
                                @else
                                    <a href="{{ route('admin.monetization.payments.course.show', $payment->id) }}" style="padding: 6px 12px; background: rgba(6, 182, 212, 0.2); color: #06b6d4; border: 1px solid #06b6d4; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 600; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(6, 182, 212, 0.3)'" onmouseout="this.style.background='rgba(6, 182, 212, 0.2)'">
                                        <i class="fas fa-eye"></i> Voir
                                    </a>
                                @endif
                            @else
                                <span style="color: rgba(255, 255, 255, 0.5); font-size: 0.85rem;">-</span>
                            @endif
                        </td>
                    </tr>
                    @if($payment->failure_reason)
                    <tr>
                        <td colspan="9" style="padding: 10px 15px; color: #ef4444; font-size: 0.9rem; border-bottom: 1px solid rgba(6, 182, 212, 0.1);">
                            <i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i>
                            <strong>Raison:</strong> {{ $payment->failure_reason }}
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div style="margin-top: 30px; display: flex; justify-content: center;">
            {{ $payments->links() }}
        </div>
    </div>
    @else
    <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 60px; text-align: center;">
        <i class="fas fa-credit-card" style="font-size: 4rem; color: rgba(6, 182, 212, 0.5); margin-bottom: 20px;"></i>
        <h3 style="font-size: 1.5rem; font-weight: 700; color: white; margin-bottom: 10px;">
            Aucun paiement
        </h3>
        <p style="color: rgba(255, 255, 255, 0.7);">
            Aucun paiement n'a été enregistré pour le moment.
        </p>
    </div>
    @endif
</div>

<!-- Modal pour refuser un paiement -->
<div id="rejectModal" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.7); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: rgba(30, 41, 59, 0.95); border: 2px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 30px; max-width: 500px; width: 90%;">
        <h3 style="color: white; font-size: 1.5rem; font-weight: 700; margin-bottom: 20px;">
            <i class="fas fa-times-circle" style="color: #ef4444; margin-right: 10px;"></i>
            Refuser l'inscription
        </h3>
        <form id="rejectForm" method="POST" action="">
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
function showRejectModal(paymentId) {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    form.action = '{{ route("admin.monetization.payments.course.reject", ":id") }}'.replace(':id', paymentId);
    modal.style.display = 'flex';
}

function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
    document.getElementById('rejectForm').reset();
}

// Fermer le modal en cliquant en dehors
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});
</script>

<style>
    @media (max-width: 768px) {
        table { font-size: 0.85rem; }
        th, td { padding: 10px !important; }
    }
</style>
@endsection



