@extends('layouts.app')

@section('title', 'Achat confirmé - Documents - NiangProgrammeur')

@push('styles')
<style>
.success-page {
    min-height: 100vh;
    padding: 4rem 1rem;
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
}

.success-container {
    max-width: 600px;
    text-align: center;
    background: #ffffff;
    border-radius: 12px;
    padding: 3rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08), 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
}

.success-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 2rem;
    font-size: 2.5rem;
    color: white;
}

.success-title {
    font-size: 2rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 1rem;
}

.success-message {
    font-size: 1.125rem;
    color: #64748b;
    margin-bottom: 2rem;
    line-height: 1.6;
}

.document-info {
    background: #f8fafc;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    text-align: left;
}

.document-info h3 {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.document-info p {
    font-size: 0.9375rem;
    color: #64748b;
    margin: 0.25rem 0;
}

.email-notice {
    background: #ecfeff;
    border: 1px solid #06b6d4;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 2rem;
    color: #0891b2;
    font-size: 0.9375rem;
}

.download-btn {
    display: inline-block;
    padding: 1rem 2rem;
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    color: white;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 700;
    font-size: 1.125rem;
    transition: all 0.3s ease;
    margin-bottom: 1rem;
}

.download-btn:hover {
    background: linear-gradient(135deg, #0891b2, #0d9488);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(6, 182, 212, 0.3);
    color: white;
}

.back-link {
    display: inline-block;
    color: #64748b;
    text-decoration: none;
    font-size: 0.9375rem;
    margin-top: 1rem;
}

.back-link:hover {
    color: #06b6d4;
}

/* Dark Mode */
body.dark-mode .success-page {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
}

body.dark-mode .success-container {
    background: rgba(30, 41, 59, 0.8);
    border-color: rgba(6, 182, 212, 0.3);
}

body.dark-mode .success-title {
    color: #f9fafb;
}

body.dark-mode .success-message {
    color: #9ca3af;
}

body.dark-mode .document-info {
    background: rgba(15, 23, 42, 0.6);
}

body.dark-mode .document-info h3 {
    color: #f9fafb;
}

body.dark-mode .document-info p {
    color: #9ca3af;
}

body.dark-mode .email-notice {
    background: rgba(6, 182, 212, 0.1);
    border-color: rgba(6, 182, 212, 0.3);
    color: #06b6d4;
}
</style>
@endpush

@section('content')
<div class="success-page">
    <div class="success-container">
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>
        
        <h1 class="success-title">Achat confirmé !</h1>
        <p class="success-message">
            Votre paiement a été effectué avec succès. Un email avec le lien de téléchargement a été envoyé à <strong>{{ $purchase->customer_email }}</strong>.
        </p>
        
        <div class="document-info">
            <h3>{{ $purchase->document->title }}</h3>
            <p><strong>Prix payé :</strong> {{ number_format($purchase->amount_paid, 0, ',', ' ') }} {{ $purchase->currency }}</p>
            <p><strong>Date d'achat :</strong> {{ $purchase->purchased_at->format('d/m/Y à H:i') }}</p>
            <p><strong>Téléchargements restants :</strong> {{ $purchase->download_limit - $purchase->download_count }} / {{ $purchase->download_limit }}</p>
        </div>
        
        <div class="email-notice">
            <i class="fas fa-envelope"></i> Vérifiez votre boîte de réception (et vos spams) pour recevoir le lien de téléchargement.
        </div>
        
        <a href="{{ route('documents.index') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Retour aux documents
        </a>
    </div>
</div>
@endsection

