@extends('admin.layout')

@section('title', 'Messages de contact | Admin')

@section('content')
<div class="messages-admin">
    <!-- Header Section -->
    <div class="messages-header">
        <div class="messages-header-content">
            <div class="messages-header-text">
                <h1 class="messages-title">
                    <span class="messages-icon-wrapper">
                        <i class="fas fa-envelope messages-icon"></i>
                    </span>
                    Messages de contact
                </h1>
                <p class="messages-subtitle">
                    Gérez les messages reçus depuis le formulaire de contact
                </p>
            </div>
            <div class="messages-filters-tabs">
                <a href="{{ route('admin.messages', ['filter' => 'all']) }}" 
                   class="filter-tab {{ $filter == 'all' ? 'filter-tab-active' : '' }}">
                    <i class="fas fa-inbox"></i>
                    <span>Tous</span>
                </a>
                <a href="{{ route('admin.messages', ['filter' => 'unread']) }}" 
                   class="filter-tab {{ $filter == 'unread' ? 'filter-tab-active' : '' }}">
                    <i class="fas fa-envelope"></i>
                    <span>Non lus</span>
                </a>
                <a href="{{ route('admin.messages', ['filter' => 'read']) }}" 
                   class="filter-tab {{ $filter == 'read' ? 'filter-tab-active' : '' }}">
                    <i class="fas fa-envelope-open"></i>
                    <span>Lus</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Messages Flash -->
    @if(session('success'))
    <div class="alert alert-success">
        <div class="alert-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="alert-content">
            <strong>Succès !</strong>
            <p>{{ session('success') }}</p>
        </div>
        <button class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-error">
        <div class="alert-icon">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <div class="alert-content">
            <strong>Erreur !</strong>
            <p>{{ session('error') }}</p>
        </div>
        <button class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="messages-stats">
        <div class="stat-card stat-total">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-inbox"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ \App\Models\ContactMessage::count() }}</div>
                <div class="stat-label">Total</div>
            </div>
        </div>
        <div class="stat-card stat-unread">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-envelope"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ \App\Models\ContactMessage::unread()->count() }}</div>
                <div class="stat-label">Non lus</div>
            </div>
        </div>
        <div class="stat-card stat-read">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-envelope-open"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ \App\Models\ContactMessage::read()->count() }}</div>
                <div class="stat-label">Lus</div>
            </div>
        </div>
    </div>

    <!-- Messages Table -->
    @if($messages->count() > 0)
    <div class="messages-table-wrapper">
        <table class="messages-table">
            <thead>
                <tr>
                    <th class="table-col-name">Nom</th>
                    <th class="table-col-email">Email</th>
                    <th class="table-col-phone">Téléphone</th>
                    <th class="table-col-subject">Sujet</th>
                    <th class="table-col-message">Message</th>
                    <th class="table-col-date">Date</th>
                    <th class="table-col-status">Statut</th>
                    <th class="table-col-actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($messages as $message)
                <tr class="table-row table-row-{{ $message->is_read ? 'read' : 'unread' }}">
                    <td class="table-cell table-cell-name">
                        <div class="cell-name-wrapper">
                            <div class="cell-avatar cell-avatar-{{ $message->is_read ? 'read' : 'unread' }}">
                                {{ strtoupper(substr($message->name, 0, 1)) }}
                            </div>
                            <div class="cell-name-text">{{ $message->name }}</div>
                        </div>
                    </td>
                    <td class="table-cell table-cell-email">
                        <a href="mailto:{{ $message->email }}" class="cell-email-link">{{ $message->email }}</a>
                    </td>
                    <td class="table-cell table-cell-phone">
                        @if($message->phone)
                            <a href="tel:{{ $message->phone }}" class="cell-phone-link">{{ $message->phone }}</a>
                        @else
                            <span class="cell-no-data">N/A</span>
                        @endif
                    </td>
                    <td class="table-cell table-cell-subject">
                        <div class="cell-subject-text">{{ \Illuminate\Support\Str::limit($message->subject, 40) }}</div>
                    </td>
                    <td class="table-cell table-cell-message">
                        <div class="cell-message-text">{{ \Illuminate\Support\Str::limit($message->message, 60) }}</div>
                    </td>
                    <td class="table-cell table-cell-date">
                        <div class="cell-date-text">
                            <i class="fas fa-calendar"></i>
                            {{ $message->created_at->format('d/m/Y') }}
                            <span class="cell-time">{{ $message->created_at->format('H:i') }}</span>
                        </div>
                    </td>
                    <td class="table-cell table-cell-status">
                        <span class="cell-status-badge cell-status-{{ $message->is_read ? 'read' : 'unread' }}">
                            @if($message->is_read)
                                <i class="fas fa-check-circle"></i>
                                <span>Lu</span>
                            @else
                                <i class="fas fa-circle"></i>
                                <span>Non lu</span>
                            @endif
                        </span>
                    </td>
                    <td class="table-cell table-cell-actions">
                        <div class="cell-actions-wrapper">
                            <button onclick="showMessageDetails({{ $message->id }})" class="action-btn-icon action-view" title="Voir les détails">
                                <i class="fas fa-eye"></i>
                            </button>
                            
                            @if(!$message->is_read)
                            <form action="{{ route('admin.messages.mark-read', $message->id) }}" method="POST" class="action-form-inline">
                                @csrf
                                <button type="submit" class="action-btn-icon action-mark-read" title="Marquer comme lu">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            @endif
                            
                            @if($message->phone)
                            <button onclick="openWhatsApp({{ $message->id }})" class="action-btn-icon action-contact" title="Ouvrir WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </button>
                            @endif
                            
                            <button onclick="openEmail({{ $message->id }})" class="action-btn-icon action-contact" title="Envoyer un email">
                                <i class="fas fa-envelope"></i>
                            </button>
                            
                            <form action="{{ route('admin.messages.delete', $message->id) }}" method="POST" class="action-form-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn-icon action-delete" title="Supprimer">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination-wrapper">
        {{ $messages->links() }}
    </div>
    @else
    <!-- Empty State -->
    <div class="empty-state">
        <div class="empty-state-icon">
            <i class="fas fa-inbox"></i>
        </div>
        <h3 class="empty-state-title">Aucun message trouvé</h3>
        <p class="empty-state-text">
            @if($filter != 'all')
                Aucun message {{ $filter == 'unread' ? 'non lu' : 'lu' }} pour le moment.
            @else
                Aucun message n'a été reçu pour le moment.
            @endif
        </p>
    </div>
    @endif
