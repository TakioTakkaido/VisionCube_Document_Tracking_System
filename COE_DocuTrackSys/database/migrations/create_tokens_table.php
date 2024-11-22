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
        Schema::create('reset_password_tokens', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('account_id');

            $table->string('token');
            $table->boolean('used');

            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
        });

        Schema::create('email_verification_tokens', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('account_id');

            $table->string('token');
            $table->boolean('used');

            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
        });

        Schema::create('drive_email_verification_tokens', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('drive_id');

            $table->string('token');
            $table->boolean('used');

            $table->foreign('drive_id')->references('id')->on('drives')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reset_password_tokens');
        Schema::dropIfExists('email_verification_tokens');
    }
};
