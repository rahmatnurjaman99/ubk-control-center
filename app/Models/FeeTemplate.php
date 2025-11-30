<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\FeeType;
use App\Enums\GradeLevel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeeTemplate extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'grade_level',
        'type',
        'amount',
        'currency',
        'due_in_days',
        'is_active',
        'description',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'grade_level' => GradeLevel::class,
            'type' => FeeType::class,
            'amount' => 'decimal:2',
            'is_active' => 'boolean',
            'due_in_days' => 'integer',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
