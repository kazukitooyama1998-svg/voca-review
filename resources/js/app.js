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
