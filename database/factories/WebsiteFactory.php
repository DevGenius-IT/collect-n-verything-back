<?php

namespace Database\Factories;

use App\Models\Website;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class WebsiteFactory extends Factory
{
    protected $model = Website::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'domain' => $this->faker->domainName(),
            'name' => $this->faker->company(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
