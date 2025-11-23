@extends('admin.layout')

@section('title', 'Gestion des Commentaires | Admin')

@section('content')
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
    <form action="{{ route('admin.comments.index') }}" method="GET" class="space-y-3">
        <div class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ $search }}" 
                   placeholder="Rechercher par nom, email, téléphone ou contenu..." 
                   class="input-admin flex-1 min-w-[200px]">
            <select name="status" class="input-admin" style="min-width: 150px;">
                <option value="">Tous les statuts</option>
                <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>En attente</option>
                <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approuvés</option>
                <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Rejetés</option>
            </select>
            <select name="sort" class="input-admin" style="min-width: 150px;">
                <option value="created_at" {{ $sortBy == 'created_at' ? 'selected' : '' }}>Date</option>
                <option value="name" {{ $sortBy == 'name' ? 'selected' : '' }}>Nom</option>
                <option value="likes" {{ $sortBy == 'likes' ? 'selected' : '' }}>Likes</option>
            </select>
            <select name="order" class="input-admin" style="min-width: 120px;">
                <option value="desc" {{ $sortOrder == 'desc' ? 'selected' : '' }}>Décroissant</option>
                <option value="asc" {{ $sortOrder == 'asc' ? 'selected' : '' }}>Croissant</option>
            </select>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="btn-primary">
                <i class="fas fa-search mr-2"></i>Rechercher
            </button>
            @if($search || $status)
            <a href="{{ route('admin.comments.index') }}" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 rounded-lg font-semibold transition">
                <i class="fas fa-times mr-2"></i>Réinitialiser
            </a>
            @endif
        </div>
    </form>
</div>

