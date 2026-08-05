@props(['index', 'meaning' => null])

<div class="flex items-center gap-2" data-repeatable-row>
    <input
        type="text"
        name="meanings[{{ $index }}]"
        value="{{ $meaning }}"
        placeholder="例) 思いやり、同情"
        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500"
    >
    <button
        type="button"
        title="この意味を削除"
        aria-label="この意味を削除"
        class="shrink-0 rounded-lg border border-red-200 p-2 text-red-600 hover:bg-red-50"
        data-remove-row
    ><i class="fa-solid fa-xmark fa-fw"></i></button>
</div>
