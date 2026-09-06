<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Resources\UserResource;
use App\Interfaces\AuthServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $result = $this->authService->register($request->validated());
            
            try {
                \App\Models\ActivityLog::create([
                    'user_id' => $result['user']->id,
                    'action' => 'register',
                    'description' => 'New user registered (ID: ' . $result['user']->id . ')',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->header('User-Agent'),
                ]);
            } catch (\Exception $logEx) {
                // Ignore log insertion errors to prevent breaking the flow
            }

            return $this->successResponse([
                'user' => new UserResource($result['user']),
                'token' => $result['token']
            ], 'Registrasi berhasil', 201);
        } catch (Exception $e) {
            $code = is_numeric($e->getCode()) ? (int) $e->getCode() : 500;
            return $this->errorResponse($e->getMessage(), $code ?: 400);
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $result = $this->authService->login($request->validated());

            try {
                \App\Models\ActivityLog::create([
                    'user_id' => $result['user']->id,
                    'action' => 'login',
                    'description' => 'User (ID: ' . $result['user']->id . ') logged in',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->header('User-Agent'),
                ]);
            } catch (\Exception $logEx) {
                // Ignore log insertion errors to prevent breaking the flow
            }

            return $this->successResponse([
                'user' => new UserResource($result['user']),
                'token' => $result['token']
            ], 'Login berhasil');
        } catch (Exception $e) {
            $code = is_numeric($e->getCode()) ? (int) $e->getCode() : 500;
            return $this->errorResponse($e->getMessage(), $code ?: 401);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            try {
                \App\Models\ActivityLog::create([
                    'user_id' => $user->id,
                    'action' => 'logout',
                    'description' => 'User (ID: ' . $user->id . ') performed logout',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->header('User-Agent'),
                ]);
            } catch (\Exception $logEx) {
                // Ignore log insertion errors
            }

            $this->authService->logout($request->user());
            return $this->successResponse(null, 'Logout berhasil');
        } catch (Exception $e) {
            return $this->errorResponse('Logout gagal', 500);
        }
    }

    public function profile(Request $request): JsonResponse
    {
        return $this->successResponse(new UserResource($request->user()), 'Data profil berhasil diambil');
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        try {
            $this->authService->changePassword(
                $request->user(), 
                $request->current_password, 
                $request->new_password
            );

            return $this->successResponse(null, 'Password berhasil diubah');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        try {
            $this->authService->forgotPassword($request->email);
            return $this->successResponse(null, 'Link reset password telah dikirim ke email Anda');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        try {
            $this->authService->resetPassword(
                $request->email, 
                $request->token, 
                $request->password
            );
            return $this->successResponse(null, 'Password berhasil direset');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode() ?: 400);
        }
    }
}
