<?php

declare(strict_types=1);

namespace App\Filament\Resources\Guardians\Tables;

use App\Support\Tables\Columns\CreatedAtColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class GuardiansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                self::getGuardianNumberColumn(),
                self::getFullNameColumn(),
                self::getRelationshipColumn(),
                self::getPhoneColumn(),
                self::getEmailColumn(),
                self::getStudentsCountColumn(),
                self::getCreatedAtColumn(),
                self::getUpdatedAtColumn(),
            ])
            ->defaultSort('full_name')
            ->filters([
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

    private static function getGuardianNumberColumn(): TextColumn
    {
        return TextColumn::make('guardian_number')
            ->label(__('filament.guardians.table.guardian_number'))
            ->searchable()
            ->sortable();
    }

    private static function getFullNameColumn(): TextColumn
    {
        return TextColumn::make('full_name')
            ->label(__('filament.guardians.table.full_name'))
            ->searchable()
            ->sortable();
    }

    private static function getRelationshipColumn(): TextColumn
    {
        return TextColumn::make('relationship')
            ->label(__('filament.guardians.table.relationship'))
            ->searchable()
            ->sortable();
    }

    private static function getPhoneColumn(): TextColumn
    {
        return TextColumn::make('phone')
            ->label(__('filament.guardians.table.phone'))
            ->searchable()
            ->toggleable();
    }

    private static function getEmailColumn(): TextColumn
    {
        return TextColumn::make('email')
            ->label(__('filament.guardians.table.email'))
            ->searchable()
            ->toggleable();
    }

    private static function getStudentsCountColumn(): TextColumn
    {
        return TextColumn::make('students_count')
            ->label(__('filament.guardians.table.students_count'))
            ->counts('students')
            ->sortable()
            ->alignRight();
    }

    private static function getCreatedAtColumn(): TextColumn
    {
        return CreatedAtColumn::make()
            ->label(__('filament.guardians.table.created_at'))
            ->toggleable(isToggledHiddenByDefault: true);
    }

    private static function getUpdatedAtColumn(): TextColumn
    {
        return TextColumn::make('updated_at')
            ->label(__('filament.guardians.table.updated_at'))
            ->dateTime()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }

    private static function getTrashedFilter(): TrashedFilter
    {
        return TrashedFilter::make()
            ->label(__('filament.guardians.filters.trashed'));
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
