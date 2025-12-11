<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\AttendanceStatus;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<StudentAttendance>
 */
class StudentAttendanceFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(AttendanceStatus::cases());
        $recordedOn = Carbon::parse(fake()->dateTimeBetween('-1 month', 'now'));

        return [
            'student_id' => Student::factory(),
            'academic_year_id' => AcademicYear::factory(),
            'classroom_id' => Classroom::factory(),
            'recorded_on' => $recordedOn->toDateString(),
            'status' => $status,
            'checked_in_at' => $status === AttendanceStatus::Present ? $recordedOn->copy()->setTime(7, 30) : null,
            'checked_out_at' => $status === AttendanceStatus::Present ? $recordedOn->copy()->setTime(15, 0) : null,
            'recorded_by' => User::factory(),
            'notes' => $status === AttendanceStatus::Present ? null : fake()->sentence(),
        ];
    }
}
