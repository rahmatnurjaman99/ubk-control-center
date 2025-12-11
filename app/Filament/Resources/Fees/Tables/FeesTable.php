<?php

declare(strict_types=1);

namespace App\Filament\Resources\Fees\Tables;

use App\Enums\FeeStatus;
use App\Enums\FeeType;
use Filament\Actions\Action as TableAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class FeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('due_date')
            ->columns([
                TextColumn::make('reference')
                    ->label(__('filament.fees.table.reference'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('title')
                    ->label(__('filament.fees.table.title'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('student.full_name')
                    ->label(__('filament.fees.table.student'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label(__('filament.fees.table.type'))
                    ->badge()
                    ->formatStateUsing(fn (?FeeType $state): ?string => $state?->getLabel())
                    ->color(fn (?FeeType $state): ?string => $state?->getColor())
                    ->sortable(),
                TextColumn::make('amount')
                    ->label(__('filament.fees.table.amount'))
                    ->money('IDR')
                    ->alignRight()
                    ->sortable(),
                TextColumn::make('paid_amount')
                    ->label(__('filament.fees.table.paid_amount'))
                    ->money('IDR')
                    ->alignRight()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('outstanding_amount')
                    ->label(__('filament.fees.table.outstanding'))
                    ->state(fn ($record): float => (float) $record->outstanding_amount)
                    ->money('IDR')
                    ->alignRight()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('status')
                    ->label(__('filament.fees.table.status'))
                    ->badge()
                    ->formatStateUsing(fn (?FeeStatus $state): ?string => $state?->getLabel())
                    ->color(fn (?FeeStatus $state): ?string => $state?->getColor())
                    ->sortable(),
                TextColumn::make('due_date')
                    ->label(__('filament.fees.table.due_date'))
                    ->date()
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('paid_at')
                    ->label(__('filament.fees.table.paid_at'))
                    ->date()
                    ->sortable()
                    ->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label(__('filament.fees.filters.type'))
                    ->options(FeeType::options()),
                SelectFilter::make('student_id')
                    ->label(__('filament.fees.filters.student'))
                    ->relationship(
                        name: 'student',
                        titleAttribute: 'full_name',
                        modifyQueryUsing: fn ($query) => $query
                            ->select(['id', 'full_name', 'student_number'])
                            ->orderBy('full_name')
                    )
                    ->searchable()
                    ->preload()
                    ->getOptionLabelFromRecordUsing(function ($record): string {
                        $number = $record->student_number ?? null;

                        return $number === null
                            ? $record->full_name
                            : sprintf('%s - %s', $number, $record->full_name);
                    }),
                SelectFilter::make('status')
                    ->label(__('filament.fees.filters.status'))
                    ->options(FeeStatus::options())
                    ->multiple(),
                SelectFilter::make('academic_year_id')
                    ->label(__('filament.fees.filters.academic_year'))
                    ->relationship('academicYear', 'name'),
                TrashedFilter::make()
                    ->label(__('filament.fees.filters.trashed')),
            ])
            ->recordActions([
                EditAction::make(),
                TableAction::make('receipt')
                    ->label(__('Print receipt'))
                    ->icon('heroicon-o-printer')
                    ->url(fn($record): string => route('receipts.fees.show', $record))
                    ->openUrlInNewTab(),
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
