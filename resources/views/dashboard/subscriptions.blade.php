@extends('dashboard.layout')

@section('title', 'Mes Abonnements | NiangProgrammeur')

@section('dashboard-content')
<div class="subscriptions-container">
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 2rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">
            <i class="fas fa-crown" style="color: #06b6d4; margin-right: 10px;"></i>
            Mes Abonnements
        </h1>
        <p style="color: #64748b; font-size: 1rem;">
            Gérez vos abonnements premium et accédez à tous les contenus exclusifs
        </p>
    </div>

    @if($activeSubscription)
    <!-- Abonnement actif -->
    <div style="background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(20, 184, 166, 0.1) 100%); border: 2px solid #06b6d4; border-radius: 16px; padding: 30px; margin-bottom: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 20px;">
            <div style="flex: 1;">
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                    <div style="width: 60px; height: 60px; background: #06b6d4; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-crown" style="font-size: 1.5rem; color: white;"></i>
                    </div>
                    <div>
                        <h2 style="font-size: 1.5rem; font-weight: 700; color: #1e293b; margin: 0;">
                            Abonnement Premium {{ $activeSubscription->plan_type === 'monthly' ? 'Mensuel' : 'Annuel' }}
                        </h2>
                        <span style="padding: 4px 12px; background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; border-radius: 6px; color: #10b981; font-size: 0.85rem; font-weight: 600;">
                            Actif
                        </span>
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 20px;">
                    <div>
                        <div style="color: #64748b; font-size: 0.9rem; margin-bottom: 5px;">Montant</div>
                        <div style="color: #1e293b; font-size: 1.5rem; font-weight: 700;">
                            {{ number_format($activeSubscription->amount, 0, ',', ' ') }} {{ $activeSubscription->currency }}
                        </div>
                    </div>
                    <div>
                        <div style="color: #64748b; font-size: 0.9rem; margin-bottom: 5px;">Date de début</div>
                        <div style="color: #1e293b; font-size: 1.1rem; font-weight: 600;">
                            {{ $activeSubscription->start_date ? $activeSubscription->start_date->format('d/m/Y') : 'N/A' }}
                        </div>
                    </div>
                    <div>
                        <div style="color: #64748b; font-size: 0.9rem; margin-bottom: 5px;">Date de fin</div>
                        <div style="color: #1e293b; font-size: 1.1rem; font-weight: 600;">
                            {{ $activeSubscription->end_date ? $activeSubscription->end_date->format('d/m/Y') : 'Illimité' }}
                        </div>
                    </div>
                    @if($activeSubscription->next_billing_date)
                    <div>
                        <div style="color: #64748b; font-size: 0.9rem; margin-bottom: 5px;">Prochain paiement</div>
                        <div style="color: #1e293b; font-size: 1.1rem; font-weight: 600;">
                            {{ $activeSubscription->next_billing_date->format('d/m/Y') }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Aucun abonnement actif -->
    <div style="background: rgba(251, 191, 36, 0.1); border: 1px solid #fbbf24; border-radius: 16px; padding: 30px; margin-bottom: 30px; text-align: center;">
        <i class="fas fa-crown" style="font-size: 3rem; color: #fbbf24; margin-bottom: 15px;"></i>
        <h3 style="font-size: 1.3rem; font-weight: 700; color: #1e293b; margin-bottom: 10px;">
            Aucun abonnement actif
        </h3>
        <p style="color: #64748b; margin-bottom: 20px;">
            Vous n'avez pas d'abonnement premium actif. Abonnez-vous pour accéder à tous les contenus exclusifs.
        </p>
        <a href="{{ route('monetization.index') }}" style="display: inline-block; padding: 12px 24px; background: #06b6d4; color: white; border-radius: 8px; text-decoration: none; font-weight: 600;">
            <i class="fas fa-crown"></i> Voir les offres
        </a>
    </div>
    @endif

    <!-- Historique des abonnements -->
    <div style="margin-bottom: 2rem;">
        <h2 style="font-size: 1.5rem; font-weight: 700; color: #1e293b; margin-bottom: 20px;">
            Historique des abonnements
        </h2>
        
        @if($subscriptions->count() > 0)
        <div style="display: grid; gap: 20px;">
            @foreach($subscriptions as $subscription)
            <div style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 15px;">
                    <div style="flex: 1;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                            <h3 style="font-size: 1.2rem; font-weight: 700; color: #1e293b; margin: 0;">
                                Abonnement {{ $subscription->plan_type === 'monthly' ? 'Mensuel' : 'Annuel' }}
                            </h3>
                            @if($subscription->status === 'active')
                                <span style="padding: 4px 10px; background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; border-radius: 6px; color: #10b981; font-size: 0.85rem; font-weight: 600;">Actif</span>
                            @elseif($subscription->status === 'pending')
                                <span style="padding: 4px 10px; background: rgba(251, 191, 36, 0.2); border: 1px solid #fbbf24; border-radius: 6px; color: #fbbf24; font-size: 0.85rem; font-weight: 600;">En attente</span>
                            @elseif($subscription->status === 'cancelled')
                                <span style="padding: 4px 10px; background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; border-radius: 6px; color: #ef4444; font-size: 0.85rem; font-weight: 600;">Annulé</span>
                            @else
                                <span style="padding: 4px 10px; background: rgba(107, 114, 128, 0.2); border: 1px solid #6b7280; border-radius: 6px; color: #6b7280; font-size: 0.85rem; font-weight: 600;">Expiré</span>
                            @endif
                        </div>
                        
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin-top: 15px;">
                            <div>
                                <div style="color: #64748b; font-size: 0.85rem; margin-bottom: 5px;">Montant</div>
                                <div style="color: #1e293b; font-weight: 600;">
                                    {{ number_format($subscription->amount, 0, ',', ' ') }} {{ $subscription->currency }}
                                </div>
                            </div>
                            <div>
                                <div style="color: #64748b; font-size: 0.85rem; margin-bottom: 5px;">Date de début</div>
                                <div style="color: #1e293b; font-weight: 600;">
                                    {{ $subscription->start_date ? $subscription->start_date->format('d/m/Y') : 'N/A' }}
                                </div>
                            </div>
                            <div>
                                <div style="color: #64748b; font-size: 0.85rem; margin-bottom: 5px;">Date de fin</div>
                                <div style="color: #1e293b; font-weight: 600;">
                                    {{ $subscription->end_date ? $subscription->end_date->format('d/m/Y') : 'Illimité' }}
                                </div>
                            </div>
                            <div>
                                <div style="color: #64748b; font-size: 0.85rem; margin-bottom: 5px;">Créé le</div>
                                <div style="color: #1e293b; font-weight: 600;">
                                    {{ $subscription->created_at->format('d/m/Y') }}
                                </div>
                            </div>
                        </div>
                        
                        @if($subscription->payment)
                        <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #e2e8f0;">
                            <div style="color: #64748b; font-size: 0.85rem; margin-bottom: 5px;">Référence de paiement</div>
                            <div style="color: #1e293b; font-weight: 600; font-family: monospace;">
                                {{ $subscription->payment->payment_reference ?? 'N/A' }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div style="margin-top: 30px; display: flex; justify-content: center;">
            {{ $subscriptions->links() }}
        </div>
        @else
        <div style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 40px; text-align: center;">
            <i class="fas fa-history" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 15px;"></i>
            <p style="color: #64748b;">
                Vous n'avez aucun abonnement dans votre historique.
            </p>
        </div>
        @endif
    </div>
</div>

<style>
.subscriptions-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

@media (max-width: 768px) {
    .subscriptions-container {
        padding: 15px;
    }
}
</style>
@endsection


