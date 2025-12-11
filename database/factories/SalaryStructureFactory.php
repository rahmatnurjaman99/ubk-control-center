<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\AcademicYear;
use App\Models\SalaryStructure;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<SalaryStructure>
 */
class SalaryStructureFactory extends Factory
{
    public function definition(): array
    {
        $effective = Carbon::parse(fake()->dateTimeBetween('-6 months', 'now'));

        return [
            'staff_id' => Staff::factory(),
            'academic_year_id' => AcademicYear::factory(),
            'title' => sprintf('Salary %s', $effective->format('F Y')),
            'currency' => 'IDR',
            'base_salary' => fake()->numberBetween(2_000_000, 6_000_000),
            'allowances' => [
                [
                    'label' => 'Transport',
                    'amount' => fake()->numberBetween(100_000, 300_000),
                ],
                [
                    'label' => 'Meals',
                    'amount' => fake()->numberBetween(100_000, 200_000),
                ],
            ],
            'deductions' => [
                [
                    'label' => 'Cooperative',
                    'amount' => fake()->numberBetween(50_000, 150_000),
                ],
            ],
            'effective_date' => $effective->toDateString(),
            'expires_on' => null,
            'is_active' => true,
            'notes' => fake()->sentence(),
            'metadata' => [],
        ];
    }
}
