@extends('admin.layout')

@section('title', 'Modifier : ' . $document->title)

@section('styles')
<style>
    body.light-mode .admin-adm-doc-form-card {
        background: #ffffff;
        border-color: rgba(6, 182, 212, 0.3);
    }
    body.light-mode .admin-adm-doc-form-card h1 { color: #0891b2; }
    body.light-mode .admin-adm-doc-form-card label,
    body.light-mode .admin-adm-doc-form-card .text-gray-400 { color: #64748b; }
    body.light-mode .admin-adm-doc-form-card input,
    body.light-mode .admin-adm-doc-form-card select,
    body.light-mode .admin-adm-doc-form-card textarea {
        background: #f8f9fa !important;
        border-color: #cbd5e1 !important;
        color: #1e293b !important;
    }
    body.light-mode .admin-adm-doc-form-card input::placeholder,
    body.light-mode .admin-adm-doc-form-card textarea::placeholder { color: #94a3b8; }
    body.light-mode .admin-adm-doc-form-card .bg-gray-600 { background: #64748b !important; }
    body.light-mode .admin-adm-doc-form-card .bg-gray-600:hover { background: #475569 !important; }
</style>
@endsection

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6 flex items-center justify-between gap-4">
        <a href="{{ route('admin.documents.administrative-documents.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg text-white transition">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
        <div class="text-sm text-gray-400 hidden sm:block">
            <span class="px-2 py-1 rounded-full border border-yellow-400/40 bg-yellow-500/10 text-yellow-300">
                Édition de fiche existante
            </span>
        </div>
    </div>
    <div class="admin-adm-doc-form-card p-6 rounded-xl bg-gray-800/80 border border-cyan-500/30 shadow-xl">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-cyan-400">Modifier : {{ $document->title }}</h1>
                <p class="mt-1 text-sm text-gray-400">Mets à jour les informations de la fiche administrative.</p>
            </div>
        </div>
        <form action="{{ route('admin.documents.administrative-documents.update', $document) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            @include('admin.documents.administrative-documents._form', ['document' => $document, 'categories' => $categories])
            <div class="pt-4 border-t border-gray-700 flex flex-wrap gap-3 justify-end">
                <a href="{{ route('admin.documents.administrative-documents.index') }}" class="px-6 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition">
                    Annuler
                </a>
                <button type="submit" class="px-6 py-2 bg-cyan-500 hover:bg-cyan-400 text-black font-semibold rounded-lg transition inline-flex items-center gap-2">
                    <i class="fas fa-save"></i> Mettre à jour la fiche
                </button>
            </div>
        </form>
    </div>
</div>
@section('scripts')
<script>
document.getElementById('coverType')?.addEventListener('change', function() {
    const isInternal = this.value === 'internal';
    const internal = document.getElementById('coverInternal');
    const external = document.getElementById('coverExternal');
    if (internal && external) {
        internal.style.display = isInternal ? 'block' : 'none';
        external.style.display = isInternal ? 'none' : 'block';
    }
});
document.getElementById('categorySelect')?.addEventListener('change', function() {
    const wrap = document.getElementById('categoryNewWrap');
    if (wrap) {
        wrap.style.display = this.value === '__new__' ? 'block' : 'none';
    }
});
</script>
@endsection
@endsection

