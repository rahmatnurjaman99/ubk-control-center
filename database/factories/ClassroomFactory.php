<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\GradeLevel;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Classroom>
 */
class ClassroomFactory extends Factory
{
    public function definition(): array
    {
        $grade = fake()->randomElement(GradeLevel::cases());

        return [
            'academic_year_id' => AcademicYear::factory(),
            'homeroom_staff_id' => Staff::factory(),
            'code' => fake()->unique()->regexify('CLS-[A-Z0-9]{4}'),
            'name' => $grade->label() . ' ' . fake()->randomLetter(),
            'grade_level' => $grade,
            'school_level' => $grade->schoolLevel(),
            'capacity' => fake()->numberBetween(20, 32),
            'description' => fake()->sentence(),
        ];
    }
}
