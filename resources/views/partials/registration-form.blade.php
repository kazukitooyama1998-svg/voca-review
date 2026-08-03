@php
    $storeRouteName = $activeTab === 'grammar' ? 'grammars.store' : 'vocabularies.store';
@endphp

<div class="rounded-2xl border border-blue-100 bg-blue-50/60 p-6 shadow-sm">
    <h2 class="mb-4 flex items-center gap-2 text-base font-bold text-blue-700">
        <i class="fa-solid fa-plus" aria-hidden="true"></i> 新しく追加する
    </h2>

    <form action="{{ route($storeRouteName) }}" method="POST" class="space-y-4">
        @csrf

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <label for="type" class="mb-1 block text-sm font-medium text-gray-700">種類</label>
                {{-- 種類を切り替えると、その種類の登録フォームがあるタブへ移動する --}}
                <select id="type" onchange="location.href = this.value" class="w-full rounded-lg border border-gray-300 py-2 px-3 text-sm text-gray-800 focus:border-blue-500 focus:ring-blue-500">
                    <option value="{{ route('vocabularies.index') }}" @selected($activeTab === 'vocabulary')>単語・フレーズ</option>
                    <option value="{{ route('grammars.index') }}" @selected($activeTab === 'grammar')>文法（Grammar）</option>
                </select>
            </div>

            @if ($activeTab === 'grammar')
                <div class="sm:col-span-2">
                    <label for="name" class="mb-1 block text-sm font-medium text-gray-700">文法名 <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="例) 倒置法（Inversion）" class="w-full rounded-lg border border-gray-300 py-2 px-3 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500">
                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="explanation" class="mb-1 block text-sm font-medium text-gray-700">解説 <span class="text-red-500">*</span></label>
                    <input type="text" id="explanation" name="explanation" value="{{ old('explanation') }}" placeholder="例) 文の語順を入れ替える文法ルール" class="w-full rounded-lg border border-gray-300 py-2 px-3 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500">
                    @error('explanation') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            @else
                <div>
                    <label for="word" class="mb-1 block text-sm font-medium text-gray-700">英単語・フレーズ <span class="text-red-500">*</span></label>
                    <input type="text" id="word" name="word" value="{{ old('word') }}" placeholder="例) compassion" class="w-full rounded-lg border border-gray-300 py-2 px-3 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500">
                    @error('word') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="meaning" class="mb-1 block text-sm font-medium text-gray-700">意味 <span class="text-red-500">*</span></label>
                    <input type="text" id="meaning" name="meaning" value="{{ old('meaning') }}" placeholder="例) 思いやり、同情" class="w-full rounded-lg border border-gray-300 py-2 px-3 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500">
                    @error('meaning') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="part_of_speech" class="mb-1 block text-sm font-medium text-gray-700">品詞 <span class="text-red-500">*</span></label>
                    <select id="part_of_speech" name="part_of_speech" class="w-full rounded-lg border border-gray-300 py-2 px-3 text-sm text-gray-800 focus:border-blue-500 focus:ring-blue-500">
                        <option value="" disabled {{ old('part_of_speech') ? '' : 'selected' }}>例) Noun (名詞)</option>
                        @foreach (\App\Enums\PartOfSpeech::cases() as $partOfSpeech)
                            <option value="{{ $partOfSpeech->value }}" @selected(old('part_of_speech') === $partOfSpeech->value)>{{ $partOfSpeech->label() }}</option>
                        @endforeach
                    </select>
                    @error('part_of_speech') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label for="example_en" class="mb-1 block text-sm font-medium text-gray-700">例文（英語）</label>
                <input type="text" id="example_en" name="example_en" value="{{ old('example_en') }}" placeholder="{{ $activeTab === 'grammar' ? '例) Never have I seen such a beautiful place.' : '例) Showing compassion towards others is important in society.' }}" class="w-full rounded-lg border border-gray-300 py-2 px-3 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label for="example_ja" class="mb-1 block text-sm font-medium text-gray-700">例文（日本語）</label>
                <input type="text" id="example_ja" name="example_ja" value="{{ old('example_ja') }}" placeholder="{{ $activeTab === 'grammar' ? '例) こんなに美しい場所を私は今まで見たことがない。' : '例) 他人に思いやりを持つことは社会で重要です。' }}" class="w-full rounded-lg border border-gray-300 py-2 px-3 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500">
            </div>
        </div>

        <div class="flex items-center justify-between gap-4">
            <label class="flex items-center gap-2 text-sm text-gray-700">
                <input type="hidden" name="is_memorized" value="0">
                <input type="checkbox" name="is_memorized" value="1" @checked(old('is_memorized')) class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                覚えた
            </label>

            <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                <i class="fa-solid fa-plus" aria-hidden="true"></i> 追加する
            </button>
        </div>
    </form>
</div>
