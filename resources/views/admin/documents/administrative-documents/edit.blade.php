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
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.documents.administrative-documents.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg text-white transition">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>
    <div class="admin-adm-doc-form-card p-6 rounded-xl bg-gray-800/70 border border-cyan-500/20">
        <h1 class="text-2xl font-bold mb-6 text-cyan-400">Modifier : {{ $document->title }}</h1>
        <form action="{{ route('admin.documents.administrative-documents.update', $document) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.documents.administrative-documents._form', ['document' => $document])
            <div class="mt-8 flex gap-3">
                <button type="submit" class="px-6 py-2 bg-cyan-500 hover:bg-cyan-600 text-black font-semibold rounded-lg transition">
                    <i class="fas fa-save mr-2"></i>Mettre à jour
                </button>
                <a href="{{ route('admin.documents.administrative-documents.index') }}" class="px-6 py-2 bg-gray-600 hover:bg-gray-500 rounded-lg transition">Annuler</a>
            </div>
        </form>
    </div>
</div>
@section('scripts')
<script>
document.getElementById('coverType')?.addEventListener('change', function() {
    const isInternal = this.value === 'internal';
    document.getElementById('coverInternal').style.display = isInternal ? 'block' : 'none';
    document.getElementById('coverExternal').style.display = isInternal ? 'none' : 'block';
});
document.getElementById('categorySelect')?.addEventListener('change', function() {
    document.getElementById('categoryNewWrap').style.display = this.value === '__new__' ? 'block' : 'none';
});
</script>
@endsection
@endsection
