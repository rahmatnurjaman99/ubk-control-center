<?php

declare(strict_types=1);

namespace App\Support\Students;

use App\Enums\GradeLevel;
use App\Models\ClassroomAssignment;
use App\Models\Fee;
use App\Models\Student;

final class StudentPromotionResult
{
    /**
     * @param list<Fee> $promotionFees
     */
    public function __construct(
        public readonly Student $student,
        public readonly ?ClassroomAssignment $assignment,
        public readonly ?GradeLevel $gradeLevel,
        public readonly bool $graduated,
        public readonly array $promotionFees,
    ) {
    }
}
