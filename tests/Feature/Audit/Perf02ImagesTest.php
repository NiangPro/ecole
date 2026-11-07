<?php

namespace Tests\Feature\Audit;

use App\Models\SiteSetting;
use App\Support\ImageVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Audit PERF-02 : images servies à une taille proche de leur taille d'affichage.
 */
class Perf02ImagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_variante_redimensionnee_en_webp(): void
    {
        $dir = sys_get_temp_dir() . '/np-variants-' . uniqid();
        $variant = ImageVariant::make(public_path('images/logo.png'), 64, $dir);

        $this->assertStringEndsWith('.webp', $variant);
        [$w] = getimagesize($variant);
        $this->assertSame(64, $w);

        // Une image déjà assez petite est renvoyée telle quelle.
        $this->assertSame(public_path('images/sokhna-80.webp'), ImageVariant::make(public_path('images/sokhna-80.webp'), 440, $dir));
    }

    public function test_logo_en_64_et_128_px_avec_srcset(): void
    {
        $url64 = SiteSetting::logoVariantUrl(64);
        $url128 = SiteSetting::logoVariantUrl(128);
        $this->assertStringContainsString('logos/variants/', $url64);

        $this->get('/')->assertOk()
            ->assertSee('srcset="' . $url64 . ' 1x, ' . $url128 . ' 2x"', false)
            ->assertSee('images/sokhna-80.webp', false);
    }
}
