<?php

namespace Tests\Feature\Audit;

use App\Models\AnalyticsEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * Audit FCT-01 : le traceur analytique fonctionne pour les visiteurs non connectés.
 */
class Fct01AnalyticsTest extends TestCase
{
    use RefreshDatabase;

    private array $evenement = [
        'event_type' => 'page_view',
        'event_name' => 'Page vue',
        'page_url'   => '/',
        'page_title' => 'Accueil',
        'metadata'   => ['scroll' => 50],
    ];

    public function test_un_invite_peut_envoyer_un_evenement(): void
    {
        $this->postJson('/api/analytics/track', $this->evenement, ['REMOTE_ADDR' => '203.0.113.7'])
            ->assertSuccessful();

        $event = AnalyticsEvent::first();
        $this->assertNotNull($event);
        $this->assertNotSame('203.0.113.7', $event->ip_address, 'IP stockée en clair');
        $this->assertStringNotContainsString('203.0.113', (string) $event->ip_address);
    }

    public function test_un_invite_peut_lire_les_tests_ab(): void
    {
        // %2F : le client de test supprime le « / » final d'une URL (« ?url=/ » devenait « ?url= »).
        $this->getJson('/api/analytics/ab-tests?url=%2F')
            ->assertSuccessful()
            ->assertExactJson([]);
    }

    public function test_les_donnees_entrantes_sont_validees(): void
    {
        $this->postJson('/api/analytics/track', ['event_type' => 'x'])->assertStatus(422);

        $this->postJson('/api/analytics/track', array_merge($this->evenement, [
            'metadata' => ['imbrique' => ['trop' => 'profond']],
        ]))->assertStatus(422);

        $this->postJson('/api/analytics/track', array_merge($this->evenement, [
            'metadata' => array_fill(0, 50, 'x'),
        ]))->assertStatus(422);
    }

    public function test_les_routes_analytics_sont_limitees_en_debit_et_sans_auth(): void
    {
        foreach (['api.analytics.track', 'api.analytics.ab-tests'] as $name) {
            $middleware = Route::getRoutes()->getByName($name)->gatherMiddleware();
            $this->assertContains('throttle:60,1', $middleware, $name);
            $this->assertNotContains('auth', $middleware, $name);
        }
    }
}
