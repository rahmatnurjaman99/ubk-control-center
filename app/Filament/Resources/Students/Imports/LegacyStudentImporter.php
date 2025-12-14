<?php

declare(strict_types=1);

namespace App\Filament\Resources\Students\Imports;

use App\Enums\StudentStatus;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\ClassroomAssignment;
use App\Models\Guardian;
use App\Models\Student;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Throwable;

class LegacyStudentImporter extends Importer
{
    protected static ?string $model = Student::class;

    private ?Guardian $guardian = null;

    private ?AcademicYear $academicYear = null;

    private ?Classroom $classroom = null;

    /**
     * @return array<ImportColumn>
     */
    public static function getColumns(): array
    {
        $statusValues = array_map(
            static fn (StudentStatus $status): string => $status->value,
            StudentStatus::cases(),
        );

        return [
            ImportColumn::make('legacy_reference')
                ->label(__('filament.students.fields.legacy_reference'))
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('student_number')
                ->label(__('filament.students.fields.student_number'))
                ->helperText(__('filament.students.import.helpers.student_number'))
                ->rules(['nullable', 'max:50']),
            ImportColumn::make('full_name')
                ->label(__('filament.students.fields.full_name'))
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('date_of_birth')
                ->label(__('filament.students.fields.date_of_birth'))
                ->castStateUsing(fn (?string $state): ?string => static::normalizeDate($state))
                ->rules(['nullable', 'date']),
            ImportColumn::make('gender')
                ->label(__('filament.students.fields.gender'))
                ->castStateUsing(fn (?string $state): ?string => static::normalizeString($state))
                ->rules(['nullable', 'max:20']),
            ImportColumn::make('status')
                ->label(__('filament.students.fields.status'))
                ->castStateUsing(fn (?string $state): ?string => static::normalizeStatus($state))
                ->rules(['nullable', Rule::in($statusValues)]),
            ImportColumn::make('enrolled_on')
                ->label(__('filament.students.fields.enrolled_on'))
                ->castStateUsing(fn (?string $state): ?string => static::normalizeDate($state))
                ->rules(['nullable', 'date']),
            ImportColumn::make('guardian_legacy_reference')
                ->label(__('filament.guardians.fields.legacy_reference'))
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('guardian_number')
                ->label(__('filament.guardians.fields.guardian_number'))
                ->rules(['nullable', 'max:50']),
            ImportColumn::make('guardian_full_name')
                ->label(__('filament.guardians.fields.full_name'))
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('guardian_relationship')
                ->label(__('filament.guardians.fields.relationship'))
                ->castStateUsing(fn (?string $state): ?string => static::normalizeString($state))
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('guardian_phone')
                ->label(__('filament.guardians.fields.phone'))
                ->rules(['nullable', 'max:30']),
            ImportColumn::make('guardian_email')
                ->label(__('filament.guardians.fields.email'))
                ->rules(['nullable', 'email', 'max:255']),
            ImportColumn::make('guardian_occupation')
                ->label(__('filament.guardians.fields.occupation'))
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('guardian_address')
                ->label(__('filament.guardians.fields.address'))
                ->rules(['nullable', 'string']),
            ImportColumn::make('academic_year_code')
                ->label(__('filament.students.fields.academic_year'))
                ->helperText(__('filament.students.import.helpers.academic_year'))
                ->rules(['nullable', Rule::exists('academic_years', 'code')]),
            ImportColumn::make('classroom_code')
                ->label(__('filament.students.fields.classroom'))
                ->helperText(__('filament.students.import.helpers.classroom'))
                ->rules(['nullable', Rule::exists('classrooms', 'code')]),
            ImportColumn::make('classroom_assigned_on')
                ->label(__('filament.students.import.columns.classroom_assigned_on'))
                ->castStateUsing(fn (?string $state): ?string => static::normalizeDate($state))
                ->rules(['nullable', 'date']),
        ];
    }

    public function resolveRecord(): ?Student
    {
        $legacyReference = $this->data['legacy_reference'] ?? null;
        $studentNumber = $this->data['student_number'] ?? null;

        if ($legacyReference !== null) {
            $record = Student::withTrashed()
                ->where('legacy_reference', $legacyReference)
                ->first();

            if ($record instanceof Student) {
                return $record;
            }
        }

        if ($studentNumber !== null) {
            $record = Student::withTrashed()
                ->where('student_number', $studentNumber)
                ->first();

            if ($record instanceof Student) {
                return $record;
            }
        }

        return new Student();
    }

    protected function beforeFill(): void
    {
        $this->guardian = $this->resolveGuardian();
        $this->academicYear = $this->resolveAcademicYear();
        $this->classroom = $this->resolveClassroom();

        if ($this->academicYear === null && $this->classroom instanceof Classroom) {
            $this->academicYear = $this->classroom->academicYear;
        }

        if (blank($this->data['student_number'] ?? null)) {
            $this->data['student_number'] = $this->generateStudentNumber();
        }

        if (blank($this->data['status'] ?? null)) {
            $this->data['status'] = StudentStatus::Active->value;
        }

        if (blank($this->data['guardian_relationship'] ?? null)) {
            $this->data['guardian_relationship'] = 'parent';
        }
    }

