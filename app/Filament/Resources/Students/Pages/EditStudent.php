<?php

declare(strict_types=1);

namespace App\Filament\Resources\Students\Pages;

use App\Enums\FeeStatus;
use App\Enums\GradeLevel;
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
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class EditStudent extends EditRecord
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getPromotionAction(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    private function getPromotionAction(): Action
    {
        return Action::make('promoteStudent')
            ->label(__('filament.students.actions.promote'))
            ->icon(Heroicon::OutlinedArrowTrendingUp)
            ->color('primary')
            ->schema([
                Select::make('academic_year_id')
                    ->label(__('filament.students.actions.target_academic_year'))
                    ->options(fn(): array => AcademicYear::query()
                        ->orderByDesc('starts_on')
                        ->pluck('name', 'id')
                        ->all())
                    ->searchable()
                    ->preload()
                    ->default(fn(): ?int => AcademicYear::query()->where('is_current', true)->value('id'))
                    ->required(),
                Select::make('grade_level')
                    ->label(__('filament.students.actions.target_grade_level'))
                    ->options(GradeLevel::options())
                    ->searchable()
                    ->nullable()
                    ->default(fn(): ?string => $this->record->next_grade_level?->value)
                    ->disabled(fn(): bool => $this->record->determineCurrentGradeLevel() !== null)
                    ->required(fn(): bool => $this->record->determineCurrentGradeLevel() === null),
                Select::make('classroom_id')
                    ->label(__('filament.students.actions.target_classroom'))
                    ->options(function (Get $get): array {
                        $academicYearId = $get('academic_year_id');
                        $gradeLevel = $get('grade_level');

                        if (blank($academicYearId) || blank($gradeLevel)) {
                            return [];
                        }

                        $query = Classroom::query()->orderBy('name');

                        $query->where('academic_year_id', $academicYearId);

                        $query->where('grade_level', $gradeLevel);

                        return $query->pluck('name', 'id')->all();
                    })
                    ->searchable()
                    ->preload()
                    ->hidden(fn(Get $get): bool => blank($get('academic_year_id')) || blank($get('grade_level')))
                    ->disabled(fn(Get $get): bool => blank($get('grade_level')))
                    ->columnSpanFull(),
                TextEntry::make('eligibility_status')
                    ->label(__('filament.students.actions.eligibility_status'))
                    ->state(fn(): string => $this->getEligibilityMessage())
                    ->columnSpanFull(),
                TextEntry::make('outstanding_fees')
                    ->label(__('filament.students.actions.outstanding_fees'))
                    ->state(fn(): string => $this->formatOutstandingFeesMessage())
                    ->suffixActions([
                        Action::make('viewFees')
                            ->label(__('filament.students.actions.view_fees'))
                            ->icon(Heroicon::OutlinedBanknotes)
                            ->url(fn(): string => $this->getFeesUrl())
                            ->openUrlInNewTab(),
                    ])
                    ->columnSpanFull()
                    ->hidden(fn(): bool => ! $this->hasOutstandingFees()),
                Checkbox::make('override_outstanding_fees')
                    ->label(__('filament.students.actions.override_outstanding_fees'))
                    ->hidden(fn(): bool => ! $this->hasOutstandingFees()),
                TextEntry::make('graduation_notice')
                    ->label(__('filament.students.actions.graduation_notice_label'))
                    ->state(fn(): string => __('filament.students.actions.graduation_notice'))
                    ->visible(fn(): bool => $this->record->determineCurrentGradeLevel()?->isTerminal() ?? false)
                    ->columnSpanFull(),
            ])
            ->action(function (array $data, PromoteStudentAction $promoter): void {
                if ($this->hasOutstandingFees() && empty($data['override_outstanding_fees'])) {
                    throw ValidationException::withMessages([
                        'override_outstanding_fees' => __('filament.students.actions.outstanding_fees_confirmation_required'),
                    ]);
                }

                $student = $this->record;

                $academicYear = AcademicYear::query()->findOrFail($data['academic_year_id']);
                $classroom = isset($data['classroom_id']) ? Classroom::query()->findOrFail($data['classroom_id']) : null;
                $gradeLevel = isset($data['grade_level']) && $data['grade_level'] !== null
                    ? GradeLevel::from($data['grade_level'])
                    : $student->determineCurrentGradeLevel()?->next();

                $result = $promoter->execute(
                    student: $student,
                    targetAcademicYear: $academicYear,
                    targetClassroom: $classroom,
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
            });
    }

    private function getEligibilityMessage(): string
    {
        if ($this->isEligibleForPromotion()) {
            return __('filament.students.actions.eligibility_ready');
        }

        if ($this->hasOutstandingFees()) {
            return __('filament.students.actions.eligibility_pending_fees');
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
