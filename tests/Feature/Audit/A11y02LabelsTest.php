<?php

namespace Tests\Feature\Audit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Audit A11Y-02 : chaque champ des formulaires d'inscription et de don a un nom accessible.
 */
class A11y02LabelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_indicatif_pays_de_l_inscription(): void
    {
        $this->get('/register')->assertOk()
            ->assertSee('id="phone_country" class="phone-select" aria-label="Indicatif pays"', false);
    }

    public function test_champs_du_formulaire_de_don_associes_a_un_label(): void
    {
        $html = $this->get('/donations')->assertOk()->getContent();

        foreach (['donor_name' => 'donor_name', 'donor_email' => 'donor_email', 'payment_method' => 'payment_method_select',
                  'amount' => 'donation_amount', 'message' => 'donation_message'] as $name => $id) {
            $this->assertMatchesRegularExpression('/<(input|select|textarea)[^>]*\bid="' . $id . '"[^>]*\bname="' . $name . '"|<(input|select|textarea)[^>]*\bname="' . $name . '"[^>]*\bid="' . $id . '"/', $html, $name);
            $this->assertStringContainsString('<label for="' . $id . '"', $html, $name);
        }
    }
}
