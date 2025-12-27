<?php

namespace App\Http\Controllers;

use App\Models\ForumCategory;
use App\Models\ForumTopic;
use App\Models\ForumReply;
use App\Models\ForumVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Contrôleur pour la gestion du forum communautaire
 * 
 * Ce contrôleur gère toutes les fonctionnalités du forum :
 * - Affichage des catégories et topics
 * - Création et gestion des topics
 * - Système de réponses et votes
 * - Recherche dans le forum
 * - Actions administratives (épingler, verrouiller)
 * 
 * @package App\Http\Controllers
 * @author NiangProgrammeur
 * @since 2024-12-25
 */
class ForumController extends Controller
{
    /**
     * Affiche la liste des catégories du forum avec les statistiques
     * 
     * Récupère toutes les catégories actives du forum avec le nombre de topics
     * et affiche les statistiques globales (topics, réponses, membres).
     * 
     * @return \Illuminate\View\View Vue contenant les catégories et statistiques
     * 
     * @example
     * Route: GET /forum
     * Retourne la vue forum.index avec :
     * - $categories : Collection des catégories actives
     * - $stats : Tableau avec total_topics, total_replies, total_users
     */
    public function index()
    {
        $categories = ForumCategory::where('is_active', true)
            ->withCount(['topics' => function($query) {
                $query->where('is_locked', false);
            }])
            ->orderBy('order')
            ->orderBy('name')
            ->get();
        
        // Statistiques globales
        $stats = [
            'total_topics' => ForumTopic::count(),
            'total_replies' => ForumReply::count(),
            'total_users' => \App\Models\User::whereHas('forumTopics')->orWhereHas('forumReplies')->distinct()->count(),
        ];
        
        return view('forum.index', compact('categories', 'stats'));
    }

