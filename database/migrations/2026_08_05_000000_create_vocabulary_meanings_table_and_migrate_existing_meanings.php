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
        Schema::create('vocabulary_meanings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vocabulary_id')->constrained()->cascadeOnDelete();
            $table->text('meaning');
            $table->timestamps();
        });

        // Carry over each existing single meaning into a one-item list so no data is lost.
        DB::table('vocabularies')->orderBy('id')->chunkById(200, function ($vocabularies) {
            foreach ($vocabularies as $vocabulary) {
                DB::table('vocabulary_meanings')->insert([
                    'vocabulary_id' => $vocabulary->id,
                    'meaning' => $vocabulary->meaning,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });

        Schema::table('vocabularies', function (Blueprint $table) {
            $table->dropColumn('meaning');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vocabularies', function (Blueprint $table) {
            $table->text('meaning')->nullable()->after('parts_of_speech');
        });

        // Only the first meaning survives the rollback; the rest is intentionally discarded.
        DB::table('vocabularies')->orderBy('id')->chunkById(200, function ($vocabularies) {
            foreach ($vocabularies as $vocabulary) {
                $firstMeaning = DB::table('vocabulary_meanings')
                    ->where('vocabulary_id', $vocabulary->id)
                    ->orderBy('id')
                    ->value('meaning');

                DB::table('vocabularies')->where('id', $vocabulary->id)->update(['meaning' => $firstMeaning]);
            }
        });

        Schema::table('vocabularies', function (Blueprint $table) {
            $table->text('meaning')->nullable(false)->change();
        });

        Schema::dropIfExists('vocabulary_meanings');
    }
};
