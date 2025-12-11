<?php

declare(strict_types=1);

namespace App\Filament\Resources\Classrooms\RelationManagers;

use App\Enums\AssignmentRole;
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
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ClassroomStaffRelationManager extends RelationManager
{
    protected static string $relationship = 'staffAssignments';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Select::make('staff_id')
                    ->label(__('filament.classroom_staff.fields.staff'))
                    ->relationship('staff', 'staff_name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('academic_year_id')
                    ->label(__('filament.classroom_staff.fields.academic_year'))
                    ->relationship('academicYear', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('assignment_role')
                    ->label(__('filament.classroom_staff.fields.role'))
                    ->options(AssignmentRole::options())
                    ->native(false)
                    ->default(AssignmentRole::Homeroom->value)
                    ->live()
                    ->partiallyRenderComponentsAfterStateUpdated(['subject_id'])
                    ->required(),
                Select::make('subject_id')
                    ->label(__('filament.classroom_staff.fields.subject'))
                    ->relationship('subject', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->hidden(
                        fn (Get $get): bool => $get('assignment_role') !== AssignmentRole::SubjectTeacher->value,
                    ),
                DatePicker::make('assigned_on')
                    ->label(__('filament.classroom_staff.fields.assigned_on'))
                    ->native(false)
                    ->required(),
                DatePicker::make('removed_on')
                    ->label(__('filament.classroom_staff.fields.removed_on'))
                    ->native(false)
                    ->after('assigned_on'),
                Textarea::make('notes')
                    ->label(__('filament.classroom_staff.fields.notes'))
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('assigned_on', 'desc')
            ->columns([
                TextColumn::make('staff.staff_name')
                    ->label(__('filament.classroom_staff.table.staff'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('assignment_role')
                    ->label(__('filament.classroom_staff.table.role'))
                    ->formatStateUsing(
                        fn (AssignmentRole|string|null $state): ?string => match (true) {
                            $state instanceof AssignmentRole => $state->getLabel(),
                            blank($state) => null,
                            default => AssignmentRole::from((string) $state)->getLabel(),
                        },
                    )
                    ->badge()
                    ->color(
                        fn (AssignmentRole|string|null $state): ?string => match (true) {
                            $state instanceof AssignmentRole => $state->getColor(),
                            blank($state) => null,
                            default => AssignmentRole::from((string) $state)->getColor(),
                        },
                    )
                    ->sortable(),
                TextColumn::make('subject.name')
                    ->label(__('filament.classroom_staff.table.subject'))
                    ->placeholder('-')
                    ->toggleable(),
                TextColumn::make('academicYear.name')
                    ->label(__('filament.classroom_staff.table.academic_year'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('assigned_on')
                    ->label(__('filament.classroom_staff.table.assigned_on'))
                    ->date()
                    ->sortable(),
                TextColumn::make('removed_on')
                    ->label(__('filament.classroom_staff.table.removed_on'))
                    ->date()
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('notes')
                    ->label(__('filament.classroom_staff.table.notes'))
                    ->limit(40)
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('assignment_role')
                    ->label(__('filament.classroom_staff.filters.role'))
                    ->options(AssignmentRole::options()),
                SelectFilter::make('academic_year_id')
                    ->label(__('filament.classroom_staff.filters.academic_year'))
                    ->relationship('academicYear', 'name'),
                SelectFilter::make('staff_id')
                    ->label(__('filament.classroom_staff.filters.staff'))
                    ->relationship('staff', 'staff_name'),
                TrashedFilter::make()
                    ->label(__('filament.classroom_staff.filters.trashed')),
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
