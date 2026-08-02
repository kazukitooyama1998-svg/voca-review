@php
    $updateRouteName = $activeTab === 'grammar' ? 'grammars.update' : 'vocabularies.update';
    $destroyRouteName = $activeTab === 'grammar' ? 'grammars.destroy' : 'vocabularies.destroy';
@endphp

<div class="rounded-2xl bg-white p-6 shadow-sm">
    <h2 class="mb-4 text-base font-bold text-gray-800">登録一覧（{{ $entries->total() }} 件）</h2>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[800px] table-auto text-left text-sm">
            <thead>
                <tr class="border-b border-gray-200 text-gray-500">
                    <th class="py-2 pr-4 font-medium">種類</th>
                    <th class="py-2 pr-4 font-medium">英単語・フレーズ / 文法名</th>
                    <th class="py-2 pr-4 font-medium">意味 / 解説</th>
                    <th class="py-2 pr-4 font-medium">品詞</th>
                    <th class="py-2 pr-4 font-medium">例文（英語）</th>
                    <th class="py-2 pr-4 font-medium">例文（日本語）</th>
                    <th class="py-2 pr-4 text-center font-medium">覚えた</th>
                    <th class="py-2 pr-2 text-center font-medium">操作</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($entries as $entry)
                    <tr class="align-top text-gray-700">
                        <td class="py-3 pr-4"><x-type-badge :type="$activeTab" /></td>
                        <td class="py-3 pr-4 font-semibold text-gray-800">{{ $activeTab === 'grammar' ? $entry->name : $entry->word }}</td>
                        <td class="py-3 pr-4">{{ $activeTab === 'grammar' ? $entry->explanation : $entry->meaning }}</td>
                        <td class="py-3 pr-4 whitespace-nowrap">{{ $activeTab === 'vocabulary' ? $entry->part_of_speech->label() : '—' }}</td>
                        <td class="max-w-xs py-3 pr-4">{{ $entry->example_en ?? '—' }}</td>
                        <td class="max-w-xs py-3 pr-4">{{ $entry->example_ja ?? '—' }}</td>
                        <td class="py-3 pr-4 text-center">
                            {{-- チェックのon/offだけで即座に更新する。他の必須項目は現在値をhiddenで引き継ぐ --}}
                            <form action="{{ route($updateRouteName, $entry) }}" method="POST">
                                @csrf
                                @method('PUT')
                                @if ($activeTab === 'grammar')
                                    <input type="hidden" name="name" value="{{ $entry->name }}">
                                    <input type="hidden" name="explanation" value="{{ $entry->explanation }}">
                                @else
                                    <input type="hidden" name="word" value="{{ $entry->word }}">
                                    <input type="hidden" name="meaning" value="{{ $entry->meaning }}">
                                    <input type="hidden" name="part_of_speech" value="{{ $entry->part_of_speech->value }}">
                                @endif
                                <input type="hidden" name="example_en" value="{{ $entry->example_en }}">
                                <input type="hidden" name="example_ja" value="{{ $entry->example_ja }}">
                                <input type="hidden" name="is_memorized" value="0">
                                <input
                                    type="checkbox"
                                    name="is_memorized"
                                    value="1"
                                    onchange="this.form.requestSubmit()"
                                    @checked($entry->is_memorized)
                                    class="rounded border-gray-300 text-green-600"
                                    aria-label="覚えた"
                                >
                            </form>
                        </td>
                        <td class="py-3 pr-2">
                            <div class="flex justify-center gap-2">
                                <button
                                    type="button"
                                    onclick="document.getElementById('edit-{{ $activeTab }}-{{ $entry->id }}').showModal()"
                                    title="編集"
                                    class="rounded-lg border border-blue-200 p-1.5 text-blue-600 hover:bg-blue-50"
                                >✏️</button>

                                <form action="{{ route($destroyRouteName, $entry) }}" method="POST" onsubmit="return confirm('削除しますか？')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="削除" class="rounded-lg border border-red-200 p-1.5 text-red-600 hover:bg-red-50">🗑️</button>
                                </form>
                            </div>

                            {{-- 編集モーダル。ブラウザ標準の <dialog> を使い、JSはshowModal/closeの呼び出しのみ --}}
                            <dialog id="edit-{{ $activeTab }}-{{ $entry->id }}" class="w-full max-w-lg rounded-2xl p-6 backdrop:bg-black/40">
                                <form action="{{ route($updateRouteName, $entry) }}" method="POST" class="space-y-4 text-left">
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
                                            <label class="mb-1 block text-sm font-medium text-gray-700">意味</label>
                                            <input type="text" name="meaning" value="{{ $entry->meaning }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                                        </div>
                                        <div>
                                            <label class="mb-1 block text-sm font-medium text-gray-700">品詞</label>
                                            <select name="part_of_speech" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                                                @foreach (\App\Enums\PartOfSpeech::cases() as $partOfSpeech)
                                                    <option value="{{ $partOfSpeech->value }}" @selected($entry->part_of_speech === $partOfSpeech)>{{ $partOfSpeech->label() }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif

                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-gray-700">例文（英語）</label>
                                        <input type="text" name="example_en" value="{{ $entry->example_en }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-gray-700">例文（日本語）</label>
                                        <input type="text" name="example_ja" value="{{ $entry->example_ja }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
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
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-10 text-center text-gray-400">登録されている項目はありません。</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $entries->onEachSide(1)->links() }}
    </div>
</div>
