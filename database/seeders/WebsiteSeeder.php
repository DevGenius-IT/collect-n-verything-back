<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        DB::table('website')->insert([
            'name' => $faker->company,
            'domain' => $faker->domainName,
            'user_id' => User::inRandomOrder()->first()->id,
        ]);
    }
}
