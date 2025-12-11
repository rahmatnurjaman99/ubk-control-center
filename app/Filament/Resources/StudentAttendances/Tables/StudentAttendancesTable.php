<?php

declare(strict_types=1);

namespace App\Filament\Resources\StudentAttendances\Tables;

use App\Enums\AttendanceStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class StudentAttendancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('recorded_on', 'desc')
            ->columns([
                TextColumn::make('recorded_on')
                    ->label(__('filament.student_attendances.table.recorded_on'))
                    ->date()
                    ->sortable(),
                TextColumn::make('student.full_name')
                    ->label(__('filament.student_attendances.table.student'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('classroom.name')
                    ->label(__('filament.student_attendances.table.classroom'))
                    ->placeholder('-')
                    ->sortable(),
                SelectColumn::make('status')
                    ->label(__('filament.student_attendances.table.status'))
                    ->options(AttendanceStatus::options())
                    ->selectablePlaceholder(false)
                    ->sortable(),
                TextColumn::make('checked_in_at')
                    ->label(__('filament.student_attendances.table.checked_in_at'))
                    ->dateTime('H:i')
                    ->placeholder('-'),
                TextColumn::make('checked_out_at')
                    ->label(__('filament.student_attendances.table.checked_out_at'))
                    ->dateTime('H:i')
                    ->placeholder('-'),
                TextInputColumn::make('notes')
                    ->label(__('filament.student_attendances.table.notes'))
                    ->placeholder(__('filament.student_attendances.table.notes_placeholder'))
                    ->rules(['nullable', 'string', 'max:1000'])
                    ->extraInputAttributes(['maxlength' => 1000]),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('filament.student_attendances.filters.status'))
                    ->options(AttendanceStatus::options()),
                SelectFilter::make('student_id')
                    ->label(__('filament.student_attendances.filters.student'))
                    ->relationship('student', 'full_name'),
                SelectFilter::make('classroom_id')
                    ->label(__('filament.student_attendances.filters.classroom'))
                    ->relationship('classroom', 'name'),
                Filter::make('recorded_period')
                    ->label(__('filament.student_attendances.filters.recorded_period'))
                    ->form([
                        DatePicker::make('from')
                            ->label(__('filament.student_attendances.filters.from')),
                        DatePicker::make('until')
                            ->label(__('filament.student_attendances.filters.until')),
                    ])
                    ->default(fn (): array => [
                        'from' => Carbon::today()->toDateString(),
                        'until' => Carbon::today()->toDateString(),
                    ])
                    ->indicateUsing(function (array $data): ?string {
                        if (($data['from'] ?? null) && ($data['until'] ?? null)) {
                            return __('filament.student_attendances.filters.recorded_period') . ': ' . $data['from'] . ' - ' . $data['until'];
                        }

                        if ($data['from'] ?? null) {
                            return __('filament.student_attendances.filters.from') . ': ' . $data['from'];
                        }

                        if ($data['until'] ?? null) {
                            return __('filament.student_attendances.filters.until') . ': ' . $data['until'];
                        }

                        return null;
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'] ?? null, fn (Builder $builder, string $date): Builder => $builder->whereDate('recorded_on', '>=', $date))
                            ->when($data['until'] ?? null, fn (Builder $builder, string $date): Builder => $builder->whereDate('recorded_on', '<=', $date));
                    }),
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
