<?php

declare(strict_types=1);

namespace App\Filament\Resources\SubjectCategories\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class SubjectCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')
                    ->label(__('filament.subject_categories.fields.name'))
                    ->required()
                    ->maxLength(100)
                    ->live()
                    ->afterStateUpdated(
                        function (Set $set, ?string $state): void {
                            $set('slug', blank($state) ? null : Str::slug($state));
                        },
                    ),
                TextInput::make('slug')
                    ->label(__('filament.subject_categories.fields.slug'))
                    ->required()
                    ->maxLength(100)
                    ->unique('subject_categories', 'slug', ignoreRecord: true),
                Textarea::make('description')
                    ->label(__('filament.subject_categories.fields.description'))
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
