<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentWishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentWishlistController extends Controller
{
    /**
     * Afficher la wishlist de l'utilisateur
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté pour accéder à votre liste de souhaits.');
        }

        $wishlistItems = DocumentWishlist::where('user_id', Auth::id())
            ->with(['document.category', 'document.author'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('dashboard.wishlist', compact('wishlistItems'));
    }

    /**
     * Ajouter/Retirer un document de la wishlist (AJAX)
     */
    public function toggle(Request $request, $documentId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous devez être connecté pour ajouter à la wishlist.',
            ], 401);
        }

        $document = Document::published()->active()->findOrFail($documentId);

        $inWishlist = DocumentWishlist::isInWishlist(Auth::id(), $document->id);

        if ($inWishlist) {
            // Retirer de la wishlist
            DocumentWishlist::removeFromWishlist(Auth::id(), $document->id);
            $action = 'removed';
            $message = 'Document retiré de votre liste de souhaits.';
        } else {
            // Ajouter à la wishlist
            DocumentWishlist::addToWishlist(Auth::id(), $document->id, true);
            $action = 'added';
            $message = 'Document ajouté à votre liste de souhaits.';
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'action' => $action,
                'message' => $message,
                'in_wishlist' => !$inWishlist,
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Retirer un document de la wishlist
     */
    public function remove($documentId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        DocumentWishlist::removeFromWishlist(Auth::id(), $documentId);

        return back()->with('success', 'Document retiré de votre liste de souhaits.');
    }
}