</div>

<!-- Modal pour les détails du message -->
<div id="messageModal" class="message-modal">
    <div class="message-modal-content">
        <div class="message-modal-header">
            <div class="message-modal-icon">
                <i class="fas fa-envelope-open-text"></i>
            </div>
            <h3 class="message-modal-title">Détails du message</h3>
            <button onclick="closeMessageModal()" class="message-modal-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="message-modal-body" id="messageDetails"></div>
    </div>
</div>

<style>
.messages-admin {
    padding: 2rem;
    max-width: 1600px;
    margin: 0 auto;
}

/* Header */
.messages-header {
    margin-bottom: 2rem;
}

.messages-header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 2rem;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(20, 184, 166, 0.1) 100%);
    border: 2px solid rgba(6, 182, 212, 0.3);
    border-radius: 24px;
    padding: 2.5rem;
    position: relative;
    overflow: hidden;
}

body.light-mode .messages-header-content {
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.08) 0%, rgba(20, 184, 166, 0.08) 100%);
    border-color: rgba(6, 182, 212, 0.4);
}

.messages-header-content::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(6, 182, 212, 0.1) 0%, transparent 70%);
    animation: rotate 20s linear infinite;
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.messages-header-text {
    position: relative;
    z-index: 1;
}

.messages-title {
    font-family: 'Poppins', sans-serif;
    font-size: 2.5rem;
    font-weight: 900;
    background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 50%, #06b6d4 100%);
    background-size: 200% auto;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: shimmer 3s linear infinite;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

@keyframes shimmer {
    to { background-position: 200% center; }
}

.messages-icon-wrapper {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
    border-radius: 16px;
    border: 2px solid rgba(6, 182, 212, 0.3);
}

.messages-icon {
    font-size: 1.8rem;
    color: #06b6d4;
}

.messages-subtitle {
    font-size: 1.1rem;
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
}

body.light-mode .messages-subtitle {
    color: #64748b;
}

.messages-filters-tabs {
    position: relative;
    z-index: 1;
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.filter-tab {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: rgba(107, 114, 128, 0.2);
    border: 1px solid rgba(107, 114, 128, 0.4);
    border-radius: 12px;
    color: white;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

body.light-mode .filter-tab {
    background: rgba(107, 114, 128, 0.1);
    color: #475569;
}

.filter-tab:hover {
    background: rgba(107, 114, 128, 0.3);
    transform: translateY(-2px);
}

.filter-tab-active {
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    border-color: rgba(6, 182, 212, 0.5);
    color: white;
    box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
}

.filter-tab-active:hover {
    box-shadow: 0 6px 20px rgba(6, 182, 212, 0.4);
}

/* Alerts */
.alert {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem 1.5rem;
    border-radius: 16px;
    margin-bottom: 2rem;
    animation: slideIn 0.3s ease;
    position: relative;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.alert-success {
    background: rgba(16, 185, 129, 0.15);
    border: 2px solid rgba(16, 185, 129, 0.3);
    color: #10b981;
}

.alert-error {
    background: rgba(239, 68, 68, 0.15);
    border: 2px solid rgba(239, 68, 68, 0.3);
    color: #ef4444;
}

.alert-icon {
    font-size: 1.5rem;
}

.alert-content {
    flex: 1;
}

.alert-content strong {
    display: block;
    margin-bottom: 0.25rem;
    font-weight: 700;
}

.alert-content p {
    margin: 0;
    opacity: 0.9;
}

.alert-close {
    background: transparent;
    border: none;
    color: inherit;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 8px;
    transition: background 0.2s;
}

.alert-close:hover {
    background: rgba(255, 255, 255, 0.1);
}

/* Stats */
.messages-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8));
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 20px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

body.light-mode .stat-card {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95));
    border-color: rgba(6, 182, 212, 0.3);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    transition: width 0.3s;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(6, 182, 212, 0.3);
}

