@extends('layouts.app')

@section('title', $epreuve->title . ' - PDF gratuit - NiangProgrammeur')
@section('meta_description', ($epreuve->description ? Str::limit(strip_tags($epreuve->description), 150) : $epreuve->title . ' à télécharger gratuitement en PDF.' . ($epreuve->matiere ? ' ' . $epreuve->matiere->name . '.' : '') . ($epreuve->year ? ' Session ' . $epreuve->year . '.' : '') . ' Examens du Sénégal.'))

@push('styles')
<style>
.epreuve-show-page {
    min-height: 100vh;
    padding: calc(var(--spacing-navbar, 76px) + 2.5rem) 1.5rem 4rem;
    background: linear-gradient(180deg, #f0fdf4 0%, #f8fafc 50%);
    color: #0f172a;
}
.epreuve-show-container { max-width: 1000px; margin: 0 auto; }
.epreuve-show-back { display: inline-flex; align-items: center; gap: 0.5rem; color: #059669; text-decoration: none; font-weight: 600; font-size: 0.9rem; margin-bottom: 1.5rem; }
.epreuve-show-back:hover { text-decoration: underline; }
.epreuve-show-header {
    background: #fff; border: 1px solid rgba(226,232,240,0.9); border-radius: 18px;
    padding: 1.75rem; margin-bottom: 1.5rem; box-shadow: 0 10px 40px rgba(15,23,42,0.06);
}
.epreuve-show-badges { display: flex; gap: 0.45rem; flex-wrap: wrap; margin-bottom: 0.9rem; }
.epreuve-show-badge { font-size: 0.75rem; font-weight: 700; padding: 0.25rem 0.7rem; border-radius: 999px; text-transform: uppercase; }
.epreuve-show-badge--exam { background: rgba(5,150,105,0.12); color: #047857; }
.epreuve-show-badge--type { background: rgba(14,165,233,0.12); color: #0369a1; }
.epreuve-show-title { font-size: clamp(1.4rem, 3vw, 2rem); font-weight: 800; line-height: 1.3; margin-bottom: 0.9rem; color: #0f172a; }
.epreuve-show-meta { display: flex; gap: 1.2rem; flex-wrap: wrap; color: #64748b; font-size: 0.9rem; margin-bottom: 1.25rem; }
.epreuve-show-meta i { margin-right: 0.35rem; color: #059669; }
.epreuve-show-actions { display: flex; gap: 0.75rem; flex-wrap: wrap; }
.epreuve-show-btn {
    display: inline-flex; align-items: center; gap: 0.55rem; padding: 0.75rem 1.5rem;
    border-radius: 12px; font-weight: 700; text-decoration: none; font-size: 0.95rem; transition: all 0.2s;
}
.epreuve-show-btn--primary { background: linear-gradient(135deg, #059669, #047857); color: #fff; }
.epreuve-show-btn--primary:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(5,150,105,0.35); }
.epreuve-show-btn--secondary { background: rgba(245,158,11,0.12); color: #b45309; border: 1px solid rgba(245,158,11,0.4); }
.epreuve-show-btn--secondary:hover { background: rgba(245,158,11,0.2); }
.epreuve-show-description { color: #475569; line-height: 1.7; margin-top: 1.25rem; }
.epreuve-show-viewer {
    background: #fff; border: 1px solid rgba(226,232,240,0.9); border-radius: 18px;
    overflow: hidden; margin-bottom: 2.5rem; box-shadow: 0 10px 40px rgba(15,23,42,0.06);
}
.epreuve-show-viewer iframe { display: block; width: 100%; height: 75vh; border: none; }
.epreuve-related-title { font-size: 1.3rem; font-weight: 800; margin-bottom: 1.25rem; color: #0f172a; }
.epreuve-related-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1rem; }
.epreuve-related-card {
    display: block; padding: 1.1rem; background: #fff; border: 1px solid rgba(226,232,240,0.9);
    border-radius: 14px; text-decoration: none; color: inherit; transition: all 0.2s;
}
.epreuve-related-card:hover { border-color: rgba(5,150,105,0.5); transform: translateY(-2px); }
.epreuve-related-card-title { font-weight: 700; font-size: 0.92rem; line-height: 1.45; color: #0f172a; margin-bottom: 0.5rem; }
.epreuve-related-card-meta { font-size: 0.8rem; color: #64748b; }

body.dark-mode .epreuve-show-page { background: linear-gradient(180deg, #022c22 0%, #0b1120 50%); color: #e2e8f0; }
body.dark-mode .epreuve-show-header, body.dark-mode .epreuve-show-viewer, body.dark-mode .epreuve-related-card { background: rgba(15,23,42,0.75); border-color: rgba(148,163,184,0.15); }
body.dark-mode .epreuve-show-title, body.dark-mode .epreuve-related-title, body.dark-mode .epreuve-related-card-title { color: #f1f5f9; }
body.dark-mode .epreuve-show-meta, body.dark-mode .epreuve-show-description, body.dark-mode .epreuve-related-card-meta { color: #94a3b8; }
body.dark-mode .epreuve-show-back { color: #34d399; }
body.dark-mode .epreuve-show-badge--exam { background: rgba(52,211,153,0.15); color: #34d399; }
body.dark-mode .epreuve-show-badge--type { background: rgba(56,189,248,0.15); color: #38bdf8; }
</style>
@endpush

@section('content')
<div class="epreuve-show-page">
    <div class="epreuve-show-container">

        <a href="{{ route('epreuves.index') }}" class="epreuve-show-back">
            <i class="fas fa-arrow-left"></i> Toutes les épreuves
        </a>

        <div class="epreuve-show-header">
            <div class="epreuve-show-badges">
                @if($epreuve->exam)
                    <span class="epreuve-show-badge epreuve-show-badge--exam">{{ $epreuve->exam_label }}</span>
                @endif
                @if($epreuve->level)
                    <span class="epreuve-show-badge epreuve-show-badge--exam">{{ $epreuve->level_label }}</span>
                @endif
                <span class="epreuve-show-badge epreuve-show-badge--type">{{ $epreuve->type_label }}</span>
            </div>

            <h1 class="epreuve-show-title">{{ $epreuve->title }}</h1>

            <div class="epreuve-show-meta">
                @if($epreuve->matiere)<span><i class="fas fa-book"></i>{{ $epreuve->matiere->name }}</span>@endif
                @if($epreuve->serie)<span><i class="fas fa-layer-group"></i>Série {{ $epreuve->serie }}</span>@endif
                @if($epreuve->year)<span><i class="fas fa-calendar"></i>Session {{ $epreuve->year }}</span>@endif
                @if($epreuve->file_size_human)<span><i class="fas fa-file-pdf"></i>{{ $epreuve->file_size_human }}</span>@endif
                <span><i class="fas fa-download"></i>{{ number_format($epreuve->downloads_count, 0, ',', ' ') }} téléchargement(s)</span>
            </div>

            <div class="epreuve-show-actions">
                <a href="{{ route('epreuves.download', ['id' => $epreuve->id, 'file' => 'epreuve']) }}" class="epreuve-show-btn epreuve-show-btn--primary">
                    <i class="fas fa-download"></i> Télécharger le PDF
                </a>
                @if($epreuve->corrige_file_path)
                    <a href="{{ route('epreuves.download', ['id' => $epreuve->id, 'file' => 'corrige']) }}" class="epreuve-show-btn epreuve-show-btn--secondary">
                        <i class="fas fa-check-circle"></i> Télécharger le corrigé
                    </a>
                @endif
            </div>

            @if($epreuve->description)
                <div class="epreuve-show-description">{!! nl2br(e($epreuve->description)) !!}</div>
            @endif
        </div>

        <div class="epreuve-show-viewer">
            <iframe src="{{ route('epreuves.view', ['id' => $epreuve->id]) }}#toolbar=0" title="Aperçu : {{ $epreuve->title }}" loading="lazy"></iframe>
        </div>

        @if($related->isNotEmpty())
            <h2 class="epreuve-related-title">Documents similaires</h2>
            <div class="epreuve-related-grid">
                @foreach($related as $rel)
                    <a href="{{ route('epreuves.show', $rel->slug) }}" class="epreuve-related-card">
                        <div class="epreuve-related-card-title">{{ $rel->title }}</div>
                        <div class="epreuve-related-card-meta">
                            {{ $rel->matiere?->name }}{{ $rel->year ? ' · ' . $rel->year : '' }}{{ $rel->serie ? ' · Série ' . $rel->serie : '' }}
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

    </div>
</div>
@endsection