    /**
     * Affiche les topics d'une catégorie spécifique
     * 
     * Récupère tous les topics d'une catégorie avec pagination.
     * Les topics épinglés sont affichés en premier, suivis des autres
     * triés par date de dernière réponse.
     * 
     * @param string $slug Slug de la catégorie
     * @return \Illuminate\View\View Vue contenant la catégorie et ses topics
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si la catégorie n'existe pas
     * 
     * @example
     * Route: GET /forum/category/general
     * Retourne la vue forum.category avec :
     * - $category : Modèle ForumCategory
     * - $topics : Paginator des topics de la catégorie
     */
    public function category($slug)
    {
        $category = ForumCategory::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
        
        $topics = ForumTopic::where('category_id', $category->id)
            ->with(['user', 'lastReplyUser'])
            ->withCount('replies')
            ->orderBy('is_pinned', 'desc')
            ->orderBy('last_reply_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('forum.category', compact('category', 'topics'));
    }

    /**
     * Affiche un topic avec ses réponses
     * 
     * Affiche le contenu d'un topic et toutes ses réponses paginées.
     * Incrémente automatiquement le compteur de vues.
     * Marque les réponses comme lues pour l'utilisateur connecté.
     * 
     * @param string $categorySlug Slug de la catégorie
     * @param string $topicSlug Slug du topic
     * @return \Illuminate\View\View Vue contenant le topic et ses réponses
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si le topic n'existe pas
     * 
     * @example
     * Route: GET /forum/general/mon-premier-topic
     * Retourne la vue forum.show avec :
     * - $category : Modèle ForumCategory
     * - $topic : Modèle ForumTopic (avec vues incrémentées)
     * - $replies : Paginator des réponses du topic
     */
    public function show($categorySlug, $topicSlug)
    {
        $category = ForumCategory::where('slug', $categorySlug)
            ->where('is_active', true)
            ->firstOrFail();
        
        $topic = ForumTopic::where('slug', $topicSlug)
            ->where('category_id', $category->id)
            ->with(['user', 'category'])
            ->firstOrFail();
        
        // Incrémenter les vues
        $topic->incrementViews();
        
        // Récupérer les réponses avec pagination
        $replies = ForumReply::where('topic_id', $topic->id)
            ->with(['user', 'votes'])
            ->orderBy('is_best_answer', 'desc')
            ->orderBy('created_at', 'asc')
            ->paginate(15);
        
        // Marquer les réponses comme lues pour l'utilisateur connecté
        if (Auth::check()) {
            foreach ($replies as $reply) {
                $reply->user_has_voted = $reply->hasUserVoted(Auth::id());
                $reply->user_vote = $reply->getUserVote(Auth::id());
            }
        }
        
        return view('forum.show', compact('category', 'topic', 'replies'));
    }

    /**
     * Affiche le formulaire de création d'un nouveau topic
     * 
     * Affiche le formulaire permettant à un utilisateur authentifié
     * de créer un nouveau topic dans une catégorie.
     * 
     * @param \Illuminate\Http\Request $request Requête HTTP
     * @return \Illuminate\View\View Vue du formulaire de création
     * 
     * @example
     * Route: GET /forum/create?category_id=1
     * Retourne la vue forum.create avec :
     * - $categories : Collection de toutes les catégories actives
     * - $categoryId : ID de la catégorie présélectionnée (optionnel)
     */
    public function create(Request $request)
    {
        $categoryId = $request->get('category_id');
        $categories = ForumCategory::where('is_active', true)
            ->orderBy('order')
            ->orderBy('name')
            ->get();
        
        return view('forum.create', compact('categories', 'categoryId'));
    }

    /**
     * Enregistre un nouveau topic dans la base de données
     * 
     * Valide et crée un nouveau topic dans la catégorie spécifiée.
     * Génère automatiquement un slug unique à partir du titre.
     * 
     * @param \Illuminate\Http\Request $request Requête HTTP contenant les données du topic
     * @return \Illuminate\Http\RedirectResponse Redirection vers le topic créé
     * @throws \Illuminate\Validation\ValidationException Si la validation échoue
     * 
     * @example
     * Route: POST /forum/create
     * Données attendues :
     * - category_id (required|exists:forum_categories,id)
     * - title (required|string|min:5|max:255)
     * - body (required|string|min:10)
     * 
     * Redirige vers /forum/{categorySlug}/{topicSlug} avec message de succès
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:forum_categories,id',
            'title' => 'required|string|min:5|max:255',
            'body' => 'required|string|min:10',
        ]);
        
        $category = ForumCategory::findOrFail($request->category_id);
        
        if (!$category->is_active) {
            return back()->with('error', 'Cette catégorie n\'est plus active.');
        }
        
        $topic = ForumTopic::create([
            'category_id' => $request->category_id,
            'user_id' => Auth::id(),
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'body' => $request->body,
            'last_reply_at' => now(),
            'last_reply_user_id' => Auth::id(),
        ]);
        
        return redirect()->route('forum.show', [$category->slug, $topic->slug])
            ->with('success', 'Topic créé avec succès !');
    }

    /**
     * Crée une réponse à un topic existant
     * 
     * Permet à un utilisateur authentifié de répondre à un topic.
     * Vérifie que le topic n'est pas verrouillé avant d'autoriser la réponse.
     * 
     * @param \Illuminate\Http\Request $request Requête HTTP contenant le contenu de la réponse
     * @param string $categorySlug Slug de la catégorie
     * @param string $topicSlug Slug du topic
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse Redirection ou réponse JSON
     * @throws \Illuminate\Validation\ValidationException Si la validation échoue
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si le topic n'existe pas
     * 
     * @example
     * Route: POST /forum/general/mon-topic/reply
     * Données attendues :
     * - body (required|string|min:5)
     * 
     * Retourne une redirection vers le topic ou une réponse JSON si requête AJAX
     */
    public function reply(Request $request, $categorySlug, $topicSlug)
    {
        $category = ForumCategory::where('slug', $categorySlug)->firstOrFail();
        $topic = ForumTopic::where('slug', $topicSlug)
            ->where('category_id', $category->id)
            ->firstOrFail();
        
        // Vérifier si le topic est verrouillé
        if ($topic->is_locked) {
            return back()->with('error', 'Ce topic est verrouillé. Vous ne pouvez plus y répondre.');
        }
        
        $request->validate([
            'body' => 'required|string|min:5',
        ]);
        
        $reply = ForumReply::create([
            'topic_id' => $topic->id,
            'user_id' => Auth::id(),
            'body' => $request->body,
        ]);
        
        // Mettre à jour le topic
        $topic->update([
            'last_reply_at' => now(),
            'last_reply_user_id' => Auth::id(),
        ]);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'reply' => $reply->load('user'),
            ]);
        }
        
        return redirect()->route('forum.show', [$category->slug, $topic->slug])
            ->with('success', 'Réponse ajoutée avec succès !');
    }

    /**
     * Vote pour une réponse (upvote ou downvote)
     * 
     * Permet à un utilisateur authentifié de voter pour une réponse.
     * Si l'utilisateur a déjà voté, le vote est modifié ou supprimé.
     * 
     * @param \Illuminate\Http\Request $request Requête HTTP contenant le type de vote
     * @param int $replyId ID de la réponse
     * @return \Illuminate\Http\JsonResponse Réponse JSON avec le résultat du vote
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si la réponse n'existe pas
     * 
     * @example
     * Route: POST /forum/reply/123/vote
     * Données attendues :
     * - type (required|in:upvote,downvote)
     * 
     * Retourne JSON :
     * {
     *   "success": true,
     *   "action": "added|removed|changed",
     *   "votes_count": 5
     * }
     */
    public function vote(Request $request, $replyId)
    {
        $reply = ForumReply::findOrFail($replyId);
        $user = Auth::user();
        
        $request->validate([
            'type' => 'required|in:upvote,downvote',
        ]);
        
        // Vérifier si l'utilisateur a déjà voté
        $existingVote = ForumVote::where('reply_id', $reply->id)
            ->where('user_id', $user->id)
            ->first();
        
        if ($existingVote) {
            // Si le même type de vote, supprimer le vote
            if ($existingVote->type === $request->type) {
                $existingVote->delete();
                return response()->json([
                    'success' => true,
                    'action' => 'removed',
                    'votes_count' => $reply->fresh()->votes_count,
                ]);
            } else {
                // Sinon, changer le type de vote
                $existingVote->update(['type' => $request->type]);
                return response()->json([
                    'success' => true,
                    'action' => 'changed',
                    'votes_count' => $reply->fresh()->votes_count,
                ]);
            }
        } else {
            // Créer un nouveau vote
            ForumVote::create([
                'reply_id' => $reply->id,
                'user_id' => $user->id,
                'type' => $request->type,
            ]);
            
            return response()->json([
                'success' => true,
                'action' => 'added',
                'votes_count' => $reply->fresh()->votes_count,
            ]);
        }
    }

    /**
     * Marque une réponse comme meilleure réponse
     * 
     * Permet au créateur du topic ou à un administrateur de marquer
     * une réponse comme meilleure réponse. Désélectionne automatiquement
     * les autres meilleures réponses du topic.
     * 
     * @param int $replyId ID de la réponse à marquer
     * @return \Illuminate\Http\RedirectResponse Redirection vers le topic
     * @throws \Illuminate\Http\Exceptions\HttpResponseException Si l'utilisateur n'a pas les droits
     * 
     * @example
     * Route: POST /forum/reply/123/best-answer
     * Seul le créateur du topic ou un admin peut marquer une réponse.
     * Retourne une redirection avec message de succès.
     */
    public function markBestAnswer($replyId)
    {
        $reply = ForumReply::with('topic')->findOrFail($replyId);
        $topic = $reply->topic;
        
        // Vérifier que l'utilisateur est le créateur du topic
        if ($topic->user_id != Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Seul le créateur du topic peut marquer une réponse comme meilleure.');
        }
        
        $reply->markAsBestAnswer();
        
        return back()->with('success', 'Réponse marquée comme meilleure réponse !');
    }

    /**
     * Recherche dans le forum
     * 
     * Recherche des topics par titre ou contenu.
     * Retourne les résultats paginés.
     * 
     * @param \Illuminate\Http\Request $request Requête HTTP contenant le terme de recherche
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse Vue des résultats ou redirection
     * 
     * @example
     * Route: GET /forum/search?q=laravel
     * Retourne la vue forum.search avec :
     * - $topics : Paginator des topics correspondants
     * - $query : Terme de recherche
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (empty($query)) {
            return redirect()->route('forum.index');
        }
        
        $topics = ForumTopic::where('title', 'like', "%{$query}%")
            ->orWhere('body', 'like', "%{$query}%")
            ->with(['user', 'category'])
            ->withCount('replies')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('forum.search', compact('topics', 'query'));
    }

    /**
     * Épingler ou désépingler un topic (admin uniquement)
     * 
     * Permet à un administrateur d'épingler un topic en haut de la liste
     * ou de le désépingler.
     * 
     * @param int $topicId ID du topic
     * @return \Illuminate\Http\RedirectResponse Redirection avec message de succès
     * @throws \Illuminate\Http\Exceptions\HttpResponseException Si l'utilisateur n'est pas admin
     * 
     * @example
     * Route: POST /forum/topic/123/pin
     * Retourne une redirection avec message de succès.
     */
    public function togglePin($topicId)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé');
        }
        
        $topic = ForumTopic::findOrFail($topicId);
        
        if ($topic->is_pinned) {
            $topic->unpin();
            $message = 'Topic désépinglé.';
        } else {
            $topic->pin();
            $message = 'Topic épinglé.';
        }
        
        return back()->with('success', $message);
    }

    /**
     * Verrouiller ou déverrouiller un topic (admin uniquement)
     * 
     * Permet à un administrateur de verrouiller un topic pour empêcher
     * de nouvelles réponses ou de le déverrouiller.
     * 
     * @param int $topicId ID du topic
     * @return \Illuminate\Http\RedirectResponse Redirection avec message de succès
     * @throws \Illuminate\Http\Exceptions\HttpResponseException Si l'utilisateur n'est pas admin
     * 
     * @example
     * Route: POST /forum/topic/123/lock
     * Retourne une redirection avec message de succès.
     */
    public function toggleLock($topicId)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé');
        }
        
        $topic = ForumTopic::findOrFail($topicId);
        
        if ($topic->is_locked) {
            $topic->unlock();
            $message = 'Topic déverrouillé.';
        } else {
            $topic->lock();
            $message = 'Topic verrouillé.';
        }
        
        return back()->with('success', $message);
    }

    /**
     * Supprime un topic (admin ou créateur)
     * 
     * Permet au créateur du topic ou à un administrateur de supprimer
     * un topic et toutes ses réponses associées.
     * 
     * @param int $topicId ID du topic à supprimer
     * @return \Illuminate\Http\RedirectResponse Redirection vers la catégorie
     * @throws \Illuminate\Http\Exceptions\HttpResponseException Si l'utilisateur n'a pas les droits
     * 
     * @example
     * Route: DELETE /forum/topic/123
     * Retourne une redirection vers la catégorie avec message de succès.
     */
    public function deleteTopic($topicId)
    {
        $topic = ForumTopic::findOrFail($topicId);
        
        if ($topic->user_id != Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé');
        }
        
        $categorySlug = $topic->category->slug;
        $topic->delete();
        
        return redirect()->route('forum.category', $categorySlug)
            ->with('success', 'Topic supprimé avec succès.');
    }

    /**
     * Supprime une réponse (admin ou créateur)
     * 
     * Permet au créateur de la réponse ou à un administrateur de supprimer
     * une réponse d'un topic.
     * 
     * @param int $replyId ID de la réponse à supprimer
     * @return \Illuminate\Http\RedirectResponse Redirection vers le topic
     * @throws \Illuminate\Http\Exceptions\HttpResponseException Si l'utilisateur n'a pas les droits
     * 
     * @example
     * Route: DELETE /forum/reply/123
     * Retourne une redirection vers le topic avec message de succès.
     */
    public function deleteReply($replyId)
    {
        $reply = ForumReply::with('topic')->findOrFail($replyId);
        
        if ($reply->user_id != Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé');
        }
        
        $topic = $reply->topic;
        $category = $topic->category;
        $reply->delete();
        
        return redirect()->route('forum.show', [$category->slug, $topic->slug])
            ->with('success', 'Réponse supprimée avec succès.');
    }
}
