<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignoutRequest extends FormRequest
{
    /**
     * Authorize the request
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [
            "username" => "string|required_without:email",
            "email" => "email|required_without:username",
            'password' => 'required|string|min:6',
        ];
    }

    /**
     * Personalised messages.
     */
    public function messages()
    {
        return [
            'email.required' => 'L’adresse email est obligatoire.',
            'email.email' => 'L’adresse email doit être valide.',

            'password.required' => 'Le mot de passe est obligatoire.',
            'password.string' => 'Le mot de passe doit être une chaîne de caractères.',
            'password.min' => 'Le mot de passe doit contenir au moins :min caractères.',
        ];
    }
}
