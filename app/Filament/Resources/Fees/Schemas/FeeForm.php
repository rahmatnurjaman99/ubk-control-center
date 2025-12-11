<?php

declare(strict_types=1);

namespace App\Filament\Resources\Fees\Schemas;

use App\Enums\FeeStatus;
use App\Enums\FeeType;
use App\Models\Fee;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Number;

class FeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::getPrimarySection(),
                self::getScheduleSection(),
                self::getMetaSection(),
            ])
            ->columns(1);
    }

    private static function getPrimarySection(): Section
    {
        return Section::make(__('filament.fees.fields.title'))
            ->columns(2)
            ->schema([
                TextInput::make('reference')
                    ->label(__('filament.fees.fields.reference'))
                    ->default(fn(): string => Fee::generateReference())
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->maxLength(50)
                    ->readOnly(),
                TextInput::make('title')
                    ->label(__('filament.fees.fields.title'))
                    ->required()
                    ->maxLength(255),
                Select::make('type')
                    ->label(__('filament.fees.fields.type'))
                    ->options(FeeType::options())
                    ->enum(FeeType::class)
                    ->native(false)
                    ->required(),
                Select::make('student_id')
                    ->label(__('filament.fees.fields.student'))
                    ->relationship('student', 'full_name')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->required()
                    ->disabled(fn(?Fee $record): bool => filled($record)),
                Select::make('academic_year_id')
                    ->label(__('filament.fees.fields.academic_year'))
                    ->relationship('academicYear', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->nullable(),
                TextInput::make('amount')
                    ->label(__('filament.fees.fields.amount'))
                    ->numeric()
                    ->minValue(0)
                    ->prefix('IDR')
                    ->required(),
                TextInput::make('paid_amount')
                    ->label(__('filament.fees.fields.paid_amount'))
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(fn(callable $get): float => (float) ($get('amount') ?? 0))
                    ->default(0)
                    ->prefix('IDR'),
                TextInput::make('currency')
                    ->label(__('filament.fees.fields.currency'))
                    ->default('IDR')
                    ->maxLength(3)
                    ->readOnly()
                    ->required(),
            ]);
    }

    private static function getScheduleSection(): Section
    {
        return Section::make(__('filament.fees.fields.due_date'))
            ->columns(2)
            ->schema([
                DatePicker::make('due_date')
                    ->label(__('filament.fees.fields.due_date'))
                    ->native(false),
                Select::make('status')
                    ->label(__('filament.fees.fields.status'))
                    ->options(FeeStatus::options())
                    ->enum(FeeStatus::class)
                    ->default(FeeStatus::Pending->value)
                    ->native(false)
                    ->required()
                    ->disabled(),
                DatePicker::make('paid_at')
                    ->label(__('filament.fees.fields.paid_at'))
                    ->native(false),
                TextEntry::make('outstanding_amount_display')
                    ->label(__('filament.fees.fields.outstanding_amount'))
                    ->state(fn(Get $get, ?Fee $record): string => Number::currency(
                        max(
                            (float) ($get('amount') ?? $record?->amount ?? 0)
                                - (float) ($get('paid_amount') ?? $record?->paid_amount ?? 0),
                            0,
                        ),
                        $get('currency') ?? $record?->currency ?? 'IDR',
                    ))
                    ->columnSpanFull(),
            ]);
    }

    private static function getMetaSection(): Section
    {
        return Section::make(__('filament.fees.fields.description'))
            ->schema([
                Textarea::make('description')
                    ->label(__('filament.fees.fields.description'))
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }
}
