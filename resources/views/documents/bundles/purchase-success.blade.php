@extends('layouts.app')

@section('title', 'Téléchargement du pack - NiangProgrammeur')
@section('meta_description', 'Téléchargez votre pack acheté.')

@push('styles')
<style>
.cs-page { min-height: 100vh; padding: calc(var(--spacing-navbar, 76px) + 3rem) 1.5rem 4rem; background: linear-gradient(180deg, #ecfeff 0%, #f8fafc 60%); }
.cs-card { max-width: 560px; margin: 0 auto; background: #fff; border: 1px solid rgba(226,232,240,0.9); border-radius: 20px; padding: 2.25rem; text-align: center; box-shadow: 0 18px 50px rgba(15,23,42,0.08); }
.cs-icon { width: 72px; height: 72px; margin: 0 auto 1.25rem; border-radius: 50%; display: grid; place-items: center; font-size: 2rem; background: rgba(6,182,212,0.12); color: #0891b2; }
.cs-title { font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-bottom: 0.5rem; }
.cs-sub { color: #475569; line-height: 1.6; margin-bottom: 1.5rem; }
.cs-doc { font-weight: 700; color: #0f172a; background: #f1f5f9; border-radius: 12px; padding: 0.9rem 1rem; margin-bottom: 1.5rem; }
.cs-btn { display: inline-flex; align-items: center; gap: 0.6rem; padding: 0.9rem 2rem; border-radius: 14px; font-weight: 800; color: #fff; text-decoration: none; background: linear-gradient(135deg, #06b6d4, #14b8a6); box-shadow: 0 10px 28px rgba(6,182,212,0.3); }
.cs-note { font-size: 0.85rem; color: #64748b; margin-top: 1.25rem; line-height: 1.6; }
.cs-pending { background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.35); color: #b45309; border-radius: 12px; padding: 1rem; margin-bottom: 1.25rem; font-size: 0.9rem; }
body.dark-mode .cs-page { background: linear-gradient(180deg,#052e2b,#0b1120 60%); }
body.dark-mode .cs-card { background: rgba(15,23,42,0.8); border-color: rgba(148,163,184,0.15); }
body.dark-mode .cs-title { color: #f1f5f9; } body.dark-mode .cs-sub { color: #94a3b8; }
body.dark-mode .cs-doc { background: rgba(2,6,23,0.6); color: #e2e8f0; }
</style>
@endpush

@section('content')
<div class="cs-page">
    <div class="cs-card">
        @if($purchase->status === 'completed' && $purchase->download_token)
            <div class="cs-icon"><i class="fas fa-check"></i></div>
            <h1 class="cs-title">Paiement confirmé</h1>
            <p class="cs-sub">Merci pour votre achat. Votre pack est prêt à être téléchargé.</p>
            <div class="cs-doc">{{ $purchase->bundle->name }}</div>
            <a href="{{ route('bundles.payment.download', ['token' => $purchase->download_token]) }}" class="cs-btn">
                <i class="fas fa-download"></i> Télécharger le pack
            </a>
            <p class="cs-note">Ce lien reste valable <strong>30 jours</strong>. Conservez-le précieusement.</p>
        @else
            <div class="cs-icon"><i class="fas fa-hourglass-half"></i></div>
            <h1 class="cs-title">Paiement en attente</h1>
            <div class="cs-pending">
                <i class="fas fa-clock"></i> Votre paiement est en cours de traitement. Le lien de téléchargement sera disponible dès confirmation.
            </div>
            <div class="cs-doc">{{ $purchase->bundle->name }}</div>
            <p class="cs-note">Rechargez cette page dans quelques instants ou revenez sur la page du pack.</p>
            <a href="{{ route('bundles.show', $purchase->bundle->slug) }}" class="cs-btn" style="margin-top:1rem;">
                <i class="fas fa-arrow-left"></i> Retour au pack
            </a>
        @endif
    </div>
</div>
@endsection
