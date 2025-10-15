<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Bekanntmachung extends Model
{
    protected $table = 'bekanntmachungen';

    protected $fillable = [
        'veroeffentlicht',
        'angebotsfrist',
        'kurzbezeichnung',
        'typ',
        'vergabeplattform',
        'geschaetzter_auftragswert',
        'beschreibung',
    ];

    protected $casts = [
        'veroeffentlicht' => 'date',
        'angebotsfrist' => 'date',
        'geschaetzter_auftragswert' => 'decimal:2',
    ];

    /**
     * Get the CPV codes associated with this Bekanntmachung.
     */
    public function cpvCodes(): BelongsToMany
    {
        return $this->belongsToMany(
            CpvCode::class,
            'bekanntmachung_cpv',
            'bekanntmachung_id',
            'cpv_code',
            'id',
            'code'
        );
    }

    /**
     * Scope to filter by CPV codes.
     */
    public function scopeWithCpvCodes($query, array $cpvCodes)
    {
        return $query->whereHas('cpvCodes', function ($q) use ($cpvCodes) {
            $q->whereIn('cpv_codes.code', $cpvCodes);
        });
    }

    /**
     * Scope to filter by date range.
     */
    public function scopePublishedBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('veroeffentlicht', [$startDate, $endDate]);
    }

    /**
     * Scope to filter by submission deadline.
     */
    public function scopeDeadlineAfter($query, $date)
    {
        return $query->where('angebotsfrist', '>=', $date);
    }
}
