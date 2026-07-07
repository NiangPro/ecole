@extends('admin.layout')

@section('title', 'Achats de corrigés')

@section('content')
<div class="epreuves-admin">
    <div class="mb-8">
        <h3 class="text-3xl font-bold mb-2">Achats de corrigés</h3>
        <p class="text-gray-400">Validez les paiements Wave pour livrer les corrigés (e-mail + WhatsApp).</p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-500/20 border border-green-500/50 rounded-lg text-green-400 flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-500/20 border border-red-500/50 rounded-lg text-red-400 flex items-center gap-2">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
        <div class="content-section text-center">
            <div class="text-2xl font-bold text-yellow-400">{{ $stats['pending'] }}</div>
            <div class="text-sm text-gray-400">En attente</div>
        </div>
        <div class="content-section text-center">
            <div class="text-2xl font-bold text-green-400">{{ $stats['completed'] }}</div>
            <div class="text-sm text-gray-400">Validés</div>
        </div>
        <div class="content-section text-center">
            <div class="text-2xl font-bold text-cyan-400">{{ number_format($stats['revenue'], 0, ',', ' ') }} <span class="text-sm">FCFA</span></div>
            <div class="text-sm text-gray-400">Revenu</div>
        </div>
    </div>

    <form method="GET" class="grid md:grid-cols-3 gap-4 mb-6">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="E-mail, téléphone, nom…" class="px-4 py-2 rounded-lg bg-gray-800 border border-gray-600 text-white placeholder-gray-500 focus:border-cyan-500 md:col-span-2">
        <select name="status" class="px-4 py-2 rounded-lg bg-gray-800 border border-gray-600 text-white focus:border-cyan-500" onchange="this.form.submit()">
            <option value="">Tous les statuts</option>
            <option value="pending" @selected(request('status')==='pending')>En attente</option>
            <option value="completed" @selected(request('status')==='completed')>Validés</option>
            <option value="failed" @selected(request('status')==='failed')>Annulés</option>
        </select>
    </form>

    <div class="content-section overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-cyan-500/30">
                    <th class="pb-3 pr-4 font-semibold">Date</th>
                    <th class="pb-3 pr-4 font-semibold">Corrigé</th>
                    <th class="pb-3 pr-4 font-semibold">Acheteur</th>
                    <th class="pb-3 pr-4 font-semibold">Montant</th>
                    <th class="pb-3 pr-4 font-semibold">Statut</th>
                    <th class="pb-3 pr-4 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($purchases as $p)
                <tr class="border-b border-gray-700/50 hover:bg-gray-800/30 transition">
                    <td class="py-3 pr-4 text-gray-400 text-sm whitespace-nowrap">{{ $p->created_at->format('d/m/Y H:i') }}</td>
                    <td class="py-3 pr-4">
                        <span class="text-cyan-400 font-medium">{{ \Illuminate\Support\Str::limit($p->epreuve?->title ?? '—', 45) }}</span>
                    </td>
                    <td class="py-3 pr-4 text-gray-300 text-sm">
                        @if($p->customer_name)<div>{{ $p->customer_name }}</div>@endif
                        @if($p->customer_email)<div class="text-gray-500"><i class="fas fa-envelope mr-1"></i>{{ $p->customer_email }}</div>@endif
                        @if($p->customer_phone)<div class="text-gray-500"><i class="fab fa-whatsapp mr-1"></i>{{ $p->country_code }} {{ $p->customer_phone }}</div>@endif
                    </td>
                    <td class="py-3 pr-4 text-gray-300 whitespace-nowrap">{{ number_format($p->amount_paid, 0, ',', ' ') }} FCFA</td>
                    <td class="py-3 pr-4">
                        <span class="px-2 py-1 rounded text-xs {{ $p->status === 'completed' ? 'bg-green-500/20 text-green-400' : ($p->status === 'pending' ? 'bg-yellow-500/20 text-yellow-400' : 'bg-red-500/20 text-red-400') }}">
                            {{ ['pending'=>'En attente','completed'=>'Validé','failed'=>'Annulé'][$p->status] ?? $p->status }}
                        </span>
                    </td>
                    <td class="py-3 pr-4 text-right whitespace-nowrap">
                        @if($p->status === 'pending')
                            <form action="{{ route('admin.epreuves.purchases.approve', $p->id) }}" method="POST" class="inline" onsubmit="return confirm('Confirmer la réception du paiement Wave et livrer le corrigé ?');">
                                @csrf
                                <button class="inline-flex items-center px-3 py-1.5 bg-green-500/20 hover:bg-green-500/30 text-green-400 rounded text-sm transition mr-1">
                                    <i class="fas fa-check mr-1"></i>Valider &amp; livrer
                                </button>
                            </form>
                            <form action="{{ route('admin.epreuves.purchases.cancel', $p->id) }}" method="POST" class="inline" onsubmit="return confirm('Annuler cet achat ?');">
                                @csrf
                                <button class="inline-flex items-center px-3 py-1.5 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded text-sm transition">
                                    <i class="fas fa-times mr-1"></i>Annuler
                                </button>
                            </form>
                        @elseif($p->status === 'completed' && $p->download_token)
                            @php
                                $downloadLink = route('epreuves.corrige.download', ['token' => $p->download_token]);
                                $shareText = "Bonjour " . ($p->customer_name ?? '') . ", voici votre corrigé : " . ($p->epreuve?->title ?? '') . "\n\nLien de téléchargement (valable 30 jours) :\n" . $downloadLink;
                            @endphp
                            @if($p->customer_email)
                                <a href="mailto:{{ $p->customer_email }}?subject={{ rawurlencode('Votre corrigé - ' . ($p->epreuve?->title ?? '')) }}&body={{ rawurlencode($shareText) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-500/20 hover:bg-blue-500/30 text-blue-400 rounded text-sm transition mr-1">
                                    <i class="fas fa-envelope mr-1"></i>Email
                                </a>
                            @endif
                            @if($p->customer_phone)
                                @php
                                    $waNum = preg_replace('/[^0-9]/', '', ($p->country_code ?? '') . $p->customer_phone);
                                @endphp
                                <a href="https://wa.me/{{ $waNum }}?text={{ rawurlencode($shareText) }}" target="_blank" rel="noopener" class="inline-flex items-center px-3 py-1.5 bg-green-500/20 hover:bg-green-500/30 text-green-400 rounded text-sm transition mr-1">
                                    <i class="fab fa-whatsapp mr-1"></i>Partager
                                </a>
                            @endif
                            <a href="{{ $downloadLink }}" target="_blank" rel="noopener" class="inline-flex items-center px-3 py-1.5 bg-cyan-500/20 hover:bg-cyan-500/30 text-cyan-400 rounded text-sm transition">
                                <i class="fas fa-download mr-1"></i>Lien
                            </a>
                        @else
                            <span class="text-gray-600">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-8 text-center text-gray-400">Aucun achat de corrigé pour le moment.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $purchases->links() }}</div>
</div>
@endsection
