<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\FeeStatus;
use App\Enums\FeeType;
use App\Enums\GradeLevel;
use App\Enums\PaymentStatus;
use App\Enums\PromotionApprovalStatus;
use App\Enums\TahfidzLogStatus;
use App\Enums\TahfidzTargetStatus;
use App\Enums\TransactionType;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\ClassroomAssignment;
use App\Models\Fee;
use App\Models\PromotionApproval;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\TahfidzLog;
use App\Models\TahfidzTarget;
use App\Models\Transaction;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SampleDataSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call(DefaultDataSeeder::class);

        $currentYear = AcademicYear::query()->where('is_current', true)->first();
        $nextYear = AcademicYear::query()
            ->where('starts_on', '>', $currentYear?->starts_on)
            ->orderBy('starts_on')
            ->first();

        if ($currentYear === null || $nextYear === null) {
            return;
        }

        $currentClassrooms = Classroom::query()
            ->where('academic_year_id', $currentYear->id)
            ->get();

        $nextYearClassrooms = $this->seedNextYearClassrooms($currentClassrooms, $nextYear);
        $approvals = $this->seedPromotionApprovals($currentYear, $nextYear, $currentClassrooms, $nextYearClassrooms);
        $this->seedFutureFees($nextYear, $approvals);
        $this->seedFutureSchedules($nextYearClassrooms, $nextYear);
        $this->seedTahfidzProgram($currentYear, $currentClassrooms);
    }

    /**
     * @param Collection<int, Classroom> $currentClassrooms
     * @return Collection<int, Classroom>
     */
    private function seedNextYearClassrooms(Collection $currentClassrooms, AcademicYear $nextYear): Collection
    {
        return $currentClassrooms
            ->mapWithKeys(function (Classroom $classroom): array {
                $nextGrade = $classroom->grade_level?->next();

                if ($nextGrade === null) {
                    return [];
                }

                return [$classroom->id => $nextGrade];
            })
            ->map(function (GradeLevel $grade) use ($nextYear, $currentClassrooms): Classroom {
                $code = sprintf('CLS-%s-%s', Str::upper(str_replace('_', '', $grade->value)), $nextYear->starts_on?->format('y'));

                return Classroom::updateOrCreate(
                    ['code' => $code],
                    [
                        'academic_year_id' => $nextYear->id,
                        'homeroom_staff_id' => $currentClassrooms->firstWhere('grade_level', $grade)?->homeroom_staff_id,
                        'name' => $grade->label() . ' ' . $nextYear->starts_on?->format('Y'),
                        'grade_level' => $grade,
                        'school_level' => $grade->schoolLevel(),
                        'capacity' => $currentClassrooms->firstWhere('grade_level', $grade)?->capacity,
                        'description' => 'Auto-generated projection for ' . $nextYear->name,
                    ],
                );
            });
    }

    /**
     * @param Collection<int, Classroom> $currentClassrooms
     * @param Collection<int, Classroom> $nextYearClassrooms
     * @return Collection<int, PromotionApproval>
     */
    private function seedPromotionApprovals(
        AcademicYear $currentYear,
        AcademicYear $nextYear,
        Collection $currentClassrooms,
        Collection $nextYearClassrooms
    ): Collection {
        $principalId = User::query()->where('email', 'alya.pratama@ubk.local')->value('id');

        $students = Student::query()
            ->with(['guardian', 'fees', 'classroom'])
            ->whereIn('classroom_id', $currentClassrooms->pluck('id'))
            ->get();

        return $students
            ->map(function (Student $student, int $index) use ($currentYear, $nextYear, $currentClassrooms, $nextYearClassrooms, $principalId): ?PromotionApproval {
                $currentClassroom = $currentClassrooms->firstWhere('id', $student->classroom_id);
                $nextClassroom = $nextYearClassrooms->get($currentClassroom?->id ?? 0);
                $nextGrade = $currentClassroom?->grade_level?->next();

                if ($currentClassroom === null || $nextClassroom === null || $nextGrade === null) {
                    return null;
                }

                $status = $index % 5 === 0 ? PromotionApprovalStatus::Pending : PromotionApprovalStatus::Approved;
                $outstanding = $student->fees->sum(function (Fee $fee): float {
                    return max((float) $fee->amount - (float) ($fee->paid_amount ?? 0), 0.0);
                });

                $approval = PromotionApproval::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'current_academic_year_id' => $currentYear->id,
                        'target_academic_year_id' => $nextYear->id,
                    ],
                    [
                        'target_classroom_id' => $nextClassroom->id,
                        'target_grade_level' => $nextGrade,
                        'outstanding_amount' => $outstanding,
                        'status' => $status,
                        'requested_by' => $student->guardian?->user_id,
                        'notes' => 'Generated by SampleDataSeeder for flow review.',
                        'decision_notes' => $status === PromotionApprovalStatus::Approved ? 'Auto approved for simulation.' : null,
                        'approved_by' => $status === PromotionApprovalStatus::Approved ? $principalId : null,
                        'approved_at' => $status === PromotionApprovalStatus::Approved ? now() : null,
                    ],
                );

                if ($status === PromotionApprovalStatus::Approved) {
                    ClassroomAssignment::updateOrCreate(
                        [
                            'student_id' => $student->id,
                            'classroom_id' => $nextClassroom->id,
                            'academic_year_id' => $nextYear->id,
                        ],
                        [
                            'grade_level' => $nextGrade,
                            'assigned_on' => CarbonImmutable::parse($nextYear->starts_on)->toDateString(),
                            'notes' => 'Reserved seat for upcoming academic year.',
                        ],
                    );
                }

                return $approval;
            })
            ->filter()
            ->values();
    }

    /**
     * @param Collection<int, PromotionApproval> $approvals
     */
    private function seedFutureFees(AcademicYear $nextYear, Collection $approvals): void
    {
        $financeUserId = User::query()->where('email', 'finance@ubk.local')->value('id') ?? User::query()->first()?->id;

        $approvals
            ->where('status', PromotionApprovalStatus::Approved)
            ->take(12)
            ->each(function (PromotionApproval $approval, int $index) use ($nextYear, $financeUserId): void {
                $student = $approval->student;
                if ($student === null) {
                    return;
                }

                $amount = $this->resolveTuitionAmount($approval->target_grade_level ?? GradeLevel::Sd1) * 0.5;
                $dueDate = CarbonImmutable::parse($nextYear->starts_on)->subMonths(1);

                $transaction = Transaction::updateOrCreate(
                    ['reference' => sprintf('TRX-RES-%s', $student->student_number)],
                    [
                        'label' => 'Reservation Fee ' . $nextYear->name,
                        'type' => TransactionType::Income,
                        'category' => 'reservation',
                        'payment_status' => $index % 3 === 0 ? PaymentStatus::Pending : PaymentStatus::Paid,
                        'payment_method' => 'transfer',
                        'amount' => $amount,
                        'currency' => 'IDR',
                        'due_date' => $dueDate->toDateString(),
                        'paid_at' => $index % 3 === 0 ? null : $dueDate->addDays(3),
                        'academic_year_id' => $nextYear->id,
                        'counterparty_name' => $student->full_name,
                        'recorded_by' => $financeUserId,
                        'notes' => 'Advance tuition reservation',
                        'metadata' => [
                            'seeded_by' => 'sample_data',
                            'tag' => 'next_year_reservation',
                        ],
                    ],
                );

                Fee::updateOrCreate(
                    ['reference' => sprintf('FEE-RES-%s', $student->student_number)],
                    [
                        'student_id' => $student->id,
                        'academic_year_id' => $nextYear->id,
                        'transaction_id' => $transaction->id,
                        'title' => 'Reservation Fee ' . $nextYear->name,
                        'type' => FeeType::Registration,
                        'amount' => $amount,
                        'paid_amount' => $index % 3 === 0 ? 0 : $amount,
                        'currency' => 'IDR',
                        'due_date' => $dueDate->toDateString(),
                        'status' => $index % 3 === 0 ? FeeStatus::Pending : FeeStatus::Paid,
                        'paid_at' => $index % 3 === 0 ? null : $dueDate->addDays(3),
                        'description' => 'Advance payment for seat confirmation.',
                        'metadata' => [
                            'tag' => 'next_year_reservation',
                            'seeded_by' => 'sample_data',
                        ],
                    ],
                );
            });
    }

    /**
     * @param Collection<int, Classroom> $nextYearClassrooms
     */
    private function seedFutureSchedules(Collection $nextYearClassrooms, AcademicYear $nextYear): void
    {
        $orientation = CarbonImmutable::parse($nextYear->starts_on)->addWeek();

        $nextYearClassrooms->each(function (Classroom $classroom) use ($orientation, $nextYear): void {
            Schedule::updateOrCreate(
                [
                    'title' => 'Orientation Week - ' . $classroom->name,
                    'classroom_id' => $classroom->id,
                    'academic_year_id' => $nextYear->id,
                ],
                [
                    'staff_id' => $classroom->homeroom_staff_id,
                    'subject_id' => null,
                    'starts_at' => $orientation->setTime(8, 0),
                    'ends_at' => $orientation->setTime(10, 0),
                    'is_all_day' => false,
                    'location' => 'Multipurpose Hall',
                    'description' => 'Orientation event for families and students.',
                    'color' => '#a855f7',
                    'metadata' => [
                        'seeded_by' => 'sample_data',
                        'tag' => 'orientation',
                    ],
                ],
            );
        });
    }

    private function seedTahfidzProgram(AcademicYear $currentYear, Collection $currentClassrooms): void
    {
        $students = Student::query()
            ->with(['classroom.homeroomStaff.user'])
            ->whereIn('classroom_id', $currentClassrooms->pluck('id'))
            ->limit(24)
            ->get();

        $assignedOn = CarbonImmutable::parse($currentYear->starts_on)->addWeeks(2);

        $students->each(function (Student $student, int $index) use ($assignedOn): void {
            [$surahId, $startAyah, $endAyah] = $this->resolveTahfidzAssignment($student->classroom?->grade_level);

            $target = TahfidzTarget::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'surah_id' => $surahId,
                    'start_ayah_number' => $startAyah,
                    'end_ayah_number' => $endAyah,
                ],
                [
                    'classroom_id' => $student->classroom_id,
                    'assigned_by_id' => $student->classroom?->homeroomStaff?->user_id,
                    'target_repetitions' => 3,
                    'assigned_on' => $assignedOn->toDateString(),
                    'due_on' => $assignedOn->addWeeks(4)->toDateString(),
                    'status' => $index % 4 === 0 ? TahfidzTargetStatus::InProgress : TahfidzTargetStatus::Assigned,
                    'tag' => 'hafidz_goal',
                    'notes' => 'Auto-generated tahfidz milestone for flow review.',
                    'metadata' => [
                        'seeded_by' => 'sample_data',
                    ],
                ],
            );

            $target->segments()
                ->where('sequence', '>', 1)
                ->delete();

            $target->segments()->updateOrCreate(
                ['sequence' => 1],
                [
                    'surah_id' => $surahId,
                    'start_ayah_number' => $startAyah,
                    'end_ayah_number' => $endAyah,
                ],
            );

            if ($index % 3 === 0) {
                return;
            }

            $logStatus = $index % 2 === 0 ? TahfidzLogStatus::NeedsRevision : TahfidzLogStatus::Passed;

            TahfidzLog::updateOrCreate(
                [
                    'tahfidz_target_id' => $target->id,
                    'recorded_on' => $assignedOn->addWeeks(1)->toDateString(),
                ],
                [
                    'student_id' => $student->id,
                    'recorded_by_id' => $student->classroom?->homeroomStaff?->user_id,
                    'evaluated_by_staff_id' => $student->classroom?->homeroom_staff_id,
                    'surah_id' => $surahId,
                    'start_ayah_number' => $startAyah,
                    'end_ayah_number' => min($endAyah, $startAyah + 5),
                    'status' => $logStatus,
                    'memorization_score' => $logStatus === TahfidzLogStatus::Passed ? 90 : 70,
                    'tajwid_score' => $logStatus === TahfidzLogStatus::Passed ? 88 : 72,
                    'fluency_score' => $logStatus === TahfidzLogStatus::Passed ? 85 : 68,
                    'is_revision' => $logStatus === TahfidzLogStatus::NeedsRevision,
                    'notes' => $logStatus === TahfidzLogStatus::Passed
                        ? 'Student presented the assigned verses confidently.'
                        : 'Needs more repetition on makhraj and fluency.',
                    'metadata' => [
                        'seeded_by' => 'sample_data',
                    ],
                ],
            );
        });
    }

    /**
     * @return array{0:int,1:int,2:int}
     */
    private function resolveTahfidzAssignment(?GradeLevel $grade): array
    {
        return match ($grade) {
            GradeLevel::Paud, GradeLevel::TkA => [114, 1, 6], // Al-Nas
            GradeLevel::TkB => [113, 1, 5], // Al-Falaq
            GradeLevel::Sd1 => [112, 1, 11], // Al-Ikhlas
            GradeLevel::Sd2 => [111, 1, 11], // Al-Masad
            GradeLevel::Sd3 => [110, 1, 10], // Al-Nasr
            GradeLevel::Sd4 => [109, 1, 6], // Al-Kafirun
            GradeLevel::Sd5 => [108, 1, 3], // Al-Kawthar
            GradeLevel::Sd6 => [67, 1, 20], // Al-Mulk opening
            default => [1, 1, 7], // Al-Fatihah
        };
    }

    private function resolveTuitionAmount(GradeLevel $grade): float
    {
        return match ($grade) {
            GradeLevel::Paud => 350_000,
            GradeLevel::TkA => 420_000,
            GradeLevel::TkB => 450_000,
            GradeLevel::Sd1 => 900_000,
            GradeLevel::Sd2 => 950_000,
            GradeLevel::Sd3 => 1_000_000,
            GradeLevel::Sd4 => 1_050_000,
            GradeLevel::Sd5 => 1_150_000,
            GradeLevel::Sd6 => 1_200_000,
        };
    }
}
