@extends('layouts.app')

@section('title', 'Papiers et documents administratifs au Sénégal - NiangProgrammeur')
@section('meta_description', 'Guide pratique des papiers administratifs au Sénégal : CNI, passeport, extrait de naissance, casier judiciaire, dossiers de concours, documents pour entreprise, et plus.')

@push('styles')
<style>
/* Light theme (default) */
.admin-docs-page {
    min-height: 100vh;
    padding: 3rem 1.5rem 4rem;
    position: relative;
    overflow: hidden;
    background:
        linear-gradient(180deg, #f0f9ff 0%, #e0f2fe 35%, #f8fafc 70%);
    color: #0f172a;
}
.admin-docs-title { color: #0f172a; }
.admin-docs-subtitle { color: #475569; }
.admin-docs-pill {
    border-color: rgba(15, 23, 42, 0.15);
    background: rgba(255, 255, 255, 0.9);
    color: #334155;
}
.admin-docs-stats { color: #334155; }
.admin-docs-stat-label { color: #64748b; }
.admin-docs-aside-card {
    background: linear-gradient(145deg, rgba(255, 255, 255, 0.95), rgba(241, 245, 249, 0.98));
    border: 1px solid rgba(203, 213, 225, 0.8);
    box-shadow: 0 10px 40px rgba(15, 23, 42, 0.08);
}
.admin-docs-aside-title { color: #0f172a; }
.admin-docs-aside-text { color: #475569; }
.admin-docs-aside-list { color: #334155; }
.admin-docs-aside-list li span { color: #059669; }
.admin-docs-search input[type="text"] {
    border-color: rgba(203, 213, 225, 0.9);
    background: #fff;
    color: #0f172a;
}
.admin-docs-search input[type="text"]::placeholder { color: #94a3b8; }
.admin-docs-category-pill {
    border-color: rgba(203, 213, 225, 0.9);
    background: rgba(255, 255, 255, 0.95);
    color: #334155;
}
.admin-docs-category-pill.active {
    background: linear-gradient(135deg, #0ea5e9, #0284c7);
    color: #fff;
}
.admin-doc-card {
    background: #fff;
    border-color: rgba(226, 232, 240, 0.9);
    box-shadow: 0 10px 40px rgba(15, 23, 42, 0.06);
}
.admin-doc-card:hover {
    box-shadow: 0 20px 50px rgba(15, 23, 42, 0.1);
    border-color: rgba(14, 165, 233, 0.5);
}
.admin-doc-card-image-wrap {
    background: linear-gradient(145deg, #e0f2fe, #f1f5f9);
}
.admin-doc-card-image-placeholder { color: #94a3b8; }
.admin-doc-badge {
    background: #ecfeff;
    color: #0e7490;
    border-color: rgba(14, 165, 233, 0.4);
}
.admin-doc-title { color: #0f172a; }
.admin-doc-summary { color: #475569; }
.admin-doc-meta { color: #64748b; }
.admin-doc-link a { color: #0284c7; }
.admin-doc-empty { color: #64748b; }

/* Dark theme */
body.dark-mode .admin-docs-page {
    background:
        radial-gradient(circle at top left, rgba(56, 189, 248, 0.18), transparent 55%),
        radial-gradient(circle at top right, rgba(59, 130, 246, 0.18), transparent 55%),
        radial-gradient(circle at bottom, rgba(8, 47, 73, 0.55), #020617 70%);
    color: #e5e7eb;
}
body.dark-mode .admin-docs-title { color: #f9fafb; }
body.dark-mode .admin-docs-subtitle { color: #cbd5f5; }
body.dark-mode .admin-docs-pill {
    border-color: rgba(148, 163, 184, 0.3);
    background: rgba(15, 23, 42, 0.7);
    color: #e5e7eb;
}
body.dark-mode .admin-docs-stats { color: #e5e7eb; }
body.dark-mode .admin-docs-stat-label { color: #94a3b8; }
body.dark-mode .admin-docs-aside-card {
    background: radial-gradient(circle at top left, rgba(59, 130, 246, 0.3), transparent 55%), rgba(15, 23, 42, 0.92);
    border-color: rgba(148, 163, 184, 0.45);
    box-shadow: 0 24px 60px rgba(15, 23, 42, 0.95);
}
body.dark-mode .admin-docs-aside-title { color: #f9fafb; }
body.dark-mode .admin-docs-aside-text { color: #cbd5f5; }
body.dark-mode .admin-docs-aside-list { color: #e5e7eb; }
body.dark-mode .admin-docs-aside-list li span { color: #22c55e; }
body.dark-mode .admin-docs-search input[type="text"] {
    border-color: rgba(148, 163, 184, 0.45);
    background: radial-gradient(circle at top left, rgba(15, 23, 42, 0.9), rgba(15, 23, 42, 0.95));
    color: #e5e7eb;
}
body.dark-mode .admin-docs-search input[type="text"]::placeholder { color: #6b7280; }
body.dark-mode .admin-docs-category-pill {
    border-color: rgba(148, 163, 184, 0.5);
    background: rgba(15, 23, 42, 0.85);
    color: #e5e7eb;
}
body.dark-mode .admin-docs-category-pill.active {
    background: radial-gradient(circle at top left, rgba(56, 189, 248, 0.85), rgba(59, 130, 246, 0.8));
    border-color: transparent;
    color: #0f172a;
}
body.dark-mode .admin-doc-card {
    background: radial-gradient(circle at top left, rgba(15, 23, 42, 0.95), rgba(15, 23, 42, 0.98));
    border-color: rgba(148, 163, 184, 0.4);
    box-shadow: 0 22px 55px rgba(15, 23, 42, 0.95);
}
body.dark-mode .admin-doc-card:hover {
    box-shadow: 0 26px 65px rgba(15, 23, 42, 0.98);
    border-color: rgba(56, 189, 248, 0.7);
}
body.dark-mode .admin-doc-card::before {
    background: radial-gradient(circle at top left, rgba(56, 189, 248, 0.35), transparent 50%);
}
body.dark-mode .admin-doc-card-image-wrap {
    background: linear-gradient(145deg, rgba(30, 58, 138, 0.6), rgba(15, 23, 42, 0.9));
}
body.dark-mode .admin-doc-card-image-placeholder { color: rgba(148, 163, 184, 0.5); }
body.dark-mode .admin-doc-badge {
    background: rgba(15, 23, 42, 0.9);
    color: #7dd3fc;
    border-color: rgba(56, 189, 248, 0.6);
}
body.dark-mode .admin-doc-title { color: #f9fafb; }
body.dark-mode .admin-doc-summary { color: #cbd5f5; }
body.dark-mode .admin-doc-meta { color: #9ca3af; }
body.dark-mode .admin-doc-link a { color: #38bdf8; }
body.dark-mode .admin-doc-empty { color: #9ca3af; }

.admin-docs-container {
    max-width: 1200px;
    margin: 0 auto;
}
.admin-docs-header {
    display: grid;
    grid-template-columns: minmax(0, 2fr) minmax(0, 1.3fr);
    gap: 2.5rem;
    align-items: center;
    margin-bottom: 2.5rem;
}
.admin-docs-title {
    font-size: 2.25rem;
    font-weight: 800;
    letter-spacing: -0.04em;
    margin-bottom: 0.5rem;
}
.admin-docs-subtitle {
    max-width: 34rem;
    font-size: 0.98rem;
}
.admin-docs-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.2rem 0.8rem;
    border-radius: 999px;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.12em;
}
.admin-docs-pill span {
    display: inline-flex;
    width: 0.6rem;
    height: 0.6rem;
    border-radius: 999px;
    background: linear-gradient(135deg, #22c55e, #a3e635);
    box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.18);
}
.admin-docs-stats {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
    margin-top: 1.75rem;
    font-size: 0.85rem;
}
.admin-docs-stat-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}
.admin-docs-stat-label {
    text-transform: uppercase;
    font-size: 0.7rem;
    letter-spacing: 0.12em;
}
.admin-docs-stat-value {
    font-weight: 600;
}
.admin-docs-aside {
    position: relative;
}
.admin-docs-aside-card {
    border-radius: 1.5rem;
    padding: 1.5rem 1.4rem;
    border: 1px solid transparent;
}
.admin-docs-aside-title {
    font-size: 0.95rem;
    font-weight: 600;
    margin-bottom: 0.65rem;
}
.admin-docs-aside-text {
    font-size: 0.82rem;
    margin-bottom: 0.9rem;
}
.admin-docs-aside-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    gap: 0.55rem;
    font-size: 0.8rem;
}
.admin-docs-aside-list li {
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
}
.admin-docs-aside-list li span {
    margin-top: 0.12rem;
}
.admin-docs-search {
    margin: 0 auto 1.5rem;
    max-width: 860px;
    display: flex;
    flex-wrap: wrap;
    gap: 0.9rem;
}
.admin-docs-search input[type="text"] {
    flex: 1 1 260px;
    padding: 0.9rem 1.1rem;
    border-radius: 999px;
    outline: none;
    font-size: 0.9rem;
    border: 1px solid transparent;
}
.admin-docs-search button {
    padding: 0.8rem 1.7rem;
    border-radius: 999px;
    border: none;
    background: linear-gradient(135deg, #0ea5e9, #22c55e);
    color: white;
    font-weight: 700;
    cursor: pointer;
    font-size: 0.9rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow:
        0 18px 40px rgba(34, 197, 94, 0.45),
        0 0 0 1px rgba(15, 23, 42, 0.7);
    transition:
        transform 160ms ease-out,
        box-shadow 160ms ease-out,
        filter 160ms ease-out;
}
.admin-docs-search button:hover {
    transform: translateY(-1px);
    filter: brightness(1.05);
    box-shadow:
        0 22px 55px rgba(34, 197, 94, 0.6),
        0 0 0 1px rgba(15, 23, 42, 0.7);
}
.admin-docs-categories {
    display: flex;
    flex-wrap: wrap;
    gap: 0.6rem;
    justify-content: flex-start;
    margin: 0 auto 1.75rem;
    max-width: 960px;
}
.admin-docs-category-pill {
    padding: 0.35rem 0.9rem;
    border-radius: 999px;
    font-size: 0.875rem;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    transition:
        background-color 140ms ease-out,
        transform 140ms ease-out,
        border-color 140ms ease-out;
}
.admin-docs-category-pill.active {
    transform: translateY(-1px);
}
.admin-docs-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
}
.admin-doc-card {
    border-radius: 1rem;
    overflow: hidden;
    border: 1px solid transparent;
    display: flex;
    flex-direction: column;
    position: relative;
    transition:
        transform 160ms ease-out,
        box-shadow 160ms ease-out,
        border-color 160ms ease-out;
}
.admin-doc-card::before {
    content: '';
    position: absolute;
    inset: -1px;
    opacity: 0;
    background: radial-gradient(circle at top left, rgba(56, 189, 248, 0.35), transparent 50%);
    transition: opacity 180ms ease-out;
    pointer-events: none;
}
.admin-doc-card:hover {
    transform: translateY(-4px) scale(1.01);
    box-shadow:
        0 26px 65px rgba(15, 23, 42, 0.98),
        0 0 0 1px rgba(15, 23, 42, 0.9) inset;
    border-color: rgba(56, 189, 248, 0.7);
}
.admin-doc-card:hover::before {
    opacity: 1;
}
.admin-doc-card-image-wrap {
    width: 100%;
    aspect-ratio: 16 / 10;
    flex-shrink: 0;
    overflow: hidden;
}
.admin-doc-card-image-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 220ms ease-out;
}
.admin-doc-card:hover .admin-doc-card-image-wrap img {
    transform: scale(1.06);
}
.admin-doc-card-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
}
.admin-doc-card-body {
    padding: 1.2rem 1.25rem;
    display: flex;
    flex-direction: column;
    gap: 0.65rem;
    flex: 1;
}
.admin-doc-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.22rem 0.7rem;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 600;
    border: 1px solid transparent;
}
.admin-doc-title {
    font-size: 1.05rem;
    font-weight: 700;
}
.admin-doc-summary {
    font-size: 0.9rem;
}
.admin-doc-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    font-size: 0.8rem;
    margin-top: auto;
}
.admin-doc-meta span {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
}
.admin-doc-link {
    margin-top: 0.75rem;
}
.admin-doc-link a {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.9rem;
    font-weight: 600;
    text-decoration: none;
}
.admin-doc-empty {
    text-align: center;
    padding: 2rem 1rem;
}
@media (max-width: 900px) {
    .admin-docs-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 640px) {
    .admin-docs-title {
        font-size: 1.55rem;
    }
    .admin-docs-header {
        grid-template-columns: minmax(0, 1fr);
        gap: 1.75rem;
    }
    .admin-docs-page {
        padding: 2.25rem 1rem 3rem;
    }
    .admin-docs-search {
        gap: 0.7rem;
    }
    .admin-docs-search button {
        width: 100%;
        justify-content: center;
    }
    .admin-docs-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('content')
<div class="admin-docs-page">
    <div class="admin-docs-container">
        <div class="admin-docs-header">
            <div>
                <div class="admin-docs-pill">
                    <span></span>
                    Papiers & démarches – Guide simplifié
                </div>
                <h1 class="admin-docs-title">Papiers et démarches administratives au Sénégal</h1>
                <p class="admin-docs-subtitle">
                    Retrouvez, en langage simple, les pièces à fournir, les lieux où déposer le dossier,
                    les coûts et délais approximatifs pour vos documents administratifs les plus demandés.
                </p>

                <div class="admin-docs-stats">
                    <div class="admin-docs-stat-item">
                        <span class="admin-docs-stat-label">Fiches disponibles</span>
                        <span class="admin-docs-stat-value">
                            {{ number_format($documents->total()) }}+
                        </span>
                    </div>
                    @if(!empty($categories))
                        <div class="admin-docs-stat-item">
                            <span class="admin-docs-stat-label">Types de démarches</span>
                            <span class="admin-docs-stat-value">
                                {{ count($categories) }} catégories
                            </span>
                        </div>
                    @endif
                    <div class="admin-docs-stat-item">
                        <span class="admin-docs-stat-label">Objectif</span>
                        <span class="admin-docs-stat-value">
                            Vous faire gagner du temps & éviter les allers-retours inutiles.
                        </span>
                    </div>
                </div>
            </div>

            <aside class="admin-docs-aside">
                <div class="admin-docs-aside-card">
                    <p class="admin-docs-aside-title">Comment utiliser ce guide&nbsp;?</p>
                    <p class="admin-docs-aside-text">
                        Cherchez un document (CNI, passeport, casier judiciaire, carte de séjour…),
                        filtrez par type de démarche puis ouvrez la fiche détaillée.
                    </p>
                    <ul class="admin-docs-aside-list">
                        <li>
                            <span>•</span>
                            <div>Trouvez les <strong>pièces à fournir</strong> et le bon <strong>lieu de dépôt</strong>.</div>
                        </li>
                        <li>
                            <span>•</span>
                            <div>Anticipez les <strong>coûts</strong> et <strong>délais moyens</strong> avant de vous déplacer.</div>
                        </li>
                        <li>
                            <span>•</span>
                            <div>Suivez les <strong>conseils pratiques</strong> pour éviter les refus de dossiers.</div>
                        </li>
                    </ul>
                </div>
            </aside>
        </div>

        <form method="GET" action="{{ route('admin-docs.index') }}" class="admin-docs-search">
            <input
                type="text"
                name="q"
                value="{{ $search }}"
                placeholder="Ex : Carte d'identité, Passeport, Casier judiciaire, Dossier de concours..."
            >
            <button type="submit">
                <i class="fas fa-search"></i>
                <span>Rechercher un document</span>
            </button>
        </form>

        @if(!empty($categories))
        <div class="admin-docs-categories">
            <a href="{{ route('admin-docs.index', array_filter(['q' => $search])) }}"
               class="admin-docs-category-pill {{ $category === '' ? 'active' : '' }}">
                Tous les types
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('admin-docs.index', array_filter(['q' => $search, 'category' => $cat])) }}"
                   class="admin-docs-category-pill {{ $category === $cat ? 'active' : '' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>
        @endif

        @if($documents->count() === 0)
            <div class="admin-doc-empty">
                <p>
                    Aucun document trouvé pour votre recherche.
                    Essayez un autre mot-clé (ex : « CNI », « Passeport », « concours »).
                </p>
            </div>
        @else
            <div class="admin-docs-grid">
                @foreach($documents as $doc)
                    @php
                        $coverUrl = null;
                        if ($doc->cover_image) {
                            $coverUrl = $doc->cover_type === 'internal'
                                ? \Illuminate\Support\Facades\URL::temporarySignedRoute('admin-docs.cover.signed', now()->addHours(24), ['id' => $doc->id])
                                : $doc->cover_image;
                        }
                    @endphp
                    <article class="admin-doc-card">
                        <a href="{{ route('admin-docs.show', $doc->slug) }}" class="admin-doc-card-image-wrap">
                            @if($coverUrl)
                                <img src="{{ $coverUrl }}" alt="" loading="lazy">
                            @else
                                <div class="admin-doc-card-image-placeholder">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                            @endif
                        </a>
                        <div class="admin-doc-card-body">
                            @if($doc->category)
                                <div class="admin-doc-badge">
                                    <i class="fas fa-folder-open"></i> {{ $doc->category }}
                                </div>
                            @endif

                            <h2 class="admin-doc-title">
                                <a href="{{ route('admin-docs.show', $doc->slug) }}" style="text-decoration: none; color: inherit;">
                                    {{ $doc->title }}
                                </a>
                            </h2>

                            @if($doc->summary)
                                <p class="admin-doc-summary">
                                    {!! \Illuminate\Support\Str::limit($doc->summary, 120) !!}
                                </p>
                            @elseif($doc->purpose)
                                <p class="admin-doc-summary">
                                    {!! \Illuminate\Support\Str::limit($doc->purpose, 120) !!}
                                </p>
                            @endif

                            <div class="admin-doc-meta">
                                @if($doc->approx_cost)
                                    <span><i class="fas fa-money-bill-wave"></i> {{ \Illuminate\Support\Str::limit($doc->approx_cost, 40) }}</span>
                                @endif
                                @if($doc->approx_delay)
                                    <span><i class="fas fa-clock"></i> {{ \Illuminate\Support\Str::limit($doc->approx_delay, 30) }}</span>
                                @endif
                            </div>

                            <div class="admin-doc-link">
                                <a href="{{ route('admin-docs.show', $doc->slug) }}">
                                    Voir la fiche détaillée <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div style="margin-top: 2rem;">
                {{ $documents->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

