@extends('admin.layout')

@section('title', 'Détails du Plan d\'Abonnement - Admin')

@section('content')
<div style="padding: 2rem;">
    <div style="margin-bottom: 2rem;">
        <a href="{{ route('admin.monetization.subscription-plans.index') }}" style="display: inline-flex; align-items: center; gap: 8px; color: #06b6d4; text-decoration: none; margin-bottom: 20px; font-weight: 600;">
            <i class="fas fa-arrow-left"></i> Retour aux plans
        </a>
        <h1 style="font-size: 2.5rem; font-weight: 800; color: white; margin-bottom: 0.5rem;">
            <i class="fas fa-cog" style="color: #06b6d4; margin-right: 15px;"></i>
            Plan : {{ $plan->name }}
        </h1>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
        <!-- Informations principales -->
        <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 30px;">
            <h2 style="font-size: 1.5rem; font-weight: 700; color: white; margin-bottom: 20px;">
                Informations du plan
            </h2>
            
            <div style="display: grid; gap: 20px;">
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                    @if($plan->is_featured)
                    <span style="padding: 6px 12px; background: rgba(251, 191, 36, 0.2); border: 1px solid #fbbf24; border-radius: 6px; color: #fbbf24; font-size: 0.85rem; font-weight: 600;">
                        <i class="fas fa-star"></i> Mis en avant
                    </span>
                    @endif
                    @if($plan->is_active)
                    <span style="padding: 6px 12px; background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; border-radius: 6px; color: #10b981; font-size: 0.85rem; font-weight: 600;">
                        Actif
                    </span>
                    @else
                    <span style="padding: 6px 12px; background: rgba(107, 114, 128, 0.2); border: 1px solid #6b7280; border-radius: 6px; color: #6b7280; font-size: 0.85rem; font-weight: 600;">
                        Inactif
                    </span>
                    @endif
                    @if($plan->badge)
                    <span style="padding: 6px 12px; background: rgba(6, 182, 212, 0.2); border: 1px solid #06b6d4; border-radius: 6px; color: #06b6d4; font-size: 0.85rem; font-weight: 600;">
                        {{ $plan->badge }}
                    </span>
                    @endif
                </div>
                
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                    <div>
                        <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 8px;">Nom</div>
                        <div style="color: white; font-size: 1.1rem; font-weight: 600;">
                            {{ $plan->name }}
                        </div>
                    </div>
                    <div>
                        <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 8px;">Slug</div>
                        <div style="color: white; font-family: monospace; font-size: 1rem;">
                            {{ $plan->slug }}
                        </div>
                    </div>
                    <div>
                        <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 8px;">Prix</div>
                        <div style="color: #06b6d4; font-size: 1.5rem; font-weight: 700;">
                            {{ number_format($plan->price, 0, ',', ' ') }} {{ $plan->currency }}
                        </div>
                        <div style="color: rgba(255, 255, 255, 0.6); font-size: 0.85rem;">
                            {{ $plan->billing_period === 'yearly' ? 'par an' : 'par mois' }}
                        </div>
                    </div>
                    <div>
                        <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 8px;">Durée</div>
                        <div style="color: white; font-size: 1.1rem; font-weight: 600;">
                            {{ $plan->duration_days }} jours
                        </div>
                    </div>
                    <div>
                        <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 8px;">Période</div>
                        <div style="color: white; font-size: 1rem;">
                            {{ $plan->billing_period === 'yearly' ? 'Annuel' : 'Mensuel' }}
                        </div>
                    </div>
                    <div>
                        <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 8px;">Ordre</div>
                        <div style="color: white; font-size: 1rem;">
                            {{ $plan->order }}
                        </div>
                    </div>
                </div>
                
                @if($plan->description)
                <div>
                    <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 8px;">Description</div>
                    <div style="color: white; font-size: 0.95rem;">
                        {{ $plan->description }}
                    </div>
                </div>
                @endif
                
                @if(!empty($plan->features))
                <div>
                    <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 10px;">Fonctionnalités</div>
                    <ul style="list-style: none; padding: 0; display: grid; gap: 10px;">
                        @foreach($plan->features as $feature)
                        <li style="color: white; display: flex; align-items: center; gap: 10px; padding: 10px; background: rgba(15, 23, 42, 0.5); border-radius: 8px;">
                            <i class="fas fa-check-circle" style="color: #10b981;"></i>
                            <span>{{ $feature }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Statistiques et Actions -->
        <div>
            <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 30px; margin-bottom: 20px;">
                <h2 style="font-size: 1.5rem; font-weight: 700; color: white; margin-bottom: 20px;">
                    Statistiques
                </h2>
                <div style="display: grid; gap: 15px;">
                    <div>
                        <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 5px;">Abonnements totaux</div>
                        <div style="color: #06b6d4; font-size: 1.5rem; font-weight: 700;">
                            {{ $plan->subscriptions()->count() }}
                        </div>
                    </div>
                    <div>
                        <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 5px;">Abonnements actifs</div>
                        <div style="color: #10b981; font-size: 1.5rem; font-weight: 700;">
                            {{ $plan->subscriptions()->where('status', 'active')->count() }}
                        </div>
                    </div>
                    <div>
                        <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 5px;">Créé le</div>
                        <div style="color: white; font-size: 1rem;">
                            {{ $plan->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    <div>
                        <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 5px;">Modifié le</div>
                        <div style="color: white; font-size: 1rem;">
                            {{ $plan->updated_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>
            
            <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 30px;">
                <h2 style="font-size: 1.5rem; font-weight: 700; color: white; margin-bottom: 20px;">
                    Actions
                </h2>
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <a href="{{ route('admin.monetization.subscription-plans.edit', $plan->id) }}" style="padding: 12px 24px; background: #06b6d4; color: white; border: none; border-radius: 8px; text-decoration: none; font-weight: 600; text-align: center;">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                    <form action="{{ route('admin.monetization.subscription-plans.destroy', $plan->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce plan ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="width: 100%; padding: 12px 24px; background: #ef4444; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                    </form>
                    <a href="{{ route('admin.monetization.subscription-plans.index') }}" style="padding: 12px 24px; background: rgba(107, 114, 128, 0.3); color: white; border: 1px solid rgba(107, 114, 128, 0.5); border-radius: 8px; text-decoration: none; font-weight: 600; text-align: center;">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

