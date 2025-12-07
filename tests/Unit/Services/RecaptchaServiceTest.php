<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\RecaptchaService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RecaptchaServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function verify_retourne_false_si_la_cle_secrete_nest_pas_configuree()
    {
        config(['services.recaptcha.secret_key' => '']);
        
        $service = new RecaptchaService();
        $result = $service->verify('test-token', '127.0.0.1');

        $this->assertFalse($result);
    }

    /** @test */
    public function verify_retourne_false_si_le_token_est_vide()
    {
        config(['services.recaptcha.secret_key' => 'test-secret']);
        
        $service = new RecaptchaService();
        $result = $service->verify('', '127.0.0.1');

        $this->assertFalse($result);
    }
}

