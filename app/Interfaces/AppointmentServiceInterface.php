<?php

namespace App\Interfaces;

interface AppointmentServiceInterface
{
    public function getPaginatedAppointments(array $filters = [], int $perPage = 15);
    public function getHistoryPaginated(array $filters = [], int $perPage = 15);
    public function getAppointmentById(string $id);
    public function createAppointment(array $data);
    public function updateAppointment(string $id, array $data);
    public function rescheduleAppointment(string $id, array $data);
    public function cancelAppointment(string $id, array $data = []);
    public function deleteAppointment(string $id);
}
