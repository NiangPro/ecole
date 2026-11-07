<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Wave accepte tous les montants (plus de minimum de 1000 FCFA).
 */
class DonationWaveAmountTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_petit_don_wave_passe_la_validation(): void
    {
        $this->from('/donations')->post('/payment/donation', [
            'donor_name' => 'Awa Diop',
            'payment_method' => 'wave',
            'amount' => 50,
            'amount_currency' => 'XOF',
        ])->assertSessionDoesntHaveErrors('amount');

        $this->assertDatabaseHas('donations', ['payment_method' => 'wave', 'amount' => 50]);
    }

    public function test_un_montant_nul_reste_refuse(): void
    {
        $this->from('/donations')->post('/payment/donation', [
            'donor_name' => 'Awa Diop',
            'payment_method' => 'wave',
            'amount' => 0,
        ])->assertSessionHasErrors('amount');
    }

    public function test_le_champ_accepte_tout_montant_pour_wave(): void
    {
        $this->get('/donations')->assertOk()
            ->assertSee('data-min-amount="1"', false)
            ->assertSee("donationAmount.step = selectedMethod === 'wave' ? '1' : '100';", false);
    }
}
