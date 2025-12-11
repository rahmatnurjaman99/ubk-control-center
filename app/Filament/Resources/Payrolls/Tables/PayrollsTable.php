<?php

declare(strict_types=1);

namespace App\Filament\Resources\Payrolls\Tables;

use App\Enums\PayrollStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Number;

class PayrollsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('period_start', 'desc')
            ->columns([
                TextColumn::make('reference')
                    ->label(__('filament.payrolls.table.reference'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('title')
                    ->label(__('filament.payrolls.table.title'))
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('status')
                    ->label(__('filament.payrolls.table.status'))
                    ->badge()
                    ->formatStateUsing(fn (?PayrollStatus $state): ?string => $state?->getLabel())
                    ->color(fn (?PayrollStatus $state): ?string => $state?->getColor())
                    ->sortable(),
                TextColumn::make('academicYear.name')
                    ->label(__('filament.payrolls.table.academic_year'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('period_start')
                    ->label(__('filament.payrolls.table.period_start'))
                    ->date()
                    ->sortable(),
                TextColumn::make('period_end')
                    ->label(__('filament.payrolls.table.period_end'))
                    ->date()
                    ->sortable(),
                TextColumn::make('total_base_salary')
                    ->label(__('filament.payrolls.table.total_base_salary'))
                    ->formatStateUsing(fn ($state, $record): string => Number::currency(
                        (float) $record->total_base_salary,
                        $record->currency ?? 'IDR',
                    ))
                    ->alignRight()
                    ->sortable(),
                TextColumn::make('total_allowances')
                    ->label(__('filament.payrolls.table.total_allowances'))
                    ->formatStateUsing(fn ($state, $record): string => Number::currency(
                        (float) $record->total_allowances,
                        $record->currency ?? 'IDR',
                    ))
                    ->alignRight()
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('total_deductions')
                    ->label(__('filament.payrolls.table.total_deductions'))
                    ->formatStateUsing(fn ($state, $record): string => Number::currency(
                        (float) $record->total_deductions,
                        $record->currency ?? 'IDR',
                    ))
                    ->alignRight()
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('total_net')
                    ->label(__('filament.payrolls.table.total_net'))
                    ->formatStateUsing(fn ($state, $record): string => Number::currency(
                        (float) $record->total_net,
                        $record->currency ?? 'IDR',
                    ))
                    ->alignRight()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('filament.payrolls.filters.status'))
                    ->options(PayrollStatus::options()),
                SelectFilter::make('academic_year_id')
                    ->label(__('filament.payrolls.filters.academic_year'))
                    ->relationship('academicYear', 'name'),
                self::getPeriodFilter(),
                TrashedFilter::make()
                    ->label(__('filament.payrolls.filters.trashed')),
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

    private static function getPeriodFilter(): Filter
    {
        return Filter::make('period')
            ->label(__('filament.payrolls.filters.period'))
            ->form([
                DatePicker::make('starts_from')
                    ->label(__('filament.payrolls.filters.starts_from')),
                DatePicker::make('ends_until')
                    ->label(__('filament.payrolls.filters.ends_until')),
            ])
            ->columns(2)
            ->query(function (Builder $query, array $data): Builder {
                return $query
                    ->when(
                        $data['starts_from'] ?? null,
                        fn (Builder $q, string $date): Builder => $q->whereDate('period_start', '>=', $date),
                    )
                    ->when(
                        $data['ends_until'] ?? null,
                        fn (Builder $q, string $date): Builder => $q->whereDate('period_end', '<=', $date),
                    );
            })
            ->indicateUsing(function (array $data): array {
                $indicators = [];

                if (! empty($data['starts_from'])) {
                    $indicators[] = __('filament.payrolls.filters.starts_from_indicator', [
                        'date' => $data['starts_from'],
                    ]);
                }

                if (! empty($data['ends_until'])) {
                    $indicators[] = __('filament.payrolls.filters.ends_until_indicator', [
                        'date' => $data['ends_until'],
                    ]);
                }

                return $indicators;
            });
    }
}
