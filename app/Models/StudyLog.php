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
        return static::max('study_date');
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
