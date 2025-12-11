<?php

declare(strict_types=1);

use App\Enums\PayrollStatus;
use App\Models\Payroll;
use App\Models\SalaryStructure;
use App\Models\Staff;
use App\Support\Finance\PayrollProcessor;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('generates payroll items from active salary structures', function (): void {
    $staff = Staff::factory()->create();
    $payroll = Payroll::factory()->create([
        'period_start' => '2025-01-01',
        'period_end' => '2025-01-31',
        'staff_ids' => [],
        'academic_year_id' => null,
    ]);

    SalaryStructure::factory()->create([
        'staff_id' => $staff->id,
        'base_salary' => 5_000_000,
        'allowances' => [
            ['label' => 'Transport', 'amount' => 200_000],
        ],
        'deductions' => [
            ['label' => 'BPJS', 'amount' => 100_000],
        ],
        'effective_date' => '2024-12-01',
        'expires_on' => null,
        'academic_year_id' => null,
    ]);

    /** @var PayrollProcessor $processor */
    $processor = app(PayrollProcessor::class);
    $items = $processor->generate($payroll);

    expect($items)->toHaveCount(1);

    $item = $items->first();
    expect($item)
        ->staff_id->toBe($staff->id)
        ->net_amount->toEqual(5_100_000.0);

    $payroll->refresh();

    expect($payroll)
        ->total_net->toEqual(5_100_000.0)
        ->status->toBe(PayrollStatus::Processing);
});

it('filters payroll generation by selected staff', function (): void {
    [$included, $excluded] = Staff::factory()->count(2)->create();
    $payroll = Payroll::factory()->create([
        'period_start' => '2025-02-01',
        'period_end' => '2025-02-29',
        'staff_ids' => [$included->id],
        'academic_year_id' => null,
    ]);

    SalaryStructure::factory()->create([
        'staff_id' => $included->id,
        'base_salary' => 3_000_000,
        'effective_date' => '2025-01-15',
        'academic_year_id' => null,
    ]);

    SalaryStructure::factory()->create([
        'staff_id' => $excluded->id,
        'base_salary' => 9_000_000,
        'effective_date' => '2025-01-15',
        'academic_year_id' => null,
    ]);

    /** @var PayrollProcessor $processor */
    $processor = app(PayrollProcessor::class);
    $items = $processor->generate($payroll);

    expect($items)->toHaveCount(1);
    expect($items->first()->staff_id)->toBe($included->id);

    $payroll->refresh();
    expect($payroll->total_base_salary)->toEqual(3_000_000.0);
});
