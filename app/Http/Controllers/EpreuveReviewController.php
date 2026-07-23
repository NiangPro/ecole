<?php

namespace App\Http\Controllers;

use App\Models\Epreuve;
use App\Models\EpreuveReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EpreuveReviewController extends Controller
{
    /**
     * Créer un nouvel avis
     */
    public function store(Request $request, $epreuveId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $epreuve = Epreuve::findOrFail($epreuveId);

        $existingReview = EpreuveReview::where('user_id', Auth::id())
            ->where('epreuve_id', $epreuve->id)
            ->first();

        if ($existingReview) {
            $existingReview->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
                'is_approved' => false, // Réapprouver après modification
            ]);

            return back()->with('success', 'Votre avis a été mis à jour et sera réexaminé.');
        }

        EpreuveReview::create([
            'epreuve_id' => $epreuve->id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => false, // Nécessite modération
        ]);

        return back()->with('success', 'Votre avis a été soumis et sera examiné avant publication.');
    }

    /**
     * Supprimer un avis
     */
    public function destroy($reviewId)
    {
        $review = EpreuveReview::findOrFail($reviewId);

        if (Auth::check() && (Auth::id() === $review->user_id || Auth::user()->isAdmin())) {
            $review->delete();
            return back()->with('success', 'Avis supprimé avec succès.');
        }

        abort(403);
    }
}
