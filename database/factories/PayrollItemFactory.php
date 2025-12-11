<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PayrollItemStatus;
use App\Models\Payroll;
use App\Models\PayrollItem;
use App\Models\SalaryStructure;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PayrollItem>
 */
class PayrollItemFactory extends Factory
{
    public function definition(): array
    {
        $base = fake()->numberBetween(2_000_000, 5_000_000);

        return [
            'payroll_id' => Payroll::factory(),
            'staff_id' => Staff::factory(),
            'salary_structure_id' => SalaryStructure::factory(),
            'status' => PayrollItemStatus::Pending,
            'base_salary' => $base,
            'allowances' => [
                [
                    'label' => 'Bonus',
                    'amount' => fake()->numberBetween(100_000, 400_000),
                ],
            ],
            'deductions' => [
                [
                    'label' => 'Insurance',
                    'amount' => fake()->numberBetween(50_000, 150_000),
                ],
            ],
            'currency' => 'IDR',
            'notes' => fake()->sentence(),
            'metadata' => [],
        ];
    }
}
