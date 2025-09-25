<?php

namespace App\Validators;

use App\Models\Contact;

class ContactValidator
{
    /**
     * Validation rules.
     */
    public static function rules(): array
    {
        return [
            'subject' => 'required|in:' . implode(',', Contact::getSubjects()),
            'email' => 'required|email|max:255',
            'body' => 'required|string',
            'created_at' => 'nullable|date',
            'updated_at' => 'nullable|date',
            'deleted_at' => 'nullable|date',
        ];
    }

    /**
     * Personalised messages.
     */
    public static function messages(): array
    {
        return [
            'body.required' => 'La réponse est requise.',
            'body.string' => 'La réponse doit être une chaîne de caractères.',

            'email.required' => "L'adresse e-mail est requise.",
            'email.email' => "L'adresse e-mail doit être valide.",
            'email.max' => "L'adresse e-mail ne peut dépasser 255 caractères.",

            'subject.required' => "Le sujet est requis.",
            'subject.in' => "Le sujet doit être " . implode(', ', Contact::getSubjects()) . ".",

            'created_at.date' => 'La date de création doit être une date valide.',
            'updated_at.date' => 'La date de mise à jour doit être une date valide.',
            'deleted_at.date' => 'La date de suppression doit être une date valide.',
        ];
    }
}
