<?php

namespace App\Validators;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserValidator
{
    /**
     * Valide les données d'un utilisateur.
     *
     * @param array $data
     * @return array
     * @throws ValidationException
     */
    public static function validate(array $data): array
    {
        // Créer un validateur avec les règles et messages définis
        $validator = Validator::make($data, self::rules(), self::messages());

        // Si la validation échoue, on lance une exception avec les erreurs
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Retourne les données validées
        return $validator->validated();
    }


    /**
     * Règles de validation.
     */
    public static function rules(): array
    {
        return [
            'us_lastname'     => 'required|string|max:255',
            'us_firstname'    => 'required|string|max:255',
            'us_username'     => 'required|string|unique:user_us,us_username|max:255',
            'us_email'        => 'required|email|unique:user_us,us_email|max:255',
            'us_password'     => 'required|string|min:8',
            'us_phone_number' => 'nullable|string|max:20',
            'us_type'         => 'nullable|string|in:USER,ADMIN',
            'ad_id'           => 'nullable|exists:address_ad,ad_id',
            'us_created_at'   => 'nullable|date',
            'us_updated_at'   => 'nullable|date',
            'us_deleted_at'   => 'nullable|date',
        ];
    }

    /**
     * Messages personnalisés.
     */
    public static function messages(): array
    {
        return [
            'us_lastname.required'     => 'Le nom de famille est requis.',
            'us_lastname.string'       => 'Le nom de famille doit être une chaîne.',
            'us_lastname.max'          => 'Le nom de famille ne peut dépasser 255 caractères.',

            'us_firstname.required'    => 'Le prénom est requis.',
            'us_firstname.string'      => 'Le prénom doit être une chaîne.',
            'us_firstname.max'         => 'Le prénom ne peut dépasser 255 caractères.',

            'us_username.required'     => "Le nom d'utilisateur est requis.",
            'us_username.string'       => "Le nom d'utilisateur doit être une chaîne.",
            'us_username.unique'       => "Ce nom d'utilisateur est déjà utilisé.",
            'us_username.max'          => "Le nom d'utilisateur ne peut dépasser 255 caractères.",

            'us_email.required'        => "L'adresse e-mail est requise.",
            'us_email.email'           => "L'adresse e-mail doit être valide.",
            'us_email.unique'          => "Cette adresse e-mail est déjà utilisée.",
            'us_email.max'             => "L'adresse e-mail ne peut dépasser 255 caractères.",

            'us_password.required'     => 'Le mot de passe est requis.',
            'us_password.string'       => 'Le mot de passe doit être une chaîne.',
            'us_password.min'          => 'Le mot de passe doit contenir au moins 8 caractères.',

            'us_phone_number.string'   => 'Le numéro de téléphone doit être une chaîne.',
            'us_phone_number.max'      => 'Le numéro de téléphone ne peut dépasser 20 caractères.',

            'us_type.in'               => "Le type d'utilisateur doit être USER ou ADMIN.",

            'ad_id.exists'             => "L'adresse sélectionnée n'existe pas.",

            'us_created_at.date'       => 'La date de création doit être une date valide.',
            'us_updated_at.date'       => 'La date de mise à jour doit être une date valide.',
            'us_deleted_at.date'       => 'La date de suppression doit être une date valide.',
        ];
    }
}