.stat-card:hover::before {
    width: 100%;
    opacity: 0.1;
}

.stat-total::before {
    background: linear-gradient(180deg, #06b6d4, #14b8a6);
}

.stat-unread::before {
    background: linear-gradient(180deg, #fb923c, #f97316);
}

.stat-read::before {
    background: linear-gradient(180deg, #10b981, #059669);
}

.stat-icon-wrapper {
    position: relative;
    z-index: 1;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.stat-total .stat-icon {
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
    color: #06b6d4;
}

.stat-unread .stat-icon {
    background: linear-gradient(135deg, rgba(251, 146, 60, 0.2), rgba(249, 115, 22, 0.2));
    color: #fb923c;
}

.stat-read .stat-icon {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(5, 150, 105, 0.2));
    color: #10b981;
}

.stat-content {
    flex: 1;
    position: relative;
    z-index: 1;
}

.stat-value {
    font-size: 2rem;
    font-weight: 800;
    color: white;
    line-height: 1;
    margin-bottom: 0.25rem;
}

body.light-mode .stat-value {
    color: #1e293b;
}

.stat-label {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.7);
}

body.light-mode .stat-label {
    color: #64748b;
}

/* Messages Table */
.messages-table-wrapper {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8));
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 20px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    overflow-x: auto;
    overflow-y: hidden;
    position: relative;
    scrollbar-width: thin;
    scrollbar-color: rgba(6, 182, 212, 0.6) rgba(6, 182, 212, 0.1);
}

