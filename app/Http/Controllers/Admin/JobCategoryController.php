<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class JobCategoryController extends Controller
{
    public function index()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $categories = Category::orderBy('order')->orderBy('name')->get();
        return view('admin.jobs.categories.index', compact('categories'));
    }

    public function create()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        return view('admin.jobs.categories.create');
    }

    public function store(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:job_categories,slug',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'image' => 'nullable|string',
            'image_type' => 'required|in:internal,external',
            'is_active' => 'boolean',
            'order' => 'nullable|integer|min:0'
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_active'] = $request->has('is_active');

        // Gérer l'upload d'image si c'est une image interne
        if ($validated['image_type'] === 'internal' && $request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('category-images', 'public');
            $validated['image'] = $path;
        }

        $category = Category::create($validated);

        // Le cache sera invalidé automatiquement par l'événement du modèle

        return redirect()->route('admin.jobs.categories.index')
            ->with('success', 'Catégorie créée avec succès');
    }

    public function edit($id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $category = Category::findOrFail($id);
        return view('admin.jobs.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:job_categories,slug,' . $id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'image' => 'nullable|string',
            'image_type' => 'required|in:internal,external',
            'is_active' => 'boolean',
            'order' => 'nullable|integer|min:0'
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_active'] = $request->has('is_active');

        // Gérer l'upload d'image si c'est une image interne
        if ($validated['image_type'] === 'internal' && $request->hasFile('image_file')) {
            // Supprimer l'ancienne image si elle existe
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }
            $path = $request->file('image_file')->store('category-images', 'public');
            $validated['image'] = $path;
        }

        $category->update($validated);

        return redirect()->route('admin.jobs.categories.index')
            ->with('success', 'Catégorie mise à jour avec succès');
    }

    public function destroy($id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $category = Category::findOrFail($id);
        
        if ($category->articles()->count() > 0) {
            return redirect()->route('admin.jobs.categories.index')
                ->with('error', 'Impossible de supprimer cette catégorie car elle contient des articles');
        }

        $category->delete();

        return redirect()->route('admin.jobs.categories.index')
            ->with('success', 'Catégorie supprimée avec succès');
    }
}
