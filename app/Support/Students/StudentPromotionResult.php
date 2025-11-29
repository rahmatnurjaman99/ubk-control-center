<?php

declare(strict_types=1);

namespace App\Support\Students;

use App\Enums\GradeLevel;
use App\Models\ClassroomAssignment;
use App\Models\Student;

final class StudentPromotionResult
{
    public function __construct(
        public readonly Student $student,
        public readonly ?ClassroomAssignment $assignment,
        public readonly ?GradeLevel $gradeLevel,
        public readonly bool $graduated,
    ) {
    }
}