body.light-mode .messages-table-wrapper {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95));
    border-color: rgba(6, 182, 212, 0.3);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    scrollbar-color: rgba(6, 182, 212, 0.5) rgba(6, 182, 212, 0.1);
}

.messages-table-wrapper::-webkit-scrollbar {
    height: 14px;
}

.messages-table-wrapper::-webkit-scrollbar-track {
    background: rgba(6, 182, 212, 0.1);
    border-radius: 10px;
    margin: 0 1rem;
    box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
}

body.light-mode .messages-table-wrapper::-webkit-scrollbar-track {
    background: rgba(6, 182, 212, 0.08);
}

.messages-table-wrapper::-webkit-scrollbar-thumb {
    background: linear-gradient(90deg, rgba(6, 182, 212, 0.6), rgba(20, 184, 166, 0.6));
    border-radius: 10px;
    border: 3px solid transparent;
    background-clip: padding-box;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.messages-table-wrapper::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(90deg, rgba(6, 182, 212, 0.8), rgba(20, 184, 166, 0.8));
    background-clip: padding-box;
    transform: scale(1.02);
    box-shadow: 0 0 10px rgba(6, 182, 212, 0.4);
}

.messages-table-wrapper::-webkit-scrollbar-thumb:active {
    background: linear-gradient(90deg, #06b6d4, #14b8a6);
    background-clip: padding-box;
    box-shadow: 0 0 15px rgba(6, 182, 212, 0.6);
}

body.light-mode .messages-table-wrapper::-webkit-scrollbar-thumb {
    background: linear-gradient(90deg, rgba(6, 182, 212, 0.5), rgba(20, 184, 166, 0.5));
}

body.light-mode .messages-table-wrapper::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(90deg, rgba(6, 182, 212, 0.7), rgba(20, 184, 166, 0.7));
}

body.light-mode .messages-table-wrapper::-webkit-scrollbar-thumb:active {
    background: linear-gradient(90deg, #06b6d4, #14b8a6);
}

.messages-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    min-width: 1200px;
}

.messages-table thead {
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.15), rgba(20, 184, 166, 0.15));
}

body.light-mode .messages-table thead {
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.1));
}

.messages-table th {
    padding: 1.25rem 1rem;
    text-align: left;
    font-weight: 800;
    font-size: 0.9rem;
    color: #06b6d4;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid rgba(6, 182, 212, 0.3);
    white-space: nowrap;
}

body.light-mode .messages-table th {
    color: #06b6d4;
}

.messages-table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid rgba(6, 182, 212, 0.1);
}

.messages-table tbody tr:last-child {
    border-bottom: none;
}

.messages-table tbody tr:hover {
    background: rgba(6, 182, 212, 0.05);
}

body.light-mode .messages-table tbody tr:hover {
    background: rgba(6, 182, 212, 0.03);
}

.table-row-unread {
    background: rgba(251, 146, 60, 0.08);
    border-left: 4px solid #fb923c;
}

body.light-mode .table-row-unread {
    background: rgba(251, 146, 60, 0.05);
}

.table-row-unread:hover {
    background: rgba(251, 146, 60, 0.15);
}

body.light-mode .table-row-unread:hover {
    background: rgba(251, 146, 60, 0.1);
}

.table-row-read {
    background: rgba(16, 185, 129, 0.08);
    border-left: 4px solid #10b981;
}

body.light-mode .table-row-read {
    background: rgba(16, 185, 129, 0.05);
}

.table-row-read:hover {
    background: rgba(16, 185, 129, 0.15);
}

