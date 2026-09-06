<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'therapy_session_id' => 'required|exists:therapy_sessions,id',
            'patient_id' => 'required|exists:patients,id',
            'physiotherapist_id' => 'required|exists:physiotherapists,id',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:Tunai,Transfer,QRIS,Debit,Kredit',
            'status' => 'required|in:Pending,Lunas,Dibatalkan',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'details' => 'required|array|min:1',
            'details.*.service_master_id' => 'required|exists:service_masters,id',
            'details.*.quantity' => 'required|integer|min:1',
            'details.*.price' => 'required|numeric|min:0',
        ];
    }
}
