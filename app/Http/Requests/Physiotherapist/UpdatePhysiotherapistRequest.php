<?php

namespace App\Http\Requests\Physiotherapist;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePhysiotherapistRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage', \App\Models\Physiotherapist::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'sip' => ['nullable', 'string', 'max:100'],
            'phone' => ['sometimes', 'required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'gender' => ['sometimes', 'required', 'in:L,P'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'status' => ['nullable', 'in:active,inactive'],
        ];
    }
}
