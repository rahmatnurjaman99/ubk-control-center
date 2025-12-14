<?php

declare(strict_types=1);

namespace App\Filament\Resources\FeeTemplates\Tables;

use App\Enums\FeeType;
use App\Enums\GradeLevel;
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
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class FeeTemplatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('grade_level')
            ->columns([
                TextColumn::make('title')
                    ->label(__('filament.fee_templates.table.title'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('academicYear.name')
                    ->label(__('filament.fee_templates.table.academic_year'))
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('grade_level')
                    ->label(__('filament.fee_templates.table.grade_level'))
                    ->badge()
                    ->formatStateUsing(fn (?GradeLevel $state): ?string => $state?->label())
                    ->sortable(),
                TextColumn::make('type')
                    ->label(__('filament.fee_templates.table.type'))
                    ->badge()
                    ->formatStateUsing(fn (?FeeType $state): ?string => $state?->getLabel())
                    ->sortable(),
                TextColumn::make('amount')
                    ->label(__('filament.fee_templates.table.amount'))
                    ->money('IDR')
                    ->alignRight()
                    ->sortable(),
                TextColumn::make('due_in_days')
                    ->label(__('filament.fee_templates.table.due_in_days'))
                    ->suffix(' ' . __('filament.fee_templates.table.days')),
                IconColumn::make('is_active')
                    ->label(__('filament.fee_templates.table.is_active'))
                    ->boolean(),
                CreatedAtColumn::make(),
            ])
            ->filters([
                SelectFilter::make('academic_year_id')
                    ->label(__('filament.fee_templates.filters.academic_year'))
                    ->relationship('academicYear', 'name'),
                SelectFilter::make('grade_level')
                    ->label(__('filament.fee_templates.filters.grade_level'))
                    ->options(GradeLevel::options()),
                SelectFilter::make('type')
                    ->label(__('filament.fee_templates.filters.type'))
                    ->options(FeeType::options()),
                TernaryFilter::make('is_active')
                    ->label(__('filament.fee_templates.filters.is_active')),
                TrashedFilter::make()
                    ->label(__('filament.fee_templates.filters.trashed')),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
