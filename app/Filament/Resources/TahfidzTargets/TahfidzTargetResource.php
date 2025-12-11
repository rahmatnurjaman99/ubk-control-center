<?php

declare(strict_types=1);

namespace App\Filament\Resources\TahfidzTargets;

use App\Filament\Resources\TahfidzTargets\Pages\CreateTahfidzTarget;
use App\Filament\Resources\TahfidzTargets\Pages\EditTahfidzTarget;
use App\Filament\Resources\TahfidzTargets\Pages\ListTahfidzTargets;
use App\Filament\Resources\TahfidzTargets\RelationManagers\TahfidzLogsRelationManager;
use App\Filament\Resources\TahfidzTargets\Schemas\TahfidzTargetForm;
use App\Filament\Resources\TahfidzTargets\Tables\TahfidzTargetsTable;
use App\Models\TahfidzTarget;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TahfidzTargetResource extends Resource
{
    protected static ?string $model = TahfidzTarget::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookmark;

    public static function form(Schema $schema): Schema
    {
        return TahfidzTargetForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TahfidzTargetsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            TahfidzLogsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTahfidzTargets::route('/'),
            'create' => CreateTahfidzTarget::route('/create'),
            'edit' => EditTahfidzTarget::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation.academics');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.tahfidz_targets.navigation.label');
    }

    public static function getModelLabel(): string
    {
        return __('filament.tahfidz_targets.model.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.tahfidz_targets.model.plural');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'student',
                'classroom',
                'assignedBy',
                'segments',
                'logs',
            ])
            ->withCount('logs');
    }
}
