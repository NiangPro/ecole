<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentReview;
use Illuminate\Http\Request;

class DocumentReviewController extends Controller
{
    /**
     * Affiche la liste des avis en attente de modération.
     */
    public function index(Request $request)
    {
        $query = DocumentReview::with(['document', 'user'])
            ->orderBy('created_at', 'desc');

        // Filtrer par statut
        if ($request->has('status')) {
            if ($request->status === 'pending') {
                $query->where('is_approved', false);
            } elseif ($request->status === 'approved') {
                $query->where('is_approved', true);
            }
        } else {
            // Par défaut, afficher tous les avis
        }

        $reviews = $query->paginate(20);

        $stats = [
            'total' => DocumentReview::count(),
            'pending' => DocumentReview::where('is_approved', false)->count(),
            'approved' => DocumentReview::where('is_approved', true)->count(),
        ];

        $documents = Document::orderBy('title')->get(['id', 'title']);

        return view('admin.documents.reviews.index', compact('reviews', 'stats', 'documents'));
    }

    /**
     * Crée un avis manuellement (ex : retour reçu par WhatsApp/email).
     */
    public function store(Request $request)
    {
        $request->validate([
            'document_id' => 'required|exists:documents,id',
            'user_name' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'is_approved' => 'nullable|boolean',
            'is_verified_purchase' => 'nullable|boolean',
        ]);

        DocumentReview::create([
            'document_id' => $request->document_id,
            'user_name' => $request->user_name,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => $request->boolean('is_approved'),
            'is_verified_purchase' => $request->boolean('is_verified_purchase'),
        ]);

        return back()->with('success', 'Avis ajouté avec succès.');
    }

    /**
     * Approuve un avis.
     */
    public function approve($reviewId)
    {
        $review = DocumentReview::findOrFail($reviewId);
        $review->update(['is_approved' => true]);

        return back()->with('success', 'Avis approuvé avec succès.');
    }

    /**
     * Supprime un avis.
     */
    public function destroy($reviewId)
    {
        $review = DocumentReview::findOrFail($reviewId);
        $review->delete();

        return back()->with('success', 'Avis supprimé avec succès.');
    }
}

