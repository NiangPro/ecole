<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\LocaleTrait;
use App\Models\DocumentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentCategoryController extends Controller
{
    use LocaleTrait;

    public function index()
    {
        $this->ensureLocale();
        $categories = DocumentCategory::with('parent', 'children')
            ->ordered()
            ->get();
        return view('admin.documents.categories.index', compact('categories'));
    }

    public function create()
    {
        $this->ensureLocale();
        $parentCategories = DocumentCategory::active()->ordered()->get();
        return view('admin.documents.categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $this->ensureLocale();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:document_categories,slug',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'image' => 'nullable|string',
            'image_type' => 'required|in:internal,external',
            'parent_id' => 'nullable|exists:document_categories,id',
            'is_active' => 'boolean',
            'order' => 'nullable|integer|min:0'
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_active'] = $request->has('is_active');

        // Gérer l'upload d'image si c'est une image interne
        if ($validated['image_type'] === 'internal' && $request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('document-category-images', 'public');
            $validated['image'] = $path;
        }

        DocumentCategory::create($validated);

        return redirect()->route('admin.documents.categories.index')
            ->with('success', 'Catégorie créée avec succès');
    }

    public function edit($id)
    {
        $this->ensureLocale();
        $category = DocumentCategory::findOrFail($id);
        $parentCategories = DocumentCategory::where('id', '!=', $id)
            ->active()
            ->ordered()
            ->get();
        return view('admin.documents.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, $id)
    {
        $this->ensureLocale();
        $category = DocumentCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:document_categories,slug,' . $id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'image' => 'nullable|string',
            'image_type' => 'required|in:internal,external',
            'parent_id' => 'nullable|exists:document_categories,id|not_in:' . $id,
            'is_active' => 'boolean',
            'order' => 'nullable|integer|min:0'
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_active'] = $request->has('is_active');

        // Gérer l'upload d'image si c'est une image interne
        if ($validated['image_type'] === 'internal') {
            if ($request->hasFile('image_file')) {
                // Supprimer l'ancienne image si elle existe
                if ($category->image && $category->image_type === 'internal' && Storage::disk('public')->exists($category->image)) {
                    Storage::disk('public')->delete($category->image);
                }
                $path = $request->file('image_file')->store('document-category-images', 'public');
                $validated['image'] = $path;
            }
        }

        $category->update($validated);

        return redirect()->route('admin.documents.categories.index')
            ->with('success', 'Catégorie mise à jour avec succès');
    }

    public function destroy($id)
    {
        $this->ensureLocale();
        $category = DocumentCategory::findOrFail($id);

        // Vérifier s'il y a des documents dans cette catégorie
        if ($category->documents()->count() > 0) {
            return redirect()->route('admin.documents.categories.index')
                ->with('error', 'Impossible de supprimer cette catégorie car elle contient des documents.');
        }

        // Vérifier s'il y a des sous-catégories
        if ($category->children()->count() > 0) {
            return redirect()->route('admin.documents.categories.index')
                ->with('error', 'Impossible de supprimer cette catégorie car elle contient des sous-catégories.');
        }

        // Supprimer l'image si elle existe
        if ($category->image && $category->image_type === 'internal' && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('admin.documents.categories.index')
            ->with('success', 'Catégorie supprimée avec succès');
    }
}
