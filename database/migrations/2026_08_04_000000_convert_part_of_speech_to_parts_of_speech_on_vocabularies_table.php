<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vocabularies', function (Blueprint $table) {
            $table->json('parts_of_speech')->nullable()->after('part_of_speech');
        });

        // Carry over each existing single value into a one-item array so no data is lost.
        DB::table('vocabularies')->orderBy('id')->chunkById(200, function ($vocabularies) {
            foreach ($vocabularies as $vocabulary) {
                DB::table('vocabularies')
                    ->where('id', $vocabulary->id)
                    ->update(['parts_of_speech' => json_encode([$vocabulary->part_of_speech])]);
            }
        });

        Schema::table('vocabularies', function (Blueprint $table) {
            $table->dropColumn('part_of_speech');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vocabularies', function (Blueprint $table) {
            $table->string('part_of_speech', 50)->nullable()->after('parts_of_speech');
        });

        // Only the first part of speech survives the rollback; the rest is intentionally discarded.
        DB::table('vocabularies')->orderBy('id')->chunkById(200, function ($vocabularies) {
            foreach ($vocabularies as $vocabulary) {
                $parts = json_decode($vocabulary->parts_of_speech, true) ?? [];

                DB::table('vocabularies')
                    ->where('id', $vocabulary->id)
                    ->update(['part_of_speech' => $parts[0] ?? null]);
            }
        });

        Schema::table('vocabularies', function (Blueprint $table) {
            $table->string('part_of_speech', 50)->nullable(false)->change();
            $table->dropColumn('parts_of_speech');
        });
    }
};
