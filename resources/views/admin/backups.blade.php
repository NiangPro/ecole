@extends('admin.layout')

@section('title', 'Sauvegardes')

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <h3 class="text-3xl font-bold">Gestion des Sauvegardes</h3>
    <form action="{{ route('admin.backups.create') }}" method="POST" class="flex gap-3">
        @csrf
        <button type="submit" class="btn-primary">
            <i class="fas fa-database mr-2"></i>Créer une sauvegarde
        </button>
    </form>
</div>

@if(session('success'))
    <div class="bg-green-500/10 border border-green-500/30 text-green-400 px-6 py-4 rounded-lg mb-6 flex items-center gap-3">
        <i class="fas fa-check-circle text-xl"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="bg-red-500/10 border border-red-500/30 text-red-400 px-6 py-4 rounded-lg mb-6 flex items-center gap-3">
        <i class="fas fa-exclamation-circle text-xl"></i>
        <span>{{ session('error') }}</span>
    </div>
@endif

<!-- Documentation -->
<div class="content-section mb-6">
    <h4 class="text-xl font-bold mb-4 text-cyan-400">
        <i class="fas fa-info-circle mr-2"></i>Comment utiliser les sauvegardes automatiques
    </h4>
    <div class="space-y-3 text-gray-300">
        <p><strong>1. Sauvegardes automatiques :</strong> Les sauvegardes sont créées automatiquement tous les jours à 2h du matin.</p>
        <p><strong>2. Pour activer les sauvegardes automatiques :</strong> Vous devez configurer le cron Laravel sur votre serveur :</p>
        <div class="bg-gray-900 p-4 rounded-lg border border-cyan-500/20 mt-3">
            <code class="text-cyan-400">* * * * * cd /chemin-vers-votre-projet && php artisan schedule:run >> /dev/null 2>&1</code>
        </div>
        <p class="mt-3"><strong>3. Création manuelle :</strong> Cliquez sur le bouton "Créer une sauvegarde" ci-dessus.</p>
        <p><strong>4. Téléchargement :</strong> Cliquez sur le bouton de téléchargement pour obtenir une sauvegarde.</p>
        <p><strong>5. Conservation :</strong> Les 10 dernières sauvegardes sont conservées automatiquement.</p>
    </div>
</div>

<!-- Liste des sauvegardes -->
<div class="content-section">
    <h4 class="text-xl font-bold mb-6">Liste des sauvegardes</h4>
    
    @if(count($backups) > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-700">
                    <th class="text-left py-4 px-4 font-semibold text-gray-300">Nom du fichier</th>
                    <th class="text-left py-4 px-4 font-semibold text-gray-300">Date de création</th>
                    <th class="text-left py-4 px-4 font-semibold text-gray-300">Taille</th>
                    <th class="text-right py-4 px-4 font-semibold text-gray-300">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($backups as $backup)
                <tr class="border-b border-gray-800 hover:bg-black/30 transition">
                    <td class="py-4 px-4">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-file-archive text-cyan-400 text-xl"></i>
                            <span class="font-mono text-sm">{{ $backup['filename'] }}</span>
                        </div>
                    </td>
                    <td class="py-4 px-4 text-gray-400">
                        <i class="fas fa-calendar mr-2"></i>{{ $backup['created_at'] }}
                    </td>
                    <td class="py-4 px-4 text-gray-400">
                        {{ number_format($backup['size'] / 1024 / 1024, 2) }} MB
                    </td>
                    <td class="py-4 px-4">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.backups.download', $backup['filename']) }}" 
                               class="px-4 py-2 bg-cyan-500/20 text-cyan-400 rounded-lg hover:bg-cyan-500/30 transition">
                                <i class="fas fa-download mr-2"></i>Télécharger
                            </a>
                            <form action="{{ route('admin.backups.delete', $backup['filename']) }}" method="POST" 
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette sauvegarde ?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500/30 transition">
                                    <i class="fas fa-trash mr-2"></i>Supprimer
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-12 text-gray-400">
        <i class="fas fa-database text-5xl mb-4 opacity-50"></i>
        <p>Aucune sauvegarde disponible</p>
        <p class="text-sm mt-2">Les sauvegardes seront créées automatiquement ou manuellement</p>
    </div>
    @endif
</div>
@endsection

