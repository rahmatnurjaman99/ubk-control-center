<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\AttendanceStatus;
use App\Enums\StudentStatus;
use App\Models\Schedule;
use App\Models\StaffAttendance;
use App\Models\StudentAttendance;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class GenerateScheduleAttendance extends Command
{
    protected $signature = 'attendance:generate-from-schedules {date? : Date in Y-m-d format}';

    protected $description = 'Generate staff and student attendance entries from schedules';

    public function handle(): int
    {
        $targetDate = $this->argument('date')
            ? Carbon::parse($this->argument('date'))
            : Carbon::today();

        $dateString = $targetDate->toDateString();

        $this->info("Generating attendance entries for {$dateString}");

        $schedules = Schedule::query()
            ->with(['classroom.students' => fn ($query) => $query->where('status', StudentStatus::Active)])
            ->whereDate('starts_at', $dateString)
            ->get();

        $studentCount = 0;
        $staffCount = 0;

        foreach ($schedules as $schedule) {
            if ($schedule->classroom_id !== null && $schedule->classroom !== null) {
                foreach ($schedule->classroom->students as $student) {
                    StudentAttendance::query()->updateOrCreate(
                        [
                            'student_id' => $student->id,
                            'recorded_on' => $dateString,
                        ],
                        [
                            'academic_year_id' => $schedule->academic_year_id,
                            'classroom_id' => $schedule->classroom_id,
                            'status' => AttendanceStatus::Present,
                            'checked_in_at' => $schedule->starts_at,
                            'checked_out_at' => $schedule->ends_at,
                            'notes' => $schedule->description,
                        ],
                    );

                    $studentCount++;
                }
            }

            if ($schedule->staff_id !== null) {
                $attendance = StaffAttendance::query()->firstOrNew([
                    'staff_id' => $schedule->staff_id,
                    'recorded_on' => $dateString,
                ]);

                $attendance->status = AttendanceStatus::Present;
                $attendance->checked_in_at = $attendance->checked_in_at
                    ? min($attendance->checked_in_at, $schedule->starts_at)
                    : $schedule->starts_at;
                $attendance->checked_out_at = $attendance->checked_out_at
                    ? max($attendance->checked_out_at, $schedule->ends_at)
                    : $schedule->ends_at;
                $attendance->notes = $schedule->description;
                $attendance->save();

                $staffCount++;
            }
        }

        $this->info("Student attendance entries synced: {$studentCount}");
        $this->info("Staff attendance entries synced: {$staffCount}");

        return Command::SUCCESS;
    }
}
