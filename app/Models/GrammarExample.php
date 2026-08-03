<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['example_en', 'example_ja'])]
class GrammarExample extends Model
{
    /**
     * The grammar point this example sentence belongs to.
     */
    public function grammar(): BelongsTo
    {
        return $this->belongsTo(Grammar::class);
    }
}
