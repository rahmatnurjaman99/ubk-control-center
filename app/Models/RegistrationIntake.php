<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\GradeLevel;
use App\Enums\RegistrationStatus;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegistrationIntake extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'form_number',
        'payment_reference',
        'payment_method',
        'payment_amount',
        'payment_received_at',
        'guardian_name',
        'guardian_phone',
        'guardian_email',
        'guardian_address',
        'student_full_name',
        'student_date_of_birth',
        'student_gender',
        'target_grade_level',
        'academic_year_id',
        'classroom_id',
        'student_id',
        'processed_by',
        'processed_at',
        'status',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'payment_amount' => 'decimal:2',
            'payment_received_at' => 'date',
            'student_date_of_birth' => 'date',
            'processed_at' => 'datetime',
            'status' => RegistrationStatus::class,
            'target_grade_level' => GradeLevel::class,
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $intake): void {
            if (blank($intake->form_number)) {
                $intake->form_number = self::generateFormNumber();
            }
        });
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * @return HasMany<RegistrationIntakeDocument>
     */
    public function documents(): HasMany
    {
        return $this->hasMany(RegistrationIntakeDocument::class);
    }

    public static function generateFormNumber(): string
    {
        do {
            $number = 'REG-' . now()->format('Ymd') . '-' . Str::upper(Str::random(4));
        } while (self::withTrashed()->where('form_number', $number)->exists());

        return $number;
    }
}
