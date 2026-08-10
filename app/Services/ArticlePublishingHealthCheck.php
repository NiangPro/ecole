<?php

namespace App\Services;

use App\Models\Category;
use App\Models\JobArticle;
use Throwable;

/**
 * Vérifie que la publication automatisée d'articles (pipeline MCP /api/mcp,
 * /api/articles) peut réellement écrire dans job_articles avant de démarrer une
 * session de publication.
 *
 * Root cause de l'incident du 27/07/2026 : une écriture impossible dans
 * job_articles (schéma incohérent / migration non exécutée en prod) n'a été
 * découverte qu'en plein milieu d'une session de publication sur la catégorie
 * "Bourses d'études", ce qui a fait échouer 3 articles (erreur 500) sans
 * possibilité de le détecter avant coup. Cette vérification fait une vraie
 * écriture (insertion + suppression immédiate) plutôt qu'un simple ping, pour
 * détecter aussi une colonne manquante ou une contrainte cassée.
 *
 * Utilisée par GET /api/mcp/health et `php artisan articles:health-check`.
 */
class ArticlePublishingHealthCheck
{
    /**
     * @return array{ok: bool, error?: string}
     */
    public function run(): array
    {
        try {
            $categoryId = Category::where('is_active', true)->value('id');

            if (!$categoryId) {
                return ['ok' => false, 'error' => "Aucune catégorie active trouvée (table job_categories)."];
            }

            $probe = JobArticle::create([
                'category_id' => $categoryId,
                'title'       => '__health_check__',
                'slug'        => '__health_check__' . uniqid('', true),
                'content'     => 'health check',
                'status'      => 'draft',
            ]);

            $probe->delete();

            return ['ok' => true];
        } catch (Throwable $e) {
            return [
                'ok'    => false,
                'error' => 'Écriture impossible dans job_articles : ' . $e->getMessage(),
            ];
        }
    }
}
