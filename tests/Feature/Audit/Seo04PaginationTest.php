<?php

namespace Tests\Feature\Audit;

use App\Models\Category;
use App\Models\JobArticle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Audit SEO-04 : canonical des pages paginées, 404 hors limite et catégorie inconnue.
 */
class Seo04PaginationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $category = Category::create(['name' => 'Offres', 'slug' => 'offres-emploi', 'is_active' => true, 'order' => 1]);
        for ($i = 1; $i <= 25; $i++) { // 20 par page → 2 pages
            JobArticle::create([
                'category_id' => $category->id, 'title' => "Offre $i", 'slug' => "offre-$i",
                'content' => '<p>x</p>', 'status' => 'published', 'published_at' => now()->subMinutes($i),
            ]);
        }
    }

    private function canonical(string $html): ?string
    {
        preg_match('/<link rel="canonical" href="([^"]+)"/', $html, $m);

        return isset($m[1]) ? html_entity_decode($m[1]) : null;
    }

    public function test_la_page_2_a_sa_propre_canonical(): void
    {
        $html = $this->get('/emplois/offres?category=offres-emploi&page=2&utm_x=1')->assertOk()->getContent();

        $this->assertSame(route('emplois.offres', ['category' => 'offres-emploi']) . '&page=2', $this->canonical($html));
    }

    public function test_la_page_1_n_a_pas_de_parametre_page(): void
    {
        $html = $this->get('/emplois/offres?category=offres-emploi')->assertOk()->getContent();

        $this->assertSame(route('emplois.offres', ['category' => 'offres-emploi']), $this->canonical($html));
    }

    public function test_page_au_dela_de_la_derniere_renvoie_404(): void
    {
        $this->get('/emplois/offres?category=offres-emploi&page=9999')->assertNotFound();
    }

    public function test_categorie_inconnue_renvoie_404(): void
    {
        $this->get('/emplois/offres?category=categorie-inexistante')->assertNotFound();
    }
}
