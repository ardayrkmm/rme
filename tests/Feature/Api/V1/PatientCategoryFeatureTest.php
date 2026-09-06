<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;
use App\Models\User;
use App\Models\PatientCategory;
use App\Enums\RoleEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PatientCategoryFeatureTest extends TestCase
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

    public function test_anyone_can_get_patient_categories()
    {
        PatientCategory::create(['name' => 'Umum']);
        PatientCategory::create(['name' => 'VIP']);

        $response = $this->actingAs($this->staff, 'sanctum')->getJson('/api/v1/patient-categories');

        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data');
    }

    public function test_staff_cannot_create_patient_category()
    {
        $response = $this->actingAs($this->staff, 'sanctum')->postJson('/api/v1/patient-categories', ['name' => 'BPJS']);
        $response->assertStatus(403);
    }

    public function test_admin_can_create_patient_category()
    {
        $response = $this->actingAs($this->admin, 'sanctum')->postJson('/api/v1/patient-categories', ['name' => 'BPJS']);
        $response->assertStatus(201)
                 ->assertJsonPath('data.name', 'BPJS');
                 
        $this->assertDatabaseHas('patient_categories', ['name' => 'BPJS']);
    }

    public function test_admin_can_update_patient_category()
    {
        $category = PatientCategory::create(['name' => 'BPJS']);
        
        $response = $this->actingAs($this->admin, 'sanctum')->putJson("/api/v1/patient-categories/{$category->id}", ['name' => 'Asuransi']);
        
        $response->assertStatus(200)
                 ->assertJsonPath('data.name', 'Asuransi');
                 
        $this->assertDatabaseHas('patient_categories', ['name' => 'Asuransi']);
    }

    public function test_admin_can_delete_patient_category()
    {
        $category = PatientCategory::create(['name' => 'BPJS']);
        
        $response = $this->actingAs($this->admin, 'sanctum')->deleteJson("/api/v1/patient-categories/{$category->id}");
        
        $response->assertStatus(200);
        $this->assertDatabaseMissing('patient_categories', ['id' => $category->id]);
    }
}
