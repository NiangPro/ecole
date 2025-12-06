@extends('admin.layout')

@section('title', 'Détails Audit de Sécurité - NiangProgrammeur')

@section('styles')
<style>
    .audit-detail-page {
        min-height: 100vh;
        padding: 1.5rem;
        transition: background 0.3s ease;
    }
    
    .audit-detail-page.dark-mode {
        background: linear-gradient(to bottom right, rgb(15, 23, 42), rgb(30, 41, 59), rgb(15, 23, 42));
    }
    
    body.light-mode .audit-detail-page {
        background: linear-gradient(to bottom right, rgba(248, 250, 252, 1), rgba(241, 245, 249, 1), rgba(248, 250, 252, 1));
    }
    
    .audit-detail-page h1 {
        color: transparent;
        background: linear-gradient(to right, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        background-clip: text;
    }
    
    .audit-detail-page .text-cyan-400 {
        color: rgba(34, 211, 238, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .audit-detail-page .text-cyan-400 {
        color: rgba(8, 145, 178, 1);
    }
    
    .audit-detail-page .text-cyan-300 {
        color: rgba(103, 232, 249, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .audit-detail-page .text-cyan-300 {
        color: rgba(14, 116, 144, 1);
    }
    
    .audit-detail-page .bg-slate-800\/50 {
        background: rgba(30, 41, 59, 0.5);
        transition: background 0.3s ease;
    }
    
    body.light-mode .audit-detail-page .bg-slate-800\/50 {
        background: rgba(255, 255, 255, 0.8);
    }
    
    .audit-detail-page .border-cyan-500\/20 {
        border-color: rgba(6, 182, 212, 0.2);
        transition: border-color 0.3s ease;
    }
    
    body.light-mode .audit-detail-page .border-cyan-500\/20 {
        border-color: rgba(6, 182, 212, 0.3);
    }
    
    .audit-detail-page .text-white {
        color: #fff;
        transition: color 0.3s ease;
    }
    
    body.light-mode .audit-detail-page .text-white {
        color: #1e293b;
    }
    
    .audit-detail-page .text-gray-300 {
        color: rgba(209, 213, 219, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .audit-detail-page .text-gray-300 {
        color: rgba(30, 41, 59, 0.8);
    }
    
    .audit-detail-page .text-gray-400 {
        color: rgba(156, 163, 175, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .audit-detail-page .text-gray-400 {
        color: rgba(100, 116, 139, 1);
    }
    
    .audit-detail-page .bg-slate-900 {
        background: rgba(15, 23, 42, 1);
        transition: background 0.3s ease;
    }
    
    body.light-mode .audit-detail-page .bg-slate-900 {
        background: rgba(241, 245, 249, 1);
    }
    
    .severity-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }
    
    .severity-critical { background: #ef4444; color: white; }
    .severity-high { background: #f59e0b; color: white; }
    .severity-medium { background: #3b82f6; color: white; }
    .severity-low { background: #10b981; color: white; }
</style>
@endsection

@section('content')
<div class="audit-detail-page p-6">
    <div class="mb-6">
        <a href="{{ route('admin.security-audit.index') }}" class="text-cyan-400 hover:text-cyan-300 mb-4 inline-block">← Retour à la liste</a>
        <h1 class="text-3xl font-bold mb-2">Détails de l'Audit #{{ $audit->id }}</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Informations principales -->
        <div class="bg-slate-800/50 p-6 rounded-lg border border-cyan-500/20">
            <h2 class="text-xl font-semibold text-white mb-4">Informations</h2>
            <dl class="space-y-3">
                <div>
                    <dt class="text-sm text-gray-400">Type d'événement</dt>
                    <dd class="text-white font-semibold">{{ \App\Models\SecurityAudit::EVENT_TYPES[$audit->event_type] ?? $audit->event_type }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-400">Sévérité</dt>
                    <dd>
                        <span class="severity-badge severity-{{ $audit->severity }}">{{ ucfirst($audit->severity) }}</span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-400">Date</dt>
                    <dd class="text-white">{{ $audit->created_at->format('d/m/Y H:i:s') }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-400">IP</dt>
                    <dd class="text-white font-mono">{{ $audit->ip_address }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-400">Utilisateur</dt>
                    <dd class="text-white">{{ $audit->user ? $audit->user->email : 'Anonyme' }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-400">Route</dt>
                    <dd class="text-white font-mono">{{ $audit->route ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-400">Méthode HTTP</dt>
                    <dd class="text-white">{{ $audit->method ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-400">Code de réponse</dt>
                    <dd class="text-white">{{ $audit->response_code ?? '-' }}</dd>
                </div>
            </dl>
        </div>

        <!-- Message et métadonnées -->
        <div class="bg-slate-800/50 p-6 rounded-lg border border-cyan-500/20">
            <h2 class="text-xl font-semibold text-white mb-4">Message</h2>
            <p class="text-gray-300 mb-6">{{ $audit->message }}</p>

            @if($audit->metadata)
            <h3 class="text-lg font-semibold text-white mb-3">Métadonnées</h3>
            <pre class="bg-slate-900 p-4 rounded text-sm text-gray-300 overflow-x-auto">{{ json_encode($audit->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            @endif
        </div>
    </div>

    <!-- User Agent -->
    @if($audit->user_agent)
    <div class="mt-6 bg-slate-800/50 p-6 rounded-lg border border-cyan-500/20">
        <h2 class="text-xl font-semibold text-white mb-4">User Agent</h2>
        <p class="text-gray-300 font-mono text-sm">{{ $audit->user_agent }}</p>
    </div>
    @endif

    <!-- Données de requête -->
    @if($audit->request_data)
    <div class="mt-6 bg-slate-800/50 p-6 rounded-lg border border-cyan-500/20">
        <h2 class="text-xl font-semibold text-white mb-4">Données de Requête</h2>
        <pre class="bg-slate-900 p-4 rounded text-sm text-gray-300 overflow-x-auto">{{ json_encode($audit->request_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
    </div>
    @endif
</div>
@endsection
