<?php

declare(strict_types=1);

namespace App\Support\Students;

use App\Enums\GradeLevel;
use App\Enums\StudentStatus;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Carbon;
use RuntimeException;

class PromoteStudentAction
{
    public function __construct(
        private readonly DatabaseManager $db,
    ) {
    }

    public function execute(
        Student $student,
        AcademicYear $targetAcademicYear,
        ?Classroom $targetClassroom = null,
        ?GradeLevel $overrideGradeLevel = null,
    ): StudentPromotionResult {
        return $this->db->transaction(
            function () use ($student, $targetAcademicYear, $targetClassroom, $overrideGradeLevel): StudentPromotionResult {
                $currentGrade = $student->determineCurrentGradeLevel();

                if ($currentGrade === null && $overrideGradeLevel === null && $targetClassroom?->grade_level === null) {
                    throw new RuntimeException('Unable to determine the student grade level.');
                }

                $previousAssignment = $student->classroomAssignments()
                    ->orderByDesc('assigned_on')
                    ->orderByDesc('id')
                    ->first();

                if ($previousAssignment !== null && $previousAssignment->removed_on === null) {
                    $previousAssignment->update([
                        'removed_on' => Carbon::now()->toDateString(),
                    ]);
                }

                $gradeToAssign = $overrideGradeLevel
                    ?? $targetClassroom?->grade_level
                    ?? $currentGrade?->next();

                if ($gradeToAssign === null) {
                    $student->update([
                        'academic_year_id' => $targetAcademicYear->id,
                        'classroom_id' => null,
                        'status' => StudentStatus::Graduated,
                    ]);

                    return new StudentPromotionResult(
                        student: $student->refresh(),
                        assignment: null,
                        gradeLevel: null,
                        graduated: true,
                    );
                }

                if ($targetClassroom !== null && $targetClassroom->academic_year_id !== $targetAcademicYear->id) {
                    throw new RuntimeException('Selected classroom is not part of the destination academic year.');
                }

                if ($targetClassroom !== null && $targetClassroom->grade_level !== null && $targetClassroom->grade_level !== $gradeToAssign) {
                    throw new RuntimeException('Selected classroom grade level does not match the target grade.');
                }

                $resolvedClassroom = $targetClassroom
                    ?? Classroom::query()
                        ->where('academic_year_id', $targetAcademicYear->id)
                        ->where('grade_level', $gradeToAssign->value)
                        ->orderBy('name')
                        ->first();

                if ($resolvedClassroom === null) {
                    throw new RuntimeException('No classroom found for the requested grade level.');
                }

                $assignment = $student->classroomAssignments()->create([
                    'classroom_id' => $resolvedClassroom->id,
                    'academic_year_id' => $targetAcademicYear->id,
                    'grade_level' => $gradeToAssign,
                    'assigned_on' => Carbon::now()->toDateString(),
                ]);

                $student->fill([
                    'academic_year_id' => $targetAcademicYear->id,
                    'classroom_id' => $resolvedClassroom->id,
                    'status' => StudentStatus::Active,
                ]);

                $student->save();

                return new StudentPromotionResult(
                    student: $student->refresh(),
                    assignment: $assignment,
                    gradeLevel: $gradeToAssign,
                    graduated: false,
                );
            },
        );
    }
}
