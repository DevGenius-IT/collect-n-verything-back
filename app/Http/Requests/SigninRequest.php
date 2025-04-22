<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SigninRequest extends FormRequest
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
    public function rules()
    {
        return [
            "username" => "string|required_without:email",
            "email" => "email|required_without:username",
            "password" => [
                "required",
                "string"
            ],

        ];
    }

    /**
     * Personalised messages.
     */
    public function messages()
    {
        return [
            'username.max' => 'Le nom d\'utilisateur ne peut pas dépasser 255 caractères.',
            'username.required_without' => 'Le nom d\'utilisateur est requis si l\'email n\'est pas renseigné.',

            'email.email' => 'Veuillez entrer une adresse e-mail valide.',
            'email.required_without' => 'L\'email est requis si le nom d\'utilisateur n\'est pas renseigné.',

            'password.required' => 'Le champ mot de passe est obligatoire.'
        ];
    }
}
