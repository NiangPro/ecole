<?php

namespace Tests\Feature\Audit;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Audit SEO-06 : og:image et twitter:image toujours en URL absolue.
 */
class Seo06OgImageTest extends TestCase
{
    use RefreshDatabase;

    private function images(string $html): array
    {
        preg_match_all('/<meta (?:property="og:image"|name="twitter:image") content="([^"]*)"/', $html, $m);

        return $m[1];
    }

    public function test_image_de_categorie_interne_en_url_absolue(): void
    {
        Category::create(['name' => 'Offres', 'slug' => 'offres-emploi', 'is_active' => true, 'order' => 1,
            'image' => 'category-images/offres.jpg', 'image_type' => 'internal']);

        foreach (['/emplois/offres?category=offres-emploi', '/emplois/categorie/offres-emploi'] as $url) {
            $images = $this->images($this->get($url)->assertOk()->getContent());

            $this->assertCount(2, $images, "$url : og:image + twitter:image attendus");
            foreach ($images as $src) {
                $this->assertMatchesRegularExpression('#^https?://[^/]+/storage/category-images/offres\.jpg$#', $src, $url);
            }
        }
    }

    public function test_categorie_sans_image_a_quand_meme_une_image_de_partage(): void
    {
        Category::create(['name' => 'Concours', 'slug' => 'concours', 'is_active' => true, 'order' => 1]);

        $images = $this->images($this->get('/emplois/offres?category=concours')->assertOk()->getContent());

        $this->assertNotEmpty($images);
        foreach ($images as $src) {
            $this->assertMatchesRegularExpression('#^https?://#', $src);
        }
    }

    public function test_pages_principales_en_url_absolue(): void
    {
        foreach (['/', '/contact', '/emplois'] as $url) {
            foreach ($this->images($this->get($url)->assertOk()->getContent()) as $src) {
                $this->assertMatchesRegularExpression('#^https?://#', $src, $url);
            }
        }
    }
}
