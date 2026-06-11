@extends('layouts.app')

@section('title', $pageTitle . ' - NiangProgrammeur')
@section('meta_description', $metaDescription)

@push('styles')
<style>
.epreuves-page {
    min-height: 100vh;
    padding: calc(var(--spacing-navbar, 76px) + 3rem) 1.5rem 4rem;
    background: linear-gradient(180deg, #f0fdf4 0%, #ecfdf5 35%, #f8fafc 70%);
    color: #0f172a;
}
.epreuves-container { max-width: 1200px; margin: 0 auto; }
.epreuves-hero { text-align: center; margin-bottom: 2.5rem; }
.epreuves-title { font-size: clamp(1.8rem, 4vw, 2.6rem); font-weight: 800; color: #0f172a; margin-bottom: 0.75rem; }
.epreuves-subtitle { color: #475569; max-width: 640px; margin: 0 auto 1.25rem; line-height: 1.6; }
.epreuves-stats { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
.epreuves-stat {
    background: rgba(255,255,255,0.9); border: 1px solid rgba(15,23,42,0.1);
    border-radius: 999px; padding: 0.45rem 1.1rem; font-size: 0.9rem; color: #334155;
}
.epreuves-stat strong { color: #059669; }
.epreuves-exam-pills { display: flex; gap: 0.6rem; justify-content: center; flex-wrap: wrap; margin-bottom: 1.5rem; }
.epreuves-exam-pill {
    padding: 0.5rem 1.3rem; border-radius: 999px; font-weight: 700; font-size: 0.95rem;
    border: 1px solid rgba(203,213,225,0.9); background: rgba(255,255,255,0.95); color: #334155;
    text-decoration: none; transition: all 0.2s;
}
.epreuves-exam-pill:hover { border-color: #059669; color: #059669; }
.epreuves-exam-pill.active { background: linear-gradient(135deg, #059669, #047857); border-color: transparent; color: #fff; }
.epreuves-filters {
    display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 0.75rem;
    background: rgba(255,255,255,0.95); border: 1px solid rgba(226,232,240,0.9);
    border-radius: 16px; padding: 1rem; margin-bottom: 2rem; box-shadow: 0 10px 40px rgba(15,23,42,0.06);
}
.epreuves-filters select, .epreuves-filters input[type="text"] {
    width: 100%; padding: 0.6rem 0.9rem; border-radius: 10px; font-size: 0.9rem;
    border: 1px solid rgba(203,213,225,0.9); background: #fff; color: #0f172a;
}
.epreuves-filters button {
    padding: 0.6rem 1.2rem; border-radius: 10px; border: none; cursor: pointer;
    background: linear-gradient(135deg, #059669, #047857); color: #fff; font-weight: 600;
}
.epreuves-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(290px, 1fr)); gap: 1.25rem; }
.epreuve-card {
    display: flex; flex-direction: column; gap: 0.65rem; padding: 1.25rem;
    background: #fff; border: 1px solid rgba(226,232,240,0.9); border-radius: 16px;
    box-shadow: 0 10px 40px rgba(15,23,42,0.05); text-decoration: none; color: inherit;
    transition: all 0.2s;
}
.epreuve-card:hover { transform: translateY(-3px); border-color: rgba(5,150,105,0.5); box-shadow: 0 18px 50px rgba(15,23,42,0.1); }
.epreuve-card-badges { display: flex; gap: 0.4rem; flex-wrap: wrap; }
.epreuve-badge {
    font-size: 0.72rem; font-weight: 700; padding: 0.22rem 0.65rem; border-radius: 999px;
    text-transform: uppercase; letter-spacing: 0.03em;
}
.epreuve-badge--exam { background: rgba(5,150,105,0.12); color: #047857; }
.epreuve-badge--type { background: rgba(14,165,233,0.12); color: #0369a1; }
.epreuve-badge--corrige { background: rgba(245,158,11,0.15); color: #b45309; }
.epreuve-card-title { font-weight: 700; font-size: 1rem; line-height: 1.45; color: #0f172a; }
.epreuve-card-meta { display: flex; gap: 0.9rem; flex-wrap: wrap; font-size: 0.82rem; color: #64748b; margin-top: auto; }
.epreuve-card-meta i { margin-right: 0.3rem; color: #059669; }
.epreuves-empty { text-align: center; padding: 4rem 1rem; color: #64748b; }
.epreuves-pagination { margin-top: 2.5rem; display: flex; justify-content: center; }

body.dark-mode .epreuves-page { background: linear-gradient(180deg, #022c22 0%, #0f172a 45%, #0b1120 100%); color: #e2e8f0; }
body.dark-mode .epreuves-title { color: #f9fafb; }
body.dark-mode .epreuves-subtitle { color: #94a3b8; }
body.dark-mode .epreuves-stat { background: rgba(15,23,42,0.7); border-color: rgba(148,163,184,0.2); color: #cbd5e1; }
body.dark-mode .epreuves-stat strong { color: #34d399; }
body.dark-mode .epreuves-exam-pill { background: rgba(15,23,42,0.7); border-color: rgba(148,163,184,0.25); color: #cbd5e1; }
body.dark-mode .epreuves-exam-pill:hover { border-color: #34d399; color: #34d399; }
body.dark-mode .epreuves-filters { background: rgba(15,23,42,0.7); border-color: rgba(148,163,184,0.15); }
body.dark-mode .epreuves-filters select, body.dark-mode .epreuves-filters input[type="text"] {
    background: rgba(2,6,23,0.8); border-color: rgba(148,163,184,0.25); color: #e2e8f0;
}
body.dark-mode .epreuve-card { background: rgba(15,23,42,0.75); border-color: rgba(148,163,184,0.15); }
body.dark-mode .epreuve-card-title { color: #f1f5f9; }
body.dark-mode .epreuve-card-meta { color: #94a3b8; }
body.dark-mode .epreuve-badge--exam { background: rgba(52,211,153,0.15); color: #34d399; }
body.dark-mode .epreuve-badge--type { background: rgba(56,189,248,0.15); color: #38bdf8; }
body.dark-mode .epreuve-badge--corrige { background: rgba(251,191,36,0.15); color: #fbbf24; }
</style>
@endpush

@section('content')
<div class="epreuves-page">
    <div class="epreuves-container">

        <div class="epreuves-hero">
            <h1 class="epreuves-title">{{ $heading }}</h1>
            <p class="epreuves-subtitle">
                Épreuves, examens blancs et corrigés des examens du Sénégal — du CI à la Terminale.
                Téléchargement gratuit au format PDF.
            </p>
            <div class="epreuves-stats">
                <span class="epreuves-stat"><strong>{{ number_format($stats['total'], 0, ',', ' ') }}</strong> documents</span>
                <span class="epreuves-stat"><strong>{{ number_format($stats['downloads'], 0, ',', ' ') }}</strong> téléchargements</span>
            </div>
        </div>

        <div class="epreuves-exam-pills">
            <a href="{{ route('epreuves.index') }}" class="epreuves-exam-pill {{ !$filters['exam'] && !$filters['level'] ? 'active' : '' }}">Tout</a>
            @foreach(\App\Models\Epreuve::EXAMS as $examKey => $examLabel)
                <a href="{{ route('epreuves.exam', $examKey) }}" class="epreuves-exam-pill {{ $filters['exam'] === $examKey ? 'active' : '' }}">{{ $examLabel }}</a>
            @endforeach
        </div>

        <form class="epreuves-filters" method="GET" action="{{ url()->current() }}">
            @unless($fixedLevel)
            <select name="level" aria-label="Classe">
                <option value="">Toutes les classes</option>
                @foreach(\App\Models\Epreuve::LEVELS as $group => $levels)
                    <optgroup label="{{ ['primaire' => 'Primaire', 'college' => 'Collège', 'lycee' => 'Lycée'][$group] }}">
                        @foreach($levels as $levelKey => $levelLabel)
                            <option value="{{ $levelKey }}" @selected($filters['level'] === $levelKey)>{{ $levelLabel }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
            @endunless
            @unless($fixedMatiere)
            <select name="matiere" aria-label="Matière">
                <option value="">Toutes les matières</option>
                @foreach($matieres as $matiere)
                    <option value="{{ $matiere->slug }}" @selected($filters['matiereSlug'] === $matiere->slug)>{{ $matiere->name }}</option>
                @endforeach
            </select>
            @endunless
            <select name="year" aria-label="Année">
                <option value="">Toutes les années</option>
                @foreach($years as $y)
                    <option value="{{ $y }}" @selected($filters['year'] === $y)>{{ $y }}</option>
                @endforeach
            </select>
            <select name="type" aria-label="Type de document">
                <option value="">Tous les types</option>
                @foreach(\App\Models\Epreuve::TYPES as $typeKey => $typeLabel)
                    <option value="{{ $typeKey }}" @selected($filters['type'] === $typeKey)>{{ $typeLabel }}</option>
                @endforeach
            </select>
            <input type="text" name="q" value="{{ $filters['search'] }}" placeholder="Rechercher un sujet...">
            <button type="submit"><i class="fas fa-filter"></i> Filtrer</button>
        </form>

        @if($epreuves->isEmpty())
            <div class="epreuves-empty">
                <p style="font-size:2.5rem; margin-bottom:1rem;">📚</p>
                <p>Aucun document ne correspond à ces critères pour le moment.</p>
            </div>
        @else
            <div class="epreuves-grid">
                @foreach($epreuves as $epreuve)
                    <a href="{{ route('epreuves.show', $epreuve->slug) }}" class="epreuve-card">
                        <div class="epreuve-card-badges">
                            @if($epreuve->exam)
                                <span class="epreuve-badge epreuve-badge--exam">{{ $epreuve->exam_label }}</span>
                            @elseif($epreuve->level)
                                <span class="epreuve-badge epreuve-badge--exam">{{ $epreuve->level_label }}</span>
                            @endif
                            <span class="epreuve-badge epreuve-badge--type">{{ $epreuve->type_label }}</span>
                            @if($epreuve->corrige_file_path || $epreuve->type === 'corrige' || $epreuve->type === 'epreuve_corrige')
                                <span class="epreuve-badge epreuve-badge--corrige">Corrigé inclus</span>
                            @endif
                        </div>
                        <div class="epreuve-card-title">{{ $epreuve->title }}</div>
                        <div class="epreuve-card-meta">
                            @if($epreuve->matiere)<span><i class="fas fa-book"></i>{{ $epreuve->matiere->name }}</span>@endif
                            @if($epreuve->serie)<span><i class="fas fa-layer-group"></i>Série {{ $epreuve->serie }}</span>@endif
                            @if($epreuve->year)<span><i class="fas fa-calendar"></i>{{ $epreuve->year }}</span>@endif
                            <span><i class="fas fa-download"></i>{{ number_format($epreuve->downloads_count, 0, ',', ' ') }}</span>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="epreuves-pagination">
                {{ $epreuves->links() }}
            </div>
        @endif

    </div>
</div>
@endsection
