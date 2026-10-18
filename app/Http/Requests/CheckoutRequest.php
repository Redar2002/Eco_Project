<?php

namespace App\Http\Requests;

use App\Enums\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** Normalise le téléphone (suppression des espaces, tirets et points). */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'phone' => preg_replace('/[\s.\-]/', '', (string) $this->input('phone')),
            'customer_name' => trim((string) $this->input('customer_name')),
        ]);
    }

    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'min:3', 'max:100'],
            // Obligatoire : la confirmation et le suivi de commande sont envoyés par e-mail
            'email' => ['required', 'email', 'max:150'],
            // Numéro marocain : 06/07/05 + 8 chiffres, ou +212 + 9 chiffres
            'phone' => ['required', 'regex:/^(?:\+212|0)[5-7]\d{8}$/'],
            'city' => ['required', 'string', 'max:80'],
            'address' => ['required', 'string', 'min:8', 'max:255'],
            'notes' => ['nullable', 'string', 'max:500'],
            'payment_method' => ['required', Rule::enum(PaymentMethod::class)],
            'terms' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Veuillez saisir un numéro marocain valide (ex. 06 12 34 56 78).',
            'terms.accepted' => 'Vous devez accepter les conditions générales de vente.',
        ];
    }
}
