<?php

declare(strict_types=1);

namespace App\Filament\Resources\Schedules\Tables;

use App\Filament\Resources\Schedules\ScheduleResource;
use App\Support\Tables\Columns\CreatedAtColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class SchedulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('starts_at')
            ->columns([
                TextColumn::make('title')
                    ->label(__('filament.schedules.table.title'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subject.name')
                    ->label(__('filament.schedules.table.subject'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('classroom.name')
                    ->label(__('filament.schedules.table.classroom'))
                    ->sortable(),
                TextColumn::make('teacher.staff_name')
                    ->label(__('filament.schedules.table.teacher'))
                    ->sortable(),
                TextColumn::make('starts_at')
                    ->label(__('filament.schedules.table.starts_at'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('ends_at')
                    ->label(__('filament.schedules.table.ends_at'))
                    ->dateTime()
                    ->sortable(),
                IconColumn::make('is_all_day')
                    ->label(__('filament.schedules.table.is_all_day'))
                    ->boolean(),
                CreatedAtColumn::make(),
            ])
            ->filters([
                SelectFilter::make('subject_id')
                    ->label(__('filament.schedules.filters.subject'))
                    ->relationship('subject', 'name'),
                SelectFilter::make('classroom_id')
                    ->label(__('filament.schedules.filters.classroom'))
                    ->relationship('classroom', 'name'),
                SelectFilter::make('staff_id')
                    ->label(__('filament.schedules.filters.teacher'))
                    ->relationship('teacher', 'staff_name'),
                Filter::make('scheduled_range')
                    ->label(__('filament.schedules.filters.date_range'))
                    ->form([
                        DatePicker::make('from')
                            ->label(__('filament.schedules.filters.from')),
                        DatePicker::make('until')
                            ->label(__('filament.schedules.filters.until')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'] ?? null, fn (Builder $builder, string $date): Builder => $builder->whereDate('starts_at', '>=', $date))
                            ->when($data['until'] ?? null, fn (Builder $builder, string $date): Builder => $builder->whereDate('starts_at', '<=', $date));
                    })
                    ->default(fn (): array => [
                        'from' => Carbon::today()->toDateString(),
                        'until' => Carbon::today()->toDateString(),
                    ]),
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn ($record): bool => ScheduleResource::canEdit($record)),
                DeleteAction::make()
                    ->visible(fn ($record): bool => ScheduleResource::canDelete($record)),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn (): bool => ScheduleResource::canDeleteAny()),
                    ForceDeleteBulkAction::make()
                        ->visible(fn (): bool => ScheduleResource::canForceDeleteAny()),
                    RestoreBulkAction::make()
                        ->visible(fn (): bool => ScheduleResource::canRestoreAny()),
                ])->visible(fn (): bool => ScheduleResource::canDeleteAny()
                    || ScheduleResource::canForceDeleteAny()
                    || ScheduleResource::canRestoreAny()),
            ]);
    }
}
