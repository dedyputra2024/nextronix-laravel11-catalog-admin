<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:20'],
            'destination_id' => ['nullable', 'string', 'max:50'],
            'destination_label' => ['nullable', 'string', 'max:255'],
            'courier_code' => ['nullable', 'string', 'max:50'],
            'courier_name' => ['nullable', 'string', 'max:100'],
            'courier_service' => ['nullable', 'string', 'max:100'],
            'courier_etd' => ['nullable', 'string', 'max:100'],
            'shipping_cost' => ['nullable', 'numeric', 'min:0'],
            'payment_method' => ['required', Rule::in(['midtrans', 'bank_transfer', 'cod', 'e_wallet'])],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
