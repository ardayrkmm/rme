<?php

namespace App\Http\Requests\Appointment;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
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
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'complaint' => 'nullable|string',
            'notes' => 'nullable|string',
        ];
    }
}
