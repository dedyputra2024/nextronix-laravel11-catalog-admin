<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage products') || (bool) $this->user()?->is_admin;
    }

    protected function prepareForValidation(): void
    {
        $source = $this->input('slug') ?: $this->input('name');

        if ($source) {
            $this->merge([
                'slug' => Str::slug($source),
            ]);
        }
    }

    public function rules(): array
    {
        $product = $this->route('product');
        $productId = $product?->id;

        return [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['required', 'string', 'max:180', Rule::unique('products', 'slug')->ignore($productId)],
            'sku' => ['nullable', 'string', 'max:80', Rule::unique('products', 'sku')->ignore($productId)],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'stock' => ['required', 'integer', 'min:0'],
            'weight_gram' => ['required', 'integer', 'min:1'],
            'length_cm' => ['nullable', 'integer', 'min:1'],
            'width_cm' => ['nullable', 'integer', 'min:1'],
            'height_cm' => ['nullable', 'integer', 'min:1'],
            'image' => ['nullable', 'string', 'max:255'],
            'image_file' => ['nullable', 'image', 'max:2048'],
            'is_featured' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'slug.unique' => 'Slug produk sudah dipakai. Ubah nama/slug agar URL produk tetap unik.',
            'sale_price.lt' => 'Harga diskon harus lebih kecil dari harga normal.',
        ];
    }
}
