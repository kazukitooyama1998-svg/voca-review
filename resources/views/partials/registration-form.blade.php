<div class="rounded-2xl border border-blue-100 bg-blue-50/60 p-6 shadow-sm">
    <h2 class="mb-4 flex items-center gap-2 text-base font-bold text-blue-700">
        <span aria-hidden="true">➕</span> 新しく追加する
    </h2>

    {{-- Controller / Route 未実装のため action は # を仮置き --}}
    <form action="#" method="POST" class="space-y-4">
        @csrf

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <label for="type" class="mb-1 block text-sm font-medium text-gray-700">種類</label>
                <select id="type" name="type" class="w-full rounded-lg border border-gray-300 py-2 px-3 text-sm text-gray-800 focus:border-blue-500 focus:ring-blue-500">
                    <option value="vocabulary">単語・フレーズ</option>
                    <option value="grammar">文法（Grammar）</option>
                </select>
            </div>

            <div>
                <label for="word" class="mb-1 block text-sm font-medium text-gray-700">英単語・フレーズ <span class="text-red-500">*</span></label>
                <input type="text" id="word" name="word" placeholder="例) compassion" class="w-full rounded-lg border border-gray-300 py-2 px-3 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label for="meaning" class="mb-1 block text-sm font-medium text-gray-700">意味 <span class="text-red-500">*</span></label>
                <input type="text" id="meaning" name="meaning" placeholder="例) 思いやり、同情" class="w-full rounded-lg border border-gray-300 py-2 px-3 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label for="part_of_speech" class="mb-1 block text-sm font-medium text-gray-700">品詞 <span class="text-red-500">*</span></label>
                <select id="part_of_speech" name="part_of_speech" class="w-full rounded-lg border border-gray-300 py-2 px-3 text-sm text-gray-800 focus:border-blue-500 focus:ring-blue-500">
                    <option value="" disabled selected>例) Noun (名詞)</option>
                    @foreach (\App\Enums\PartOfSpeech::cases() as $partOfSpeech)
                        <option value="{{ $partOfSpeech->value }}">{{ $partOfSpeech->label() }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label for="example_en" class="mb-1 block text-sm font-medium text-gray-700">例文（英語）</label>
                <input type="text" id="example_en" name="example_en" placeholder="例) Showing compassion towards others is important in society." class="w-full rounded-lg border border-gray-300 py-2 px-3 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label for="example_ja" class="mb-1 block text-sm font-medium text-gray-700">例文（日本語）</label>
                <input type="text" id="example_ja" name="example_ja" placeholder="例) 他人に思いやりを持つことは社会で重要です。" class="w-full rounded-lg border border-gray-300 py-2 px-3 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500">
            </div>
        </div>

        <div class="flex items-center justify-between gap-4">
            <label class="flex items-center gap-2 text-sm text-gray-700">
                <input type="checkbox" name="is_memorized" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                覚えた
            </label>

            <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                <span aria-hidden="true">➕</span> 追加する
            </button>
        </div>
    </form>
</div>
