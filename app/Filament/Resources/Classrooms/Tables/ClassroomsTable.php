<?php

declare(strict_types=1);

namespace App\Filament\Resources\Classrooms\Tables;

use App\Enums\GradeLevel;
use App\Enums\SchoolLevel;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ClassroomsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                self::getCodeColumn(),
                self::getNameColumn(),
                self::getAcademicYearColumn(),
                self::getSchoolLevelColumn(),
                self::getGradeLevelColumn(),
                self::getCapacityColumn(),
                self::getStudentsCountColumn(),
                self::getUpdatedAtColumn(),
            ])
            ->defaultSort('name')
            ->filters([
                self::getSchoolLevelFilter(),
                self::getAcademicYearFilter(),
                self::getGradeLevelFilter(),
                self::getTrashedFilter(),
            ])
            ->recordActions([
                self::getEditAction(),
                self::getDeleteAction(),
            ])
            ->toolbarActions([
                self::getBulkActions(),
            ]);
    }

    private static function getCodeColumn(): TextColumn
    {
        return TextColumn::make('code')
            ->label(__('filament.classrooms.table.code'))
            ->sortable()
            ->searchable();
    }

    private static function getNameColumn(): TextColumn
    {
        return TextColumn::make('name')
            ->label(__('filament.classrooms.table.name'))
            ->sortable()
            ->searchable();
    }

    private static function getAcademicYearColumn(): TextColumn
    {
        return TextColumn::make('academicYear.name')
            ->label(__('filament.classrooms.table.academic_year'))
            ->sortable()
            ->searchable();
    }

    private static function getSchoolLevelColumn(): TextColumn
    {
        return TextColumn::make('school_level')
            ->label(__('filament.classrooms.table.school_level'))
            ->formatStateUsing(
                fn (SchoolLevel|string|null $state): ?string => match (true) {
                    $state instanceof SchoolLevel => $state->label(),
                    blank($state) => null,
                    default => SchoolLevel::from((string) $state)->label(),
                },
            )
            ->badge()
            ->colors([
                'primary',
            ])
            ->sortable();
    }

    private static function getGradeLevelColumn(): TextColumn
    {
        return TextColumn::make('grade_level')
            ->label(__('filament.classrooms.table.grade_level'))
            ->formatStateUsing(
                fn (GradeLevel|string|null $state): ?string => match (true) {
                    $state instanceof GradeLevel => $state->label(),
                    blank($state) => null,
                    default => GradeLevel::from((string) $state)->label(),
                },
            )
            ->badge()
            ->sortable();
    }

    private static function getCapacityColumn(): TextColumn
    {
        return TextColumn::make('capacity')
            ->label(__('filament.classrooms.table.capacity'))
            ->numeric()
            ->sortable();
    }

    private static function getGradeLevelFilter(): SelectFilter
    {
        return SelectFilter::make('grade_level')
            ->label(__('filament.classrooms.filters.grade_level'))
            ->options(GradeLevel::options());
    }

    private static function getStudentsCountColumn(): TextColumn
    {
        return TextColumn::make('students_count')
            ->label(__('filament.classrooms.table.students_count'))
            ->counts('students')
            ->sortable()
            ->alignRight();
    }

    private static function getUpdatedAtColumn(): TextColumn
    {
        return TextColumn::make('updated_at')
            ->label(__('filament.classrooms.table.updated_at'))
            ->dateTime()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }

    private static function getSchoolLevelFilter(): SelectFilter
    {
        return SelectFilter::make('school_level')
            ->label(__('filament.classrooms.filters.school_level'))
            ->options(SchoolLevel::options());
    }

    private static function getAcademicYearFilter(): SelectFilter
    {
        return SelectFilter::make('academic_year_id')
            ->label(__('filament.classrooms.filters.academic_year'))
            ->relationship('academicYear', 'name');
    }

    private static function getTrashedFilter(): TrashedFilter
    {
        return TrashedFilter::make()
            ->label(__('filament.classrooms.filters.trashed'));
    }

    private static function getEditAction(): EditAction
    {
        return EditAction::make();
    }

    private static function getDeleteAction(): DeleteAction
    {
        return DeleteAction::make();
    }

    private static function getBulkActions(): BulkActionGroup
    {
        return BulkActionGroup::make([
            DeleteBulkAction::make(),
            ForceDeleteBulkAction::make(),
            RestoreBulkAction::make(),
        ]);
    }
}
