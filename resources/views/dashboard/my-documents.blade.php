@extends('dashboard.layout')

@section('dashboard-content')
@php
    // S'assurer que la locale est définie
    if (!app()->getLocale()) {
        app()->setLocale(session('language', 'fr'));
    }
    $pageTitle = 'Mes Documents';
    $pageDescription = 'Accédez à tous vos documents achetés';
@endphp

<style>
.documents-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.document-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1), 0 0 0 1px rgba(6, 182, 212, 0.1);
    border: 1px solid rgba(6, 182, 212, 0.2);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.document-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #06b6d4, #14b8a6, #06b6d4);
    background-size: 200% 100%;
    animation: shimmer 3s linear infinite;
}

@keyframes shimmer {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

.document-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 16px 48px rgba(6, 182, 212, 0.2);
    border-color: rgba(6, 182, 212, 0.4);
}

.document-image-wrapper {
    width: 100%;
    height: 180px;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 1.25rem;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.1));
    display: flex;
    align-items: center;
    justify-content: center;
}

.document-image-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.document-image-placeholder {
    font-size: 3.5rem;
    color: #06b6d4;
    opacity: 0.5;
}

.document-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 1rem;
    line-height: 1.3;
}

.document-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    margin-bottom: 1.25rem;
    font-size: 0.85rem;
    color: #64748b;
}

.document-meta span {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.document-meta i {
    color: #06b6d4;
    font-size: 0.875rem;
}

.download-info {
    padding-top: 1.25rem;
    border-top: 2px solid rgba(6, 182, 212, 0.1);
}

.download-stats {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding: 0.75rem 1rem;
    background: rgba(6, 182, 212, 0.05);
    border-radius: 10px;
}

.download-stats-label {
    font-size: 0.875rem;
    color: #64748b;
    font-weight: 600;
}

.download-stats-value {
    font-size: 1rem;
    font-weight: 800;
    color: #1e293b;
}

.download-stats-value.limit-reached {
    color: #ef4444;
}

.download-stats-value.available {
    color: #06b6d4;
}

.download-btn {
    width: 100%;
    padding: 0.875rem 1.25rem;
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.95rem;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 16px rgba(6, 182, 212, 0.3);
    position: relative;
    overflow: hidden;
}

.download-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.5s ease;
}

.download-btn:hover::before {
    left: 100%;
}

.download-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(6, 182, 212, 0.4);
    color: white;
}

.download-btn:disabled {
    background: #9ca3af;
    cursor: not-allowed;
    opacity: 0.6;
    transform: none;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    border: 1px solid rgba(6, 182, 212, 0.2);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

.empty-state-icon {
    font-size: 4rem;
    color: #cbd5e1;
    margin-bottom: 1.5rem;
    display: block;
}

.empty-state h2 {
    font-size: 1.75rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 0.75rem;
}

.empty-state p {
    color: #64748b;
    font-size: 1rem;
    margin-bottom: 2rem;
}

.btn-browse {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.875rem 1.75rem;
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    color: white;
    border-radius: 12px;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 16px rgba(6, 182, 212, 0.3);
}

.btn-browse:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(6, 182, 212, 0.4);
    color: white;
}

.pagination-wrapper {
    margin-top: 2rem;
    display: flex;
    justify-content: center;
}

/* Dark Mode */
body.dark-mode .document-card {
    background: rgba(30, 41, 59, 0.8);
    border-color: rgba(6, 182, 212, 0.3);
}

body.dark-mode .document-title {
    color: white;
}

body.dark-mode .document-meta {
    color: rgba(255, 255, 255, 0.7);
}

body.dark-mode .download-stats-value {
    color: white;
}

body.dark-mode .empty-state {
    background: rgba(30, 41, 59, 0.8);
    border-color: rgba(6, 182, 212, 0.3);
}

body.dark-mode .empty-state h2 {
    color: white;
}

body.dark-mode .empty-state p {
    color: rgba(255, 255, 255, 0.7);
}

@media (max-width: 768px) {
    .documents-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="content-card">
    <h2 class="card-title dashboard-text-primary" style="margin-bottom: 2rem;">
        <i class="fas fa-file-alt"></i>
        Mes Documents Achetés
    </h2>

    @if($purchases->count() > 0)
    <div class="documents-grid">
        @foreach($purchases as $purchase)
        <div class="document-card">
            <div class="document-image-wrapper">
                @if($purchase->document && $purchase->document->cover_image)
                    @if($purchase->document->cover_type === 'internal')
                        <img src="{{ asset('storage/' . $purchase->document->cover_image) }}" alt="{{ $purchase->document->title }}">
                    @else
                        <img src="{{ $purchase->document->cover_image }}" alt="{{ $purchase->document->title }}">
                    @endif
                @else
                    <div class="document-image-placeholder">
                        <i class="fas fa-file-{{ $purchase->document && $purchase->document->file_extension === 'pdf' ? 'pdf' : ($purchase->document && ($purchase->document->file_extension === 'doc' || $purchase->document->file_extension === 'docx') ? 'word' : 'alt') }}"></i>
                    </div>
                @endif
            </div>

            <h3 class="document-title">{{ $purchase->document ? $purchase->document->title : 'Document supprimé' }}</h3>

            @if($purchase->document)
            <div class="document-meta">
                @if($purchase->document->category)
                <span>
                    <i class="fas fa-folder"></i>
                    {{ $purchase->document->category->name }}
                </span>
                @endif
                @if($purchase->purchased_at)
                <span>
                    <i class="fas fa-calendar"></i>
                    {{ $purchase->purchased_at->format('d/m/Y') }}
                </span>
                @endif
                <span>
                    <i class="fas fa-money-bill"></i>
                    {{ number_format($purchase->amount_paid, 0, ',', ' ') }} {{ $purchase->currency }}
                </span>
            </div>

            <div class="download-info">
                <div class="download-stats">
                    <span class="download-stats-label">Téléchargements :</span>
                    <span class="download-stats-value {{ $purchase->downloads->count() >= $purchase->download_limit ? 'limit-reached' : 'available' }}">
                        {{ $purchase->downloads->count() }}/{{ $purchase->download_limit }}
                    </span>
                </div>

                @if($purchase->downloads->count() < $purchase->download_limit && $purchase->download_token)
                    @php
                        $userEmail = Auth::user()->email;
                        $downloadLink = route('documents.download.token', ['token' => $purchase->download_token]) . '?email=' . urlencode($userEmail);
                    @endphp
                    <a href="{{ $downloadLink }}" class="download-btn">
                        <i class="fas fa-download"></i>
                        <span>Télécharger</span>
                    </a>
                @else
                    <button class="download-btn" disabled>
                        <i class="fas fa-ban"></i>
                        <span>
                            @if($purchase->downloads->count() >= $purchase->download_limit)
                                Limite atteinte
                            @else
                                Non disponible
                            @endif
                        </span>
                    </button>
                @endif
            </div>
            @endif
        </div>
        @endforeach
    </div>

    <div class="pagination-wrapper">
        {{ $purchases->links() }}
    </div>
    @else
    <div class="empty-state">
        <i class="fas fa-inbox empty-state-icon"></i>
        <h2>Aucun document acheté</h2>
        <p>Vous n'avez pas encore acheté de documents. Parcourez notre catalogue pour découvrir nos ressources.</p>
        <a href="{{ route('documents.index') }}" class="btn-browse">
            <i class="fas fa-search"></i>
            <span>Parcourir les documents</span>
        </a>
    </div>
    @endif
</div>
@endsection

