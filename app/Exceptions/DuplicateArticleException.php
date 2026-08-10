<?php

namespace App\Exceptions;

use App\Models\JobArticle;
use RuntimeException;

/**
 * Levée par JobArticlePublisher::publish() quand un article de titre/sujet très
 * proche existe déjà dans la même catégorie. Ajoutée suite à la découverte d'un
 * cluster de 9 articles quasi identiques ("candidature spontanée chez Sonatel")
 * publiés entre le 16/06/2026 et le 21/07/2026 par le pipeline de publication
 * automatisée (MCP), faute de vérification anti-doublon avant création.
 */
class DuplicateArticleException extends RuntimeException
{
    public function __construct(
        public readonly JobArticle $existingArticle,
        public readonly float $similarity
    ) {
        parent::__construct(sprintf(
            'Un article très similaire existe déjà : "%s" (similarité %d%%).',
            $existingArticle->title,
            round($similarity * 100)
        ));
    }
}
