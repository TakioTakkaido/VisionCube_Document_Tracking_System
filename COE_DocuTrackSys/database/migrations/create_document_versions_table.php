<?php

// VISION CUBE SOFTWARE CO. 
// Migration: DocumentVersion
// Creates the table for the document versions of documents of the document tracking system
// Contributor/s: 
// Calulut, Joshua Miguel C.

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
        Schema::create('document_versions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->unsignedBigInteger('document_id');
            $table->unsignedBigInteger('version_number');
            $table->string('description');
            $table->string('modified_by');

            $table->string('type');
            $table->string('status');
            $table->string('sender');
            $table->json('senderArray')->nullable();
            $table->string('recipient');
            $table->json('recipientArray')->nullable();
            $table->string('subject');
            $table->string('assignee');
            $table->string('category');
            $table->unsignedBigInteger('series_number')->nullable();
            $table->unsignedBigInteger('memo_number')->nullable();
            $table->date('document_date');

            $table->string('previous_type')->default('N/A');
            $table->string('previous_status')->default('N/A');
            $table->string('previous_sender')->default('N/A');
            $table->string('previous_recipient')->default('N/A');
            $table->string('previous_subject')->default('N/A');
            $table->string('previous_assignee')->default('N/A');
            $table->string('previous_category')->default('N/A');
            $table->date('previous_document_date')->default('N/A');
            $table->unsignedBigInteger('previous_series_number')->nullable();
            $table->unsignedBigInteger('previous_memo_number')->nullable();

            // Foreign key established with: Document
            $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('document_versions');
    }
};