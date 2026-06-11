@extends('admin.layout')

@section('title', 'Nouvelle épreuve')

@section('content')
<div class="epreuves-admin max-w-6xl">
    <div class="mb-8">
        <a href="{{ route('admin.epreuves.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-emerald-400 transition mb-3">
            <i class="fas fa-arrow-left"></i> Épreuves &amp; Corrigés
        </a>
        <h3 class="text-3xl font-extrabold mb-1 bg-gradient-to-r from-emerald-400 to-cyan-400 bg-clip-text text-transparent inline-block">
            Nouvelle épreuve
        </h3>
        <p class="text-gray-400">Déposez le PDF — l'examen, la matière, l'année et la série sont détectés automatiquement.</p>
    </div>

    <form method="POST" action="{{ route('admin.epreuves.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.epreuves._form')
    </form>
</div>
@endsection
