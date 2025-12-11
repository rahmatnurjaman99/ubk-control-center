<?php

declare(strict_types=1);

namespace App\Filament\Resources\SalaryStructures\Tables;

use App\Models\SalaryStructure;
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
use Illuminate\Support\Number;

class SalaryStructuresTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('effective_date', 'desc')
            ->columns([
                TextColumn::make('staff.staff_name')
                    ->label(__('filament.salary_structures.table.staff'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('title')
                    ->label(__('filament.salary_structures.table.title'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('base_salary')
                    ->label(__('filament.salary_structures.table.base_salary'))
                    ->state(fn (SalaryStructure $record): float => (float) $record->base_salary)
                    ->formatStateUsing(fn (SalaryStructure $record): string => Number::currency(
                        (float) $record->base_salary,
                        $record->currency ?? 'IDR',
                    ))
                    ->alignRight()
                    ->sortable(),
                TextColumn::make('allowances_total')
                    ->label(__('filament.salary_structures.table.allowances_total'))
                    ->state(fn (SalaryStructure $record): float => (float) $record->allowances_total)
                    ->formatStateUsing(fn (SalaryStructure $record): string => Number::currency(
                        (float) $record->allowances_total,
                        $record->currency ?? 'IDR',
                    ))
                    ->alignRight()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('deductions_total')
                    ->label(__('filament.salary_structures.table.deductions_total'))
                    ->state(fn (SalaryStructure $record): float => (float) $record->deductions_total)
                    ->formatStateUsing(fn (SalaryStructure $record): string => Number::currency(
                        (float) $record->deductions_total,
                        $record->currency ?? 'IDR',
                    ))
                    ->alignRight()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('net_amount')
                    ->label(__('filament.salary_structures.table.net_amount'))
                    ->state(fn (SalaryStructure $record): float => $record->calculateNetAmount())
                    ->formatStateUsing(fn (SalaryStructure $record): string => Number::currency(
                        $record->calculateNetAmount(),
                        $record->currency ?? 'IDR',
                    ))
                    ->alignRight()
                    ->sortable(),
                TextColumn::make('effective_date')
                    ->label(__('filament.salary_structures.table.effective_date'))
                    ->date()
                    ->sortable(),
                TextColumn::make('expires_on')
                    ->label(__('filament.salary_structures.table.expires_on'))
                    ->date()
                    ->sortable()
                    ->placeholder('-'),
                IconColumn::make('is_active')
                    ->label(__('filament.salary_structures.table.is_active'))
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('staff_id')
                    ->label(__('filament.salary_structures.filters.staff'))
                    ->relationship('staff', 'staff_name'),
                SelectFilter::make('academic_year_id')
                    ->label(__('filament.salary_structures.filters.academic_year'))
                    ->relationship('academicYear', 'name'),
                TernaryFilter::make('is_active')
                    ->label(__('filament.salary_structures.filters.is_active')),
                TrashedFilter::make()
                    ->label(__('filament.salary_structures.filters.trashed')),
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
