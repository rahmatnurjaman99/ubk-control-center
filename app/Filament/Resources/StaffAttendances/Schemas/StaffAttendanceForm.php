<?php

declare(strict_types=1);

namespace App\Filament\Resources\StaffAttendances\Schemas;

use App\Enums\AttendanceStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StaffAttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::getPrimarySection(),
                self::getNotesSection(),
            ])
            ->columns(1);
    }

    private static function getPrimarySection(): Section
    {
        return Section::make(__('filament.staff_attendances.sections.details'))
            ->columns(2)
            ->schema([
                Select::make('staff_id')
                    ->label(__('filament.staff_attendances.fields.staff'))
                    ->relationship('staff', 'staff_name')
                    ->searchable()
                    ->preload()
                    ->required(),
                DatePicker::make('recorded_on')
                    ->label(__('filament.staff_attendances.fields.recorded_on'))
                    ->native(false)
                    ->default(now())
                    ->required(),
                Select::make('status')
                    ->label(__('filament.staff_attendances.fields.status'))
                    ->options(AttendanceStatus::options())
                    ->enum(AttendanceStatus::class)
                    ->native(false)
                    ->default(AttendanceStatus::Present->value)
                    ->required(),
                TextInput::make('location')
                    ->label(__('filament.staff_attendances.fields.location'))
                    ->maxLength(255)
                    ->nullable(),
                DateTimePicker::make('checked_in_at')
                    ->label(__('filament.staff_attendances.fields.checked_in_at'))
                    ->seconds(false)
                    ->native(false),
                DateTimePicker::make('checked_out_at')
                    ->label(__('filament.staff_attendances.fields.checked_out_at'))
                    ->seconds(false)
                    ->native(false),
                Hidden::make('recorded_by')
                    ->default(fn (): ?int => auth()->id()),
            ]);
    }

    private static function getNotesSection(): Section
    {
        return Section::make(__('filament.staff_attendances.sections.notes'))
            ->schema([
                Textarea::make('notes')
                    ->label(__('filament.staff_attendances.fields.notes'))
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }
}