body.light-mode .table-row-read:hover {
    background: rgba(16, 185, 129, 0.1);
}

.messages-table td {
    padding: 1.25rem 1rem;
    vertical-align: middle;
}

/* Colonnes */
.table-col-name { width: 180px; }
.table-col-email { width: 220px; }
.table-col-phone { width: 150px; }
.table-col-subject { width: 200px; }
.table-col-message { width: 300px; }
.table-col-date { width: 150px; }
.table-col-status { width: 120px; }
.table-col-actions { width: 200px; }

/* Cellules */
.table-cell {
    color: rgba(255, 255, 255, 0.9);
}

body.light-mode .table-cell {
    color: #334155;
}

.cell-name-wrapper {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.cell-avatar {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    font-weight: 700;
    border: 2px solid;
    flex-shrink: 0;
}

.cell-avatar-unread {
    background: linear-gradient(135deg, rgba(251, 146, 60, 0.2), rgba(249, 115, 22, 0.2));
    border-color: rgba(251, 146, 60, 0.4);
    color: #fb923c;
}

.cell-avatar-read {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(5, 150, 105, 0.2));
    border-color: rgba(16, 185, 129, 0.4);
    color: #10b981;
}

.cell-name-text {
    font-weight: 700;
    font-size: 0.95rem;
    color: white;
}

body.light-mode .cell-name-text {
    color: #1e293b;
}

.cell-email-link,
.cell-phone-link {
    color: #06b6d4;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.2s;
}

.cell-email-link:hover,
.cell-phone-link:hover {
    color: #14b8a6;
    text-decoration: underline;
}

.cell-subject-text,
.cell-message-text {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.8);
    line-height: 1.4;
}

body.light-mode .cell-subject-text,
body.light-mode .cell-message-text {
    color: #475569;
}

.cell-date-text {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.8);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-direction: column;
    align-items: flex-start;
}

body.light-mode .cell-date-text {
    color: #475569;
}

.cell-date-text i {
    color: #06b6d4;
}

.cell-time {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.6);
    display: block;
    margin-top: 0.25rem;
}

body.light-mode .cell-time {
    color: #94a3b8;
}

.cell-no-data {
    color: rgba(255, 255, 255, 0.5);
    font-style: italic;
}

body.light-mode .cell-no-data {
    color: #94a3b8;
}

.cell-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.75rem;
    border-radius: 10px;
    font-size: 0.8rem;
    font-weight: 700;
    white-space: nowrap;
}

.cell-status-read {
    background: rgba(16, 185, 129, 0.2);
    border: 1px solid rgba(16, 185, 129, 0.4);
    color: #10b981;
}

.cell-status-unread {
    background: rgba(251, 146, 60, 0.2);
    border: 1px solid rgba(251, 146, 60, 0.4);
    color: #fb923c;
}

.cell-actions-wrapper {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.action-form-inline {
    display: inline;
    margin: 0;
}

.action-btn-icon {
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    border-radius: 8px;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s ease;
    background: transparent;
}

.action-view {
    color: #3b82f6;
    background: rgba(59, 130, 246, 0.15);
    border: 1px solid rgba(59, 130, 246, 0.3);
}

.action-view:hover {
    background: rgba(59, 130, 246, 0.25);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.action-mark-read {
    color: #10b981;
    background: rgba(16, 185, 129, 0.15);
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.action-mark-read:hover {
    background: rgba(16, 185, 129, 0.25);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.action-contact {
    color: #06b6d4;
    background: rgba(6, 182, 212, 0.15);
    border: 1px solid rgba(6, 182, 212, 0.3);
}

.action-contact:hover {
    background: rgba(6, 182, 212, 0.25);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(6, 182, 212, 0.3);
}

.action-delete {
    color: #ef4444;
    background: rgba(239, 68, 68, 0.15);
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.action-delete:hover {
    background: rgba(239, 68, 68, 0.25);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

/* Pagination */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.6), rgba(51, 65, 85, 0.6));
    border: 2px dashed rgba(6, 182, 212, 0.3);
    border-radius: 24px;
}

body.light-mode .empty-state {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.8), rgba(248, 250, 252, 0.8));
    border-color: rgba(6, 182, 212, 0.4);
}

.empty-state-icon {
    width: 120px;
    height: 120px;
    margin: 0 auto 2rem;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.1));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 4rem;
    color: rgba(6, 182, 212, 0.5);
    border: 3px dashed rgba(6, 182, 212, 0.3);
}

