<?php

namespace App\Validators;


class SubscriptionValidator
{

    /**
     * Validation rules.
     */
    public static function rules(): array
    {
        return [
            'user_id'            => 'nullable|exists:users,id',
            'pack_id'            => 'nullable|exists:packs,id',
            'start_date'         => 'required|date|after_or_equal:today',
            'free_trial_end_date' => 'nullable|date|after:start_date',
        ];
    }

    /**
     * Personalised messages.
     */
    public static function messages(): array
    {
        return [
            'user_id.exists'                => 'L\'utilisateur sélectionné n\'existe pas.',
            'pack_id.exists'                => 'Le pack sélectionné n\'existe pas.',
            'start_date.required'           => 'La date de début est requise.',
            'start_date.date'               => 'La date de début doit être une date valide.',
            'start_date.after_or_equal'     => 'La date de début ne peut pas être dans le passé.',
            'free_trial_end_date.date'      => 'La date de fin d\'essai gratuit doit être une date valide.',
            'free_trial_end_date.after'     => 'La date de fin d\'essai gratuit doit être après la date de début.',
        ];
    }
}
