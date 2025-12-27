<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\MessageAttachment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

/**
 * Contrôleur pour la gestion de la messagerie interne
 * 
 * Ce contrôleur gère toutes les fonctionnalités de messagerie :
 * - Liste et affichage des conversations
 * - Création et envoi de messages
 * - Gestion des pièces jointes
 * - Marquage des messages comme lus
 * - Suppression de conversations et messages
 * 
 * @package App\Http\Controllers
 * @author NiangProgrammeur
 * @since 2024-12-25
 */
class MessageController extends Controller
{
    /**
     * Affiche la liste des conversations de l'utilisateur authentifié
     * 
     * Récupère toutes les conversations où l'utilisateur est impliqué,
     * triées par date de dernier message. Affiche également le nombre
     * de messages non lus.
     * 
     * @return \Illuminate\View\View Vue contenant les conversations et le nombre de messages non lus
     * 
     * @example
     * Route: GET /dashboard/messages
     * Retourne la vue messages.index avec :
     * - $conversations : Paginator des conversations
     * - $unreadCount : Nombre de messages non lus
     */
    public function index()
    {
        $user = Auth::user();
        
        // Récupérer toutes les conversations où l'utilisateur est impliqué
        $conversations = Conversation::where(function($query) use ($user) {
                $query->where('user1_id', $user->id)
                      ->where('user1_deleted', false);
            })
            ->orWhere(function($query) use ($user) {
                $query->where('user2_id', $user->id)
                      ->where('user2_deleted', false);
            })
            ->with(['user1', 'user2', 'lastMessage'])
            ->orderBy('last_message_at', 'desc')
            ->paginate(20);
        
        // Compter les messages non lus
        $unreadCount = Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->where('receiver_deleted', false)
            ->count();
        
        return view('messages.index', compact('conversations', 'unreadCount'));
    }

    /**
     * Affiche une conversation spécifique avec tous ses messages
     * 
     * Affiche tous les messages d'une conversation et marque automatiquement
     * tous les messages non lus comme lus pour l'utilisateur connecté.
     * 
     * @param int $conversationId ID de la conversation
     * @return \Illuminate\View\View Vue contenant la conversation et ses messages
     * @throws \Illuminate\Http\Exceptions\HttpResponseException Si l'utilisateur n'a pas accès
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si la conversation n'existe pas
     * 
     * @example
     * Route: GET /dashboard/messages/5
     * Retourne la vue messages.show avec :
     * - $conversation : Modèle Conversation
     * - $messages : Collection des messages
     * - $otherUser : Modèle User (l'autre utilisateur de la conversation)
     */
    public function show($conversationId)
    {
        $user = Auth::user();
        $conversation = Conversation::with(['user1', 'user2'])
            ->findOrFail($conversationId);
        
        // Vérifier que l'utilisateur fait partie de la conversation
        if ($conversation->user1_id != $user->id && $conversation->user2_id != $user->id) {
            abort(403, 'Accès non autorisé');
        }
        
        // Vérifier si la conversation peut être vue
        if (!$conversation->canBeViewedBy($user->id)) {
            abort(404, 'Conversation introuvable');
        }
        
        // Récupérer les messages
        $messages = Message::where('conversation_id', $conversationId)
            ->where(function($query) use ($user) {
                $query->where(function($q) use ($user) {
                    $q->where('sender_id', $user->id)
                      ->where('sender_deleted', false);
                })->orWhere(function($q) use ($user) {
                    $q->where('receiver_id', $user->id)
                      ->where('receiver_deleted', false);
                });
            })
            ->with(['sender', 'receiver', 'attachments'])
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Marquer tous les messages comme lus
        Message::where('conversation_id', $conversationId)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        
        // Obtenir l'autre utilisateur
        $otherUser = $conversation->getOtherUser($user->id);
        
        return view('messages.show', compact('conversation', 'messages', 'otherUser'));
    }

