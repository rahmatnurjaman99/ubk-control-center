<?php

declare(strict_types=1);

namespace App\Filament\Resources\Scholarships\Schemas;

use App\Enums\ScholarshipType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ScholarshipForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->label(__('filament.scholarships.fields.name'))
                            ->required()
                            ->maxLength(255),
                        TextInput::make('code')
                            ->label(__('filament.scholarships.fields.code'))
                            ->maxLength(50)
                            ->unique(ignoreRecord: true),
                        Select::make('type')
                            ->label(__('filament.scholarships.fields.type'))
                            ->options(ScholarshipType::options())
                            ->default(ScholarshipType::Percentage->value)
                            ->native(false)
                            ->required(),
                        TextInput::make('amount')
                            ->label(__('filament.scholarships.fields.amount'))
                            ->numeric()
                            ->minValue(0)
                            ->required()
                            ->prefix(fn (Get $get): ?string => ScholarshipType::tryFrom((string) $get('type')) === ScholarshipType::Nominal ? 'IDR' : null)
                            ->suffix(fn (Get $get): ?string => ScholarshipType::tryFrom((string) $get('type')) === ScholarshipType::Percentage ? '%' : null),
                        DatePicker::make('starts_on')
                            ->label(__('filament.scholarships.fields.starts_on'))
                            ->native(false),
                        DatePicker::make('ends_on')
                            ->label(__('filament.scholarships.fields.ends_on'))
                            ->native(false)
                            ->minDate(fn (Get $get) => $get('starts_on')),
                        Toggle::make('is_active')
                            ->label(__('filament.scholarships.fields.is_active'))
                            ->default(true)
                            ->inline(false),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make(__('filament.scholarships.fields.description'))
                    ->schema([
                        Textarea::make('description')
                            ->label(__('filament.scholarships.fields.description'))
                            ->rows(4),
                    ])
                    ->columnSpan(2),
            ]);
    }
}
