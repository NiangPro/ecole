<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\JobArticle;
use Illuminate\Support\Facades\RateLimiter;
use App\Services\RecaptchaService;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        // Vérifier le Honeypot
        if ($request->has('website') && !empty($request->input('website'))) {
            // Bot détecté - rejeter silencieusement
            abort(403, 'Spam détecté');
        }

        // Vérifier le temps de remplissage du formulaire
        if ($request->has('_form_time')) {
            $formTime = (float) $request->input('_form_time');
            $submitTime = microtime(true);
            $timeDiff = $submitTime - $formTime;

            // Si le formulaire a été soumis en moins de 2 secondes, c'est probablement un bot
            if ($timeDiff < 2) {
                abort(403, 'Spam détecté');
            }
        }

        // Vérifier reCAPTCHA si configuré
        $recaptchaService = new RecaptchaService();
        if (!empty(config('services.recaptcha.secret_key'))) {
            $recaptchaToken = $request->input('g-recaptcha-response');
            if (!$recaptchaService->verify($recaptchaToken, $request->ip())) {
                return back()->with('error', 'Vérification anti-spam échouée. Veuillez réessayer.');
            }
        }

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

        // Sanitizer les données avant sauvegarde
        $sanitized = \App\Services\SanitizationService::sanitizeArray($request->all(), [
            'name' => 'sanitizeName',
            'email' => 'sanitizeEmail',
            'phone' => 'sanitizePhone',
            'content' => 'sanitizeContent',
        ]);
        
        $comment = Comment::create([
            'user_id' => null, // Toujours anonyme maintenant
            'name' => $sanitized['name'],
            'email' => $sanitized['email'],
            'phone' => $sanitized['phone'] ?? null,
            'commentable_type' => $commentableType,
            'commentable_id' => $commentableId,
            'content' => $sanitized['content'],
            'parent_id' => $request->parent_id,
            'status' => 'pending', // En attente de modération
            'ip_address' => $request->ip(),
        ]);

        // Invalider le cache
        if ($commentableType === 'App\\Models\\JobArticle') {
            \Illuminate\Support\Facades\Cache::forget("job_article_{$commentable->slug}");
            \Illuminate\Support\Facades\Cache::forget("article_comments_{$commentable->id}");
            \Illuminate\Support\Facades\Cache::forget("article_latest_comments_{$commentable->id}");
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
