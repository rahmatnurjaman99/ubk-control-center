<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Guardian extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'guardian_number',
        'full_name',
        'relationship',
        'phone',
        'email',
        'occupation',
        'address',
        'legacy_reference',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<Student>
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public static function generateGuardianNumber(): string
    {
        $prefix = 'GRD-' . now()->format('Ym');

        do {
            $number = $prefix . '-' . Str::upper(Str::random(4));
        } while (self::withTrashed()->where('guardian_number', $number)->exists());

        return $number;
    }
}
