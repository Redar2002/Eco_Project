<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsletterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'newsletter_email' => ['required', 'email', 'max:150'],
        ];
    }

    public function attributes(): array
    {
        return ['newsletter_email' => 'adresse e-mail'];
    }
}
