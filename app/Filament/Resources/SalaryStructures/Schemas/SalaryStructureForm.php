<?php

declare(strict_types=1);

namespace App\Filament\Resources\SalaryStructures\Schemas;

use App\Models\SalaryStructure;
use App\Support\AcademicYearResolver;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Number;

class SalaryStructureForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::getGeneralSection(),
                self::getCompensationSection(),
                self::getNotesSection(),
            ])
            ->columns(1);
    }

    private static function getGeneralSection(): Section
    {
        return Section::make(__('filament.salary_structures.sections.general'))
            ->columns(2)
            ->schema([
                Select::make('academic_year_id')
                    ->label(__('filament.salary_structures.fields.academic_year'))
                    ->relationship('academicYear', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->default(fn (): ?int => AcademicYearResolver::currentId())
                    ->nullable(),
                Select::make('staff_id')
                    ->label(__('filament.salary_structures.fields.staff'))
                    ->relationship('staff', 'staff_name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->native(false),
                TextInput::make('title')
                    ->label(__('filament.salary_structures.fields.title'))
                    ->maxLength(255)
                    ->required()
                    ->columnSpanFull(),
                DatePicker::make('effective_date')
                    ->label(__('filament.salary_structures.fields.effective_date'))
                    ->native(false)
                    ->required(),
                DatePicker::make('expires_on')
                    ->label(__('filament.salary_structures.fields.expires_on'))
                    ->native(false)
                    ->after('effective_date'),
                Toggle::make('is_active')
                    ->label(__('filament.salary_structures.fields.is_active'))
                    ->default(true),
            ]);
    }

    private static function getCompensationSection(): Section
    {
        return Section::make(__('filament.salary_structures.sections.compensation'))
            ->columns(2)
            ->schema([
                TextInput::make('base_salary')
                    ->label(__('filament.salary_structures.fields.base_salary'))
                    ->numeric()
                    ->minValue(0)
                    ->required()
                    ->prefix(fn (Get $get, ?SalaryStructure $record): string => self::resolveCurrency($get, $record)),
                TextInput::make('currency')
                    ->label(__('filament.salary_structures.fields.currency'))
                    ->maxLength(3)
                    ->default('IDR')
                    ->required(),
                self::getAllowancesRepeater(),
                self::getAllowancesPreview(),
                self::getDeductionsRepeater(),
                self::getDeductionsPreview(),
                self::getNetPreview(),
            ]);
    }

    private static function getAllowancesRepeater(): Repeater
    {
        return Repeater::make('allowances')
            ->label(__('filament.salary_structures.fields.allowances'))
            ->columns(2)
            ->schema([
                TextInput::make('label')
                    ->label(__('filament.salary_structures.fields.component_label'))
                    ->maxLength(255)
                    ->required(),
                TextInput::make('amount')
                    ->label(__('filament.salary_structures.fields.component_amount'))
                    ->numeric()
                    ->minValue(0)
                    ->required()
                    ->prefix('IDR'),
            ])
            ->addActionLabel(__('filament.salary_structures.actions.add_allowance'))
            ->columnSpanFull();
    }

    private static function getAllowancesPreview(): TextEntry
    {
        return TextEntry::make('allowances_total_preview')
            ->label(__('filament.salary_structures.fields.allowances_total'))
            ->state(fn (Get $get, ?SalaryStructure $record): string => self::formatComponentsTotal(
                $get('allowances') ?? $record?->allowances ?? [],
                $get,
                $record,
            ))
            ->columnSpanFull();
    }

    private static function getDeductionsRepeater(): Repeater
    {
        return Repeater::make('deductions')
            ->label(__('filament.salary_structures.fields.deductions'))
            ->columns(2)
            ->schema([
                TextInput::make('label')
                    ->label(__('filament.salary_structures.fields.component_label'))
                    ->maxLength(255)
                    ->required(),
                TextInput::make('amount')
                    ->label(__('filament.salary_structures.fields.component_amount'))
                    ->numeric()
                    ->minValue(0)
                    ->required()
                    ->prefix('IDR'),
            ])
            ->addActionLabel(__('filament.salary_structures.actions.add_deduction'))
            ->columnSpanFull();
    }

    private static function getDeductionsPreview(): TextEntry
    {
        return TextEntry::make('deductions_total_preview')
            ->label(__('filament.salary_structures.fields.deductions_total'))
            ->state(fn (Get $get, ?SalaryStructure $record): string => self::formatComponentsTotal(
                $get('deductions') ?? $record?->deductions ?? [],
                $get,
                $record,
            ))
            ->columnSpanFull();
    }

    private static function getNetPreview(): TextEntry
    {
        return TextEntry::make('net_amount_preview')
            ->label(__('filament.salary_structures.fields.net_amount'))
            ->state(function (Get $get, ?SalaryStructure $record): string {
                $base = (float) ($get('base_salary') ?? $record?->base_salary ?? 0);
                $allowances = self::calculateComponentsTotal($get('allowances') ?? $record?->allowances ?? []);
                $deductions = self::calculateComponentsTotal($get('deductions') ?? $record?->deductions ?? []);

                return Number::currency(
                    max($base + $allowances - $deductions, 0),
                    self::resolveCurrency($get, $record),
                );
            })
            ->columnSpanFull();
    }

    private static function getNotesSection(): Section
    {
        return Section::make(__('filament.salary_structures.sections.notes'))
            ->schema([
                Textarea::make('notes')
                    ->label(__('filament.salary_structures.fields.notes'))
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }

    /**
     * @param array<int, array<string, mixed>> $components
     */
    private static function calculateComponentsTotal(array $components): float
    {
        return collect($components)
            ->sum(fn (array $component): float => (float) ($component['amount'] ?? 0));
    }

    /**
     * @param array<int, array<string, mixed>> $components
     */
    private static function formatComponentsTotal(array $components, Get $get, ?SalaryStructure $record): string
    {
        return Number::currency(
            self::calculateComponentsTotal($components),
            self::resolveCurrency($get, $record),
        );
    }

    private static function resolveCurrency(Get $get, ?SalaryStructure $record): string
    {
        return $get('currency') ?? $record?->currency ?? 'IDR';
    }
}
