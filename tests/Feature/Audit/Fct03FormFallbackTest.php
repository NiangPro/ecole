<?php

namespace Tests\Feature\Audit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Audit FCT-03 : formulaires newsletter et Sokhna en POST, repli sans JS pour la newsletter.
 */
class Fct03FormFallbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_formulaires_en_post(): void
    {
        $this->get('/')->assertOk()
            ->assertSee('id="newsletterForm" class="ft__newsletter-form" method="POST" action="' . route('newsletter.subscribe') . '"', false)
            ->assertSee('id="sokhna-form" method="post" action="#"', false);
    }

    public function test_repli_sans_js_redirige_avec_message(): void
    {
        $this->from('/formations')
            ->post('/newsletter/subscribe', ['email' => 'awa@example.com'])
            ->assertRedirect(url('/formations') . '#newsletterForm')
            ->assertSessionHas('newsletter_success');

        $this->assertDatabaseHas('newsletters', ['email' => 'awa@example.com']);

        // Le message s'affiche sous le formulaire au retour sur la page.
        $this->get('/formations')->assertSee('Merci pour votre inscription', false);
    }

    public function test_repli_sans_js_erreur_dans_le_sac_newsletter(): void
    {
        $this->from('/formations')
            ->post('/newsletter/subscribe', ['email' => 'pas-un-email'])
            ->assertRedirect(url('/formations'))
            ->assertSessionHasErrorsIn('newsletter', 'email');
    }

    public function test_redirection_limitee_au_site(): void
    {
        $this->withHeader('referer', 'https://evil.example/piege')
            ->post('/newsletter/subscribe', ['email' => 'moussa@example.com'])
            ->assertRedirect(route('home') . '#newsletterForm');
    }

    public function test_appel_fetch_garde_sa_reponse_json(): void
    {
        $this->postJson('/newsletter/subscribe', ['email' => 'fatou@example.com'])
            ->assertOk()->assertJson(['success' => true]);

        $this->postJson('/newsletter/subscribe', ['email' => ''])
            ->assertStatus(422)->assertJsonValidationErrors('email');
    }
}
