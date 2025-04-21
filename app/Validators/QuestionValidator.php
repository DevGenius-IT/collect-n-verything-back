<?php

namespace App\Validators;


class QuestionValidator
{

    /**
     * Validation rules.
     */
    public static function rules(): array
    {
        return [
            'title' => 'required|string|max:255|unique:question,title',
            'body' => 'required|string',
            'user_id' => 'nullable|exists:user,id',
        ];
    }

    /**
     * Personalised messages.
     */
    public static function messages(): array
    {
        return [
            'title.required' => 'Le titre est requis.',
            'title.string'   => 'Le titre doit être une chaîne de caractères.',
            'title.max'      => 'Le titre ne peut pas dépasser 255 caractères.',
            'title.unique'   => "Ce titre est déjà utilisé.",

            'body.required'  => 'Le contenu est requis.',
            'body.string'    => 'Le contenu doit être une chaîne de caractères.',

            'user_id.exists' => "L'utilisateur spécifié n'existe pas.",
        ];
    }
}
