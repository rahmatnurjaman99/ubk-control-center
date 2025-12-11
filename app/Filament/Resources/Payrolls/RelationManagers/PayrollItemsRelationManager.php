<?php

declare(strict_types=1);

namespace App\Filament\Resources\Payrolls\RelationManagers;

use App\Enums\PayrollItemStatus;
use App\Models\PayrollItem;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Number;

class PayrollItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Select::make('staff_id')
                    ->label(__('filament.payroll_items.fields.staff'))
                    ->relationship('staff', 'staff_name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->native(false),
                Select::make('salary_structure_id')
                    ->label(__('filament.payroll_items.fields.salary_structure'))
                    ->relationship('salaryStructure', 'title')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->nullable(),
                Select::make('status')
                    ->label(__('filament.payroll_items.fields.status'))
                    ->options(PayrollItemStatus::options())
                    ->enum(PayrollItemStatus::class)
                    ->native(false)
                    ->required(),
                TextInput::make('base_salary')
                    ->label(__('filament.payroll_items.fields.base_salary'))
                    ->numeric()
                    ->minValue(0)
                    ->required()
                    ->prefix(fn (Get $get, ?PayrollItem $record): string => $this->resolveCurrency($get, $record)),
                TextInput::make('currency')
                    ->label(__('filament.payroll_items.fields.currency'))
                    ->maxLength(3)
                    ->default('IDR')
                    ->required(),
                $this->getRepeater('allowances', 'filament.payroll_items.fields.allowances', 'filament.payroll_items.actions.add_allowance'),
                $this->getPlaceholder('allowances', __('filament.payroll_items.fields.allowances_total')),
                $this->getRepeater('deductions', 'filament.payroll_items.fields.deductions', 'filament.payroll_items.actions.add_deduction'),
                $this->getPlaceholder('deductions', __('filament.payroll_items.fields.deductions_total')),
                TextEntry::make('net_amount_preview')
                    ->label(__('filament.payroll_items.fields.net_amount'))
                    ->state(fn (Get $get, ?PayrollItem $record): string => Number::currency(
                        max(
                            (float) ($get('base_salary') ?? $record?->base_salary ?? 0)
                                + $this->calculateComponentsTotal($get('allowances') ?? $record?->allowances ?? [])
                                - $this->calculateComponentsTotal($get('deductions') ?? $record?->deductions ?? []),
                            0,
                        ),
                        $this->resolveCurrency($get, $record),
                    ))
                    ->columnSpanFull(),
                Textarea::make('notes')
                    ->label(__('filament.payroll_items.fields.notes'))
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('staff.staff_name')
                    ->label(__('filament.payroll_items.table.staff'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('salaryStructure.title')
                    ->label(__('filament.payroll_items.table.salary_structure'))
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('status')
                    ->label(__('filament.payroll_items.table.status'))
                    ->badge()
                    ->formatStateUsing(fn (?PayrollItemStatus $state): ?string => $state?->getLabel())
                    ->color(fn (?PayrollItemStatus $state): ?string => $state?->getColor())
                    ->sortable(),
                TextColumn::make('base_salary')
                    ->label(__('filament.payroll_items.table.base_salary'))
                    ->formatStateUsing(fn (PayrollItem $record): string => Number::currency(
                        (float) $record->base_salary,
                        $record->currency ?? 'IDR',
                    ))
                    ->alignRight()
                    ->summarize(Sum::make()->label(__('filament.payroll_items.table.sum_base_salary'))->money('IDR'))
                    ->sortable(),
                TextColumn::make('allowances_total')
                    ->label(__('filament.payroll_items.table.allowances_total'))
                    ->formatStateUsing(fn (PayrollItem $record): string => Number::currency(
                        (float) $record->allowances_total,
                        $record->currency ?? 'IDR',
                    ))
                    ->alignRight()
                    ->toggleable()
                    ->summarize(Sum::make()->label(__('filament.payroll_items.table.sum_allowances'))->money('IDR'))
                    ->sortable(),
                TextColumn::make('deductions_total')
                    ->label(__('filament.payroll_items.table.deductions_total'))
                    ->formatStateUsing(fn (PayrollItem $record): string => Number::currency(
                        (float) $record->deductions_total,
                        $record->currency ?? 'IDR',
                    ))
                    ->alignRight()
                    ->toggleable()
                    ->summarize(Sum::make()->label(__('filament.payroll_items.table.sum_deductions'))->money('IDR'))
                    ->sortable(),
                TextColumn::make('net_amount')
                    ->label(__('filament.payroll_items.table.net_amount'))
                    ->formatStateUsing(fn (PayrollItem $record): string => Number::currency(
                        (float) $record->net_amount,
                        $record->currency ?? 'IDR',
                    ))
                    ->alignRight()
                    ->summarize(Sum::make()->label(__('filament.payroll_items.table.sum_net'))->money('IDR'))
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label(__('filament.payroll_items.table.updated_at'))
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('filament.payroll_items.filters.status'))
                    ->options(PayrollItemStatus::options()),
                SelectFilter::make('staff_id')
                    ->label(__('filament.payroll_items.filters.staff'))
                    ->relationship('staff', 'staff_name'),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    private function getRepeater(string $field, string $label, string $actionLabel): Repeater
    {
        return Repeater::make($field)
            ->label(__($label))
            ->columns(2)
            ->schema([
                TextInput::make('label')
                    ->label(__('filament.payroll_items.fields.component_label'))
                    ->maxLength(255)
                    ->required(),
                TextInput::make('amount')
                    ->label(__('filament.payroll_items.fields.component_amount'))
                    ->numeric()
                    ->minValue(0)
                    ->required()
                    ->prefix('IDR'),
            ])
            ->addActionLabel(__($actionLabel))
            ->columnSpanFull();
    }

    private function getPlaceholder(string $field, string $label): TextEntry
    {
        return TextEntry::make("{$field}_preview")
            ->label($label)
            ->state(fn (Get $get, ?PayrollItem $record): string => Number::currency(
                $this->calculateComponentsTotal($get($field) ?? $record?->{$field} ?? []),
                $this->resolveCurrency($get, $record),
            ))
            ->columnSpanFull();
    }

    /**
     * @param array<int, array<string, mixed>> $components
     */
    private function calculateComponentsTotal(array $components): float
    {
        return collect($components)
            ->sum(fn (array $component): float => (float) ($component['amount'] ?? 0));
    }

    private function resolveCurrency(Get $get, ?PayrollItem $record): string
    {
        return $get('currency') ?? $record?->currency ?? 'IDR';
    }
}
