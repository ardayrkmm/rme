<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
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
            'patient_id' => $this->patient_id,
            'patient' => new PatientResource($this->whenLoaded('patient')),
            'physiotherapist_id' => $this->physiotherapist_id,
            'physiotherapist' => new PhysiotherapistResource($this->whenLoaded('physiotherapist')),
            'service_master_id' => $this->service_master_id,
            'service_master' => $this->whenLoaded('serviceMaster'),
            'appointment_date' => $this->appointment_date ? $this->appointment_date->format('Y-m-d') : null,
            'appointment_time' => $this->appointment_time,
            'complaint' => $this->complaint,
            'status' => $this->status,
            'notes' => $this->notes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->when(isset($this->deleted_at), $this->deleted_at),
        ];
    }
}
