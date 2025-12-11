<?php

declare(strict_types=1);

namespace App\Filament\Resources\Students;

use App\Filament\Resources\Students\Pages\EditStudent;
use App\Filament\Resources\Students\Pages\ListStudents;
use App\Filament\Resources\Students\Pages\ViewStudent;
use App\Filament\Resources\Students\RelationManagers\ClassroomAssignmentsRelationManager;
use App\Filament\Resources\Students\RelationManagers\FeesRelationManager;
use App\Filament\Resources\Students\RelationManagers\PromotionApprovalsRelationManager;
use App\Filament\Resources\Students\RelationManagers\TahfidzTargetsRelationManager;
use App\Filament\Resources\Students\Schemas\StudentForm;
use App\Filament\Resources\Students\Schemas\StudentInfolist;
use App\Filament\Resources\Students\Tables\StudentsTable;
use App\Models\Student;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return StudentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StudentsTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StudentInfolist::configure($schema);
    }

    public static function getRelations(): array
    {
        return [
            ClassroomAssignmentsRelationManager::class,
            FeesRelationManager::class,
            PromotionApprovalsRelationManager::class,
            TahfidzTargetsRelationManager::class,
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation.people_students');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.students.navigation.label');
    }

    public static function getModelLabel(): string
    {
        return __('filament.students.model.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.students.model.plural');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStudents::route('/'),
            'view' => ViewStudent::route('/{record}'),
            'edit' => EditStudent::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(self::getTableRelations());
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->with(self::getRecordRelations())
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    /**
     * @return list<string>
     */
    private static function getTableRelations(): array
    {
        return [
            'user',
            'guardian',
            'academicYear',
            'classroom',
            'province',
            'regency',
            'district',
            'village',
        ];
    }

    /**
     * @return list<string>
     */
    private static function getRecordRelations(): array
    {
        return array_merge(
            self::getTableRelations(),
            [
                'documents',
                'registrationDocuments',
            ],
        );
    }
}
