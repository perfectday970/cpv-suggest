<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CpvCode extends Model
{
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'code',
        'title',
        'level',
        'parent_code',
    ];

    protected $casts = [
        'level' => 'integer',
    ];

    /**
     * Get the parent CPV code.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(CpvCode::class, 'parent_code', 'code');
    }

    /**
     * Get child CPV codes.
     */
    public function children(): HasMany
    {
        return $this->hasMany(CpvCode::class, 'parent_code', 'code');
    }

    /**
     * Get synonyms for this CPV code.
     */
    public function synonyms(): HasMany
    {
        return $this->hasMany(CpvSynonym::class, 'code', 'code');
    }

    /**
     * Get the full hierarchical path of this CPV code.
     */
    public function getPath(): array
    {
        $path = [];
        $current = $this;

        while ($current) {
            array_unshift($path, [
                'code' => $current->code,
                'title' => $current->title,
            ]);
            $current = $current->parent;
        }

        return $path;
    }

    /**
     * Search CPV codes by title or code.
     */
    public static function search(string $query, int $limit = 10): array
    {
        return self::where('title', 'ILIKE', "%{$query}%")
            ->orWhere('code', 'LIKE', "{$query}%")
            ->limit($limit)
            ->get()
            ->toArray();
    }
}
