@props(['text'])

{{-- タップ（クリック）するまで意味を隠しておき、タップでカードが裏返るように見せるコンポーネント。
     裏返り演出自体はresources/css/app.cssの.flashcard-*クラス、開閉のトグルは
     resources/js/app.jsの.meaning-flashcardクリックリスナーで行う。実際の意味はページ内に
     そのまま出力されている（隠しているのは見た目だけ）ので、JSが無効でも内容は読める。 --}}
<button
    type="button"
    class="meaning-flashcard mt-1 block w-full max-w-full cursor-pointer border-0 bg-transparent p-0 text-left"
    aria-expanded="false"
>
    <span class="flashcard-inner">
        <span class="flashcard-face flashcard-front inline-flex items-center gap-1.5 rounded-lg border border-dashed border-gray-300 bg-gray-50 px-3 py-1 text-sm text-gray-400">
            <i class="fa-solid fa-rotate fa-fw"></i>
            タップして意味を表示
        </span>
        <span class="flashcard-face flashcard-back text-sm text-gray-600">
            {{ $text }}
        </span>
    </span>
</button>
