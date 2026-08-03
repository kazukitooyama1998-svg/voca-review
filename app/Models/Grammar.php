<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'explanation', 'is_memorized'])]
class Grammar extends Model
{
    /**
     * The example sentences (English/Japanese pairs) registered for this grammar point.
     */
    public function examples(): HasMany
    {
        return $this->hasMany(GrammarExample::class)->oldest('id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_memorized' => 'boolean',
            'studied_at' => 'datetime',
        ];
    }
}
