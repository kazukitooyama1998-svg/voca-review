<?php

use App\Http\Controllers\GrammarController;
use App\Http\Controllers\StudyLogController;
use App\Http\Controllers\VocabularyController;
use Illuminate\Support\Facades\Route;

// トップページ（すべての機能を1画面に集約）。デフォルトタブの単語・フレーズ一覧をそのまま表示する。
Route::get('/', [VocabularyController::class, 'index'])->name('vocabularies.index');

Route::resource('vocabularies', VocabularyController::class)->only(['store', 'update', 'destroy']);
Route::resource('grammars', GrammarController::class)->only(['index', 'store', 'update', 'destroy']);
Route::resource('study-logs', StudyLogController::class)->only(['index', 'store']);
