<?php

declare(strict_types=1);

namespace App\Filament\Resources\TahfidzTargets\Tables;

use App\Enums\TahfidzTargetStatus;
use App\Support\Quran\QuranOptions;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TahfidzTargetsTable
{
    public static function configure(Table $table, bool $forRelation = false): Table
    {
        return $table
            ->columns(self::getColumns($forRelation))
            ->filters(self::getFilters())
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('assigned_on', 'desc');
    }

    /**
     * @return array<int, \Filament\Tables\Columns\Column>
     */
    private static function getColumns(bool $forRelation): array
    {
        return array_values(array_filter([
            $forRelation ? null : TextColumn::make('student.full_name')
                ->label(__('filament.tahfidz_targets.table.student'))
                ->searchable()
                ->sortable(),
            TextColumn::make('classroom.name')
                ->label(__('filament.tahfidz_targets.table.classroom'))
                ->placeholder('-')
                ->sortable()
                ->searchable(),
            TextColumn::make('range_summary')
                ->label(__('filament.tahfidz_targets.table.range_summary'))
                ->wrap(),
            TextColumn::make('segments_count')
                ->counts('segments')
                ->label(__('filament.tahfidz_targets.table.segments'))
                ->alignCenter(),
            TextColumn::make('target_repetitions')
                ->label(__('filament.tahfidz_targets.table.repetitions'))
                ->alignCenter(),
            TextColumn::make('progress_percentage')
                ->label(__('filament.tahfidz_targets.table.progress'))
                ->suffix('%')
                ->alignCenter()
                ->sortable(),
            BadgeColumn::make('status')
                ->label(__('filament.tahfidz_targets.table.status'))
                ->formatStateUsing(fn (?TahfidzTargetStatus $state): ?string => $state?->label())
                ->colors([
                    'gray' => TahfidzTargetStatus::Assigned->value,
                    'warning' => TahfidzTargetStatus::InProgress->value,
                    'success' => TahfidzTargetStatus::Completed->value,
                    'danger' => TahfidzTargetStatus::Missed->value,
                    'info' => TahfidzTargetStatus::OnHold->value,
                ]),
            TextColumn::make('assigned_on')
                ->label(__('filament.tahfidz_targets.table.assigned_on'))
                ->date()
                ->sortable(),
            TextColumn::make('due_on')
                ->label(__('filament.tahfidz_targets.table.due_on'))
                ->date()
                ->placeholder('-')
                ->sortable(),
            TextColumn::make('logs_count')
                ->counts('logs')
                ->label(__('filament.tahfidz_targets.table.logs_count'))
                ->alignCenter(),
        ]));
    }

    /**
     * @return array<int, \Filament\Tables\Filters\Filter>
     */
    private static function getFilters(): array
    {
        return [
            SelectFilter::make('status')
                ->label(__('filament.tahfidz_targets.filters.status'))
                ->options(TahfidzTargetStatus::options()),
            SelectFilter::make('surah_id')
                ->label(__('filament.tahfidz_targets.filters.surah'))
                ->options(fn (): array => QuranOptions::surahOptions())
                ->searchable(),
        ];
    }
}
