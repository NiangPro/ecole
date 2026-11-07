<?php

namespace Tests\Feature\Audit;

use App\Models\Category;
use App\Models\JobArticle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Audit SEO-07 : un seul FAQPage par article (le JSON-LD de la vue), sans microdata
 * Question/Answer en double dans le bloc FAQ visible.
 */
class Seo07FaqPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_seul_bloc_faqpage_par_article(): void
    {
        $category = Category::create(['name' => 'Offres', 'slug' => 'offres-emploi', 'is_active' => true, 'order' => 1]);
        JobArticle::create([
            'category_id'  => $category->id,
            'title'        => 'Recrutement développeur web à Dakar',
            'slug'         => 'recrutement-developpeur-web-dakar',
            'excerpt'      => 'Une entreprise recrute un développeur web.',
            'content'      => str_repeat('<p>Une entreprise de Dakar recrute un développeur web. Profil : diplôme, expérience, dossier de candidature à envoyer avant la date limite.</p>', 6),
            'status'       => 'published',
            'published_at' => now()->subDay(),
        ]);

        $html = $this->get('/emplois/article/recrutement-developpeur-web-dakar')->assertOk()->getContent();

        $this->assertStringContainsString('np-faq-section', $html, 'Le bloc FAQ visible doit être présent pour que le test ait un sens');
        $this->assertSame(1, substr_count($html, '"FAQPage"'));
        $this->assertStringNotContainsString('itemtype="https://schema.org/Question"', $html);
        $this->assertStringNotContainsString('itemtype="https://schema.org/FAQPage"', $html);
    }
}
