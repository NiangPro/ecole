@extends('admin.layout')

@section('title', 'Monitoring Téléchargements — Admin')

@push('styles')
<style>
/* ── Variables page ── */
:root {
    --c-cyan:    #06b6d4;
    --c-teal:    #14b8a6;
    --c-red:     #ef4444;
    --c-amber:   #f59e0b;
    --c-green:   #22c55e;
    --c-purple:  #a855f7;
    --c-surface: rgba(255,255,255,0.04);
    --c-border:  rgba(255,255,255,0.08);
    --c-border-accent: rgba(6,182,212,0.25);
    --c-text:    rgba(255,255,255,0.90);
    --c-muted:   rgba(255,255,255,0.45);
    --radius:    16px;
    --radius-sm: 10px;
    --blur:      blur(24px) saturate(160%);
}

body.light-mode {
    --c-surface: rgba(255,255,255,0.80);
    --c-border:  rgba(0,0,0,0.08);
    --c-border-accent: rgba(6,182,212,0.30);
    --c-text:    #0f172a;
    --c-muted:   #64748b;
}

/* ── Page wrapper ── */
.dm-page { animation: dm-fadein .45s ease; }
@keyframes dm-fadein { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:none; } }

/* ── Page header ── */
.dm-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 2rem;
}
.dm-title {
    font-size: clamp(1.35rem, 2.5vw, 1.75rem);
    font-weight: 800;
    background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 50%, #a855f7 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    letter-spacing: -.02em;
    line-height: 1.2;
}
.dm-subtitle {
    font-size: .8rem;
    color: var(--c-muted);
    margin-top: 2px;
    font-weight: 400;
    -webkit-text-fill-color: initial;
}
.dm-live-dot {
    display: inline-block;
    width: 8px; height: 8px;
    background: var(--c-green);
    border-radius: 50%;
    margin-right: 6px;
    box-shadow: 0 0 0 0 rgba(34,197,94,.6);
    animation: dm-ping 1.6s ease-in-out infinite;
    vertical-align: middle;
}
@keyframes dm-ping {
    0%   { box-shadow: 0 0 0 0 rgba(34,197,94,.6); }
    70%  { box-shadow: 0 0 0 8px rgba(34,197,94,0); }
    100% { box-shadow: 0 0 0 0 rgba(34,197,94,0); }
}

/* ── Controls toolbar ── */
.dm-toolbar {
    display: flex;
    align-items: center;
    gap: .75rem;
    flex-wrap: wrap;
}

/* Segmented time picker */
.dm-seg {
    display: flex;
    background: var(--c-surface);
    border: 1px solid var(--c-border);
    border-radius: 999px;
    padding: 3px;
    backdrop-filter: var(--blur);
}
.dm-seg a {
    padding: 6px 16px;
    border-radius: 999px;
    font-size: .78rem;
    font-weight: 600;
    color: var(--c-muted);
    text-decoration: none;
    transition: all .2s ease;
    white-space: nowrap;
}
.dm-seg a:hover { color: var(--c-cyan); }
.dm-seg a.active {
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    color: #000;
    box-shadow: 0 2px 12px rgba(6,182,212,.45);
}

/* Export button */
.dm-btn-export {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    padding: 8px 18px;
    border-radius: 999px;
    background: rgba(6,182,212,.12);
    border: 1px solid var(--c-border-accent);
    color: var(--c-cyan);
    font-size: .8rem;
    font-weight: 600;
    text-decoration: none;
    transition: all .2s ease;
    backdrop-filter: var(--blur);
}
.dm-btn-export:hover {
    background: rgba(6,182,212,.22);
    box-shadow: 0 0 20px rgba(6,182,212,.25);
}

/* ── KPI CARDS ── */
.dm-kpi-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 2rem;
}
@media (max-width: 1200px) { .dm-kpi-grid { grid-template-columns: repeat(2,1fr); } }
@media (max-width: 640px)  { .dm-kpi-grid { grid-template-columns: 1fr; } }

