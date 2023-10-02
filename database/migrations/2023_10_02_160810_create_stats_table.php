<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    ///////////////////////////////////////////////////////////////////////////
    public function up(): void {
        Log::info('Migration to create table stats started...');

        $this->createTable();

        Log::info('Migration to create table stats completed!');
    }

    ///////////////////////////////////////////////////////////////////////////
    public function down(): void {
        Log::info('Rollback migration to drop table stats started...');
        
        $this->dropTable();

        Log::info('Rollback migration to drop table stats completed!');
    }

    ///////////////////////////////////////////////////////////////////////////
    private function createTable(): void {
        Schema::create('stats', function (Blueprint $table) {
            $table->id();
            $table->morphs('statsable');
            $table->unsignedBigInteger('total_impressions')->default(0);
            $table->unsignedBigInteger('total_likes')->default(0);
            $table->timestamps();

            // since the relationship of all models to the Stats model
            // is of one-to-one type, duplicated entries for a single record
            // should naturally not be allowed
            $table->unique(['statsable_type', 'statsable_id'], 'statsable_unique');
        });
    }

    ///////////////////////////////////////////////////////////////////////////
    private function dropTable(): void {
        Schema::dropIfExists('stats');
    }
};
