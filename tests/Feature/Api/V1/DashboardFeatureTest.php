<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;
use App\Enums\RoleEnum;
use App\Models\User;
use App\Models\Patient;
use App\Models\PatientCategory;
use App\Models\Appointment;
use App\Models\Physiotherapist;
use App\Models\ServiceCategory;
use App\Models\ServiceMaster;
use App\Models\Payment;
use App\Models\MedicalRecord;
use App\Models\TherapySession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class DashboardFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $physioUser;
    
    protected $patient;
    protected $physio;
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create(['role' => RoleEnum::ADMIN]);
        $this->physioUser = User::factory()->create(['role' => RoleEnum::FISIOTERAPIS]);

        $patientCategory = PatientCategory::create(['name' => 'Umum']);
        $serviceCategory = ServiceCategory::create(['name' => 'Konsultasi']);

        $this->patient = Patient::create([
            'patient_category_id' => $patientCategory->id,
            'medical_record_number' => 'RM00001',
            'name' => 'Pasien A',
            'nik' => '1111111111111111',
            'birth_date' => '1990-01-01',
            'phone' => '0811',
            'address' => 'Jl. A',
            'status' => 'active'
        ]);

        $this->physio = Physiotherapist::create([
            'user_id' => $this->physioUser->id,
            'name' => 'Fisio 1',
            'email' => $this->physioUser->email,
            'phone' => '08111',
            'specialization' => 'Umum',
            'status' => 'active'
        ]);

        $this->service = ServiceMaster::create([
            'service_category_id' => $serviceCategory->id,
            'category' => 'Konsultasi',
            'code' => 'SVC001',
            'name' => 'Terapi Fisik',
            'price' => 100000,
            'duration' => 60,
            'description' => 'Terapi',
            'is_active' => true
        ]);
        
        // Setup data for current year/month/today
        $today = Carbon::today()->toDateString();
        
        $appointment = Appointment::create([
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physio->id,
            'service_master_id' => $this->service->id,
            'appointment_date' => $today,
            'appointment_time' => '10:00:00',
            'status' => 'scheduled',
            'notes' => 'Catatan'
        ]);
        
        $session = TherapySession::create([
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physio->id,
            'appointment_id' => $appointment->id,
            'therapy_date' => $today,
            'complaint' => 'Sakit pinggang',
            'treatment_given' => 'Terapi',
            'status' => 'completed'
        ]);
        
        MedicalRecord::create([
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physio->id,
            'examination_date' => $today,
            'anamnesis' => 'Anamnesis',
            'diagnosis' => 'Low Back Pain', // for diseases chart
            'therapy' => 'Therapy'
        ]);
        
        Payment::create([
            'therapy_session_id' => $session->id,
            'invoice_number' => 'INV-001',
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physio->id,
            'payment_date' => $today,
            'payment_method' => 'Tunai',
            'status' => 'Lunas',
            'subtotal' => 100000,
            'total' => 100000,
        ]);
    }

    public function test_admin_can_get_dashboard_summary()
    {
        $response = $this->actingAs($this->admin, 'sanctum')->getJson('/api/v1/dashboard/admin');

        $response->assertStatus(200)
                 ->assertJsonPath('success', true);

        // Check if data is populated properly
        $this->assertEquals(1, $response->json('data.summary.total_pasien'));
        $this->assertEquals(1, $response->json('data.summary.total_fisioterapi'));
        $this->assertEquals(1, $response->json('data.summary.total_appointment'));
        $this->assertEquals(1, $response->json('data.summary.appointment_hari_ini'));
    }

    public function test_fisioterapis_gets_physio_specific_dashboard()
    {
        $response = $this->actingAs($this->physioUser, 'sanctum')->getJson('/api/v1/dashboard/fisio');

        $response->assertStatus(200)
                 ->assertJsonPath('success', true);

        // Validate physio specific stats
        $this->assertEquals(1, $response->json('data.summary.today_appointments'));
        $this->assertEquals(1, $response->json('data.summary.today_patients'));
        $this->assertEquals(1, $response->json('data.summary.today_therapy_sessions'));
        
        $this->assertEquals('Pasien A', $response->json('data.next_appointment.patient_name'));
    }

    public function test_dashboard_filters_execute_sql_without_error()
    {
        // Testing that the custom MySQL grouping queries don't fail syntactically
        $filters = ['today', 'week', 'month', 'year'];

        foreach ($filters as $filter) {
            $response = $this->actingAs($this->admin, 'sanctum')->getJson("/api/v1/dashboard/admin?filter={$filter}");
            $response->assertStatus(200);
            
            // Check that chart arrays are returned
            $this->assertIsArray($response->json('data.charts.patients'));
            $this->assertIsArray($response->json('data.charts.appointments'));
            $this->assertIsArray($response->json('data.charts.revenue'));
        }
    }
}
