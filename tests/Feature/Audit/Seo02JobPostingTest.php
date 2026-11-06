<?php

namespace Tests\Feature\Audit;

use App\Models\Category;
use App\Models\JobArticle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Audit SEO-02 : pas de JobPosting sans employeur ni date limite réels.
 */
class Seo02JobPostingTest extends TestCase
{
    use RefreshDatabase;

    private function article(string $categorySlug, string $slug): JobArticle
    {
        $category = Category::create(['name' => $categorySlug, 'slug' => $categorySlug, 'is_active' => true, 'order' => 1]);

        return JobArticle::create([
            'category_id'  => $category->id,
            'title'        => 'Article ' . $slug,
            'slug'         => $slug,
            'excerpt'      => 'Résumé',
            'content'      => '<p>Contenu de l\'article</p>',
            'status'       => 'published',
            'published_at' => now()->subMonths(3),
        ]);
    }

    public function test_une_candidature_spontanee_n_a_pas_de_jobposting(): void
    {
        $this->article('candidature-spontanee', 'exemple-candidature-spontanee-2026');

        $this->get('/emplois/article/exemple-candidature-spontanee-2026')
            ->assertOk()
            ->assertDontSee('"JobPosting"', false)
            ->assertSee('NewsArticle', false);
    }

    public function test_une_offre_sans_employeur_ni_date_limite_n_a_pas_de_jobposting(): void
    {
        $this->article('offres-emploi', 'offre-sans-date-limite');

        $this->get('/emplois/article/offre-sans-date-limite')
            ->assertOk()
            ->assertDontSee('"JobPosting"', false)
            ->assertDontSee('Recruteur — via NiangProgrammeur');
    }
}
