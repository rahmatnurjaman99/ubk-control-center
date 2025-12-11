<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AttendanceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\ValidationException;

class StaffAttendance extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'staff_id',
        'recorded_on',
        'status',
        'checked_in_at',
        'checked_out_at',
        'location',
        'recorded_by',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'recorded_on' => 'date',
            'status' => AttendanceStatus::class,
            'checked_in_at' => 'datetime',
            'checked_out_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (self $attendance): void {
            if ($attendance->status === AttendanceStatus::Present) {
                return;
            }

            if (blank($attendance->notes)) {
                throw ValidationException::withMessages([
                    'notes' => __('filament.staff_attendances.validation.reason_required'),
                ]);
            }
        });
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
