<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validation des paramètres du catalogue (query string).
 */
class CatalogFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:100'],
            'categorie' => ['nullable', 'string', 'max:100'],
            'min' => ['nullable', 'numeric', 'min:0', 'max:1000000'],
            'max' => ['nullable', 'numeric', 'min:0', 'max:1000000'],
            'tri' => ['nullable', 'in:nouveautes,prix-asc,prix-desc,nom'],
            'promo' => ['nullable', 'boolean'],
        ];
    }

    /**
     * En cas de paramètre invalide on l'ignore simplement plutôt que de bloquer la page.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator): void
    {
        $this->replace(collect($this->query())->except($validator->errors()->keys())->all());
    }

    /** @return array<string, mixed> */
    public function filters(): array
    {
        return array_filter(
            $this->only(['q', 'categorie', 'min', 'max', 'tri', 'promo']),
            fn ($value) => $value !== null && $value !== '',
        );
    }
}
