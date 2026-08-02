<?php

namespace App\Http\Controllers;

use App\Models\StudyLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StudyLogController extends Controller
{
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
