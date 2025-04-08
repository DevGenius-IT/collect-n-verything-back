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
    Schema::create("website_we", function (Blueprint $table) {
      $table->id("we_id");
      $table->string('we_name');
      $table->string('we_domain');
      $table->unsignedBigInteger('us_id')->nullable();
      $table->foreign('us_id')->references('us_id')->on('user_us')->onDelete('cascade');
      $table->softDeletes('we_deleted_at')->nullable();
      $table->timestamp('we_created_at');
      $table->timestamp('we_updated_at')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists("website_we");
  }
};
