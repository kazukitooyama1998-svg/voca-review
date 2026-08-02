<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vocabularies', function (Blueprint $table) {
            $table->timestamp('studied_at')->nullable()->after('is_memorized');
        });

        Schema::table('grammars', function (Blueprint $table) {
            $table->timestamp('studied_at')->nullable()->after('is_memorized');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vocabularies', function (Blueprint $table) {
            $table->dropColumn('studied_at');
        });

        Schema::table('grammars', function (Blueprint $table) {
            $table->dropColumn('studied_at');
        });
    }
};
