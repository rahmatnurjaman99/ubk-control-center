<?php

declare(strict_types=1);

namespace App\Filament\Resources\Staff\Tables;

use App\Enums\StaffRole;
use App\Models\Staff;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class StaffTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                self::getNameColumn(),
                self::getStaffNumberColumn(),
                self::getStaffNameColumn(),
                self::getRoleColumn(),
                self::getEducationLevelColumn(),
                self::getJoinedOnColumn(),
                self::getPhoneColumn(),
                self::getEmergencyContactColumn(),
                self::getCreatedAtColumn(),
            ])
            ->defaultSort('joined_on', 'desc')
            ->filters([
                self::getRoleFilter(),
                self::getJoinedOnFilter(),
                self::getTrashedFilter(),
            ])
            ->recordActions([
                self::getViewAction(),
                self::getEditAction(),
                self::getDeleteAction(),
            ])
            ->toolbarActions([
                self::getBulkActionGroup(),
            ]);
    }

    private static function getNameColumn(): TextColumn
    {
        return TextColumn::make('user.name')
            ->label(__('filament.staff.fields.user'))
            ->sortable()
            ->searchable();
    }

    private static function getStaffNumberColumn(): TextColumn
    {
        return TextColumn::make('staff_number')
            ->label(__('filament.staff.fields.staff_number'))
            ->searchable()
            ->sortable();
    }

    private static function getStaffNameColumn(): TextColumn
    {
        return TextColumn::make('staff_name')
            ->label(__('filament.staff.fields.staff_name'))
            ->searchable()
            ->sortable();
    }

    private static function getRoleColumn(): TextColumn
    {
        return TextColumn::make('user.roles.name')
            ->label(__('filament.staff.fields.role'))
            ->badge()
            ->separator(', ')
            ->wrap()
            ->state(fn (Staff $record): array => self::formatRoleState($record));
    }

    private static function getJoinedOnColumn(): TextColumn
    {
        return TextColumn::make('joined_on')
            ->label(__('filament.staff.fields.joined_on'))
            ->date()
            ->sortable();
    }

    private static function getPhoneColumn(): TextColumn
    {
        return TextColumn::make('phone')
            ->label(__('filament.staff.fields.phone'))
            ->wrap()
            ->copyable();
    }

    private static function getEducationLevelColumn(): TextColumn
    {
        return TextColumn::make('education_level')
            ->label(__('filament.staff.fields.education_level'))
            ->toggleable(isToggledHiddenByDefault: true);
    }

    private static function getEmergencyContactColumn(): TextColumn
    {
        return TextColumn::make('emergency_contact_name')
            ->label(__('filament.staff.fields.emergency_contact_name'))
            ->toggleable(isToggledHiddenByDefault: true);
    }

    private static function getCreatedAtColumn(): TextColumn
    {
        return TextColumn::make('created_at')
            ->label(__('filament.staff.table.created_at'))
            ->dateTime()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }

    private static function getRoleFilter(): SelectFilter
    {
        return SelectFilter::make('role')
            ->label(__('filament.staff.filters.role'))
            ->options(fn (): array => self::getRoleOptions())
            ->native(false);
    }

    private static function getJoinedOnFilter(): Filter
    {
        return Filter::make('joined_period')
            ->label(__('filament.staff.filters.joined_period'))
            ->form([
                DatePicker::make('from')
                    ->label(__('filament.staff.filters.joined_from')),
                DatePicker::make('until')
                    ->label(__('filament.staff.filters.joined_until')),
            ])
            ->columns(2)
            ->query(function (Builder $query, array $data): Builder {
                return $query
                    ->when($data['from'] ?? null, fn (Builder $q, string $date): Builder => $q->whereDate('joined_on', '>=', $date))
                    ->when($data['until'] ?? null, fn (Builder $q, string $date): Builder => $q->whereDate('joined_on', '<=', $date));
            })
            ->indicateUsing(function (array $data): array {
                $indicators = [];

                if (! empty($data['from'])) {
                    $indicators[] = __('filament.staff.filters.joined_from_indicator', ['date' => $data['from']]);
                }

                if (! empty($data['until'])) {
                    $indicators[] = __('filament.staff.filters.joined_until_indicator', ['date' => $data['until']]);
                }

                return $indicators;
            });
    }

    private static function getTrashedFilter(): TrashedFilter
    {
        return TrashedFilter::make();
    }

    /**
     * @return list<string>
     */
    private static function formatRoleState(Staff $record): array
    {
        $names = $record->user?->roles
            ?->pluck('name')
            ->filter()
            ->unique()
            ->values()
            ->map(fn (string $name): string => self::formatRoleLabel($name) ?? $name)
            ->all();

        if (! empty($names)) {
            return $names;
        }

        $fallback = (string) $record->role;
        if ($fallback === '') {
            return [];
        }

        return [self::formatRoleLabel($fallback) ?? $fallback];
    }

    private static function getRoleOptions(): array
    {
        $options = Role::query()
            ->orderBy('name')
            ->pluck('name')
            ->mapWithKeys(fn (string $name): array => [$name => self::formatRoleLabel($name) ?? $name])
            ->toArray();

        if (! empty($options)) {
            return $options;
        }

        return collect(StaffRole::cases())
            ->mapWithKeys(fn (StaffRole $role): array => [$role->value => $role->getLabel() ?? $role->value])
            ->toArray();
    }

    private static function formatRoleLabel(string $value): ?string
    {
        $enum = StaffRole::tryFrom($value);

        if ($enum !== null) {
            return $enum->getLabel();
        }

        return Str::headline(str_replace('_', ' ', $value));
    }

    private static function getViewAction(): ViewAction
    {
        return ViewAction::make();
    }

    private static function getEditAction(): EditAction
    {
        return EditAction::make();
    }

    private static function getDeleteAction(): DeleteAction
    {
        return DeleteAction::make();
    }

    private static function getBulkActionGroup(): BulkActionGroup
    {
        return BulkActionGroup::make([
            self::getDeleteBulkAction(),
            self::getForceDeleteBulkAction(),
            self::getRestoreBulkAction(),
        ]);
    }

    private static function getDeleteBulkAction(): DeleteBulkAction
    {
        return DeleteBulkAction::make();
    }

    private static function getForceDeleteBulkAction(): ForceDeleteBulkAction
    {
        return ForceDeleteBulkAction::make();
    }

    private static function getRestoreBulkAction(): RestoreBulkAction
    {
        return RestoreBulkAction::make();
    }
}
