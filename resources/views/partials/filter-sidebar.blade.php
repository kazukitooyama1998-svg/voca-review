<div class="space-y-6">
    <div class="rounded-2xl bg-white p-6 shadow-sm">
        <h2 class="mb-4 flex items-center gap-2 text-base font-bold text-gray-800">
            <span aria-hidden="true">🔍</span> 絞り込み
        </h2>

        <form action="#" method="GET" class="space-y-6">
            <fieldset>
                <legend class="mb-2 text-sm font-semibold text-gray-700">覚えた状態</legend>
                <div class="space-y-2 text-sm text-gray-700">
                    <label class="flex items-center gap-2">
                        <input type="radio" name="memorized" value="all" checked class="border-gray-300 text-blue-600 focus:ring-blue-500">
                        すべて
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="memorized" value="memorized" class="border-gray-300 text-blue-600 focus:ring-blue-500">
                        覚えた
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="memorized" value="unmemorized" class="border-gray-300 text-blue-600 focus:ring-blue-500">
                        覚えていない
                    </label>
                </div>
            </fieldset>

            <fieldset>
                <legend class="mb-2 text-sm font-semibold text-gray-700">種類</legend>
                <div class="space-y-2 text-sm text-gray-700">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="types[]" value="vocabulary" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        単語・フレーズ
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="types[]" value="grammar" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        文法（Grammar）
                    </label>
                </div>
            </fieldset>
        </form>
    </div>

    <div class="rounded-2xl border border-amber-200 bg-amber-100/60 p-5 text-sm text-amber-900">
        <span aria-hidden="true">💡</span>
        1日に100項目を目標にコツコツ続けましょう！
    </div>
</div>
