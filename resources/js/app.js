// 単語カードのスピーカーボタン用: Web Speech API (SpeechSynthesis) で
// 追加コストなしに発音を再生する。voiceを明示的にen-USへ寄せないと
// ブラウザ既定の声（言語が曖昧・訛りが強い等）が選ばれてしまうため、
// 利用可能なボイス一覧からアメリカ英語のものを探して指定する。
let americanEnglishVoice = null;

// 同じen-USでも、OS標準搭載のローカル合成音声（ロボット的な発音になりがち）と、
// クラウド経由の高品質な音声（Chromeの"Google US English"、Edgeの"...Online (Natural)"、
// macOSの"Enhanced/Premium"ボイス等）が混在している。名前のパターンで後者を優先し、
// 学習に適した自然な発音になるようにする。
const VOICE_QUALITY_PATTERNS = [/natural|online/i, /google/i, /premium|enhanced/i];

function voiceQualityRank(voice) {
    const patternIndex = VOICE_QUALITY_PATTERNS.findIndex((pattern) => pattern.test(voice.name));

    if (patternIndex !== -1) {
        return patternIndex;
    }

    // 名前に一致しなくても、ローカル合成でない（クラウド提供の）ボイスはある程度優先する。
    return voice.localService ? VOICE_QUALITY_PATTERNS.length + 1 : VOICE_QUALITY_PATTERNS.length;
}

function pickAmericanEnglishVoice() {
    const voices = window.speechSynthesis.getVoices();
    const englishVoices = voices.filter((voice) => voice.lang === 'en-US');
    const candidates = englishVoices.length > 0 ? englishVoices : voices.filter((voice) => voice.lang?.startsWith('en'));

    americanEnglishVoice = [...candidates].sort((a, b) => voiceQualityRank(a) - voiceQualityRank(b))[0] ?? null;
}

if ('speechSynthesis' in window) {
    pickAmericanEnglishVoice();
    // ボイス一覧は非同期に読み込まれるブラウザがあるため、
    // 準備できたタイミングで再度取得し直す。
    window.speechSynthesis.onvoiceschanged = pickAmericanEnglishVoice;
}

function speakWord(word) {
    if (!('speechSynthesis' in window) || !word) {
        return;
    }

    window.speechSynthesis.cancel();

    const utterance = new SpeechSynthesisUtterance(word);
    utterance.lang = 'en-US';

    if (americanEnglishVoice) {
        utterance.voice = americanEnglishVoice;
    }

    window.speechSynthesis.speak(utterance);
}

document.addEventListener('click', (event) => {
    const button = event.target.closest('.speak-btn');

    if (button) {
        speakWord(button.dataset.word);
    }
});

// data-preserve-scroll を付けたフォーム（学習した/覚えたのチェック、編集、削除）は
// ページ全体を再読み込みして送信するため、送信直前にスクロール位置を保存しておく。
// 復元はこのスクリプト（<script type="module">なのでDOM解析後まで実行が遅れる）では
// なく、layouts/app.blade.php末尾の同期的なインラインスクリプトで行う。ここで復元
// すると、一瞬先頭にジャンプしてから正しい位置へ戻るチラつきが起きてしまうため。
document.addEventListener('submit', (event) => {
    if (event.target.matches('[data-preserve-scroll]')) {
        sessionStorage.setItem('vocareview:scrollY', String(window.scrollY));
    }
});

// 「＋追加」「削除」で行を増減する繰り返し入力欄（例文・意味など）共通のロジック。
// <template>の中身を複製し、name属性の__INDEX__は連番に置き換える。examples[][example_en]
// のように添字なしの[]にすると、PHP側で複数フィールドが行ごとに正しく対応付かなくなるため。
document.addEventListener('click', (event) => {
    const addButton = event.target.closest('[data-add-row]');

    if (addButton) {
        const wrapper = addButton.closest('[data-repeatable-fields]');
        const rowsContainer = wrapper.querySelector('[data-repeatable-rows]');
        const template = wrapper.querySelector('[data-repeatable-template]');
        const nextIndex = Number(wrapper.dataset.nextIndex);

        const row = template.content.firstElementChild.cloneNode(true);
        row.querySelectorAll('[name]').forEach((field) => {
            field.name = field.name.replace('__INDEX__', String(nextIndex));
        });

        rowsContainer.appendChild(row);
        wrapper.dataset.nextIndex = String(nextIndex + 1);

        return;
    }

    const removeButton = event.target.closest('[data-remove-row]');

    if (removeButton) {
        removeButton.closest('[data-repeatable-row]').remove();
    }
});
