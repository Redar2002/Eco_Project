<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // 0 = suppression de l'article
        return [
            'quantity' => ['required', 'integer', 'min:0', 'max:'.config('shop.max_quantity_per_item')],
        ];
    }
}
