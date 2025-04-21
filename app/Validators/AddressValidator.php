<?php

namespace App\Validators;

class AddressValidator
{

    /**
     * Validation rules.
     */
    public static function rules(): array
    {
        return [
            'country'      => 'required|string|max:255',
            'city'         => 'required|string|max:255',
            'postal_code'  => 'required|string|max:20',
            'streetname'   => 'required|string|max:255',
            'number'       => 'required|string|max:10',
        ];
    }

    /**
     * Personalised messages.
     */
    public static function messages(): array
    {
        return [
            'country.required'     => 'Le pays est requis.',
            'country.string'       => 'Le pays doit être une chaîne de caractères.',
            'country.max'          => 'Le pays ne peut pas dépasser 255 caractères.',

            'city.required'        => 'La ville est requise.',
            'city.string'          => 'La ville doit être une chaîne de caractères.',
            'city.max'             => 'La ville ne peut pas dépasser 255 caractères.',

            'postal_code.required' => 'Le code postal est requis.',
            'postal_code.string'   => 'Le code postal doit être une chaîne de caractères.',
            'postal_code.max'      => 'Le code postal ne peut pas dépasser 20 caractères.',

            'streetname.required'  => 'Le nom de rue est requis.',
            'streetname.string'    => 'Le nom de rue doit être une chaîne de caractères.',
            'streetname.max'       => 'Le nom de rue ne peut pas dépasser 255 caractères.',

            'number.required'      => 'Le numéro est requis.',
            'number.string'        => 'Le numéro doit être une chaîne de caractères.',
            'number.max'           => 'Le numéro ne peut pas dépasser 10 caractères.',
        ];
    }
}
