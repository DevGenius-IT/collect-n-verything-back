<?php

namespace Database\Seeders;

use App\Models\Pack;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionSeeder extends Seeder
{
    public function run()
    {

        DB::table('subscription')->insert([
            'user_id'            => User::inRandomOrder()->first()->id,
            'pack_id'            => Pack::inRandomOrder()->first()->id,
            'start_date'         => now(),
            'free_trial_end_date' => now()->addDays(7),
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
            'type' => "type",
            'stripe_id' => 1,
            'stripe_status' => "Good"
        ]);
    }
}
