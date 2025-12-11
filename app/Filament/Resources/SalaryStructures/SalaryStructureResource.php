<?php

declare(strict_types=1);

namespace App\Filament\Resources\SalaryStructures;

use App\Filament\Resources\SalaryStructures\Pages\CreateSalaryStructure;
use App\Filament\Resources\SalaryStructures\Pages\EditSalaryStructure;
use App\Filament\Resources\SalaryStructures\Pages\ListSalaryStructures;
use App\Filament\Resources\SalaryStructures\Schemas\SalaryStructureForm;
use App\Filament\Resources\SalaryStructures\Tables\SalaryStructuresTable;
use App\Models\SalaryStructure;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SalaryStructureResource extends Resource
{
    protected static ?string $model = SalaryStructure::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAdjustmentsVertical;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return SalaryStructureForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SalaryStructuresTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSalaryStructures::route('/'),
            'create' => CreateSalaryStructure::route('/create'),
            'edit' => EditSalaryStructure::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation.finance');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.salary_structures.navigation.label');
    }

    public static function getModelLabel(): string
    {
        return __('filament.salary_structures.model.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.salary_structures.model.plural');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
