<?php

declare(strict_types=1);

namespace App\Filament\Resources\Guardians\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GuardianForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::getUserComponent(),
                self::getGuardianNumberComponent(),
                self::getFullNameComponent(),
                self::getRelationshipComponent(),
                self::getPhoneComponent(),
                self::getEmailComponent(),
                self::getOccupationComponent(),
                self::getAddressComponent(),
                self::getLegacyReferenceComponent(),
            ])
            ->columns(2);
    }

    private static function getUserComponent(): Select
    {
        return Select::make('user_id')
            ->label(__('filament.guardians.fields.user'))
            ->relationship('user', 'name')
            ->searchable()
            ->preload()
            ->nullable()
            ->columnSpan(1);
    }

    private static function getGuardianNumberComponent(): TextInput
    {
        return TextInput::make('guardian_number')
            ->label(__('filament.guardians.fields.guardian_number'))
            ->required()
            ->unique(ignoreRecord: true)
            ->maxLength(50);
    }

    private static function getFullNameComponent(): TextInput
    {
        return TextInput::make('full_name')
            ->label(__('filament.guardians.fields.full_name'))
            ->required()
            ->maxLength(255);
    }

    private static function getRelationshipComponent(): TextInput
    {
        return TextInput::make('relationship')
            ->label(__('filament.guardians.fields.relationship'))
            ->required()
            ->maxLength(255);
    }

    private static function getPhoneComponent(): TextInput
    {
        return TextInput::make('phone')
            ->label(__('filament.guardians.fields.phone'))
            ->tel()
            ->maxLength(30);
    }

    private static function getEmailComponent(): TextInput
    {
        return TextInput::make('email')
            ->label(__('filament.guardians.fields.email'))
            ->email()
            ->maxLength(255);
    }

    private static function getOccupationComponent(): TextInput
    {
        return TextInput::make('occupation')
            ->label(__('filament.guardians.fields.occupation'))
            ->maxLength(255);
    }

    private static function getAddressComponent(): Textarea
    {
        return Textarea::make('address')
            ->label(__('filament.guardians.fields.address'))
            ->rows(3)
            ->columnSpanFull();
    }

    private static function getLegacyReferenceComponent(): TextInput
    {
        return TextInput::make('legacy_reference')
            ->label(__('filament.guardians.fields.legacy_reference'))
            ->maxLength(255);
    }
}
