<?php

namespace Database\Factories;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'start_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'free_trial_end_date' => $this->faker->dateTimeBetween('now', '+2 weeks'),
            'type' => 'monthly', // ou 'yearly', selon ton application
            'stripe_id' => 'sub_' . Str::random(12),
            'stripe_status' => 'active', // nouveau champ ajouté
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
