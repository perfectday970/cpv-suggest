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
    ];

    protected $casts = [
        'level' => 'integer',
    ];

    /**
     * Calculate parent code from the code itself.
     * CPV hierarchy is encoded in the code structure.
     *
     * CPV codes: XX000000 (div) -> XXYY0000 (group) -> XXYYZZ00 (class) -> XXYYZZKK (category) -> XXYYZZKKT (subcategory)
     */
    public function getParentCodeAttribute(): ?string
    {
        // Remove trailing zeros to find significant digits
        $trimmed = rtrim($this->code, '0');

        // Level 1 codes have no parent (only 2 significant digits)
        if (strlen($trimmed) <= 2) {
            return null;
        }

        // Parent is formed by removing last 2 significant digits and padding with zeros
        $parent = substr($trimmed, 0, -2);
        $parentCode = str_pad($parent, 8, '0');

        // Avoid self-reference
        if ($parentCode === $this->code) {
            return null;
        }

        return $parentCode;
    }

    /**
     * Get the parent CPV code.
     */
    public function getParentAttribute(): ?CpvCode
    {
        $parentCode = $this->parent_code;
        if (!$parentCode) {
            return null;
        }

        return static::find($parentCode);
    }

    /**
     * Get child CPV codes.
     * Since we don't have a parent_code column anymore, we need to calculate children.
     */
    public function getChildrenAttribute()
    {
        $trimmed = rtrim($this->code, '0');
        $significantLength = strlen($trimmed);

        // Find all codes where:
        // 1. They start with this code's significant digits
        // 2. They have exactly 2 more significant digits (direct children only)
        return static::where('code', 'LIKE', $trimmed . '%')
            ->where('code', '!=', $this->code)
            ->get()
            ->filter(function ($code) use ($significantLength) {
                $childTrimmed = rtrim($code->code, '0');
                return strlen($childTrimmed) === $significantLength + 2;
            })
            ->values();
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
