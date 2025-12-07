<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function une_notification_peut_etre_creee()
    {
        $user = User::factory()->create();

        $notification = Notification::createNotification(
            $user->id,
            'info',
            'Titre',
            'Message de notification'
        );

        $this->assertDatabaseHas('notifications', [
            'user_id' => $user->id,
            'type' => 'info',
            'title' => 'Titre',
            'is_read' => false,
        ]);
    }

    /** @test */
    public function une_notification_peut_etre_marquee_comme_lue()
    {
        $user = User::factory()->create();
        $notification = Notification::createNotification(
            $user->id,
            'info',
            'Titre',
            'Message'
        );

        $notification->markAsRead();

        $this->assertTrue($notification->fresh()->is_read);
    }

    /** @test */
    public function toutes_les_notifications_peuvent_etre_marquees_comme_lues()
    {
        $user = User::factory()->create();
        
        Notification::createNotification($user->id, 'info', 'Titre 1', 'Message 1');
        Notification::createNotification($user->id, 'info', 'Titre 2', 'Message 2');

        $count = Notification::markAllAsRead($user->id);

        $this->assertEquals(2, $count);
        $this->assertEquals(0, Notification::countUnread($user->id));
    }

    /** @test */
    public function countUnread_retourne_le_nombre_de_notifications_non_lues()
    {
        $user = User::factory()->create();
        
        Notification::createNotification($user->id, 'info', 'Titre 1', 'Message 1');
        Notification::createNotification($user->id, 'info', 'Titre 2', 'Message 2');
        
        $notification = Notification::createNotification($user->id, 'info', 'Titre 3', 'Message 3');
        $notification->markAsRead();

        $this->assertEquals(2, Notification::countUnread($user->id));
    }

    /** @test */
    public function getUnread_retourne_les_notifications_non_lues()
    {
        $user = User::factory()->create();
        
        Notification::createNotification($user->id, 'info', 'Titre 1', 'Message 1');
        Notification::createNotification($user->id, 'info', 'Titre 2', 'Message 2');

        $unread = Notification::getUnread($user->id);

        $this->assertCount(2, $unread);
        $this->assertFalse($unread->first()->is_read);
    }
}

