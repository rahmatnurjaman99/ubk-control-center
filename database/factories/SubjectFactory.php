<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\SchoolLevel;
use App\Models\AcademicYear;
use App\Models\Subject;
use App\Models\SubjectCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subject>
 */
class SubjectFactory extends Factory
{
    public function definition(): array
    {
        $level = fake()->randomElement(SchoolLevel::cases());

        return [
            'academic_year_id' => AcademicYear::factory(),
            'subject_category_id' => SubjectCategory::factory(),
            'school_level' => $level,
            'code' => fake()->unique()->regexify('SUB-[A-Z0-9]{4}'),
            'name' => ucfirst(fake()->unique()->words(2, true)),
            'is_compulsory' => fake()->boolean(70),
            'credit_hours' => fake()->numberBetween(1, 4),
            'description' => fake()->sentence(),
        ];
    }
}
