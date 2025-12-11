<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TahfidzLogStatus;
use App\Enums\TahfidzTargetStatus;
use App\Support\Quran\QuranOptions;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TahfidzTarget extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::saved(function (self $target): void {
            $target->syncPrimaryRange();
        });
    }

    /**
     * @var list<string>
     */
    protected $fillable = [
        'student_id',
        'classroom_id',
        'assigned_by_id',
        'surah_id',
        'start_ayah_number',
        'end_ayah_number',
        'target_repetitions',
        'assigned_on',
        'due_on',
        'status',
        'tag',
        'notes',
        'metadata',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'assigned_on' => 'date',
            'due_on' => 'date',
            'metadata' => 'array',
            'status' => TahfidzTargetStatus::class,
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by_id');
    }

    /**
     * @return HasMany<TahfidzLog>
     */
    public function logs(): HasMany
    {
        return $this->hasMany(TahfidzLog::class);
    }

    /**
     * @return HasMany<TahfidzTargetSegment>
     */
    public function segments(): HasMany
    {
        return $this->hasMany(TahfidzTargetSegment::class)
            ->orderBy('sequence')
            ->orderBy('id');
    }

    protected function surahLabel(): Attribute
    {
        return Attribute::make(
            get: fn (): ?string => QuranOptions::surahLabel($this->surah_id),
        );
    }

    protected function ayahRangeLabel(): Attribute
    {
        return Attribute::make(
            get: fn (): ?string => $this->start_ayah_number === null
                ? null
                : sprintf(
                    'Ayah %d–%d',
                    $this->start_ayah_number,
                    $this->end_ayah_number ?? $this->start_ayah_number,
                ),
        );
    }

    protected function rangeSummary(): Attribute
    {
        return Attribute::make(
            get: function (): ?string {
                $segments = $this->segments;

                if ($segments->isEmpty()) {
                    if ($this->surah_id === null || $this->start_ayah_number === null) {
                        return null;
                    }

                    return sprintf(
                        '%s (%d–%d)',
                        QuranOptions::surahLabel($this->surah_id),
                        $this->start_ayah_number,
                        $this->end_ayah_number ?? $this->start_ayah_number,
                    );
                }

                return $segments
                    ->map(fn (TahfidzTargetSegment $segment): string => sprintf(
                        '%s (%d–%d)',
                        QuranOptions::surahLabel($segment->surah_id),
                        $segment->start_ayah_number,
                        $segment->end_ayah_number,
                    ))
                    ->implode(', ');
            },
        );
    }

    protected function totalAyahCount(): Attribute
    {
        return Attribute::make(
            get: function (): int {
                $segments = $this->segments;

                if ($segments->isEmpty()) {
                    if ($this->start_ayah_number === null) {
                        return 0;
                    }

                    return max(
                        0,
                        ($this->end_ayah_number ?? $this->start_ayah_number) - $this->start_ayah_number + 1,
                    );
                }

                return $segments->sum(fn (TahfidzTargetSegment $segment): int => max(
                    0,
                    $segment->end_ayah_number - $segment->start_ayah_number + 1,
                ));
            },
        );
    }

    protected function completedAyahCount(): Attribute
    {
        return Attribute::make(
            get: fn (): int => $this->logs
                ->where('status', TahfidzLogStatus::Passed)
                ->sum(function (TahfidzLog $log): int {
                    $start = (int) ($log->start_ayah_number ?? 0);
                    $end = (int) ($log->end_ayah_number ?? $start);

                    return $start > 0 && $end >= $start ? ($end - $start + 1) : 0;
                }),
        );
    }

    protected function progressPercentage(): Attribute
    {
        return Attribute::make(
            get: function (): float {
                $total = $this->total_ayah_count;

                if ($total === 0) {
                    return 0.0;
                }

                return round(($this->completed_ayah_count / $total) * 100, 1);
            },
        );
    }

    public function primarySegment(): ?TahfidzTargetSegment
    {
        return $this->segments()->orderBy('sequence')->orderBy('id')->first();
    }

    public function syncPrimaryRange(): void
    {
        $segment = $this->primarySegment();

        if ($segment === null) {
            if ($this->surah_id === null && $this->start_ayah_number === null && $this->end_ayah_number === null) {
                return;
            }

            $this->forceFill([
                'surah_id' => null,
                'start_ayah_number' => null,
                'end_ayah_number' => null,
            ])->saveQuietly();

            return;
        }

        if (
            $this->surah_id === $segment->surah_id
            && $this->start_ayah_number === $segment->start_ayah_number
            && $this->end_ayah_number === $segment->end_ayah_number
        ) {
            return;
        }

        $this->forceFill([
            'surah_id' => $segment->surah_id,
            'start_ayah_number' => $segment->start_ayah_number,
            'end_ayah_number' => $segment->end_ayah_number,
        ])->saveQuietly();
    }
}
