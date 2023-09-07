<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    ///////////////////////////////////////////////////////////////////////////
    public function up(): void {
        Log::info('Migration to create table essays started...');

        $this->createTable();

        Log::info('Migration to create table essays completed!');
    }

    ///////////////////////////////////////////////////////////////////////////
    public function down(): void {
        Log::info('Rollback migration to drop table essays started...');
        
        $this->dropTable();

        Log::info('Rollback migration to drop table essays completed!');
    }

    ///////////////////////////////////////////////////////////////////////////
    private function createTable(): void {
        Schema::create('essays', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active')->default(false);
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('text')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });
    }

    ///////////////////////////////////////////////////////////////////////////
    private function dropTable(): void {
        Schema::dropIfExists('essays');
    }
};
