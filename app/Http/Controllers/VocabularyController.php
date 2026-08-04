<?php

namespace App\Http\Controllers;

use App\Enums\PartOfSpeech;
use App\Models\StudyLog;
use App\Models\Vocabulary;
use Illuminate\Database\Eloquent\Builder;
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
        $vocabularies = $this->filteredQuery($request)
            ->with('examples')
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
        $validated = $this->validateVocabulary($request);
        $examples = $this->filledExamples($validated);

        $vocabulary = Vocabulary::create($validated);
        $vocabulary->examples()->createMany($examples);

        return redirect()->route('vocabularies.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vocabulary $vocabulary): RedirectResponse
    {
        $validated = $this->validateVocabulary($request);
        $examples = $this->filledExamples($validated);

        $vocabulary->update($validated);

        // Replace all example sentences with the submitted set. Simpler and
        // safer than diffing rows by id, and cheap since each word only ever
        // has a handful of examples.
        $vocabulary->examples()->delete();
        $vocabulary->examples()->createMany($examples);

        return $this->redirectBackToEntry();
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

        return $this->redirectBackToEntry();
    }

    /**
     * Clear the "studied today" flag on every vocabulary matching the
     * current search/filter (the "学習した" bulk-clear button above the list).
     */
    public function clearStudied(Request $request): RedirectResponse
    {
        $studiedTodayIds = $this->filteredQuery($request)
            ->whereDate('studied_at', today())
            ->pluck('id');

        if ($studiedTodayIds->isNotEmpty()) {
            Vocabulary::whereIn('id', $studiedTodayIds)->update(['studied_at' => null]);
            StudyLog::undoReviews($studiedTodayIds->count());
        }

        return $this->redirectBackToEntry();
    }

    /**
     * Clear the "memorized" flag on every vocabulary matching the current
     * search/filter (the "覚えた" bulk-clear button above the list).
     */
    public function clearMemorized(Request $request): RedirectResponse
    {
        $this->filteredQuery($request)
            ->where('is_memorized', true)
            ->update(['is_memorized' => false]);

        return $this->redirectBackToEntry();
    }

    /**
     * The search/filter conditions shared by index() and the bulk-clear
     * actions, so "clear" always matches exactly what's currently shown.
     */
    private function filteredQuery(Request $request): Builder
    {
        return Vocabulary::query()
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $keyword = $request->string('keyword');

                $query->where(function ($query) use ($keyword) {
                    $query->where('word', 'like', "%{$keyword}%")
                        ->orWhere('meaning', 'like', "%{$keyword}%")
                        ->orWhereHas('examples', function ($query) use ($keyword) {
                            $query->where('example_en', 'like', "%{$keyword}%")
                                ->orWhere('example_ja', 'like', "%{$keyword}%");
                        });
                });
            })
            ->when($request->filled('part_of_speech'), fn ($query) => $query->whereJsonContains('parts_of_speech', $request->string('part_of_speech')->toString()))
            ->when($request->input('memorized') === 'memorized', fn ($query) => $query->where('is_memorized', true))
            ->when($request->input('memorized') === 'unmemorized', fn ($query) => $query->where('is_memorized', false));
    }

    /**
     * Redirect back to the page the request came from (keeping the current
     * search/filter/pagination), instead of always jumping to the top of a
     * fresh vocabularies.index request. The scroll position itself is
     * restored client-side (see resources/js/app.js) — a URL fragment isn't
     * used here because the browser's own "scroll to fragment" behavior can
     * re-fire after that restore and override it.
     */
    private function redirectBackToEntry(): RedirectResponse
    {
        return redirect(url()->previous(route('vocabularies.index')));
    }

    /**
     * Validate the fields shared by store() and update(), including the
     * repeatable "examples" rows submitted by the example-fields component.
     */
    private function validateVocabulary(Request $request): array
    {
        return $request->validate([
            'word' => ['required', 'string', 'max:255'],
            'parts_of_speech' => ['required', 'array', 'min:1'],
            'parts_of_speech.*' => [Rule::enum(PartOfSpeech::class)],
            'meaning' => ['required', 'string'],
            'is_memorized' => ['boolean'],
            'examples' => ['nullable', 'array'],
            'examples.*.example_en' => ['nullable', 'string'],
            'examples.*.example_ja' => ['nullable', 'string'],
        ]);
    }

    /**
     * Pull the "examples" rows out of the validated data (the vocabularies
     * table no longer has example_en/example_ja columns) and drop any blank
     * row left over from an unused "add example" slot.
     */
    private function filledExamples(array &$validated): array
    {
        $examples = collect($validated['examples'] ?? [])
            ->filter(fn (array $example) => filled($example['example_en'] ?? null) || filled($example['example_ja'] ?? null))
            ->values()
            ->all();

        unset($validated['examples']);

        return $examples;
    }
}
