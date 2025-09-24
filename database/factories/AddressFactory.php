<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    public function definition()
    {
        return [
            'country' => $this->faker->country,
            'city' => $this->faker->city,
            'postal_code' => $this->faker->postcode,
            'streetname' => $this->faker->streetName,
            'number' => $this->faker->buildingNumber,
        ];
    }
}
