<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SchoolLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'academic_year_id',
        'subject_category_id',
        'school_level',
        'code',
        'name',
        'is_compulsory',
        'credit_hours',
        'description',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'school_level' => SchoolLevel::class,
            'is_compulsory' => 'boolean',
            'credit_hours' => 'integer',
        ];
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(SubjectCategory::class, 'subject_category_id');
    }

    /**
     * @return BelongsToMany<Classroom>
     */
    public function classrooms(): BelongsToMany
    {
        return $this->belongsToMany(Classroom::class)
            ->withTimestamps();
    }

    /**
     * @return HasMany<Schedule>
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }
}
