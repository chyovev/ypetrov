<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    ///////////////////////////////////////////////////////////////////////////
    public function up(): void {
        Log::info('Migration to create table comments started...');

        $this->createTable();

        Log::info('Migration to create table comments completed!');
    }

    ///////////////////////////////////////////////////////////////////////////
    public function down(): void {
        Log::info('Rollback migration to drop table comments started...');
        
        $this->dropTable();

        Log::info('Rollback migration to drop table comments completed!');
    }

    ///////////////////////////////////////////////////////////////////////////
    private function createTable(): void {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->morphs('commentable');
            $table->string('name');
            $table->text('message');
            $table->string('ip_hash', 64); // the IP is hashed using the sha256 altorithm which is exactly 64 characters long
            $table->softDeletes();
            $table->timestamps();

            $table->index('ip_hash', 'comment_ip_index');
        });
    }

    ///////////////////////////////////////////////////////////////////////////
    private function dropTable(): void {
        Schema::dropIfExists('comments');
    }
};
