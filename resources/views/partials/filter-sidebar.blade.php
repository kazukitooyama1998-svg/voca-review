@php
    $indexRoute = $activeTab === 'grammar' ? route('grammars.index') : route('vocabularies.index');
@endphp

<div class="space-y-6">
    <div class="rounded-2xl bg-white p-6 shadow-sm">
        <h2 class="mb-4 flex items-center gap-2 text-base font-bold text-gray-800">
            <span aria-hidden="true">🔍</span> 絞り込み
        </h2>

        {{-- ツールバー側の検索条件はhiddenで引き継ぎ、ラジオ選択時に自動送信する --}}
        <form action="{{ $indexRoute }}" method="GET" class="space-y-6">
            <input type="hidden" name="keyword" value="{{ request('keyword') }}">
            @if ($activeTab === 'vocabulary')
                <input type="hidden" name="part_of_speech" value="{{ request('part_of_speech') }}">
            @endif
            <input type="hidden" name="sort" value="{{ request('sort', 'newest') }}">

            <fieldset>
                <legend class="mb-2 text-sm font-semibold text-gray-700">覚えた状態</legend>
                <div class="space-y-2 text-sm text-gray-700">
                    <label class="flex items-center gap-2">
                        <input type="radio" name="memorized" value="all" onchange="this.form.requestSubmit()" @checked(request('memorized', 'all') === 'all') class="border-gray-300 text-blue-600 focus:ring-blue-500">
                        すべて
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="memorized" value="memorized" onchange="this.form.requestSubmit()" @checked(request('memorized') === 'memorized') class="border-gray-300 text-blue-600 focus:ring-blue-500">
                        覚えた
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="memorized" value="unmemorized" onchange="this.form.requestSubmit()" @checked(request('memorized') === 'unmemorized') class="border-gray-300 text-blue-600 focus:ring-blue-500">
                        覚えていない
                    </label>
                </div>
            </fieldset>
        </form>

        {{-- 種類はタブそのものが絞り込みを兼ねるため、ここではタブ切り替えへのリンクとして表示する --}}
        <fieldset class="mt-6">
            <legend class="mb-2 text-sm font-semibold text-gray-700">種類</legend>
            <div class="space-y-2 text-sm">
                <a href="{{ route('vocabularies.index') }}" class="flex items-center gap-2 {{ $activeTab === 'vocabulary' ? 'font-semibold text-blue-700' : 'text-gray-600 hover:text-gray-800' }}">
                    <span aria-hidden="true">{{ $activeTab === 'vocabulary' ? '☑' : '☐' }}</span> 単語・フレーズ
                </a>
                <a href="{{ route('grammars.index') }}" class="flex items-center gap-2 {{ $activeTab === 'grammar' ? 'font-semibold text-blue-700' : 'text-gray-600 hover:text-gray-800' }}">
                    <span aria-hidden="true">{{ $activeTab === 'grammar' ? '☑' : '☐' }}</span> 文法（Grammar）
                </a>
            </div>
        </fieldset>
    </div>

    <div class="rounded-2xl border border-amber-200 bg-amber-100/60 p-5 text-sm text-amber-900">
        <span aria-hidden="true">💡</span>
        1日に100項目を目標にコツコツ続けましょう！
    </div>
</div>
