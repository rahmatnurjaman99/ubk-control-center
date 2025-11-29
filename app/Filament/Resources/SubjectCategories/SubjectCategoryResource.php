<?php

declare(strict_types=1);

namespace App\Filament\Resources\SubjectCategories;

use App\Filament\Resources\SubjectCategories\Pages\CreateSubjectCategory;
use App\Filament\Resources\SubjectCategories\Pages\EditSubjectCategory;
use App\Filament\Resources\SubjectCategories\Pages\ListSubjectCategories;
use App\Filament\Resources\SubjectCategories\RelationManagers\SubjectsRelationManager;
use App\Filament\Resources\SubjectCategories\Schemas\SubjectCategoryForm;
use App\Filament\Resources\SubjectCategories\Tables\SubjectCategoriesTable;
use App\Models\SubjectCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SubjectCategoryResource extends Resource
{
    protected static ?string $model = SubjectCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleGroup;

    public static function form(Schema $schema): Schema
    {
        return SubjectCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SubjectCategoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            SubjectsRelationManager::class,
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation.academics');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.subject_categories.navigation.label');
    }

    public static function getModelLabel(): string
    {
        return __('filament.subject_categories.model.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.subject_categories.model.plural');
    }

    public static function getNavigationBadge(): ?string
    {
        $count = SubjectCategory::query()->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSubjectCategories::route('/'),
            'create' => CreateSubjectCategory::route('/create'),
            'edit' => EditSubjectCategory::route('/{record}/edit'),
        ];
    }
}
