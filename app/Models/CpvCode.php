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
        'check_digit',
        'title',
        'level',
    ];

    protected $casts = [
        'level' => 'integer',
        'check_digit' => 'integer',
    ];

    /**
     * Get full CPV code with check digit (e.g., "72000000-7").
     */
    public function getFullCodeAttribute(): string
    {
        if ($this->check_digit !== null) {
            return $this->code . '-' . $this->check_digit;
        }
        return $this->code;
    }

    /**
     * Calculate parent code from the code itself.
     * CPV hierarchy is encoded in the code structure.
     *
     * CPV codes: XX000000 (div) -> XXYY0000 (group) -> XXYYZZ00 (class) -> XXYYZZKK (category)
     *
     * The parent is formed by setting the last non-zero digit to zero:
     * - 72222300 -> 72222000 (last digit 3 -> 0)
     * - 72222000 -> 72220000 (last digit 2 -> 0)
     * - 72220000 -> 72200000 (last digit 2 -> 0)
     * - 72200000 -> 72000000 (last digit 2 -> 0)
     * - 72000000 -> null (level 1, no parent)
     */
    public function getParentCodeAttribute(): ?string
    {
        // Level 1 codes (XX000000) have no parent
        if ($this->level === 1) {
            return null;
        }

        // Remove trailing zeros to find significant part
        $trimmed = rtrim($this->code, '0');

        if (strlen($trimmed) <= 2) {
            return null; // Already at top level
        }

        // Set the last digit to 0 and pad back to 8 digits
        $parentTrimmed = substr($trimmed, 0, -1) . '0';
        $parentCode = str_pad($parentTrimmed, 8, '0');

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
