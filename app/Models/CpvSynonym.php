<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CpvSynonym extends Model
{
    protected $fillable = [
        'term',
        'code',
        'weight',
    ];

    protected $casts = [
        'weight' => 'float',
    ];

    /**
     * Get the CPV code associated with this synonym.
     */
    public function cpvCode(): BelongsTo
    {
        return $this->belongsTo(CpvCode::class, 'code', 'code');
    }

    /**
     * Find CPV code by matching synonym term.
     */
    public static function match(string $term): ?self
    {
        return self::where('term', 'ILIKE', "%{$term}%")
            ->orderByDesc('weight')
            ->first();
    }

    /**
     * Find all matching synonyms for a term.
     */
    public static function findMatches(string $term, int $limit = 10): array
    {
        return self::where('term', 'ILIKE', "%{$term}%")
            ->orderByDesc('weight')
            ->limit($limit)
            ->with('cpvCode')
            ->get()
            ->toArray();
    }
}
