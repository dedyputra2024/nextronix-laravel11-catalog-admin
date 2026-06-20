<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage categories') || (bool) $this->user()?->is_admin;
    }

    protected function prepareForValidation(): void
    {
        $source = $this->input('slug') ?: $this->input('name');

        if ($source) {
            $this->merge(['slug' => Str::slug($source)]);
        }
    }

    public function rules(): array
    {
        $category = $this->route('category');

        return [
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['required', 'string', 'max:120', Rule::unique('categories', 'slug')->ignore($category?->id)],
            'description' => ['nullable', 'string', 'max:500'],
            'image' => ['nullable', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
