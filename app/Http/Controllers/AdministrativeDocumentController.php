<?php

namespace App\Http\Controllers;

use App\Models\AdministrativeDocument;
use Illuminate\Http\Request;

class AdministrativeDocumentController extends Controller
{
    /**
     * Liste des documents administratifs avec recherche / filtre.
     */
    public function index(Request $request)
    {
        $query = AdministrativeDocument::published();

        $search = trim((string) $request->get('q', ''));
        $category = trim((string) $request->get('category', ''));

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhere('purpose', 'like', "%{$search}%");
            });
        }

        if ($category !== '') {
            $query->where('category', $category);
        }

        $documents = $query
            ->orderByDesc('is_featured')
            ->orderBy('title')
            ->paginate(12)
            ->appends($request->only('q', 'category'));

        $categories = AdministrativeDocument::published()
            ->select('category')
            ->whereNotNull('category')
            ->groupBy('category')
            ->orderBy('category')
            ->pluck('category')
            ->toArray();

        return view('admin-docs.index', compact('documents', 'categories', 'search', 'category'));
    }

    /**
     * Affichage d'une fiche détaillée.
     */
    public function show(string $slug)
    {
        $document = AdministrativeDocument::published()->where('slug', $slug)->firstOrFail();

        return view('admin-docs.show', compact('document'));
    }
}

