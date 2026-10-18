<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'quantity' => ['sometimes', 'integer', 'min:1', 'max:'.config('shop.max_quantity_per_item')],
        ];
    }

    public function quantity(): int
    {
        return (int) $this->input('quantity', 1);
    }
}
