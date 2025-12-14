<?php

declare(strict_types=1);

namespace App\Filament\Resources\Students\Schemas;

use App\Enums\StudentStatus;
use App\Models\District;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Student;
use App\Models\Village;
use App\Support\AcademicYearResolver;
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

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::getUserComponent(),
                self::getGuardianComponent(),
                self::getAcademicYearComponent(),
                self::getClassroomComponent(),
                self::getStudentNumberComponent(),
                self::getFullNameComponent(),
                self::getPhotoComponent(),
                self::getDateOfBirthComponent(),
                self::getGenderComponent(),
                self::getAddressComponent(),
                self::getProvinceComponent(),
                self::getRegencyComponent(),
                self::getDistrictComponent(),
                self::getVillageComponent(),
                self::getStatusComponent(),
                self::getEnrolledOnComponent(),
                self::getLegacyReferenceComponent(),
                self::getRegistrationDocumentsSection(),
                self::getDocumentsSection(),
            ])
            ->columns(2);
    }

    private static function getUserComponent(): Select
    {
        return Select::make('user_id')
            ->label(__('filament.users.model.singular'))
            ->relationship('user', 'name')
            ->searchable()
            ->preload()
            ->unique(ignoreRecord: true)
            ->nullable()
            ->disabled();
    }

    private static function getGuardianComponent(): Select
    {
        return Select::make('guardian_id')
            ->label(__('filament.students.fields.guardian'))
            ->relationship('guardian', 'full_name')
            ->searchable()
            ->preload()
            ->nullable()
            ->disabled();
    }

    private static function getAcademicYearComponent(): Select
    {
        return Select::make('academic_year_id')
            ->label(__('filament.students.fields.academic_year'))
            ->relationship('academicYear', 'name')
            ->searchable()
            ->preload()
            ->default(fn (): ?int => AcademicYearResolver::currentId())
            ->nullable()
            ->disabled();
    }

    private static function getClassroomComponent(): Select
    {
        return Select::make('classroom_id')
            ->label(__('filament.students.fields.classroom'))
            ->relationship('classroom', 'name')
            ->searchable()
            ->preload()
            ->nullable()
            ->disabled();
    }

    private static function getStudentNumberComponent(): TextInput
    {
        return TextInput::make('student_number')
            ->label(__('filament.students.fields.student_number'))
            ->required()
            ->maxLength(50)
            ->unique(ignoreRecord: true)
            ->disabled();
    }

    private static function getFullNameComponent(): TextInput
    {
        return TextInput::make('full_name')
            ->label(__('filament.students.fields.full_name'))
            ->required()
            ->maxLength(255)
            ->columnSpanFull();
    }

    private static function getPhotoComponent(): FileUpload
    {
        return FileUpload::make('photo_path')
            ->label(__('filament.students.fields.photo'))
            ->image()
            ->avatar()
            ->imageEditor()
            ->directory('student-photos')
            ->disk('public')
            ->visibility('public')
            ->maxSize(5_000);
    }

    private static function getDateOfBirthComponent(): DatePicker
    {
        return DatePicker::make('date_of_birth')
            ->label(__('filament.students.fields.date_of_birth'))
            ->native(false)
            ->maxDate(now());
    }

    private static function getGenderComponent(): Select
    {
        return Select::make('gender')
            ->label(__('filament.students.fields.gender'))
            ->options(self::getGenderOptions())
            ->native(false)
            ->nullable();
    }

    private static function getAddressComponent(): Textarea
    {
        return Textarea::make('address')
            ->label(__('filament.students.fields.address'))
            ->rows(3)
            ->columnSpanFull();
    }

    private static function getProvinceComponent(): Select
    {
        return Select::make('province_id')
            ->label(__('filament.students.fields.province'))
            ->options(fn (): array => Province::query()->orderBy('name')->pluck('name', 'id')->all())
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
            ->label(__('filament.students.fields.regency'))
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
            ->label(__('filament.students.fields.district'))
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
            ->label(__('filament.students.fields.village'))
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

    private static function getStatusComponent(): Select
    {
        return Select::make('status')
            ->label(__('filament.students.fields.status'))
            ->options(self::getStatusOptions())
            ->enum(StudentStatus::class)
            ->native(false)
            ->required()
            ->default(StudentStatus::Active->value)
            ->disabled();
    }

    private static function getEnrolledOnComponent(): DatePicker
    {
        return DatePicker::make('enrolled_on')
            ->label(__('filament.students.fields.enrolled_on'))
            ->native(false);
    }

    private static function getLegacyReferenceComponent(): TextInput
    {
        return TextInput::make('legacy_reference')
            ->label(__('filament.students.fields.legacy_reference'))
            ->maxLength(255)
            ->columnSpanFull()
            ->disabled();
    }

    private static function getDocumentsSection(): Section
    {
        return Section::make(__('filament.students.sections.student_documents'))
            ->schema([
                Repeater::make('documents')
                    ->relationship()
                    ->label(__('filament.students.fields.documents'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label(__('filament.students.fields.document_name'))
                            ->maxLength(255)
                            ->required(),
                        TextInput::make('type')
                            ->label(__('filament.students.fields.document_type'))
                            ->maxLength(100),
                        FileUpload::make('file_path')
                            ->label(__('filament.students.fields.document_file'))
                            ->directory('student-documents')
                            ->disk('public')
                            ->visibility('public')
                            ->required(),
                        Textarea::make('notes')
                            ->label(__('filament.students.fields.document_notes'))
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ])
            ->columnSpanFull();
    }

    private static function getRegistrationDocumentsSection(): Section
    {
        return Section::make(__('filament.students.sections.registration_documents'))
            ->schema([
                Repeater::make('registration_documents_preview')
                    ->label(__('filament.students.sections.registration_documents'))
                    ->columns(2)
                    ->addable(false)
                    ->deletable(false)
                    ->reorderable(false)
                    ->defaultItems(0)
                    ->dehydrated(false)
                    ->schema([
                        TextInput::make('name')
                            ->label(__('filament.students.fields.document_name'))
                            ->disabled(),
                        TextInput::make('type')
                            ->label(__('filament.students.fields.document_type'))
                            ->disabled(),
                        FileUpload::make('file_path')
                            ->label(__('filament.students.fields.document_file'))
                            ->disk('public')
                            ->directory('registration-intakes')
                            ->visibility('public')
                            ->previewable(false)
                            ->downloadable()
                            ->disabled(),
                        Textarea::make('notes')
                            ->label(__('filament.students.fields.document_notes'))
                            ->rows(3)
                            ->disabled()
                            ->columnSpanFull(),
                    ])
                    ->afterStateHydrated(function (Repeater $component, ?Student $record): void {
                        if (! $record) {
                            $component->state([]);

                            return;
                        }

                        $documents = $record->registrationDocuments()
                            ->orderByDesc('registration_intake_documents.created_at')
                            ->get([
                                'registration_intake_documents.name as name',
                                'registration_intake_documents.type as type',
                                'registration_intake_documents.file_path as file_path',
                                'registration_intake_documents.notes as notes',
                            ])
                            ->map(fn ($doc): array => [
                                'name' => $doc->name,
                                'type' => $doc->type,
                                'file_path' => $doc->file_path,
                                'notes' => $doc->notes,
                            ])
                            ->all();

                        $component->state($documents);
                    }),
            ])
            ->columnSpanFull()
            ->visible(fn (?Student $record): bool => $record?->registrationDocuments()->exists() ?? false);
    }

    /**
     * @return array<string, string>
     */
    private static function getGenderOptions(): array
    {
        return [
            'male' => __('filament.students.genders.male'),
            'female' => __('filament.students.genders.female'),
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function getStatusOptions(): array
    {
        $cases = StudentStatus::cases();

        $options = [];

        foreach ($cases as $status) {
            $options[$status->value] = $status->getLabel() ?? $status->value;
        }

        return $options;
    }
}
