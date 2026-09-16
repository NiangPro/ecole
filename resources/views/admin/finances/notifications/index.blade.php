@extends('admin.layout')

@section('title', 'Notifications Finances - Admin')

@section('content')
<div class="finance-module text-slate-100">

    <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
        <h1 class="text-2xl font-bold text-white">🔔 Notifications</h1>
        <div class="flex gap-2">
            <form method="POST" action="{{ route('admin.finances.notifications.read-all') }}">
                @csrf @method('PATCH')
                <button type="submit" class="bg-slate-700 hover:bg-slate-600 text-slate-100 px-4 py-2 rounded-lg text-sm font-medium">
                    Tout marquer lu
                </button>
            </form>
            <a href="{{ route('admin.finances.dashboard') }}"
               class="bg-slate-700 hover:bg-slate-600 text-slate-100 px-4 py-2 rounded-lg text-sm font-medium">
                ← Dashboard
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-500/10 border border-green-500/30 text-green-400 rounded-lg p-3 mb-4 text-sm">{{ session('success') }}</div>
    @endif

    {{-- Prochaines échéances : reprise de la section du dashboard, mais fenêtre
         resserrée à 2 jours (au lieu de 30) — on ne veut voir ici que l'imminent. --}}
    <div class="stat-card mb-6">
        <h2 class="font-semibold text-slate-200 mb-4">📅 Prochaines échéances (2 jours)</h2>
        @forelse($upcomingRecurrings as $r)
        <div class="flex justify-between items-center py-2 border-b border-slate-700 last:border-0">
            <div class="flex items-center gap-2">
                <span>{{ $r->category->icon }}</span>
                <span class="text-sm text-slate-300">{{ $r->label }}</span>
                <span class="text-xs px-2 py-0.5 rounded-full {{ $r->type === 'income' ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                    {{ $r->type === 'income' ? 'Revenu' : 'Dépense' }}
                </span>
            </div>
            <div class="text-right">
                <p class="text-sm font-semibold {{ $r->type === 'income' ? 'text-green-400' : 'text-red-400' }}">
                    {{ number_format($r->amount, 0, ',', ' ') }} XOF
                </p>
                <p class="text-xs text-slate-500">{{ $r->next_due_date->format('d/m/Y') }}</p>
            </div>
        </div>
        @empty
        <p class="text-slate-500 text-sm">Aucune échéance dans les 2 prochains jours.</p>
        @endforelse
    </div>

    <div class="stat-card !p-0 divide-y divide-slate-800">
        @forelse($notifications as $notif)
        <div class="flex justify-between items-center px-4 py-3 {{ $notif->is_read ? 'opacity-50' : '' }}">
            <div>
                <p class="text-sm text-slate-200 font-medium">{{ $notif->title }}</p>
                <p class="text-xs text-slate-500">{{ $notif->message }} · {{ $notif->due_date->format('d/m/Y') }}</p>
                @if($notif->recurring?->category)
                <p class="text-xs text-slate-600">{{ $notif->recurring->category->icon }} {{ $notif->recurring->category->name }}</p>
                @endif
            </div>
            @unless($notif->is_read)
            <form method="POST" action="{{ route('admin.finances.notifications.read', $notif) }}">
                @csrf @method('PATCH')
                <button type="submit" class="text-xs text-cyan-400 hover:underline">✓ Marquer lu</button>
            </form>
            @endunless
        </div>
        @empty
        <div class="px-4 py-6 text-center text-slate-500">Aucune notification.</div>
        @endforelse
    </div>

    <div class="mt-4">{{ $notifications->links() }}</div>

</div>
@endsection
