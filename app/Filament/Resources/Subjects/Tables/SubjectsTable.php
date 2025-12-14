<?php

declare(strict_types=1);

namespace App\Filament\Resources\Subjects\Tables;

use App\Enums\SchoolLevel;
use App\Models\Subject;
use App\Support\Tables\Columns\CreatedAtColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SubjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn(Builder $query): Builder => $query->with(['category', 'classrooms']),
            )
            ->columns([
                self::getCodeColumn(),
                self::getNameColumn(),
                self::getCategoryColumn(),
                self::getSchoolLevelColumn(),
                self::getAcademicYearColumn(),
                self::getIsCompulsoryColumn(),
                self::getCreditHoursColumn(),
                self::getClassroomsColumn(),
                CreatedAtColumn::make(),
                self::getUpdatedAtColumn(),
            ])
            ->defaultSort('name')
            ->filters([
                self::getSchoolLevelFilter(),
                self::getAcademicYearFilter(),
                self::getCategoryFilter(),
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
            ->label(__('filament.subjects.table.code'))
            ->searchable()
            ->sortable();
    }

    private static function getNameColumn(): TextColumn
    {
        return TextColumn::make('name')
            ->label(__('filament.subjects.table.name'))
            ->searchable()
            ->sortable();
    }

    private static function getSchoolLevelColumn(): TextColumn
    {
        return TextColumn::make('school_level')
            ->label(__('filament.subjects.table.school_level'))
            ->formatStateUsing(
                fn (SchoolLevel|string|null $state): ?string => match (true) {
                    $state instanceof SchoolLevel => $state->label(),
                    blank($state) => null,
                    default => SchoolLevel::from((string) $state)->label(),
                },
            )
            ->badge()
            ->sortable();
    }

    private static function getCategoryColumn(): TextColumn
    {
        return TextColumn::make('category.name')
            ->label(__('filament.subjects.table.category'))
            ->sortable()
            ->searchable()
            ->toggleable();
    }

    private static function getAcademicYearColumn(): TextColumn
    {
        return TextColumn::make('academicYear.name')
            ->label(__('filament.subjects.table.academic_year'))
            ->sortable()
            ->searchable()
            ->toggleable();
    }

    private static function getIsCompulsoryColumn(): IconColumn
    {
        return IconColumn::make('is_compulsory')
            ->label(__('filament.subjects.table.is_compulsory'))
            ->boolean();
    }

    private static function getCreditHoursColumn(): TextColumn
    {
        return TextColumn::make('credit_hours')
            ->label(__('filament.subjects.table.credit_hours'))
            ->formatStateUsing(
                fn(?int $state): string => $state !== null ? (string) $state : '-',
            )
            ->sortable()
            ->alignRight();
    }

    private static function getClassroomsColumn(): TextColumn
    {
        return TextColumn::make('classrooms_summary')
            ->label(__('filament.subjects.table.classrooms'))
            ->getStateUsing(
                fn(Subject $record): string => $record->classrooms
                    ->pluck('name')
                    ->implode(', '),
            )
            ->formatStateUsing(
                fn(string $state): string => $state !== '' ? $state : '-',
            )
            ->toggleable()
            ->wrap();
    }

    private static function getUpdatedAtColumn(): TextColumn
    {
        return TextColumn::make('updated_at')
            ->label(__('filament.subjects.table.updated_at'))
            ->dateTime()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }

    private static function getSchoolLevelFilter(): SelectFilter
    {
        return SelectFilter::make('school_level')
            ->label(__('filament.subjects.filters.school_level'))
            ->options(SchoolLevel::options());
    }

    private static function getAcademicYearFilter(): SelectFilter
    {
        return SelectFilter::make('academic_year_id')
            ->label(__('filament.subjects.filters.academic_year'))
            ->relationship('academicYear', 'name');
    }

    private static function getCategoryFilter(): SelectFilter
    {
        return SelectFilter::make('subject_category_id')
            ->label(__('filament.subjects.filters.category'))
            ->relationship('category', 'name');
    }

    private static function getTrashedFilter(): TrashedFilter
    {
        return TrashedFilter::make()
            ->label(__('filament.subjects.filters.trashed'));
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
