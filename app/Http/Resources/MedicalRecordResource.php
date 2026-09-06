<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MedicalRecordResource extends JsonResource
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
            'visit_number' => $this->visit_number,
            'patient_id' => $this->patient_id,
            'patient' => new PatientResource($this->whenLoaded('patient')),
            'service_id' => $this->service_id,
            'service' => $this->whenLoaded('service', fn() => [
                'id' => $this->service->id,
                'name' => $this->service->name,
            ]),
            'physiotherapist_id' => $this->physiotherapist_id,
            'physiotherapist' => new PhysiotherapistResource($this->whenLoaded('physiotherapist')),
            'appointment_id' => $this->appointment_id,
            'appointment' => new AppointmentResource($this->whenLoaded('appointment')),
            'examination_date' => $this->examination_date ? $this->examination_date->format('Y-m-d H:i:s') : null,
            'anamnesis' => $this->anamnesis,
            'diagnosis' => $this->diagnosis,
            'therapy' => $this->therapy,
            'notes' => $this->notes,
            'prescription' => $this->prescription,
            'attachment' => $this->attachment,
            'attachment_url' => $this->attachment ? url(Storage::url($this->attachment)) : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->when(isset($this->deleted_at), $this->deleted_at),
        ];
    }
}
