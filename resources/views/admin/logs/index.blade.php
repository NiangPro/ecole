@extends('admin.layout')

@section('title', 'Logs Admin - NiangProgrammeur')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-black mb-4 bg-gradient-to-r from-cyan-400 to-teal-500 bg-clip-text text-transparent">
                <i class="fas fa-history mr-3"></i>Logs d'Administration
            </h1>
            <p class="text-gray-400">Historique de toutes les actions effectuées dans l'administration</p>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-slate-800/50 backdrop-blur-xl border border-cyan-500/20 rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Total</p>
                        <p class="text-3xl font-bold text-white">{{ number_format($stats['total']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-cyan-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-list text-cyan-400 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-slate-800/50 backdrop-blur-xl border border-teal-500/20 rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Aujourd'hui</p>
                        <p class="text-3xl font-bold text-white">{{ number_format($stats['today']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-teal-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-calendar-day text-teal-400 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-slate-800/50 backdrop-blur-xl border border-purple-500/20 rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Cette semaine</p>
                        <p class="text-3xl font-bold text-white">{{ number_format($stats['this_week']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-calendar-week text-purple-400 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-slate-800/50 backdrop-blur-xl border border-pink-500/20 rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Ce mois</p>
                        <p class="text-3xl font-bold text-white">{{ number_format($stats['this_month']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-pink-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-pink-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-slate-800/50 backdrop-blur-xl border border-cyan-500/20 rounded-xl p-6 mb-6">
            <form action="{{ route('admin.logs.index') }}" method="GET" class="space-y-4">
                <div class="flex flex-wrap gap-3">
                    <input type="text" name="search" value="{{ $search }}" 
                           placeholder="Rechercher dans les logs..." 
                           class="input-admin flex-1 min-w-[200px]">
                    <select name="action" class="input-admin" style="min-width: 150px;">
                        <option value="">Toutes les actions</option>
                        @foreach($actions as $actionOption)
                        <option value="{{ $actionOption }}" {{ $action == $actionOption ? 'selected' : '' }}>
                            {{ ucfirst($actionOption) }}
                        </option>
                        @endforeach
                    </select>
                    <select name="sort" class="input-admin" style="min-width: 150px;">
                        <option value="created_at" {{ $sortBy == 'created_at' ? 'selected' : '' }}>Date</option>
                        <option value="action" {{ $sortBy == 'action' ? 'selected' : '' }}>Action</option>
                    </select>
                    <select name="order" class="input-admin" style="min-width: 120px;">
                        <option value="desc" {{ $sortOrder == 'desc' ? 'selected' : '' }}>Décroissant</option>
                        <option value="asc" {{ $sortOrder == 'asc' ? 'selected' : '' }}>Croissant</option>
                    </select>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-search mr-2"></i>Rechercher
                    </button>
                    @if($search || $action)
                    <a href="{{ route('admin.logs.index') }}" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 rounded-lg font-semibold transition">
                        <i class="fas fa-times mr-2"></i>Réinitialiser
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Liste des logs -->
        <div class="bg-slate-800/50 backdrop-blur-xl border border-cyan-500/20 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-700/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-300">Action</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-300">Description</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-300">Modèle</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-300">IP</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-300">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/50">
                        @forelse($logs as $log)
                        <tr class="hover:bg-slate-700/30 transition">
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $log->action === 'create' ? 'bg-green-500/20 text-green-400' : '' }}
                                    {{ $log->action === 'update' ? 'bg-blue-500/20 text-blue-400' : '' }}
                                    {{ $log->action === 'delete' ? 'bg-red-500/20 text-red-400' : '' }}
                                    {{ $log->action === 'approve' ? 'bg-teal-500/20 text-teal-400' : '' }}
                                    {{ $log->action === 'reject' ? 'bg-yellow-500/20 text-yellow-400' : '' }}
                                    {{ !in_array($log->action, ['create', 'update', 'delete', 'approve', 'reject']) ? 'bg-gray-500/20 text-gray-400' : '' }}">
                                    {{ ucfirst($log->action) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-300">{{ $log->description }}</td>
                            <td class="px-6 py-4 text-sm text-gray-400">
                                @if($log->model_type)
                                {{ class_basename($log->model_type) }}
                                @if($log->model_id)
                                <span class="text-gray-500">#{{ $log->model_id }}</span>
                                @endif
                                @else
                                <span class="text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-400">{{ $log->ip_address }}</td>
                            <td class="px-6 py-4 text-sm text-gray-400">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                <i class="fas fa-inbox text-4xl mb-4 opacity-50"></i>
                                <p>Aucun log trouvé</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($logs->hasPages())
            <div class="px-6 py-4 border-t border-slate-700/50">
                {{ $logs->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

