<?php

namespace App\Validators;

use Illuminate\Foundation\Http\FormRequest;

class UserValidator extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette demande.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; 
    }

    /**
     * Obtenez les règles de validation qui s'appliquent à la requête.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'lastname' => 'required|string|max:255', 
            'firstname' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username|max:255', 
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8|confirmed', 
            'password_requested_at' => 'nullable|date', 
            'phone_number' => 'nullable|string|max:20', 
            'address_id' => 'nullable|exists:addresses,id',
            'created_at' => 'nullable|date', 
            'updated_at' => 'nullable|date', 
            'deleted_at' => 'nullable|date',
        ];
    }

    public function messages()
{
    return [
        'lastname.required' => 'Le nom de famille est requis.',
        'lastname.string' => 'Le nom de famille doit être une chaîne de caractères.',
        'lastname.max' => 'Le nom de famille ne peut pas dépasser 255 caractères.',

        'firstname.required' => 'Le prénom est requis.',
        'firstname.string' => 'Le prénom doit être une chaîne de caractères.',
        'firstname.max' => 'Le prénom ne peut pas dépasser 255 caractères.',

        'username.required' => "Le nom d'utilisateur est requis.",
        'username.string' => "Le nom d'utilisateur doit être une chaîne de caractères.",
        'username.unique' => "Ce nom d'utilisateur est déjà utilisé.",
        'username.max' => "Le nom d'utilisateur ne peut pas dépasser 255 caractères.",

        'email.required' => "L'adresse e-mail est requise.",
        'email.email' => "L'adresse e-mail doit être valide.",
        'email.unique' => "Cette adresse e-mail est déjà utilisée.",
        'email.max' => "L'adresse e-mail ne peut pas dépasser 255 caractères.",
        
        'password.required' => 'Le mot de passe est requis.',
        'password.string' => 'Le mot de passe doit être une chaîne de caractères.',
        'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',

        'password_requested_at.date' => 'La date de demande de mot de passe doit être une date valide.',

        'phone_number.string' => 'Le numéro de téléphone doit être une chaîne de caractères.',
        'phone_number.max' => 'Le numéro de téléphone ne peut pas dépasser 20 caractères.',

        'has_newsletter.boolean' => 'Le champ newsletter doit être vrai ou faux.',

        'address_id.exists' => "L'adresse sélectionnée n'existe pas.",

        'created_at.date' => 'La date de création doit être une date valide.',
        'updated_at.date' => 'La date de mise à jour doit être une date valide.',
        'deleted_at.date' => 'La date de suppression doit être une date valide.',
    ];
}

}
