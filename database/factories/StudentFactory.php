<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\StudentStatus;
use App\Models\AcademicYear;
use App\Models\Guardian;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Student>
 */
class StudentFactory extends Factory
{
    public function definition(): array
    {
        $enrolled = fake()->dateTimeBetween('-2 years', 'now');
        $dob = fake()->dateTimeBetween('-12 years', '-5 years');

        return [
            'guardian_id' => Guardian::factory(),
            'academic_year_id' => AcademicYear::factory(),
            'classroom_id' => null,
            'student_number' => fake()->unique()->regexify('STD-[A-Z0-9]{6}'),
            'full_name' => fake()->name(),
            'date_of_birth' => $dob->format('Y-m-d'),
            'gender' => fake()->randomElement(['male', 'female']),
            'status' => fake()->randomElement(StudentStatus::cases()),
            'enrolled_on' => $enrolled->format('Y-m-d'),
            'legacy_reference' => fake()->unique()->regexify('LEG-STD-[0-9]{5}'),
        ];
    }
}
