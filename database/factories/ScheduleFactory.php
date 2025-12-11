<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\Staff;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Schedule>
 */
class ScheduleFactory extends Factory
{
    public function definition(): array
    {
        $start = Carbon::parse(fake()->dateTimeBetween('now', '+1 month'));
        $end = (clone $start)->addHours(2);

        return [
            'title' => ucfirst(fake()->words(3, true)),
            'subject_id' => Subject::factory(),
            'classroom_id' => Classroom::factory(),
            'staff_id' => Staff::factory(),
            'academic_year_id' => AcademicYear::factory(),
            'starts_at' => $start,
            'ends_at' => $end,
            'is_all_day' => false,
            'location' => fake()->randomElement(['Room A', 'Lab', 'Library']),
            'description' => fake()->sentence(),
            'color' => fake()->hexColor(),
            'metadata' => [
                'notes' => fake()->sentence(),
            ],
        ];
    }
}
