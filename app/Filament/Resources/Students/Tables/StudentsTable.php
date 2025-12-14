<?php

declare(strict_types=1);

namespace App\Filament\Resources\Students\Tables;

use App\Enums\GradeLevel;
use App\Enums\PromotionApprovalStatus;
use App\Enums\StudentStatus;
use App\Filament\Resources\Students\Imports\LegacyStudentImporter;
use App\Support\Tables\Columns\CreatedAtColumn;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\PromotionApproval;
use App\Models\Student;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\Filter;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\ImportAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select as FormSelect;
use Filament\Forms\Components\Textarea as FormTextarea;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Builder;

class StudentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('student_number')
            ->columns([
                self::getPhotoColumn(),
                self::getStudentNumberColumn(),
                self::getFullNameColumn(),
                self::getGuardianColumn(),
                self::getAcademicYearColumn(),
                self::getClassroomColumn(),
                self::getGradeLevelColumn(),
                self::getPromotionEligibilityColumn(),
                self::getStatusColumn(),
                self::getScholarshipsColumn(),
                self::getEnrolledOnColumn(),
                self::getProvinceColumn(),
                self::getRegencyColumn(),
                self::getDistrictColumn(),
                self::getVillageColumn(),
                CreatedAtColumn::make(),
            ])
            ->filters([
                self::getStatusFilter(),
                self::getAcademicYearFilter(),
                self::getCurrentAcademicYearFilter(),
                self::getClassroomFilter(),
                self::getScholarshipFilter(),
                self::getProvinceFilter(),
                self::getRegencyFilter(),
                self::getDistrictFilter(),
                self::getVillageFilter(),
                TrashedFilter::make(),
            ])
            ->headerActions([
                ImportAction::make('importLegacyStudents')
                    ->label(__('filament.students.import.legacy_action'))
                    ->importer(LegacyStudentImporter::class),
            ])
            ->recordActions([
                self::getViewAction(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    self::getPromotionApprovalBulkAction(),
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    private static function getPhotoColumn(): ImageColumn
    {
        return ImageColumn::make('photo_url')
            ->label(__('filament.students.fields.photo'))
            ->circular();
    }

    private static function getStudentNumberColumn(): TextColumn
    {
        return TextColumn::make('student_number')
            ->label(__('filament.students.fields.student_number'))
            ->sortable()
            ->searchable();
    }

    private static function getFullNameColumn(): TextColumn
    {
        return TextColumn::make('full_name')
            ->label(__('filament.students.fields.full_name'))
            ->description(fn ($record): ?string => $record->legacy_reference)
            ->sortable()
            ->searchable()
            ->toggleable(isToggledHiddenByDefault: false);
    }

    private static function getGuardianColumn(): TextColumn
    {
        return TextColumn::make('guardian.full_name')
            ->label(__('filament.students.fields.guardian'))
            ->searchable()
            ->sortable()
            ->placeholder('-');
    }

    private static function getAcademicYearColumn(): TextColumn
    {
        return TextColumn::make('academicYear.name')
            ->label(__('filament.students.fields.academic_year'))
            ->sortable()
            ->toggleable();
    }

    private static function getClassroomColumn(): TextColumn
    {
        return TextColumn::make('classroom.name')
            ->label(__('filament.students.fields.classroom'))
            ->sortable()
            ->toggleable()
            ->placeholder('-');
    }

    private static function getGradeLevelColumn(): TextColumn
    {
        return TextColumn::make('currentGradeLevel')
            ->label(__('filament.classrooms.table.grade_level'))
            ->formatStateUsing(
                fn (GradeLevel|string|null $state): ?string => match (true) {
                    $state instanceof GradeLevel => $state->label(),
                    blank($state) => null,
                    default => GradeLevel::from((string) $state)->label(),
                },
            )
            ->badge()
            ->sortable()
            ->toggleable();
    }

    private static function getPromotionEligibilityColumn(): TextColumn
    {
        return TextColumn::make('promotionEligibility')
            ->label(__('filament.students.actions.eligibility_status'))
            ->state(function (Student $record): string {
                if ($record->hasOutstandingFees()) {
                    return $record->determineCurrentGradeLevel()?->isTerminal()
                        ? __('filament.students.actions.eligibility_pending_graduation_fees')
                        : __('filament.students.actions.eligibility_pending_fees');
                }

                return __('filament.students.actions.eligibility_ready');
            })
            ->badge()
            ->color(fn (Student $record): string => $record->hasOutstandingFees() ? 'warning' : 'success')
            ->toggleable();
    }

    private static function getStatusColumn(): TextColumn
    {
        return TextColumn::make('status')
            ->label(__('filament.students.fields.status'))
            ->formatStateUsing(
                fn (StudentStatus|string|null $state): ?string => match (true) {
                    $state instanceof StudentStatus => $state->getLabel(),
                    blank($state) => null,
                    default => StudentStatus::from((string) $state)->getLabel(),
                },
            )
            ->badge()
            ->color(fn (StudentStatus|string|null $state): ?string => match (true) {
                $state instanceof StudentStatus => $state->getColor(),
                blank($state) => null,
                default => StudentStatus::from((string) $state)->getColor(),
            })
            ->sortable();
    }

    private static function getScholarshipsColumn(): TextColumn
    {
        return TextColumn::make('scholarships.name')
            ->label(__('filament.students.fields.scholarships'))
            ->badge()
            ->separator(', ')
            ->color('info')
            ->toggleable(isToggledHiddenByDefault: true)
            ->placeholder('—');
    }

    private static function getEnrolledOnColumn(): TextColumn
    {
        return TextColumn::make('enrolled_on')
            ->label(__('filament.students.fields.enrolled_on'))
            ->date()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }

    private static function getProvinceColumn(): TextColumn
    {
        return TextColumn::make('province.name')
            ->label(__('filament.students.fields.province'))
            ->toggleable(isToggledHiddenByDefault: true);
    }

    private static function getRegencyColumn(): TextColumn
    {
        return TextColumn::make('regency.name')
            ->label(__('filament.students.fields.regency'))
            ->toggleable(isToggledHiddenByDefault: true);
    }

    private static function getDistrictColumn(): TextColumn
    {
        return TextColumn::make('district.name')
            ->label(__('filament.students.fields.district'))
            ->toggleable(isToggledHiddenByDefault: true);
    }

    private static function getVillageColumn(): TextColumn
    {
        return TextColumn::make('village.name')
            ->label(__('filament.students.fields.village'))
            ->toggleable(isToggledHiddenByDefault: true);
    }

    private static function getStatusFilter(): SelectFilter
    {
        return SelectFilter::make('status')
            ->label(__('filament.students.fields.status'))
            ->options(
                collect(StudentStatus::cases())
                    ->mapWithKeys(fn (StudentStatus $status): array => [$status->value => $status->getLabel() ?? $status->value])
                    ->all(),
            );
    }

    private static function getAcademicYearFilter(): SelectFilter
    {
        return SelectFilter::make('academic_year_id')
            ->label(__('filament.students.fields.academic_year'))
            ->relationship('academicYear', 'name');
    }

    private static function getClassroomFilter(): SelectFilter
    {
        return SelectFilter::make('classroom_id')
            ->label(__('filament.students.fields.classroom'))
            ->relationship('classroom', 'name');
    }

    private static function getProvinceFilter(): SelectFilter
    {
        return SelectFilter::make('province_id')
            ->label(__('filament.students.fields.province'))
            ->relationship('province', 'name')
            ->searchable()
            ->native(false);
    }

    private static function getScholarshipFilter(): SelectFilter
    {
        return SelectFilter::make('scholarships')
            ->label(__('filament.students.filters.scholarships'))
            ->relationship('scholarships', 'name')
            ->searchable()
            ->preload()
            ->multiple()
            ->native(false);
    }

    private static function getRegencyFilter(): SelectFilter
    {
        return SelectFilter::make('regency_id')
            ->label(__('filament.students.fields.regency'))
            ->relationship('regency', 'name')
            ->searchable()
            ->native(false);
    }

    private static function getDistrictFilter(): SelectFilter
    {
        return SelectFilter::make('district_id')
            ->label(__('filament.students.fields.district'))
            ->relationship('district', 'name')
            ->searchable()
            ->native(false);
    }

    private static function getVillageFilter(): SelectFilter
    {
        return SelectFilter::make('village_id')
            ->label(__('filament.students.fields.village'))
            ->relationship('village', 'name')
            ->searchable()
            ->native(false);
    }

    private static function getCurrentAcademicYearFilter(): Filter
    {
        return Filter::make('currentAcademicYear')
            ->label(__('filament.students.filters.current_academic_year'))
            ->toggle()
            ->query(fn (Builder $query): Builder => $query->whereHas(
                'academicYear',
                fn (Builder $subQuery): Builder => $subQuery->where('is_current', true),
            ));
    }

    private static function getPromotionApprovalBulkAction(): BulkAction
    {
        return BulkAction::make('requestPromotionApproval')
            ->label(__('filament.promotion_approvals.bulk_request'))
            ->icon('heroicon-o-check-circle')
            ->requiresConfirmation()
            ->modalHeading(__('filament.students.bulk_promotion.modal_heading'))
            ->modalDescription(__('filament.students.bulk_promotion.modal_description'))
            ->modalSubmitActionLabel(__('filament.students.bulk_promotion.modal_submit'))
            ->deselectRecordsAfterCompletion()
            ->form([
                FormSelect::make('target_academic_year_id')
                    ->label(__('filament.promotion_approvals.fields.target_academic_year'))
                    ->options(fn (): array => AcademicYear::query()
                        ->orderByDesc('starts_on')
                        ->pluck('name', 'id')
                        ->all())
                    ->searchable()
                    ->preload()
                    ->required(),
                FormSelect::make('target_grade_level')
                    ->label(__('filament.promotion_approvals.fields.target_grade_level'))
                    ->options(GradeLevel::options())
                    ->required(),
                FormSelect::make('target_classroom_id')
                    ->label(__('filament.promotion_approvals.fields.target_classroom'))
                    ->options(fn (Get $get): array => Classroom::query()
                        ->when($get('target_academic_year_id'), fn($query, $year) => $query->where('academic_year_id', $year))
                        ->when($get('target_grade_level'), fn($query, $grade) => $query->where('grade_level', $grade))
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->all())
                    ->searchable()
                    ->preload()
                    ->native(false),
                FormTextarea::make('notes')
                    ->label(__('filament.promotion_approvals.fields.notes'))
                    ->rows(3),
            ])
            ->action(function (EloquentCollection $records, array $data): void {
                $count = 0;

                foreach ($records as $student) {
                    $hasPending = PromotionApproval::query()
                        ->where('student_id', $student->id)
                        ->where('status', PromotionApprovalStatus::Pending)
                        ->exists();

                    if ($hasPending) {
                        continue;
                    }

                    PromotionApproval::create([
                        'student_id' => $student->id,
                        'current_academic_year_id' => $student->academic_year_id,
                        'target_academic_year_id' => $data['target_academic_year_id'],
                        'target_classroom_id' => $data['target_classroom_id'] ?? null,
                        'target_grade_level' => $data['target_grade_level'],
                        'outstanding_amount' => $student->getOutstandingFees()->sum(
                            fn($fee) => $fee->outstanding_amount,
                        ),
                        'status' => PromotionApprovalStatus::Pending,
                        'requested_by' => auth()->id(),
                        'notes' => $data['notes'] ?? null,
                    ]);

                    $count++;
                }

                if ($count > 0) {
                    Notification::make()
                        ->title(__('filament.promotion_approvals.notifications.requested', ['count' => $count]))
                        ->body(__('filament.students.bulk_promotion.notification_body'))
                        ->success()
                        ->send();
                } else {
                    Notification::make()
                        ->title(__('filament.promotion_approvals.notifications.none_created'))
                        ->warning()
                        ->send();
                }
            });
    }

    private static function getViewAction(): ViewAction
    {
        return ViewAction::make();
    }
}
