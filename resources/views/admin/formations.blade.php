@extends('admin.layout')

@section('content')
<h3 class="text-3xl font-bold mb-8">Gestion des Formations</h3>

<div class="content-section">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h4 class="text-xl font-bold">Liste des formations</h4>
        <button class="btn-primary w-full sm:w-auto">
            <i class="fas fa-plus mr-2"></i>Ajouter une formation
        </button>
    </div>
    
    <div class="space-y-4">
        @foreach(['HTML5', 'CSS3', 'JavaScript', 'PHP', 'Bootstrap', 'Git', 'WordPress', 'IA'] as $formation)
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 bg-black/30 rounded-lg gap-4">
            <div class="flex items-center gap-4">
                <i class="fas fa-code text-cyan-400 text-2xl"></i>
                <div>
                    <p class="font-semibold text-lg">{{ $formation }}</p>
                    <p class="text-sm text-gray-400">Formation complète</p>
                </div>
            </div>
            <div class="flex gap-3 w-full sm:w-auto">
                <button class="flex-1 sm:flex-none px-4 py-2 bg-cyan-500/20 text-cyan-400 rounded-lg hover:bg-cyan-500/30 transition">
                    <i class="fas fa-edit mr-2"></i>Modifier
                </button>
                <button class="flex-1 sm:flex-none px-4 py-2 bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500/30 transition">
                    <i class="fas fa-trash mr-2"></i>Supprimer
                </button>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
