<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    ///////////////////////////////////////////////////////////////////////////
    public function up(): void {
        Log::info('Migration to create table impressions started...');

        $this->createTable();

        Log::info('Migration to create table impressions completed!');
    }

    ///////////////////////////////////////////////////////////////////////////
    public function down(): void {
        Log::info('Rollback migration to drop table impressions started...');
        
        $this->dropTable();

        Log::info('Rollback migration to drop table impressions completed!');
    }

    ///////////////////////////////////////////////////////////////////////////
    private function createTable(): void {
        Schema::create('impressions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stats_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('ip_hash', 64); // the IP is hashed using the sha256 altorithm which is exactly 64 characters long
            $table->timestamps();
        });
    }

    ///////////////////////////////////////////////////////////////////////////
    private function dropTable(): void {
        Schema::dropIfExists('impressions');
    }
};
