<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;
use App\Models\User;
use App\Models\ServiceMaster;
use App\Models\ServiceCategory;
use App\Enums\RoleEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServiceMasterFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $staff;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create(['role' => RoleEnum::ADMIN]);
        $this->staff = User::factory()->create(['role' => RoleEnum::STAFF]);
        
        $this->category = ServiceCategory::create(['name' => 'Konsultasi']);
    }

    public function test_anyone_can_get_service_masters()
    {
        ServiceMaster::create([
            'service_category_id' => $this->category->id,
            'category' => 'Konsultasi',
            'code' => 'SVC001',
            'name' => 'Konsultasi Dokter',
            'price' => 150000,
            'duration' => 30,
            'is_active' => true
        ]);

        $response = $this->actingAs($this->staff, 'sanctum')->getJson('/api/v1/service-masters');

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data.data');
    }

    public function test_staff_cannot_create_service_master()
    {
        $serviceData = [
            'category' => 'Konsultasi',
            'name' => 'Konsultasi Spesialis',
            'duration' => 60,
            'price' => 300000,
            'is_active' => true,
        ];

        $response = $this->actingAs($this->staff, 'sanctum')->postJson('/api/v1/service-masters', $serviceData);
        $response->assertStatus(403);
    }

    public function test_admin_can_create_service_master()
    {
        $serviceData = [
            'category' => 'Konsultasi',
            'name' => 'Konsultasi Spesialis',
            'duration' => 60,
            'price' => 300000,
            'is_active' => true,
        ];

        $response = $this->actingAs($this->admin, 'sanctum')->postJson('/api/v1/service-masters', $serviceData);
        $response->assertStatus(201)
                 ->assertJsonPath('data.name', 'Konsultasi Spesialis');
                 
        $this->assertDatabaseHas('service_masters', ['name' => 'Konsultasi Spesialis']);
    }

    public function test_admin_can_update_service_master()
    {
        $service = ServiceMaster::create([
            'service_category_id' => $this->category->id,
            'category' => 'Konsultasi',
            'code' => 'SVC001',
            'name' => 'Konsultasi Dokter',
            'price' => 150000,
            'duration' => 30,
            'is_active' => true
        ]);
        
        $updateData = [
            'category' => 'Konsultasi',
            'name' => 'Konsultasi Umum',
            'duration' => 45,
            'price' => 200000,
            'is_active' => true,
        ];

        $response = $this->actingAs($this->admin, 'sanctum')->putJson("/api/v1/service-masters/{$service->id}", $updateData);
        
        $response->assertStatus(200)
                 ->assertJsonPath('data.name', 'Konsultasi Umum');
                 
        $this->assertDatabaseHas('service_masters', ['id' => $service->id, 'name' => 'Konsultasi Umum']);
    }

    public function test_admin_can_delete_service_master()
    {
        $service = ServiceMaster::create([
            'service_category_id' => $this->category->id,
            'category' => 'Konsultasi',
            'code' => 'SVC001',
            'name' => 'Konsultasi Dokter',
            'price' => 150000,
            'duration' => 30,
            'is_active' => true
        ]);
        
        $response = $this->actingAs($this->admin, 'sanctum')->deleteJson("/api/v1/service-masters/{$service->id}");
        
        $response->assertStatus(200);
        $this->assertSoftDeleted('service_masters', ['id' => $service->id]);
    }
}
