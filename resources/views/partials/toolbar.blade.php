<div class="rounded-2xl bg-white p-4 shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-4">
        {{-- タブ切り替え。ルーティング未定義のため # を仮置き --}}
        <nav class="flex gap-6 border-b border-gray-200 sm:border-0">
            <a href="#" class="border-b-2 border-blue-600 pb-2 text-sm font-semibold text-blue-700 sm:border-0 sm:pb-0">
                単語・フレーズ
            </a>
            <a href="#" class="flex items-center gap-1 border-b-2 border-transparent pb-2 text-sm font-medium text-gray-500 hover:text-gray-700 sm:border-0 sm:pb-0">
                <span aria-hidden="true">📖</span> 文法（Grammar）
            </a>
        </nav>

        <form action="#" method="GET" class="flex flex-1 flex-wrap items-center gap-3 sm:justify-end">
            <div class="relative min-w-[220px] flex-1 sm:flex-none">
                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400" aria-hidden="true">🔍</span>
                <input
                    type="text"
                    name="keyword"
                    placeholder="単語・意味・例文で検索..."
                    class="w-full rounded-lg border border-gray-300 py-2 pr-3 pl-9 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500"
                >
            </div>

            <select name="part_of_speech" class="rounded-lg border border-gray-300 py-2 pr-8 pl-3 text-sm text-gray-700 focus:border-blue-500 focus:ring-blue-500">
                <option value="">すべて</option>
                @foreach (\App\Enums\PartOfSpeech::cases() as $partOfSpeech)
                    <option value="{{ $partOfSpeech->value }}">{{ $partOfSpeech->label() }}</option>
                @endforeach
            </select>

            <select name="sort" class="rounded-lg border border-gray-300 py-2 pr-8 pl-3 text-sm text-gray-700 focus:border-blue-500 focus:ring-blue-500">
                <option value="">すべて</option>
                <option value="newest">新しい順</option>
                <option value="oldest">古い順</option>
            </select>

            <button type="button" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50">
                リセット
            </button>
        </form>
    </div>
</div>
