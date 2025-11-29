<?php

declare(strict_types=1);

namespace App\Filament\Resources\RegistrationIntakes;

use App\Filament\Resources\RegistrationIntakes\Pages\CreateRegistrationIntake;
use App\Filament\Resources\RegistrationIntakes\Pages\EditRegistrationIntake;
use App\Filament\Resources\RegistrationIntakes\Pages\ListRegistrationIntakes;
use App\Filament\Resources\RegistrationIntakes\Schemas\RegistrationIntakeForm;
use App\Filament\Resources\RegistrationIntakes\Tables\RegistrationIntakesTable;
use App\Models\RegistrationIntake;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RegistrationIntakeResource extends Resource
{
    protected static ?string $model = RegistrationIntake::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $recordTitleAttribute = 'form_number';

    public static function form(Schema $schema): Schema
    {
        return RegistrationIntakeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RegistrationIntakesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRegistrationIntakes::route('/'),
            'create' => CreateRegistrationIntake::route('/create'),
            'edit' => EditRegistrationIntake::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation.admissions');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.registration_intakes.navigation.label');
    }

    public static function getModelLabel(): string
    {
        return __('filament.registration_intakes.model.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.registration_intakes.model.plural');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
