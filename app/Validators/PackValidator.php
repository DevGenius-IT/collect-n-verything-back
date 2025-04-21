<?php

namespace App\Validators;


class PackValidator
{

    /**
     * Validation rules.
     */
    public static function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'price'    => 'required|numeric|min:0',
            'features' => 'nullable|string',
        ];
    }

    /**
     * Personalised messages.
     */
    public static function messages(): array
    {
        return [
            'name.required'     => 'Le nom du pack est requis.',
            'name.string'       => 'Le nom doit être une chaîne de caractères.',
            'name.max'          => 'Le nom ne peut pas dépasser 255 caractères.',

            'price.required'    => 'Le prix est requis.',
            'price.numeric'     => 'Le prix doit être un nombre.',
            'price.min'         => 'Le prix ne peut pas être négatif.',

            'features.string'   => 'Les fonctionnalités doivent être une chaîne de caractères.',
        ];
    }
}
