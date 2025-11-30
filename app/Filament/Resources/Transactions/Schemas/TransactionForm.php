<?php

declare(strict_types=1);

namespace App\Filament\Resources\Transactions\Schemas;

use App\Enums\PaymentStatus;
use App\Enums\TransactionType;
use App\Models\Transaction as TransactionModel;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::getDetailsSection(),
                self::getPaymentSection(),
                self::getMetaSection(),
            ])
            ->columns(1);
    }

    private static function getDetailsSection(): Section
    {
        return Section::make(__('filament.transactions.fields.reference'))
            ->columns(2)
            ->schema([
                TextInput::make('reference')
                    ->label(__('filament.transactions.fields.reference'))
                    ->default(fn (): string => TransactionModel::generateReference())
                    ->maxLength(50)
                    ->unique(ignoreRecord: true)
                    ->required(),
                TextInput::make('label')
                    ->label(__('filament.transactions.fields.label'))
                    ->maxLength(255)
                    ->required(),
                Select::make('type')
                    ->label(__('filament.transactions.fields.type'))
                    ->options(TransactionType::options())
                    ->enum(TransactionType::class)
                    ->native(false)
                    ->required(),
                TextInput::make('category')
                    ->label(__('filament.transactions.fields.category'))
                    ->maxLength(100),
                TextInput::make('amount')
                    ->label(__('filament.transactions.fields.amount'))
                    ->numeric()
                    ->prefix('IDR')
                    ->minValue(0)
                    ->required(),
                TextInput::make('currency')
                    ->label(__('filament.transactions.fields.currency'))
                    ->maxLength(3)
                    ->default('IDR')
                    ->required(),
            ]);
    }

    private static function getPaymentSection(): Section
    {
        return Section::make(__('filament.transactions.fields.payment_status'))
            ->columns(2)
            ->schema([
                Select::make('payment_status')
                    ->label(__('filament.transactions.fields.payment_status'))
                    ->options(PaymentStatus::options())
                    ->enum(PaymentStatus::class)
                    ->native(false)
                    ->default(PaymentStatus::Pending->value)
                    ->required(),
                TextInput::make('payment_method')
                    ->label(__('filament.transactions.fields.payment_method'))
                    ->maxLength(100),
                DatePicker::make('due_date')
                    ->label(__('filament.transactions.fields.due_date'))
                    ->native(false),
                DateTimePicker::make('paid_at')
                    ->label(__('filament.transactions.fields.paid_at'))
                    ->seconds(false)
                    ->native(false),
                Select::make('academic_year_id')
                    ->label(__('filament.transactions.fields.academic_year'))
                    ->relationship('academicYear', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->nullable(),
                Select::make('recorded_by')
                    ->label(__('filament.transactions.fields.recorded_by'))
                    ->relationship('recorder', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->default(fn (): ?int => auth()->id())
                    ->disabled()
                    ->dehydrated(),
            ]);
    }

    private static function getMetaSection(): Section
    {
        return Section::make(__('filament.transactions.fields.notes'))
            ->columns(2)
            ->schema([
                TextInput::make('counterparty_name')
                    ->label(__('filament.transactions.fields.counterparty_name'))
                    ->maxLength(255),
                Textarea::make('notes')
                    ->label(__('filament.transactions.fields.notes'))
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }
}
