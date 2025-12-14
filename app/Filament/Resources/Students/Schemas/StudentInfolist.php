<?php

declare(strict_types=1);

namespace App\Filament\Resources\Students\Schemas;

use App\Enums\GradeLevel;
use App\Enums\ScholarshipType;
use App\Enums\StudentStatus;
use App\Models\Student;
use App\Models\Scholarship;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

class StudentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::getProfileSection(),
                self::getGuardianSection(),
                self::getScholarshipsSection(),
                self::getRegistrationDocumentsSection(),
                self::getDocumentsSection(),
                self::getMetaSection(),
            ]);
    }

    private static function getProfileSection(): Section
    {
        return Section::make(__('filament.students.sections.profile'))
            ->schema([
                ImageEntry::make('photo_url')
                    ->label(__('filament.students.fields.photo'))
                    ->circular()
                    ->columnSpan(1),
                TextEntry::make('full_name')
                    ->label(__('filament.students.fields.full_name'))
                    ->weight('medium'),
                TextEntry::make('student_number')
                    ->label(__('filament.students.fields.student_number'))
                    ->copyable(),
                TextEntry::make('status')
                    ->label(__('filament.students.fields.status'))
                    ->badge()
                    ->formatStateUsing(fn (StudentStatus|string|null $state): ?string => self::formatStatus($state))
                    ->color(fn (StudentStatus|string|null $state): ?string => self::formatStatusColor($state)),
                TextEntry::make('currentGradeLevel')
                    ->label(__('filament.classrooms.table.grade_level'))
                    ->badge()
                    ->formatStateUsing(fn (?GradeLevel $state): ?string => $state?->label()),
                TextEntry::make('academicYear.name')
                    ->label(__('filament.students.fields.academic_year'))
                    ->placeholder('—'),
                TextEntry::make('classroom.name')
                    ->label(__('filament.students.fields.classroom'))
                    ->placeholder('—'),
                TextEntry::make('date_of_birth')
                    ->label(__('filament.students.fields.date_of_birth'))
                    ->date()
                    ->placeholder('—'),
                TextEntry::make('gender')
                    ->label(__('filament.students.fields.gender'))
                    ->formatStateUsing(fn (?string $state): ?string => self::formatGender($state))
                    ->placeholder('—'),
                TextEntry::make('enrolled_on')
                    ->label(__('filament.students.fields.enrolled_on'))
                    ->date()
                    ->placeholder('—'),
                TextEntry::make('address')
                    ->label(__('filament.students.fields.address'))
                    ->columnSpanFull()
                    ->placeholder('—'),
                TextEntry::make('province.name')
                    ->label(__('filament.students.fields.province'))
                    ->placeholder('—'),
                TextEntry::make('regency.name')
                    ->label(__('filament.students.fields.regency'))
                    ->placeholder('—'),
                TextEntry::make('district.name')
                    ->label(__('filament.students.fields.district'))
                    ->placeholder('—'),
                TextEntry::make('village.name')
                    ->label(__('filament.students.fields.village'))
                    ->placeholder('—'),
                TextEntry::make('legacy_reference')
                    ->label(__('filament.students.fields.legacy_reference'))
                    ->placeholder('—')
                    ->copyable()
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }

    private static function getGuardianSection(): Section
    {
        return Section::make(__('filament.students.sections.guardian'))
            ->schema([
                TextEntry::make('guardian.full_name')
                    ->label(__('filament.students.fields.guardian'))
                    ->placeholder('—'),
                TextEntry::make('guardian.relationship')
                    ->label(__('filament.guardians.fields.relationship'))
                    ->placeholder('—'),
                TextEntry::make('guardian.phone')
                    ->label(__('filament.guardians.fields.phone'))
                    ->icon('heroicon-o-phone')
                    ->copyable()
                    ->placeholder('—'),
                TextEntry::make('guardian.email')
                    ->label(__('filament.guardians.fields.email'))
                    ->icon('heroicon-o-envelope')
                    ->copyable()
                    ->placeholder('—'),
            ])
            ->columns(2);
    }

    private static function getRegistrationDocumentsSection(): Section
    {
        return Section::make(__('filament.students.sections.registration_documents'))
            ->schema([
                RepeatableEntry::make('registrationDocuments')
                    ->schema(self::getDocumentEntries())
                    ->columns(2),
            ]);
    }

    private static function getDocumentsSection(): Section
    {
        return Section::make(__('filament.students.sections.student_documents'))
            ->schema([
                RepeatableEntry::make('documents')
                    ->schema(self::getDocumentEntries())
                    ->columns(2),
            ]);
    }

    private static function getScholarshipsSection(): Section
    {
        return Section::make(__('filament.scholarships.sections.student_assignments'))
            ->visible(fn (?Student $record): bool => $record?->scholarships?->isNotEmpty() ?? false)
            ->schema([
                RepeatableEntry::make('scholarships')
                    ->schema([
                        TextEntry::make('name')
                            ->label(__('filament.scholarships.fields.name'))
                            ->badge()
                            ->color('info'),
                        TextEntry::make('type')
                            ->label(__('filament.scholarships.fields.type'))
                            ->badge()
                            ->formatStateUsing(fn ($state): ?string => $state instanceof ScholarshipType ? $state->label() : $state),
                        TextEntry::make('amount')
                            ->label(__('filament.scholarships.fields.amount'))
                            ->formatStateUsing(function ($state, ?Scholarship $record): ?string {
                                if (! $record) {
                                    return null;
                                }

                                return $record->type === ScholarshipType::Percentage
                                    ? number_format((float) $record->amount, 0) . '%'
                                    : 'IDR ' . number_format((float) $record->amount, 0);
                            }),
                        TextEntry::make('pivot.effective_from')
                            ->label(__('filament.scholarships.fields.effective_from'))
                            ->date()
                            ->placeholder('—'),
                        TextEntry::make('pivot.effective_until')
                            ->label(__('filament.scholarships.fields.effective_until'))
                            ->date()
                            ->placeholder('—'),
                        TextEntry::make('pivot.notes')
                            ->label(__('filament.scholarships.fields.notes'))
                            ->columnSpanFull()
                            ->placeholder('—'),
                    ])
                    ->columns(2),
            ]);
    }

    private static function getMetaSection(): Section
    {
        return Section::make(__('filament.students.sections.metadata'))
            ->schema([
                TextEntry::make('created_at')
                    ->label(__('filament.students.table.created_at'))
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->label(__('filament.students.table.updated_at'))
                    ->dateTime(),
                TextEntry::make('deleted_at')
                    ->label(__('filament.students.table.deleted_at'))
                    ->since()
                    ->visible(fn (?Student $record): bool => filled($record?->deleted_at)),
            ])
            ->columns(3);
    }

    /**
     * @return array<int, TextEntry>
     */
    private static function getDocumentEntries(): array
    {
        return [
            TextEntry::make('name')
                ->label(__('filament.students.fields.document_name'))
                ->weight('medium'),
            TextEntry::make('type')
                ->label(__('filament.students.fields.document_type'))
                ->badge()
                ->placeholder('—'),
            TextEntry::make('file_path')
                ->label(__('filament.students.fields.document_file'))
                ->icon('heroicon-o-arrow-up-on-square-stack')
                ->url(fn (?string $state): ?string => filled($state) ? Storage::disk('public')->url($state) : null)
                ->openUrlInNewTab()
                ->copyable()
                ->columnSpanFull(),
            TextEntry::make('notes')
                ->label(__('filament.students.fields.document_notes'))
                ->placeholder('—')
                ->columnSpanFull(),
        ];
    }

    private static function formatStatus(StudentStatus|string|null $state): ?string
    {
        return match (true) {
            $state instanceof StudentStatus => $state->getLabel(),
            blank($state) => null,
            default => StudentStatus::from((string) $state)->getLabel(),
        };
    }

    private static function formatStatusColor(StudentStatus|string|null $state): ?string
    {
        return match (true) {
            $state instanceof StudentStatus => $state->getColor(),
            blank($state) => null,
            default => StudentStatus::from((string) $state)->getColor(),
        };
    }

    private static function formatGender(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        return __('filament.students.genders.' . $value);
    }
}
