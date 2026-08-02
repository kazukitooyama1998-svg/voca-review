@php
    $indexRoute = $activeTab === 'grammar' ? route('grammars.index') : route('vocabularies.index');
@endphp

<div class="rounded-2xl bg-white p-4 shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <nav class="flex gap-6 border-b border-gray-200 sm:border-0">
            <a
                href="{{ route('vocabularies.index') }}"
                class="border-b-2 pb-2 text-sm font-semibold sm:border-0 sm:pb-0 {{ $activeTab === 'vocabulary' ? 'border-blue-600 text-blue-700' : 'border-transparent text-gray-500 hover:text-gray-700' }}"
            >
                単語・フレーズ
            </a>
            <a
                href="{{ route('grammars.index') }}"
                class="flex items-center gap-1 border-b-2 pb-2 text-sm font-medium sm:border-0 sm:pb-0 {{ $activeTab === 'grammar' ? 'border-blue-600 text-blue-700' : 'border-transparent text-gray-500 hover:text-gray-700' }}"
            >
                <span aria-hidden="true">📖</span> 文法（Grammar）
            </a>
        </nav>

        {{-- 「覚えた状態」は右サイドバーのフォームが担当するため、現在の選択値をhiddenで引き継ぐ --}}
        <form action="{{ $indexRoute }}" method="GET" class="flex flex-1 flex-wrap items-center gap-3 sm:justify-end">
            <input type="hidden" name="memorized" value="{{ request('memorized', 'all') }}">

            <div class="relative min-w-[220px] flex-1 sm:flex-none">
                <button type="submit" class="absolute inset-y-0 left-3 flex items-center text-gray-400" aria-label="検索">
                    🔍
                </button>
                <input
                    type="text"
                    name="keyword"
                    value="{{ request('keyword') }}"
                    placeholder="単語・意味・例文で検索..."
                    class="w-full rounded-lg border border-gray-300 py-2 pr-3 pl-9 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500"
                >
            </div>

            @if ($activeTab === 'vocabulary')
                <select name="part_of_speech" class="rounded-lg border border-gray-300 py-2 pr-8 pl-3 text-sm text-gray-700 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">すべて</option>
                    @foreach (\App\Enums\PartOfSpeech::cases() as $partOfSpeech)
                        <option value="{{ $partOfSpeech->value }}" @selected(request('part_of_speech') === $partOfSpeech->value)>{{ $partOfSpeech->label() }}</option>
                    @endforeach
                </select>
            @endif

            <select name="sort" class="rounded-lg border border-gray-300 py-2 pr-8 pl-3 text-sm text-gray-700 focus:border-blue-500 focus:ring-blue-500">
                <option value="newest" @selected(request('sort', 'newest') === 'newest')>新しい順</option>
                <option value="oldest" @selected(request('sort') === 'oldest')>古い順</option>
            </select>

            <a href="{{ $indexRoute }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50">
                リセット
            </a>
        </form>
    </div>
</div>
