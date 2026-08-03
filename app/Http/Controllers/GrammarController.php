<?php

namespace App\Http\Controllers;

use App\Models\Grammar;
use App\Models\StudyLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GrammarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $grammars = Grammar::query()
            ->with('examples')
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $keyword = $request->string('keyword');

                $query->where(function ($query) use ($keyword) {
                    $query->where('name', 'like', "%{$keyword}%")
                        ->orWhere('explanation', 'like', "%{$keyword}%")
                        ->orWhereHas('examples', function ($query) use ($keyword) {
                            $query->where('example_en', 'like', "%{$keyword}%")
                                ->orWhere('example_ja', 'like', "%{$keyword}%");
                        });
                });
            })
            ->when($request->input('memorized') === 'memorized', fn ($query) => $query->where('is_memorized', true))
            ->when($request->input('memorized') === 'unmemorized', fn ($query) => $query->where('is_memorized', false))
            ->when($request->input('sort') === 'oldest', fn ($query) => $query->oldest(), fn ($query) => $query->latest())
            ->paginate(20)
            ->withQueryString();

        return view('welcome', ['grammars' => $grammars]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateGrammar($request);
        $examples = $this->filledExamples($validated);

        $grammar = Grammar::create($validated);
        $grammar->examples()->createMany($examples);

        return redirect()->route('grammars.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Grammar $grammar): RedirectResponse
    {
        $validated = $this->validateGrammar($request);
        $examples = $this->filledExamples($validated);

        $grammar->update($validated);

        // Replace all example sentences with the submitted set. Simpler and
        // safer than diffing rows by id, and cheap since each entry only ever
        // has a handful of examples.
        $grammar->examples()->delete();
        $grammar->examples()->createMany($examples);

        return $this->redirectBackToEntry($grammar);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grammar $grammar): RedirectResponse
    {
        $grammar->delete();

        return redirect()->route('grammars.index');
    }

    /**
     * Mark the grammar point as studied today (or undo it), updating today's study log.
     */
    public function toggleStudied(Grammar $grammar): RedirectResponse
    {
        if ($grammar->studied_at?->isToday()) {
            $grammar->studied_at = null;
            StudyLog::undoReview();
        } else {
            $grammar->studied_at = now();
            StudyLog::recordReview();
        }

        $grammar->save();

        return $this->redirectBackToEntry($grammar);
    }

    /**
     * Redirect back to the page the request came from (keeping the current
     * search/filter/pagination), scrolled to this entry via a URL fragment,
     * instead of always jumping to the top of a fresh grammars.index request.
     */
    private function redirectBackToEntry(Grammar $grammar): RedirectResponse
    {
        $previousUrl = url()->previous(route('grammars.index'));

        return redirect($previousUrl.'#entry-grammar-'.$grammar->id);
    }

    /**
     * Validate the fields shared by store() and update(), including the
     * repeatable "examples" rows submitted by the example-fields component.
     */
    private function validateGrammar(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'explanation' => ['required', 'string'],
            'is_memorized' => ['boolean'],
            'examples' => ['nullable', 'array'],
            'examples.*.example_en' => ['nullable', 'string'],
            'examples.*.example_ja' => ['nullable', 'string'],
        ]);
    }

    /**
     * Pull the "examples" rows out of the validated data (the grammars table
     * no longer has example_en/example_ja columns) and drop any blank row
     * left over from an unused "add example" slot.
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
