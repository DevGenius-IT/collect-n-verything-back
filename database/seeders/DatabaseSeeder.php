<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            AddressSeeder::class,
            UserSeeder::class,
            WebsiteSeeder::class,
            QuestionSeeder::class,
            AnswerSeeder::class,
        ]);
    }
}
