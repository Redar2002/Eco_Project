<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrackOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'number' => strtoupper(trim((string) $this->input('number'))),
            'phone' => preg_replace('/[\s.\-]/', '', (string) $this->input('phone')),
        ]);
    }

    public function rules(): array
    {
        return [
            'number' => ['required', 'string', 'max:30'],
            'phone' => ['required', 'string', 'max:20'],
        ];
    }

    public function attributes(): array
    {
        return ['number' => 'numéro de commande'];
    }
}
