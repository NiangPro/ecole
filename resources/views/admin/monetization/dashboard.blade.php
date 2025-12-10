@extends('admin.layout')

@section('title', 'Dashboard Monétisation - Admin')

@section('content')
<div style="padding: 2rem;">
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 2.5rem; font-weight: 800; color: white; margin-bottom: 0.5rem;">
            <i class="fas fa-dollar-sign" style="color: #06b6d4; margin-right: 15px;"></i>
            Dashboard Monétisation
        </h1>
        <p style="color: rgba(255, 255, 255, 0.7); font-size: 1.1rem;">
            Vue d'ensemble des revenus et statistiques de monétisation
        </p>
    </div>

    <!-- Statistiques Principales -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2)); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 25px;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                <div>
                    <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 5px;">Revenus Totaux</div>
                    <div style="font-size: 2rem; font-weight: 800; color: #06b6d4;">{{ number_format($stats['total_revenue'], 0, ',', ' ') }} FCFA</div>
                </div>
                <i class="fas fa-chart-line" style="font-size: 2.5rem; color: rgba(6, 182, 212, 0.5);"></i>
            </div>
        </div>

        <div style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(5, 150, 105, 0.2)); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 16px; padding: 25px;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                <div>
                    <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 5px;">Abonnements Actifs</div>
                    <div style="font-size: 2rem; font-weight: 800; color: #10b981;">{{ $stats['subscriptions_count'] }}</div>
                </div>
                <i class="fas fa-crown" style="font-size: 2.5rem; color: rgba(16, 185, 129, 0.5);"></i>
            </div>
        </div>

        <div style="background: linear-gradient(135deg, rgba(251, 191, 36, 0.2), rgba(245, 158, 11, 0.2)); border: 1px solid rgba(251, 191, 36, 0.3); border-radius: 16px; padding: 25px;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                <div>
                    <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 5px;">Cours Vendus</div>
                    <div style="font-size: 2rem; font-weight: 800; color: #fbbf24;">{{ $stats['courses_sold'] }}</div>
                </div>
                <i class="fas fa-graduation-cap" style="font-size: 2.5rem; color: rgba(251, 191, 36, 0.5);"></i>
            </div>
        </div>

        <div style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(220, 38, 38, 0.2)); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 16px; padding: 25px;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                <div>
                    <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 5px;">Total Dons</div>
                    <div style="font-size: 2rem; font-weight: 800; color: #ef4444;">{{ number_format($stats['donations_total'], 0, ',', ' ') }} FCFA</div>
                </div>
                <i class="fas fa-heart" style="font-size: 2.5rem; color: rgba(239, 68, 68, 0.5);"></i>
            </div>
        </div>

        <div style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.2), rgba(124, 58, 237, 0.2)); border: 1px solid rgba(139, 92, 246, 0.3); border-radius: 16px; padding: 25px;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                <div>
                    <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 5px;">Affiliés Actifs</div>
                    <div style="font-size: 2rem; font-weight: 800; color: #8b5cf6;">{{ $stats['affiliates_count'] }}</div>
                </div>
                <i class="fas fa-users" style="font-size: 2.5rem; color: rgba(139, 92, 246, 0.5);"></i>
            </div>
        </div>

        <div style="background: linear-gradient(135deg, rgba(251, 191, 36, 0.2), rgba(245, 158, 11, 0.2)); border: 1px solid rgba(251, 191, 36, 0.3); border-radius: 16px; padding: 25px;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                <div>
                    <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 5px;">Paiements en Attente</div>
                    <div style="font-size: 2rem; font-weight: 800; color: #fbbf24;">{{ $stats['pending_payments'] }}</div>
                </div>
                <i class="fas fa-clock" style="font-size: 2.5rem; color: rgba(251, 191, 36, 0.5);"></i>
            </div>
        </div>
    </div>

    <!-- Graphique des Revenus -->
    @if($revenueByMonth->count() > 0)
    <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 30px; margin-bottom: 30px;">
        <h2 style="font-size: 1.5rem; font-weight: 700; color: white; margin-bottom: 25px;">
            <i class="fas fa-chart-area" style="color: #06b6d4; margin-right: 10px;"></i>
            Revenus par Mois
        </h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(100px, 1fr)); gap: 15px;">
            @foreach($revenueByMonth as $monthData)
            <div style="text-align: center;">
                <div style="font-size: 1.25rem; font-weight: 700; color: #06b6d4; margin-bottom: 5px;">
                    {{ number_format($monthData->total, 0, ',', ' ') }} FCFA
                </div>
                <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.85rem;">
                    {{ \Carbon\Carbon::createFromFormat('Y-m', $monthData->month)->format('M Y') }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Paiements Récents -->
    <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 30px;">
        <h2 style="font-size: 1.5rem; font-weight: 700; color: white; margin-bottom: 25px;">
            <i class="fas fa-receipt" style="color: #06b6d4; margin-right: 10px;"></i>
            Paiements Récents
        </h2>
        
        @if($recentPayments->count() > 0)
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
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentPayments as $payment)
                    <tr style="border-bottom: 1px solid rgba(6, 182, 212, 0.1);">
                        <td style="padding: 15px; color: rgba(255, 255, 255, 0.8);">#{{ $payment->id }}</td>
                        <td style="padding: 15px; color: rgba(255, 255, 255, 0.8);">
                            {{ $payment->user ? $payment->user->name : 'N/A' }}
                        </td>
                        <td style="padding: 15px; color: rgba(255, 255, 255, 0.8);">
                            @if(class_basename($payment->paymentable_type) === 'Subscription')
                                <span style="padding: 4px 10px; background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; border-radius: 6px; color: #10b981; font-size: 0.85rem; font-weight: 600;">Abonnement</span>
                            @elseif(class_basename($payment->paymentable_type) === 'CoursePurchase')
                                <span style="padding: 4px 10px; background: rgba(251, 191, 36, 0.2); border: 1px solid #fbbf24; border-radius: 6px; color: #fbbf24; font-size: 0.85rem; font-weight: 600;">Cours</span>
                            @elseif(class_basename($payment->paymentable_type) === 'Donation')
                                <span style="padding: 4px 10px; background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; border-radius: 6px; color: #ef4444; font-size: 0.85rem; font-weight: 600;">Don</span>
                            @endif
                        </td>
                        <td style="padding: 15px; color: #06b6d4; font-weight: 700;">
                            {{ number_format($payment->amount, 0, ',', ' ') }} {{ $payment->currency }}
                        </td>
                        <td style="padding: 15px; color: rgba(255, 255, 255, 0.8);">
                            {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                        </td>
                        <td style="padding: 15px;">
                            @if($payment->status === 'completed')
                                <span style="padding: 4px 10px; background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; border-radius: 6px; color: #10b981; font-size: 0.85rem; font-weight: 600;">Confirmé</span>
                            @elseif($payment->status === 'pending')
                                <span style="padding: 4px 10px; background: rgba(251, 191, 36, 0.2); border: 1px solid #fbbf24; border-radius: 6px; color: #fbbf24; font-size: 0.85rem; font-weight: 600;">En attente</span>
                            @elseif($payment->status === 'failed')
                                <span style="padding: 4px 10px; background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; border-radius: 6px; color: #ef4444; font-size: 0.85rem; font-weight: 600;">Échoué</span>
                            @else
                                <span style="padding: 4px 10px; background: rgba(107, 114, 128, 0.2); border: 1px solid #6b7280; border-radius: 6px; color: #6b7280; font-size: 0.85rem; font-weight: 600;">{{ ucfirst($payment->status) }}</span>
                            @endif
                        </td>
                        <td style="padding: 15px; color: rgba(255, 255, 255, 0.8); font-size: 0.9rem;">
                            {{ $payment->created_at->format('d/m/Y H:i') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div style="text-align: center; padding: 40px; color: rgba(255, 255, 255, 0.7);">
            <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.5;"></i>
            <p>Aucun paiement récent</p>
        </div>
        @endif
    </div>

    <!-- Liens Rapides -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 30px;">
        <a href="{{ route('admin.monetization.subscriptions') }}" style="display: block; padding: 20px; background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 12px; text-decoration: none; transition: all 0.3s ease;">
            <i class="fas fa-crown" style="font-size: 2rem; color: #06b6d4; margin-bottom: 10px;"></i>
            <div style="color: white; font-weight: 600; font-size: 1.1rem;">Abonnements</div>
        </a>
        <a href="{{ route('admin.monetization.courses.index') }}" style="display: block; padding: 20px; background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 12px; text-decoration: none; transition: all 0.3s ease;">
            <i class="fas fa-graduation-cap" style="font-size: 2rem; color: #06b6d4; margin-bottom: 10px;"></i>
            <div style="color: white; font-weight: 600; font-size: 1.1rem;">Cours Payants</div>
        </a>
        <a href="{{ route('admin.monetization.donations.index') }}" style="display: block; padding: 20px; background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 12px; text-decoration: none; transition: all 0.3s ease;">
            <i class="fas fa-heart" style="font-size: 2rem; color: #06b6d4; margin-bottom: 10px;"></i>
            <div style="color: white; font-weight: 600; font-size: 1.1rem;">Donations</div>
        </a>
        <a href="{{ route('admin.monetization.affiliates') }}" style="display: block; padding: 20px; background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 12px; text-decoration: none; transition: all 0.3s ease;">
            <i class="fas fa-users" style="font-size: 2rem; color: #06b6d4; margin-bottom: 10px;"></i>
            <div style="color: white; font-weight: 600; font-size: 1.1rem;">Affiliés</div>
        </a>
        <a href="{{ route('admin.monetization.payments') }}" style="display: block; padding: 20px; background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 12px; text-decoration: none; transition: all 0.3s ease;">
            <i class="fas fa-credit-card" style="font-size: 2rem; color: #06b6d4; margin-bottom: 10px;"></i>
            <div style="color: white; font-weight: 600; font-size: 1.1rem;">Paiements</div>
        </a>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        table { font-size: 0.85rem; }
        th, td { padding: 10px !important; }
    }
</style>
@endsection

