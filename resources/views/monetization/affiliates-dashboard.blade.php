@extends('layouts.app')

@section('title', 'Dashboard Affilié')

@section('content')
<div class="affiliate-dashboard" style="min-height: 80vh; padding: 4rem 2rem;">
    <div style="max-width: 1400px; margin: 0 auto;">
        <!-- Header -->
        <div style="margin-bottom: 3rem;">
            <h1 style="font-size: 2.5rem; font-weight: 800; color: white; margin-bottom: 0.5rem;">
                <i class="fas fa-chart-line" style="color: #06b6d4; margin-right: 15px;"></i>
                Dashboard Affilié
            </h1>
            <p style="color: rgba(255, 255, 255, 0.7); font-size: 1.1rem;">
                Gérez vos références et suivez vos gains
            </p>
        </div>

        <!-- Messages Flash -->
        @if(session('success'))
        <div style="background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; border-radius: 12px; padding: 15px 20px; margin-bottom: 20px; display: flex; align-items: center; gap: 15px;">
            <i class="fas fa-check-circle" style="color: #10b981; font-size: 1.2rem;"></i>
            <div style="flex: 1; color: #10b981;">
                <strong>{{ session('success') }}</strong>
            </div>
            <button onclick="this.parentElement.remove()" style="background: none; border: none; color: #10b981; cursor: pointer; font-size: 1.2rem;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        @endif

        <!-- Stats Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 25px;">
                <div style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; margin-bottom: 10px;">Gains Totaux</div>
                <div style="font-size: 2.5rem; font-weight: 800; color: #10b981;">{{ number_format($stats['total_earnings'], 0, ',', ' ') }} FCFA</div>
            </div>
            <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 25px;">
                <div style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; margin-bottom: 10px;">Gains Payés</div>
                <div style="font-size: 2.5rem; font-weight: 800; color: #10b981;">{{ number_format($stats['paid_earnings'], 0, ',', ' ') }} FCFA</div>
            </div>
            <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 25px;">
                <div style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; margin-bottom: 10px;">En Attente</div>
                <div style="font-size: 2.5rem; font-weight: 800; color: #fbbf24;">{{ number_format($stats['pending_earnings'], 0, ',', ' ') }} FCFA</div>
            </div>
            <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 25px;">
                <div style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; margin-bottom: 10px;">Taux de Commission</div>
                <div style="font-size: 2.5rem; font-weight: 800; color: #06b6d4;">{{ number_format($stats['commission_rate'], 2) }}%</div>
            </div>
        </div>

        <!-- Lien d'Affiliation -->
        <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 30px; margin-bottom: 30px;">
            <h2 style="font-size: 1.5rem; font-weight: 700; color: white; margin-bottom: 20px;">
                <i class="fas fa-link" style="color: #06b6d4; margin-right: 10px;"></i>
                Votre Lien d'Affiliation
            </h2>
            <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                <input type="text" id="affiliateLink" value="{{ $affiliate->referral_url }}" readonly style="flex: 1; min-width: 300px; padding: 12px 15px; background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem; font-family: monospace;">
                <button onclick="copyAffiliateLink()" style="padding: 12px 24px; background: #06b6d4; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                    <i class="fas fa-copy" style="margin-right: 8px;"></i>
                    Copier
                </button>
            </div>
            <div style="margin-top: 15px; color: rgba(255, 255, 255, 0.6); font-size: 0.9rem;">
                <i class="fas fa-info-circle" style="margin-right: 8px;"></i>
                Code d'affiliation: <code style="background: rgba(15, 23, 42, 0.6); padding: 3px 8px; border-radius: 4px; color: #06b6d4; font-weight: 600;">{{ $affiliate->affiliate_code }}</code>
            </div>
        </div>

        <!-- Statistiques des Références -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 20px; text-align: center;">
                <div style="font-size: 2rem; font-weight: 800; color: white; margin-bottom: 5px;">{{ $stats['total_referrals'] }}</div>
                <div style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem;">Total Références</div>
            </div>
            <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(251, 191, 36, 0.3); border-radius: 16px; padding: 20px; text-align: center;">
                <div style="font-size: 2rem; font-weight: 800; color: #fbbf24; margin-bottom: 5px;">{{ $stats['pending_referrals'] }}</div>
                <div style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem;">En Attente</div>
            </div>
            <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 20px; text-align: center;">
                <div style="font-size: 2rem; font-weight: 800; color: #06b6d4; margin-bottom: 5px;">{{ $stats['approved_referrals'] }}</div>
                <div style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem;">Approuvées</div>
            </div>
            <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 16px; padding: 20px; text-align: center;">
                <div style="font-size: 2rem; font-weight: 800; color: #10b981; margin-bottom: 5px;">{{ $stats['paid_referrals'] }}</div>
                <div style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem;">Payées</div>
            </div>
        </div>

        <!-- Liste des Références -->
        <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 30px;">
            <h2 style="font-size: 1.5rem; font-weight: 700; color: white; margin-bottom: 20px;">
                <i class="fas fa-list" style="color: #06b6d4; margin-right: 10px;"></i>
                Vos Références ({{ $referrals->total() }})
            </h2>

            @if($referrals->count() > 0)
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: rgba(15, 23, 42, 0.8);">
                            <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">Date</th>
                            <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">Type</th>
                            <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">Montant</th>
                            <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">Commission</th>
                            <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($referrals as $referral)
                        <tr style="border-bottom: 1px solid rgba(6, 182, 212, 0.1);">
                            <td style="padding: 15px; color: white;">{{ $referral->created_at->format('d/m/Y H:i') }}</td>
                            <td style="padding: 15px; color: white;">
                                @if($referral->referral_type === 'subscription')
                                    <span style="padding: 4px 8px; background: rgba(6, 182, 212, 0.2); color: #06b6d4; border-radius: 4px; font-size: 0.85rem;">Abonnement</span>
                                @elseif($referral->referral_type === 'course_purchase')
                                    <span style="padding: 4px 8px; background: rgba(139, 92, 246, 0.2); color: #8b5cf6; border-radius: 4px; font-size: 0.85rem;">Cours</span>
                                @elseif($referral->referral_type === 'donation')
                                    <span style="padding: 4px 8px; background: rgba(239, 68, 68, 0.2); color: #ef4444; border-radius: 4px; font-size: 0.85rem;">Donation</span>
                                @else
                                    <span style="padding: 4px 8px; background: rgba(107, 114, 128, 0.2); color: #6b7280; border-radius: 4px; font-size: 0.85rem;">Autre</span>
                                @endif
                            </td>
                            <td style="padding: 15px; color: white; font-weight: 600;">{{ number_format($referral->amount, 0, ',', ' ') }} FCFA</td>
                            <td style="padding: 15px; color: #10b981; font-weight: 600;">{{ number_format($referral->commission, 0, ',', ' ') }} FCFA</td>
                            <td style="padding: 15px;">
                                <span style="padding: 5px 12px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; text-transform: uppercase;
                                    background: {{ $referral->status === 'paid' ? 'rgba(16, 185, 129, 0.2)' : ($referral->status === 'approved' ? 'rgba(6, 182, 212, 0.2)' : ($referral->status === 'rejected' ? 'rgba(239, 68, 68, 0.2)' : 'rgba(251, 191, 36, 0.2)')) }};
                                    color: {{ $referral->status === 'paid' ? '#10b981' : ($referral->status === 'approved' ? '#06b6d4' : ($referral->status === 'rejected' ? '#ef4444' : '#fbbf24')) }};
                                    border: 1px solid {{ $referral->status === 'paid' ? '#10b981' : ($referral->status === 'approved' ? '#06b6d4' : ($referral->status === 'rejected' ? '#ef4444' : '#fbbf24')) }};
                                ">
                                    @if($referral->status === 'pending')
                                        En attente
                                    @elseif($referral->status === 'approved')
                                        Approuvée
                                    @elseif($referral->status === 'paid')
                                        Payée
                                    @else
                                        Rejetée
                                    @endif
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 20px;">
                {{ $referrals->links() }}
            </div>
            @else
            <div style="text-align: center; padding: 40px; color: rgba(255, 255, 255, 0.6);">
                <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.5;"></i>
                <p>Aucune référence pour le moment. Commencez à partager votre lien d'affiliation !</p>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function copyAffiliateLink() {
    const linkInput = document.getElementById('affiliateLink');
    linkInput.select();
    linkInput.setSelectionRange(0, 99999); // Pour mobile
    
    try {
        document.execCommand('copy');
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check" style="margin-right: 8px;"></i>Copié !';
        button.style.background = '#10b981';
        
        setTimeout(() => {
            button.innerHTML = originalText;
            button.style.background = '#06b6d4';
        }, 2000);
    } catch (err) {
        alert('Erreur lors de la copie');
    }
}
</script>

<style>
@media (max-width: 768px) {
    .affiliate-dashboard table {
        font-size: 0.85rem;
    }
    .affiliate-dashboard th,
    .affiliate-dashboard td {
        padding: 10px 8px !important;
    }
}
</style>
@endsection

