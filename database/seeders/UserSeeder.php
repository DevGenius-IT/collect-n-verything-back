<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Insérer des données dans la table `user`
        $faker = Faker::create();

        DB::table('user')->insert([
            'username' => $faker->userName,
            'lastname' => $faker->lastName,
            'firstname' => $faker->firstName,
            'email' => $faker->unique()->safeEmail,
            'password' => bcrypt('password123'), // Mot de passe crypté
            'phone_number' => $faker->phoneNumber,
            'type' => 'USER', // Valeur par défaut
            'stripe_id' => $faker->optional()->uuid, // Stripe ID optionnel
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
            'address_id' => null,
        ]);
    }
}
