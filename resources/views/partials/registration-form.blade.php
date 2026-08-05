@php
    $storeRouteName = $activeTab === 'grammar' ? 'grammars.store' : 'vocabularies.store';
@endphp

<div class="rounded-2xl border border-blue-100 bg-blue-50/60 p-6 shadow-sm">
    <h2 class="mb-4 flex items-center gap-2 text-base font-bold text-blue-700">
        <i class="fa-solid fa-plus fa-fw" aria-hidden="true"></i> 新しく追加する
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

                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-gray-700">品詞（複数選択可） <span class="text-red-500">*</span></label>
                    <div class="flex flex-wrap gap-x-4 gap-y-2 rounded-lg border border-gray-300 px-3 py-2">
                        @foreach (\App\Enums\PartOfSpeech::cases() as $partOfSpeech)
                            <label class="flex items-center gap-1.5 text-sm text-gray-700">
                                <input
                                    type="checkbox"
                                    name="parts_of_speech[]"
                                    value="{{ $partOfSpeech->value }}"
                                    @checked(collect(old('parts_of_speech'))->contains($partOfSpeech->value))
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                >
                                {{ $partOfSpeech->label() }}
                            </label>
                        @endforeach
                    </div>
                    @error('parts_of_speech') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    @error('parts_of_speech.*') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            @endif
        </div>

        @if ($activeTab === 'vocabulary')
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">意味（複数登録可） <span class="text-red-500">*</span></label>
                <x-meaning-fields :meanings="old('meanings', [])" />
                @error('meanings') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        @endif

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">例文（複数登録可）</label>
            <x-example-fields :examples="old('examples', [])" />
        </div>

        <div class="flex items-center justify-between gap-4">
            <label class="flex items-center gap-2 text-sm text-gray-700">
                <input type="hidden" name="is_memorized" value="0">
                <input type="checkbox" name="is_memorized" value="1" @checked(old('is_memorized')) class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                覚えた
            </label>

            <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                <i class="fa-solid fa-plus fa-fw" aria-hidden="true"></i> 追加する
            </button>
        </div>
    </form>
</div>
