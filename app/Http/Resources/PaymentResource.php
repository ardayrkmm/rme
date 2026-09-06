<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoice_number' => $this->invoice_number,
            'therapy_session_id' => $this->therapy_session_id,
            'patient_id' => $this->patient_id,
            'patient_name' => $this->whenLoaded('patient', function () {
                return $this->patient->name;
            }),
            'physiotherapist_id' => $this->physiotherapist_id,
            'physiotherapist_name' => $this->whenLoaded('physiotherapist', function () {
                return $this->physiotherapist->name;
            }),
            'payment_date' => $this->payment_date,
            'payment_method' => $this->payment_method,
            'status' => $this->status,
            'subtotal' => $this->subtotal,
            'discount' => $this->discount,
            'tax' => $this->tax,
            'total' => $this->total,
            'notes' => $this->notes,
            'share_link' => \Illuminate\Support\Facades\URL::signedRoute('payment.invoice.download', ['id' => $this->id]),
            'details' => PaymentDetailResource::collection($this->whenLoaded('paymentDetails')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
