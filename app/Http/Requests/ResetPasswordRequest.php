<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
            "old_password" => [
                "required",
                "string"
            ],
            "password" => [
                "required",
                "string",
                "min:6",
                "regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).+$/",
                "different:old_password"
            ],
            "password_confirmation" => ['required', 'same:password'],
        ];
    }

    /**
     * Personalised messages.
     */
    public function messages(): array
    {
        return [
            'username.max' => 'Le nom d\'utilisateur ne peut pas dépasser 255 caractères.',
            'username.required_without' => 'Le nom d\'utilisateur est requis si l\'email n\'est pas renseigné.',

            'email.email' => 'Veuillez entrer une adresse e-mail valide.',
            'email.required_without' => 'L\'email est requis si le nom d\'utilisateur n\'est pas renseigné.',

            'old_password.required' => 'Le champ mot de passe est obligatoire.',

            'password.required' => 'Le champ nouveau mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit comporter au moins 8 caractères.',
            'password.different' => 'Le nouveau mot de passe doit être différent de l\'ancien.',

            'password_confirmation.same' => 'La confirmation du mot de passe doit être identique au nouveau mot de passe.',
        ];
    }
}
