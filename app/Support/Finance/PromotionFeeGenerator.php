<?php

declare(strict_types=1);

namespace App\Support\Finance;

use App\Enums\FeeStatus;
use App\Enums\FeeType;
use App\Enums\GradeLevel;
use App\Models\AcademicYear;
use App\Models\Fee;
use App\Models\FeeTemplate;
use App\Models\Scholarship;
use App\Models\Student;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Support\Carbon;

class PromotionFeeGenerator
{
    public function __construct(
        private readonly ConfigRepository $config,
    ) {
    }

    /**
     * @return list<Fee>
     */
    public function createForPromotion(
        Student $student,
        GradeLevel $gradeLevel,
        AcademicYear $academicYear,
    ): array {
        $settings = $this->config->get('finance.promotion_fees', []);

        if (! (bool) data_get($settings, 'enabled', false)) {
            return [];
        }

        $type = FeeType::tryFrom((string) data_get($settings, 'type')) ?? FeeType::Tuition;
        $status = FeeStatus::tryFrom((string) data_get($settings, 'status')) ?? FeeStatus::Pending;
        $currency = (string) data_get($settings, 'currency', 'IDR');
        $defaultDueInDays = (int) data_get($settings, 'due_in_days', 14);

        $templates = FeeTemplate::query()
            ->active()
            ->where('grade_level', $gradeLevel->value)
            ->where('academic_year_id', $academicYear->id)
            ->orderBy('title')
            ->get();

        if ($templates->isEmpty()) {
            $templates = FeeTemplate::query()
                ->active()
                ->where('grade_level', $gradeLevel->value)
                ->whereNull('academic_year_id')
                ->orderBy('title')
                ->get();
        }

        $scholarship = $this->resolveScholarship($student, $academicYear);

        $fees = $templates
            ->map(fn (FeeTemplate $template): ?Fee => $this->createFromTemplate(
                student: $student,
                academicYear: $academicYear,
                gradeLevel: $gradeLevel,
                template: $template,
                status: $status,
                defaultDueInDays: $defaultDueInDays,
                scholarship: $scholarship,
            ))
            ->filter()
            ->values()
            ->all();

        if ($fees !== []) {
            return $fees;
        }

        $amounts = (array) data_get($settings, 'grade_amounts', []);
        $amount = $amounts[$gradeLevel->value] ?? null;

        if ($amount === null) {
            return [];
        }

        $existingFee = $this->findExistingPromotionFee($student, $academicYear, $gradeLevel, $type);

        if ($existingFee instanceof Fee) {
            return [$existingFee];
        }

        [$finalAmount, $discountAmount, $discountPercent] = $this->calculateScholarshipAdjustments(
            amount: (float) $amount,
            scholarship: $scholarship,
        );

        $fallbackFee = Fee::query()->create([
            'student_id' => $student->id,
            'academic_year_id' => $academicYear->id,
            'title' => __('filament.fees.promotion.title', [
                'grade' => $gradeLevel->label(),
                'year' => $academicYear->name,
            ]),
            'type' => $type,
            'amount' => $finalAmount,
            'currency' => $currency,
            'due_date' => $this->resolveDueDate($academicYear, $defaultDueInDays)->toDateString(),
            'status' => $status,
            'description' => __('filament.fees.promotion.description', [
                'year' => $academicYear->name,
            ]),
            'allow_partial_payment' => $settings['allow_partial_payment'] ?? false,
            'requires_partial_approval' => $settings['require_partial_approval'] ?? false,
            'scholarship_id' => $scholarship?->id,
            'scholarship_discount_amount' => $discountAmount,
            'scholarship_discount_percent' => $discountPercent,
            'metadata' => [
                'source' => 'promotion',
                'grade_level' => $gradeLevel->value,
            ],
        ]);

        return [$fallbackFee];
    }

