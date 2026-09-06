<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivityLogFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $superAdmin;
    protected $staff;

    protected function setUp(): void
    {
        parent::setUp();
        $this->superAdmin = User::factory()->create(['role' => \App\Enums\RoleEnum::ADMIN]);
        $this->staff = User::factory()->create(['role' => \App\Enums\RoleEnum::STAFF]);
    }

    public function test_super_admin_can_view_activity_logs()
    {
        ActivityLog::factory()->count(5)->create();

        $response = $this->actingAs($this->superAdmin, 'sanctum')->getJson('/api/v1/activity-logs');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         'data' => [
                             '*' => ['id', 'user', 'action', 'model_type', 'model_id', 'ip_address', 'created_at']
                         ]
                     ]
                 ]);
    }

    public function test_staff_cannot_view_activity_logs()
    {
        $response = $this->actingAs($this->staff, 'sanctum')->getJson('/api/v1/activity-logs');

        $response->assertStatus(403);
    }

    public function test_can_filter_activity_logs_by_action()
    {
        ActivityLog::factory()->create(['action' => 'created']);
        ActivityLog::factory()->create(['action' => 'deleted']);

        $response = $this->actingAs($this->superAdmin, 'sanctum')->getJson('/api/v1/activity-logs?action=created');

        $response->assertStatus(200);
        
        $responseData = $response->json('data.data');
        $this->assertCount(1, $responseData);
        $this->assertEquals('created', $responseData[0]['action']);
    }
}
