<?php

namespace Tests\Feature\Audit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Audit SEO-01 : sélecteur de langue (redirection limitée au site, pas de hreflang faux,
 * lien non suivi par les robots).
 */
class Seo01LanguageTest extends TestCase
{
    use RefreshDatabase;

    public function test_redirection_vers_une_url_relative_du_site_avec_sa_query_string(): void
    {
        $this->get('/language/en?redirect=' . urlencode('/emplois/offres?category=concours&lang=fr'))
            ->assertRedirect('/emplois/offres?category=concours');
    }

    public function test_redirection_vers_une_url_absolue_du_meme_site(): void
    {
        $this->get('/language/en?redirect=' . urlencode(url('/contact')))
            ->assertRedirect('/contact');
    }

    public function test_pas_de_redirection_ouverte(): void
    {
        foreach (['https://evil.example/phish', '//evil.example/x', '/\\evil.example', 'javascript:alert(1)'] as $redirect) {
            $location = $this->get('/language/fr?redirect=' . urlencode($redirect))->headers->get('Location');
            $this->assertStringNotContainsString('evil.example', (string) $location, $redirect);
            $this->assertSame(url('/'), $location, $redirect);
        }
    }

    public function test_referer_externe_ignore(): void
    {
        $this->get('/language/fr', ['Referer' => 'https://evil.example/'])
            ->assertRedirect('/');
    }

    public function test_la_langue_est_bien_changee(): void
    {
        $this->get('/language/en?redirect=/')->assertSessionHas('language', 'en');
    }

    public function test_plus_de_hreflang_vers_l_url_d_action(): void
    {
        $html = $this->get('/contact')->assertOk()->getContent();

        $this->assertStringNotContainsString('hreflang', $html);
        $this->assertDoesNotMatchRegularExpression('#<a [^>]*href="[^"]*/language/[^"]*"(?![^>]*rel="nofollow")[^>]*>#', $html);
    }

    public function test_robots_txt_interdit_language(): void
    {
        $this->assertStringContainsString('Disallow: /language/', file_get_contents(public_path('robots.txt')));
    }

    /** Rendu anglais (même URL que le français) : non indexé, sans canonical ; le français reste indexé. */
    public function test_variante_anglaise_par_parametre_non_indexee(): void
    {
        foreach (['/', '/formations/html5', '/contact'] as $url) {
            $en = $this->get($url . '?lang=en')->assertOk()->getContent();
            $this->assertStringContainsString('<meta name="robots" content="noindex, follow">', $en, $url);
            $this->assertStringNotContainsString('rel="canonical"', $en, $url);

            // Nouveau visiteur (sans la session anglaise du rendu précédent) : version française.
            $this->flushSession();
            $this->defaultCookies = [];
            app()->setLocale('fr');
            $fr = $this->get($url)->assertOk()->getContent();
            $this->assertStringContainsString('<html lang="fr"', $fr, $url);
            $this->assertStringContainsString('<meta name="robots" content="index, follow', $fr, $url);
            $this->assertStringContainsString('rel="canonical"', $fr, $url);
        }
    }
}
