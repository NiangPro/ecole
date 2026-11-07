<?php

namespace Tests\Feature\Audit;

use App\Models\Category;
use App\Models\JobArticle;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * Audit SEO-02 : JobPosting seulement pour une vraie offre avec employeur et date limite réels,
 * bannière « Offre clôturée » et sortie des listes après la date limite.
 */
class Seo02JobPostingTest extends TestCase
{
    use RefreshDatabase;

    private function article(string $categorySlug, string $slug, array $extra = []): JobArticle
    {
        $category = Category::firstOrCreate(['slug' => $categorySlug], ['name' => $categorySlug, 'is_active' => true, 'order' => 1]);

        return JobArticle::create(array_merge([
            'category_id'  => $category->id,
            'title'        => 'Article ' . $slug,
            'slug'         => $slug,
            'excerpt'      => 'Résumé',
            'content'      => '<p>Contenu de l\'article</p>',
            'status'       => 'published',
            'published_at' => now()->subDays(3),
        ], $extra));
    }

    /** Le bloc JSON-LD JobPosting de la page, décodé, ou null. */
    private function jobPosting(string $html): ?array
    {
        preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $m);
        foreach ($m[1] as $json) {
            $data = json_decode($json, true);
            if (($data['@type'] ?? null) === 'JobPosting') {
                return $data;
            }
        }

