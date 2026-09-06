<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Interfaces\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request): JsonResponse
    {
        $this->authorize('manage', \App\Models\User::class);

        $filters = $request->only(['search', 'role']);
        $perPage = min(100, (int) $request->input('per_page', 15));

        $users = $this->userService->getPaginatedUsers($filters, $perPage);

        return $this->successResponse(
            UserResource::collection($users)->response()->getData(true),
            'Data user berhasil diambil'
        );
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            $user = $this->userService->createUser($request->validated());
            return $this->successResponse(new UserResource($user), 'User berhasil dibuat', 201);
        } catch (Exception $e) {
            return $this->errorResponse('Gagal membuat user: ' . $e->getMessage(), 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        $this->authorize('manage', \App\Models\User::class);

        $user = \App\Models\User::withTrashed()->find($id);

        if (!$user) {
            return $this->errorResponse('User tidak ditemukan', 404);
        }

        return $this->successResponse(new UserResource($user), 'Data user berhasil diambil');
    }

    public function update(UpdateUserRequest $request, string $id): JsonResponse
    {
        try {
            $user = $this->userService->updateUser($id, $request->validated());
            return $this->successResponse(new UserResource($user), 'User berhasil diupdate');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        $this->authorize('manage', \App\Models\User::class);

        try {
            $this->userService->deleteUser($id);
            return $this->successResponse(null, 'User berhasil dihapus');
        } catch (Exception $e) {
            return $this->errorResponse('Gagal menghapus user', 500);
        }
    }

    public function restore(string $id): JsonResponse
    {
        $this->authorize('manage', \App\Models\User::class);

        try {
            $this->userService->restoreUser($id);
            return $this->successResponse(null, 'User berhasil direstore');
        } catch (Exception $e) {
            return $this->errorResponse('Gagal merestore user', 500);
        }
    }

    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        try {
            $user = $this->userService->updateProfile($request->user()->id, $request->validated());
            return $this->successResponse(new UserResource($user), 'Profil berhasil diupdate');
        } catch (Exception $e) {
            return $this->errorResponse('Gagal mengupdate profil: ' . $e->getMessage(), 500);
        }
    }
}
