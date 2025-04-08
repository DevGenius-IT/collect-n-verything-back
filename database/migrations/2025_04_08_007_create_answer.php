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
    Schema::create("answer_an", function (Blueprint $table) {
      $table->id("an_id");
      $table->string('an_body');
      $table->unsignedBigInteger('qu_id')->nullable();
      $table->foreign('qu_id')->references('qu_id')->on('question_qu')->onDelete('cascade');
      $table->softDeletes('an_deleted_at')->nullable();
      $table->timestamp('an_created_at');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists("answer_an");
  }
};
