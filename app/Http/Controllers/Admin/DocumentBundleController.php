<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentBundle;
use App\Models\Epreuve;
use App\Rules\ValidDocumentFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DocumentBundleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bundles = DocumentBundle::with('items.itemable')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.documents.bundles.index', compact('bundles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $documents = Document::published()->active()->get();
        $epreuves = Epreuve::published()->get();
        $categories = \App\Models\DocumentCategory::active()->ordered()->get();
        return view('admin.documents.bundles.create', compact('documents', 'epreuves', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_type' => 'nullable|in:internal,external',
            'cover_image_file' => 'nullable|image|max:5120',
            'cover_image_url' => 'nullable|url',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'document_ids' => 'nullable|array',
            'document_ids.*' => 'exists:documents,id',
            'epreuve_ids' => 'nullable|array',
            'epreuve_ids.*' => 'exists:epreuves,id',
        ]);

        $documentIds = $request->input('document_ids', []);
        $epreuveIds = $request->input('epreuve_ids', []);
        $hasNewDocument = $request->filled('new_document.title') && $request->hasFile('new_document.file');
        $totalCount = count($documentIds) + count($epreuveIds) + ($hasNewDocument ? 1 : 0);

        if ($totalCount < 2) {
            return back()->withInput()
                ->withErrors(['documents' => 'Le pack doit contenir au moins 2 éléments (documents et/ou épreuves).']);
        }

        if ($hasNewDocument) {
            $documentIds[] = $this->createFascicule($request);
        }

        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');
        $validated = array_merge($validated, $this->handleCoverImage($request));

        $bundle = DocumentBundle::create($validated);

        $this->attachItems($bundle, $documentIds, $epreuveIds);

        return redirect()->route('admin.documents.bundles.index')
            ->with('success', 'Pack créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bundle = DocumentBundle::with('items.itemable')->findOrFail($id);
        return view('admin.documents.bundles.show', compact('bundle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $bundle = DocumentBundle::with('items.itemable')->findOrFail($id);
        $documents = Document::published()->active()->get();
        $epreuves = Epreuve::published()->get();
        $categories = \App\Models\DocumentCategory::active()->ordered()->get();
        return view('admin.documents.bundles.edit', compact('bundle', 'documents', 'epreuves', 'categories'));
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
            'cover_type' => 'nullable|in:internal,external',
            'cover_image_file' => 'nullable|image|max:5120',
            'cover_image_url' => 'nullable|url',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'document_ids' => 'nullable|array',
            'document_ids.*' => 'exists:documents,id',
            'epreuve_ids' => 'nullable|array',
            'epreuve_ids.*' => 'exists:epreuves,id',
        ]);

        $documentIds = $request->input('document_ids', []);
        $epreuveIds = $request->input('epreuve_ids', []);
        $hasNewDocument = $request->filled('new_document.title') && $request->hasFile('new_document.file');
        $totalCount = count($documentIds) + count($epreuveIds) + ($hasNewDocument ? 1 : 0);

        if ($totalCount < 2) {
            return back()->withInput()
                ->withErrors(['documents' => 'Le pack doit contenir au moins 2 éléments (documents et/ou épreuves).']);
        }

        if ($hasNewDocument) {
            $documentIds[] = $this->createFascicule($request);
        }

        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');
        $validated = array_merge($validated, $this->handleCoverImage($request, $bundle));

        $bundle->update($validated);

        $bundle->items()->delete();
        $this->attachItems($bundle, $documentIds, $epreuveIds);

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

    /**
     * Sert l'image de couverture interne d'un pack (contourne l'absence de symlink /storage/ sur Infomaniak)
     */
    public function serveCover($id)
    {
        $bundle = DocumentBundle::findOrFail($id);

        if ($bundle->cover_type !== 'internal' || empty($bundle->cover_image)) {
            abort(404);
        }

        $path = $bundle->cover_image;
        $storagePath = storage_path('app/public/' . $path);
        $publicPath = public_path('storage/' . $path);

        $fullPath = null;
        if (file_exists($storagePath) && is_file($storagePath)) {
            $fullPath = $storagePath;
        } elseif (file_exists($publicPath) && is_file($publicPath)) {
            $fullPath = $publicPath;
        }

        if (!$fullPath) {
            abort(404);
        }

        $mimeType = mime_content_type($fullPath) ?: 'image/png';

        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    /**
     * Crée un document ("fascicule") directement depuis le formulaire de pack et retourne son ID.
     */
    private function createFascicule(Request $request): int
    {
        $newDocValidated = $request->validate([
            'new_document.title' => 'required|string|max:255',
            'new_document.category_id' => 'required|exists:document_categories,id',
            'new_document.price' => 'required|numeric|min:0',
            'new_document.file' => ['required', 'file', new ValidDocumentFile(), 'max:102400'],
        ])['new_document'];

        $file = $request->file('new_document.file');
        $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('documents', $fileName, 'local');

        $document = Document::create([
            'title' => $newDocValidated['title'],
            'slug' => Str::slug($newDocValidated['title']) . '-' . Str::lower(Str::random(6)),
            'category_id' => $newDocValidated['category_id'],
            'price' => $newDocValidated['price'],
            'file_path' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'file_type' => $file->getMimeType(),
            'file_extension' => $file->getClientOriginalExtension(),
            'author_id' => Auth::id(),
            'status' => 'published',
            'is_active' => true,
            'published_at' => now(),
        ]);

        return $document->id;
    }

    /**
     * Gère l'upload (interne) ou l'URL (externe) de l'image de couverture d'un pack.
     */
    private function handleCoverImage(Request $request, ?DocumentBundle $bundle = null): array
    {
        $coverType = $request->input('cover_type');

        if ($coverType === 'internal' && $request->hasFile('cover_image_file')) {
            if ($bundle && $bundle->cover_type === 'internal' && $bundle->cover_image
                && \Illuminate\Support\Facades\Storage::disk('public')->exists($bundle->cover_image)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($bundle->cover_image);
            }

            $image = $request->file('cover_image_file');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('bundle-covers', $imageName, 'public');

            return ['cover_image' => $imagePath, 'cover_type' => 'internal'];
        }

        if ($coverType === 'external' && $request->filled('cover_image_url')) {
            $url = trim($request->input('cover_image_url', ''));
            if (!empty($url) && (str_starts_with($url, 'http://') || str_starts_with($url, 'https://'))) {
                return ['cover_image' => $url, 'cover_type' => 'external'];
            }
        }

        return $bundle ? [] : ['cover_image' => null, 'cover_type' => null];
    }

    /**
     * Attache les documents et épreuves sélectionnés au pack, dans l'ordre de sélection.
     */
    private function attachItems(DocumentBundle $bundle, array $documentIds, array $epreuveIds): void
    {
        $order = 0;

        foreach ($documentIds as $documentId) {
            $bundle->items()->create([
                'item_type' => Document::class,
                'item_id' => $documentId,
                'order' => $order++,
            ]);
        }

        foreach ($epreuveIds as $epreuveId) {
            $bundle->items()->create([
                'item_type' => Epreuve::class,
                'item_id' => $epreuveId,
                'order' => $order++,
            ]);
        }
    }
}
