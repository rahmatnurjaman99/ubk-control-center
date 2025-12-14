<?php

declare(strict_types=1);

namespace App\Filament\Resources\ScholarshipAssignments\Tables;

use App\Models\ScholarshipAssignment;
use App\Support\Tables\Columns\CreatedAtColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ScholarshipAssignmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('scholarship.name')
                    ->label(__('filament.scholarship_assignments.fields.scholarship'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('student.full_name')
                    ->label(__('filament.scholarship_assignments.fields.student'))
                    ->description(fn (ScholarshipAssignment $record): ?string => $record->student?->student_number)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('effective_from')
                    ->label(__('filament.scholarship_assignments.fields.effective_from'))
                    ->date()
                    ->sortable(),
                TextColumn::make('effective_until')
                    ->label(__('filament.scholarship_assignments.fields.effective_until'))
                    ->date()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label(__('filament.scholarship_assignments.fields.is_active'))
                    ->boolean()
                    ->sortable(),
                CreatedAtColumn::make(),
            ])
            ->filters([
                SelectFilter::make('scholarship_id')
                    ->label(__('filament.scholarship_assignments.filters.scholarship'))
                    ->relationship('scholarship', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('student_id')
                    ->label(__('filament.scholarship_assignments.filters.student'))
                    ->relationship('student', 'full_name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('is_active')
                    ->label(__('filament.scholarship_assignments.filters.status'))
                    ->options([
                        '1' => __('filament.scholarship_assignments.statuses.active'),
                        '0' => __('filament.scholarship_assignments.statuses.inactive'),
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
