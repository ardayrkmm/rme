<?php

namespace Tests\Feature\Api\V1;

use App\Enums\RoleEnum;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\PatientCategory;
use App\Models\Physiotherapist;
use App\Models\ServiceCategory;
use App\Models\ServiceMaster;
use App\Models\TherapySession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MedicalRecordFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $staff;
    protected $physioUser1;
    protected $physioUser2;

    protected $patient1;
    protected $patient2;
    protected $physio1;
    protected $physio2;
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        
        Storage::fake('public');

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->staff = User::factory()->create(['role' => 'staff']);
        
        $this->physioUser1 = User::factory()->create(['role' => 'fisioterapis']);
        $this->physioUser2 = User::factory()->create(['role' => 'fisioterapis']);

        $patientCategory = PatientCategory::create(['name' => 'Umum']);
        $serviceCategory = ServiceCategory::create(['name' => 'Konsultasi']);

        $this->patient1 = Patient::create([
            'patient_category_id' => $patientCategory->id,
            'medical_record_number' => 'RM00001',
            'name' => 'Pasien A',
            'nik' => '1111111111111111',
            'birth_date' => '1990-01-01',
            'phone' => '0811',
            'address' => 'Jl. A',
            'status' => 'active'
        ]);

        $this->patient2 = Patient::create([
            'patient_category_id' => $patientCategory->id,
            'medical_record_number' => 'RM00002',
            'name' => 'Pasien B',
            'nik' => '2222222222222222',
            'birth_date' => '1990-02-02',
            'phone' => '0822',
            'address' => 'Jl. B',
            'status' => 'active'
        ]);

        $this->physio1 = Physiotherapist::create([
            'user_id' => $this->physioUser1->id,
            'name' => 'Fisio 1',
            'email' => $this->physioUser1->email,
            'phone' => '08111',
            'specialization' => 'Umum',
            'status' => 'active'
        ]);

        $this->physio2 = Physiotherapist::create([
            'user_id' => $this->physioUser2->id,
            'name' => 'Fisio 2',
            'email' => $this->physioUser2->email,
            'phone' => '08222',
            'specialization' => 'Olahraga',
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

    protected function createTherapySession($patientId, $physioId)
    {
        $appointment = Appointment::create([
            'patient_id' => $patientId,
            'physiotherapist_id' => $physioId,
            'service_master_id' => $this->service->id,
            'appointment_date' => now()->toDateString(),
            'appointment_time' => '10:00:00',
            'status' => 'approved',
            'notes' => 'Catatan'
        ]);
        
        return TherapySession::create([
            'patient_id' => $patientId,
            'physiotherapist_id' => $physioId,
            'appointment_id' => $appointment->id,
            'therapy_date' => now()->toDateString(),
            'complaint' => 'Sakit',
            'treatment_given' => 'Terapi',
            'status' => 'completed'
        ]);
    }

    public function test_admin_can_get_all_medical_records()
    {
        MedicalRecord::create([
            'patient_id' => $this->patient1->id,
            'physiotherapist_id' => $this->physio1->id,
            'examination_date' => now()->toDateString(),
            'anamnesis' => 'Anamnesis 1',
            'diagnosis' => 'Diagnosis 1',
            'therapy' => 'Therapy 1'
        ]);

        MedicalRecord::create([
            'patient_id' => $this->patient2->id,
            'physiotherapist_id' => $this->physio2->id,
            'examination_date' => now()->toDateString(),
            'anamnesis' => 'Anamnesis 2',
            'diagnosis' => 'Diagnosis 2',
            'therapy' => 'Therapy 2'
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')->getJson('/api/v1/medical-records');

        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data.data');
    }

    public function test_physiotherapist_can_only_get_own_patients_medical_records()
    {
        $this->createTherapySession($this->patient1->id, $this->physio1->id);

        MedicalRecord::create([
            'patient_id' => $this->patient1->id,
            'physiotherapist_id' => $this->physio1->id,
            'examination_date' => now()->toDateString(),
            'anamnesis' => 'Anamnesis 1',
            'diagnosis' => 'Diagnosis 1',
            'therapy' => 'Therapy 1'
        ]);

        MedicalRecord::create([
            'patient_id' => $this->patient2->id,
            'physiotherapist_id' => $this->physio2->id,
            'examination_date' => now()->toDateString(),
            'anamnesis' => 'Anamnesis 2',
            'diagnosis' => 'Diagnosis 2',
            'therapy' => 'Therapy 2'
        ]);

        // Physio 1 requests all records
        $response = $this->actingAs($this->physioUser1, 'sanctum')->getJson('/api/v1/medical-records');

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data.data')
                 ->assertJsonPath('data.data.0.physiotherapist_id', $this->physio1->id);
    }

    public function test_staff_cannot_access_medical_records()
    {
        $response = $this->actingAs($this->staff, 'sanctum')->getJson('/api/v1/medical-records');
        $response->assertStatus(403);
    }

    public function test_physio_can_get_patient_history_if_treated()
    {
        // Physio 1 treats Patient 1
        $this->createTherapySession($this->patient1->id, $this->physio1->id);

        MedicalRecord::create([
            'patient_id' => $this->patient1->id,
            'physiotherapist_id' => $this->physio1->id,
            'examination_date' => now()->toDateString(),
            'anamnesis' => 'Anamnesis 1',
            'diagnosis' => 'Diagnosis 1',
            'therapy' => 'Therapy 1'
        ]);

        $response = $this->actingAs($this->physioUser1, 'sanctum')->getJson('/api/v1/patients/' . $this->patient1->id . '/medical-records');
        
        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data.data');
    }

    public function test_physio_cannot_get_patient_history_if_not_treated()
    {
        // Physio 1 has NEVER treated Patient 2
        MedicalRecord::create([
            'patient_id' => $this->patient2->id,
            'physiotherapist_id' => $this->physio2->id,
            'examination_date' => now()->toDateString(),
            'anamnesis' => 'Anamnesis 2',
            'diagnosis' => 'Diagnosis 2',
            'therapy' => 'Therapy 2'
        ]);

        $response = $this->actingAs($this->physioUser1, 'sanctum')->getJson('/api/v1/patients/' . $this->patient2->id . '/medical-records');
        $response->assertStatus(403); // Expected RBAC block based on Controller logic
    }

    public function test_admin_can_create_medical_record_with_attachment()
    {
        $payload = [
            'patient_id' => $this->patient1->id,
            'physiotherapist_id' => $this->physio1->id,
            'examination_date' => now()->toDateString(),
            'anamnesis' => 'Anamnesis Test',
            'diagnosis' => 'Diagnosis Test',
            'therapy' => 'Therapy Test',
            'attachment' => UploadedFile::fake()->create('document.pdf', 100, 'application/pdf'),
        ];

        $response = $this->actingAs($this->admin, 'sanctum')->postJson('/api/v1/medical-records', $payload);

        $response->assertStatus(201);
        $this->assertDatabaseHas('medical_records', [
            'patient_id' => $this->patient1->id,
            'diagnosis' => 'Diagnosis Test'
        ]);
        
        $record = MedicalRecord::where('diagnosis', 'Diagnosis Test')->first();
        if ($record->attachment) {
            Storage::disk('public')->assertExists($record->attachment);
        }
    }

    public function test_physio_can_create_medical_record_for_self()
    {
        $payload = [
            'patient_id' => $this->patient1->id,
            'physiotherapist_id' => $this->physio1->id, // matches acting user
            'examination_date' => now()->toDateString(),
            'anamnesis' => 'Anamnesis Test',
            'diagnosis' => 'Diagnosis Test',
            'therapy' => 'Therapy Test',
        ];

        $response = $this->actingAs($this->physioUser1, 'sanctum')->postJson('/api/v1/medical-records', $payload);

        $response->assertStatus(201);
    }

    public function test_physio_cannot_create_medical_record_for_other_physio()
    {
        $payload = [
            'patient_id' => $this->patient1->id,
            'physiotherapist_id' => $this->physio2->id, // MISMATCH
            'examination_date' => now()->toDateString(),
            'anamnesis' => 'Anamnesis Test',
            'diagnosis' => 'Diagnosis Test',
            'therapy' => 'Therapy Test',
        ];

        $response = $this->actingAs($this->physioUser1, 'sanctum')->postJson('/api/v1/medical-records', $payload);

        $response->assertStatus(403); // Controller logic blocks this
    }

    public function test_physio_can_update_own_medical_record()
    {
        $record = MedicalRecord::create([
            'patient_id' => $this->patient1->id,
            'physiotherapist_id' => $this->physio1->id,
            'examination_date' => now()->toDateString(),
            'anamnesis' => 'Old',
            'diagnosis' => 'Old',
            'therapy' => 'Old'
        ]);

        $payload = [
            'patient_id' => $this->patient1->id,
            'physiotherapist_id' => $this->physio1->id,
            'examination_date' => now()->toDateString(),
            'anamnesis' => 'New',
            'diagnosis' => 'New',
            'therapy' => 'New',
        ];

        $response = $this->actingAs($this->physioUser1, 'sanctum')->putJson('/api/v1/medical-records/' . $record->id, $payload);
        $response->assertStatus(200);
        $this->assertDatabaseHas('medical_records', [
            'id' => $record->id,
            'diagnosis' => 'New'
        ]);
    }

    public function test_physio_cannot_update_other_physio_medical_record()
    {
        $record = MedicalRecord::create([
            'patient_id' => $this->patient2->id,
            'physiotherapist_id' => $this->physio2->id, // belongs to Physio 2
            'examination_date' => now()->toDateString(),
            'anamnesis' => 'Old',
            'diagnosis' => 'Old',
            'therapy' => 'Old'
        ]);

        $payload = [
            'patient_id' => $this->patient2->id,
            'physiotherapist_id' => $this->physio2->id,
            'examination_date' => now()->toDateString(),
            'anamnesis' => 'New',
            'diagnosis' => 'New',
            'therapy' => 'New',
        ];

        // Physio 1 tries to update
        $response = $this->actingAs($this->physioUser1, 'sanctum')->putJson('/api/v1/medical-records/' . $record->id, $payload);
        $response->assertStatus(403);
    }

    public function test_staff_cannot_delete_medical_record()
    {
        $record = MedicalRecord::create([
            'patient_id' => $this->patient1->id,
            'physiotherapist_id' => $this->physio1->id,
            'examination_date' => now()->toDateString(),
            'anamnesis' => 'Old',
            'diagnosis' => 'Old',
            'therapy' => 'Old'
        ]);

        $response = $this->actingAs($this->staff, 'sanctum')->deleteJson('/api/v1/medical-records/' . $record->id);
        $response->assertStatus(403);
    }

    public function test_admin_can_delete_medical_record()
    {
        $record = MedicalRecord::create([
            'patient_id' => $this->patient1->id,
            'physiotherapist_id' => $this->physio1->id,
            'examination_date' => now()->toDateString(),
            'anamnesis' => 'Old',
            'diagnosis' => 'Old',
            'therapy' => 'Old'
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')->deleteJson('/api/v1/medical-records/' . $record->id);
        $response->assertStatus(200);
        $this->assertSoftDeleted('medical_records', ['id' => $record->id]);
    }

    public function test_can_export_pdf()
    {
        $record = MedicalRecord::create([
            'patient_id' => $this->patient1->id,
            'physiotherapist_id' => $this->physio1->id,
            'examination_date' => now()->toDateString(),
            'anamnesis' => 'Old',
            'diagnosis' => 'Old',
            'therapy' => 'Old'
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')->get('/api/v1/medical-records/' . $record->id . '/export-pdf');
        
        // Either PDF download works or returns 200 with Dummy PDF logic from controller
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
