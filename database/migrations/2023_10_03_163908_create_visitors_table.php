<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    ///////////////////////////////////////////////////////////////////////////
    public function up(): void {
        Log::info('Migration to create table visitors started...');

        $this->createTable();

        Log::info('Migration to create table visitors completed!');
    }

    ///////////////////////////////////////////////////////////////////////////
    public function down(): void {
        Log::info('Rollback migration to drop table visitors started...');
        
        $this->dropTable();

        Log::info('Rollback migration to drop table visitors completed!');
    }

    ///////////////////////////////////////////////////////////////////////////
    private function createTable(): void {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('ip_hash', 64); // the IP is hashed using the sha256 altorithm which is exactly 64 characters long
            $table->string('country_code', 2)->nullable(); // the country code will be fetched from an API, but if it fails, keep field empty

            // use descriptive column names for the timestamp columns
            // instead of the default created_at and updated_at names
            $table->timestamp('first_visit_date')->nullable();
            $table->timestamp('last_visit_date')->nullable();

            // each visitor gets identified by their IP address
            // so there should be no duplicates on the ip_hash field
            $table->unique(['ip_hash'], 'visitor_unique');
        });
    }

    ///////////////////////////////////////////////////////////////////////////
    private function dropTable(): void {
        Schema::dropIfExists('visitors');
    }

};
