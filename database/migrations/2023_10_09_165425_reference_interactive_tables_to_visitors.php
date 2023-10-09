<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A lot of tables are used for interaction with the visitors:
 * comments, contact_messages, likes, etc. All of them store
 * the IP addresses in the same manner which is repetitive
 * and redundant.
 * Since visitors are now stored in a separate table, it's
 * more elegant to reference that table using foreign keys.
 */

return new class extends Migration
{

    ///////////////////////////////////////////////////////////////////////////
    public function up(): void {
        Log::info('Migration to reference interactive tables to visitors started...');

        $this->referenceOtherTablesToVisitors();

        $this->recreateUniqueConstraintForLikes();

        Log::info('Migration to reference interactive tables to visitors completed!');
    }

    ///////////////////////////////////////////////////////////////////////////
    public function down(): void {
        Log::info('Rollback migration to revert reference from interactive tables to visitors started...');
        
        $this->revertOtherTablesReferenceToVisitors();

        $this->revertUniqueConstraintForLikes();

        Log::info('Rollback migration to revert reference from interactive tables to visitors completed!');
    }

    ///////////////////////////////////////////////////////////////////////////
    private function referenceOtherTablesToVisitors(): void {
        $this->referenceTableToVisitors('contact_messages');
        $this->referenceTableToVisitors('comments');
        $this->referenceTableToVisitors('likes');
        $this->referenceTableToVisitors('impressions');
    }

    ///////////////////////////////////////////////////////////////////////////
    private function referenceTableToVisitors(string $table): void {
        $this->truncateTable($table);
        $this->addForeignKeyToVisitors($table);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * The constrained foreign key cannot be added for tables with
     * existing records, same goes for the non-nullable ip_hash
     * column during rollback migration. Therefore, all tables
     * should be truncated before their structures are altered.
     */
    private function truncateTable(string $table): void {
        Log::info("Truncating table {$table}...");

        DB::table($table)->truncate();
    }

    ///////////////////////////////////////////////////////////////////////////
    private function addForeignKeyToVisitors(string $table): void {
        Log::info("Adding foreign key reference to visitors to table {$table}...");

        // the $table parameter in the callback method is NOT
        // the same as the one passed to the parent method
        Schema::table($table, function (Blueprint $table) {
            $table->foreignId('visitor_id')->after('id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->dropColumn('ip_hash');
        });
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * The likes table has a unique constraint on stats object + ip.
     * Once the ip column gets dropped in favour for the visitor FK,
     * the unique constraint should be recreated, otherwise it will
     * consist of the stats object FK only.
     * 
     * NB! Foreign key constraints need to be temporarily switched
     *     off, otherwise SQL will not allow the operation.
     */
    private function recreateUniqueConstraintForLikes(): void {
        Log::info("Recreating unique constraint for likes table...");

        // unfortunately, simply dropping the unique constraint and
        // recreating is not possible due to a FK constraint on the
        // stats_id column – even when using withoutForeignKeyConstraints
        Schema::table('likes', function(Blueprint $table) {
            $table->dropForeign(['stats_id']);
        });
            
        Schema::table('likes', function(Blueprint $table) {
            $table->dropUnique('like_unique');
            $table->foreign('stats_id')->references('id')->on('stats')->cascadeOnUpdate()->cascadeOnDelete();
            $table->unique(['stats_id', 'visitor_id'], 'like_unique');
        });
    }

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     *                                                                       *
     *                     Rollback migration methods...                     *
     *                                                                       *
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

    ///////////////////////////////////////////////////////////////////////////
    private function revertOtherTablesReferenceToVisitors(): void {
        $this->revertTableReferenceToVisitors('contact_messages');
        $this->revertTableReferenceToVisitors('comments');
        $this->revertTableReferenceToVisitors('likes');
        $this->revertTableReferenceToVisitors('impressions');
    }
    
    ///////////////////////////////////////////////////////////////////////////
    private function revertTableReferenceToVisitors(string $table): void {
        $this->truncateTable($table);
        $this->readdIpHashColumn($table);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function readdIpHashColumn(string $table): void {
        Log::info("Reverting foreign key reference to visitors for table {$table}...");

        // the $table parameter in the callback method is NOT
        // the same as the one passed to the parent method
        Schema::table($table, function (Blueprint $table) {
            $table->string('ip_hash', 64); // the IP is hashed using the sha256 altorithm which is exactly 64 characters long
            $table->dropConstrainedForeignId('visitor_id');
        });
    }

    ///////////////////////////////////////////////////////////////////////////
    private function revertUniqueConstraintForLikes(): void {
        Log::info("Reverting unique constraint for likes table...");

        Schema::table('likes', function(Blueprint $table) {
            $table->dropForeign(['stats_id']);
        });

        Schema::table('likes', function(Blueprint $table) {
            $table->dropUnique('like_unique');
            $table->foreign('stats_id')->references('id')->on('stats')->cascadeOnUpdate()->cascadeOnDelete();
            $table->unique(['stats_id', 'ip_hash'], 'like_unique');
        });
    }

};
