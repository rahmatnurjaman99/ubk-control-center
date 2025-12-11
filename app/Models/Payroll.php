<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PayrollStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Payroll extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'reference',
        'title',
        'status',
        'academic_year_id',
        'period_start',
        'period_end',
        'processed_at',
        'total_base_salary',
        'total_allowances',
        'total_deductions',
        'total_net',
        'currency',
        'staff_ids',
        'notes',
        'metadata',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => PayrollStatus::class,
            'period_start' => 'date',
            'period_end' => 'date',
            'processed_at' => 'datetime',
            'total_base_salary' => 'decimal:2',
            'total_allowances' => 'decimal:2',
            'total_deductions' => 'decimal:2',
            'total_net' => 'decimal:2',
            'staff_ids' => 'array',
            'metadata' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $payroll): void {
            if (blank($payroll->reference)) {
                $payroll->reference = self::generateReference();
            }

            if (blank($payroll->currency)) {
                $payroll->currency = 'IDR';
            }
        });
    }

    public static function generateReference(): string
    {
        do {
            $reference = 'PRL-' . now()->format('Ymd') . '-' . Str::upper(Str::random(4));
        } while (self::withTrashed()->where('reference', $reference)->exists());

        return $reference;
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * @return HasMany<PayrollItem>
     */
    public function items(): HasMany
    {
        return $this->hasMany(PayrollItem::class);
    }

    public function scopeStatus(Builder $query, PayrollStatus|string $status): Builder
    {
        $value = $status instanceof PayrollStatus ? $status->value : (string) $status;

        return $query->where('status', $value);
    }

    /**
     * @return list<int>
     */
    public function getStaffFilter(): array
    {
        $staff = $this->staff_ids ?? [];

        return collect($staff)
            ->filter(fn ($id): bool => filled($id))
            ->map(fn ($id): int => (int) $id)
            ->values()
            ->all();
    }

    public function refreshTotals(): void
    {
        $totals = $this->items()
            ->selectRaw('
                COALESCE(SUM(base_salary), 0) as base_salary,
                COALESCE(SUM(allowances_total), 0) as allowances_total,
                COALESCE(SUM(deductions_total), 0) as deductions_total,
                COALESCE(SUM(net_amount), 0) as net_amount
            ')
            ->first();

        $this->forceFill([
            'total_base_salary' => (float) ($totals?->base_salary ?? 0),
            'total_allowances' => (float) ($totals?->allowances_total ?? 0),
            'total_deductions' => (float) ($totals?->deductions_total ?? 0),
            'total_net' => (float) ($totals?->net_amount ?? 0),
        ])->save();
    }
}
