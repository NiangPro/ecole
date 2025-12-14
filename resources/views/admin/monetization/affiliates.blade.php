@extends('admin.layout')

@section('title', 'Gestion des Affiliés - Admin')

@section('content')
<div class="affiliates-admin" style="padding: 2rem;">
    <!-- Header Section -->
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
        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
            <a href="{{ route('admin.monetization.affiliates.create') }}" style="padding: 12px 24px; background: #06b6d4; color: white; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-plus-circle"></i>
                <span>Nouvel Affilié</span>
            </a>
            <a href="{{ route('admin.monetization.dashboard') }}" style="padding: 12px 24px; background: rgba(6, 182, 212, 0.2); color: #06b6d4; border: 2px solid #06b6d4; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 8px;">
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

    @if(session('error'))
    <div style="background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; border-radius: 12px; padding: 15px 20px; margin-bottom: 20px; display: flex; align-items: center; gap: 15px;">
        <i class="fas fa-exclamation-circle" style="color: #ef4444; font-size: 1.2rem;"></i>
        <div style="flex: 1; color: #ef4444;">
            <strong>{{ session('error') }}</strong>
        </div>
        <button onclick="this.parentElement.remove()" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 1.2rem;">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 20px;">
            <div style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; margin-bottom: 10px;">Total Affiliés</div>
            <div style="font-size: 2rem; font-weight: 800; color: white;">{{ $stats['total'] ?? 0 }}</div>
        </div>
        <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 16px; padding: 20px;">
            <div style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; margin-bottom: 10px;">Actifs</div>
            <div style="font-size: 2rem; font-weight: 800; color: #10b981;">{{ $stats['active'] ?? 0 }}</div>
        </div>
        <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 20px;">
            <div style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; margin-bottom: 10px;">Gains Totaux</div>
            <div style="font-size: 2rem; font-weight: 800; color: #06b6d4;">{{ number_format($stats['total_earnings'] ?? 0, 0, ',', ' ') }} FCFA</div>
        </div>
        <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(251, 191, 36, 0.3); border-radius: 16px; padding: 20px;">
            <div style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; margin-bottom: 10px;">En Attente</div>
            <div style="font-size: 2rem; font-weight: 800; color: #fbbf24;">{{ number_format($stats['pending_earnings'] ?? 0, 0, ',', ' ') }} FCFA</div>
        </div>
    </div>

    <!-- Filters -->
    <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 20px; margin-bottom: 20px;">
        <form method="GET" action="{{ route('admin.monetization.affiliates') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; align-items: end;">
            <div>
                <label style="display: block; color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 8px;">Recherche</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, email, code..." style="width: 100%; padding: 10px 15px; background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem;">
            </div>
            <div>
                <label style="display: block; color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 8px;">Statut</label>
                <select name="status" style="width: 100%; padding: 10px 15px; background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem;">
                    <option value="">Tous</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactif</option>
                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspendu</option>
                </select>
            </div>
            <div>
                <label style="display: block; color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 8px;">Tri</label>
                <select name="sort" style="width: 100%; padding: 10px 15px; background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem;">
                    <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Date de création</option>
                    <option value="total_earnings" {{ request('sort') === 'total_earnings' ? 'selected' : '' }}>Gains totaux</option>
                    <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Nom</option>
                </select>
            </div>
            <div>
                <button type="submit" style="width: 100%; padding: 10px 20px; background: #06b6d4; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                    <i class="fas fa-search" style="margin-right: 8px;"></i>Filtrer
                </button>
            </div>
            @if(request()->hasAny(['search', 'status', 'sort']))
            <div>
                <a href="{{ route('admin.monetization.affiliates') }}" style="width: 100%; padding: 10px 20px; background: rgba(107, 114, 128, 0.3); color: white; border: 1px solid rgba(107, 114, 128, 0.5); border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-block; text-align: center;">
                    <i class="fas fa-times" style="margin-right: 8px;"></i>Réinitialiser
                </a>
            </div>
            @endif
        </form>
    </div>

    <!-- Table -->
    @if($affiliates->count() > 0)
    <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; overflow: hidden;">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: rgba(15, 23, 42, 0.8);">
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">Affilié</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">Code</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">Taux</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">Références</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">Gains</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">Statut</th>
                        <th style="padding: 15px; text-align: center; color: rgba(255, 255, 255, 0.9); font-weight: 600; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($affiliates as $affiliate)
                    <tr style="border-bottom: 1px solid rgba(6, 182, 212, 0.1); transition: background 0.2s ease;" onmouseover="this.style.background='rgba(6, 182, 212, 0.1)'" onmouseout="this.style.background='transparent'">
                        <td style="padding: 15px;">
                            <div style="font-weight: 600; color: white; margin-bottom: 5px;">{{ $affiliate->name }}</div>
                            <div style="font-size: 0.85rem; color: rgba(255, 255, 255, 0.6);">{{ $affiliate->email }}</div>
                            @if($affiliate->user)
                            <div style="font-size: 0.8rem; color: rgba(6, 182, 212, 0.8); margin-top: 5px;">
                                <i class="fas fa-user" style="margin-right: 5px;"></i>{{ $affiliate->user->name }}
                            </div>
                            @endif
                        </td>
                        <td style="padding: 15px;">
                            <code style="background: rgba(15, 23, 42, 0.6); padding: 5px 10px; border-radius: 6px; color: #06b6d4; font-size: 0.9rem; font-weight: 600;">{{ $affiliate->affiliate_code }}</code>
                        </td>
                        <td style="padding: 15px; color: white; font-weight: 600;">{{ number_format($affiliate->commission_rate, 2) }}%</td>
                        <td style="padding: 15px; color: white; font-weight: 600;">{{ $affiliate->referrals_count }}</td>
                        <td style="padding: 15px;">
                            <div style="color: #10b981; font-weight: 600; margin-bottom: 3px;">{{ number_format($affiliate->total_earnings, 0, ',', ' ') }} FCFA</div>
                            <div style="font-size: 0.8rem; color: rgba(255, 255, 255, 0.6);">
                                Payé: {{ number_format($affiliate->paid_earnings, 0, ',', ' ') }} FCFA
                            </div>
                            <div style="font-size: 0.8rem; color: #fbbf24;">
                                En attente: {{ number_format($affiliate->pending_earnings, 0, ',', ' ') }} FCFA
                            </div>
                        </td>
                        <td style="padding: 15px;">
                            <span style="padding: 5px 12px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; 
                                background: {{ $affiliate->status === 'active' ? 'rgba(16, 185, 129, 0.2)' : ($affiliate->status === 'suspended' ? 'rgba(239, 68, 68, 0.2)' : 'rgba(107, 114, 128, 0.2)') }};
                                color: {{ $affiliate->status === 'active' ? '#10b981' : ($affiliate->status === 'suspended' ? '#ef4444' : '#6b7280') }};
                                border: 1px solid {{ $affiliate->status === 'active' ? '#10b981' : ($affiliate->status === 'suspended' ? '#ef4444' : '#6b7280') }};
                            ">
                                {{ $affiliate->status === 'active' ? 'Actif' : ($affiliate->status === 'suspended' ? 'Suspendu' : 'Inactif') }}
                            </span>
                        </td>
                        <td style="padding: 15px;">
                            <div style="display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">
                                <a href="{{ route('admin.monetization.affiliates.show', $affiliate->id) }}" style="padding: 6px 12px; background: rgba(6, 182, 212, 0.2); color: #06b6d4; border: 1px solid #06b6d4; border-radius: 6px; text-decoration: none; font-size: 0.85rem; transition: all 0.2s ease;" title="Voir détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.monetization.affiliates.edit', $affiliate->id) }}" style="padding: 6px 12px; background: rgba(251, 191, 36, 0.2); color: #fbbf24; border: 1px solid #fbbf24; border-radius: 6px; text-decoration: none; font-size: 0.85rem; transition: all 0.2s ease;" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.monetization.affiliates.destroy', $affiliate->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet affilié ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="padding: 6px 12px; background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid #ef4444; border-radius: 6px; cursor: pointer; font-size: 0.85rem; transition: all 0.2s ease;" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div style="margin-top: 30px; display: flex; justify-content: center;">
        {{ $affiliates->links() }}
    </div>
    @else
    <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 60px; text-align: center;">
        <i class="fas fa-users" style="font-size: 4rem; color: rgba(6, 182, 212, 0.5); margin-bottom: 20px;"></i>
        <h3 style="font-size: 1.5rem; font-weight: 700; color: white; margin-bottom: 10px;">
            Aucun affilié trouvé
        </h3>
        <p style="color: rgba(255, 255, 255, 0.7); margin-bottom: 20px;">
            @if(request()->hasAny(['search', 'status']))
                Aucun affilié ne correspond à vos critères de recherche.
            @else
                Aucun affilié n'a été enregistré pour le moment.
            @endif
        </p>
        @if(!request()->hasAny(['search', 'status']))
        <a href="{{ route('admin.monetization.affiliates.create') }}" style="padding: 12px 24px; background: #06b6d4; color: white; border-radius: 12px; text-decoration: none; font-weight: 600; display: inline-block;">
            <i class="fas fa-plus-circle" style="margin-right: 8px;"></i>Créer le premier affilié
        </a>
        @endif
    </div>
    @endif
</div>

<style>
@media (max-width: 768px) {
    .affiliates-admin table {
        font-size: 0.85rem;
    }
    .affiliates-admin th,
    .affiliates-admin td {
        padding: 10px 8px !important;
    }
}
</style>
@endsection
