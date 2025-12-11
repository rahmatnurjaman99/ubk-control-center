<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\FeeStatus;
use App\Enums\GradeLevel;
use App\Enums\StudentStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class Student extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'guardian_id',
        'user_id',
        'academic_year_id',
        'classroom_id',
        'student_number',
        'full_name',
        'photo_path',
        'date_of_birth',
        'gender',
        'address',
        'province_id',
        'regency_id',
        'district_id',
        'village_id',
        'status',
        'enrolled_on',
        'legacy_reference',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => StudentStatus::class,
            'date_of_birth' => 'date',
            'enrolled_on' => 'date',
        ];
    }

    public function guardian(): BelongsTo
    {
        return $this->belongsTo(Guardian::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function regency(): BelongsTo
    {
        return $this->belongsTo(Regency::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }

    /**
     * @return HasMany<ClassroomAssignment>
     */
    public function classroomAssignments(): HasMany
    {
        return $this->hasMany(ClassroomAssignment::class);
    }

    /**
     * @return HasMany<StudentAttendance>
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(StudentAttendance::class);
    }

    /**
     * @return HasMany<StudentDocument>
     */
    public function documents(): HasMany
    {
        return $this->hasMany(StudentDocument::class);
    }

    /**
     * @return HasManyThrough<RegistrationIntakeDocument>
     */
    public function registrationDocuments(): HasManyThrough
    {
        return $this->hasManyThrough(
            RegistrationIntakeDocument::class,
            RegistrationIntake::class,
            'student_id',
            'registration_intake_id',
        );
    }

    /**
     * @return HasMany<Fee>
     */
    public function fees(): HasMany
    {
        return $this->hasMany(Fee::class);
    }

    /**
     * @return HasMany<PromotionApproval>
     */
    public function promotionApprovals(): HasMany
    {
        return $this->hasMany(PromotionApproval::class);
    }

    /**
     * @return HasMany<TahfidzTarget>
     */
    public function tahfidzTargets(): HasMany
    {
        return $this->hasMany(TahfidzTarget::class);
    }

    /**
     * @return HasMany<TahfidzLog>
     */
    public function tahfidzLogs(): HasMany
    {
        return $this->hasMany(TahfidzLog::class);
    }

    /**
     * @return HasMany<Fee>
     */
    public function outstandingFees(): HasMany
    {
        return $this->fees()->whereIn('status', [
            FeeStatus::Pending,
            FeeStatus::Partial,
        ]);
    }

    public function hasOutstandingFees(): bool
    {
        return $this->outstandingFees()->exists();
    }

    /**
     * @return Collection<int, Fee>
     */
    public function getOutstandingFees(): Collection
    {
        return $this->outstandingFees()
            ->orderBy('due_date')
            ->get();
    }

    public function determineCurrentGradeLevel(): ?GradeLevel
    {
        if ($this->classroom?->grade_level instanceof GradeLevel) {
            return $this->classroom->grade_level;
        }

        $latestAssignment = $this->classroomAssignments()
            ->orderByDesc('assigned_on')
            ->orderByDesc('id')
            ->first();

        return $latestAssignment?->grade_level;
    }

    public function getCurrentGradeLevelAttribute(): ?GradeLevel
    {
        return $this->determineCurrentGradeLevel();
    }

    public function getNextGradeLevelAttribute(): ?GradeLevel
    {
        return $this->determineCurrentGradeLevel()?->next();
    }

    protected function photoUrl(): Attribute
    {
        return Attribute::make(
            get: fn (): string => filled($this->photo_path)
                ? Storage::disk('public')->url($this->photo_path)
                : $this->getDefaultPhotoUrl(),
        );
    }

    private function getDefaultPhotoUrl(): string
    {
        $name = urlencode($this->full_name ?? 'Student');
        $background = match ($this->gender) {
            'female' => 'F472B6',
            'male' => '2563EB',
            default => '6B7280',
        };

        return "https://ui-avatars.com/api/?name={$name}&background={$background}&color=FFFFFF&size=256";
    }
}
