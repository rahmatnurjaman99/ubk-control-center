<?php

use App\Enums\FeeStatus;
use App\Enums\FeeType;
use App\Enums\GradeLevel;

return [
    'promotion_fees' => [
        'enabled' => env('FINANCE_PROMOTION_FEES_ENABLED', true),
        'type' => FeeType::Tuition->value,
        'status' => FeeStatus::Pending->value,
        'currency' => 'IDR',
        'due_in_days' => 14,
        'grade_amounts' => [
            GradeLevel::Paud->value => 500000,
            GradeLevel::TkA->value => 550000,
            GradeLevel::TkB->value => 600000,
            GradeLevel::Sd1->value => 650000,
            GradeLevel::Sd2->value => 700000,
            GradeLevel::Sd3->value => 750000,
            GradeLevel::Sd4->value => 800000,
            GradeLevel::Sd5->value => 850000,
            GradeLevel::Sd6->value => 900000,
        ],
    ],
];
