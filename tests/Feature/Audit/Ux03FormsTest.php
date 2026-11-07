<?php

namespace Tests\Feature\Audit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Audit UX-03 : erreurs sous les champs, maxlength = limites serveur, pas de double envoi.
 */
class Ux03FormsTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_sans_alert_ni_onclick_et_avec_maxlength(): void
    {
        $html = $this->get('/contact')->assertOk()->getContent();

        $this->assertStringNotContainsString('alert(', $html);
        $this->assertStringNotContainsString("document.getElementById('contact-form').submit()", $html);
        $this->assertStringContainsString('id="phone-error"', $html);
        foreach (['id="name" name="name" required maxlength="255"', 'name="email" required maxlength="255"', 'maxlength="20"', 'name="subject" required maxlength="255"', 'name="message" required maxlength="5000"'] as $attr) {
            $this->assertStringContainsString($attr, $html);
        }
        $this->assertStringContainsString('btn.disabled = true', $html);
    }

    public function test_contact_serveur_memes_limites_et_erreurs_sous_les_champs(): void
    {
        $this->from('/contact')->post('/contact', [
            'name' => str_repeat('a', 256),
            'email' => 'awa@example.com',
            'subject' => 'Sujet',
            'message' => str_repeat('m', 5001),
        ])->assertRedirect('/contact')->assertSessionHasErrors(['name', 'message']);

        $this->get('/contact')->assertSee('id="name-error"', false)->assertSee('id="message-error"', false);
    }

    public function test_boutons_desactives_pendant_l_envoi(): void
    {
        $this->get('/login')->assertOk()->assertSee('id="login-submit"', false)->assertSee('btn.disabled = true', false);
        $this->get('/register')->assertOk()->assertSee('submitBtn.disabled = true', false);
        $this->get('/donations')->assertOk()
            ->assertSee('id="donation-form"', false)
            ->assertSee("document.getElementById('donation-form')", false)
            ->assertDontSee("document.querySelector('form').addEventListener('submit'", false)
            ->assertSee('donationSubmit.disabled = true', false)
            ->assertSee('name="message" rows="3" maxlength="500"', false);
    }
}
