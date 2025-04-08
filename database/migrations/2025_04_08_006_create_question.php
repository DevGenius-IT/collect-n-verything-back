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
    Schema::create("question_qu", function (Blueprint $table) {
      $table->id("qu_id");
      $table->string('qu_title');
      $table->string('qu_body');
      $table->unsignedBigInteger('us_id')->nullable();
      $table->foreign('us_id')->references('us_id')->on('user_us')->onDelete('cascade');
      $table->softDeletes('qu_deleted_at')->nullable();
      $table->timestamp('qu_created_at');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists("question_qu");
  }
};
