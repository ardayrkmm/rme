<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'medical_record_number' => $this->medical_record_number,
            'nik' => $this->nik,
            'name' => $this->name,
            'birth_date' => $this->birth_date ? $this->birth_date->format('Y-m-d') : null,
            'age' => $this->birth_date ? $this->birth_date->age : null,
            'patient_category_id' => $this->patient_category_id,
            'category' => $this->category ? $this->category->name : null,
            'gender_id' => $this->gender_id,
            'gender' => $this->gender ? $this->gender->name : null,
            'blood_type' => $this->blood_type,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'occupation' => $this->occupation,
            'marital_status' => $this->marital_status,
            'emergency_contact_name' => $this->emergency_contact_name,
            'emergency_contact_phone' => $this->emergency_contact_phone,
            'medical_history' => $this->medical_history,
            'allergies' => $this->allergies,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->when(isset($this->deleted_at), $this->deleted_at),
        ];
    }
}
