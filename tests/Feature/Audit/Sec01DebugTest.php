<?php

namespace Tests\Feature\Audit;

use App\Providers\AppServiceProvider;
use Tests\TestCase;

/**
 * Audit SEC-01 : aucune page ni réponse JSON de debug en production.
 */
class Sec01DebugTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['app.debug' => false]);
    }

    public function test_get_sur_une_route_post_renvoie_une_405_sans_trace(): void
    {
        $response = $this->get('/newsletter/subscribe');

        $response->assertStatus(405);
        $response->assertSee('Méthode non autorisée');
        $response->assertDontSee('Stack trace', false);
        $response->assertDontSee('vendor', false);
        $response->assertDontSee(base_path(), false);
    }

    public function test_la_reponse_json_d_erreur_ne_contient_ni_exception_ni_trace(): void
    {
        $response = $this->getJson('/newsletter/subscribe');

        $response->assertStatus(405);
        $response->assertJsonMissingPath('exception');
        $response->assertJsonMissingPath('file');
        $response->assertJsonMissingPath('line');
        $response->assertJsonMissingPath('trace');
    }

    public function test_la_page_419_est_en_francais(): void
    {
        $html = view('errors.419')->render();

        $this->assertStringContainsString('La page a expiré, rechargez-la et réessayez', $html);
    }

    public function test_le_debug_est_force_a_false_en_production(): void
    {
        $this->app['env'] = 'production';
        config(['app.debug' => true]);

        (new AppServiceProvider($this->app))->register();

        $this->assertFalse(config('app.debug'));
    }
}
