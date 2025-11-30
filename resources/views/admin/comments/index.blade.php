@extends('admin.layout')

@section('title', 'Gestion des Commentaires | Admin')

@section('styles')
<style>
    /* Styles pour la page Comments */
    .comments-page h3 {
        color: #fff;
        transition: color 0.3s ease;
    }
    
    body.light-mode .comments-page h3 {
        color: #1e293b;
    }
    
    .comments-page .text-gray-400 {
        color: rgba(156, 163, 175, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .comments-page .text-gray-400 {
        color: rgba(100, 116, 139, 1);
    }
    
    .comments-page .text-gray-300 {
        color: rgba(209, 213, 219, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .comments-page .text-gray-300 {
        color: rgba(30, 41, 59, 0.8);
    }
    
    body.light-mode .comments-page .text-white {
        color: #1e293b;
    }
    
    /* Table design */
    .comments-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .comments-table thead {
        background: rgba(6, 182, 212, 0.1);
        border-bottom: 2px solid rgba(6, 182, 212, 0.3);
    }
    
    body.light-mode .comments-table thead {
        background: rgba(6, 182, 212, 0.05);
        border-bottom-color: rgba(6, 182, 212, 0.2);
    }
    
    .comments-table th {
        padding: 12px 8px;
        text-align: left;
        font-weight: 600;
        font-size: 0.875rem;
        color: #06b6d4;
        white-space: nowrap;
    }
    
    body.light-mode .comments-table th {
        color: #0891b2;
    }
    
    .comments-table td {
        padding: 10px 8px;
        border-bottom: 1px solid rgba(55, 65, 81, 0.3);
        vertical-align: middle;
    }
    
    body.light-mode .comments-table td {
        border-bottom-color: rgba(226, 232, 240, 0.5);
    }
    
    .comments-table tbody tr {
        transition: background 0.2s ease;
    }
    
    .comments-table tbody tr:hover {
        background: rgba(6, 182, 212, 0.05);
    }
    
    body.light-mode .comments-table tbody tr:hover {
        background: rgba(6, 182, 212, 0.02);
    }
    
    /* Avatar compact */
    .comment-avatar {
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
    
    .status-pending {
        background: rgba(234, 179, 8, 0.15);
        color: #eab308;
    }
    
    .status-approved {
        background: rgba(34, 197, 94, 0.15);
        color: #22c55e;
    }
    
    .status-rejected {
        background: rgba(239, 68, 68, 0.15);
        color: #ef4444;
    }
    
    /* Content preview */
    .content-preview {
        max-width: 300px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-size: 0.875rem;
        color: rgba(209, 213, 219, 1);
    }
    
    body.light-mode .content-preview {
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
    
    .btn-approve {
        background: rgba(34, 197, 94, 0.15);
        color: #22c55e;
    }
    
    .btn-approve:hover {
        background: rgba(34, 197, 94, 0.25);
    }
    
    .btn-reject {
        background: rgba(239, 68, 68, 0.15);
        color: #ef4444;
    }
    
    .btn-reject:hover {
        background: rgba(239, 68, 68, 0.25);
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
    
    /* Modal pour les détails */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }
    
    .modal-overlay.active {
        display: flex;
    }
    
    .modal-content {
        background: rgba(15, 23, 42, 0.95);
        border: 1px solid rgba(6, 182, 212, 0.3);
        border-radius: 12px;
        padding: 24px;
        max-width: 700px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
    }
    
    body.light-mode .modal-content {
        background: rgba(255, 255, 255, 0.98);
        border-color: rgba(6, 182, 212, 0.4);
    }
    
    /* Responsive */
    @media (max-width: 1024px) {
        .comments-table {
            font-size: 0.8rem;
        }
        
        .comments-table th,
        .comments-table td {
            padding: 8px 6px;
        }
        
        .content-preview {
            max-width: 200px;
        }
    }
    
    @media (max-width: 768px) {
        .comments-table {
            display: block;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .comments-table thead,
        .comments-table tbody,
        .comments-table tr,
        .comments-table td,
        .comments-table th {
            display: block;
        }
        
        .comments-table thead {
            display: none;
        }
        
        .comments-table tr {
            margin-bottom: 12px;
            border: 1px solid rgba(55, 65, 81, 0.3);
            border-radius: 8px;
            padding: 12px;
            background: rgba(0, 0, 0, 0.2);
        }
        
        body.light-mode .comments-table tr {
            background: rgba(255, 255, 255, 0.5);
            border-color: rgba(226, 232, 240, 0.5);
        }
        
        .comments-table td {
            border: none;
            padding: 6px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .comments-table td:before {
            content: attr(data-label);
            font-weight: 600;
            color: #06b6d4;
            margin-right: 12px;
        }
        
        .content-preview {
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
<div class="comments-page">
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h3 class="text-3xl font-bold mb-2">Gestion des Commentaires</h3>
        <p class="text-gray-400">Approuvez, rejetez ou supprimez les commentaires</p>
    </div>
</div>

<!-- Statistiques -->
<div class="grid md:grid-cols-4 gap-4 mb-8">
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <i class="fas fa-comments text-3xl text-cyan-400"></i>
        </div>
        <div class="stat-number">{{ number_format($stats['total']) }}</div>
        <p class="text-gray-400 mt-2 text-sm">Total commentaires</p>
    </div>
    
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <i class="fas fa-clock text-3xl text-yellow-400"></i>
        </div>
        <div class="stat-number">{{ number_format($stats['pending']) }}</div>
        <p class="text-gray-400 mt-2 text-sm">En attente</p>
    </div>
    
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <i class="fas fa-check-circle text-3xl text-green-400"></i>
        </div>
        <div class="stat-number">{{ number_format($stats['approved']) }}</div>
        <p class="text-gray-400 mt-2 text-sm">Approuvés</p>
    </div>
    
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <i class="fas fa-times-circle text-3xl text-red-400"></i>
        </div>
        <div class="stat-number">{{ number_format($stats['rejected']) }}</div>
        <p class="text-gray-400 mt-2 text-sm">Rejetés</p>
    </div>
</div>

<!-- Barre de recherche et filtres -->
<div class="content-section mb-6">
    <form action="{{ route('admin.comments.index') }}" method="GET">
        <div class="grid grid-cols-4 gap-3 mb-3">
            <input type="text" name="search" value="{{ $search }}" 
                   placeholder="Rechercher..." 
                   class="input-admin">
            <select name="status" class="input-admin">
                <option value="">Tous les statuts</option>
                <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>En attente</option>
                <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approuvés</option>
                <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Rejetés</option>
            </select>
            <select name="sort" class="input-admin">
                <option value="created_at" {{ $sortBy == 'created_at' ? 'selected' : '' }}>Date</option>
                <option value="name" {{ $sortBy == 'name' ? 'selected' : '' }}>Nom</option>
            </select>
            <select name="order" class="input-admin">
                <option value="desc" {{ $sortOrder == 'desc' ? 'selected' : '' }}>Décroissant</option>
                <option value="asc" {{ $sortOrder == 'asc' ? 'selected' : '' }}>Croissant</option>
            </select>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="btn-primary">
                <i class="fas fa-search mr-2"></i>Rechercher
            </button>
            @if($search || $status)
            <a href="{{ route('admin.comments.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 rounded-lg font-semibold transition text-sm">
                <i class="fas fa-times mr-2"></i>Réinitialiser
            </a>
            @endif
        </div>
    </form>
</div>

<!-- Liste des commentaires en tableau -->
<div class="content-section">
    @if($comments->count() > 0)
    <div class="overflow-x-auto">
        <table class="comments-table">
            <thead>
                <tr>
                    <th style="width: 40px;">
                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                    </th>
                    <th style="width: 60px;">Avatar</th>
                    <th style="min-width: 150px;">Auteur</th>
                    <th style="min-width: 120px;">Téléphone</th>
                    <th style="width: 100px;">Statut</th>
                    <th style="width: 100px;">Date</th>
                    <th style="width: 200px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($comments as $comment)
                <tr data-comment-id="{{ $comment->id }}">
                    <td>
                        <input type="checkbox" class="comment-checkbox" value="{{ $comment->id }}">
                    </td>
                    <td>
                        <div class="comment-avatar bg-gradient-to-br from-cyan-500 to-teal-500 text-white">
                            {{ strtoupper(substr($comment->author_name, 0, 1)) }}
                        </div>
                    </td>
                    <td data-label="Auteur">
                        <div class="font-semibold text-white">{{ $comment->author_name }}</div>
                        @if($comment->parent)
                        <div class="text-xs text-gray-400 mt-1">
                            <i class="fas fa-reply mr-1"></i>Réponse à {{ $comment->parent->author_name }}
                        </div>
                        @endif
                    </td>
                    <td data-label="Téléphone">
                        @if($comment->phone)
                        <a href="tel:{{ $comment->phone }}" class="text-cyan-400 hover:text-cyan-300 text-sm">
                            {{ $comment->phone }}
                        </a>
                        @else
                        <span class="text-gray-500">-</span>
                        @endif
                    </td>
                    <td data-label="Statut">
                        @if($comment->status == 'pending')
                        <span class="status-badge status-pending">
                            <i class="fas fa-clock"></i> En attente
                        </span>
                        @elseif($comment->status == 'approved')
                        <span class="status-badge status-approved">
                            <i class="fas fa-check-circle"></i> Approuvé
                        </span>
                        @else
                        <span class="status-badge status-rejected">
                            <i class="fas fa-times-circle"></i> Rejeté
                        </span>
                        @endif
                    </td>
                    <td data-label="Date">
                        <div class="text-xs text-gray-400">
                            {{ $comment->created_at->format('d/m/Y') }}
                            <br>
                            {{ $comment->created_at->format('H:i') }}
                        </div>
                    </td>
                    <td data-label="Actions">
                        <div class="flex flex-wrap gap-1">
                            <div class="flex flex-wrap gap-1">
                                @if($comment->status == 'pending')
                                <form action="{{ route('admin.comments.approve', $comment->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="action-btn btn-approve" title="Approuver">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.comments.reject', $comment->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="action-btn btn-reject" title="Rejeter">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                                @elseif($comment->status == 'rejected')
                                <form action="{{ route('admin.comments.approve', $comment->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="action-btn btn-approve" title="Approuver">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('admin.comments.reject', $comment->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="action-btn btn-reject" title="Rejeter">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                                @endif
                                
                                @if($comment->phone)
                                <button onclick="openWhatsApp({{ $comment->id }})" class="action-btn btn-contact" title="Contacter par WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </button>
                                @else
                                <button class="action-btn btn-contact" style="opacity: 0.4; cursor: not-allowed;" title="Pas de numéro de téléphone" disabled>
                                    <i class="fab fa-whatsapp"></i>
                                </button>
                                @endif
                                
                                <button onclick="openEmail({{ $comment->id }})" class="action-btn btn-contact" title="Contacter par Email">
                                    <i class="fas fa-envelope"></i>
                                </button>
                                
                                <button onclick="showCommentDetails({{ $comment->id }})" class="action-btn btn-contact" title="Voir les détails">
                                    <i class="fas fa-eye"></i>
                                </button>
                                
                                @auth
                                @if(Auth::user()->isAdmin())
                                <form action="{{ route('admin.comments.delete', $comment->id) }}" method="POST" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn btn-delete" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                                @endauth
                            </div>
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
        <button onclick="bulkApprove()" class="action-btn btn-approve">
            <i class="fas fa-check"></i> Approuver
        </button>
        <button onclick="bulkReject()" class="action-btn btn-reject">
            <i class="fas fa-times"></i> Rejeter
        </button>
        <button onclick="bulkDelete()" class="action-btn btn-delete">
            <i class="fas fa-trash"></i> Supprimer
        </button>
        <button onclick="clearSelection()" class="action-btn btn-contact">
            <i class="fas fa-times"></i> Annuler
        </button>
    </div>
    
    <!-- Pagination -->
    @if($comments->hasPages())
    <div class="mt-6">
        {{ $comments->links() }}
    </div>
    @endif
    @else
    <div class="text-center py-12 text-gray-400">
        <i class="fas fa-comments text-5xl mb-4 opacity-50"></i>
        <p>Aucun commentaire trouvé</p>
    </div>
    @endif
</div>

<!-- Modal pour les détails du commentaire -->
<div class="modal-overlay" id="commentModal" onclick="closeModal(event)">
    <div class="modal-content" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-4">
            <h4 class="text-xl font-bold text-white">Détails du commentaire</h4>
            <button onclick="closeModal()" class="text-gray-400 hover:text-white">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <div id="commentDetails"></div>
    </div>
</div>

<script>
const commentsData = {
    @foreach($comments as $comment)
    {{ $comment->id }}: {
        id: {{ $comment->id }},
        author_name: @json($comment->author_name),
        author_email: @json($comment->author_email),
        phone: @json($comment->phone),
        content: @json($comment->content),
        status: @json($comment->status),
        created_at: @json($comment->created_at->format('d/m/Y à H:i')),
        likes: {{ $comment->likes }},
        article_title: @json($comment->commentable->title ?? 'N/A'),
        article_slug: @json($comment->commentable->slug ?? '#'),
        parent_author: @json($comment->parent->author_name ?? null),
    },
    @endforeach
};

function showCommentDetails(commentId) {
    const comment = commentsData[commentId];
    if (!comment) return;
    
    const modal = document.getElementById('commentModal');
    const details = document.getElementById('commentDetails');
    
    details.innerHTML = `
        <div class="space-y-4">
            <div>
                <label class="text-gray-400 text-sm">Auteur</label>
                <p class="text-white font-semibold">${comment.author_name}</p>
            </div>
            <div>
                <label class="text-gray-400 text-sm">Email</label>
                <p class="text-white">
                    <a href="mailto:${comment.author_email}" class="text-cyan-400 hover:text-cyan-300">${comment.author_email}</a>
                </p>
            </div>
            ${comment.phone ? `
            <div>
                <label class="text-gray-400 text-sm">Téléphone</label>
                <p class="text-white">
                    <a href="tel:${comment.phone}" class="text-cyan-400 hover:text-cyan-300">${comment.phone}</a>
                </p>
            </div>
            ` : ''}
            <div>
                <label class="text-gray-400 text-sm">Contenu</label>
                <div class="bg-black/50 rounded-lg p-4 border border-gray-700 mt-2">
                    <p class="text-gray-300 whitespace-pre-wrap">${comment.content}</p>
                </div>
            </div>
            <div>
                <label class="text-gray-400 text-sm">Article</label>
                <p class="text-white">
                    <a href="/emplois/${comment.article_slug}" target="_blank" class="text-cyan-400 hover:text-cyan-300">${comment.article_title}</a>
                </p>
            </div>
            ${comment.parent_author ? `
            <div>
                <label class="text-gray-400 text-sm">Réponse à</label>
                <p class="text-cyan-400">${comment.parent_author}</p>
            </div>
            ` : ''}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-gray-400 text-sm">Date</label>
                    <p class="text-white">${comment.created_at}</p>
                </div>
                <div>
                    <label class="text-gray-400 text-sm">Likes</label>
                    <p class="text-white">${comment.likes}</p>
                </div>
            </div>
        </div>
    `;
    
    modal.classList.add('active');
}

function closeModal(event) {
    if (event && event.target !== event.currentTarget) return;
    document.getElementById('commentModal').classList.remove('active');
}

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.comment-checkbox');
    checkboxes.forEach(cb => cb.checked = selectAll.checked);
    updateBulkActions();
}

function updateBulkActions() {
    const selected = document.querySelectorAll('.comment-checkbox:checked');
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
    document.querySelectorAll('.comment-checkbox').forEach(cb => cb.checked = false);
    document.getElementById('selectAll').checked = false;
    updateBulkActions();
}

function bulkApprove() {
    const selected = Array.from(document.querySelectorAll('.comment-checkbox:checked')).map(cb => cb.value);
    if (selected.length === 0) return;
    
    if (!confirm(`Approuver ${selected.length} commentaire(s) ?`)) return;
    
    selected.forEach(id => {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/comments/${id}/approve`;
        form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
        document.body.appendChild(form);
        form.submit();
    });
}

function bulkReject() {
    const selected = Array.from(document.querySelectorAll('.comment-checkbox:checked')).map(cb => cb.value);
    if (selected.length === 0) return;
    
    if (!confirm(`Rejeter ${selected.length} commentaire(s) ?`)) return;
    
    selected.forEach(id => {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/comments/${id}/reject`;
        form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
        document.body.appendChild(form);
        form.submit();
    });
}

function bulkDelete() {
    const selected = Array.from(document.querySelectorAll('.comment-checkbox:checked')).map(cb => cb.value);
    if (selected.length === 0) return;
    
    if (!confirm(`Supprimer ${selected.length} commentaire(s) ? Cette action est irréversible.`)) return;
    
    selected.forEach(id => {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/comments/${id}`;
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
    document.querySelectorAll('.comment-checkbox').forEach(cb => {
        cb.addEventListener('change', updateBulkActions);
    });
});

function openWhatsApp(commentId) {
    const comment = commentsData[commentId];
    if (!comment || !comment.phone) {
        toastr.warning('Aucun numéro de téléphone disponible pour ce commentaire', 'Information');
        return;
    }
    
    // Nettoyer le numéro de téléphone (enlever les espaces, tirets, etc.)
    const phone = comment.phone.replace(/\s+/g, '').replace(/-/g, '').replace(/\./g, '');
    
    // Créer le lien WhatsApp
    const message = encodeURIComponent(`Bonjour ${comment.author_name},\n\nMerci pour votre commentaire.`);
    const whatsappUrl = `https://wa.me/${phone}?text=${message}`;
    
    window.open(whatsappUrl, '_blank');
}

function openEmail(commentId) {
    const comment = commentsData[commentId];
    if (!comment || !comment.author_email) {
        toastr.warning('Aucune adresse email disponible pour ce commentaire', 'Information');
        return;
    }
    
    // Créer le lien email avec sujet et corps pré-remplis
    const subject = encodeURIComponent(`Réponse à votre commentaire`);
    const body = encodeURIComponent(`Bonjour ${comment.author_name},\n\nMerci pour votre commentaire.\n\nCordialement,`);
    const emailUrl = `mailto:${comment.author_email}?subject=${subject}&body=${body}`;
    
    window.location.href = emailUrl;
}
</script>
</div>
@endsection
