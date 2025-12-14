<?php

namespace Database\Factories;

use App\Enums\FeeType;
use App\Enums\GradeLevel;
use App\Models\AcademicYear;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\FeeTemplate>
 */
class FeeTemplateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $grade = fake()->randomElement(GradeLevel::cases());

        return [
            'title' => $grade->label() . ' ' . fake()->word(),
            'academic_year_id' => AcademicYear::factory(),
            'grade_level' => $grade,
            'type' => fake()->randomElement(FeeType::cases()),
            'amount' => fake()->numberBetween(500_000, 2_000_000),
            'currency' => 'IDR',
            'due_in_days' => fake()->numberBetween(7, 30),
            'is_active' => true,
            'description' => fake()->sentence(),
        ];
    }
}
