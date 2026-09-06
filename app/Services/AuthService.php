<?php

namespace App\Services;

use App\Interfaces\AuthServiceInterface;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Exception;

class AuthService implements AuthServiceInterface
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $data): array
    {
        // Catatan: User model memiliki cast 'password' => 'hashed' yang otomatis
        // meng-hash password saat disimpan. Tidak perlu Hash::make() manual di sini.
        $user = $this->userRepository->create($data);
        
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function login(array $credentials): array
    {
        $user = $this->userRepository->findByEmail($credentials['email']);

        if (!$user) {
            throw new Exception('Email atau password salah.', 401);
        }

        $isValid = false;
        try {
            $isValid = Hash::check($credentials['password'], $user->password);
        } catch (\RuntimeException $e) {
            // Tangani error "This password does not use the Bcrypt algorithm." dari Laravel (biasanya karena format $2a$)
            if (str_contains($e->getMessage(), 'Bcrypt algorithm')) {
                // Fallback menggunakan password_verify bawaan PHP yang mendukung format $2a$
                $isValid = password_verify($credentials['password'], $user->password);
                
                // Jika password ternyata benar, otomatis update hash di database ke format $2y$ (Laravel modern)
                if ($isValid) {
                    $this->userRepository->update($user->id, [
                        'password' => $credentials['password'] // Cast 'hashed' pada model akan otomatis meng-hash ulang
                    ]);
                }
            } else {
                throw $e;
            }
        }

        if (!$isValid) {
            throw new Exception('Email atau password salah.', 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function logout($user): bool
    {
        return (bool) $user->currentAccessToken()?->delete();
    }

    public function changePassword($user, string $currentPassword, string $newPassword): bool
    {
        if (!Hash::check($currentPassword, $user->password)) {
            throw new Exception('Password lama tidak sesuai.', 400);
        }

        // Cast 'hashed' pada model User sudah meng-hash password secara otomatis.
        $result = $this->userRepository->update($user->id, [
            'password' => $newPassword
        ]);

        return (bool) $result;
    }

    public function forgotPassword(string $email): bool
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            throw new Exception('Pengguna dengan email tersebut tidak ditemukan.', 404);
        }

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => Hash::make($token),
                'created_at' => Carbon::now()
            ]
        );

        // Simulasi pengiriman email
        Log::info("Reset Password Token untuk {$email}: {$token}");

        return true;
    }

    public function resetPassword(string $email, string $token, string $password): bool
    {
        $resetRecord = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$resetRecord) {
            throw new Exception('Token tidak valid atau sudah kadaluarsa.', 400);
        }

        if (!Hash::check($token, $resetRecord->token)) {
            throw new Exception('Token reset password tidak valid.', 400);
        }

        // Cek kadaluarsa token (misal: 60 menit)
        if (Carbon::parse($resetRecord->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            throw new Exception('Token reset password sudah kadaluarsa.', 400);
        }

        $user = $this->userRepository->findByEmail($email);
        
        $this->userRepository->update($user->id, [
            'password' => $password // cast 'hashed' di model User menangani hashing
        ]);

        DB::table('password_reset_tokens')->where('email', $email)->delete();

        return true;
    }
}
