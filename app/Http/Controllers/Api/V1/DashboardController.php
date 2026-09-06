<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\DashboardServiceInterface;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardServiceInterface $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function admin(\Illuminate\Http\Request $request): JsonResponse
    {
        $filter = $request->query('filter', 'year');

        $data = [
            'summary' => $this->dashboardService->getSummaryStats($filter),
            'charts' => [
                'patients' => $this->dashboardService->getPatientChartData($filter),
                'appointments' => $this->dashboardService->getAppointmentChartData($filter),
                'revenue' => $this->dashboardService->getRevenueChartData($filter),
                'diseases' => $this->dashboardService->getDiseaseChartData($filter),
            ]
        ];

        return $this->successResponse($data, 'Data beranda admin berhasil diambil');
    }

    public function fisio(\Illuminate\Http\Request $request): JsonResponse
    {
        $user = $request->user();
        $data = $this->dashboardService->getFisioterapisStats($user);
        return $this->successResponse($data, 'Data beranda fisioterapis berhasil diambil');
    }
}

