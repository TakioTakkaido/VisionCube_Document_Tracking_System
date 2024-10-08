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
        Schema::table('accounts', function (Blueprint $table) {
            $table->boolean('canUpload')->default(true);
            $table->boolean('canEdit')->default(true);
            $table->boolean('canMove')->default(true);
            $table->boolean('canArchive')->default(true);
            $table->boolean('canDownload')->default(true);
            $table->boolean('canPrint')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn('canUpload');
            $table->dropColumn('canEdit');
            $table->dropColumn('canMove');
            $table->dropColumn('canArchive');
            $table->dropColumn('canDownload');
            $table->dropColumn('canPrint');
        });
    }
};
