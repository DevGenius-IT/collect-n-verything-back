<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 20) as $index) {
            DB::table('user')->insert([
                'username' => $faker->userName,
                'lastname' => $faker->lastName,
                'firstname' => $faker->firstName,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt('password123'),
                'phone_number' => $faker->phoneNumber,
                'type' => 'USER',
                'stripe_id' => $faker->optional()->uuid,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
                'address_id' => null,
            ]);
        }
    }
}
