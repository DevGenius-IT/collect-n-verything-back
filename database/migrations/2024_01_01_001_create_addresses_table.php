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
    Schema::create("addresses", function (Blueprint $table) {
      $table->id();
      $table->string('street');
      $table->string('additional')->nullable();
      $table->string('locality')->nullable();
      $table->string('zip_code');
      $table->string('city');
      $table->string('department');
      $table->string('country');
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
