<?php

namespace Tests\Feature\Audit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Audit UX-01 (boutons flottants / hero mobile) et UX-02 (annotations AdSense du hero).
 */
class Ux0102HomeHeroTest extends TestCase
{
    use RefreshDatabase;

    public function test_hero_exclu_des_annotations_adsense(): void
    {
        $this->get('/')->assertOk()
            ->assertSee('<section class="hp-hero google-anno-skip google-anno-sc">', false)
            ->assertSee('class="navbar-modern google-anno-skip"', false);
    }

    public function test_observateur_des_boutons_du_hero_present(): void
    {
        $this->get('/')->assertOk()->assertSee("classList.toggle('np-hero-ctas-visible'", false);

        $css = file_get_contents(resource_path('css/features/homepage.css'));
        $this->assertStringContainsString('body.np-hero-ctas-visible .whatsapp-widget', $css);
    }
}
