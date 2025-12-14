<?php

declare(strict_types=1);

namespace App\Filament\Resources\AcademicYears\Tables;

use App\Support\Tables\Columns\CreatedAtColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AcademicYearsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                self::getCodeColumn(),
                self::getNameColumn(),
                self::getStartsOnColumn(),
                self::getEndsOnColumn(),
                self::getIsCurrentColumn(),
                self::getCreatedAtColumn(),
                self::getUpdatedAtColumn(),
            ])
            ->defaultSort('starts_on', 'desc')
            ->filters([
                self::getCurrentFilter(),
                self::getDateRangeFilter(),
                self::getTrashedFilter(),
            ])
            ->recordActions([
                self::getViewAction(),
                self::getEditAction(),
                self::getDeleteAction(),
            ])
            ->toolbarActions([
                self::getBulkActionGroup(),
            ]);
    }

    private static function getCodeColumn(): TextColumn
    {
        return TextColumn::make('code')
            ->label(__('filament.academic_years.table.code'))
            ->searchable()
            ->sortable();
    }

    private static function getNameColumn(): TextColumn
    {
        return TextColumn::make('name')
            ->label(__('filament.academic_years.table.name'))
            ->searchable()
            ->sortable();
    }

    private static function getStartsOnColumn(): TextColumn
    {
        return TextColumn::make('starts_on')
            ->label(__('filament.academic_years.table.starts_on'))
            ->date()
            ->sortable();
    }

    private static function getEndsOnColumn(): TextColumn
    {
        return TextColumn::make('ends_on')
            ->label(__('filament.academic_years.table.ends_on'))
            ->date()
            ->sortable();
    }

    private static function getIsCurrentColumn(): IconColumn
    {
        return IconColumn::make('is_current')
            ->label(__('filament.academic_years.table.current'))
            ->boolean();
    }

    private static function getCreatedAtColumn(): TextColumn
    {
        return CreatedAtColumn::make()
            ->label(__('filament.academic_years.table.created_at'))
            ->toggleable(isToggledHiddenByDefault: true);
    }

    private static function getUpdatedAtColumn(): TextColumn
    {
        return TextColumn::make('updated_at')
            ->label(__('filament.academic_years.table.updated_at'))
            ->dateTime()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }

    private static function getCurrentFilter(): TernaryFilter
    {
        return TernaryFilter::make('is_current')
            ->label(__('filament.academic_years.filters.current'));
    }

    private static function getDateRangeFilter(): Filter
    {
        return Filter::make('period')
            ->label(__('filament.academic_years.filters.date_range'))
            ->form([
                DatePicker::make('starts_from')
                    ->label(__('filament.academic_years.filters.starts_from_label')),
                DatePicker::make('ends_until')
                    ->label(__('filament.academic_years.filters.ends_until_label')),
            ])
            ->columns(2)
            ->query(function (Builder $query, array $data): Builder {
                return $query
                    ->when($data['starts_from'] ?? null, fn (Builder $q, string $date): Builder => $q->whereDate('starts_on', '>=', $date))
                    ->when($data['ends_until'] ?? null, fn (Builder $q, string $date): Builder => $q->whereDate('ends_on', '<=', $date));
            })
            ->indicateUsing(function (array $data): array {
                $indicators = [];

                if (! empty($data['starts_from'])) {
                    $indicators[] = __('filament.academic_years.filters.starts_from', ['date' => $data['starts_from']]);
                }

                if (! empty($data['ends_until'])) {
                    $indicators[] = __('filament.academic_years.filters.ends_until', ['date' => $data['ends_until']]);
                }

                return $indicators;
            });
    }

    private static function getTrashedFilter(): TrashedFilter
    {
        return TrashedFilter::make();
    }

    private static function getViewAction(): ViewAction
    {
        return ViewAction::make();
    }

    private static function getEditAction(): EditAction
    {
        return EditAction::make();
    }

    private static function getDeleteAction(): DeleteAction
    {
        return DeleteAction::make();
    }

    private static function getBulkActionGroup(): BulkActionGroup
    {
        return BulkActionGroup::make([
            self::getDeleteBulkAction(),
            self::getForceDeleteBulkAction(),
            self::getRestoreBulkAction(),
        ]);
    }

    private static function getDeleteBulkAction(): DeleteBulkAction
    {
        return DeleteBulkAction::make();
    }

    private static function getForceDeleteBulkAction(): ForceDeleteBulkAction
    {
        return ForceDeleteBulkAction::make();
    }

    private static function getRestoreBulkAction(): RestoreBulkAction
    {
        return RestoreBulkAction::make();
    }
}
