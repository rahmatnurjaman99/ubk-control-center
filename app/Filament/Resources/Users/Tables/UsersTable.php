<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Tables;

use App\Enums\SystemRole;
use App\Enums\UserStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                self::getAvatarColumn(),
                self::getNameColumn(),
                self::getEmailColumn(),
                self::getRolesColumn(),
                self::getStatusColumn(),
                self::getEmailVerifiedAtColumn(),
                self::getCreatedAtColumn(),
                self::getUpdatedAtColumn(),
            ])
            ->filters([
                self::getRolesFilter(),
                self::getStatusFilter(),
            ])
            ->recordActions([
                self::getViewAction(),
                self::getEditAction(),
            ])
            ->toolbarActions([
                self::getBulkActionGroup(),
            ]);
    }

    private static function getAvatarColumn(): ImageColumn
    {
        return ImageColumn::make('avatar_url')
            ->label(__('filament.users.table.avatar'))
            ->circular();
    }

    private static function getNameColumn(): TextColumn
    {
        return TextColumn::make('name')
            ->label(__('filament.users.fields.name'))
            ->searchable()
            ->sortable();
    }

    private static function getEmailColumn(): TextColumn
    {
        return TextColumn::make('email')
            ->label(__('filament.users.fields.email'))
            ->searchable()
            ->sortable();
    }

    private static function getRolesColumn(): TextColumn
    {
        return TextColumn::make('roles.name')
            ->label(__('filament.users.fields.roles'))
            ->badge()
            ->color(fn (?string $state): ?string => self::resolveRoleBadgeColor($state))
            ->separator(', ')
            ->wrap();
    }

    private static function getStatusColumn(): TextColumn
    {
        return TextColumn::make('status')
            ->label(__('filament.users.fields.status'))
            ->badge()
            ->sortable();
    }

    private static function getEmailVerifiedAtColumn(): TextColumn
    {
        return TextColumn::make('email_verified_at')
            ->label(__('filament.users.table.verified_at'))
            ->dateTime()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }

    private static function getCreatedAtColumn(): TextColumn
    {
        return TextColumn::make('created_at')
            ->dateTime()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }

    private static function getUpdatedAtColumn(): TextColumn
    {
        return TextColumn::make('updated_at')
            ->dateTime()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }

    private static function getRolesFilter(): SelectFilter
    {
        return SelectFilter::make('roles')
            ->relationship('roles', 'name')
            ->multiple()
            ->preload();
    }

    private static function getStatusFilter(): SelectFilter
    {
        return SelectFilter::make('status')
            ->label(__('filament.users.fields.status'))
            ->options(UserStatus::class);
    }

    private static function getViewAction(): ViewAction
    {
        return ViewAction::make();
    }

    private static function getEditAction(): EditAction
    {
        return EditAction::make();
    }

    private static function getBulkActionGroup(): BulkActionGroup
    {
        return BulkActionGroup::make([
            self::getDeleteBulkAction(),
        ]);
    }

    private static function getDeleteBulkAction(): DeleteBulkAction
    {
        return DeleteBulkAction::make();
    }

    private static function resolveRoleBadgeColor(?string $role): ?string
    {
        return match ($role) {
            SystemRole::SuperAdmin->value => 'danger',
            SystemRole::Admin->value => 'warning',
            SystemRole::Teacher->value => 'success',
            SystemRole::Guardian->value => 'info',
            SystemRole::Student->value => 'gray',
            SystemRole::PanelUser->value => 'primary',
            default => 'secondary',
        };
    }
}
