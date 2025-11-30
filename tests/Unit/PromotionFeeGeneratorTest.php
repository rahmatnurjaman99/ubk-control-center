<?php

use App\Enums\GradeLevel;
use App\Models\AcademicYear;
use App\Models\Fee;
use App\Models\FeeTemplate;
use App\Models\Student;
use App\Support\Finance\PromotionFeeGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    config()->set('finance.promotion_fees.enabled', true);
});

it('creates promotion fees based on active templates', function (): void {
    $academicYear = AcademicYear::query()->create([
        'code' => '2425',
        'name' => '2024/2025',
        'starts_on' => now()->toDateString(),
        'ends_on' => now()->addYear()->toDateString(),
    ]);

    $student = Student::query()->create([
        'student_number' => 'STD-' . uniqid(),
        'full_name' => 'Template Student',
        'academic_year_id' => $academicYear->id,
    ]);

    $template = FeeTemplate::factory()->create([
        'grade_level' => GradeLevel::Sd1,
        'amount' => 1_250_000,
        'due_in_days' => 10,
        'title' => 'SD 1 Tuition',
    ]);

    $generator = app(PromotionFeeGenerator::class);

    $fees = $generator->createForPromotion($student, GradeLevel::Sd1, $academicYear);

    expect($fees)
        ->toHaveCount(1)
        ->and($fees[0])->toBeInstanceOf(Fee::class)
        ->and($fees[0]->amount)->toEqual(1_250_000)
        ->and($fees[0]->metadata['template_id'])->toEqual($template->id);
});

it('falls back to config amount when no template exists', function (): void {
    config()->set('finance.promotion_fees.grade_amounts', [
        GradeLevel::Sd2->value => 1_500_000,
    ]);

    $academicYear = AcademicYear::query()->create([
        'code' => '2526',
        'name' => '2025/2026',
        'starts_on' => now()->toDateString(),
        'ends_on' => now()->addYear()->toDateString(),
    ]);

    $student = Student::query()->create([
        'student_number' => 'STD-' . uniqid(),
        'full_name' => 'Fallback Student',
        'academic_year_id' => $academicYear->id,
    ]);

    $generator = app(PromotionFeeGenerator::class);

    $fees = $generator->createForPromotion($student, GradeLevel::Sd2, $academicYear);

    expect($fees)
        ->toHaveCount(1)
        ->and($fees[0]->amount)->toEqual(1_500_000)
        ->and($fees[0]->metadata['grade_level'])->toEqual(GradeLevel::Sd2->value);
});

it('reuses existing promotion fees instead of duplicating', function (): void {
    config()->set('finance.promotion_fees.grade_amounts', [
        GradeLevel::Sd3->value => 1_750_000,
    ]);

    $academicYear = AcademicYear::query()->create([
        'code' => '2627',
        'name' => '2026/2027',
        'starts_on' => now()->toDateString(),
        'ends_on' => now()->addYear()->toDateString(),
    ]);

    $student = Student::query()->create([
        'student_number' => 'STD-' . uniqid(),
        'full_name' => 'Repeat Student',
        'academic_year_id' => $academicYear->id,
    ]);

    $generator = app(PromotionFeeGenerator::class);

    $firstRun = $generator->createForPromotion($student, GradeLevel::Sd3, $academicYear);
    $secondRun = $generator->createForPromotion($student, GradeLevel::Sd3, $academicYear);

    expect($firstRun[0]->id)->toBe($secondRun[0]->id);
});