    /**
     * Crée une nouvelle conversation ou envoie un message dans une conversation existante
     * 
     * Crée une nouvelle conversation si elle n'existe pas, ou réutilise
     * une conversation existante. Gère également l'upload de pièces jointes.
     * 
     * @param \Illuminate\Http\Request $request Requête HTTP contenant les données du message
     * @return \Illuminate\Http\RedirectResponse Redirection vers la conversation
     * @throws \Illuminate\Validation\ValidationException Si la validation échoue
     * 
     * @example
     * Route: POST /dashboard/messages
     * Données attendues :
     * - receiver_id (required|exists:users,id)
     * - subject (nullable|string|max:255)
     * - body (required|string|min:1)
     * - attachments (nullable|array|max:5)
     * - attachments.* (file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt)
     * 
     * Retourne une redirection vers la conversation avec message de succès.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string|min:1',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt',
        ]);
        
        $receiverId = $request->receiver_id;
        
        // Ne pas permettre d'envoyer un message à soi-même
        if ($receiverId == $user->id) {
            return back()->with('error', 'Vous ne pouvez pas vous envoyer un message à vous-même.');
        }
        
        DB::beginTransaction();
        try {
            // Chercher une conversation existante ou en créer une nouvelle
            $conversation = Conversation::where(function($query) use ($user, $receiverId) {
                    $query->where('user1_id', $user->id)
                          ->where('user2_id', $receiverId);
                })
                ->orWhere(function($query) use ($user, $receiverId) {
                    $query->where('user1_id', $receiverId)
                          ->where('user2_id', $user->id);
                })
                ->first();
            
            if (!$conversation) {
                // Créer une nouvelle conversation
                $conversation = Conversation::create([
                    'user1_id' => $user->id,
                    'user2_id' => $receiverId,
                    'last_message_at' => now(),
                ]);
            } else {
                // Réactiver la conversation si elle était supprimée
                if ($conversation->user1_id == $user->id) {
                    $conversation->user1_deleted = false;
                } else {
                    $conversation->user2_deleted = false;
                }
                $conversation->last_message_at = now();
                $conversation->save();
            }
            
            // Créer le message
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $user->id,
                'receiver_id' => $receiverId,
                'subject' => $request->subject,
                'body' => $request->body,
                'is_read' => false,
            ]);
            
            // Gérer les pièces jointes
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('message-attachments', 'public');
                    
                    MessageAttachment::create([
                        'message_id' => $message->id,
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'file_type' => $file->getMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }
            
            DB::commit();
            
            return redirect()->route('messages.show', $conversation->id)
                ->with('success', 'Message envoyé avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de l\'envoi du message: ' . $e->getMessage());
        }
    }

    /**
     * Affiche le formulaire pour créer un nouveau message
     * 
     * Affiche le formulaire permettant de créer un nouveau message.
     * Peut pré-remplir le destinataire si un paramètre 'to' est fourni.
     * 
     * @param \Illuminate\Http\Request $request Requête HTTP (peut contenir 'to' pour pré-remplir)
     * @return \Illuminate\View\View Vue du formulaire de création
     * 
     * @example
     * Route: GET /dashboard/messages/create?to=5
     * Retourne la vue messages.create avec :
     * - $receiver : Modèle User si 'to' est fourni, null sinon
     * - $users : Collection de tous les utilisateurs (sauf l'utilisateur actuel)
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $receiverId = $request->get('to');
        $receiver = null;
        
        if ($receiverId) {
            $receiver = User::find($receiverId);
        }
        
        // Liste des utilisateurs pour le select (exclure l'utilisateur actuel)
        $users = User::where('id', '!=', $user->id)
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();
        
        return view('messages.create', compact('receiver', 'users'));
    }

    /**
     * Répond à un message dans une conversation existante
     * 
     * Permet d'envoyer une réponse dans une conversation existante.
     * Gère également l'upload de pièces jointes. Supporte les requêtes AJAX.
     * 
     * @param \Illuminate\Http\Request $request Requête HTTP contenant le contenu de la réponse
     * @param int $conversationId ID de la conversation
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse Redirection ou réponse JSON
     * @throws \Illuminate\Validation\ValidationException Si la validation échoue
     * @throws \Illuminate\Http\Exceptions\HttpResponseException Si l'utilisateur n'a pas accès
     * 
     * @example
     * Route: POST /dashboard/messages/5/reply
     * Données attendues :
     * - body (required|string|min:1)
     * - attachments (nullable|array|max:5)
     * - attachments.* (file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt)
     * 
     * Retourne une redirection ou une réponse JSON selon le type de requête.
     */
    public function reply(Request $request, $conversationId)
    {
        $user = Auth::user();
        $conversation = Conversation::findOrFail($conversationId);
        
        // Vérifier que l'utilisateur fait partie de la conversation
        if ($conversation->user1_id != $user->id && $conversation->user2_id != $user->id) {
            abort(403, 'Accès non autorisé');
        }
        
        $request->validate([
            'body' => 'required|string|min:1',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt',
        ]);
        
        $receiverId = $conversation->getOtherUser($user->id)->id;
        
        DB::beginTransaction();
        try {
            // Créer le message
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $user->id,
                'receiver_id' => $receiverId,
                'subject' => $request->subject ?? $conversation->messages()->latest()->first()?->subject,
                'body' => $request->body,
                'is_read' => false,
            ]);
            
