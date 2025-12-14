<?php

declare(strict_types=1);

namespace App\Filament\Resources\Schedules\Schemas;

use App\Models\Schedule;
use App\Support\AcademicYearResolver;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::getDetailsSection(),
                self::getMetaSection(),
            ])
            ->columns(1);
    }

    private static function getDetailsSection(): Section
    {
        return Section::make(__('filament.schedules.sections.details'))
            ->columns(2)
            ->schema([
                Select::make('academic_year_id')
                    ->label(__('filament.schedules.fields.academic_year'))
                    ->relationship('academicYear', 'name')
                    ->searchable()
                    ->preload()
                    ->default(fn (): ?int => AcademicYearResolver::currentId())
                    ->required(),
                TextInput::make('title')
                    ->label(__('filament.schedules.fields.title'))
                    ->maxLength(255)
                    ->required(),
                Select::make('subject_id')
                    ->label(__('filament.schedules.fields.subject'))
                    ->relationship('subject', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                Select::make('classroom_id')
                    ->label(__('filament.schedules.fields.classroom'))
                    ->relationship('classroom', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                Select::make('staff_id')
                    ->label(__('filament.schedules.fields.teacher'))
                    ->relationship('teacher', 'staff_name')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                DateTimePicker::make('starts_at')
                    ->label(__('filament.schedules.fields.starts_at'))
                    ->seconds(false)
                    ->native(false)
                    ->required(),
                DateTimePicker::make('ends_at')
                    ->label(__('filament.schedules.fields.ends_at'))
                    ->seconds(false)
                    ->native(false)
                    ->required()
                    ->rule('after:starts_at'),
                Toggle::make('is_all_day')
                    ->label(__('filament.schedules.fields.is_all_day'))
                    ->default(false),
                TextInput::make('location')
                    ->label(__('filament.schedules.fields.location'))
                    ->maxLength(255),
            ]);
    }

    private static function getMetaSection(): Section
    {
        return Section::make(__('filament.schedules.sections.meta'))
            ->schema([
                DatePicker::make('repeat_weekly_until')
                    ->label(__('filament.schedules.fields.repeat_weekly_until'))
                    ->helperText(__('filament.schedules.hints.repeat_weekly_until'))
                    ->native(false)
                    ->after('ends_at')
                    ->hidden(fn (?Schedule $record): bool => $record !== null)
                    ->dehydrated()
                    ->nullable(),
                ColorPicker::make('color')
                    ->label(__('filament.schedules.fields.color'))
                    ->nullable(),
                Textarea::make('description')
                    ->label(__('filament.schedules.fields.description'))
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }
}
