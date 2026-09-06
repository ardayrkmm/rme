<?php

namespace Tests\Feature\Api\V1;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Physiotherapist;
use App\Models\ServiceMaster;
use App\Models\TherapySession;
use App\Models\MedicalRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Enums\RoleEnum;

class TherapySessionFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $staff;
    protected $fisioterapis1;
    protected $fisioterapis2;
    protected $physioModel1;
    protected $physioModel2;
    protected $patient;
    protected $service;
    protected $appointment;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => RoleEnum::ADMIN]);
        $this->staff = User::factory()->create(['role' => RoleEnum::STAFF]);
        $this->fisioterapis1 = User::factory()->create(['role' => RoleEnum::FISIOTERAPIS]);
        $this->fisioterapis2 = User::factory()->create(['role' => RoleEnum::FISIOTERAPIS]);

        $this->physioModel1 = Physiotherapist::create([
            'name' => 'Fisio 1',
            'email' => $this->fisioterapis1->email,
            'phone' => '08111111111',
            'specialization' => 'Umum',
            'status' => 'active'
        ]);

        $this->physioModel2 = Physiotherapist::create([
            'name' => 'Fisio 2',
            'email' => $this->fisioterapis2->email,
            'phone' => '08222222222',
            'specialization' => 'Umum',
            'status' => 'active'
        ]);

        $patientCategory = \App\Models\PatientCategory::create(['name' => 'Umum']);
        $serviceCategory = \App\Models\ServiceCategory::create(['name' => 'Konsultasi']);

        $this->patient = Patient::create([
            'patient_category_id' => $patientCategory->id,
            'medical_record_number' => 'RM00001',
            'name' => 'Pasien A',
            'nik' => '1234567890123456',
            'birth_date' => '1990-01-01',
            'phone' => '08333333333',
            'address' => 'Jl. Test',
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

        $this->appointment = Appointment::create([
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physioModel1->id,
            'service_master_id' => $this->service->id,
            'appointment_date' => now()->toDateString(),
            'appointment_time' => '10:00:00',
            'status' => 'approved',
            'notes' => 'Catatan'
        ]);
    }

    // =====================================================
    // HELPER
    // =====================================================
    protected function makeSession(array $overrides = []): TherapySession
    {
        return TherapySession::create(array_merge([
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physioModel1->id,
            'appointment_id' => $this->appointment->id,
            'therapy_date' => now()->toDateString(),
            'status' => 'scheduled',
            'complaint' => 'Sakit punggung',
            'diagnosis' => 'Diagnosis awal',
            'treatment_given' => 'Terapi manual',
        ], $overrides));
    }

    protected function makeMedicalRecord(TherapySession $session): MedicalRecord
    {
        return MedicalRecord::create([
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physioModel1->id,
            'appointment_id' => $this->appointment->id,
            'therapy_session_id' => $session->id,
            'examination_date' => now()->toDateString(),
            'complaint' => 'Sakit',
            'anamnesis' => 'Anamnesis detail',
            'diagnosis' => 'Sakit punggung kronis',
            'physical_examination' => 'Normal',
            'therapy' => 'Terapi Fisik',
            'record_date' => now()->toDateString(),
            'subjective' => 'S',
            'objective' => 'O',
            'assessment' => 'A',
            'plan' => 'P'
        ]);
    }

    // =====================================================
    // INDEX
    // =====================================================

    /** @test */
    public function test_admin_can_get_all_therapy_sessions()
    {
        $this->makeSession();
        $this->makeSession(['complaint' => 'Sakit lutut', 'treatment_given' => 'Fisio']);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/v1/therapy-sessions');

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'data' => ['data']])
                 ->assertJsonPath('success', true);

        $this->assertCount(2, $response->json('data.data'));
    }

    /** @test */
    public function test_can_filter_sessions_by_patient_id()
    {
        $patCat2 = \App\Models\PatientCategory::create(['name' => 'BPJS']);
        $patient2 = Patient::create([
            'patient_category_id' => $patCat2->id,
            'medical_record_number' => 'RM00002',
            'name' => 'Pasien B',
            'nik' => '9999999999999999',
            'birth_date' => '1995-05-05',
            'phone' => '082',
            'address' => 'Jl. B',
            'status' => 'active'
        ]);

        $this->makeSession();
        TherapySession::create([
            'patient_id' => $patient2->id,
            'physiotherapist_id' => $this->physioModel1->id,
            'appointment_id' => $this->appointment->id,
            'therapy_date' => now()->toDateString(),
            'status' => 'scheduled',
            'complaint' => 'Lutut sakit',
            'diagnosis' => '-',
            'treatment_given' => '-',
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/v1/therapy-sessions?patient_id=' . $this->patient->id);

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data.data'));
        $this->assertEquals($this->patient->id, $response->json('data.data.0.patient_id'));
    }

    // =====================================================
    // STORE
    // =====================================================

    /** @test */
    public function test_admin_can_create_therapy_session()
    {
        $data = [
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physioModel1->id,
            'appointment_id' => $this->appointment->id,
            'therapy_date' => now()->toDateString(),
            'complaint' => 'Sakit punggung',
            'treatment_given' => 'Manual terapi',
            'status' => 'scheduled',
            'service_master_ids' => [$this->service->id],
        ];

        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/v1/therapy-sessions', $data);

        $response->assertStatus(201)
                 ->assertJsonPath('success', true)
                 ->assertJsonPath('data.complaint', 'Sakit punggung');

        $this->assertDatabaseHas('therapy_sessions', [
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physioModel1->id,
            'complaint' => 'Sakit punggung',
        ]);
    }

    /** @test */
    public function test_fisioterapis_can_create_therapy_session()
    {
        $data = [
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physioModel1->id,
            'appointment_id' => $this->appointment->id,
            'therapy_date' => now()->toDateString(),
            'complaint' => 'Nyeri bahu',
            'treatment_given' => 'Ultrasound terapi',
            'status' => 'scheduled',
        ];

        $response = $this->actingAs($this->fisioterapis1, 'sanctum')
            ->postJson('/api/v1/therapy-sessions', $data);

        $response->assertStatus(201);
    }

    /** @test */
    public function test_cannot_create_session_without_required_fields()
    {
        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/v1/therapy-sessions', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['patient_id', 'physiotherapist_id', 'therapy_date', 'complaint', 'treatment_given']);
    }

    /** @test */
    public function test_cannot_create_session_with_invalid_patient()
    {
        $data = [
            'patient_id' => 9999,
            'physiotherapist_id' => $this->physioModel1->id,
            'therapy_date' => now()->toDateString(),
            'complaint' => 'Test',
            'treatment_given' => 'Test',
        ];

        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/v1/therapy-sessions', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['patient_id']);
    }

    // =====================================================
    // SHOW
    // =====================================================

    /** @test */
    public function test_can_show_therapy_session()
    {
        $session = $this->makeSession();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson("/api/v1/therapy-sessions/{$session->id}");

        $response->assertStatus(200)
                 ->assertJsonPath('success', true)
                 ->assertJsonPath('data.id', $session->id)
                 ->assertJsonPath('data.complaint', 'Sakit punggung');
    }

    /** @test */
    public function test_show_returns_404_for_nonexistent_session()
    {
        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/v1/therapy-sessions/9999');

        $response->assertStatus(404)
                 ->assertJsonPath('success', false);
    }

    // =====================================================
    // UPDATE — Business Rules (existing tests)
    // =====================================================

    /** @test */
    public function test_fisioterapis_cannot_update_other_fisioterapis_session()
    {
        $session = $this->makeSession();

        $response = $this->actingAs($this->fisioterapis2, 'sanctum')
            ->putJson("/api/v1/therapy-sessions/{$session->id}", [
                'status' => 'completed',
                'complaint' => 'Sakit punggung',
                'treatment_given' => '-'
            ]);

        $response->assertStatus(403)
                 ->assertJsonPath('success', false);
    }

    /** @test */
    public function test_status_completed_cannot_revert_to_scheduled()
    {
        $session = $this->makeSession(['status' => 'completed']);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->putJson("/api/v1/therapy-sessions/{$session->id}", [
                'status' => 'scheduled',
                'complaint' => 'Sakit punggung',
                'treatment_given' => '-'
            ]);

        $response->assertStatus(400)
                 ->assertJsonPath('success', false);
    }

    /** @test */
    public function test_cannot_complete_session_without_medical_record()
    {
        $session = $this->makeSession([
            'status' => 'ongoing',
            'service_master_ids' => [$this->service->id]
        ]);

        $response = $this->actingAs($this->fisioterapis1, 'sanctum')
            ->putJson("/api/v1/therapy-sessions/{$session->id}", [
                'status' => 'completed',
                'complaint' => 'Sakit punggung',
                'treatment_given' => '-',
                'service_master_ids' => [$this->service->id]
            ]);

        $response->assertStatus(400)
                 ->assertJsonFragment([
                     'success' => false,
                     'message' => 'Tidak dapat menyelesaikan sesi: Data klinis (Rekam Medis) wajib diisi terlebih dahulu'
                 ]);
    }

    /** @test */
    public function test_can_complete_session_and_auto_generate_payment()
    {
        $session = $this->makeSession([
            'status' => 'ongoing',
            'service_master_ids' => [$this->service->id]
        ]);

        $this->makeMedicalRecord($session);

        $response = $this->actingAs($this->fisioterapis1, 'sanctum')
            ->putJson("/api/v1/therapy-sessions/{$session->id}", [
                'status' => 'completed',
                'complaint' => 'Sakit punggung',
                'treatment_given' => '-',
                'service_master_ids' => [$this->service->id]
            ]);

        $response->assertStatus(200);

        // Assert Payment auto-generated
        $this->assertDatabaseHas('payments', [
            'therapy_session_id' => $session->id,
            'patient_id' => $session->patient->id,
            'total' => $this->service->price,
            'status' => 'Pending'
        ]);

        // Assert Appointment status updated to completed
        $this->assertDatabaseHas('appointments', [
            'id' => $this->appointment->id,
            'status' => 'completed'
        ]);
    }

    /** @test */
    public function test_completing_session_does_not_duplicate_payment()
    {
        $session = $this->makeSession([
            'status' => 'ongoing',
            'service_master_ids' => [$this->service->id]
        ]);

        $this->makeMedicalRecord($session);

        // Create existing payment
        \App\Models\Payment::create([
            'therapy_session_id' => $session->id,
            'invoice_number' => 'INV-EXISTING',
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physioModel1->id,
            'payment_date' => now()->toDateString(),
            'payment_method' => 'Tunai',
            'status' => 'Pending',
            'subtotal' => 100000,
            'total' => 100000,
        ]);

        $this->actingAs($this->fisioterapis1, 'sanctum')
            ->putJson("/api/v1/therapy-sessions/{$session->id}", [
                'status' => 'completed',
                'complaint' => 'Sakit punggung',
                'treatment_given' => '-',
                'service_master_ids' => [$this->service->id]
            ]);

        // Hanya ada 1 payment, tidak duplikat
        $this->assertDatabaseCount('payments', 1);
    }

    // =====================================================
    // DESTROY
    // =====================================================

    /** @test */
    public function test_admin_can_delete_therapy_session()
    {
        $session = $this->makeSession();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->deleteJson("/api/v1/therapy-sessions/{$session->id}");

        $response->assertStatus(200)
                 ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('therapy_sessions', ['id' => $session->id]);
    }

    /** @test */
    public function test_delete_returns_404_for_nonexistent_session()
    {
        $response = $this->actingAs($this->admin, 'sanctum')
            ->deleteJson('/api/v1/therapy-sessions/9999');

        $response->assertStatus(404)
                 ->assertJsonPath('success', false);
    }

    // =====================================================
    // SCHEDULE
    // =====================================================

    /** @test */
    public function test_can_get_daily_schedule()
    {
        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/v1/therapy-sessions/schedule?date=' . now()->toDateString());

        $response->assertStatus(200)
                 ->assertJsonPath('success', true)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['physiotherapist', 'slots']
                     ]
                 ]);
    }

    /** @test */
    public function test_daily_schedule_shows_appointment_slot()
    {
        // Appointment sudah dibuat di setUp (jam 10:00)
        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/v1/therapy-sessions/schedule?date=' . now()->toDateString());

        $response->assertStatus(200);

        // Ada 1 physiotherapist di system
        $scheduleData = $response->json('data');
        $this->assertNotEmpty($scheduleData);

        // Cari slot jam 10:00 yang tidak kosong
        $slots = $scheduleData[0]['slots'];
        $slot10 = collect($slots)->firstWhere('time', '10:00');
        $this->assertNotNull($slot10);
        $this->assertFalse($slot10['is_empty']);
        $this->assertEquals($this->patient->name, $slot10['data']['patient_name']);
    }

    /** @test */
    public function test_can_get_weekly_schedule()
    {
        $startDate = now()->startOfWeek()->toDateString();
        $endDate = now()->endOfWeek()->toDateString();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson("/api/v1/therapy-sessions/schedule/weekly?start_date={$startDate}&end_date={$endDate}");

        $response->assertStatus(200)
                 ->assertJsonPath('success', true)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['physiotherapist', 'schedule']
                     ]
                 ]);
    }

    /** @test */
    public function test_weekly_schedule_uses_default_dates_when_not_provided()
    {
        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/v1/therapy-sessions/schedule/weekly');

        $response->assertStatus(200)
                 ->assertJsonPath('success', true);
    }
}
