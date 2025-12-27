<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentBundle;
use App\Models\Document;
use Illuminate\Http\Request;

class DocumentBundleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bundles = DocumentBundle::with('items.document')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.documents.bundles.index', compact('bundles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $documents = Document::published()->active()->get();
        return view('admin.documents.bundles.create', compact('documents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|string',
            'cover_type' => 'nullable|in:internal,external',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'documents' => 'required|array|min:2',
            'documents.*' => 'exists:documents,id',
        ]);

        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        $bundle = DocumentBundle::create($validated);

        // Ajouter les documents au bundle
        foreach ($request->documents as $index => $documentId) {
            $bundle->items()->create([
                'document_id' => $documentId,
                'order' => $index,
            ]);
        }

        return redirect()->route('admin.documents.bundles.index')
            ->with('success', 'Pack créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bundle = DocumentBundle::with('items.document')->findOrFail($id);
        return view('admin.documents.bundles.show', compact('bundle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $bundle = DocumentBundle::with('items')->findOrFail($id);
        $documents = Document::published()->active()->get();
        return view('admin.documents.bundles.edit', compact('bundle', 'documents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $bundle = DocumentBundle::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|string',
            'cover_type' => 'nullable|in:internal,external',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'documents' => 'required|array|min:2',
            'documents.*' => 'exists:documents,id',
        ]);

        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        $bundle->update($validated);

        // Mettre à jour les documents du bundle
        $bundle->items()->delete();
        foreach ($request->documents as $index => $documentId) {
            $bundle->items()->create([
                'document_id' => $documentId,
                'order' => $index,
            ]);
        }

        return redirect()->route('admin.documents.bundles.index')
            ->with('success', 'Pack mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bundle = DocumentBundle::findOrFail($id);
        $bundle->delete();

        return redirect()->route('admin.documents.bundles.index')
            ->with('success', 'Pack supprimé avec succès.');
    }
}
