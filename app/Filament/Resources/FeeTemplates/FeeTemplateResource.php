<?php

declare(strict_types=1);

namespace App\Filament\Resources\FeeTemplates;

use App\Filament\Resources\FeeTemplates\Pages\CreateFeeTemplate;
use App\Filament\Resources\FeeTemplates\Pages\EditFeeTemplate;
use App\Filament\Resources\FeeTemplates\Pages\ListFeeTemplates;
use App\Filament\Resources\FeeTemplates\Schemas\FeeTemplateForm;
use App\Filament\Resources\FeeTemplates\Tables\FeeTemplatesTable;
use App\Models\FeeTemplate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FeeTemplateResource extends Resource
{
    protected static ?string $model = FeeTemplate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return FeeTemplateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FeeTemplatesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFeeTemplates::route('/'),
            'create' => CreateFeeTemplate::route('/create'),
            'edit' => EditFeeTemplate::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation.finance');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.fee_templates.navigation.label');
    }

    public static function getModelLabel(): string
    {
        return __('filament.fee_templates.model.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.fee_templates.model.plural');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
