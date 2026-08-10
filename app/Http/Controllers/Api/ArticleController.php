<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\DuplicateArticleException;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\JobArticlePublisher;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
     *   slug             (string, optionnel)   – Slug personnalisé, sinon généré depuis le titre
     *   content          (string, requis)      – Contenu HTML complet
     *   excerpt          (string, optionnel)   – Résumé affiché sur les cartes
     *   category_id      (int,    requis)      – ID de la catégorie (table job_categories)
     *   status           (string, optionnel)   – "draft" (défaut) | "published" | "archived"
     *   cover_image_url  (string, optionnel)   – URL de l'image de couverture externe
     *   cover_type       (string, optionnel)   – "external" (défaut si cover_image_url fourni)
     *   meta_title       (string, optionnel)   – Titre SEO (max 70 car.)
     *   meta_description (string, optionnel)   – Description SEO (max 160 car.)
     *   meta_keywords    (string ou array, optionnel) – "mot1, mot2" ou ["mot1","mot2"]
     *   is_sponsored     (bool,   optionnel)   – défaut false
     *   is_featured      (bool,   optionnel)   – défaut false
     *   published_at     (string, optionnel)   – Date ISO 8601, défaut = now() si status=published
     *   allow_duplicate  (bool,   optionnel)   – défaut false, voir réponse 409
     */
    public function store(Request $request, JobArticlePublisher $publisher): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title'            => ['required', 'string', 'max:255'],
            'slug'             => ['nullable', 'string', 'max:255', 'unique:job_articles,slug'],
            'content'          => ['required', 'string'],
            'excerpt'          => ['nullable', 'string', 'max:500'],
            'category_id'      => ['required', 'integer', 'exists:job_categories,id'],
            'status'           => ['nullable', 'string', 'in:draft,published,archived'],
            'cover_image_url'  => ['nullable', 'url', 'max:2048'],
            'cover_type'       => ['nullable', 'string', 'in:internal,external'],
            'meta_title'       => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'meta_keywords'    => ['nullable'],
            'meta_keywords.*'  => ['string', 'max:50'],
            'is_sponsored'     => ['nullable', 'boolean'],
            'is_featured'      => ['nullable', 'boolean'],
            'published_at'     => ['nullable', 'date'],
            'allow_duplicate'  => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Les données fournies sont invalides.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $article = $publisher->publish($validator->validated());
        } catch (DuplicateArticleException $e) {
            return response()->json([
                'success'          => false,
                'message'          => $e->getMessage(),
                'existing_article' => [
                    'id'   => $e->existingArticle->id,
                    'slug' => $e->existingArticle->slug,
                    'url'  => route('emplois.article', $e->existingArticle->slug),
                ],
                'hint' => 'Pour publier quand même, renvoyez la requête avec "allow_duplicate": true.',
            ], 409);
        } catch (UniqueConstraintViolationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ce slug existe déjà. Veuillez en choisir un autre.',
            ], 422);
        }

        return response()->json([
            'success' => true,
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
}
