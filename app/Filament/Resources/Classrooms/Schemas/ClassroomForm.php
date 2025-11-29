<?php

declare(strict_types=1);

namespace App\Filament\Resources\Classrooms\Schemas;

use App\Enums\GradeLevel;
use App\Enums\SchoolLevel;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ClassroomForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::getCodeComponent(),
                self::getNameComponent(),
                self::getSchoolLevelComponent(),
                self::getGradeLevelComponent(),
                self::getAcademicYearComponent(),
                self::getCapacityComponent(),
                self::getHomeroomStaffComponent(),
                self::getDescriptionComponent(),
            ])
            ->columns(2);
    }

    private static function getGradeLevelComponent(): Select
    {
        return Select::make('grade_level')
            ->label(__('filament.classrooms.fields.grade_level'))
            ->options(function (Get $get): array {
                $schoolLevel = $get('school_level');

                if ($schoolLevel === null) {
                    return GradeLevel::options();
                }

                $values = array_map(
                    static fn (SchoolLevel $level): string => $level->value,
                    SchoolLevel::cases(),
                );

                if (! in_array($schoolLevel, $values, true)) {
                    return GradeLevel::options();
                }

                return SchoolLevel::from($schoolLevel)->gradeOptions();
            })
            ->live()
            ->required()
            ->searchable()
            ->preload();
    }

    private static function getCodeComponent(): TextInput
    {
        return TextInput::make('code')
            ->label(__('filament.classrooms.fields.code'))
            ->required()
            ->unique(ignoreRecord: true)
            ->maxLength(50);
    }

    private static function getNameComponent(): TextInput
    {
        return TextInput::make('name')
            ->label(__('filament.classrooms.fields.name'))
            ->required()
            ->maxLength(255);
    }

    private static function getSchoolLevelComponent(): Select
    {
        return Select::make('school_level')
            ->label(__('filament.classrooms.fields.school_level'))
            ->options(SchoolLevel::options())
            ->required();
    }

    private static function getAcademicYearComponent(): Select
    {
        return Select::make('academic_year_id')
            ->label(__('filament.classrooms.fields.academic_year'))
            ->relationship('academicYear', 'name')
            ->searchable()
            ->preload()
            ->required();
    }

    private static function getCapacityComponent(): TextInput
    {
        return TextInput::make('capacity')
            ->label(__('filament.classrooms.fields.capacity'))
            ->numeric()
            ->minValue(1)
            ->maxValue(200);
    }

    private static function getHomeroomStaffComponent(): Select
    {
        return Select::make('homeroom_staff_id')
            ->label(__('filament.classrooms.fields.homeroom_staff'))
            ->relationship('homeroomStaff', 'staff_name')
            ->searchable()
            ->preload()
            ->nullable();
    }

    private static function getDescriptionComponent(): Textarea
    {
        return Textarea::make('description')
            ->label(__('filament.classrooms.fields.description'))
            ->rows(4)
            ->columnSpanFull();
    }
}
