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
            $table->id();
            $table->string('us_username')->unique();
            $table->string('us_lastname');
            $table->string('us_firstname');
            $table->string('us_email')->unique();
            $table->string('us_password');
            $table->string('us_phone_number')->nullable();
            $table->string('us_type')->default("USER");
            $table->string('us_stripe_id')->nullable();
            $table->timestamps(); //created_at, updated_at
            $table->softDeletes(); //deleted_at
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_us');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
