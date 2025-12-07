<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Badge;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BadgeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function un_badge_peut_etre_cree()
    {
        $badge = Badge::factory()->create([
            'name' => 'Premier Pas',
            'code' => 'first_step',
            'description' => 'Description du badge',
        ]);

        $this->assertDatabaseHas('badges', [
            'code' => 'first_step',
            'name' => 'Premier Pas',
        ]);
    }

    /** @test */
    public function un_badge_peut_etre_attribue_a_un_utilisateur()
    {
        $user = User::factory()->create();
        $badge = Badge::factory()->create();

        $user->badges()->attach($badge->id, ['earned_at' => now()]);

        $this->assertTrue($user->hasBadge($badge->code));
        $this->assertCount(1, $user->badges);
    }

    /** @test */
    public function un_badge_peut_etre_retrouve_par_code()
    {
        $badge = Badge::factory()->create(['code' => 'test_badge']);

        $found = Badge::where('code', 'test_badge')->first();

        $this->assertNotNull($found);
        $this->assertEquals($badge->id, $found->id);
    }
}

