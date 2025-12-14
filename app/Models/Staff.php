<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EducationLevel;
use App\Enums\StaffRole;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        'gender',
        'photo_path',
        'role',
        'joined_on',
        'phone',
        'address',
        'province_id',
        'regency_id',
        'district_id',
        'village_id',
        'bank_name',
        'bank_account_name',
        'bank_account_number',
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

    /**
     * @return HasMany<Schedule>
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'staff_id');
    }

    /**
     * @return HasMany<SalaryStructure>
     */
    public function salaryStructures(): HasMany
    {
        return $this->hasMany(SalaryStructure::class);
    }

    /**
     * @return HasMany<PayrollItem>
     */
    public function payrollItems(): HasMany
    {
        return $this->hasMany(PayrollItem::class);
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
        $name = urlencode($this->staff_name ?? 'Staff');

        $background = match ($this->gender) {
            'female' => 'F472B6',
            'male' => '2563EB',
            default => '0D9488',
        };

        return "https://ui-avatars.com/api/?name={$name}&background={$background}&color=FFFFFF&size=256";
    }

    public static function generateStaffNumber(): string
    {
        $prefix = 'STF-' . now()->format('Ym');

        do {
            $number = $prefix . '-' . Str::upper(Str::random(4));
        } while (self::withTrashed()->where('staff_number', $number)->exists());

        return $number;
    }
}
