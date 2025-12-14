<?php

declare(strict_types=1);

namespace App\Filament\Resources\Payrolls\Schemas;

use App\Enums\PayrollStatus;
use App\Models\Payroll;
use App\Models\Staff;
use App\Support\AcademicYearResolver;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Number;

class PayrollForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::getDetailsSection(),
                self::getPeriodSection(),
                self::getTotalsSection(),
                self::getNotesSection(),
            ])
            ->columns(1);
    }

    private static function getDetailsSection(): Section
    {
        return Section::make(__('filament.payrolls.sections.details'))
            ->columns(2)
            ->schema([
                Select::make('academic_year_id')
                    ->label(__('filament.payrolls.fields.academic_year'))
                    ->relationship('academicYear', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->default(fn (): ?int => AcademicYearResolver::currentId())
                    ->nullable(),
                TextInput::make('reference')
                    ->label(__('filament.payrolls.fields.reference'))
                    ->default(fn (): string => Payroll::generateReference())
                    ->unique(ignoreRecord: true)
                    ->readOnly()
                    ->required(),
                TextInput::make('title')
                    ->label(__('filament.payrolls.fields.title'))
                    ->maxLength(255)
                    ->required(),
                Select::make('status')
                    ->label(__('filament.payrolls.fields.status'))
                    ->options(PayrollStatus::options())
                    ->enum(PayrollStatus::class)
                    ->default(PayrollStatus::Draft->value)
                    ->native(false)
                    ->required(),
                TextInput::make('currency')
                    ->label(__('filament.payrolls.fields.currency'))
                    ->maxLength(3)
                    ->default('IDR')
                    ->required(),
                Select::make('staff_ids')
                    ->label(__('filament.payrolls.fields.staff_ids'))
                    ->options(fn (): array => Staff::query()->orderBy('staff_name')->pluck('staff_name', 'id')->all())
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->helperText(__('filament.payrolls.fields.staff_ids_helper')),
            ]);
    }

    private static function getPeriodSection(): Section
    {
        return Section::make(__('filament.payrolls.sections.period'))
            ->columns(2)
            ->schema([
                DatePicker::make('period_start')
                    ->label(__('filament.payrolls.fields.period_start'))
                    ->native(false)
                    ->required(),
                DatePicker::make('period_end')
                    ->label(__('filament.payrolls.fields.period_end'))
                    ->native(false)
                    ->afterOrEqual('period_start')
                    ->required(),
            ]);
    }

    private static function getTotalsSection(): Section
    {
        return Section::make(__('filament.payrolls.sections.totals'))
            ->columns(2)
            ->schema([
                self::getTotalPlaceholder('total_base_salary', 'filament.payrolls.fields.total_base_salary'),
                self::getTotalPlaceholder('total_allowances', 'filament.payrolls.fields.total_allowances'),
                self::getTotalPlaceholder('total_deductions', 'filament.payrolls.fields.total_deductions'),
                self::getTotalPlaceholder('total_net', 'filament.payrolls.fields.total_net'),
            ]);
    }

    private static function getNotesSection(): Section
    {
        return Section::make(__('filament.payrolls.sections.notes'))
            ->schema([
                Textarea::make('notes')
                    ->label(__('filament.payrolls.fields.notes'))
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }

    private static function getTotalPlaceholder(string $field, string $labelTranslation): TextEntry
    {
        return TextEntry::make("{$field}_preview")
            ->label(__($labelTranslation))
            ->state(fn (Get $get, ?Payroll $record): string => Number::currency(
                (float) ($get($field) ?? $record?->{$field} ?? 0),
                $get('currency') ?? $record?->currency ?? 'IDR',
            ));
    }
}
