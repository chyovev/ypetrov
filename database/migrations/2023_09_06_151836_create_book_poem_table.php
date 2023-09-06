<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    ///////////////////////////////////////////////////////////////////////////
    public function up(): void {
        Log::info('Migration to create table book_poem started...');

        $this->createTable();

        Log::info('Migration to create table book_poem completed!');
    }

    ///////////////////////////////////////////////////////////////////////////
    public function down(): void {
        Log::info('Rollback migration to drop table book_poem started...');
        
        $this->dropTable();

        Log::info('Rollback migration to drop table book_poem completed!');
    }

    ///////////////////////////////////////////////////////////////////////////
    private function createTable(): void {
        Schema::create('book_poem', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('poem_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            // even though the same poem can appear in multiple books,
            // it cannot appear in the same book more than once
            $table->unique(['book_id', 'poem_id'], 'book_poem_unique');
        });
    }

    ///////////////////////////////////////////////////////////////////////////
    private function dropTable(): void {
        Schema::dropIfExists('book_poem');
    }
};
