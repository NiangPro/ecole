@extends('admin.layout')

@section('title', 'Messages de contact | Admin')

@section('styles')
<style>
    /* Styles pour la page Messages */
    .messages-page h3 {
        color: #fff;
        transition: color 0.3s ease;
    }
    
    body.light-mode .messages-page h3 {
        color: #1e293b;
    }
    
    .messages-page .text-gray-400 {
        color: rgba(156, 163, 175, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .messages-page .text-gray-400 {
        color: rgba(100, 116, 139, 1);
    }
    
    .messages-page .text-gray-300 {
        color: rgba(209, 213, 219, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .messages-page .text-gray-300 {
        color: rgba(30, 41, 59, 0.8);
    }
    
    body.light-mode .messages-page .text-white {
        color: #1e293b;
    }
    
    /* Table design */
    .messages-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .messages-table thead {
        background: rgba(6, 182, 212, 0.1);
        border-bottom: 2px solid rgba(6, 182, 212, 0.3);
    }
    
    body.light-mode .messages-table thead {
        background: rgba(6, 182, 212, 0.05);
        border-bottom-color: rgba(6, 182, 212, 0.2);
    }
    
    .messages-table th {
        padding: 12px 8px;
        text-align: left;
        font-weight: 600;
        font-size: 0.875rem;
        color: #06b6d4;
        white-space: nowrap;
    }
    
    body.light-mode .messages-table th {
        color: #0891b2;
    }
    
    .messages-table td {
        padding: 10px 8px;
        border-bottom: 1px solid rgba(55, 65, 81, 0.3);
        vertical-align: middle;
    }
    
    body.light-mode .messages-table td {
        border-bottom-color: rgba(226, 232, 240, 0.5);
    }
    
    .messages-table tbody tr {
        transition: background 0.2s ease;
    }
    
    .messages-table tbody tr:hover {
        background: rgba(6, 182, 212, 0.05);
    }
    
    body.light-mode .messages-table tbody tr:hover {
        background: rgba(6, 182, 212, 0.02);
    }
    
    /* Avatar compact */
    .message-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: bold;
        flex-shrink: 0;
    }
    
    /* Status badge compact */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        white-space: nowrap;
    }
    
    .status-unread {
        background: rgba(251, 146, 60, 0.15);
        color: #fb923c;
    }
    
    .status-read {
        background: rgba(34, 197, 94, 0.15);
        color: #22c55e;
    }
    
    /* Subject preview */
    .subject-preview {
        max-width: 250px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-size: 0.875rem;
        color: rgba(209, 213, 219, 1);
    }
    
    body.light-mode .subject-preview {
        color: rgba(30, 41, 59, 0.8);
    }
    
    /* Action buttons compact */
    .action-btn {
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 0.75rem;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    
    .action-btn:hover {
        transform: translateY(-1px);
    }
    
    .btn-mark-read {
        background: rgba(34, 197, 94, 0.15);
        color: #22c55e;
    }
    
    .btn-mark-read:hover {
        background: rgba(34, 197, 94, 0.25);
    }
    
    .btn-delete {
        background: rgba(239, 68, 68, 0.15);
        color: #ef4444;
    }
    
    .btn-delete:hover {
        background: rgba(239, 68, 68, 0.25);
    }
    
    .btn-contact {
        background: rgba(6, 182, 212, 0.15);
        color: #06b6d4;
    }
    
    .btn-contact:hover {
        background: rgba(6, 182, 212, 0.25);
    }
    
    /* Modal ultra moderne */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.85);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.3s ease;
    }
    
    body.light-mode .modal-overlay {
        background: rgba(0, 0, 0, 0.6);
    }
    
    .modal-overlay.active {
        display: flex;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    .modal-content {
        background: linear-gradient(145deg, rgba(15, 23, 42, 0.95), rgba(30, 41, 59, 0.95));
        backdrop-filter: blur(20px) saturate(180%);
        -webkit-backdrop-filter: blur(20px) saturate(180%);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 24px;
        padding: 0;
        max-width: 850px;
        width: 90%;
        max-height: 90vh;
        overflow: hidden;
        box-shadow: 
            0 25px 50px -12px rgba(0, 0, 0, 0.5),
            0 0 0 1px rgba(6, 182, 212, 0.1),
            inset 0 1px 0 rgba(255, 255, 255, 0.05);
        animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
    }
    
    .modal-content::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, 
            transparent, 
            rgba(6, 182, 212, 0.5), 
            rgba(20, 184, 166, 0.5), 
            transparent
        );
    }
    
    body.light-mode .modal-content {
        background: linear-gradient(145deg, rgba(255, 255, 255, 0.98), rgba(248, 250, 252, 0.98));
        border-color: rgba(6, 182, 212, 0.3);
        box-shadow: 
            0 25px 50px -12px rgba(0, 0, 0, 0.15),
            0 0 0 1px rgba(6, 182, 212, 0.1),
            inset 0 1px 0 rgba(255, 255, 255, 0.8);
    }
    
    body.light-mode .modal-content::before {
        background: linear-gradient(90deg, 
            transparent, 
            rgba(6, 182, 212, 0.3), 
            rgba(20, 184, 166, 0.3), 
            transparent
        );
    }
    
    .modal-header {
        background: linear-gradient(135deg, 
            rgba(6, 182, 212, 0.08), 
            rgba(20, 184, 166, 0.08),
            rgba(6, 182, 212, 0.05)
        );
        border-bottom: 1px solid rgba(6, 182, 212, 0.15);
        padding: 24px 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
    }
    
    .modal-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, 
            transparent, 
            rgba(6, 182, 212, 0.3), 
            transparent
        );
    }
    
    body.light-mode .modal-header {
        background: linear-gradient(135deg, 
            rgba(6, 182, 212, 0.04), 
            rgba(20, 184, 166, 0.04),
            rgba(6, 182, 212, 0.02)
        );
        border-bottom-color: rgba(6, 182, 212, 0.12);
    }
    
    .modal-body {
        padding: 28px;
        overflow-y: auto;
        max-height: calc(90vh - 100px);
        scrollbar-width: thin;
        scrollbar-color: rgba(6, 182, 212, 0.3) transparent;
    }
    
    .modal-body::-webkit-scrollbar {
        width: 6px;
    }
    
    .modal-body::-webkit-scrollbar-track {
        background: transparent;
    }
    
    .modal-body::-webkit-scrollbar-thumb {
        background: rgba(6, 182, 212, 0.3);
        border-radius: 3px;
    }
    
    .modal-body::-webkit-scrollbar-thumb:hover {
        background: rgba(6, 182, 212, 0.5);
    }
    
    .modal-info-item {
        margin-bottom: 24px;
        padding: 20px;
        background: linear-gradient(135deg, 
            rgba(6, 182, 212, 0.05), 
            rgba(20, 184, 166, 0.03)
        );
        border: 1px solid rgba(6, 182, 212, 0.15);
        border-radius: 16px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .modal-info-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 3px;
        height: 100%;
        background: linear-gradient(180deg, #06b6d4, #14b8a6);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .modal-info-item:hover {
        transform: translateX(4px);
        border-color: rgba(6, 182, 212, 0.3);
        box-shadow: 0 4px 12px rgba(6, 182, 212, 0.1);
    }
    
    .modal-info-item:hover::before {
        opacity: 1;
    }
    
    body.light-mode .modal-info-item {
        background: linear-gradient(135deg, 
            rgba(6, 182, 212, 0.03), 
            rgba(20, 184, 166, 0.02)
        );
        border-color: rgba(6, 182, 212, 0.12);
    }
    
    body.light-mode .modal-info-item:hover {
        border-color: rgba(6, 182, 212, 0.25);
        box-shadow: 0 4px 12px rgba(6, 182, 212, 0.08);
    }
    
    .modal-info-item:last-child {
        margin-bottom: 0;
    }
    
    .modal-info-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        color: #06b6d4;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
        position: relative;
    }
    
    .modal-info-label i {
        font-size: 0.85rem;
        width: 20px;
        text-align: center;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
        padding: 6px;
        border-radius: 8px;
        border: 1px solid rgba(6, 182, 212, 0.2);
    }
    
    body.light-mode .modal-info-label {
        color: #0891b2;
    }
    
    body.light-mode .modal-info-label i {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.15), rgba(20, 184, 166, 0.15));
        border-color: rgba(6, 182, 212, 0.2);
    }
    
    .modal-info-value {
        font-size: 1rem;
        color: rgba(209, 213, 219, 1);
        word-break: break-word;
        line-height: 1.6;
    }
    
    body.light-mode .modal-info-value {
        color: rgba(30, 41, 59, 0.9);
    }
    
    .modal-message-box {
        background: linear-gradient(135deg, 
            rgba(0, 0, 0, 0.4), 
            rgba(15, 23, 42, 0.4)
        );
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 16px;
        padding: 24px;
        margin-top: 12px;
        position: relative;
        box-shadow: 
            inset 0 1px 0 rgba(255, 255, 255, 0.05),
            0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .modal-message-box::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, 
            transparent, 
            rgba(6, 182, 212, 0.5), 
            rgba(20, 184, 166, 0.5), 
            transparent
        );
        border-radius: 16px 16px 0 0;
    }
    
    body.light-mode .modal-message-box {
        background: linear-gradient(135deg, 
            rgba(248, 250, 252, 0.9), 
            rgba(241, 245, 249, 0.9)
        );
        border-color: rgba(6, 182, 212, 0.25);
        box-shadow: 
            inset 0 1px 0 rgba(255, 255, 255, 0.8),
            0 4px 12px rgba(0, 0, 0, 0.05);
    }
    
    .modal-close-btn {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, 
            rgba(239, 68, 68, 0.1), 
            rgba(220, 38, 38, 0.1)
        );
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.2);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    
    .modal-close-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(239, 68, 68, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.3s ease, height 0.3s ease;
    }
    
    .modal-close-btn:hover {
        background: linear-gradient(135deg, 
            rgba(239, 68, 68, 0.2), 
            rgba(220, 38, 38, 0.2)
        );
        transform: rotate(90deg) scale(1.1);
        border-color: rgba(239, 68, 68, 0.4);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
    }
    
    .modal-close-btn:hover::before {
        width: 100%;
        height: 100%;
    }
    
    .modal-close-btn i {
        position: relative;
        z-index: 1;
    }
    
    .modal-title {
        font-size: 1.75rem;
        font-weight: 800;
        background: linear-gradient(135deg, #06b6d4, #14b8a6, #06b6d4);
        background-size: 200% 200%;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: gradientShift 3s ease infinite;
        letter-spacing: -0.5px;
    }
    
    @keyframes gradientShift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }
    
    body.light-mode .modal-title {
        background: linear-gradient(135deg, #0891b2, #0d9488, #0891b2);
        background-size: 200% 200%;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .modal-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    
    @media (max-width: 640px) {
        .modal-grid {
            grid-template-columns: 1fr;
        }
    }
    
    /* Responsive */
    @media (max-width: 1024px) {
        .messages-table {
            font-size: 0.8rem;
        }
        
        .messages-table th,
        .messages-table td {
            padding: 8px 6px;
        }
        
        .subject-preview {
            max-width: 150px;
        }
    }
    
    @media (max-width: 768px) {
        .messages-table {
            display: block;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .messages-table thead,
        .messages-table tbody,
        .messages-table tr,
        .messages-table td,
        .messages-table th {
            display: block;
        }
        
        .messages-table thead {
            display: none;
        }
        
        .messages-table tr {
            margin-bottom: 12px;
            border: 1px solid rgba(55, 65, 81, 0.3);
            border-radius: 8px;
            padding: 12px;
            background: rgba(0, 0, 0, 0.2);
        }
        
        body.light-mode .messages-table tr {
            background: rgba(255, 255, 255, 0.5);
            border-color: rgba(226, 232, 240, 0.5);
        }
        
        .messages-table td {
            border: none;
            padding: 6px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .messages-table td:before {
            content: attr(data-label);
            font-weight: 600;
            color: #06b6d4;
            margin-right: 12px;
        }
        
        .subject-preview {
            max-width: 100%;
            white-space: normal;
        }
    }
    
    /* Bulk actions */
    .bulk-actions {
        display: flex;
        gap: 8px;
        align-items: center;
        margin-bottom: 16px;
        padding: 12px;
        background: rgba(6, 182, 212, 0.1);
        border-radius: 8px;
    }
    
    body.light-mode .bulk-actions {
        background: rgba(6, 182, 212, 0.05);
    }
</style>
@endsection

@section('content')
<div class="messages-page">
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h3 class="text-3xl font-bold mb-2">Messages de contact</h3>
        <p class="text-gray-400">Gérez les messages reçus depuis le formulaire de contact</p>
    </div>
    
    <div class="flex gap-3">
        <a href="{{ route('admin.messages', ['filter' => 'all']) }}" 
           class="px-4 py-2 rounded-lg font-semibold transition {{ $filter == 'all' ? 'bg-cyan-500 text-black' : 'bg-gray-700 text-gray-300 hover:bg-gray-600' }}">
            <i class="fas fa-inbox mr-2"></i>Tous
        </a>
        <a href="{{ route('admin.messages', ['filter' => 'unread']) }}" 
           class="px-4 py-2 rounded-lg font-semibold transition {{ $filter == 'unread' ? 'bg-cyan-500 text-black' : 'bg-gray-700 text-gray-300 hover:bg-gray-600' }}">
            <i class="fas fa-envelope mr-2"></i>Non lus
        </a>
        <a href="{{ route('admin.messages', ['filter' => 'read']) }}" 
           class="px-4 py-2 rounded-lg font-semibold transition {{ $filter == 'read' ? 'bg-cyan-500 text-black' : 'bg-gray-700 text-gray-300 hover:bg-gray-600' }}">
            <i class="fas fa-envelope-open mr-2"></i>Lus
        </a>
    </div>
</div>

<!-- Statistiques -->
<div class="grid md:grid-cols-3 gap-6 mb-8">
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <i class="fas fa-inbox text-4xl text-cyan-400"></i>
        </div>
        <div class="stat-number">{{ \App\Models\ContactMessage::count() }}</div>
        <p class="text-gray-400 mt-2">Total messages</p>
    </div>
    
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <i class="fas fa-envelope text-4xl text-orange-400"></i>
        </div>
        <div class="stat-number">{{ \App\Models\ContactMessage::unread()->count() }}</div>
        <p class="text-gray-400 mt-2">Non lus</p>
    </div>
    
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <i class="fas fa-envelope-open text-4xl text-green-400"></i>
        </div>
        <div class="stat-number">{{ \App\Models\ContactMessage::read()->count() }}</div>
        <p class="text-gray-400 mt-2">Lus</p>
    </div>
</div>

<!-- Liste des messages en tableau -->
<div class="content-section">
    @if($messages->count() > 0)
    <div class="overflow-x-auto">
        <table class="messages-table">
            <thead>
                <tr>
                    <th style="width: 40px;">
                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                    </th>
                    <th style="width: 60px;">Avatar</th>
                    <th style="min-width: 150px;">Nom</th>
                    <th style="min-width: 120px;">Téléphone</th>
                    <th style="width: 100px;">Statut</th>
                    <th style="width: 100px;">Date</th>
                    <th style="width: 200px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($messages as $message)
                <tr data-message-id="{{ $message->id }}" class="{{ !$message->is_read ? 'bg-orange-500/5' : '' }}">
                    <td>
                        <input type="checkbox" class="message-checkbox" value="{{ $message->id }}">
                    </td>
                    <td>
                        <div class="message-avatar bg-gradient-to-br from-cyan-500 to-teal-500 text-white">
                        {{ strtoupper(substr($message->name, 0, 1)) }}
                        </div>
                    </td>
                    <td data-label="Nom">
                        <div class="font-semibold text-white">{{ $message->name }}</div>
                    </td>
                    <td data-label="Téléphone">
                        @if($message->phone)
                        <a href="tel:{{ $message->phone }}" class="text-cyan-400 hover:text-cyan-300 text-sm">
                            {{ $message->phone }}
                        </a>
                        @else
                        <span class="text-gray-500">-</span>
                        @endif
                    </td>
                    <td data-label="Statut">
                        @if(!$message->is_read)
                        <span class="status-badge status-unread">
                            <i class="fas fa-circle text-xs"></i> Non lu
                            </span>
                        @else
                        <span class="status-badge status-read">
                            <i class="fas fa-check-circle"></i> Lu
                            </span>
                            @endif
                    </td>
                    <td data-label="Date">
                        <div class="text-xs text-gray-400">
                            {{ $message->created_at->format('d/m/Y') }}
                            <br>
                            {{ $message->created_at->format('H:i') }}
                        </div>
                    </td>
                    <td data-label="Actions">
                        <div class="flex flex-wrap gap-1">
                    @if(!$message->is_read)
                            <form action="{{ route('admin.messages.mark-read', $message->id) }}" method="POST" class="inline">
                        @csrf
                                <button type="submit" class="action-btn btn-mark-read" title="Marquer comme lu">
                            <i class="fas fa-check"></i>
                        </button>
                    </form>
                    @endif
                            
                            @if($message->phone)
                            <button onclick="openWhatsApp({{ $message->id }})" class="action-btn btn-contact" title="Contacter par WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </button>
                            @else
                            <button class="action-btn btn-contact" style="opacity: 0.4; cursor: not-allowed;" title="Pas de numéro de téléphone" disabled>
                                <i class="fab fa-whatsapp"></i>
                            </button>
                            @endif
                            
                            <button onclick="openEmail({{ $message->id }})" class="action-btn btn-contact" title="Contacter par Email">
                                <i class="fas fa-envelope"></i>
                            </button>
                            
                            <button onclick="showMessageDetails({{ $message->id }})" class="action-btn btn-contact" title="Voir les détails">
                                <i class="fas fa-eye"></i>
                            </button>
                    
                    <form action="{{ route('admin.messages.delete', $message->id) }}" method="POST" 
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?')" class="inline">
                        @csrf
                        @method('DELETE')
                                <button type="submit" class="action-btn btn-delete" title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
            </div>
            
    <!-- Actions en masse -->
    <div class="bulk-actions" id="bulkActions" style="display: none;">
        <span class="text-white font-semibold" id="selectedCount">0 sélectionné</span>
        <button onclick="bulkMarkRead()" class="action-btn btn-mark-read">
            <i class="fas fa-check"></i> Marquer comme lu
        </button>
        <button onclick="bulkDelete()" class="action-btn btn-delete">
            <i class="fas fa-trash"></i> Supprimer
        </button>
        <button onclick="clearSelection()" class="action-btn btn-contact">
            <i class="fas fa-times"></i> Annuler
        </button>
    </div>
    
    <!-- Pagination -->
    @if($messages->hasPages())
    <div class="mt-6">
        {{ $messages->links() }}
    </div>
    @endif
    @else
    <div class="text-center py-12 text-gray-400">
        <i class="fas fa-inbox text-5xl mb-4 opacity-50"></i>
        <p>Aucun message trouvé</p>
    </div>
    @endif
</div>

<!-- Modal pour les détails du message -->
<div class="modal-overlay" id="messageModal" onclick="closeModal(event)">
    <div class="modal-content" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h4 class="modal-title">Détails du message</h4>
            <button onclick="closeModal()" class="modal-close-btn">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" id="messageDetails"></div>
    </div>
</div>

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
        <div class="modal-info-item">
            <div class="modal-info-label">
                <i class="fas fa-user"></i> Nom
            </div>
            <div class="modal-info-value font-semibold">${message.name}</div>
        </div>
        
        <div class="modal-info-item">
            <div class="modal-info-label">
                <i class="fas fa-envelope"></i> Email
            </div>
            <div class="modal-info-value">
                <a href="mailto:${message.email}" class="text-cyan-400 hover:text-cyan-300 transition">${message.email}</a>
            </div>
        </div>
        
        ${message.phone ? `
        <div class="modal-info-item">
            <div class="modal-info-label">
                <i class="fas fa-phone"></i> Téléphone
            </div>
            <div class="modal-info-value">
                <a href="tel:${message.phone}" class="text-cyan-400 hover:text-cyan-300 transition">${message.phone}</a>
            </div>
        </div>
        ` : ''}
        
        <div class="modal-info-item">
            <div class="modal-info-label">
                <i class="fas fa-tag"></i> Sujet
            </div>
            <div class="modal-info-value font-semibold">${message.subject}</div>
        </div>
        
        <div class="modal-info-item">
            <div class="modal-info-label">
                <i class="fas fa-comment-alt"></i> Message
            </div>
            <div class="modal-message-box">
                <p class="modal-info-value whitespace-pre-wrap leading-relaxed">${message.message}</p>
            </div>
        </div>
        
        <div class="modal-grid">
            <div class="modal-info-item">
                <div class="modal-info-label">
                    <i class="fas fa-calendar"></i> Date
                </div>
                <div class="modal-info-value">${message.created_at}</div>
            </div>
            <div class="modal-info-item">
                <div class="modal-info-label">
                    <i class="fas fa-info-circle"></i> Statut
                </div>
                <div class="modal-info-value">
                    ${message.is_read ? '<span class="status-badge status-read"><i class="fas fa-check-circle"></i> Lu</span>' : '<span class="status-badge status-unread"><i class="fas fa-circle text-xs"></i> Non lu</span>'}
                </div>
            </div>
        </div>
    `;
    
    modal.classList.add('active');
}

function closeModal(event) {
    if (event && event.target !== event.currentTarget) return;
    document.getElementById('messageModal').classList.remove('active');
}

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.message-checkbox');
    checkboxes.forEach(cb => cb.checked = selectAll.checked);
    updateBulkActions();
}

function updateBulkActions() {
    const selected = document.querySelectorAll('.message-checkbox:checked');
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');
    
    if (selected.length > 0) {
        bulkActions.style.display = 'flex';
        selectedCount.textContent = `${selected.length} sélectionné${selected.length > 1 ? 's' : ''}`;
    } else {
        bulkActions.style.display = 'none';
    }
}

function clearSelection() {
    document.querySelectorAll('.message-checkbox').forEach(cb => cb.checked = false);
    document.getElementById('selectAll').checked = false;
    updateBulkActions();
}

function bulkMarkRead() {
    const selected = Array.from(document.querySelectorAll('.message-checkbox:checked')).map(cb => cb.value);
    if (selected.length === 0) return;
    
    if (!confirm(`Marquer ${selected.length} message(s) comme lu(s) ?`)) return;
    
    selected.forEach(id => {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/messages/${id}/mark-read`;
        form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
        document.body.appendChild(form);
        form.submit();
    });
}

function bulkDelete() {
    const selected = Array.from(document.querySelectorAll('.message-checkbox:checked')).map(cb => cb.value);
    if (selected.length === 0) return;
    
    if (!confirm(`Supprimer ${selected.length} message(s) ? Cette action est irréversible.`)) return;
    
    selected.forEach(id => {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/messages/${id}`;
        form.innerHTML = `
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="DELETE">
        `;
        document.body.appendChild(form);
        form.submit();
    });
}

// Écouter les changements de checkbox
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.message-checkbox').forEach(cb => {
        cb.addEventListener('change', updateBulkActions);
    });
});

function openWhatsApp(messageId) {
    const message = messagesData[messageId];
    if (!message || !message.phone) {
        toastr.warning('Aucun numéro de téléphone disponible pour ce message', 'Information');
        return;
    }
    
    // Nettoyer le numéro de téléphone (enlever les espaces, tirets, etc.)
    const phone = message.phone.replace(/\s+/g, '').replace(/-/g, '').replace(/\./g, '');
    
    // Créer le lien WhatsApp
    const messageText = encodeURIComponent(`Bonjour ${message.name},\n\nMerci pour votre message concernant "${message.subject}".`);
    const whatsappUrl = `https://wa.me/${phone}?text=${messageText}`;
    
    window.open(whatsappUrl, '_blank');
}

function openEmail(messageId) {
    const message = messagesData[messageId];
    if (!message || !message.email) {
        toastr.warning('Aucune adresse email disponible pour ce message', 'Information');
        return;
    }
    
    // Créer le lien email avec sujet et corps pré-remplis
    const subject = encodeURIComponent(`Re: ${message.subject}`);
    const body = encodeURIComponent(`Bonjour ${message.name},\n\nMerci pour votre message.\n\nCordialement,`);
    const emailUrl = `mailto:${message.email}?subject=${subject}&body=${body}`;
    
    window.location.href = emailUrl;
}
</script>
</div>
@endsection
