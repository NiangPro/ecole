<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ForumCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Contrôleur admin pour la gestion des catégories du forum
 * 
 * Permet aux administrateurs de gérer les catégories du forum :
 * - Créer, modifier et supprimer des catégories
 * - Configurer l'ordre d'affichage, les couleurs et icônes
 * - Activer/désactiver des catégories
 * 
 * @package App\Http\Controllers\Admin
 * @author NiangProgrammeur
 * @since 2024-12-25
 */
class ForumCategoryController extends Controller
{
    /**
     * Affiche la liste de toutes les catégories du forum
     * 
     * Liste toutes les catégories avec leurs statistiques (nombre de topics)
     * triées par ordre d'affichage puis par nom.
     * 
     * @return \Illuminate\View\View Vue contenant la liste des catégories
     * 
     * @example
     * Route: GET /admin/forum/categories
     * Retourne la vue admin.forum.categories.index avec :
     * - $categories : Paginator des catégories
     */
    public function index()
    {
        $categories = ForumCategory::orderBy('order')->orderBy('name')->paginate(20);
        return view('admin.forum.categories.index', compact('categories'));
    }

    /**
     * Affiche le formulaire de création d'une nouvelle catégorie
     * 
     * @return \Illuminate\View\View Vue du formulaire de création
     * 
     * @example
     * Route: GET /admin/forum/categories/create
     * Retourne la vue admin.forum.categories.create
     */
    public function create()
    {
        return view('admin.forum.categories.create');
    }

    /**
     * Enregistre une nouvelle catégorie dans la base de données
     * 
     * Valide et crée une nouvelle catégorie. Génère automatiquement
     * un slug si non fourni.
     * 
     * @param \Illuminate\Http\Request $request Requête HTTP contenant les données de la catégorie
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste avec message de succès
     * @throws \Illuminate\Validation\ValidationException Si la validation échoue
     * 
     * @example
     * Route: POST /admin/forum/categories
     * Données attendues :
     * - name (required|string|max:255)
     * - slug (nullable|string|max:255|unique:forum_categories,slug)
     * - description (nullable|string)
     * - icon (nullable|string|max:255)
     * - color (nullable|string|max:7)
     * - order (nullable|integer|min:0)
     * - is_active (nullable|boolean)
     * 
     * Redirige vers admin.forum.categories.index avec message de succès.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:forum_categories,slug',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:7',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $category = ForumCategory::create([
            'name' => $request->name,
            'slug' => $request->slug ?? Str::slug($request->name),
            'description' => $request->description,
            'icon' => $request->icon ?? 'fas fa-folder',
            'color' => $request->color ?? '#06b6d4',
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
        ]);

        return redirect()->route('admin.forum.categories.index')
            ->with('success', 'Catégorie créée avec succès !');
    }

    /**
     * Affiche les détails d'une catégorie
     * 
     * @param \App\Models\ForumCategory $category Catégorie à afficher
     * @return \Illuminate\View\View Vue contenant les détails de la catégorie
     * 
     * @example
     * Route: GET /admin/forum/categories/1
     * Retourne la vue admin.forum.categories.show avec :
     * - $category : Modèle ForumCategory
     */
    public function show(ForumCategory $category)
    {
        return view('admin.forum.categories.show', compact('category'));
    }

    /**
     * Affiche le formulaire d'édition d'une catégorie
     * 
     * @param \App\Models\ForumCategory $category Catégorie à modifier
     * @return \Illuminate\View\View Vue du formulaire d'édition
     * 
     * @example
     * Route: GET /admin/forum/categories/1/edit
     * Retourne la vue admin.forum.categories.edit avec :
     * - $category : Modèle ForumCategory
     */
    public function edit(ForumCategory $category)
    {
        return view('admin.forum.categories.edit', compact('category'));
    }

    /**
     * Met à jour une catégorie existante
     * 
     * Valide et met à jour les données d'une catégorie.
     * Génère automatiquement un slug si non fourni.
     * 
     * @param \Illuminate\Http\Request $request Requête HTTP contenant les nouvelles données
     * @param \App\Models\ForumCategory $category Catégorie à modifier
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste avec message de succès
     * @throws \Illuminate\Validation\ValidationException Si la validation échoue
     * 
     * @example
     * Route: PUT /admin/forum/categories/1
     * Données attendues (mêmes que store)
     * 
     * Redirige vers admin.forum.categories.index avec message de succès.
     */
    public function update(Request $request, ForumCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:forum_categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:7',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => $request->slug ?? Str::slug($request->name),
            'description' => $request->description,
            'icon' => $request->icon ?? 'fas fa-folder',
            'color' => $request->color ?? '#06b6d4',
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
        ]);

        return redirect()->route('admin.forum.categories.index')
            ->with('success', 'Catégorie mise à jour avec succès !');
    }

    /**
     * Supprime une catégorie
     * 
     * Vérifie qu'il n'y a pas de topics dans la catégorie avant de permettre
     * la suppression. Si des topics existent, retourne une erreur.
     * 
     * @param \App\Models\ForumCategory $category Catégorie à supprimer
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste avec message
     * 
     * @example
     * Route: DELETE /admin/forum/categories/1
     * Retourne une redirection avec message de succès ou d'erreur si des topics existent.
     */
    public function destroy(ForumCategory $category)
    {
        // Vérifier s'il y a des topics dans cette catégorie
        if ($category->topics()->count() > 0) {
            return redirect()->route('admin.forum.categories.index')
                ->with('error', 'Impossible de supprimer cette catégorie car elle contient des topics.');
        }

        $category->delete();

        return redirect()->route('admin.forum.categories.index')
            ->with('success', 'Catégorie supprimée avec succès !');
    }
}
