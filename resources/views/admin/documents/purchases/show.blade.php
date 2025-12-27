@extends('admin.layout')

@section('title', 'Détails Achat')

@section('styles')
<style>
    /* Variables CSS - Design System */
    :root {
        --primary: #06b6d4;
        --secondary: #14b8a6;
        --accent: #8b5cf6;
        --success: #22c55e;
        --warning: #f59e0b;
        --danger: #ef4444;
        --bg-dark: #0a0e27;
        --bg-card: rgba(30, 41, 59, 0.6);
        --text-primary: #ffffff;
        --text-secondary: rgba(255, 255, 255, 0.8);
        --text-muted: rgba(255, 255, 255, 0.6);
        --border: rgba(6, 182, 212, 0.2);
    }

    /* Page Container */
    .purchase-show-page {
        min-height: 100vh;
        background: var(--bg-dark);
        padding: 2rem 0;
        position: relative;
        overflow-x: hidden;
    }

    /* Animated Background */
    .purchase-show-page::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: 
            radial-gradient(circle at 20% 30%, rgba(6, 182, 212, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 70%, rgba(20, 184, 166, 0.1) 0%, transparent 50%);
        animation: bgFloat 20s ease infinite;
        pointer-events: none;
        z-index: 0;
    }

    @keyframes bgFloat {
        0%, 100% { transform: translate(0, 0) scale(1); }
        50% { transform: translate(20px, -20px) scale(1.1); }
    }

    .purchase-show-page > * {
        position: relative;
        z-index: 1;
    }

    /* Hero Header */
    .page-hero {
        margin-bottom: 2.5rem;
        padding: 0 1rem;
    }

    .hero-content {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .hero-title-section h1 {
        font-size: 2.5rem;
        font-weight: 900;
        background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 50%, #8b5cf6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
        line-height: 1.2;
    }

    .hero-title-section p {
        color: var(--text-secondary);
        font-size: 1.125rem;
    }

    .hero-actions {
        display: flex;
        gap: 1rem;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: rgba(6, 182, 212, 0.1);
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 12px;
        color: #06b6d4;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(10px);
    }

    .btn-back:hover {
        background: rgba(6, 182, 212, 0.2);
        border-color: #06b6d4;
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(6, 182, 212, 0.3);
    }

    /* Flash Message */
    .flash-message {
        margin: 0 1rem 2rem;
        padding: 1rem 1.5rem;
        background: rgba(34, 197, 94, 0.15);
        border: 2px solid rgba(34, 197, 94, 0.3);
        border-radius: 12px;
        color: #22c55e;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        backdrop-filter: blur(10px);
        animation: slideDown 0.4s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Main Grid */
    .main-content-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 2rem;
        padding: 0 1rem;
    }

    /* Info Cards */
    .info-card {
        background: rgba(30, 41, 59, 0.6);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 2rem;
        border: 1px solid rgba(6, 182, 212, 0.2);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .info-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #06b6d4, #14b8a6, #8b5cf6);
        background-size: 200% 100%;
        animation: shimmer 3s linear infinite;
    }

    @keyframes shimmer {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    .info-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(6, 182, 212, 0.2);
        border-color: rgba(6, 182, 212, 0.4);
    }

    .card-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid rgba(6, 182, 212, 0.2);
    }

    .card-header i {
        font-size: 1.5rem;
        color: #06b6d4;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(6, 182, 212, 0.1);
        border-radius: 10px;
    }

    .card-header h3 {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--text-primary);
        margin: 0;
    }

    .info-item {
        margin-bottom: 1.25rem;
    }

    .info-item:last-child {
        margin-bottom: 0;
    }

    .info-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 700;
        color: #06b6d4;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        font-size: 1.125rem;
        color: var(--text-primary);
        font-weight: 600;
        word-break: break-word;
    }

    .info-value.highlight {
        font-size: 1.5rem;
        font-weight: 900;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-badge.completed {
        background: rgba(34, 197, 94, 0.2);
        color: #22c55e;
        border: 2px solid rgba(34, 197, 94, 0.3);
    }

    .status-badge.pending {
        background: rgba(245, 158, 11, 0.2);
        color: #f59e0b;
        border: 2px solid rgba(245, 158, 11, 0.3);
    }

    .status-badge.cancelled {
        background: rgba(107, 114, 128, 0.2);
        color: #9ca3af;
        border: 2px solid rgba(107, 114, 128, 0.3);
    }

    .status-badge.failed {
        background: rgba(239, 68, 68, 0.2);
        color: #ef4444;
        border: 2px solid rgba(239, 68, 68, 0.3);
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding: 1rem 1.5rem;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        position: relative;
        overflow: hidden;
    }

    .btn-action::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
    }

    .btn-action:hover::before {
        left: 100%;
    }

    .btn-action.approve {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: white;
        box-shadow: 0 4px 16px rgba(34, 197, 94, 0.4);
    }

    .btn-action.approve:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(34, 197, 94, 0.5);
    }

    .btn-action.cancel {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        box-shadow: 0 4px 16px rgba(239, 68, 68, 0.4);
    }

    .btn-action.cancel:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(239, 68, 68, 0.5);
    }

    .btn-action.copy {
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: white;
        box-shadow: 0 4px 16px rgba(6, 182, 212, 0.4);
    }

    .btn-action.copy:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(6, 182, 212, 0.5);
    }

    .btn-action.whatsapp {
        background: linear-gradient(135deg, #25D366, #128C7E);
        color: white;
        box-shadow: 0 4px 16px rgba(37, 211, 102, 0.4);
    }

    .btn-action.whatsapp:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(37, 211, 102, 0.5);
    }

    /* Downloads History */
    .downloads-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .download-item {
        padding: 1rem;
        background: rgba(6, 182, 212, 0.05);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .download-item:hover {
        background: rgba(6, 182, 212, 0.1);
        border-color: rgba(6, 182, 212, 0.4);
        transform: translateX(4px);
    }

    .download-date {
        font-size: 0.9375rem;
        color: var(--text-primary);
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .download-ip {
        font-size: 0.8125rem;
        color: var(--text-muted);
    }

    /* Document Image Card */
    .document-image-card {
        grid-column: 1 / -1;
    }

    .cover-image-wrapper {
        position: relative;
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        background: rgba(6, 182, 212, 0.05);
        aspect-ratio: 16 / 9;
    }

    .cover-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .cover-image-wrapper:hover img {
        transform: scale(1.05);
    }

    .cover-image-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.1));
        color: #06b6d4;
        font-size: 4rem;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .main-content-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .hero-title-section h1 {
            font-size: 2rem;
        }

        .info-card {
            padding: 1.5rem;
        }

        .card-header h3 {
            font-size: 1.25rem;
        }
    }
