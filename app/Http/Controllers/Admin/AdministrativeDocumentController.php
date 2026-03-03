<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdministrativeDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdministrativeDocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = AdministrativeDocument::query();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qry) use ($q) {
                $qry->where('title', 'like', "%{$q}%")
                    ->orWhere('category', 'like', "%{$q}%")
                    ->orWhere('summary', 'like', "%{$q}%");
            });
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $documents = $query->orderBy('title')->paginate(15)->withQueryString();
        $categories = AdministrativeDocument::whereNotNull('category')->distinct()->pluck('category')->sort()->values()->toArray();

        return view('admin.documents.administrative-documents.index', compact('documents', 'categories'));
    }

    /**
     * Sert l'image de couverture d'un document administratif (comme pour /documents)
     * via une URL signée, sans nécessiter de session.
     */
    public function serveCover($id)
    {
        $document = AdministrativeDocument::findOrFail($id);

        if ($document->cover_type !== 'internal' || empty($document->cover_image)) {
            abort(404);
        }

        $path = $document->cover_image;
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

    public function create()
    {
        $categories = AdministrativeDocument::whereNotNull('category')->distinct()->pluck('category')->sort()->values()->toArray();
        return view('admin.documents.administrative-documents.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'title' => 'required|string|max:255',
                'slug' => 'nullable|string|max:255',
                'seo_title' => 'nullable|string|max:255',
                'category' => 'nullable|string|max:100',
                'category_new' => 'nullable|string|max:100',
                'summary' => 'nullable|string',
                'seo_description' => 'nullable|string',
                'seo_keywords' => 'nullable|string|max:512',
                'purpose' => 'nullable|string',
                'target_audience' => 'nullable|string',
                'required_documents_text' => 'nullable|string',
                'where_to_apply_text' => 'nullable|string',
                'approx_cost' => 'nullable|string',
                'approx_delay' => 'nullable|string',
                'tips' => 'nullable|string',
                'cover_type' => 'required|in:internal,external',
                'cover_image_file' => 'nullable|image|max:2048',
                'cover_image_url' => 'nullable|url|max:2048',
                'is_featured' => 'nullable|boolean',
                'status' => 'required|in:draft,published',
            ],
            [
                'required' => 'Le champ :attribute est obligatoire.',
                'string' => 'Le champ :attribute doit être une chaîne de caractères.',
                'max.string' => 'Le champ :attribute ne doit pas dépasser :max caractères.',
                'in' => 'La valeur sélectionnée pour :attribute est invalide.',
                'image' => 'Le champ :attribute doit être une image valide.',
                'url' => 'Le champ :attribute doit être une URL valide.',
                'boolean' => 'Le champ :attribute doit être vrai ou faux.',
            ],
            [
                'title' => 'titre',
                'slug' => 'slug',
                'seo_title' => 'titre SEO',
                'category' => 'catégorie',
                'category_new' => 'nouvelle catégorie',
                'summary' => 'résumé',
                'seo_description' => 'meta description',
                'seo_keywords' => 'mots-clés',
                'purpose' => 'à quoi sert ce document',
                'target_audience' => 'public ciblé',
                'required_documents_text' => 'pièces à fournir',
                'where_to_apply_text' => 'lieux de dépôt',
                'approx_cost' => 'coût approximatif',
                'approx_delay' => 'délais moyens',
                'tips' => 'conseils',
                'cover_type' => 'type d’image de couverture',
                'cover_image_file' => 'image de couverture',
                'cover_image_url' => 'URL de l’image de couverture',
                'is_featured' => 'fiche vedette',
                'status' => 'statut',
            ]
        );

        $validated['slug'] = $this->resolveSlug($validated['title'], $validated['slug'] ?? null);
        $validated['category'] = $this->resolveCategory($request->input('category'), $request->input('category_new'));
        $validated['required_documents'] = $this->linesToArray($request->input('required_documents_text'));
        $validated['where_to_apply'] = $this->linesToArray($request->input('where_to_apply_text'));
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['cover_type'] = $request->input('cover_type', 'internal');
        $coverImage = $this->handleCoverImage($request, null);
        $validated['cover_image'] = $coverImage === false ? null : $coverImage;
        unset($validated['required_documents_text'], $validated['where_to_apply_text'], $validated['category_new']);

        AdministrativeDocument::create($validated);

        return redirect()->route('admin.documents.administrative-documents.index')
            ->with('success', 'Document administratif créé.');
    }

    public function edit(AdministrativeDocument $administrative_document)
    {
        $categories = AdministrativeDocument::whereNotNull('category')->distinct()->pluck('category')->sort()->values()->toArray();
        return view('admin.documents.administrative-documents.edit', ['document' => $administrative_document, 'categories' => $categories]);
    }

    public function update(Request $request, AdministrativeDocument $administrative_document)
    {
        $validated = $request->validate(
            [
                'title' => 'required|string|max:255',
                'slug' => 'nullable|string|max:255',
                'seo_title' => 'nullable|string|max:255',
                'category' => 'nullable|string|max:100',
                'category_new' => 'nullable|string|max:100',
                'summary' => 'nullable|string',
                'seo_description' => 'nullable|string',
                'seo_keywords' => 'nullable|string|max:512',
                'purpose' => 'nullable|string',
                'target_audience' => 'nullable|string',
                'required_documents_text' => 'nullable|string',
                'where_to_apply_text' => 'nullable|string',
                'approx_cost' => 'nullable|string',
                'approx_delay' => 'nullable|string',
                'tips' => 'nullable|string',
                'cover_type' => 'required|in:internal,external',
                'cover_image_file' => 'nullable|image|max:2048',
                'cover_image_url' => 'nullable|url|max:2048',
                'is_featured' => 'nullable|boolean',
                'status' => 'required|in:draft,published',
            ],
            [
                'required' => 'Le champ :attribute est obligatoire.',
                'string' => 'Le champ :attribute doit être une chaîne de caractères.',
                'max.string' => 'Le champ :attribute ne doit pas dépasser :max caractères.',
                'in' => 'La valeur sélectionnée pour :attribute est invalide.',
                'image' => 'Le champ :attribute doit être une image valide.',
                'url' => 'Le champ :attribute doit être une URL valide.',
                'boolean' => 'Le champ :attribute doit être vrai ou faux.',
            ],
            [
                'title' => 'titre',
                'slug' => 'slug',
                'seo_title' => 'titre SEO',
                'category' => 'catégorie',
                'category_new' => 'nouvelle catégorie',
                'summary' => 'résumé',
                'seo_description' => 'meta description',
                'seo_keywords' => 'mots-clés',
                'purpose' => 'à quoi sert ce document',
                'target_audience' => 'public ciblé',
                'required_documents_text' => 'pièces à fournir',
                'where_to_apply_text' => 'lieux de dépôt',
                'approx_cost' => 'coût approximatif',
                'approx_delay' => 'délais moyens',
                'tips' => 'conseils',
                'cover_type' => 'type d’image de couverture',
                'cover_image_file' => 'image de couverture',
                'cover_image_url' => 'URL de l’image de couverture',
                'is_featured' => 'fiche vedette',
                'status' => 'statut',
            ]
        );

        $validated['slug'] = $this->resolveSlug($validated['title'], $validated['slug'] ?? null, $administrative_document->id);
        $validated['category'] = $this->resolveCategory($request->input('category'), $request->input('category_new'));
        $validated['required_documents'] = $this->linesToArray($request->input('required_documents_text'));
        $validated['where_to_apply'] = $this->linesToArray($request->input('where_to_apply_text'));
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['cover_type'] = $request->input('cover_type', 'internal');
        $coverImage = $this->handleCoverImage($request, $administrative_document);
        if ($coverImage !== false) {
            $validated['cover_image'] = $coverImage;
        }
        unset($validated['required_documents_text'], $validated['where_to_apply_text'], $validated['category_new']);

        $administrative_document->update($validated);

        return redirect()->route('admin.documents.administrative-documents.index')
            ->with('success', 'Document administratif mis à jour.');
    }

    public function destroy(AdministrativeDocument $administrative_document)
    {
        $administrative_document->delete();
        return redirect()->route('admin.documents.administrative-documents.index')
            ->with('success', 'Document administratif supprimé.');
    }

    private function resolveCategory(?string $category, ?string $categoryNew): ?string
    {
        $value = ($category === '__new__' ? trim($categoryNew ?? '') : $category);
        return $value !== '' ? $value : null;
    }

    private function linesToArray(?string $text): array
    {
        if (empty(trim($text ?? ''))) {
            return [];
        }
        $lines = array_map('trim', explode("\n", $text));
        return array_values(array_filter($lines));
    }

    private function handleCoverImage(Request $request, ?AdministrativeDocument $document)
    {
        $coverType = $request->input('cover_type', 'internal');
        if ($coverType === 'internal' && $request->hasFile('cover_image_file')) {
            if ($document && $document->cover_type === 'internal' && $document->cover_image) {
                Storage::disk('public')->delete($document->cover_image);
            }
            return $request->file('cover_image_file')->store('administrative-document-covers', 'public');
        }
        if ($coverType === 'external' && $request->filled('cover_image_url')) {
            $url = trim($request->input('cover_image_url', ''));
            if (!empty($url) && (str_starts_with($url, 'http://') || str_starts_with($url, 'https://'))) {
                if ($document && $document->cover_type === 'internal' && $document->cover_image) {
                    Storage::disk('public')->delete($document->cover_image);
                }
                return $url;
            }
        }
        if ($coverType === 'external' && empty(trim($request->input('cover_image_url', '')))) {
            if ($document && $document->cover_type === 'internal' && $document->cover_image) {
                Storage::disk('public')->delete($document->cover_image);
            }
            return null;
        }
        return $document ? false : null;
    }

    private function resolveSlug(string $title, ?string $slug, ?int $excludeId = null): string
    {
        $base = $slug ? Str::slug($slug) : Str::slug($title);
        $existsQuery = AdministrativeDocument::where('slug', $base);
        if ($excludeId) {
            $existsQuery->where('id', '!=', $excludeId);
        }
        if (!$existsQuery->exists()) {
            return $base;
        }
        $counter = 1;
        while (true) {
            $candidate = $base . '-' . $counter;
            $q = AdministrativeDocument::where('slug', $candidate);
            if ($excludeId) {
                $q->where('id', '!=', $excludeId);
            }
            if (!$q->exists()) {
                return $candidate;
            }
            $counter++;
        }
    }
}
