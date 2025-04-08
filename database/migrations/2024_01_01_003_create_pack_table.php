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
    Schema::create("pack_pa", function (Blueprint $table) {
      $table->id("pa_id");
      $table->string('pa_name');
      $table->string('pa_price');
      $table->string('pa_features');
      $table->timestamp('pa_created_at');
      $table->timestamp('pa_updated_at')->nullable();
      $table->softDeletes($column="pa_deleted_at");
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists("pack_pa");
  }
};
