<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\JobArticle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * POST /api/articles
     *
     * Crée un article emploi/opportunité depuis un agent externe.
     * Requiert un Personal Access Token Sanctum dans l'en-tête Authorization.
     *
     * Corps JSON attendu :
     *   title            (string, requis)      – Titre de l'article
     *   content          (string, requis)      – Contenu HTML complet
     *   excerpt          (string, optionnel)   – Résumé affiché sur les cartes
     *   category_id      (int,    requis)      – ID de la catégorie (table job_categories)
     *   status           (string, optionnel)   – "draft" (défaut) | "published"
     *   cover_image_url  (string, optionnel)   – URL de l'image de couverture externe
     *   cover_type       (string, optionnel)   – "external" (défaut si cover_image_url fourni)
     *   meta_title       (string, optionnel)   – Titre SEO (max 70 car.)
     *   meta_description (string, optionnel)   – Description SEO (max 160 car.)
     *   meta_keywords    (array,  optionnel)   – Tableau de mots-clés ex: ["emploi","sénégal"]
     *   published_at     (string, optionnel)   – Date ISO 8601, défaut = now() si status=published
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title'            => ['required', 'string', 'max:255'],
            'content'          => ['required', 'string'],
            'excerpt'          => ['nullable', 'string', 'max:500'],
            'category_id'      => ['required', 'integer', 'exists:job_categories,id'],
            'status'           => ['nullable', 'string', 'in:draft,published'],
            'cover_image_url'  => ['nullable', 'url', 'max:2048'],
            'cover_type'       => ['nullable', 'string', 'in:internal,external'],
            'meta_title'       => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'meta_keywords'    => ['nullable', 'array'],
            'meta_keywords.*'  => ['string', 'max:50'],
            'published_at'     => ['nullable', 'date'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Les données fournies sont invalides.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $data   = $validator->validated();
        $status = $data['status'] ?? 'draft';

        // Slug unique généré depuis le titre
        $slug = $this->uniqueSlug($data['title']);

        // Image de couverture externe
        $coverImage = $data['cover_image_url'] ?? null;
        $coverType  = $coverImage ? 'external' : ($data['cover_type'] ?? 'external');

        // published_at automatique si on publie maintenant
        $publishedAt = null;
        if ($status === 'published') {
            $publishedAt = isset($data['published_at'])
                ? \Carbon\Carbon::parse($data['published_at'])
                : now();
        }

        $article = JobArticle::create([
            'title'            => $data['title'],
            'slug'             => $slug,
            'content'          => $data['content'],
            'excerpt'          => $data['excerpt'] ?? null,
            'category_id'      => $data['category_id'],
            'status'           => $status,
            'cover_image'      => $coverImage,
            'cover_type'       => $coverType,
            'meta_title'       => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'meta_keywords'    => $data['meta_keywords'] ?? null,
            'published_at'     => $publishedAt,
        ]);

        return response()->json([
            'message' => 'Article créé avec succès.',
            'article' => [
                'id'           => $article->id,
                'title'        => $article->title,
                'slug'         => $article->slug,
                'status'       => $article->status,
                'published_at' => $article->published_at?->toIso8601String(),
                'url'          => route('emplois.article', $article->slug),
                'category'     => $article->category_id
                    ? optional(Category::find($article->category_id))->name
                    : null,
            ],
        ], 201);
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i    = 1;

        while (JobArticle::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
