<?php

namespace App\Http\Requests;

use App\Enums\RolesEnum;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SignupRequest extends FormRequest
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
            "lastname" => "required|string",
            "firstname" => "required|string",
            "username" => "required|string|unique:user,username",
            "email" => "required|email|unique:user,email",
            "password" => [
                "required",
                "string",
                "min:6",
                "regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).+$/",

            ],
            "password_confirmation" => ['required', 'same:password'],
            "phone_number" => ["nullable", "string", "regex:/^\+?[1-9]\d{1,14}$/"],
            'type'         => 'required|in:' . implode(',', User::getTypes()),
        ];
    }

    /**
     * Personalised messages.
     */
    public function messages()
    {
        return [
            'username.required' => 'Le champ nom d\'utilisateur est obligatoire.',
            'username.unique' => 'Ce nom d\'utilisateur est déjà pris.',
            'username.max' => 'Le nom d\'utilisateur ne peut pas dépasser 255 caractères.',

            'lastname.required' => 'Le champ nom de famille est obligatoire.',
            'lastname.max' => 'Le nom de famille ne peut pas dépasser 255 caractères.',

            'firstname.required' => 'Le champ prénom est obligatoire.',
            'firstname.max' => 'Le prénom ne peut pas dépasser 255 caractères.',

            'email.required' => 'Le champ adresse e-mail est obligatoire.',
            'email.email' => 'Veuillez entrer une adresse e-mail valide.',
            'email.unique' => 'Cette adresse e-mail est déjà enregistrée.',
            'email.max' => 'L\'adresse e-mail ne peut pas dépasser 255 caractères.',

            'password.required' => 'Le champ mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit comporter au moins 8 caractères.',

            'password_confirmation.same' => 'La confirmation du mot de passe doit être identique au mot de passe.',

            'phone_number.nullable' => 'Le numéro de téléphone peut être vide.',
            'phone_number.regex' => 'Le numéro de téléphone n\'est pas valide.', // Si tu as une validation spécifique pour les numéros de téléphone

            'type.required' => 'Le type est obligatoire.',
            'type.string' => 'Le type doit être une chaîne de caractères.',
            'type.in' => 'Le type doit être une valeur valide parmi les rôles autorisés.',

            'stripe_id.nullable' => 'Le champ stripe_id peut être vide.',

            'created_at.required' => 'La date de création est nécessaire.',
            'updated_at.required' => 'La date de mise à jour est nécessaire.',
            'deleted_at.nullable' => 'La date de suppression peut être vide.',
        ];
    }
}
