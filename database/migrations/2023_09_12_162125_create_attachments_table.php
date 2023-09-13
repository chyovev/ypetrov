<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    ///////////////////////////////////////////////////////////////////////////
    public function up(): void {
        Log::info('Migration to create table attachments started...');

        $this->createTable();

        Log::info('Migration to create table attachments completed!');
    }

    ///////////////////////////////////////////////////////////////////////////
    public function down(): void {
        Log::info('Rollback migration to drop table attachments started...');
        
        $this->dropTable();

        Log::info('Rollback migration to drop table attachments completed!');
    }

    ///////////////////////////////////////////////////////////////////////////
    private function createTable(): void {
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->morphs('attachable');
            $table->string('original_file_name');
            $table->string('server_file_name');
            $table->string('caption')->nullable();
            $table->unsignedBigInteger('file_size')->default(0);
            $table->string('mime_type');
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            // a single attachable object cannot have more
            // than one file with the same server file name
            $table->unique(['attachable_type', 'attachable_id', 'server_file_name'], 'attachment_unique');

        });
    }

    ///////////////////////////////////////////////////////////////////////////
    private function dropTable(): void {
        Schema::dropIfExists('attachments');
    }
};
