<?php

namespace Tests\Feature\Api\V1;

use App\Models\Patient;
use App\Models\User;
use App\Models\PatientCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Enums\RoleEnum;

class ReportFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create(['role' => RoleEnum::ADMIN]);
    }

    public function test_can_get_dashboard_report()
    {
        $category = PatientCategory::create(['name' => 'Umum']);
        
        Patient::create([
            'patient_category_id' => $category->id,
            'medical_record_number' => 'RM0001',
            'name' => 'Pasien A',
            'nik' => '1234567890',
            'birth_date' => '1990-01-01',
            'phone' => '081',
            'address' => 'Jl',
            'status' => 'active'
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')->getJson('/api/v1/reports/dashboard');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'summary' => [
                             'total_patients',
                             'total_physiotherapists',
                             'total_appointments',
                             'total_sessions',
                         ],
                         'appointments_by_status',
                         'sessions_by_month'
                     ]
                 ]);
                 
        $this->assertEquals(1, $response->json('data.summary.total_patients'));
    }
}
