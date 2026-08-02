<?php

namespace App\Http\Controllers;

use App\Models\StudyLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudyLogController extends Controller
{
    /**
     * Display the study statistics shown in the page header.
     */
    public function index(): View
    {
        return view('welcome', [
            'todayReviewCount' => StudyLog::todayReviewCount(),
            'streakDays' => StudyLog::currentStreak(),
            'totalReviewCount' => StudyLog::totalReviewCount(),
            'lastStudyDate' => StudyLog::lastStudyDate(),
        ]);
    }

    /**
     * Record that items were reviewed today, incrementing today's count.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'count' => ['sometimes', 'integer', 'min:1'],
        ]);

        $studyLog = StudyLog::firstOrCreate(['study_date' => today()]);
        $studyLog->increment('review_count', $request->integer('count', 1));

        return redirect()->back();
    }
}
