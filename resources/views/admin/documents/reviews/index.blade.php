@extends('admin.layout')

@section('title', 'Modération des Avis')

@section('styles')
<style>
    .reviews-table-wrapper {
        overflow-x: auto;
        position: relative;
        border-radius: 12px;
    }

    .reviews-table-wrapper::-webkit-scrollbar {
        height: 12px;
    }

    .reviews-table-wrapper::-webkit-scrollbar-track {
        background: rgba(6, 182, 212, 0.1);
        border-radius: 10px;
    }

    .reviews-table-wrapper::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border-radius: 10px;
        border: 2px solid rgba(6, 182, 212, 0.1);
        transition: background 0.3s ease;
    }

    .reviews-table-wrapper::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #14b8a6, #06b6d4);
    }

    .reviews-table {
        width: 100%;
        min-width: 1000px;
    }

    .reviews-table thead {
        background: rgba(6, 182, 212, 0.05);
    }

    .reviews-table th {
        padding: 1rem;
        text-align: left;
        color: #06b6d4;
        font-weight: 700;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid rgba(6, 182, 212, 0.2);
        white-space: nowrap;
    }

    .reviews-table td {
        padding: 1rem;
        border-bottom: 1px solid rgba(6, 182, 212, 0.1);
        color: rgba(255, 255, 255, 0.8);
    }

    .reviews-table tbody tr {
        transition: all 0.3s ease;
    }

    .reviews-table tbody tr:hover {
        background: rgba(6, 182, 212, 0.05);
        transform: translateX(4px);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.75rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-badge.approved {
        background: rgba(34, 197, 94, 0.2);
        color: #22c55e;
        border: 1px solid rgba(34, 197, 94, 0.3);
    }

    .status-badge.pending {
        background: rgba(245, 158, 11, 0.2);
        color: #f59e0b;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        transition: all 0.3s ease;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .action-btn.approve {
        background: rgba(34, 197, 94, 0.2);
        color: #22c55e;
    }

    .action-btn.approve:hover {
        background: rgba(34, 197, 94, 0.3);
    }

    .action-btn.delete {
        background: rgba(239, 68, 68, 0.2);
        color: #ef4444;
    }

    .action-btn.delete:hover {
        background: rgba(239, 68, 68, 0.3);
    }

    .rating-stars {
        color: #f59e0b;
        font-size: 0.875rem;
    }

    .stats-card {
        background: rgba(6, 182, 212, 0.1);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 12px;
        padding: 1.5rem;
        transition: all 0.3s ease;
    }

    .stats-card:hover {
        background: rgba(6, 182, 212, 0.15);
        border-color: rgba(6, 182, 212, 0.4);
        transform: translateY(-2px);
    }

    .stats-number {
        font-size: 2rem;
        font-weight: 800;
        color: #06b6d4;
        margin-bottom: 0.5rem;
    }

    .stats-label {
        font-size: 0.875rem;
        color: rgba(255, 255, 255, 0.7);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    body.light-mode .reviews-table td {
        color: rgba(30, 41, 59, 0.8);
    }

    body.light-mode .stats-label {
        color: rgba(30, 41, 59, 0.7);
    }
    
    /* Dark Mode Styles */
    body.light-mode h3 {
        color: #1e293b;
    }
    
    body.light-mode p {
        color: #64748b;
    }
    
    body.light-mode .content-section {
        background: #ffffff;
        border-color: rgba(6, 182, 212, 0.2);
    }
    
    body.light-mode .input-admin {
        background: #f8f9fa;
        border-color: rgba(6, 182, 212, 0.3);
        color: #1e293b;
    }
    
    body.light-mode .input-admin:focus {
        background: #ffffff;
        border-color: #06b6d4;
    }
    
    body.light-mode .reviews-table th {
        color: #06b6d4;
    }
    
    body.light-mode .reviews-table tbody tr:hover {
        background: rgba(6, 182, 212, 0.05);
    }
    
    body.light-mode .text-gray-400 {
        color: #64748b;
    }
    
    body.light-mode .text-gray-300 {
        color: #475569;
    }
    
    body.light-mode .stats-card {
        background: rgba(6, 182, 212, 0.05);
        border-color: rgba(6, 182, 212, 0.2);
    }
    
    body.light-mode .stats-card:hover {
        background: rgba(6, 182, 212, 0.1);
    }

    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.2s ease;
        padding: 1rem;
    }

    .modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .modal-container {
        background: #1e293b;
        border: 1px solid rgba(6, 182, 212, 0.3);
        border-radius: 16px;
        padding: 2rem;
        max-width: 480px;
        width: 100%;
    }

    body.light-mode .modal-container {
        background: #ffffff;
    }

    .modal-title {
        font-size: 1.25rem;
        font-weight: 800;
        margin-bottom: 1.5rem;
    }

    .form-field { margin-bottom: 1rem; }
    .form-field label { display: block; margin-bottom: 0.375rem; font-size: 0.875rem; font-weight: 600; color: #94a3b8; }
    body.light-mode .form-field label { color: #475569; }
</style>
@endsection

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h3 class="text-3xl font-bold mb-2">Modération des Avis</h3>
        <p class="text-gray-400">Gérez et approuvez les avis des documents</p>
    </div>
    <button type="button" onclick="document.getElementById('add-review-modal').classList.add('active')" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-black font-semibold rounded-lg transition">
        <i class="fas fa-plus mr-2"></i>Ajouter un avis
    </button>
</div>

@if(session('success'))
    <div class="mb-4 p-4 bg-green-500/20 border border-green-500/50 rounded-lg text-green-400 flex items-center gap-2">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 p-4 bg-red-500/20 border border-red-500/50 rounded-lg text-red-400 flex items-center gap-2">
        <i class="fas fa-exclamation-circle"></i>
        {{ session('error') }}
    </div>
@endif

<!-- Statistiques -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="stats-card">
        <div class="stats-number">{{ $stats['total'] }}</div>
        <div class="stats-label">Total des avis</div>
    </div>
    <div class="stats-card">
        <div class="stats-number" style="color: #f59e0b;">{{ $stats['pending'] }}</div>
        <div class="stats-label">En attente</div>
    </div>
    <div class="stats-card">
        <div class="stats-number" style="color: #22c55e;">{{ $stats['approved'] }}</div>
        <div class="stats-label">Approuvés</div>
    </div>
</div>

<!-- Filtres -->
<div class="content-section mb-6">
    <form method="GET" action="{{ route('admin.documents.reviews.index') }}" class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div>
            <label class="block text-gray-300 mb-2 text-sm font-semibold">Statut</label>
            <select name="status" class="input-admin">
                <option value="">Tous les avis</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approuvés</option>
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-black font-semibold rounded-lg transition flex-1">
                <i class="fas fa-search mr-2"></i>Filtrer
            </button>
            <a href="{{ route('admin.documents.reviews.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition">
                <i class="fas fa-redo"></i>
            </a>
        </div>
    </form>
</div>

<!-- Tableau des avis -->
<div class="content-section">
    <div class="reviews-table-wrapper">
        <table class="reviews-table">
            <thead>
                <tr>
                    <th>Document</th>
                    <th>Utilisateur</th>
                    <th>Note</th>
                    <th>Commentaire</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                <tr>
                    <td>
                        <div class="font-semibold">{{ $review->document->title ?? 'Document supprimé' }}</div>
                        @if($review->document)
                        <div class="text-sm text-gray-400">{{ $review->document->category->name ?? 'Non classé' }}</div>
                        @endif
                    </td>
                    <td>
                        <div class="font-semibold">{{ $review->user_name ?? ($review->user ? $review->user->name : 'Anonyme') }}</div>
                        @if($review->user_email)
                        <div class="text-sm text-gray-400">{{ $review->user_email }}</div>
                        @elseif($review->user)
                        <div class="text-sm text-gray-400">{{ $review->user->email }}</div>
                        @endif
                        @if($review->is_verified_purchase)
                        <span class="status-badge approved" style="margin-top: 0.25rem; font-size: 0.7rem;">
                            <i class="fas fa-check-circle"></i> Achat vérifié
                        </span>
                        @endif
                    </td>
                    <td>
                        <div class="rating-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                            @endfor
                        </div>
                        <div class="text-sm text-gray-400 mt-1">{{ $review->rating }}/5</div>
                    </td>
                    <td>
                        @if($review->comment)
                            <div class="max-w-md">{{ Str::limit($review->comment, 100) }}</div>
                        @else
                            <span class="text-gray-400 italic">Aucun commentaire</span>
                        @endif
                    </td>
                    <td>
                        <span class="status-badge {{ $review->is_approved ? 'approved' : 'pending' }}">
                            <i class="fas {{ $review->is_approved ? 'fa-check-circle' : 'fa-clock' }}"></i>
                            {{ $review->is_approved ? 'Approuvé' : 'En attente' }}
                        </span>
                    </td>
                    <td>
                        <div class="text-sm text-gray-300">{{ $review->created_at->format('d/m/Y H:i') }}</div>
                    </td>
                    <td style="text-align: right;">
                        <div class="flex items-center justify-end gap-2">
                            @if(!$review->is_approved)
                                <form action="{{ route('admin.documents.reviews.approve', $review->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir approuver cet avis ?');">
                                    @csrf
                                    <button type="submit" class="action-btn approve" title="Approuver">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('admin.documents.reviews.destroy', $review->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet avis ? Cette action est irréversible.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-8 text-center text-gray-400">
                        <i class="fas fa-comments text-4xl mb-4 block"></i>
                        <p>Aucun avis trouvé</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($reviews->hasPages())
        <div class="mt-6">
            {{ $reviews->links() }}
        </div>
    @endif
</div>

<!-- Modal Ajouter un avis -->
<div class="modal-overlay" id="add-review-modal">
    <div class="modal-container">
        <h3 class="modal-title"><i class="fas fa-comment-dots mr-2"></i>Ajouter un avis</h3>
        <form method="POST" action="{{ route('admin.documents.reviews.store') }}">
            @csrf
            <div class="form-field">
                <label for="document_id">Document</label>
                <select name="document_id" id="document_id" class="input-admin" required>
                    <option value="">— Choisir un document —</option>
                    @foreach($documents as $document)
                        <option value="{{ $document->id }}">{{ $document->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-field">
                <label for="user_name">Nom de l'auteur</label>
                <input type="text" name="user_name" id="user_name" class="input-admin" placeholder="Ex : Awa Diop" required>
            </div>
            <div class="form-field">
                <label for="rating">Note</label>
                <select name="rating" id="rating" class="input-admin" required>
                    <option value="5">5 étoiles</option>
                    <option value="4">4 étoiles</option>
                    <option value="3">3 étoiles</option>
                    <option value="2">2 étoiles</option>
                    <option value="1">1 étoile</option>
                </select>
            </div>
            <div class="form-field">
                <label for="comment">Commentaire (optionnel)</label>
                <textarea name="comment" id="comment" class="input-admin" rows="3" maxlength="1000" placeholder="Ex : Corrigé très clair, m'a aidé à réviser rapidement."></textarea>
            </div>
            <div class="form-field flex items-center gap-2">
                <input type="checkbox" name="is_approved" id="is_approved" value="1" checked>
                <label for="is_approved" class="!mb-0">Publier immédiatement (approuvé)</label>
            </div>
            <div class="form-field flex items-center gap-2">
                <input type="checkbox" name="is_verified_purchase" id="is_verified_purchase" value="1">
                <label for="is_verified_purchase" class="!mb-0">Marquer comme achat vérifié</label>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="document.getElementById('add-review-modal').classList.remove('active')" class="flex-1 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition">
                    Annuler
                </button>
                <button type="submit" class="flex-1 px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-black font-semibold rounded-lg transition">
                    <i class="fas fa-plus mr-2"></i>Ajouter
                </button>
            </div>
        </form>
    </div>
</div>

@if($errors->any())
<script>document.getElementById('add-review-modal').classList.add('active');</script>
@endif
@endsection

