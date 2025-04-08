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
            'lastname' => 'required|string|max:255', // Nom de famille obligatoire
            'firstname' => 'required|string|max:255', // Prénom obligatoire
            'username' => 'required|string|unique:users,username|max:255', // Identifiant unique et obligatoire
            'email' => 'required|email|unique:users,email|max:255', // Email unique et obligatoire
            'enabled' => 'boolean', // Champ booléen
            'password' => 'required|string|min:8|confirmed', // Mot de passe obligatoire, min 8 caractères et confirmation
            'password_requested_at' => 'nullable|date', // Date de la demande de réinitialisation du mot de passe (optionnel)
            'phone_number' => 'nullable|string|max:20', // Numéro de téléphone (optionnel)
            'has_newsletter' => 'boolean', // Champ booléen pour l'option de newsletter
            'address_id' => 'nullable|exists:addresses,id', // Identifiant d'adresse (optionnel, et doit exister dans la table addresses)
            'google_id' => 'nullable|string|max:255', // Google ID (optionnel)
            'created_at' => 'nullable|date', // Date de création (optionnel)
            'updated_at' => 'nullable|date', // Date de mise à jour (optionnel)
            'deleted_at' => 'nullable|date', // Date de suppression (optionnel)
        ];
    }
}
