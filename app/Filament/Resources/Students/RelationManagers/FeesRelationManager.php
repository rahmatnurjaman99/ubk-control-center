<?php

declare(strict_types=1);

namespace App\Filament\Resources\Students\RelationManagers;

use App\Enums\FeeStatus;
use App\Models\Fee;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Query\Builder as QueryBuilder;

class FeesRelationManager extends RelationManager
{
    protected static string $relationship = 'fees';

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('filament.students.fees.heading'))
            ->defaultSort('due_date', 'desc')
            ->columns([
                TextColumn::make('reference')
                    ->label(__('filament.fees.table.reference'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('title')
                    ->label(__('filament.fees.table.title'))
                    ->wrap()
                    ->toggleable(),
                TextColumn::make('type')
                    ->label(__('filament.fees.table.type'))
                    ->badge()
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('amount')
                    ->label(__('filament.fees.table.amount'))
                    ->money('IDR')
                    ->summarize([
                        Sum::make('total_amount')
                            ->label(__('filament.students.fees.total_amount'))
                            ->money('IDR'),
                    ])
                    ->sortable()
                    ->alignRight(),
                TextColumn::make('paid_amount')
                    ->label(__('filament.fees.table.paid_amount'))
                    ->money('IDR')
                    ->alignRight()
                    ->summarize([
                        Sum::make('paid_amount')
                            ->label(__('filament.students.fees.total_paid'))
                            ->money('IDR'),
                    ])
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('outstanding_amount')
                    ->label(__('filament.fees.fields.outstanding_amount'))
                    ->state(fn (Fee $record): float => $record->outstanding_amount)
                    ->money('IDR')
                    ->alignRight()
                    ->summarize([
                        Summarizer::make()
                            ->label(__('filament.students.fees.outstanding_amount'))
                            ->using(function (QueryBuilder $query): float {
                                return (float) $query
                                    ->whereIn('status', [
                                        FeeStatus::Pending->value,
                                        FeeStatus::Partial->value,
                                    ])
                                    ->selectRaw('SUM(GREATEST(amount - COALESCE(paid_amount, 0), 0)) as outstanding')
                                    ->value('outstanding');
                            })
                            ->money('IDR'),
                    ])
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('status')
                    ->label(__('filament.fees.table.status'))
                    ->formatStateUsing(
                        fn(FeeStatus|string|null $state): ?string => match (true) {
                            $state instanceof FeeStatus => $state->getLabel(),
                            blank($state) => null,
                            default => FeeStatus::from((string) $state)->getLabel(),
                        },
                    )
                    ->badge()
                    ->color(
                        fn(FeeStatus|string|null $state): ?string => match (true) {
                            $state instanceof FeeStatus => $state->getColor(),
                            blank($state) => null,
                            default => FeeStatus::from((string) $state)->getColor(),
                        },
                    )
                    ->sortable(),
                TextColumn::make('due_date')
                    ->label(__('filament.fees.table.due_date'))
                    ->date()
                    ->sortable(),
                TextColumn::make('paid_at')
                    ->label(__('filament.fees.table.paid_at'))
                    ->dateTime()
                    ->placeholder('-')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('filament.fees.filters.status'))
                    ->options(FeeStatus::options()),
                TrashedFilter::make()
                    ->label(__('filament.fees.filters.trashed')),
            ]);
    }
}
