<?php

use App\Http\Controllers\GrammarController;
use App\Http\Controllers\StudyLogController;
use App\Http\Controllers\VocabularyController;
use Illuminate\Support\Facades\Route;

// トップページ（すべての機能を1画面に集約）。デフォルトタブの単語・フレーズ一覧をそのまま表示する。
Route::get('/', [VocabularyController::class, 'index'])->name('vocabularies.index');

// 「clear-studied」「clear-memorized」はURLの形が resource の {vocabulary} と衝突するため、
// Route::resource より先に登録して、こちらが優先的にマッチするようにする。
Route::patch('vocabularies/clear-studied', [VocabularyController::class, 'clearStudied'])->name('vocabularies.clear-studied');
Route::patch('vocabularies/clear-memorized', [VocabularyController::class, 'clearMemorized'])->name('vocabularies.clear-memorized');
Route::resource('vocabularies', VocabularyController::class)->only(['store', 'update', 'destroy']);
Route::patch('vocabularies/{vocabulary}/toggle-studied', [VocabularyController::class, 'toggleStudied'])->name('vocabularies.toggle-studied');

Route::patch('grammars/clear-studied', [GrammarController::class, 'clearStudied'])->name('grammars.clear-studied');
Route::patch('grammars/clear-memorized', [GrammarController::class, 'clearMemorized'])->name('grammars.clear-memorized');
Route::resource('grammars', GrammarController::class)->only(['index', 'store', 'update', 'destroy']);
Route::patch('grammars/{grammar}/toggle-studied', [GrammarController::class, 'toggleStudied'])->name('grammars.toggle-studied');

Route::resource('study-logs', StudyLogController::class)->only(['store']);
