<?php

declare(strict_types=1);

namespace App\Filament\Resources\StaffAttendances;

use App\Filament\Resources\StaffAttendances\Pages\CreateStaffAttendance;
use App\Filament\Resources\StaffAttendances\Pages\EditStaffAttendance;
use App\Filament\Resources\StaffAttendances\Pages\ListStaffAttendances;
use App\Filament\Resources\StaffAttendances\Schemas\StaffAttendanceForm;
use App\Filament\Resources\StaffAttendances\Tables\StaffAttendancesTable;
use App\Models\StaffAttendance;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StaffAttendanceResource extends Resource
{
    protected static ?string $model = StaffAttendance::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $recordTitleAttribute = 'recorded_on';

    public static function form(Schema $schema): Schema
    {
        return StaffAttendanceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StaffAttendancesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStaffAttendances::route('/'),
            'create' => CreateStaffAttendance::route('/create'),
            'edit' => EditStaffAttendance::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation.attendance');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.staff_attendances.navigation.label');
    }

    public static function getModelLabel(): string
    {
        return __('filament.staff_attendances.model.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.staff_attendances.model.plural');
    }
}
