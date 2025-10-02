<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $types = ['admin', 'superadmin', 'client'];

        $adminEmail = env("ADMIN_EMAIL");
        $adminPassword = env("ADMIN_PASSWORD");
        $adminLastname = env("ADMIN_LASTNAME") ?? $faker->lastName();
        $adminFirstname = env("ADMIN_FIRSTNAME") ?? $faker->firstName();
        $adminUsername = env("ADMIN_USERNAME") ?? $faker->userName;

        if ($adminEmail && $adminPassword) {
            $admin = User::firstOrCreate(
                ['email' => $adminEmail],
                [
                    'username' => $adminUsername,
                    'lastname' => $adminLastname,
                    'firstname' => $adminFirstname,
                    'password' => bcrypt($adminPassword),
                    'phone_number' => $faker->phoneNumber,
                    'type' => 'superadmin',
                ]
            );

            if (!$admin->stripe_id) {
                $admin->createAsStripeCustomer();
            }
        }

        foreach (range(1, 20) as $index) {
            $user = User::create([
                'username' => $faker->userName,
                'lastname' => $faker->lastName,
                'firstname' => $faker->firstName,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt('password123'),
                'phone_number' => $faker->phoneNumber,
                'type' => $types[array_rand($types)],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $user->createAsStripeCustomer();
        }
    }
}
