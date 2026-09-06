<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage', \App\Models\Patient::class);
    }

    public function rules(): array
    {
        $patientId = $this->route('id');

        return [
            'nik' => ['nullable', 'string', 'max:20', Rule::unique('patients', 'nik')->ignore($patientId)],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'birth_date' => ['sometimes', 'required', 'date'],
            'gender_id' => ['sometimes', 'required', 'exists:genders,id'],
            'patient_category_id' => ['sometimes', 'required', 'exists:patient_categories,id'],
            'blood_type' => ['nullable', 'string', 'max:5'],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'occupation' => ['nullable', 'string', 'max:100'],
            'marital_status' => ['nullable', 'string', 'max:50'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:50'],
            'medical_history' => ['nullable', 'string'],
            'allergies' => ['nullable', 'string'],
        ];
    }
}
