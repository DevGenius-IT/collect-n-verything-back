<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PackSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        DB::table('pack')->insert([
            'name' => $faker->word,
            'price' => $faker->randomFloat(2, 5, 500),
            'features' => implode(', ', $faker->sentences(3)),
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);
    }
}
