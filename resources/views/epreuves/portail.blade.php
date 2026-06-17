@extends('layouts.app')

@section('title', 'Portail ' . $examLabel . ' Sénégal — Toutes les épreuves PDF - NiangProgrammeur')
@section('meta_description', 'Toutes les épreuves ' . $examLabel . ' du Sénégal classées par année et par matière. Téléchargez chaque PDF gratuitement ou téléchargez le pack complet d\'une année en un clic.')

@section('styles')
<style>
/* ── Layout ─────────────────────────────────────────────────────────── */
.portail-page {
    min-height: 100vh;
    padding: calc(var(--spacing-navbar, 76px) + 2.5rem) 1.25rem 5rem;
    background: linear-gradient(180deg, #f0fdf4 0%, #ecfdf5 35%, #f8fafc 70%);
    color: #0f172a;
}
.portail-container { max-width: 1200px; margin: 0 auto; }

/* ── Hero ────────────────────────────────────────────────────────────── */
.portail-hero { text-align: center; margin-bottom: 2rem; }
.portail-exam-badge {
    display: inline-block; font-size: 0.8rem; font-weight: 800; letter-spacing: 0.12em;
    text-transform: uppercase; padding: 0.3rem 1rem; border-radius: 999px;
    background: linear-gradient(135deg, #059669, #047857); color: #fff;
    margin-bottom: 1rem;
}
.portail-hero h1 { font-size: clamp(1.7rem, 3.5vw, 2.4rem); font-weight: 800; color: #0f172a; margin-bottom: 0.6rem; }
.portail-hero p { color: #475569; max-width: 580px; margin: 0 auto 1.25rem; }
.portail-stats { display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap; }
.portail-stat {
    background: rgba(255,255,255,0.9); border: 1px solid rgba(15,23,42,0.1);
    border-radius: 999px; padding: 0.4rem 1rem; font-size: 0.88rem; color: #334155;
}
.portail-stat strong { color: #059669; }

/* ── Switcher examen ─────────────────────────────────────────────────── */
.portail-switcher {
    display: flex; gap: 0.5rem; justify-content: center; flex-wrap: wrap;
    margin-bottom: 2rem;
}
.portail-switcher a {
    padding: 0.45rem 1.2rem; border-radius: 999px; font-weight: 700; font-size: 0.9rem;
    border: 1.5px solid rgba(203,213,225,0.9); background: rgba(255,255,255,0.95);
    color: #334155; text-decoration: none; transition: all 0.18s;
}
.portail-switcher a:hover { border-color: #059669; color: #059669; }
.portail-switcher a.active { background: linear-gradient(135deg,#059669,#047857); border-color: transparent; color: #fff; }
.portail-switcher a.portail-list-link {
    border-style: dashed; color: #64748b; font-weight: 500;
}
.portail-switcher a.portail-list-link:hover { border-color: #64748b; color: #334155; }

/* ── Légende ─────────────────────────────────────────────────────────── */
.portail-legend {
    display: flex; gap: 1.2rem; flex-wrap: wrap; justify-content: flex-end;
    font-size: 0.8rem; color: #64748b; margin-bottom: 0.75rem;
}
.portail-legend span { display: flex; align-items: center; gap: 0.35rem; }

/* ── Table wrapper ───────────────────────────────────────────────────── */
.portail-table-wrap {
    background: rgba(255,255,255,0.95); border: 1px solid rgba(226,232,240,0.9);
    border-radius: 18px; box-shadow: 0 10px 40px rgba(15,23,42,0.06);
    overflow-x: auto; -webkit-overflow-scrolling: touch;
}
.portail-table {
    width: 100%; border-collapse: collapse; min-width: 560px;
}
.portail-table thead th {
    padding: 0.9rem 0.7rem; font-size: 0.78rem; font-weight: 700; letter-spacing: 0.04em;
    text-transform: uppercase; color: #475569; text-align: center;
    border-bottom: 2px solid rgba(226,232,240,0.9); white-space: nowrap;
    position: sticky; top: 0; background: rgba(255,255,255,0.98); z-index: 2;
}
.portail-table thead th:first-child { text-align: left; padding-left: 1.2rem; min-width: 80px; }
.portail-table thead th:last-child { min-width: 110px; }

.portail-table tbody tr { border-bottom: 1px solid rgba(226,232,240,0.6); transition: background 0.15s; }
.portail-table tbody tr:hover { background: rgba(5,150,105,0.04); }
.portail-table tbody tr:last-child { border-bottom: none; }

/* Year cell */
.portail-year-cell {
    padding: 0.75rem 0.75rem 0.75rem 1.2rem; font-weight: 800; font-size: 1.05rem;
    color: #0f172a; text-align: left; white-space: nowrap;
}
.portail-year-new { color: #059669; }

/* Doc cells */
.portail-cell { text-align: center; padding: 0.5rem 0.4rem; position: relative; }

.portail-cell-available {
    display: inline-flex; align-items: center; justify-content: center;
    width: 38px; height: 38px; border-radius: 10px;
    background: rgba(5,150,105,0.1); color: #059669;
    text-decoration: none; font-size: 1.15rem; transition: all 0.18s;
    cursor: pointer; border: 1.5px solid rgba(5,150,105,0.2);
}
.portail-cell-available:hover {
    background: rgba(5,150,105,0.2); transform: scale(1.12);
    border-color: rgba(5,150,105,0.4);
}

.portail-cell-multi-btn {
    position: relative; display: inline-flex; align-items: center; justify-content: center;
    min-width: 44px; height: 38px; border-radius: 10px; border: 1.5px solid rgba(5,150,105,0.3);
    background: rgba(5,150,105,0.08); color: #047857; font-size: 0.78rem; font-weight: 700;
    cursor: pointer; gap: 0.2rem; padding: 0 0.5rem; transition: all 0.18s;
}
.portail-cell-multi-btn:hover { background: rgba(5,150,105,0.15); }
.portail-cell-multi-btn i { font-size: 0.85rem; }

.portail-dropdown {
    display: none; position: absolute; top: calc(100% + 4px); left: 50%; transform: translateX(-50%);
    background: #fff; border: 1px solid rgba(226,232,240,0.9); border-radius: 12px;
    box-shadow: 0 16px 40px rgba(15,23,42,0.14); z-index: 99; min-width: 220px;
    padding: 0.5rem; text-align: left;
}
.portail-dropdown.open { display: block; }
.portail-dropdown a {
    display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 0.7rem;
    border-radius: 8px; text-decoration: none; font-size: 0.83rem; color: #334155;
    transition: background 0.12s;
}
.portail-dropdown a:hover { background: rgba(5,150,105,0.08); color: #047857; }
.portail-dropdown a i { color: #059669; width: 16px; text-align: center; }

.portail-cell-missing {
    display: inline-flex; align-items: center; justify-content: center;
    width: 38px; height: 38px; border-radius: 10px;
    border: 1.5px dashed rgba(203,213,225,0.8); color: #cbd5e1;
    font-size: 0.7rem; font-weight: 600;
}

/* Pack cell */
.portail-pack-cell { text-align: center; padding: 0.5rem 0.75rem; }
.portail-pack-btn {
    display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.4rem 0.85rem;
    border-radius: 8px; font-size: 0.8rem; font-weight: 700;
    background: linear-gradient(135deg, #0ea5e9, #0284c7); color: #fff;
    text-decoration: none; transition: all 0.18s; white-space: nowrap;
    border: none; cursor: pointer;
}
.portail-pack-btn:hover { filter: brightness(1.1); transform: translateY(-1px); color: #fff; }
.portail-pack-btn.disabled {
    background: rgba(203,213,225,0.5); color: #94a3b8; cursor: not-allowed;
    pointer-events: none; filter: none; transform: none;
}

/* ── Empty state ─────────────────────────────────────────────────────── */
.portail-empty {
    text-align: center; padding: 4rem 1rem; color: #64748b;
}
.portail-empty i { font-size: 2.5rem; color: #e2e8f0; margin-bottom: 1rem; }

/* ── Dark mode ───────────────────────────────────────────────────────── */
body.dark-mode .portail-page { background: linear-gradient(180deg, #022c22 0%, #0f172a 45%, #0b1120 100%); color: #e2e8f0; }
body.dark-mode .portail-hero h1 { color: #f9fafb; }
body.dark-mode .portail-hero p { color: #94a3b8; }
body.dark-mode .portail-stat { background: rgba(15,23,42,0.7); border-color: rgba(148,163,184,0.2); color: #cbd5e1; }
body.dark-mode .portail-stat strong { color: #34d399; }
body.dark-mode .portail-switcher a { background: rgba(15,23,42,0.7); border-color: rgba(148,163,184,0.25); color: #cbd5e1; }
body.dark-mode .portail-switcher a:hover { border-color: #34d399; color: #34d399; }
body.dark-mode .portail-table-wrap { background: rgba(15,23,42,0.8); border-color: rgba(148,163,184,0.15); }
body.dark-mode .portail-table thead th { background: rgba(15,23,42,0.95); color: #94a3b8; border-color: rgba(148,163,184,0.15); }
body.dark-mode .portail-table tbody tr { border-color: rgba(148,163,184,0.1); }
body.dark-mode .portail-table tbody tr:hover { background: rgba(52,211,153,0.04); }
body.dark-mode .portail-year-cell { color: #f1f5f9; }
body.dark-mode .portail-year-new { color: #34d399; }
body.dark-mode .portail-cell-available { background: rgba(52,211,153,0.12); color: #34d399; border-color: rgba(52,211,153,0.25); }
body.dark-mode .portail-cell-multi-btn { background: rgba(52,211,153,0.1); color: #34d399; border-color: rgba(52,211,153,0.25); }
body.dark-mode .portail-cell-missing { border-color: rgba(148,163,184,0.2); color: #334155; }
body.dark-mode .portail-dropdown { background: #1e293b; border-color: rgba(148,163,184,0.2); }
body.dark-mode .portail-dropdown a { color: #cbd5e1; }
body.dark-mode .portail-dropdown a:hover { background: rgba(52,211,153,0.1); color: #34d399; }
body.dark-mode .portail-legend { color: #94a3b8; }
</style>
@endsection

@section('content')
<div class="portail-page">
<div class="portail-container">

    {{-- Hero --}}
    <div class="portail-hero">
        <div class="portail-exam-badge">{{ $examLabel }}</div>
        <h1>Portail {{ $examLabel }} Sénégal — Toutes les épreuves</h1>
        <p>
            Retrouve toutes les épreuves du {{ $examLabel }} classées par année et par matière.
            Télécharge chaque PDF gratuitement ou prends le pack complet d'une année en un clic.
        </p>
        <div class="portail-stats">
            <span class="portail-stat"><strong>{{ $stats['total'] }}</strong> épreuves</span>
            <span class="portail-stat"><strong>{{ $stats['years'] }}</strong> années</span>
            <span class="portail-stat"><strong>{{ number_format($stats['downloads'], 0, ',', ' ') }}</strong> téléchargements</span>
        </div>
    </div>

    {{-- Switcher --}}
    <div class="portail-switcher">
        @foreach(\App\Models\Epreuve::EXAMS as $key => $label)
            @if(in_array($key, ['cfee','bfem','bac','bts','cap']))
                <a href="{{ route('portail.exam', $key) }}" class="{{ $key === $exam && !($isConcours ?? false) ? 'active' : '' }}">{{ $label }}</a>
            @endif
        @endforeach
        <a href="{{ route('portail.concours') }}" class="{{ ($isConcours ?? false) ? 'active' : '' }}"
           style="{{ ($isConcours ?? false) ? '' : '' }}">
            <i class="fas fa-award" style="font-size:0.8em;margin-right:0.3rem"></i>Concours
        </a>
        <a href="{{ route('epreuves.index') }}" class="portail-list-link">
            <i class="fas fa-list" style="font-size:0.8em"></i> Tous les docs
        </a>
    </div>

    {{-- Fil d'ariane concours --}}
    @if($isConcours ?? false)
    <div style="text-align:center;margin-bottom:1.25rem;">
        <a href="{{ route('portail.concours') }}" style="font-size:0.85rem;color:#7c3aed;text-decoration:none;">
            <i class="fas fa-arrow-left" style="font-size:0.8em"></i> Toutes les institutions
        </a>
        <span style="color:#94a3b8;margin:0 0.5rem">›</span>
        <span style="font-size:0.85rem;color:#334155;font-weight:600">{{ $examLabel }}</span>
    </div>
    @endif

    @if($years->isEmpty())
        <div class="portail-empty">
            <i class="fas fa-folder-open"></i>
            <p>Aucune épreuve disponible pour le {{ $examLabel }} pour l'instant.</p>
            <a href="{{ route('epreuves.index') }}" style="color:#059669">← Parcourir tous les documents</a>
        </div>
    @else
        {{-- Légende --}}
        <div class="portail-legend">
            <span><span class="portail-cell-available" style="pointer-events:none;width:22px;height:22px;font-size:0.85rem">✓</span> Disponible</span>
            <span><span class="portail-cell-missing" style="width:22px;height:22px;font-size:0.65rem">—</span> Non disponible</span>
            <span><i class="fas fa-file-zipper" style="color:#0ea5e9"></i> Pack ZIP = toutes les épreuves de l'année</span>
        </div>

        {{-- Grille --}}
        <div class="portail-table-wrap">
            <table class="portail-table">
                <thead>
                    <tr>
                        <th>Année</th>
                        @foreach($matieres as $matiere)
                            <th title="{{ $matiere->name }}">
                                @if($matiere->icon)
                                    <i class="fas {{ $matiere->icon }}" style="margin-right:0.3rem;font-size:0.9em;color:#059669"></i>
                                @endif
                                {{ Str::limit($matiere->name, 14) }}
                            </th>
                        @endforeach
                        <th>📦 Pack</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($years as $year)
                        @php
                            $yearHasDocs = isset($grid[$year]) && count($grid[$year]) > 0;
                            $isRecent = $year >= 2015;
                        @endphp
                        <tr>
                            {{-- Année --}}
                            <td class="portail-year-cell {{ $isRecent ? 'portail-year-new' : '' }}">
                                {{ $year }}
                            </td>

                            {{-- Cellule par matière --}}
                            @foreach($matieres as $matiere)
                                <td class="portail-cell">
                                    @php $items = $grid[$year][$matiere->id] ?? []; @endphp

                                    @if(count($items) === 1)
                                        @php $e = $items[0]; @endphp
                                        <a href="{{ route('epreuves.download', $e->id) }}"
                                           class="portail-cell-available"
                                           title="{{ $e->title }}"
                                           aria-label="Télécharger {{ $e->title }}">
                                            <i class="fas fa-download"></i>
                                        </a>

                                    @elseif(count($items) > 1)
                                        <div class="portail-cell-multi-btn" onclick="toggleDrop(this)" title="{{ count($items) }} épreuves disponibles">
                                            <i class="fas fa-file-pdf"></i> {{ count($items) }}
                                            <div class="portail-dropdown">
                                                @foreach($items as $e)
                                                    <a href="{{ route('epreuves.download', $e->id) }}">
                                                        <i class="fas fa-download"></i>
                                                        {{ $e->type_label }} {{ $year }}
                                                        @if($e->description)
                                                            <small style="color:#94a3b8;display:block;font-size:0.75rem;margin-top:1px;">{{ Str::limit($e->description, 40) }}</small>
                                                        @endif
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>

                                    @else
                                        <span class="portail-cell-missing" title="Non disponible">—</span>
                                    @endif
                                </td>
                            @endforeach

                            {{-- Pack ZIP --}}
                            <td class="portail-pack-cell">
                                @if($yearHasDocs)
                                    @php
                                        $packUrl = ($isConcours ?? false)
                                            ? route('portail.concours.pack', [$institution, $year])
                                            : route('epreuves.pack', [$exam, $year]);
                                    @endphp
                                    <a href="{{ $packUrl }}"
                                       class="portail-pack-btn"
                                       title="Télécharger toutes les épreuves {{ $examLabel }} {{ $year }} en ZIP">
                                        <i class="fas fa-file-zipper"></i> {{ $year }}
                                    </a>
                                @else
                                    <span class="portail-pack-btn disabled">
                                        <i class="fas fa-file-zipper"></i> —
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <p style="text-align:center;margin-top:1.5rem;font-size:0.85rem;color:#94a3b8;">
            <i class="fas fa-info-circle"></i>
            Les années en <span style="color:#059669;font-weight:700">vert</span> correspondent au nouveau programme (2015+).
            Toutes les épreuves sont gratuites.
        </p>
    @endif

</div>
</div>
@endsection

@section('scripts')
<script>
// Ferme tous les dropdowns ouverts sauf celui cliqué
function toggleDrop(btn) {
    const drop = btn.querySelector('.portail-dropdown');
    const wasOpen = drop.classList.contains('open');
    document.querySelectorAll('.portail-dropdown.open').forEach(d => d.classList.remove('open'));
    if (!wasOpen) drop.classList.add('open');
}
document.addEventListener('click', function(e) {
    if (!e.target.closest('.portail-cell-multi-btn')) {
        document.querySelectorAll('.portail-dropdown.open').forEach(d => d.classList.remove('open'));
    }
});
</script>
@endsection
