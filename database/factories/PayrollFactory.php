<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PayrollStatus;
use App\Models\AcademicYear;
use App\Models\Payroll;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Payroll>
 */
class PayrollFactory extends Factory
{
    public function definition(): array
    {
        $periodStart = Carbon::parse(fake()->dateTimeBetween('-3 months', 'now'))->startOfMonth();
        $periodEnd = $periodStart->copy()->endOfMonth();

        return [
            'title' => sprintf('Payroll %s', $periodStart->format('F Y')),
            'status' => PayrollStatus::Draft,
            'academic_year_id' => AcademicYear::factory(),
            'period_start' => $periodStart->toDateString(),
            'period_end' => $periodEnd->toDateString(),
            'currency' => 'IDR',
            'staff_ids' => [],
            'notes' => fake()->sentence(),
            'metadata' => [],
        ];
    }
}
