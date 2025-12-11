<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\AssignmentRole;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\ClassroomStaff;
use App\Models\Staff;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ClassroomStaff>
 */
class ClassroomStaffFactory extends Factory
{
    public function definition(): array
    {
        return [
            'classroom_id' => Classroom::factory(),
            'staff_id' => Staff::factory(),
            'academic_year_id' => AcademicYear::factory(),
            'subject_id' => Subject::factory(),
            'assignment_role' => fake()->randomElement(AssignmentRole::cases()),
            'assigned_on' => fake()->date(),
            'removed_on' => null,
            'notes' => fake()->sentence(),
        ];
    }
}
