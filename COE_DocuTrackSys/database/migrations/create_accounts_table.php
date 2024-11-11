<?php

// VISION CUBE SOFTWARE CO. 
// Migration: User
// Creates the table for the users of the document tracking system
// Contributor/s: 
// Calulut, Joshua Miguel C.

// Enums Used
use App\AccountRole;

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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('email_verification_token')->nullable();
            $table->string('reset_password_token')->nullable();
            $table->string('password');
            $table->string('role');
            $table->boolean('deactivated')->default(false);
            $table->rememberToken();

            $table->boolean('canUpload')->default(false);
            $table->boolean('canEdit')->default(false);
            $table->boolean('canMove')->default(false);
            $table->boolean('canArchive')->default(false);
            $table->boolean('canDownload')->default(false);
            $table->boolean('canPrint')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('accounts');
    }
};
