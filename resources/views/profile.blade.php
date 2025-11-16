@extends('layouts.app')

@section('title', 'Mon Profil | NiangProgrammeur')

@section('content')
<div style="min-height: calc(100vh - 200px); padding: 40px 20px; background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);">
    <div style="max-width: 1200px; margin: 0 auto;">
        <h1 style="font-size: 2.5rem; font-weight: 900; color: #fff; margin-bottom: 40px; text-align: center; background: linear-gradient(135deg, #06b6d4, #14b8a6); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
            Mon Profil
        </h1>

        <div style="display: grid; grid-template-columns: 300px 1fr; gap: 30px; margin-bottom: 40px;">
            <!-- Profil Card -->
            <div style="background: rgba(15, 23, 42, 0.8); border: 2px solid rgba(6, 182, 212, 0.3); border-radius: 24px; padding: 30px; height: fit-content; position: sticky; top: 80px;">
                <div style="text-align: center; margin-bottom: 30px;">
                    <div style="width: 100px; height: 100px; background: linear-gradient(135deg, #06b6d4, #14b8a6); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 900; color: #fff; font-size: 2.5rem; margin: 0 auto 20px;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <h2 style="font-size: 1.5rem; font-weight: 800; color: #fff; margin-bottom: 5px;">{{ $user->name }}</h2>
                    <p style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem;">{{ $user->email }}</p>
                    @if($user->phone)
                    <p style="color: rgba(255, 255, 255, 0.6); font-size: 0.85rem; margin-top: 5px;"><i class="fas fa-phone mr-2"></i>{{ $user->phone }}</p>
                    @endif
                </div>
                <div style="border-top: 1px solid rgba(6, 182, 212, 0.2); padding-top: 20px;">
                    <div style="margin-bottom: 15px;">
                        <p style="color: rgba(255, 255, 255, 0.6); font-size: 0.85rem; margin-bottom: 5px;">Membre depuis</p>
                        <p style="color: #fff; font-weight: 600;">{{ $user->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Contenu Principal -->
            <div>
                <!-- Progression Formations -->
                <div style="background: rgba(15, 23, 42, 0.8); border: 2px solid rgba(6, 182, 212, 0.3); border-radius: 24px; padding: 30px; margin-bottom: 30px;">
                    <h3 style="font-size: 1.5rem; font-weight: 800; color: #06b6d4; margin-bottom: 25px; display: flex; align-items: center; gap: 12px;">
                        <i class="fas fa-chart-line"></i>
                        Progression des Formations
                    </h3>

                    @if($progress->count() > 0)
                    <div style="display: grid; gap: 20px;">
                        @foreach($progress as $prog)
                        <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(6, 182, 212, 0.2); border-radius: 16px; padding: 20px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                                <h4 style="font-size: 1.1rem; font-weight: 700; color: #fff; text-transform: uppercase;">
                                    {{ str_replace('-', ' ', $prog->formation_slug) }}
                                </h4>
                                <span style="padding: 6px 12px; background: rgba(6, 182, 212, 0.2); color: #06b6d4; border-radius: 8px; font-size: 0.85rem; font-weight: 600;">
                                    {{ $prog->progress_percentage }}%
                                </span>
                            </div>
                            
                            <div style="background: rgba(15, 23, 42, 0.8); height: 10px; border-radius: 10px; overflow: hidden; margin-bottom: 10px;">
                                <div style="background: linear-gradient(135deg, #06b6d4, #14b8a6); height: 100%; width: {{ $prog->progress_percentage }}%; transition: width 0.5s ease;"></div>
                            </div>
                            
                            <div style="display: flex; justify-content: space-between; font-size: 0.85rem; color: rgba(255, 255, 255, 0.6);">
                                <span><i class="fas fa-check-circle mr-2"></i>{{ count($prog->completed_sections ?? []) }} sections complétées</span>
                                <span><i class="fas fa-clock mr-2"></i>{{ $prog->time_spent_minutes }} min</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div style="text-align: center; padding: 40px; color: rgba(255, 255, 255, 0.6);">
                        <i class="fas fa-chart-line" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.5;"></i>
                        <p>Aucune formation en cours. Commencez votre première formation!</p>
                    </div>
                    @endif
                </div>

                <!-- Statistiques -->
                <div style="background: rgba(15, 23, 42, 0.8); border: 2px solid rgba(6, 182, 212, 0.3); border-radius: 24px; padding: 30px;">
                    <h3 style="font-size: 1.5rem; font-weight: 800; color: #06b6d4; margin-bottom: 25px; display: flex; align-items: center; gap: 12px;">
                        <i class="fas fa-chart-bar"></i>
                        Statistiques
                    </h3>
                    
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                        <div style="text-align: center; padding: 20px; background: rgba(15, 23, 42, 0.6); border-radius: 16px;">
                            <div style="font-size: 2.5rem; font-weight: 900; color: #06b6d4; margin-bottom: 10px;">{{ $progress->count() }}</div>
                            <p style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem;">Formations</p>
                        </div>
                        <div style="text-align: center; padding: 20px; background: rgba(15, 23, 42, 0.6); border-radius: 16px;">
                            <div style="font-size: 2.5rem; font-weight: 900; color: #14b8a6; margin-bottom: 10px;">{{ $progress->sum('time_spent_minutes') }}</div>
                            <p style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem;">Minutes</p>
                        </div>
                        <div style="text-align: center; padding: 20px; background: rgba(15, 23, 42, 0.6); border-radius: 16px;">
                            <div style="font-size: 2.5rem; font-weight: 900; color: #a855f7; margin-bottom: 10px;">{{ $progress->where('progress_percentage', 100)->count() }}</div>
                            <p style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem;">Terminées</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media (max-width: 968px) {
        div[style*="grid-template-columns: 300px 1fr"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection

