@extends('admin.layout')

@section('title', 'Détails du Cours Payant - Admin')

@section('content')
<style>
    .csh-hero {
        position: relative;
        border-radius: 1.25rem;
        overflow: hidden;
        border: 1px solid rgba(6, 182, 212, 0.25);
        margin-bottom: 1.75rem;
        padding: 2.25rem;
        background: linear-gradient(150deg, rgba(15,23,42,0.92), rgba(6,182,212,0.1));
    }
    .csh-hero-bg {
        position: absolute;
        inset: 0;
        background-size: cover;
        background-position: center;
        filter: blur(18px) brightness(0.45) saturate(1.2);
        transform: scale(1.15);
        z-index: 0;
    }
    .csh-hero > * { position: relative; z-index: 1; }
    body.light-mode .csh-hero { background: linear-gradient(150deg, #ffffff, rgba(6,182,212,0.08)); border-color: rgba(6,182,212,0.3); }

    .csh-card {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 1.25rem;
        padding: 1.75rem;
    }
    body.light-mode .csh-card { background: rgba(255,255,255,0.85); border-color: rgba(6,182,212,0.3); }

    .csh-title { color: #fff; font-weight: 800; font-size: 1.1rem; }
    body.light-mode .csh-title { color: #1e293b; }

    .csh-label { color: rgba(255,255,255,0.6); font-weight: 600; font-size: 0.85rem; }
    body.light-mode .csh-label { color: #64748b; }
    .csh-value { color: #fff; font-weight: 600; }
    body.light-mode .csh-value { color: #1e293b; }

    .csh-row { display: flex; justify-content: space-between; align-items: center; gap: 1rem; padding: 0.9rem 1.1rem; background: rgba(255,255,255,0.03); border-radius: 0.75rem; }
    body.light-mode .csh-row { background: rgba(6,182,212,0.05); }

    .csh-pill { padding: 0.65rem 1.1rem; border-radius: 0.75rem; border: 2px solid rgba(6,182,212,0.3); font-weight: 700; font-size: 0.85rem; color: #06b6d4; cursor: pointer; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; background: rgba(6,182,212,0.1); }
    .csh-pill:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(6,182,212,0.25); }
    .csh-pill.amber { color: #fbbf24; border-color: rgba(251,191,36,0.4); background: rgba(251,191,36,0.1); }
    .csh-pill.violet { color: #8b5cf6; border-color: rgba(139,92,246,0.4); background: rgba(139,92,246,0.1); }
    .csh-pill.green { color: #10b981; border-color: rgba(16,185,129,0.4); background: rgba(16,185,129,0.1); }
    .csh-pill.red { color: #ef4444; border-color: rgba(239,68,68,0.4); background: rgba(239,68,68,0.1); }
    .csh-pill.gray { color: #94a3b8; border-color: rgba(148,163,184,0.35); background: rgba(148,163,184,0.1); }

    .csh-badge { display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.3rem 0.8rem; border-radius: 999px; font-size: 0.78rem; font-weight: 800; }

    .csh-kpi { border-radius: 1.1rem; padding: 1.35rem; border: 1px solid rgba(6,182,212,0.2); background: rgba(15,23,42,0.6); backdrop-filter: blur(20px); transition: all 0.25s ease; }
    .csh-kpi:hover { transform: translateY(-3px); border-color: rgba(6,182,212,0.45); }
    body.light-mode .csh-kpi { background: rgba(255,255,255,0.85); border-color: rgba(6,182,212,0.3); }
    .csh-kpi-num { font-size: 1.7rem; font-weight: 900; }
    .csh-kpi-label { color: rgba(255,255,255,0.55); font-size: 0.8rem; font-weight: 600; margin-top: 0.2rem; }
    body.light-mode .csh-kpi-label { color: #64748b; }

    .csh-learn-item { display: flex; gap: 0.6rem; align-items: flex-start; padding: 0.6rem 0; color: rgba(255,255,255,0.85); }
    body.light-mode .csh-learn-item { color: #334155; }

    .csh-table { width: 100%; border-collapse: collapse; }
    .csh-table th { text-align: left; padding: 0.9rem; font-size: 0.8rem; color: rgba(255,255,255,0.55); border-bottom: 2px solid rgba(6,182,212,0.25); }
    .csh-table td { padding: 0.9rem; border-bottom: 1px solid rgba(6,182,212,0.1); color: rgba(255,255,255,0.85); }
    body.light-mode .csh-table th { color: #64748b; }
    body.light-mode .csh-table td { color: #334155; }

    .csh-avatar { width: 2.25rem; height: 2.25rem; border-radius: 0.6rem; background: linear-gradient(135deg, #06b6d4, #14b8a6); display: inline-flex; align-items: center; justify-content: center; font-weight: 800; color: #04141a; font-size: 0.85rem; margin-right: 0.6rem; flex-shrink: 0; }

    .csh-danger { border: 1px solid rgba(239,68,68,0.35); background: rgba(239,68,68,0.08); border-radius: 1.25rem; padding: 1.75rem; }
</style>

<div style="max-width: 1400px; margin: 0 auto;">

    @if(session('success'))
    <div class="csh-card" style="border-color: rgba(16,185,129,0.4); background: rgba(16,185,129,0.1); margin-bottom: 1.5rem; display:flex; align-items:center; gap:0.75rem; padding: 1rem 1.5rem;">
        <i class="fas fa-check-circle" style="color:#10b981;"></i>
        <span style="color:#10b981; font-weight:600;">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Hero -->
    <div class="csh-hero">
        @if($course->cover_image)
            <div class="csh-hero-bg" style="background-image: url('{{ ($course->cover_type ?? 'internal') === 'internal' ? asset('storage/' . $course->cover_image) : $course->cover_image }}');"></div>
        @endif

        <p style="color:#06b6d4; font-size:0.8rem; font-weight:700; text-transform:uppercase; letter-spacing:0.03em;">
            <a href="{{ route('admin.monetization.courses.index') }}" style="color:inherit; text-decoration:none;">Monétisation / Cours Payants</a>
        </p>

        <div style="display:flex; flex-wrap:wrap; justify-content:space-between; align-items:flex-end; gap:1.5rem; margin-top:0.5rem;">
            <div>
                <div style="display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap;">
                    <h1 style="font-size:2.2rem; font-weight:900; color:white; margin:0;">
                        <i class="fas fa-graduation-cap" style="color:#06b6d4; margin-right:12px;"></i>{{ $course->title }}
                    </h1>
                    @if($course->status === 'published')
                        <span class="csh-badge" style="background:rgba(16,185,129,0.2); color:#10b981;"><i class="fas fa-circle" style="font-size:0.5rem;"></i>Publié</span>
                    @elseif($course->status === 'draft')
                        <span class="csh-badge" style="background:rgba(251,191,36,0.2); color:#fbbf24;"><i class="fas fa-circle" style="font-size:0.5rem;"></i>Brouillon</span>
                    @else
                        <span class="csh-badge" style="background:rgba(107,114,128,0.2); color:#94a3b8;"><i class="fas fa-circle" style="font-size:0.5rem;"></i>Archivé</span>
                    @endif
                </div>
                <p style="color:rgba(255,255,255,0.6); margin-top:0.5rem;">
                    <i class="fas fa-link" style="margin-right:6px;"></i>{{ $course->slug }}
                    <span style="margin: 0 0.6rem;">·</span>
                    <i class="fas fa-clock" style="margin-right:6px;"></i>Créé le {{ $course->created_at->format('d/m/Y') }}
                </p>
            </div>

            <div style="display:flex; gap:0.75rem; flex-wrap:wrap;">
                <a href="{{ route('admin.monetization.courses.edit', $course->id) }}" class="csh-pill amber"><i class="fas fa-edit"></i>Modifier</a>
                <form method="POST" action="{{ route('admin.monetization.courses.duplicate', $course->id) }}" onsubmit="return confirm('Dupliquer ce cours ?');">
                    @csrf
                    <button type="submit" class="csh-pill violet"><i class="fas fa-copy"></i>Dupliquer</button>
                </form>
                <form method="POST" action="{{ route('admin.monetization.courses.toggle-status', $course->id) }}">
                    @csrf
                    <button type="submit" class="csh-pill green">
                        <i class="fas fa-toggle-{{ $course->status === 'published' ? 'on' : 'off' }}"></i>
                        {{ $course->status === 'published' ? 'Dépublier' : 'Publier' }}
                    </button>
                </form>
                <a href="{{ route('admin.monetization.courses.index') }}" class="csh-pill gray"><i class="fas fa-arrow-left"></i>Retour</a>
            </div>
        </div>
    </div>

    <!-- KPIs -->
    <div style="display:grid; grid-template-columns: repeat(2, 1fr); gap:1rem; margin-bottom:1.75rem;" class="csh-kpi-grid">
        <div class="csh-kpi">
            <i class="fas fa-shopping-cart" style="color:#10b981;"></i>
            <div class="csh-kpi-num" style="color:#10b981;">{{ $courseStats['total_sales'] ?? 0 }}</div>
            <div class="csh-kpi-label">Ventes complétées</div>
        </div>
        <div class="csh-kpi">
            <i class="fas fa-sack-dollar" style="color:#8b5cf6;"></i>
            <div class="csh-kpi-num" style="color:#8b5cf6;">{{ number_format($courseStats['total_revenue'] ?? 0, 0, ',', ' ') }}</div>
            <div class="csh-kpi-label">Revenus (FCFA)</div>
        </div>
        <div class="csh-kpi">
            <i class="fas fa-hourglass-half" style="color:#fbbf24;"></i>
            <div class="csh-kpi-num" style="color:#fbbf24;">{{ $courseStats['pending_sales'] ?? 0 }}</div>
            <div class="csh-kpi-label">En attente</div>
        </div>
        <div class="csh-kpi">
            <i class="fas fa-circle-xmark" style="color:#ef4444;"></i>
            <div class="csh-kpi-num" style="color:#ef4444;">{{ $courseStats['failed_sales'] ?? 0 }}</div>
            <div class="csh-kpi-label">Échouées</div>
        </div>
        <div class="csh-kpi">
            <i class="fas fa-users" style="color:#06b6d4;"></i>
            <div class="csh-kpi-num" style="color:#06b6d4;">{{ $course->students_count }}</div>
            <div class="csh-kpi-label">Étudiants</div>
        </div>
        <div class="csh-kpi">
            <i class="fas fa-star" style="color:#f59e0b;"></i>
            <div class="csh-kpi-num" style="color:#f59e0b;">{{ number_format($course->rating, 1) }}<span style="font-size:1rem; opacity:0.6;">/5</span></div>
            <div class="csh-kpi-label">{{ $course->reviews_count }} avis</div>
        </div>
    </div>

    <div style="display:grid; grid-template-columns: 2fr 1fr; gap:1.5rem; margin-bottom:1.5rem;" class="csh-main-grid">
        <!-- Main column -->
        <div style="display:flex; flex-direction:column; gap:1.5rem;">

            <!-- Revenue chart -->
            <div class="csh-card">
                <h2 class="csh-title" style="margin-bottom:1.25rem;"><i class="fas fa-chart-line" style="color:#06b6d4; margin-right:10px;"></i>Revenus mensuels</h2>
                @if($revenueByMonth->count() > 0)
                    <canvas id="revenueChart" height="90"></canvas>
                @else
                    <p style="color:rgba(255,255,255,0.5); text-align:center; padding:2rem 0;">
                        <i class="fas fa-chart-line" style="font-size:1.5rem; display:block; margin-bottom:0.5rem; opacity:0.4;"></i>
                        Aucun revenu enregistré pour l'instant.
                    </p>
                @endif
            </div>

            <!-- Course info -->
            <div class="csh-card">
                <h2 class="csh-title" style="margin-bottom:1.25rem;"><i class="fas fa-info-circle" style="color:#06b6d4; margin-right:10px;"></i>Informations du Cours</h2>

                @if($course->cover_image)
                <div style="margin-bottom:1.5rem;">
                    @if(($course->cover_type ?? 'internal') === 'internal')
                        <img loading="lazy" src="{{ asset('storage/' . $course->cover_image) }}" alt="{{ $course->title }}" style="width:100%; max-height:320px; object-fit:cover; border-radius:1rem;">
                    @else
                        <img loading="lazy" src="{{ $course->cover_image }}" alt="{{ $course->title }}" style="width:100%; max-height:320px; object-fit:cover; border-radius:1rem;" onerror="this.style.display='none'">
                    @endif
                </div>
                @endif

                @if($course->description)
                <div style="margin-bottom:1.25rem;">
                    <p class="csh-label" style="margin-bottom:0.5rem;">Description</p>
                    <p class="csh-value" style="font-weight:400; line-height:1.7; color:rgba(255,255,255,0.85);">{{ $course->description }}</p>
                </div>
                @endif

                @if($course->content)
                <div style="margin-bottom:1.25rem;">
                    <p class="csh-label" style="margin-bottom:0.5rem;">Contenu</p>
                    <div style="line-height:1.7; color:rgba(255,255,255,0.8); max-height:280px; overflow-y:auto; padding-right:0.5rem;">
                        {!! nl2br(e($course->content)) !!}
                    </div>
                </div>
                @endif

                @if($course->what_you_learn && count($course->what_you_learn) > 0)
                <div style="margin-bottom:1.25rem;">
                    <p class="csh-label" style="margin-bottom:0.5rem;"><i class="fas fa-check-circle" style="color:#10b981; margin-right:6px;"></i>Ce que vous apprendrez</p>
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:0 1.5rem;" class="csh-learn-grid">
                        @foreach($course->what_you_learn as $item)
                        <div class="csh-learn-item"><i class="fas fa-check" style="color:#10b981; margin-top:0.3rem;"></i><span>{{ $item }}</span></div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($course->requirements && count($course->requirements) > 0)
                <div>
                    <p class="csh-label" style="margin-bottom:0.5rem;"><i class="fas fa-list-check" style="color:#06b6d4; margin-right:6px;"></i>Prérequis</p>
                    @foreach($course->requirements as $item)
                    <div class="csh-learn-item"><i class="fas fa-angle-right" style="color:#06b6d4; margin-top:0.3rem;"></i><span>{{ $item }}</span></div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Purchases -->
            <div class="csh-card">
                <h2 class="csh-title" style="margin-bottom:1.25rem;">
                    <i class="fas fa-receipt" style="color:#06b6d4; margin-right:10px;"></i>
                    Achats
                    <span style="color:rgba(255,255,255,0.5); font-weight:600; font-size:0.9rem;">({{ $course->purchases_count ?? $course->purchases->count() }} au total)</span>
                </h2>

                @if($course->purchases->count() > 0)
                <div style="overflow-x:auto;">
                    <table class="csh-table">
                        <thead>
                            <tr>
                                <th>Utilisateur</th>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($course->purchases as $purchase)
                            <tr>
                                <td>
                                    <div style="display:flex; align-items:center;">
                                        <span class="csh-avatar">{{ strtoupper(substr($purchase->user->name ?? '?', 0, 1)) }}</span>
                                        <div>
                                            <div style="font-weight:700;">{{ $purchase->user->name ?? 'N/A' }}</div>
                                            <div style="font-size:0.8rem; color:rgba(255,255,255,0.5);">{{ $purchase->user->email ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="color:#10b981; font-weight:700;">{{ number_format($purchase->amount_paid, 0, ',', ' ') }} {{ $purchase->currency }}</td>
                                <td>
                                    @if($purchase->status === 'completed')
                                        <span class="csh-badge" style="background:rgba(16,185,129,0.2); color:#10b981;">Complété</span>
                                    @elseif($purchase->status === 'pending')
                                        <span class="csh-badge" style="background:rgba(251,191,36,0.2); color:#fbbf24;">En attente</span>
                                    @else
                                        <span class="csh-badge" style="background:rgba(239,68,68,0.2); color:#ef4444;">Échoué</span>
                                    @endif
                                </td>
                                <td>{{ $purchase->purchased_at ? $purchase->purchased_at->format('d/m/Y H:i') : $purchase->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if(($course->purchases_count ?? 0) > $course->purchases->count())
                <p style="color:rgba(255,255,255,0.45); font-size:0.85rem; margin-top:0.75rem;">Affichage des {{ $course->purchases->count() }} achats les plus récents.</p>
                @endif
                @else
                <p style="color:rgba(255,255,255,0.5); text-align:center; padding:2rem 0;">
                    <i class="fas fa-inbox" style="font-size:1.5rem; display:block; margin-bottom:0.5rem; opacity:0.4;"></i>
                    Aucun achat pour l'instant.
                </p>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div style="display:flex; flex-direction:column; gap:1.5rem;">
            <!-- Price -->
            <div class="csh-card">
                <h2 class="csh-title" style="margin-bottom:1.25rem;"><i class="fas fa-tag" style="color:#06b6d4; margin-right:10px;"></i>Prix</h2>
                <div style="text-align:center; margin-bottom:1.5rem;">
                    <div style="font-size:2.6rem; font-weight:900;" class="bg-clip-text text-transparent bg-gradient-to-r from-cyan-400 to-teal-400">
                        {{ number_format($course->current_price, 0, ',', ' ') }}
                    </div>
                    <div style="color:rgba(255,255,255,0.6);">{{ $course->currency }}</div>
                    @if($course->hasDiscount())
                    <div class="csh-badge" style="background:rgba(239,68,68,0.15); color:#ef4444; margin-top:0.75rem;">
                        <span style="text-decoration:line-through; opacity:0.7;">{{ number_format($course->price, 0, ',', ' ') }} {{ $course->currency }}</span>
                        <span>-{{ $course->discount_percentage }}%</span>
                    </div>
                    @endif
                </div>
                <div style="display:grid; gap:0.75rem;">
                    <div class="csh-row">
                        <span class="csh-label">Prix de base</span>
                        <span class="csh-value">{{ number_format($course->price, 0, ',', ' ') }} {{ $course->currency }}</span>
                    </div>
                    @if($course->discount_price)
                    <div class="csh-row">
                        <span class="csh-label">Prix réduit</span>
                        <span class="csh-value" style="color:#ef4444;">{{ number_format($course->discount_price, 0, ',', ' ') }} {{ $course->currency }}</span>
                    </div>
                    @endif
                    @if($course->duration_hours)
                    <div class="csh-row">
                        <span class="csh-label">Durée</span>
                        <span class="csh-value">{{ $course->duration_hours }} h</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Meta -->
            <div class="csh-card">
                <h2 class="csh-title" style="margin-bottom:1.25rem;"><i class="fas fa-clipboard-list" style="color:#06b6d4; margin-right:10px;"></i>Métadonnées</h2>
                <div style="display:grid; gap:0.75rem;">
                    <div class="csh-row">
                        <span class="csh-label">Slug</span>
                        <span class="csh-value" style="font-family: monospace; font-size:0.85rem;">{{ $course->slug }}</span>
                    </div>
                    <div class="csh-row">
                        <span class="csh-label">Créé le</span>
                        <span class="csh-value" style="font-size:0.85rem;">{{ $course->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="csh-row">
                        <span class="csh-label">Modifié le</span>
                        <span class="csh-value" style="font-size:0.85rem;">{{ $course->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Danger zone -->
            <div class="csh-danger">
                <h2 style="font-size:1.1rem; font-weight:800; color:#ef4444; margin-bottom:0.75rem;">
                    <i class="fas fa-triangle-exclamation" style="margin-right:8px;"></i>Zone de Danger
                </h2>
                <p style="color:rgba(255,255,255,0.6); font-size:0.9rem; margin-bottom:1rem;">
                    La suppression de ce cours est définitive. Un cours avec des achats associés ne peut pas être supprimé.
                </p>
                <form method="POST" action="{{ route('admin.monetization.courses.destroy', $course->id) }}" onsubmit="return confirm('Êtes-vous ABSOLUMENT sûr de vouloir supprimer ce cours ? Cette action est irréversible !');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="csh-pill red" style="width:100%; justify-content:center;">
                        <i class="fas fa-trash"></i>Supprimer ce Cours
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    @media (min-width: 900px) {
        .csh-kpi-grid { grid-template-columns: repeat(6, 1fr) !important; }
    }
    @media (max-width: 900px) {
        .csh-main-grid { grid-template-columns: 1fr !important; }
        .csh-learn-grid { grid-template-columns: 1fr !important; }
    }
</style>

@if($revenueByMonth->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
(function() {
    const raw = @json($revenueByMonth->reverse()->values());
    const labels = raw.map(r => {
        const [y, m] = r.month.split('-');
        return new Date(y, m - 1).toLocaleDateString('fr-FR', { month: 'short', year: '2-digit' });
    });
    const data = raw.map(r => parseFloat(r.total));

    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Revenus',
                data: data,
                borderColor: '#06b6d4',
                backgroundColor: 'rgba(6, 182, 212, 0.15)',
                fill: true,
                tension: 0.35,
                pointBackgroundColor: '#06b6d4',
                pointRadius: 3,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { color: 'rgba(255,255,255,0.5)' }, grid: { color: 'rgba(255,255,255,0.05)' } },
                y: { ticks: { color: 'rgba(255,255,255,0.5)' }, grid: { color: 'rgba(255,255,255,0.05)' } }
            }
        }
    });
})();
</script>
@endif
@endsection
