<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $search = $request->get('search');
        $status = $request->get('status', '');
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');

        $query = Comment::with(['commentable', 'parent']);

        // Recherche
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Filtre par statut
        if ($status) {
            $query->where('status', $status);
        }

        // Tri
        $query->orderBy($sortBy, $sortOrder);

        $comments = $query->paginate(20)->withQueryString();

        // Statistiques
        $stats = [
            'total' => Comment::count(),
            'pending' => Comment::where('status', 'pending')->count(),
            'approved' => Comment::where('status', 'approved')->count(),
            'rejected' => Comment::where('status', 'rejected')->count(),
        ];

        return view('admin.comments.index', compact('comments', 'search', 'status', 'sortBy', 'sortOrder', 'stats'));
    }

    public function approve($id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $comment = Comment::findOrFail($id);
        $comment->update(['status' => 'approved']);

        // Invalider le cache
        if ($comment->commentable_type === 'App\\Models\\JobArticle') {
            $article = $comment->commentable;
            \Illuminate\Support\Facades\Cache::forget("job_article_{$article->slug}");
        }

        return back()->with('success', 'Commentaire approuvé avec succès!');
    }

    public function reject($id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $comment = Comment::findOrFail($id);
        $comment->update(['status' => 'rejected']);

        return back()->with('success', 'Commentaire rejeté avec succès!');
    }

    public function delete($id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $comment = Comment::findOrFail($id);
        $commentType = $comment->commentable_type;
        $commentable = $comment->commentable;
        
        $comment->delete();

        // Invalider le cache
        if ($commentType === 'App\\Models\\JobArticle') {
            \Illuminate\Support\Facades\Cache::forget("job_article_{$commentable->slug}");
        }

        return back()->with('success', 'Commentaire supprimé avec succès!');
    }

    public function getWhatsAppLink($id)
    {
        if (!session('admin_logged_in')) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $comment = Comment::findOrFail($id);
        $phone = $comment->phone ?? '';
        
        if (!$phone) {
            return response()->json(['error' => 'Aucun numéro de téléphone'], 404);
        }

        // Nettoyer le numéro pour WhatsApp
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        
        // Message par défaut
        $message = urlencode("Bonjour {$comment->name}, merci pour votre commentaire sur notre site !");
        
        $whatsappUrl = "https://wa.me/{$cleanPhone}?text={$message}";
        
        return response()->json(['url' => $whatsappUrl]);
    }

    public function getEmailLink($id)
    {
        if (!session('admin_logged_in')) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $comment = Comment::findOrFail($id);
        $email = $comment->email;
        
        if (!$email) {
            return response()->json(['error' => 'Aucun email'], 404);
        }

        // Sujet et message par défaut
        $subject = urlencode("Réponse à votre commentaire");
        $body = urlencode("Bonjour {$comment->name},\n\nMerci pour votre commentaire sur notre site !\n\nCordialement,\nNiangProgrammeur");
        
        $emailUrl = "mailto:{$email}?subject={$subject}&body={$body}";
        
        return response()->json(['url' => $emailUrl]);
    }
}
