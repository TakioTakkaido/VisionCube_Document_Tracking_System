<?php

// VISION CUBE SOFTWARE CO. 
// Migration: Documents
// Creates the table for the document of the document tracking system
// Contributor/s: 
// Calulut, Joshua Miguel C.

// Enums Used
use App\DocumentStatus;
use App\DocumentType;

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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->default(DocumentType::DEFAULT->value);
            $table->string('status')->default(DocumentStatus::DEFAULT->value);
            $table->unsignedBigInteger('owner_id');
            $table->string('file');
            $table->timestamps();

            // Foreign key established with: User
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
