<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    /**
     * Ajouter/Retirer un favori
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'type' => 'required|in:formation,article',
            'slug' => 'required|string',
            'name' => 'required|string|max:255',
        ]);

        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous devez être connecté pour ajouter aux favoris',
                'action' => 'login_required'
            ], 401);
        }

        $userId = Auth::id();
        $type = $request->type;
        $slug = $request->slug;
        $name = $request->name;

        $exists = Favorite::exists($userId, $type, $slug);

        if ($exists) {
            Favorite::remove($userId, $type, $slug);
            $action = 'removed';
            $message = 'Retiré des favoris';
        } else {
            Favorite::add($userId, $type, $slug, $name);
            $action = 'added';
            $message = 'Ajouté aux favoris';
        }

        return response()->json([
            'success' => true,
            'action' => $action,
            'message' => $message,
            'is_favorite' => !$exists
        ]);
    }

    /**
     * Vérifier si un élément est en favori
     */
    public function check(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['is_favorite' => false]);
        }

        $request->validate([
            'type' => 'required|in:formation,article',
            'slug' => 'required|string',
        ]);

        $isFavorite = Favorite::exists(
            Auth::id(),
            $request->type,
            $request->slug
        );

        return response()->json(['is_favorite' => $isFavorite]);
    }

    /**
     * Liste des favoris de l'utilisateur
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $favorites = Auth::user()
            ->favorites()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('dashboard.favorites', compact('favorites'));
    }
}
