<?php

namespace Tests\Feature\Audit;

use App\Models\Category;
use App\Models\JobArticle;
use App\Models\JobArticleRedirect;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Audit SEO-03 : doublons — redirection 301, sitemap canonique, commande en lecture seule.
 */
class Seo03DuplicatesTest extends TestCase
{
    use RefreshDatabase;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->category = Category::create(['name' => 'Offres', 'slug' => 'offres-emploi', 'is_active' => true, 'order' => 1]);
    }

    private function article(string $slug, string $title = 'TotalEnergies recrute un ingénieur travaux'): JobArticle
    {
        return JobArticle::create([
            'category_id'  => $this->category->id,
            'title'        => $title,
            'slug'         => $slug,
            'content'      => '<p>Contenu</p>',
            'status'       => 'published',
            'published_at' => now()->subDay(),
        ]);
    }

    public function test_un_slug_redirige_renvoie_301_vers_la_canonique(): void
    {
        $this->article('totalenergies-ingenieur-travaux');
        $this->article('totalenergies-ingenieur-travaux-2'); // encore publié en base

        JobArticleRedirect::create(['old_slug' => 'totalenergies-ingenieur-travaux-2', 'new_slug' => 'totalenergies-ingenieur-travaux']);

        $this->get('/emplois/article/totalenergies-ingenieur-travaux-2')
            ->assertStatus(301)
            ->assertRedirect(route('emplois.article', 'totalenergies-ingenieur-travaux'));

        $this->get('/emplois/article/totalenergies-ingenieur-travaux')->assertOk();
    }

    public function test_les_redirections_historiques_sonatel_restent_en_301(): void
    {
        $this->get('/emplois/article/sonatel-recrute-candidature-spontanee-2026')
            ->assertStatus(301)
            ->assertRedirect('/emplois/article/candidature-spontanee-sonatel-2026-postulez-au-senegal');
    }

    public function test_le_sitemap_ne_contient_pas_d_url_non_canonique(): void
    {
        $this->article('totalenergies-ingenieur-travaux');
        $this->article('totalenergies-ingenieur-travaux-2');
        $this->article('sonatel-recrute-candidature-spontanee-2026', 'Sonatel');
        JobArticleRedirect::create(['old_slug' => 'totalenergies-ingenieur-travaux-2', 'new_slug' => 'totalenergies-ingenieur-travaux']);

        $xml = $this->get('/sitemap-articles.xml')->assertOk()->getContent();

        $this->assertStringContainsString('/emplois/article/totalenergies-ingenieur-travaux<', $xml);
        $this->assertStringNotContainsString('/emplois/article/totalenergies-ingenieur-travaux-2<', $xml);
        $this->assertStringNotContainsString('sonatel-recrute-candidature-spontanee-2026', $xml);
    }

    public function test_la_commande_doublons_liste_sans_rien_modifier(): void
    {
        $a = $this->article('unicef-chargee-de-communication');
        $b = $this->article('unicef-chargee-de-communication-2', 'UNICEF chargée de communication');
        $this->article('autre-offre', 'Autre offre');
        $avant = JobArticle::orderBy('id')->get()->toArray();

        $this->artisan('articles:doublons')
            ->expectsOutputToContain('unicef-chargee-de-communication-2')
            ->assertSuccessful();

        $this->assertSame($avant, JobArticle::orderBy('id')->get()->toArray());
        $this->assertSame(0, JobArticleRedirect::count());
    }
}
