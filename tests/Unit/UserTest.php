<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Badge;
use App\Models\Notification;
use App\Models\Favorite;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function un_utilisateur_peut_etre_cree()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User',
        ]);
    }

    /** @test */
    public function isAdmin_retourne_true_pour_un_admin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($user->isAdmin());
    }

    /** @test */
    public function scopeActive_filtre_les_utilisateurs_actifs()
    {
        User::factory()->create(['is_active' => true]);
        User::factory()->create(['is_active' => false]);

        $activeUsers = User::active()->get();

        $this->assertCount(1, $activeUsers);
        $this->assertTrue($activeUsers->first()->is_active);
    }

    /** @test */
    public function un_utilisateur_peut_avoir_des_badges()
    {
        $user = User::factory()->create();
        $badge = Badge::factory()->create();

        $user->badges()->attach($badge->id, ['earned_at' => now()]);

        $this->assertTrue($user->hasBadge($badge->code));
        $this->assertEquals($badge->id, $user->getBadge($badge->code)->id);
    }

    /** @test */
    public function un_utilisateur_peut_avoir_des_notifications()
    {
        $user = User::factory()->create();
        
        Notification::createNotification(
            $user->id,
            'info',
            'Test Notification',
            'Message de test'
        );

        $this->assertEquals(1, Notification::countUnread($user->id));
        $this->assertCount(1, Notification::getUnread($user->id));
    }

    /** @test */
    public function un_utilisateur_peut_avoir_des_favoris()
    {
        $user = User::factory()->create();
        
        Favorite::create([
            'user_id' => $user->id,
            'favoritable_type' => 'formation',
            'favoritable_slug' => 'html5',
            'favoritable_name' => 'HTML5',
        ]);

        $this->assertCount(1, $user->favorites);
    }

    /** @test */
    public function getProgressForFormation_retourne_la_progression()
    {
        $user = User::factory()->create();
        
        $progress = \App\Models\FormationProgress::factory()->create([
            'user_id' => $user->id,
            'formation_slug' => 'html5',
        ]);

        $result = $user->getProgressForFormation('html5');

        $this->assertNotNull($result);
        $this->assertEquals($progress->id, $result->id);
    }
}

