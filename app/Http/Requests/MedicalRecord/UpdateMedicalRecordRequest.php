<?php

namespace App\Http\Requests\MedicalRecord;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMedicalRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage', \App\Models\MedicalRecord::class);
    }

    public function rules(): array
    {
        return [
            'visit_number' => ['nullable', 'string', 'max:255'],
            'patient_id' => ['sometimes', 'required', 'exists:patients,id'],
            'service_id' => ['sometimes', 'required', 'exists:service_masters,id'],
            'physiotherapist_id' => ['sometimes', 'required', 'exists:physiotherapists,id'],
            'appointment_id' => ['nullable', 'exists:appointments,id'],
            'examination_date' => ['sometimes', 'required', 'date'],
            'anamnesis' => ['sometimes', 'required', 'string'],
            'diagnosis' => ['sometimes', 'required', 'string'],
            'therapy' => ['sometimes', 'required', 'string'],
            'notes' => ['nullable', 'string'],
            'prescription' => ['nullable', 'string'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }
}
