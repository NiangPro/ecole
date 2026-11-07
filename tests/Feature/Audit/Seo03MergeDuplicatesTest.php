<?php

namespace Tests\Feature\Audit;

use App\Models\Category;
use App\Models\JobArticle;
use App\Models\JobArticleRedirect;
use App\Support\DuplicateArticleFinder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Audit SEO-03 : articles:fusionner-doublons (simulation par défaut, archivage + 301 avec --appliquer).
 */
class Seo03MergeDuplicatesTest extends TestCase
{
    use RefreshDatabase;

    private function article(string $slug, string $title, int $views, string $status = 'published'): JobArticle
    {
        $category = Category::firstOrCreate(['slug' => 'offres-emploi'], ['name' => 'Offres', 'is_active' => true, 'order' => 1]);

        return JobArticle::create([
            'category_id' => $category->id, 'title' => $title, 'slug' => $slug, 'excerpt' => 'r',
            'content' => '<p>c</p>', 'status' => $status, 'published_at' => now()->subWeek(), 'views' => $views,
        ]);
    }

    public function test_regle_de_choix_de_l_article_garde(): void
    {
        $base = $this->article('recrutement-comptable', 'Recrutement comptable', 10);
        $suffixe = $this->article('recrutement-comptable-1', 'Recrutement comptable', 500);
        $brouillon = $this->article('recrutement-comptable-dakar', 'Recrutement comptable', 900, 'draft');

        $groups = DuplicateArticleFinder::groups();
        $this->assertCount(1, $groups);
        // Publié d'abord, puis URL sans suffixe, même si le doublon -1 est plus vu.
        $this->assertSame($base->id, DuplicateArticleFinder::keeper($groups[0]['articles'])->id);
    }

    public function test_la_simulation_ne_modifie_rien(): void
    {
        $this->article('offre-a', 'Offre A', 10);
        $this->article('offre-a-1', 'Offre A', 5);

        $this->artisan('articles:fusionner-doublons')->assertSuccessful();

        $this->assertSame(0, JobArticleRedirect::count());
        $this->assertSame(2, JobArticle::where('status', 'published')->count());
    }

    public function test_appliquer_archive_et_redirige_en_301(): void
    {
        $garde = $this->article('offre-b', 'Offre B', 10);
        $doublon = $this->article('offre-b-1', 'Offre B', 5);
        $rapport = sys_get_temp_dir() . '/fusion-' . uniqid() . '.md';

        $this->artisan('articles:fusionner-doublons', ['--appliquer' => true, '--force' => true, '--rapport' => $rapport])
            ->assertSuccessful();

        $this->assertSame('archived', $doublon->fresh()->status);
        $this->assertSame('published', $garde->fresh()->status);
        $this->assertDatabaseHas('job_article_redirects', ['old_slug' => 'offre-b-1', 'new_slug' => 'offre-b']);
        $this->assertNotNull(JobArticle::find($doublon->id), 'jamais supprimé');

        $this->get('/emplois/article/offre-b-1')->assertStatus(301)->assertRedirect(route('emplois.article', 'offre-b'));
        $this->get('/emplois/article/offre-b')->assertOk();

        $this->assertStringContainsString('| ' . $doublon->id . ' | offre-b-1 | published |', file_get_contents($rapport));
        @unlink($rapport);

        // Relancer ne refait rien (doublon déjà redirigé).
        $this->artisan('articles:fusionner-doublons', ['--appliquer' => true, '--force' => true])
            ->expectsOutput('Aucun doublon à fusionner.')->assertSuccessful();
    }

    public function test_appliquer_sans_force_demande_confirmation(): void
    {
        $this->article('offre-c', 'Offre C', 10);
        $this->article('offre-c-1', 'Offre C', 5);

        $this->artisan('articles:fusionner-doublons', ['--appliquer' => true])
            ->expectsConfirmation('Archiver ces articles et créer les redirections 301 ?', 'no')
            ->assertFailed();

        $this->assertSame(0, JobArticleRedirect::count());
    }
}
