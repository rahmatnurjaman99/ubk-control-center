<?php

declare(strict_types=1);

namespace App\Filament\Resources\SubjectCategories\RelationManagers;

use App\Enums\SchoolLevel;
use App\Models\Subject;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SubjectsRelationManager extends RelationManager
{
    protected static string $relationship = 'subjects';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('code')
                    ->label(__('filament.subjects.fields.code'))
                    ->required()
                    ->maxLength(50)
                    ->unique(ignoreRecord: true),
                TextInput::make('name')
                    ->label(__('filament.subjects.fields.name'))
                    ->required()
                    ->maxLength(255),
                Select::make('subject_category_id')
                    ->label(__('filament.subjects.fields.category'))
                    ->relationship('category', 'name')
                    ->default(fn (): int => (int) $this->ownerRecord->getKey())
                    ->disabled()
                    ->dehydrated()
                    ->required(),
                Select::make('school_level')
                    ->label(__('filament.subjects.fields.school_level'))
                    ->options(SchoolLevel::options())
                    ->native(false)
                    ->required(),
                Select::make('academic_year_id')
                    ->label(__('filament.subjects.fields.academic_year'))
                    ->relationship('academicYear', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                Toggle::make('is_compulsory')
                    ->label(__('filament.subjects.fields.is_compulsory'))
                    ->default(true),
                TextInput::make('credit_hours')
                    ->label(__('filament.subjects.fields.credit_hours'))
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(40)
                    ->nullable(),
                Textarea::make('description')
                    ->label(__('filament.subjects.fields.description'))
                    ->rows(4)
                    ->columnSpanFull(),
                Select::make('classrooms')
                    ->label(__('filament.subjects.fields.classrooms'))
                    ->relationship('classrooms', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn (Builder $query): Builder => $query->with(['academicYear', 'classrooms']),
            )
            ->defaultSort('name')
            ->columns([
                TextColumn::make('code')
                    ->label(__('filament.subjects.table.code'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label(__('filament.subjects.table.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('academicYear.name')
                    ->label(__('filament.subjects.table.academic_year'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('school_level')
                    ->label(__('filament.subjects.table.school_level'))
                    ->formatStateUsing(
                        fn (?string $state): ?string => $state !== null ? SchoolLevel::from($state)->label() : null,
                    )
                    ->badge()
                    ->sortable(),
                IconColumn::make('is_compulsory')
                    ->label(__('filament.subjects.table.is_compulsory'))
                    ->boolean(),
                TextColumn::make('credit_hours')
                    ->label(__('filament.subjects.table.credit_hours'))
                    ->formatStateUsing(
                        fn (?int $state): string => $state !== null ? (string) $state : '-',
                    )
                    ->sortable()
                    ->alignRight(),
                TextColumn::make('classrooms_summary')
                    ->label(__('filament.subjects.table.classrooms'))
                    ->getStateUsing(
                        fn (Subject $record): string => $record->classrooms
                            ->pluck('name')
                            ->implode(', '),
                    )
                    ->formatStateUsing(
                        fn (string $state): string => $state !== '' ? $state : '-',
                    )
                    ->toggleable()
                    ->wrap(),
                TextColumn::make('updated_at')
                    ->label(__('filament.subjects.table.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make()
                    ->label(__('filament.subjects.filters.trashed')),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ]);
    }
}