    public function fillRecord(): void
    {
        $this->record->fill([
            'student_number' => $this->data['student_number'],
            'full_name' => $this->data['full_name'],
            'date_of_birth' => $this->data['date_of_birth'] ?? null,
            'gender' => $this->data['gender'] ?? null,
            'status' => $this->data['status'],
            'enrolled_on' => $this->data['enrolled_on'] ?? null,
            'legacy_reference' => $this->data['legacy_reference'],
        ]);

        $this->record->guardian()->associate($this->guardian);
        $this->record->academicYear()->associate($this->academicYear);
        $this->record->classroom()->associate($this->classroom);
    }

    protected function afterSave(): void
    {
        if (! $this->record->classroom_id || ! $this->classroom instanceof Classroom) {
            return;
        }

        $assignment = ClassroomAssignment::firstOrNew([
            'student_id' => $this->record->id,
            'classroom_id' => $this->classroom->id,
            'academic_year_id' => $this->academicYear?->id ?? $this->classroom->academic_year_id,
        ]);

        $assignment->grade_level = $this->classroom->grade_level?->value;
        $assignment->assigned_on ??= $this->data['classroom_assigned_on']
            ?? $this->data['enrolled_on']
            ?? now()->toDateString();
        $assignment->removed_on = null;

        if (blank($assignment->notes)) {
            $assignment->notes = __('filament.students.import.assignment_note', [
                'reference' => $this->record->legacy_reference,
            ]);
        }

        $assignment->save();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        return __('filament.students.import.notifications.completed', [
            'success' => $import->successful_rows,
            'failed' => $import->getFailedRowsCount(),
            'total' => $import->total_rows,
        ]);
    }

    private function resolveGuardian(): ?Guardian
    {
        $legacyReference = static::normalizeString($this->data['guardian_legacy_reference'] ?? null);
        $guardianNumber = static::normalizeString($this->data['guardian_number'] ?? null);
        $email = static::normalizeString($this->data['guardian_email'] ?? null);
        $phone = static::normalizeString($this->data['guardian_phone'] ?? null);

        $guardian = null;

        if ($legacyReference !== null) {
            $guardian = Guardian::withTrashed()
                ->where('legacy_reference', $legacyReference)
                ->first();
        }

        if (! $guardian && $guardianNumber !== null) {
            $guardian = Guardian::withTrashed()
                ->where('guardian_number', $guardianNumber)
                ->first();
        }

        if (! $guardian && $email !== null) {
            $guardian = Guardian::withTrashed()
                ->where('email', $email)
                ->first();
        }

        if (! $guardian && $phone !== null) {
            $guardian = Guardian::withTrashed()
                ->where('phone', $phone)
                ->first();
        }

        if ($guardian instanceof Guardian) {
            if ($guardian->trashed()) {
                $guardian->restore();
            }

            return $guardian;
        }

        if (blank($this->data['guardian_full_name'] ?? null)) {
            return null;
        }

        return Guardian::create([
            'guardian_number' => $guardianNumber ?? $this->generateGuardianNumber(),
            'full_name' => $this->data['guardian_full_name'],
            'relationship' => $this->data['guardian_relationship'] ?? 'parent',
            'phone' => $phone,
            'email' => $email,
            'occupation' => $this->data['guardian_occupation'] ?? null,
            'address' => $this->data['guardian_address'] ?? null,
            'legacy_reference' => $legacyReference,
        ]);
    }

    private function resolveAcademicYear(): ?AcademicYear
    {
        $code = static::normalizeString($this->data['academic_year_code'] ?? null);

        if ($code === null) {
            return null;
        }

        return AcademicYear::query()
            ->where('code', $code)
            ->first();
    }

    private function resolveClassroom(): ?Classroom
    {
        $code = static::normalizeString($this->data['classroom_code'] ?? null);

        if ($code === null) {
            return null;
        }

        return Classroom::withTrashed()
            ->where('code', $code)
            ->first();
    }

    private function generateGuardianNumber(): string
    {
        return Guardian::generateGuardianNumber();
    }

    private function generateStudentNumber(): string
    {
        return Student::generateStudentNumber();
    }

    private static function normalizeStatus(?string $state): ?string
    {
        if (blank($state)) {
            return null;
        }

        $normalized = str($state)->lower()->replace([' ', '-'], '_')->value();

        $status = StudentStatus::tryFrom($normalized);

        return $status?->value ?? $state;
    }

    private static function normalizeString(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        return $value !== '' ? $value : null;
    }

    private static function normalizeDate(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        try {
            return Carbon::parse($value)->toDateString();
        } catch (Throwable) {
            return $value;
        }
    }
}
