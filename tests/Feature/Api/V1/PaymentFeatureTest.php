<?php

namespace Tests\Feature\Api\V1;

use App\Models\Patient;
use App\Models\PatientCategory;
use App\Models\Physiotherapist;
use App\Models\Payment;
use App\Models\PaymentDetail;
use App\Models\ServiceCategory;
use App\Models\ServiceMaster;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $staff;
    protected $patient;
    protected $physio;
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->staff = User::factory()->create(['role' => 'staff']);

        // Setup base data
        $patientCategory = PatientCategory::create(['name' => 'Umum']);
        $serviceCategory = ServiceCategory::create(['name' => 'Konsultasi']);

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

        $this->physio = Physiotherapist::create([
            'user_id' => $this->staff->id,
            'name' => 'Fisio 1',
            'email' => $this->staff->email,
            'phone' => '08111111111',
            'specialization' => 'Umum',
            'status' => 'active'
        ]);

        $this->service = ServiceMaster::create([
            'service_category_id' => $serviceCategory->id,
            'category' => 'Konsultasi', // Added missing field
            'code' => 'SVC001',
            'name' => 'Terapi Fisik',
            'price' => 100000,
            'duration' => 60,
            'description' => 'Terapi',
            'is_active' => true
        ]);
    }

    protected function createTherapySession()
    {
        $appointment = \App\Models\Appointment::create([
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physio->id,
            'service_master_id' => $this->service->id,
            'appointment_date' => now()->toDateString(),
            'appointment_time' => '10:00:00',
            'status' => 'approved',
            'notes' => 'Catatan'
        ]);
        
        return \App\Models\TherapySession::create([
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physio->id,
            'appointment_id' => $appointment->id,
            'therapy_date' => now()->toDateString(),
            'complaint' => 'Sakit pinggang',
            'treatment_given' => 'Terapi fisik',
            'status' => 'scheduled'
        ]);
    }

    public function test_can_get_all_payments()
    {
        $session = $this->createTherapySession();
        Payment::create([
            'therapy_session_id' => $session->id,
            'invoice_number' => 'INV-001',
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physio->id,
            'payment_date' => now()->toDateString(),
            'payment_method' => 'Tunai',
            'status' => 'Pending',
            'subtotal' => 100000,
            'total' => 100000,
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')->getJson('/api/v1/payments');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         'data' => [
                             '*' => ['id', 'invoice_number', 'status']
                         ]
                     ]
                 ]);
    }

    public function test_can_create_payment_with_valid_data()
    {
        $session = $this->createTherapySession();
        
        $payload = [
            'therapy_session_id' => $session->id,
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physio->id,
            'payment_date' => now()->toDateString(),
            'payment_method' => 'Tunai',
            'status' => 'Pending',
            'discount' => 0,
            'tax' => 0,
            'details' => [
                [
                    'service_master_id' => $this->service->id,
                    'quantity' => 1,
                    'price' => 100000,
                ]
            ]
        ];

        $response = $this->actingAs($this->admin, 'sanctum')->postJson('/api/v1/payments', $payload);

        $response->assertStatus(201)
                 ->assertJsonPath('data.status', 'Pending');

        $this->assertDatabaseHas('payments', [
            'patient_id' => $this->patient->id,
        ]);

        $this->assertDatabaseHas('payment_details', [
            'service_master_id' => $this->service->id,
            'quantity' => 1,
        ]);
    }

    public function test_cannot_create_payment_with_invalid_data()
    {
        $payload = [
            'payment_method' => 'Tunai',
        ];

        $response = $this->actingAs($this->admin, 'sanctum')->postJson('/api/v1/payments', $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['therapy_session_id', 'patient_id']);
    }

    public function test_can_update_payment_status()
    {
        $session = $this->createTherapySession();
        $payment = Payment::create([
            'therapy_session_id' => $session->id,
            'invoice_number' => 'INV-001',
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physio->id,
            'payment_date' => now()->toDateString(),
            'payment_method' => 'Tunai',
            'status' => 'Pending',
            'subtotal' => 100000,
            'total' => 100000,
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')->putJson('/api/v1/payments/' . $payment->id, [
            'status' => 'Lunas'
        ]);

        $response->assertStatus(200);
        
        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'Lunas'
        ]);
    }

    public function test_staff_cannot_delete_payment()
    {
        $session = $this->createTherapySession();
        $payment = Payment::create([
            'therapy_session_id' => $session->id,
            'invoice_number' => 'INV-001',
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physio->id,
            'payment_date' => now()->toDateString(),
            'payment_method' => 'Tunai',
            'status' => 'Pending',
            'subtotal' => 100000,
            'total' => 100000,
        ]);

        $response = $this->actingAs($this->staff, 'sanctum')->deleteJson('/api/v1/payments/' . $payment->id);
        
        // Asserting 403. If it fails (meaning no authorization is actually enforced in code), 
        // we'll know the project is missing the implementation, but we assert what SHOULD happen.
        $response->assertStatus(403);
    }
    
    public function test_admin_can_delete_payment()
    {
        $session = $this->createTherapySession();
        $payment = Payment::create([
            'therapy_session_id' => $session->id,
            'invoice_number' => 'INV-001',
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physio->id,
            'payment_date' => now()->toDateString(),
            'payment_method' => 'Tunai',
            'status' => 'Pending',
            'subtotal' => 100000,
            'total' => 100000,
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')->deleteJson('/api/v1/payments/' . $payment->id);
        $response->assertStatus(200);
        $this->assertSoftDeleted('payments', ['id' => $payment->id]);
    }

    public function test_can_export_csv()
    {
        $response = $this->actingAs($this->admin, 'sanctum')->get('/api/v1/payments/export/csv');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_can_download_pdf()
    {
        $session = $this->createTherapySession();
        $payment = Payment::create([
            'therapy_session_id' => $session->id,
            'invoice_number' => 'INV-001',
            'patient_id' => $this->patient->id,
            'physiotherapist_id' => $this->physio->id,
            'payment_date' => now()->toDateString(),
            'payment_method' => 'Tunai',
            'status' => 'Pending',
            'subtotal' => 100000,
            'total' => 100000,
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')->get("/api/v1/payments/{$payment->id}/pdf/download");
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    // =====================================================
    // HELPER
    // =====================================================

    protected function createPayment(array $overrides = []): Payment
    {
        $session = $this->createTherapySession();
        return Payment::create(array_merge([
            'therapy_session_id' => $session->id,
            'invoice_number'     => 'INV-' . uniqid(),
            'patient_id'         => $this->patient->id,
            'physiotherapist_id' => $this->physio->id,
            'payment_date'       => now()->toDateString(),
            'payment_method'     => 'Tunai',
            'status'             => 'Pending',
            'subtotal'           => 100000,
            'total'              => 100000,
            'discount'           => 0,
            'tax'                => 0,
        ], $overrides));
    }

    // =====================================================
    // SHOW
    // =====================================================

    public function test_can_show_payment_by_id()
    {
        $payment = $this->createPayment(['status' => 'Lunas']);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson("/api/v1/payments/{$payment->id}");

        $response->assertStatus(200)
                 ->assertJsonPath('success', true)
                 ->assertJsonPath('data.id', $payment->id)
                 ->assertJsonPath('data.status', 'Lunas');
    }

    public function test_show_payment_returns_404_for_nonexistent_payment()
    {
        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/v1/payments/9999');

        $response->assertStatus(404)
                 ->assertJsonPath('success', false);
    }

    public function test_show_payment_response_includes_correct_structure()
    {
        $payment = $this->createPayment();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson("/api/v1/payments/{$payment->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id',
                         'invoice_number',
                         'status',
                         'payment_method',
                         'total',
                     ]
                 ]);
    }

    // =====================================================
    // SHARE LINK
    // =====================================================

    public function test_can_generate_share_link_for_payment()
    {
        $payment = $this->createPayment();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson("/api/v1/payments/{$payment->id}/share-link");

        $response->assertStatus(200)
                 ->assertJsonPath('success', true)
                 ->assertJsonStructure([
                     'data' => ['share_url']
                 ]);

        // URL yang dihasilkan harus mengandung route yang benar
        $shareUrl = $response->json('data.share_url');
        $this->assertStringContainsString('invoice', $shareUrl);
    }

    public function test_share_link_returns_error_for_nonexistent_payment()
    {
        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/v1/payments/9999/share-link');

        $response->assertStatus(500)
                 ->assertJsonPath('success', false);
    }

    public function test_share_link_is_signed_url()
    {
        $payment = $this->createPayment();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson("/api/v1/payments/{$payment->id}/share-link");

        $shareUrl = $response->json('data.share_url');

        // Signed URL harus mengandung signature parameter
        $this->assertStringContainsString('signature=', $shareUrl);
    }

    // =====================================================
    // PDF PREVIEW
    // =====================================================

    public function test_can_preview_invoice_pdf()
    {
        $payment = $this->createPayment();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->get("/api/v1/payments/{$payment->id}/pdf/preview");

        $response->assertStatus(200)
                 ->assertHeader('Content-Type', 'application/pdf');
    }

    // =====================================================
    // RECEIPT
    // =====================================================

    public function test_can_preview_receipt_pdf()
    {
        $payment = $this->createPayment(['status' => 'Lunas']);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->get("/api/v1/payments/{$payment->id}/pdf/receipt/preview");

        $response->assertStatus(200)
                 ->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_can_download_receipt_pdf()
    {
        $payment = $this->createPayment(['status' => 'Lunas']);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->get("/api/v1/payments/{$payment->id}/pdf/receipt/download");

        $response->assertStatus(200)
                 ->assertHeader('Content-Type', 'application/pdf');
    }

    // =====================================================
    // EXPORT LIST PDF
    // =====================================================

    public function test_can_export_payments_list_pdf()
    {
        $this->createPayment();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->get('/api/v1/payments/export/pdf');

        $response->assertStatus(200)
                 ->assertHeader('Content-Type', 'application/pdf');
    }

    // =====================================================
    // VALIDATION
    // =====================================================

    public function test_store_validation_fails_with_invalid_payment_method()
    {
        $session = $this->createTherapySession();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/v1/payments', [
                'therapy_session_id' => $session->id,
                'patient_id'         => $this->patient->id,
                'physiotherapist_id' => $this->physio->id,
                'payment_date'       => now()->toDateString(),
                'payment_method'     => 'GoPay',
                'status'             => 'Pending',
                'details'            => [
                    ['service_master_id' => $this->service->id, 'quantity' => 1, 'price' => 100000]
                ]
            ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['payment_method']);
    }

    public function test_store_validation_fails_with_invalid_status()
    {
        $session = $this->createTherapySession();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/v1/payments', [
                'therapy_session_id' => $session->id,
                'patient_id'         => $this->patient->id,
                'physiotherapist_id' => $this->physio->id,
                'payment_date'       => now()->toDateString(),
                'payment_method'     => 'Tunai',
                'status'             => 'InvalidStatus',
                'details'            => [
                    ['service_master_id' => $this->service->id, 'quantity' => 1, 'price' => 100000]
                ]
            ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['status']);
    }

    public function test_store_validation_fails_without_details()
    {
        $session = $this->createTherapySession();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/v1/payments', [
                'therapy_session_id' => $session->id,
                'patient_id'         => $this->patient->id,
                'physiotherapist_id' => $this->physio->id,
                'payment_date'       => now()->toDateString(),
                'payment_method'     => 'Tunai',
                'status'             => 'Pending',
            ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['details']);
    }

    public function test_update_validation_fails_with_invalid_status()
    {
        $payment = $this->createPayment();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->putJson("/api/v1/payments/{$payment->id}", ['status' => 'StatusSalah']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['status']);
    }

    // =====================================================
    // UNAUTHENTICATED
    // =====================================================

    public function test_unauthenticated_cannot_access_payments()
    {
        $response = $this->getJson('/api/v1/payments');
        $response->assertStatus(401);
    }

    // =====================================================
    // OWNER RBAC
    // =====================================================

    public function test_owner_can_delete_payment()
    {
        $owner = \App\Models\User::factory()->create(['role' => \App\Enums\RoleEnum::OWNER]);
        $payment = $this->createPayment();

        $response = $this->actingAs($owner, 'sanctum')
            ->deleteJson("/api/v1/payments/{$payment->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('payments', ['id' => $payment->id]);
    }

    // =====================================================
    // BUSINESS LOGIC — PAYMENT DETAILS
    // =====================================================

    public function test_payment_detail_is_stored_correctly()
    {
        $session = $this->createTherapySession();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/v1/payments', [
                'therapy_session_id' => $session->id,
                'patient_id'         => $this->patient->id,
                'physiotherapist_id' => $this->physio->id,
                'payment_date'       => now()->toDateString(),
                'payment_method'     => 'Transfer',
                'status'             => 'Lunas',
                'discount'           => 10000,
                'tax'                => 0,
                'details'            => [
                    [
                        'service_master_id' => $this->service->id,
                        'quantity'          => 2,
                        'price'             => 100000,
                    ]
                ]
            ]);

        $response->assertStatus(201);

        $paymentId = $response->json('data.id');

        $this->assertDatabaseHas('payment_details', [
            'payment_id'        => $paymentId,
            'service_master_id' => $this->service->id,
            'quantity'          => 2,
            'price'             => 100000,
        ]);
    }
}
