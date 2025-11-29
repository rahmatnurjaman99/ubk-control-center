<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\GradeLevel;
use App\Enums\SchoolLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classroom extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'academic_year_id',
        'homeroom_staff_id',
        'code',
        'name',
        'grade_level',
        'school_level',
        'capacity',
        'description',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'school_level' => SchoolLevel::class,
            'grade_level' => GradeLevel::class,
            'capacity' => 'integer',
        ];
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function homeroomStaff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'homeroom_staff_id');
    }

    /**
     * @return HasMany<Student>
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    /**
     * @return HasMany<ClassroomAssignment>
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(ClassroomAssignment::class);
    }

    /**
     * @return HasMany<ClassroomStaff>
     */
    public function staffAssignments(): HasMany
    {
        return $this->hasMany(ClassroomStaff::class);
    }

    /**
     * @return BelongsToMany<Subject>
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class)
            ->withTimestamps();
    }
}
