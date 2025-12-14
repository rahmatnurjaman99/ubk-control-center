<?php

declare(strict_types=1);

namespace App\Filament\Resources\Subjects\Schemas;

use App\Enums\SchoolLevel;
use App\Models\Classroom;
use App\Models\Subject;
use App\Support\AcademicYearResolver;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;

class SubjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::getAcademicYearComponent(),
                self::getCodeComponent(),
                self::getNameComponent(),
                self::getCategoryComponent(),
                self::getSchoolLevelComponent(),
                self::getIsCompulsoryComponent(),
                self::getCreditHoursComponent(),
                self::getClassroomsComponent(),
                self::getDescriptionComponent(),
            ])
            ->columns(2);
    }

    private static function getCodeComponent(): TextInput
    {
        return TextInput::make('code')
            ->label(__('filament.subjects.fields.code'))
            ->required()
            ->unique(ignoreRecord: true)
            ->maxLength(50);
    }

    private static function getNameComponent(): TextInput
    {
        return TextInput::make('name')
            ->label(__('filament.subjects.fields.name'))
            ->required()
            ->maxLength(255);
    }

    private static function getCategoryComponent(): Select
    {
        return Select::make('subject_category_id')
            ->label(__('filament.subjects.fields.category'))
            ->relationship('category', 'name')
            ->searchable()
            ->preload()
            ->required()
            ->createOptionForm([
                TextInput::make('name')
                    ->label(__('filament.subjects.fields.category_name'))
                    ->live()
                    ->partiallyRenderComponentsAfterStateUpdated(['slug'])
                    ->afterStateUpdated(
                        function (Set $set, ?string $state): void {
                            $set('slug', blank($state) ? null : Str::slug($state));
                        },
                    )
                    ->required()
                    ->maxLength(100),
                TextInput::make('slug')
                    ->label(__('filament.subjects.fields.category_slug'))
                    ->required()
                    ->maxLength(100)
                    ->unique('subject_categories', 'slug', ignoreRecord: true),
                Textarea::make('description')
                    ->label(__('filament.subjects.fields.category_description'))
                    ->rows(3),
            ]);
    }

    private static function getSchoolLevelComponent(): Select
    {
        return Select::make('school_level')
            ->label(__('filament.subjects.fields.school_level'))
            ->options(SchoolLevel::options())
            ->live()
            ->partiallyRenderComponentsAfterStateUpdated(['classrooms'])
            ->afterStateUpdated(fn (Set $set): static => $set('classrooms', []))
            ->required()
            ->native(false);
    }

    private static function getAcademicYearComponent(): Select
    {
        return Select::make('academic_year_id')
            ->label(__('filament.subjects.fields.academic_year'))
            ->relationship('academicYear', 'name')
            ->searchable()
            ->preload()
            ->live()
            ->partiallyRenderComponentsAfterStateUpdated(['classrooms'])
            ->afterStateUpdated(fn (Set $set): static => $set('classrooms', []))
            ->default(fn (): ?int => AcademicYearResolver::currentId())
            ->nullable();
    }

    private static function getIsCompulsoryComponent(): Toggle
    {
        return Toggle::make('is_compulsory')
            ->label(__('filament.subjects.fields.is_compulsory'))
            ->default(true);
    }

    private static function getCreditHoursComponent(): TextInput
    {
        return TextInput::make('credit_hours')
            ->label(__('filament.subjects.fields.credit_hours'))
            ->numeric()
            ->minValue(1)
            ->maxValue(40)
            ->nullable();
    }

    private static function getDescriptionComponent(): Textarea
    {
        return Textarea::make('description')
            ->label(__('filament.subjects.fields.description'))
            ->rows(4)
            ->columnSpanFull();
    }

    private static function getClassroomsComponent(): Select
    {
        return Select::make('classrooms')
            ->label(__('filament.subjects.fields.classrooms'))
            ->relationship('classrooms', 'name')
            ->options(fn (Get $get, ?Subject $record): array => self::getClassroomOptions(
                schoolLevel: $get('school_level') ?? $record?->school_level,
                academicYearId: $get('academic_year_id') ?? $record?->academic_year_id,
                record: $record,
            ))
            ->searchable()
            ->multiple()
            ->preload()
            ->columnSpanFull();
    }

    /**
     * @param  SchoolLevel|string|null  $schoolLevel
     * @return array<int, string>
     */
    private static function getClassroomOptions(
        SchoolLevel|string|null $schoolLevel,
        int|string|null $academicYearId,
        ?Subject $record,
    ): array
    {
        $levelValue = $schoolLevel instanceof SchoolLevel ? $schoolLevel->value : $schoolLevel;

        $query = Classroom::query()
            ->orderBy('name');

        if (filled($levelValue)) {
            $query->where('school_level', $levelValue);
        }

        if (filled($academicYearId)) {
            $query->where('academic_year_id', $academicYearId);
        }

        $options = $query->pluck('name', 'id')->all();

        if ($record instanceof Subject && $record->exists) {
            $selected = $record->classrooms()
                ->whereNotIn('classrooms.id', array_keys($options))
                ->pluck('classrooms.name', 'classrooms.id')
                ->all();

            $options += $selected;
        }

        return $options;
    }
}
