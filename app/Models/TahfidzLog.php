<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TahfidzLogStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TahfidzLog extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'tahfidz_target_id',
        'student_id',
        'recorded_by_id',
        'evaluated_by_staff_id',
        'surah_id',
        'start_ayah_number',
        'end_ayah_number',
        'recorded_on',
        'status',
        'memorization_score',
        'tajwid_score',
        'fluency_score',
        'is_revision',
        'notes',
        'metadata',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'recorded_on' => 'date',
            'status' => TahfidzLogStatus::class,
            'metadata' => 'array',
            'is_revision' => 'boolean',
        ];
    }

    public function target(): BelongsTo
    {
        return $this->belongsTo(TahfidzTarget::class, 'tahfidz_target_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by_id');
    }

    public function evaluatedByStaff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'evaluated_by_staff_id');
    }
}
