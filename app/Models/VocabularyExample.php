<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['example_en', 'example_ja'])]
class VocabularyExample extends Model
{
    /**
     * The vocabulary this example sentence belongs to.
     */
    public function vocabulary(): BelongsTo
    {
        return $this->belongsTo(Vocabulary::class);
    }
}
