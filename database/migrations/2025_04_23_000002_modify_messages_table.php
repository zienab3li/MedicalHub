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
        Schema::table('messages', function (Blueprint $table) {
            // Drop existing foreign keys
            $table->dropForeign(['sender_id']);
            $table->dropForeign(['receiver_id']);
            
            // Drop existing columns
            $table->dropColumn(['sender_id', 'receiver_id']);
            
            // Add new columns
            $table->foreignId('conversation_id')->constrained('conversations')->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->enum('sender_type', ['user', 'doctor'])->default('user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            // Drop new foreign keys
            $table->dropForeign(['conversation_id']);
            $table->dropForeign(['sender_id']);
            
            // Drop new columns
            $table->dropColumn(['conversation_id', 'sender_type']);
            
            // Add back original columns
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receiver_id')->constrained('doctors')->onDelete('cascade');
        });
    }
}; 