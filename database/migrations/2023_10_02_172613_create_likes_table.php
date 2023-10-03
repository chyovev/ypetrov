<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    ///////////////////////////////////////////////////////////////////////////
    public function up(): void {
        Log::info('Migration to create table likes started...');

        $this->createTable();

        Log::info('Migration to create table likes completed!');
    }

    ///////////////////////////////////////////////////////////////////////////
    public function down(): void {
        Log::info('Rollback migration to drop table likes started...');
        
        $this->dropTable();

        Log::info('Rollback migration to drop table likes completed!');
    }

    ///////////////////////////////////////////////////////////////////////////
    private function createTable(): void {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stats_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('ip_hash', 64); // the IP is hashed using the sha256 altorithm which is exactly 64 characters long
            $table->timestamps();

            // a single stats record can only be “liked” once
            // from a single IP address, therefore both fields
            // should have the unique constraint
            $table->unique(['stats_id', 'ip_hash'], 'like_unique');
        });
    }

    ///////////////////////////////////////////////////////////////////////////
    private function dropTable(): void {
        Schema::dropIfExists('likes');
    }
};
