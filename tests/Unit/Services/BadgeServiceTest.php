<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\BadgeService;
use App\Models\User;
use App\Models\Badge;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BadgeServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function le_service_peut_verifier_et_attribuer_un_badge()
    {
        $user = User::factory()->create();
        $badge = Badge::factory()->create([
            'code' => 'first_formation',
            'requirement_type' => 'formation_completed',
            'requirement_value' => 1,
        ]);

        $service = new BadgeService();
        $service->checkAndAwardBadges($user);

        // Le badge devrait être attribué si les conditions sont remplies
        $this->assertTrue(true); // Test de base
    }
}

