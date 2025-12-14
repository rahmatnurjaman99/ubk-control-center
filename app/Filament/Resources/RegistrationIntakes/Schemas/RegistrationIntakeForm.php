<?php

declare(strict_types=1);

namespace App\Filament\Resources\RegistrationIntakes\Schemas;

use App\Enums\GradeLevel;
use App\Enums\RegistrationStatus;
use App\Models\RegistrationIntake;
use App\Support\AcademicYearResolver;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class RegistrationIntakeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::getAcademicYearSection(),
                self::getPaymentSection(),
                self::getGuardianSection(),
                self::getStudentSection(),
                self::getProcessingSection(),
                self::getDocumentsSection(),
            ])
            ->columns(1);
    }

    private static function getAcademicYearSection(): Section
    {
        return Section::make(__('filament.registration_intakes.fields.academic_year'))
            ->schema([
                Select::make('academic_year_id')
                    ->label(__('filament.registration_intakes.fields.academic_year'))
                    ->relationship('academicYear', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->default(fn (): ?int => AcademicYearResolver::currentId())
                    ->required(),
            ]);
    }

    private static function getPaymentSection(): Section
    {
        return Section::make(__('filament.registration_intakes.sections.payment'))
            ->columns(2)
            ->schema([
                TextInput::make('form_number')
                    ->label(__('filament.registration_intakes.fields.form_number'))
                    ->default(fn (): string => RegistrationIntake::generateFormNumber())
                    ->readOnly()
                    ->required()
                    ->maxLength(50)
                    ->unique(ignoreRecord: true),
                Select::make('payment_method')
                    ->label(__('filament.registration_intakes.fields.payment_method'))
                    ->options([
                        'cash' => __('filament.registration_intakes.payment_methods.cash'),
                        'transfer' => __('filament.registration_intakes.payment_methods.transfer'),
                        // 'card' => __('filament.registration_intakes.payment_methods.card'),
                    ])
                    ->native(false)
                    ->default('cash')
                    ->live()
                    ->afterStateUpdated(function (Set $set, ?string $state): void {
                        if ($state === 'cash') {
                            $set('payment_reference', self::generateCashPaymentReference());

                            return;
                        }

                        $set('payment_reference', null);
                    }),
                TextInput::make('payment_reference')
                    ->label(__('filament.registration_intakes.fields.payment_reference'))
                    ->maxLength(100)
                    ->required(fn (Get $get): bool => $get('payment_method') === 'transfer')
                    ->readOnly(fn (Get $get): bool => $get('payment_method') === 'cash')
                    ->default(fn (): string => self::generateCashPaymentReference())
                    ->helperText(__('filament.registration_intakes.helpers.payment_reference')),
                TextInput::make('payment_amount')
                    ->label(__('filament.registration_intakes.fields.payment_amount'))
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->prefix('IDR')
                    ->required(),
                DatePicker::make('payment_received_at')
                    ->label(__('filament.registration_intakes.fields.payment_received_at'))
                    ->native(false),
            ]);
    }

    private static function getGuardianSection(): Section
    {
        return Section::make(__('filament.registration_intakes.sections.guardian'))
            ->columns(2)
            ->schema([
                TextInput::make('guardian_name')
                    ->label(__('filament.registration_intakes.fields.guardian_name'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('guardian_phone')
                    ->label(__('filament.registration_intakes.fields.guardian_phone'))
                    ->tel()
                    ->maxLength(30)
                    ->required(),
                TextInput::make('guardian_email')
                    ->label(__('filament.registration_intakes.fields.guardian_email'))
                    ->email()
                    ->maxLength(255),
                Textarea::make('guardian_address')
                    ->label(__('filament.registration_intakes.fields.guardian_address'))
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    private static function getStudentSection(): Section
    {
        return Section::make(__('filament.registration_intakes.sections.student'))
            ->columns(2)
            ->schema([
                TextInput::make('student_full_name')
                    ->label(__('filament.registration_intakes.fields.student_full_name'))
                    ->required()
                    ->maxLength(255),
                DatePicker::make('student_date_of_birth')
                    ->label(__('filament.registration_intakes.fields.student_date_of_birth'))
                    ->native(false),
                Select::make('student_gender')
                    ->label(__('filament.registration_intakes.fields.student_gender'))
                    ->options([
                        'male' => __('filament.registration_intakes.gender.male'),
                        'female' => __('filament.registration_intakes.gender.female'),
                    ])
                    ->native(false)
                    ->nullable(),
                Select::make('target_grade_level')
                    ->label(__('filament.registration_intakes.fields.target_grade_level'))
                    ->options(GradeLevel::options())
                    ->native(false),
                Select::make('classroom_id')
                    ->label(__('filament.registration_intakes.fields.classroom'))
                    ->relationship('classroom', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->nullable(),
            ]);
    }

    private static function getProcessingSection(): Section
    {
        return Section::make(__('filament.registration_intakes.sections.processing'))
            ->columns(2)
            ->schema([
                Select::make('status')
                    ->label(__('filament.registration_intakes.fields.status'))
                    ->options(RegistrationStatus::options())
                    ->enum(RegistrationStatus::class)
                    ->native(false)
                    ->default(RegistrationStatus::Pending->value)
                    ->required(),
                Select::make('student_id')
                    ->label(__('filament.students.model.singular'))
                    ->relationship('student', 'full_name')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->nullable(),
                Select::make('processed_by')
                    ->label(__('filament.registration_intakes.fields.processed_by'))
                    ->relationship('processor', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->nullable(),
                DateTimePicker::make('processed_at')
                    ->label(__('filament.registration_intakes.fields.processed_at'))
                    ->seconds(false)
                    ->native(false),
                Textarea::make('notes')
                    ->label(__('filament.registration_intakes.fields.notes'))
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }

    private static function getDocumentsSection(): Section
    {
        return Section::make(__('filament.registration_intakes.fields.documents'))
            ->schema([
                Repeater::make('documents')
                    ->relationship()
                    ->label(__('filament.registration_intakes.fields.documents'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label(__('filament.registration_intakes.fields.document_name'))
                            ->maxLength(255)
                            ->required(),
                        TextInput::make('type')
                            ->label(__('filament.registration_intakes.fields.document_type'))
                            ->maxLength(100),
                        FileUpload::make('file_path')
                            ->label(__('filament.registration_intakes.fields.document_file'))
                            ->directory('registration-intakes')
                            ->disk('public')
                            ->visibility('public')
                            ->required(),
                        Textarea::make('notes')
                            ->label(__('filament.registration_intakes.fields.document_notes'))
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    private static function generateCashPaymentReference(): string
    {
        return 'CASH-' . Str::upper(Str::random(6));
    }
}
