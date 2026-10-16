<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:20'],
            'subject' => ['required', 'string', 'in:commande,produit,retour,partenariat,autre'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
            // Champ piège anti-spam : doit rester vide
            'website' => ['prohibited'],
        ];
    }
}
