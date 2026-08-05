@php
    $updateRouteName = $activeTab === 'grammar' ? 'grammars.update' : 'vocabularies.update';
    $destroyRouteName = $activeTab === 'grammar' ? 'grammars.destroy' : 'vocabularies.destroy';
    $toggleStudiedRouteName = $activeTab === 'grammar' ? 'grammars.toggle-studied' : 'vocabularies.toggle-studied';
    $clearStudiedRouteName = $activeTab === 'grammar' ? 'grammars.clear-studied' : 'vocabularies.clear-studied';
    $clearMemorizedRouteName = $activeTab === 'grammar' ? 'grammars.clear-memorized' : 'vocabularies.clear-memorized';
    // 一括解除は「今表示されている絞り込み結果」全件が対象になるよう、現在のクエリ文字列をそのまま引き継ぐ
    $currentQueryString = http_build_query(request()->query());
@endphp

<div class="rounded-2xl bg-white p-6 shadow-sm">
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <h2 class="text-base font-bold text-gray-800">登録一覧（{{ $entries->total() }} 件）</h2>

        <div class="flex flex-wrap gap-2">
            <form
                action="{{ route($clearStudiedRouteName).($currentQueryString ? '?'.$currentQueryString : '') }}"
                method="POST"
                onsubmit="return confirm('表示中の「学習した」チェックを一括で解除しますか？')"
            >
                @csrf
                @method('PATCH')
                <button type="submit" class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-50">
                    <i class="fa-solid fa-rotate-left fa-fw"></i> 学習したを一括解除
                </button>
            </form>
            <form
                action="{{ route($clearMemorizedRouteName).($currentQueryString ? '?'.$currentQueryString : '') }}"
                method="POST"
                onsubmit="return confirm('表示中の「覚えた」チェックを一括で解除しますか？')"
            >
                @csrf
                @method('PATCH')
                <button type="submit" class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-50">
                    <i class="fa-solid fa-rotate-left fa-fw"></i> 覚えたを一括解除
                </button>
            </form>
        </div>
    </div>

    {{-- 横スクロールが必要なテーブルではなく、1件ずつカード状に積み上げる形にして
         例文（英語・日本語）を縦に並べることで、横幅に依存せず最初から全項目が見えるようにする --}}
    <div class="divide-y divide-gray-100">
        @forelse ($entries as $entry)
            <div id="entry-{{ $activeTab }}-{{ $entry->id }}" class="py-5">
                <div class="flex flex-col items-start gap-3 sm:flex-row sm:justify-between sm:gap-x-4 sm:gap-y-3">
                    <div class="flex min-w-0 flex-1 items-start gap-3">
                        <x-type-badge :type="$activeTab" class="mt-0.5 shrink-0" />
                        <div class="min-w-0">
                            <p class="flex flex-wrap items-baseline gap-x-2">
                                <span class="text-base font-semibold text-gray-800">{{ $activeTab === 'grammar' ? $entry->name : $entry->word }}</span>
                                @if ($activeTab === 'vocabulary')
                                    <button
                                        type="button"
                                        class="speak-btn text-blue-500 hover:text-blue-700"
                                        data-word="{{ $entry->word }}"
                                        title="発音を再生"
                                        aria-label="発音を再生"
                                    ><i class="fa-solid fa-volume fa-fw"></i></button>
                                    <span class="text-xs text-gray-500">{{ $entry->partsOfSpeechLabel() }}</span>
                                @endif
                            </p>
                            <p class="mt-1 text-sm text-gray-600">{{ $activeTab === 'grammar' ? $entry->explanation : $entry->meaningsLabel() }}</p>
                        </div>
                    </div>

                    <div class="flex shrink-0 flex-wrap items-center gap-x-4 gap-y-2 self-end sm:self-auto">
                        {{-- 「今日すでに学習したか」を表すフラグ。チェックすると学習記録（今日の復習数）に+1、外すと-1。
                             studied_atが「今日」かどうかで判定するため、日付が変わると自動的に未チェックへ戻る --}}
                        <form action="{{ route($toggleStudiedRouteName, $entry) }}" method="POST" data-preserve-scroll>
                            @csrf
                            @method('PATCH')
                            <label class="flex cursor-pointer items-center gap-1.5 text-xs whitespace-nowrap text-gray-500">
                                <input
                                    type="checkbox"
                                    onchange="this.form.requestSubmit()"
                                    @checked($entry->studied_at?->isToday())
                                    class="rounded border-gray-300 text-blue-600"
                                >
                                学習した
                            </label>
                        </form>

                        {{-- チェックのon/offだけで即座に更新する。他の必須項目は現在値をhiddenで引き継ぐ --}}
                        <form action="{{ route($updateRouteName, $entry) }}" method="POST" data-preserve-scroll>
                            @csrf
                            @method('PUT')
                            @if ($activeTab === 'grammar')
                                <input type="hidden" name="name" value="{{ $entry->name }}">
                                <input type="hidden" name="explanation" value="{{ $entry->explanation }}">
                            @else
                                <input type="hidden" name="word" value="{{ $entry->word }}">
                                @foreach ($entry->meanings as $index => $meaning)
                                    <input type="hidden" name="meanings[{{ $index }}]" value="{{ $meaning->meaning }}">
                                @endforeach
                                @foreach ($entry->parts_of_speech as $partOfSpeech)
                                    <input type="hidden" name="parts_of_speech[]" value="{{ $partOfSpeech->value }}">
                                @endforeach
                            @endif
                            @foreach ($entry->examples as $index => $example)
                                <input type="hidden" name="examples[{{ $index }}][example_en]" value="{{ $example->example_en }}">
                                <input type="hidden" name="examples[{{ $index }}][example_ja]" value="{{ $example->example_ja }}">
                            @endforeach
                            <input type="hidden" name="is_memorized" value="0">
                            <label class="flex cursor-pointer items-center gap-1.5 text-xs whitespace-nowrap text-gray-500">
                                <input
                                    type="checkbox"
                                    name="is_memorized"
                                    value="1"
                                    onchange="this.form.requestSubmit()"
                                    @checked($entry->is_memorized)
                                    class="rounded border-gray-300 text-green-600"
                                >
                                覚えた
                            </label>
                        </form>

                        <div class="flex gap-2">
                            <button
                                type="button"
                                onclick="document.getElementById('edit-{{ $activeTab }}-{{ $entry->id }}').showModal()"
                                title="編集"
                                class="rounded-lg border border-blue-300 p-1.5 text-blue-700 hover:bg-blue-50"
                            ><i class="fa-solid fa-pen fa-fw"></i></button>

                            <button
                                type="button"
                                onclick="document.getElementById('delete-confirm-{{ $activeTab }}-{{ $entry->id }}').showModal()"
                                title="削除"
                                class="rounded-lg border border-red-300 p-1.5 text-red-700 hover:bg-red-50"
                            ><i class="fa-solid fa-trash fa-fw"></i></button>
                        </div>
                    </div>
                </div>

                @if ($entry->examples->isNotEmpty())
                    <div class="mt-3 space-y-3 rounded-lg bg-gray-50 px-4 py-3 text-sm">
                        @foreach ($entry->examples as $example)
                            <div class="space-y-1 {{ ! $loop->last ? 'border-b border-gray-200 pb-3' : '' }}">
                                @if ($example->example_en)
                                    <p class="text-gray-700"><span class="mr-1 text-xs font-semibold text-gray-400">EN</span>{{ $example->example_en }}</p>
                                @endif
                                @if ($example->example_ja)
                                    <p class="text-gray-500"><span class="mr-1 text-xs font-semibold text-gray-400">JA</span>{{ $example->example_ja }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- 編集モーダル。ブラウザ標準の <dialog> を使い、JSはshowModal/closeの呼び出しのみ --}}
                <dialog id="edit-{{ $activeTab }}-{{ $entry->id }}" class="m-auto max-h-[85vh] w-full max-w-lg overflow-y-auto rounded-2xl p-6 backdrop:bg-black/40">
                    <form action="{{ route($updateRouteName, $entry) }}" method="POST" class="space-y-4 text-left" data-preserve-scroll>
                        @csrf
                        @method('PUT')

                        @if ($activeTab === 'grammar')
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700">文法名</label>
                                <input type="text" name="name" value="{{ $entry->name }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700">解説</label>
                                <input type="text" name="explanation" value="{{ $entry->explanation }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                            </div>
                        @else
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700">英単語・フレーズ</label>
                                <input type="text" name="word" value="{{ $entry->word }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700">意味（複数登録可）</label>
                                <x-meaning-fields :meanings="$entry->meanings" />
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700">品詞（複数選択可）</label>
                                <div class="flex flex-wrap gap-x-4 gap-y-2 rounded-lg border border-gray-300 px-3 py-2">
                                    @foreach (\App\Enums\PartOfSpeech::cases() as $partOfSpeech)
                                        <label class="flex items-center gap-1.5 text-sm text-gray-700">
                                            <input
                                                type="checkbox"
                                                name="parts_of_speech[]"
                                                value="{{ $partOfSpeech->value }}"
                                                @checked($entry->parts_of_speech->contains($partOfSpeech))
                                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                            >
                                            {{ $partOfSpeech->label() }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">例文（複数登録可）</label>
                            <x-example-fields :examples="$entry->examples" />
                        </div>

                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input type="hidden" name="is_memorized" value="0">
                            <input type="checkbox" name="is_memorized" value="1" @checked($entry->is_memorized) class="rounded border-gray-300 text-blue-600">
                            覚えた
                        </label>

                        <div class="flex justify-end gap-2 pt-2">
                            <button type="button" onclick="this.closest('dialog').close()" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">キャンセル</button>
                            <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">更新する</button>
                        </div>
                    </form>
                </dialog>

                {{-- 削除確認モーダル。ブラウザ標準のconfirm()はデザインが崩れる上に自動化ツール等で
                     処理が固まることもあるため、編集モーダルと同じ<dialog>ベースの確認画面にする --}}
                <dialog id="delete-confirm-{{ $activeTab }}-{{ $entry->id }}" class="m-auto w-full max-w-sm rounded-2xl p-6 backdrop:bg-black/40">
                    <p class="text-sm text-gray-700">
                        <span class="font-semibold text-gray-900">{{ $activeTab === 'grammar' ? $entry->name : $entry->word }}</span> を削除しますか？<br>
                        この操作は取り消せません。
                    </p>
                    <form action="{{ route($destroyRouteName, $entry) }}" method="POST" class="mt-5 flex justify-end gap-2" data-preserve-scroll>
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="this.closest('dialog').close()" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">キャンセル</button>
                        <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">削除する</button>
                    </form>
                </dialog>
            </div>
        @empty
            <p class="py-10 text-center text-gray-400">登録されている項目はありません。</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $entries->onEachSide(1)->links() }}
    </div>
</div>
