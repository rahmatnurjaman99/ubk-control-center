<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TahfidzTargetSegment extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::saved(function (self $segment): void {
            $segment->target?->syncPrimaryRange();
        });

        static::deleted(function (self $segment): void {
            $segment->target?->syncPrimaryRange();
        });
    }

    /**
     * @var list<string>
     */
    protected $fillable = [
        'tahfidz_target_id',
        'surah_id',
        'start_ayah_number',
        'end_ayah_number',
        'sequence',
        'metadata',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    public function target(): BelongsTo
    {
        return $this->belongsTo(TahfidzTarget::class, 'tahfidz_target_id');
    }
}
