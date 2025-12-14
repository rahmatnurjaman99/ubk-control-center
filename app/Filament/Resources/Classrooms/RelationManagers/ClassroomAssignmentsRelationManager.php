<?php

declare(strict_types=1);

namespace App\Filament\Resources\Classrooms\RelationManagers;

use App\Enums\GradeLevel;
use App\Support\AcademicYearResolver;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ClassroomAssignmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'assignments';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Select::make('academic_year_id')
                    ->label(__('filament.classroom_assignments.fields.academic_year'))
                    ->relationship('academicYear', 'name')
                    ->searchable()
                    ->preload()
                    ->default(fn (): ?int => AcademicYearResolver::currentId())
                    ->required(),
                Select::make('student_id')
                    ->label(__('filament.classroom_assignments.fields.student'))
                    ->relationship('student', 'full_name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('grade_level')
                    ->label(__('filament.classroom_assignments.fields.grade_level'))
                    ->options(GradeLevel::options())
                    ->default(fn (): ?string => $this->ownerRecord->grade_level?->value)
                    ->required(),
                DatePicker::make('assigned_on')
                    ->label(__('filament.classroom_assignments.fields.assigned_on'))
                    ->native(false)
                    ->required(),
                DatePicker::make('removed_on')
                    ->label(__('filament.classroom_assignments.fields.removed_on'))
                    ->native(false)
                    ->after('assigned_on'),
                Textarea::make('notes')
                    ->label(__('filament.classroom_assignments.fields.notes'))
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('assigned_on', 'desc')
            ->columns([
                TextColumn::make('student.full_name')
                    ->label(__('filament.classroom_assignments.table.student'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('academicYear.name')
                    ->label(__('filament.classroom_assignments.table.academic_year'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('grade_level')
                    ->label(__('filament.classroom_assignments.table.grade_level'))
                    ->formatStateUsing(
                        fn (GradeLevel|string|null $state): ?string => match (true) {
                            $state instanceof GradeLevel => $state->label(),
                            blank($state) => null,
                            default => GradeLevel::from((string) $state)->label(),
                        },
                    )
                    ->badge()
                    ->sortable(),
                TextColumn::make('assigned_on')
                    ->label(__('filament.classroom_assignments.table.assigned_on'))
                    ->date()
                    ->sortable(),
                TextColumn::make('removed_on')
                    ->label(__('filament.classroom_assignments.table.removed_on'))
                    ->date()
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('notes')
                    ->label(__('filament.classroom_assignments.table.notes'))
                    ->limit(40)
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('academic_year_id')
                    ->label(__('filament.classroom_assignments.filters.academic_year'))
                    ->relationship('academicYear', 'name'),
                SelectFilter::make('student_id')
                    ->label(__('filament.classroom_assignments.filters.student'))
                    ->relationship('student', 'full_name'),
                TrashedFilter::make()
                    ->label(__('filament.classroom_assignments.filters.trashed')),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
            ]);
    }
}
