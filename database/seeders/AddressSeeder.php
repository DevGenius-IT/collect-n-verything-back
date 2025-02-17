<?php

namespace Database\Seeders;

use App\Models\Address;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    Address::factory()->count(10, 50)->create();
  }
}
