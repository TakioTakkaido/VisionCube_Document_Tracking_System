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
        Schema::table('documents', function (Blueprint $table) {
            $table->unsignedBigInteger('series_number')->nullable();
            $table->unsignedBigInteger('memo_number')->nullable();
            $table->date('document_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('series_number');
            $table->dropColumn('memo_number');
            $table->dropColumn('document_date');
        });
    }
};
