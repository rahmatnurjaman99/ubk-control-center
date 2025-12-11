<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalaryStructure extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'staff_id',
        'academic_year_id',
        'title',
        'currency',
        'base_salary',
        'allowances',
        'allowances_total',
        'deductions',
        'deductions_total',
        'effective_date',
        'expires_on',
        'is_active',
        'notes',
        'metadata',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'base_salary' => 'decimal:2',
            'allowances' => 'array',
            'allowances_total' => 'decimal:2',
            'deductions' => 'array',
            'deductions_total' => 'decimal:2',
            'effective_date' => 'date',
            'expires_on' => 'date',
            'is_active' => 'boolean',
            'metadata' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (self $structure): void {
            $structure->allowances_total = self::calculateComponentTotal($structure->allowances ?? []);
            $structure->deductions_total = self::calculateComponentTotal($structure->deductions ?? []);
        });
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function calculateGrossAmount(): float
    {
        return (float) $this->base_salary + (float) $this->allowances_total;
    }

    public function calculateNetAmount(): float
    {
        return max($this->calculateGrossAmount() - (float) $this->deductions_total, 0);
    }

    /**
     * @param array<int, array<string, mixed>> $components
     */
    private static function calculateComponentTotal(array $components): float
    {
        return collect($components)
            ->sum(function (array $component): float {
                return (float) ($component['amount'] ?? 0);
            });
    }
}
