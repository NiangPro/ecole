<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Redirection 301 d'un ancien slug d'article vers l'article conservé (audit SEO-03).
 *
 * Fusionner un doublon : JobArticleRedirect::create(['old_slug' => …, 'new_slug' => …]),
 * puis archiver l'ancien article (status = archived). Tant que la ligne existe, l'ancienne
 * URL renvoie une 301 (même si l'article est encore publié) et sort du sitemap.
 */
class JobArticleRedirect extends Model
{
    protected $fillable = ['old_slug', 'new_slug'];

    /**
     * Redirections historiques (cluster « candidature spontanée Sonatel », juillet 2026),
     * auparavant codées en dur dans routes/web.php.
     */
    public const LEGACY = [
        'comment-postuler-a-sonatel-orange-senegal-en-2026-1'      => 'candidature-spontanee-sonatel-2026-postulez-au-senegal',
        'comment-postuler-a-la-sonatel-en-2026'                    => 'candidature-spontanee-sonatel-2026-postulez-au-senegal',
        'comment-postuler-a-sonatel-orange-senegal-en-2026'        => 'candidature-spontanee-sonatel-2026-postulez-au-senegal',
        'sonatel-recrute-candidature-spontanee-2026'               => 'candidature-spontanee-sonatel-2026-postulez-au-senegal',
        'deposer-une-candidature-spontanee-chez-sonatel-2026'      => 'candidature-spontanee-sonatel-2026-postulez-au-senegal',
        'candidature-spontanee-sonatel-orange-senegal-2026-2'      => 'candidature-spontanee-sonatel-2026-postulez-au-senegal',
        'candidature-spontanee-sonatel-comment-postuler-en-2026'   => 'candidature-spontanee-sonatel-2026-postulez-au-senegal',
        'comment-postuler-a-la-sonatel-orange-senegal-2026'        => 'candidature-spontanee-sonatel-2026-postulez-au-senegal',
    ];

    private const CACHE_KEY = 'job_article_redirects_map';

    protected static function booted(): void
    {
        $forget = function () {
            Cache::forget(self::CACHE_KEY);
            Cache::forget('sitemap_articles_' . md5('https://www.niangprogrammeur.com'));
            \App\Http\Middleware\PageCache::flush();
        };
        static::saved($forget);
        static::deleted($forget);
    }

    /** @return array<string, string> ancien slug => nouveau slug */
    public static function map(): array
    {
        $fromDb = Cache::remember(self::CACHE_KEY, 3600, function () {
            try {
                return static::query()->pluck('new_slug', 'old_slug')->all();
            } catch (\Throwable $e) {
                return []; // table absente (migration pas encore lancée)
            }
        });

        return $fromDb + self::LEGACY;
    }

    public static function targetFor(string $slug): ?string
    {
        $target = self::map()[$slug] ?? null;

        return $target !== null && $target !== $slug ? $target : null;
    }

    /** @return list<string> */
    public static function redirectedSlugs(): array
    {
        return array_keys(self::map());
    }
}
