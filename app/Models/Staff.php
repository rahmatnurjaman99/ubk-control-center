<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EducationLevel;
use App\Enums\StaffRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'staff_number',
        'staff_name',
        'role',
        'joined_on',
        'phone',
        'education_level',
        'education_institution',
        'graduated_year',
        'emergency_contact_name',
        'emergency_contact_phone',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'role' => StaffRole::class,
            'joined_on' => 'date',
            'education_level' => EducationLevel::class,
            'graduated_year' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<StaffDocument>
     */
    public function documents(): HasMany
    {
        return $this->hasMany(StaffDocument::class);
    }

    /**
     * @return HasMany<ClassroomStaff>
     */
    public function classroomAssignments(): HasMany
    {
        return $this->hasMany(ClassroomStaff::class);
    }

    /**
     * @return HasMany<StaffAttendance>
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(StaffAttendance::class);
    }
}
