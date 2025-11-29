<?php

declare(strict_types=1);

namespace App\Filament\Resources\AcademicYears\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AcademicYearInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::getCodeEntry(),
                self::getNameEntry(),
                self::getStartsOnEntry(),
                self::getEndsOnEntry(),
                self::getIsCurrentEntry(),
                self::getCreatedAtEntry(),
                self::getUpdatedAtEntry(),
            ]);
    }

    private static function getCodeEntry(): TextEntry
    {
        return TextEntry::make('code')
            ->label(__('filament.academic_years.fields.code'))
            ->weight('medium');
    }

    private static function getNameEntry(): TextEntry
    {
        return TextEntry::make('name')
            ->label(__('filament.academic_years.fields.name'))
            ->weight('medium');
    }

    private static function getStartsOnEntry(): TextEntry
    {
        return TextEntry::make('starts_on')
            ->label(__('filament.academic_years.fields.starts_on'))
            ->date();
    }

    private static function getEndsOnEntry(): TextEntry
    {
        return TextEntry::make('ends_on')
            ->label(__('filament.academic_years.fields.ends_on'))
            ->date();
    }

    private static function getIsCurrentEntry(): IconEntry
    {
        return IconEntry::make('is_current')
            ->label(__('filament.academic_years.table.current'))
            ->boolean();
    }

    private static function getCreatedAtEntry(): TextEntry
    {
        return TextEntry::make('created_at')
            ->label(__('filament.academic_years.table.created_at'))
            ->dateTime();
    }

    private static function getUpdatedAtEntry(): TextEntry
    {
        return TextEntry::make('updated_at')
            ->label(__('filament.academic_years.table.updated_at'))
            ->dateTime();
    }
}
