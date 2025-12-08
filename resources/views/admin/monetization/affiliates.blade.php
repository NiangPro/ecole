@extends('admin.layout')

@section('title', 'Gestion des Affiliés - Admin')

@section('content')
<div style="padding: 2rem;">
    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
        <div>
            <h1 style="font-size: 2.5rem; font-weight: 800; color: white; margin-bottom: 0.5rem;">
                <i class="fas fa-users" style="color: #06b6d4; margin-right: 15px;"></i>
                Gestion des Affiliés
            </h1>
            <p style="color: rgba(255, 255, 255, 0.7); font-size: 1.1rem;">
                Gérez tous les affiliés et leurs commissions
            </p>
        </div>
        <a href="{{ route('admin.monetization.dashboard') }}" style="padding: 12px 24px; background: rgba(6, 182, 212, 0.2); color: #06b6d4; border: 2px solid #06b6d4; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
            <i class="fas fa-arrow-left" style="margin-right: 8px;"></i>
            Retour au dashboard
        </a>
    </div>

    @if($affiliates->count() > 0)
    <div style="display: grid; gap: 20px;">
        @foreach($affiliates as $affiliate)
        <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 25px;">
            <div style="display: grid; grid-template-columns: 1fr auto; gap: 20px; margin-bottom: 20px;">
                <div>
                    <h3 style="font-size: 1.5rem; font-weight: 700; color: white; margin-bottom: 10px;">
                        {{ $affiliate->name }}
                    </h3>
                    <div style="color: rgba(255, 255, 255, 0.7); margin-bottom: 5px;">
                        <i class="fas fa-envelope" style="margin-right: 8px; color: #06b6d4;"></i>
                        {{ $affiliate->email }}
                    </div>
                    <div style="color: rgba(255, 255, 255, 0.7); margin-bottom: 5px;">
                        <i class="fas fa-code" style="margin-right: 8px; color: #06b6d4;"></i>
                        Code: <span style="font-family: monospace; font-weight: 600; color: #06b6d4;">{{ $affiliate->affiliate_code }}</span>
                    </div>
                    @if($affiliate->user)
                    <div style="color: rgba(255, 255, 255, 0.7);">
                        <i class="fas fa-user" style="margin-right: 8px; color: #06b6d4;"></i>
                        Utilisateur: {{ $affiliate->user->name }}
                    </div>
                    @endif
                </div>
                <div style="text-align: right;">
                    <span style="padding: 6px 15px; background: {{ $affiliate->status === 'active' ? 'rgba(16, 185, 129, 0.2)' : 'rgba(107, 114, 128, 0.2)' }}; border: 1px solid {{ $affiliate->status === 'active' ? '#10b981' : '#6b7280' }}; border-radius: 6px; color: {{ $affiliate->status === 'active' ? '#10b981' : '#6b7280' }}; font-size: 0.9rem; font-weight: 600; text-transform: uppercase;">
                        {{ $affiliate->status }}
                    </span>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px; padding-top: 20px; border-top: 1px solid rgba(6, 182, 212, 0.2);">
                <div>
                    <div style="color: rgba(255, 255, 255, 0.6); font-size: 0.85rem; margin-bottom: 5px;">Taux de Commission</div>
                    <div style="font-size: 1.5rem; font-weight: 800; color: #06b6d4;">
                        {{ number_format($affiliate->commission_rate, 2) }}%
                    </div>
                </div>
                <div>
                    <div style="color: rgba(255, 255, 255, 0.6); font-size: 0.85rem; margin-bottom: 5px;">Gains Totaux</div>
                    <div style="font-size: 1.5rem; font-weight: 800; color: #10b981;">
                        {{ number_format($affiliate->total_earnings, 0, ',', ' ') }} FCFA
                    </div>
                </div>
                <div>
                    <div style="color: rgba(255, 255, 255, 0.6); font-size: 0.85rem; margin-bottom: 5px;">Gains Payés</div>
                    <div style="font-size: 1.5rem; font-weight: 800; color: #10b981;">
                        {{ number_format($affiliate->paid_earnings, 0, ',', ' ') }} FCFA
                    </div>
                </div>
                <div>
                    <div style="color: rgba(255, 255, 255, 0.6); font-size: 0.85rem; margin-bottom: 5px;">En Attente</div>
                    <div style="font-size: 1.5rem; font-weight: 800; color: #fbbf24;">
                        {{ number_format($affiliate->pending_earnings, 0, ',', ' ') }} FCFA
                    </div>
                </div>
                <div>
                    <div style="color: rgba(255, 255, 255, 0.6); font-size: 0.85rem; margin-bottom: 5px;">Références</div>
                    <div style="font-size: 1.5rem; font-weight: 800; color: #8b5cf6;">
                        {{ $affiliate->referrals_count }}
                    </div>
                </div>
            </div>

            @if($affiliate->notes)
            <div style="padding: 15px; background: rgba(15, 23, 42, 0.6); border-left: 3px solid #06b6d4; border-radius: 8px; margin-top: 15px;">
                <div style="color: rgba(255, 255, 255, 0.8); font-size: 0.9rem;">
                    <strong>Notes:</strong> {{ $affiliate->notes }}
                </div>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div style="margin-top: 30px; display: flex; justify-content: center;">
        {{ $affiliates->links() }}
    </div>
    @else
    <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 60px; text-align: center;">
        <i class="fas fa-users" style="font-size: 4rem; color: rgba(6, 182, 212, 0.5); margin-bottom: 20px;"></i>
        <h3 style="font-size: 1.5rem; font-weight: 700; color: white; margin-bottom: 10px;">
            Aucun affilié
        </h3>
        <p style="color: rgba(255, 255, 255, 0.7);">
            Aucun affilié n'a été enregistré pour le moment.
        </p>
    </div>
    @endif
</div>

<style>
    @media (max-width: 768px) {
        .affiliate-grid { grid-template-columns: 1fr !important; }
    }
</style>
@endsection



