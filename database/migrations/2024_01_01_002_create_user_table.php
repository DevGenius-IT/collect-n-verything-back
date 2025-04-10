<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_us', function (Blueprint $table) {
            $table->id('us_id');
            $table->string('us_username')->unique();
            $table->string('us_lastname');
            $table->string('us_firstname');
            $table->string('us_email')->unique();
            $table->string('us_password');
            $table->string('us_phone_number')->nullable();
            $table->string('us_type')->default("USER");
            $table->string('us_stripe_id')->nullable();
            $table->timestamp('us_created_at');
            $table->timestamp('us_updated_at')->nullable();
            $table->softDeletes($column="us_deleted_at"); //deleted_at
            $table->unsignedBigInteger('ad_id')->nullable();
            $table->foreign('ad_id')->references('ad_id')->on('address_ad')->onDelete('set null');            
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_us');
        Schema::dropIfExists('password_reset_tokens');
    }
};
