@extends('admin.layout')

@section('title', 'Logs Admin - NiangProgrammeur')

@section('styles')
<style>
    /* Styles pour la page Logs */
    .logs-page {
        min-height: 100vh;
        padding: 1.5rem;
        transition: background 0.3s ease;
    }
    
    .logs-page.dark-mode {
        background: linear-gradient(to bottom right, rgb(15, 23, 42), rgb(30, 41, 59), rgb(15, 23, 42));
    }
    
    body.light-mode .logs-page {
        background: linear-gradient(to bottom right, rgba(248, 250, 252, 1), rgba(241, 245, 249, 1), rgba(248, 250, 252, 1));
    }
    
    .logs-page h1 {
        color: transparent;
        background: linear-gradient(to right, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        background-clip: text;
    }
    
    .logs-page .text-gray-400 {
        color: rgba(156, 163, 175, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .logs-page .text-gray-400 {
        color: rgba(100, 116, 139, 1);
    }
    
    .logs-page .bg-slate-800\/50 {
        background: rgba(30, 41, 59, 0.5);
        transition: background 0.3s ease;
    }
    
    body.light-mode .logs-page .bg-slate-800\/50 {
        background: rgba(255, 255, 255, 0.8);
    }
    
    .logs-page .border-cyan-500\/20 {
        border-color: rgba(6, 182, 212, 0.2);
        transition: border-color 0.3s ease;
    }
    
    body.light-mode .logs-page .border-cyan-500\/20 {
        border-color: rgba(6, 182, 212, 0.3);
    }
    
    .logs-page .border-teal-500\/20 {
        border-color: rgba(20, 184, 166, 0.2);
        transition: border-color 0.3s ease;
    }
    
    body.light-mode .logs-page .border-teal-500\/20 {
        border-color: rgba(20, 184, 166, 0.3);
    }
    
    .logs-page .border-purple-500\/20 {
        border-color: rgba(168, 85, 247, 0.2);
        transition: border-color 0.3s ease;
    }
    
    body.light-mode .logs-page .border-purple-500\/20 {
        border-color: rgba(168, 85, 247, 0.3);
    }
    
    .logs-page .border-pink-500\/20 {
        border-color: rgba(236, 72, 153, 0.2);
        transition: border-color 0.3s ease;
    }
    
    body.light-mode .logs-page .border-pink-500\/20 {
        border-color: rgba(236, 72, 153, 0.3);
    }
    
    .logs-page .text-white {
        color: #fff;
        transition: color 0.3s ease;
    }
    
    body.light-mode .logs-page .text-white {
        color: #1e293b;
    }
    
    .logs-page .text-gray-300 {
        color: rgba(209, 213, 219, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .logs-page .text-gray-300 {
        color: rgba(30, 41, 59, 0.8);
    }
    
    .logs-page .bg-slate-700\/50 {
        background: rgba(51, 65, 85, 0.5);
        transition: background 0.3s ease;
    }
    
    body.light-mode .logs-page .bg-slate-700\/50 {
        background: rgba(241, 245, 249, 0.9);
    }
    
    .logs-page .divide-slate-700\/50 > * {
        border-color: rgba(51, 65, 85, 0.5);
        transition: border-color 0.3s ease;
    }
    
    body.light-mode .logs-page .divide-slate-700\/50 > * {
        border-color: rgba(226, 232, 240, 0.5);
    }
    
    .logs-page .hover\:bg-slate-700\/30:hover {
        background: rgba(51, 65, 85, 0.3);
    }
    
    body.light-mode .logs-page .hover\:bg-slate-700\/30:hover {
        background: rgba(6, 182, 212, 0.05);
    }
    
    .logs-page .bg-gray-600 {
        background: rgba(75, 85, 99, 1);
        transition: background 0.3s ease;
    }
    
    body.light-mode .logs-page .bg-gray-600 {
        background: rgba(148, 163, 184, 1);
    }
    
    .logs-page .text-gray-500 {
        color: rgba(107, 114, 128, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .logs-page .text-gray-500 {
        color: rgba(148, 163, 184, 1);
    }
    
    .logs-page .border-slate-700\/50 {
        border-color: rgba(51, 65, 85, 0.5);
        transition: border-color 0.3s ease;
    }
    
    body.light-mode .logs-page .border-slate-700\/50 {
        border-color: rgba(226, 232, 240, 0.5);
    }
</style>
@endsection

@section('content')
<div class="logs-page dark-mode">
<div class="max-w-7xl mx-auto">
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
</div>
@endsection

