<?php

declare(strict_types=1);

namespace App\Support\Finance;

use App\Enums\FeeStatus;
use App\Enums\FeeType;
use App\Enums\GradeLevel;
use App\Models\AcademicYear;
use App\Models\Fee;
use App\Models\FeeTemplate;
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
            ->orderBy('title')
            ->get();

        $fees = $templates
            ->map(fn (FeeTemplate $template): ?Fee => $this->createFromTemplate(
                student: $student,
                academicYear: $academicYear,
                gradeLevel: $gradeLevel,
                template: $template,
                status: $status,
                defaultDueInDays: $defaultDueInDays,
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

        $fallbackFee = Fee::query()->create([
            'student_id' => $student->id,
            'academic_year_id' => $academicYear->id,
            'title' => __('filament.fees.promotion.title', [
                'grade' => $gradeLevel->label(),
                'year' => $academicYear->name,
            ]),
            'type' => $type,
            'amount' => $amount,
            'currency' => $currency,
            'due_date' => $this->resolveDueDate($academicYear, $defaultDueInDays)->toDateString(),
            'status' => $status,
            'description' => __('filament.fees.promotion.description', [
                'year' => $academicYear->name,
            ]),
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

        return Fee::query()->create([
            'student_id' => $student->id,
            'academic_year_id' => $academicYear->id,
            'title' => $template->title,
            'type' => $template->type ?? FeeType::Tuition,
            'amount' => $template->amount,
            'currency' => $template->currency,
            'due_date' => $dueDate->toDateString(),
            'status' => $status,
            'description' => $template->description,
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
}
