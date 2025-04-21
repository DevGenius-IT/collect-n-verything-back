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
    Schema::create("subscription", function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('user_id')->nullable();
      $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
      $table->unsignedBigInteger('pack_id')->nullable();
      $table->foreign('pack_id')->references('id')->on('pack')->onDelete('cascade');   
      $table->dateTime('start_date');
      $table->dateTime('free_trial_end_date')->nullable();  
      $table->timestamps();
      $table->softDeletes();
      $table->unique(['user_id', 'pack_id']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists("subscription");
  }
};
