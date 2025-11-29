<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserStatus;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::getAvatarEntry(),
                self::getNameEntry(),
                self::getEmailEntry(),
                self::getRolesEntry(),
                self::getStatusEntry(),
                self::getEmailVerifiedAtEntry(),
                self::getCreatedAtEntry(),
                self::getUpdatedAtEntry(),
            ]);
    }

    private static function getAvatarEntry(): ImageEntry
    {
        return ImageEntry::make('avatar_url')
            ->label(__('filament.users.fields.avatar'))
            ->circular();
    }

    private static function getNameEntry(): TextEntry
    {
        return TextEntry::make('name')
            ->label(__('filament.users.fields.name'))
            ->weight('medium');
    }

    private static function getEmailEntry(): TextEntry
    {
        return TextEntry::make('email')
            ->label(__('filament.users.fields.email'))
            ->copyable()
            ->icon('heroicon-o-envelope');
    }

    private static function getRolesEntry(): TextEntry
    {
        return TextEntry::make('roles.name')
            ->label(__('filament.users.fields.roles'))
            ->badge()
            ->color('primary')
            ->separator(', ')
            ->listWithLineBreaks();
    }

    private static function getStatusEntry(): TextEntry
    {
        return TextEntry::make('status')
            ->label(__('filament.users.fields.status'))
            ->badge();
    }

    private static function getEmailVerifiedAtEntry(): TextEntry
    {
        return TextEntry::make('email_verified_at')
            ->label(__('filament.users.table.verified_at'))
            ->dateTime()
            ->placeholder('--');
    }

    private static function getCreatedAtEntry(): TextEntry
    {
        return TextEntry::make('created_at')
            ->label(__('filament.users.table.created_at'))
            ->dateTime();
    }

    private static function getUpdatedAtEntry(): TextEntry
    {
        return TextEntry::make('updated_at')
            ->label(__('filament.users.table.updated_at'))
            ->dateTime();
    }
}
