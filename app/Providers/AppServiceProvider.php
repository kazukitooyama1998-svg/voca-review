<?php

namespace App\Providers;

use App\Models\StudyLog;
use App\Models\Vocabulary;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ヘッダーの学習統計はどのページ（単語一覧／文法一覧）でも表示するため、
        // 各Controllerに個別に持たせず、partials.header 専用の View Composer で一括注入する。
        View::composer('partials.header', function ($view) {
            $view->with([
                'todayReviewCount' => StudyLog::todayReviewCount(),
                'todayReviewGoal' => 100,
                'streakDays' => StudyLog::currentStreak(),
                'totalReviewCount' => StudyLog::totalReviewCount(),
                'lastStudyDate' => StudyLog::lastStudyDate(),
                'memorizedVocabularyCount' => Vocabulary::memorizedCount(),
                'totalVocabularyCount' => Vocabulary::count(),
            ]);
        });
    }
}
