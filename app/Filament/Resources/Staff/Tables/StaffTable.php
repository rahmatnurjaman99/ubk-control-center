<?php

declare(strict_types=1);

namespace App\Filament\Resources\Staff\Tables;

use App\Enums\StaffRole;
use App\Models\Staff;
use App\Support\Tables\Columns\CreatedAtColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\ImageColumn;
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
                self::getPhotoColumn(),
                self::getNameColumn(),
                self::getStaffNumberColumn(),
                self::getStaffNameColumn(),
                self::getGenderColumn(),
                self::getRoleColumn(),
                self::getEducationLevelColumn(),
                self::getJoinedOnColumn(),
                self::getPhoneColumn(),
                self::getAddressColumn(),
                self::getProvinceColumn(),
                self::getRegencyColumn(),
                self::getDistrictColumn(),
                self::getVillageColumn(),
                self::getEmergencyContactColumn(),
                self::getCreatedAtColumn(),
            ])
            ->defaultSort('joined_on', 'desc')
            ->filters([
                self::getRoleFilter(),
                self::getProvinceFilter(),
                self::getRegencyFilter(),
                self::getDistrictFilter(),
                self::getVillageFilter(),
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

    private static function getPhotoColumn(): ImageColumn
    {
        return ImageColumn::make('photo_url')
            ->label(__('filament.staff.fields.photo'))
            ->circular();
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

    private static function getGenderColumn(): TextColumn
    {
        return TextColumn::make('gender')
            ->label(__('filament.staff.fields.gender'))
            ->badge()
            ->formatStateUsing(fn (?string $state): ?string => match ($state) {
                'male' => __('filament.staff.genders.male'),
                'female' => __('filament.staff.genders.female'),
                default => null,
            })
            ->toggleable(isToggledHiddenByDefault: true);
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

    private static function getAddressColumn(): TextColumn
    {
        return TextColumn::make('address')
            ->label(__('filament.staff.fields.address'))
            ->wrap()
            ->toggleable(isToggledHiddenByDefault: true);
    }

    private static function getProvinceColumn(): TextColumn
    {
        return TextColumn::make('province.name')
            ->label(__('filament.staff.fields.province'))
            ->toggleable(isToggledHiddenByDefault: true);
    }

    private static function getRegencyColumn(): TextColumn
    {
        return TextColumn::make('regency.name')
            ->label(__('filament.staff.fields.regency'))
            ->toggleable(isToggledHiddenByDefault: true);
    }

    private static function getDistrictColumn(): TextColumn
    {
        return TextColumn::make('district.name')
            ->label(__('filament.staff.fields.district'))
            ->toggleable(isToggledHiddenByDefault: true);
    }

    private static function getVillageColumn(): TextColumn
    {
        return TextColumn::make('village.name')
            ->label(__('filament.staff.fields.village'))
            ->toggleable(isToggledHiddenByDefault: true);
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
        return CreatedAtColumn::make()
            ->label(__('filament.staff.table.created_at'))
            ->toggleable(isToggledHiddenByDefault: true);
    }

    private static function getRoleFilter(): SelectFilter
    {
        return SelectFilter::make('role')
            ->label(__('filament.staff.filters.role'))
            ->options(fn (): array => self::getRoleOptions())
            ->native(false);
    }

    private static function getProvinceFilter(): SelectFilter
    {
        return SelectFilter::make('province_id')
            ->label(__('filament.staff.fields.province'))
            ->relationship('province', 'name')
            ->searchable()
            ->native(false);
    }

    private static function getRegencyFilter(): SelectFilter
    {
        return SelectFilter::make('regency_id')
            ->label(__('filament.staff.fields.regency'))
            ->relationship('regency', 'name')
            ->searchable()
            ->native(false);
    }

    private static function getDistrictFilter(): SelectFilter
    {
        return SelectFilter::make('district_id')
            ->label(__('filament.staff.fields.district'))
            ->relationship('district', 'name')
            ->searchable()
            ->native(false);
    }

    private static function getVillageFilter(): SelectFilter
    {
        return SelectFilter::make('village_id')
            ->label(__('filament.staff.fields.village'))
            ->relationship('village', 'name')
            ->searchable()
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
