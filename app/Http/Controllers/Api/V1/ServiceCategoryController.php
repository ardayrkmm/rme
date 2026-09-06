<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceCategoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = min(100, (int) $request->input('per_page', 100));
        $categories = ServiceCategory::paginate($perPage);

        return $this->successResponse($categories, 'Data kategori layanan berhasil diambil');
    }

    public function show(string $id): JsonResponse
    {
        $category = ServiceCategory::find($id);

        if (!$category) {
            return $this->errorResponse('Kategori layanan tidak ditemukan', 404);
        }

        return $this->successResponse($category, 'Detail kategori layanan berhasil diambil');
    }

    public function store(Request $request): JsonResponse
    {
        if (!in_array($request->user()->role->value, ['admin', 'owner'])) {
            return $this->errorResponse('Unauthorized', 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category = ServiceCategory::create($validated);

        return $this->successResponse($category, 'Kategori layanan berhasil dibuat', 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        if (!in_array($request->user()->role->value, ['admin', 'owner'])) {
            return $this->errorResponse('Unauthorized', 403);
        }

        $category = ServiceCategory::find($id);

        if (!$category) {
            return $this->errorResponse('Kategori layanan tidak ditemukan', 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category->update($validated);

        return $this->successResponse($category, 'Kategori layanan berhasil diupdate');
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        if (!in_array($request->user()->role->value, ['admin', 'owner'])) {
            return $this->errorResponse('Unauthorized', 403);
        }

        $category = ServiceCategory::find($id);

        if (!$category) {
            return $this->errorResponse('Kategori layanan tidak ditemukan', 404);
        }

        $category->delete();

        return $this->successResponse(null, 'Kategori layanan berhasil dihapus');
    }
}
