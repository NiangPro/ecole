@extends('admin.layout')

@section('title', 'Statistiques des Donations - Admin')

@section('content')
<div style="padding: 2rem;">
    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
        <div>
            <h1 style="font-size: 2.5rem; font-weight: 800; color: white; margin-bottom: 0.5rem;">
                <i class="fas fa-chart-bar" style="color: #06b6d4; margin-right: 15px;"></i>
                Statistiques des Donations
            </h1>
            <p style="color: rgba(255, 255, 255, 0.7); font-size: 1.1rem;">
                Analyse détaillée des donations reçues
            </p>
        </div>
        <a href="{{ route('admin.monetization.donations.index') }}" style="padding: 12px 24px; background: rgba(6, 182, 212, 0.2); color: #06b6d4; border: 2px solid #06b6d4; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
            <i class="fas fa-arrow-left" style="margin-right: 8px;"></i>
            Retour à la liste
        </a>
    </div>

    <!-- Statistiques Principales -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2)); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 25px;">
            <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 8px;">Total Donations</div>
            <div style="font-size: 2rem; font-weight: 800; color: #06b6d4;">{{ $stats['total_donations'] }}</div>
        </div>

        <div style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(5, 150, 105, 0.2)); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 16px; padding: 25px;">
            <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 8px;">Donations Complétées</div>
            <div style="font-size: 2rem; font-weight: 800; color: #10b981;">{{ $stats['completed_donations'] }}</div>
            <div style="font-size: 1.1rem; font-weight: 700; color: #10b981; margin-top: 5px;">
                {{ number_format($stats['total_amount'], 0, ',', ' ') }} FCFA
            </div>
        </div>

        <div style="background: linear-gradient(135deg, rgba(251, 191, 36, 0.2), rgba(245, 158, 11, 0.2)); border: 1px solid rgba(251, 191, 36, 0.3); border-radius: 16px; padding: 25px;">
            <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 8px;">En Attente</div>
            <div style="font-size: 2rem; font-weight: 800; color: #fbbf24;">{{ $stats['pending_donations'] }}</div>
            <div style="font-size: 1.1rem; font-weight: 700; color: #fbbf24; margin-top: 5px;">
                {{ number_format($stats['pending_amount'], 0, ',', ' ') }} FCFA
            </div>
        </div>

        <div style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(220, 38, 38, 0.2)); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 16px; padding: 25px;">
            <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 8px;">Échouées</div>
            <div style="font-size: 2rem; font-weight: 800; color: #ef4444;">{{ $stats['failed_donations'] }}</div>
        </div>

        <div style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.2), rgba(124, 58, 237, 0.2)); border: 1px solid rgba(139, 92, 246, 0.3); border-radius: 16px; padding: 25px;">
            <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 8px;">Don Moyen</div>
            <div style="font-size: 2rem; font-weight: 800; color: #8b5cf6;">
                {{ number_format($stats['average_donation'] ?? 0, 0, ',', ' ') }} FCFA
            </div>
        </div>

        <div style="background: linear-gradient(135deg, rgba(107, 114, 128, 0.2), rgba(75, 85, 99, 0.2)); border: 1px solid rgba(107, 114, 128, 0.3); border-radius: 16px; padding: 25px;">
            <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 8px;">Anonymes</div>
            <div style="font-size: 2rem; font-weight: 800; color: #6b7280;">{{ $stats['anonymous_count'] }}</div>
        </div>

        <div style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(5, 150, 105, 0.2)); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 16px; padding: 25px;">
            <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 8px;">Publiques</div>
            <div style="font-size: 2rem; font-weight: 800; color: #10b981;">{{ $stats['public_count'] }}</div>
        </div>
    </div>

    <!-- Donations par Mois -->
    @if($donationsByMonth->count() > 0)
    <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 30px; margin-bottom: 30px;">
        <h2 style="font-size: 1.5rem; font-weight: 700; color: white; margin-bottom: 25px;">
            <i class="fas fa-chart-line" style="color: #06b6d4; margin-right: 10px;"></i>
            Donations par Mois (12 derniers mois)
        </h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
            @foreach($donationsByMonth as $monthData)
            <div style="text-align: center; padding: 20px; background: rgba(15, 23, 42, 0.6); border-radius: 12px; border: 1px solid rgba(6, 182, 212, 0.2);">
                <div style="font-size: 1.5rem; font-weight: 700; color: #06b6d4; margin-bottom: 5px;">
                    {{ number_format($monthData->total, 0, ',', ' ') }} FCFA
                </div>
                <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 5px;">
                    {{ $monthData->count }} donation(s)
                </div>
                <div style="color: rgba(255, 255, 255, 0.6); font-size: 0.85rem;">
                    {{ \Carbon\Carbon::createFromFormat('Y-m', $monthData->month)->format('M Y') }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Top 10 Donateurs -->
    @if($topDonors->count() > 0)
    <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 30px;">
        <h2 style="font-size: 1.5rem; font-weight: 700; color: white; margin-bottom: 25px;">
            <i class="fas fa-trophy" style="color: #fbbf24; margin-right: 10px;"></i>
            Top 10 Donateurs
        </h2>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid rgba(6, 182, 212, 0.3);">
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">Rang</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">Donateur</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">Email</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">Total Donné</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">Nombre de Dons</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topDonors as $index => $donor)
                    <tr style="border-bottom: 1px solid rgba(6, 182, 212, 0.1);">
                        <td style="padding: 15px; color: rgba(255, 255, 255, 0.8);">
                            @if($index === 0)
                            <span style="padding: 6px 12px; background: linear-gradient(135deg, #fbbf24, #f59e0b); border-radius: 50%; color: white; font-weight: 700; font-size: 1.1rem;">🥇</span>
                            @elseif($index === 1)
                            <span style="padding: 6px 12px; background: linear-gradient(135deg, #9ca3af, #6b7280); border-radius: 50%; color: white; font-weight: 700; font-size: 1.1rem;">🥈</span>
                            @elseif($index === 2)
                            <span style="padding: 6px 12px; background: linear-gradient(135deg, #cd7f32, #b87333); border-radius: 50%; color: white; font-weight: 700; font-size: 1.1rem;">🥉</span>
                            @else
                            <span style="color: rgba(255, 255, 255, 0.7); font-weight: 600;">#{{ $index + 1 }}</span>
                            @endif
                        </td>
                        <td style="padding: 15px; color: white; font-weight: 600;">{{ $donor->donor_name }}</td>
                        <td style="padding: 15px; color: rgba(255, 255, 255, 0.8);">{{ $donor->donor_email ?? 'N/A' }}</td>
                        <td style="padding: 15px; color: #10b981; font-weight: 700; font-size: 1.1rem;">
                            {{ number_format($donor->total_donated, 0, ',', ' ') }} FCFA
                        </td>
                        <td style="padding: 15px; color: rgba(255, 255, 255, 0.8);">
                            <span style="padding: 4px 10px; background: rgba(6, 182, 212, 0.2); border: 1px solid #06b6d4; border-radius: 6px; color: #06b6d4; font-size: 0.85rem; font-weight: 600;">
                                {{ $donor->donation_count }} don(s)
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
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



