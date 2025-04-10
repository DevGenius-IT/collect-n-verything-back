<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Insérer des données dans la table `user_us`
        $faker = Faker::create();

        DB::table('user_us')->insert([
            'us_username' => $faker->userName,
            'us_lastname' => $faker->lastName,
            'us_firstname' => $faker->firstName,
            'us_email' => $faker->unique()->safeEmail,
            'us_password' => bcrypt('password123'), // Mot de passe crypté
            'us_phone_number' => $faker->phoneNumber,
            'us_type' => 'USER', // Valeur par défaut
            'us_stripe_id' => $faker->optional()->uuid, // Stripe ID optionnel
            'us_created_at' => now(),
            'us_updated_at' => now(),
            'us_deleted_at' => null,
            'ad_id' => null,
        ]);
    }
}
