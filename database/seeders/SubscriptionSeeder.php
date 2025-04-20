<?php

namespace Database\Seeders;

use App\Models\Pack;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class SubscriptionSeeder extends Seeder
{
    public function run()
    {
        // Insérer des données dans la table `user`
        $faker = Faker::create();

        DB::table('subscription')->insert([
            'user_id'            => User::inRandomOrder()->first()->id,
            'pack_id'            => Pack::inRandomOrder()->first()->id, 
            'start_date'         => now(), 
            'free_trial_end_date'=> now()->addDays(7), 
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);
    }
}