<!-- Liste des commentaires -->
<div class="content-section">
    <div class="space-y-4">
        @forelse($comments as $comment)
        <div class="bg-black/30 rounded-lg p-6 border {{ $comment->status == 'pending' ? 'border-yellow-500/30' : ($comment->status == 'approved' ? 'border-green-500/30' : 'border-red-500/30') }} hover:border-cyan-500/50 transition">
            <div class="flex flex-col lg:flex-row gap-4 mb-4">
                <div class="flex items-start gap-4 flex-1">
                    <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-teal-500 rounded-full flex items-center justify-center font-bold flex-shrink-0">
                        {{ strtoupper(substr($comment->author_name, 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2 flex-wrap">
                            <h4 class="text-lg font-bold">{{ $comment->author_name }}</h4>
                            @if($comment->status == 'pending')
                                <span class="px-3 py-1 bg-yellow-500/20 text-yellow-400 rounded-full text-xs font-semibold">
                                    <i class="fas fa-clock mr-1"></i>En attente
                                </span>
                            @elseif($comment->status == 'approved')
                                <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-xs font-semibold">
                                    <i class="fas fa-check-circle mr-1"></i>Approuvé
                                </span>
                            @else
                                <span class="px-3 py-1 bg-red-500/20 text-red-400 rounded-full text-xs font-semibold">
                                    <i class="fas fa-times-circle mr-1"></i>Rejeté
                                </span>
                            @endif
                        </div>
                        
                        <div class="flex flex-wrap gap-4 text-sm text-gray-400 mb-3">
                            <span>
                                <i class="fas fa-envelope mr-2"></i>
                                <a href="mailto:{{ $comment->author_email }}" class="hover:text-cyan-400 transition">{{ $comment->author_email }}</a>
                            </span>
                            @if($comment->phone)
                            <span>
                                <i class="fas fa-phone mr-2"></i>
                                <a href="tel:{{ $comment->phone }}" class="hover:text-cyan-400 transition">{{ $comment->phone }}</a>
                            </span>
                            @endif
                            <span>
                                <i class="fas fa-clock mr-2"></i>{{ $comment->created_at->diffForHumans() }}
                            </span>
                            @if($comment->commentable)
                            <span>
                                <i class="fas fa-file-alt mr-2"></i>
                                <a href="{{ route('emplois.article', $comment->commentable->slug ?? '#') }}" target="_blank" class="hover:text-cyan-400 transition">
                                    {{ $comment->commentable->title ?? 'Article' }}
                                </a>
                            </span>
                            @endif
                            @if($comment->likes > 0)
                            <span>
                                <i class="fas fa-heart mr-2"></i>{{ $comment->likes }} like(s)
                            </span>
                            @endif
                        </div>
                        
                        <div class="bg-black/50 rounded-lg p-4 border border-gray-700">
                            <p class="text-gray-300 leading-relaxed whitespace-pre-wrap">{{ $comment->content }}</p>
                        </div>
                        
                        @if($comment->parent)
                        <div class="mt-3 ml-6 pl-4 border-l-2 border-cyan-500/30">
                            <p class="text-sm text-gray-400 mb-1">
                                <i class="fas fa-reply mr-2"></i>Réponse à : <span class="text-cyan-400">{{ $comment->parent->author_name }}</span>
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
                
                <div class="flex flex-col gap-2 lg:min-w-[200px]">
                    @if($comment->status == 'pending')
                    <form action="{{ route('admin.comments.approve', $comment->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-green-500/20 text-green-400 rounded-lg hover:bg-green-500/30 transition text-sm font-semibold">
                            <i class="fas fa-check mr-2"></i>Approuver
                        </button>
                    </form>
                    
                    <form action="{{ route('admin.comments.reject', $comment->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500/30 transition text-sm font-semibold">
                            <i class="fas fa-times mr-2"></i>Rejeter
                        </button>
                    </form>
                    @elseif($comment->status == 'rejected')
                    <form action="{{ route('admin.comments.approve', $comment->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-green-500/20 text-green-400 rounded-lg hover:bg-green-500/30 transition text-sm font-semibold">
                            <i class="fas fa-check mr-2"></i>Approuver
                        </button>
                    </form>
                    @else
                    <form action="{{ route('admin.comments.reject', $comment->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500/30 transition text-sm font-semibold">
                            <i class="fas fa-times mr-2"></i>Rejeter
                        </button>
                    </form>
                    @endif
                    
                    <div class="flex flex-col gap-2">
                        @if($comment->phone)
                        <button onclick="openWhatsApp({{ $comment->id }})" class="w-full px-4 py-2 bg-green-500/20 text-green-400 rounded-lg hover:bg-green-500/30 transition text-sm font-semibold flex items-center justify-center gap-2" title="Répondre par WhatsApp">
                            <i class="fab fa-whatsapp"></i>
                            <span>WhatsApp</span>
                        </button>
                        @endif
                        
                        <button onclick="openEmail({{ $comment->id }})" class="w-full px-4 py-2 bg-blue-500/20 text-blue-400 rounded-lg hover:bg-blue-500/30 transition text-sm font-semibold flex items-center justify-center gap-2" title="Répondre par Email">
                            <i class="fas fa-envelope"></i>
                            <span>Email</span>
                        </button>
                    </div>
                    
                    @auth
                    @if(Auth::user()->isAdmin())
                    <form action="{{ route('admin.comments.delete', $comment->id) }}" method="POST" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500/30 transition text-sm font-semibold">
                            <i class="fas fa-trash mr-2"></i>Supprimer
                        </button>
                    </form>
                    @endif
                    @endauth
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-12 text-gray-400">
            <i class="fas fa-comments text-5xl mb-4 opacity-50"></i>
            <p>Aucun commentaire trouvé</p>
        </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if($comments->hasPages())
    <div class="mt-6">
        {{ $comments->links() }}
    </div>
    @endif
</div>

<script>
function openWhatsApp(commentId) {
    fetch(`/admin/comments/${commentId}/whatsapp-link`)
        .then(response => response.json())
        .then(data => {
            if (data.url) {
                window.open(data.url, '_blank');
            } else {
                toastr.error(data.error || 'Impossible d\'obtenir le lien WhatsApp', 'Erreur');
            }
        })
        .catch(err => {
            console.error('Erreur:', err);
            toastr.error('Erreur lors de la récupération du lien WhatsApp', 'Erreur');
        });
}

function openEmail(commentId) {
    fetch(`/admin/comments/${commentId}/email-link`)
        .then(response => response.json())
        .then(data => {
            if (data.url) {
                window.location.href = data.url;
            } else {
                toastr.error(data.error || 'Impossible d\'obtenir le lien email', 'Erreur');
            }
        })
        .catch(err => {
            console.error('Erreur:', err);
            toastr.error('Erreur lors de la récupération du lien email', 'Erreur');
        });
}
</script>
@endsection

