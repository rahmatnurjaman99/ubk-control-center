<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PayrollItemStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollItem extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'payroll_id',
        'staff_id',
        'salary_structure_id',
        'status',
        'base_salary',
        'allowances',
        'allowances_total',
        'deductions',
        'deductions_total',
        'net_amount',
        'currency',
        'notes',
        'metadata',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => PayrollItemStatus::class,
            'base_salary' => 'decimal:2',
            'allowances' => 'array',
            'allowances_total' => 'decimal:2',
            'deductions' => 'array',
            'deductions_total' => 'decimal:2',
            'net_amount' => 'decimal:2',
            'metadata' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (self $item): void {
            $item->allowances_total = self::calculateComponentTotal($item->allowances ?? []);
            $item->deductions_total = self::calculateComponentTotal($item->deductions ?? []);
            $item->net_amount = max(
                (float) $item->base_salary + (float) $item->allowances_total - (float) $item->deductions_total,
                0,
            );
        });
    }

    public function payroll(): BelongsTo
    {
        return $this->belongsTo(Payroll::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function salaryStructure(): BelongsTo
    {
        return $this->belongsTo(SalaryStructure::class);
    }

    /**
     * @param array<int, array<string, mixed>> $components
     */
    private static function calculateComponentTotal(array $components): float
    {
        return collect($components)
            ->sum(fn (array $component): float => (float) ($component['amount'] ?? 0));
    }
}
