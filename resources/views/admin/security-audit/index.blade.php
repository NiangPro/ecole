@extends('admin.layout')

@section('title', 'Audit de Sécurité - NiangProgrammeur')

@section('styles')
<style>
    /* Styles pour la page Audit de Sécurité */
    .audit-page {
        min-height: 100vh;
        padding: 1.5rem;
        transition: background 0.3s ease;
    }
    
    .audit-page.dark-mode {
        background: linear-gradient(to bottom right, rgb(15, 23, 42), rgb(30, 41, 59), rgb(15, 23, 42));
    }
    
    body.light-mode .audit-page {
        background: linear-gradient(to bottom right, rgba(248, 250, 252, 1), rgba(241, 245, 249, 1), rgba(248, 250, 252, 1));
    }
    
    .audit-page h1 {
        color: transparent;
        background: linear-gradient(to right, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        background-clip: text;
    }
    
    .audit-page .text-gray-400 {
        color: rgba(156, 163, 175, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .audit-page .text-gray-400 {
        color: rgba(100, 116, 139, 1);
    }
    
    .audit-page .bg-slate-800\/50 {
        background: rgba(30, 41, 59, 0.5);
        transition: background 0.3s ease;
    }
    
    body.light-mode .audit-page .bg-slate-800\/50 {
        background: rgba(255, 255, 255, 0.8);
    }
    
    .audit-page .border-cyan-500\/20 {
        border-color: rgba(6, 182, 212, 0.2);
        transition: border-color 0.3s ease;
    }
    
    body.light-mode .audit-page .border-cyan-500\/20 {
        border-color: rgba(6, 182, 212, 0.3);
    }
    
    .audit-page .border-red-500\/20 {
        border-color: rgba(239, 68, 68, 0.2);
        transition: border-color 0.3s ease;
    }
    
    body.light-mode .audit-page .border-red-500\/20 {
        border-color: rgba(239, 68, 68, 0.3);
    }
    
    .audit-page .border-orange-500\/20 {
        border-color: rgba(249, 115, 22, 0.2);
        transition: border-color 0.3s ease;
    }
    
    body.light-mode .audit-page .border-orange-500\/20 {
        border-color: rgba(249, 115, 22, 0.3);
    }
    
    .audit-page .border-teal-500\/20 {
        border-color: rgba(20, 184, 166, 0.2);
        transition: border-color 0.3s ease;
    }
    
    body.light-mode .audit-page .border-teal-500\/20 {
        border-color: rgba(20, 184, 166, 0.3);
    }
    
    .audit-page .border-purple-500\/20 {
        border-color: rgba(168, 85, 247, 0.2);
        transition: border-color 0.3s ease;
    }
    
    body.light-mode .audit-page .border-purple-500\/20 {
        border-color: rgba(168, 85, 247, 0.3);
    }
    
    .audit-page .text-white {
        color: #fff;
        transition: color 0.3s ease;
    }
    
    body.light-mode .audit-page .text-white {
        color: #1e293b;
    }
    
    .audit-page .text-gray-300 {
        color: rgba(209, 213, 219, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .audit-page .text-gray-300 {
        color: rgba(30, 41, 59, 0.8);
    }
    
    .audit-page .bg-slate-700\/50 {
        background: rgba(51, 65, 85, 0.5);
        transition: background 0.3s ease;
    }
    
    body.light-mode .audit-page .bg-slate-700\/50 {
        background: rgba(241, 245, 249, 0.9);
    }
    
    .audit-page .bg-slate-700 {
        background: rgba(51, 65, 85, 1);
        transition: background 0.3s ease;
    }
    
    body.light-mode .audit-page .bg-slate-700 {
        background: rgba(226, 232, 240, 1);
    }
    
    .audit-page .divide-slate-700\/50 > * {
        border-color: rgba(51, 65, 85, 0.5);
        transition: border-color 0.3s ease;
    }
    
    body.light-mode .audit-page .divide-slate-700\/50 > * {
        border-color: rgba(226, 232, 240, 0.5);
    }
    
    .audit-page .hover\:bg-slate-700\/30:hover {
        background: rgba(51, 65, 85, 0.3);
    }
    
    body.light-mode .audit-page .hover\:bg-slate-700\/30:hover {
        background: rgba(6, 182, 212, 0.05);
    }
    
    .audit-page .border-slate-700\/50 {
        border-color: rgba(51, 65, 85, 0.5);
        transition: border-color 0.3s ease;
    }
    
    body.light-mode .audit-page .border-slate-700\/50 {
        border-color: rgba(226, 232, 240, 0.5);
    }
    
    .audit-page .text-red-400 {
        color: rgba(248, 113, 113, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .audit-page .text-red-400 {
        color: rgba(220, 38, 38, 1);
    }
    
    .audit-page .text-orange-400 {
        color: rgba(251, 146, 60, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .audit-page .text-orange-400 {
        color: rgba(234, 88, 12, 1);
    }
    
    .audit-page .text-teal-400 {
        color: rgba(45, 212, 191, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .audit-page .text-teal-400 {
        color: rgba(13, 148, 136, 1);
    }
    
    .audit-page .text-purple-400 {
        color: rgba(192, 132, 252, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .audit-page .text-purple-400 {
        color: rgba(147, 51, 234, 1);
    }
    
    .audit-page .text-cyan-400 {
        color: rgba(34, 211, 238, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .audit-page .text-cyan-400 {
        color: rgba(8, 145, 178, 1);
    }
    
    .audit-page .text-cyan-300 {
        color: rgba(103, 232, 249, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .audit-page .text-cyan-300 {
        color: rgba(14, 116, 144, 1);
    }
    
    .audit-page .bg-cyan-600 {
        background: rgba(8, 145, 178, 1);
        transition: background 0.3s ease;
    }
    
    .audit-page .bg-cyan-600:hover {
        background: rgba(14, 116, 144, 1);
    }
    
    .audit-page .bg-slate-700:hover {
        background: rgba(51, 65, 85, 0.8);
        transition: background 0.3s ease;
    }
    
    body.light-mode .audit-page .bg-slate-700:hover {
        background: rgba(203, 213, 225, 1);
    }
    
    .audit-page .bg-green-600 {
        background: rgba(22, 163, 74, 1);
        transition: background 0.3s ease;
    }
    
    .audit-page .bg-green-600:hover {
        background: rgba(20, 83, 45, 1);
    }
    
    .severity-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .severity-critical { background: #ef4444; color: white; }
    .severity-high { background: #f59e0b; color: white; }
    .severity-medium { background: #3b82f6; color: white; }
    .severity-low { background: #10b981; color: white; }
    
    /* Styles pour les inputs et selects en light mode */
    body.light-mode .audit-page select,
    body.light-mode .audit-page input[type="text"],
    body.light-mode .audit-page input[type="date"] {
        color: rgba(30, 41, 59, 0.9);
    }
    
    body.light-mode .audit-page select option {
        background: white;
        color: rgba(30, 41, 59, 0.9);
    }
</style>
@endsection

@section('content')
<div class="audit-page">
    <div class="mb-6">
        <h1 class="text-3xl font-bold mb-2">Audit de Sécurité</h1>
        <p class="text-gray-400">Surveillance et analyse des événements de sécurité</p>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-slate-800/50 p-4 rounded-lg border border-cyan-500/20">
            <div class="text-sm text-gray-400 mb-1">Total</div>
            <div class="text-2xl font-bold text-white">{{ number_format($stats['total']) }}</div>
        </div>
        <div class="bg-slate-800/50 p-4 rounded-lg border border-red-500/20">
            <div class="text-sm text-gray-400 mb-1">Critiques</div>
            <div class="text-2xl font-bold text-red-400">{{ number_format($stats['critical']) }}</div>
        </div>
        <div class="bg-slate-800/50 p-4 rounded-lg border border-orange-500/20">
            <div class="text-sm text-gray-400 mb-1">Élevées</div>
            <div class="text-2xl font-bold text-orange-400">{{ number_format($stats['high']) }}</div>
        </div>
        <div class="bg-slate-800/50 p-4 rounded-lg border border-teal-500/20">
            <div class="text-sm text-gray-400 mb-1">Aujourd'hui</div>
            <div class="text-2xl font-bold text-teal-400">{{ number_format($stats['today']) }}</div>
        </div>
        <div class="bg-slate-800/50 p-4 rounded-lg border border-purple-500/20">
            <div class="text-sm text-gray-400 mb-1">24h</div>
            <div class="text-2xl font-bold text-purple-400">{{ number_format($stats['last_24h']) }}</div>
        </div>
    </div>

    <!-- Filtres -->
    <form method="GET" action="{{ route('admin.security-audit.index') }}" class="mb-6 bg-slate-800/50 p-4 rounded-lg border border-cyan-500/20">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm text-gray-400 mb-2">Sévérité</label>
                <select name="severity" class="w-full bg-slate-700 border border-cyan-500/20 rounded px-3 py-2 text-white">
                    <option value="">Toutes</option>
                    <option value="critical" {{ request('severity') === 'critical' ? 'selected' : '' }}>Critique</option>
                    <option value="high" {{ request('severity') === 'high' ? 'selected' : '' }}>Élevée</option>
                    <option value="medium" {{ request('severity') === 'medium' ? 'selected' : '' }}>Moyenne</option>
                    <option value="low" {{ request('severity') === 'low' ? 'selected' : '' }}>Faible</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Type d'événement</label>
                <select name="event_type" class="w-full bg-slate-700 border border-cyan-500/20 rounded px-3 py-2 text-white">
                    <option value="">Tous</option>
                    @foreach(\App\Models\SecurityAudit::EVENT_TYPES as $key => $label)
                        <option value="{{ $key }}" {{ request('event_type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">IP</label>
                <input type="text" name="ip_address" value="{{ request('ip_address') }}" placeholder="Adresse IP" class="w-full bg-slate-700 border border-cyan-500/20 rounded px-3 py-2 text-white">
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Date début</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full bg-slate-700 border border-cyan-500/20 rounded px-3 py-2 text-white">
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Date fin</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full bg-slate-700 border border-cyan-500/20 rounded px-3 py-2 text-white">
            </div>
        </div>
        <div class="mt-4 flex gap-2">
            <button type="submit" class="px-4 py-2 bg-cyan-600 hover:bg-cyan-700 text-white rounded">Filtrer</button>
            <a href="{{ route('admin.security-audit.index') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded">Réinitialiser</a>
            <a href="{{ route('admin.security-audit.export', request()->all()) }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded">Exporter CSV</a>
        </div>
    </form>

    <!-- Top IPs suspectes -->
    @if($topIps->count() > 0)
    <div class="mb-6 bg-slate-800/50 p-4 rounded-lg border border-orange-500/20">
        <h3 class="text-lg font-semibold text-white mb-3">Top 10 IPs suspectes (7 derniers jours)</h3>
        <div class="space-y-2">
            @foreach($topIps as $ip)
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-300">{{ $ip->ip_address }}</span>
                <span class="text-orange-400 font-semibold">{{ $ip->count }} événements</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Liste des audits -->
    <div class="bg-slate-800/50 rounded-lg border border-cyan-500/20 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-700/50">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-300">Date</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-300">Type</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-300">Sévérité</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-300">IP</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-300">Utilisateur</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-300">Route</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-300">Message</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-300">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    @forelse($audits as $audit)
                    <tr class="hover:bg-slate-700/30">
                        <td class="px-4 py-3 text-sm text-gray-300">{{ $audit->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3 text-sm text-gray-300">{{ \App\Models\SecurityAudit::EVENT_TYPES[$audit->event_type] ?? $audit->event_type }}</td>
                        <td class="px-4 py-3">
                            <span class="severity-badge severity-{{ $audit->severity }}">{{ ucfirst($audit->severity) }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-300">{{ $audit->ip_address }}</td>
                        <td class="px-4 py-3 text-sm text-gray-300">{{ $audit->user ? $audit->user->email : 'Anonyme' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-300">{{ $audit->route ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-300">{{ \Illuminate\Support\Str::limit($audit->message, 50) }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.security-audit.show', $audit) }}" class="text-cyan-400 hover:text-cyan-300">Voir</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-400">Aucun audit trouvé</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-slate-700/50">
            {{ $audits->links() }}
        </div>
    </div>
</div>
@endsection
