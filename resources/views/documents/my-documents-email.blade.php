@extends('layouts.app')

@section('title', 'Mes documents - NiangProgrammeur')

@push('styles')
<style>
.my-documents-page {
    min-height: 100vh;
    padding: 2rem 1rem;
    background: #ffffff;
}

.my-documents-container {
    max-width: 1200px;
    margin: 0 auto;
}

.page-header {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #e2e8f0;
}

.page-header h1 {
    font-size: 2.5rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.page-header p {
    color: #64748b;
    font-size: 1.125rem;
}

.email-form {
    background: #f8fafc;
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    border: 1px solid #e2e8f0;
}

.email-form h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 1rem;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.form-group input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 1rem;
}

.form-group button {
    padding: 0.75rem 1.5rem;
    background: #06b6d4;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.form-group button:hover {
    background: #0891b2;
}

.documents-list {
    display: grid;
    gap: 1.5rem;
}

.document-item {
    background: #ffffff;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08), 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
    display: flex;
    gap: 1.5rem;
}

.document-image {
    width: 120px;
    height: 120px;
    border-radius: 8px;
    object-fit: cover;
    background: #f1f5f9;
    flex-shrink: 0;
}

.document-image-placeholder {
    width: 120px;
    height: 120px;
    border-radius: 8px;
    background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
    flex-shrink: 0;
}

.document-details {
    flex: 1;
}

.document-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.document-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1rem;
    font-size: 0.875rem;
    color: #64748b;
}

.download-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #e2e8f0;
}

.download-stats {
    font-size: 0.875rem;
    color: #64748b;
}

.download-btn {
    padding: 0.5rem 1rem;
    background: #06b6d4;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.download-btn:hover {
    background: #0891b2;
    color: white;
}

.download-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: #f8fafc;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
}

.empty-state i {
    font-size: 4rem;
    color: #cbd5e1;
    margin-bottom: 1.5rem;
    display: block;
}

.empty-state h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: #64748b;
}

/* Dark Mode */
body.dark-mode .my-documents-page {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
}

body.dark-mode .page-header h1 {
    color: #f9fafb;
}

body.dark-mode .page-header p {
    color: #9ca3af;
}

body.dark-mode .email-form {
    background: rgba(30, 41, 59, 0.6);
    border-color: rgba(6, 182, 212, 0.3);
}

body.dark-mode .email-form h2 {
    color: #f9fafb;
}

body.dark-mode .document-item {
    background: rgba(30, 41, 59, 0.8);
    border-color: rgba(6, 182, 212, 0.3);
}

body.dark-mode .document-title {
    color: #f9fafb;
}

body.dark-mode .empty-state {
    background: rgba(30, 41, 59, 0.6);
    border-color: rgba(6, 182, 212, 0.3);
}
</style>
@endpush

@section('content')
<div class="my-documents-page">
    <div class="my-documents-container">
        <div class="page-header">
            <h1>📚 Mes Documents</h1>
            <p>Accédez à vos documents achetés</p>
        </div>

        <div class="email-form">
            <h2>Entrez votre email</h2>
            <form action="{{ route('documents.my-documents-email') }}" method="GET">
                <div class="form-group">
                    <label for="email">Adresse email *</label>
                    <input type="email" name="email" id="email" value="{{ $email ?? '' }}" required 
                        placeholder="votre@email.com">
                </div>
                <div class="form-group">
                    <button type="submit">
                        <i class="fas fa-search"></i> Voir mes documents
                    </button>
                </div>
            </form>
        </div>

        @if(isset($purchases) && $purchases->count() > 0)
        <div class="documents-list">
            @foreach($purchases as $purchase)
            <div class="document-item">
                @if($purchase->document->cover_image)
                    @if($purchase->document->cover_type === 'internal')
                        <img src="{{ asset('storage/' . $purchase->document->cover_image) }}" 
                             alt="{{ $purchase->document->title }}" 
                             class="document-image">
                    @else
                        <img src="{{ $purchase->document->cover_image }}" 
                             alt="{{ $purchase->document->title }}" 
                             class="document-image">
                    @endif
                @else
                    <div class="document-image-placeholder">
                        <i class="fas fa-file-{{ $purchase->document->file_extension === 'pdf' ? 'pdf' : ($purchase->document->file_extension === 'doc' || $purchase->document->file_extension === 'docx' ? 'word' : 'alt') }}"></i>
                    </div>
                @endif

                <div class="document-details">
                    <h3 class="document-title">{{ $purchase->document->title }}</h3>
                    <div class="document-meta">
                        <span><i class="fas fa-tag"></i> {{ $purchase->document->category->name }}</span>
                        <span><i class="fas fa-calendar"></i> Acheté le {{ $purchase->purchased_at->format('d/m/Y') }}</span>
                        <span><i class="fas fa-money-bill"></i> {{ number_format($purchase->amount_paid, 0, ',', ' ') }} {{ $purchase->currency }}</span>
                    </div>

                    <div class="download-info">
                        <div class="download-stats">
                            <strong>Téléchargements :</strong> {{ $purchase->download_count }} / {{ $purchase->download_limit }}
                            @if($purchase->download_count >= $purchase->download_limit)
                                <span style="color: #ef4444;">(Limite atteinte)</span>
                            @else
                                <span style="color: #06b6d4;">({{ $purchase->download_limit - $purchase->download_count }} restants)</span>
                            @endif
                        </div>
                        @if($purchase->canDownload() && $purchase->download_token)
                            <a href="{{ route('documents.download.token', ['token' => $purchase->download_token]) }}?email={{ urlencode($email) }}" 
                               class="download-btn">
                                <i class="fas fa-download"></i> Télécharger
                            </a>
                        @else
                            <button class="download-btn" disabled>
                                <i class="fas fa-ban"></i> Non disponible
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @elseif(isset($purchases) && $purchases->count() === 0)
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h2>Aucun document trouvé</h2>
            <p>Aucun document acheté n'a été trouvé pour cette adresse email.</p>
            <a href="{{ route('documents.index') }}" style="display: inline-block; margin-top: 1rem; color: #06b6d4; text-decoration: none;">
                <i class="fas fa-arrow-left"></i> Parcourir les documents
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

