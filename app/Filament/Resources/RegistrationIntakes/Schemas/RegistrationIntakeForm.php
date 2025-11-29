<?php

declare(strict_types=1);

namespace App\Filament\Resources\RegistrationIntakes\Schemas;

use App\Enums\GradeLevel;
use App\Enums\RegistrationStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RegistrationIntakeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::getPaymentSection(),
                self::getGuardianSection(),
                self::getStudentSection(),
                self::getProcessingSection(),
                self::getDocumentsSection(),
            ])
            ->columns(1);
    }

    private static function getPaymentSection(): Section
    {
        return Section::make(__('filament.registration_intakes.sections.payment'))
            ->columns(2)
            ->schema([
                TextInput::make('form_number')
                    ->label(__('filament.registration_intakes.fields.form_number'))
                    ->required()
                    ->maxLength(50)
                    ->unique(ignoreRecord: true),
                TextInput::make('payment_reference')
                    ->label(__('filament.registration_intakes.fields.payment_reference'))
                    ->maxLength(100),
                TextInput::make('payment_method')
                    ->label(__('filament.registration_intakes.fields.payment_method'))
                    ->maxLength(50),
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
                TextInput::make('student_gender')
                    ->label(__('filament.registration_intakes.fields.student_gender'))
                    ->maxLength(20),
                Select::make('target_grade_level')
                    ->label(__('filament.registration_intakes.fields.target_grade_level'))
                    ->options(GradeLevel::options())
                    ->native(false),
                Select::make('academic_year_id')
                    ->label(__('filament.registration_intakes.fields.academic_year'))
                    ->relationship('academicYear', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->nullable(),
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
}