    private function createFromTemplate(
        Student $student,
        AcademicYear $academicYear,
        GradeLevel $gradeLevel,
        FeeTemplate $template,
        FeeStatus $status,
        int $defaultDueInDays,
        ?Scholarship $scholarship,
    ): ?Fee {
        $existing = Fee::query()
            ->where('student_id', $student->id)
            ->where('academic_year_id', $academicYear->id)
            ->where('metadata->template_id', $template->id)
            ->first();

        if ($existing instanceof Fee) {
            return $existing;
        }

        $dueDate = $this->resolveDueDate($academicYear, $template->due_in_days ?? $defaultDueInDays);
        $allowPartial = (bool) $template->allow_partial_payment;
        $requireApproval = $allowPartial && (bool) $template->require_partial_approval;

        [$finalAmount, $discountAmount, $discountPercent] = $this->calculateScholarshipAdjustments(
            amount: (float) $template->amount,
            scholarship: $scholarship,
        );

        return Fee::query()->create([
            'student_id' => $student->id,
            'academic_year_id' => $academicYear->id,
            'title' => $template->title,
            'type' => $template->type ?? FeeType::Tuition,
            'amount' => $finalAmount,
            'currency' => $template->currency,
            'due_date' => $dueDate->toDateString(),
            'status' => $status,
            'description' => $template->description,
            'allow_partial_payment' => $allowPartial,
            'requires_partial_approval' => $requireApproval,
            'scholarship_id' => $scholarship?->id,
            'scholarship_discount_amount' => $discountAmount,
            'scholarship_discount_percent' => $discountPercent,
            'metadata' => [
                'source' => 'promotion',
                'grade_level' => $gradeLevel->value,
                'template_id' => $template->id,
            ],
        ]);
    }

    private function resolveDueDate(AcademicYear $academicYear, int $dueInDays): Carbon
    {
        $base = $academicYear->starts_on?->copy() ?? Carbon::now();

        return $base->addDays($dueInDays);
    }

    private function findExistingPromotionFee(
        Student $student,
        AcademicYear $academicYear,
        GradeLevel $gradeLevel,
        FeeType $type,
    ): ?Fee {
        return Fee::query()
            ->where('student_id', $student->id)
            ->where('academic_year_id', $academicYear->id)
            ->where('type', $type->value)
            ->where('metadata->source', 'promotion')
            ->where('metadata->grade_level', $gradeLevel->value)
            ->whereNull('metadata->template_id')
            ->first();
    }

    private function resolveScholarship(Student $student, AcademicYear $academicYear): ?Scholarship
    {
        return $student->scholarships()
            ->wherePivot('is_active', true)
            ->where(function ($query) use ($academicYear): void {
                if ($academicYear->starts_on !== null) {
                    $query->whereNull('effective_from')
                        ->orWhere('effective_from', '<=', $academicYear->starts_on);
                }
            })
            ->where(function ($query) use ($academicYear): void {
                if ($academicYear->ends_on !== null) {
                    $query->whereNull('effective_until')
                        ->orWhere('effective_until', '>=', $academicYear->ends_on);
                }
            })
            ->orderByDesc('scholarship_student.effective_from')
            ->first();
    }

    /**
     * @return array{0: float, 1: float, 2: ?int}
     */
    private function calculateScholarshipAdjustments(float $amount, ?Scholarship $scholarship): array
    {
        if ($scholarship === null || $amount <= 0) {
            return [$amount, 0.0, null];
        }

        $discount = 0.0;
        $percent = null;
        $value = (float) $scholarship->amount;

        if ($scholarship->type === 'percentage') {
            $percent = (int) round(min(100, max(0, $value)));
            $discount = ($amount * $percent) / 100;
        } else {
            $discount = min($amount, $value);
            $percent = $amount > 0 ? (int) round(($discount / $amount) * 100) : null;
        }

        $adjusted = max($amount - $discount, 0);

        return [$adjusted, $discount, $percent];
    }
}
