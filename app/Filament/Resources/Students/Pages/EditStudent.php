<?php

declare(strict_types=1);

namespace App\Filament\Resources\Students\Pages;

use App\Enums\FeeStatus;
use App\Enums\GradeLevel;
use App\Filament\Resources\AcademicYears\AcademicYearResource;
use App\Filament\Resources\Fees\FeeResource;
use App\Filament\Resources\Students\StudentResource;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Fee;
use App\Support\Students\PromoteStudentAction;
use App\Support\Students\StudentPromotionResult;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class EditStudent extends EditRecord
{
    protected static string $resource = StudentResource::class;

    private bool $referenceAcademicYearResolved = false;

    private ?AcademicYear $cachedReferenceAcademicYear = null;

    private bool $nextAcademicYearResolved = false;

    private ?AcademicYear $cachedNextAcademicYear = null;

    protected function getHeaderActions(): array
    {
        return [
            $this->getPromotionAction(),
            $this->getRepeatAction(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    private function getPromotionAction(): Action
    {
        $hasNextAcademicYear = $this->hasNextAcademicYear();

        return Action::make('promoteStudent')
            ->label(__('filament.students.actions.promote'))
            ->icon(Heroicon::OutlinedArrowTrendingUp)
            ->color(Color::Green)
            ->requiresConfirmation()
            ->schema($hasNextAcademicYear ? $this->getPromotionFormSchema() : [])
            ->modalHeading($hasNextAcademicYear ? null : __('filament.students.actions.next_academic_year_required_title'))
            ->modalDescription($hasNextAcademicYear ? null : __('filament.students.actions.next_academic_year_required_description'))
            ->modalSubmitActionLabel($hasNextAcademicYear
                ? __('filament.students.actions.promote_submit')
                : __('filament.students.actions.open_academic_years'))
            ->action(function (array $data, PromoteStudentAction $promoter) use ($hasNextAcademicYear): mixed {
                if (! $hasNextAcademicYear) {
                    return redirect()->to(AcademicYearResource::getUrl());
                }

                if ($this->hasOutstandingFees() && empty($data['override_outstanding_fees'])) {
                    throw ValidationException::withMessages([
                        'override_outstanding_fees' => __('filament.students.actions.outstanding_fees_confirmation_required'),
                    ]);
                }

                $student = $this->record;

                $academicYear = AcademicYear::query()->findOrFail($data['academic_year_id']);
                $classroom = null;
                $shouldRepeat = (bool) ($data['repeat_current_grade'] ?? false);
                $gradeLevel = $shouldRepeat
                    ? $student->determineCurrentGradeLevel()
                    : (isset($data['grade_level']) && $data['grade_level'] !== null
                        ? GradeLevel::from($data['grade_level'])
                        : $student->determineCurrentGradeLevel()?->next());

                $result = $promoter->execute(
                    student: $student,
                    targetAcademicYear: $academicYear,
                    targetClassroom: null,
                    overrideGradeLevel: $gradeLevel,
                );

                $message = $result->graduated
                    ? __('filament.students.actions.success_graduated')
                    : __('filament.students.actions.success_promoted', [
                        'grade' => $result->gradeLevel?->label() ?? '',
                    ]);

                $body = $this->resolvePromotionFeeBody($result);

                $notification = Notification::make()
                    ->title($message)
                    ->success();

                if ($body !== null) {
                    $notification->body($body);
                }

                $notification->send();

                $this->record->refresh();

                return null;
            });
    }

    private function getRepeatAction(): Action
    {
        $hasNextAcademicYear = $this->hasNextAcademicYear();

        return Action::make('repeatGrade')
            ->label(__('filament.students.actions.repeat_grade'))
            ->icon(Heroicon::OutlinedArrowPath)
            ->color('warning')
            ->visible(fn(): bool => $this->record->determineCurrentGradeLevel() !== null)
            ->requiresConfirmation()
            ->schema($hasNextAcademicYear ? $this->getRepeatFormSchema() : [])
            ->modalHeading($hasNextAcademicYear ? null : __('filament.students.actions.next_academic_year_required_title'))
            ->modalDescription($hasNextAcademicYear ? null : __('filament.students.actions.next_academic_year_required_description'))
            ->modalSubmitActionLabel($hasNextAcademicYear
                ? __('filament.students.actions.repeat_submit')
                : __('filament.students.actions.open_academic_years'))
            ->action(function (array $data, PromoteStudentAction $promoter) use ($hasNextAcademicYear): mixed {
                if (! $hasNextAcademicYear) {
                    return redirect()->to(AcademicYearResource::getUrl());
                }

                $currentGrade = $this->record->determineCurrentGradeLevel();

                if ($currentGrade === null) {
                    Notification::make()
                        ->title(__('filament.students.actions.repeat_unavailable'))
                        ->danger()
                        ->send();

                    return null;
                }

                if ($this->hasOutstandingFees() && empty($data['override_outstanding_fees'])) {
                    throw ValidationException::withMessages([
                        'override_outstanding_fees' => __('filament.students.actions.outstanding_fees_confirmation_required'),
                    ]);
                }

                $academicYear = AcademicYear::query()->findOrFail($data['academic_year_id']);
                $classroom = isset($data['classroom_id']) ? Classroom::query()->findOrFail($data['classroom_id']) : null;

                $promoter->execute(
                    student: $this->record,
                    targetAcademicYear: $academicYear,
                    targetClassroom: $classroom,
                    overrideGradeLevel: $currentGrade,
                );

                Notification::make()
                    ->title(__('filament.students.actions.repeat_success', [
                        'grade' => $currentGrade->label(),
                        'year' => $academicYear->name,
                    ]))
                    ->success()
                    ->send();

                $this->record->refresh();

                return null;
            });
    }

    /**
     * @return array
     */
    private function getPromotionFormSchema(): array
    {
        return [
            $this->makeAcademicYearSelectComponent(),
            $this->makeGradeLevelSelectComponent(),
            $this->makeIneligibleGradeBadgeComponent(),
            // $this->makeRepeatToggleComponent(),
            $this->makeGraduationBadgeComponent(),
            // Classroom selection is determined automatically by grade level; we hide the dropdown for simplicity.
            $this->makeEligibilityEntryComponent(),
            $this->makeOutstandingFeesEntryComponent(),
            $this->makeOverrideOutstandingFeesCheckboxComponent(),
            $this->makeGraduationNoticeComponent(),
        ];
    }

    /**
     * @return array
     */
    private function getRepeatFormSchema(): array
    {
        return [
            $this->makeAcademicYearSelectComponent(),
            $this->makeRepeatGradeBadgeComponent(),
            $this->makeEligibilityEntryComponent(),
            $this->makeOutstandingFeesEntryComponent(),
            $this->makeOverrideOutstandingFeesCheckboxComponent(),
        ];
    }

    private function makeAcademicYearSelectComponent(): Select
    {
        return Select::make('academic_year_id')
            ->label(__('filament.students.actions.target_academic_year'))
            ->options(fn (): array => AcademicYear::query()
                ->orderByDesc('starts_on')
                ->pluck('name', 'id')
                ->all())
            ->searchable()
            ->preload()
            ->default(fn (): ?int => $this->getNextAcademicYear()?->id ?? $this->getReferenceAcademicYear()?->id)
            ->required();
    }

    private function makeGradeLevelSelectComponent(): Select
    {
        return Select::make('grade_level')
            ->label(__('filament.students.actions.target_grade_level'))
            ->options(GradeLevel::options())
            ->searchable()
            ->nullable()
            ->default(fn (): ?string => $this->record->next_grade_level?->value)
            ->disabled(fn (): bool => $this->record->determineCurrentGradeLevel() !== null)
            ->required(fn (): bool => $this->record->determineCurrentGradeLevel() === null)
            ->hidden(fn (): bool => ! $this->canConfigureNextGrade())
            ->dehydrated(fn (): bool => $this->canConfigureNextGrade());
    }

    private function makeRepeatToggleComponent(): Checkbox
    {
        return Checkbox::make('repeat_current_grade')
            ->label(__('filament.students.actions.repeat_current_grade_toggle'))
            ->helperText(fn (): string => __('filament.students.actions.repeat_current_grade_description', [
                'grade' => $this->record->determineCurrentGradeLevel()?->label() ?? '-',
            ]))
            ->visible(fn (): bool => ! $this->isGraduating() && ! $this->isEligibleForPromotion())
            ->reactive()
            ->columnSpanFull();
    }

    private function makeIneligibleGradeBadgeComponent(): TextEntry
    {
        return TextEntry::make('ineligible_grade_display')
            ->label(__('filament.students.actions.target_grade_level'))
            ->state(fn (): string => __('filament.students.actions.ineligible_grade_label'))
            ->badge()
            ->color('warning')
            ->visible(fn (): bool => ! $this->canConfigureNextGrade() && ! $this->isGraduating())
            ->columnSpanFull();
    }

    private function makeGraduationBadgeComponent(): TextEntry
    {
        return TextEntry::make('grade_level_display')
            ->label(__('filament.students.actions.target_grade_level'))
            ->state(fn (): string => __('filament.students.actions.graduated_label'))
            ->badge()
            ->color('success')
            ->visible(fn (): bool => $this->isGraduating())
            ->columnSpanFull();
    }

    private function makeRepeatGradeBadgeComponent(): TextEntry
    {
        return TextEntry::make('repeat_grade_level_display')
            ->label(__('filament.students.actions.repeat_grade_label'))
            ->state(function (): string {
                $grade = $this->record->determineCurrentGradeLevel();

                if ($grade === null) {
                    return __('filament.students.actions.repeat_grade_unknown');
                }

                return $grade->label();
            })
            ->badge()
            ->color('warning')
            ->columnSpanFull();
    }

    private function makeClassroomSelectComponent(bool $allowGradeFallback = false): Select
    {
        return Select::make('classroom_id')
            ->label(__('filament.students.actions.target_classroom'))
            ->options(function (Get $get) use ($allowGradeFallback): array {
                $academicYearId = $get('academic_year_id');
                $gradeLevel = $this->resolveGradeLevelForClassroomSelect($get, $allowGradeFallback);

                if (blank($academicYearId) || blank($gradeLevel)) {
                    return [];
                }

                return Classroom::query()
                    ->where('academic_year_id', $academicYearId)
                    ->where('grade_level', $gradeLevel)
                    ->orderBy('name')
                    ->pluck('name', 'id')
                    ->all();
            })
            ->searchable()
            ->preload()
            ->hidden()
            ->dehydrated(false)
            ->columnSpanFull();
    }

    private function makeEligibilityEntryComponent(): TextEntry
    {
        return TextEntry::make('eligibility_status')
            ->label(__('filament.students.actions.eligibility_status'))
            ->state(fn (): string => $this->getEligibilityMessage())
            ->columnSpanFull();
    }

    private function makeOutstandingFeesEntryComponent(): TextEntry
    {
        return TextEntry::make('outstanding_fees')
            ->label(__('filament.students.actions.outstanding_fees'))
            ->state(fn (): string => $this->formatOutstandingFeesMessage())
            ->suffixActions([
                Action::make('viewFees')
                    ->label(__('filament.students.actions.view_fees'))
                    ->icon(Heroicon::OutlinedBanknotes)
                    ->url(fn (): string => $this->getFeesUrl())
                    ->openUrlInNewTab(),
            ])
            ->columnSpanFull()
            ->hidden(fn (): bool => ! $this->hasOutstandingFees());
    }

    private function makeOverrideOutstandingFeesCheckboxComponent(): Checkbox
    {
        return Checkbox::make('override_outstanding_fees')
            ->label(fn (): string => $this->getOverrideOutstandingFeesLabel())
            ->hidden(fn (): bool => ! $this->hasOutstandingFees());
    }

    private function makeGraduationNoticeComponent(): TextEntry
    {
        return TextEntry::make('graduation_notice')
            ->label(__('filament.students.actions.graduation_notice_label'))
            ->state(fn (): string => __('filament.students.actions.graduation_notice'))
            ->visible(fn (): bool => $this->record->determineCurrentGradeLevel()?->isTerminal() ?? false)
            ->columnSpanFull();
    }

    private function resolveGradeLevelForClassroomSelect(Get $get, bool $allowGradeFallback): ?string
    {
        $repeat = (bool) $get('repeat_current_grade');

        if ($repeat) {
            return $this->record->determineCurrentGradeLevel()?->value;
        }

        $gradeLevel = $get('grade_level');

        if (filled($gradeLevel)) {
            return $gradeLevel;
        }

        if ($allowGradeFallback) {
            return $this->record->determineCurrentGradeLevel()?->value;
        }

        return null;
    }


    private function getEligibilityMessage(): string
    {
        if ($this->isEligibleForPromotion()) {
            return __('filament.students.actions.eligibility_ready');
        }

        if ($this->hasOutstandingFees()) {
            return $this->isGraduating()
                ? __('filament.students.actions.eligibility_pending_graduation_fees')
                : __('filament.students.actions.eligibility_pending_fees');
        }

        return __('filament.students.actions.eligibility_pending_scores');
    }

    private function resolvePromotionFeeBody(StudentPromotionResult $result): ?string
    {
        if ($result->promotionFees === []) {
            return null;
        }

        $formatted = collect($result->promotionFees)
            ->map(fn(Fee $fee): string => $fee->reference . ' (' . $this->formatCurrency((float) $fee->amount) . ')')
            ->join(', ');

        return __('filament.students.actions.promotion_fees_created', [
            'fees' => $formatted,
        ]);
    }

    private function formatOutstandingFeesMessage(): string
    {
        $fees = $this->getOutstandingFees();
        $total = $fees->sum(fn (Fee $fee): float => $fee->outstanding_amount);

        return __('filament.students.actions.outstanding_fees_message', [
            'count' => $fees->count(),
            'amount' => $this->formatCurrency((float) $total),
        ]);
    }

    private function getOutstandingFees(): Collection
    {
        return $this->record->getOutstandingFees();
    }

    private function hasOutstandingFees(): bool
    {
        return $this->record->hasOutstandingFees();
    }

    private function isEligibleForPromotion(): bool
    {
        return ! $this->hasOutstandingFees();
    }

    private function formatCurrency(float $amount): string
    {
        return 'IDR ' . number_format($amount, 0);
    }

    private function getReferenceAcademicYear(): ?AcademicYear
    {
        if (! $this->referenceAcademicYearResolved) {
            $this->referenceAcademicYearResolved = true;

            $studentYear = $this->record->academicYear;

            if ($studentYear === null && $this->record->academic_year_id !== null) {
                $studentYear = AcademicYear::query()->find($this->record->academic_year_id);
            }

            $this->cachedReferenceAcademicYear = $studentYear
                ?? AcademicYear::query()->current()->orderByDesc('starts_on')->first()
                ?? AcademicYear::query()->orderByDesc('starts_on')->first();
        }

        return $this->cachedReferenceAcademicYear;
    }

    private function getNextAcademicYear(): ?AcademicYear
    {
        if (! $this->nextAcademicYearResolved) {
            $this->nextAcademicYearResolved = true;

            $reference = $this->getReferenceAcademicYear();

            if ($reference?->starts_on !== null) {
                $this->cachedNextAcademicYear = AcademicYear::query()
                    ->whereDate('starts_on', '>', $reference->starts_on)
                    ->orderBy('starts_on')
                    ->first();
            } elseif ($reference !== null) {
                $this->cachedNextAcademicYear = AcademicYear::query()
                    ->where('id', '>', $reference->id)
                    ->orderBy('id')
                    ->first();
            } else {
                $this->cachedNextAcademicYear = null;
            }
        }

        return $this->cachedNextAcademicYear;
    }

    private function hasNextAcademicYear(): bool
    {
        return $this->getNextAcademicYear() !== null;
    }

    private function getOverrideOutstandingFeesLabel(): string
    {
        return $this->isGraduating()
            ? __('filament.students.actions.override_outstanding_graduation_fees')
            : __('filament.students.actions.override_outstanding_fees');
    }

    private function canConfigureNextGrade(): bool
    {
        return ! $this->isGraduating() && $this->isEligibleForPromotion();
    }

    private function isGraduating(): bool
    {
        return $this->record->determineCurrentGradeLevel()?->isTerminal() ?? false;
    }

    private function getFeesUrl(): string
    {
        return FeeResource::getUrl('index', parameters: [
            'filters' => [
                'student_id' => [
                    'value' => $this->record->getKey(),
                ],
                'status' => [
                    'values' => [
                        FeeStatus::Pending->value,
                        FeeStatus::Partial->value,
                    ],
                ],
            ],
        ]);
    }
}
