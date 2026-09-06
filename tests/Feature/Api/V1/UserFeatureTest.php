<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;
use App\Models\User;
use App\Enums\RoleEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $staff;
    protected $fisioterapis;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create(['role' => RoleEnum::ADMIN]);
        $this->staff = User::factory()->create(['role' => RoleEnum::STAFF]);
        $this->fisioterapis = User::factory()->create(['role' => RoleEnum::FISIOTERAPIS]);
    }

    public function test_admin_can_view_users()
    {
        User::factory()->count(3)->create();

        $response = $this->actingAs($this->admin, 'sanctum')->getJson('/api/v1/users');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         'data' => [
                             '*' => ['id', 'name', 'email', 'role']
                         ]
                     ]
                 ]);
    }

    public function test_staff_cannot_view_users()
    {
        $response = $this->actingAs($this->staff, 'sanctum')->getJson('/api/v1/users');

        $response->assertStatus(403);
    }

    public function test_admin_can_create_user()
    {
        $userData = [
            'name' => 'New Staff',
            'email' => 'newstaff@clinic.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'staff',
        ];

        $response = $this->actingAs($this->admin, 'sanctum')->postJson('/api/v1/users', $userData);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'User berhasil dibuat',
                     'data' => [
                         'name' => 'New Staff',
                         'email' => 'newstaff@clinic.com',
                         'role' => 'staff',
                     ]
                 ]);

        $this->assertDatabaseHas('users', ['email' => 'newstaff@clinic.com']);
    }

    public function test_staff_cannot_create_user()
    {
        $userData = [
            'name' => 'New Staff 2',
            'email' => 'newstaff2@clinic.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'staff',
        ];

        $response = $this->actingAs($this->staff, 'sanctum')->postJson('/api/v1/users', $userData);
        $response->assertStatus(403);
    }

    public function test_cannot_create_user_with_invalid_data()
    {
        $userData = [
            'name' => '', // required
            'email' => 'not-an-email', // invalid format
            'password' => 'short', // min 8
            'password_confirmation' => 'mismatch', // doesn't match
            'role' => 'invalid_role', // must be enum
        ];

        $response = $this->actingAs($this->admin, 'sanctum')->postJson('/api/v1/users', $userData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'email', 'password', 'role']);
    }

    public function test_admin_can_update_user()
    {
        $userToUpdate = User::factory()->create(['role' => RoleEnum::STAFF]);

        $updateData = [
            'name' => 'Updated Name',
            'role' => 'owner',
        ];

        $response = $this->actingAs($this->admin, 'sanctum')->putJson("/api/v1/users/{$userToUpdate->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                         'name' => 'Updated Name',
                         'role' => 'owner',
                     ]
                 ]);

        $this->assertDatabaseHas('users', ['id' => $userToUpdate->id, 'name' => 'Updated Name']);
    }

    public function test_admin_can_delete_user()
    {
        $userToDelete = User::factory()->create();

        $response = $this->actingAs($this->admin, 'sanctum')->deleteJson("/api/v1/users/{$userToDelete->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('users', ['id' => $userToDelete->id]);
    }

    public function test_admin_can_restore_deleted_user()
    {
        $userToRestore = User::factory()->create();
        $userToRestore->delete(); // Soft delete it

        $this->assertSoftDeleted('users', ['id' => $userToRestore->id]);

        $response = $this->actingAs($this->admin, 'sanctum')->postJson("/api/v1/users/{$userToRestore->id}/restore");

        $response->assertStatus(200)
                 ->assertJsonPath('message', 'User berhasil direstore');

        $this->assertDatabaseHas('users', [
            'id' => $userToRestore->id,
            'deleted_at' => null
        ]);
    }

    public function test_any_user_can_update_their_own_profile()
    {
        $updateData = [
            'name' => 'Fisioterapis Ganti Nama',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123'
        ];

        $response = $this->actingAs($this->fisioterapis, 'sanctum')->postJson('/api/v1/profile', $updateData);

        $response->assertStatus(200)
                 ->assertJsonPath('data.name', 'Fisioterapis Ganti Nama');

        $this->assertDatabaseHas('users', [
            'id' => $this->fisioterapis->id,
            'name' => 'Fisioterapis Ganti Nama'
        ]);
    }
}
