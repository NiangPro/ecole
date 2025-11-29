@extends('admin.layout')

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
    
    .messages-page .bg-gray-700 {
        background: rgba(55, 65, 81, 1);
        transition: background 0.3s ease;
    }
    
    body.light-mode .messages-page .bg-gray-700 {
        background: rgba(241, 245, 249, 1);
    }
    
    .messages-page .text-gray-300 {
        color: rgba(209, 213, 219, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .messages-page .text-gray-300 {
        color: rgba(30, 41, 59, 0.8);
    }
    
    .messages-page .bg-black\/30 {
        background: rgba(0, 0, 0, 0.3);
        transition: background 0.3s ease;
    }
    
    body.light-mode .messages-page .bg-black\/30 {
        background: rgba(255, 255, 255, 0.9);
    }
    
    .messages-page .bg-black\/50 {
        background: rgba(0, 0, 0, 0.5);
        transition: background 0.3s ease;
    }
    
    body.light-mode .messages-page .bg-black\/50 {
        background: rgba(255, 255, 255, 0.95);
    }
    
    .messages-page .border-gray-700 {
        border-color: rgba(55, 65, 81, 1);
        transition: border-color 0.3s ease;
    }
    
    body.light-mode .messages-page .border-gray-700 {
        border-color: rgba(226, 232, 240, 1);
    }
    
    .messages-page h4 {
        color: #fff;
        transition: color 0.3s ease;
    }
    
    body.light-mode .messages-page h4 {
        color: #1e293b;
    }
    
    .messages-page .border-cyan-500\/30 {
        border-color: rgba(6, 182, 212, 0.3);
    }
    
    body.light-mode .messages-page .border-cyan-500\/30 {
        border-color: rgba(6, 182, 212, 0.4);
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

<!-- Liste des messages -->
<div class="content-section">
    <div class="space-y-4">
        @forelse($messages as $message)
        <div class="bg-black/30 rounded-lg p-6 border {{ $message->is_read ? 'border-gray-700' : 'border-cyan-500/30' }} hover:border-cyan-500/50 transition">
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-4">
                <div class="flex items-start gap-4 flex-1">
                    <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-teal-500 rounded-full flex items-center justify-center font-bold flex-shrink-0">
                        {{ strtoupper(substr($message->name, 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h4 class="text-xl font-bold">{{ $message->name }}</h4>
                            @if(!$message->is_read)
                                <span class="px-3 py-1 bg-orange-500/20 text-orange-400 rounded-full text-xs font-semibold">
                                    <i class="fas fa-circle mr-1 text-xs"></i>Nouveau
                                </span>
                            @endif
                        </div>
                        <p class="text-cyan-400 mb-1">
                            <i class="fas fa-tag mr-2"></i>{{ $message->subject }}
                        </p>
                        <div class="flex flex-wrap gap-4 text-sm text-gray-400">
                            <span>
                                <i class="fas fa-envelope mr-2"></i>
                                <a href="mailto:{{ $message->email }}" class="hover:text-cyan-400 transition">{{ $message->email }}</a>
                            </span>
                            @if($message->phone)
                            <span>
                                <i class="fas fa-phone mr-2"></i>
                                <a href="tel:{{ $message->phone }}" class="hover:text-cyan-400 transition">{{ $message->phone }}</a>
                            </span>
                            @endif
                            <span>
                                <i class="fas fa-clock mr-2"></i>{{ $message->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="flex gap-2">
                    @if($message->phone)
                    <a href="{{ $message->getWhatsAppUrl() }}" 
                       target="_blank" 
                       class="px-4 py-2 bg-green-500/20 text-green-400 rounded-lg hover:bg-green-500/30 transition"
                       title="Répondre par WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    @endif
                    
                    @if(!$message->is_read)
                    <form action="{{ route('admin.messages.mark-read', $message->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-green-500/20 text-green-400 rounded-lg hover:bg-green-500/30 transition" title="Marquer comme lu">
                            <i class="fas fa-check"></i>
                        </button>
                    </form>
                    @endif
                    
                    <form action="{{ route('admin.messages.delete', $message->id) }}" method="POST" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500/30 transition" title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="bg-black/50 rounded-lg p-4 border border-gray-700">
                <p class="text-gray-300 leading-relaxed">{{ $message->message }}</p>
            </div>
        </div>
        @empty
        <div class="text-center py-12 text-gray-400">
            <i class="fas fa-inbox text-5xl mb-4 opacity-50"></i>
            <p>Aucun message trouvé</p>
        </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if($messages->hasPages())
    <div class="mt-6">
        {{ $messages->links() }}
    </div>
    @endif
</div>
</div>
@endsection
