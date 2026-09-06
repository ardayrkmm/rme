<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PatientCategory;
use Illuminate\Http\JsonResponse;

class PatientCategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = PatientCategory::all();
        return $this->successResponse($categories, 'Data kategori pasien berhasil diambil');
    }

    public function store(\Illuminate\Http\Request $request): JsonResponse
    {
        if (!in_array($request->user()->role->value, ['admin', 'owner'])) {
            return $this->errorResponse('Unauthorized', 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category = PatientCategory::create($validated);
        return $this->successResponse($category, 'Kategori pasien berhasil dibuat', 201);
    }

    public function update(\Illuminate\Http\Request $request, string $id): JsonResponse
    {
        if (!in_array($request->user()->role->value, ['admin', 'owner'])) {
            return $this->errorResponse('Unauthorized', 403);
        }

        $category = PatientCategory::find($id);
        if (!$category) {
            return $this->errorResponse('Kategori pasien tidak ditemukan', 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category->update($validated);
        return $this->successResponse($category, 'Kategori pasien berhasil diupdate');
    }

    public function destroy(\Illuminate\Http\Request $request, string $id): JsonResponse
    {
        if (!in_array($request->user()->role->value, ['admin', 'owner'])) {
            return $this->errorResponse('Unauthorized', 403);
        }

        $category = PatientCategory::find($id);
        if (!$category) {
            return $this->errorResponse('Kategori pasien tidak ditemukan', 404);
        }

        $category->delete();
        return $this->successResponse(null, 'Kategori pasien berhasil dihapus');
    }
}
