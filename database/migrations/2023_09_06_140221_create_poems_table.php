<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    ///////////////////////////////////////////////////////////////////////////
    public function up(): void {
        Log::info('Migration to create table poems started...');

        $this->createTable();

        Log::info('Migration to create table poems completed!');
    }

    ///////////////////////////////////////////////////////////////////////////
    public function down(): void {
        Log::info('Rollback migration to drop table poems started...');
        
        $this->dropTable();

        Log::info('Rollback migration to drop table poems completed!');
    }

    ///////////////////////////////////////////////////////////////////////////
    private function createTable(): void {
        Schema::create('poems', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active')->default(false);
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('dedication')->nullable();
            $table->text('text');
            $table->boolean('use_monospace_font')->default(true)->comment('monospace fonts benefit text alignment for certain poems');
            $table->timestamps();
        });
    }

    ///////////////////////////////////////////////////////////////////////////
    private function dropTable(): void {
        Schema::dropIfExists('poems');
    }
};
