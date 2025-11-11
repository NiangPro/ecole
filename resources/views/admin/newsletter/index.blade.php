@extends('admin.layout')

@section('title', 'Gestion Newsletter - Admin')

@section('content')
<!-- Page Title -->
<div class="mb-8">
    <h3 class="text-3xl font-bold mb-2">
        <i class="fas fa-envelope-open-text text-blue-400 mr-3"></i>
        Gestion Newsletter
    </h3>
    <p class="text-gray-400">Gérez vos abonnés à la newsletter</p>
</div>
            <!-- Messages -->
            @if(session('success'))
            <div class="bg-green-500/20 border border-green-500 text-green-400 px-6 py-4 rounded-lg mb-6">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-500/20 border border-red-500 text-red-400 px-6 py-4 rounded-lg mb-6">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
            @endif

            <!-- Statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-br from-cyan-500/20 to-blue-500/20 border border-cyan-500/30 rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm mb-1">Total Abonnés</p>
                            <p class="text-3xl font-bold text-cyan-400">{{ $subscribers->total() }}</p>
                        </div>
                        <div class="w-16 h-16 bg-cyan-500/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-users text-3xl text-cyan-400"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500/20 to-emerald-500/20 border border-green-500/30 rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm mb-1">Actifs</p>
                            <p class="text-3xl font-bold text-green-400">{{ $totalSubscribers }}</p>
                        </div>
                        <div class="w-16 h-16 bg-green-500/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-circle text-3xl text-green-400"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-red-500/20 to-orange-500/20 border border-red-500/30 rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm mb-1">Inactifs</p>
                            <p class="text-3xl font-bold text-red-400">{{ $totalInactive }}</p>
                        </div>
                        <div class="w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-times-circle text-3xl text-red-400"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 mb-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-white">
                        <i class="fas fa-list mr-2"></i>Liste des abonnés
                    </h2>
                    <a href="{{ route('admin.newsletter.export') }}" class="px-6 py-3 bg-gradient-to-r from-cyan-500 to-blue-600 text-white font-bold rounded-lg hover:shadow-lg transition">
                        <i class="fas fa-download mr-2"></i>Exporter CSV
                    </a>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-900">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-400">Email</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-400">Date d'inscription</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-400">Statut</th>
                                <th class="px-6 py-4 text-right text-sm font-semibold text-gray-400">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @forelse($subscribers as $subscriber)
                            <tr class="hover:bg-gray-700/50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-cyan-500/20 rounded-full flex items-center justify-center">
                                            <i class="fas fa-envelope text-cyan-400"></i>
                                        </div>
                                        <span class="text-white">{{ $subscriber->email }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-400">
                                    {{ $subscriber->subscribed_at->format('d/m/Y à H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($subscriber->is_active)
                                        <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-sm">
                                            <i class="fas fa-check-circle mr-1"></i>Actif
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-red-500/20 text-red-400 rounded-full text-sm">
                                            <i class="fas fa-times-circle mr-1"></i>Inactif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Toggle Status -->
                                        <form action="{{ route('admin.newsletter.toggle', $subscriber->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 bg-blue-500/20 text-blue-400 rounded-lg hover:bg-blue-500/30 transition">
                                                <i class="fas fa-sync-alt mr-1"></i>
                                                {{ $subscriber->is_active ? 'Désactiver' : 'Activer' }}
                                            </button>
                                        </form>

                                        <!-- Delete -->
                                        <form action="{{ route('admin.newsletter.destroy', $subscriber->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet abonné ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-4 py-2 bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500/30 transition">
                                                <i class="fas fa-trash mr-1"></i>Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                    <i class="fas fa-inbox text-5xl mb-4 block"></i>
                                    Aucun abonné pour le moment
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($subscribers->hasPages())
                <div class="px-6 py-4 border-t border-gray-700">
                    {{ $subscribers->links() }}
                </div>
                @endif
            </div>
@endsection
