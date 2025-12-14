<?php

declare(strict_types=1);

namespace App\Filament\Resources\RegistrationIntakes\Tables;

use App\Enums\RegistrationStatus;
use App\Support\Tables\Columns\CreatedAtColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class RegistrationIntakesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('form_number')
                    ->label(__('filament.registration_intakes.table.form_number'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('guardian_name')
                    ->label(__('filament.registration_intakes.table.guardian'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('student_full_name')
                    ->label(__('filament.registration_intakes.table.student'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('filament.registration_intakes.table.status'))
                    ->badge()
                    ->color(fn (?RegistrationStatus $state): ?string => $state?->getColor())
                    ->formatStateUsing(fn (?RegistrationStatus $state): ?string => $state?->getLabel())
                    ->sortable(),
                TextColumn::make('payment_amount')
                    ->label(__('filament.registration_intakes.table.payment_amount'))
                    ->money('IDR')
                    ->sortable()
                    ->alignRight(),
                TextColumn::make('payment_received_at')
                    ->label(__('filament.registration_intakes.table.payment_received_at'))
                    ->date()
                    ->sortable()
                    ->placeholder('-'),
                CreatedAtColumn::make(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('filament.registration_intakes.filters.status'))
                    ->options(RegistrationStatus::options()),
                SelectFilter::make('academic_year_id')
                    ->label(__('filament.registration_intakes.filters.academic_year'))
                    ->relationship('academicYear', 'name'),
                TrashedFilter::make()
                    ->label(__('filament.registration_intakes.filters.trashed')),
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
