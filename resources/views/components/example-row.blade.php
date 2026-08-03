@props(['index', 'example' => null])

<div class="grid grid-cols-1 gap-2 sm:grid-cols-[1fr_1fr_auto] sm:items-start" data-example-row>
    <input
        type="text"
        name="examples[{{ $index }}][example_en]"
        value="{{ $example->example_en ?? '' }}"
        placeholder="例文（英語）"
        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500"
    >
    <input
        type="text"
        name="examples[{{ $index }}][example_ja]"
        value="{{ $example->example_ja ?? '' }}"
        placeholder="例文（日本語）"
        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500"
    >
    <button
        type="button"
        title="この例文を削除"
        aria-label="この例文を削除"
        class="justify-self-start rounded-lg border border-red-200 p-2 text-red-600 hover:bg-red-50"
        data-remove-example
    ><i class="fa-solid fa-xmark fa-fw"></i></button>
</div>