</style>
@endsection

@section('content')
<div class="purchase-show-page">
    <div class="page-hero">
        <div class="hero-content">
            <div class="hero-title-section">
                <h1>📦 Détails de l'Achat</h1>
                <p>Informations complètes sur l'achat du document</p>
            </div>
            <div class="hero-actions">
                <a href="{{ route('admin.documents.purchases.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour</span>
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="flash-message">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <div class="main-content-grid">
        <!-- Image du Document -->
        @if($purchase->document->cover_image)
        <div class="info-card document-image-card">
            <div class="card-header">
                <i class="fas fa-image"></i>
                <h3>Image du Document</h3>
            </div>
            <div class="cover-image-wrapper">
                @if($purchase->document->cover_type === 'internal')
                    <img src="{{ asset('storage/' . $purchase->document->cover_image) }}" alt="{{ $purchase->document->title }}">
                @else
                    <img src="{{ $purchase->document->cover_image }}" alt="{{ $purchase->document->title }}">
                @endif
            </div>
        </div>
        @else
        <div class="info-card document-image-card">
            <div class="card-header">
                <i class="fas fa-image"></i>
                <h3>Image du Document</h3>
            </div>
            <div class="cover-image-wrapper">
                <div class="cover-image-placeholder">
                    <i class="fas fa-file-{{ $purchase->document->file_extension === 'pdf' ? 'pdf' : ($purchase->document->file_extension === 'doc' || $purchase->document->file_extension === 'docx' ? 'word' : 'alt') }}"></i>
                </div>
            </div>
        </div>
        @endif

        <!-- Informations Client -->
        <div class="info-card">
            <div class="card-header">
                <i class="fas fa-user-circle"></i>
                <h3>Informations Client</h3>
            </div>
            <div class="info-item">
                <span class="info-label">Nom</span>
                <div class="info-value">{{ $purchase->customer_name ?? ($purchase->user ? $purchase->user->name : 'N/A') }}</div>
            </div>
            <div class="info-item">
                <span class="info-label">Email</span>
                <div class="info-value">{{ $purchase->customer_email ?? ($purchase->user ? $purchase->user->email : 'N/A') }}</div>
            </div>
            @if($purchase->customer_phone)
            <div class="info-item">
                <span class="info-label">Téléphone</span>
                <div class="info-value">{{ $purchase->country_code }}{{ $purchase->customer_phone }}</div>
            </div>
            @endif
        </div>

        <!-- Informations Document -->
        <div class="info-card">
            <div class="card-header">
                <i class="fas fa-file-alt"></i>
                <h3>Informations Document</h3>
            </div>
            <div class="info-item">
                <span class="info-label">Titre</span>
                <div class="info-value">{{ $purchase->document->title }}</div>
            </div>
            <div class="info-item">
                <span class="info-label">Catégorie</span>
                <div class="info-value">{{ $purchase->document->category->name }}</div>
            </div>
            <div class="info-item">
                <span class="info-label">Prix payé</span>
                <div class="info-value highlight">{{ number_format($purchase->document->current_price, 0, ',', ' ') }} FCFA</div>
            </div>
        </div>

        <!-- Détails de l'Achat -->
        <div class="info-card">
            <div class="card-header">
                <i class="fas fa-receipt"></i>
                <h3>Détails de l'Achat</h3>
            </div>
            <div class="info-item">
                <span class="info-label">Statut</span>
                <div class="info-value">
                    <span class="status-badge 
                        @if($purchase->status === 'completed') completed
                        @elseif($purchase->status === 'pending') pending
                        @elseif($purchase->status === 'cancelled') cancelled
                        @else failed
                        @endif">
                        <i class="fas 
                            @if($purchase->status === 'completed') fa-check-circle
                            @elseif($purchase->status === 'pending') fa-clock
                            @elseif($purchase->status === 'cancelled') fa-times-circle
                            @else fa-exclamation-circle
                            @endif"></i>
                        @if($purchase->status === 'completed') Complété
                        @elseif($purchase->status === 'pending') En attente
                        @elseif($purchase->status === 'cancelled') Annulé
                        @else Échoué
                        @endif
                    </span>
                </div>
            </div>
            <div class="info-item">
                <span class="info-label">Montant payé</span>
                <div class="info-value highlight">{{ number_format($purchase->amount_paid, 0, ',', ' ') }} {{ $purchase->currency }}</div>
            </div>
            <div class="info-item">
                <span class="info-label">Date de création</span>
                <div class="info-value">{{ $purchase->created_at->format('d/m/Y à H:i') }}</div>
            </div>
            @if($purchase->purchased_at)
            <div class="info-item">
                <span class="info-label">Date d'achat</span>
                <div class="info-value">{{ $purchase->purchased_at->format('d/m/Y à H:i') }}</div>
            </div>
            @endif
            <div class="info-item">
                <span class="info-label">Téléchargements</span>
                <div class="info-value">{{ $purchase->download_count }}/{{ $purchase->download_limit }}</div>
            </div>
            @if($purchase->expires_at)
            <div class="info-item">
                <span class="info-label">Expire le</span>
                <div class="info-value">{{ $purchase->expires_at->format('d/m/Y à H:i') }}</div>
            </div>
            @endif
        </div>

        <!-- Actions -->
        @if($purchase->status === 'pending')
        <div class="info-card">
            <div class="card-header">
                <i class="fas fa-cog"></i>
                <h3>Actions</h3>
            </div>
            <div class="action-buttons">
                <form action="{{ route('admin.documents.purchases.approve', $purchase->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-action approve">
                        <i class="fas fa-check"></i>
                        <span>Approuver l'achat</span>
                    </button>
                </form>
                <form action="{{ route('admin.documents.purchases.cancel', $purchase->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cet achat ?');">
                    @csrf
                    <button type="submit" class="btn-action cancel">
                        <i class="fas fa-times"></i>
                        <span>Annuler l'achat</span>
                    </button>
                </form>
            </div>
        </div>
        @endif

        <!-- Partage -->
        @if($purchase->status === 'completed' && $purchase->download_token)
        <div class="info-card">
            <div class="card-header">
                <i class="fas fa-share-alt"></i>
                <h3>Partage</h3>
            </div>
            <div class="action-buttons">
                @php
                    $customerName = $purchase->customer_name ?? ($purchase->user ? $purchase->user->name : 'Client');
                    $customerEmail = $purchase->customer_email ?? ($purchase->user ? $purchase->user->email : '');
                    $downloadLink = route('documents.download.token', ['token' => $purchase->download_token]) . '?email=' . urlencode($customerEmail);
                    $whatsappMessage = "Bonjour " . $customerName . ",\n\nMerci pour votre achat sur NiangProgrammeur !\n\nVoici votre lien de téléchargement pour le document : " . $purchase->document->title . "\n\n" . $downloadLink . "\n\nCordialement,\nL'équipe NiangProgrammeur";
                    $phoneNumber = '';
                    if ($purchase->customer_phone && $purchase->country_code) {
                        $phoneNumber = preg_replace('/[^0-9]/', '', $purchase->country_code . $purchase->customer_phone);
                    }
                    $whatsappUrl = $phoneNumber ? "https://wa.me/" . $phoneNumber . "?text=" . urlencode($whatsappMessage) : "https://wa.me/?text=" . urlencode($whatsappMessage);
                @endphp
                <button type="button" onclick="copyDownloadLink()" class="btn-action copy">
                    <i class="fas fa-link"></i>
                    <span>Copier le lien de téléchargement</span>
                </button>
                <a href="{{ $whatsappUrl }}" target="_blank" class="btn-action whatsapp">
                    <i class="fab fa-whatsapp"></i>
                    <span>Partager via WhatsApp</span>
                </a>
            </div>
        </div>
        @endif

        <!-- Historique des Téléchargements -->
        @if($purchase->downloads->count() > 0)
        <div class="info-card">
            <div class="card-header">
                <i class="fas fa-history"></i>
                <h3>Historique des Téléchargements</h3>
            </div>
            <div class="downloads-list">
                @foreach($purchase->downloads as $download)
                <div class="download-item">
                    <div class="download-date">
                        <i class="fas fa-download"></i>
                        {{ $download->downloaded_at->format('d/m/Y à H:i') }}
                    </div>
                    @if($download->ip_address)
                    <div class="download-ip">IP: {{ $download->ip_address }}</div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

