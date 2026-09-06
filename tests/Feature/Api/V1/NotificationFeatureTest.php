<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;

class NotificationFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    protected function createNotification()
    {
        $notification = new DatabaseNotification([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'type' => 'App\Notifications\SystemNotification',
            'notifiable_type' => User::class,
            'notifiable_id' => $this->user->id,
            'data' => ['title' => 'Test', 'message' => 'Test message'],
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        $this->user->notifications()->save($notification);
        
        return $notification;
    }

    public function test_can_get_all_notifications()
    {
        $this->createNotification();

        $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/v1/notifications');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         'data' => [
                             '*' => ['id', 'type', 'data', 'read_at', 'created_at']
                         ]
                     ]
                 ]);
    }

    public function test_can_get_unread_notifications()
    {
        $this->createNotification();

        $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/v1/notifications/unread');

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data.data');
    }

    public function test_can_mark_notification_as_read()
    {
        $notification = $this->createNotification();

        $response = $this->actingAs($this->user, 'sanctum')->postJson("/api/v1/notifications/{$notification->id}/read");

        $response->assertStatus(200);
        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_can_delete_notification()
    {
        $notification = $this->createNotification();

        $response = $this->actingAs($this->user, 'sanctum')->deleteJson("/api/v1/notifications/{$notification->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('notifications', ['id' => $notification->id]);
    }
}
