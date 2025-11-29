<?php

declare(strict_types=1);

namespace App\Filament\Resources\AcademicYears\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AcademicYearForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::getCodeComponent(),
                self::getNameComponent(),
                self::getStartsOnComponent(),
                self::getEndsOnComponent(),
                self::getIsCurrentComponent(),
            ])
            ->columns(2);
    }

    private static function getCodeComponent(): TextInput
    {
        return TextInput::make('code')
            ->label(__('filament.academic_years.fields.code'))
            ->helperText(__('filament.academic_years.fields.code_helper'))
            ->maxLength(50)
            ->required()
            ->unique(ignoreRecord: true);
    }

    private static function getNameComponent(): TextInput
    {
        return TextInput::make('name')
            ->label(__('filament.academic_years.fields.name'))
            ->maxLength(255)
            ->required();
    }

    private static function getStartsOnComponent(): DatePicker
    {
        return DatePicker::make('starts_on')
            ->label(__('filament.academic_years.fields.starts_on'))
            ->native(false)
            ->required();
    }

    private static function getEndsOnComponent(): DatePicker
    {
        return DatePicker::make('ends_on')
            ->label(__('filament.academic_years.fields.ends_on'))
            ->native(false)
            ->required()
            ->afterOrEqual('starts_on');
    }

    private static function getIsCurrentComponent(): Toggle
    {
        return Toggle::make('is_current')
            ->label(__('filament.academic_years.fields.is_current'))
            ->helperText(__('filament.academic_years.fields.is_current_helper'))
            ->default(false);
    }
}
