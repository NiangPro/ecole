@extends('admin.layout')

@section('title', 'Détails de l\'Affilié - Admin')

@section('content')
<div style="padding: 2rem;">
    <!-- Header -->
    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
        <div>
            <h1 style="font-size: 2.5rem; font-weight: 800; color: white; margin-bottom: 0.5rem;">
                <i class="fas fa-user" style="color: #06b6d4; margin-right: 15px;"></i>
                {{ $affiliate->name }}
            </h1>
            <p style="color: rgba(255, 255, 255, 0.7); font-size: 1.1rem;">
                Détails et références de l'affilié
            </p>
        </div>
        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
            <a href="{{ route('admin.monetization.affiliates.edit', $affiliate->id) }}" style="padding: 12px 24px; background: rgba(251, 191, 36, 0.2); color: #fbbf24; border: 2px solid #fbbf24; border-radius: 12px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-edit"></i>
                <span>Modifier</span>
            </a>
            <a href="{{ route('admin.monetization.affiliates') }}" style="padding: 12px 24px; background: rgba(6, 182, 212, 0.2); color: #06b6d4; border: 2px solid #06b6d4; border-radius: 12px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-arrow-left"></i>
                <span>Retour</span>
            </a>
        </div>
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

    <!-- Informations de l'Affilié -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
        <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 25px;">
            <h2 style="font-size: 1.3rem; font-weight: 700; color: white; margin-bottom: 20px; border-bottom: 2px solid rgba(6, 182, 212, 0.3); padding-bottom: 10px;">
                <i class="fas fa-info-circle" style="color: #06b6d4; margin-right: 10px;"></i>
                Informations
            </h2>
            <div style="display: grid; gap: 15px;">
                <div>
                    <div style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; margin-bottom: 5px;">Email</div>
                    <div style="color: white; font-weight: 600;">{{ $affiliate->email }}</div>
                </div>
                <div>
                    <div style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; margin-bottom: 5px;">Code d'Affiliation</div>
                    <code style="background: rgba(15, 23, 42, 0.6); padding: 5px 10px; border-radius: 6px; color: #06b6d4; font-size: 1rem; font-weight: 600;">{{ $affiliate->affiliate_code }}</code>
                </div>
                <div>
                    <div style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; margin-bottom: 5px;">Lien de Référence</div>
                    <div style="background: rgba(15, 23, 42, 0.6); padding: 10px; border-radius: 6px; color: #06b6d4; font-size: 0.9rem; word-break: break-all;">{{ $affiliate->referral_url }}</div>
                </div>
                @if($affiliate->user)
                <div>
                    <div style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; margin-bottom: 5px;">Utilisateur</div>
                    <div style="color: white; font-weight: 600;">{{ $affiliate->user->name }}</div>
                </div>
                @endif
                <div>
                    <div style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; margin-bottom: 5px;">Statut</div>
                    <span style="padding: 5px 12px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; 
                        background: {{ $affiliate->status === 'active' ? 'rgba(16, 185, 129, 0.2)' : ($affiliate->status === 'suspended' ? 'rgba(239, 68, 68, 0.2)' : 'rgba(107, 114, 128, 0.2)') }};
                        color: {{ $affiliate->status === 'active' ? '#10b981' : ($affiliate->status === 'suspended' ? '#ef4444' : '#6b7280') }};
                        border: 1px solid {{ $affiliate->status === 'active' ? '#10b981' : ($affiliate->status === 'suspended' ? '#ef4444' : '#6b7280') }};
                    ">
                        {{ $affiliate->status === 'active' ? 'Actif' : ($affiliate->status === 'suspended' ? 'Suspendu' : 'Inactif') }}
                    </span>
                </div>
                @if($affiliate->notes)
                <div>
                    <div style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; margin-bottom: 5px;">Notes</div>
                    <div style="color: white;">{{ $affiliate->notes }}</div>
                </div>
                @endif
            </div>
        </div>

        <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 25px;">
            <h2 style="font-size: 1.3rem; font-weight: 700; color: white; margin-bottom: 20px; border-bottom: 2px solid rgba(6, 182, 212, 0.3); padding-bottom: 10px;">
                <i class="fas fa-chart-line" style="color: #06b6d4; margin-right: 10px;"></i>
                Statistiques
            </h2>
            <div style="display: grid; gap: 20px;">
                <div>
                    <div style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; margin-bottom: 5px;">Taux de Commission</div>
                    <div style="font-size: 2rem; font-weight: 800; color: #06b6d4;">{{ number_format($affiliate->commission_rate, 2) }}%</div>
                </div>
                <div>
                    <div style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; margin-bottom: 5px;">Total Références</div>
                    <div style="font-size: 2rem; font-weight: 800; color: white;">{{ $affiliateStats['total_referrals'] }}</div>
                    <div style="font-size: 0.85rem; color: rgba(255, 255, 255, 0.6); margin-top: 5px;">
                        En attente: {{ $affiliateStats['pending_referrals'] }} | 
                        Approuvées: {{ $affiliateStats['approved_referrals'] }} | 
                        Payées: {{ $affiliateStats['paid_referrals'] }}
                    </div>
                </div>
                <div>
                    <div style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; margin-bottom: 5px;">Gains Totaux</div>
                    <div style="font-size: 2rem; font-weight: 800; color: #10b981;">{{ number_format($affiliate->total_earnings, 0, ',', ' ') }} FCFA</div>
                </div>
                <div>
                    <div style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; margin-bottom: 5px;">Gains Payés</div>
                    <div style="font-size: 1.5rem; font-weight: 800; color: #10b981;">{{ number_format($affiliate->paid_earnings, 0, ',', ' ') }} FCFA</div>
                </div>
                <div>
                    <div style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; margin-bottom: 5px;">En Attente</div>
                    <div style="font-size: 1.5rem; font-weight: 800; color: #fbbf24;">{{ number_format($affiliate->pending_earnings, 0, ',', ' ') }} FCFA</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Références -->
    <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 25px;">
        <h2 style="font-size: 1.5rem; font-weight: 700; color: white; margin-bottom: 20px;">
            <i class="fas fa-list" style="color: #06b6d4; margin-right: 10px;"></i>
            Références ({{ $referrals->total() }})
        </h2>

        @if($referrals->count() > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: rgba(15, 23, 42, 0.8);">
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">Date</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">Type</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">Utilisateur</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">Montant</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">Commission</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">Statut</th>
                        <th style="padding: 15px; text-align: center; color: rgba(255, 255, 255, 0.9); font-weight: 600; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">Actions</th>
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
                        <td style="padding: 15px; color: white;">
                            @if($referral->user)
                                {{ $referral->user->name }}
                            @else
                                <span style="color: rgba(255, 255, 255, 0.5);">N/A</span>
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
                        <td style="padding: 15px;">
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                @if($referral->status === 'pending')
                                <form action="{{ route('admin.monetization.affiliates.referrals.approve', [$affiliate->id, $referral->id]) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" style="padding: 6px 12px; background: rgba(16, 185, 129, 0.2); color: #10b981; border: 1px solid #10b981; border-radius: 6px; cursor: pointer; font-size: 0.85rem;" title="Approuver">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.monetization.affiliates.referrals.reject', [$affiliate->id, $referral->id]) }}" method="POST" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir rejeter cette référence ?');">
                                    @csrf
                                    <button type="submit" style="padding: 6px 12px; background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid #ef4444; border-radius: 6px; cursor: pointer; font-size: 0.85rem;" title="Rejeter">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                                @elseif($referral->status === 'approved')
                                <form action="{{ route('admin.monetization.affiliates.referrals.pay', [$affiliate->id, $referral->id]) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" style="padding: 6px 12px; background: rgba(16, 185, 129, 0.2); color: #10b981; border: 1px solid #10b981; border-radius: 6px; cursor: pointer; font-size: 0.85rem;" title="Marquer comme payée">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
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
            <p>Aucune référence pour le moment.</p>
        </div>
        @endif
    </div>
</div>

<style>
@media (max-width: 768px) {
    .affiliate-show-grid {
        grid-template-columns: 1fr !important;
    }
    table {
        font-size: 0.85rem;
    }
    th, td {
        padding: 10px 8px !important;
    }
}
</style>
@endsection

