<?php

declare(strict_types=1);

namespace App\Filament\Resources\Students\Pages;

use App\Enums\GradeLevel;
use App\Filament\Resources\Students\StudentResource;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Support\Students\PromoteStudentAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\Action;
use Filament\Actions\RestoreAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Resources\Pages\EditRecord;

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
            ->form([
                Select::make('academic_year_id')
                    ->label(__('filament.students.actions.target_academic_year'))
                    ->options(fn (): array => AcademicYear::query()
                        ->orderByDesc('starts_on')
                        ->pluck('name', 'id')
                        ->all())
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('grade_level')
                    ->label(__('filament.students.actions.target_grade_level'))
                    ->options(GradeLevel::options())
                    ->searchable()
                    ->nullable()
                    ->default(fn () => $this->record->next_grade_level?->value)
                    ->required(fn (): bool => $this->record->determineCurrentGradeLevel() === null),
                Select::make('classroom_id')
                    ->label(__('filament.students.actions.target_classroom'))
                    ->options(function (Get $get): array {
                        $academicYearId = $get('academic_year_id');

                        if (blank($academicYearId)) {
                            return [];
                        }

                        $query = Classroom::query()->orderBy('name');

                        $query->where('academic_year_id', $academicYearId);

                        if ($gradeLevel = $get('grade_level')) {
                            $query->where('grade_level', $gradeLevel);
                        }

                        return $query->pluck('name', 'id')->all();
                    })
                    ->searchable()
                    ->preload()
                    ->hidden(fn (Get $get): bool => blank($get('academic_year_id')))
                    ->columnSpanFull(),
            ])
            ->action(function (array $data, PromoteStudentAction $promoter): void {
                $student = $this->record;

                $academicYear = AcademicYear::query()->findOrFail($data['academic_year_id']);
                $classroom = isset($data['classroom_id']) ? Classroom::query()->findOrFail($data['classroom_id']) : null;
                $gradeLevel = isset($data['grade_level']) && $data['grade_level'] !== null
                    ? GradeLevel::from($data['grade_level'])
                    : null;

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

                Notification::make()
                    ->title($message)
                    ->success()
                    ->send();

                $this->record->refresh();
            });
    }
}
