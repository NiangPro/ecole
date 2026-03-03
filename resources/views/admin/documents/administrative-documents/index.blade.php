@extends('admin.layout')

@section('title', 'Documents administratifs')

@section('styles')
<style>
    body.light-mode .administrative-docs-admin h3 { color: #1e293b; }
    body.light-mode .administrative-docs-admin p { color: #64748b; }
    body.light-mode .administrative-docs-admin .content-section {
        background: #ffffff;
        border-color: rgba(6, 182, 212, 0.2);
    }
    body.light-mode .administrative-docs-admin input[type="text"],
    body.light-mode .administrative-docs-admin select {
        background: #f8f9fa;
        border-color: #cbd5e1;
        color: #1e293b;
    }
    body.light-mode .administrative-docs-admin input::placeholder { color: #94a3b8; }
    body.light-mode .administrative-docs-admin select option { background: #fff; color: #1e293b; }
    body.light-mode .administrative-docs-admin .border-cyan-500\/30 { border-color: rgba(6, 182, 212, 0.4); }
    body.light-mode .administrative-docs-admin .border-gray-700\/50 { border-color: #e2e8f0; }
    body.light-mode .administrative-docs-admin tbody tr:hover { background: #f1f5f9; }
    body.light-mode .administrative-docs-admin .text-cyan-400 { color: #0891b2; }
    body.light-mode .administrative-docs-admin .text-gray-400 { color: #64748b; }
    body.light-mode .administrative-docs-admin .bg-cyan-500\/20 { background: rgba(6, 182, 212, 0.1); }
    body.light-mode .administrative-docs-admin .bg-cyan-500\/20:hover { background: rgba(6, 182, 212, 0.2); }
</style>
@endsection

@section('content')
<div class="administrative-docs-admin">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h3 class="text-3xl font-bold mb-2">Documents administratifs</h3>
            <p class="text-gray-400">Gérez les fiches papiers & démarches (Sénégal)</p>
        </div>
        <a href="{{ route('admin.documents.administrative-documents.create') }}" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-black font-semibold rounded-lg transition">
            <i class="fas fa-plus mr-2"></i>Nouvelle fiche
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

    <form method="GET" action="{{ route('admin.documents.administrative-documents.index') }}" class="grid md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Recherche (titre, catégorie…)" class="px-4 py-2 rounded-lg bg-gray-800 border border-gray-600 text-white placeholder-gray-500 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
        <select name="category" class="px-4 py-2 rounded-lg bg-gray-800 border border-gray-600 text-white focus:border-cyan-500">
            <option value="">Toutes les catégories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>
        <select name="status" class="px-4 py-2 rounded-lg bg-gray-800 border border-gray-600 text-white focus:border-cyan-500">
            <option value="">Tous les statuts</option>
            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Publié</option>
            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Brouillon</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-cyan-500/20 hover:bg-cyan-500/30 text-cyan-400 rounded-lg border border-cyan-500/40 transition">
            <i class="fas fa-search mr-2"></i>Filtrer
        </button>
    </form>

    <div class="content-section overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-cyan-500/30">
                    <th class="pb-3 pr-4 font-semibold">Titre</th>
                    <th class="pb-3 pr-4 font-semibold">Catégorie</th>
                    <th class="pb-3 pr-4 font-semibold">Statut</th>
                    <th class="pb-3 pr-4 font-semibold">Vedette</th>
                    <th class="pb-3 pr-4 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($documents as $doc)
                <tr class="border-b border-gray-700/50 hover:bg-gray-800/30 transition">
                    <td class="py-3 pr-4">
                        <a href="{{ route('admin-docs.show', $doc->slug) }}" target="_blank" rel="noopener" class="text-cyan-400 hover:underline font-medium">{{ $doc->title }}</a>
                    </td>
                    <td class="py-3 pr-4 text-gray-400">{{ $doc->category ?? '—' }}</td>
                    <td class="py-3 pr-4">
                        <span class="px-2 py-1 rounded text-xs {{ $doc->status === 'published' ? 'bg-green-500/20 text-green-400' : 'bg-gray-500/20 text-gray-400' }}">
                            {{ $doc->status === 'published' ? 'Publié' : 'Brouillon' }}
                        </span>
                    </td>
                    <td class="py-3 pr-4">
                        @if($doc->is_featured)
                            <i class="fas fa-star text-yellow-400"></i>
                        @else
                            —
                        @endif
                    </td>
                    <td class="py-3 pr-4 text-right">
                        <a href="{{ route('admin.documents.administrative-documents.edit', $doc) }}" class="inline-flex items-center px-3 py-1.5 bg-yellow-500/20 hover:bg-yellow-500/30 text-yellow-400 rounded text-sm transition mr-1">
                            <i class="fas fa-edit mr-1"></i>Modifier
                        </a>
                        <form action="{{ route('admin.documents.administrative-documents.destroy', $doc) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer cette fiche ?');">
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
                    <td colspan="5" class="py-8 text-center text-gray-400">
                        <i class="fas fa-id-card text-4xl mb-4 block"></i>
                        Aucune fiche. <a href="{{ route('admin.documents.administrative-documents.create') }}" class="text-cyan-400 hover:underline">Créer la première</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($documents->hasPages())
            <div class="mt-6">
                {{ $documents->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
