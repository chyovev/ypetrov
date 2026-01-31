<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    ///////////////////////////////////////////////////////////////////////////
    public function up(): void {
        Schema::table('books', function (Blueprint $table) {
            $table->text('text')->after('publish_year')->nullable();
        });
    }

    ///////////////////////////////////////////////////////////////////////////
    public function down(): void {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('text');
        });
    }
};
