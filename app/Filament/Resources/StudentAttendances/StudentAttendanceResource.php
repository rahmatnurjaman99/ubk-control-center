<?php

declare(strict_types=1);

namespace App\Filament\Resources\StudentAttendances;

use App\Filament\Resources\StudentAttendances\Pages\CreateStudentAttendance;
use App\Filament\Resources\StudentAttendances\Pages\EditStudentAttendance;
use App\Filament\Resources\StudentAttendances\Pages\ListStudentAttendances;
use App\Filament\Resources\StudentAttendances\Schemas\StudentAttendanceForm;
use App\Filament\Resources\StudentAttendances\Tables\StudentAttendancesTable;
use App\Models\StudentAttendance;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StudentAttendanceResource extends Resource
{
    protected static ?string $model = StudentAttendance::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static ?string $recordTitleAttribute = 'recorded_on';

    public static function form(Schema $schema): Schema
    {
        return StudentAttendanceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StudentAttendancesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStudentAttendances::route('/'),
            'create' => CreateStudentAttendance::route('/create'),
            'edit' => EditStudentAttendance::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation.attendance');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.student_attendances.navigation.label');
    }

    public static function getModelLabel(): string
    {
        return __('filament.student_attendances.model.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.student_attendances.model.plural');
    }
}
