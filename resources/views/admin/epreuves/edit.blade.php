@extends('admin.layout')

@section('title', 'Modifier l\'épreuve')

@section('content')
<div class="epreuves-admin max-w-6xl">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <a href="{{ route('admin.epreuves.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-emerald-400 transition mb-3">
                <i class="fas fa-arrow-left"></i> Épreuves &amp; Corrigés
            </a>
            <h3 class="text-3xl font-extrabold mb-1 bg-gradient-to-r from-emerald-400 to-cyan-400 bg-clip-text text-transparent inline-block">
                Modifier l'épreuve
            </h3>
            <p class="text-gray-400">{{ Str::limit($epreuve->title, 80) }}</p>
        </div>
        <a href="{{ route('epreuves.show', $epreuve->slug) }}" target="_blank" rel="noopener" class="px-4 py-2 bg-emerald-500/15 hover:bg-emerald-500/25 text-emerald-400 rounded-xl border border-emerald-500/40 transition whitespace-nowrap">
            <i class="fas fa-eye mr-2"></i>Voir sur le site
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-500/15 border border-green-500/40 rounded-2xl text-green-400 flex items-center gap-2">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.epreuves.update', $epreuve) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.epreuves._form')
    </form>
</div>
@endsection
