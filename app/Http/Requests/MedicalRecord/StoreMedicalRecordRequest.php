<?php

namespace App\Http\Requests\MedicalRecord;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicalRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage', \App\Models\MedicalRecord::class);
    }

    public function rules(): array
    {
        return [
            'visit_number' => ['nullable', 'string', 'max:255'],
            'patient_id' => ['required', 'exists:patients,id'],
            'service_id' => ['required', 'exists:service_masters,id'],
            'physiotherapist_id' => ['required', 'exists:physiotherapists,id'],
            'appointment_id' => ['nullable', 'exists:appointments,id'],
            'examination_date' => ['required', 'date'],
            'anamnesis' => ['required', 'string'],
            'diagnosis' => ['required', 'string'],
            'therapy' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
            'prescription' => ['nullable', 'string'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'], // Max 5MB
        ];
    }
}
