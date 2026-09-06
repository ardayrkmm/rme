<?php

namespace App\Http\Requests\Appointment;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage', \App\Models\Appointment::class);
    }

    public function rules(): array
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'physiotherapist_id' => 'required|exists:physiotherapists,id',
            'service_master_id' => 'required|exists:service_masters,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
            'complaint' => 'nullable|string',
            'status' => 'required|in:pending,confirmed,telah_tiba,completed,cancelled,rescheduled',
            'notes' => 'nullable|string',
        ];
    }
}
