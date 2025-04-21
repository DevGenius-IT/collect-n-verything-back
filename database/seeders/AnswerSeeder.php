<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AnswerSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        DB::table('answer')->insert([
            'body' => $faker->paragraph(3),
            'question_id' => rand(1, 5),
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);
    }
}
