<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create("address_ad", function (Blueprint $table) {
      $table->id();
      $table->string('ad_country');
      $table->string('ad_city');
      $table->string('ad_postal_code');
      $table->string('ad_streetname');
      $table->string('ad_number');
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists("addresses");
  }
};
