@extends('layouts.app')

@section('title', 'Portail Concours Sénégal — CREM, ENA, Police, Gendarmerie PDF - NiangProgrammeur')
@section('meta_description', 'Épreuves et corrigés de tous les concours au Sénégal : CREM, ENA, ENDSS, CFJ, Police, Gendarmerie, Douanes, Eaux et Forêts. Téléchargement PDF gratuit.')

@section('styles')
<style>
/* ── Layout ─────────────────────────────────────────────────────────── */
.concours-page {
    min-height: 100vh;
    padding: calc(var(--spacing-navbar, 76px) + 2.5rem) 1.25rem 5rem;
    background: linear-gradient(180deg, #f5f3ff 0%, #ede9fe 30%, #f8fafc 70%);
    color: #0f172a;
}
.concours-container { max-width: 1100px; margin: 0 auto; }

/* ── Hero ────────────────────────────────────────────────────────────── */
.concours-hero { text-align: center; margin-bottom: 2.5rem; }
.concours-badge {
    display: inline-block; font-size: 0.8rem; font-weight: 800; letter-spacing: 0.12em;
    text-transform: uppercase; padding: 0.3rem 1rem; border-radius: 999px;
    background: linear-gradient(135deg, #7c3aed, #6d28d9); color: #fff; margin-bottom: 1rem;
}
.concours-hero h1 { font-size: clamp(1.7rem, 3.5vw, 2.4rem); font-weight: 800; color: #0f172a; margin-bottom: 0.6rem; }
.concours-hero p { color: #475569; max-width: 600px; margin: 0 auto 1.25rem; }
.concours-stats { display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap; }
.concours-stat {
    background: rgba(255,255,255,0.9); border: 1px solid rgba(124,58,237,0.15);
    border-radius: 999px; padding: 0.4rem 1rem; font-size: 0.88rem; color: #334155;
}
.concours-stat strong { color: #7c3aed; }

/* ── Liens rapides vers les autres portails ──────────────────────────── */
.concours-switcher {
    display: flex; gap: 0.5rem; justify-content: center; flex-wrap: wrap; margin-bottom: 2rem;
}
.concours-switcher a {
    padding: 0.4rem 1.1rem; border-radius: 999px; font-weight: 700; font-size: 0.88rem;
    border: 1.5px solid rgba(203,213,225,0.9); background: rgba(255,255,255,0.95);
    color: #334155; text-decoration: none; transition: all 0.18s;
}
.concours-switcher a:hover { border-color: #7c3aed; color: #7c3aed; }
.concours-switcher a.active { background: linear-gradient(135deg,#7c3aed,#6d28d9); border-color: transparent; color: #fff; }

/* ── Grille institutions ─────────────────────────────────────────────── */
.concours-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 1rem;
    margin-bottom: 2.5rem;
}
.concours-card {
    display: flex; flex-direction: column; gap: 0.6rem; padding: 1.3rem 1.25rem;
    background: #fff; border: 1.5px solid rgba(226,232,240,0.9); border-radius: 16px;
    box-shadow: 0 8px 30px rgba(15,23,42,0.05); text-decoration: none; color: inherit;
    transition: all 0.2s;
}
.concours-card:hover {
    transform: translateY(-3px); border-color: rgba(124,58,237,0.4);
    box-shadow: 0 16px 45px rgba(124,58,237,0.1);
}
.concours-card-header { display: flex; align-items: center; gap: 0.75rem; }
.concours-card-icon {
    width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center;
    justify-content: center; font-size: 1.1rem; flex-shrink: 0;
    background: linear-gradient(135deg, rgba(124,58,237,0.12), rgba(109,40,217,0.08));
    color: #7c3aed;
}
.concours-card-name { font-weight: 800; font-size: 1rem; color: #0f172a; line-height: 1.3; }
.concours-card-meta { display: flex; gap: 0.75rem; flex-wrap: wrap; }
.concours-card-count {
    font-size: 0.82rem; color: #64748b;
    display: flex; align-items: center; gap: 0.3rem;
}
.concours-card-count i { color: #7c3aed; }
.concours-card-count.has-docs { color: #047857; font-weight: 600; }
.concours-card-count.has-docs i { color: #059669; }
.concours-card-year { font-size: 0.78rem; color: #94a3b8; margin-top: auto; }
.concours-card-cta {
    display: inline-flex; align-items: center; gap: 0.4rem; margin-top: 0.25rem;
    font-size: 0.82rem; font-weight: 700; color: #7c3aed;
}
.concours-card:hover .concours-card-cta { color: #6d28d9; }

/* Empty card (no docs yet) */
.concours-card.empty { opacity: 0.55; }
.concours-card.empty:hover { transform: none; border-color: rgba(226,232,240,0.9); box-shadow: 0 8px 30px rgba(15,23,42,0.05); }
.concours-card.empty .concours-card-name { color: #64748b; }

/* ── Section info ────────────────────────────────────────────────────── */
.concours-info {
    background: rgba(255,255,255,0.8); border: 1px solid rgba(124,58,237,0.15);
    border-radius: 14px; padding: 1.25rem 1.5rem; font-size: 0.9rem; color: #475569;
    display: flex; gap: 1rem; align-items: flex-start;
}
.concours-info i { color: #7c3aed; font-size: 1.1rem; margin-top: 0.1rem; flex-shrink: 0; }

/* ── Dark mode ───────────────────────────────────────────────────────── */
body.dark-mode .concours-page { background: linear-gradient(180deg, #1e1433 0%, #0f172a 45%, #0b1120 100%); color: #e2e8f0; }
body.dark-mode .concours-hero h1 { color: #f9fafb; }
body.dark-mode .concours-hero p { color: #94a3b8; }
body.dark-mode .concours-stat { background: rgba(15,23,42,0.7); border-color: rgba(167,139,250,0.2); color: #cbd5e1; }
body.dark-mode .concours-stat strong { color: #a78bfa; }
body.dark-mode .concours-switcher a { background: rgba(15,23,42,0.7); border-color: rgba(148,163,184,0.25); color: #cbd5e1; }
body.dark-mode .concours-switcher a:hover { border-color: #a78bfa; color: #a78bfa; }
body.dark-mode .concours-card { background: rgba(15,23,42,0.75); border-color: rgba(148,163,184,0.15); }
body.dark-mode .concours-card:hover { border-color: rgba(167,139,250,0.4); }
body.dark-mode .concours-card-name { color: #f1f5f9; }
body.dark-mode .concours-card-count { color: #94a3b8; }
body.dark-mode .concours-card-icon { background: rgba(167,139,250,0.15); color: #a78bfa; }
body.dark-mode .concours-card-cta { color: #a78bfa; }
body.dark-mode .concours-info { background: rgba(15,23,42,0.6); border-color: rgba(167,139,250,0.2); color: #94a3b8; }
body.dark-mode .concours-info i { color: #a78bfa; }
</style>
@endsection

@section('content')
<div class="concours-page">
<div class="concours-container">

    {{-- Hero --}}
    <div class="concours-hero">
        <div class="concours-badge"><i class="fas fa-award" style="margin-right:0.4rem"></i>Concours</div>
        <h1>Portail Concours — Épreuves du Sénégal</h1>
        <p>
            Tous les concours administratifs et académiques du Sénégal en un seul endroit :
            CREM, ENA, Police, Gendarmerie, Douanes, et bien d'autres.
            Épreuves PDF gratuites classées par institution et par année.
        </p>
        <div class="concours-stats">
            <span class="concours-stat"><strong>{{ $stats['total'] > 0 ? $stats['total'] : '—' }}</strong> épreuves</span>
            <span class="concours-stat"><strong>{{ count(\App\Models\Epreuve::LEVELS['concours']) }}</strong> institutions</span>
            @if($stats['institutions'] > 0)
                <span class="concours-stat"><strong>{{ $stats['institutions'] }}</strong> avec des docs</span>
            @endif
        </div>
    </div>

    {{-- Switcher vers les autres portails --}}
    <div class="concours-switcher">
        @foreach(\App\Models\Epreuve::EXAMS as $key => $label)
            @if(in_array($key, ['cfee','bfem','bac','bts','cap']))
                <a href="{{ route('portail.exam', $key) }}">{{ $label }}</a>
            @endif
        @endforeach
        <a href="{{ route('portail.concours') }}" class="active">
            <i class="fas fa-award" style="font-size:0.8em;margin-right:0.3rem"></i>Concours
        </a>
        <a href="{{ route('epreuves.index') }}" style="border-style:dashed;color:#64748b;font-weight:500">
            <i class="fas fa-list" style="font-size:0.8em"></i> Tous les docs
        </a>
    </div>

    {{-- Grille des institutions --}}
    <div class="concours-grid">
        @foreach($cards as $card)
            @php
                $hasDocs = $card['total'] > 0;
                $icons = [
                    'crem'        => 'fa-chalkboard-teacher',
                    'ena'         => 'fa-landmark',
                    'endss'       => 'fa-heartbeat',
                    'cfj'         => 'fa-newspaper',
                    'police'      => 'fa-shield-halved',
                    'esogn'       => 'fa-shield',
                    'douanes'     => 'fa-boxes-stacked',
                    'eaux-forets' => 'fa-tree',
                    'ems'         => 'fa-stethoscope',
                    'ept'         => 'fa-screwdriver-wrench',
                    'esp'         => 'fa-graduation-cap',
                    'cesti'       => 'fa-satellite-dish',
                    'ensae'       => 'fa-chart-bar',
                    'isfar'       => 'fa-scale-balanced',
                    'eamac'       => 'fa-plane',
                ];
                $icon = $icons[$card['slug']] ?? 'fa-building-columns';
            @endphp
            <a href="{{ route('portail.concours.institution', $card['slug']) }}"
               class="concours-card {{ !$hasDocs ? 'empty' : '' }}">
                <div class="concours-card-header">
                    <div class="concours-card-icon"><i class="fas {{ $icon }}"></i></div>
                    <div class="concours-card-name">{{ $card['label'] }}</div>
                </div>
                <div class="concours-card-meta">
                    <span class="concours-card-count {{ $hasDocs ? 'has-docs' : '' }}">
                        <i class="fas fa-file-pdf"></i>
                        {{ $hasDocs ? $card['total'] . ' épreuve' . ($card['total'] > 1 ? 's' : '') : 'Aucun doc encore' }}
                    </span>
                    @if($card['last_year'])
                        <span class="concours-card-count">
                            <i class="fas fa-calendar"></i> jusqu'à {{ $card['last_year'] }}
                        </span>
                    @endif
                </div>
                @if($hasDocs)
                    <span class="concours-card-cta">
                        Voir les épreuves <i class="fas fa-arrow-right" style="font-size:0.8em"></i>
                    </span>
                @else
                    <span style="font-size:0.78rem;color:#94a3b8">Bientôt disponible</span>
                @endif
            </a>
        @endforeach
    </div>

    {{-- Info --}}
    <div class="concours-info">
        <i class="fas fa-lightbulb"></i>
        <div>
            <strong>Comment contribuer ?</strong> Si tu as des épreuves de concours en PDF que tu ne trouves pas ici,
            tu peux nous les envoyer via la page
            <a href="{{ route('contact') }}" style="color:#7c3aed">Contact</a>.
            On les ajoute gratuitement pour toute la communauté.
        </div>
    </div>

</div>
</div>
@endsection
