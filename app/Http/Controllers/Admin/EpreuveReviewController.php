<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EpreuveReview;
use Illuminate\Http\Request;

class EpreuveReviewController extends Controller
{
    /**
     * Affiche la liste des avis en attente de modération.
     */
    public function index(Request $request)
    {
        $query = EpreuveReview::with(['epreuve', 'user'])
            ->orderBy('created_at', 'desc');

        if ($request->has('status')) {
            if ($request->status === 'pending') {
                $query->where('is_approved', false);
            } elseif ($request->status === 'approved') {
                $query->where('is_approved', true);
            }
        }

        $reviews = $query->paginate(20);

        $stats = [
            'total' => EpreuveReview::count(),
            'pending' => EpreuveReview::where('is_approved', false)->count(),
            'approved' => EpreuveReview::where('is_approved', true)->count(),
        ];

        return view('admin.epreuves.reviews.index', compact('reviews', 'stats'));
    }

    /**
     * Approuve un avis.
     */
    public function approve($reviewId)
    {
        $review = EpreuveReview::findOrFail($reviewId);
        $review->update(['is_approved' => true]);

        return back()->with('success', 'Avis approuvé avec succès.');
    }

    /**
     * Supprime un avis.
     */
    public function destroy($reviewId)
    {
        $review = EpreuveReview::findOrFail($reviewId);
        $review->delete();

        return back()->with('success', 'Avis supprimé avec succès.');
    }
}
