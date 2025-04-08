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
      $table->id("ad_id");
      $table->string('ad_country');
      $table->string('ad_city');
      $table->string('ad_postal_code');
      $table->string('ad_streetname');
      $table->string('ad_number');
      $table->timestamp('ad_created_at');
      $table->timestamp('ad_updated_at')->nullable();
      $table->softDeletes($column="ad_deleted_at");
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists("address_ad");
  }
};