.dm-kpi {
    position: relative;
    padding: 1.5rem;
    border-radius: var(--radius);
    background: var(--c-surface);
    border: 1px solid var(--c-border);
    backdrop-filter: var(--blur);
    overflow: hidden;
    transition: transform .2s ease, box-shadow .2s ease;
}
.dm-kpi:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 40px -8px var(--kpi-glow, rgba(6,182,212,.3));
}
.dm-kpi::before {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: inherit;
    background: linear-gradient(135deg, var(--kpi-a, #06b6d4) 0%, var(--kpi-b, #14b8a6) 100%);
    opacity: .06;
}
.dm-kpi::after {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--kpi-a, #06b6d4), transparent);
    border-radius: inherit;
}
.dm-kpi--total  { --kpi-a:#06b6d4; --kpi-b:#14b8a6; --kpi-glow:rgba(6,182,212,.3); }
.dm-kpi--block  { --kpi-a:#ef4444; --kpi-b:#dc2626; --kpi-glow:rgba(239,68,68,.25); }
.dm-kpi--susp   { --kpi-a:#f59e0b; --kpi-b:#d97706; --kpi-glow:rgba(245,158,11,.25); }
.dm-kpi--ok     { --kpi-a:#22c55e; --kpi-b:#16a34a; --kpi-glow:rgba(34,197,94,.25); }

.dm-kpi-icon {
    width: 44px; height: 44px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--kpi-a), var(--kpi-b));
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem;
    color: #fff;
    margin-bottom: 1.1rem;
    box-shadow: 0 4px 16px -4px var(--kpi-glow);
}
.dm-kpi-val {
    font-size: clamp(2rem, 3vw, 2.6rem);
    font-weight: 900;
    color: var(--c-text);
    line-height: 1;
    letter-spacing: -.03em;
    position: relative;
}
.dm-kpi-label {
    font-size: .75rem;
    font-weight: 600;
    color: var(--kpi-a);
    text-transform: uppercase;
    letter-spacing: .08em;
    margin-bottom: .35rem;
}
.dm-kpi-sub {
    font-size: .78rem;
    color: var(--c-muted);
    margin-top: .4rem;
}
.dm-kpi-badge {
    position: absolute;
    top: 1.25rem; right: 1.25rem;
    padding: 3px 10px;
    border-radius: 999px;
    font-size: .68rem;
    font-weight: 700;
    background: linear-gradient(135deg, var(--kpi-a), var(--kpi-b));
    color: #fff;
    opacity: .9;
}

/* ── TABS ── */
.dm-tabs {
    display: flex;
    gap: .5rem;
    margin-bottom: 1.75rem;
    border-bottom: 1px solid var(--c-border);
    padding-bottom: 1px;
}
.dm-tab {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    padding: 10px 20px;
    border-radius: 10px 10px 0 0;
    font-size: .85rem;
    font-weight: 600;
    color: var(--c-muted);
    text-decoration: none;
    border: 1px solid transparent;
    border-bottom: none;
    transition: all .2s ease;
    position: relative;
    bottom: -1px;
}
.dm-tab:hover { color: var(--c-cyan); }
.dm-tab.active {
    color: var(--c-cyan);
    background: var(--c-surface);
    border-color: var(--c-border);
    border-bottom-color: transparent;
    backdrop-filter: var(--blur);
}
.dm-tab-count {
    font-size: .68rem;
    padding: 2px 7px;
    border-radius: 999px;
    background: rgba(6,182,212,.15);
    color: var(--c-cyan);
    font-weight: 700;
}
.dm-tab-count.danger { background: rgba(239,68,68,.15); color:#ef4444; }

/* ── CARD ── */
.dm-card {
    background: var(--c-surface);
    border: 1px solid var(--c-border);
    border-radius: var(--radius);
    backdrop-filter: var(--blur);
    overflow: hidden;
}
.dm-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.1rem 1.5rem;
    border-bottom: 1px solid var(--c-border);
}
.dm-card-title {
    font-size: .9rem;
    font-weight: 700;
    color: var(--c-text);
    display: flex;
    align-items: center;
    gap: .6rem;
}
.dm-card-title i { color: var(--c-cyan); font-size: .85rem; }
.dm-card-body { padding: 1.25rem 1.5rem; }

/* ── LAYOUT GRID ── */
.dm-grid-8-4  { display: grid; grid-template-columns: 2fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem; }
.dm-grid-6-6  { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
@media (max-width: 1024px) { .dm-grid-8-4, .dm-grid-6-6 { grid-template-columns: 1fr; } }

/* Chart area */
.dm-chart-wrap { position: relative; height: 280px; padding: 1.25rem 1.5rem; }

/* ── TABLES ── */
.dm-table-wrap { overflow-x: auto; }
.dm-table {
    width: 100%;
    border-collapse: collapse;
    font-size: .82rem;
}
.dm-table th {
    padding: .65rem 1.2rem;
    font-size: .7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: var(--c-muted);
    background: rgba(255,255,255,.025);
    white-space: nowrap;
}
.dm-table td {
    padding: .75rem 1.2rem;
    color: var(--c-text);
    border-top: 1px solid var(--c-border);
    vertical-align: middle;
}
.dm-table tbody tr { transition: background .15s ease; }
.dm-table tbody tr:hover { background: rgba(6,182,212,.04); }
.dm-table tbody tr.is-blocked { background: rgba(239,68,68,.04); }
.dm-table tbody tr.is-suspicious { background: rgba(245,158,11,.04); }

/* Progress bar */
.dm-progress-wrap { display: flex; align-items: center; gap: .6rem; }
.dm-progress {
    flex: 1;
    height: 5px;
    background: rgba(255,255,255,.07);
    border-radius: 999px;
    overflow: hidden;
    min-width: 60px;
}
.dm-progress-bar {
    height: 100%;
    border-radius: 999px;
    background: linear-gradient(90deg, #ef4444, #dc2626);
    transition: width .6s cubic-bezier(.4,0,.2,1);
}
.dm-progress-pct {
    font-size: .72rem;
    font-weight: 700;
    color: var(--c-muted);
    min-width: 30px;
    text-align: right;
}

/* Badges */
.dm-badge {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    padding: 3px 9px;
    border-radius: 999px;
    font-size: .72rem;
    font-weight: 700;
}
.dm-badge--ok     { background:rgba(34,197,94,.12);  color:#22c55e; }
.dm-badge--block  { background:rgba(239,68,68,.12);  color:#ef4444; }
.dm-badge--susp   { background:rgba(245,158,11,.12); color:#f59e0b; }
.dm-badge--cyan   { background:rgba(6,182,212,.12);  color:#06b6d4; }
.dm-badge--gray   { background:rgba(255,255,255,.08);color:var(--c-muted); }

code.dm-code {
    background: rgba(6,182,212,.1);
    color: var(--c-cyan);
    padding: 2px 8px;
    border-radius: 6px;
    font-family: 'JetBrains Mono', 'Fira Code', monospace;
    font-size: .75rem;
}
code.dm-code.danger { background:rgba(239,68,68,.1); color:#ef4444; }

/* Block reasons list */
.dm-reason-row {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: .65rem 0;
    border-bottom: 1px solid var(--c-border);
}
.dm-reason-row:last-child { border-bottom: none; }
.dm-reason-label { flex: 1; font-size: .8rem; color: var(--c-text); }
.dm-reason-bar-wrap {
    width: 80px;
    height: 4px;
    background: rgba(255,255,255,.07);
    border-radius: 999px;
    overflow: hidden;
}
.dm-reason-bar { height: 100%; border-radius: 999px; background: linear-gradient(90deg,#ef4444,#f59e0b); }
.dm-reason-count {
    font-size: .75rem;
    font-weight: 700;
    color: var(--c-red);
    min-width: 28px;
    text-align: right;
}

/* ── Suspicious IPs ── */
.dm-threat-header {
    display: flex;
    align-items: center;
    gap: .75rem;
    margin-bottom: 1.5rem;
}
.dm-threat-level {
    display: flex;
    align-items: center;
    gap: .4rem;
    padding: 6px 14px;
    border-radius: 999px;
    font-size: .75rem;
    font-weight: 700;
    border: 1px solid;
}
.dm-threat-level.low    { color:#22c55e; border-color:rgba(34,197,94,.3);  background:rgba(34,197,94,.08); }
.dm-threat-level.medium { color:#f59e0b; border-color:rgba(245,158,11,.3); background:rgba(245,158,11,.08); }
.dm-threat-level.high   { color:#ef4444; border-color:rgba(239,68,68,.3);  background:rgba(239,68,68,.08); }

/* Unblock button */
.dm-btn-unblock {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    padding: 5px 12px;
    border-radius: 8px;
    font-size: .75rem;
    font-weight: 600;
    background: rgba(34,197,94,.1);
    border: 1px solid rgba(34,197,94,.25);
    color: #22c55e;
    cursor: pointer;
    transition: all .2s ease;
}
.dm-btn-unblock:hover {
    background: rgba(34,197,94,.2);
    box-shadow: 0 0 14px rgba(34,197,94,.25);
}

/* Clear logs form */
.dm-clear-form {
    display: flex;
    align-items: center;
    gap: .6rem;
    flex-wrap: wrap;
}
.dm-select {
    padding: 6px 12px;
    border-radius: 8px;
    background: rgba(255,255,255,.06);
    border: 1px solid var(--c-border);
    color: var(--c-text);
    font-size: .78rem;
    cursor: pointer;
    outline: none;
    transition: border-color .2s;
}
.dm-select:focus { border-color: var(--c-cyan); }
body.light-mode .dm-select { background: #fff; color: #0f172a; }
.dm-btn-danger {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: 6px 14px;
    border-radius: 8px;
    font-size: .78rem;
    font-weight: 600;
    background: rgba(239,68,68,.1);
    border: 1px solid rgba(239,68,68,.25);
    color: #ef4444;
    cursor: pointer;
    transition: all .2s ease;
}
.dm-btn-danger:hover { background: rgba(239,68,68,.2); }

/* Timestamp pill */
.dm-ts {
    font-size: .72rem;
    color: var(--c-muted);
    font-variant-numeric: tabular-nums;
}
.dm-empty {
    text-align: center;
    padding: 3rem 1rem;
    color: var(--c-muted);
    font-size: .85rem;
}
.dm-empty i { font-size: 2rem; display: block; margin-bottom: .75rem; opacity: .3; }

/* light-mode overrides */
body.light-mode .dm-card { background: rgba(255,255,255,.85); border-color: rgba(0,0,0,.08); }
body.light-mode .dm-table th { background: rgba(0,0,0,.03); }
body.light-mode .dm-table tbody tr:hover { background: rgba(6,182,212,.06); }
body.light-mode .dm-progress { background: rgba(0,0,0,.08); }
body.light-mode .dm-reason-bar-wrap { background: rgba(0,0,0,.08); }
body.light-mode code.dm-code { background: rgba(6,182,212,.08); }
body.light-mode .dm-kpi { background: rgba(255,255,255,.85); }
body.light-mode .dm-seg { background: rgba(0,0,0,.04); border-color: rgba(0,0,0,.1); }
body.light-mode .dm-tab.active { background: rgba(255,255,255,.85); border-color: rgba(0,0,0,.08); }
</style>
@endpush

@section('content')
@php
    $activeTab = request('tab', 'overview');
    $activeHours = (int) request('hours', 24);
    $maxBlock = $blockReasons->max('count') ?: 1;
    $threatLevel = $totalBlocked == 0 ? 'low' : ($blockRate > 20 ? 'high' : 'medium');
    $threatLabel  = $threatLevel === 'low' ? 'Faible' : ($threatLevel === 'high' ? 'Élevée' : 'Modérée');
    $threatIcon   = $threatLevel === 'low' ? 'fa-shield-alt' : ($threatLevel === 'high' ? 'fa-skull-crossbones' : 'fa-exclamation-triangle');
@endphp

<div class="dm-page">

    {{-- ── HEADER ── --}}
    <div class="dm-header">
        <div>
            <div class="dm-title">
                <span class="dm-live-dot"></span>
                Monitoring Téléchargements
            </div>
            <div class="dm-subtitle">
                Analyse en temps réel · {{ now()->format('d/m/Y H:i') }}
            </div>
        </div>
        <div class="dm-toolbar">
            <div class="dm-seg">
                @foreach([24=>'24h', 48=>'48h', 168=>'7j', 720=>'30j'] as $h => $label)
                    <a href="?hours={{ $h }}&tab={{ $activeTab }}"
                       class="{{ $activeHours == $h ? 'active' : '' }}">{{ $label }}</a>
                @endforeach
            </div>
            <a href="{{ route('admin.downloads.export', ['hours' => $activeHours]) }}" class="dm-btn-export">
                <i class="fas fa-file-csv"></i> Exporter CSV
            </a>
        </div>
    </div>

    {{-- ── KPI CARDS ── --}}
    <div class="dm-kpi-grid">
        {{-- Total --}}
        <div class="dm-kpi dm-kpi--total">
            <span class="dm-kpi-badge">{{ $activeHours }}h</span>
            <div class="dm-kpi-icon"><i class="fas fa-download"></i></div>
            <div class="dm-kpi-label">Téléchargements</div>
            <div class="dm-kpi-val" data-count="{{ $totalDownloads }}">{{ number_format($totalDownloads) }}</div>
            <div class="dm-kpi-sub">Requêtes reçues</div>
        </div>
        {{-- Bloqués --}}
        <div class="dm-kpi dm-kpi--block">
            <span class="dm-kpi-badge">{{ $blockRate }}%</span>
            <div class="dm-kpi-icon"><i class="fas fa-ban"></i></div>
            <div class="dm-kpi-label">Bloqués</div>
            <div class="dm-kpi-val" data-count="{{ $totalBlocked }}">{{ number_format($totalBlocked) }}</div>
            <div class="dm-kpi-sub">Accès refusés</div>
        </div>
        {{-- Suspects --}}
        <div class="dm-kpi dm-kpi--susp">
            <div class="dm-kpi-icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="dm-kpi-label">Suspects</div>
            <div class="dm-kpi-val" data-count="{{ $totalSuspicious }}">{{ number_format($totalSuspicious) }}</div>
            <div class="dm-kpi-sub">Activités anormales</div>
        </div>
        {{-- OK --}}
        <div class="dm-kpi dm-kpi--ok">
            <div class="dm-kpi-icon"><i class="fas fa-check-circle"></i></div>
            <div class="dm-kpi-label">Autorisés</div>
            <div class="dm-kpi-val" data-count="{{ $totalDownloads - $totalBlocked }}">{{ number_format($totalDownloads - $totalBlocked) }}</div>
            <div class="dm-kpi-sub">Téléchargements légitimes</div>
        </div>
    </div>

    {{-- ── TABS ── --}}
    <div class="dm-tabs">
        <a href="?tab=overview&hours={{ $activeHours }}"
           class="dm-tab {{ $activeTab == 'overview' ? 'active' : '' }}">
            <i class="fas fa-chart-area"></i> Vue d'ensemble
        </a>
        <a href="?tab=suspicious&hours={{ $activeHours }}"
           class="dm-tab {{ $activeTab == 'suspicious' ? 'active' : '' }}">
            <i class="fas fa-user-secret"></i> IPs Suspectes
            @if($suspiciousIps->count() > 0)
                <span class="dm-tab-count danger">{{ $suspiciousIps->count() }}</span>
            @endif
        </a>
        <a href="?tab=logs&hours={{ $activeHours }}"
           class="dm-tab {{ $activeTab == 'logs' ? 'active' : '' }}">
            <i class="fas fa-stream"></i> Logs récents
            <span class="dm-tab-count">50</span>
        </a>
    </div>

    {{-- ═══════════════ TAB : OVERVIEW ═══════════════ --}}
    @if($activeTab == 'overview')

    <div class="dm-grid-8-4">
        {{-- Chart --}}
        <div class="dm-card">
            <div class="dm-card-header">
                <span class="dm-card-title"><i class="fas fa-chart-line"></i> Tendance des téléchargements</span>
                <span class="dm-badge dm-badge--cyan"><i class="fas fa-clock"></i> par heure</span>
            </div>
            <div class="dm-chart-wrap">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        {{-- Block reasons --}}
        <div class="dm-card">
            <div class="dm-card-header">
                <span class="dm-card-title"><i class="fas fa-shield-alt"></i> Raisons de blocage</span>
                @if($totalBlocked > 0)
                    <span class="dm-badge dm-badge--block">{{ $totalBlocked }}</span>
                @endif
            </div>
            <div class="dm-card-body">
                @forelse($blockReasons as $reason)
                    @php $pct = $maxBlock > 0 ? round($reason->count / $maxBlock * 100) : 0; @endphp
                    <div class="dm-reason-row">
                        <div class="dm-reason-label">{{ $reason->reason ?: 'Non spécifiée' }}</div>
                        <div class="dm-reason-bar-wrap">
                            <div class="dm-reason-bar" style="width:{{ $pct }}%"></div>
                        </div>
                        <div class="dm-reason-count">{{ $reason->count }}</div>
                    </div>
                @empty
                    <div class="dm-empty" style="padding:2rem 0;">
                        <i class="fas fa-check-circle" style="color:#22c55e;opacity:.6;"></i>
                        Aucun blocage
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="dm-grid-6-6">
        {{-- Top fichiers --}}
        <div class="dm-card">
            <div class="dm-card-header">
                <span class="dm-card-title"><i class="fas fa-fire"></i> Top fichiers téléchargés</span>
            </div>
            <div class="dm-table-wrap">
                <table class="dm-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>File ID</th>
                            <th style="text-align:right">Total</th>
                            <th style="min-width:140px">% bloqué</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topFiles as $i => $file)
                            @php $p = $file->count > 0 ? round($file->blocked_count / $file->count * 100) : 0; @endphp
                            <tr>
                                <td style="color:var(--c-muted);font-weight:700;">{{ $i + 1 }}</td>
                                <td><code class="dm-code">{{ $file->file_id ?: 'N/A' }}</code></td>
                                <td style="text-align:right;font-weight:700;">{{ $file->count }}</td>
                                <td>
                                    <div class="dm-progress-wrap">
                                        <div class="dm-progress">
                                            <div class="dm-progress-bar" style="width:{{ $p }}%"></div>
                                        </div>
                                        <div class="dm-progress-pct">{{ $p }}%</div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4"><div class="dm-empty"><i class="fas fa-inbox"></i>Aucun téléchargement</div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Types --}}
        <div class="dm-card">
            <div class="dm-card-header">
                <span class="dm-card-title"><i class="fas fa-layer-group"></i> Répartition par type</span>
            </div>
            <div class="dm-table-wrap">
                <table class="dm-table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th style="text-align:right">Total</th>
                            <th style="text-align:right">Bloqués</th>
                            <th style="min-width:110px">Taux</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fileTypes as $type)
                            @php $p = $type->count > 0 ? round($type->blocked_count / $type->count * 100) : 0; @endphp
                            <tr>
                                <td>
                                    <span class="dm-badge dm-badge--cyan">{{ $type->file_type ?: '—' }}</span>
                                </td>
                                <td style="text-align:right;font-weight:700;">{{ $type->count }}</td>
                                <td style="text-align:right;">
                                    @if($type->blocked_count > 0)
                                        <span class="dm-badge dm-badge--block">{{ $type->blocked_count }}</span>
                                    @else
                                        <span style="color:var(--c-muted)">—</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dm-progress-wrap">
                                        <div class="dm-progress">
                                            <div class="dm-progress-bar" style="width:{{ $p }}%"></div>
                                        </div>
                                        <span class="dm-progress-pct">{{ $p }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4"><div class="dm-empty"><i class="fas fa-inbox"></i>Aucune donnée</div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ═══════════════ TAB : SUSPICIOUS ═══════════════ --}}
    @elseif($activeTab == 'suspicious')
    <div class="dm-card">
        <div class="dm-card-header">
            <span class="dm-card-title"><i class="fas fa-user-secret"></i> Identifiants suspects</span>
            <div style="display:flex;align-items:center;gap:.75rem;">
                <div class="dm-threat-level {{ $threatLevel }}">
                    <i class="fas {{ $threatIcon }}"></i>
                    Menace {{ $threatLabel }}
                </div>
                @if($suspiciousIps->count() > 0)
                    <span class="dm-badge dm-badge--block">{{ $suspiciousIps->count() }} suspects</span>
                @endif
            </div>
        </div>
        <div class="dm-table-wrap">
            <table class="dm-table">
                <thead>
                    <tr>
                        <th>Identifiant (hash)</th>
                        <th style="text-align:right">Tentatives</th>
                        <th style="text-align:right">Bloqués</th>
                        <th>Raisons</th>
                        <th>Dernière tentative</th>
                        <th style="text-align:right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suspiciousIps as $ip)
                        <tr>
                            <td>
                                <code class="dm-code danger" title="{{ $ip['full_hash'] }}">{{ $ip['hash'] }}</code>
                            </td>
                            <td style="text-align:right;font-weight:800;font-size:.9rem;">{{ $ip['attempts'] }}</td>
                            <td style="text-align:right;">
                                <span class="dm-badge dm-badge--block">{{ $ip['blocked_count'] }}</span>
                            </td>
                            <td style="max-width:240px;color:var(--c-muted);font-size:.78rem;">
                                {{ $ip['reasons'] ?: '—' }}
                            </td>
                            <td class="dm-ts">
                                @if($ip['last_attempt'])
                                    {{ \Carbon\Carbon::parse($ip['last_attempt'])->diffForHumans() }}
                                @else
                                    —
                                @endif
                            </td>
                            <td style="text-align:right;">
                                <form action="{{ route('admin.downloads.unblock', ['hash' => $ip['full_hash']]) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="dm-btn-unblock"
                                        onclick="return confirm('Débloquer cet identifiant ?')">
                                        <i class="fas fa-lock-open"></i> Débloquer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="dm-empty">
                                    <i class="fas fa-shield-alt" style="color:#22c55e;"></i>
                                    Aucune activité suspecte détectée sur les {{ $activeHours }} dernières heures
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ═══════════════ TAB : LOGS ═══════════════ --}}
    @elseif($activeTab == 'logs')
    <div class="dm-card">
        <div class="dm-card-header">
            <span class="dm-card-title"><i class="fas fa-stream"></i> Logs récents <span style="color:var(--c-muted);font-weight:400;">(50 derniers)</span></span>
            <form action="{{ route('admin.downloads.clear-logs') }}" method="POST" class="dm-clear-form">
                @csrf
                <select name="hours" class="dm-select">
                    <option value="24">Logs > 24h</option>
                    <option value="48">Logs > 48h</option>
                    <option value="168">Logs > 7j</option>
                    <option value="720">Logs > 30j</option>
                </select>
                <button type="submit" class="dm-btn-danger"
                    onclick="return confirm('Supprimer les anciens logs ?')">
                    <i class="fas fa-trash-alt"></i> Supprimer
                </button>
            </form>
        </div>
        <div class="dm-table-wrap">
            <table class="dm-table">
                <thead>
                    <tr>
                        <th>Horodatage</th>
                        <th>Utilisateur</th>
                        <th>Type</th>
                        <th>File ID</th>
                        <th>IP</th>
                        <th>Raison</th>
                        <th style="text-align:right">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentLogs as $log)
                        <tr class="{{ $log->blocked ? 'is-blocked' : ($log->is_suspicious ? 'is-suspicious' : '') }}">
                            <td class="dm-ts">{{ $log->created_at->format('d/m/y H:i:s') }}</td>
                            <td style="font-size:.78rem;color:var(--c-muted);">
                                {{ $log->user_id ? 'ID ' . $log->user_id : 'Anonyme' }}
                            </td>
                            <td>
                                <span class="dm-badge dm-badge--cyan">{{ $log->file_type ?: '—' }}</span>
                            </td>
                            <td>
                                <code class="dm-code" style="font-size:.7rem;">{{ $log->file_id ?: '—' }}</code>
                            </td>
                            <td>
                                <code class="dm-code" style="font-size:.7rem;">{{ $log->ip_address ?: '—' }}</code>
                            </td>
                            <td style="font-size:.76rem;color:var(--c-muted);max-width:180px;">
                                {{ $log->reason ?: '—' }}
                            </td>
                            <td style="text-align:right;">
                                @if($log->blocked)
                                    <span class="dm-badge dm-badge--block"><i class="fas fa-ban"></i> Bloqué</span>
                                @elseif($log->is_suspicious)
                                    <span class="dm-badge dm-badge--susp"><i class="fas fa-exclamation-triangle"></i> Suspect</span>
                                @else
                                    <span class="dm-badge dm-badge--ok"><i class="fas fa-check"></i> OK</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="dm-empty">
                                    <i class="fas fa-inbox"></i>
                                    Aucun log pour cette période
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── Animated counters ── */
    document.querySelectorAll('[data-count]').forEach(el => {
        const target = parseInt(el.dataset.count, 10);
        if (target === 0) return;
        const duration = 900, step = 16;
        const increment = target / (duration / step);
        let current = 0;
        const timer = setInterval(() => {
            current = Math.min(current + increment, target);
            el.textContent = Math.round(current).toLocaleString('fr-FR');
            if (current >= target) clearInterval(timer);
        }, step);
    });

    /* ── Chart ── */
    const trendData = @json($trend);
    const canvas = document.getElementById('trendChart');
    if (!canvas || !trendData.length) {
        if (canvas) {
            canvas.parentElement.innerHTML = '<div class="dm-empty"><i class="fas fa-chart-area"></i>Aucune donnée de tendance</div>';
        }
        return;
    }

    const isDark = !document.body.classList.contains('light-mode');
    const gridColor  = isDark ? 'rgba(255,255,255,.06)' : 'rgba(0,0,0,.06)';
    const labelColor = isDark ? 'rgba(255,255,255,.45)' : '#94a3b8';

    const ctx = canvas.getContext('2d');

    /* Gradient fills */
    const gTotal = ctx.createLinearGradient(0, 0, 0, 280);
    gTotal.addColorStop(0, 'rgba(6,182,212,.35)');
    gTotal.addColorStop(1, 'rgba(6,182,212,0)');

    const gBlocked = ctx.createLinearGradient(0, 0, 0, 280);
    gBlocked.addColorStop(0, 'rgba(239,68,68,.3)');
    gBlocked.addColorStop(1, 'rgba(239,68,68,0)');

    const gSusp = ctx.createLinearGradient(0, 0, 0, 280);
    gSusp.addColorStop(0, 'rgba(245,158,11,.25)');
    gSusp.addColorStop(1, 'rgba(245,158,11,0)');

    const labels      = trendData.map(d => d.hour ? d.hour.slice(5, 16) : '');
    const totalData   = trendData.map(d => d.total);
    const blockedData = trendData.map(d => d.blocked);
    const suspData    = trendData.map(d => d.suspicious);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: 'Total',
                    data: totalData,
                    borderColor: '#06b6d4',
                    backgroundColor: gTotal,
                    borderWidth: 2.5,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#06b6d4',
                    pointRadius: 3,
                    pointHoverRadius: 6,
                },
                {
                    label: 'Bloqués',
                    data: blockedData,
                    borderColor: '#ef4444',
                    backgroundColor: gBlocked,
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#ef4444',
                    pointRadius: 3,
                    pointHoverRadius: 6,
                },
                {
                    label: 'Suspects',
                    data: suspData,
                    borderColor: '#f59e0b',
                    backgroundColor: gSusp,
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#f59e0b',
                    pointRadius: 3,
                    pointHoverRadius: 6,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: {
                    position: 'top',
                    align: 'end',
                    labels: {
                        color: labelColor,
                        boxWidth: 12,
                        boxHeight: 12,
                        borderRadius: 4,
                        useBorderRadius: true,
                        padding: 16,
                        font: { size: 12, weight: '600', family: 'Inter, sans-serif' },
                    },
                },
                tooltip: {
                    backgroundColor: 'rgba(8,10,24,.95)',
                    borderColor: 'rgba(6,182,212,.25)',
                    borderWidth: 1,
                    titleColor: 'rgba(255,255,255,.9)',
                    bodyColor: 'rgba(255,255,255,.6)',
                    padding: 12,
                    cornerRadius: 10,
                    callbacks: {
                        label: ctx => ` ${ctx.dataset.label}: ${ctx.parsed.y}`,
                    },
                },
            },
            scales: {
                x: {
                    grid: { color: gridColor, drawBorder: false },
                    ticks: { color: labelColor, font: { size: 11 }, maxRotation: 45 },
                },
                y: {
                    grid: { color: gridColor, drawBorder: false },
                    ticks: { color: labelColor, font: { size: 11 }, stepSize: 1, precision: 0 },
                    beginAtZero: true,
                },
            },
        },
    });
});
</script>
@endpush
