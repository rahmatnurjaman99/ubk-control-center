<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\AttendanceStatus;
use App\Models\Staff;
use App\Models\StaffAttendance;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<StaffAttendance>
 */
class StaffAttendanceFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(AttendanceStatus::cases());
        $recordedOn = Carbon::parse(fake()->dateTimeBetween('-1 month', 'now'));
        $staffId = Staff::factory();
        $checkIn = $recordedOn->copy()->setTime(7, fake()->numberBetween(0, 59));
        $checkOut = $checkIn->copy()->addHours(8);

        return [
            'staff_id' => Staff::factory(),
            'recorded_on' => $recordedOn->toDateString(),
            'status' => $status,
            'checked_in_at' => $status === AttendanceStatus::Present ? $checkIn : null,
            'checked_out_at' => $status === AttendanceStatus::Present ? $checkOut : null,
            'location' => fake()->address(),
            'recorded_by' => User::factory(),
            'notes' => $status === AttendanceStatus::Present ? null : fake()->sentence(),
        ];
    }
}
