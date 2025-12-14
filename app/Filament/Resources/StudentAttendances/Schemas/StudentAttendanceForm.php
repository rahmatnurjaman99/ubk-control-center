<?php

declare(strict_types=1);

namespace App\Filament\Resources\StudentAttendances\Schemas;

use App\Enums\AttendanceStatus;
use App\Support\AcademicYearResolver;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StudentAttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::getDetailsSection(),
                self::getNotesSection(),
            ])
            ->columns(1);
    }

    private static function getDetailsSection(): Section
    {
        return Section::make(__('filament.student_attendances.sections.details'))
            ->columns(2)
            ->schema([
                Select::make('academic_year_id')
                    ->label(__('filament.student_attendances.fields.academic_year'))
                    ->relationship('academicYear', 'name')
                    ->searchable()
                    ->preload()
                    ->default(fn (): ?int => AcademicYearResolver::currentId())
                    ->required(),
                Select::make('student_id')
                    ->label(__('filament.student_attendances.fields.student'))
                    ->relationship('student', 'full_name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('classroom_id')
                    ->label(__('filament.student_attendances.fields.classroom'))
                    ->relationship('classroom', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                DatePicker::make('recorded_on')
                    ->label(__('filament.student_attendances.fields.recorded_on'))
                    ->native(false)
                    ->default(now())
                    ->required(),
                Select::make('status')
                    ->label(__('filament.student_attendances.fields.status'))
                    ->options(AttendanceStatus::options())
                    ->enum(AttendanceStatus::class)
                    ->native(false)
                    ->required()
                    ->default(AttendanceStatus::Present->value),
                DateTimePicker::make('checked_in_at')
                    ->label(__('filament.student_attendances.fields.checked_in_at'))
                    ->seconds(false)
                    ->native(false),
                DateTimePicker::make('checked_out_at')
                    ->label(__('filament.student_attendances.fields.checked_out_at'))
                    ->seconds(false)
                    ->native(false),
                Hidden::make('recorded_by')
                    ->default(fn(): ?int => auth()->id()),
            ]);
    }

    private static function getNotesSection(): Section
    {
        return Section::make(__('filament.student_attendances.sections.notes'))
            ->schema([
                Textarea::make('notes')
                    ->label(__('filament.student_attendances.fields.notes'))
                    ->required(fn(Get $get): bool => $get('status') !== AttendanceStatus::Present->value)
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }
}
