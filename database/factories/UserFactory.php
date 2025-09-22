<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'lastname' => $this->faker->lastName(),
            'firstname' => $this->faker->firstName(),
            'username' => $this->faker->unique()->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'), // mot de passe par défaut pour les tests
            'phone_number' => $this->faker->phoneNumber(),
            'type' => $this->faker->randomElement(User::getTypes()),
            'address_id' => Address::factory(), // crée une adresse associée
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Optionnel : générer un utilisateur admin
     */
    public function admin()
    {
        return $this->state(fn (array $attributes) => [
            'type' => User::TYPE_ADMIN,
        ]);
    }

    /**
     * Optionnel : générer un utilisateur superadmin
     */
    public function superAdmin()
    {
        return $this->state(fn (array $attributes) => [
            'type' => User::TYPE_SUPERADMIN,
        ]);
    }

    /**
     * Optionnel : générer un utilisateur client
     */
    public function client()
    {
        return $this->state(fn (array $attributes) => [
            'type' => User::TYPE_CLIENT,
        ]);
    }
}