            // Gérer les pièces jointes
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('message-attachments', 'public');
                    
                    MessageAttachment::create([
                        'message_id' => $message->id,
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'file_type' => $file->getMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }
            
            // Mettre à jour la conversation
            $conversation->update([
                'last_message_at' => now(),
            ]);
            
            // Réactiver la conversation si elle était supprimée
            if ($conversation->user1_id == $user->id) {
                $conversation->user1_deleted = false;
            } else {
                $conversation->user2_deleted = false;
            }
            $conversation->save();
            
            DB::commit();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message->load(['sender', 'attachments']),
                ]);
            }
            
            return redirect()->route('messages.show', $conversation->id)
                ->with('success', 'Message envoyé avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'envoi du message.',
                ], 500);
            }
            
            return back()->with('error', 'Erreur lors de l\'envoi du message: ' . $e->getMessage());
        }
    }

    /**
     * Marque un message spécifique comme lu
     * 
     * @param int $messageId ID du message
     * @return \Illuminate\Http\JsonResponse Réponse JSON avec le résultat
     * @throws \Illuminate\Http\Exceptions\HttpResponseException Si l'utilisateur n'est pas le destinataire
     * 
     * @example
     * Route: POST /dashboard/messages/message/123/read
     * Retourne JSON : {"success": true}
     */
    public function markAsRead($messageId)
    {
        $user = Auth::user();
        $message = Message::findOrFail($messageId);
        
        // Vérifier que l'utilisateur est le destinataire
        if ($message->receiver_id != $user->id) {
            abort(403, 'Accès non autorisé');
        }
        
        $message->markAsRead();
        
        return response()->json(['success' => true]);
    }

    /**
     * Marque tous les messages non lus comme lus
     * 
     * @return \Illuminate\Http\JsonResponse Réponse JSON avec le résultat
     * 
     * @example
     * Route: POST /dashboard/messages/mark-all-read
     * Retourne JSON : {"success": true}
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        
        Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        
        return response()->json(['success' => true]);
    }

    /**
     * Supprime une conversation pour l'utilisateur connecté
     * 
     * Marque la conversation et tous ses messages comme supprimés
     * pour l'utilisateur, mais ne les supprime pas réellement de la base.
     * 
     * @param int $conversationId ID de la conversation
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste des conversations
     * @throws \Illuminate\Http\Exceptions\HttpResponseException Si l'utilisateur n'a pas accès
     * 
     * @example
     * Route: POST /dashboard/messages/5/delete
     * Retourne une redirection vers messages.index avec message de succès.
     */
    public function deleteConversation($conversationId)
    {
        $user = Auth::user();
        $conversation = Conversation::findOrFail($conversationId);
        
        // Vérifier que l'utilisateur fait partie de la conversation
        if ($conversation->user1_id != $user->id && $conversation->user2_id != $user->id) {
            abort(403, 'Accès non autorisé');
        }
        
        // Marquer la conversation comme supprimée pour cet utilisateur
        if ($conversation->user1_id == $user->id) {
            $conversation->user1_deleted = true;
        } else {
            $conversation->user2_deleted = true;
        }
        $conversation->save();
        
        // Marquer tous les messages comme supprimés pour cet utilisateur
        Message::where('conversation_id', $conversationId)
            ->where(function($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->orWhere('receiver_id', $user->id);
            })
            ->get()
            ->each(function($message) use ($user) {
                if ($message->sender_id == $user->id) {
                    $message->sender_deleted = true;
                } else {
                    $message->receiver_deleted = true;
                }
                $message->save();
            });
        
        return redirect()->route('messages.index')
            ->with('success', 'Conversation supprimée.');
    }

    /**
     * Supprime un message pour l'utilisateur connecté
     * 
     * Marque le message comme supprimé pour l'utilisateur (expéditeur ou destinataire),
     * mais ne le supprime pas réellement de la base.
     * 
     * @param int $messageId ID du message
     * @return \Illuminate\Http\JsonResponse Réponse JSON avec le résultat
     * @throws \Illuminate\Http\Exceptions\HttpResponseException Si l'utilisateur n'a pas accès
     * 
     * @example
     * Route: POST /dashboard/messages/message/123/delete
     * Retourne JSON : {"success": true}
     */
    public function deleteMessage($messageId)
    {
        $user = Auth::user();
        $message = Message::findOrFail($messageId);
        
        // Vérifier que l'utilisateur est l'expéditeur ou le destinataire
        if ($message->sender_id != $user->id && $message->receiver_id != $user->id) {
            abort(403, 'Accès non autorisé');
        }
        
        // Marquer le message comme supprimé
        if ($message->sender_id == $user->id) {
            $message->sender_deleted = true;
        } else {
            $message->receiver_deleted = true;
        }
        $message->save();
        
        return response()->json(['success' => true]);
    }

    /**
     * API: Récupère les nouveaux messages non lus (pour polling AJAX)
     * 
     * Utilisé pour mettre à jour en temps réel le nombre de messages non lus
     * sans recharger la page.
     * 
     * @param \Illuminate\Http\Request $request Requête HTTP (peut contenir 'last_message_id')
     * @return \Illuminate\Http\JsonResponse Réponse JSON avec les nouveaux messages
     * 
     * @example
     * Route: GET /dashboard/messages/api/new-messages?last_message_id=100
     * Retourne JSON :
     * {
     *   "success": true,
     *   "messages": [...],
     *   "count": 3
     * }
     */
    public function getNewMessages(Request $request)
    {
        $user = Auth::user();
        $lastMessageId = $request->get('last_message_id', 0);
        
        $messages = Message::where('receiver_id', $user->id)
            ->where('id', '>', $lastMessageId)
            ->where('is_read', false)
            ->where('receiver_deleted', false)
            ->with(['sender', 'conversation'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'messages' => $messages,
            'count' => $messages->count(),
        ]);
    }

    /**
     * Télécharge une pièce jointe d'un message
     * 
     * Vérifie que l'utilisateur a accès au message avant d'autoriser
     * le téléchargement de la pièce jointe.
     * 
     * @param int $attachmentId ID de la pièce jointe
     * @return \Symfony\Component\HttpFoundation\StreamedResponse Réponse de téléchargement
     * @throws \Illuminate\Http\Exceptions\HttpResponseException Si l'utilisateur n'a pas accès
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException Si le fichier n'existe pas
     * 
     * @example
     * Route: GET /dashboard/messages/attachment/123/download
     * Retourne le fichier en téléchargement.
     */
    public function downloadAttachment($attachmentId)
    {
        $user = Auth::user();
        $attachment = MessageAttachment::with('message')->findOrFail($attachmentId);
        
        // Vérifier que l'utilisateur a accès au message
        $message = $attachment->message;
        if ($message->sender_id != $user->id && $message->receiver_id != $user->id) {
            abort(403, 'Accès non autorisé');
        }
        
        if (!Storage::disk('public')->exists($attachment->file_path)) {
            abort(404, 'Fichier introuvable');
        }
        
        return Storage::disk('public')->download($attachment->file_path, $attachment->file_name);
    }
}
