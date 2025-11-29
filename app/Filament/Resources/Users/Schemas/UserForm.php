<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::getNameComponent(),
                self::getEmailComponent(),
                self::getStatusComponent(),
                self::getEmailVerifiedAtComponent(),
                self::getPasswordComponent(),
                self::getPasswordConfirmationComponent(),
                self::getRolesComponent(),
                self::getAvatarComponent(),
            ])
            ->columns(2);
    }

    private static function getNameComponent(): TextInput
    {
        return TextInput::make('name')
            ->label(__('filament.users.fields.name'))
            ->maxLength(255)
            ->required();
    }

    private static function getEmailComponent(): TextInput
    {
        return TextInput::make('email')
            ->label(__('filament.users.fields.email'))
            ->email()
            ->maxLength(255)
            ->unique(ignoreRecord: true)
            ->required();
    }

    private static function getStatusComponent(): Select
    {
        return Select::make('status')
            ->label(__('filament.users.fields.status'))
            ->options(UserStatus::class)
            ->enum(UserStatus::class)
            ->native(false)
            ->default(UserStatus::Active->value)
            ->required();
    }

    private static function getEmailVerifiedAtComponent(): DateTimePicker
    {
        return DateTimePicker::make('email_verified_at')
            ->label(__('filament.users.fields.email_verified_at'))
            ->seconds(false);
    }

    private static function getPasswordComponent(): TextInput
    {
        return TextInput::make('password')
            ->label(__('filament.users.fields.password'))
            ->password()
            ->revealable()
            ->confirmed()
            ->maxLength(255)
            ->required(fn (string $operation): bool => $operation === 'create')
            ->dehydrated(fn (?string $state): bool => filled($state))
            ->dehydrateStateUsing(
                fn (?string $state): ?string => filled($state) ? Hash::make($state) : null
            );
    }

    private static function getPasswordConfirmationComponent(): TextInput
    {
        return TextInput::make('password_confirmation')
            ->label(__('filament.users.fields.password_confirmation'))
            ->password()
            ->revealable()
            ->required(fn (string $operation): bool => $operation === 'create')
            ->dehydrated(false);
    }

    private static function getRolesComponent(): Select
    {
        return Select::make('roles')
            ->label(__('filament.users.fields.roles'))
            ->relationship('roles', 'name')
            ->multiple()
            ->preload()
            ->searchable();
    }

    private static function getAvatarComponent(): FileUpload
    {
        return FileUpload::make('avatar')
            ->label(__('filament.users.fields.avatar'))
            ->avatar()
            ->image()
            ->directory('avatars')
            ->disk('public')
            ->visibility('public')
            ->maxSize(2048)
            ->imageEditor();
    }
}
