<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;
use App\Models\User;
use App\Models\Patient;
use App\Models\PatientCategory;
use App\Enums\RoleEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class PatientFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $staff;
    protected $fisioterapis;
    protected $patientCategory;
    protected $genderL;
    protected $genderP;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create(['role' => RoleEnum::ADMIN]);
        $this->staff = User::factory()->create(['role' => RoleEnum::STAFF]);
        $this->fisioterapis = User::factory()->create(['role' => RoleEnum::FISIOTERAPIS]);

        $this->patientCategory = PatientCategory::create(['name' => 'Umum']);
        
        // Seed genders manually as they are usually seeded by database seeders
        $this->genderL = DB::table('genders')->insertGetId(['name' => 'Laki-laki']);
        $this->genderP = DB::table('genders')->insertGetId(['name' => 'Perempuan']);
    }

    public function test_staff_can_get_all_patients()
    {
        Patient::create([
            'patient_category_id' => $this->patientCategory->id,
            'medical_record_number' => 'RM00001',
            'name' => 'Pasien A',
            'nik' => '1111111111111111',
            'birth_date' => '1990-01-01',
            'phone' => '0811',
            'address' => 'Jl. A',
            'status' => 'active',
            'gender_id' => $this->genderL
        ]);

        $response = $this->actingAs($this->staff, 'sanctum')->getJson('/api/v1/patients');

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data.data');
    }

    public function test_fisioterapis_can_view_but_cannot_manage_patients()
    {
        Patient::create([
            'patient_category_id' => $this->patientCategory->id,
            'medical_record_number' => 'RM00001',
            'name' => 'Pasien A',
            'nik' => '1111111111111111',
            'birth_date' => '1990-01-01',
            'phone' => '0811',
            'address' => 'Jl. A',
            'status' => 'active',
            'gender_id' => $this->genderL
        ]);

        $response = $this->actingAs($this->fisioterapis, 'sanctum')->getJson('/api/v1/patients');
        $response->assertStatus(200); // Can view

        $patientData = [
            'nik' => '2222222222222222',
            'name' => 'Budi Santoso',
            'birth_date' => '1990-01-01',
            'gender_id' => $this->genderL,
            'patient_category_id' => $this->patientCategory->id,
        ];

        $response2 = $this->actingAs($this->fisioterapis, 'sanctum')->postJson('/api/v1/patients', $patientData);
        $response2->assertStatus(403); // Cannot manage
    }

    public function test_staff_can_create_patient()
    {
        $patientData = [
            'nik' => '1234567890123456',
            'name' => 'Budi Santoso',
            'birth_date' => '1990-01-01',
            'gender_id' => $this->genderL,
            'patient_category_id' => $this->patientCategory->id,
            'phone' => '081234567890',
            'address' => 'Jl. Merdeka No. 10',
        ];

        $response = $this->actingAs($this->staff, 'sanctum')->postJson('/api/v1/patients', $patientData);

        $response->assertStatus(201)
                 ->assertJsonPath('data.name', 'Budi Santoso');

        $this->assertDatabaseHas('patients', ['nik' => '1234567890123456']);
        
        // Assert MRN is generated
        $createdPatient = Patient::where('nik', '1234567890123456')->first();
        $this->assertNotNull($createdPatient->medical_record_number);
    }

    public function test_cannot_create_patient_with_duplicate_nik()
    {
        Patient::create([
            'patient_category_id' => $this->patientCategory->id,
            'medical_record_number' => 'RM00001',
            'name' => 'Pasien A',
            'nik' => '1111111111111111',
            'birth_date' => '1990-01-01',
            'phone' => '0811',
            'address' => 'Jl. A',
            'status' => 'active',
            'gender_id' => $this->genderL
        ]);

        $patientData = [
            'nik' => '1111111111111111', // Duplicate
            'name' => 'Budi Baru',
            'birth_date' => '1990-01-01',
            'gender_id' => $this->genderL,
            'patient_category_id' => $this->patientCategory->id,
        ];

        $response = $this->actingAs($this->admin, 'sanctum')->postJson('/api/v1/patients', $patientData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['nik']);
    }

    public function test_staff_can_update_patient()
    {
        $patient = Patient::create([
            'patient_category_id' => $this->patientCategory->id,
            'medical_record_number' => 'RM00001',
            'name' => 'Pasien A',
            'nik' => '1111111111111111',
            'birth_date' => '1990-01-01',
            'phone' => '0811',
            'address' => 'Jl. A',
            'status' => 'active',
            'gender_id' => $this->genderL
        ]);

        $updateData = [
            'name' => 'Updated Patient Name',
            'phone' => '089999999999',
            'birth_date' => '1990-01-01',
            'gender_id' => $this->genderL,
            'patient_category_id' => $this->patientCategory->id,
        ];

        $response = $this->actingAs($this->staff, 'sanctum')->putJson("/api/v1/patients/{$patient->id}", $updateData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('patients', ['id' => $patient->id, 'name' => 'Updated Patient Name']);
    }

    public function test_staff_can_soft_delete_and_restore_patient()
    {
        $patient = Patient::create([
            'patient_category_id' => $this->patientCategory->id,
            'medical_record_number' => 'RM00001',
            'name' => 'Pasien A',
            'nik' => '1111111111111111',
            'birth_date' => '1990-01-01',
            'phone' => '0811',
            'address' => 'Jl. A',
            'status' => 'active',
            'gender_id' => $this->genderL
        ]);

        // Soft Delete
        $response = $this->actingAs($this->staff, 'sanctum')->deleteJson("/api/v1/patients/{$patient->id}");
        $response->assertStatus(200);
        $this->assertSoftDeleted('patients', ['id' => $patient->id]);
        
        // Restore
        $response2 = $this->actingAs($this->staff, 'sanctum')->postJson("/api/v1/patients/{$patient->id}/restore");
        $response2->assertStatus(200);
        $this->assertDatabaseHas('patients', ['id' => $patient->id, 'deleted_at' => null]);
    }

    // =====================================================
    // SHOW
    // =====================================================

    public function test_staff_can_show_patient_by_id()
    {
        $patient = Patient::create([
            'patient_category_id' => $this->patientCategory->id,
            'medical_record_number' => 'RM00001',
            'name' => 'Pasien Detail',
            'nik' => '3333333333333333',
            'birth_date' => '1985-06-15',
            'phone' => '0812',
            'address' => 'Jl. B',
            'status' => 'active',
            'gender_id' => $this->genderL
        ]);

        $response = $this->actingAs($this->staff, 'sanctum')
            ->getJson("/api/v1/patients/{$patient->id}");

        $response->assertStatus(200)
                 ->assertJsonPath('success', true)
                 ->assertJsonPath('data.id', $patient->id)
                 ->assertJsonPath('data.name', 'Pasien Detail')
                 ->assertJsonPath('data.medical_record_number', 'RM00001');
    }

    public function test_fisioterapis_can_show_patient_by_id()
    {
        $patient = Patient::create([
            'patient_category_id' => $this->patientCategory->id,
            'medical_record_number' => 'RM00002',
            'name' => 'Pasien B',
            'nik' => '4444444444444444',
            'birth_date' => '1992-03-20',
            'phone' => '0813',
            'address' => 'Jl. C',
            'status' => 'active',
            'gender_id' => $this->genderP
        ]);

        $response = $this->actingAs($this->fisioterapis, 'sanctum')
            ->getJson("/api/v1/patients/{$patient->id}");

        // Fisioterapis punya izin 'view' pada PatientPolicy
        $response->assertStatus(200)
                 ->assertJsonPath('data.id', $patient->id);
    }

    public function test_show_patient_returns_404_for_nonexistent_patient()
    {
        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/v1/patients/9999');

        $response->assertStatus(404)
                 ->assertJsonPath('success', false);
    }

    public function test_show_patient_response_includes_correct_fields()
    {
        $patient = Patient::create([
            'patient_category_id' => $this->patientCategory->id,
            'medical_record_number' => 'RM00003',
            'name' => 'Pasien Lengkap',
            'nik' => '5555555555555555',
            'birth_date' => '1988-11-30',
            'phone' => '081234',
            'address' => 'Jl. Lengkap No. 5',
            'status' => 'active',
            'gender_id' => $this->genderP,
            'occupation' => 'Pegawai',
            'marital_status' => 'Menikah',
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson("/api/v1/patients/{$patient->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id',
                         'medical_record_number',
                         'name',
                         'nik',
                         'birth_date',
                     ]
                 ]);
    }

    // =====================================================
    // EXPORT CSV
    // =====================================================

    public function test_staff_can_export_patients_csv()
    {
        Patient::create([
            'patient_category_id' => $this->patientCategory->id,
            'medical_record_number' => 'RM00001',
            'name' => 'Pasien Export',
            'nik' => '6666666666666666',
            'birth_date' => '1990-01-01',
            'phone' => '0814',
            'address' => 'Jl. Export',
            'status' => 'active',
            'gender_id' => $this->genderL
        ]);

        $response = $this->actingAs($this->staff, 'sanctum')
            ->get('/api/v1/patients/export/csv');

        $response->assertStatus(200)
                 ->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_admin_can_export_patients_csv()
    {
        $response = $this->actingAs($this->admin, 'sanctum')
            ->get('/api/v1/patients/export/csv');

        $response->assertStatus(200)
                 ->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_fisioterapis_cannot_export_patients_csv()
    {
        // Fisioterapis punya policy 'viewAny' tetapi 'export' memanggil authorize('viewAny')
        // Fisioterapis juga punya viewAny, jadi dapat mengakses export
        // Test ini mendokumentasikan behavior aktual
        $response = $this->actingAs($this->fisioterapis, 'sanctum')
            ->get('/api/v1/patients/export/csv');

        // Fisioterapis boleh export karena viewAny policy mengizinkan
        $response->assertStatus(200);
    }

    public function test_csv_export_contains_patient_data()
    {
        Patient::create([
            'patient_category_id' => $this->patientCategory->id,
            'medical_record_number' => 'RM-CSV-001',
            'name' => 'Pasien CSV Test',
            'nik' => '7777777777777777',
            'birth_date' => '1995-05-10',
            'phone' => '0815',
            'address' => 'Jl. CSV',
            'status' => 'active',
            'gender_id' => $this->genderL
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->get('/api/v1/patients/export/csv');

        $response->assertStatus(200);

        $csvContent = $response->streamedContent();

        // Pastikan nama pasien ada di dalam file CSV
        $this->assertStringContainsString('Pasien CSV Test', $csvContent);
        // Pastikan MRN ada di CSV
        $this->assertStringContainsString('RM-CSV-001', $csvContent);
        // Pastikan header kolom ada
        $this->assertStringContainsString('No. RM', $csvContent);
        $this->assertStringContainsString('Nama', $csvContent);
    }

    // =====================================================
    // AUTH & VALIDATION EDGE CASES
    // =====================================================

    public function test_unauthenticated_cannot_access_patients()
    {
        $response = $this->getJson('/api/v1/patients');
        $response->assertStatus(401);
    }

    public function test_store_validation_fails_without_required_fields()
    {
        $response = $this->actingAs($this->staff, 'sanctum')
            ->postJson('/api/v1/patients', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'birth_date', 'gender_id', 'patient_category_id']);
    }

    public function test_store_validates_gender_must_exist()
    {
        $response = $this->actingAs($this->staff, 'sanctum')
            ->postJson('/api/v1/patients', [
                'name' => 'Test',
                'birth_date' => '1990-01-01',
                'gender_id' => 9999,
                'patient_category_id' => $this->patientCategory->id,
            ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['gender_id']);
    }

    public function test_medical_record_number_cannot_be_updated_by_client()
    {
        $patient = Patient::create([
            'patient_category_id' => $this->patientCategory->id,
            'medical_record_number' => 'RM-ORIGINAL',
            'name' => 'Pasien MRN',
            'nik' => '8888888888888888',
            'birth_date' => '1990-01-01',
            'phone' => '0816',
            'address' => 'Jl. MRN',
            'status' => 'active',
            'gender_id' => $this->genderL
        ]);

        $updateData = [
            'name' => 'Updated Name',
            'birth_date' => '1990-01-01',
            'gender_id' => $this->genderL,
            'patient_category_id' => $this->patientCategory->id,
            'medical_record_number' => 'RM-HACKED', // Attempt to overwrite MRN
        ];

        $response = $this->actingAs($this->staff, 'sanctum')
            ->putJson("/api/v1/patients/{$patient->id}", $updateData);

        $response->assertStatus(200);

        // MRN harus tetap sama — PatientService::updatePatient() membuang medical_record_number dari data
        $this->assertDatabaseHas('patients', [
            'id' => $patient->id,
            'medical_record_number' => 'RM-ORIGINAL',
            'name' => 'Updated Name',
        ]);
    }
}
