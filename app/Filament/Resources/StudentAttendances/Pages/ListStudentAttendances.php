<?php

declare(strict_types=1);

namespace App\Filament\Resources\StudentAttendances\Pages;

use App\Enums\AttendanceStatus;
use App\Filament\Resources\StudentAttendances\StudentAttendanceResource;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\StudentAttendance;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Carbon;

class ListStudentAttendances extends ListRecords
{
    protected static string $resource = StudentAttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getGenerateRosterAction(),
            CreateAction::make(),
        ];
    }

    private function getGenerateRosterAction(): Action
    {
        return Action::make('generateRoster')
            ->label(__('filament.student_attendances.actions.generate'))
            ->icon(Heroicon::OutlinedClipboardDocumentList)
            ->schema([
                DatePicker::make('recorded_on')
                    ->label(__('filament.student_attendances.fields.recorded_on'))
                    ->native(false)
                    ->default(now())
                    ->required(),
                Select::make('academic_year_id')
                    ->label(__('filament.student_attendances.fields.academic_year'))
                    ->options(fn(): array => AcademicYear::query()->orderByDesc('starts_on')->pluck('name', 'id')->all())
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('classroom_id')
                    ->label(__('filament.student_attendances.fields.classroom'))
                    ->options(function (Get $get): array {
                        $academicYear = $get('academic_year_id');

                        if (blank($academicYear)) {
                            return [];
                        }

                        return Classroom::query()
                            ->where('academic_year_id', $academicYear)
                            ->orderBy('name')
                            ->pluck('name', 'id')
                            ->all();
                    })
                    ->reactive()
                    ->searchable()
                    ->preload()
                    ->required(),
            ])
            ->action(function (array $data): void {
                $recordedOn = Carbon::parse($data['recorded_on'])->toDateString();

                $students = Student::query()
                    ->where('academic_year_id', $data['academic_year_id'])
                    ->where('classroom_id', $data['classroom_id'])
                    ->orderBy('full_name')
                    ->get();

                foreach ($students as $student) {
                    StudentAttendance::query()->updateOrCreate(
                        [
                            'student_id' => $student->id,
                            'recorded_on' => $recordedOn,
                        ],
                        [
                            'academic_year_id' => $data['academic_year_id'],
                            'classroom_id' => $data['classroom_id'],
                            'status' => AttendanceStatus::Present,
                            'recorded_by' => auth()->id(),
                        ],
                    );
                }

                Notification::make()
                    ->title(__('filament.student_attendances.actions.generate_success', ['count' => $students->count()]))
                    ->success()
                    ->send();
            });
    }
}
