<?php

declare(strict_types=1);

namespace App\Filament\Resources\Staff\Schemas;

use App\Enums\EducationLevel;
use App\Enums\StaffRole;
use App\Models\District;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Staff;
use App\Models\Village;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class StaffForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::getPrimarySection(),
                self::getPaymentSection(),
                self::getEmergencySection(),
                self::getEducationSection(),
                self::getDocumentsSection(),
            ])
            ->columns(1);
    }

    private static function getPrimarySection(): Section
    {
        return Section::make(__('filament.staff.sections.profile'))
            ->columns(2)
            ->schema([
                self::getUserComponent(),
                self::getPhotoComponent(),
                self::getStaffNameComponent(),
                self::getGenderComponent(),
                self::getStaffNumberComponent(),
                self::getRoleComponent(),
                self::getJoinedOnComponent(),
                self::getPhoneComponent(),
                self::getAddressComponent(),
                self::getProvinceComponent(),
                self::getRegencyComponent(),
                self::getDistrictComponent(),
                self::getVillageComponent(),
            ]);
    }

    private static function getPaymentSection(): Section
    {
        return Section::make(__('filament.staff.sections.payment'))
            ->columns(2)
            ->schema([
                self::getBankNameComponent(),
                self::getBankAccountNameComponent(),
                self::getBankAccountNumberComponent(),
            ]);
    }

    private static function getEmergencySection(): Section
    {
        return Section::make(__('filament.staff.sections.emergency'))
            ->columns(2)
            ->schema([
                self::getEmergencyContactNameComponent(),
                self::getEmergencyContactPhoneComponent(),
            ]);
    }

    private static function getEducationSection(): Section
    {
        return Section::make(__('filament.staff.sections.education'))
            ->columns(2)
            ->schema([
                self::getEducationLevelComponent(),
                self::getEducationInstitutionComponent(),
                self::getGraduatedYearComponent(),
            ]);
    }

    private static function getDocumentsSection(): Section
    {
        return Section::make(__('filament.staff.sections.documents'))
            ->schema([
                self::getDocumentsRepeater(),
            ]);
    }

    private static function getUserComponent(): Select
    {
        return Select::make('user_id')
            ->label(__('filament.staff.fields.user'))
            ->relationship('user', 'name')
            ->searchable()
            ->preload()
            ->required()
            ->native(false)
            ->unique(ignoreRecord: true);
    }

    private static function getStaffNumberComponent(): TextInput
    {
        return TextInput::make('staff_number')
            ->label(__('filament.staff.fields.staff_number'))
            ->maxLength(50)
            ->required()
            ->default(fn (?Staff $record): string => $record?->staff_number ?? Staff::generateStaffNumber())
            ->unique(ignoreRecord: true);
    }

    private static function getStaffNameComponent(): TextInput
    {
        return TextInput::make('staff_name')
            ->label(__('filament.staff.fields.staff_name'))
            ->maxLength(255)
            ->required();
    }

    private static function getGenderComponent(): Select
    {
        return Select::make('gender')
            ->label(__('filament.staff.fields.gender'))
            ->options(self::getGenderOptions())
            ->native(false)
            ->nullable();
    }

    /**
     * @return array<string, string>
     */
    private static function getGenderOptions(): array
    {
        return [
            'male' => __('filament.staff.genders.male'),
            'female' => __('filament.staff.genders.female'),
        ];
    }

    private static function getPhotoComponent(): FileUpload
    {
        return FileUpload::make('photo_path')
            ->label(__('filament.staff.fields.photo'))
            ->image()
            ->avatar()
            ->imageEditor()
            ->directory('staff-photos')
            ->disk('public')
            ->visibility('public')
            ->maxSize(5_000);
    }

    private static function getBankNameComponent(): TextInput
    {
        return TextInput::make('bank_name')
            ->label(__('filament.staff.fields.bank_name'))
            ->maxLength(100);
    }

    private static function getBankAccountNameComponent(): TextInput
    {
        return TextInput::make('bank_account_name')
            ->label(__('filament.staff.fields.bank_account_name'))
            ->maxLength(255);
    }

    private static function getBankAccountNumberComponent(): TextInput
    {
        return TextInput::make('bank_account_number')
            ->label(__('filament.staff.fields.bank_account_number'))
            ->maxLength(50)
            ->unique(ignoreRecord: true);
    }

    private static function getRoleComponent(): Select
    {
        return Select::make('role')
            ->label(__('filament.staff.fields.role'))
            ->options(fn (): array => self::getRoleOptions())
            ->getOptionLabelUsing(fn (?string $value): ?string => self::formatRoleLabel($value))
            ->placeholder(__('filament.staff.fields.role'))
            ->searchable()
            ->native(false)
            ->required();
    }

    private static function getJoinedOnComponent(): DatePicker
    {
        return DatePicker::make('joined_on')
            ->label(__('filament.staff.fields.joined_on'))
            ->native(false)
            ->required();
    }

    private static function getPhoneComponent(): TextInput
    {
        return TextInput::make('phone')
            ->label(__('filament.staff.fields.phone'))
            ->tel()
            ->maxLength(30);
    }

    private static function getAddressComponent(): Textarea
    {
        return Textarea::make('address')
            ->label(__('filament.staff.fields.address'))
            ->rows(3)
            ->columnSpanFull();
    }

    private static function getProvinceComponent(): Select
    {
        return Select::make('province_id')
            ->label(__('filament.staff.fields.province'))
            ->options(fn (): array => Province::query()
                ->orderBy('name')
                ->pluck('name', 'id')
                ->all())
            ->searchable()
            ->native(false)
            ->live()
            ->partiallyRenderAfterStateUpdated()
            ->partiallyRenderComponentsAfterStateUpdated(['regency_id', 'district_id', 'village_id'])
            ->afterStateUpdated(function (Set $set): void {
                $set('regency_id', null);
                $set('district_id', null);
                $set('village_id', null);
            });
    }

    private static function getRegencyComponent(): Select
    {
        return Select::make('regency_id')
            ->label(__('filament.staff.fields.regency'))
            ->options(function (Get $get): array {
                $provinceId = $get('province_id');

                if (blank($provinceId)) {
                    return [];
                }

                return Regency::query()
                    ->where('province_id', $provinceId)
                    ->orderBy('name')
                    ->pluck('name', 'id')
                    ->all();
            })
            ->disabled(fn (Get $get): bool => blank($get('province_id')))
            ->searchable()
            ->native(false)
            ->live()
            ->partiallyRenderComponentsAfterStateUpdated(['district_id', 'village_id'])
            ->afterStateUpdated(function (Set $set): void {
                $set('district_id', null);
                $set('village_id', null);
            });
    }

    private static function getDistrictComponent(): Select
    {
        return Select::make('district_id')
            ->label(__('filament.staff.fields.district'))
            ->options(function (Get $get): array {
                $regencyId = $get('regency_id');

                if (blank($regencyId)) {
                    return [];
                }

                return District::query()
                    ->where('regency_id', $regencyId)
                    ->orderBy('name')
                    ->pluck('name', 'id')
                    ->all();
            })
            ->disabled(fn (Get $get): bool => blank($get('regency_id')))
            ->searchable()
            ->native(false)
            ->live()
            ->partiallyRenderComponentsAfterStateUpdated(['village_id'])
            ->afterStateUpdated(fn (Set $set) => $set('village_id', null));
    }

    private static function getVillageComponent(): Select
    {
        return Select::make('village_id')
            ->label(__('filament.staff.fields.village'))
            ->options(function (Get $get): array {
                $districtId = $get('district_id');

                if (blank($districtId)) {
                    return [];
                }

                return Village::query()
                    ->where('district_id', $districtId)
                    ->orderBy('name')
                    ->pluck('name', 'id')
                    ->all();
            })
            ->disabled(fn (Get $get): bool => blank($get('district_id')))
            ->searchable()
            ->native(false);
    }

    private static function getEmergencyContactNameComponent(): TextInput
    {
        return TextInput::make('emergency_contact_name')
            ->label(__('filament.staff.fields.emergency_contact_name'))
            ->maxLength(255);
    }

    private static function getEmergencyContactPhoneComponent(): TextInput
    {
        return TextInput::make('emergency_contact_phone')
            ->label(__('filament.staff.fields.emergency_contact_phone'))
            ->tel()
            ->maxLength(30);
    }

    private static function getDocumentsRepeater(): Repeater
    {
        return Repeater::make('documents')
            ->relationship()
            ->label(__('filament.staff.fields.documents'))
            ->columns(2)
            ->schema([
                TextInput::make('name')
                    ->label(__('filament.staff.fields.document_name'))
                    ->maxLength(255)
                    ->required(),
                TextInput::make('type')
                    ->label(__('filament.staff.fields.document_type'))
                    ->maxLength(100),
                FileUpload::make('file_path')
                    ->label(__('filament.staff.fields.document_file'))
                    ->directory('staff-documents')
                    ->disk('public')
                    ->visibility('public')
                    ->required(),
                TextInput::make('notes')
                    ->label(__('filament.staff.fields.document_notes'))
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }

    private static function formatRoleLabel(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $enum = StaffRole::tryFrom($value);

        if ($enum !== null) {
            return $enum->getLabel();
        }

        return Str::headline(str_replace('_', ' ', $value));
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

    private static function getEducationLevelComponent(): Select
    {
        return Select::make('education_level')
            ->label(__('filament.staff.fields.education_level'))
            ->options(EducationLevel::class)
            ->enum(EducationLevel::class)
            ->native(false)
            ->required();
    }

    private static function getEducationInstitutionComponent(): TextInput
    {
        return TextInput::make('education_institution')
            ->label(__('filament.staff.fields.education_institution'))
            ->maxLength(255)
            ->required();
    }

    private static function getGraduatedYearComponent(): TextInput
    {
        return TextInput::make('graduated_year')
            ->label(__('filament.staff.fields.graduated_year'))
            ->numeric()
            ->minValue(1900)
            ->maxValue((int) now()->year)
            ->length(4)
            ->required();
    }
}
