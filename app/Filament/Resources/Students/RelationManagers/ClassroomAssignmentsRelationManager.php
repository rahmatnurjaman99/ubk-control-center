<?php

declare(strict_types=1);

namespace App\Filament\Resources\Students\RelationManagers;

use App\Enums\GradeLevel;
use App\Enums\SchoolLevel;
use App\Models\Classroom;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ClassroomAssignmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'classroomAssignments';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('academic_year_id')
                    ->label(__('filament.classroom_assignments.fields.academic_year'))
                    ->relationship('academicYear', 'name')
                    ->preload()
                    ->searchable()
                    ->required(),
                Select::make('classroom_id')
                    ->label(__('filament.classroom_assignments.fields.classroom'))
                    ->relationship('classroom', 'name')
                    ->preload()
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(
                        function (Set $set, ?int $state): void {
                            if ($state === null) {
                                $set('grade_level', null);

                                return;
                            }

                            $classroom = Classroom::query()->find($state);
                            $set('grade_level', $classroom?->grade_level?->value);
                        },
                    ),
                Select::make('grade_level')
                    ->label(__('filament.classroom_assignments.fields.grade_level'))
                    ->options(GradeLevel::options())
                    ->disabled(fn (Get $get): bool => filled($get('grade_level')))
                    ->dehydrated()
                    ->required(),
                DatePicker::make('assigned_on')
                    ->label(__('filament.classroom_assignments.fields.assigned_on'))
                    ->native(false),
                DatePicker::make('removed_on')
                    ->label(__('filament.classroom_assignments.fields.removed_on'))
                    ->native(false)
                    ->after('assigned_on'),
                Textarea::make('notes')
                    ->label(__('filament.classroom_assignments.fields.notes'))
                    ->rows(3),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('academicYear.name')
                    ->label(__('filament.classroom_assignments.table.academic_year'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('classroom.name')
                    ->label(__('filament.classroom_assignments.table.classroom'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('grade_level')
                    ->label(__('filament.classroom_assignments.table.grade_level'))
                    ->formatStateUsing(
                        fn (?string $state): ?string => blank($state) ? null : GradeLevel::from($state)->label(),
                    )
                    ->badge(),
                TextColumn::make('classroom.school_level')
                    ->label(__('filament.classroom_assignments.table.school_level'))
                    ->formatStateUsing(
                        fn(?string $state): ?string => $state !== null ? SchoolLevel::from($state)->label() : null,
                    )
                    ->badge(),
                TextColumn::make('assigned_on')
                    ->label(__('filament.classroom_assignments.table.assigned_on'))
                    ->date(),
                TextColumn::make('removed_on')
                    ->label(__('filament.classroom_assignments.table.removed_on'))
                    ->date()
                    ->placeholder('-'),
                TextColumn::make('notes')
                    ->label(__('filament.classroom_assignments.table.notes'))
                    ->limit(30)
                    ->toggleable(),
            ])
            ->defaultSort('assigned_on', 'desc')
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
            ])
            ->poll('30s');
    }
}
