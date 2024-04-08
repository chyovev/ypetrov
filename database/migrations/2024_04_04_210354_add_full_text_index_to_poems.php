<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    ///////////////////////////////////////////////////////////////////////////
    public function up(): void {
        Log::info('Migration to add full text index to poems started...');
        
        $this->addIndex();

        Log::info('Migration to add full text index to poems completed!');
    }

    ///////////////////////////////////////////////////////////////////////////
    public function down(): void {
        Log::info('Rollback migration to remove full text index from poems started...');

        $this->dropIndex();

        Log::info('Rollback migration to remove full text index from poems completed!');
    }

    ///////////////////////////////////////////////////////////////////////////
    private function addIndex(): void {
        Schema::table('poems', function (Blueprint $table) {
            $table->fullText(['title', 'dedication', 'text'], 'poems_search');
        });
    }

    ///////////////////////////////////////////////////////////////////////////
    private function dropIndex(): void {
        Schema::table('poems', function (Blueprint $table) {
            $table->dropIndex('poems_search');
        });
    }
};