.empty-state-title {
    font-size: 1.75rem;
    font-weight: 800;
    color: white;
    margin-bottom: 1rem;
}

body.light-mode .empty-state-title {
    color: #1e293b;
}

.empty-state-text {
    font-size: 1.1rem;
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 2rem;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

body.light-mode .empty-state-text {
    color: #64748b;
}

/* Message Modal */
.message-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(5px);
    z-index: 10000;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.message-modal-content {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.95), rgba(51, 65, 85, 0.95));
    border: 2px solid rgba(6, 182, 212, 0.3);
    border-radius: 24px;
    padding: 2rem;
    max-width: 700px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

body.light-mode .message-modal-content {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.98), rgba(248, 250, 252, 0.98));
    border-color: rgba(6, 182, 212, 0.4);
}

.message-modal-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid rgba(6, 182, 212, 0.2);
}

.message-modal-icon {
    width: 50px;
    height: 50px;
    background: rgba(6, 182, 212, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #06b6d4;
}

.message-modal-title {
    flex: 1;
    font-size: 1.5rem;
    font-weight: 800;
    color: white;
    margin: 0;
}

body.light-mode .message-modal-title {
    color: #1e293b;
}

.message-modal-close {
    width: 40px;
    height: 40px;
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.3);
    border-radius: 10px;
    color: #ef4444;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.message-modal-close:hover {
    background: rgba(239, 68, 68, 0.2);
    transform: rotate(90deg);
}

.message-modal-body {
    display: grid;
    gap: 1rem;
}

.message-modal-detail {
    padding: 1rem;
    background: rgba(6, 182, 212, 0.05);
    border-radius: 12px;
}

body.light-mode .message-modal-detail {
    background: rgba(6, 182, 212, 0.03);
}

.message-modal-detail-label {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.6);
    margin-bottom: 0.5rem;
    font-weight: 600;
}

body.light-mode .message-modal-detail-label {
    color: #94a3b8;
}

.message-modal-detail-value {
    font-size: 1rem;
    color: white;
    word-break: break-word;
}

body.light-mode .message-modal-detail-value {
    color: #1e293b;
}

.message-modal-content-box {
    background: rgba(15, 23, 42, 0.5);
    border: 1px solid rgba(6, 182, 212, 0.2);
    border-radius: 12px;
    padding: 1.5rem;
    margin-top: 0.5rem;
    white-space: pre-wrap;
    line-height: 1.6;
}

body.light-mode .message-modal-content-box {
    background: rgba(6, 182, 212, 0.05);
}