        return null;
    }

    public function test_offre_valide_a_un_jobposting_avec_le_vrai_employeur(): void
    {
        $this->article('offres-emploi', 'comptable-sonatel', [
            'employer_name' => 'Sonatel',
            'application_deadline' => now()->addDays(10)->toDateString(),
            'location' => 'Dakar',
        ]);

        $html = $this->get('/emplois/article/comptable-sonatel')->assertOk()
            ->assertDontSee('class="art-closed"', false)
            ->assertSee('class="art-offer-facts"', false)
            ->getContent();

        $jp = $this->jobPosting($html);
        $this->assertNotNull($jp, 'JobPosting attendu');
        $this->assertSame('https://schema.org', $jp['@context']);
        $this->assertSame('Sonatel', $jp['hiringOrganization']['name']);
        $this->assertNotSame('Recruteur — via NiangProgrammeur', $jp['hiringOrganization']['name']);
        $this->assertStringStartsWith(now()->addDays(10)->toDateString(), $jp['validThrough']);
        $this->assertSame('Dakar', $jp['jobLocation']['address']['addressLocality']);
        $this->assertSame('SN', $jp['jobLocation']['address']['addressCountry']);
    }

    public function test_offre_expiree_sans_jobposting_avec_banniere(): void
    {
        $this->article('offres-emploi', 'offre-expiree', [
            'employer_name' => 'PNUD',
            'application_deadline' => now()->subDay()->toDateString(),
        ]);

        $html = $this->get('/emplois/article/offre-expiree')->assertOk()
            ->assertSee('class="art-closed"', false)->assertSee('Offre clôturée')
            ->getContent();
        $this->assertNull($this->jobPosting($html));
    }

    public function test_le_jour_de_la_date_limite_l_offre_est_encore_ouverte(): void
    {
        $this->article('offres-emploi', 'offre-dernier-jour', [
            'employer_name' => 'PNUD',
            'application_deadline' => now()->toDateString(),
        ]);

        $html = $this->get('/emplois/article/offre-dernier-jour')->assertOk()->assertDontSee('class="art-closed"', false)->getContent();
        $this->assertNotNull($this->jobPosting($html));
    }

    public function test_une_candidature_spontanee_n_a_pas_de_jobposting(): void
    {
        $this->article('candidature-spontanee', 'exemple-candidature-spontanee-2026', [
            'employer_name' => 'Sonatel',
            'application_deadline' => now()->addMonth()->toDateString(),
        ]);

        $html = $this->get('/emplois/article/exemple-candidature-spontanee-2026')
            ->assertOk()
            ->assertSee('NewsArticle', false)
            ->getContent();
        $this->assertNull($this->jobPosting($html));
    }

    public function test_une_offre_sans_employeur_ni_date_limite_n_a_pas_de_jobposting(): void
    {
        $this->article('offres-emploi', 'offre-sans-date-limite');

        $html = $this->get('/emplois/article/offre-sans-date-limite')
            ->assertOk()
            ->assertDontSee('Recruteur — via NiangProgrammeur')
            ->assertDontSee('class="art-closed"', false)
            ->getContent();
        $this->assertNull($this->jobPosting($html));
    }

    public function test_offre_expiree_hors_des_listes_mais_consultable(): void
    {
        $this->article('offres-emploi', 'offre-ouverte', ['application_deadline' => now()->addWeek()->toDateString(), 'is_featured' => true]);
        $this->article('offres-emploi', 'offre-close', ['application_deadline' => now()->subWeek()->toDateString(), 'is_featured' => true]);
        $this->article('offres-emploi', 'offre-sans-date', ['is_featured' => true]);
        Cache::flush();

        $this->assertEqualsCanonicalizing(
            ['offre-ouverte', 'offre-sans-date'],
            JobArticle::published()->notExpired()->pluck('slug')->all()
        );

        foreach (['/emplois/articles-recents', '/articles/vedettes', '/'] as $url) {
            $this->get($url)->assertOk()
                ->assertSee('/emplois/article/offre-ouverte', false)
                ->assertDontSee('/emplois/article/offre-close"', false);
        }

        $this->get('/emplois/article/offre-close')->assertOk();
    }

    public function test_l_admin_enregistre_employeur_date_limite_et_lieu(): void
    {
        $admin = User::forceCreate([
            'name' => 'Admin', 'email' => 'admin-seo02@example.com', 'password' => bcrypt('x'),
            'role' => 'admin', 'is_active' => true,
        ]);
        $article = $this->article('offres-emploi', 'offre-a-completer');

        $this->actingAs($admin)->put(route('admin.jobs.articles.update', $article->id), [
            'category_id' => $article->category_id,
            'title' => $article->title,
            'content' => '<p>Contenu de l\'offre, assez long pour être enregistré.</p>',
            'cover_type' => 'external',
            'status' => 'published',
            'employer_name' => 'Orange Sénégal',
            'application_deadline' => '2026-12-31',
            'location' => 'Thiès',
        ])->assertSessionHasNoErrors();

        $article->refresh();
        $this->assertSame('Orange Sénégal', $article->employer_name);
        $this->assertSame('2026-12-31', $article->application_deadline->toDateString());
        $this->assertSame('Thiès', $article->location);

        $this->actingAs($admin)->get(route('admin.jobs.articles.edit', $article->id))->assertOk()
            ->assertSee('name="employer_name"', false)
            ->assertSee('value="2026-12-31"', false);
    }

    public function test_la_creation_admin_enregistre_l_offre(): void
    {
        $admin = User::forceCreate([
            'name' => 'Admin', 'email' => 'admin-seo02b@example.com', 'password' => bcrypt('x'),
            'role' => 'admin', 'is_active' => true,
        ]);
        $category = Category::firstOrCreate(['slug' => 'offres-emploi'], ['name' => 'Offres', 'is_active' => true, 'order' => 1]);

        $this->actingAs($admin)->get(route('admin.jobs.articles.create'))->assertOk()
            ->assertSee('name="employer_name"', false)
            ->assertSee('name="application_deadline"', false)
            ->assertSee('name="location"', false);

        $this->actingAs($admin)->post(route('admin.jobs.articles.store'), [
            'category_id' => $category->id,
            'title' => 'Recrutement développeur Sonatel',
            'content' => '<p>Sonatel recrute un développeur web à Dakar.</p>',
            'cover_type' => 'external',
            'status' => 'draft',
            'employer_name' => 'Sonatel',
            'application_deadline' => '2026-12-15',
            'location' => 'Dakar',
        ])->assertSessionHasNoErrors();

        $article = JobArticle::where('title', 'Recrutement développeur Sonatel')->firstOrFail();
        $this->assertSame('Sonatel', $article->employer_name);
        $this->assertSame('2026-12-15', $article->application_deadline->toDateString());
        $this->assertSame('Dakar', $article->location);
    }
}
