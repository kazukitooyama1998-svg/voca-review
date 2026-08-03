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
        Schema::create('vocabulary_examples', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vocabulary_id')->constrained()->cascadeOnDelete();
            $table->text('example_en')->nullable();
            $table->text('example_ja')->nullable();
            $table->timestamps();
        });

        Schema::create('grammar_examples', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grammar_id')->constrained()->cascadeOnDelete();
            $table->text('example_en')->nullable();
            $table->text('example_ja')->nullable();
            $table->timestamps();
        });

        $this->moveExistingExamples('vocabularies', 'vocabulary_examples', 'vocabulary_id');
        $this->moveExistingExamples('grammars', 'grammar_examples', 'grammar_id');

        Schema::table('vocabularies', function (Blueprint $table) {
            $table->dropColumn(['example_en', 'example_ja']);
        });

        Schema::table('grammars', function (Blueprint $table) {
            $table->dropColumn(['example_en', 'example_ja']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vocabularies', function (Blueprint $table) {
            $table->text('example_en')->nullable();
            $table->text('example_ja')->nullable();
        });

        Schema::table('grammars', function (Blueprint $table) {
            $table->text('example_en')->nullable();
            $table->text('example_ja')->nullable();
        });

        $this->restoreFirstExample('vocabularies', 'vocabulary_examples', 'vocabulary_id');
        $this->restoreFirstExample('grammars', 'grammar_examples', 'grammar_id');

        Schema::dropIfExists('vocabulary_examples');
        Schema::dropIfExists('grammar_examples');
    }

    /**
     * Copy each parent row's single example_en/example_ja into a new one-row
     * child record, so no existing example sentence is lost.
     */
    private function moveExistingExamples(string $parentTable, string $childTable, string $foreignKey): void
    {
        DB::table($parentTable)->orderBy('id')->chunkById(200, function ($rows) use ($childTable, $foreignKey) {
            foreach ($rows as $row) {
                if ($row->example_en === null && $row->example_ja === null) {
                    continue;
                }

                DB::table($childTable)->insert([
                    $foreignKey => $row->id,
                    'example_en' => $row->example_en,
                    'example_ja' => $row->example_ja,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }

    /**
     * Roll back by pulling the first child example back onto the parent row.
     * Only one example survives the rollback; the rest is intentionally discarded.
     */
    private function restoreFirstExample(string $parentTable, string $childTable, string $foreignKey): void
    {
        DB::table($parentTable)->orderBy('id')->chunkById(200, function ($rows) use ($childTable, $foreignKey) {
            foreach ($rows as $row) {
                $firstExample = DB::table($childTable)->where($foreignKey, $row->id)->orderBy('id')->first();

                if (! $firstExample) {
                    continue;
                }

                DB::table($parentTable)->where('id', $row->id)->update([
                    'example_en' => $firstExample->example_en,
                    'example_ja' => $firstExample->example_ja,
                ]);
            }
        });
    }
};
