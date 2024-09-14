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
        Schema::table('documents', function (Blueprint $table) {
            // Drop the foreign key constraint for 'owner_id' on the 'users' table
            $table->dropForeign(['owner_id']);

            // Add the foreign key constraint for 'owner_id' on the 'accounts' table
            $table->foreign('owner_id')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // Drop the foreign key constraint for 'owner_id' on the 'accounts' table
            $table->dropForeign(['owner_id']);

            // Restore the foreign key constraint for 'owner_id' on the 'users' table
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
