<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class QuestionSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        DB::table('question')->insert([
            'title' => $faker->sentence,
            'body' => $faker->paragraph(4),
            'user_id' => rand(1, 5),
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);
    }
}
