<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;
use App\Enums\RoleEnum;
use App\Models\User;
use App\Models\Patient;
use App\Models\PatientCategory;
use App\Models\Physiotherapist;
use App\Models\ServiceCategory;
use App\Models\ServiceMaster;
use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class AppointmentFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $staff;
    protected $physioUser;
    
    protected $patient;
    protected $physio;
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create(['role' => RoleEnum::ADMIN]);
        $this->staff = User::factory()->create(['role' => RoleEnum::STAFF]);
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
    }

    public function test_staff_can_create_appointment()
    {
        $payload = [
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physio->id,
            'service_master_id' => $this->service->id,
            'appointment_date' => now()->addDays(1)->toDateString(),
            'appointment_time' => '10:00',
            'complaint' => 'Sakit punggung',
        ];

        $response = $this->actingAs($this->staff, 'sanctum')->postJson('/api/v1/appointments', $payload);

        $response->assertStatus(201)
                 ->assertJsonPath('data.status', 'pending');

        $appointmentId = $response->json('data.id');

        $this->assertDatabaseHas('appointments', [
            'id' => $appointmentId,
            'status' => 'pending',
            'complaint' => 'Sakit punggung'
        ]);
    }

    public function test_cannot_create_double_booking_for_physiotherapist()
    {
        // First appointment
        Appointment::create([
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physio->id,
            'service_master_id' => $this->service->id,
            'appointment_date' => now()->addDays(1)->toDateString(),
            'appointment_time' => '10:00:00',
            'status' => 'scheduled'
        ]);

        $payload = [
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physio->id,
            'service_master_id' => $this->service->id,
            'appointment_date' => now()->addDays(1)->toDateString(),
            'appointment_time' => '10:00', // Conflict!
        ];

        $response = $this->actingAs($this->staff, 'sanctum')->postJson('/api/v1/appointments', $payload);

        // Service throws Exception with 422
        $response->assertStatus(422)
                 ->assertJsonPath('message', 'Gagal membuat temu janji: Fisioterapis sudah memiliki jadwal pada tanggal dan jam tersebut.');
    }

    public function test_staff_can_reschedule_appointment()
    {
        $appointment = Appointment::create([
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physio->id,
            'service_master_id' => $this->service->id,
            'appointment_date' => now()->addDays(1)->toDateString(),
            'appointment_time' => '10:00:00',
            'status' => 'pending'
        ]);

        $payload = [
            'appointment_date' => now()->addDays(2)->toDateString(),
            'appointment_time' => '14:00',
        ];

        $response = $this->actingAs($this->staff, 'sanctum')->postJson("/api/v1/appointments/{$appointment->id}/reschedule", $payload);

        $response->assertStatus(200)
                 ->assertJsonPath('data.status', 'rescheduled');
                 
        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'appointment_date' => now()->addDays(2)->toDateString(),
            'appointment_time' => '14:00:00'
        ]);
    }

    public function test_cannot_reschedule_to_double_booked_time()
    {
        $appointment1 = Appointment::create([
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physio->id,
            'service_master_id' => $this->service->id,
            'appointment_date' => now()->addDays(1)->toDateString(),
            'appointment_time' => '10:00:00',
            'status' => 'pending'
        ]);

        $appointment2 = Appointment::create([
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physio->id,
            'service_master_id' => $this->service->id,
            'appointment_date' => now()->addDays(2)->toDateString(),
            'appointment_time' => '14:00:00',
            'status' => 'pending'
        ]);

        $payload = [
            'appointment_date' => now()->addDays(2)->toDateString(),
            'appointment_time' => '14:00', // Conflict with appointment2
        ];

        $response = $this->actingAs($this->staff, 'sanctum')->postJson("/api/v1/appointments/{$appointment1->id}/reschedule", $payload);

        $response->assertStatus(422)
                 ->assertJsonPath('message', 'Fisioterapis sudah memiliki jadwal pada tanggal dan jam tersebut.');
    }

    public function test_staff_can_cancel_appointment()
    {
        $appointment = Appointment::create([
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physio->id,
            'service_master_id' => $this->service->id,
            'appointment_date' => now()->addDays(1)->toDateString(),
            'appointment_time' => '10:00:00',
            'status' => 'pending'
        ]);

        $payload = [
            'notes' => 'Pasien membatalkan sepihak'
        ];

        $response = $this->actingAs($this->staff, 'sanctum')->postJson("/api/v1/appointments/{$appointment->id}/cancel", $payload);

        $response->assertStatus(200)
                 ->assertJsonPath('data.status', 'cancelled');
                 
        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => 'cancelled'
        ]);
    }

    public function test_cannot_cancel_completed_appointment()
    {
        $appointment = Appointment::create([
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physio->id,
            'service_master_id' => $this->service->id,
            'appointment_date' => now()->addDays(1)->toDateString(),
            'appointment_time' => '10:00:00',
            'status' => 'completed'
        ]);

        $response = $this->actingAs($this->staff, 'sanctum')->postJson("/api/v1/appointments/{$appointment->id}/cancel", []);

        $response->assertStatus(400)
                 ->assertJsonPath('message', 'Tidak dapat membatalkan jadwal yang sudah selesai');
    }

    public function test_physiotherapist_can_only_see_their_appointments()
    {
        $appointment = Appointment::create([
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physio->id,
            'service_master_id' => $this->service->id,
            'appointment_date' => now()->addDays(1)->toDateString(),
            'appointment_time' => '10:00:00',
            'status' => 'pending'
        ]);

        // Creating another physio
        $physioUser2 = User::factory()->create(['role' => RoleEnum::FISIOTERAPIS]);
        $physio2 = Physiotherapist::create([
            'user_id' => $physioUser2->id,
            'name' => 'Fisio 2',
            'email' => $physioUser2->email,
            'phone' => '08222',
            'specialization' => 'Umum',
            'status' => 'active'
        ]);

        // Trying to access physio 1's appointment
        $response = $this->actingAs($physioUser2, 'sanctum')->getJson("/api/v1/appointments/{$appointment->id}");

        $response->assertStatus(403)
                 ->assertJsonPath('message', 'Akses ditolak');
                 
        // Physio 1 accessing their own
        $response2 = $this->actingAs($this->physioUser, 'sanctum')->getJson("/api/v1/appointments/{$appointment->id}");
        $response2->assertStatus(200);
    }

    public function test_staff_cannot_soft_delete_appointment()
    {
        $appointment = Appointment::create([
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physio->id,
            'service_master_id' => $this->service->id,
            'appointment_date' => now()->addDays(1)->toDateString(),
            'appointment_time' => '10:00:00',
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->staff, 'sanctum')->deleteJson("/api/v1/appointments/{$appointment->id}");
        $response->assertStatus(403);
    }

    public function test_admin_can_soft_delete_appointment()
    {
        $appointment = Appointment::create([
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physio->id,
            'service_master_id' => $this->service->id,
            'appointment_date' => now()->addDays(1)->toDateString(),
            'appointment_time' => '10:00:00',
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')->deleteJson("/api/v1/appointments/{$appointment->id}");
        $response->assertStatus(200);
        $this->assertSoftDeleted('appointments', ['id' => $appointment->id]);
    }

    // =====================================================
    // INDEX
    // =====================================================

    public function test_admin_can_get_all_appointments()
    {
        Appointment::create([
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physio->id,
            'service_master_id' => $this->service->id,
            'appointment_date' => now()->addDays(1)->toDateString(),
            'appointment_time' => '09:00:00',
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')->getJson('/api/v1/appointments');

        $response->assertStatus(200)
                 ->assertJsonPath('success', true)
                 ->assertJsonStructure(['data' => ['data']]);

        $this->assertGreaterThanOrEqual(1, count($response->json('data.data')));
    }

    public function test_staff_can_get_all_appointments()
    {
        $response = $this->actingAs($this->staff, 'sanctum')->getJson('/api/v1/appointments');

        $response->assertStatus(200)
                 ->assertJsonPath('success', true);
    }

    public function test_fisioterapis_can_access_all_appointments_index()
    {
        // Appointment milik physioUser (fisioterapis1)
        Appointment::create([
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physio->id,
            'service_master_id' => $this->service->id,
            'appointment_date' => now()->addDays(1)->toDateString(),
            'appointment_time' => '09:00:00',
            'status' => 'pending'
        ]);

        // Fisioterapis lain
        $physioUser2 = User::factory()->create(['role' => RoleEnum::FISIOTERAPIS]);
        $physio2 = Physiotherapist::create([
            'name' => 'Fisio 2',
            'email' => $physioUser2->email,
            'phone' => '08999',
            'specialization' => 'Umum',
            'status' => 'active'
        ]);
        Appointment::create([
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $physio2->id,
            'service_master_id' => $this->service->id,
            'appointment_date' => now()->addDays(1)->toDateString(),
            'appointment_time' => '11:00:00',
            'status' => 'pending'
        ]);

        // Behavior aktual: index tidak mem-filter per-fisioterapis, fisioterapis bisa lihat semua appointment
        $response = $this->actingAs($this->physioUser, 'sanctum')->getJson('/api/v1/appointments');

        $response->assertStatus(200)
                 ->assertJsonPath('success', true);

        // Fisioterapis bisa menggunakan filter physiotherapist_id untuk lihat jadwalnya sendiri
        $filteredResponse = $this->actingAs($this->physioUser, 'sanctum')
            ->getJson('/api/v1/appointments?physiotherapist_id=' . $this->physio->id);

        $filteredResponse->assertStatus(200);
        foreach ($filteredResponse->json('data.data') as $item) {
            $this->assertEquals($this->physio->id, $item['physiotherapist_id']);
        }
    }

    public function test_unauthenticated_user_cannot_access_appointments()
    {
        $response = $this->getJson('/api/v1/appointments');
        $response->assertStatus(401);
    }

    // =====================================================
    // SHOW
    // =====================================================

    public function test_admin_can_show_appointment_by_id()
    {
        $appointment = Appointment::create([
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physio->id,
            'service_master_id' => $this->service->id,
            'appointment_date' => now()->addDays(1)->toDateString(),
            'appointment_time' => '10:00:00',
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')->getJson("/api/v1/appointments/{$appointment->id}");

        $response->assertStatus(200)
                 ->assertJsonPath('success', true)
                 ->assertJsonPath('data.id', $appointment->id)
                 ->assertJsonPath('data.status', 'pending');
    }

    public function test_show_returns_404_for_nonexistent_appointment()
    {
        $response = $this->actingAs($this->admin, 'sanctum')->getJson('/api/v1/appointments/9999');

        $response->assertStatus(404)
                 ->assertJsonPath('success', false);
    }

    // =====================================================
    // UPDATE
    // =====================================================

    public function test_admin_can_update_appointment_status()
    {
        $appointment = Appointment::create([
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physio->id,
            'service_master_id' => $this->service->id,
            'appointment_date' => now()->addDays(1)->toDateString(),
            'appointment_time' => '10:00:00',
            'status' => 'pending'
        ]);

        $payload = [
            'patient_id'         => $this->patient->id,
            'physiotherapist_id' => $this->physio->id,
            'service_master_id'  => $this->service->id,
            'appointment_date'   => now()->addDays(1)->toDateString(),
            'appointment_time'   => '10:00',
            'status'             => 'confirmed',
        ];

        $response = $this->actingAs($this->admin, 'sanctum')
            ->putJson("/api/v1/appointments/{$appointment->id}", $payload);

        $response->assertStatus(200)
                 ->assertJsonPath('data.status', 'confirmed');

        $this->assertDatabaseHas('appointments', [
            'id'     => $appointment->id,
            'status' => 'confirmed',
        ]);
    }

    public function test_staff_can_update_appointment_status()
    {
        // Behavior aktual: Policy 'manage' mengizinkan STAFF untuk update appointment
        $appointment = Appointment::create([
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physio->id,
            'service_master_id' => $this->service->id,
            'appointment_date' => now()->addDays(1)->toDateString(),
            'appointment_time' => '10:00:00',
            'status' => 'pending'
        ]);

        $payload = [
            'patient_id'         => $this->patient->id,
            'physiotherapist_id' => $this->physio->id,
            'service_master_id'  => $this->service->id,
            'appointment_date'   => now()->addDays(1)->toDateString(),
            'appointment_time'   => '10:00',
            'status'             => 'confirmed',
        ];

        $response = $this->actingAs($this->staff, 'sanctum')
            ->putJson("/api/v1/appointments/{$appointment->id}", $payload);

        // Staff diizinkan manage appointment sesuai AppointmentPolicy::manage()
        $response->assertStatus(200)
                 ->assertJsonPath('data.status', 'confirmed');
    }

    public function test_update_validation_fails_with_invalid_status()
    {
        $appointment = Appointment::create([
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physio->id,
            'service_master_id' => $this->service->id,
            'appointment_date' => now()->addDays(1)->toDateString(),
            'appointment_time' => '10:00:00',
            'status' => 'pending'
        ]);

        $payload = [
            'patient_id'         => $this->patient->id,
            'physiotherapist_id' => $this->physio->id,
            'service_master_id'  => $this->service->id,
            'appointment_date'   => now()->addDays(1)->toDateString(),
            'appointment_time'   => '10:00',
            'status'             => 'invalid_status',
        ];

        $response = $this->actingAs($this->admin, 'sanctum')
            ->putJson("/api/v1/appointments/{$appointment->id}", $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['status']);
    }

    // =====================================================
    // HISTORY
    // =====================================================

    public function test_can_get_appointment_history()
    {
        // Hanya completed/cancelled yang masuk history
        Appointment::create([
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physio->id,
            'service_master_id' => $this->service->id,
            'appointment_date' => now()->subDays(5)->toDateString(),
            'appointment_time' => '10:00:00',
            'status' => 'completed'
        ]);
        Appointment::create([
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physio->id,
            'service_master_id' => $this->service->id,
            'appointment_date' => now()->subDays(3)->toDateString(),
            'appointment_time' => '11:00:00',
            'status' => 'cancelled'
        ]);
        // Ini pending — tidak masuk history
        Appointment::create([
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physio->id,
            'service_master_id' => $this->service->id,
            'appointment_date' => now()->addDays(1)->toDateString(),
            'appointment_time' => '09:00:00',
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')->getJson('/api/v1/appointments/history');

        $response->assertStatus(200)
                 ->assertJsonPath('success', true);

        $historyData = $response->json('data.data');
        $this->assertCount(2, $historyData);

        // Semua item di history harus berstatus completed atau cancelled
        foreach ($historyData as $item) {
            $this->assertContains($item['status'], ['completed', 'cancelled']);
        }
    }

    public function test_history_can_be_filtered_by_date_range()
    {
        Appointment::create([
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physio->id,
            'service_master_id' => $this->service->id,
            'appointment_date' => now()->subDays(10)->toDateString(),
            'appointment_time' => '08:00:00',
            'status' => 'completed'
        ]);
        Appointment::create([
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physio->id,
            'service_master_id' => $this->service->id,
            'appointment_date' => now()->subDays(2)->toDateString(),
            'appointment_time' => '09:00:00',
            'status' => 'completed'
        ]);

        $startDate = now()->subDays(5)->toDateString();
        $endDate   = now()->toDateString();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson("/api/v1/appointments/history?start_date={$startDate}&end_date={$endDate}");

        $response->assertStatus(200);
        // Hanya 1 appointment yang masuk rentang tanggal ini
        $this->assertCount(1, $response->json('data.data'));
    }

    public function test_store_validation_fails_without_required_fields()
    {
        $response = $this->actingAs($this->staff, 'sanctum')
            ->postJson('/api/v1/appointments', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['patient_id', 'physiotherapist_id', 'service_master_id', 'appointment_date', 'appointment_time']);
    }
}
