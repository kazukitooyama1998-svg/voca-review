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
        // 「学習した」チェックを、日付が変わると自動的に外れる一時フラグ(studied_atが今日かどうかで判定)
        // から、明示的にリセットするまで保持され続ける永続フラグに変更する。
        // studied_atは「最後に学習記録へ加算された日時」として引き続き使い、同じ日に何度も
        // チェックし直しても復習数が二重に加算されないようにする。
        Schema::table('vocabularies', function (Blueprint $table) {
            $table->boolean('is_studied')->default(false)->after('studied_at');
        });

        Schema::table('grammars', function (Blueprint $table) {
            $table->boolean('is_studied')->default(false)->after('studied_at');
        });

        // 既存データは、これまで画面に表示されていた「今日学習した」チェック状態(studied_atが今日)を
        // そのままis_studiedへ引き継ぐ。こうすることで移行の前後で見た目が変わらない。
        DB::table('vocabularies')->whereDate('studied_at', today())->update(['is_studied' => true]);
        DB::table('grammars')->whereDate('studied_at', today())->update(['is_studied' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vocabularies', function (Blueprint $table) {
            $table->dropColumn('is_studied');
        });

        Schema::table('grammars', function (Blueprint $table) {
            $table->dropColumn('is_studied');
        });
    }
};
