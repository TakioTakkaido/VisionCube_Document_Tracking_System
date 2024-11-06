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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->boolean('maintenance')->default(false);
            $table->string('detail')->nullable();
            $table->json('access')->nullable();
            $table->json('addedParticipant')->nullable();
            $table->json('deletedParticipant')->nullable();
            $table->json('addedParticipantGroup')->nullable();
            $table->json('deletedParticipantGroup')->nullable();
            $table->json('updatedParticipant')->nullable();
            $table->json('updatedParticipantGroup')->nullable();
            $table->json('addedType')->nullable();
            $table->json('deletedType')->nullable();
            $table->json('addedStatus')->nullable();
            $table->json('deletedStatus')->nullable();
            $table->string('fileExtensions')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
