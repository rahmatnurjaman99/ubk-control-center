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
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

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
                    ->default(fn (): string => Fee::generateReference())
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->maxLength(50),
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
                    ->required(),
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
                TextInput::make('currency')
                    ->label(__('filament.fees.fields.currency'))
                    ->default('IDR')
                    ->maxLength(3)
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
                    ->required(),
                DatePicker::make('paid_at')
                    ->label(__('filament.fees.fields.paid_at'))
                    ->native(false),
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
