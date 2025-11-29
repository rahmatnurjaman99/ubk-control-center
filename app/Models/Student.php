<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\GradeLevel;
use App\Enums\StudentStatus;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'date_of_birth',
        'gender',
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

    /**
     * @return HasMany<ClassroomAssignment>
     */
    public function classroomAssignments(): HasMany
    {
        return $this->hasMany(ClassroomAssignment::class);
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
}
