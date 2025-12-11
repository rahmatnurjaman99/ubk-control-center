<?php

declare(strict_types=1);

namespace App\Support\Finance;

use App\Enums\PayrollItemStatus;
use App\Enums\PayrollStatus;
use App\Models\Payroll;
use App\Models\PayrollItem;
use App\Models\SalaryStructure;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Carbon;

class PayrollProcessor
{
    /**
     * @param list<int>|null $staffIds
     * @return EloquentCollection<int, PayrollItem>
     */
    public function generate(Payroll $payroll, ?array $staffIds = null): EloquentCollection
    {
        $targetStaff = $staffIds ?? $payroll->getStaffFilter();
        $periodEnd = $payroll->period_end ?? Carbon::now();
        $periodStart = $payroll->period_start;

        $structures = SalaryStructure::query()
            ->active()
            ->with('staff')
            ->when(
                $payroll->academic_year_id,
                fn ($query): mixed => $query->where(function ($inner) use ($payroll): void {
                    $inner
                        ->whereNull('academic_year_id')
                        ->orWhere('academic_year_id', $payroll->academic_year_id);
                }),
            )
            ->when($targetStaff !== [], fn ($query): mixed => $query->whereIn('staff_id', $targetStaff))
            ->whereDate('effective_date', '<=', $periodEnd->toDateString())
            ->when($periodStart, function ($query) use ($periodStart): void {
                $query->where(function ($inner) use ($periodStart): void {
                    $inner
                        ->whereNull('expires_on')
                        ->orWhere('expires_on', '>=', $periodStart?->toDateString());
                });
            })
            ->orderByDesc('effective_date')
            ->orderByDesc('id')
            ->get()
            ->unique('staff_id');

        $items = $structures->map(function (SalaryStructure $structure) use ($payroll): PayrollItem {
            return PayrollItem::query()->updateOrCreate(
                [
                    'payroll_id' => $payroll->id,
                    'staff_id' => $structure->staff_id,
                ],
                [
                    'salary_structure_id' => $structure->id,
                    'status' => PayrollItemStatus::Pending,
                    'base_salary' => $structure->base_salary,
                    'allowances' => $structure->allowances ?? [],
                    'allowances_total' => $structure->allowances_total,
                    'deductions' => $structure->deductions ?? [],
                    'deductions_total' => $structure->deductions_total,
                    'currency' => $structure->currency,
                    'notes' => $structure->notes,
                ],
            );
        });

        if ($items->isNotEmpty()) {
            $payroll->forceFill([
                'processed_at' => Carbon::now(),
                'status' => $payroll->status === PayrollStatus::Draft
                    ? PayrollStatus::Processing
                    : $payroll->status,
            ])->save();
        }

        $payroll->refreshTotals();

        return new EloquentCollection($items->values());
    }
}
