<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ScholarshipType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Scholarship extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'type',
        'amount',
        'starts_on',
        'ends_on',
        'is_active',
    ];

    protected $casts = [
        'type' => ScholarshipType::class,
        'starts_on' => 'date',
        'ends_on' => 'date',
        'is_active' => 'boolean',
        'amount' => 'decimal:2',
    ];

    /**
     * @return BelongsToMany<Student>
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class)
            ->withPivot(['effective_from', 'effective_until', 'is_active', 'notes'])
            ->withTimestamps();
    }

    /**
     * @return HasMany<FeePartialApproval>
     */
    public function partialApprovals(): HasMany
    {
        return $this->hasMany(FeePartialApproval::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
