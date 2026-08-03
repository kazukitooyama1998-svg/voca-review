<?php

namespace App\Models;

use App\Enums\PartOfSpeech;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['word', 'parts_of_speech', 'meaning', 'is_memorized'])]
class Vocabulary extends Model
{
    /**
     * The example sentences (English/Japanese pairs) registered for this word.
     */
    public function examples(): HasMany
    {
        return $this->hasMany(VocabularyExample::class)->oldest('id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'parts_of_speech' => AsEnumCollection::of(PartOfSpeech::class),
            'is_memorized' => 'boolean',
            'studied_at' => 'datetime',
        ];
    }

    /**
     * Comma-separated labels for all parts of speech, e.g. "Noun (名詞)・Verb (動詞)".
     */
    public function partsOfSpeechLabel(): string
    {
        return $this->parts_of_speech->map->label()->implode('・');
    }
}
