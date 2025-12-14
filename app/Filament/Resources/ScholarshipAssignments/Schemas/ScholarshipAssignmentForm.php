<?php

declare(strict_types=1);

namespace App\Filament\Resources\ScholarshipAssignments\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ScholarshipAssignmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Select::make('scholarship_id')
                    ->label(__('filament.scholarship_assignments.fields.scholarship'))
                    ->relationship('scholarship', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('student_id')
                    ->label(__('filament.scholarship_assignments.fields.student'))
                    ->relationship('student', 'full_name')
                    ->searchable()
                    ->preload()
                    ->required(),
                DatePicker::make('effective_from')
                    ->label(__('filament.scholarship_assignments.fields.effective_from'))
                    ->native(false),
                DatePicker::make('effective_until')
                    ->label(__('filament.scholarship_assignments.fields.effective_until'))
                    ->native(false)
                    ->minDate(fn (Get $get) => $get('effective_from')),
                Toggle::make('is_active')
                    ->label(__('filament.scholarship_assignments.fields.is_active'))
                    ->default(true),
                Textarea::make('notes')
                    ->label(__('filament.scholarship_assignments.fields.notes'))
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