/* Responsive */
@media (max-width: 768px) {
    .messages-title {
        font-size: 1.75rem;
    }
    
    .messages-icon-wrapper {
        width: 50px;
        height: 50px;
    }
    
    .messages-icon {
        font-size: 1.5rem;
    }
    
    .messages-filters-tabs {
        flex-direction: column;
        width: 100%;
    }
    
    .filter-tab {
        width: 100%;
        justify-content: center;
    }
    
    .messages-table-wrapper {
        padding: 1rem;
    }
    
    .messages-table {
        min-width: 1000px;
    }
    
    .messages-table th,
    .messages-table td {
        padding: 0.75rem 0.5rem;
        font-size: 0.85rem;
    }
    
    .cell-actions-wrapper {
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .action-btn-icon {
        width: 32px;
        height: 32px;
    }
}
</style>

<script>
const messagesData = {
    @foreach($messages as $message)
    {{ $message->id }}: {
        id: {{ $message->id }},
        name: @json($message->name),
        email: @json($message->email),
        phone: @json($message->phone),
        subject: @json($message->subject),
        message: @json($message->message),
        is_read: {{ $message->is_read ? 'true' : 'false' }},
        created_at: @json($message->created_at->format('d/m/Y à H:i')),
    },
    @endforeach
};

function showMessageDetails(messageId) {
    const message = messagesData[messageId];
    if (!message) return;
    
    const modal = document.getElementById('messageModal');
    const details = document.getElementById('messageDetails');
    
    details.innerHTML = `
        <div class="message-modal-detail">
            <div class="message-modal-detail-label">Nom</div>
            <div class="message-modal-detail-value font-semibold">${message.name}</div>
        </div>
        
        <div class="message-modal-detail">
            <div class="message-modal-detail-label">Email</div>
            <div class="message-modal-detail-value">
                <a href="mailto:${message.email}" style="color: #06b6d4; text-decoration: none;">${message.email}</a>
            </div>
        </div>
        
        ${message.phone ? `
        <div class="message-modal-detail">
            <div class="message-modal-detail-label">Téléphone</div>
            <div class="message-modal-detail-value">
                <a href="tel:${message.phone}" style="color: #06b6d4; text-decoration: none;">${message.phone}</a>
            </div>
        </div>
        ` : ''}
        
        <div class="message-modal-detail">
            <div class="message-modal-detail-label">Sujet</div>
            <div class="message-modal-detail-value font-semibold">${message.subject}</div>
        </div>
        
        <div class="message-modal-detail">
            <div class="message-modal-detail-label">Message</div>
            <div class="message-modal-content-box">${message.message}</div>
        </div>
        
        <div class="message-modal-detail">
            <div class="message-modal-detail-label">Date</div>
            <div class="message-modal-detail-value">${message.created_at}</div>
        </div>
        
        <div class="message-modal-detail">
            <div class="message-modal-detail-label">Statut</div>
            <div class="message-modal-detail-value">
                ${message.is_read ? '<span class="cell-status-badge cell-status-read"><i class="fas fa-check-circle"></i> Lu</span>' : '<span class="cell-status-badge cell-status-unread"><i class="fas fa-circle"></i> Non lu</span>'}
            </div>
        </div>
    `;
    
    modal.style.display = 'flex';
}

function closeMessageModal() {
    document.getElementById('messageModal').style.display = 'none';
}

// Fermer la modal en cliquant en dehors
document.getElementById('messageModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeMessageModal();
    }
});

// Fermer avec Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeMessageModal();
    }
});

function openWhatsApp(messageId) {
    const message = messagesData[messageId];
    if (!message || !message.phone) {
        alert('Aucun numéro de téléphone disponible pour ce message');
        return;
    }
    
    const phone = message.phone.replace(/\s+/g, '').replace(/-/g, '').replace(/\./g, '');
    const messageText = encodeURIComponent(`Bonjour ${message.name},\n\nMerci pour votre message concernant "${message.subject}".`);
    const whatsappUrl = `https://wa.me/${phone}?text=${messageText}`;
    
    window.open(whatsappUrl, '_blank');
}

function openEmail(messageId) {
    const message = messagesData[messageId];
    if (!message || !message.email) {
        alert('Aucune adresse email disponible pour ce message');
        return;
    }
    
    const subject = encodeURIComponent(`Re: ${message.subject}`);
    const body = encodeURIComponent(`Bonjour ${message.name},\n\nMerci pour votre message.\n\nCordialement,`);
    const emailUrl = `mailto:${message.email}?subject=${subject}&body=${body}`;
    
    window.location.href = emailUrl;
}
</script>
@endsection
