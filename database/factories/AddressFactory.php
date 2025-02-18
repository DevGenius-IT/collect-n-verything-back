<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      "street" => fake()->streetAddress,
      "additional" => fake()->optional()->secondaryAddress,
      "locality" => fake()->optional()->city,
      "zip_code" => fake()->postcode,
      "city" => fake()->city,
      "department" => ucfirst(fake()->word()),
      "country" => fake()->country,
    ];
  }
}
