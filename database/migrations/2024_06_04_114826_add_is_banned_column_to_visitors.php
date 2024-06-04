<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    ///////////////////////////////////////////////////////////////////////////
    public function up(): void {
        Log::info("Migration to add is_banned column to visitors started...");

        $this->addColumn();

        Log::info("Migration to add is_banned column to visitors completed!");
    }

    ///////////////////////////////////////////////////////////////////////////
    public function down(): void {
        Log::info("Rollback migration to drop is_banned column from visitors started...");

        $this->dropColumn();

        Log::info("Rollback migration to drop is_banned column from visitors completed!");
    }

    ///////////////////////////////////////////////////////////////////////////
    private function addColumn(): void {
        Schema::table('visitors', function (Blueprint $table) {
            $table
                ->unsignedTinyInteger('is_banned')
                ->default(false)
                ->after('country_code');
        });
    }

    ///////////////////////////////////////////////////////////////////////////
    private function dropColumn(): void {
        Schema::table('visitors', function (Blueprint $table) {
            $table->dropColumn('is_banned');
        });
    }
};
