<?php

namespace Tests\Feature\Audit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Audit SEO-05 : pages de recherche non indexables, sans canonical.
 */
class Seo05SearchNoindexTest extends TestCase
{
    use RefreshDatabase;

    public function test_recherche_et_recherche_forum_en_noindex_follow(): void
    {
        foreach (['/search?q=zzqxwvaudit', '/forum/search?q=laravel'] as $url) {
            $response = $this->get($url)->assertOk();

            $response->assertHeader('X-Robots-Tag', 'noindex, follow');
            $response->assertSee('<meta name="robots" content="noindex, follow">', false);
            $response->assertDontSee('rel="canonical"', false);
            $this->assertSame(1, substr_count($response->getContent(), 'name="robots"'), $url);
        }
    }

    public function test_les_autres_pages_restent_indexables(): void
    {
        $response = $this->get('/contact')->assertOk();

        $response->assertSee('<meta name="robots" content="index, follow', false);
        $response->assertSee('rel="canonical"', false);
        $this->assertNull($response->headers->get('X-Robots-Tag'));
    }
}
