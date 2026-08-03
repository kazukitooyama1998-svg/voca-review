<?php

namespace App\Http\Controllers;

use App\Enums\PartOfSpeech;
use App\Models\StudyLog;
use App\Models\Vocabulary;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VocabularyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $vocabularies = Vocabulary::query()
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $keyword = $request->string('keyword');

                $query->where(function ($query) use ($keyword) {
                    $query->where('word', 'like', "%{$keyword}%")
                        ->orWhere('meaning', 'like', "%{$keyword}%")
                        ->orWhere('example_en', 'like', "%{$keyword}%")
                        ->orWhere('example_ja', 'like', "%{$keyword}%");
                });
            })
            ->when($request->filled('part_of_speech'), fn ($query) => $query->where('part_of_speech', $request->string('part_of_speech')))
            ->when($request->input('memorized') === 'memorized', fn ($query) => $query->where('is_memorized', true))
            ->when($request->input('memorized') === 'unmemorized', fn ($query) => $query->where('is_memorized', false))
            ->when($request->input('sort') === 'oldest', fn ($query) => $query->oldest(), fn ($query) => $query->latest())
            ->paginate(20)
            ->withQueryString();

        return view('welcome', ['vocabularies' => $vocabularies]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'word' => ['required', 'string', 'max:255'],
            'part_of_speech' => ['required', Rule::enum(PartOfSpeech::class)],
            'meaning' => ['required', 'string'],
            'example_en' => ['nullable', 'string'],
            'example_ja' => ['nullable', 'string'],
            'is_memorized' => ['boolean'],
        ]);

        Vocabulary::create($validated);

        return redirect()->route('vocabularies.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vocabulary $vocabulary): RedirectResponse
    {
        $validated = $request->validate([
            'word' => ['required', 'string', 'max:255'],
            'part_of_speech' => ['required', Rule::enum(PartOfSpeech::class)],
            'meaning' => ['required', 'string'],
            'example_en' => ['nullable', 'string'],
            'example_ja' => ['nullable', 'string'],
            'is_memorized' => ['boolean'],
        ]);

        $vocabulary->update($validated);

        return $this->redirectBackToEntry($vocabulary);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vocabulary $vocabulary): RedirectResponse
    {
        $vocabulary->delete();

        return redirect()->route('vocabularies.index');
    }

    /**
     * Mark the vocabulary as studied today (or undo it), updating today's study log.
     */
    public function toggleStudied(Vocabulary $vocabulary): RedirectResponse
    {
        if ($vocabulary->studied_at?->isToday()) {
            $vocabulary->studied_at = null;
            StudyLog::undoReview();
        } else {
            $vocabulary->studied_at = now();
            StudyLog::recordReview();
        }

        $vocabulary->save();

        return $this->redirectBackToEntry($vocabulary);
    }

    /**
     * Redirect back to the page the request came from (keeping the current
     * search/filter/pagination), scrolled to this entry via a URL fragment,
     * instead of always jumping to the top of a fresh vocabularies.index request.
     */
    private function redirectBackToEntry(Vocabulary $vocabulary): RedirectResponse
    {
        $previousUrl = url()->previous(route('vocabularies.index'));

        return redirect($previousUrl.'#entry-vocabulary-'.$vocabulary->id);
    }
}
