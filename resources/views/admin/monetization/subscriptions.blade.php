@extends('admin.layout')

@section('title', 'Gestion des Abonnements - Admin')

@section('content')
<div style="padding: 2rem;">
    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
        <div>
            <h1 style="font-size: 2.5rem; font-weight: 800; color: white; margin-bottom: 0.5rem;">
                <i class="fas fa-crown" style="color: #06b6d4; margin-right: 15px;"></i>
                Gestion des Abonnements
            </h1>
            <p style="color: rgba(255, 255, 255, 0.7); font-size: 1.1rem;">
                Gérez tous les abonnements premium de la plateforme
            </p>
        </div>
    </div>

    @if($subscriptions->count() > 0)
    <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 30px;">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid rgba(6, 182, 212, 0.3);">
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">ID</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">Utilisateur</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">Plan</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">Montant</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">Statut</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">Début</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">Fin</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">Méthode</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subscriptions as $subscription)
                    <tr style="border-bottom: 1px solid rgba(6, 182, 212, 0.1);">
                        <td style="padding: 15px; color: rgba(255, 255, 255, 0.8);">#{{ $subscription->id }}</td>
                        <td style="padding: 15px; color: rgba(255, 255, 255, 0.8);">
                            {{ $subscription->user ? $subscription->user->name : 'N/A' }}
                            @if($subscription->user)
                            <div style="font-size: 0.85rem; color: rgba(255, 255, 255, 0.6);">{{ $subscription->user->email }}</div>
                            @endif
                        </td>
                        <td style="padding: 15px;">
                            <span style="padding: 4px 12px; background: rgba(6, 182, 212, 0.2); border: 1px solid #06b6d4; border-radius: 6px; color: #06b6d4; font-size: 0.9rem; font-weight: 600; text-transform: uppercase;">
                                {{ $subscription->plan_type }}
                            </span>
                        </td>
                        <td style="padding: 15px; color: #06b6d4; font-weight: 700;">
                            {{ number_format($subscription->amount, 0, ',', ' ') }} {{ $subscription->currency }}
                        </td>
                        <td style="padding: 15px;">
                            @if($subscription->status === 'active')
                                <span style="padding: 4px 10px; background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; border-radius: 6px; color: #10b981; font-size: 0.85rem; font-weight: 600;">Actif</span>
                            @elseif($subscription->status === 'pending')
                                <span style="padding: 4px 10px; background: rgba(251, 191, 36, 0.2); border: 1px solid #fbbf24; border-radius: 6px; color: #fbbf24; font-size: 0.85rem; font-weight: 600;">En attente</span>
                            @elseif($subscription->status === 'cancelled')
                                <span style="padding: 4px 10px; background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; border-radius: 6px; color: #ef4444; font-size: 0.85rem; font-weight: 600;">Annulé</span>
                            @else
                                <span style="padding: 4px 10px; background: rgba(107, 114, 128, 0.2); border: 1px solid #6b7280; border-radius: 6px; color: #6b7280; font-size: 0.85rem; font-weight: 600;">Expiré</span>
                            @endif
                        </td>
                        <td style="padding: 15px; color: rgba(255, 255, 255, 0.8); font-size: 0.9rem;">
                            {{ $subscription->start_date ? $subscription->start_date->format('d/m/Y') : 'N/A' }}
                        </td>
                        <td style="padding: 15px; color: rgba(255, 255, 255, 0.8); font-size: 0.9rem;">
                            {{ $subscription->end_date ? $subscription->end_date->format('d/m/Y') : 'Illimité' }}
                        </td>
                        <td style="padding: 15px; color: rgba(255, 255, 255, 0.8);">
                            {{ ucfirst(str_replace('_', ' ', $subscription->payment_method ?? 'N/A')) }}
                        </td>
                        <td style="padding: 15px; color: rgba(255, 255, 255, 0.8); font-size: 0.9rem;">
                            {{ $subscription->created_at->format('d/m/Y') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div style="margin-top: 30px; display: flex; justify-content: center;">
            {{ $subscriptions->links() }}
        </div>
    </div>
    @else
    <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 60px; text-align: center;">
        <i class="fas fa-crown" style="font-size: 4rem; color: rgba(6, 182, 212, 0.5); margin-bottom: 20px;"></i>
        <h3 style="font-size: 1.5rem; font-weight: 700; color: white; margin-bottom: 10px;">
            Aucun abonnement
        </h3>
        <p style="color: rgba(255, 255, 255, 0.7);">
            Aucun abonnement n'a été créé pour le moment.
        </p>
    </div>
    @endif
</div>

<style>
    @media (max-width: 768px) {
        table { font-size: 0.85rem; }
        th, td { padding: 10px !important; }
    }
</style>
@endsection



