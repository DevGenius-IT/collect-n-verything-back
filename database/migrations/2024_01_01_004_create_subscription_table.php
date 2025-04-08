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
    Schema::create("subscription_su", function (Blueprint $table) {
      $table->id("su_id");
      $table->unsignedBigInteger('us_id')->nullable();
      $table->foreign('us_id')->references('us_id')->on('user_us')->onDelete('cascade');
      $table->unsignedBigInteger('pa_id')->nullable();
      $table->foreign('pa_id')->references('pa_id')->on('pack_pa')->onDelete('cascade');      
      $table->dateTime('su_start_date');
      $table->dateTime('su_free_trial_end_date')->nullable();  
      $table->timestamp('su_updated_at')->nullable();
      $table->softDeletes('su_cancellation_date')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists("subscription_su");
  }
};
