<?php

namespace Tests\Feature\Audit;

use App\Http\Middleware\PageCache;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Audit CACHE-01 : le HTML mis en cache ne doit jamais passer d'un visiteur à l'autre
 * (jeton CSRF, langue, contenu d'un utilisateur connecté).
 */
class Cache01PageCacheTest extends TestCase
{
    use RefreshDatabase;

    private function sessionCookieName(): string
    {
        return config('session.cookie');
    }

    /** Un « nouveau visiteur » : aucune donnée de cookie, aucun utilisateur connecté. */
    private function visiteurNeuf(string $url): TestResponse
    {
        $this->flushHeaders();
        $this->defaultCookies = [];
        $this->unencryptedCookies = [];
        Auth::forgetGuards();
        // Dans un test, l'application (et donc l'objet session et la locale) est réutilisée
        // d'une requête à l'autre ; en production, chaque requête repart de zéro.
        $this->app['session']->forgetDrivers();
        $this->app->forgetInstance('session.store');
        $this->app->setLocale(config('app.fallback_locale', 'fr'));

        return $this->get($url);
    }

    private function jeton(TestResponse $response): ?string
    {
        preg_match('/<meta name="csrf-token" content="([^"]+)"/', $response->getContent(), $m);

        return $m[1] ?? null;
    }

    public function test_deux_visiteurs_sans_cookie_recoivent_deux_jetons_differents(): void
    {
        foreach (['/', '/login'] as $url) {
            $a = $this->visiteurNeuf($url)->assertOk();
            $b = $this->visiteurNeuf($url)->assertOk();

            $this->assertNotNull($this->jeton($a), "jeton absent sur $url");
            $this->assertNotSame($this->jeton($a), $this->jeton($b), "même jeton pour deux visiteurs sur $url");
            $this->assertStringNotContainsString('__CSRF__', $b->getContent());
        }
    }

    public function test_la_page_servie_depuis_le_cache_porte_le_jeton_du_visiteur(): void
    {
        $this->visiteurNeuf('/')->assertHeader('X-Page-Cache', 'MISS');

        $hit = $this->visiteurNeuf('/')->assertHeader('X-Page-Cache', 'HIT');
        $this->assertNotNull($this->jeton($hit));
        $this->assertStringNotContainsString('__CSRF__', $hit->getContent());
    }

    public function test_un_nouveau_visiteur_obtient_422_et_non_419_sur_la_newsletter(): void
    {
        $this->visiteurNeuf('/'); // remplit le cache avec la session d'un autre visiteur

        $page = $this->visiteurNeuf('/')->assertHeader('X-Page-Cache', 'HIT');
        $session = $page->getCookie($this->sessionCookieName())->getValue();
        $xsrf = $page->getCookie('XSRF-TOKEN', false)->getValue(); // valeur brute, comme le lit le JS

        // Réactive la vérification CSRF, que Laravel coupe en environnement « testing ».
        $this->app['env'] = 'local';

        // Avec le jeton de la page (meta / champ caché)
        $this->withCookie($this->sessionCookieName(), $session)
            ->postJson('/newsletter/subscribe', ['email' => ''], ['X-CSRF-TOKEN' => $this->jeton($page)])
            ->assertStatus(422);

        // Avec le cookie XSRF-TOKEN, comme npCsrfHeader() côté JS
        $this->withCookie($this->sessionCookieName(), $session)
            ->postJson('/newsletter/subscribe', ['email' => ''], ['X-XSRF-TOKEN' => urldecode($xsrf)])
            ->assertStatus(422);
    }

    public function test_la_langue_d_un_visiteur_ne_contamine_pas_les_autres(): void
    {
        // Visiteur A passe en anglais puis visite l'accueil et /contact avec sa session.
        $switch = $this->visiteurNeuf('/language/en?redirect=/');
        $sessionA = $switch->getCookie($this->sessionCookieName())->getValue();
        foreach (['/', '/contact'] as $url) {
            $this->withCookie($this->sessionCookieName(), $sessionA)->get($url)
                ->assertSee('<html lang="en">', false);
        }

        // Visiteur B, sans cookie : français.
        foreach (['/', '/contact'] as $url) {
            $this->visiteurNeuf($url)->assertSee('<html lang="fr">', false);
        }
    }

    public function test_une_page_vue_par_un_utilisateur_connecte_n_est_jamais_servie_a_un_invite(): void
    {
        $user = User::factory()->create(['name' => 'Zorglub Connecte']);

        $this->actingAs($user)->get('/')->assertHeader('X-Page-Cache', 'BYPASS');

        $invite = $this->visiteurNeuf('/');
        $invite->assertDontSee('Zorglub Connecte');
        $invite->assertHeader('X-Page-Cache', 'MISS');
    }

    public function test_un_visiteur_avec_session_n_utilise_pas_le_cache(): void
    {
        $this->visiteurNeuf('/');

        $this->withCookie($this->sessionCookieName(), 'session-existante')
            ->get('/')
            ->assertHeader('X-Page-Cache', 'BYPASS');
    }

    public function test_les_pages_html_ne_sont_jamais_publiques(): void
    {
        foreach (['/', '/contact', '/login'] as $url) {
            $cacheControl = $this->visiteurNeuf($url)->headers->get('Cache-Control');
            $this->assertStringContainsString('private', $cacheControl, $url);
            $this->assertStringNotContainsString('public', $cacheControl, $url);
        }
    }

    public function test_les_urls_exclues_ne_sont_jamais_mises_en_cache(): void
    {
        foreach (['/login', '/register', '/contact', '/donations', '/forum', '/search?q=x'] as $url) {
            $request = \Illuminate\Http\Request::create($url);
            $this->assertFalse(PageCache::isCacheableRequest($request), $url);
        }
        $this->assertTrue(PageCache::isCacheableRequest(\Illuminate\Http\Request::create('/emplois')));
    }

    public function test_le_vidage_invalide_les_pages_en_cache(): void
    {
        $this->visiteurNeuf('/')->assertHeader('X-Page-Cache', 'MISS');
        $this->visiteurNeuf('/')->assertHeader('X-Page-Cache', 'HIT');

        $this->artisan('page-cache:clear')->assertSuccessful();

        $this->visiteurNeuf('/')->assertHeader('X-Page-Cache', 'MISS');
    }

    public function test_le_vidage_invalide_aussi_le_cache_des_formations(): void
    {
        $this->visiteurNeuf('/formations/html5')->assertOk();
        $avant = \App\Http\Middleware\PageCache::version();
        $this->assertTrue(\Illuminate\Support\Facades\Cache::has("formation_html_v2_p{$avant}_html5_fr"));

        $this->artisan('page-cache:clear')->assertSuccessful();

        $apres = \App\Http\Middleware\PageCache::version();
        $this->assertNotSame($avant, $apres);
        $this->assertFalse(\Illuminate\Support\Facades\Cache::has("formation_html_v2_p{$apres}_html5_fr"));
    }
}
