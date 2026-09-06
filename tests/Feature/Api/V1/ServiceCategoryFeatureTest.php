<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;
use App\Models\User;
use App\Models\ServiceCategory;
use App\Enums\RoleEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServiceCategoryFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $staff;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create(['role' => RoleEnum::ADMIN]);
        $this->staff = User::factory()->create(['role' => RoleEnum::STAFF]);
    }

    public function test_anyone_can_get_service_categories()
    {
        ServiceCategory::create(['name' => 'Konsultasi']);
        ServiceCategory::create(['name' => 'Terapi']);

        $response = $this->actingAs($this->staff, 'sanctum')->getJson('/api/v1/service-categories');

        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data.data'); // It uses paginate()
    }
    
    public function test_anyone_can_get_service_category_detail()
    {
        $category = ServiceCategory::create(['name' => 'Konsultasi']);

        $response = $this->actingAs($this->staff, 'sanctum')->getJson("/api/v1/service-categories/{$category->id}");

        $response->assertStatus(200)
                 ->assertJsonPath('data.name', 'Konsultasi');
    }

    public function test_staff_cannot_create_service_category()
    {
        $response = $this->actingAs($this->staff, 'sanctum')->postJson('/api/v1/service-categories', ['name' => 'Operasi']);
        $response->assertStatus(403);
    }

    public function test_admin_can_create_service_category()
    {
        $response = $this->actingAs($this->admin, 'sanctum')->postJson('/api/v1/service-categories', ['name' => 'Operasi']);
        $response->assertStatus(201)
                 ->assertJsonPath('data.name', 'Operasi');
                 
        $this->assertDatabaseHas('service_categories', ['name' => 'Operasi']);
    }

    public function test_admin_can_update_service_category()
    {
        $category = ServiceCategory::create(['name' => 'Konsultasi']);
        
        $response = $this->actingAs($this->admin, 'sanctum')->putJson("/api/v1/service-categories/{$category->id}", ['name' => 'Diskusi']);
        
        $response->assertStatus(200)
                 ->assertJsonPath('data.name', 'Diskusi');
                 
        $this->assertDatabaseHas('service_categories', ['name' => 'Diskusi']);
    }

    public function test_admin_can_delete_service_category()
    {
        $category = ServiceCategory::create(['name' => 'Konsultasi']);
        
        $response = $this->actingAs($this->admin, 'sanctum')->deleteJson("/api/v1/service-categories/{$category->id}");
        
        $response->assertStatus(200);
        $this->assertSoftDeleted('service_categories', ['id' => $category->id]);
    }
}
