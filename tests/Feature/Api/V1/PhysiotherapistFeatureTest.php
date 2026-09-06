<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;
use App\Models\User;
use App\Models\Physiotherapist;
use App\Enums\RoleEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PhysiotherapistFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $staff;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create(['role' => RoleEnum::ADMIN]);
        $this->staff = User::factory()->create(['role' => RoleEnum::STAFF]);
        Storage::fake('public');
    }

    public function test_anyone_can_get_all_physiotherapists()
    {
        Physiotherapist::create([
            'name' => 'Fisio A',
            'sip' => 'SIP-001',
            'phone' => '0811',
            'email' => 'fisioA@example.com',
            'address' => 'Jl. A',
            'gender' => 'L',
            'status' => 'active'
        ]);

        $response = $this->actingAs($this->staff, 'sanctum')->getJson('/api/v1/physiotherapists');

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data.data');
    }

    public function test_staff_cannot_create_physiotherapist()
    {
        $physioData = [
            'name' => 'Dr. Physio',
            'sip' => 'SIP-1234-5678',
            'phone' => '081112223334',
            'email' => 'physio@klinik.com',
            'address' => 'Jl. Sehat',
            'gender' => 'P',
        ];

        $response = $this->actingAs($this->staff, 'sanctum')->postJson('/api/v1/physiotherapists', $physioData);
        $response->assertStatus(403);
    }

    public function test_admin_can_create_physiotherapist_with_photo()
    {
        $physioData = [
            'name' => 'Dr. Physio',
            'sip' => 'SIP-1234-5678',
            'phone' => '081112223334',
            'email' => 'physio@klinik.com',
            'address' => 'Jl. Sehat',
            'gender' => 'P',
            'photo' => UploadedFile::fake()->image('photo.jpg'),
        ];

        $response = $this->actingAs($this->admin, 'sanctum')->postJson('/api/v1/physiotherapists', $physioData);

        $response->assertStatus(201)
                 ->assertJsonPath('data.name', 'Dr. Physio');

        $this->assertDatabaseHas('physiotherapists', ['sip' => 'SIP-1234-5678']);
        
        $physio = Physiotherapist::where('sip', 'SIP-1234-5678')->first();
        $this->assertNotNull($physio->photo);
        Storage::disk('public')->assertExists($physio->photo);
    }

    public function test_admin_can_update_physiotherapist()
    {
        $physio = Physiotherapist::create([
            'name' => 'Fisio A',
            'sip' => 'SIP-001',
            'phone' => '0811',
            'email' => 'fisioA@example.com',
            'address' => 'Jl. A',
            'gender' => 'L',
            'status' => 'active'
        ]);

        $updateData = [
            'name' => 'Updated Physio Name',
            'gender' => 'L', // required field
            'phone' => '0822', // required field
        ];

        $response = $this->actingAs($this->admin, 'sanctum')->putJson("/api/v1/physiotherapists/{$physio->id}", $updateData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('physiotherapists', ['id' => $physio->id, 'name' => 'Updated Physio Name']);
    }

    public function test_admin_can_soft_delete_and_restore_physiotherapist()
    {
        $physio = Physiotherapist::create([
            'name' => 'Fisio A',
            'sip' => 'SIP-001',
            'phone' => '0811',
            'email' => 'fisioA@example.com',
            'address' => 'Jl. A',
            'gender' => 'L',
            'status' => 'active'
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')->deleteJson("/api/v1/physiotherapists/{$physio->id}");
        $response->assertStatus(200);
        $this->assertSoftDeleted('physiotherapists', ['id' => $physio->id]);
        
        $response2 = $this->actingAs($this->admin, 'sanctum')->postJson("/api/v1/physiotherapists/{$physio->id}/restore");
        $response2->assertStatus(200);
        
        $this->assertDatabaseHas('physiotherapists', ['id' => $physio->id, 'deleted_at' => null]);
    }
}
