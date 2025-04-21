<?php

namespace App\Validators;


class WebsiteValidator
{

    /**
     * Validation rules.
     */
    public static function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:website,name',
            'domain' => 'required|string|url|max:255|unique:website,domain', 
            'user_id' => 'required|nullable|exists:user,id', 
        ];
    }

    /**
     * Personalised messages.
     */
    public static function messages(): array
    {
        return [
            'name.required' => 'Le nom du site est requis.',
            'name.string' => 'Le nom du site doit être une chaîne de caractères.',
            'name.max' => 'Le nom du site ne doit pas dépasser 255 caractères.',
            'name.unique' => 'Le nom du site est déjà utilisé.',
            'domain.required' => 'Le domaine est requis.',
            'domain.string' => 'Le domaine doit être une chaîne de caractères.',
            'domain.url' => 'Le domaine doit être une URL valide.',
            'domain.max' => 'Le domaine ne doit pas dépasser 255 caractères.',
            'domain.unique' => 'Ce domaine est déjà utilisé.',
            'user_id.exists' => 'L\'utilisateur spécifié n\'existe pas.',
            'user_id.required' => 'L\'utilisateur est requis.',
        ];
    }
}
