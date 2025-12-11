<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\GradeLevel;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\ClassroomAssignment;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<ClassroomAssignment>
 */
class ClassroomAssignmentFactory extends Factory
{
    public function definition(): array
    {
        $assigned = Carbon::parse(fake()->dateTimeBetween('-1 years', 'now'));

        return [
            'student_id' => Student::factory(),
            'classroom_id' => Classroom::factory(),
            'academic_year_id' => AcademicYear::factory(),
            'grade_level' => fake()->randomElement(GradeLevel::cases()),
            'assigned_on' => $assigned->toDateString(),
            'removed_on' => null,
            'notes' => fake()->sentence(),
        ];
    }
}
