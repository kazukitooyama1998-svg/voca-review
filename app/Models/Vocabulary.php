<?php

namespace App\Models;

use App\Enums\PartOfSpeech;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['word', 'part_of_speech', 'meaning', 'example_en', 'example_ja', 'is_memorized'])]
class Vocabulary extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'part_of_speech' => PartOfSpeech::class,
            'is_memorized' => 'boolean',
            'studied_at' => 'datetime',
        ];
    }
}
