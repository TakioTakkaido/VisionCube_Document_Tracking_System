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
        Schema::create('folders', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->date('year')->nullable();
            $table->date('month')->nullable();
            $table->string('folder_id');
            $table->unsignedBigInteger('drive_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->timestamps();

            // Foreign key established with: Document
            $table->foreign('drive_id')->references('id')->on('drives')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('folders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('folders');
    }
};
