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
        Schema::table('documents', function(Blueprint $table){
            $table->string('sender');
            $table->string('recipient');
            $table->string('subject');
            $table->string('assignee');
            $table->string('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn([
                'sender',
                'recipient',
                'subject',
                'assignee',
                'category'
            ]);
        });
    }
};
