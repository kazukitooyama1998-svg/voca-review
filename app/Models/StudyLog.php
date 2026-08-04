<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

#[Fillable(['study_date', 'review_count'])]
class StudyLog extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'study_date' => 'date',
            'review_count' => 'integer',
        ];
    }

    /**
     * Number of items reviewed today.
     */
    public static function todayReviewCount(): int
    {
        return (int) static::whereDate('study_date', today())->value('review_count');
    }

    /**
     * Total number of items reviewed across all days.
     */
    public static function totalReviewCount(): int
    {
        return (int) static::sum('review_count');
    }

    /**
     * The most recent date a review was logged.
     */
    public static function lastStudyDate(): ?Carbon
    {
        // review_count = 0の行（チェックを入れてすぐ外した等）は実際の学習記録ではないため除外する。
        // max() は集計クエリなので、モデルのdateキャストを通らない生のカラム値が返る。
        $date = static::where('review_count', '>', 0)->max('study_date');

        return $date ? Carbon::parse($date) : null;
    }

    /**
     * Record that one more item was studied today.
     */
    public static function recordReview(): void
    {
        $studyLog = static::firstOrCreate(['study_date' => today()]);
        $studyLog->increment('review_count');
    }

    /**
     * Undo a review recorded earlier today (e.g. the "学習した" checkbox was unchecked again).
     */
    public static function undoReview(): void
    {
        static::undoReviews(1);
    }

    /**
     * Undo several reviews recorded earlier today at once (e.g. the bulk
     * "一括解除" action). Clamped at 0 so the count never goes negative.
     */
    public static function undoReviews(int $count): void
    {
        if ($count <= 0) {
            return;
        }

        $studyLog = static::whereDate('study_date', today())->first();

        if (! $studyLog) {
            return;
        }

        $studyLog->update(['review_count' => max(0, $studyLog->review_count - $count)]);
    }

    /**
     * Number of consecutive days (including today, if already studied) with at least one review.
     */
    public static function currentStreak(): int
    {
        $studyDates = static::where('review_count', '>', 0)
            ->orderByDesc('study_date')
            ->pluck('study_date')
            ->map(fn ($date) => $date->toDateString());

        if ($studyDates->isEmpty()) {
            return 0;
        }

        $expectedDate = $studyDates->contains(today()->toDateString())
            ? today()
            : today()->subDay();

        $streak = 0;

        foreach ($studyDates as $studyDate) {
            if ($studyDate !== $expectedDate->toDateString()) {
                break;
            }

            $streak++;
            $expectedDate = $expectedDate->subDay();
        }

        return $streak;
    }
}
