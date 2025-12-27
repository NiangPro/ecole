<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentReview;
use App\Models\DocumentPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentReviewController extends Controller
{
    /**
     * Créer un nouvel avis
     */
    public function store(Request $request, $documentId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $document = Document::findOrFail($documentId);

        // Vérifier si l'utilisateur a acheté le document (pour avis vérifié)
        $isVerifiedPurchase = false;
        if (Auth::check()) {
            $isVerifiedPurchase = DocumentPurchase::where('user_id', Auth::id())
                ->where('document_id', $document->id)
                ->where('status', 'completed')
                ->exists();
        }

        // Vérifier si l'utilisateur a déjà laissé un avis
        $existingReview = null;
        if (Auth::check()) {
            $existingReview = DocumentReview::where('user_id', Auth::id())
                ->where('document_id', $document->id)
                ->first();
        }

        if ($existingReview) {
            // Mettre à jour l'avis existant
            $existingReview->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
                'is_approved' => false, // Réapprouver après modification
                'is_verified_purchase' => $isVerifiedPurchase,
            ]);

            return back()->with('success', 'Votre avis a été mis à jour et sera réexaminé.');
        }

        // Créer un nouvel avis
        $review = DocumentReview::create([
            'document_id' => $document->id,
            'user_id' => Auth::id(),
            'user_name' => Auth::check() ? null : $request->user_name,
            'user_email' => Auth::check() ? null : $request->user_email,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => false, // Nécessite modération
            'is_verified_purchase' => $isVerifiedPurchase,
        ]);

        return back()->with('success', 'Votre avis a été soumis et sera examiné avant publication.');
    }

    /**
     * Approuver un avis (Admin)
     */
    public function approve($reviewId)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $review = DocumentReview::findOrFail($reviewId);
        $review->update(['is_approved' => true]);

        return back()->with('success', 'Avis approuvé avec succès.');
    }

    /**
     * Liste des avis (Admin)
     */
    public function index(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $query = DocumentReview::with(['document', 'user'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->where('is_approved', false);
            } elseif ($request->status === 'approved') {
                $query->where('is_approved', true);
            }
        }

        $reviews = $query->paginate(20);

        return view('admin.documents.reviews.index', compact('reviews'));
    }

    /**
     * Supprimer un avis
     */
    public function destroy($reviewId)
    {
        $review = DocumentReview::findOrFail($reviewId);

        // Seul l'auteur ou un admin peut supprimer
        if (Auth::check() && (Auth::id() === $review->user_id || Auth::user()->isAdmin())) {
            $review->delete();
            return back()->with('success', 'Avis supprimé avec succès.');
        }

        abort(403);
    }
}
