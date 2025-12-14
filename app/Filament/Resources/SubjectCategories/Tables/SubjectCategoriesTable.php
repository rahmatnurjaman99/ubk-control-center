<?php

declare(strict_types=1);

namespace App\Filament\Resources\SubjectCategories\Tables;

use App\Support\Tables\Columns\CreatedAtColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SubjectCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                self::getNameColumn(),
                self::getSlugColumn(),
                self::getSubjectsCountColumn(),
                CreatedAtColumn::make(),
                self::getUpdatedAtColumn(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    private static function getNameColumn(): TextColumn
    {
        return TextColumn::make('name')
            ->label(__('filament.subject_categories.table.name'))
            ->searchable()
            ->sortable();
    }

    private static function getSlugColumn(): TextColumn
    {
        return TextColumn::make('slug')
            ->label(__('filament.subject_categories.table.slug'))
            ->copyable()
            ->copyMessage(__('filament.subject_categories.table.slug_copied'))
            ->copyMessageDuration(1500)
            ->searchable()
            ->sortable();
    }

    private static function getSubjectsCountColumn(): TextColumn
    {
        return TextColumn::make('subjects_count')
            ->label(__('filament.subject_categories.table.subjects_count'))
            ->counts('subjects')
            ->alignCenter()
            ->sortable();
    }

    private static function getUpdatedAtColumn(): TextColumn
    {
        return TextColumn::make('updated_at')
            ->label(__('filament.subject_categories.table.updated_at'))
            ->dateTime()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }
}
