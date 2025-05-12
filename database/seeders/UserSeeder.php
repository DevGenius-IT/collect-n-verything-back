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
        $types = ['admin', 'superadmin', 'client'];

        $adminEmail = Env("ADMIN_EMAIL");
        $adminPassword = Env("ADMIN_PASSWORD");
        $adminLastname = Env("ADMIN_LASTNAME") ?? $faker->lastName();
        $adminFirstname = Env("ADMIN_FIRSTNAME") ?? $faker->firstName();
        $adminUsername = Env("ADMIN_USERNAME") ?? $faker->userName;

        if ($adminEmail && $adminPassword) {
            DB::table('user')->insert([
                'username' => $adminUsername,
                'lastname' => $adminLastname,
                'firstname' => $adminFirstname,
                'email' => $adminEmail,
                'password' => bcrypt($adminPassword),
                'phone_number' => $faker->phoneNumber,
                'type' => 'superadmin',
                'stripe_id' => $faker->optional()->uuid,
            ]);
        }

        foreach (range(1, 20) as $index) {
            DB::table('user')->insert([
                'username' => $faker->userName,
                'lastname' => $faker->lastName,
                'firstname' => $faker->firstName,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt('password123'),
                'phone_number' => $faker->phoneNumber,
                'type' => $types[array_rand($types)],
                'stripe_id' => $faker->optional()->uuid,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
                'address_id' => null,
            ]);
        }
    }
}
