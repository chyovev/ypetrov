<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    ///////////////////////////////////////////////////////////////////////////
    public function up(): void {
        Log::info('Migration to create table contact_messages started...');

        $this->createTable();

        Log::info('Migration to create table contact_messages completed!');
    }

    ///////////////////////////////////////////////////////////////////////////
    public function down(): void {
        Log::info('Rollback migration to drop table contact_messages started...');
        
        $this->dropTable();

        Log::info('Rollback migration to drop table contact_messages completed!');
    }

    ///////////////////////////////////////////////////////////////////////////
    private function createTable(): void {
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_read')->default(false);
            $table->string('name');
            $table->string('email')->nullable();
            $table->text('message');
            $table->string('ip_hash', 64); // the IP is hashed using the sha256 altorithm which is exactly 64 characters long
            $table->timestamps();

            // premature optimization is the root of all evil,
            // but these keys *will* be used, so... ¯\_(ツ)_/¯
            $table->index('is_read', 'unread_messages_index');
            $table->index('ip_hash', 'message_ip_index');
        });
    }

    ///////////////////////////////////////////////////////////////////////////
    private function dropTable(): void {
        Schema::dropIfExists('contact_messages');
    }
};
