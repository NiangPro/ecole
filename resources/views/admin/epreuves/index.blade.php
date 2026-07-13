@extends('admin.layout')

@section('title', 'Épreuves & Corrigés')

@section('content')
<div class="epreuves-admin">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h3 class="text-3xl font-bold mb-2">Épreuves &amp; Corrigés</h3>
            <p class="text-gray-400">Gérez les épreuves, examens blancs et corrigés (Sénégal)</p>
        </div>
        <a href="{{ route('admin.epreuves.create') }}" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-black font-semibold rounded-lg transition">
            <i class="fas fa-plus mr-2"></i>Nouvelle épreuve
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-500/20 border border-green-500/50 rounded-lg text-green-400 flex items-center gap-2">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-500/20 border border-red-500/50 rounded-lg text-red-400 flex items-center gap-2">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    <form method="GET" action="{{ route('admin.epreuves.index') }}" class="grid md:grid-cols-3 lg:grid-cols-4 gap-4 mb-6">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Recherche (titre…)" class="px-4 py-2 rounded-lg bg-gray-800 border border-gray-600 text-white placeholder-gray-500 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 lg:col-span-2">
        <select name="exam" class="px-4 py-2 rounded-lg bg-gray-800 border border-gray-600 text-white focus:border-cyan-500">
            <option value="">Tous les examens</option>
            @foreach(\App\Models\Epreuve::EXAMS as $key => $label)
                <option value="{{ $key }}" @selected(request('exam') === $key)>{{ $label }}</option>
            @endforeach
        </select>
        <select name="matiere_id" class="px-4 py-2 rounded-lg bg-gray-800 border border-gray-600 text-white focus:border-cyan-500">
            <option value="">Toutes les matières</option>
            @foreach($matieres as $matiere)
                <option value="{{ $matiere->id }}" @selected(request('matiere_id') == $matiere->id)>{{ $matiere->name }}</option>
            @endforeach
        </select>
        <select name="status" class="px-4 py-2 rounded-lg bg-gray-800 border border-gray-600 text-white focus:border-cyan-500">
            <option value="">Tous les statuts</option>
            <option value="published" @selected(request('status') === 'published')>Publié</option>
            <option value="draft" @selected(request('status') === 'draft')>Brouillon</option>
        </select>
        <input type="number" name="min_downloads" min="0" step="1" value="{{ request('min_downloads') }}"
               placeholder="Téléch. min (ex : 10)"
               class="px-4 py-2 rounded-lg bg-gray-800 border border-gray-600 text-white placeholder-gray-500 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
        <select name="corrige" class="px-4 py-2 rounded-lg bg-gray-800 border border-gray-600 text-white focus:border-cyan-500">
            <option value="">Tous (corrigé ou non)</option>
            <option value="1" @selected(request('corrige') === '1')>✅ Avec corrigé</option>
            <option value="0" @selected(request('corrige') === '0')>❌ Sans corrigé</option>
        </select>
        <select name="sort" class="px-4 py-2 rounded-lg bg-gray-800 border border-gray-600 text-white focus:border-cyan-500">
            <option value="recent" @selected(request('sort', 'recent') === 'recent')>Tri : plus récents</option>
            <option value="downloads_desc" @selected(request('sort') === 'downloads_desc')>Tri : téléch. décroissant</option>
            <option value="downloads_asc" @selected(request('sort') === 'downloads_asc')>Tri : téléch. croissant</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-cyan-500/20 hover:bg-cyan-500/30 text-cyan-400 rounded-lg border border-cyan-500/40 transition">
            <i class="fas fa-search mr-2"></i>Filtrer
        </button>
    </form>

    <form id="bulk-action-form" method="POST" action="{{ route('admin.epreuves.bulk-destroy') }}" onsubmit="return confirm(document.querySelectorAll('.row-checkbox:checked').length + ' épreuve(s) et leurs fichiers PDF seront supprimés. Confirmer ?');">
        @csrf
        @method('DELETE')
    </form>

    <div class="content-section mb-4 flex items-center justify-between gap-4" id="bulk-actions-bar" style="display:none;">
        <div class="flex items-center gap-2 text-sm text-gray-300">
            <span id="selected-count">0 sélectionnée(s)</span>
        </div>
        <button type="submit" form="bulk-action-form" class="inline-flex items-center px-3 py-1.5 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded text-sm transition">
            <i class="fas fa-trash mr-1"></i>Supprimer la sélection
        </button>
    </div>

    <div class="content-section overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-cyan-500/30">
                    <th class="pb-3 pr-4 font-semibold w-8">
                        <input type="checkbox" id="select-all" onchange="toggleSelectAllEpreuves(this)">
                    </th>
                    <th class="pb-3 pr-4 font-semibold">Titre</th>
                    <th class="pb-3 pr-4 font-semibold">Examen / Classe</th>
                    <th class="pb-3 pr-4 font-semibold">Matière</th>
                    <th class="pb-3 pr-4 font-semibold">Année</th>
                    <th class="pb-3 pr-4 font-semibold">Téléch.</th>
                    <th class="pb-3 pr-4 font-semibold">Corrigé</th>
                    <th class="pb-3 pr-4 font-semibold">Statut</th>
                    <th class="pb-3 pr-4 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($epreuves as $epreuve)
                <tr class="border-b border-gray-700/50 hover:bg-gray-800/30 transition">
                    <td class="py-3 pr-4">
                        <input type="checkbox" name="ids[]" value="{{ $epreuve->id }}" form="bulk-action-form" class="row-checkbox" onchange="updateSelectedEpreuvesCount()">
                    </td>
                    <td class="py-3 pr-4">
                        <a href="{{ route('epreuves.show', $epreuve->slug) }}" target="_blank" rel="noopener" class="text-cyan-400 hover:underline font-medium">{{ Str::limit($epreuve->title, 60) }}</a>
                        <div class="text-xs text-gray-500 mt-0.5">{{ $epreuve->type_label }}{{ $epreuve->serie ? ' · Série ' . $epreuve->serie : '' }}</div>
                    </td>
                    <td class="py-3 pr-4 text-gray-400">{{ $epreuve->exam_label ?? $epreuve->level_label ?? '—' }}</td>
                    <td class="py-3 pr-4 text-gray-400">{{ $epreuve->matiere?->name ?? '—' }}</td>
                    <td class="py-3 pr-4 text-gray-400">{{ $epreuve->year ?? '—' }}</td>
                    <td class="py-3 pr-4 text-gray-400">{{ number_format($epreuve->downloads_count, 0, ',', ' ') }}</td>
                    <td class="py-3 pr-4">
                        @if($epreuve->hasCorrige())
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                <i class="fas fa-check-circle"></i> Oui
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs bg-gray-700/50 text-gray-500 border border-gray-600/30">
                                <i class="fas fa-times-circle"></i> Non
                            </span>
                        @endif
                    </td>
                    <td class="py-3 pr-4">
                        <form action="{{ route('admin.epreuves.toggle-publish', $epreuve) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-2 py-1 rounded text-xs {{ $epreuve->status === 'published' ? 'bg-green-500/20 text-green-400' : 'bg-gray-500/20 text-gray-400' }}" title="Cliquer pour {{ $epreuve->status === 'published' ? 'dépublier' : 'publier' }}">
                                {{ $epreuve->status === 'published' ? 'Publié' : 'Brouillon' }}
                            </button>
                        </form>
                    </td>
                    <td class="py-3 pr-4 text-right whitespace-nowrap">
                        <a href="{{ route('admin.epreuves.edit', $epreuve) }}" class="inline-flex items-center px-3 py-1.5 bg-yellow-500/20 hover:bg-yellow-500/30 text-yellow-400 rounded text-sm transition mr-1">
                            <i class="fas fa-edit mr-1"></i>Modifier
                        </a>
                        <form action="{{ route('admin.epreuves.destroy', $epreuve) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer cette épreuve et ses fichiers PDF ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded text-sm transition">
                                <i class="fas fa-trash mr-1"></i>Supprimer
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="py-8 text-center text-gray-400">
                        Aucune épreuve pour le moment. <a href="{{ route('admin.epreuves.create') }}" class="text-cyan-400 hover:underline">Ajoutez la première</a>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $epreuves->links() }}
    </div>
</div>

<script>
function toggleSelectAllEpreuves(source) {
    document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = source.checked);
    updateSelectedEpreuvesCount();
}

function updateSelectedEpreuvesCount() {
    const checked = document.querySelectorAll('.row-checkbox:checked');
    const bar = document.getElementById('bulk-actions-bar');
    const countEl = document.getElementById('selected-count');
    const all = document.querySelectorAll('.row-checkbox');
    const selectAll = document.getElementById('select-all');

    countEl.textContent = checked.length + ' sélectionnée(s)';
    bar.style.display = checked.length > 0 ? 'flex' : 'none';
    if (selectAll) {
        selectAll.checked = all.length > 0 && checked.length === all.length;
    }
}
</script>
@endsection
