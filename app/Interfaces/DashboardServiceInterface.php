<?php

namespace App\Interfaces;

interface DashboardServiceInterface
{
    public function getSummaryStats(string $filter = 'year'): array;
    public function getPatientChartData(string $filter = 'year'): array;
    public function getAppointmentChartData(string $filter = 'year'): array;
    public function getDiseaseChartData(string $filter = 'year'): array;
    public function getRevenueChartData(string $filter = 'year'): array;
    public function getFisioterapisStats($user): array;
}
