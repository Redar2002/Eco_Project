<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('access-admin') ?? false;
    }

    protected function prepareForValidation(): void
    {
        // Les cases à cocher non cochées ne sont pas envoyées par le navigateur
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'is_featured' => $this->boolean('is_featured'),
            'sale_price' => $this->filled('sale_price') ? $this->input('sale_price') : null,
        ]);
    }

    public function rules(): array
    {
        $product = $this->route('product');

        return [
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'alpha_dash', 'max:170', Rule::unique('products', 'slug')->ignore($product)],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['required', 'string', 'max:5000'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'stock' => ['required', 'integer', 'min:0', 'max:100000'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
            'images' => ['nullable', 'array', 'max:8'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ];
    }

    public function messages(): array
    {
        return [
            'sale_price.lt' => 'Le prix promo doit être inférieur au prix normal.',
            'images.*.max' => 'Chaque image doit peser moins de 3 Mo.',
        ];
    }
}
