{{-- 表示用のサンプルデータ。Controller 実装後は Eloquent から渡された一覧に置き換える --}}
@php
    $entries = [
        [
            'type' => 'vocabulary',
            'title' => 'compassion',
            'meaning' => '思いやり、同情',
            'part_of_speech' => 'Noun (名詞)',
            'example_en' => 'Showing compassion towards others is important in society.',
            'example_ja' => '他人に思いやりを持つことは社会で重要です。',
            'is_memorized' => false,
        ],
        [
            'type' => 'vocabulary',
            'title' => 'upon',
            'meaning' => '〜の上に / 〜に基づいて / 〜するとすぐに',
            'part_of_speech' => 'Preposition (前置詞)',
            'example_en' => 'Decisions were made based upon careful analysis.',
            'example_ja' => '決定は慎重な分析に基づいて行われた。',
            'is_memorized' => false,
        ],
        [
            'type' => 'vocabulary',
            'title' => 'trigger',
            'meaning' => '引き起こす、誘発する / 引き金、きっかけ',
            'part_of_speech' => 'Verb (動詞)',
            'example_en' => 'The trigger for the protest was a new law.',
            'example_ja' => '抗議活動の引き金は新しい法律だった。',
            'is_memorized' => false,
        ],
        [
            'type' => 'vocabulary',
            'title' => 'distract',
            'meaning' => '気をそらす、注意を逸らす',
            'part_of_speech' => 'Verb (動詞)',
            'example_en' => 'Smartphones can distract students from their studies.',
            'example_ja' => 'スマートフォンは学生の勉強の妨げになることがある。',
            'is_memorized' => false,
        ],
        [
            'type' => 'grammar',
            'title' => '倒置法（Inversion）',
            'meaning' => '文の語順を通常の語順から入れ替える文法ルール。強調やフォーマルな表現で使われる。',
            'part_of_speech' => null,
            'example_en' => 'Never have I seen such a beautiful place.',
            'example_ja' => 'こんなに美しい場所を私は今まで見たことがない。',
            'is_memorized' => true,
        ],
    ];
@endphp

<div class="rounded-2xl bg-white p-6 shadow-sm">
    <h2 class="mb-4 text-base font-bold text-gray-800">登録一覧（{{ count($entries) }} 件）</h2>

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
                @foreach ($entries as $entry)
                    <tr class="align-top text-gray-700">
                        <td class="py-3 pr-4"><x-type-badge :type="$entry['type']" /></td>
                        <td class="py-3 pr-4 font-semibold text-gray-800">{{ $entry['title'] }}</td>
                        <td class="py-3 pr-4">{{ $entry['meaning'] }}</td>
                        <td class="py-3 pr-4 whitespace-nowrap">{{ $entry['part_of_speech'] ?? '—' }}</td>
                        <td class="py-3 pr-4 max-w-xs">{{ $entry['example_en'] }}</td>
                        <td class="py-3 pr-4 max-w-xs">{{ $entry['example_ja'] }}</td>
                        <td class="py-3 pr-4 text-center">
                            <input type="checkbox" disabled @checked($entry['is_memorized']) class="rounded border-gray-300 text-green-600">
                        </td>
                        <td class="py-3 pr-2">
                            <div class="flex justify-center gap-2">
                                <a href="#" title="編集" class="rounded-lg border border-blue-200 p-1.5 text-blue-600 hover:bg-blue-50">✏️</a>
                                <a href="#" title="削除" class="rounded-lg border border-red-200 p-1.5 text-red-600 hover:bg-red-50">🗑️</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ページネーション。Route / Controller 未実装のため # を仮置き --}}
    <nav class="mt-6 flex items-center justify-center gap-1 text-sm" aria-label="ページネーション">
        <a href="#" class="rounded-lg px-3 py-1.5 text-gray-400 hover:bg-gray-100">‹</a>
        <a href="#" class="rounded-lg bg-blue-600 px-3 py-1.5 font-semibold text-white">1</a>
        <a href="#" class="rounded-lg px-3 py-1.5 text-gray-600 hover:bg-gray-100">2</a>
        <a href="#" class="rounded-lg px-3 py-1.5 text-gray-600 hover:bg-gray-100">3</a>
        <a href="#" class="rounded-lg px-3 py-1.5 text-gray-600 hover:bg-gray-100">4</a>
        <a href="#" class="rounded-lg px-3 py-1.5 text-gray-600 hover:bg-gray-100">5</a>
        <span class="px-2 text-gray-400">…</span>
        <a href="#" class="rounded-lg px-3 py-1.5 text-gray-600 hover:bg-gray-100">13</a>
        <a href="#" class="rounded-lg px-3 py-1.5 text-gray-400 hover:bg-gray-100">›</a>
    </nav>
</div>
