<?php

declare(strict_types=1);

namespace App\Filament\Resources\Transactions\Tables;

use App\Models\Fee;
use App\Models\Transaction;
use App\Enums\PaymentStatus;
use App\Enums\TransactionType;
use App\Support\Tables\Columns\CreatedAtColumn;
use Filament\Actions\Action as TableAction;
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

class TransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('paid_at', 'desc')
            ->columns([
                TextColumn::make('reference')
                    ->label(__('filament.transactions.table.reference'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('label')
                    ->label(__('filament.transactions.table.label'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label(__('filament.transactions.table.type'))
                    ->badge()
                    ->formatStateUsing(fn (?TransactionType $state): ?string => $state?->getLabel())
                    ->color(fn (?TransactionType $state): ?string => $state?->getColor())
                    ->sortable(),
                TextColumn::make('category')
                    ->label(__('filament.transactions.table.category'))
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('amount')
                    ->label(__('filament.transactions.table.amount'))
                    ->money('IDR')
                    ->alignRight()
                    ->sortable(),
                TextColumn::make('scholarship_discounts')
                    ->label(__('filament.transactions.table.scholarships'))
                    ->state(fn (Transaction $record): array => self::formatScholarshipState($record))
                    ->listWithLineBreaks()
                    ->toggleable(),
                TextColumn::make('payment_status')
                    ->label(__('filament.transactions.table.payment_status'))
                    ->badge()
                    ->formatStateUsing(fn (?PaymentStatus $state): ?string => $state?->getLabel())
                    ->color(fn (?PaymentStatus $state): ?string => $state?->getColor())
                    ->sortable(),
                TextColumn::make('paid_at')
                    ->label(__('filament.transactions.table.paid_at'))
                    ->date()
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('recorder.name')
                    ->label(__('filament.transactions.fields.recorded_by'))
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('academicYear.name')
                    ->label(__('filament.transactions.fields.academic_year'))
                    ->sortable()
                    ->toggleable(),
                CreatedAtColumn::make(),
                TextColumn::make('updated_at')
                    ->label(__('filament.transactions.table.updated_at'))
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label(__('filament.transactions.filters.type'))
                    ->options(TransactionType::options()),
                SelectFilter::make('payment_status')
                    ->label(__('filament.transactions.filters.payment_status'))
                    ->options(PaymentStatus::options()),
                SelectFilter::make('academic_year_id')
                    ->label(__('filament.transactions.filters.academic_year'))
                    ->relationship('academicYear', 'name'),
                TrashedFilter::make()
                    ->label(__('filament.transactions.filters.trashed')),
            ])
            ->recordActions([
                EditAction::make(),
                TableAction::make('receipt')
                    ->label(__('Print receipt'))
                    ->icon('heroicon-o-printer')
                    ->url(fn($record): string => route('receipts.transactions.show', $record))
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

    /**
     * @return list<string>
     */
    private static function formatScholarshipState(Transaction $transaction): array
    {
        return $transaction->scholarshipFees()
            ->map(fn (Fee $fee): ?string => self::formatScholarshipLine($fee))
            ->filter()
            ->values()
            ->all();
    }

    private static function formatScholarshipLine(Fee $fee): ?string
    {
        $discount = $fee->formattedScholarshipDiscount();

        if ($discount === null) {
            return null;
        }

        $title = $fee->title ?? __('filament.fees.model.singular');

        return sprintf('%s — %s', $title, $discount);
    }
}
