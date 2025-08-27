<?php

namespace App\Validators;

use App\Models\User;

class UserValidator
{

    /**
     * Validation rules.
     */
    public static function rules(): array
    {
        return [
            'lastname'     => 'required|string|max:255',
            'firstname'    => 'required|string|max:255',
            'username'     => 'required|string|unique:user,username|max:255',
            'email'        => 'required|email|unique:user,email|max:255',
            'password'     => 'required|string|min:8',
            'phone_number' => 'nullable|string|max:20',
            'type'         => 'nullable|in:' . implode(',', User::getTypes()),
            'address_id'   => 'nullable|exists:address,id',
            'created_at'   => 'nullable|date',
            'updated_at'   => 'nullable|date',
            'deleted_at'   => 'nullable|date',
        ];
    }

    /**
     * Personalised messages.
     */
    public static function messages(): array
    {
        return [
            'lastname.required'     => 'Le nom de famille est requis.',
            'lastname.string'       => 'Le nom de famille doit être une chaîne.',
            'lastname.max'          => 'Le nom de famille ne peut dépasser 255 caractères.',

            'firstname.required'    => 'Le prénom est requis.',
            'firstname.string'      => 'Le prénom doit être une chaîne.',
            'firstname.max'         => 'Le prénom ne peut dépasser 255 caractères.',

            'username.required'     => "Le nom d'utilisateur est requis.",
            'username.string'       => "Le nom d'utilisateur doit être une chaîne.",
            'username.unique'       => "Ce nom d'utilisateur est déjà utilisé.",
            'username.max'          => "Le nom d'utilisateur ne peut dépasser 255 caractères.",

            'email.required'        => "L'adresse e-mail est requise.",
            'email.email'           => "L'adresse e-mail doit être valide.",
            'email.unique'          => "Cette adresse e-mail est déjà utilisée.",
            'email.max'             => "L'adresse e-mail ne peut dépasser 255 caractères.",

            'password.required'     => 'Le mot de passe est requis.',
            'password.string'       => 'Le mot de passe doit être une chaîne.',
            'password.min'          => 'Le mot de passe doit contenir au moins 8 caractères.',

            'phone_number.string'   => 'Le numéro de téléphone doit être une chaîne.',
            'phone_number.max'      => 'Le numéro de téléphone ne peut dépasser 20 caractères.',

            'address_id.exists'     => "L'adresse sélectionnée n'existe pas.",

            'created_at.date'       => 'La date de création doit être une date valide.',
            'updated_at.date'       => 'La date de mise à jour doit être une date valide.',
            'deleted_at.date'       => 'La date de suppression doit être une date valide.',
        ];
    }
}
