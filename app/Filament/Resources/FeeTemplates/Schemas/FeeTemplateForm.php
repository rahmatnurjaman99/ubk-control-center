<?php

declare(strict_types=1);

namespace App\Filament\Resources\FeeTemplates\Schemas;

use App\Enums\FeeType;
use App\Enums\GradeLevel;
use App\Support\AcademicYearResolver;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class FeeTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::getDetailsSection(),
            ])
            ->columns(1);
    }

    private static function getDetailsSection(): Section
    {
        return Section::make(__('filament.fee_templates.sections.details'))
            ->columns(2)
            ->schema([
                Select::make('academic_year_id')
                    ->label(__('filament.fee_templates.fields.academic_year'))
                    ->relationship('academicYear', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->default(fn (): ?int => AcademicYearResolver::currentId())
                    ->required(),
                TextInput::make('title')
                    ->label(__('filament.fee_templates.fields.title'))
                    ->required()
                    ->maxLength(255),
                Select::make('grade_level')
                    ->label(__('filament.fee_templates.fields.grade_level'))
                    ->options(GradeLevel::options())
                    ->enum(GradeLevel::class)
                    ->required()
                    ->native(false),
                Select::make('type')
                    ->label(__('filament.fee_templates.fields.type'))
                    ->options(FeeType::options())
                    ->enum(FeeType::class)
                    ->required()
                    ->native(false),
                TextInput::make('amount')
                    ->label(__('filament.fee_templates.fields.amount'))
                    ->numeric()
                    ->minValue(0)
                    ->prefix('IDR')
                    ->required(),
                TextInput::make('currency')
                    ->label(__('filament.fee_templates.fields.currency'))
                    ->maxLength(3)
                    ->default('IDR')
                    ->required(),
                TextInput::make('due_in_days')
                    ->label(__('filament.fee_templates.fields.due_in_days'))
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(60)
                    ->default(14),
                Toggle::make('is_active')
                    ->label(__('filament.fee_templates.fields.is_active'))
                    ->default(true)
                    ->inline(false),
                RichEditor::make('description')
                    ->label(__('filament.fee_templates.fields.description'))
                    ->columnSpanFull()
                    ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList', 'link'])
                    ->disableLabel(),
            ]);
    }
}
