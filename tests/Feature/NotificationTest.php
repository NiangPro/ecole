<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function un_utilisateur_peut_voir_ses_notifications_non_lues()
    {
        $user = $this->actingAsUser();

        Notification::createNotification($user->id, 'info', 'Titre 1', 'Message 1');
        Notification::createNotification($user->id, 'info', 'Titre 2', 'Message 2');

        $response = $this->getJson('/api/notifications/unread');

        $response->assertStatus(200)
            ->assertJsonCount(2);
    }

    /** @test */
    public function un_utilisateur_peut_marquer_une_notification_comme_lue()
    {
        $user = $this->actingAsUser();
        $notification = Notification::createNotification($user->id, 'info', 'Titre', 'Message');

        $response = $this->postJson("/api/notifications/{$notification->id}/read");

        $response->assertStatus(200);
        $this->assertTrue($notification->fresh()->is_read);
    }

    /** @test */
    public function un_utilisateur_peut_marquer_toutes_les_notifications_comme_lues()
    {
        $user = $this->actingAsUser();
        
        Notification::createNotification($user->id, 'info', 'Titre 1', 'Message 1');
        Notification::createNotification($user->id, 'info', 'Titre 2', 'Message 2');

        $response = $this->postJson('/api/notifications/read-all');

        $response->assertStatus(200);
        $this->assertEquals(0, Notification::countUnread($user->id));
    }
}

