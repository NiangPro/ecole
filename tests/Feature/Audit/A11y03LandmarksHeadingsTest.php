<?php

namespace Tests\Feature\Audit;

use App\Models\Category;
use App\Models\JobArticle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Audit A11Y-03 : un seul <main id="main-content">, pas de saut de niveau de titre,
 * menu mobile dans un repère <nav aria-label="Menu mobile">.
 */
class A11y03LandmarksHeadingsTest extends TestCase
{
    use RefreshDatabase;

    /** Sauts de niveau (h1 → h3…) dans le HTML rendu, scripts et styles exclus. */
    private function sauts(string $html): array
    {
        $html = preg_replace('#<(script|style)\b.*?</\1>#s', '', $html);
        preg_match_all('#<h([1-6])\b[^>]*>(.*?)</h\1>#s', $html, $m, PREG_SET_ORDER);
        $prev = 0;
        $sauts = [];
        foreach ($m as [$all, $lvl, $txt]) {
            if ($prev && $lvl > $prev + 1) {
                $sauts[] = "h$prev → h$lvl : " . trim(strip_tags($txt));
            }
            $prev = (int) $lvl;
        }

        return $sauts;
    }

    public function test_un_seul_main_par_page(): void
    {
        foreach (['/register', '/formations/html5', '/formations/php', '/', '/documents'] as $url) {
            $html = $this->get($url)->assertOk()->getContent();
            $this->assertSame(1, preg_match_all('/<main\b/', $html), "Plusieurs <main> sur $url");
            $this->assertStringContainsString('<main id="main-content"', $html);
        }
    }

    public function test_pas_de_saut_de_niveau_de_titre(): void
    {
        $category = Category::create(['name' => 'Offres', 'slug' => 'offres-emploi', 'is_active' => true, 'order' => 1]);
        JobArticle::create([
            'category_id' => $category->id, 'title' => 'Recrutement comptable à Dakar', 'slug' => 'recrutement-comptable-dakar',
            'excerpt' => 'Résumé', 'content' => '<p>Contenu</p>', 'status' => 'published', 'published_at' => now()->subDay(),
        ]);

        foreach (['/', '/emplois/offres', '/emplois/article/recrutement-comptable-dakar', '/contact', '/login', '/register',
                  '/donations', '/formations', '/formations/html5', '/faq', '/emplois/candidature-spontanee', '/emplois/bourses', '/emplois/concours', '/emplois/tous-les-articles', '/articles/vedettes', '/documents'] as $url) {
            $this->assertSame([], $this->sauts($this->get($url)->assertOk()->getContent()), "Saut de titre sur $url");
        }
    }

    public function test_menu_mobile_dans_un_repere_nav(): void
    {
        $this->get('/')->assertOk()
            ->assertSee('<nav class="mobile-menu" id="mobileMenu" aria-label="Menu mobile"', false);
    }
}
