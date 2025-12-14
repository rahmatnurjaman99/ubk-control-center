<?php

declare(strict_types=1);

namespace App\Filament\Resources\Scholarships\Tables;

use App\Enums\ScholarshipType;
use App\Models\Scholarship;
use App\Support\Tables\Columns\CreatedAtColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ScholarshipsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament.scholarships.fields.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('code')
                    ->label(__('filament.scholarships.fields.code'))
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('type')
                    ->label(__('filament.scholarships.fields.type'))
                    ->badge()
                    ->formatStateUsing(fn (?ScholarshipType $state): ?string => $state?->label())
                    ->sortable(),
                TextColumn::make('amount')
                    ->label(__('filament.scholarships.fields.amount'))
                    ->state(fn (Scholarship $record): string => $record->type === ScholarshipType::Percentage
                        ? number_format((float) $record->amount, 0) . '%'
                        : 'IDR ' . number_format((float) $record->amount, 0))
                    ->sortable(),
                TextColumn::make('period')
                    ->label(__('filament.scholarships.fields.period'))
                    ->state(fn (Scholarship $record): string => self::formatPeriod($record))
                    ->toggleable(),
                IconColumn::make('is_active')
                    ->label(__('filament.scholarships.fields.is_active'))
                    ->boolean()
                    ->sortable(),
                CreatedAtColumn::make(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label(__('filament.scholarships.fields.type'))
                    ->options(ScholarshipType::options()),
                SelectFilter::make('is_active')
                    ->label(__('filament.scholarships.filters.status'))
                    ->options([
                        '1' => __('filament.scholarships.statuses.active'),
                        '0' => __('filament.scholarships.statuses.inactive'),
                    ]),
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    private static function formatPeriod(Scholarship $record): string
    {
        $start = $record->starts_on?->format('d M Y') ?? '—';
        $end = $record->ends_on?->format('d M Y') ?? '—';

        return "{$start} → {$end}";
    }
}