@if($purchase->status === 'completed' && $purchase->download_token)
@section('scripts')
<script>
    function copyDownloadLink() {
        @php
            $customerEmail = $purchase->customer_email ?? ($purchase->user ? $purchase->user->email : '');
            $downloadLink = route('documents.download.token', ['token' => $purchase->download_token]) . '?email=' . urlencode($customerEmail);
        @endphp
        const downloadLink = '{{ $downloadLink }}';
        
        // Vérifier si l'API Clipboard est disponible
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(downloadLink).then(function() {
                showNotification('Lien copié avec succès !', 'success');
            }).catch(function(err) {
                console.error('Erreur lors de la copie:', err);
                // Fallback pour les navigateurs qui ne supportent pas clipboard API
                fallbackCopy(downloadLink);
            });
        } else {
            // Fallback pour les navigateurs qui ne supportent pas clipboard API
            fallbackCopy(downloadLink);
        }
    }

    function fallbackCopy(text) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        textArea.style.top = '-999999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        try {
            const successful = document.execCommand('copy');
            if (successful) {
                showNotification('Lien copié avec succès !', 'success');
            } else {
                showNotification('Impossible de copier le lien. Veuillez le copier manuellement.', 'error');
            }
        } catch (err) {
            console.error('Erreur lors de la copie:', err);
            showNotification('Impossible de copier le lien. Veuillez le copier manuellement.', 'error');
        }
        document.body.removeChild(textArea);
    }

    function showNotification(message, type) {
        // Supprimer les notifications existantes
        const existingNotifications = document.querySelectorAll('.copy-notification');
        existingNotifications.forEach(n => n.remove());
        
        const notification = document.createElement('div');
        notification.className = `copy-notification notification-${type}`;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            background: ${type === 'success' ? 'rgba(34, 197, 94, 0.95)' : 'rgba(239, 68, 68, 0.95)'};
            color: white;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
            z-index: 10000;
            animation: slideInRight 0.3s ease;
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
        `;
        notification.innerHTML = `
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
            <span>${message}</span>
        `;
        document.body.appendChild(notification);
        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
</script>
<style>
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
</style>
@endsection
@endif
@endsection
