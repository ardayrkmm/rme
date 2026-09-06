<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;
use App\Models\User;
use App\Enums\RoleEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthFeatureTest extends TestCase
{
    use RefreshDatabase;

    // =====================================================
    // LOGIN
    // =====================================================

    public function test_user_can_login_with_valid_credentials()
    {
        // Gunakan factory default — password sudah di-hash SEKALI oleh cast 'hashed' via Hash::make
        $user = User::factory()->create([
            'email' => 'test@clinic.com',
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email'    => 'test@clinic.com',
            'password' => 'password', // default factory password
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => ['user', 'token'],
                 ]);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@clinic.com',
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email'    => 'test@clinic.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Email atau password salah.',
                 ]);
    }

    public function test_login_validation_fails_without_email()
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'password' => 'password123',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    public function test_login_creates_activity_log()
    {
        $user = User::factory()->create(['email' => 'log@clinic.com']);

        $this->postJson('/api/v1/auth/login', [
            'email'    => 'log@clinic.com',
            'password' => 'password',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'action'  => 'login',
        ]);
    }

    // =====================================================
    // PROFILE
    // =====================================================

    public function test_user_can_get_profile_when_authenticated()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/v1/auth/profile');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data'    => ['email' => $user->email],
                 ]);
    }

    public function test_user_cannot_get_profile_when_unauthenticated()
    {
        $response = $this->getJson('/api/v1/auth/profile');
        $response->assertStatus(401);
    }

    // =====================================================
    // LOGOUT
    // =====================================================

    public function test_user_can_logout()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/auth/logout');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Logout berhasil',
                 ]);
    }

    public function test_logout_creates_activity_log()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum')->postJson('/api/v1/auth/logout');

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'action'  => 'logout',
        ]);
    }

    // =====================================================
    // REGISTER
    // =====================================================

    public function test_user_can_register_with_valid_data()
    {
        $payload = [
            'name'                  => 'Dokter Baru',
            'email'                 => 'dokter@clinic.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'role'                  => 'staff',
        ];

        $response = $this->postJson('/api/v1/auth/register', $payload);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'data' => ['user', 'token']
                 ]);

        $this->assertDatabaseHas('users', ['email' => 'dokter@clinic.com']);
    }

    public function test_registered_user_can_immediately_login()
    {
        // Verifikasi tidak ada double-hashing: user yang baru register harus langsung bisa login
        $this->postJson('/api/v1/auth/register', [
            'name'                  => 'User Baru',
            'email'                 => 'newuser@clinic.com',
            'password'              => 'secretPass9',
            'password_confirmation' => 'secretPass9',
            'role'                  => 'staff',
        ]);

        $loginResponse = $this->postJson('/api/v1/auth/login', [
            'email'    => 'newuser@clinic.com',
            'password' => 'secretPass9',
        ]);

        // Jika password di-hash dua kali, login akan gagal
        $loginResponse->assertStatus(200)
                      ->assertJsonPath('success', true);
    }

    public function test_register_validation_fails_with_duplicate_email()
    {
        User::factory()->create(['email' => 'exist@clinic.com']);

        $response = $this->postJson('/api/v1/auth/register', [
            'name'                  => 'Duplikat',
            'email'                 => 'exist@clinic.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    public function test_register_validation_fails_when_password_not_confirmed()
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name'                  => 'Test User',
            'email'                 => 'test@clinic.com',
            'password'              => 'password123',
            'password_confirmation' => 'different_password',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['password']);
    }

    public function test_register_validation_fails_without_required_fields()
    {
        $response = $this->postJson('/api/v1/auth/register', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    // =====================================================
    // CHANGE PASSWORD
    // =====================================================

    public function test_user_can_change_password()
    {
        $user = User::factory()->create(); // password: 'password' (default factory)

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/auth/change-password', [
                'current_password'          => 'password',
                'new_password'              => 'newSecure123',
                'new_password_confirmation' => 'newSecure123',
            ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        // Password di DB harus berubah — cek dengan login ulang
        $loginResponse = $this->postJson('/api/v1/auth/login', [
            'email'    => $user->email,
            'password' => 'newSecure123',
        ]);
        $loginResponse->assertStatus(200);
    }

    public function test_change_password_fails_with_wrong_current_password()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/auth/change-password', [
                'current_password'          => 'wrongOldPass',
                'new_password'              => 'newSecure123',
                'new_password_confirmation' => 'newSecure123',
            ]);

        $response->assertStatus(400)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Password lama tidak sesuai.',
                 ]);
    }

    public function test_change_password_validation_fails_when_new_password_not_confirmed()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/auth/change-password', [
                'current_password'          => 'password',
                'new_password'              => 'newSecure123',
                'new_password_confirmation' => 'mismatch',
            ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['new_password']);
    }

    public function test_change_password_fails_when_new_same_as_old()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/auth/change-password', [
                'current_password'          => 'password',
                'new_password'              => 'password',
                'new_password_confirmation' => 'password',
            ]);

        // Rule 'different:current_password' harus menolak
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['new_password']);
    }

    public function test_change_password_requires_authentication()
    {
        $response = $this->postJson('/api/v1/auth/change-password', [
            'current_password'          => 'password',
            'new_password'              => 'newSecure123',
            'new_password_confirmation' => 'newSecure123',
        ]);

        $response->assertStatus(401);
    }

    // =====================================================
    // FORGOT PASSWORD
    // =====================================================

    public function test_forgot_password_with_registered_email()
    {
        $user = User::factory()->create(['email' => 'forgot@clinic.com']);

        $response = $this->postJson('/api/v1/auth/forgot-password', [
            'email' => 'forgot@clinic.com',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        // Token harus tersimpan di tabel password_reset_tokens
        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'forgot@clinic.com',
        ]);
    }

    public function test_forgot_password_fails_with_unregistered_email()
    {
        // ForgotPasswordRequest memiliki rule 'exists:users,email'
        // Email tidak terdaftar langsung ditolak di level validasi → 422
        $response = $this->postJson('/api/v1/auth/forgot-password', [
            'email' => 'notexist@clinic.com',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    public function test_forgot_password_validation_fails_without_email()
    {
        $response = $this->postJson('/api/v1/auth/forgot-password', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    // =====================================================
    // RESET PASSWORD
    // =====================================================

    public function test_can_reset_password_with_valid_token()
    {
        $user = User::factory()->create(['email' => 'reset@clinic.com']);

        // Trigger forgot password untuk dapatkan token
        $this->postJson('/api/v1/auth/forgot-password', [
            'email' => 'reset@clinic.com',
        ]);

        // Ambil token raw dari log — simulasi: ambil dari DB, kita buat ulang token baru
        // Karena token di-hash di DB, kita perlu update langsung dengan token yang kita tahu
        $rawToken = 'valid-test-token-123';
        DB::table('password_reset_tokens')
            ->where('email', 'reset@clinic.com')
            ->update(['token' => Hash::make($rawToken)]);

        $response = $this->postJson('/api/v1/auth/reset-password', [
            'email'                 => 'reset@clinic.com',
            'token'                 => $rawToken,
            'password'              => 'resetNewPass9',
            'password_confirmation' => 'resetNewPass9',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        // Token harus dihapus setelah dipakai
        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => 'reset@clinic.com',
        ]);

        // User harus bisa login dengan password baru
        $loginResponse = $this->postJson('/api/v1/auth/login', [
            'email'    => 'reset@clinic.com',
            'password' => 'resetNewPass9',
        ]);
        $loginResponse->assertStatus(200);
    }

    public function test_reset_password_fails_with_invalid_token()
    {
        $user = User::factory()->create(['email' => 'reset2@clinic.com']);

        DB::table('password_reset_tokens')->insert([
            'email'      => 'reset2@clinic.com',
            'token'      => Hash::make('correct-token'),
            'created_at' => now(),
        ]);

        $response = $this->postJson('/api/v1/auth/reset-password', [
            'email'                 => 'reset2@clinic.com',
            'token'                 => 'wrong-token',
            'password'              => 'newPass123',
            'password_confirmation' => 'newPass123',
        ]);

        $response->assertStatus(400)
                 ->assertJson(['success' => false]);
    }

    public function test_reset_password_fails_when_no_token_record_exists()
    {
        $user = User::factory()->create(['email' => 'notoken@clinic.com']);

        $response = $this->postJson('/api/v1/auth/reset-password', [
            'email'                 => 'notoken@clinic.com',
            'token'                 => 'any-token',
            'password'              => 'newPass123',
            'password_confirmation' => 'newPass123',
        ]);

        $response->assertStatus(400)
                 ->assertJson(['success' => false]);
    }
}
