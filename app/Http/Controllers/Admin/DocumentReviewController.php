<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

        return view('admin.documents.reviews.index', compact('reviews', 'stats'));
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

