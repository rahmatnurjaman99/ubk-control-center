<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\GradeLevel;
use App\Enums\PromotionApprovalStatus;
use App\Filament\Resources\PromotionApprovalResource\Pages;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\PromotionApproval;
use App\Support\Students\PromoteStudentAction;
use BackedEnum;
use UnitEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PromotionApprovalResource extends Resource
{
    protected static ?string $model = PromotionApproval::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-check-badge';

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('student.full_name')
                    ->label(__('filament.students.fields.full_name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('targetAcademicYear.name')
                    ->label(__('filament.promotion_approvals.fields.target_academic_year'))
                    ->sortable(),
                TextColumn::make('target_grade_level')
                    ->label(__('filament.promotion_approvals.fields.target_grade_level'))
                    ->formatStateUsing(fn (?string $state): ?string => blank($state) ? null : GradeLevel::tryFrom($state)?->label())
                    ->badge()
                    ->sortable(),
                TextColumn::make('targetClassroom.name')
                    ->label(__('filament.promotion_approvals.fields.target_classroom'))
                    ->placeholder('-')
                    ->toggleable(),
                TextColumn::make('outstanding_amount')
                    ->label(__('filament.promotion_approvals.fields.outstanding_amount'))
                    ->money('IDR')
                    ->alignRight(),
                TextColumn::make('status')
                    ->label(__('filament.promotion_approvals.fields.status'))
                    ->badge()
                    ->formatStateUsing(fn (PromotionApprovalStatus|string|null $state): ?string => match (true) {
                        $state instanceof PromotionApprovalStatus => $state->getLabel(),
                        blank($state) => null,
                        default => PromotionApprovalStatus::from((string) $state)->getLabel(),
                    })
                    ->color(fn (PromotionApprovalStatus|string|null $state): ?string => match (true) {
                        $state instanceof PromotionApprovalStatus => $state->getColor(),
                        blank($state) => null,
                        default => PromotionApprovalStatus::from((string) $state)->getColor(),
                    })
                    ->sortable(),
                TextColumn::make('requester.name')
                    ->label(__('filament.promotion_approvals.fields.requested_by'))
                    ->placeholder('-')
                    ->toggleable(),
                TextColumn::make('approver.name')
                    ->label(__('filament.promotion_approvals.fields.approved_by'))
                    ->placeholder('-')
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('filament.promotion_approvals.fields.status'))
                    ->options(PromotionApprovalStatus::options()),
                SelectFilter::make('target_academic_year_id')
                    ->label(__('filament.promotion_approvals.fields.target_academic_year'))
                    ->relationship('targetAcademicYear', 'name'),
                Filter::make('currentAcademicYear')
                    ->label(__('filament.promotion_approvals.filters.current_academic_year'))
                    ->toggle()
                    ->query(fn (Builder $query): Builder => $query->whereHas(
                        'targetAcademicYear',
                        fn (Builder $subQuery): Builder => $subQuery->where('is_current', true),
                    )),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label(__('filament.promotion_approvals.actions.approve'))
                    ->icon('heroicon-o-check')
                    ->visible(fn (PromotionApproval $record): bool => $record->status === PromotionApprovalStatus::Pending)
                    ->schema(self::getApprovalFormComponents())
                    ->action(function (PromotionApproval $record, array $data, PromoteStudentAction $promoter): void {
                        DB::transaction(function () use ($record, $data, $promoter): void {
                            $record->update([
                                'target_academic_year_id' => $data['target_academic_year_id'],
                                'target_grade_level' => $data['target_grade_level'],
                                'target_classroom_id' => $data['target_classroom_id'] ?? null,
                                'decision_notes' => $data['decision_notes'] ?? null,
                                'status' => PromotionApprovalStatus::Approved,
                                'approved_by' => Auth::id(),
                                'approved_at' => now(),
                            ]);

                            $student = $record->student;
                            $academicYear = AcademicYear::findOrFail($data['target_academic_year_id']);
                            $classroom = filled($data['target_classroom_id'])
                                ? Classroom::findOrFail($data['target_classroom_id'])
                                : null;
                            $gradeLevel = filled($data['target_grade_level'])
                                ? GradeLevel::from($data['target_grade_level'])
                                : null;

                            $promoter->execute(
                                student: $student,
                                targetAcademicYear: $academicYear,
                                targetClassroom: $classroom,
                                overrideGradeLevel: $gradeLevel,
                            );
                        });
                    }),
                Action::make('reject')
                    ->label(__('filament.promotion_approvals.actions.reject'))
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->visible(fn (PromotionApproval $record): bool => $record->status === PromotionApprovalStatus::Pending)
                    ->schema([
                        Textarea::make('decision_notes')
                            ->label(__('filament.promotion_approvals.fields.decision_notes'))
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (PromotionApproval $record, array $data): void {
                        $record->update([
                            'status' => PromotionApprovalStatus::Rejected,
                            'decision_notes' => $data['decision_notes'],
                            'approved_by' => Auth::id(),
                            'approved_at' => now(),
                        ]);
                    }),
            ]);
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation.people_students');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.promotion_approvals.navigation.label');
    }

    public static function getModelLabel(): string
    {
        return __('filament.promotion_approvals.model.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.promotion_approvals.model.plural');
    }

    /**
     * @return array<int, \Filament\Forms\Components\Component>
     */
    private static function getApprovalFormComponents(): array
    {
        return [
            Select::make('target_academic_year_id')
                ->label(__('filament.promotion_approvals.fields.target_academic_year'))
                ->options(fn (): array => AcademicYear::query()
                    ->orderByDesc('starts_on')
                    ->pluck('name', 'id')
                    ->all())
                ->required()
                ->searchable()
                ->preload(),
            Select::make('target_grade_level')
                ->label(__('filament.promotion_approvals.fields.target_grade_level'))
                ->options(GradeLevel::options())
                ->required(),
            Select::make('target_classroom_id')
                ->label(__('filament.promotion_approvals.fields.target_classroom'))
                ->options(fn (\Filament\Schemas\Components\Utilities\Get $get): array => Classroom::query()
                    ->when($get('target_academic_year_id'), fn ($query, $year) => $query->where('academic_year_id', $year))
                    ->when($get('target_grade_level'), fn ($query, $grade) => $query->where('grade_level', $grade))
                    ->orderBy('name')
                    ->pluck('name', 'id')
                    ->all())
                ->searchable()
                ->preload()
                ->native(false),
            Textarea::make('decision_notes')
                ->label(__('filament.promotion_approvals.fields.decision_notes'))
                ->rows(3),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPromotionApprovals::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
