<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AddressSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 20) as $index) {
            DB::table('address')->insert([
                'country'      => $faker->country(),
                'city'         => $faker->city(),
                'postal_code'  => $faker->postcode(),
                'streetname'   => $faker->streetName(),
                'number'       => $faker->buildingNumber(),
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ]);
        }
    }
}
