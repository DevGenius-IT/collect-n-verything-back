<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
  /**
   * The current password being used by the factory.
   */
  protected static ?string $password;

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      "lastname" => fake()->lastName(),
      "firstname" => fake()->firstName(),
      "username" => fake()->unique()->userName(),
      "email" => fake()->unique()->safeEmail(),
      "enabled" => fake()->boolean(),
      "password" => (static::$password ??= Hash::make("password")),
      "password_requested_at" => null,
      "phone_number" => fake()->optional()->phoneNumber(),
      "has_newsletter" => fake()->boolean()
    ];
  }
}