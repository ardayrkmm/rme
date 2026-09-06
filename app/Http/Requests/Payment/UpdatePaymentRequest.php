<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_method' => 'sometimes|required|in:Tunai,Transfer,QRIS,Debit,Kredit',
            'status' => 'sometimes|required|in:Pending,Lunas,Dibatalkan',
            'notes' => 'nullable|string',
        ];
    }
}
