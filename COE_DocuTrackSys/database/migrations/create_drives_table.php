<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('drives', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->timestamp('verified_at')->nullable();
            $table->boolean('disabled')->default(false);
            $table->boolean('canReport')->default(false);
            $table->boolean('canDocument')->default(false);
            $table->string('refresh_token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void{
        Schema::dropIfExists('drives');
    }
};
