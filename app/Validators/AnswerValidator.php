<?php

namespace App\Validators;


class AnswerValidator
{

    /**
     * Validation rules.
     */
    public static function rules(): array
    {
        return [
            'body' => 'required|string',
            'question_id' => 'nullable|exists:question,id',
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

            'question_id.exists' => 'La question associée n’existe pas.',
        ];
    }
}
