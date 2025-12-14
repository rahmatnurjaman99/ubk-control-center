<?php

declare(strict_types=1);

namespace App\Filament\Resources\Staff;

use App\Filament\Resources\Staff\Pages\CreateStaff;
use App\Filament\Resources\Staff\Pages\EditStaff;
use App\Filament\Resources\Staff\Pages\ListStaff;
use App\Filament\Resources\Staff\Pages\ViewStaff;
use App\Filament\Resources\Staff\Schemas\StaffForm;
use App\Filament\Resources\Staff\Schemas\StaffInfolist;
use App\Filament\Resources\Staff\Tables\StaffTable;
use App\Models\Staff;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StaffResource extends Resource
{
    protected static ?string $model = Staff::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBriefcase;

    protected static ?string $recordTitleAttribute = 'staff_number';

    public static function form(Schema $schema): Schema
    {
        return StaffForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StaffInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StaffTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation.people_staff');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.staff.navigation.label');
    }

    public static function getModelLabel(): string
    {
        return __('filament.staff.model.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.staff.model.plural');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStaff::route('/'),
            'create' => CreateStaff::route('/create'),
            'edit' => EditStaff::route('/{record}/edit'),
            'view' => ViewStaff::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['user.roles', 'documents', 'province', 'regency', 'district', 'village'])
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
