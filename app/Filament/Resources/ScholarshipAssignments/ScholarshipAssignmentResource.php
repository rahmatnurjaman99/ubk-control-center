<?php

declare(strict_types=1);

namespace App\Filament\Resources\ScholarshipAssignments;

use App\Filament\Resources\ScholarshipAssignments\Pages\CreateScholarshipAssignment;
use App\Filament\Resources\ScholarshipAssignments\Pages\EditScholarshipAssignment;
use App\Filament\Resources\ScholarshipAssignments\Pages\ListScholarshipAssignments;
use App\Filament\Resources\ScholarshipAssignments\Schemas\ScholarshipAssignmentForm;
use App\Filament\Resources\ScholarshipAssignments\Tables\ScholarshipAssignmentsTable;
use App\Models\ScholarshipAssignment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ScholarshipAssignmentResource extends Resource
{
    protected static ?string $model = ScholarshipAssignment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTicket;

    public static function form(Schema $schema): Schema
    {
        return ScholarshipAssignmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ScholarshipAssignmentsTable::configure($table);
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation.finance');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.scholarship_assignments.navigation.label');
    }

    public static function getModelLabel(): string
    {
        return __('filament.scholarship_assignments.model.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.scholarship_assignments.model.plural');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListScholarshipAssignments::route('/'),
            'create' => CreateScholarshipAssignment::route('/create'),
            'edit' => EditScholarshipAssignment::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['scholarship', 'student.user']);
    }
}
