<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Gender;
use Illuminate\Http\JsonResponse;

class GenderController extends Controller
{
    public function index(): JsonResponse
    {
        $genders = Gender::all();
        return $this->successResponse($genders, 'Data jenis kelamin berhasil diambil');
    }
}
