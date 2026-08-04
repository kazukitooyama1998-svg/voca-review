<?php

namespace App\Enums;

enum PartOfSpeech: string
{
    case Noun = 'noun';
    case Verb = 'verb';
    case Adjective = 'adjective';
    case Adverb = 'adverb';
    case Pronoun = 'pronoun';
    case Preposition = 'preposition';
    case Conjunction = 'conjunction';
    case Interjection = 'interjection';
    case Idiom = 'idiom';

    /**
     * Japanese label used in the UI, e.g. "Noun (名詞)".
     */
    public function label(): string
    {
        return match ($this) {
            self::Noun => 'Noun (名詞)',
            self::Verb => 'Verb (動詞)',
            self::Adjective => 'Adjective (形容詞)',
            self::Adverb => 'Adverb (副詞)',
            self::Pronoun => 'Pronoun (代名詞)',
            self::Preposition => 'Preposition (前置詞)',
            self::Conjunction => 'Conjunction (接続詞)',
            self::Interjection => 'Interjection (感動詞)',
            self::Idiom => 'Idiom (イディオム)',
        };
    }
}
