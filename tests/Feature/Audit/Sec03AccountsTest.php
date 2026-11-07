<?php

namespace Tests\Feature\Audit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * Audit SEC-03 : mots de passe de 8 caractères minimum, limitation de débit, security.txt.
 */
class Sec03AccountsTest extends TestCase
{
    use RefreshDatabase;

    public function test_inscription_refuse_un_mot_de_passe_de_7_caracteres(): void
    {
        $this->post('/register', [
            'first_name' => 'Awa',
            'last_name' => 'Diop',
            'email' => 'awa@example.com',
            'password' => '1234567',
            'password_confirmation' => '1234567',
        ])->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', ['email' => 'awa@example.com']);
    }

    public function test_reinitialisation_refuse_un_mot_de_passe_de_7_caracteres(): void
    {
        $this->post(route('password.otp.verify'), [
            'email' => 'awa@example.com',
            'otp' => '123456',
            'password' => '1234567',
            'password_confirmation' => '1234567',
        ])->assertSessionHasErrors('password');
    }

    public function test_champs_du_formulaire_en_minlength_8(): void
    {
        $this->get('/register')->assertOk()
            ->assertSee('name="password" required minlength="8"', false)
            ->assertDontSee('minlength="6"', false);
    }

    public function test_routes_sensibles_limitees(): void
    {
        foreach (['login.post', 'register.post', 'newsletter.subscribe', 'contact.send', 'payment.donation', 'password.otp.send', 'password.otp.verify'] as $name) {
            $middleware = Route::getRoutes()->getByName($name)->gatherMiddleware();
            $this->assertNotEmpty(
                array_filter($middleware, fn ($m) => is_string($m) && str_starts_with($m, 'throttle')),
                "Pas de throttle sur la route $name"
            );
        }
    }

    public function test_security_txt_present(): void
    {
        $txt = file_get_contents(public_path('.well-known/security.txt'));
        $this->assertStringContainsString('Contact: mailto:NiangProgrammeur@gmail.com', $txt);
        $this->assertStringContainsString('Preferred-Languages: fr, en', $txt);
        $this->assertMatchesRegularExpression('/Expires: \d{4}-\d{2}-\d{2}T/', $txt);
    }
}
