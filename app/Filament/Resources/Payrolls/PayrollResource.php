<?php

declare(strict_types=1);

namespace App\Filament\Resources\Payrolls;

use App\Filament\Resources\Payrolls\Pages\CreatePayroll;
use App\Filament\Resources\Payrolls\Pages\EditPayroll;
use App\Filament\Resources\Payrolls\Pages\ListPayrolls;
use App\Filament\Resources\Payrolls\RelationManagers\PayrollItemsRelationManager;
use App\Filament\Resources\Payrolls\Schemas\PayrollForm;
use App\Filament\Resources\Payrolls\Tables\PayrollsTable;
use App\Models\Payroll;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PayrollResource extends Resource
{
    protected static ?string $model = Payroll::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedReceiptPercent;

    protected static ?string $recordTitleAttribute = 'reference';

    public static function form(Schema $schema): Schema
    {
        return PayrollForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PayrollsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPayrolls::route('/'),
            'create' => CreatePayroll::route('/create'),
            'edit' => EditPayroll::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            PayrollItemsRelationManager::class,
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation.finance');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.payrolls.navigation.label');
    }

    public static function getModelLabel(): string
    {
        return __('filament.payrolls.model.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.payrolls.model.plural');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
