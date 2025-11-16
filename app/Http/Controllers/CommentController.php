<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\JobArticle;
use Illuminate\Support\Facades\RateLimiter;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'commentable_type' => 'required|string',
            'commentable_id' => 'required|integer',
            'content' => 'required|string|min:10|max:2000',
            'parent_id' => 'nullable|exists:comments,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        // Rate limiting : 5 commentaires par 15 minutes par IP
        $key = 'comment:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->with('error', 'Trop de commentaires envoyés. Veuillez patienter.');
        }
        RateLimiter::hit($key, 900); // 15 minutes

        $commentableType = $request->commentable_type;
        $commentableId = $request->commentable_id;

        // Vérifier que le commentable existe
        if ($commentableType === 'App\\Models\\JobArticle') {
            $commentable = JobArticle::findOrFail($commentableId);
        } else {
            return back()->with('error', 'Type de contenu invalide.');
        }

        $comment = Comment::create([
            'user_id' => null, // Toujours anonyme maintenant
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'commentable_type' => $commentableType,
            'commentable_id' => $commentableId,
            'content' => $request->content,
            'parent_id' => $request->parent_id,
            'status' => 'pending', // En attente de modération
            'ip_address' => $request->ip(),
        ]);

        // Invalider le cache
        if ($commentableType === 'App\\Models\\JobArticle') {
            \Illuminate\Support\Facades\Cache::forget("job_article_{$commentable->slug}");
        }

        return back()->with('success', 'Votre commentaire a été soumis avec succès! Il sera publié après modération.');
    }

    public function like($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->increment('likes');
        
        return response()->json(['likes' => $comment->likes]);
    }
}
