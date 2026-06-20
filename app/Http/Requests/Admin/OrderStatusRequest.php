<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage orders') || (bool) $this->user()?->is_admin;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(['pending', 'processing', 'shipped', 'completed', 'cancelled'])],
        ];
    }
}
