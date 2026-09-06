<?php

namespace App\Http\Requests\TherapySession;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTherapySessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'patient_id' => ['sometimes', 'required', 'exists:patients,id'],
            'physiotherapist_id' => ['sometimes', 'required', 'exists:physiotherapists,id'],
            'appointment_id' => ['nullable', 'exists:appointments,id'],
            'service_master_id' => ['nullable', 'exists:service_masters,id'],
            'service_master_ids' => ['nullable', 'array'],
            'service_master_ids.*' => ['exists:service_masters,id'],
            'therapy_date' => ['sometimes', 'required', 'date'],
            'complaint' => ['required', 'string'],
            'objective' => ['nullable', 'string'],
            'assessment' => ['nullable', 'string'],
            'plan' => ['nullable', 'string'],
            'treatment_given' => ['required', 'string'],
            'duration' => ['nullable', 'integer'],
            'notes' => ['nullable', 'string'],
            'status' => ['nullable', 'in:scheduled,ongoing,completed,cancelled'],
        ];
    }
}
